<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Admin Dashboard - LapanganKita')</title>
  <meta name="description" content="@yield('meta_description', 'Portal Pengelolaan dan Administrasi LapanganKita')">
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
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <!-- jQuery & DataTables CDN (Sesuai Ketentuan Tugas Stage 1) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="{{ asset('css/datatable-custom.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap-tooltips.css') }}?v=2.2">
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="{{ asset('js/datatable-init.js') }}"></script>
  <script src="{{ asset('js/bootstrap-tooltips.js') }}?v=2.2"></script>
  <!-- Chart.js (Local Primary + CDN Fallback) -->
  <script src="{{ asset('js/chart.min.js') }}"></script>
  <script>
    if (typeof Chart === 'undefined') {
      document.write('<script src="https://cdn.jsdelivr.net/npm/chart.js"><\/script>');
    }
  </script>
  
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
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans antialiased selection:bg-emerald-500 selection:text-white min-h-screen">

  @php
    $user = auth()->user();
    $role = $user->role ?? 'tenant';
    $isSuperAdmin = ($role === 'superadmin');
    $currentTenant = \App\Models\Tenant::where('owner_user_id', $user->id ?? 0)->first();
  @endphp

  <!-- ========================================================================= -->
  <!-- 1. SIDEBAR DESKTOP (FIXED LEFT)                                           -->
  <!-- ========================================================================= -->
  <aside id="desktop-sidebar" class="hidden lg:flex flex-col fixed inset-y-0 left-0 w-64 bg-slate-900 border-r border-slate-800 z-40 select-none shadow-2xl transition-transform duration-300 ease-in-out">
    
    <!-- BRAND / LOGO HEADER -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-slate-800/80 bg-slate-900/50 backdrop-blur-md">
      <a href="{{ $isSuperAdmin ? route('admin.dashboard') : route('tenant.dashboard') }}" class="flex items-center gap-2.5 group min-w-0">
        @if(!$isSuperAdmin)
          @if(!empty($currentTenant?->logo_image))
            <div class="w-9 h-9 rounded-xl overflow-hidden border border-slate-700 bg-slate-800 shadow-md shrink-0">
              <img src="{{ $currentTenant->logo_image }}" alt="{{ $currentTenant->name }}" class="w-full h-full object-cover">
            </div>
          @else
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center text-slate-950 font-black shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform shrink-0">
              <i data-lucide="trophy" class="w-4 h-4 text-slate-950"></i>
            </div>
          @endif
        @else
          {{-- Super Admin: Logo Konsep 1 LapanganKita --}}
          <div class="w-9 h-9 rounded-xl overflow-hidden border border-emerald-500/40 bg-slate-950 shadow-lg shadow-emerald-500/25 group-hover:scale-105 transition-transform shrink-0 flex items-center justify-center">
            <img src="{{ asset('images/logo-lk-concept-1.jpg') }}" alt="LapanganKita Master Admin" class="w-full h-full object-cover">
          </div>
        @endif
        <div class="truncate">
          <span class="text-sm font-black tracking-tight text-white block leading-none truncate">
            {{ (!$isSuperAdmin && $currentTenant) ? $currentTenant->name : 'LapanganKita' }}
          </span>
          <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mt-1 truncate">
            {{ $isSuperAdmin ? 'Super Admin Portal' : 'Admin Mitra Venue' }}
          </span>
        </div>
      </a>

      <!-- Hamburger Button di Sidebar -->
      <button type="button" onclick="toggleDesktopSidebar()" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800/80 transition cursor-pointer shrink-0 ml-1" title="Sembunyikan / Buka Sidebar">
        <i data-lucide="menu" class="w-4 h-4"></i>
      </button>
    </div>

    <!-- ROLE STATUS BADGE -->
    <div class="px-4 py-2 bg-slate-950/40 border-b border-slate-800/60">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
          </span>
          <span class="text-[11px] font-semibold text-slate-300">
            {{ $isSuperAdmin ? 'Platform Control' : 'Venue Operasional' }}
          </span>
        </div>
        <span class="px-2 py-0.5 rounded-full text-[9px] font-black {{ $isSuperAdmin ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' }}">
          {{ $isSuperAdmin ? 'Super Admin' : 'Mitra Venue' }}
        </span>
      </div>
    </div>

    <!-- NAVIGATION LINKS -->
    <div class="flex-1 overflow-y-auto px-3 py-3.5 space-y-4">
      
      <!-- MENU UTAMA -->
      <div>
        <p class="px-2.5 mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
          Menu Utama
        </p>
        <nav class="space-y-1 text-xs font-semibold">
          
          @if($isSuperAdmin)
            <!-- SUPER ADMIN: DASHBOARD MASTER -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}"
               data-bs-toggle="tooltip" data-bs-placement="right" title="Pusat kendali seluruh platform SaaS: pantau MRR, tenant aktif, dan webhook gateway.">
              <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
              <span>Dashboard Master</span>
            </a>

            <!-- SUPER ADMIN: ANALITIK PLATFORM -->
            <a href="{{ route('admin.analytics') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.analytics') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}"
               data-bs-toggle="tooltip" data-bs-placement="right" title="Laporan statistik global seluruh pertumbuhan omset dan transaksi booking di platform.">
              <i data-lucide="line-chart" class="w-4 h-4 {{ request()->routeIs('admin.analytics') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
              <span>Analitik Platform</span>
            </a>

            <!-- SUPER ADMIN: MODE MITRA VENUE -->
            <a href="{{ route('tenant.dashboard') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('tenant.*') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}"
               data-bs-toggle="tooltip" data-bs-placement="right" title="Buka sudut pandang pengelola venue untuk memeriksa modul kasir dan lapangan.">
              <i data-lucide="store" class="w-4 h-4 {{ request()->routeIs('tenant.*') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
              <span>Mode Mitra Venue</span>
            </a>
          @else
            <!-- MITRA: DASHBOARD UTAMA -->
            <a href="{{ route('tenant.dashboard') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('tenant.dashboard') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}"
               data-bs-toggle="tooltip" data-bs-placement="right" title="Ringkasan operasional harian venue: omset hari ini, jadwal masuk, dan performa lapangan.">
              <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->routeIs('tenant.dashboard') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
              <span>Dashboard Ringkasan</span>
            </a>

            <!-- MITRA: KELOLA LAPANGAN & SLOT -->
            <a href="{{ route('tenant.courts') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('tenant.courts') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}"
               data-bs-toggle="tooltip" data-bs-placement="right" title="Manajemen unit lapangan, pengaturan tarif siang/malam, dan kunci jam pesanan kasir.">
              <i data-lucide="calendar-check" class="w-4 h-4 {{ request()->routeIs('tenant.courts') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
              <span>Kelola Lapangan & Slot</span>
            </a>

            <!-- MITRA: ANALITIK BISNIS -->
            <a href="{{ route('tenant.analytics') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('tenant.analytics') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}"
               data-bs-toggle="tooltip" data-bs-placement="right" title="Grafik performa bisnis, AOV, okupansi jam malam, dan rekomendasi strategi omset.">
              <i data-lucide="bar-chart-2" class="w-4 h-4 {{ request()->routeIs('tenant.analytics') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
              <span>Analitik Bisnis</span>
            </a>

            <!-- MITRA: BILLING & SAAS TRIAL -->
            <a href="{{ route('tenant.billing') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('tenant.billing') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}"
               data-bs-toggle="tooltip" data-bs-placement="right" title="Status paket SaaS, sisa hari aktif uji coba/langganan, dan aktivasi perpanjangan.">
              <i data-lucide="credit-card" class="w-4 h-4 {{ request()->routeIs('tenant.billing') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
              <span>Langganan & Billing</span>
            </a>

            <!-- MITRA: PROFIL VENUE -->
            <a href="{{ route('tenant.profile') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('tenant.profile') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/70' }}"
               data-bs-toggle="tooltip" data-bs-placement="right" title="Kelola identitas venue, alamat, nomor WhatsApp kasir, logo, dan foto banner.">
              <i data-lucide="settings" class="w-4 h-4 {{ request()->routeIs('tenant.profile') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
              <span>Profil Tempat Futsal</span>
            </a>
          @endif

        </nav>
      </div>

      <!-- NAVIGASI WEB PUBLIK / PEMAIN -->
      <div>
        <p class="px-2.5 mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
          Portal Publik
        </p>
        <nav class="space-y-1 text-xs font-semibold">
          <a href="{{ route('home') }}" target="_blank"
             class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-400 hover:text-emerald-400 hover:bg-slate-800/70 transition">
            <div class="flex items-center gap-2.5">
              <i data-lucide="external-link" class="w-4 h-4 text-slate-500"></i>
              <span>Web Pemain / Beranda</span>
            </div>
            <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-slate-500"></i>
          </a>

          <a href="{{ route('venues.index') }}" target="_blank"
             class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-400 hover:text-emerald-400 hover:bg-slate-800/70 transition">
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
        <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-xs shrink-0 shadow-md">
          {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
        </div>
        <div class="min-w-0 flex-1">
          <p class="text-xs font-bold text-white truncate leading-tight">{{ $user->name ?? 'Administrator' }}</p>
          <p class="text-[10px] text-slate-400 truncate">{{ $user->email ?? 'admin@lapangankita.id' }}</p>
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
  <!-- 2. MOBILE SIDEBAR DRAWER (RESPONSIVE)                                      -->
  <!-- ========================================================================= -->
  <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 hidden transition-opacity" onclick="toggleMobileSidebar()"></div>
  <aside id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-slate-900 border-r border-slate-800 z-50 flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden select-none">
    
    <div class="h-16 flex items-center justify-between px-4 border-b border-slate-800 bg-slate-900">
      <div class="flex items-center gap-2.5 min-w-0">
        @if(!$isSuperAdmin)
          @if(!empty($currentTenant?->logo_image))
            <div class="w-8 h-8 rounded-xl overflow-hidden border border-slate-700 bg-slate-800 shadow-md shrink-0">
              <img src="{{ $currentTenant->logo_image }}" alt="{{ $currentTenant->name }}" class="w-full h-full object-cover">
            </div>
          @else
            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center text-slate-950 font-black shrink-0">
              <i data-lucide="trophy" class="w-4 h-4 text-slate-950"></i>
            </div>
          @endif
        @else
          {{-- Super Admin Mobile: Logo Konsep 1 LapanganKita --}}
          <div class="w-8 h-8 rounded-xl overflow-hidden border border-emerald-500/40 bg-slate-950 shadow-md shadow-emerald-500/25 shrink-0 flex items-center justify-center">
            <img src="{{ asset('images/logo-lk-concept-1.jpg') }}" alt="LapanganKita Master Admin" class="w-full h-full object-cover">
          </div>
        @endif
        <div class="truncate">
          <span class="text-sm font-black tracking-tight text-white block leading-none truncate">
            {{ (!$isSuperAdmin && $currentTenant) ? $currentTenant->name : 'LapanganKita' }}
          </span>
          <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mt-0.5 truncate">
            {{ $isSuperAdmin ? 'Super Admin' : 'Admin Mitra' }}
          </span>
        </div>
      </div>
      <button type="button" onclick="toggleMobileSidebar()" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
        <i data-lucide="x" class="w-4 h-4"></i>
      </button>
    </div>

    <!-- Mobile Nav Links -->
    <div class="flex-1 overflow-y-auto px-3 py-3.5 space-y-4">
      <div>
        <p class="px-2.5 mb-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Menu Utama</p>
        <nav class="space-y-1 text-xs font-semibold">
          @if($isSuperAdmin)
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800' }}">
              <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
              <span>Dashboard Master</span>
            </a>
            <a href="{{ route('admin.analytics') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.analytics') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800' }}">
              <i data-lucide="line-chart" class="w-4 h-4"></i>
              <span>Analitik Platform</span>
            </a>
            <a href="{{ route('tenant.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('tenant.*') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800' }}">
              <i data-lucide="store" class="w-4 h-4"></i>
              <span>Mode Mitra Venue</span>
            </a>
          @else
            <a href="{{ route('tenant.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('tenant.dashboard') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800' }}">
              <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
              <span>Dashboard Ringkasan</span>
            </a>
            <a href="{{ route('tenant.courts') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('tenant.courts') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800' }}">
              <i data-lucide="calendar-check" class="w-4 h-4"></i>
              <span>Kelola Lapangan & Slot</span>
            </a>
            <a href="{{ route('tenant.analytics') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('tenant.analytics') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800' }}">
              <i data-lucide="bar-chart-2" class="w-4 h-4"></i>
              <span>Analitik Bisnis</span>
            </a>
            <a href="{{ route('tenant.billing') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('tenant.billing') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800' }}">
              <i data-lucide="credit-card" class="w-4 h-4"></i>
              <span>Langganan & Billing</span>
            </a>
            <a href="{{ route('tenant.profile') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition {{ request()->routeIs('tenant.profile') ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800' }}">
              <i data-lucide="settings" class="w-4 h-4"></i>
              <span>Profil Tempat Futsal</span>
            </a>
          @endif
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
  <!-- 3. MAIN CONTENT WRAPPER (DESKTOP OFFSET PL-64)                            -->
  <!-- ========================================================================= -->
  <div id="main-content-wrapper" class="lg:pl-64 flex flex-col min-h-screen transition-all duration-300 ease-in-out">
    
    <!-- TOP HEADER BAR -->
    <header class="sticky top-0 z-30 h-16 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800/80 px-4 sm:px-6 lg:px-6 flex items-center justify-between transition-colors">
      
      <!-- Left: Hamburger Menu Trigger & Breadcrumb -->
      <div class="flex items-center gap-3">
        <!-- Hamburger Button (Mobile Drawer & Desktop Toggle) -->
        <button type="button" onclick="toggleSidebarUniversal()" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 hover:text-emerald-500 transition cursor-pointer" title="Buka / Tutup Sidebar">
          <i data-lucide="menu" class="w-4 h-4"></i>
        </button>

        <div>
          <div class="flex items-center gap-2">
            <h2 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white tracking-tight">
              {{ $isSuperAdmin ? 'Portal Super Administrator' : 'Portal Pengelola Mitra' }}
            </h2>
            <span class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
              Live Sync
            </span>
          </div>
          <nav class="flex items-center gap-1.5 text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
            <span>{{ $isSuperAdmin ? 'Platform' : 'Mitra Venue' }}</span>
            <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
            <span class="text-emerald-600 dark:text-emerald-400 font-bold">@yield('title', 'Dashboard')</span>
          </nav>
        </div>
      </div>

      <!-- Right: Action Icons & Quick Switch -->
      <div class="flex items-center gap-2 sm:gap-2.5">
        
        <!-- Quick View Public Site -->
        <a href="{{ route('home') }}" target="_blank" 
           class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs transition border border-slate-200 dark:border-slate-700">
          <i data-lucide="globe" class="w-3.5 h-3.5 text-emerald-500"></i>
          <span>Lihat Web Pemain</span>
        </a>

        <!-- Dark / Light Mode Switch -->
        <button type="button" onclick="toggleTheme()" 
                class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center transition" 
                title="Ganti Tema">
          <i data-lucide="sun" class="w-4 h-4 hidden dark:block text-amber-400"></i>
          <i data-lucide="moon" class="w-4 h-4 block dark:hidden text-slate-600"></i>
        </button>

        <!-- User / Venue Avatar -->
        <div class="flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-slate-800">
          @if(!$isSuperAdmin)
            @if(!empty($currentTenant?->logo_image))
              <div class="w-8 h-8 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm shrink-0">
                <img src="{{ $currentTenant->logo_image }}" alt="{{ $currentTenant->name }}" class="w-full h-full object-cover">
              </div>
            @else
              <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 font-black text-xs shadow-sm">
                {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
              </div>
            @endif
          @else
            {{-- Super Admin Topbar Avatar: Logo Konsep 1 --}}
            <div class="w-8 h-8 rounded-xl overflow-hidden border border-emerald-500/40 bg-slate-950 shadow-sm shrink-0 flex items-center justify-center">
              <img src="{{ asset('images/logo-lk-concept-1.jpg') }}" alt="Super Admin" class="w-full h-full object-cover">
            </div>
          @endif
          <div class="hidden xl:block text-left">
            <span class="block text-xs font-bold text-slate-900 dark:text-white leading-tight">
              {{ (!$isSuperAdmin && $currentTenant) ? $currentTenant->name : ($user->name ?? 'Admin') }}
            </span>
            <span class="block text-[10px] text-slate-400 font-medium">{{ $isSuperAdmin ? 'Super Admin' : 'Mitra Venue' }}</span>
          </div>
        </div>

      </div>

    </header>

    <!-- CONTENT BODY (YIELD) -->
    <main class="flex-1">
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

    <!-- ADMIN FOOTER -->
    <footer class="py-4 px-6 border-t border-slate-200 dark:border-slate-800/80 bg-white/50 dark:bg-slate-900/40 text-xs text-slate-500 dark:text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-2">
      <div>
        © 2026 <strong class="text-slate-700 dark:text-slate-300">LapanganKita Indonesia</strong>. Seluruh hak cipta dilindungi.
      </div>
      <div class="flex items-center gap-3 text-[11px]">
        <span class="flex items-center gap-1 text-emerald-500 font-semibold">
          <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
          Portal Terenkripsi & Aman
        </span>
        <span>•</span>
        <span>Versi 2.4.0</span>
      </div>
    </footer>

  </div>

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
        localStorage.setItem('lapangankita_sidebar_collapsed', 'false');
      } else {
        // Sembunyikan sidebar (memberi ruang penuh pada konten)
        desktopSidebar.classList.add('lg:-translate-x-full');
        mainWrapper.classList.remove('lg:pl-64');
        mainWrapper.classList.add('lg:pl-0');
        localStorage.setItem('lapangankita_sidebar_collapsed', 'true');
      }
      if (window.lucide) lucide.createIcons();
    }

    function toggleSidebarUniversal() {
      if (window.innerWidth < 1024) {
        toggleMobileSidebar();
      } else {
        toggleDesktopSidebar();
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      if (window.lucide) lucide.createIcons();

      // Pulihkan preferensi sidebar desktop dari localStorage
      if (window.innerWidth >= 1024 && localStorage.getItem('lapangankita_sidebar_collapsed') === 'true') {
        const desktopSidebar = document.getElementById('desktop-sidebar');
        const mainWrapper = document.getElementById('main-content-wrapper');
        if (desktopSidebar && mainWrapper) {
          desktopSidebar.classList.add('lg:-translate-x-full');
          mainWrapper.classList.remove('lg:pl-64');
          mainWrapper.classList.add('lg:pl-0');
        }
      }
    });
  </script>
  @yield('scripts')
</body>
</html>
