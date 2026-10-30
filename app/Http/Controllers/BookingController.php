<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Tenant;
use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Halaman Pembayaran Simulator / Checkout (Dinamis sesuai slot lapangan yang dipilih pemain)
     */
    public function payment(Request $request)
    {
        $user = Auth::user();
        $bookingCode = $request->query('booking_code');
        
        $booking = null;
        if ($bookingCode) {
            $booking = Booking::with(['tenant', 'court', 'user', 'payment'])
                ->where('booking_code', $bookingCode)
                ->first();
        }

        // 1. JIKA PEMAIN CHECKOUT DARI HALAMAN VENUE DETAIL DENGAN PARAMETER SLOT
        if (!$booking && $request->filled('court_id')) {
            $court = Court::with('tenant')->find($request->court_id);
            $tenant = $court?->tenant;

            // PROTEKSI BACKEND: Pemilik venue dilarang menyewa lapangan miliknya sendiri
            if ($user && $tenant) {
                $isOwner = ($tenant->owner_user_id == $user->id) || ($user->tenant_id && $user->tenant_id == $tenant->id);
                if ($isOwner) {
                    return redirect()->route('tenant.courts')->with('error', 'Perhatian: Anda adalah pemilik venue (' . $tenant->name . '). Akun pemilik dilarang menyewa lapangan miliknya sendiri! Silakan gunakan menu Jadwal & Lapangan untuk mengelola jadwal kasir.');
                }
            }

            $duration = (int) $request->input('duration', 1);
            $courtPrice = (float) $request->input('court_price', ($court?->price_regular ?? 120000) * $duration);
            $serviceFee = 2500;
            $totalAmount = (float) $request->input('total_amount', $courtPrice + $serviceFee);
            $date = $request->input('date', Carbon::today()->toDateString());
            $startTime = $request->input('start_time', '09:00:00');
            $endTime = $request->input('end_time', '10:00:00');

            $booking = (object)[
                'id' => null,
                'booking_code' => 'LK-BKG-' . rand(10000, 99999),
                'user_id' => $user?->id ?? 2,
                'user' => $user,
                'tenant_id' => $tenant?->id ?? 1,
                'tenant' => $tenant,
                'court_id' => $court?->id ?? 1,
                'court' => $court,
                'booking_date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_hours' => $duration,
                'court_price' => $courtPrice,
                'service_fee' => $serviceFee,
                'total_amount' => $totalAmount,
                'booking_type' => 'regular',
                'status' => 'pending',
                'payment' => null,
            ];

            return view('booking.payment', compact('booking'));
        }

        // 2. JIKA ADA BOOKING TERAKHIR DI DATABASE MILIK USER
        if (!$booking && $user) {
            $booking = Booking::with(['tenant', 'court', 'user', 'payment'])
                ->where('user_id', $user->id)
                ->latest()
                ->first();
        }

        // 3. JIKA DATABASE KOSONG & TIDAK ADA PARAMETER, PAKAI DEFAULT 1 JAM (BUKAN 2 JAM)
        if (!$booking) {
            $firstTenant = Tenant::with('courts')->first();
            $firstCourt = $firstTenant?->courts?->first();

            $pricePerHour = $firstCourt?->price_regular ?? 120000;
            $duration = 1;
            $courtPrice = $pricePerHour * $duration;
            $serviceFee = 2500;
            $totalAmount = $courtPrice + $serviceFee;

            $booking = (object)[
                'id' => null,
                'booking_code' => 'LK-BKG-' . rand(10000, 99999),
                'user_id' => $user?->id ?? 2,
                'user' => $user,
                'tenant_id' => $firstTenant?->id ?? 1,
                'tenant' => $firstTenant,
                'court_id' => $firstCourt?->id ?? 1,
                'court' => $firstCourt,
                'booking_date' => Carbon::today()->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '10:00:00',
                'duration_hours' => $duration,
                'court_price' => $courtPrice,
                'service_fee' => $serviceFee,
                'total_amount' => $totalAmount,
                'booking_type' => 'regular',
                'status' => 'pending',
                'payment' => null,
            ];
        }

        // PROTEKSI FINAL: Cek apakah user yang membuka pembayaran adalah pemilik venue tersebut
        if ($booking && $user && isset($booking->tenant)) {
            $isOwner = ($booking->tenant->owner_user_id == $user->id) || ($user->tenant_id && $user->tenant_id == $booking->tenant->id);
            if ($isOwner) {
                return redirect()->route('tenant.courts')->with('error', 'Perhatian: Anda adalah pemilik venue (' . $booking->tenant->name . '). Akun pemilik dilarang menyewa lapangan miliknya sendiri! Silakan gunakan menu Jadwal & Lapangan untuk mengelola jadwal kasir.');
            }
        }

        return view('booking.payment', compact('booking'));
    }

    /**
     * Konfirmasi Pembayaran Sukses (Simulasi Callback / Sandbox Gateway)
     */
    public function confirmPayment(Request $request)
    {
        $user = Auth::user();
        $bookingCode = $request->input('booking_code');

        $booking = Booking::with('tenant')->where('booking_code', $bookingCode)->first();

        // Validasi identitas tenant untuk proteksi
        $courtId = $request->input('court_id', 1);
        $court = Court::with('tenant')->find($courtId);
        $tenant = $booking?->tenant ?? $court?->tenant ?? Tenant::find($request->input('tenant_id'));

        // PROTEKSI: Pemilik venue dilarang booking di tempatnya sendiri
        if ($user && $tenant) {
            $isOwner = ($tenant->owner_user_id == $user->id) || ($user->tenant_id && $user->tenant_id == $tenant->id);
            if ($isOwner) {
                return redirect()->route('tenant.courts')->with('error', 'Transaksi Ditolak: Anda adalah pemilik venue (' . $tenant->name . '). Akun pemilik tidak dapat menyewa lapangan miliknya sendiri!');
            }
        }

        // Jika booking belum tersimpan di DB (masih simulasi checkout), simpan dengan data persis yang dipilih pemain
        if (!$booking) {
            $courtId = $request->input('court_id', 1);
            $court = Court::find($courtId);
            $tenantId = $request->input('tenant_id', $court?->tenant_id ?? 1);
            $duration = (int) $request->input('duration_hours', 1);
            $courtPrice = (float) $request->input('court_price', 120000);
            $serviceFee = (float) $request->input('service_fee', 2500);
            $totalAmount = (float) $request->input('total_amount', $courtPrice + $serviceFee);
            $date = $request->input('booking_date', Carbon::today()->toDateString());
            $startTime = $request->input('start_time', '09:00:00');
            $endTime = $request->input('end_time', '10:00:00');

            // VALIDASI ANTI-BENTROK JADWAL SEWA REGULER
            $conflict = Booking::where('court_id', $courtId)
                ->where('booking_date', $date)
                ->whereIn('status', ['paid', 'pending'])
                ->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                })
                ->first();

            if ($conflict) {
                $typeName = match($conflict->booking_type) {
                    'open_slot' => 'Open Slot Mabar',
                    'sparring' => 'Match Sparing',
                    default => 'Penyewa lain'
                };
                $slug = $tenant?->slug ?? 'champion-futsal-arena-bandung';
                return redirect()->route('venues.show', $slug)->with('error', "Maaf, jadwal pada tanggal {$date} jam " . substr($startTime, 0, 5) . " - " . substr($endTime, 0, 5) . " WIB baru saja terisi oleh {$typeName}! Silakan pilih jam atau lapangan lain.");
            }

            $booking = Booking::create([
                'booking_code' => $bookingCode ?: ('LK-BKG-' . rand(10000, 99999)),
                'user_id' => $user?->id ?? 2,
                'tenant_id' => $tenantId,
                'court_id' => $courtId,
                'booking_date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration_hours' => $duration,
                'court_price' => $courtPrice,
                'service_fee' => $serviceFee,
                'total_amount' => $totalAmount,
                'booking_type' => 'regular',
                'status' => 'paid',
            ]);
        } else {
            $booking->update(['status' => 'paid']);
        }

        // Catat di tabel Payment
        Payment::create([
            'transaction_code' => 'LK-TRX-' . strtoupper(uniqid()),
            'booking_id' => $booking->id,
            'payment_type' => 'qris',
            'amount' => $booking->total_amount,
            'status' => 'settlement',
            'paid_at' => Carbon::now(),
        ]);

        return redirect()->route('player.dashboard')->with('success', 'Pembayaran booking ' . $booking->booking_code . ' (Rp ' . number_format($booking->total_amount, 0, ',', '.') . ') berhasil! Tiket jadwal Anda aktif.');
    }

    /**
     * Cetak E-Tiket & Bukti Bayar PDF Pemain
     */
    public function printTicketPdf($bookingCode)
    {
        $booking = Booking::with(['tenant', 'court', 'user'])
            ->where('booking_code', $bookingCode)
            ->firstOrFail();

        // Validasi: Struk & E-Tiket hanya dapat dicetak jika pembayaran telah lunas
        if ($booking->status !== 'paid') {
            return redirect()->back()->with('error', 'Struk & E-Tiket tidak dapat dicetak karena pembayaran reservasi ini masih ditunda (Pending / Belum Lunas). Silakan selesaikan pembayaran terlebih dahulu.');
        }

        return view('pdf.booking_ticket', compact('booking'));
    }
}
