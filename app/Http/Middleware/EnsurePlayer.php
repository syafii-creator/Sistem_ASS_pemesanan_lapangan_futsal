<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlayer
{
    /**
     * Otorisasi Role: Member Tenant (Pemain / Pengguna Layanan)
     * Akun Tenant dilarang mengakses Lobby Pemain, Open Slot Mabar, dan Sparing.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && ($user->isAdminTenant() || in_array($user->role, ['tenant', 'mitra', 'admin_tenant']))) {
            return redirect()->route('tenant.dashboard')->with('error', 'Akses Dibatasi: Akun Anda terdaftar sebagai Admin Tenant (Mitra Venue). Lobby Pemain, Mabar Open Slot, dan Sparing khusus untuk akun Member Tenant / Pemain.');
        }

        return $next($request);
    }
}
