<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Otorisasi Role: Super Admin Platform
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->isSuperAdmin() && !in_array($user->role, ['superadmin', 'super_admin'])) {
            abort(403, 'Akses Ditolak: Halaman ini khusus untuk Super Admin Platform LapanganKita.');
        }

        return $next($request);
    }
}
