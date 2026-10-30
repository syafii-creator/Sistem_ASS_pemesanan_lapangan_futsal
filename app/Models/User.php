<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'role',
        'phone',
        'avatar',
        'tenant_id',
        'archived',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'archived' => 'boolean',
        ];
    }

    /**
     * Otorisasi Role: Super Admin Platform
     */
    public function isSuperAdmin(): bool
    {
        return in_array($this->role, ['superadmin', 'super_admin', 'master_admin']);
    }

    /**
     * Otorisasi Role: Admin Tenant (Mitra Venue Pengelola)
     */
    public function isAdminTenant(): bool
    {
        return in_array($this->role, ['mitra', 'tenant', 'admin_tenant', 'admin']);
    }

    /**
     * Otorisasi Role: Member Tenant (Pemain / Pengguna Layanan)
     */
    public function isMemberTenant(): bool
    {
        return in_array($this->role, ['user', 'member_tenant', 'member', 'player']);
    }

    /**
     * Cek apakah pengguna memiliki salah satu role yang ditentukan
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $r) {
            if ($r === 'superadmin' && $this->isSuperAdmin()) return true;
            if ($r === 'mitra' && $this->isAdminTenant()) return true;
            if ($r === 'admin_tenant' && $this->isAdminTenant()) return true;
            if ($r === 'user' && $this->isMemberTenant()) return true;
            if ($r === 'member_tenant' && $this->isMemberTenant()) return true;
            if ($this->role === $r) return true;
        }

        return false;
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class, 'owner_user_id');
    }
}
