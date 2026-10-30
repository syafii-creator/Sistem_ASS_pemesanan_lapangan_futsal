<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenSlotMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'player_role',
        'slots_count',
        'price_per_slot',
        'total_paid',
        'payment_id',
        'status',
        'notes',
        'ticket_code',
    ];

    public function room()
    {
        return $this->belongsTo(OpenSlotRoom::class, 'room_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
