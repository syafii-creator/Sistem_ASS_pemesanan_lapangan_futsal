<?php

namespace App\Http\Controllers;

use App\Models\OpenSlotRoom;
use App\Models\OpenSlotMember;
use App\Models\SparringChallenge;
use App\Models\Tenant;
use App\Models\Court;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Payment;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CommunityController extends Controller
{
    public function openSlot()
    {
        // Auto-sinkronisasi status Midtrans HANYA jika ada transaksi pending yang baru dibuat (< 15 menit) milik user aktif
        $user = Auth::user();
        if ($user) {
            $recentPending = Payment::where('status', 'pending')
                ->where('created_at', '>=', now()->subMinutes(15))
                ->where(function ($q) {
                    $q->where('transaction_code', 'LIKE', 'LK-INV-SLOT-%')
                      ->orWhere('transaction_code', 'LIKE', 'LK-SLOT-HOST-%');
                })
                ->where(function ($q) use ($user) {
                    $q->where('payload->user_name', $user->name)
                      ->orWhereHas('booking', function ($bq) use ($user) {
                          $bq->where('user_id', $user->id);
                      });
                })
                ->first();

            if ($recentPending) {
                try {
                    $midtransService = app(\App\Services\MidtransService::class);
                    $midtransService->initMidtrans();
                    $statusData = \Midtrans\Transaction::status($recentPending->transaction_code);
                    $txStatus = is_object($statusData) ? ($statusData->transaction_status ?? '') : ($statusData['transaction_status'] ?? '');
                    if (in_array($txStatus, ['settlement', 'capture'])) {
                        $midtransService->markPaymentSuccess($recentPending->transaction_code, 'midtrans', (array) $statusData);
                    }
                } catch (\Throwable $e) {}
            }
        }

        // AUTO-CANCEL: Batalkan otomatis room yang kuotanya belum terpenuhi hingga H-2 Jam sebelum kick-off
        // Risiko Host: Jadwal lapangan langsung dilepas ke publik dan pembayaran host hangus
        $this->autoCancelExpiredRooms();

        $rooms = OpenSlotRoom::with(['booking.tenant', 'booking.court', 'host', 'members'])
            ->where('status', '!=', 'pending_payment')
            ->latest()
            ->get();

        $venues = Tenant::with('courts')->where('status', 'active')->get();

        return view('community.open-slot', compact('rooms', 'venues'));
    }

    public function storeOpenSlot(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'venue_id' => 'nullable|exists:tenants,id',
            'court_id' => 'nullable|exists:courts,id',
            'level' => 'nullable|string',
            'booking_date' => 'required|date',
            'time_slot' => 'required|string',
            'target_players' => 'required|integer|min:4|max:20',
            'price_per_person' => 'required|numeric|min:5000',
        ]);

        $user = Auth::user() ?? User::where('email', 'budi@gmail.com')->first();
        if ($request->filled('court_id')) {
            $court = Court::with('tenant')->findOrFail($request->court_id);
            $tenant = $court->tenant;
        } else {
            $tenant = Tenant::findOrFail($request->venue_id);
            $court = Court::where('tenant_id', $tenant->id)->first() ?? Court::first();
        }

        // Normalisasi format jam: ubah titik jadi titik dua (19.00 -> 19:00), dukung berbagai pemisah
        $rawSlot = str_replace('.', ':', trim($request->time_slot));
        if (preg_match('/(\d{1,2}:\d{2})\s*[-–]\s*(\d{1,2}:\d{2})/', $rawSlot, $matches)) {
            $startTime = str_pad($matches[1], 5, '0', STR_PAD_LEFT) . ':00';
            $endTime = str_pad($matches[2], 5, '0', STR_PAD_LEFT) . ':00';
        } else {
            $clean = preg_replace('/[^0-9:]/', '', $rawSlot);
            $startH = (int) substr($clean, 0, 2);
            $startTime = sprintf('%02d:00:00', $startH);
            $endTime = sprintf('%02d:00:00', min(24, $startH + 2));
        }

        $startHour = (int) substr($startTime, 0, 2);
        $endHour = (int) substr($endTime, 0, 2);
        $durationHours = max(1, $endHour - $startHour);

        // VALIDASI ANTI-BENTROK JADWAL LAPANGAN
        $conflict = Booking::where('court_id', $court->id)
            ->where('booking_date', $request->booking_date)
            ->whereIn('status', ['paid', 'pending'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->first();

        if ($conflict) {
            if ($conflict->status === 'pending' && ($conflict->user_id === $user->id || $conflict->created_at < \Carbon\Carbon::now()->subMinutes(15))) {
                $conflict->update(['status' => 'cancelled']);
            } else {
                $typeName = match($conflict->booking_type) {
                    'open_slot' => 'Open Slot Mabar lain',
                    'sparring' => 'Match Sparing',
                    default => 'Booking Reguler'
                };
                $jam = substr($conflict->start_time, 0, 5) . ' - ' . substr($conflict->end_time, 0, 5);
                return back()->withInput()->with('error', "Jadwal Bentrok! Lapangan \"{$court->name}\" di {$tenant->name} pada tanggal {$request->booking_date} jam {$jam} WIB sudah terisi oleh {$typeName}. Silakan pilih jam atau lapangan lain.");
            }
        }

        $booking = Booking::create([
            'booking_code' => 'LK-BKG-' . rand(10000, 99999),
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'booking_date' => $request->booking_date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_hours' => $durationHours,
            'court_price' => $request->price_per_person * $request->target_players,
            'service_fee' => 2500,
            'total_amount' => ($request->price_per_person * $request->target_players) + 2500,
            'booking_type' => 'open_slot',
            'status' => ($request->target_players <= 1) ? 'paid' : 'pending',
        ]);

        $hostSlots = (int) $request->input('host_slots', 1);
        if ($hostSlots < 1) $hostSlots = 1;
        if ($hostSlots >= $request->target_players) $hostSlots = $request->target_players - 1;

        $room = OpenSlotRoom::create([
            'booking_id' => $booking->id,
            'host_user_id' => $user->id,
            'title' => $request->title,
            'target_players' => $request->target_players,
            'current_players' => $hostSlots,
            'price_per_person' => $request->price_per_person,
            'status' => 'open',
        ]);

        $hostAmount = $request->price_per_person * $hostSlots;

        // Catat Payment Resmi untuk share patungan Host
        $trxCode = 'LK-INV-SLOT-' . strtoupper(Str::random(6));
        $payment = Payment::create([
            'transaction_code' => $trxCode,
            'booking_id' => $booking->id,
            'payment_type' => 'qris',
            'amount' => $hostAmount,
            'status' => 'settlement',
            'payload' => [
                'type' => 'open_slot_host',
                'room_title' => $room->title,
                'user_name' => $user->name,
                'slot_count' => $hostSlots,
                'price_per_person' => $request->price_per_person,
                'gateway' => 'QRIS Instan Simulator',
            ],
            'paid_at' => Carbon::now(),
        ]);

        OpenSlotMember::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'slots_count' => $hostSlots,
            'payment_id' => $payment->id,
            'status' => 'paid',
            'notes' => 'Host Room (' . $hostSlots . ' Slot)',
        ]);

        return redirect()->route('player.dashboard')->with('success', 'Room mabar "' . $room->title . '" berhasil diterbitkan! Pembayaran patungan untuk ' . $hostSlots . ' orang sebesar Rp ' . number_format($hostAmount, 0, ',', '.') . ' LUNAS. Invoice patungan telah tercatat di profil Anda.');
    }

    public function joinOpenSlot(Request $request)
    {
        $roomId = $request->input('room_id');
        $room = OpenSlotRoom::with('booking')->findOrFail($roomId);
        $user = Auth::user() ?? User::where('email', 'budi@gmail.com')->first();

        // VALIDASI: Host tidak boleh join room buatannya sendiri (karena sudah lunas di awal)
        if ($room->host_user_id === $user->id) {
            return back()->with('error', 'Anda adalah Host pembuat room mabar ini. Slot Anda sudah otomatis terdaftar dan lunas saat pembuatan room.');
        }

        // VALIDASI: Member yang sudah lunas tidak boleh double-join
        $existingMember = OpenSlotMember::where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->where('status', 'paid')
            ->first();
        if ($existingMember) {
            return back()->with('error', 'Anda sudah terdaftar dan lunas di room mabar ini.');
        }

        $availableSlots = max(0, $room->target_players - $room->current_players);
        if ($availableSlots <= 0) {
            return back()->with('error', 'Maaf, kuota room mabar ini sudah penuh!');
        }

        // Jumlah orang / slot yang dibayar (custom oleh satu orang pembayar)
        $slotCount = (int) $request->input('slot_count', 1);
        if ($slotCount < 1) {
            $slotCount = 1;
        }

        if ($slotCount > $availableSlots) {
            return back()->with('error', "Jumlah slot yang dipilih ({$slotCount} orang) melebihi sisa kuota yang tersedia ({$availableSlots} slot lagi).");
        }

        $notes = trim($request->input('notes', ''));

        // Hitung total patungan: jumlah orang * biaya asli per orang
        $totalAmount = $room->price_per_person * $slotCount;

        // Tambahkan jumlah pemain ke kuota room
        $room->increment('current_players', $slotCount);
        if ($room->current_players >= $room->target_players) {
            $room->update(['status' => 'full']);
            if ($room->booking) {
                $room->booking->update(['status' => 'paid']);
            }
        }

        // Catat Payment Resmi untuk patungan multi-orang
        $trxCode = 'LK-INV-SLOT-' . strtoupper(Str::random(6));
        $payment = Payment::create([
            'transaction_code' => $trxCode,
            'booking_id' => $room->booking_id,
            'payment_type' => 'qris',
            'amount' => $totalAmount,
            'status' => 'settlement',
            'payload' => [
                'type' => 'open_slot_member',
                'room_title' => $room->title,
                'user_name' => $user->name,
                'slot_count' => $slotCount,
                'price_per_person' => $room->price_per_person,
                'notes' => $notes ?: "Patungan {$slotCount} Orang",
                'gateway' => 'QRIS Instan Simulator',
            ],
            'paid_at' => Carbon::now(),
        ]);

        OpenSlotMember::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'slots_count' => $slotCount,
            'payment_id' => $payment->id,
            'status' => 'paid',
            'notes' => $notes ?: "Patungan {$slotCount} Orang",
        ]);

        return redirect()->route('player.dashboard')->with('success', 'Pembayaran patungan mabar "' . $room->title . '" untuk ' . $slotCount . ' orang sebesar Rp ' . number_format($totalAmount, 0, ',', '.') . ' BERHASIL! Invoice resmi telah tersimpan di profil Anda.');
    }

    public function cancelOpenSlot($id)
    {
        $room = OpenSlotRoom::with(['booking', 'members.payment'])->findOrFail($id);
        $user = Auth::user();

        // Hanya Host atau Admin yang bisa membatalkan
        if ($user && $user->id !== $room->host_user_id && $user->role !== 'superadmin') {
            return back()->with('error', 'Anda tidak memiliki hak akses untuk membatalkan room ini.');
        }

        $room->update(['status' => 'cancelled']);
        if ($room->booking) {
            $room->booking->update(['status' => 'cancelled']);
        }

        // Batalkan status member:
        // Host: Risiko host open slot (slot hangus sebagai kompensasi penahanan jadwal)
        // Peserta lain: Refunded
        foreach ($room->members as $member) {
            if ($member->user_id === $room->host_user_id) {
                $member->update([
                    'status' => 'cancelled',
                    'notes' => 'Hangus - Risiko Host Open Slot (Dibatalkan oleh Host)',
                ]);
            } else {
                $member->update([
                    'status' => 'refunded',
                    'notes' => 'Di-refund otomatis - Room dibatalkan oleh Host',
                ]);
                if ($member->payment) {
                    $member->payment->update(['status' => 'refunded']);
                }
            }
        }

        return redirect()->route('player.dashboard')->with('success', 'Room mabar "' . $room->title . '" berhasil dibatalkan. Jadwal lapangan telah dilepas kembali ke publik.');
    }

    /**
     * AUTO-CANCEL EXPIRED ROOMS:
     * Batalkan otomatis room open slot jika kuota target belum terpenuhi hingga batas H-2 Jam sebelum kick-off.
     * Aturan Risiko Host:
     * 1. Jadwal booking lapangan otomatis di-cancel sehingga bebas disewa oleh pengguna lain di katalog venue.
     * 2. Slot yang dibayar host di awal menjadi non-refundable (hangus) sebagai kompensasi hold lapangan.
     * 3. Peserta publik lain yang sempat patungan statusnya menjadi refunded.
     */
    public function autoCancelExpiredRooms(): int
    {
        $openRooms = OpenSlotRoom::with(['booking', 'members.payment'])
            ->where('status', 'open')
            ->get();

        $cancelledCount = 0;
        $now = Carbon::now();

        foreach ($openRooms as $room) {
            if (!$room->booking) {
                continue;
            }

            try {
                $bookingDate = Carbon::parse($room->booking->booking_date)->toDateString();
                $startTime = substr($room->booking->start_time ?? '00:00:00', 0, 5);
                $matchDateTime = Carbon::parse($bookingDate . ' ' . $startTime);

                // Batas Auto-Cancel: H-2 Jam sebelum kick-off
                $autoCancelDeadline = $matchDateTime->copy()->subHours(2);

                if ($now->greaterThanOrEqualTo($autoCancelDeadline) && $room->current_players < $room->target_players) {
                    $room->update(['status' => 'cancelled']);

                    // 1. BEBASKAN JADWAL LAPANGAN (seketika bisa disewa umum)
                    if ($room->booking) {
                        $room->booking->update(['status' => 'cancelled']);
                    }

                    // 2. ATURAN RISIKO HOST & REFUND PESERTA
                    foreach ($room->members as $member) {
                        if ($member->user_id === $room->host_user_id) {
                            $member->update([
                                'status' => 'cancelled',
                                'notes' => 'Hangus - Risiko Host Open Slot (Batas kuota H-2 jam tidak terpenuhi)',
                            ]);
                        } else {
                            $member->update([
                                'status' => 'refunded',
                                'notes' => 'Di-refund otomatis - Kuota mabar tidak terpenuhi sebelum batas H-2 jam',
                            ]);
                            if ($member->payment) {
                                $member->payment->update(['status' => 'refunded']);
                            }
                        }
                    }

                    $cancelledCount++;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return $cancelledCount;
    }

    public function printOpenSlotInvoicePdf($id)
    {
        $member = OpenSlotMember::with(['room.booking.tenant', 'room.booking.court', 'room.host', 'user', 'payment'])
            ->findOrFail($id);

        if ($member->status !== 'paid' && optional($member->payment)->status !== 'settlement') {
            return redirect()->back()->with('error', 'Invoice mabar open slot tidak dapat dicetak karena pembayaran masih ditunda (Pending / Belum Lunas).');
        }

        return view('pdf.openslot_invoice', compact('member'));
    }

    public function sparring()
    {
        // Auto-sinkronisasi status Midtrans HANYA jika ada pembayaran sparring pending yang baru (< 15 menit) milik user aktif
        $user = Auth::user();
        if ($user) {
            $recentPending = Payment::where('status', 'pending')
                ->where('transaction_code', 'LIKE', 'LK-SPAR-%')
                ->where('created_at', '>=', now()->subMinutes(15))
                ->where(function ($q) use ($user) {
                    $q->whereHas('booking', function ($bq) use ($user) {
                        $bq->where('user_id', $user->id);
                    })->orWhere('payload->challenger_user_id', $user->id);
                })
                ->first();

            if ($recentPending) {
                try {
                    $midtransService = app(\App\Services\MidtransService::class);
                    $midtransService->initMidtrans();
                    $statusData = \Midtrans\Transaction::status($recentPending->transaction_code);
                    $txStatus = is_object($statusData) ? ($statusData->transaction_status ?? '') : ($statusData['transaction_status'] ?? '');
                    if (in_array($txStatus, ['settlement', 'capture'])) {
                        $midtransService->markPaymentSuccess($recentPending->transaction_code, 'midtrans', (array) $statusData);
                    }
                } catch (\Throwable $e) {}
            }
        }

        $challenges = SparringChallenge::with(['booking.tenant', 'booking.court', 'challenger'])
            ->whereIn('status', ['open', 'matched'])
            ->latest()
            ->get();

        // Hitung dynamic leaderboard dari data riil tantangan sparring di database (Bukan Data Dummy)
        $allChallenges = SparringChallenge::all();
        $teamStats = [];

        foreach ($allChallenges as $c) {
            $host = trim($c->host_team_name ?? '');
            $challenger = trim($c->challenger_team_name ?? '');
            $isMatched = in_array($c->status, ['matched', 'completed']);
            
            $tierRaw = strtolower($c->skill_tier ?? 'menengah');
            $tierLabel = match($tierRaw) {
                'amatir' => 'Amatir',
                'semipro', 'semi-pro' => 'Semi-Pro',
                'kompetitif' => 'Kompetitif',
                default => 'Menengah'
            };

            if (!empty($host)) {
                $hostKey = preg_replace('/\bFc\b/i', 'FC', ucwords(strtolower($host)));
                if (!isset($teamStats[$hostKey])) {
                    $teamStats[$hostKey] = [
                        'name' => $hostKey,
                        'matches' => 0,
                        'total_challenges' => 0,
                        'tier' => $tierLabel,
                    ];
                }
                $teamStats[$hostKey]['total_challenges']++;
                if ($isMatched) {
                    $teamStats[$hostKey]['matches']++;
                    $teamStats[$hostKey]['tier'] = $tierLabel;
                }
            }

            if (!empty($challenger)) {
                $challengerKey = preg_replace('/\bFc\b/i', 'FC', ucwords(strtolower($challenger)));
                if (!isset($teamStats[$challengerKey])) {
                    $teamStats[$challengerKey] = [
                        'name' => $challengerKey,
                        'matches' => 0,
                        'total_challenges' => 0,
                        'tier' => $tierLabel,
                    ];
                }
                $teamStats[$challengerKey]['total_challenges']++;
                if ($isMatched) {
                    $teamStats[$challengerKey]['matches']++;
                    $teamStats[$challengerKey]['tier'] = $tierLabel;
                }
            }
        }

        // Urutkan berdasarkan pertandingan terbanyak (matches desc, lalu total_challenges desc)
        usort($teamStats, function ($a, $b) {
            if ($b['matches'] === $a['matches']) {
                return $b['total_challenges'] <=> $a['total_challenges'];
            }
            return $b['matches'] <=> $a['matches'];
        });

        // Top 5 tim riil dari database
        $leaderboard = array_slice($teamStats, 0, 5);

        $venues = Tenant::with('courts')->where('status', 'active')->get();

        return view('community.sparring', compact('challenges', 'venues', 'leaderboard'));
    }

    public function storeSparring(Request $request)
    {
        $request->validate([
            'team_name' => 'required|string|max:100',
            'venue_id' => 'nullable|exists:tenants,id',
            'court_id' => 'nullable|exists:courts,id',
            'skill_tier' => 'required|string',
            'split_rule' => 'required|string',
            'booking_date' => 'required|date',
            'time_slot' => 'required|string',
            'contact_wa' => 'required|string',
        ]);

        $user = Auth::user() ?? User::where('email', 'budi@gmail.com')->first();
        if ($request->filled('court_id')) {
            $court = Court::with('tenant')->findOrFail($request->court_id);
            $tenant = $court->tenant;
        } else {
            $tenant = Tenant::findOrFail($request->venue_id);
            $court = Court::where('tenant_id', $tenant->id)->first() ?? Court::first();
        }

        $rawSlot = str_replace('.', ':', trim($request->time_slot));
        if (preg_match('/(\d{1,2}:\d{2})\s*[-–]\s*(\d{1,2}:\d{2})/', $rawSlot, $matches)) {
            $startTime = str_pad($matches[1], 5, '0', STR_PAD_LEFT) . ':00';
            $endTime = str_pad($matches[2], 5, '0', STR_PAD_LEFT) . ':00';
        } else {
            $clean = preg_replace('/[^0-9:]/', '', $rawSlot);
            $startH = (int) substr($clean, 0, 2);
            $startTime = sprintf('%02d:00:00', $startH);
            $endTime = sprintf('%02d:00:00', min(24, $startH + 2));
        }

        // VALIDASI ANTI-BENTROK JADWAL LAPANGAN
        $conflict = Booking::where('court_id', $court->id)
            ->where('booking_date', $request->booking_date)
            ->whereIn('status', ['paid', 'pending'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->first();

        if ($conflict) {
            if ($conflict->status === 'pending' && ($conflict->user_id === $user->id || $conflict->created_at < \Carbon\Carbon::now()->subMinutes(15))) {
                $conflict->update(['status' => 'cancelled']);
            } else {
                $typeName = match($conflict->booking_type) {
                    'open_slot' => 'Open Slot Mabar',
                    'sparring' => 'Match Sparing lain',
                    default => 'Booking Reguler'
                };
                $jam = substr($conflict->start_time, 0, 5) . ' - ' . substr($conflict->end_time, 0, 5);
                return back()->withInput()->with('error', "Jadwal Bentrok! Lapangan \"{$court->name}\" di {$tenant->name} pada tanggal {$request->booking_date} jam {$jam} WIB sudah terisi oleh {$typeName}. Silakan pilih jam atau lapangan lain.");
            }
        }

        $startHour = (int) substr($startTime, 0, 2);
        $endHour = (int) substr($endTime, 0, 2);
        $duration = max(1, $endHour - $startHour);
        $ratePerHour = ($startHour >= 17) ? ($court->price_peak ?? 180000) : ($court->price_regular ?? 120000);
        $courtPrice = $ratePerHour * $duration;
        $serviceFee = 2500;
        $totalAmount = $courtPrice + $serviceFee;

        // BIAYA SPARING DIBAGI ADIL 50 : 50
        $hostShare = round($totalAmount / 2);

        $booking = Booking::create([
            'booking_code' => 'LK-BKG-' . rand(10000, 99999),
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'booking_date' => $request->booking_date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_hours' => $duration,
            'court_price' => $courtPrice,
            'service_fee' => $serviceFee,
            'total_amount' => $totalAmount,
            'booking_type' => 'sparring',
            'status' => 'pending', // Pending: Host baru bayar 50%, menunggu lawan bayar 50%
        ]);

        // Catat Payment Resmi untuk Porsi 50% Tim Host
        $trxCode = 'LK-SPAR-HOST-' . strtoupper(Str::random(6));
        Payment::create([
            'transaction_code' => $trxCode,
            'booking_id' => $booking->id,
            'payment_type' => 'qris',
            'amount' => $hostShare,
            'status' => 'settlement',
            'payload' => [
                'type' => 'sparring_host',
                'team_name' => $request->team_name,
                'share' => '50%',
                'paid_by' => $user->name,
                'gateway' => 'QRIS Instan Simulator',
            ],
            'paid_at' => Carbon::now(),
        ]);

        $challenge = SparringChallenge::create([
            'booking_id' => $booking->id,
            'host_team_name' => $request->team_name,
            'challenger_team_name' => null,
            'skill_tier' => $request->skill_tier,
            'split_rule' => '50_50',
            'contact_wa' => $request->contact_wa,
            'status' => 'open',
        ]);

        return back()->with('success', 'Tantangan tanding tim "' . $challenge->host_team_name . '" berhasil dipasang! Pembayaran patungan 50% Anda (Rp ' . number_format($hostShare, 0, ',', '.') . ') LUNAS via QRIS.');
    }

    public function acceptSparring(Request $request)
    {
        $challengeId = $request->input('challenge_id');
        $challengerTeam = $request->input('challenger_team', 'Tim Tamu FC');

        $challenge = SparringChallenge::with('booking')->findOrFail($challengeId);
        $user = Auth::user() ?? User::where('email', 'budi@gmail.com')->first();

        // VALIDASI: Host / pembuat tantangan tidak boleh menerima tantangannya sendiri
        if ($challenge->booking && $challenge->booking->user_id === $user->id) {
            return back()->with('error', 'Anda adalah pembuat tantangan ini dan tidak dapat menerima tantangan sparring Anda sendiri.');
        }

        if ($challenge->status === 'matched') {
            return back()->with('error', 'Tantangan sparring ini sudah diterima oleh tim lawan.');
        }

        if ($challenge->status === 'cancelled') {
            return back()->with('error', 'Tantangan sparring ini sudah dibatalkan.');
        }

        // Hitung porsi 50% untuk Tim Lawan
        $totalAmount = $challenge->booking ? $challenge->booking->total_amount : 352500;
        $hostShare = round($totalAmount / 2);
        $guestShare = $totalAmount - $hostShare;

        // Catat Payment Resmi untuk Porsi 50% Tim Tamu (Lawan)
        $trxCode = 'LK-SPAR-GUEST-' . strtoupper(Str::random(6));
        Payment::create([
            'transaction_code' => $trxCode,
            'booking_id' => $challenge->booking_id,
            'payment_type' => 'qris',
            'amount' => $guestShare,
            'status' => 'settlement',
            'payload' => [
                'type' => 'sparring_guest',
                'team_name' => $challengerTeam,
                'share' => '50%',
                'paid_by' => $user->name,
                'gateway' => 'QRIS Instan Simulator',
            ],
            'paid_at' => Carbon::now(),
        ]);

        $challenge->update([
            'challenger_team_name' => $challengerTeam,
            'challenger_user_id' => $user->id,
            'status' => 'matched',
        ]);

        // Karena kedua tim sudah melunasi masing-masing 50%, status booking menjadi Lunas Penuh ('paid')
        if ($challenge->booking) {
            $challenge->booking->update(['status' => 'paid']);
        }

        return back()->with('success', 'Tantangan tanding tim "' . $challenge->host_team_name . '" berhasil diterima oleh "' . $challengerTeam . '"! Pembayaran patungan 50% (Rp ' . number_format($guestShare, 0, ',', '.') . ') LUNAS via QRIS.');
    }

    public function cancelSparring($id)
    {
        $challenge = SparringChallenge::with('booking')->findOrFail($id);
        $user = Auth::user();

        // Hanya Host (pembuat tantangan) atau Admin yang bisa membatalkan
        if ($user && $challenge->booking && $user->id !== $challenge->booking->user_id && $user->role !== 'superadmin') {
            return back()->with('error', 'Anda tidak memiliki hak akses untuk membatalkan tantangan sparring ini.');
        }

        if ($challenge->status === 'matched') {
            return back()->with('error', 'Tantangan tidak dapat dibatalkan karena sudah diterima oleh tim lawan.');
        }

        $challenge->update(['status' => 'cancelled']);
        if ($challenge->booking) {
            $challenge->booking->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'Tantangan sparring tim "' . $challenge->host_team_name . '" berhasil dibatalkan.');
    }

    public function printSparringInvoicePdf($paymentId)
    {
        $payment = Payment::with(['booking.tenant', 'booking.court', 'booking.sparringChallenge', 'booking.user'])
            ->findOrFail($paymentId);

        if ($payment->status !== 'settlement') {
            return redirect()->back()->with('error', 'Invoice patungan sparring tidak dapat dicetak karena pembayaran belum lunas.');
        }

        $booking = $payment->booking;
        $challenge = $booking ? $booking->sparringChallenge : null;
        $user = Auth::user();

        $isHost = isset($payment->payload['type']) && $payment->payload['type'] === 'sparring_host';

        return view('pdf.sparring_invoice', compact('payment', 'challenge', 'booking', 'isHost', 'user'));
    }
}
