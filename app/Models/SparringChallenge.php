<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparringChallenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'host_team_name',
        'challenger_team_name',
        'challenger_user_id',
        'skill_tier',
        'split_rule',
        'contact_wa',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function challenger()
    {
        return $this->belongsTo(User::class, 'challenger_user_id');
    }
}
