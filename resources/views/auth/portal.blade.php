@extends('layouts.app')

@section('title', 'Portal Akun - Masuk & Daftar LapanganKita')
@section('meta_description', 'Masuk ke akun LapanganKita sebagai Pemain, Mitra Pemilik Venue, atau Super Admin dengan fitur simpan sandi otomatis.')

@section('content')
<div class="py-12 md:py-20 flex items-center justify-center px-4 relative">
  <!-- Glow background -->
  <div class="absolute w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[120px] pointer-events-none"></div>

  <div class="w-full max-w-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-10 shadow-2xl relative z-10">
    
    <!-- Header -->
    <div class="text-center mb-8 space-y-2">
      <div class="w-16 h-16 rounded-2xl overflow-hidden border border-emerald-500/30 bg-slate-950 shadow-xl shadow-emerald-500/20 mx-auto flex items-center justify-center p-1">
        <img src="{{ asset('images/logo-lk-concept-1.jpg') }}" alt="Logo LapanganKita" class="w-full h-full object-cover rounded-xl">
      </div>
      <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">Portal Akun LapanganKita</h1>
      <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Masuk ke akun Anda atau daftarkan akun baru untuk mulai bermain</p>
    </div>

    <!-- Alert Notifikasi Sukses / Error -->
    @if(session('success'))
      <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-xs flex items-center gap-2.5">
        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 shrink-0"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    @if($errors->any())
      <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-300 text-xs space-y-1">
        <div class="font-bold flex items-center gap-2">
          <i data-lucide="alert-circle" class="w-4 h-4 text-rose-500 shrink-0"></i>
          <span>Periksa kembali data yang kamu masukkan:</span>
        </div>
        <ul class="list-disc list-inside pl-1 text-[11px] text-rose-600 dark:text-rose-400">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- Tab Selector: Masuk vs Daftar Baru -->
    <div class="flex p-1 bg-slate-100 dark:bg-slate-800 rounded-xl mb-6">
      <button type="button" onclick="switchTab('login')" id="tab-btn-login" class="flex-1 py-2.5 rounded-lg text-xs font-bold transition bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm">
        Masuk Akun
      </button>
      <button type="button" onclick="switchTab('register')" id="tab-btn-register" class="flex-1 py-2.5 rounded-lg text-xs font-bold transition text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
        Daftar Akun Baru
      </button>
    </div>

    <!-- 1. FORM LOGIN DENGAN AUTO-SAVE PASSWORD BROWSER & REMEMBER ME -->
    <form action="{{ route('auth.login') }}" method="POST" id="form-login" class="space-y-4">
      @csrf

      <div>
        <label for="login-email" class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
          Email Pengguna <span class="text-rose-500 font-bold">*</span>
        </label>
        <div class="relative">
          <i data-lucide="mail" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5"></i>
          <input type="email" 
                 name="email" 
                 id="login-email" 
                 autocomplete="username" 
                 required 
                 autofocus
                 value="{{ old('email') }}" 
                 placeholder="nama@email.com" 
                 class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl pl-10 pr-4 py-3 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>
      </div>

      <div>
        <div class="flex justify-between items-center mb-1">
          <label for="login-password" class="text-xs font-bold text-slate-700 dark:text-slate-300">
            Kata Sandi <span class="text-rose-500 font-bold">*</span>
          </label>
          <a href="#" class="text-[11px] text-emerald-600 dark:text-emerald-400 hover:underline">Lupa Sandi?</a>
        </div>
        <div class="relative">
          <i data-lucide="lock" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5"></i>
          <input type="password" 
                 name="password" 
                 id="login-password" 
                 autocomplete="current-password" 
                 required 
                 placeholder="Masukkan kata sandi..." 
                 class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl pl-10 pr-12 py-3 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
          <button type="button" onclick="togglePasswordVisibility('login-password')" class="absolute right-3.5 top-3.5 text-slate-400 hover:text-slate-200 cursor-pointer">
            <i data-lucide="eye" class="w-4 h-4"></i>
          </button>
        </div>
      </div>

      <!-- FITUR SIMPAN SANDI & INGAT SAYA (REMEMBER ME) -->
      <div class="flex items-center justify-between pt-1">
        <label class="flex items-center gap-2 cursor-pointer select-none">
          <input type="checkbox" 
                 name="remember" 
                 value="1" 
                 checked 
                 class="w-4 h-4 rounded text-emerald-500 focus:ring-emerald-500 border-slate-300 dark:border-slate-700">
          <span class="text-xs font-medium text-slate-700 dark:text-slate-300">
            Simpan kata sandi & tetap masuk
          </span>
        </label>
        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10 px-2 py-0.5 rounded-full">Auto-Save Aktif</span>
      </div>

      <button type="submit" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-sm rounded-xl transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 mt-4 cursor-pointer">
        <span>Masuk Sekarang</span>
        <i data-lucide="arrow-right" class="w-4 h-4"></i>
      </button>

      <!-- DIVIDER ATAU -->
      <div class="relative flex items-center justify-center my-4">
        <div class="border-t border-slate-200 dark:border-slate-800 w-full"></div>
        <span class="bg-white dark:bg-slate-900 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider shrink-0">atau masuk dengan</span>
        <div class="border-t border-slate-200 dark:border-slate-800 w-full"></div>
      </div>

      <!-- GOOGLE SSO LOGIN BUTTON -->
      <a href="{{ route('auth.google') }}" class="w-full py-3 bg-white dark:bg-slate-850 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-extrabold text-xs sm:text-sm rounded-xl transition border border-slate-200 dark:border-slate-700 flex items-center justify-center gap-3 shadow-sm hover:shadow-md cursor-pointer group">
        <svg class="w-4 h-4 shrink-0 transition transform group-hover:scale-110" viewBox="0 0 24 24">
          <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
          <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
          <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
          <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
        </svg>
        <span>Masuk dengan Google</span>
      </a>
    </form>

    <!-- 2. FORM REGISTER AKUN BARU DENGAN SELF-SERVICE ONBOARDING MITRA -->
    <form action="{{ route('auth.register') }}" method="POST" id="form-register" class="space-y-4 hidden">
      @csrf

      <div>
        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
          Daftar Sebagai <span class="text-rose-500 font-bold">*</span>
        </label>
        <div class="grid grid-cols-2 gap-2">
          <label id="role-label-pemain" class="p-2.5 rounded-xl border border-emerald-500 bg-emerald-500/10 flex items-center gap-2 cursor-pointer">
            <input type="radio" name="role" value="pemain" checked onchange="toggleVenueFields(false)" class="text-emerald-500">
            <span class="text-xs font-bold text-slate-900 dark:text-white">Pemain / Komunitas</span>
          </label>
          <label id="role-label-tenant" class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center gap-2 cursor-pointer">
            <input type="radio" name="role" value="tenant" onchange="toggleVenueFields(true)" class="text-emerald-500">
            <span class="text-xs font-bold text-slate-900 dark:text-white">Pengelola / Pemilik Venue</span>
          </label>
        </div>
      </div>

      <div>
        <label for="reg-name" class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
          Nama Lengkap <span class="text-rose-500 font-bold">*</span>
        </label>
        <input type="text" 
               name="name" 
               id="reg-name" 
               required 
               placeholder="Contoh: Andi Pratama" 
               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
      </div>

      <div>
        <label for="reg-email" class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
          Alamat Email <span class="text-rose-500 font-bold">*</span>
        </label>
        <input type="email" 
               name="email" 
               id="reg-email" 
               autocomplete="username" 
               required 
               placeholder="nama@email.com" 
               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
      </div>

      <div>
        <label for="reg-phone" class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
          Nomor WhatsApp Aktif <span class="text-rose-500 font-bold">*</span>
        </label>
        <input type="tel" 
               name="phone" 
               id="reg-phone" 
               required 
               placeholder="Contoh: 081234567890" 
               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
      </div>

      <!-- SELF-SERVICE ONBOARDING: FIELDS KHUSUS MITRA VENUE BARU -->
      <div id="tenant-onboard-fields" class="space-y-4 pt-3 border-t border-slate-200 dark:border-slate-800 hidden">
        <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center gap-2.5">
          <i data-lucide="sparkles" class="w-4 h-4 text-emerald-500 shrink-0"></i>
          <span class="text-[11px] text-emerald-700 dark:text-emerald-300 font-bold">Pendaftaran Venue Futsal: Gratis Coba 7 Hari Penuh!</span>
        </div>

        <div>
          <label for="reg-venue-name" class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
            Nama Tempat / Venue Futsal <span class="text-rose-500 font-bold">*</span>
          </label>
          <input type="text" 
                 name="venue_name" 
                 id="reg-venue-name" 
                 placeholder="Contoh: Siliwangi Futsal Arena" 
                 class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label for="reg-city" class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
              Kota Venue <span class="text-rose-500 font-bold">*</span>
            </label>
            <input type="text" 
                   name="city" 
                   id="reg-city" 
                   placeholder="Contoh: Bandung" 
                   class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
          </div>
          <div>
            <label for="reg-package" class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
              Pilihan Paket Langganan <span class="text-rose-500 font-bold">*</span>
            </label>
            <select name="package_id" id="reg-package" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-3 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold">
              @if(isset($saasPackages))
                @foreach($saasPackages as $sp)
                  <option value="{{ $sp->id }}">{{ $sp->name }} - Rp {{ number_format($sp->price, 0, ',', '.') }} ({{ ucfirst($sp->billing_cycle) }})</option>
                @endforeach
              @else
                <option value="1">Free Trial 7 Hari (Starter Bulanan)</option>
              @endif
            </select>
          </div>
        </div>
      </div>

      <div>
        <label for="reg-password" class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
          Kata Sandi Akun <span class="text-rose-500 font-bold">*</span>
        </label>
        <div class="relative">
          <input type="password" 
                 name="password" 
                 id="reg-password" 
                 autocomplete="new-password" 
                 required 
                 minlength="6"
                 placeholder="Tentukan kata sandi akun (min. 6 karakter)..." 
                 class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 pr-12 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
          <button type="button" onclick="togglePasswordVisibility('reg-password')" class="absolute right-3.5 top-3.5 text-slate-400 hover:text-slate-200 cursor-pointer">
            <i data-lucide="eye" class="w-4 h-4"></i>
          </button>
        </div>
        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Bebas tentukan kata sandi rahasia Anda sendiri (minimal 6 karakter).</p>
      </div>

      <button type="submit" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-sm rounded-xl transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 mt-4 cursor-pointer">
        <span>Daftar & Onboarding Sekarang</span>
        <i data-lucide="check" class="w-4 h-4"></i>
      </button>

      <!-- DIVIDER ATAU -->
      <div class="relative flex items-center justify-center my-4">
        <div class="border-t border-slate-200 dark:border-slate-800 w-full"></div>
        <span class="bg-white dark:bg-slate-900 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider shrink-0">atau daftar instan dengan</span>
        <div class="border-t border-slate-200 dark:border-slate-800 w-full"></div>
      </div>

      <!-- GOOGLE SSO REGISTER BUTTON -->
      <a href="{{ route('auth.google') }}" class="w-full py-3 bg-white dark:bg-slate-850 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-extrabold text-xs sm:text-sm rounded-xl transition border border-slate-200 dark:border-slate-700 flex items-center justify-center gap-3 shadow-sm hover:shadow-md cursor-pointer group">
        <svg class="w-4 h-4 shrink-0 transition transform group-hover:scale-110" viewBox="0 0 24 24">
          <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
          <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
          <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
          <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
        </svg>
        <span>Daftar dengan Akun Google</span>
      </a>
    </form>

  </div>
</div>
@endsection

@section('scripts')
<script>
  function switchTab(tab) {
    const btnLogin = document.getElementById('tab-btn-login');
    const btnReg = document.getElementById('tab-btn-register');
    const formLogin = document.getElementById('form-login');
    const formReg = document.getElementById('form-register');

    if (tab === 'login') {
      btnLogin.className = 'flex-1 py-2.5 rounded-lg text-xs font-bold transition bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm';
      btnReg.className = 'flex-1 py-2.5 rounded-lg text-xs font-bold transition text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white';
      formLogin.classList.remove('hidden');
      formReg.classList.add('hidden');
      document.getElementById('login-email').focus();
    } else {
      btnReg.className = 'flex-1 py-2.5 rounded-lg text-xs font-bold transition bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm';
      btnLogin.className = 'flex-1 py-2.5 rounded-lg text-xs font-bold transition text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white';
      formReg.classList.remove('hidden');
      formLogin.classList.add('hidden');
      document.getElementById('reg-name').focus();
    }
    if (window.lucide) lucide.createIcons();
  }

  function toggleVenueFields(isTenant) {
    const fields = document.getElementById('tenant-onboard-fields');
    const labelPemain = document.getElementById('role-label-pemain');
    const labelTenant = document.getElementById('role-label-tenant');
    if (isTenant) {
      fields.classList.remove('hidden');
      labelTenant.className = 'p-2.5 rounded-xl border border-emerald-500 bg-emerald-500/10 flex items-center gap-2 cursor-pointer';
      labelPemain.className = 'p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center gap-2 cursor-pointer';
    } else {
      fields.classList.add('hidden');
      labelPemain.className = 'p-2.5 rounded-xl border border-emerald-500 bg-emerald-500/10 flex items-center gap-2 cursor-pointer';
      labelTenant.className = 'p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 flex items-center gap-2 cursor-pointer';
    }
    if (window.lucide) lucide.createIcons();
  }

  function togglePasswordVisibility(id) {
    const el = document.getElementById(id);
    if (el.type === 'password') {
      el.type = 'text';
    } else {
      el.type = 'password';
    }
  }

  // Cek a
  // ah ada query mode register dari URL misal ?mode=register
  document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('mode') === 'register') {
      switchTab('register');
    }
  });
</script>
@endsection
