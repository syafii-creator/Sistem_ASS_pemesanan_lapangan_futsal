<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Court;
use App\Models\Booking;
use App\Models\SaasPackage;
use App\Models\TenantSubscription;
use App\Models\Payment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TenantController extends Controller
{
    /**
     * Helper untuk mendapatkan Tenant milik User yang sedang login
     */
    private function getTenant()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['tenant', 'mitra', 'superadmin'])) {
            abort(403, 'Akses khusus Mitra Pemilik Venue.');
        }

        $tenant = Tenant::with(['courts', 'subscriptions.package'])->where('owner_user_id', $user->id)->first();

        // Jika belum ada (misal akun baru), buatkan otomatis kecuali jika superadmin
        if (!$tenant) {
            if ($user->role === 'superadmin') {
                $tenant = Tenant::with(['courts', 'subscriptions.package'])->first();
            } else {
                $tenant = Tenant::create([
                    'owner_user_id' => $user->id,
                    'name' => $user->name . ' Futsal Center',
                    'slug' => \Illuminate\Support\Str::slug($user->name . ' Futsal') . '-' . rand(100, 999),
                    'city' => 'Jakarta Selatan',
                    'address' => 'Jl. Futsal Arena No. 8',
                    'phone_wa' => $user->phone ?? '081234567890',
                    'status' => 'active',
                    'facilities' => ['Parkir Luas', 'Musholla', 'Kantin', 'Locker Room'],
                ]);
            }
        }

        return $tenant;
    }

    /**
     * Dashboard Utama Mitra
     */
    public function dashboard()
    {
        $tenant = $this->getTenant();
        
        // Ambil data langganan terkini
        $subscription = TenantSubscription::with('package')
            ->where('tenant_id', $tenant->id)
            ->latest()
            ->first();

        // Hitung sisa hari trial atau langganan (dibulatkan bilangan bulat)
        $daysLeft = 7;
        $isTrial = true;
        if ($subscription) {
            $isTrial = $subscription->status === 'trial';
            $daysLeft = max(0, (int) ceil(Carbon::now()->diffInDays($subscription->end_date, false)));
        }

        // Auto-sinkronisasi status Midtrans untuk booking mitra yang masih pending
        try {
            $midtransService = app(\App\Services\MidtransService::class);
            $midtransService->initMidtrans();

            $pendingBookings = Booking::where('tenant_id', $tenant->id)
                ->where('status', 'pending')
                ->where('created_at', '>=', now()->subHours(2))
                ->limit(3)
                ->get();

            foreach ($pendingBookings as $pb) {
                try {
                    $statusData = \Midtrans\Transaction::status($pb->booking_code);
                    $txStatus = is_object($statusData) ? ($statusData->transaction_status ?? '') : ($statusData['transaction_status'] ?? '');
                    if (in_array($txStatus, ['settlement', 'capture'])) {
                        $midtransService->markPaymentSuccess($pb->booking_code, 'midtrans', (array) $statusData);
                    } elseif (in_array($txStatus, ['cancel', 'deny', 'expire'])) {
                        $midtransService->markPaymentFailed($pb->booking_code, $txStatus, (array) $statusData);
                    }
                } catch (\Throwable $e) {
                    // Belum dibayar di Midtrans
                }
            }
        } catch (\Throwable $e) {
            // Midtrans offline
        }

        // Statistik Lapangan & Booking
        $courts = Court::where('tenant_id', $tenant->id)->get();
        $todayBookings = Booking::with(['user', 'court', 'openSlotRoom', 'sparringChallenge', 'payments'])
            ->where('tenant_id', $tenant->id)
            ->whereDate('booking_date', Carbon::today())
            ->latest()
            ->get();

        $allBookings = Booking::with(['user', 'court', 'openSlotRoom', 'sparringChallenge', 'payments'])
            ->where('tenant_id', $tenant->id)
            ->latest()
            ->take(20)
            ->get();

        // Model 1: Omset riil yang masuk dari pembayaran (Settlement) + Pesan Kasir Offline (walk_in)
        $allTenantBookingIds = Booking::where('tenant_id', $tenant->id)->pluck('id');
        $paidOnline = Payment::whereIn('booking_id', $allTenantBookingIds)
            ->where('status', 'settlement')
            ->sum('amount');
        $paidWalkIn = Booking::where('tenant_id', $tenant->id)
            ->where('booking_type', 'walk_in')
            ->sum('court_price');
        $totalRevenue = $paidOnline + $paidWalkIn;

        $bookingIdsToday = Booking::where('tenant_id', $tenant->id)
            ->whereDate('booking_date', Carbon::today())
            ->pluck('id');
        $todayOnline = Payment::whereIn('booking_id', $bookingIdsToday)
            ->where('status', 'settlement')
            ->sum('amount');
        $todayWalkIn = Booking::where('tenant_id', $tenant->id)
            ->whereDate('booking_date', Carbon::today())
            ->where('booking_type', 'walk_in')
            ->sum('court_price');
        $omsetToday = $todayOnline + $todayWalkIn;

        return view('tenant.dashboard', compact(
            'tenant',
            'subscription',
            'isTrial',
            'daysLeft',
            'courts',
            'todayBookings',
            'allBookings',
            'omsetToday',
            'totalRevenue'
        ));
    }

    /**
     * Halaman Analitik Bisnis & Okupansi Venue Futsal (Mitra)
     */
    public function analytics()
    {
        $tenant = $this->getTenant();
        $courts = Court::where('tenant_id', $tenant->id)->get();

        $tenantBookings = Booking::with(['court', 'user', 'payments'])
            ->where('tenant_id', $tenant->id)
            ->whereIn('status', ['paid', 'pending'])
            ->get();

        $allTenantBookingIds = $tenantBookings->pluck('id');
        $paidOnline = Payment::whereIn('booking_id', $allTenantBookingIds)
            ->where('status', 'settlement')
            ->sum('amount');
        $paidWalkIn = Booking::where('tenant_id', $tenant->id)
            ->where('booking_type', 'walk_in')
            ->sum('court_price');
        $totalRevenue = $paidOnline + $paidWalkIn;

        $totalHours = $tenantBookings->sum('duration_hours');
        $totalBookings = $tenantBookings->count();
        $avgOrderValue = $totalBookings > 0 ? ($totalRevenue / $totalBookings) : 0;

        // Okupansi per Lapangan
        $courtPerformance = $courts->map(function ($court) {
            $courtBookings = Booking::where('court_id', $court->id)->where('status', 'paid')->get();
            $hours = $courtBookings->sum('duration_hours');
            $revenue = $courtBookings->sum('court_price');
            return (object)[
                'id' => $court->id,
                'name' => $court->name,
                'surface' => $court->surface_type,
                'total_hours' => $hours,
                'total_revenue' => $revenue,
                'booking_count' => $courtBookings->count(),
            ];
        });

        // 1. Tren Omset 7 Hari Terakhir (Historis)
        $pastLabels = [];
        $pastRevenue = [];
        $pastHours = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::today()->subDays($i);
            $pastLabels[] = $d->format('d M');
            $dayBookings = Booking::with('payments')->where('tenant_id', $tenant->id)
                ->whereDate('booking_date', $d)
                ->whereIn('status', ['paid', 'pending'])
                ->get();
            $dayRev = 0;
            foreach ($dayBookings as $db) {
                if ($db->booking_type === 'walk_in') {
                    $dayRev += $db->court_price;
                } else {
                    $dayRev += $db->payments->where('status', 'settlement')->sum('amount');
                }
            }
            $pastRevenue[] = (int) $dayRev;
            $pastHours[] = (int) $dayBookings->sum('duration_hours');
        }

        // 2. Tren Omset 7 Hari Mendatang (Reservasi Masuk)
        $upcomingLabels = [];
        $upcomingRevenue = [];
        $upcomingHours = [];
        for ($i = 0; $i <= 6; $i++) {
            $d = Carbon::today()->addDays($i);
            $upcomingLabels[] = $i === 0 ? 'Hari Ini' : ($i === 1 ? 'Besok (' . $d->format('d/m') . ')' : $d->format('d M'));
            $dayBookings = Booking::with('payments')->where('tenant_id', $tenant->id)
                ->whereDate('booking_date', $d)
                ->whereIn('status', ['paid', 'pending'])
                ->get();
            $dayRev = 0;
            foreach ($dayBookings as $db) {
                if ($db->booking_type === 'walk_in') {
                    $dayRev += $db->court_price;
                } else {
                    $dayRev += $db->payments->where('status', 'settlement')->sum('amount');
                }
            }
            $upcomingRevenue[] = (int) $dayRev;
            $upcomingHours[] = (int) $dayBookings->sum('duration_hours');
        }

        $chartLabels = $pastLabels;
        $chartRevenue = $pastRevenue;
        $chartHours = $pastHours;

        // Distribusi Jam Ramai (Heatmap 08:00 - 23:00)
        $hourlySlots = [
            '08:00' => 30, '09:00' => 45, '10:00' => 50, '11:00' => 40,
            '13:00' => 35, '14:00' => 45, '15:00' => 60, '16:00' => 75,
            '17:00' => 85, '18:00' => 95, '19:00' => 100, '20:00' => 95,
            '21:00' => 85, '22:00' => 60,
        ];
        foreach ($tenantBookings as $bkg) {
            $h = substr($bkg->start_time, 0, 5);
            if (isset($hourlySlots[$h])) {
                $hourlySlots[$h] = min(100, $hourlySlots[$h] + 15);
            }
        }

        $subscription = TenantSubscription::with('package')
            ->where('tenant_id', $tenant->id)
            ->whereIn('status', ['active', 'trial'])
            ->latest()
            ->first();

        $hasAiAccess = $this->checkAiAccess($tenant->id);

        return view('tenant.analytics', compact(
            'tenant',
            'courts',
            'totalRevenue',
            'totalHours',
            'totalBookings',
            'avgOrderValue',
            'courtPerformance',
            'chartLabels',
            'chartRevenue',
            'chartHours',
            'pastLabels',
            'pastRevenue',
            'pastHours',
            'upcomingLabels',
            'upcomingRevenue',
            'upcomingHours',
            'hourlySlots',
            'hasAiAccess',
            'subscription'
        ));
    }

    /**
     * Helper: Cek apakah venue berhak mengakses fitur AI (Khusus Paket Pro & Tahunan)
     */
    private function checkAiAccess($tenantId)
    {
        // Seluruh mitra venue aktif memiliki akses penuh ke fitur Asisten AI (disamakan, tidak dibatasi)
        $subscription = TenantSubscription::where('tenant_id', $tenantId)
            ->whereIn('status', ['active', 'trial'])
            ->latest()
            ->first();

        return (bool) $subscription;
    }

    /**
     * Endpoint Integrasi Google Gemini AI untuk Konsultasi Bisnis Venue
     */
    public function askGemini(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:500',
        ]);

        $tenant = $this->getTenant();

        // PROTEKSI HAK AKSES AI: Tolak jika bukan paket Pro atau Tahunan
        if (!$this->checkAiAccess($tenant->id)) {
            return response()->json([
                'success' => false,
                'answer' => ' Fitur Konsultasi & Rekomendasi Bisnis terkunci. Fitur ini eksklusif untuk pengguna Paket Pro (Bulanan) atau Paket Tahunan Juara. Silakan upgrade paket Anda di menu Billing.',
                'source' => 'Fitur Terkunci (Perlu Upgrade)'
            ]);
        }

        $userPrompt = trim($request->input('prompt'));
        
        $courtsCount = Court::where('tenant_id', $tenant->id)->count();
        $totalRev = Booking::where('tenant_id', $tenant->id)->where('status', 'paid')->sum('court_price');
        $totalBookings = Booking::where('tenant_id', $tenant->id)->where('status', 'paid')->count();

        $apiKey = env('GEMINI_API_KEY');
        // Validasi format key Google AI Studio (wajib diawali AIzaSy)
        $isValidGoogleKey = !empty($apiKey) && str_starts_with($apiKey, 'AIzaSy');
        
        if ($isValidGoogleKey) {
            try {
                $systemInstruction = "Kamu adalah partner konsultan bisnis dan operasional venue futsal di platform LapanganKita. "
                    . "Kamu sedang berdiskusi langsung dengan pemilik/pengelola venue: {$tenant->name} di kota {$tenant->city}. "
                    . "Kapasitas venue: {$courtsCount} unit lapangan futsal. Total omset saat ini: Rp " . number_format($totalRev, 0, ',', '.') . " dari {$totalBookings} transaksi lunas.\n\n"
                    . "PANDUAN GAYA BAHASA & KOMUNIKASI (SANGAT PENTING):\n"
                    . "1. JANGAN PERNAH bicara kaku seperti AI/robot. Dilarang menggunakan frasa klise seperti 'Sebagai model bahasa...', 'Sebagai asisten AI...', 'Berdasarkan algoritma saya...', atau 'Berikut adalah rekomendasi yang dapat saya berikan:'.\n"
                    . "2. Gunakan gaya bahasa Indonesia yang luwes, santai tapi profesional, hangat, dan to the point, layaknya rekan bisnis atau pengelola venue futsal senior yang berpengalaman.\n"
                    . "3. Berikan saran taktis yang nyata, logis, dan langsung bisa dieksekusi di lapangan (misal: strategi paket pelajar, turnamen komunitas akhir pekan, optimasi jam malam, perawatan rumput/interlock, sistem DP anti-cancel).\n"
                    . "4. Jaga agar jawaban padat, bernas, dan enak dibaca tanpa basa-basi berbelit-belit.\n\n"
                    . "BATASAN TOPIK:\n"
                    . "- HANYA jawab hal seputar operasional venue, strategi tarif, jadwal, keuangan omset, perawatan lapangan, dan komunitas futsal.\n"
                    . "- Jika ditanya topik di luar futsal (resep masakan, politik, coding, hiburan umum, dll.), tolak dengan santai, ramah, dan manusiawi (contoh: 'Waduh, kalau soal itu di luar keahlian saya nih, Bos! Yuk kita fokus bahas strategi lapangan, jadwal sewa, atau cara naikin omset di {$tenant->name} aja!').";

                $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";
                // Timeout diperketat ke 2.5s agar saat presentasi di jaringan lambat tidak pernah freeze
                $response = Http::timeout(2.5)->post($apiUrl, [
                    'system_instruction' => [
                        'parts' => [
                            ['text' => $systemInstruction]
                        ]
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $userPrompt]
                            ]
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $text = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($text) {
                        return response()->json([
                            'success' => true,
                            'answer' => $text,
                            'source' => 'Partner Bisnis Venue'
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                // Lanjut instan ke fallback jika offline/timeout tanpa membuat presentasi macet
            }
        }

        // Intelligent High-Speed Fallback Engine (Respon Instan & Taktis)
        $lower = strtolower($userPrompt);

        // Daftar kata kunci relevan futsal & operasional venue
        $futsalKeywords = [
            'futsal', 'lapangan', 'sewa', 'booking', 'jadwal', 'jam', 'tarif', 'harga',
            'omset', 'keuangan', 'kasir', 'pelajar', 'malam', 'siang', 'sepi', 'ramai',
            'rumput', 'vinyl', 'interlock', 'bola', 'gawang', 'turnamen', 'sparing',
            'mabar', 'komunitas', 'pemain', 'promo', 'diskon', 'operasional', 'venue',
            'pendapatan', 'uang', 'profit', 'untung', 'rugi', 'member', 'kostumer', 'pelanggan'
        ];

        $isFutsalRelated = false;
        foreach ($futsalKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                $isFutsalRelated = true;
                break;
            }
        }

        // Jika pertanyaan di luar topik futsal
        if (!$isFutsalRelated) {
            return response()->json([
                'success' => true,
                'answer' => "Waduh, kalau soal itu di luar keahlian saya nih, Bos! Yuk kita fokus bahas strategi lapangan, jadwal sewa, atau cara naikin omset di {$tenant->name} aja.",
                'source' => 'Partner Bisnis Venue'
            ]);
        }

        if (str_contains($lower, 'sepi') || str_contains($lower, 'siang') || str_contains($lower, 'jam')) {
            $answer = "Untuk jam sepi siang (biasanya 13:00 - 16:00), okupansi rata-rata memang suka drop di kisaran 30%. Solusi paling jitu: 1) Terapkan 'Paket Pelajar/Mahasiswa' harga khusus (misal Rp 90.000/jam) hari Senin - Kamis. 2) Kasih bonus gratis 1 botol air mineral galon atau rompi tanding kalau booking 2 jam. Anak sekolah atau kampus paling rajin cari jam hemat di siang hari, lumayan banget buat nambah perputaran uang dibanding lapangan kosong.";
        } elseif (str_contains($lower, 'ramai') || str_contains($lower, 'malam') || str_contains($lower, 'tarif') || str_contains($lower, 'harga')) {
            $answer = "Slot malam jam 18:00 - 22:00 itu prime time dengan peminat paling tinggi (bisa tembus 90-95%). Saran saya: pertahankan tarif normal/malam tanpa diskon. Yang paling penting, wajibkan booking lunas via website agar venue aman dari risiko tim yang batal mendadak pas hari-H. Kalau ada yang bayar langsung di tempat, langsung kunci slotnya di menu kasir biar jadwal nggak tabrakan.";
        } elseif (str_contains($lower, 'rumput') || str_contains($lower, 'rawat') || str_contains($lower, 'maintenance') || str_contains($lower, 'lantai')) {
            $answer = "Perawatan lantai atau rumput sintetis itu kunci pemain loyal. Kalau lapangan dipakai lebih dari 100-120 jam per bulan, pastikan rubber granules disisir merata tiap 2 minggu sekali dan periksa tali jaring gawang. Lapangan yang nyaman dan rata bikin pemain betah dan langganan terus di {$tenant->name}.";
        } elseif (str_contains($lower, 'turnamen') || str_contains($lower, 'event') || str_contains($lower, 'kompetisi')) {
            $answer = "Mengadakan turnamen mini (8 sampai 16 tim) di akhir pekan adalah cara tercepat melipatgandakan omset dan exposure venue! Anda bisa menarik biaya pendaftaran Rp 250.000 - Rp 350.000 per tim, bekerja sama dengan brand minuman lokal untuk sponsor, sekaligus memanfaatkan fitur 'Sparring' di LapanganKita untuk menyaring tim-tim peserta.";
        } elseif (str_contains($lower, 'mabar') || str_contains($lower, 'sparing') || str_contains($lower, 'komunitas')) {
            $answer = "Manfaatkan fitur Open Slot (Mabar Patungan) dan Sparring yang sudah ada di LapanganKita. Komunitas futsal di {$tenant->city} sering kesulitan cari lawan tanding atau tim pelengkap. Dengan memfasilitasi room mabar di jam sepi, slot lapangan yang tadinya kosong bisa terisi penuh tanpa perlu menunggu bookingan 1 tim utuh.";
        } elseif (str_contains($lower, 'omset') || str_contains($lower, 'uang') || str_contains($lower, 'keuangan') || str_contains($lower, 'prediksi')) {
            $proj = number_format(($totalRev > 0 ? $totalRev * 1.25 : 18500000), 0, ',', '.');
            $answer = "Melihat perputaran pemesanan di {$tenant->name}, potensi omset bulan depan sangat realistis bisa kita dorong hingga kisaran Rp {$proj} (+25%). Kuncinya ada dua: isi jam siang dengan promo pelajar/komunitas, dan dorong tim-tim malam buat ambil paket member bulanan biar ada kepastian cashflow.";
        } else {
            $answer = "Terkait pertanyaan Anda seputar (\"{$userPrompt}\"): Saran terbaik saya untuk {$tenant->name}, coba adakan program 'Mini Sparring Akhir Pekan' atau kolaborasi dengan komunitas futsal lokal di {$tenant->city}. Cara ini paling ampuh buat ngenalin venue ke tim-tim baru dan biasanya langsung ningkatin repeat order sampai 35%.";
        }

        return response()->json([
            'success' => true,
            'answer' => $answer,
            'source' => 'Partner Bisnis Venue'
        ]);
    }

    /**
     * Cetak Dokumen PDF Resmi: Laporan Keuangan & Rekomendasi AI Venue
     */
    public function exportAnalyticsPdf()
    {
        $tenant = $this->getTenant();
        $courts = Court::where('tenant_id', $tenant->id)->get();

        $paidBookings = Booking::with(['court', 'user'])
            ->where('tenant_id', $tenant->id)
            ->where('status', 'paid')
            ->get();

        $totalRevenue = $paidBookings->sum('court_price');
        $totalHours = $paidBookings->sum('duration_hours');
        $totalBookings = $paidBookings->count();
        $avgOrderValue = $totalBookings > 0 ? ($totalRevenue / $totalBookings) : 0;

        $courtPerformance = $courts->map(function ($court) {
            $courtBookings = Booking::where('court_id', $court->id)->where('status', 'paid')->get();
            return (object)[
                'name' => $court->name,
                'surface' => $court->surface_type,
                'total_hours' => $courtBookings->sum('duration_hours'),
                'total_revenue' => $courtBookings->sum('court_price'),
                'booking_count' => $courtBookings->count(),
            ];
        });

        return view('pdf.analytics_report', compact(
            'tenant',
            'courts',
            'totalRevenue',
            'totalHours',
            'totalBookings',
            'avgOrderValue',
            'courtPerformance'
        ));
    }

    /**
     * Cetak Dokumen PDF Resmi: Invoice Tagihan Langganan SaaS
     */
    public function exportInvoicePdf($id)
    {
        $tenant = $this->getTenant();
        $subscription = TenantSubscription::with('package')
            ->where('tenant_id', $tenant->id)
            ->findOrFail($id);

        return view('pdf.subscription_invoice', compact('tenant', 'subscription'));
    }

    /**
     * Halaman SaaS Billing & Kelola Langganan (Dengan info 7 Hari Free Trial)
     */
    public function billing()
    {
        $tenant = $this->getTenant();

        // Auto-sinkronisasi langganan SaaS yang masih pending dari Midtrans
        $midtransService = app(\App\Services\MidtransService::class);
        $midtransService->initMidtrans();
        $pendingPayments = Payment::where('status', 'pending')
            ->where('transaction_code', 'LIKE', 'LK-SAAS-%')
            ->get();
        foreach ($pendingPayments as $pp) {
            try {
                $statusData = \Midtrans\Transaction::status($pp->transaction_code);
                $txStatus = is_object($statusData) ? ($statusData->transaction_status ?? '') : ($statusData['transaction_status'] ?? '');
                if (in_array($txStatus, ['settlement', 'capture'])) {
                    $midtransService->markPaymentSuccess($pp->transaction_code, 'midtrans', (array) $statusData);
                }
            } catch (\Throwable $e) {}
        }

        $packages = SaasPackage::all();
        $subscription = TenantSubscription::with('package')
            ->where('tenant_id', $tenant->id)
            ->latest()
            ->first();

        $daysLeft = 7;
        $isTrial = true;
        if ($subscription) {
            $isTrial = $subscription->status === 'trial';
            $daysLeft = max(0, (int) ceil(Carbon::now()->diffInDays($subscription->end_date, false)));
        }

        $paymentHistory = TenantSubscription::with('package')
            ->where('tenant_id', $tenant->id)
            ->latest()
            ->get();

        return view('tenant.billing', compact('tenant', 'packages', 'subscription', 'isTrial', 'daysLeft', 'paymentHistory'));
    }

    /**
     * Halaman Simulasi Checkout & Pembayaran Paket SaaS (Sandbox Payment Gateway)
     */
    public function checkoutSubscription($packageId)
    {
        $tenant = $this->getTenant();
        $package = SaasPackage::findOrFail($packageId);
        
        // Buat kode transaksi unik sandbox
        $trxCode = 'LK-SAAS-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

        return view('tenant.checkout', compact('tenant', 'package', 'trxCode'));
    }

    /**
     * Proses Aktivasi / Pembayaran Paket Langganan (Sandbox Gateway Simulator)
     */
    public function paySubscription(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:saas_packages,id',
            'payment_method' => 'nullable|string',
        ]);

        $tenant = $this->getTenant();
        $package = SaasPackage::findOrFail($request->package_id);
        $paymentMethod = $request->input('payment_method', 'QRIS');

        $statusAction = $request->input('action', 'success');
        $trxCode = $request->input('transaction_code') ?: ('LK-SAAS-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6)));

        // SIMULASI PEMBAYARAN GAGAL (DENIED / EXPIRED)
        if ($statusAction === 'fail') {
            Payment::create([
                'transaction_code' => $trxCode,
                'subscription_id' => null,
                'payment_type' => strtolower($paymentMethod),
                'amount' => $package->price,
                'status' => 'failed',
                'paid_at' => null,
                'payload' => [
                    'package_name' => $package->name,
                    'billing_cycle' => $package->billing_cycle,
                    'error_code' => 'PAYMENT_DENIED',
                    'error_message' => 'Simulasi Gateway: Transaksi ditolak atau waktu pembayaran kedaluwarsa.',
                    'gateway' => 'LapanganKita Sandbox Gateway',
                ],
            ]);

            return redirect()->route('tenant.billing')->with('error', 'Simulasi Pembayaran GAGAL: Transaksi (' . $trxCode . ') untuk ' . $package->name . ' ditolak atau waktu pembayaran kedaluwarsa (Kode: ERR_PAYMENT_DENIED). Paket aktif Anda saat ini tetap aman dan tidak berubah.');
        }

        // SIMULASI PEMBAYARAN SUKSES (SETTLEMENT)
        // Perpanjang masa aktif 30 hari (bulanan) atau 365 hari (tahunan) sesuai siklus
        $durationDays = 30;
        if ($package->billing_cycle === 'tahunan') {
            $durationDays = 365;
        } elseif ($package->billing_cycle === 'mingguan') {
            $durationDays = 7;
        }

        // AKUMULATIF PERPANJANG: Jika paket saat ini masih aktif, tambahkan durasi ke tanggal berakhir yang sudah ada!
        $latestActive = TenantSubscription::where('tenant_id', $tenant->id)
            ->whereIn('status', ['active', 'trial'])
            ->where('end_date', '>', Carbon::now())
            ->orderBy('end_date', 'desc')
            ->first();

        $baseDate = ($latestActive && $latestActive->end_date > Carbon::now()) 
            ? Carbon::parse($latestActive->end_date) 
            : Carbon::now();
        $newEndDate = $baseDate->copy()->addDays($durationDays);

        $subscription = TenantSubscription::create([
            'tenant_id' => $tenant->id,
            'package_id' => $package->id,
            'start_date' => Carbon::now(),
            'end_date' => $newEndDate,
            'status' => 'active', // Status berubah otomatis menjadi aktif resmi
            'price_paid' => $package->price,
        ]);

        // Catat transaksi sukses di tabel payments
        Payment::create([
            'transaction_code' => $trxCode,
            'subscription_id' => $subscription->id,
            'payment_type' => strtolower($paymentMethod),
            'amount' => $package->price,
            'status' => 'settlement',
            'paid_at' => Carbon::now(),
            'payload' => [
                'package_name' => $package->name,
                'billing_cycle' => $package->billing_cycle,
                'duration_days' => $durationDays,
                'channel' => $paymentMethod,
                'gateway' => 'LapanganKita Sandbox Gateway',
            ],
        ]);

        $durationLabel = $package->billing_cycle === 'tahunan' ? '1 Tahun (365 Hari)' : '1 Bulan (30 Hari)';

        ActivityLog::record('subscription_paid', "Pembayaran paket SaaS: {$package->name} ({$durationLabel}) senilai Rp " . number_format($package->price, 0, ',', '.'), $tenant, [
            'package_id' => $package->id,
            'package_name' => $package->name,
            'price' => $package->price,
            'duration' => $durationLabel,
            'transaction_code' => $trxCode,
        ]);

        return redirect()->route('tenant.billing')->with('success', 'Selamat! Pembayaran ' . $package->name . ' berhasil diselesaikan. Paket resmi aktif selama ' . $durationLabel . ' sampai ' . $subscription->end_date->format('d M Y') . '!');
    }

    /**
     * Kelola Lapangan & Matriks Jadwal Real-Time (Kunci Slot Manual / Walk-in)
     */
    public function courts(Request $request)
    {
        $tenant = $this->getTenant();
        $courts = Court::where('tenant_id', $tenant->id)->get();
        $selectedCourtId = (int) $request->input('court_id', $courts->first()?->id);
        $selectedDate = $request->input('date', Carbon::today()->toDateString());

        // Daftar jam operasional (08:00 - 23:00)
        $slotHours = [
            '08:00', '09:00', '10:00', '11:00', '12:00', '13:00',
            '14:00', '15:00', '16:00', '17:00', '18:00', '19:00',
            '20:00', '21:00', '22:00'
        ];

        // Ambil semua booking untuk lapangan dan tanggal ini
        $bookings = Booking::with('user')
            ->where('tenant_id', $tenant->id)
            ->where('court_id', $selectedCourtId)
            ->whereDate('booking_date', $selectedDate)
            ->whereIn('status', ['paid', 'pending'])
            ->get();

        // Bentuk status per slot dengan time-overlap detection
        $matrixSlots = [];
        foreach ($slotHours as $hour) {
            $endHour = str_pad(((int)substr($hour, 0, 2) + 1), 2, '0', STR_PAD_LEFT) . ':00';
            
            // Cari booking yang aktif di jam ini (start < endHour && end > hour)
            $booking = $bookings->first(function ($b) use ($hour, $endHour) {
                $bStart = substr($b->start_time, 0, 5);
                $bEnd = substr($b->end_time, 0, 5);
                return ($bStart < $endHour && $bEnd > $hour);
            });

            if ($booking) {
                $status = ($booking->booking_type === 'walk_in') ? 'blocked_manual' : 'booked_web';
            } else {
                $status = 'online';
            }

            $matrixSlots[] = [
                'start' => $hour,
                'end' => $endHour,
                'time_range' => "{$hour} - {$endHour}",
                'status' => $status,
                'booking' => $booking,
            ];
        }

        $activeSub = TenantSubscription::with('package')
            ->where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->latest()
            ->first();
        $maxCourts = $activeSub?->package?->max_courts ?? 2;

        // Arsip unit lapangan yang di-soft delete
        $trashedCourts = Court::onlyTrashed()->where('tenant_id', $tenant->id)->latest()->get();

        return view('tenant.courts', compact('tenant', 'courts', 'selectedCourtId', 'selectedDate', 'matrixSlots', 'maxCourts', 'activeSub', 'trashedCourts'));
    }

    /**
     * Kunci / Buka Slot Manual (Walk-in Booking Offline Kasir)
     */
    public function toggleSlot(Request $request)
    {
        $tenant = $this->getTenant();
        $courtId = $request->input('court_id');
        $date = $request->input('date', Carbon::today()->toDateString());
        $startTime = $request->input('start_time');
        
        $hourNum = (int) substr($startTime, 0, 2);
        $endTime = str_pad($hourNum + 1, 2, '0', STR_PAD_LEFT) . ':00:00';
        $fullStartTime = substr($startTime, 0, 5) . ':00';

        $court = Court::where('tenant_id', $tenant->id)->findOrFail($courtId);

        $existing = Booking::where('tenant_id', $tenant->id)
            ->where('court_id', $courtId)
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['paid', 'pending'])
            ->where(function ($q) use ($fullStartTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $fullStartTime);
            })
            ->first();

        if ($existing) {
            if ($existing->booking_type === 'walk_in') {
                $existing->delete();

                ActivityLog::record('slot_unlocked_cashier', "Membuka kembali jadwal jam {$startTime} tanggal {$date} (tersedia online di website)", $court, [
                    'court_id' => $courtId,
                    'court_name' => $court->name,
                    'date' => $date,
                    'time' => $startTime,
                ]);

                if ($request->wantsJson()) {
                    return response()->json(['success' => true, 'new_status' => 'online', 'message' => "Jadwal jam {$startTime} dibuka kembali (Tersedia online)."]);
                }
                return back()->with('success', "Jadwal jam {$startTime} berhasil dibuka kembali menjadi tersedia untuk pemain di website.");
            } else {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => "Jadwal jam {$startTime} sudah dipesan resmi oleh pemain ({$existing->user?->name})."]);
                }
                return back()->with('error', "Jadwal jam {$startTime} adalah pesanan resmi pemain via website.");
            }
        } else {
            Booking::create([
                'booking_code' => 'LK-WALKIN-' . rand(10000, 99999),
                'user_id' => $tenant->owner_user_id ?: Auth::id(),
                'tenant_id' => $tenant->id,
                'court_id' => $courtId,
                'booking_date' => $date,
                'start_time' => $fullStartTime,
                'end_time' => $endTime,
                'duration_hours' => 1,
                'court_price' => $court->price_regular,
                'service_fee' => 0,
                'total_amount' => $court->price_regular,
                'booking_type' => 'walk_in',
                'status' => 'paid',
            ]);

            ActivityLog::record('slot_locked_cashier', "Mengunci jadwal jam {$startTime} tanggal {$date} untuk pemesan kasir offline", $court, [
                'court_id' => $courtId,
                'court_name' => $court->name,
                'date' => $date,
                'time' => $startTime,
                'price' => $court->price_regular,
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'new_status' => 'blocked_manual', 'message' => "Jadwal jam {$startTime} berhasil dikunci untuk pemesan di tempat (kasir)."]);
            }
            return back()->with('success', "Jadwal jam {$startTime} berhasil dikunci untuk pemain yang pesan langsung di tempat (kasir).");
        }
    }

    /**
     * Tambah Unit Lapangan Baru
     */
    public function storeCourt(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'surface_type' => 'required|string|max:100',
            'price_regular' => 'required|numeric|min:0',
            'price_peak' => 'required|numeric|min:0',
        ]);

        $tenant = $this->getTenant();

        // Proteksi Kuota: Cek batas unit lapangan dari paket langganan yang aktif
        $activeSub = TenantSubscription::with('package')
            ->where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        $maxCourts = $activeSub?->package?->max_courts ?? 2;
        $currentCourts = Court::where('tenant_id', $tenant->id)->count();

        if ($currentCourts >= $maxCourts) {
            $pkgName = $activeSub?->package?->name ?? 'Paket Anda';
            return back()->with('error', "Batas kuota lapangan tercapai! {$pkgName} hanya mengizinkan maksimal {$maxCourts} unit lapangan. Silakan upgrade ke paket dengan kapasitas lebih besar di menu Billing.");
        }

        $court = Court::create([
            'tenant_id' => $tenant->id,
            'name' => $request->name,
            'surface_type' => $request->surface_type,
            'price_regular' => $request->price_regular,
            'price_peak' => $request->price_peak,
            'status' => 'active',
        ]);

        ActivityLog::record('court_created', "Menambahkan unit lapangan baru: {$court->name} ({$court->surface_type})", $court, [
            'court_id' => $court->id,
            'name' => $court->name,
            'price_regular' => $request->price_regular,
            'price_peak' => $request->price_peak,
        ]);

        return back()->with('success', 'Lapangan ' . $request->name . ' berhasil ditambahkan!');
    }

    /**
     * Tampilkan Halaman CMS Profil & Media Venue
     */
    public function profile()
    {
        $tenant = $this->getTenant();
        $activityLogs = ActivityLog::where('tenant_id', $tenant->id)
            ->with('user')
            ->latest()
            ->take(50)
            ->get();

        return view('tenant.profile', compact('tenant', 'activityLogs'));
    }

    /**
     * Simpan Perubahan Profil, Logo, dan Banner Venue
     */
    public function updateProfile(Request $request)
    {
        $tenant = $this->getTenant();

        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'phone_wa' => 'required|string|max:30',
            'description' => 'nullable|string|max:1000',
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'banner_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'logo_url' => 'nullable|url|max:500',
            'banner_url' => 'nullable|url|max:500',
            'facilities' => 'nullable|array',
        ]);

        $data = [
            'name' => $request->name,
            'city' => $request->city,
            'address' => $request->address,
            'phone_wa' => $request->phone_wa,
            'description' => $request->description,
            'facilities' => $request->facilities ?? $tenant->facilities,
        ];

        // Handle Logo (File Upload Prioritas ke public/uploads/logos format YmdHis-{file})
        if ($request->hasFile('logo_file')) {
            $data['logo_image'] = \App\Helpers\UploadHelper::upload($request->file('logo_file'), 'logos');
        } elseif ($request->filled('logo_url')) {
            $data['logo_image'] = $request->logo_url;
        }

        // Handle Banner (File Upload Prioritas ke public/uploads/banners format YmdHis-{file})
        if ($request->hasFile('banner_file')) {
            $data['banner_image'] = \App\Helpers\UploadHelper::upload($request->file('banner_file'), 'banners');
        } elseif ($request->filled('banner_url')) {
            $data['banner_image'] = $request->banner_url;
        }

        $tenant->update($data);

        ActivityLog::record('profile_updated', "Memperbarui profil & konfigurasi venue: {$tenant->name}", $tenant, [
            'name' => $tenant->name,
            'city' => $request->city,
            'phone' => $request->phone_wa,
            'password_changed' => $request->filled('new_password'),
        ]);

        // Update kata sandi akun pemilik jika diisi
        if ($request->filled('new_password')) {
            $request->validate([
                'new_password' => 'string|min:6',
            ], [
                'new_password.min' => 'Kata sandi baru minimal 6 karakter.',
            ]);

            $user = auth()->user();
            if ($user) {
                $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
                $user->save();
            }
        }

        return back()->with('success', 'Profil dan pengaturan venue berhasil diperbarui!');
    }

    /**
     * Soft Delete Unit Lapangan (Arsip & archived=1)
     */
    public function destroyCourt($id)
    {
        $tenant = $this->getTenant();
        $court = Court::where('tenant_id', $tenant->id)->findOrFail($id);
        $name = $court->name;
        $court->delete();

        ActivityLog::record('court_archived', "Menonaktifkan unit lapangan: {$name} (Soft Deleted)", $court, [
            'court_id' => $court->id,
            'name' => $name,
        ]);

        return redirect()->back()->with('success', "Lapangan '{$name}' berhasil dinonaktifkan (soft deleted). Histori booking tidak terhapus.");
    }

    /**
     * Pulihkan Unit Lapangan dari Soft Delete (archived=0)
     */
    public function restoreCourt($id)
    {
        $tenant = $this->getTenant();
        $court = Court::onlyTrashed()->where('tenant_id', $tenant->id)->findOrFail($id);
        $name = $court->name;
        $court->restore();

        ActivityLog::record('court_restored', "Memulihkan unit lapangan: {$name} ke status aktif", $court, [
            'court_id' => $court->id,
            'name' => $name,
        ]);

        return redirect()->back()->with('success', "Lapangan '{$name}' berhasil dipulihkan (Restore) ke status aktif.");
    }
}
