<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use App\Models\Court;
use App\Models\SaasPackage;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Tampilkan Halaman Portal Auth (Login & Register)
     */
    public function portal(Request $request)
    {
        $saasPackages = SaasPackage::all();
        return view('auth.portal', compact('saasPackages'));
    }

    /**
     * Proses Login Real dengan Dukungan Simpan Sandi (Remember Me)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember', true);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            ActivityLog::record(
                action: 'user_login',
                description: "Pengguna {$user->name} ({$user->email}) berhasil login ke sistem.",
                subject: $user
            );

            if ($user->isAdminTenant() || in_array($user->role, ['tenant', 'mitra', 'admin_tenant'])) {
                return redirect()->route('tenant.dashboard')->with('success', 'Selamat datang kembali, ' . $user->name . '!');
            } elseif ($user->isSuperAdmin() || in_array($user->role, ['superadmin', 'super_admin'])) {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Pemilik Platform (Super Admin)!');
            } else {
                return redirect()->route('venues.index')->with('success', 'Selamat datang kembali, ' . $user->name . '!');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Proses Pendaftaran Akun Baru (Pemain atau Mitra Venue Self-Service Onboarding)
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'role' => 'required|in:pemain,tenant,mitra',
            'venue_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau langsung masuk.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        $userRole = in_array($validated['role'], ['tenant', 'mitra']) ? 'mitra' : 'user';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $userRole,
            'password' => Hash::make($validated['password']),
        ]);

        // Self-Service Onboarding Mitra Tenant: Buatkan profil venue, langganan trial, dan unit lapangan awal
        if ($userRole === 'mitra') {
            $venueName = $request->filled('venue_name') ? $request->venue_name : ($user->name . ' Futsal Arena');
            $venueCity = $request->filled('city') ? $request->city : 'Bandung';
            $venueSlug = Str::slug($venueName) . '-' . rand(100, 999);

            $tenant = Tenant::create([
                'owner_user_id' => $user->id,
                'name' => $venueName,
                'slug' => $venueSlug,
                'city' => $venueCity,
                'address' => $request->input('address', 'Jl. Utama No. 1, ' . $venueCity),
                'phone_wa' => $user->phone,
                'status' => 'active',
                'facilities' => ['Parkir Luas', 'Musholla', 'Kantin', 'Loker'],
            ]);

            $user->update(['tenant_id' => $tenant->id]);

            // Paket Langganan SaaS Awal (Trial 7 Hari Aktif)
            $pkgId = $request->input('package_id');
            $pkg = $pkgId ? SaasPackage::find($pkgId) : SaasPackage::first();

            TenantSubscription::create([
                'tenant_id' => $tenant->id,
                'package_id' => $pkg?->id ?? 1,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addDays(7),
                'status' => 'active',
                'price_paid' => 0,
            ]);

            // Unit lapangan awal siap pakai
            Court::create([
                'tenant_id' => $tenant->id,
                'name' => 'Lapangan 1 (Interlock Vinyl)',
                'surface_type' => 'Interlock FIFA',
                'price_regular' => 120000,
                'price_peak' => 150000,
                'status' => 'active',
            ]);
        }

        ActivityLog::record(
            action: 'user_registered',
            description: "Pendaftaran akun baru {$user->name} sebagai role " . strtoupper($userRole),
            subject: $user
        );

        // Login otomatis setelah registrasi
        Auth::login($user, true);
        $request->session()->regenerate();

        if ($userRole === 'mitra') {
            return redirect()->route('tenant.dashboard')->with('success', 'Onboarding Mitra berhasil! Selamat menikmati 7 Hari Free Trial SaaS.');
        }

        return redirect()->route('venues.index')->with('success', 'Akun berhasil dibuat! Selamat datang di LapanganKita, ' . $user->name . '.');
    }

    /**
     * Logout Pengguna
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::record(
                action: 'user_logout',
                description: "Pengguna {$user->name} telah logout dari sesi aplikasi.",
                subject: $user
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.portal')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Redirect ke Halaman Login Google (OAuth SSO)
     */
    public function redirectToGoogle()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Callback dari Google OAuth setelah pengguna memberikan izin
     */
    public function handleGoogleCallback(Request $request)
    {
        // Cek jika pengguna menekan cancel / batal di layar Google
        if ($request->has('error')) {
            return redirect()->route('auth.portal')->with('error', 'Login Google dibatalkan atau izin akses ditolak.');
        }

        try {
            // Gunakan stateless() agar terhindar dari isu session cookie cross-site di localhost
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->stateless()->user();

            // 1. Cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Update google_id dan avatar jika belum ada
                $updateData = [];
                if (!$user->google_id) {
                    $updateData['google_id'] = $googleUser->getId();
                }
                if (!$user->avatar && $googleUser->getAvatar()) {
                    $updateData['avatar'] = $googleUser->getAvatar();
                }
                if (!empty($updateData)) {
                    $user->update($updateData);
                }
            } else {
                // Buat user baru (pemain) otomatis
                $user = User::create([
                    'name' => $googleUser->getName() ?? 'Pemain Google',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'role' => 'pemain',
                    'phone' => '08' . rand(1000000000, 9999999999),
                    'password' => null,
                ]);

                ActivityLog::record(
                    action: 'user_registered_google',
                    description: "Pendaftaran akun baru {$user->name} via Google SSO",
                    subject: $user
                );
            }

            Auth::login($user, true);
            $request->session()->regenerate();

            ActivityLog::record(
                action: 'user_login_google',
                description: "Pengguna {$user->name} berhasil login via Google SSO.",
                subject: $user
            );

            if ($user->isAdminTenant() || in_array($user->role, ['tenant', 'mitra', 'admin_tenant'])) {
                return redirect()->route('tenant.dashboard')->with('success', 'Selamat datang kembali, ' . $user->name . '!');
            } elseif ($user->isSuperAdmin() || in_array($user->role, ['superadmin', 'super_admin'])) {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Super Admin!');
            } else {
                return redirect()->route('player.dashboard')->with('success', 'Berhasil masuk dengan akun Google! Selamat datang, ' . $user->name . '.');
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
            ]);
            $errorMessage = $e->getMessage() ?: 'Sesi autentikasi kedaluwarsa atau dibatalkan. Silakan coba klik tombol kembali.';
            return redirect()->route('auth.portal')->with('error', 'Gagal masuk dengan akun Google: ' . $errorMessage);
        }
    }
}
