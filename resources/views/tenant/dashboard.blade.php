@extends('layouts.admin')

@section('title', 'Dashboard Pengelola Lapangan - LapanganKita')
@section('meta_description', 'Kelola operasional lapangan futsal: pantau omset harian, jadwal sewa real-time, status uji coba 7 hari, dan paket langganan.')

@section('content')
<div class="py-8 md:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

  <!-- BANNER NOTIFIKASI UJI COBA 7 HARI ATAU LANGGANAN RESMI -->
  @if($isTrial)
    <div class="p-5 sm:p-6 rounded-3xl bg-gradient-to-r from-emerald-500/15 via-teal-500/10 to-amber-500/15 border border-emerald-500/30 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm relative overflow-hidden">
      <div class="flex items-start sm:items-center gap-3.5">
        <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-slate-950 flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/20 font-black">
          <i data-lucide="gift" class="w-6 h-6"></i>
        </div>
        <div>
          <div class="flex flex-wrap items-center gap-2">
            <span class="text-sm sm:text-base font-black text-slate-900 dark:text-white">Uji Coba Gratis 7 Hari (Aktif)</span>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-500/20 text-amber-500 border border-amber-500/30 flex items-center gap-1">
              <i data-lucide="clock" class="w-3 h-3"></i>
              <span>Sisa {{ (int) round($daysLeft) }} Hari Lagi</span>
            </span>
            @if($subscription && $subscription->package)
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">
                {{ $subscription->package->name }}
              </span>
            @endif
          </div>
          <p class="text-xs text-slate-600 dark:text-slate-300 mt-1">
            Semua fitur manajemen lapangan & booking aktif penuh. Segera bayar tagihan sebelum masa uji coba berakhir agar jadwal lapangan tetap tayang untuk pemain.
          </p>
        </div>
      </div>

      <div class="flex items-center gap-2.5 shrink-0 self-end md:self-center">
        <a href="{{ route('tenant.billing') }}" class="px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs transition shadow-md shadow-emerald-500/20 flex items-center gap-2">
          <i data-lucide="credit-card" class="w-4 h-4"></i>
          <span>Bayar & Aktivasi Penuh</span>
        </a>
      </div>
    </div>
  @else
    <div class="p-4 sm:p-5 rounded-3xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-between gap-4">
      <div class="flex items-center gap-3">
        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500 shrink-0"></i>
        <div>
          <span class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white">Paket Berlangganan Aktif: {{ $subscription->package->name ?? 'Pro' }}</span>
          <span class="text-xs text-slate-500 block">Masa aktif sampai {{ optional($subscription->end_date)->format('d M Y') ?? 'Aktif' }}</span>
        </div>
      </div>
      <a href="{{ route('tenant.billing') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">Kelola Paket &rarr;</a>
    </div>
  @endif

  <!-- WELCOME & QUICK VENUE HEADER -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
    <div class="flex items-center gap-4">
      <div class="w-14 h-14 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 shadow-md shrink-0 flex items-center justify-center">
        @if(!empty($tenant->logo_image))
          <img src="{{ $tenant->logo_image }}" alt="{{ $tenant->name }}" class="w-full h-full object-cover">
        @else
          <div class="w-full h-full bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white text-xl font-black">
            {{ strtoupper(substr($tenant->name, 0, 2)) }}
          </div>
        @endif
      </div>
      <div>
        <div class="flex items-center gap-2">
          <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">{{ $tenant->name }}</h1>
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
            {{ ucfirst($tenant->status) }}
          </span>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
          Pemilik: {{ auth()->user()->name }} • {{ $tenant->city }} • {{ $tenant->phone_wa }}
        </p>
      </div>
    </div>

    <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
      <a href="{{ route('tenant.analytics') }}" class="px-4 py-2.5 rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500 hover:text-slate-950 font-black text-xs transition flex items-center gap-1.5 border border-emerald-500/30 shadow-sm">
        <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
        <span>Analitik Bisnis</span>
      </a>
      <a href="{{ route('tenant.profile') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
        <i data-lucide="image" class="w-4 h-4 text-emerald-500"></i>
        <span>Ganti Foto & Logo</span>
      </a>
      <a href="{{ route('tenant.courts') }}" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
        <i data-lucide="calendar" class="w-4 h-4 text-emerald-400"></i>
        <span>Jadwal & Lapangan</span>
      </a>
      <a href="{{ route('tenant.billing') }}" class="px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
        <i data-lucide="credit-card" class="w-4 h-4"></i>
        <span>Paket Langganan</span>
      </a>
    </div>
  </div>

  <!-- HIGHLIGHT REKOMENDASI BISNIS FINANSIAL & JAM OPERASIONAL -->
  <div class="bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 border border-emerald-500/40 rounded-3xl p-4 sm:p-5 text-white shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="flex items-center gap-3.5">
      <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-400 to-teal-300 text-slate-950 flex items-center justify-center font-bold shrink-0 shadow-md shadow-emerald-500/30">
        <i data-lucide="trending-up" class="w-6 h-6 text-slate-950"></i>
      </div>
      <div>
        <div class="flex items-center gap-2">
          <span class="text-xs font-black uppercase tracking-wider text-emerald-400 term-tooltip" data-bs-toggle="tooltip" title="Sistem analitik yang memantau pola sewa lapangan dan memberikan rekomendasi optimalisasi omset.">
            <span>Rekomendasi Strategi Venue Hari Ini</span>
            <i data-lucide="help-circle" class="w-3 h-3 inline text-emerald-400/80"></i>
          </span>
          <span class="px-2 py-0.2 rounded-full text-[9px] font-bold bg-emerald-500/20 text-emerald-300">Smart Advisory</span>
        </div>
        <p class="text-xs text-slate-200 mt-0.5">
          Jam siang <strong>13:00 - 15:00</strong> berpotensi menambah omset <strong>+Rp 240.000</strong> jika dibuka promo pelajar. Jam malam <strong>18:00 - 21:00</strong> terisi 95%!
        </p>
      </div>
    </div>

    <div class="shrink-0 self-end md:self-center">
      <a href="{{ route('tenant.analytics') }}" class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs transition shadow-md shadow-emerald-500/20 flex items-center gap-1.5" data-bs-toggle="tooltip" title="Lihat grafik detail pendapatan, AOV, dan tingkat okupansi jam malam.">
        <i data-lucide="bar-chart-2" class="w-3.5 h-3.5"></i>
        <span>Buka Analisis Lengkap &rarr;</span>
      </a>
    </div>
  </div>

  <!-- 4 STATISTIK UTAMA (METRICS REAL DATABASE) -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
    <!-- Card 1: Omset Hari Ini -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
      <div class="flex items-center justify-between text-slate-500">
        <span class="text-xs font-bold uppercase tracking-wider term-tooltip" data-bs-toggle="tooltip" title="Total pemasukan bersih dari booking lapangan yang sudah berstatus Lunas (Paid) untuk jadwal hari ini.">
          <span>Omset Hari Ini</span>
          <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
        </span>
        <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
          <i data-lucide="badge-dollar-sign" class="w-5 h-5"></i>
        </div>
      </div>
      <div>
        <div class="text-2xl font-black text-slate-900 dark:text-white">
          Rp {{ number_format($omsetToday, 0, ',', '.') }}
        </div>
        <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold mt-1 flex items-center gap-1">
          <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
          <span>Dari transaksi lunas hari ini</span>
        </p>
      </div>
    </div>

    <!-- Card 2: Total Booking Hari Ini -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
      <div class="flex items-center justify-between text-slate-500">
        <span class="text-xs font-bold uppercase tracking-wider term-tooltip" data-bs-toggle="tooltip" title="Total sesi bermain yang terjadwal di seluruh lapangan Anda untuk hari ini (termasuk status lunas dan pending).">
          <span>Jadwal Hari Ini</span>
          <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
        </span>
        <div class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
          <i data-lucide="calendar" class="w-5 h-5"></i>
        </div>
      </div>
      <div>
        <div class="text-2xl font-black text-slate-900 dark:text-white">
          {{ $todayBookings->where('status', 'paid')->count() }} Terkonfirmasi
        </div>
        <div class="text-[11px] text-slate-500 mt-1 flex flex-wrap items-center gap-1.5">
          <span>Jadwal lunas siap main</span>
          @if($todayBookings->where('status', 'pending')->count() > 0)
            <span class="px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-600 dark:text-amber-400 font-bold text-[10px] flex items-center gap-1 cursor-help" data-bs-toggle="tooltip" title="Menunggu batas waktu pembayaran pemain sebelum slot dibatalkan otomatis.">
              <i data-lucide="clock" class="w-2.5 h-2.5"></i>
              <span>{{ $todayBookings->where('status', 'pending')->count() }} Ditunda</span>
            </span>
          @endif
        </div>
      </div>
    </div>

    <!-- Card 3: Unit Lapangan Aktif -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
      <div class="flex items-center justify-between text-slate-500">
        <span class="text-xs font-bold uppercase tracking-wider term-tooltip" data-bs-toggle="tooltip" title="Jumlah total lapangan futsal aktif di venue ini yang siap menerima booking pemain.">
          <span>Unit Lapangan</span>
          <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
        </span>
        <div class="w-9 h-9 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center">
          <i data-lucide="layers" class="w-5 h-5"></i>
        </div>
      </div>
      <div>
        <div class="text-2xl font-black text-slate-900 dark:text-white">
          {{ $courts->count() }} Lapangan
        </div>
        <p class="text-[11px] text-purple-600 dark:text-purple-400 font-semibold mt-1">
          <a href="{{ route('tenant.courts') }}" class="hover:underline">Tambah lapangan &rarr;</a>
        </p>
      </div>
    </div>

    <!-- Card 4: Total Omset Keseluruhan -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
      <div class="flex items-center justify-between text-slate-500">
        <span class="text-xs font-bold uppercase tracking-wider term-tooltip" data-bs-toggle="tooltip" title="Akumulasi seluruh pendapatan kotor sewa lapangan sejak venue Anda pertama kali menggunakan platform.">
          <span>Total Omset</span>
          <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
        </span>
        <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
          <i data-lucide="wallet" class="w-5 h-5"></i>
        </div>
      </div>
      <div>
        <div class="text-2xl font-black text-slate-900 dark:text-white">
          Rp {{ number_format($totalRevenue, 0, ',', '.') }}
        </div>
        <p class="text-[11px] text-slate-500 mt-1">Akumulasi pendapatan</p>
      </div>
    </div>
  </div>

  <!-- TABEL JADWAL & TRANSAKSI RESERVASI TERBARU -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm space-y-5">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h2 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="clock" class="w-5 h-5 text-emerald-500"></i>
          <span>Riwayat Booking & Jadwal Masuk</span>
        </h2>
        <p class="text-xs text-slate-500">Data reservasi lapangan dari para pemain (Lunas & Ditunda)</p>
      </div>

      <!-- Quick Filter Tabs -->
      <div class="flex items-center gap-1.5 bg-slate-100 dark:bg-slate-800/80 p-1 rounded-2xl border border-slate-200 dark:border-slate-700/60 text-xs font-bold self-start md:self-auto">
        <button type="button" onclick="filterDashboardBookings('all')" id="btn-filter-all" class="dashboard-filter-btn px-3 py-1.5 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm transition flex items-center gap-1.5 cursor-pointer">
          <span>Semua</span>
          <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300">{{ $allBookings->count() }}</span>
        </button>
        <button type="button" onclick="filterDashboardBookings('paid')" id="btn-filter-paid" class="dashboard-filter-btn px-3 py-1.5 rounded-xl text-slate-500 hover:text-slate-900 dark:hover:text-white transition flex items-center gap-1.5 cursor-pointer">
          <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
          <span>Lunas</span>
          <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">{{ $allBookings->where('status', 'paid')->count() }}</span>
        </button>
        <button type="button" onclick="filterDashboardBookings('pending')" id="btn-filter-pending" class="dashboard-filter-btn px-3 py-1.5 rounded-xl text-slate-500 hover:text-slate-900 dark:hover:text-white transition flex items-center gap-1.5 cursor-pointer">
          <span class="w-2 h-2 rounded-full bg-amber-500"></span>
          <span>Ditunda</span>
          <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-amber-500/15 text-amber-600 dark:text-amber-400">{{ $allBookings->where('status', 'pending')->count() }}</span>
        </button>
      </div>
    </div>

    @if($allBookings->count() > 0)
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead>
            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
              <th class="pb-3">Kode Booking</th>
              <th class="pb-3">Nama Pemain & Tipe</th>
              <th class="pb-3">Lapangan</th>
              <th class="pb-3">Tanggal & Jam</th>
              <th class="pb-3">Total Biaya</th>
              <th class="pb-3">Status</th>
              <th class="pb-3 text-right">Invoice / Struk</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-medium">
            @foreach($allBookings as $bkg)
              <tr class="dashboard-booking-row hover:bg-slate-50 dark:hover:bg-slate-800/40 transition" data-status="{{ $bkg->status }}">
                <td class="py-3.5 font-bold text-slate-900 dark:text-white">
                  {{ $bkg->booking_code }}
                </td>
                <td class="py-3.5">
                  @if($bkg->booking_type === 'walk_in')
                    <div class="font-bold text-amber-600 dark:text-amber-400 flex items-center gap-1 cursor-help" data-bs-toggle="tooltip" title="Walk-In (Kasir): Pemesanan langsung di tempat tanpa melalui sistem booking online pemain.">
                      <i data-lucide="store" class="w-3.5 h-3.5"></i>
                      <span>Kasir (Pemain Offline)</span>
                    </div>
                  @elseif($bkg->booking_type === 'open_slot')
                    <div class="font-bold text-slate-900 dark:text-white">{{ $bkg->user->name ?? 'Pemain Tamu' }} (Host)</div>
                    <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-md text-[9px] font-extrabold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 cursor-help" data-bs-toggle="tooltip" title="Open Slot (Mabar): Pemain patungan bayar per slot individu hingga target jumlah pemain terkumpul.">
                      <span> Open Slot ({{ $bkg->openSlotRoom?->current_players ?? 1 }}/{{ $bkg->openSlotRoom?->target_players ?? '-' }} Pemain)</span>
                    </span>
                  @elseif($bkg->booking_type === 'sparring')
                    <div class="font-bold text-slate-900 dark:text-white">
                      {{ $bkg->sparringChallenge->host_team_name ?? ($bkg->user->name ?? 'Tim Host') }}
                      <span class="text-slate-400 font-normal text-[11px]">vs</span>
                      <span class="{{ $bkg->sparringChallenge?->challenger_team_name ? 'text-purple-600 dark:text-purple-400 font-bold' : 'text-slate-400 italic font-normal text-[11px]' }}">
                        {{ $bkg->sparringChallenge->challenger_team_name ?? '(Menunggu Lawan)' }}
                      </span>
                    </div>
                    <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-md text-[9px] font-extrabold bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20 cursor-help" data-bs-toggle="tooltip" title="Match Sparing 50:50: Pertandingan antar dua tim di mana biaya sewa lapangan dibagi rata secara otomatis.">
                      <span> Sparing (Bagi 50:50)</span>
                    </span>
                  @else
                    <div class="font-bold text-slate-900 dark:text-white">{{ $bkg->user->name ?? 'Pemain Tamu' }}</div>
                    <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-md text-[9px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 cursor-help" data-bs-toggle="tooltip" title="Pemesanan reguler per jam penuh oleh individu atau komunitas tim via website.">
                      <span>Reguler Web</span>
                    </span>
                  @endif
                </td>
                <td class="py-3.5 text-slate-600 dark:text-slate-300">{{ $bkg->court->name ?? '-' }}</td>
                <td class="py-3.5">
                  <span class="font-bold text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($bkg->booking_date)->format('d M Y') }}</span>
                  <span class="text-slate-400 block text-[11px]">{{ $bkg->start_time }} - {{ $bkg->end_time }}</span>
                </td>
                <td class="py-3.5">
                  @if($bkg->booking_type === 'open_slot')
                    @php
                      $collectedAmount = $bkg->payments ? $bkg->payments->where('status', 'settlement')->sum('amount') : 0;
                    @endphp
                    <span class="font-bold text-emerald-600 dark:text-emerald-400 block">
                      Rp {{ number_format($collectedAmount, 0, ',', '.') }}
                    </span>
                    <span class="text-[10px] text-slate-400 block">
                      Target: Rp {{ number_format($bkg->total_amount, 0, ',', '.') }}
                    </span>
                  @elseif($bkg->booking_type === 'sparring')
                    @php
                      $collectedAmount = $bkg->payments ? $bkg->payments->where('status', 'settlement')->sum('amount') : 0;
                      $halfAmount = round($bkg->total_amount / 2);
                    @endphp
                    <span class="font-bold text-emerald-600 dark:text-emerald-400 block">
                      Rp {{ number_format($collectedAmount, 0, ',', '.') }}
                    </span>
                    <span class="text-[10px] text-slate-400 block">
                      Target: Rp {{ number_format($bkg->total_amount, 0, ',', '.') }} (50:50 @ Rp {{ number_format($halfAmount, 0, ',', '.') }})
                    </span>
                  @else
                    <span class="font-bold text-emerald-600 dark:text-emerald-400">
                      Rp {{ number_format($bkg->total_amount, 0, ',', '.') }}
                    </span>
                  @endif
                </td>
                <td class="py-3.5">
                  @if($bkg->booking_type === 'open_slot')
                    @php
                      $cur = $bkg->openSlotRoom?->current_players ?? 1;
                      $tar = $bkg->openSlotRoom?->target_players ?? 10;
                    @endphp
                    @if($bkg->status === 'paid' || $cur >= $tar)
                      <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                         Lunas ({{ $cur }}/{{ $tar }})
                      </span>
                    @else
                      <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 flex items-center gap-1 w-max">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        <span>Ditunda ({{ $cur }}/{{ $tar }} Slot)</span>
                      </span>
                    @endif
                  @elseif($bkg->booking_type === 'sparring')
                    @if($bkg->status === 'paid' && $bkg->sparringChallenge && $bkg->sparringChallenge->status === 'matched')
                      <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                         Lunas 50:50 (Lengkap)
                      </span>
                    @elseif($bkg->status === 'pending')
                      <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 flex items-center gap-1 w-max">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        <span>Ditunda (Menunggu Bayar)</span>
                      </span>
                    @else
                      <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-500 border border-purple-500/20">
                         Host Bayar 50% (Cari Lawan)
                      </span>
                    @endif
                  @else
                    @if($bkg->status === 'paid')
                      <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Lunas</span>
                    @elseif($bkg->status === 'pending')
                      <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 flex items-center gap-1 w-max">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        <span>Ditunda (Pending)</span>
                      </span>
                    @else
                      <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-500/10 text-slate-400">{{ ucfirst($bkg->status) }}</span>
                    @endif
                  @endif
                </td>
                <td class="py-3.5 text-right">
                  @if($bkg->status === 'paid')
                    <a href="{{ route('booking.ticket.pdf', $bkg->booking_code) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-emerald-500 hover:text-slate-950 dark:bg-slate-800 dark:hover:bg-emerald-500 text-slate-700 dark:text-slate-300 font-bold text-[10px] transition border border-slate-200 dark:border-slate-700 hover:border-emerald-500 shadow-sm" title="Cetak struk resmi reservasi">
                      <i data-lucide="receipt" class="w-3 h-3"></i>
                      <span>Lihat Struk</span>
                    </a>
                  @elseif($bkg->status === 'pending')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 font-bold text-[10px] border border-amber-500/20 cursor-not-allowed select-none" title="Struk ditunda. Tidak dapat dicetak karena pembayaran belum lunas.">
                      <i data-lucide="clock" class="w-3 h-3"></i>
                      <span>Ditunda (Belum Lunas)</span>
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/60 text-slate-400 font-bold text-[10px] border border-slate-200 dark:border-slate-800 cursor-not-allowed select-none" title="Struk tidak tersedia">
                      <i data-lucide="ban" class="w-3 h-3"></i>
                      <span>Tidak Tersedia</span>
                    </span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="text-center py-12 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-2xl space-y-2">
        <i data-lucide="calendar-x" class="w-10 h-10 text-slate-400 mx-auto"></i>
        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum Ada Reservasi Masuk</h3>
        <p class="text-xs text-slate-500 max-w-sm mx-auto">Saat pemain memesan lapangan futsal Anda di katalog, jadwalnya akan otomatis muncul secara real-time di sini.</p>
      </div>
    @endif
  </div>

</div>
@endsection

@section('scripts')
<script>
  function filterDashboardBookings(status) {
    document.querySelectorAll('.dashboard-filter-btn').forEach(btn => {
      btn.classList.remove('bg-white', 'dark:bg-slate-900', 'text-slate-900', 'dark:text-white', 'shadow-sm');
      btn.classList.add('text-slate-500');
    });
    const activeBtn = document.getElementById('btn-filter-' + status);
    if (activeBtn) {
      activeBtn.classList.add('bg-white', 'dark:bg-slate-900', 'text-slate-900', 'dark:text-white', 'shadow-sm');
      activeBtn.classList.remove('text-slate-500');
    }
    document.querySelectorAll('.dashboard-booking-row').forEach(row => {
      const rowStatus = row.getAttribute('data-status');
      if (status === 'all' || rowStatus === status) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }
</script>
@endsection
