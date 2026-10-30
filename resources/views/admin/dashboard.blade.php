@extends('layouts.superadmin')

@section('title', 'Super Admin Dashboard - SaaS Platform LapanganKita')
@section('meta_description', 'Pusat kendali dan analitik skala platform untuk pemilik web LapanganKita: monitoring GMV, platform fee, langganan mitra, dan performa seluruh venue.')

@section('content')
  <!-- ========================================================================= -->
  <!-- 1. GLOBAL SAAS DASHBOARD (RINGKASAN METRIKS & PERFORMA FINANSIAL)         -->
  <!-- ========================================================================= -->
  <section id="section-overview" class="space-y-8">

    <!-- 1. GLOBAL STAT METRICS (EXACT MATCH PROTOTYPE) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      
      <!-- Metric 1: Monthly Recurring Revenue (MRR) -->
      <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden transition-colors">
        <div class="flex items-center justify-between">
          <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider term-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Monthly Recurring Revenue: Pendapatan langganan bulanan rutin yang diterima platform dari seluruh tempat futsal aktif.">
            <span>MRR (Sewa SaaS)</span>
            <i data-lucide="help-circle" class="w-3.5 h-3.5 inline text-slate-400"></i>
          </span>
          <div class="w-9 h-9 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center border border-purple-500/20">
            <i data-lucide="credit-card" class="w-5 h-5"></i>
          </div>
        </div>
        <div class="mt-3">
          <div class="text-2xl font-black text-slate-900 dark:text-white">Rp {{ number_format($mrr, 0, ',', '.') }}</div>
          <div class="flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400 font-bold mt-1">
            <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
            <span>+{{ $growthPercentage ?? '16.3' }}% vs bulan lalu</span>
          </div>
        </div>
      </div>

      <!-- Metric 2: Annual Run Rate (ARR) -->
      <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden transition-colors">
        <div class="flex items-center justify-between">
          <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider term-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Annual Run Rate: Proyeksi total pendapatan tahunan platform LapanganKita (dihitung dari MRR x 12 bulan).">
            <span>Estimasi ARR</span>
            <i data-lucide="help-circle" class="w-3.5 h-3.5 inline text-slate-400"></i>
          </span>
          <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-500/20">
            <i data-lucide="badge-dollar-sign" class="w-5 h-5"></i>
          </div>
        </div>
        <div class="mt-3">
          <div class="text-2xl font-black text-slate-900 dark:text-white">Rp {{ number_format($arr, 0, ',', '.') }}</div>
          <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            Dari {{ $totalTenants }} Tenant Berlangganan
          </div>
        </div>
      </div>

      <!-- Metric 3: Total Tenant Venue Aktif -->
      <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden transition-colors">
        <div class="flex items-center justify-between">
          <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider term-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Total mitra pemilik tempat futsal yang terdaftar dan mengelola lapangannya di platform SaaS LapanganKita.">
            <span>Total Tenant Aktif</span>
            <i data-lucide="help-circle" class="w-3.5 h-3.5 inline text-slate-400"></i>
          </span>
          <div class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-500/20">
            <i data-lucide="store" class="w-5 h-5"></i>
          </div>
        </div>
        <div class="mt-3">
          <div class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalTenants }} Venue</div>
          <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex flex-wrap items-center gap-1.5 font-medium">
            <span class="text-emerald-500 font-bold term-tooltip" data-bs-toggle="tooltip" title="Lisensi aktif resmi dan dapat menggunakan seluruh modul lapangan.">{{ $activeTenants }} Aktif</span>
            <span>•</span>
            <span class="text-amber-500 font-bold term-tooltip" data-bs-toggle="tooltip" title="Grace Period: Masa tenggang toleransi (3-7 hari) setelah paket habis sebelum venue dibekukan.">{{ $graceTenants }} Grace</span>
            <span>•</span>
            <span class="text-rose-500 font-bold term-tooltip" data-bs-toggle="tooltip" title="Suspended: Status akun dibekukan sementara karena belum memperpanjang paket sewa aplikasi.">{{ $suspendedTenants }} Suspend</span>
          </div>
        </div>
      </div>

      <!-- Metric 4: Global Bookings Sistem -->
      <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden transition-colors">
        <div class="flex items-center justify-between">
          <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider term-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="Total akumulasi jadwal sewa lapangan yang dipesan oleh pemain futsal dari seluruh venue di platform.">
            <span>Booking Pemain Global</span>
            <i data-lucide="help-circle" class="w-3.5 h-3.5 inline text-slate-400"></i>
          </span>
          <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-500/20">
            <i data-lucide="calendar" class="w-5 h-5"></i>
          </div>
        </div>
        <div class="mt-3">
          <div class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($totalBookingsCount) }} Match</div>
          <div class="text-xs text-emerald-600 dark:text-emerald-400 font-bold mt-1 term-tooltip" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Gross Merchandise Value (GMV): Total akumulasi perputaran uang sewa lapangan, dan Biaya Layanan Platform Rp 2.500/transaksi.">
            Nilai Transaksi (GMV): Rp {{ number_format($totalGmv / 1000000, 1) }} Jt • Fee: Rp {{ number_format($totalPlatformFee, 0, ',', '.') }}
          </div>
        </div>
      </div>

    </div>

    <!-- 2. CHART: PERTUMBUHAN TENANT & REVENUE SAAS (FULL WIDTH CLEAN VIEW) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

      <!-- Revenue Growth Chart (12 COLS FULL WIDTH) -->
      <div class="lg:col-span-12 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4 transition-colors">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
          <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
              <i data-lucide="trending-up" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i> Pertumbuhan Pendapatan Sewa SaaS & Tenant
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400">Pertumbuhan recurring revenue (MRR) dalam 6 bulan terakhir</p>
          </div>
          <div class="flex items-center gap-2 text-xs">
            <span class="flex items-center gap-1 text-purple-600 dark:text-purple-400 font-bold">
              <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> Pendapatan SaaS (Juta Rp)
            </span>
          </div>
        </div>

        <div class="h-64 sm:h-72 w-full pt-2">
          <canvas id="saasRevenueChart"></canvas>
        </div>
      </div>

    </div>

    <!-- 3. QUICK APPROVAL TABLE (PENDAFTARAN TENANT BARU & STATUS LISENSI) -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">
      <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <div>
          <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
            <i data-lucide="user-check" class="w-5 h-5 text-amber-500"></i> Status Pendaftaran Venue & Masa Sewa
          </h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pemilik lapangan futsal yang terdaftar pada sistem SaaS</p>
        </div>
        <button type="button" onclick="switchDashboardTab('tenants')" class="text-xs font-bold text-purple-600 dark:text-purple-400 hover:underline cursor-pointer">Kelola Semua Tenant &rarr;</button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
            <tr>
              <th class="py-3.5 px-6">Nama Venue & Pemilik</th>
              <th class="py-3.5 px-6">Lokasi</th>
              <th class="py-3.5 px-6">Pilihan Paket</th>
              <th class="py-3.5 px-6">Sisa Waktu</th>
              <th class="py-3.5 px-6">Status Bayar SaaS</th>
              <th class="py-3.5 px-6 text-center">Tindakan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
            @forelse($tenantsData as $t)
              @php
                $isSusp = ($t->status === 'suspended');
                $isGr = ($t->status === 'grace');
              @endphp
              <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                <td class="py-4 px-6">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 shrink-0 flex items-center justify-center">
                      @if(!empty($t->logo_image))
                        <img src="{{ $t->logo_image }}" class="w-full h-full object-cover" alt="{{ $t->name }}" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                        <span class="hidden font-black text-xs text-purple-600 dark:text-purple-400">{{ substr($t->name, 0, 2) }}</span>
                      @else
                        <span class="font-black text-xs text-purple-600 dark:text-purple-400">{{ substr($t->name, 0, 2) }}</span>
                      @endif
                    </div>
                    <div>
                      <span class="font-extrabold text-slate-900 dark:text-white text-sm block">{{ $t->name }}</span>
                      <span class="text-slate-500 dark:text-slate-400 text-[11px]">{{ $t->owner->name ?? 'Pemilik' }} • {{ $t->phone_wa }}</span>
                    </div>
                  </div>
                </td>
                <td class="py-4 px-6 text-slate-600 dark:text-slate-300">{{ $t->city }}</td>
                <td class="py-4 px-6 font-bold text-purple-600 dark:text-purple-400">{{ $t->plan_name }}</td>
                <td class="py-4 px-6 font-bold {{ $isSusp ? 'text-rose-500' : ($isGr ? 'text-amber-500' : 'text-emerald-500') }}">
                  {{ $t->days_left }} Hari Tersisa
                </td>
                <td class="py-4 px-6">
                  @if($isSusp)
                    <span class="px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/30 font-bold text-[10px]">SUSPENDED</span>
                  @elseif($isGr)
                    <span class="px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/30 font-bold text-[10px]">GRACE PERIOD</span>
                  @else
                    <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 font-bold text-[10px]">PAID & AKTIF</span>
                  @endif
                </td>
                <td class="py-4 px-6 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <button type="button" 
                            onclick="openExtendModal(this)"
                            data-id="{{ $t->id }}"
                            data-name="{{ $t->name }}"
                            data-package-id="{{ $t->subscriptions->sortByDesc('end_date')->first()?->package_id ?? 1 }}"
                            class="px-2.5 py-1.5 bg-purple-500/10 hover:bg-purple-600 text-purple-600 dark:text-purple-400 hover:text-white rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer" 
                            title="Perpanjang Lisensi: Tambah durasi hari aktif paket SaaS venue ini.">
                      <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i>
                      <span>Perpanjang</span>
                    </button>
                    <button type="button" 
                            onclick="switchDashboardTab('tenants')" 
                            class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded-lg text-xs transition border border-slate-200 dark:border-slate-700 cursor-pointer" 
                            title="Lihat detail status lisensi dan manajemen seluruh venue mitra.">
                      Detail & Aksi &rarr;
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-6 text-center text-slate-400 dark:text-slate-500">Belum ada tenant yang terdaftar.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </section>

  <!-- ========================================================================= -->
  <!-- 2. TENANT MANAGEMENT (PERSIS PROTOTYPE MEDIA_1792398860804.HTML)          -->
  <!-- ========================================================================= -->
  <section id="section-tenants" class="space-y-6 hidden">

    <!-- TOP CONTROLS & ONBOARDING BUTTON -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h3 class="text-lg font-black text-slate-900 dark:text-white">Daftar Tenant & Status Lisensi</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400">Verifikasi, kontrol akses, perpanjangan sewa, dan audit venue lapangan futsal</p>
      </div>

      <button type="button" onclick="document.getElementById('modalOnboardTenant').classList.remove('hidden')" class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-extrabold text-xs shadow-lg shadow-purple-600/30 transition flex items-center gap-2 cursor-pointer self-start sm:self-auto">
        <i data-lucide="plus-circle" class="w-4 h-4"></i>
        <span>+ Onboarding Mitra Baru</span>
      </button>
    </div>

    <!-- FILTER & SEARCH BAR -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4 transition-colors">
      <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 text-xs">
        <button type="button" onclick="filterTenantsTable('all')" id="btnFilter-all" class="px-3.5 py-2 rounded-xl bg-purple-600 text-white font-bold whitespace-nowrap shadow-sm cursor-pointer">Semua Tenant</button>
        <button type="button" onclick="filterTenantsTable('active')" id="btnFilter-active" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium whitespace-nowrap transition cursor-pointer">Aktif</button>
        <button type="button" onclick="filterTenantsTable('grace')" id="btnFilter-grace" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-amber-600 dark:text-amber-400 font-medium whitespace-nowrap transition flex items-center gap-1 cursor-pointer">
          <span>Grace Period</span>
          <span class="w-2 h-2 rounded-full bg-amber-400"></span>
        </button>
        <button type="button" onclick="filterTenantsTable('suspended')" id="btnFilter-suspended" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-rose-600 dark:text-rose-400 font-medium whitespace-nowrap transition cursor-pointer">Suspended</button>
      </div>

      <div class="relative w-full md:w-80">
        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-3 text-slate-400"></i>
        <input type="text" id="tenantSearchInput" onkeyup="searchTenantsTable()" placeholder="Cari nama venue, pemilik, kota..." class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500">
      </div>
    </div>

    <!-- DATATABLE TENANT (PRD SPEC) -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs" id="tenantsMainTable">
          <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
            <tr>
              <th class="py-4 px-6">Venue & Pemilik</th>
              <th class="py-4 px-6">
                <span class="term-tooltip" data-bs-toggle="tooltip" title="Software as a Service (SaaS): Model langganan sewa aplikasi manajemen lapangan berbasis cloud tanpa perlu install software di komputer.">
                  <span>Paket SaaS</span>
                  <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
                </span>
              </th>
              <th class="py-4 px-6">
                <span class="term-tooltip" data-bs-toggle="tooltip" title="Sisa jumlah hari sebelum masa aktif lisensi aplikasi habis. Dihitung secara akumulatif saat diperpanjang.">
                  <span>Masa Aktif / Sisa</span>
                  <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
                </span>
              </th>
              <th class="py-4 px-6">Unit Lapangan</th>
              <th class="py-4 px-6">
                <span class="term-tooltip" data-bs-toggle="tooltip" title="Status lisensi: Active (aktif resmi), Grace Period (tenggang toleransi 3-7 hari), atau Suspended (dibekukan sementara).">
                  <span>Status Tenant</span>
                  <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
                </span>
              </th>
              <th class="py-4 px-6 text-center">Aksi / Tindakan</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
            @foreach($tenantsData as $t)
              @php
                $isSusp = ($t->status === 'suspended');
                $isGr = ($t->status === 'grace');
              @endphp
              <tr class="tenant-row hover:bg-slate-50 dark:hover:bg-slate-800/40 transition" id="tenantRow-{{ $t->id }}" data-status="{{ $t->status }}" data-name="{{ strtolower($t->name) }}" data-owner="{{ strtolower($t->owner->name ?? '') }}" data-city="{{ strtolower($t->city) }}">
                <td class="py-4 px-6">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 shrink-0 flex items-center justify-center">
                      @if(!empty($t->logo_image))
                        <img src="{{ $t->logo_image }}" class="w-full h-full object-cover" alt="{{ $t->name }}" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                        <span class="hidden font-black text-xs text-purple-600 dark:text-purple-400">{{ substr($t->name, 0, 2) }}</span>
                      @else
                        <span class="font-black text-xs text-purple-600 dark:text-purple-400">{{ substr($t->name, 0, 2) }}</span>
                      @endif
                    </div>
                    <div>
                      <span class="font-extrabold text-sm text-slate-900 dark:text-white block">{{ $t->name }}</span>
                      <span class="text-slate-500 dark:text-slate-400 text-[11px]">{{ $t->owner->name ?? 'Pemilik' }} • {{ $t->phone_wa }} • {{ $t->city }}</span>
                    </div>
                  </div>
                </td>
                <td class="py-4 px-6">
                  <span class="bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 px-2.5 py-1 rounded-lg font-bold text-[11px]">
                    {{ $t->plan_name }}
                  </span>
                </td>
                <td class="py-4 px-6">
                  <span class="text-slate-900 dark:text-white font-bold block">{{ $t->days_left }} Hari Tersisa</span>
                  <span class="text-slate-500 dark:text-slate-400 text-[10px]">Lisensi Aktif</span>
                </td>
                <td class="py-4 px-6 text-slate-600 dark:text-slate-300 font-semibold">{{ $t->courts->count() }} Lapangan</td>
                <td class="py-4 px-6" id="tenantStatusCol-{{ $t->id }}">
                  @if($isSusp)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/30 font-bold text-[10px] cursor-help" data-bs-toggle="tooltip" title="Akun venue dibekukan sementara karena belum memperpanjang paket sewa aplikasi.">
                      <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> SUSPENDED
                    </span>
                  @elseif($isGr)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/30 font-bold text-[10px] cursor-help" data-bs-toggle="tooltip" title="Grace Period: Masa tenggang toleransi (3-7 hari). Venue masih bisa beroperasi sementara sembari menunggu pembayaran.">
                      <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> GRACE PERIOD
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 font-bold text-[10px] cursor-help" data-bs-toggle="tooltip" title="Lisensi resmi aktif. Venue dapat mengakses seluruh modul lapangan, kasir, dan booking online.">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> ACTIVE
                    </span>
                  @endif
                </td>
                <td class="py-4 px-6 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <!-- Tombol Perpanjang Cepat -->
                    <button type="button" 
                            onclick="openExtendModal(this)" 
                            data-id="{{ $t->id }}"
                            data-name="{{ $t->name }}"
                            data-package-id="{{ $t->subscriptions->sortByDesc('end_date')->first()?->package_id ?? 1 }}"
                            class="px-2.5 py-1.5 rounded-xl bg-purple-500/10 hover:bg-purple-600 text-purple-600 dark:text-purple-300 hover:text-white font-bold text-xs transition flex items-center gap-1.5 cursor-pointer shadow-sm"
                            title="Perpanjang Lisensi SaaS">
                      <i data-lucide="calendar-plus" class="w-3.5 h-3.5"></i>
                      <span>Perpanjang</span>
                    </button>

                    <!-- Tombol Menu Aksi / Tindakan Lengkap -->
                    <button type="button" 
                            onclick="openTenantActionMenu(this)" 
                            data-id="{{ $t->id }}"
                            data-name="{{ $t->name }}"
                            data-owner="{{ $t->owner->name ?? 'Pemilik' }}"
                            data-email="{{ $t->owner->email ?? '' }}"
                            data-phone="{{ $t->phone_wa }}"
                            data-city="{{ $t->city }}"
                            data-status="{{ $t->status }}"
                            data-plan="{{ $t->plan_name }}"
                            data-days="{{ $t->days_left }}"
                            data-package-id="{{ $t->subscriptions->sortByDesc('end_date')->first()?->package_id ?? 1 }}"
                            data-slug="{{ $t->slug }}"
                            class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs transition border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 cursor-pointer shadow-sm"
                            title="Buka Pilihan Tindakan & Kelola Tenant">
                      <i data-lucide="more-horizontal" class="w-3.5 h-3.5 text-slate-500"></i>
                      <span>Aksi / Tindakan</span>
                      <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </section>

  <!-- ========================================================================= -->
  <!-- 3. SAAS PACKAGE MASTER (PERSIS PROTOTYPE MEDIA_1792398872888.HTML)        -->
  <!-- ========================================================================= -->
  <section id="section-packages" class="space-y-8 hidden">

    <!-- 1. TABEL DAFTAR MASTER PAKET SAAT INI -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors">
      <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
        <div>
          <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
            <i data-lucide="list-checks" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i> Paket Langganan SaaS Aktif
          </h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Paket yang saat ini tampil di landing page dan portal perpanjangan tenant</p>
        </div>
        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
          {{ $saasPackages->count() }} Paket Terdaftar
        </span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
            <tr>
              <th class="py-3.5 px-6">Nama Paket</th>
              <th class="py-3.5 px-6">Durasi Sewa</th>
              <th class="py-3.5 px-6">Harga Langganan</th>
              <th class="py-3.5 px-6">
                <span class="term-tooltip" data-bs-toggle="tooltip" title="Batas maksimal jumlah lapangan yang dapat didaftarkan oleh venue pada paket ini (misal: 2 unit, 5 unit, atau unlimited).">
                  <span>Limit Lapangan</span>
                  <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
                </span>
              </th>
              <th class="py-3.5 px-6">
                <span class="term-tooltip" data-bs-toggle="tooltip" title="Akses modul analitik cerdas untuk rekomendasi bisnis dan analisis okupansi otomatis.">
                  <span>Fitur Analitik Bisnis</span>
                  <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
                </span>
              </th>
              <th class="py-3.5 px-6">
                <span class="term-tooltip" data-bs-toggle="tooltip" title="Status ketersediaan paket: Published berarti muncul di halaman registrasi tenant dan portal perpanjangan.">
                  <span>Status Publik</span>
                  <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
                </span>
              </th>
              <th class="py-3.5 px-6 text-center">Aksi Konfigurasi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
            @foreach($saasPackages as $pkg)
              @php
                $cycleLabel = match(strtolower($pkg->billing_cycle ?? 'bulanan')) {
                  'mingguan', '7' => '7 Hari',
                  'tahunan', '365' => '365 Hari',
                  default => '30 Hari'
                };
              @endphp
              <tr id="pkg-row-{{ $pkg->id }}" class="pkg-table-row hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                <td class="py-4 px-6">
                  <span class="font-bold text-slate-900 dark:text-white text-sm block">{{ $pkg->name }}</span>
                  @if(!empty($pkg->badge_text))
                    <span class="text-purple-600 dark:text-purple-400 text-[10px] font-bold uppercase tracking-wider">{{ $pkg->badge_text }}</span>
                  @endif
                </td>
                <td class="py-4 px-6 text-slate-600 dark:text-slate-300 font-semibold">{{ $cycleLabel }}</td>
                <td class="py-4 px-6 font-extrabold text-purple-600 dark:text-purple-400 text-sm">Rp {{ number_format($pkg->price, 0, ',', '.') }}</td>
                <td class="py-4 px-6 text-slate-600 dark:text-slate-300 font-semibold">
                  {{ ($pkg->max_courts > 0 && $pkg->max_courts < 90) ? $pkg->max_courts . ' Unit' : 'Unlimited' }}
                </td>
                <td class="py-4 px-6">
                  @if($pkg->has_ai_analytics)
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1 text-[11px]">
                      <i data-lucide="check" class="w-3.5 h-3.5"></i> YA (Aktif)
                    </span>
                  @else
                    <span class="text-slate-400 font-bold text-[11px]">TIDAK</span>
                  @endif
                </td>
                <td class="py-4 px-6">
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">PUBLISHED</span>
                </td>
                <td class="py-4 px-6 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <button type="button" onclick="loadPackageToForm({{ $pkg->id }}, '{{ addslashes($pkg->name) }}', '{{ $pkg->billing_cycle ?? 'bulanan' }}', {{ (int)$pkg->price }}, {{ (int)$pkg->max_courts }}, {{ $pkg->has_ai_analytics ? 'true' : 'false' }}, true, true, {{ $pkg->is_popular ? 'true' : 'false' }}, '{{ addslashes($pkg->badge_text ?? '') }}')" class="px-3.5 py-1.5 bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/40 dark:hover:bg-purple-900/40 text-purple-600 dark:text-purple-300 font-bold rounded-xl text-xs transition border border-purple-500/30 flex items-center gap-1.5 cursor-pointer shadow-sm" data-bs-toggle="tooltip" title="Edit konfigurasi paket & lihat live preview kartu.">
                      <i data-lucide="sliders" class="w-3.5 h-3.5"></i>
                      <span>Edit & Pratinjau</span>
                    </button>
                    <form method="POST" action="{{ route('admin.packages.destroy', $pkg->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin mengarsipkan (soft delete) paket ini? Paket tidak akan tampil lagi di publik.')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/40 text-rose-600 dark:text-rose-400 font-bold rounded-xl text-xs transition border border-rose-500/30 cursor-pointer shadow-sm" data-bs-toggle="tooltip" title="Soft Delete: Mengarsipkan paket tanpa menghapus data secara permanen.">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- 2. CONFIG FORM & REAL-TIME PREVIEW CARD (PERSIS PRD SPEC MEDIA_1792398872888.HTML) -->
    <div id="package-form-container" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

      <!-- FORM CONFIG (7 COLS) -->
      <div class="lg:col-span-7 bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6 transition-colors">
        <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
          <div class="flex items-center justify-between">
            <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
              <i data-lucide="sliders" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
              <span>Form Konfigurasi Paket SaaS</span>
            </h3>
            <span id="activeEditingBadge" class="text-[10px] font-extrabold px-2.5 py-1 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20">
              Paket Aktif
            </span>
          </div>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Ubah field di bawah ini, pratinjau kartu di samping akan berubah secara instan secara real-time</p>
        </div>

        <form id="packageConfigForm" method="POST" action="" class="space-y-4 text-xs">
          @csrf
          <input type="hidden" id="configPkgId" name="package_id" value="">

          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Paket Langganan</label>
            <input id="input-pkg-name" name="name" type="text" value="Pro (Bulanan)" oninput="updateLivePreview()" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl font-bold text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Durasi Hari Sewa</label>
              <select id="input-pkg-duration" name="billing_cycle" onchange="updateLivePreview()" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl font-bold text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option value="mingguan">7 Hari (Mingguan)</option>
                <option value="bulanan" selected>30 Hari (Bulanan)</option>
                <option value="tahunan">365 Hari (Tahunan)</option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Harga Sewa (Rupiah)</label>
              <input id="input-pkg-price" name="price" type="number" value="150000" oninput="updateLivePreview()" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl font-mono font-bold text-purple-600 dark:text-purple-400 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Limit Jumlah Unit Lapangan</label>
              <input id="input-pkg-courts" name="max_courts" type="number" value="5" oninput="updateLivePreview()" required placeholder="0 untuk Unlimited" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl font-bold text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
              <span class="text-[10px] text-slate-400 mt-0.5 block">Ketik 0 atau 99 untuk Unlimited</span>
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Teks Badge Promo (Opsional)</label>
              <input id="input-pkg-badge" name="badge_text" type="text" value="Paling Populer" oninput="updateLivePreview()" placeholder="Misal: Paling Populer, Hemat 4 Bulan" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl font-bold text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
          </div>

          <!-- Fitur Limits Checklist -->
          <div class="pt-2">
            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-2">Limitasi & Hak Akses Fitur:</label>
            <div class="space-y-3 bg-slate-50 dark:bg-slate-800/60 p-4 rounded-2xl border border-slate-200 dark:border-slate-700">
              <label class="flex items-center gap-3 cursor-pointer">
                <input id="check-pkg-ai" name="has_ai_analytics" type="checkbox" value="1" checked onchange="updateLivePreview()" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-slate-300 dark:border-slate-600">
                <div>
                  <span class="text-slate-900 dark:text-slate-200 font-bold block">Modul Smart Advisory &amp; Analitik Finansial</span>
                  <span class="text-[10px] text-slate-500 dark:text-slate-400">Analisis tren okupansi lapangan dan proyeksi jam sibuk secara otomatis</span>
                </div>
              </label>

              <label class="flex items-center gap-3 cursor-pointer">
                <input id="check-pkg-banner" name="has_banner" type="checkbox" value="1" checked onchange="updateLivePreview()" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-slate-300 dark:border-slate-600">
                <div>
                  <span class="text-slate-900 dark:text-slate-200 font-bold block">Custom Upload Banner Hero & Logo Venue Tanpa Batas</span>
                  <span class="text-[10px] text-slate-500 dark:text-slate-400">Tampilan beranda venue eksklusif untuk menarik minat pemain futsal</span>
                </div>
              </label>

              <label class="flex items-center gap-3 cursor-pointer">
                <input id="check-pkg-matchmaking" name="has_matchmaking" type="checkbox" value="1" checked onchange="updateLivePreview()" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-slate-300 dark:border-slate-600">
                <div>
                  <span class="text-slate-900 dark:text-slate-200 font-bold block">Akses Modul Matchmaking (Open Slot & Sparing Tim)</span>
                  <span class="text-[10px] text-slate-500 dark:text-slate-400">Sistem otomatis mencari lawan tanding dan pemain tambahan tanpa admin manual</span>
                </div>
              </label>

              <label class="flex items-center gap-3 cursor-pointer">
                <input id="check-pkg-featured" name="is_popular" type="checkbox" value="1" checked onchange="updateLivePreview()" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500 border-slate-300 dark:border-slate-600">
                <div>
                  <span class="text-amber-600 dark:text-amber-400 font-extrabold block">Pasang Badge "Paling Populer" (Featured Highlight Card)</span>
                  <span class="text-[10px] text-slate-500 dark:text-slate-400">Memberikan sorotan utama pada pilihan paket ini di halaman registrasi</span>
                </div>
              </label>
            </div>
          </div>

          <div class="pt-3">
            <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-500 text-white font-black rounded-xl shadow-lg shadow-purple-600/30 transition flex items-center justify-center gap-2 cursor-pointer text-xs">
              <i data-lucide="check-circle" class="w-4 h-4"></i>
              <span>Simpan Konfigurasi Paket</span>
            </button>
          </div>
        </form>
      </div>

      <!-- CARD PREVIEW: TAMPILAN VISUAL DI HALAMAN TENANT (PRD SPEC) (5 COLS) -->
      <div class="lg:col-span-5 space-y-4 sticky top-20">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
            <i data-lucide="eye" class="w-4 h-4 text-purple-600 dark:text-purple-400"></i> Pratinjau Visual Card Tenant
          </span>
          <span class="text-[10px] bg-purple-500/10 text-purple-600 dark:text-purple-400 font-bold px-2 py-0.5 rounded-full border border-purple-500/20">Live Preview</span>
        </div>

        <!-- The Live Preview Card -->
        <div id="preview-card-container" class="bg-gradient-to-b from-slate-900 to-slate-950 text-white border-2 border-purple-500 rounded-3xl p-7 flex flex-col justify-between shadow-2xl relative transition-all duration-300">
          <div id="preview-badge" class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-[10px] font-black uppercase tracking-wider py-1 px-4 rounded-full shadow-lg border border-purple-400/40">
            Paling Populer
          </div>

          <div>
            <h4 id="preview-name" class="text-xl font-black text-white">Pro (Bulanan)</h4>
            <p id="preview-desc" class="text-xs text-slate-400 mt-1">Standar ideal operasional harian venue futsal aktif</p>

            <div class="my-6">
              <span id="preview-price" class="text-3xl font-black text-white">Rp 150.000</span>
              <span id="preview-duration" class="text-xs text-slate-400"> / 30 hari</span>
            </div>

            <ul class="space-y-3 text-xs text-slate-300">
              <li class="flex items-center gap-2.5">
                <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                <span id="preview-feat-courts">Maksimal 5 Unit Lapangan</span>
              </li>
              <li id="preview-feat-banner" class="flex items-center gap-2.5">
                <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                <span>Custom Banner Hero & Galeri Foto</span>
              </li>
              <li id="preview-feat-matchmaking" class="flex items-center gap-2.5">
                <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                <span>Modul Open Slot & Lobby Sparing</span>
              </li>
              <li id="preview-feat-ai" class="flex items-center gap-2.5">
                <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                <span><strong>Modul Rekomendasi Finansial &amp; Operasional</strong></span>
              </li>
            </ul>
          </div>

          <button type="button" class="w-full mt-8 py-3 rounded-xl bg-purple-600 text-white font-extrabold text-xs shadow-lg transition opacity-90 cursor-default flex items-center justify-center gap-2">
            <span>Pilih Paket Ini di Halaman Tenant</span>
          </button>
        </div>
      </div>

    </div>

  </section>

  <!-- ========================================================================= -->
  <!-- 4. AUDIT TRAIL, WEBHOOK & ARSIP DATA SOFT DELETE (SUBTAB SYSTEM)           -->
  <!-- ========================================================================= -->
  <section id="section-logs" class="space-y-6 hidden">

    <!-- STAT SUMMARY STRIP -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
      <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm transition-colors">
        <span class="text-slate-500 dark:text-slate-400 block text-[10px] uppercase font-bold term-tooltip" data-bs-toggle="tooltip" title="Catatan riwayat rekam jejak setiap aksi pengguna dan sistem (siapa melakukan apa, kapan, dan dari IP mana).">
          <span>Log Aktivitas Sistem</span>
          <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
        </span>
        <span class="text-xl font-black text-purple-600 dark:text-purple-400 mt-1 block">{{ isset($activityLogs) ? $activityLogs->count() : 0 }} Aksi</span>
      </div>
      <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm transition-colors">
        <span class="text-slate-500 dark:text-slate-400 block text-[10px] uppercase font-bold term-tooltip" data-bs-toggle="tooltip" title="Webhook Callbacks: Panggilan data realtime dari server gateway pembayaran (Midtrans) saat pemain berhasil melunasi sewa lapangan.">
          <span>Webhook Callbacks</span>
          <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
        </span>
        <span class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1 block">{{ $totalCallbacks }} Event</span>
      </div>
      <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm transition-colors">
        <span class="text-slate-500 dark:text-slate-400 block text-[10px] uppercase font-bold term-tooltip" data-bs-toggle="tooltip" title="Persentase sinyal webhook yang berhasil diproses oleh sistem tanpa kegagalan jaringan atau timeout.">
          <span>Pengiriman Sukses</span>
          <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
        </span>
        <span class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1 block">{{ $deliveryRate }}%</span>
      </div>
      <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm transition-colors">
        <span class="text-slate-500 dark:text-slate-400 block text-[10px] uppercase font-bold term-tooltip" data-bs-toggle="tooltip" title="Soft Delete: Arsip data terhapus semu. Data tidak dibuang permanen, sewaktu-waktu dapat dipulihkan (restore).">
          <span>Arsip Soft Delete</span>
          <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
        </span>
        <span class="text-xl font-black text-amber-600 dark:text-amber-400 mt-1 block">{{ (isset($trashedTenants) ? $trashedTenants->count() : 0) + (isset($trashedPackages) ? $trashedPackages->count() : 0) }} Entitas</span>
      </div>
    </div>

    <!-- SUB-TAB TOGGLES (PERSIS KETENTUAN TUGAS) -->
    <div class="bg-white dark:bg-slate-900 p-3 sm:p-4 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between gap-4 flex-wrap transition-colors">
      <div class="flex items-center gap-2 overflow-x-auto text-xs">
        <button type="button" onclick="switchLogSubtab('activity')" id="btnSubtab-activity" class="px-3.5 py-2 rounded-xl bg-purple-600 text-white font-bold whitespace-nowrap shadow-sm cursor-pointer transition flex items-center gap-2">
          <i data-lucide="shield-check" class="w-4 h-4"></i>
          <span>Log Aktivitas Sistem ({{ isset($activityLogs) ? $activityLogs->count() : 0 }})</span>
        </button>
        <button type="button" onclick="switchLogSubtab('payments')" id="btnSubtab-payments" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium whitespace-nowrap transition cursor-pointer flex items-center gap-2">
          <i data-lucide="activity" class="w-4 h-4"></i>
          <span>Log Webhook Transaksi ({{ $totalCallbacks }})</span>
        </button>
        <button type="button" onclick="switchLogSubtab('archive')" id="btnSubtab-archive" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium whitespace-nowrap transition cursor-pointer flex items-center gap-2">
          <i data-lucide="archive" class="w-4 h-4 text-amber-500"></i>
          <span>Arsip Soft Delete Platform ({{ (isset($trashedTenants) ? $trashedTenants->count() : 0) + (isset($trashedPackages) ? $trashedPackages->count() : 0) }})</span>
        </button>
      </div>
    </div>

    <!-- ================= SUBTAB 1: LOG AKTIVITAS SISTEM & AUDIT TRAIL ================= -->
    <div id="subtab-content-activity" class="space-y-4">
      <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors p-5">
        <div class="overflow-x-auto">
          <table class="datatable w-full text-left text-xs" id="tableActivityLogs">
            <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
              <tr>
                <th class="py-3.5 px-4">Waktu & Tanggal</th>
                <th class="py-3.5 px-4">Aktor / Pengguna</th>
                <th class="py-3.5 px-4">Tipe Aksi</th>
                <th class="py-3.5 px-4">Deskripsi Aktivitas</th>
                <th class="py-3.5 px-4">Target Model</th>
                <th class="py-3.5 px-4">IP Address & Device</th>
                <th class="py-3.5 px-4 text-center">Payload</th>
              </tr>
            </thead>
            <tbody id="activityTableBody" class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
              @if(isset($activityLogs) && $activityLogs->isNotEmpty())
                @foreach($activityLogs as $log)
                  @php
                    $actionLower = strtolower($log->action);
                    $badgeClass = 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-500/20';
                    if (str_contains($actionLower, 'created') || str_contains($actionLower, 'store')) {
                      $badgeClass = 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20';
                    } elseif (str_contains($actionLower, 'updated')) {
                      $badgeClass = 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-500/20';
                    } elseif (str_contains($actionLower, 'soft_deleted') || str_contains($actionLower, 'delete')) {
                      $badgeClass = 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20';
                    } elseif (str_contains($actionLower, 'restored')) {
                      $badgeClass = 'bg-teal-500/10 text-teal-600 dark:text-teal-400 border-teal-500/20';
                    } elseif (str_contains($actionLower, 'login')) {
                      $badgeClass = 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20';
                    }
                  @endphp
                  <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span class="font-bold text-slate-900 dark:text-white block">{{ \Carbon\Carbon::parse($log->created_at)->locale('id')->translatedFormat('d M Y, H:i:s') }}</span>
                      <span class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                    </td>
                    <td class="py-3.5 px-4">
                      <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-[10px] shrink-0">
                          {{ strtoupper(substr($log->user->name ?? 'SYS', 0, 2)) }}
                        </div>
                        <div class="truncate max-w-[130px]">
                          <span class="font-bold text-slate-900 dark:text-white block truncate">{{ $log->user->name ?? 'Sistem Otomatis' }}</span>
                          <span class="text-[10px] text-slate-400 font-semibold">{{ ucfirst($log->user->role ?? 'system') }}</span>
                        </div>
                      </div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $badgeClass }}">
                        {{ strtoupper($log->action) }}
                      </span>
                    </td>
                    <td class="py-3.5 px-4 max-w-xs text-slate-800 dark:text-slate-200">
                      <p class="truncate" title="{{ $log->description }}">{{ $log->description }}</p>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      @if($log->subject_type)
                        <span class="px-2 py-0.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-mono text-[11px] border border-slate-200 dark:border-slate-700">
                          {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                        </span>
                      @else
                        <span class="text-slate-400 text-[11px]">-</span>
                      @endif
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                      <span class="font-mono text-[11px] text-slate-600 dark:text-slate-300 block">{{ $log->ip_address ?? '127.0.0.1' }}</span>
                      <span class="text-[10px] text-slate-400 truncate max-w-[110px] block" title="{{ $log->user_agent }}">{{ substr($log->user_agent ?? 'Console', 0, 20) }}...</span>
                    </td>
                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                      @if(!empty($log->properties))
                        <button type="button" onclick="viewActivityPayload('{{ $log->action }}', '{{ addslashes($log->description) }}', '{{ addslashes($log->user->name ?? 'Sistem') }}', '{{ $log->ip_address }}', {{ json_encode($log->properties) }})" class="p-2 bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/40 dark:hover:bg-purple-900/40 text-purple-600 dark:text-purple-400 rounded-xl transition border border-purple-500/20 cursor-pointer shadow-sm" title="Lihat Payload Diff JSON">
                          <i data-lucide="code" class="w-3.5 h-3.5"></i>
                        </button>
                      @else
                        <span class="text-slate-400 text-[10px]">-</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ================= SUBTAB 2: LOG WEBHOOK & TRANSAKSI ================= -->
    <div id="subtab-content-payments" class="space-y-4 hidden">
      <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors p-5">
        <div class="overflow-x-auto">
          <table class="datatable w-full text-left text-xs" id="tableWebhookLogs">
            <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
              <tr>
                <th class="py-3.5 px-4">Payment ID & Ref</th>
                <th class="py-3.5 px-4">Tipe Pembayaran</th>
                <th class="py-3.5 px-4">Pelanggan / Venue</th>
                <th class="py-3.5 px-4 text-right">Nominal Transaksi</th>
                <th class="py-3.5 px-4">Status Gateway</th>
                <th class="py-3.5 px-4">Timestamp Callback</th>
                <th class="py-3.5 px-4 text-center">Payload Debugger</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
              @foreach($payments as $p)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                  <td class="py-3.5 px-4">
                    <span class="font-mono text-purple-600 dark:text-purple-400 font-bold block">#{{ $p->reference_id ?? 'PAY-'.$p->id }}</span>
                    <span class="text-[10px] text-slate-400 dark:text-slate-500">ID: {{ $p->id }}</span>
                  </td>
                  <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300 font-semibold">
                    @if($p->booking)
                      Booking Lapangan
                    @elseif(str_starts_with($p->transaction_code, 'LK-INV-SLOT-') || ($p->payload['type'] ?? '') === 'open_slot_member')
                      Open Slot Mabar
                    @elseif(str_starts_with($p->transaction_code, 'LK-SPAR-') || str_contains($p->payload['type'] ?? '', 'sparring'))
                      Match Sparing
                    @elseif($p->subscription)
                      Langganan SaaS
                    @else
                      Pembayaran Futsal
                    @endif
                  </td>
                  <td class="py-3.5 px-4">
                    <span class="font-bold text-slate-900 dark:text-white block">
                      {{ $p->booking->user->name ?? ($p->payload['paid_by'] ?? ($p->payload['user_name'] ?? ($p->payload['customer_details']['full_name'] ?? ($p->subscription->tenant->name ?? 'Pengguna')))) }}
                    </span>
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-semibold">
                      {{ $p->payment_channel }}
                    </span>
                  </td>
                  <td class="py-3.5 px-4 text-right font-extrabold text-slate-900 dark:text-white font-mono">
                    Rp {{ number_format($p->amount, 0, ',', '.') }}
                  </td>
                  <td class="py-3.5 px-4">
                    @if(in_array($p->status, ['paid', 'settlement', 'success']))
                      <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">SUCCESS</span>
                    @elseif(in_array($p->status, ['pending']))
                      <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">PENDING</span>
                    @else
                      <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">FAILED</span>
                    @endif
                  </td>
                  <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400">
                    {{ \Carbon\Carbon::parse($p->created_at)->locale('id')->translatedFormat('d M Y, H:i') }} WIB
                  </td>
                  <td class="py-3.5 px-4 text-center">
                    <button type="button" onclick="viewWebhookPayload('{{ $p->transaction_code ?? 'PAY-'.$p->id }}', '{{ $p->status }}', '{{ $p->payment_channel }}', {{ $p->amount }})" class="p-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl transition border border-slate-200 dark:border-slate-700 cursor-pointer" title="Inspect Raw Payload">
                      <i data-lucide="code" class="w-3.5 h-3.5"></i>
                    </button>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ================= SUBTAB 3: ARSIP DATA SOFT DELETE (archived=1) ================= -->
    <div id="subtab-content-archive" class="space-y-6 hidden">
      <!-- Trashed Tenants -->
      <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors p-5 space-y-4">
        <div class="border-b border-slate-200 dark:border-slate-800 pb-3 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <i data-lucide="store" class="w-5 h-5 text-amber-500"></i>
            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Arsip Venue Mitra yang Di-Soft Delete ({{ isset($trashedTenants) ? $trashedTenants->count() : 0 }})</h3>
          </div>
          <span class="text-xs text-slate-400">Status: archived=1 (Histori transaksi booking & langganan tetap utuh)</span>
        </div>
        <div class="overflow-x-auto">
          <table class="datatable w-full text-left text-xs" id="tableTrashedTenants">
            <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
              <tr>
                <th class="py-3 px-4">Venue Lapangan</th>
                <th class="py-3 px-4">Pemilik</th>
                <th class="py-3 px-4">Kota</th>
                <th class="py-3 px-4">Waktu Soft Delete</th>
                <th class="py-3 px-4">Flag</th>
                <th class="py-3 px-4 text-center">Tindakan</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
              @if(isset($trashedTenants) && $trashedTenants->isNotEmpty())
                @foreach($trashedTenants as $tt)
                  <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                    <td class="py-3.5 px-4 font-bold text-slate-700 dark:text-slate-300 line-through">
                      {{ $tt->name }}
                    </td>
                    <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300">
                      {{ $tt->owner->name ?? 'Pemilik' }}
                    </td>
                    <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300">
                      {{ $tt->city }}
                    </td>
                    <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400">
                      {{ \Carbon\Carbon::parse($tt->deleted_at)->locale('id')->translatedFormat('d M Y, H:i') }} WIB
                    </td>
                    <td class="py-3.5 px-4">
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                        Archived (1)
                      </span>
                    </td>
                    <td class="py-3.5 px-4 text-center">
                      <form method="POST" action="{{ route('admin.tenants.restore', $tt->id) }}">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs transition flex items-center gap-1.5 mx-auto shadow-sm cursor-pointer" title="Pulihkan Venue">
                          <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                          <span>Pulihkan (Restore)</span>
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>

      <!-- Trashed Packages -->
      <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm transition-colors p-5 space-y-4">
        <div class="border-b border-slate-200 dark:border-slate-800 pb-3 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <i data-lucide="package" class="w-5 h-5 text-amber-500"></i>
            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Arsip Paket SaaS yang Di-Soft Delete ({{ isset($trashedPackages) ? $trashedPackages->count() : 0 }})</h3>
          </div>
          <span class="text-xs text-slate-400">Status: archived=1 (Paket tidak lagi dijual ke umum)</span>
        </div>
        <div class="overflow-x-auto">
          <table class="datatable w-full text-left text-xs" id="tableTrashedPackages">
            <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
              <tr>
                <th class="py-3 px-4">Nama Paket</th>
                <th class="py-3 px-4">Siklus</th>
                <th class="py-3 px-4 text-right">Harga</th>
                <th class="py-3 px-4">Waktu Soft Delete</th>
                <th class="py-3 px-4">Flag</th>
                <th class="py-3 px-4 text-center">Tindakan</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
              @if(isset($trashedPackages) && $trashedPackages->isNotEmpty())
                @foreach($trashedPackages as $tp)
                  <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                    <td class="py-3.5 px-4 font-bold text-slate-700 dark:text-slate-300 line-through">
                      {{ $tp->name }}
                    </td>
                    <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300 font-semibold uppercase text-[11px]">
                      {{ $tp->billing_cycle }}
                    </td>
                    <td class="py-3.5 px-4 text-right font-extrabold text-slate-900 dark:text-white font-mono">
                      Rp {{ number_format($tp->price, 0, ',', '.') }}
                    </td>
                    <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400">
                      {{ \Carbon\Carbon::parse($tp->deleted_at)->locale('id')->translatedFormat('d M Y, H:i') }} WIB
                    </td>
                    <td class="py-3.5 px-4">
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                        Archived (1)
                      </span>
                    </td>
                    <td class="py-3.5 px-4 text-center">
                      <form method="POST" action="{{ route('admin.packages.restore', $tp->id) }}">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs transition flex items-center gap-1.5 mx-auto shadow-sm cursor-pointer" title="Pulihkan Paket">
                          <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                          <span>Pulihkan (Restore)</span>
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </section>

  <!-- ========================================================================= -->
  <!-- MODAL ONBOARDING MITRA BARU                                               -->
  <!-- ========================================================================= -->
  <div id="modalOnboardTenant" class="fixed inset-0 z-50 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 max-w-lg w-full space-y-5 text-slate-900 dark:text-white shadow-2xl">
      <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
        <h3 class="font-black text-base flex items-center gap-2">
          <i data-lucide="store" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
          <span>Onboarding Mitra Tenant Baru</span>
        </h3>
        <button type="button" onclick="document.getElementById('modalOnboardTenant').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-700 dark:hover:text-white">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form method="POST" action="{{ route('admin.tenants.store') }}" class="space-y-4 text-xs">
        @csrf
        <div>
          <label class="block text-slate-600 dark:text-slate-400 mb-1 font-bold">Nama Venue Lapangan <span class="text-rose-500">*</span></label>
          <input type="text" name="name" required autofocus placeholder="Contoh: Garuda Futsal Arena" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500">
        </div>
        <div>
          <label class="block text-slate-600 dark:text-slate-400 mb-1 font-bold">Nama Pemilik / Pengelola <span class="text-rose-500">*</span></label>
          <input type="text" name="owner_name" required placeholder="Contoh: Bpk. Bambang Pamungkas" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-slate-600 dark:text-slate-400 mb-1 font-bold">Email Akun Login <span class="text-rose-500">*</span></label>
            <input type="email" name="email" required placeholder="owner@garuda.com" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500">
          </div>
          <div>
            <label class="block text-slate-600 dark:text-slate-400 mb-1 font-bold">No. WhatsApp <span class="text-rose-500">*</span></label>
            <input type="text" name="phone_wa" required placeholder="08123456789" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500">
          </div>
        </div>
        <div>
          <label class="block text-slate-600 dark:text-slate-400 mb-1 font-bold">Kata Sandi Akun Pemilik <span class="text-rose-500">*</span></label>
          <input type="password" name="password" required minlength="6" placeholder="Tentukan kata sandi akun pemilik (min. 6 karakter)..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500">
          <p class="text-[10px] text-slate-400 mt-1">Bebas ditentukan oleh admin atau pemilik venue (tidak lagi default ke password123).</p>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-slate-600 dark:text-slate-400 mb-1 font-bold">Kota Venue <span class="text-rose-500">*</span></label>
            <input type="text" name="city" required placeholder="Bandung" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500">
          </div>
          <div>
            <label class="block text-slate-600 dark:text-slate-400 mb-1 font-bold">Pilihan Paket Awal <span class="text-rose-500">*</span></label>
            <select name="package_id" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500">
              @foreach($saasPackages as $p)
                <option value="{{ $p->id }}">{{ $p->name }} (Rp {{ number_format($p->price) }})</option>
              @endforeach
            </select>
          </div>
        </div>
        <div>
          <label class="block text-slate-600 dark:text-slate-400 mb-1 font-bold">Alamat Lengkap Venue <span class="text-slate-400 font-normal">(Opsional)</span></label>
          <textarea name="address" rows="2" placeholder="Jl. Soekarno Hatta No. 12, Bandung..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500"></textarea>
        </div>

        <div class="pt-2 flex justify-end gap-2">
          <button type="button" onclick="document.getElementById('modalOnboardTenant').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold transition">Batal</button>
          <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-extrabold shadow-md">Simpan & Aktifkan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MODAL EDIT PAKET SAAS (UPGRADED WITH RICH CONFIGURATION)                  -->
  <!-- ========================================================================= -->
  <div id="modalEditPackage" class="fixed inset-0 z-50 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 max-w-lg w-full space-y-5 text-slate-900 dark:text-white shadow-2xl">
      <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
        <h3 class="font-black text-base flex items-center gap-2">
          <i data-lucide="edit" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
          <span>Edit Pengaturan Paket SaaS</span>
        </h3>
        <button type="button" onclick="document.getElementById('modalEditPackage').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-700 dark:hover:text-white">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form id="editPackageForm" method="POST" action="" class="space-y-4 text-xs">
        @csrf
        <div>
          <label class="block text-slate-600 dark:text-slate-400 mb-1 font-bold">Nama Paket <span class="text-rose-500">*</span></label>
          <input type="text" id="editPkgName" name="name" required autofocus placeholder="Contoh: Pro Bulanan" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500 font-bold">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-slate-600 dark:text-slate-400 mb-1 font-bold">Durasi Sewa <span class="text-rose-500">*</span></label>
            <select id="editPkgCycle" name="billing_cycle" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500 font-bold">
              <option value="mingguan">7 Hari (Mingguan)</option>
              <option value="bulanan">30 Hari (Bulanan)</option>
              <option value="tahunan">365 Hari (Tahunan)</option>
            </select>
          </div>
          <div>
            <label class="block text-slate-600 dark:text-slate-400 mb-1 font-bold">Harga Langganan (Rp) <span class="text-rose-500">*</span></label>
            <input type="number" id="editPkgPrice" name="price" required placeholder="149000" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500 font-mono font-bold text-purple-600 dark:text-purple-400">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-slate-600 dark:text-slate-400 mb-1 font-bold">Limit Unit Lapangan <span class="text-rose-500">*</span></label>
            <input type="number" id="editPkgMaxCourts" name="max_courts" required placeholder="5 (0 untuk unlimited)" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500 font-bold">
          </div>
          <div>
            <label class="block text-slate-600 dark:text-slate-400 mb-1 font-bold">Teks Badge Promo <span class="text-slate-400 font-normal">(Opsional)</span></label>
            <input type="text" id="editPkgBadge" name="badge_text" placeholder="Misal: Paling Populer" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-purple-500 font-bold">
          </div>
        </div>

        <div class="space-y-2 pt-2 bg-slate-50 dark:bg-slate-800/60 p-3.5 rounded-2xl border border-slate-200 dark:border-slate-700">
          <label class="flex items-center gap-2.5 cursor-pointer">
            <input type="checkbox" id="editPkgAi" name="has_ai_analytics" value="1" class="rounded bg-slate-50 dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-purple-600 focus:ring-purple-500 w-4 h-4">
            <span class="text-slate-800 dark:text-slate-200 font-bold text-[11px]">Akses Modul Analitik Bisnis</span>
          </label>
          <label class="flex items-center gap-2.5 cursor-pointer">
            <input type="checkbox" id="editPkgBanner" name="has_banner" value="1" checked class="rounded bg-slate-50 dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-purple-600 focus:ring-purple-500 w-4 h-4">
            <span class="text-slate-800 dark:text-slate-200 font-bold text-[11px]">Upload Custom Banner Hero & Logo Venue</span>
          </label>
          <label class="flex items-center gap-2.5 cursor-pointer">
            <input type="checkbox" id="editPkgMatch" name="has_matchmaking" value="1" checked class="rounded bg-slate-50 dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-purple-600 focus:ring-purple-500 w-4 h-4">
            <span class="text-slate-800 dark:text-slate-200 font-bold text-[11px]">Modul Open Slot & Lobby Sparing</span>
          </label>
          <label class="flex items-center gap-2.5 cursor-pointer">
            <input type="checkbox" id="editPkgPopular" name="is_popular" value="1" class="rounded bg-slate-50 dark:bg-slate-800 border-slate-300 dark:border-slate-700 text-purple-600 focus:ring-purple-500 w-4 h-4">
            <span class="text-amber-600 dark:text-amber-400 font-extrabold text-[11px]">Pasang Pita "Paling Populer" (Featured)</span>
          </label>
        </div>

        <div class="pt-2 flex justify-end gap-2">
          <button type="button" onclick="document.getElementById('modalEditPackage').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold transition">Batal</button>
          <button type="submit" class="px-5 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-extrabold shadow-md">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MODAL WEBHOOK PAYLOAD DEBUGGER                                            -->
  <!-- ========================================================================= -->
  <div id="modalWebhookPayload" class="fixed inset-0 z-50 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 max-w-xl w-full space-y-4 text-slate-900 dark:text-white shadow-2xl">
      <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
        <h3 class="font-black text-base flex items-center gap-2">
          <i data-lucide="terminal" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
          <span>Raw Webhook Payload Inspector</span>
        </h3>
        <button type="button" onclick="document.getElementById('modalWebhookPayload').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-700 dark:hover:text-white">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <pre id="webhookPayloadContent" class="p-4 bg-slate-950 border border-slate-800 rounded-2xl text-[11px] font-mono text-emerald-400 overflow-x-auto max-h-64"></pre>

      <div class="flex justify-end">
        <button type="button" onclick="document.getElementById('modalWebhookPayload').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold">Tutup Inspector</button>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MODAL ACTIVITY LOG PAYLOAD & DIFF INSPECTOR                               -->
  <!-- ========================================================================= -->
  <div id="modalActivityPayload" class="fixed inset-0 z-50 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 max-w-xl w-full space-y-4 text-slate-900 dark:text-white shadow-2xl">
      <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
        <div class="flex items-center gap-2">
          <i data-lucide="shield-check" class="w-5 h-5 text-purple-600 dark:text-purple-400"></i>
          <div>
            <h3 class="font-black text-base" id="activityModalAction">Detail Log Aktivitas</h3>
            <span class="text-[11px] text-slate-500 dark:text-slate-400" id="activityModalActor">Aktor: -</span>
          </div>
        </div>
        <button type="button" onclick="document.getElementById('modalActivityPayload').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-700 dark:hover:text-white">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <div>
        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300" id="activityModalDesc"></p>
        <span class="text-[10px] font-mono text-slate-400 mt-0.5 block" id="activityModalIp">IP: 127.0.0.1</span>
      </div>

      <div class="space-y-1.5">
        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Payload Changes / Snapshot JSON</label>
        <pre id="activityPayloadContent" class="p-4 bg-slate-950 border border-slate-800 rounded-2xl text-[11px] font-mono text-emerald-400 overflow-x-auto max-h-72"></pre>
      </div>

      <div class="flex justify-end pt-1">
        <button type="button" onclick="document.getElementById('modalActivityPayload').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold transition cursor-pointer">Tutup Inspector</button>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MODAL AKSI & TINDAKAN LENGKAP TENANT (SUPER ADMIN MENU)                   -->
  <!-- ========================================================================= -->
  <div id="modalTenantActionMenu" class="fixed inset-0 z-50 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 max-w-lg w-full space-y-5 text-slate-900 dark:text-white shadow-2xl animate-in fade-in zoom-in-95 duration-150">
      
      <!-- Header Modal -->
      <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
        <div class="flex items-center gap-3">
          <div class="w-11 h-11 rounded-2xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold shrink-0">
            <i data-lucide="store" class="w-6 h-6"></i>
          </div>
          <div class="min-w-0">
            <h3 class="font-black text-base text-slate-900 dark:text-white truncate" id="actionModalTenantName">Nama Venue</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 truncate" id="actionModalTenantOwner">Pemilik: - • Kota: -</p>
          </div>
        </div>
        <button type="button" onclick="closeTenantActionMenu()" class="p-2 text-slate-400 hover:text-slate-700 dark:hover:text-white rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer shrink-0">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <!-- Info Ringkas Status Tenant -->
      <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs">
        <div>
          <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Paket Lisensi:</span>
          <span class="font-extrabold text-purple-600 dark:text-purple-400" id="actionModalTenantPlan">-</span>
        </div>
        <div>
          <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Sisa Masa Aktif:</span>
          <span class="font-extrabold text-slate-900 dark:text-white" id="actionModalTenantDays">- Hari</span>
        </div>
        <div>
          <span class="text-slate-500 dark:text-slate-400 block text-[11px]">Status Akun:</span>
          <span class="px-2 py-0.5 rounded-full font-bold text-[10px]" id="actionModalTenantStatusBadge">ACTIVE</span>
        </div>
      </div>

      <!-- 1. Tampilan Utama: Daftar Pilihan Tindakan (Action List) -->
      <div id="actionMenuListView" class="space-y-2.5">
        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pilih Tindakan / Aksi:</label>

        <!-- 1. Perpanjang Lisensi SaaS -->
        <button type="button" id="btnActionExtend" class="w-full p-3 rounded-2xl border border-purple-500/30 bg-purple-500/5 hover:bg-purple-500/15 text-left transition flex items-center justify-between group cursor-pointer">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
              <i data-lucide="calendar-plus" class="w-4 h-4"></i>
            </div>
            <div>
              <span class="text-xs font-bold text-slate-900 dark:text-white block group-hover:text-purple-500">Perpanjang Lisensi SaaS</span>
              <span class="text-[11px] text-slate-500 dark:text-slate-400 block">Tambah masa aktif lisensi (+7, +30, +90, +365 hari)</span>
            </div>
          </div>
          <i data-lucide="chevron-right" class="w-4 h-4 text-purple-500"></i>
        </button>

        <!-- 2. Buka Halaman Publik Venue -->
        <a id="linkActionVenuePublic" href="#" target="_blank" class="w-full p-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-left transition flex items-center justify-between group cursor-pointer">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-blue-500/15 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
              <i data-lucide="external-link" class="w-4 h-4"></i>
            </div>
            <div>
              <span class="text-xs font-bold text-slate-900 dark:text-white block group-hover:text-blue-500">Lihat Halaman Venue (Web Pemain)</span>
              <span class="text-[11px] text-slate-500 dark:text-slate-400 block">Periksa katalog, foto venue, dan slot booking publik</span>
            </div>
          </div>
          <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
        </a>

        <!-- 3. Toggle Suspend / Aktifkan -->
        <button type="button" id="btnActionToggleStatus" onclick="handleToggleStatusClick()" class="w-full p-3 rounded-2xl border transition flex items-center justify-between group cursor-pointer text-left">
          <div class="flex items-center gap-3">
            <div id="iconActionToggleBox" class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0">
              <i id="iconActionToggle" data-lucide="ban" class="w-4 h-4"></i>
            </div>
            <div>
              <span id="textActionToggleTitle" class="text-xs font-bold block">Bekukan / Suspend Venue</span>
              <span id="textActionToggleSubtitle" class="text-[11px] text-slate-500 dark:text-slate-400 block">Nonaktifkan akses sementara untuk venue ini</span>
            </div>
          </div>
          <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
        </button>

        <!-- 4. Reset Password Pemilik (Buka Konfirmasi Modern) -->
        <button type="button" id="btnActionResetPassword" onclick="showTenantConfirm('reset-password')" class="w-full p-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-left transition flex items-center justify-between group cursor-pointer">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-amber-500/15 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
              <i data-lucide="key" class="w-4 h-4"></i>
            </div>
            <div>
              <span class="text-xs font-bold text-slate-900 dark:text-white block group-hover:text-amber-500">Reset Kata Sandi Akun Pemilik</span>
              <span class="text-[11px] text-slate-500 dark:text-slate-400 block">Kembalikan password akun ke default: "password123"</span>
            </div>
          </div>
          <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400"></i>
        </button>

        <!-- 5. Soft Delete / Arsip Venue (Buka Konfirmasi Modern) -->
        <button type="button" id="btnActionDestroy" onclick="showTenantConfirm('soft-delete')" class="w-full p-3 rounded-2xl border border-rose-500/20 bg-rose-500/5 hover:bg-rose-500/15 text-left transition flex items-center justify-between group cursor-pointer">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-rose-500/15 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
              <i data-lucide="trash-2" class="w-4 h-4"></i>
            </div>
            <div>
              <span class="text-xs font-bold text-rose-600 dark:text-rose-400 block">Arsipkan Venue (Soft Delete)</span>
              <span class="text-[11px] text-slate-500 dark:text-slate-400 block">Tandai archived tanpa menghapus riwayat transaksi</span>
            </div>
          </div>
          <i data-lucide="chevron-right" class="w-4 h-4 text-rose-500"></i>
        </button>

        <div class="pt-2 flex justify-end">
          <button type="button" onclick="closeTenantActionMenu()" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition cursor-pointer">
            Tutup Menu
          </button>
        </div>
      </div>

      <!-- 2. Tampilan Konfirmasi Aksi (Modern Inline Confirmation) -->
      <div id="actionMenuConfirmView" class="hidden space-y-4 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-850 animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-start gap-3.5">
          <div id="confirmViewIconBox" class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 font-bold">
            <i id="confirmViewIcon" data-lucide="alert-triangle" class="w-5 h-5"></i>
          </div>
          <div class="min-w-0 flex-1">
            <h4 id="confirmViewTitle" class="text-xs font-black uppercase tracking-wider">Konfirmasi Tindakan</h4>
            <div id="confirmViewMessage" class="text-xs text-slate-600 dark:text-slate-300 mt-1 leading-relaxed"></div>
          </div>
        </div>

        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-200 dark:border-slate-800">
          <button type="button" onclick="cancelTenantConfirm()" class="px-4 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs transition cursor-pointer">
            Batal
          </button>
          <button type="button" id="btnConfirmExecute" onclick="executeTenantConfirmedAction()" class="px-5 py-2.5 rounded-xl text-white font-extrabold text-xs transition flex items-center gap-2 cursor-pointer shadow-md">
            <span id="btnConfirmExecuteText">Konfirmasi</span>
          </button>
        </div>
      </div>

      <!-- Hidden Fallback Forms for Standard Non-JS Submissions -->
      <form id="formActionToggleStatus" method="POST" action="" class="hidden">
        @csrf
      </form>
      <form id="formActionResetPassword" method="POST" action="" class="hidden">
        @csrf
      </form>
      <form id="formActionDestroy" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
      </form>

    </div>
  </div>

  <!-- MODAL PERPANJANG LISENSI TENANT (SUPER ADMIN TOOL) -->
  <div id="modalExtendTenant" class="fixed inset-0 z-50 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 max-w-md w-full space-y-5 text-slate-900 dark:text-white shadow-2xl animate-in fade-in zoom-in-95 duration-150">
      <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
        <div class="flex items-center gap-2.5">
          <div class="w-9 h-9 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold">
            <i data-lucide="calendar-plus" class="w-5 h-5"></i>
          </div>
          <div>
            <h3 class="font-black text-base">Perpanjang Lisensi SaaS</h3>
            <span class="text-xs text-slate-500 dark:text-slate-400" id="extendTenantName">Nama Venue</span>
          </div>
        </div>
        <button type="button" onclick="document.getElementById('modalExtendTenant').classList.add('hidden')" class="p-1 text-slate-400 hover:text-slate-700 dark:hover:text-white">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form id="formExtendTenant" method="POST" action="" class="space-y-4">
        @csrf

        <!-- Pilih Paket SaaS -->
        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Pilihan Paket Lisensi:</label>
          <select id="extendPackageId" name="package_id" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            @foreach($saasPackages as $p)
              <option value="{{ $p->id }}">{{ $p->name }} (Rp {{ number_format($p->price, 0, ',', '.') }} / {{ $p->billing_cycle }})</option>
            @endforeach
          </select>
        </div>

        <!-- Preset Tambah Hari Cepat -->
        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Preset Durasi Cepat:</label>
          <div class="grid grid-cols-4 gap-2 text-xs">
            <button type="button" onclick="setExtendDays(7)" class="py-2 px-1 rounded-xl bg-slate-100 hover:bg-purple-100 dark:bg-slate-800 dark:hover:bg-purple-950/40 text-slate-700 dark:text-slate-300 font-bold text-center border border-slate-200 dark:border-slate-700 transition cursor-pointer">
              +7 Hari
            </button>
            <button type="button" onclick="setExtendDays(30)" class="py-2 px-1 rounded-xl bg-purple-500/15 hover:bg-purple-500/25 text-purple-600 dark:text-purple-400 font-black text-center border border-purple-500/30 transition cursor-pointer">
              +30 Hari
            </button>
            <button type="button" onclick="setExtendDays(90)" class="py-2 px-1 rounded-xl bg-slate-100 hover:bg-purple-100 dark:bg-slate-800 dark:hover:bg-purple-950/40 text-slate-700 dark:text-slate-300 font-bold text-center border border-slate-200 dark:border-slate-700 transition cursor-pointer">
              +90 Hari
            </button>
            <button type="button" onclick="setExtendDays(365)" class="py-2 px-1 rounded-xl bg-slate-100 hover:bg-purple-100 dark:bg-slate-800 dark:hover:bg-purple-950/40 text-slate-700 dark:text-slate-300 font-bold text-center border border-slate-200 dark:border-slate-700 transition cursor-pointer">
              +1 Tahun
            </button>
          </div>
        </div>

        <!-- Input Hari Kustom -->
        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tambahan Jumlah Hari (Akumulatif):</label>
          <div class="relative">
            <input type="number" id="extendDaysInput" name="days" value="30" min="1" max="1000" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
            <span class="absolute right-3.5 top-2.5 text-xs text-slate-400 font-semibold">Hari</span>
          </div>
          <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
            * Durasi akan otomatis diakumulasikan ke sisa hari lisensi aktif saat ini tanpa memotong masa aktif yang sudah berjalan.
          </p>
        </div>

        <!-- Submit Button -->
        <div class="pt-2 flex items-center justify-end gap-2.5">
          <button type="button" onclick="document.getElementById('modalExtendTenant').classList.add('hidden')" class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-bold transition cursor-pointer">
            Batal
          </button>
          <button type="submit" class="px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-extrabold text-xs shadow-lg shadow-purple-600/30 transition flex items-center gap-1.5 cursor-pointer">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            <span>Simpan &amp; Perpanjang</span>
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  // Render Chart.js dynamic adaptive colors for Dark & Light Mode
  window.renderSaasRevenueChart = function() {
    const canvas = document.getElementById('saasRevenueChart');
    if (!canvas) return;

    if (window.saasChartInstance) {
      window.saasChartInstance.destroy();
    }

    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? '#334155' : 'rgba(0, 0, 0, 0.06)';
    const tickColor = isDark ? '#94a3b8' : '#64748b';

    const ctx = canvas.getContext('2d');
    window.saasChartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober'],
        datasets: [{
          label: 'Revenue SaaS (Juta Rupiah)',
          data: @json($chartRevenueData),
          borderColor: '#a855f7',
          backgroundColor: isDark ? 'rgba(168, 85, 247, 0.12)' : 'rgba(168, 85, 247, 0.08)',
          fill: true,
          tension: 0.4,
          pointBackgroundColor: '#a855f7',
          pointRadius: 5
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function(context) {
                const valInJt = context.parsed.y;
                const rupiahVal = Math.round(valInJt * 1000000);
                return ' Revenue: Rp ' + rupiahVal.toLocaleString('id-ID');
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: gridColor },
            ticks: {
              color: tickColor,
              callback: function(v) { return 'Rp ' + Number(v).toFixed(2) + ' Jt'; }
            }
          },
          x: {
            grid: { color: gridColor },
            ticks: { color: tickColor }
          }
        }
      }
    });
  };

  // Load Package into Form & Real-time Live Preview Card
  function loadPackageToForm(id, name, cycle, price, maxCourts, hasAi, hasBanner, hasMatch, isPopular, badgeText) {
    const form = document.getElementById('packageConfigForm');
    if (form) form.action = '/admin/packages/' + id;
    
    const configId = document.getElementById('configPkgId');
    if (configId) configId.value = id;

    const inputName = document.getElementById('input-pkg-name');
    if (inputName) inputName.value = name;

    const inputDuration = document.getElementById('input-pkg-duration');
    if (inputDuration) {
      if (cycle === 'mingguan' || cycle === '7') inputDuration.value = 'mingguan';
      else if (cycle === 'tahunan' || cycle === '365') inputDuration.value = 'tahunan';
      else inputDuration.value = 'bulanan';
    }

    const inputPrice = document.getElementById('input-pkg-price');
    if (inputPrice) inputPrice.value = price;

    const inputCourts = document.getElementById('input-pkg-courts');
    if (inputCourts) inputCourts.value = maxCourts || 0;

    const inputBadge = document.getElementById('input-pkg-badge');
    if (inputBadge) inputBadge.value = badgeText || '';

    const checkAi = document.getElementById('check-pkg-ai');
    if (checkAi) checkAi.checked = !!hasAi;

    const checkBanner = document.getElementById('check-pkg-banner');
    if (checkBanner) checkBanner.checked = !!hasBanner;

    const checkMatch = document.getElementById('check-pkg-matchmaking');
    if (checkMatch) checkMatch.checked = !!hasMatch;

    const checkFeatured = document.getElementById('check-pkg-featured');
    if (checkFeatured) checkFeatured.checked = !!isPopular;

    const badgeStatus = document.getElementById('activeEditingBadge');
    if (badgeStatus) badgeStatus.innerText = 'Mengedit: ' + name;

    updateLivePreview();

    // Visual highlight on selected table row
    document.querySelectorAll('.pkg-table-row').forEach(r => r.classList.remove('bg-purple-500/10', 'border-l-4', 'border-purple-500'));
    const activeRow = document.getElementById('pkg-row-' + id);
    if (activeRow) activeRow.classList.add('bg-purple-500/10', 'border-l-4', 'border-purple-500');

    if (window.lucide) lucide.createIcons();
  }

  function updateLivePreview() {
    const nameEl = document.getElementById('input-pkg-name');
    const name = nameEl ? nameEl.value : 'Nama Paket';

    const durationEl = document.getElementById('input-pkg-duration');
    const cycle = durationEl ? durationEl.value : 'bulanan';

    const priceEl = document.getElementById('input-pkg-price');
    const price = parseInt(priceEl ? priceEl.value : 0) || 0;

    const courtsEl = document.getElementById('input-pkg-courts');
    const courts = parseInt(courtsEl ? courtsEl.value : 0) || 0;

    const badgeTextEl = document.getElementById('input-pkg-badge');
    const badgeText = badgeTextEl ? badgeTextEl.value.trim() : '';

    const isFeaturedEl = document.getElementById('check-pkg-featured');
    const isFeatured = isFeaturedEl ? isFeaturedEl.checked : false;

    const hasAi = document.getElementById('check-pkg-ai') ? document.getElementById('check-pkg-ai').checked : false;
    const hasBanner = document.getElementById('check-pkg-banner') ? document.getElementById('check-pkg-banner').checked : false;
    const hasMatch = document.getElementById('check-pkg-matchmaking') ? document.getElementById('check-pkg-matchmaking').checked : false;

    // Update Live Card
    const prevName = document.getElementById('preview-name');
    if (prevName) prevName.innerText = name;

    let cycleText = ' / 30 hari';
    if (cycle === 'mingguan' || cycle === '7') cycleText = ' / 7 hari';
    else if (cycle === 'tahunan' || cycle === '365') cycleText = ' / 365 hari';
    const prevDur = document.getElementById('preview-duration');
    if (prevDur) prevDur.innerText = cycleText;

    const prevPrice = document.getElementById('preview-price');
    if (prevPrice) prevPrice.innerText = 'Rp ' + price.toLocaleString('id-ID');

    // Badge
    const badgeEl = document.getElementById('preview-badge');
    if (badgeEl) {
      if (isFeatured || badgeText.length > 0) {
        badgeEl.innerText = badgeText || 'Paling Populer';
        badgeEl.classList.remove('hidden');
      } else {
        badgeEl.classList.add('hidden');
      }
    }

    // Court limit feature
    const prevCourts = document.getElementById('preview-feat-courts');
    if (prevCourts) {
      prevCourts.innerText = (courts <= 0 || courts >= 90) ? 'Unlimited Unit Lapangan Futsal' : `Maksimal ${courts} Unit Lapangan`;
    }

    // AI feature
    const featAi = document.getElementById('preview-feat-ai');
    if (featAi) featAi.style.display = hasAi ? 'flex' : 'none';

    // Banner feature
    const featBanner = document.getElementById('preview-feat-banner');
    if (featBanner) featBanner.style.display = hasBanner ? 'flex' : 'none';

    // Matchmaking feature
    const featMatch = document.getElementById('preview-feat-matchmaking');
    if (featMatch) featMatch.style.display = hasMatch ? 'flex' : 'none';
  }

  document.addEventListener('DOMContentLoaded', function() {
    window.renderSaasRevenueChart();

    // Auto re-render chart saat beralih mode gelap / terang agar selalu jernih dan beradaptasi warna
    const themeObserver = new MutationObserver(function() {
      if (typeof window.renderSaasRevenueChart === 'function') {
        window.renderSaasRevenueChart();
      }
    });
    themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    // Auto-load first or popular package into live editor form
    @if($saasPackages->isNotEmpty())
      @php
        $popularPkg = $saasPackages->firstWhere('is_popular', true) ?? $saasPackages->first();
      @endphp
      loadPackageToForm(
        {{ $popularPkg->id }},
        '{{ addslashes($popularPkg->name) }}',
        '{{ $popularPkg->billing_cycle ?? 'bulanan' }}',
        {{ (int)$popularPkg->price }},
        {{ (int)$popularPkg->max_courts }},
        {{ $popularPkg->has_ai_analytics ? 'true' : 'false' }},
        true,
        true,
        {{ $popularPkg->is_popular ? 'true' : 'false' }},
        '{{ addslashes($popularPkg->badge_text ?? '') }}'
      );
    @endif
  });

  // Filter Tenants
  function filterTenantsTable(status) {
    const rows = document.querySelectorAll('.tenant-row');
    const btnIds = ['all', 'active', 'grace', 'suspended'];
    btnIds.forEach(id => {
      const b = document.getElementById('btnFilter-' + id);
      if (b) {
        if (id === status) {
          b.className = 'px-3.5 py-2 rounded-xl bg-purple-600 text-white font-bold whitespace-nowrap shadow-sm cursor-pointer';
        } else {
          b.className = 'px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium whitespace-nowrap transition cursor-pointer';
        }
      }
    });

    rows.forEach(r => {
      const s = r.getAttribute('data-status');
      if (status === 'all' || s === status) {
        r.style.display = '';
      } else {
        r.style.display = 'none';
      }
    });
  }

  function searchTenantsTable() {
    const q = document.getElementById('tenantSearchInput').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.tenant-row');
    rows.forEach(r => {
      const text = (r.getAttribute('data-name') + ' ' + r.getAttribute('data-owner') + ' ' + r.getAttribute('data-city')).toLowerCase();
      r.style.display = text.includes(q) ? '' : 'none';
    });
  }

  function openPackageEditorModal(id, name, price, maxCourts, hasAi, cycle = 'bulanan', badge = '', isPop = false) {
    const form = document.getElementById('editPackageForm');
    form.action = '/admin/packages/' + id;
    document.getElementById('editPkgName').value = name;
    document.getElementById('editPkgPrice').value = price;
    document.getElementById('editPkgMaxCourts').value = maxCourts;
    document.getElementById('editPkgAi').checked = !!hasAi;
    if (document.getElementById('editPkgCycle')) document.getElementById('editPkgCycle').value = cycle;
    if (document.getElementById('editPkgBadge')) document.getElementById('editPkgBadge').value = badge;
    if (document.getElementById('editPkgPopular')) document.getElementById('editPkgPopular').checked = !!isPop;
    document.getElementById('modalEditPackage').classList.remove('hidden');
    if (window.lucide) lucide.createIcons();
  }

  function viewWebhookPayload(refId, status, method, amount) {
    const payload = {
      event: "payment.callback.received",
      gateway: "Midtrans / Xendit Sandbox",
      reference_id: refId,
      status: status,
      payment_type: method,
      gross_amount: amount,
      currency: "IDR",
      signature_verified: true,
      delivered_at: new Date().toISOString()
    };
    document.getElementById('webhookPayloadContent').innerText = JSON.stringify(payload, null, 2);
    document.getElementById('modalWebhookPayload').classList.remove('hidden');
    if (window.lucide) lucide.createIcons();
  }

  // Switch Log Subtabs (Activity, Payments, Archive)
  function switchLogSubtab(tab) {
    const tabs = ['activity', 'payments', 'archive'];
    tabs.forEach(t => {
      const content = document.getElementById('subtab-content-' + t);
      const btn = document.getElementById('btnSubtab-' + t);
      if (content) {
        if (t === tab) {
          content.classList.remove('hidden');
        } else {
          content.classList.add('hidden');
        }
      }
      if (btn) {
        if (t === tab) {
          btn.className = 'px-3.5 py-2 rounded-xl bg-purple-600 text-white font-bold whitespace-nowrap shadow-sm cursor-pointer transition flex items-center gap-2';
        } else {
          btn.className = 'px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium whitespace-nowrap transition cursor-pointer flex items-center gap-2';
        }
      }
    });
    if (window.lucide) lucide.createIcons();
  }

  // View Activity Payload Modal
  function viewActivityPayload(action, desc, actor, ip, properties) {
    const modal = document.getElementById('modalActivityPayload');
    if (!modal) return;
    document.getElementById('activityModalAction').innerText = 'Aksi: ' + action.toUpperCase();
    document.getElementById('activityModalActor').innerText = 'Aktor: ' + actor;
    document.getElementById('activityModalDesc').innerText = desc;
    document.getElementById('activityModalIp').innerText = 'IP Address: ' + (ip || '127.0.0.1');
    document.getElementById('activityPayloadContent').innerText = JSON.stringify(properties || {}, null, 2);
    modal.classList.remove('hidden');
    if (window.lucide) lucide.createIcons();
  }

  // Tool Super Admin: Open Extend Modal (Mendukung button element atau direct parameters)
  function openExtendModal(target, nameFallback = '', packageIdFallback = 1) {
    let tenantId, tenantName, packageId;
    if (typeof target === 'object' && target !== null && target.getAttribute) {
      tenantId = target.getAttribute('data-id');
      tenantName = target.getAttribute('data-name');
      packageId = target.getAttribute('data-package-id') || 1;
    } else {
      tenantId = target;
      tenantName = nameFallback;
      packageId = packageIdFallback;
    }

    const modal = document.getElementById('modalExtendTenant');
    const form = document.getElementById('formExtendTenant');
    const nameEl = document.getElementById('extendTenantName');
    const pkgSelect = document.getElementById('extendPackageId');
    if (!modal || !form) return;

    form.action = '{{ url("admin/tenants") }}/' + tenantId + '/extend';
    if (nameEl) nameEl.innerText = tenantName;
    if (pkgSelect && packageId) {
      pkgSelect.value = packageId;
    }
    document.getElementById('extendDaysInput').value = 30;
    modal.classList.remove('hidden');
    if (window.lucide) lucide.createIcons();
  }

  function setExtendDays(days) {
    document.getElementById('extendDaysInput').value = days;
  }

  // Tool Super Admin: Open Full Tenant Action Menu Modal
  let currentActionTenantId = null;
  let currentActionPackageId = null;
  let currentActionTenantName = '';
  let currentActionTenantOwner = '';
  let currentActionTenantEmail = '';
  let currentActionTenantStatus = 'active';
  let currentPendingConfirmType = null;

  function openTenantActionMenu(target, nameFallback = '') {
    let id, name, owner, email, phone, city, status, plan, daysLeft, packageId, slug;

    if (typeof target === 'object' && target !== null && target.getAttribute) {
      id = target.getAttribute('data-id');
      name = target.getAttribute('data-name');
      owner = target.getAttribute('data-owner') || 'Pemilik';
      email = target.getAttribute('data-email') || '-';
      phone = target.getAttribute('data-phone') || '-';
      city = target.getAttribute('data-city') || '-';
      status = target.getAttribute('data-status') || 'active';
      plan = target.getAttribute('data-plan') || '-';
      daysLeft = target.getAttribute('data-days') || '0';
      packageId = target.getAttribute('data-package-id') || 1;
      slug = target.getAttribute('data-slug') || '';
    } else {
      id = target;
      name = nameFallback;
      owner = 'Pemilik';
      email = '-';
      city = '-';
      status = 'active';
      plan = '-';
      daysLeft = '30';
      packageId = 1;
      slug = '';
    }

    currentActionTenantId = id;
    currentActionPackageId = packageId;
    currentActionTenantName = name;
    currentActionTenantOwner = owner;
    currentActionTenantEmail = email;
    currentActionTenantStatus = status;

    const modal = document.getElementById('modalTenantActionMenu');
    if (!modal) return;

    // Reset Views: Tampilkan List, Sembunyikan Confirm
    cancelTenantConfirm();

    document.getElementById('actionModalTenantName').innerText = name;
    document.getElementById('actionModalTenantOwner').innerText = 'Pemilik: ' + owner + ' (' + email + ') • ' + city;
    document.getElementById('actionModalTenantPlan').innerText = plan;
    document.getElementById('actionModalTenantDays').innerText = daysLeft + ' Hari';

    // Status Badge
    const badge = document.getElementById('actionModalTenantStatusBadge');
    if (status === 'suspended') {
      badge.className = 'px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/30';
      badge.innerText = 'SUSPENDED';
    } else if (status === 'grace') {
      badge.className = 'px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/30';
      badge.innerText = 'GRACE PERIOD';
    } else {
      badge.className = 'px-2.5 py-0.5 rounded-full font-bold text-[10px] bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30';
      badge.innerText = 'ACTIVE';
    }

    // Public Link
    const publicLink = document.getElementById('linkActionVenuePublic');
    if (publicLink) {
      publicLink.href = '{{ url("venues") }}/' + (slug || id);
    }

    // Toggle Suspend Action Form & Button Style
    const formToggle = document.getElementById('formActionToggleStatus');
    if (formToggle) {
      formToggle.action = '{{ url("admin/tenants") }}/' + id + '/toggle-status';
    }
    
    const btnToggle = document.getElementById('btnActionToggleStatus');
    const iconBox = document.getElementById('iconActionToggleBox');
    const icon = document.getElementById('iconActionToggle');
    const title = document.getElementById('textActionToggleTitle');
    const subtitle = document.getElementById('textActionToggleSubtitle');

    if (status === 'suspended') {
      if (btnToggle) btnToggle.className = 'w-full p-3 rounded-2xl border border-emerald-500/30 bg-emerald-500/5 hover:bg-emerald-500/15 text-left transition flex items-center justify-between group cursor-pointer';
      if (iconBox) iconBox.className = 'w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0';
      if (icon) icon.setAttribute('data-lucide', 'check-circle');
      if (title) {
        title.className = 'text-xs font-bold text-emerald-600 dark:text-emerald-400 block';
        title.innerText = 'Buka Suspend (Aktifkan Kembali)';
      }
      if (subtitle) subtitle.innerText = 'Kembalikan hak akses aplikasi dan publikasi venue';
    } else {
      if (btnToggle) btnToggle.className = 'w-full p-3 rounded-2xl border border-rose-500/30 bg-rose-500/5 hover:bg-rose-500/15 text-left transition flex items-center justify-between group cursor-pointer';
      if (iconBox) iconBox.className = 'w-8 h-8 rounded-xl bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0';
      if (icon) icon.setAttribute('data-lucide', 'ban');
      if (title) {
        title.className = 'text-xs font-bold text-rose-600 dark:text-rose-400 block';
        title.innerText = 'Bekukan / Suspend Venue';
      }
      if (subtitle) subtitle.innerText = 'Nonaktifkan akses sementara untuk venue ini';
    }

    // Fallback Forms Setup
    const formReset = document.getElementById('formActionResetPassword');
    if (formReset) {
      formReset.action = '{{ url("admin/tenants") }}/' + id + '/reset-password';
    }

    const formDestroy = document.getElementById('formActionDestroy');
    if (formDestroy) {
      formDestroy.action = '{{ url("admin/tenants") }}/' + id;
    }

    // Perpanjang Button Link
    const btnExtend = document.getElementById('btnActionExtend');
    if (btnExtend) {
      btnExtend.onclick = function() {
        closeTenantActionMenu();
        openExtendModal(currentActionTenantId, currentActionTenantName, currentActionPackageId);
      };
    }

    modal.classList.remove('hidden');
    if (window.lucide) lucide.createIcons();
  }

  function closeTenantActionMenu() {
    const modal = document.getElementById('modalTenantActionMenu');
    if (modal) modal.classList.add('hidden');
    cancelTenantConfirm();
  }

  // Tampilkan View Konfirmasi Modern
  function showTenantConfirm(type) {
    currentPendingConfirmType = type;
    const listView = document.getElementById('actionMenuListView');
    const confirmView = document.getElementById('actionMenuConfirmView');
    const iconBox = document.getElementById('confirmViewIconBox');
    const icon = document.getElementById('confirmViewIcon');
    const title = document.getElementById('confirmViewTitle');
    const message = document.getElementById('confirmViewMessage');
    const btnExecute = document.getElementById('btnConfirmExecute');
    const btnText = document.getElementById('btnConfirmExecuteText');

    if (!listView || !confirmView) return;

    listView.classList.add('hidden');
    confirmView.classList.remove('hidden');

    if (type === 'reset-password') {
      iconBox.className = 'w-10 h-10 rounded-2xl bg-amber-500/15 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 font-bold';
      icon.setAttribute('data-lucide', 'key');
      title.className = 'text-xs font-black uppercase tracking-wider text-amber-600 dark:text-amber-400';
      title.innerText = 'Konfirmasi Reset Kata Sandi Pemilik';
      message.innerHTML = `
        Apakah Anda yakin ingin mereset kata sandi akun pemilik <strong>${currentActionTenantOwner}</strong> (${currentActionTenantEmail}) untuk venue <strong>${currentActionTenantName}</strong>?<br>
        <div class="mt-2 p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20 text-[11px] text-amber-700 dark:text-amber-300">
          Kata sandi akan diubah ke default: <strong class="font-mono bg-amber-500/20 px-1.5 py-0.5 rounded">password123</strong>. Pemilik dapat login langsung dengan kata sandi baru tersebut.
        </div>
      `;
      btnExecute.className = 'px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold text-xs transition flex items-center gap-2 cursor-pointer shadow-md';
      btnText.innerText = 'Ya, Reset Kata Sandi';
    } else if (type === 'soft-delete') {
      iconBox.className = 'w-10 h-10 rounded-2xl bg-rose-500/15 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 font-bold';
      icon.setAttribute('data-lucide', 'trash-2');
      title.className = 'text-xs font-black uppercase tracking-wider text-rose-600 dark:text-rose-400';
      title.innerText = 'Konfirmasi Arsip (Soft Delete) Venue';
      message.innerHTML = `
        Apakah Anda yakin ingin mengarsipkan venue <strong>${currentActionTenantName}</strong>?<br>
        <div class="mt-2 p-2.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-[11px] text-rose-700 dark:text-rose-300">
          Venue akan dinonaktifkan dari katalog publik. Semua riwayat transaksi booking dan pembayaran <strong>tetap tersimpan utuh</strong> di database dan dapat dipulihkan kapan saja dari tab <strong>Arsip Soft Delete</strong>.
        </div>
      `;
      btnExecute.className = 'px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-extrabold text-xs transition flex items-center gap-2 cursor-pointer shadow-md';
      btnText.innerText = 'Ya, Arsipkan Venue';
    }

    if (window.lucide) lucide.createIcons();
  }

  function cancelTenantConfirm() {
    currentPendingConfirmType = null;
    const listView = document.getElementById('actionMenuListView');
    const confirmView = document.getElementById('actionMenuConfirmView');
    if (listView) listView.classList.remove('hidden');
    if (confirmView) confirmView.classList.add('hidden');
  }

  // Eksekusi Tindakan yang Dikonfirmasi
  async function executeTenantConfirmedAction() {
    if (!currentActionTenantId || !currentPendingConfirmType) return;

    const btn = document.getElementById('btnConfirmExecute');
    const btnText = document.getElementById('btnConfirmExecuteText');
    const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    if (btn) btn.disabled = true;

    if (currentPendingConfirmType === 'reset-password') {
      if (btnText) btnText.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Mereset...';
      if (window.lucide) lucide.createIcons();

      try {
        const res = await fetch('{{ url("admin/tenants") }}/' + currentActionTenantId + '/reset-password', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (res.ok) {
          const data = await res.json();
          closeTenantActionMenu();
          if (window.showSaToast) {
            window.showSaToast('success', 'Reset Kata Sandi Berhasil', data.message || 'Password akun pemilik telah di-reset ke default: "password123"');
          }
        } else {
          // Fallback ke form submit biasa jika status error
          document.getElementById('formActionResetPassword').submit();
        }
      } catch (err) {
        document.getElementById('formActionResetPassword').submit();
      } finally {
        if (btn) btn.disabled = false;
      }
    } else if (currentPendingConfirmType === 'soft-delete') {
      if (btnText) btnText.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Mengarsipkan...';
      if (window.lucide) lucide.createIcons();

      try {
        const res = await fetch('{{ url("admin/tenants") }}/' + currentActionTenantId, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        if (res.ok) {
          const data = await res.json();
          closeTenantActionMenu();
          
          // Hapus baris tabel secara halus
          const row = document.getElementById('tenantRow-' + currentActionTenantId);
          if (row) {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateX(20px)';
            setTimeout(() => row.remove(), 350);
          }

          if (window.showSaToast) {
            window.showSaToast('success', 'Venue Berhasil Diarsipkan', data.message || 'Venue berhasil di-soft delete dan tersimpan di tab Arsip.');
          }
        } else {
          // Fallback ke form submit biasa
          document.getElementById('formActionDestroy').submit();
        }
      } catch (err) {
        document.getElementById('formActionDestroy').submit();
      } finally {
        if (btn) btn.disabled = false;
      }
    }
  }

  // Handle Toggle Status Via AJAX
  async function handleToggleStatusClick() {
    if (!currentActionTenantId) return;

    const btn = document.getElementById('btnActionToggleStatus');
    const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    if (btn) btn.style.opacity = '0.6';

    try {
      const res = await fetch('{{ url("admin/tenants") }}/' + currentActionTenantId + '/toggle-status', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      if (res.ok) {
        const data = await res.json();
        closeTenantActionMenu();

        // Update badge di tabel secara real-time
        const statusCol = document.getElementById('tenantStatusCol-' + currentActionTenantId);
        const row = document.getElementById('tenantRow-' + currentActionTenantId);
        if (statusCol) {
          if (data.status === 'suspended') {
            statusCol.innerHTML = `
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/30 font-bold text-[10px]">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> SUSPENDED
              </span>
            `;
          } else {
            statusCol.innerHTML = `
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 font-bold text-[10px]">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> ACTIVE
              </span>
            `;
          }
        }
        if (row) {
          row.setAttribute('data-status', data.status);
        }

        if (window.showSaToast) {
          window.showSaToast('success', 'Status Tenant Diperbarui', data.message);
        }
      } else {
        document.getElementById('formActionToggleStatus').submit();
      }
    } catch (err) {
      document.getElementById('formActionToggleStatus').submit();
    } finally {
      if (btn) btn.style.opacity = '1';
    }
  }
</script>
@endpush
