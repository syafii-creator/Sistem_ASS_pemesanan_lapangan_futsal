<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaasPackage extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'billing_cycle',
        'price',
        'max_courts',
        'features',
        'is_popular',
        'badge_text',
        'archived',
    ];

    protected $casts = [
        'features' => 'array',
        'is_popular' => 'boolean',
        'archived' => 'boolean',
    ];

    public function getHasAiAnalyticsAttribute()
    {
        $feat = is_array($this->features) ? json_encode($this->features) : (string)$this->features;
        return stripos($feat, 'Analitik') !== false || stripos($feat, 'Strategi') !== false || stripos($feat, 'Advisory') !== false || stripos($feat, 'Gemini') !== false;
    }

    public function subscriptions()
    {
        return $this->hasMany(TenantSubscription::class, 'package_id');
    }
}
