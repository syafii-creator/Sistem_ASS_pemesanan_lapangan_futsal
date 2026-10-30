<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'owner_user_id',
        'name',
        'slug',
        'city',
        'address',
        'phone_wa',
        'description',
        'facilities',
        'banner_image',
        'logo_image',
        'status',
        'archived',
    ];

    protected $casts = [
        'facilities' => 'array',
        'archived' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function courts()
    {
        return $this->hasMany(Court::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(TenantSubscription::class);
    }

    public function subscription()
    {
        return $this->hasOne(TenantSubscription::class)->latestOfMany();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
