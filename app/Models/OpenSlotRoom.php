<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenSlotRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'host_user_id',
        'title',
        'target_players',
        'current_players',
        'price_per_person',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function host()
    {
        return $this->belongsTo(User::class, 'host_user_id');
    }

    public function members()
    {
        return $this->hasMany(OpenSlotMember::class, 'room_id');
    }
}
