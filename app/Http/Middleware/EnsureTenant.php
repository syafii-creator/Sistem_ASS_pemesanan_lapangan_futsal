<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenant
{
    /**
     * Otorisasi Role: Admin Tenant (Mitra Venue Pengelola)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->isAdminTenant() && !in_array($user->role, ['tenant', 'mitra', 'admin_tenant'])) {
            if ($user->isSuperAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('player.dashboard')->with('error', 'Akses Dibatasi: Area ini khusus untuk Admin Tenant (Mitra Pengelola Venue).');
        }

        return $next($request);
    }
}
