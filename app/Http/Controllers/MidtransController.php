<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\Tenant;
use App\Models\Court;
use App\Models\SaasPackage;
use App\Models\TenantSubscription;
use App\Models\OpenSlotRoom;
use App\Models\OpenSlotMember;
use App\Models\SparringChallenge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MidtransController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Endpoint API AJAX untuk meminta Snap Token Midtrans bagi semua jenis pembayaran
     */
    public function getSnapToken(Request $request)
    {
        $type = $request->input('type'); // subscription, booking, open_slot, sparring
        $user = Auth::user() ?? User::where('role', 'player')->first() ?? User::first();

        switch ($type) {
            case 'subscription':
                return $this->createSubscriptionSnap($request, $user);

            case 'booking':
                return $this->createBookingSnap($request, $user);

            case 'open_slot':
                return $this->createOpenSlotSnap($request, $user);

            case 'open_slot_host':
                return $this->createOpenSlotHostSnap($request, $user);

            case 'sparring':
            case 'sparring_guest':
                return $this->createSparringSnap($request, $user);

            case 'sparring_host':
                return $this->createSparringHostSnap($request, $user);

            case 'sparring_pending_host':
                return $this->payPendingSparringSnap($request, $user);

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Tipe pembayaran tidak dikenali: ' . $type
                ], 400);
        }
    }

    /**
     * 1. Midtrans Snap Token untuk Langganan SaaS Mitra
     */
    protected function createSubscriptionSnap(Request $request, $user)
    {
        $packageId = $request->input('package_id');
        $package = SaasPackage::findOrFail($packageId);
        $tenant = Tenant::where('owner_user_id', $user->id)->first()
            ?? ($user->tenant_id ? Tenant::find($user->tenant_id) : null)
            ?? Tenant::first();

        $orderId = 'LK-SAAS-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        $amount = (float) $package->price;

        $payment = Payment::create([
            'transaction_code' => $orderId,
            'subscription_id' => null,
            'payment_type' => 'midtrans',
            'amount' => $amount,
            'status' => 'pending',
            'payload' => [
                'type' => 'saas_subscription',
                'package_id' => $package->id,
                'package_name' => $package->name,
                'billing_cycle' => $package->billing_cycle,
                'tenant_id' => $tenant->id,
            ],
        ]);

        $snapData = $this->midtransService->createSnapToken([
            'order_id' => $orderId,
            'gross_amount' => $amount,
            'customer_name' => $tenant->name ?? $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $tenant->phone ?? '081234567890',
            'item_name' => 'Langganan SaaS: ' . $package->name,
        ]);

        $payment->update(['snap_token' => $snapData['snap_token']]);

        return response()->json(array_merge($snapData, [
            'amount' => $amount,
            'title' => 'Langganan ' . $package->name,
        ]));
    }

    /**
     * 2. Midtrans Snap Token untuk Booking Lapangan Individu
     */
    protected function createBookingSnap(Request $request, $user)
    {
        $courtId = $request->input('court_id', 1);
        $court = Court::with('tenant')->findOrFail($courtId);
        $tenant = $court->tenant;

        $date = $request->input('booking_date', Carbon::today()->toDateString());
        $startTime = $request->input('start_time', '19:00:00');
        $endTime = $request->input('end_time', '20:00:00');
        $duration = (int) $request->input('duration_hours', 1);
        $courtPrice = (float) $request->input('court_price', ($court->price_regular ?? 120000) * $duration);
        $serviceFee = 2500;
        $totalAmount = $courtPrice + $serviceFee;

        // Validasi bentrok
        $conflict = Booking::where('court_id', $courtId)
            ->where('booking_date', $date)
            ->whereIn('status', ['paid', 'pending'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->first();

        if ($conflict) {
            // Jika bentrok dengan booking pending milik user itu sendiri, atau booking pending sudah kedaluwarsa (> 15 menit),
            // batalkan pending lama tersebut secara aman agar tidak mengunci lapangan sendiri.
            if ($conflict->status === 'pending' && ($conflict->user_id === $user->id || $conflict->created_at < Carbon::now()->subMinutes(15))) {
                $conflict->update(['status' => 'cancelled']);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal bentrok! Lapangan sudah terisi pada jam tersebut.'
                ], 422);
            }
        }

        $orderId = 'LK-BKG-' . rand(10000, 99999);

        $booking = Booking::create([
            'booking_code' => $orderId,
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'booking_date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_hours' => $duration,
            'court_price' => $courtPrice,
            'service_fee' => $serviceFee,
            'total_amount' => $totalAmount,
            'booking_type' => 'regular',
            'status' => 'pending',
        ]);

        $payment = Payment::create([
            'transaction_code' => $orderId,
            'booking_id' => $booking->id,
            'payment_type' => 'midtrans',
            'amount' => $totalAmount,
            'status' => 'pending',
            'payload' => [
                'type' => 'regular_booking',
                'booking_code' => $orderId,
                'court_name' => $court->name,
                'tenant_name' => $tenant->name,
            ],
        ]);

        $snapData = $this->midtransService->createSnapToken([
            'order_id' => $orderId,
            'gross_amount' => $totalAmount,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone ?? '081234567890',
            'item_name' => 'Sewa ' . $court->name . ' (' . $duration . ' Jam)',
        ]);

        $payment->update(['snap_token' => $snapData['snap_token']]);

        return response()->json(array_merge($snapData, [
            'amount' => $totalAmount,
            'title' => 'Sewa Lapangan ' . $court->name,
            'booking_code' => $orderId,
        ]));
    }

    /**
     * 3. Midtrans Snap Token untuk Open Slot Mabar Komunitas
     */
    protected function createOpenSlotSnap(Request $request, $user)
    {
        $roomId = $request->input('room_id');
        $room = OpenSlotRoom::with('booking')->findOrFail($roomId);

        // VALIDASI: Host tidak boleh bayar slot di room sendiri via Midtrans (sudah lunas di awal)
        if ($room->host_user_id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda adalah Host pembuat room mabar ini. Slot Anda sudah otomatis terdaftar dan lunas saat pembuatan room.'
            ], 422);
        }

        // VALIDASI: Member yang sudah lunas tidak boleh double-join
        $existingMember = OpenSlotMember::where('room_id', $room->id)
            ->where('user_id', $user->id)
            ->where('status', 'paid')
            ->first();
        if ($existingMember) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah terdaftar dan lunas di room mabar ini.'
            ], 422);
        }

        $availableSlots = max(0, $room->target_players - $room->current_players);
        if ($availableSlots <= 0) {
            return response()->json(['success' => false, 'message' => 'Room sudah penuh.'], 422);
        }

        $playerRole = $request->input('player_role', 'player');
        if ($playerRole === 'goalkeeper') {
            $targetGk = $room->target_gk ?? 2;
            $currentGk = $room->current_gk ?? 0;
            if ($currentGk >= $targetGk) {
                return response()->json(['success' => false, 'message' => 'Kuota posisi kiper sudah penuh.'], 422);
            }
        }

        $slotCount = (int) $request->input('slot_count', 1);
        if ($slotCount < 1) $slotCount = 1;
        if ($slotCount > $availableSlots) {
            return response()->json(['success' => false, 'message' => 'Jumlah slot melebihi kuota.'], 422);
        }

        $unitPrice = ($playerRole === 'goalkeeper')
            ? ($room->gk_price ?: max(10000, $room->price_per_person - 10000))
            : $room->price_per_person;

        $totalAmount = $unitPrice * $slotCount;
        $orderId = 'LK-INV-SLOT-' . strtoupper(Str::random(6));
        $ticketCode = 'LK-TKT-' . strtoupper(Str::random(8));

        // Tambahkan ke kuota room
        $room->increment('current_players', $slotCount);
        if ($playerRole === 'goalkeeper') {
            $room->increment('current_gk', 1);
        }

        $payment = Payment::create([
            'transaction_code' => $orderId,
            'booking_id' => $room->booking_id,
            'payment_type' => 'midtrans',
            'amount' => $totalAmount,
            'status' => 'pending',
            'payload' => [
                'type' => 'open_slot_member',
                'room_title' => $room->title,
                'user_name' => $user->name,
                'player_role' => $playerRole,
                'slot_count' => $slotCount,
                'price_per_person' => $unitPrice,
                'notes' => $request->input('notes', ''),
                'policy' => 'NON-REFUNDABLE',
                'ticket_code' => $ticketCode,
            ],
        ]);

        OpenSlotMember::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'player_role' => $playerRole,
            'slots_count' => $slotCount,
            'price_per_slot' => $unitPrice,
            'total_paid' => $totalAmount,
            'payment_id' => $payment->id,
            'status' => 'pending', // Pending sampai Midtrans lunas
            'notes' => $request->input('notes') ?: ($playerRole === 'goalkeeper' ? 'Posisi Kiper' : "Patungan {$slotCount} Orang"),
            'ticket_code' => $ticketCode,
        ]);

        $snapData = $this->midtransService->createSnapToken([
            'order_id' => $orderId,
            'gross_amount' => $totalAmount,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone ?? '081234567890',
            'item_name' => 'Patungan Mabar: ' . substr($room->title, 0, 30),
        ]);

        $payment->update(['snap_token' => $snapData['snap_token']]);

        return response()->json(array_merge($snapData, [
            'amount' => $totalAmount,
            'ticket_code' => $ticketCode,
            'title' => 'Patungan Mabar ' . $room->title,
        ]));
    }

    /**
     * 3b. Midtrans Snap Token untuk Host yang Membuka Room Open Slot (Bayar Rombongan Host)
     */
    protected function createOpenSlotHostSnap(Request $request, $user)
    {
        $title = trim($request->input('title', 'Mabar Open Slot'));
        $courtId = $request->input('court_id');
        if ($courtId) {
            $court = Court::with('tenant')->findOrFail($courtId);
            $tenant = $court->tenant;
        } else {
            $tenantId = $request->input('venue_id');
            $tenant = Tenant::findOrFail($tenantId);
            $court = Court::where('tenant_id', $tenant->id)->first() ?? Court::first();
        }

        $bookingDate = $request->input('booking_date', Carbon::today()->addDay()->toDateString());
        $timeSlot = $request->input('time_slot', '19:00 - 21:00');
        $targetPlayers = (int) $request->input('target_players', 10);
        if ($targetPlayers < 4) $targetPlayers = 4;
        if ($targetPlayers > 30) $targetPlayers = 30;

        $hostSlots = (int) $request->input('host_slots', 1);
        if ($hostSlots < 1) $hostSlots = 1;
        if ($hostSlots >= $targetPlayers) {
            return response()->json([
                'success' => false,
                'message' => "Slot rombongan Host ({$hostSlots} orang) tidak boleh melebihi atau sama dengan kuota total ({$targetPlayers} orang). Sisakan minimal 1 slot untuk pemain umum."
            ], 422);
        }

        $pricePerPerson = (float) $request->input('price_per_person', 25000);
        if ($pricePerPerson < 5000) $pricePerPerson = 25000;

        // Parse time slot (dukung format titik 19.00 - 20.00 maupun titik dua 19:00 - 20:00)
        $rawSlot = str_replace('.', ':', trim($timeSlot));
        if (preg_match('/(\d{1,2}:\d{2})\s*[-–]\s*(\d{1,2}:\d{2})/', $rawSlot, $matches)) {
            $startTime = str_pad($matches[1], 5, '0', STR_PAD_LEFT) . ':00';
            $endTime = str_pad($matches[2], 5, '0', STR_PAD_LEFT) . ':00';
        } else {
            $clean = preg_replace('/[^0-9:]/', '', $rawSlot);
            $startH = (int) substr($clean, 0, 2);
            $startTime = sprintf('%02d:00:00', $startH);
            $endTime = sprintf('%02d:00:00', min(24, $startH + 2));
        }
        $durationHours = max(1, (int)substr($endTime, 0, 2) - (int)substr($startTime, 0, 2));

        // VALIDASI ANTI-BENTROK JADWAL LAPANGAN
        $conflict = Booking::where('court_id', $court->id)
            ->where('booking_date', $bookingDate)
            ->whereIn('status', ['paid', 'pending'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->first();

        if ($conflict) {
            if ($conflict->status === 'pending' && ($conflict->user_id === $user->id || $conflict->created_at < Carbon::now()->subMinutes(15))) {
                $conflict->update(['status' => 'cancelled']);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Jadwal Bentrok! Lapangan \"{$court->name}\" di {$tenant->name} pada jam tersebut sudah terisi. Silakan pilih jam atau lapangan lain."
                ], 422);
            }
        }

        $hostAmount = $pricePerPerson * $hostSlots;
        $totalCourtAmount = ($pricePerPerson * $targetPlayers) + 2500;
        $orderId = 'LK-SLOT-HOST-' . strtoupper(Str::random(6));
        $ticketCode = 'LK-TKT-' . strtoupper(Str::random(8));

        $booking = Booking::create([
            'booking_code' => 'LK-BKG-' . rand(10000, 99999),
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'booking_date' => $bookingDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_hours' => $durationHours,
            'court_price' => $pricePerPerson * $targetPlayers,
            'service_fee' => 2500,
            'total_amount' => $totalCourtAmount,
            'booking_type' => 'open_slot',
            'status' => 'pending',
        ]);

        $room = OpenSlotRoom::create([
            'booking_id' => $booking->id,
            'host_user_id' => $user->id,
            'title' => $title,
            'target_players' => $targetPlayers,
            'current_players' => $hostSlots,
            'price_per_person' => $pricePerPerson,
            'status' => 'pending_payment',
        ]);

        $payment = Payment::create([
            'transaction_code' => $orderId,
            'booking_id' => $booking->id,
            'payment_type' => 'midtrans',
            'amount' => $hostAmount,
            'status' => 'pending',
            'payload' => [
                'type' => 'open_slot_host',
                'room_id' => $room->id,
                'room_title' => $room->title,
                'user_name' => $user->name,
                'host_slots' => $hostSlots,
                'price_per_person' => $pricePerPerson,
                'target_players' => $targetPlayers,
                'ticket_code' => $ticketCode,
            ],
        ]);

        OpenSlotMember::create([
            'room_id' => $room->id,
            'user_id' => $user->id,
            'slots_count' => $hostSlots,
            'price_per_slot' => $pricePerPerson,
            'total_paid' => $hostAmount,
            'payment_id' => $payment->id,
            'status' => 'pending',
            'notes' => 'Host Rombongan (' . $hostSlots . ' Slot)',
            'ticket_code' => $ticketCode,
        ]);

        $snapData = $this->midtransService->createSnapToken([
            'order_id' => $orderId,
            'gross_amount' => $hostAmount,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone ?? '081234567890',
            'item_name' => 'Patungan Host Mabar (' . $hostSlots . ' Orang): ' . substr($room->title, 0, 25),
        ]);

        $payment->update(['snap_token' => $snapData['snap_token']]);

        return response()->json(array_merge($snapData, [
            'amount' => $hostAmount,
            'title' => 'Patungan Host Mabar (' . $hostSlots . ' Orang)',
            'ticket_code' => $ticketCode,
        ]));
    }

    /**
     * 4. Midtrans Snap Token untuk Sparing Match (Split Bill 50:50)
     */
    protected function createSparringSnap(Request $request, $user)
    {
        $challengeId = $request->input('challenge_id');
        $challengerTeam = $request->input('challenger_team', 'Tim Penantang FC');

        $challenge = SparringChallenge::with('booking')->findOrFail($challengeId);

        // VALIDASI: Host / pembuat tantangan tidak boleh menerima tantangannya sendiri
        if ($challenge->booking && $challenge->booking->user_id === $user->id) {
            return response()->json(['error' => 'Anda adalah pembuat tantangan ini dan tidak dapat menerima tantangan sparring Anda sendiri.'], 422);
        }

        if ($challenge->status === 'matched') {
            return response()->json(['error' => 'Tantangan sparring ini sudah diterima oleh tim lawan.'], 422);
        }

        if ($challenge->status === 'cancelled') {
            return response()->json(['error' => 'Tantangan sparring ini sudah dibatalkan.'], 422);
        }

        $totalAmount = $challenge->booking ? $challenge->booking->total_amount : 352500;
        $guestShare = round($totalAmount / 2);

        $orderId = 'LK-SPAR-GUEST-' . strtoupper(Str::random(6));

        $payment = Payment::create([
            'transaction_code' => $orderId,
            'booking_id' => $challenge->booking_id,
            'payment_type' => 'midtrans',
            'amount' => $guestShare,
            'status' => 'pending',
            'payload' => [
                'type' => 'sparring_guest',
                'challenge_id' => $challenge->id,
                'team_name' => $challengerTeam,
                'challenger_user_id' => $user->id,
                'share' => '50%',
                'paid_by' => $user->name,
            ],
        ]);

        $snapData = $this->midtransService->createSnapToken([
            'order_id' => $orderId,
            'gross_amount' => $guestShare,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone ?? '081234567890',
            'item_name' => 'Patungan Sparing 50%: vs ' . $challenge->host_team_name,
        ]);

        $payment->update(['snap_token' => $snapData['snap_token']]);

        return response()->json(array_merge($snapData, [
            'amount' => $guestShare,
            'title' => 'Sparing 50% vs ' . $challenge->host_team_name,
        ]));
    }

    /**
     * 5. Midtrans Snap Token untuk Host yang Memposting Sparing Match (Bayar 50% Pertama)
     */
    protected function createSparringHostSnap(Request $request, $user)
    {
        $teamName = $request->input('team_name', 'Tim Tuan Rumah FC');
        $courtId = $request->input('court_id');
        if ($courtId) {
            $court = Court::with('tenant')->findOrFail($courtId);
            $tenant = $court->tenant;
        } else {
            $tenantId = $request->input('venue_id');
            $tenant = Tenant::findOrFail($tenantId);
            $court = Court::where('tenant_id', $tenant->id)->first() ?? Court::first();
        }

        $date = $request->input('booking_date', Carbon::today()->addDays(2)->toDateString());
        $timeSlot = $request->input('time_slot', '20:00 - 21:00');
        $skillTier = $request->input('skill_tier', 'menengah');
        $contactWa = $request->input('contact_wa', '081234567890');

        $rawSlot = str_replace('.', ':', trim($timeSlot));
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
            ->where('booking_date', $date)
            ->whereIn('status', ['paid', 'pending'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->first();

        if ($conflict) {
            if ($conflict->status === 'pending' && ($conflict->user_id === $user->id || $conflict->created_at < Carbon::now()->subMinutes(15))) {
                $conflict->update(['status' => 'cancelled']);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => "Jadwal Bentrok! Lapangan \"{$court->name}\" sudah terisi pada jam tersebut. Silakan pilih slot lain."
                ], 422);
            }
        }

        $startHour = (int) substr($startTime, 0, 2);
        $endHour = (int) substr($endTime, 0, 2);
        $duration = max(1, $endHour - $startHour);
        $ratePerHour = ($startHour >= 17) ? ($court->price_peak ?? 180000) : ($court->price_regular ?? 120000);
        $courtPrice = $ratePerHour * $duration;
        $serviceFee = 2500;
        $totalAmount = $courtPrice + $serviceFee;
        $hostShare = round($totalAmount / 2);

        $orderId = 'LK-SPAR-HOST-' . strtoupper(Str::random(6));

        $booking = Booking::create([
            'booking_code' => 'LK-BKG-' . rand(10000, 99999),
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'court_id' => $court->id,
            'booking_date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration_hours' => $duration,
            'court_price' => $courtPrice,
            'service_fee' => $serviceFee,
            'total_amount' => $totalAmount,
            'booking_type' => 'sparring',
            'status' => 'pending',
        ]);

        $payment = Payment::create([
            'transaction_code' => $orderId,
            'booking_id' => $booking->id,
            'payment_type' => 'midtrans',
            'amount' => $hostShare,
            'status' => 'pending',
            'payload' => [
                'type' => 'sparring_host',
                'team_name' => $teamName,
                'skill_tier' => $skillTier,
                'contact_wa' => $contactWa,
                'share' => '50%',
                'paid_by' => $user->name,
            ],
        ]);

        $challenge = SparringChallenge::create([
            'booking_id' => $booking->id,
            'host_team_name' => $teamName,
            'challenger_team_name' => null,
            'skill_tier' => $skillTier,
            'split_rule' => '50_50',
            'contact_wa' => $contactWa,
            'status' => 'pending_payment',
        ]);

        $snapData = $this->midtransService->createSnapToken([
            'order_id' => $orderId,
            'gross_amount' => $hostShare,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $contactWa ?: ($user->phone ?? '081234567890'),
            'item_name' => 'Patungan Sparing 50% (Host): ' . $teamName,
        ]);

        $payment->update(['snap_token' => $snapData['snap_token']]);

        return response()->json(array_merge($snapData, [
            'amount' => $hostShare,
            'title' => 'Patungan 50% Host: ' . $teamName,
        ]));
    }

    /**
     * Pelunasan 50% Host yang Tertunda dari Dashboard
     */
    protected function payPendingSparringSnap(Request $request, $user)
    {
        $challengeId = $request->input('challenge_id');
        $challenge = SparringChallenge::with(['booking.tenant', 'booking.court', 'booking.payments'])->findOrFail($challengeId);

        if (!$challenge->booking || $challenge->booking->user_id !== $user->id) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $hostShare = round($challenge->booking->total_amount / 2);

        $pendingPayment = $challenge->booking->payments
            ->first(function ($p) {
                return isset($p->payload['type']) && $p->payload['type'] === 'sparring_host' && $p->status === 'pending';
            });

        if (!$pendingPayment) {
            $orderId = 'LK-SPAR-HOST-' . strtoupper(Str::random(6));
            $pendingPayment = Payment::create([
                'transaction_code' => $orderId,
                'booking_id' => $challenge->booking_id,
                'payment_type' => 'midtrans',
                'amount' => $hostShare,
                'status' => 'pending',
                'payload' => [
                    'type' => 'sparring_host',
                    'team_name' => $challenge->host_team_name,
                    'share' => '50%',
                    'paid_by' => $user->name,
                ],
            ]);
        }

        $snapData = $this->midtransService->createSnapToken([
            'order_id' => $pendingPayment->transaction_code,
            'gross_amount' => $pendingPayment->amount,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $challenge->contact_wa ?? ($user->phone ?? '081234567890'),
            'item_name' => 'Patungan Sparing 50% (Host): ' . $challenge->host_team_name,
        ]);

        $pendingPayment->update(['snap_token' => $snapData['snap_token']]);

        return response()->json(array_merge($snapData, [
            'amount' => $pendingPayment->amount,
            'title' => 'Patungan 50% Host: ' . $challenge->host_team_name,
        ]));
    }

    /**
     * Webhook Callback Resmi dari Midtrans (Exempt from CSRF)
     */
    public function handleNotification(Request $request)
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        // Verifikasi signature jika dikirimkan oleh Midtrans
        if ($orderId && $statusCode && $grossAmount && $signatureKey) {
            $isValid = $this->midtransService->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey);
            if (!$isValid) {
                return response()->json(['error' => 'Invalid signature key'], 403);
            }
        }

        $result = $this->midtransService->processNotification($payload);

        return response()->json(['status' => 'OK', 'result' => $result]);
    }

    /**
     * Endpoint Simulasi Pelunasan Instan (Untuk Demo / Presentasi / Sandbox Simulator)
     */
    public function simulatePayment(Request $request)
    {
        $orderId = $request->input('order_id');
        $paymentType = $request->input('payment_type', 'qris_midtrans');

        $result = $this->midtransService->markPaymentSuccess($orderId, $paymentType, [
            'gateway' => 'Midtrans Sandbox Simulator',
            'simulated_at' => Carbon::now()->toDateTimeString(),
        ]);

        $redirectUrl = route('midtrans.finish', ['order_id' => $orderId]);

        return response()->json([
            'success' => true,
            'result' => $result,
            'redirect_url' => $redirectUrl,
        ]);
    }

    /**
     * Finish Return URL saat pembayaran selesai
     */
    public function finish(Request $request)
    {
        $orderId = $request->query('order_id');

        if ($orderId) {
            try {
                $this->midtransService->initMidtrans();
                $statusData = \Midtrans\Transaction::status($orderId);
                $txStatus = is_object($statusData) ? ($statusData->transaction_status ?? '') : ($statusData['transaction_status'] ?? '');
                if (in_array($txStatus, ['settlement', 'capture'])) {
                    $this->midtransService->markPaymentSuccess($orderId, 'midtrans', (array) $statusData);
                }
            } catch (\Throwable $e) {
                // Abaikan jika order belum tercatat di Midtrans
            }
        }

        if (str_starts_with($orderId, 'LK-SAAS-')) {
            return redirect()->route('tenant.billing')->with('success', 'Pembayaran paket kemitraan via Midtrans BERHASIL! Status langganan venue kini aktif.');
        } elseif (str_starts_with($orderId, 'LK-SLOT-HOST-')) {
            return redirect()->route('community.open-slot')->with('success', 'Room mabar berhasil diterbitkan! Pembayaran slot rombongan Host LUNAS via Midtrans. Sisa slot kini dibuka untuk pemain umum.');
        } elseif (str_starts_with($orderId, 'LK-SPAR-HOST-')) {
            return redirect()->route('player.dashboard')->with('success', 'Pembayaran patungan 50% Host LUNAS via Midtrans! Tantangan sparring tim Anda kini resmi terpasang di Lobby.');
        } elseif (str_starts_with($orderId, 'LK-SPAR-')) {
            return redirect()->route('player.dashboard')->with('success', 'Pembayaran patungan 50% tim lawan via Midtrans LUNAS! Pertandingan sparring berhasil terkunci.');
        } else {
            return redirect()->route('player.dashboard')->with('success', 'Pembayaran via Midtrans BERHASIL! E-Tiket dan bukti transaksi telah tersimpan di akun Anda.');
        }
    }
}
