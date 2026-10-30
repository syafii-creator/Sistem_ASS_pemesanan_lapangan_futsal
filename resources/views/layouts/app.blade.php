<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'LapanganKita - Platform Reservasi Lapangan Futsal & Ekosistem Olahraga')</title>
  <meta name="description" content="@yield('meta_description', 'Sewa lapangan futsal online real-time, cari teman mabar di Open Slot, dan tantang tim lawan di Lobby Sparing.')">
  
  <!-- Anti-Flicker Theme Init Script -->
  <script>
    (function () {
      const savedTheme = localStorage.getItem('lapangankita_theme');
      if (savedTheme === 'light') {
        document.documentElement.classList.remove('dark');
      } else {
        document.documentElement.classList.add('dark');
      }
    })();
  </script>

  <!-- Favicon -->
  <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-lk-concept-1.jpg') }}">

  <!-- Google Fonts: Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <!-- jQuery & DataTables CDN (Sesuai Ketentuan Tugas Stage 1) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="{{ asset('css/datatable-custom.css') }}">
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="{{ asset('js/datatable-init.js') }}"></script>
  
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
          },
          colors: {
            slate: {
              750: '#283548',
              850: '#151f32',
              950: '#020617',
            },
            brand: {
              50: '#ecfdf5',
              100: '#d1fae5',
              400: '#34d399',
              500: '#10b981',
              600: '#059669',
              700: '#047857',
              800: '#065f46',
              900: '#064e3b',
              950: '#022c22',
            }
          }
        }
      }
    }
  </script>

  <style>
    /* Smooth theme transitions */
    html {
      transition: background-color 0.2s ease, color 0.2s ease;
    }
    body {
      transition: background-color 0.2s ease, color 0.2s ease;
    }

    .glass-nav {
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
    }
    .dark .glass-nav {
      background: rgba(15, 23, 42, 0.88);
      border-color: rgba(51, 65, 85, 0.6);
    }
    html:not(.dark) .glass-nav {
      background: rgba(255, 255, 255, 0.92);
      border-color: rgba(226, 232, 240, 0.8);
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }

    .dark .gradient-text {
      background: linear-gradient(135deg, #ffffff 0%, #a7f3d0 50%, #34d399 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    html:not(.dark) .gradient-text {
      background: linear-gradient(135deg, #0f172a 0%, #059669 60%, #10b981 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    /* Select option dropdowns across browsers */
    select option {
      background-color: #ffffff;
      color: #0f172a;
    }
    .dark select option {
      background-color: #0f172a;
      color: #f8fafc;
    }

    /* Scrollbars for light and dark */
    ::-webkit-scrollbar {
      width: 7px;
      height: 7px;
    }
    ::-webkit-scrollbar-track {
      background: transparent;
    }
    .dark ::-webkit-scrollbar-thumb {
      background: rgba(148, 163, 184, 0.25);
      border-radius: 9999px;
    }
    .dark ::-webkit-scrollbar-thumb:hover {
      background: rgba(148, 163, 184, 0.4);
    }
    html:not(.dark) ::-webkit-scrollbar-thumb {
      background: rgba(100, 116, 139, 0.3);
      border-radius: 9999px;
    }
    html:not(.dark) ::-webkit-scrollbar-thumb:hover {
      background: rgba(100, 116, 139, 0.5);
    }
  </style>
  @yield('styles')
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 font-sans antialiased min-h-screen selection:bg-emerald-500 selection:text-black transition-colors duration-300 flex flex-col justify-between">

  <!-- ========================================================================= -->
  <!-- NAVBAR UTAMA (ROLE UMUM & PEMAIN)                                        -->
  <!-- ========================================================================= -->
  <nav class="sticky top-0 z-40 w-full border-b border-slate-200 dark:border-slate-800/80 glass-nav transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
      
      <!-- Left Column: Brand Logo -->
      <div class="flex items-center justify-start flex-1 shrink-0">
        <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
          <div class="w-11 h-11 rounded-2xl overflow-hidden border border-emerald-500/30 bg-slate-950 shadow-lg shadow-emerald-500/25 group-hover:scale-105 transition-transform flex items-center justify-center shrink-0">
            <img src="{{ asset('images/logo-lk-concept-1.jpg') }}" alt="Logo LapanganKita" class="w-full h-full object-cover">
          </div>
          <div>
            <span class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white block">Lapangan<span class="text-emerald-500 dark:text-emerald-400">Kita</span></span>
            <span class="text-[10px] font-semibold tracking-wider text-slate-500 dark:text-slate-400 uppercase -mt-1 block">Futsal Reservation</span>
          </div>
        </a>
      </div>

      <!-- Center Column: Desktop Navigation Menu (Dead-Center, Symmetrical Spacing) -->
      <div class="hidden md:flex items-center justify-center shrink-0 gap-1 lg:gap-2 text-xs lg:text-sm font-semibold text-slate-600 dark:text-slate-300">
        <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('home') ? 'text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10' : 'hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' }} transition">
          Beranda
        </a>
        
        @auth
          @if(auth()->user()->role === 'superadmin')
            <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('admin.dashboard') ? 'text-amber-500 font-bold bg-amber-500/10' : 'hover:text-amber-500 hover:bg-slate-100 dark:hover:bg-slate-800/60' }} transition">
              Master Admin
            </a>
            <a href="{{ route('admin.analytics') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('admin.analytics') ? 'text-amber-500 font-bold bg-amber-500/10' : 'hover:text-amber-500 hover:bg-slate-100 dark:hover:bg-slate-800/60' }} transition">
              Analitik Platform
            </a>
            <a href="{{ route('tenant.dashboard') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('tenant.*') ? 'text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10' : 'hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' }} transition">
              Mode Mitra
            </a>
            <a href="{{ route('venues.index') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 transition">
              Katalog Venue
            </a>
          @elseif(auth()->user()->role === 'tenant')
            <a href="{{ route('tenant.dashboard') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('tenant.dashboard') ? 'text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10' : 'hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' }} transition">
              Dashboard Venue
            </a>
            <a href="{{ route('tenant.analytics') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('tenant.analytics') ? 'text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10' : 'hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' }} transition flex items-center gap-1.5">
              <i data-lucide="bar-chart-2" class="w-3.5 h-3.5 text-emerald-500"></i>
              <span>Analitik Bisnis</span>
            </a>
            <a href="{{ route('tenant.profile') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('tenant.profile') ? 'text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10' : 'hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' }} transition">
              Profil & Foto
            </a>
            <a href="{{ route('tenant.courts') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('tenant.courts') ? 'text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10' : 'hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' }} transition">
              Jadwal & Lapangan
            </a>
            <a href="{{ route('tenant.billing') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('tenant.billing') ? 'text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10' : 'hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' }} transition">
              Paket Langganan
            </a>
          @else
            <!-- Menu Khusus Pemain yang Sudah Login -->
            <a href="{{ route('player.dashboard') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('player.dashboard') ? 'text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10' : 'hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' }} transition">
              Dashboard Saya
            </a>
            <a href="{{ route('venues.index') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('venues.*') ? 'text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10' : 'hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' }} transition">
              Katalog Venue
            </a>
            <a href="{{ route('community.open-slot') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('community.open-slot') ? 'text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10' : 'hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' }} transition">
              Mabar Open Slot
            </a>
            <a href="{{ route('community.sparring') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('community.sparring') ? 'text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10' : 'hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' }} transition">
              Lobby Sparing
            </a>
          @endif
        @else
          <!-- Menu untuk Tamu / Belum Login (Symmetrical Capsule Spacing) -->
          <a href="{{ route('venues.index') }}" class="px-3.5 py-2 rounded-xl whitespace-nowrap {{ request()->routeIs('venues.*') ? 'text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10' : 'hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60' }} transition">
            Katalog Venue
          </a>
          <a href="{{ route('home') }}#keunggulan" class="px-3.5 py-2 rounded-xl whitespace-nowrap hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 transition">
            Keunggulan & Fitur
          </a>
          <a href="{{ route('home') }}#paket-saas" class="px-3.5 py-2 rounded-xl whitespace-nowrap hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 transition">
            Paket Langganan
          </a>
        @endauth
      </div>

      <!-- Right Column: Action Controls (Balanced flex-1 to keep center aligned) -->
      <div class="flex items-center justify-end flex-1 shrink-0 gap-2.5 sm:gap-3">
        
        <!-- Toggle Dark/Light Mode -->
        <button onclick="toggleTheme()" id="theme-toggle-btn" title="Ganti Mode Terang / Gelap" class="p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition flex items-center justify-center shadow-sm">
          <i id="icon-sun" data-lucide="sun" class="w-4 h-4 text-amber-400 hidden dark:block"></i>
          <i id="icon-moon" data-lucide="moon" class="w-4 h-4 text-slate-700 block dark:hidden"></i>
        </button>

        @auth
          <!-- Tampilan Jika Sudah Login -->
          @php
            $profileRoute = auth()->user()->role === 'superadmin' ? route('admin.dashboard') : (auth()->user()->role === 'tenant' ? route('tenant.dashboard') : route('player.dashboard'));
          @endphp
          <a href="{{ $profileRoute }}" class="pl-2 pr-3.5 py-1.5 rounded-2xl text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800/90 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-100 border border-slate-200 dark:border-slate-700 transition flex items-center gap-2.5 shadow-sm group">
            <div class="relative shrink-0">
              <div class="w-7 h-7 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-slate-950 font-black text-[11px] flex items-center justify-center shadow-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
              </div>
              <span class="w-2 h-2 rounded-full bg-emerald-400 border-2 border-white dark:border-slate-900 absolute -bottom-0.5 -right-0.5"></span>
            </div>
            <div class="flex flex-col text-left leading-tight">
              <span class="max-w-[110px] truncate text-xs font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition">{{ auth()->user()->name }}</span>
              <span class="text-[9px] font-semibold text-emerald-600 dark:text-emerald-400">
                @if(auth()->user()->role === 'superadmin')
                  Admin Pusat
                @elseif(auth()->user()->role === 'tenant')
                  Pengelola Venue
                @else
                  Pemain Aktif
                @endif
              </span>
            </div>
          </a>

          <form action="{{ route('auth.logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" title="Keluar Akun" class="p-2.5 rounded-xl text-slate-500 hover:text-rose-500 hover:bg-rose-500/10 border border-slate-200 dark:border-slate-700 transition flex items-center justify-center">
              <i data-lucide="log-out" class="w-4 h-4"></i>
            </button>
          </form>
        @else
          <!-- Tampilan Jika Tamu / Belum Login -->
          <a href="{{ route('auth.portal') }}" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white bg-slate-100 hover:bg-slate-200 dark:bg-transparent dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700/60 transition flex items-center gap-2">
            <i data-lucide="log-in" class="w-4 h-4 text-emerald-500 dark:text-emerald-400"></i>
            <span>Masuk</span>
          </a>
          
          <a href="{{ route('auth.portal') }}?mode=register&role=tenant" class="px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 shadow-lg shadow-emerald-500/20 transition transform hover:-translate-y-0.5 flex items-center gap-2">
            <i data-lucide="store" class="w-4 h-4"></i>
            <span class="hidden sm:inline">Daftarkan Venue</span>
            <span class="sm:hidden">Venue</span>
          </a>
        @endauth
      </div>

    </div>
  </nav>

  <!-- ========================================================================= -->
  <!-- MAIN CONTENT AREA                                                         -->
  <!-- ========================================================================= -->
  <main class="flex-1">
    @if(session('info'))
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="p-4 rounded-2xl bg-blue-500/10 border border-blue-500/30 text-blue-600 dark:text-blue-400 text-xs font-bold flex items-center gap-3 shadow-sm">
          <i data-lucide="info" class="w-5 h-5 shrink-0 text-blue-500"></i>
          <span>{{ session('info') }}</span>
        </div>
      </div>
    @endif

    @if(session('warning'))
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400 text-xs font-bold flex items-center gap-3 shadow-sm">
          <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0 text-amber-500"></i>
          <span>{{ session('warning') }}</span>
        </div>
      </div>
    @endif

    @if(session('error'))
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-bold flex items-center gap-3 shadow-sm">
          <i data-lucide="shield-alert" class="w-5 h-5 shrink-0 text-rose-500"></i>
          <span>{{ session('error') }}</span>
        </div>
      </div>
    @endif

    @if(session('success'))
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center gap-3 shadow-sm">
          <i data-lucide="check-circle" class="w-5 h-5 shrink-0 text-emerald-500"></i>
          <span>{{ session('success') }}</span>
        </div>
      </div>
    @endif

    @yield('content')
  </main>

  <!-- ========================================================================= -->
  <!-- FOOTER TERSTRUKTUR & RAPI                                                 -->
  <!-- ========================================================================= -->
  <footer class="bg-white dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800/80 pt-16 pb-12 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Top 4 Columns Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10 pb-12">
        
        <!-- Kolom 1: Brand & Profil (4 Cols) -->
        <div class="lg:col-span-3 space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
              <i data-lucide="trophy" class="w-5 h-5"></i>
            </div>
            <div>
              <span class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white block">Lapangan<span class="text-emerald-500 dark:text-emerald-400">Kita</span></span>
              <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block -mt-1">Futsal Ecosystem</span>
            </div>
          </div>
          
          <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm leading-relaxed max-w-sm">
            Platform reservasi lapangan futsal terintegrasi dan solusi SaaS manajemen venue nomor satu di Indonesia. Cari lawan tanding, mabar patungan, dan pesan lapangan bebas ribet.
          </p>

          <!-- Social Media Icons (Clean Inline SVGs) -->
          <div class="flex items-center gap-2.5 pt-1">
            <!-- Ins  ram -->
            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" title="Instagram LapanganKita" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-emerald-500 hover:text-slate-950 dark:bg-slate-900 dark:hover:bg-emerald-500 dark:hover:text-slate-950 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 transition flex items-center justify-center shadow-sm">
              <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
              </svg>
            </a>

            <!-- Facebook -->
            <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" title="Facebook LapanganKita" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-emerald-500 hover:text-slate-950 dark:bg-slate-900 dark:hover:bg-emerald-500 dark:hover:text-slate-950 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 transition flex items-center justify-center shadow-sm">
              <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
              </svg>
            </a>

            <!-- WhatsApp -->
            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" title="WhatsApp Customer Service" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-emerald-500 hover:text-slate-950 dark:bg-slate-900 dark:hover:bg-emerald-500 dark:hover:text-slate-950 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-800 transition flex items-center justify-center shadow-sm">
              <svg class="w-4 h-4 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
              </svg>
            </a>
          </div>
        </div>

        <!-- Kolom 2: Eksplorasi Keunggulan Platform (3 Cols) -->
        <div class="lg:col-span-3 space-y-3">
          <h4 class="font-extrabold text-slate-900 dark:text-white text-xs uppercase tracking-wider">Eksplorasi Platform</h4>
          <ul class="space-y-2.5 text-xs text-slate-600 dark:text-slate-400 font-medium">
            <li><a href="{{ route('home') }}#beranda" class="hover:text-emerald-500 transition flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3 text-emerald-500"></i><span>Beranda Utama</span></a></li>
            <li><a href="{{ route('home') }}#keunggulan" class="hover:text-emerald-500 transition flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3 text-emerald-500"></i><span>Keunggulan & Fitur</span></a></li>
            <li><a href="{{ route('home') }}#paket-saas" class="hover:text-emerald-500 transition flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3 text-emerald-500"></i><span>Paket Langganan SaaS</span></a></li>
            @auth
              <li><a href="{{ route('player.dashboard') }}" class="hover:text-emerald-500 transition flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3 text-emerald-500"></i><span>Dashboard & Tiket Saya</span></a></li>
              <li><a href="{{ route('venues.index') }}" class="hover:text-emerald-500 transition flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3 text-emerald-500"></i><span>Katalog Venue Futsal</span></a></li>
            @else
              <li><a href="{{ route('auth.portal') }}" class="hover:text-emerald-500 transition flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3 text-emerald-500"></i><span>Masuk Akun Pemain</span></a></li>
              <li><a href="{{ route('auth.portal') }}?mode=register" class="hover:text-emerald-500 transition flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3 text-emerald-500"></i><span>Daftar Akun Baru</span></a></li>
            @endauth
          </ul>
        </div>

        <!-- Kolom 3: Pemilik Venue & Bisnis (3 Cols) -->
        <div class="lg:col-span-3 space-y-3">
          <h4 class="font-extrabold text-slate-900 dark:text-white text-xs uppercase tracking-wider">Pengelola Venue</h4>
          <ul class="space-y-2.5 text-xs text-slate-600 dark:text-slate-400 font-medium">
            <li><a href="{{ route('auth.portal') }}?mode=register&role=tenant" class="hover:text-emerald-500 transition flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3 text-emerald-500"></i><span>Daftarkan Venue Futsal</span></a></li>
            <li><a href="{{ route('home') }}#paket-saas" class="hover:text-emerald-500 transition flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3 text-emerald-500"></i><span>Paket Langganan SaaS</span></a></li>
            <li><a href="{{ route('tenant.dashboard') }}" class="hover:text-emerald-500 transition flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3 text-emerald-500"></i><span>Dashboard Venue</span></a></li>
            <li><a href="{{ route('tenant.profile') }}" class="hover:text-emerald-500 transition flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3 text-emerald-500"></i><span>Ganti Foto & Logo Venue</span></a></li>
            <li><a href="{{ route('tenant.courts') }}" class="hover:text-emerald-500 transition flex items-center gap-2"><i data-lucide="chevron-right" class="w-3 h-3 text-emerald-500"></i><span>Kelola Lapangan & Tarif</span></a></li>
          </ul>
        </div>

        <!-- Kolom 4: Bantuan & Kontak CS (2 Cols) -->
        <div class="lg:col-span-3 space-y-3">
          <h4 class="font-extrabold text-slate-900 dark:text-white text-xs uppercase tracking-wider">Kontak &amp; Bantuan</h4>
          <div class="space-y-2.5 text-xs text-slate-600 dark:text-slate-400">
            <a href="https://wa.me/6281234567890" target="_blank" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center gap-3 hover:border-emerald-500 transition">
              <i data-lucide="phone" class="w-4 h-4 text-emerald-500 shrink-0"></i>
              <div class="min-w-0">
                <span class="text-[10px] text-slate-400 block font-normal">WhatsApp CS</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 block">0812-3456-7890</span>
              </div>
            </a>

            <a href="mailto:support@lapangankita.id" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center gap-3 hover:border-emerald-500 transition">
              <i data-lucide="mail" class="w-4 h-4 text-emerald-500 shrink-0"></i>
              <div class="min-w-0">
                <span class="text-[10px] text-slate-400 block font-normal">Email Support</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 block truncate">support@lapangankita.id</span>
              </div>
            </a>
          </div>
        </div>

      </div>

      <!-- Bottom Bar: Hak Cipta, Badge Metode Pembayaran, & Links Legal -->
      <div class="pt-8 border-t border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500 dark:text-slate-400">
        <div>
          <p>© 2026 <strong class="text-slate-700 dark:text-slate-300">LapanganKita Indonesia</strong>. Seluruh hak cipta dilindungi undang-undang.</p>
        </div>

        <!-- Badges Pembayaran Resmi -->
        <div class="flex items-center gap-1.5 flex-wrap justify-center text-[10px] font-bold">
          <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400">QRIS</span>
          <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400">BCA VA</span>
          <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400">Mandiri VA</span>
          <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400">BNI VA</span>
          <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400">GoPay</span>
        </div>

        <div class="flex items-center gap-4 text-[11px]">
          <a href="#" class="hover:text-emerald-500 transition">Kebijakan Privasi</a>
          <span>•</span>
          <a href="#" class="hover:text-emerald-500 transition">Syarat & Ketentuan</a>
        </div>
      </div>

    </div>
  </footer>

  <!-- Scripts -->
  <script>
    function toggleTheme() {
      const html = document.documentElement;
      if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('lapangankita_theme', 'light');
      } else {
        html.classList.add('dark');
        localStorage.setItem('lapangankita_theme', 'dark');
      }
      lucide.createIcons();
    }
    document.addEventListener('DOMContentLoaded', function() {
      lucide.createIcons();
    });
  </script>
  @yield('scripts')
</body>
</html>
