<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\OpenSlotMember;
use App\Models\SparringChallenge;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard Khusus Pemain (Memerlukan Auth Middleware)
     */
    public function index()
    {
        $user = Auth::user();

        // Redirect sesuai role jika bukan pemain
        if ($user->role === 'tenant') {
            return redirect()->route('tenant.dashboard');
        } elseif ($user->role === 'superadmin') {
            return redirect()->route('tenant.dashboard');
        }

        // Auto-sinkronisasi status Midtrans untuk transaksi booking yang masih pending
        $midtransService = app(\App\Services\MidtransService::class);
        $midtransService->initMidtrans();

        $pendingBookings = Booking::with('payment')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();

        foreach ($pendingBookings as $pb) {
            // 1. Cek dari status payment lokal jika sudah ada data expired/failed
            if ($pb->payment && in_array($pb->payment->status, ['expire', 'expired', 'failed', 'cancel'])) {
                $pb->update(['status' => 'cancelled']);
                continue;
            }

            // 2. Cek ke gateway Midtrans
            try {
                $statusData = \Midtrans\Transaction::status($pb->booking_code);
                $txStatus = is_object($statusData) ? ($statusData->transaction_status ?? '') : ($statusData['transaction_status'] ?? '');
                if (in_array($txStatus, ['settlement', 'capture'])) {
                    $midtransService->markPaymentSuccess($pb->booking_code, 'midtrans', (array) $statusData);
                } elseif (in_array($txStatus, ['cancel', 'deny', 'expire'])) {
                    $midtransService->markPaymentFailed($pb->booking_code, $txStatus, (array) $statusData);
                }
            } catch (\Throwable $e) {
                // Jika booking sudah kadaluarsa (misal lebih dari 2 jam belum dibayar)
                if ($pb->created_at && $pb->created_at->lt(now()->subHours(2))) {
                    $pb->update(['status' => 'cancelled']);
                    if ($pb->payment) {
                        $pb->payment->update(['status' => 'expired']);
                    }
                }
            }
        }

        // Auto-sinkronisasi status Midtrans untuk open slot yang masih pending
        $pendingSlots = OpenSlotMember::with('payment')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subHours(2))
            ->limit(2)
            ->get();

        foreach ($pendingSlots as $ps) {
            if ($ps->payment && $ps->payment->transaction_code) {
                try {
                    $statusData = \Midtrans\Transaction::status($ps->payment->transaction_code);
                    $txStatus = is_object($statusData) ? ($statusData->transaction_status ?? '') : ($statusData['transaction_status'] ?? '');
                    if (in_array($txStatus, ['settlement', 'capture'])) {
                        $midtransService->markPaymentSuccess($ps->payment->transaction_code, 'midtrans', (array) $statusData);
                    }
                } catch (\Throwable $e) {
                    // Abaikan jika belum dibayar di Midtrans
                }
            }
        }

        // Auto-sinkronisasi status Midtrans untuk sparring patungan yang masih pending
        $pendingSparPayments = Payment::where('status', 'pending')
            ->where('transaction_code', 'LIKE', 'LK-SPAR-%')
            ->where('created_at', '>=', now()->subHours(2))
            ->where(function ($q) use ($user) {
                $q->whereHas('booking', function ($bq) use ($user) {
                    $bq->where('user_id', $user->id);
                })->orWhere('payload->challenger_user_id', $user->id);
            })
            ->limit(2)
            ->get();

        foreach ($pendingSparPayments as $psp) {
            try {
                $statusData = \Midtrans\Transaction::status($psp->transaction_code);
                $txStatus = is_object($statusData) ? ($statusData->transaction_status ?? '') : ($statusData['transaction_status'] ?? '');
                if (in_array($txStatus, ['settlement', 'capture'])) {
                    $midtransService->markPaymentSuccess($psp->transaction_code, 'midtrans', (array) $statusData);
                }
            } catch (\Throwable $e) {}
        }

        // Ambil data riwayat booking reguler milik pemain
        $bookings = Booking::with(['tenant', 'court', 'payment', 'sparringChallenge', 'payments'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Ambil data riwayat open slot mabar pemain
        $openSlots = OpenSlotMember::with(['room.booking.tenant', 'room.booking.court', 'payment'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Ambil data riwayat match sparring (baik sebagai Host maupun Challenger)
        $sparringMatches = SparringChallenge::with(['booking.tenant', 'booking.court', 'booking.payments', 'challenger'])
            ->where(function ($q) use ($user) {
                $q->whereHas('booking', function ($bq) use ($user) {
                    $bq->where('user_id', $user->id);
                })->orWhere('challenger_user_id', $user->id);
            })
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->get();

        return view('player.dashboard', compact('user', 'bookings', 'openSlots', 'sparringMatches'));
    }

    /**
     * Update profil pemain (nama & kontak telepon)
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Data profil pemain berhasil diperbarui.');
    }

    /**
     * Cek status pembayaran booking spesifik secara manual
     */
    public function checkBookingStatus(Request $request, $id)
    {
        $user = Auth::user();
        $booking = Booking::with('payment')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($booking->status === 'paid') {
            return back()->with('success', 'Pembayaran untuk booking #' . $booking->booking_code . ' sudah LUNAS.');
        }

        if ($booking->status === 'cancelled') {
            return back()->with('info', 'Status booking #' . $booking->booking_code . ' sudah DIBATALKAN / KADALUARSA.');
        }

        // Cek dari status payment lokal jika sudah expire
        if ($booking->payment && in_array($booking->payment->status, ['expire', 'expired', 'failed', 'cancel'])) {
            $booking->update(['status' => 'cancelled']);
            return back()->with('warning', 'Batas waktu pembayaran untuk booking #' . $booking->booking_code . ' telah habis (Expired). Pesanan dibatalkan.');
        }

        // Cek ke Midtrans
        $midtransService = app(\App\Services\MidtransService::class);
        $midtransService->initMidtrans();

        try {
            $statusData = \Midtrans\Transaction::status($booking->booking_code);
            $txStatus = is_object($statusData) ? ($statusData->transaction_status ?? '') : ($statusData['transaction_status'] ?? '');

            if (in_array($txStatus, ['settlement', 'capture'])) {
                $midtransService->markPaymentSuccess($booking->booking_code, 'midtrans', (array) $statusData);
                return back()->with('success', 'Pembayaran terverifikasi! Status booking #' . $booking->booking_code . ' kini LUNAS.');
            } elseif (in_array($txStatus, ['cancel', 'deny', 'expire'])) {
                $midtransService->markPaymentFailed($booking->booking_code, $txStatus, (array) $statusData);
                return back()->with('warning', 'Pembayaran gagal atau telah kadaluarsa (' . strtoupper($txStatus) . '). Booking dibatalkan.');
            } else {
                return back()->with('info', 'Pembayaran untuk booking #' . $booking->booking_code . ' belum diselesaikan (Status: MENUNGGU PEMBAYARAN). Silakan klik tombol "Bayar" untuk melanjutkan.');
            }
        } catch (\Throwable $e) {
            // Jika booking sudah lebih dari 2 jam, otomatis tandai expired/cancelled
            if ($booking->created_at && $booking->created_at->lt(now()->subHours(2))) {
                $booking->update(['status' => 'cancelled']);
                if ($booking->payment) {
                    $booking->payment->update(['status' => 'expired']);
                }
                return back()->with('warning', 'Waktu pembayaran untuk booking #' . $booking->booking_code . ' telah kadaluarsa (Expired). Booking dibatalkan.');
            }

            return back()->with('info', 'Pembayaran belum diselesaikan. Silakan klik tombol "Bayar" untuk melunasi sewa lapangan via Midtrans.');
        }
    }

    /**
     * Batalkan booking pending secara manual oleh pemain
     */
    public function cancelBooking(Request $request, $id)
    {
        $user = Auth::user();
        $booking = Booking::with('payment')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($booking->status === 'paid') {
            return back()->with('error', 'Booking yang sudah lunas tidak dapat dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);
        if ($booking->payment) {
            $booking->payment->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'Booking #' . $booking->booking_code . ' berhasil dibatalkan.');
    }
}
