<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Super Admin Dashboard - SaaS Platform LapanganKita')</title>
  <meta name="description" content="@yield('meta_description', 'Portal Master Super Admin platform LapanganKita: monitoring transaksi nasional, recurring revenue MRR/ARR, tenant management, master paket SaaS, dan audit webhook.')">
  <link rel="icon" type="image/jpeg" href="{{ asset('images/logo-lk-concept-1.jpg') }}">

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

  <!-- Google Fonts: Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <!-- Chart.js (Local Primary + CDN Fallback) -->
  <script src="{{ asset('js/chart.min.js') }}"></script>
  <script>
    if (typeof Chart === 'undefined') {
      document.write('<script src="https://cdn.jsdelivr.net/npm/chart.js"><\/script>');
    }
  </script>
  <!-- jQuery & DataTables CDN (Sesuai Ketentuan Tugas Stage 1) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="{{ asset('css/datatable-custom.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap-tooltips.css') }}?v=2.2">
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="{{ asset('js/datatable-init.js') }}"></script>
  <script src="{{ asset('js/bootstrap-tooltips.js') }}?v=2.2"></script>

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

    /* Select option dropdowns across browsers */
    select option {
      background-color: #ffffff;
      color: #0f172a;
    }
    .dark select option {
      background-color: #0f172a;
      color: #f8fafc;
    }

    /* Custom Scrollbar */
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
  @stack('styles')
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans antialiased selection:bg-purple-500 selection:text-white min-h-screen transition-colors duration-200">

  @php
    $user = auth()->user();
  @endphp

  <!-- ========================================================================= -->
  <!-- 1. SIDEBAR DESKTOP SUPER ADMIN (FIXED LEFT, SLEEK DARK & SMOOTH TRANSITION)-->
  <!-- ========================================================================= -->
  <aside id="desktop-sidebar" class="hidden lg:flex flex-col fixed inset-y-0 left-0 w-64 bg-slate-900 border-r border-slate-800 z-40 select-none shadow-2xl transition-transform duration-300 ease-in-out">
    
    <!-- BRAND / LOGO HEADER -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-slate-800/80 bg-slate-900/50 backdrop-blur-md">
      <div class="flex items-center gap-2.5 min-w-0">
        <div class="w-9 h-9 rounded-xl overflow-hidden border border-purple-500/30 bg-slate-950 p-0.5 shadow-lg shadow-purple-500/20 shrink-0 flex items-center justify-center">
          <img src="{{ asset('images/logo-lk-concept-1.jpg') }}" alt="Logo LapanganKita" class="w-full h-full object-cover rounded-lg">
        </div>
        <div class="truncate">
          <div class="flex items-center gap-1.5 truncate">
            <span class="text-sm font-black tracking-tight text-white block leading-none truncate">LapanganKita</span>
            <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-purple-500/20 text-purple-400 border border-purple-500/30">SA</span>
          </div>
          <span class="text-[9px] font-bold text-purple-400 uppercase tracking-widest block mt-1 truncate">
            Super Admin Portal
          </span>
        </div>
      </div>

      <!-- Hamburger Button di Sidebar -->
      <button type="button" onclick="toggleDesktopSidebar()" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/80 transition cursor-pointer shrink-0 ml-1" title="Sembunyikan / Buka Sidebar">
        <i data-lucide="menu" class="w-4 h-4"></i>
      </button>
    </div>

    <!-- SERVER STATUS BADGE -->
    <div class="px-4 py-2 bg-slate-950/40 border-b border-slate-800/60">
      <div class="flex items-center gap-2">
        <span class="relative flex h-2 w-2">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
        </span>
        <span class="text-[11px] font-bold text-slate-300">Sistem Aktif &amp; Terhubung</span>
      </div>
    </div>

    <!-- NAVIGASI UTAMA (TAB SYSTEM) -->
    <div class="flex-1 overflow-y-auto px-3 py-3.5 space-y-4">
      <div>
        <p class="px-2.5 mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
          Menu Super Admin
        </p>
        <nav class="space-y-1 text-xs font-semibold">
          <button type="button" onclick="switchDashboardTab('overview')" id="sidebarBtn-overview"
             class="nav-tab-btn w-full flex items-center justify-between px-3 py-2 rounded-xl bg-purple-600 text-white shadow-md shadow-purple-600/30 transition-colors text-left cursor-pointer"
             data-bs-toggle="tooltip" data-bs-placement="right" title="Ringkasan finansial platform: MRR, estimasi ARR, total tenant aktif, dan booking pemain global.">
            <div class="flex items-center gap-2.5 min-w-0">
              <i data-lucide="layout-grid" class="w-4 h-4 shrink-0 text-white"></i>
              <span class="truncate font-semibold text-xs">Global SaaS Dashboard</span>
            </div>
          </button>

          <button type="button" onclick="switchDashboardTab('tenants')" id="sidebarBtn-tenants"
             class="nav-tab-btn w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-colors text-left cursor-pointer"
             data-bs-toggle="tooltip" data-bs-placement="right" title="Daftar mitra venue, perpanjang lisensi paket, kontrol suspend, dan reset sandi.">
            <div class="flex items-center gap-2.5 min-w-0">
              <i data-lucide="store" class="w-4 h-4 text-purple-400 shrink-0"></i>
              <span class="truncate font-semibold text-xs">Tenant Management</span>
            </div>
          </button>

          <button type="button" onclick="switchDashboardTab('packages')" id="sidebarBtn-packages"
             class="nav-tab-btn w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-colors text-left cursor-pointer"
             data-bs-toggle="tooltip" data-bs-placement="right" title="Pengaturan harga paket sewa aplikasi, limitasi unit lapangan, modul analitik, dan live preview.">
            <div class="flex items-center gap-2.5 min-w-0">
              <i data-lucide="package" class="w-4 h-4 text-purple-400 shrink-0"></i>
              <span class="truncate font-semibold text-xs">SaaS Package Master</span>
            </div>
          </button>

          <button type="button" onclick="switchDashboardTab('logs')" id="sidebarBtn-logs"
             class="nav-tab-btn w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-colors text-left cursor-pointer"
             data-bs-toggle="tooltip" data-bs-placement="right" title="Rekam jejak audit aktivitas sistem, callback webhook Midtrans, dan data arsip soft delete.">
            <div class="flex items-center gap-2.5 min-w-0">
              <i data-lucide="activity" class="w-4 h-4 text-purple-400 shrink-0"></i>
              <span class="truncate font-semibold text-xs">Payment & Webhook Log</span>
            </div>
          </button>
        </nav>
      </div>

      <!-- PORTAL PUBLIK / PEMAIN -->
      <div>
        <p class="px-2.5 mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
          Portal Publik
        </p>
        <nav class="space-y-1 text-xs font-semibold">
          <a href="{{ route('home') }}" target="_blank"
             class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-400 hover:text-purple-400 hover:bg-slate-800/70 transition">
            <div class="flex items-center gap-2.5">
              <i data-lucide="external-link" class="w-4 h-4 text-slate-500"></i>
              <span>Web Pemain / Beranda</span>
            </div>
            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-slate-500"></i>
          </a>

          <a href="{{ route('venues.index') }}" target="_blank"
             class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-400 hover:text-purple-400 hover:bg-slate-800/70 transition">
            <div class="flex items-center gap-2.5">
              <i data-lucide="map-pin" class="w-4 h-4 text-slate-500"></i>
              <span>Katalog Lapangan</span>
            </div>
            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-slate-500"></i>
          </a>
        </nav>
      </div>

    </div>

    <!-- SIDEBAR FOOTER (USER INFO & LOGOUT) -->
    <div class="p-3 border-t border-slate-800/80 bg-slate-950/50">
      <div class="flex items-center gap-2.5 mb-2.5">
        <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-purple-600 to-indigo-600 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-md">
          SA
        </div>
        <div class="min-w-0 flex-1">
          <p class="text-xs font-bold text-white truncate leading-tight">{{ $user->name ?? 'Syafii Maarif' }}</p>
          <p class="text-[10px] text-purple-400 font-semibold truncate">{{ $user->email ?? 'superadmin@lapangankita.id' }}</p>
        </div>
      </div>

      <form action="{{ route('auth.logout') }}" method="POST">
        @csrf
        <button type="submit" 
                class="w-full flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-bold text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 border border-rose-500/20 transition cursor-pointer">
          <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
          <span>Keluar Portal</span>
        </button>
      </form>
    </div>

  </aside>

  <!-- ========================================================================= -->
  <!-- 2. MOBILE SIDEBAR DRAWER (RESPONSIVE & SMOOTH SLIDE)                      -->
  <!-- ========================================================================= -->
  <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden transition-opacity" onclick="toggleMobileSidebar()"></div>
  <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-slate-900 border-r border-slate-800 z-50 flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden select-none">
    
    <div class="h-16 flex items-center justify-between px-4 border-b border-slate-800 bg-slate-900">
      <div class="flex items-center gap-2.5 min-w-0">
        <div class="w-8 h-8 rounded-xl overflow-hidden border border-purple-500/40 bg-slate-950 shadow-md shadow-purple-500/25 shrink-0 flex items-center justify-center p-0.5">
          <img src="{{ asset('images/logo-lk-concept-1.jpg') }}" alt="LapanganKita Master Admin" class="w-full h-full object-cover rounded-lg">
        </div>
        <div class="truncate">
          <span class="text-sm font-black tracking-tight text-white block leading-none truncate">
            LapanganKita
          </span>
          <span class="text-[9px] font-bold text-purple-400 uppercase tracking-widest block mt-0.5 truncate">
            Super Admin Console
          </span>
        </div>
      </div>
      <button type="button" onclick="toggleMobileSidebar()" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
        <i data-lucide="x" class="w-4 h-4"></i>
      </button>
    </div>

    <!-- Mobile Nav Tabs -->
    <div class="flex-1 overflow-y-auto px-3 py-3.5 space-y-4">
      <div>
        <p class="px-2.5 mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Menu Super Admin</p>
        <nav class="space-y-1 text-xs font-semibold">
          <button onclick="switchDashboardTab('overview'); toggleMobileSidebar();" id="mobileBtn-overview" class="mobile-tab-btn w-full flex items-center justify-between px-3 py-2 rounded-xl bg-purple-600 text-white text-left shadow-sm transition-colors">
            <div class="flex items-center gap-2.5 min-w-0">
              <i data-lucide="layout-grid" class="w-4 h-4 shrink-0 text-white"></i>
              <span class="truncate font-semibold text-xs">Global SaaS Dashboard</span>
            </div>
          </button>
          <button onclick="switchDashboardTab('tenants'); toggleMobileSidebar();" id="mobileBtn-tenants" class="mobile-tab-btn w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white text-left transition-colors">
            <div class="flex items-center gap-2.5 min-w-0">
              <i data-lucide="store" class="w-4 h-4 text-purple-400 shrink-0"></i>
              <span class="truncate font-semibold text-xs">Tenant Management</span>
            </div>
          </button>
          <button onclick="switchDashboardTab('packages'); toggleMobileSidebar();" id="mobileBtn-packages" class="mobile-tab-btn w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white text-left transition-colors">
            <div class="flex items-center gap-2.5 min-w-0">
              <i data-lucide="package" class="w-4 h-4 text-purple-400 shrink-0"></i>
              <span class="truncate font-semibold text-xs">SaaS Package Master</span>
            </div>
          </button>
          <button onclick="switchDashboardTab('logs'); toggleMobileSidebar();" id="mobileBtn-logs" class="mobile-tab-btn w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white text-left transition-colors">
            <div class="flex items-center gap-2.5 min-w-0">
              <i data-lucide="activity" class="w-4 h-4 text-purple-400 shrink-0"></i>
              <span class="truncate font-semibold text-xs">Payment & Webhook Log</span>
            </div>
          </button>
        </nav>
      </div>

      <div>
        <p class="px-2.5 mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Portal Publik</p>
        <nav class="space-y-1 text-xs font-semibold">
          <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800">
            <i data-lucide="external-link" class="w-4 h-4"></i>
            <span>Web Pemain / Beranda</span>
          </a>
          <a href="{{ route('venues.index') }}" target="_blank" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800">
            <i data-lucide="map-pin" class="w-4 h-4"></i>
            <span>Katalog Lapangan</span>
          </a>
        </nav>
      </div>
    </div>

    <div class="p-3 border-t border-slate-800 bg-slate-950/60">
      <form action="{{ route('auth.logout') }}" method="POST">
        @csrf
        <button type="submit" class="w-full flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-bold text-rose-400 bg-rose-500/10 border border-rose-500/20">
          <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
          <span>Keluar Portal</span>
        </button>
      </form>
    </div>

  </aside>

  <!-- ========================================================================= -->
  <!-- 3. MAIN CONTENT WRAPPER (DESKTOP OFFSET LG:PL-64 WITH SMOOTH TRANSITION)   -->
  <!-- ========================================================================= -->
  <div id="main-content-wrapper" class="lg:pl-64 flex flex-col min-h-screen transition-[padding-left] duration-300 ease-in-out">
    
    <!-- TOP HEADER BAR -->
    <header class="sticky top-0 z-30 h-16 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800/80 px-4 sm:px-6 lg:px-6 flex items-center justify-between transition-colors">
      
      <!-- Left: Hamburger Trigger & Titles -->
      <div class="flex items-center gap-3 min-w-0">
        <!-- Hamburger Button (Tenant Style Universal Trigger) -->
        <button type="button" onclick="toggleSidebarUniversal()" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 hover:text-purple-500 transition cursor-pointer shadow-sm shrink-0" title="Buka / Tutup Sidebar">
          <i data-lucide="menu" class="w-4 h-4"></i>
        </button>

        <div class="min-w-0">
          <div class="flex items-center gap-2">
            <h2 id="topHeaderTitle" class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white tracking-tight truncate">
              Monitoring Performa Bisnis SaaS LapanganKita
            </h2>
          </div>
          <p id="topHeaderSubtitle" class="text-[11px] text-slate-500 dark:text-slate-400 hidden sm:block truncate">
            Ringkasan transaksi nasional, recurring revenue MRR/ARR, dan operasional platform
          </p>
        </div>
      </div>

      <!-- Right: Action Icons & Avatar -->
      <div class="flex items-center gap-2 sm:gap-2.5">

        <!-- Dark / Light Mode Switch (Smooth Lucide Icons) -->
        <button type="button" onclick="toggleTheme()" id="themeToggleBtn"
                class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center transition shadow-sm cursor-pointer" 
                title="Ganti Tema">
          <i data-lucide="sun" class="w-4 h-4 hidden dark:block text-amber-400"></i>
          <i data-lucide="moon" class="w-4 h-4 block dark:hidden text-purple-600"></i>
        </button>

        <!-- Super Admin Avatar -->
        <div class="flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-slate-800">
          <div class="w-8 h-8 rounded-xl overflow-hidden border border-purple-500/40 bg-slate-950 shadow-sm shrink-0 flex items-center justify-center p-0.5">
            <img src="{{ asset('images/logo-lk-concept-1.jpg') }}" alt="Super Admin" class="w-full h-full object-cover rounded-lg">
          </div>
          <div class="hidden xl:block text-left">
            <span class="block text-xs font-bold text-slate-900 dark:text-white leading-tight">
              {{ $user->name ?? 'Syafii Maarif' }}
            </span>
            <span class="block text-[10px] text-purple-400 font-medium">Root Administrator</span>
          </div>
        </div>
      </div>

    </header>

    <!-- CONTENT BODY (YIELD) -->
    <main class="p-4 sm:p-8 space-y-6 max-w-7xl w-full mx-auto flex-1 relative">
      <!-- GLOBAL TOAST NOTIFICATION CONTAINER -->
      <div id="saToastContainer" class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none max-w-sm w-full"></div>

      <!-- SERVER FLASH MESSAGE (SESSION SUCCESS / ERROR) -->
      @if(session('success'))
        <div id="serverSuccessAlert" class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 dark:text-emerald-300 flex items-start justify-between gap-3 shadow-lg shadow-emerald-500/5 animate-in fade-in slide-in-from-top-4 duration-200">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-emerald-500 text-slate-950 flex items-center justify-center font-bold shrink-0 mt-0.5">
              <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <div>
              <h4 class="text-xs font-black uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Operasi Berhasil</h4>
              <p class="text-xs font-semibold mt-0.5">{{ session('success') }}</p>
            </div>
          </div>
          <button type="button" onclick="this.parentElement.remove()" class="p-1 text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-200 rounded-lg hover:bg-emerald-500/20 transition cursor-pointer">
            <i data-lucide="x" class="w-4 h-4"></i>
          </button>
        </div>
      @endif

      @if(session('error'))
        <div id="serverErrorAlert" class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-800 dark:text-rose-300 flex items-start justify-between gap-3 shadow-lg shadow-rose-500/5 animate-in fade-in slide-in-from-top-4 duration-200">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center font-bold shrink-0 mt-0.5">
              <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div>
              <h4 class="text-xs font-black uppercase tracking-wider text-rose-700 dark:text-rose-400">Terjadi Kesalahan</h4>
              <p class="text-xs font-semibold mt-0.5">{{ session('error') }}</p>
            </div>
          </div>
          <button type="button" onclick="this.parentElement.remove()" class="p-1 text-rose-500 hover:text-rose-700 dark:hover:text-rose-200 rounded-lg hover:bg-rose-500/20 transition cursor-pointer">
            <i data-lucide="x" class="w-4 h-4"></i>
          </button>
        </div>
      @endif

      @yield('content')
    </main>

  </div>

  <!-- SCRIPT LOGIC (EXACT TENANT SMOOTH HAMBURGER + TAB SWITCHING) -->
  <script>
    if (window.lucide) {
      lucide.createIcons();
    }

    // Smooth Dark / Light Mode Toggle
    function toggleTheme() {
      const html = document.documentElement;
      if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('lapangankita_theme', 'light');
      } else {
        html.classList.add('dark');
        localStorage.setItem('lapangankita_theme', 'dark');
      }
      if (window.renderSaasRevenueChart) {
        setTimeout(window.renderSaasRevenueChart, 100);
      }
      if (window.lucide) lucide.createIcons();
    }
    const handleThemeToggle = toggleTheme;

    // Smooth Mobile Sidebar Drawer Toggle
    function toggleMobileSidebar() {
      const sidebar = document.getElementById('mobile-sidebar');
      const backdrop = document.getElementById('mobile-sidebar-backdrop');
      if (!sidebar || !backdrop) return;
      
      if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
      } else {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
      }
      if (window.lucide) lucide.createIcons();
    }
    const toggleMobileNav = toggleMobileSidebar;

    // Smooth Desktop Sidebar Toggle (Slides with CSS transition-transform)
    function toggleDesktopSidebar() {
      const desktopSidebar = document.getElementById('desktop-sidebar');
      const mainWrapper = document.getElementById('main-content-wrapper');
      if (!desktopSidebar || !mainWrapper) return;

      const isHidden = desktopSidebar.classList.contains('lg:-translate-x-full');

      if (isHidden) {
        // Tampilkan kembali sidebar
        desktopSidebar.classList.remove('lg:-translate-x-full');
        mainWrapper.classList.remove('lg:pl-0');
        mainWrapper.classList.add('lg:pl-64');
        localStorage.setItem('lapangankita_sa_sidebar_collapsed', 'false');
      } else {
        // Sembunyikan sidebar (memberi ruang penuh pada konten)
        desktopSidebar.classList.add('lg:-translate-x-full');
        mainWrapper.classList.remove('lg:pl-64');
        mainWrapper.classList.add('lg:pl-0');
        localStorage.setItem('lapangankita_sa_sidebar_collapsed', 'true');
      }

      // Re-render chart jika sedang tampil agar responsif penuh
      if (window.renderSaasRevenueChart) {
        setTimeout(window.renderSaasRevenueChart, 310);
      }
      if (window.lucide) lucide.createIcons();
    }

    // Universal Hamburger Trigger: Mobile Drawer on <1024px, Desktop Slide on >=1024px
    function toggleSidebarUniversal() {
      if (window.innerWidth < 1024) {
        toggleMobileSidebar();
      } else {
        toggleDesktopSidebar();
      }
    }
    const toggleSidebar = toggleSidebarUniversal;

    // Restore desktop sidebar collapsed preference upon DOM load
    document.addEventListener('DOMContentLoaded', function() {
      if (window.lucide) lucide.createIcons();

      if (window.innerWidth >= 1024 && localStorage.getItem('lapangankita_sa_sidebar_collapsed') === 'true') {
        const desktopSidebar = document.getElementById('desktop-sidebar');
        const mainWrapper = document.getElementById('main-content-wrapper');
        if (desktopSidebar && mainWrapper) {
          desktopSidebar.classList.add('lg:-translate-x-full');
          mainWrapper.classList.remove('lg:pl-64');
          mainWrapper.classList.add('lg:pl-0');
        }
      }
    });

    // Global Toast Notification Helper
    window.showSaToast = function(type, title, message) {
      const container = document.getElementById('saToastContainer');
      if (!container) return;

      const toast = document.createElement('div');
      toast.className = `pointer-events-auto p-4 rounded-2xl border shadow-2xl backdrop-blur-md flex items-start justify-between gap-3 animate-in fade-in slide-in-from-top-3 duration-200 transition-all ${
        type === 'success' 
          ? 'bg-slate-900/95 border-emerald-500/40 text-white' 
          : (type === 'error' ? 'bg-slate-900/95 border-rose-500/40 text-white' : 'bg-slate-900/95 border-purple-500/40 text-white')
      }`;

      const iconBg = type === 'success' ? 'bg-emerald-500 text-slate-950' : (type === 'error' ? 'bg-rose-500 text-white' : 'bg-purple-500 text-white');
      const iconName = type === 'success' ? 'check-circle' : (type === 'error' ? 'alert-triangle' : 'info');

      toast.innerHTML = `
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-xl ${iconBg} flex items-center justify-center font-bold shrink-0 mt-0.5">
            <i data-lucide="${iconName}" class="w-4 h-4"></i>
          </div>
          <div class="min-w-0">
            <h4 class="text-xs font-extrabold tracking-wide ${type === 'success' ? 'text-emerald-400' : (type === 'error' ? 'text-rose-400' : 'text-purple-400')}">${title}</h4>
            <p class="text-xs font-medium text-slate-200 mt-0.5 leading-relaxed">${message}</p>
          </div>
        </div>
        <button type="button" class="p-1 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800 transition cursor-pointer shrink-0">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      `;

      const closeBtn = toast.querySelector('button');
      if (closeBtn) {
        closeBtn.onclick = () => {
          toast.classList.add('opacity-0', 'scale-95');
          setTimeout(() => toast.remove(), 200);
        };
      }

      container.appendChild(toast);
      if (window.lucide) lucide.createIcons();

      setTimeout(() => {
        if (toast.parentNode) {
          toast.classList.add('opacity-0', 'scale-95');
          setTimeout(() => toast.remove(), 250);
        }
      }, 5000);
    };

    // Tab Switching for Unified Super Admin Dashboard
    function switchDashboardTab(tabName) {
      const tabs = ['overview', 'tenants', 'packages', 'logs'];
      if (!tabs.includes(tabName)) tabName = 'overview';
      
      const titles = {
        'overview': { title: 'Monitoring Performa Bisnis SaaS LapanganKita', subtitle: 'Ringkasan transaksi nasional, recurring revenue MRR/ARR, dan kesehatan infrastruktur' },
        'tenants': { title: 'Manajemen Akun Tenant Lapangan Futsal', subtitle: 'Verifikasi, kontrol akses, perpanjangan sewa, dan audit venue' },
        'packages': { title: 'Master Paket Langganan Sewa Web SaaS', subtitle: 'Konfigurasi harga, durasi aktif, kuota limit unit, dan penawaran fitur' },
        'logs': { title: 'Global Payment & Webhook Transaction Log', subtitle: 'Monitoring real-time lalu lintas pembayaran SaaS tenant dan booking lapangan' }
      };

      try {
        localStorage.setItem('lapangankita_sa_active_tab', tabName);
        if (history.replaceState) {
          history.replaceState(null, '', '#' + tabName);
        }
      } catch (e) {}

      tabs.forEach(t => {
        const sec = document.getElementById('section-' + t);
        const btn = document.getElementById('sidebarBtn-' + t);
        const mBtn = document.getElementById('mobileBtn-' + t);
        const isCurrent = (t === tabName);

        if (sec) {
          if (isCurrent) {
            sec.classList.remove('hidden');
          } else {
            sec.classList.add('hidden');
          }
        }

        // Desktop Sidebar Button Style
        if (btn) {
          const icon = btn.querySelector('i');
          if (isCurrent) {
            btn.className = 'nav-tab-btn w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-purple-600 text-white shadow-md shadow-purple-600/30 transition-colors text-left cursor-pointer';
            if (icon) {
              icon.classList.remove('text-purple-400');
              icon.classList.add('text-white');
            }
          } else {
            btn.className = 'nav-tab-btn w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-colors text-left cursor-pointer';
            if (icon) {
              icon.classList.remove('text-white');
              icon.classList.add('text-purple-400');
            }
          }
        }

        // Mobile Drawer Button Style
        if (mBtn) {
          const mIcon = mBtn.querySelector('i');
          if (isCurrent) {
            mBtn.className = 'mobile-tab-btn w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-purple-600 text-white text-left shadow-sm transition-colors';
            if (mIcon) {
              mIcon.classList.remove('text-purple-400');
              mIcon.classList.add('text-white');
            }
          } else {
            mBtn.className = 'mobile-tab-btn w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white text-left transition-colors';
            if (mIcon) {
              mIcon.classList.remove('text-white');
              mIcon.classList.add('text-purple-400');
            }
          }
        }
      });

      if (titles[tabName]) {
        const titleEl = document.getElementById('topHeaderTitle');
        const subEl = document.getElementById('topHeaderSubtitle');
        if (titleEl) titleEl.innerText = titles[tabName].title;
        if (subEl) subEl.innerText = titles[tabName].subtitle;
      }

      // If overview and chart canvas exists, trigger chart resize/render
      if (tabName === 'overview' && window.renderSaasRevenueChart) {
        setTimeout(window.renderSaasRevenueChart, 100);
      }

      if (window.lucide) lucide.createIcons();
    }

    // Restore active tab on load
    document.addEventListener('DOMContentLoaded', () => {
      const hash = window.location.hash.replace('#', '');
      let savedTab = null;
      try {
        savedTab = localStorage.getItem('lapangankita_sa_active_tab');
      } catch (e) {}

      const validTabs = ['overview', 'tenants', 'packages', 'logs'];
      const initialTab = validTabs.includes(hash) ? hash : (validTabs.includes(savedTab) ? savedTab : 'overview');
      if (initialTab && initialTab !== 'overview') {
        switchDashboardTab(initialTab);
      }
    });
  </script>
  @stack('scripts')
</body>
</html>
