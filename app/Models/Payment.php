<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'booking_id',
        'subscription_id',
        'payment_type',
        'amount',
        'status',
        'snap_token',
        'payload',
        'paid_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'paid_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function subscription()
    {
        return $this->belongsTo(TenantSubscription::class);
    }

    /**
     * Dapatkan nama channel pembayaran asli dari Midtrans (BCA VA, Mandiri Bill, QRIS, dll.)
     */
    public function getPaymentChannelAttribute(): string
    {
        $payload = $this->payload;
        if (is_string($payload)) {
            $payload = json_decode($payload, true);
        }

        // 1. Cek bank transfer di payload Midtrans (va_numbers)
        if (!empty($payload['va_numbers'][0]['bank'])) {
            return strtoupper($payload['va_numbers'][0]['bank']) . ' Virtual Account';
        }

        if (!empty($payload['payment_type'])) {
            $type = strtolower($payload['payment_type']);
            if ($type === 'bank_transfer') {
                if (!empty($payload['bca_va_number'])) return 'BCA Virtual Account';
                if (!empty($payload['bni_va_number'])) return 'BNI Virtual Account';
                if (!empty($payload['bri_va_number'])) return 'BRI Virtual Account';
                if (!empty($payload['permata_va_number'])) return 'Permata Virtual Account';
                return 'Bank Transfer (VA)';
            }
            if ($type === 'echannel') return 'Mandiri Bill Payment';
            if ($type === 'qris') {
                $issuer = !empty($payload['issuer']) ? strtoupper($payload['issuer']) : (!empty($payload['acquirer']) ? strtoupper($payload['acquirer']) : '');
                return $issuer ? 'QRIS (' . $issuer . ')' : 'QRIS Dinamis';
            }
            if ($type === 'gopay') return 'GoPay';
            if ($type === 'shopeepay') return 'ShopeePay';
            if ($type === 'credit_card') return 'Kartu Kredit';
            if ($type === 'cstore') return strtoupper($payload['store'] ?? 'Alfamart / Indomaret');
            return strtoupper(str_replace('_', ' ', $type));
        }

        if (!empty($this->payment_type)) {
            $colType = strtolower($this->payment_type);
            if ($colType === 'midtrans') return 'Midtrans Gateway';
            if ($colType === 'bca_va') return 'BCA Virtual Account';
            if ($colType === 'mandiri_va') return 'Mandiri Virtual Account';
            if ($colType === 'qris') return 'QRIS Dinamis';
            return strtoupper(str_replace('_', ' ', $colType));
        }

        return 'Midtrans Gateway';
    }

    /**
     * Alias accessor agar kompatibel dengan pemanggilan $payment->payment_method
     */
    public function getPaymentMethodAttribute(): string
    {
        return $this->getPaymentChannelAttribute();
    }
}
