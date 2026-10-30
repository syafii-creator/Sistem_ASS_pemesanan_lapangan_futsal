<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'booking_code',
        'user_id',
        'tenant_id',
        'court_id',
        'booking_date',
        'start_time',
        'end_time',
        'duration_hours',
        'court_price',
        'service_fee',
        'total_amount',
        'booking_type',
        'status',
        'archived',
    ];

    protected $casts = [
        'archived' => 'boolean',
        'booking_date' => 'date',
        'court_price' => 'float',
        'service_fee' => 'float',
        'total_amount' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function openSlotRoom()
    {
        return $this->hasOne(OpenSlotRoom::class);
    }

    public function sparringChallenge()
    {
        return $this->hasOne(SparringChallenge::class);
    }
}
