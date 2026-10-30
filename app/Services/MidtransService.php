<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Booking;
use App\Models\TenantSubscription;
use App\Models\OpenSlotRoom;
use App\Models\OpenSlotMember;
use App\Models\SparringChallenge;
use App\Models\SaasPackage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        $this->initMidtrans();
    }

    /**
     * Inisialisasi konfigurasi Midtrans SDK
     */
    public function initMidtrans(): void
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$clientKey = config('midtrans.client_key');
        \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production', false);
        \Midtrans\Config::$isSanitized = (bool) config('midtrans.is_sanitized', true);
        \Midtrans\Config::$is3ds = (bool) config('midtrans.is_3ds', true);
    }

    /**
     * Buat Snap Token Midtrans untuk checkout
     */
    public function createSnapToken(array $params): array
    {
        $this->initMidtrans();

        $grossAmount = (int) round($params['gross_amount']);
        $orderId = $params['order_id'];

        $midtransParams = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $params['customer_name'] ?? 'Pemain',
                'email' => $params['customer_email'] ?? 'pemain@lapangankita.id',
                'phone' => $params['customer_phone'] ?? '081234567890',
            ],
            'item_details' => $params['item_details'] ?? [
                [
                    'id' => $orderId,
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => substr($params['item_name'] ?? 'Pembayaran LapanganKita', 0, 50),
                ]
            ],
            'callbacks' => [
                'finish' => route('midtrans.finish', ['order_id' => $orderId]),
            ],
        ];

        try {
            // Coba request Snap Token resmi dari Midtrans API
            $snapToken = \Midtrans\Snap::getSnapToken($midtransParams);
            return [
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
                'is_mock' => false,
            ];
        } catch (\Throwable $e) {
            Log::warning('Midtrans API fallback to sandbox simulator: ' . $e->getMessage());

            // Jika offline / kredensial sandbox demo belum aktif di Midtrans, buat mock token yang aman
            $mockToken = 'MOCK-SNAP-' . strtoupper(Str::random(24));
            return [
                'success' => true,
                'snap_token' => $mockToken,
                'order_id' => $orderId,
                'redirect_url' => '#',
                'is_mock' => true,
                'note' => 'Berjalan di mode Sandbox Simulator LapanganKita.',
            ];
        }
    }

    /**
     * Verifikasi keaslian signature key dari Webhook Notification Midtrans
     */
    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $serverKey = config('midtrans.server_key');
        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        return hash_equals($expected, $signatureKey);
    }

    /**
     * Eksekusi status pembayaran saat Midtrans mengirim callback / webhook
     */
    public function processNotification(array $payload): array
    {
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? 'settlement';
        $fraudStatus = $payload['fraud_status'] ?? 'accept';
        $paymentType = $payload['payment_type'] ?? 'qris';

        if (!$orderId) {
            return ['success' => false, 'message' => 'Order ID tidak ditemukan dalam payload.'];
        }

        $payment = Payment::where('transaction_code', $orderId)->first();

        // Tentukan apakah pembayaran berhasil Lunas
        $isSuccess = false;
        if ($transactionStatus === 'capture') {
            $isSuccess = ($fraudStatus === 'accept');
        } elseif ($transactionStatus === 'settlement') {
            $isSuccess = true;
        }

        if ($isSuccess) {
            return $this->markPaymentSuccess($orderId, $paymentType, $payload);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            return $this->markPaymentFailed($orderId, $transactionStatus, $payload);
        }

        return ['success' => true, 'status' => 'pending', 'message' => 'Menunggu penyelesaian pembayaran.'];
    }

    /**
     * Tandai pembayaran LUNAS dan aktifkan entitas terkait (Langganan, Booking, Open Slot, Sparing)
     */
    public function markPaymentSuccess(string $orderId, string $paymentType = 'midtrans', array $rawPayload = []): array
    {
        $payment = Payment::where('transaction_code', $orderId)->first();

        // 1. KATEGORI: LANGGANAN SAAS MITRA (LK-SAAS-...)
        if (str_starts_with($orderId, 'LK-SAAS-')) {
            return $this->processSubscriptionSuccess($orderId, $payment, $paymentType, $rawPayload);
        }

        // 2. KATEGORI: BOOKING LAPANGAN REGULER (LK-BKG-...)
        if (str_starts_with($orderId, 'LK-BKG-')) {
            return $this->processBookingSuccess($orderId, $payment, $paymentType, $rawPayload);
        }

        // 3. KATEGORI: OPEN SLOT MABAR KOMUNITAS (LK-INV-SLOT- & LK-SLOT-HOST-)
        if (str_starts_with($orderId, 'LK-INV-SLOT-') || str_starts_with($orderId, 'LK-SLOT-HOST-')) {
            return $this->processOpenSlotSuccess($orderId, $payment, $paymentType, $rawPayload);
        }

        // 4. KATEGORI: SPARING MATCH TIM (LK-SPAR-...)
        if (str_starts_with($orderId, 'LK-SPAR-')) {
            return $this->processSparringSuccess($orderId, $payment, $paymentType, $rawPayload);
        }

        // Fallback jika tidak ada prefix khusus
        if ($payment) {
            $payment->update([
                'status' => 'settlement',
                'payment_type' => $paymentType,
                'paid_at' => Carbon::now(),
                'payload' => array_merge($payment->payload ?? [], $rawPayload),
            ]);
            if ($payment->booking) {
                $payment->booking->update(['status' => 'paid']);
            }
        }

        return ['success' => true, 'status' => 'settlement', 'message' => 'Pembayaran umum berhasil diselesaikan.'];
    }

    /**
     * Tandai pembayaran GAGAL / KEDALUWARSA
     */
    public function markPaymentFailed(string $orderId, string $reason, array $rawPayload = []): array
    {
        $payment = Payment::where('transaction_code', $orderId)->first();
        if ($payment) {
            $payment->update([
                'status' => $reason === 'expire' ? 'expired' : 'failed',
                'payload' => array_merge($payment->payload ?? [], $rawPayload, ['failure_reason' => $reason]),
            ]);

            if ($payment->booking && $payment->booking->status === 'pending') {
                $payment->booking->update(['status' => 'cancelled']);
            }
        }

        return ['success' => true, 'status' => 'failed', 'reason' => $reason];
    }

    // =========================================================================
    // SUB-HANDLER 1: LANGGANAN SAAS MITRA
    // =========================================================================
    protected function processSubscriptionSuccess(string $orderId, ?Payment $payment, string $paymentType, array $payload): array
    {
        if ($payment) {
            $subscription = $payment->subscription;
            $tenantId = $payment->payload['tenant_id'] ?? $subscription?->tenant_id;

            if (!$subscription && isset($payment->payload['package_id']) && $tenantId) {
                $package = SaasPackage::find($payment->payload['package_id']);
                $durationDays = ($package?->billing_cycle === 'tahunan') ? 365 : 30;

                // Akumulatif perpanjangan
                $latestActive = TenantSubscription::where('tenant_id', $tenantId)
                    ->whereIn('status', ['active', 'trial'])
                    ->where('end_date', '>', Carbon::now())
                    ->orderBy('end_date', 'desc')
                    ->first();

                $baseDate = ($latestActive && $latestActive->end_date > Carbon::now()) 
                    ? Carbon::parse($latestActive->end_date) 
                    : Carbon::now();
                $newEndDate = $baseDate->copy()->addDays($durationDays);

                $subscription = TenantSubscription::create([
                    'tenant_id' => $tenantId,
                    'package_id' => $package->id,
                    'start_date' => Carbon::now(),
                    'end_date' => $newEndDate,
                    'status' => 'active',
                    'price_paid' => $payment->amount,
                ]);

                $payment->update(['subscription_id' => $subscription->id]);
            } elseif ($subscription) {
                $package = $subscription->package;
                $durationDays = ($package?->billing_cycle === 'tahunan') ? 365 : 30;

                $baseDate = ($subscription->end_date > Carbon::now()) 
                    ? Carbon::parse($subscription->end_date) 
                    : Carbon::now();
                $newEndDate = $baseDate->copy()->addDays($durationDays);

                $subscription->update([
                    'status' => 'active',
                    'end_date' => $newEndDate,
                ]);
            }

            $payment->update([
                'status' => 'settlement',
                'payment_type' => $paymentType,
                'paid_at' => Carbon::now(),
                'payload' => array_merge($payment->payload ?? [], $payload, ['midtrans_status' => 'settlement']),
            ]);
        }

        return ['success' => true, 'type' => 'subscription', 'message' => 'Langganan SaaS Mitra berhasil diaktifkan via Midtrans.'];
    }

    // =========================================================================
    // SUB-HANDLER 2: BOOKING LAPANGAN INDIVIDU
    // =========================================================================
    protected function processBookingSuccess(string $orderId, ?Payment $payment, string $paymentType, array $payload): array
    {
        $booking = Booking::where('booking_code', $orderId)->first();
        if (!$booking && $payment && $payment->booking) {
            $booking = $payment->booking;
        }

        if ($booking) {
            $booking->update(['status' => 'paid']);
        }

        if ($payment) {
            $payment->update([
                'status' => 'settlement',
                'payment_type' => $paymentType,
                'paid_at' => Carbon::now(),
                'payload' => array_merge($payment->payload ?? [], $payload, ['midtrans_status' => 'settlement']),
            ]);
        }

        return ['success' => true, 'type' => 'booking', 'message' => 'Booking Lapangan Individu berhasil dilunasi via Midtrans.'];
    }

    // =========================================================================
    // SUB-HANDLER 3: OPEN SLOT MABAR KOMUNITAS
    // =========================================================================
    protected function processOpenSlotSuccess(string $orderId, ?Payment $payment, string $paymentType, array $payload): array
    {
        if ($payment) {
            $payment->update([
                'status' => 'settlement',
                'payment_type' => $paymentType,
                'paid_at' => Carbon::now(),
                'payload' => array_merge($payment->payload ?? [], $payload, ['midtrans_status' => 'settlement']),
            ]);

            $member = OpenSlotMember::where('payment_id', $payment->id)->first();
            if ($member) {
                $member->update(['status' => 'paid']);
                $room = $member->room;
                if ($room) {
                    if ($room->status === 'pending_payment') {
                        $room->update(['status' => 'open']);
                    }
                    if ($room->current_players >= $room->target_players) {
                        $room->update(['status' => 'full']);
                        if ($room->booking) {
                            $room->booking->update(['status' => 'paid']);
                        }
                    }
                }
            }
        }

        return ['success' => true, 'type' => 'open_slot', 'message' => 'Pembayaran Patungan Open Slot berhasil diproses via Midtrans.'];
    }

    // =========================================================================
    // SUB-HANDLER 4: SPARING MATCH
    // =========================================================================
    protected function processSparringSuccess(string $orderId, ?Payment $payment, string $paymentType, array $payload): array
    {
        if ($payment) {
            $payment->update([
                'status' => 'settlement',
                'payment_type' => $paymentType,
                'paid_at' => Carbon::now(),
                'payload' => array_merge($payment->payload ?? [], $payload, ['midtrans_status' => 'settlement']),
            ]);

            // Kasus 1: Host melunasi porsi 50% saat membuat tantangan -> Buka tantangan di Lobby
            if (str_starts_with($orderId, 'LK-SPAR-HOST-')) {
                if ($payment->booking) {
                    $challenge = SparringChallenge::where('booking_id', $payment->booking_id)->first();
                    if ($challenge) {
                        $challenge->update(['status' => 'open']);
                    }
                }
            }

            // Kasus 2: Jika ini pembayaran tim penantang (guest), maka tandai matched dan booking lunas
            if (str_starts_with($orderId, 'LK-SPAR-GUEST-')) {
                if ($payment->booking) {
                    $challenge = SparringChallenge::where('booking_id', $payment->booking_id)->first();
                    if ($challenge) {
                        $teamName = $payment->payload['team_name'] ?? 'Tim Penantang FC';
                        $challengerUserId = $payment->payload['challenger_user_id'] ?? null;
                        $challenge->update([
                            'status' => 'matched',
                            'challenger_team_name' => $teamName,
                            'challenger_user_id' => $challengerUserId,
                        ]);
                    }
                    $payment->booking->update(['status' => 'paid']);
                }
            }
        }

        return ['success' => true, 'type' => 'sparring', 'message' => 'Pembayaran Sparing 50% berhasil diselesaikan via Midtrans.'];
    }
}
