<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'tenant_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Record an activity log entry conveniently
     */
    public static function record(string $action, string $description, ?Model $subject = null, array $properties = []): self
    {
        $user = Auth::user();
        $tenantId = null;

        if ($user && !empty($user->tenant_id)) {
            $tenantId = $user->tenant_id;
        } elseif ($subject && !empty($subject->tenant_id)) {
            $tenantId = $subject->tenant_id;
        } elseif ($subject instanceof Tenant) {
            $isForce = method_exists($subject, 'isForceDeleting') && $subject->isForceDeleting();
            $tenantId = (!$isForce && $subject->exists) ? $subject->id : null;
        }

        return self::create([
            'user_id' => $user?->id,
            'tenant_id' => $tenantId,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'properties' => !empty($properties) ? $properties : null,
            'ip_address' => Request::ip() ?? '127.0.0.1',
            'user_agent' => Request::userAgent() ?? 'System',
        ]);
    }
}
