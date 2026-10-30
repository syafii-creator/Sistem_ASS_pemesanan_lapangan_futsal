@extends('layouts.app')

@section('title', 'Lobby Open Slot & Cari Pemain Acak - LapanganKita')
@section('meta_description', 'Gabung room yang kekurangan pemain atau buka room sendiri untuk patungan biaya sewa lapangan futsal secara otomatis.')

@section('content')
<div class="space-y-0 pb-16">

  <!-- ========================================================================= -->
  <!-- ALERT FLASH NOTIFIKASI                                                    -->
  <!-- ========================================================================= -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
    @if(session('success'))
      <div class="mb-4 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-xs flex items-center justify-between gap-3 shadow-sm">
        <div class="flex items-center gap-2.5">
          <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500 shrink-0"></i>
          <span class="font-bold">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white cursor-pointer">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>
    @endif

    @if(session('error'))
      <div class="mb-4 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-300 text-xs flex items-center justify-between gap-3 shadow-sm">
        <div class="flex items-center gap-2.5">
          <i data-lucide="alert-circle" class="w-5 h-5 text-rose-500 shrink-0"></i>
          <span class="font-bold">{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white cursor-pointer">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>
    @endif
  </div>

  <!-- ========================================================================= -->
  <!-- BANNER HEADER "BUTUH ORANG" (SESUAI DESAIN USER)                          -->
  <!-- ========================================================================= -->
  <header class="bg-gradient-to-r from-amber-600 via-orange-600 to-amber-700 text-white py-10 sm:py-12 relative overflow-hidden shadow-inner">
    <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
      <i data-lucide="users-2" class="w-80 h-80"></i>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="max-w-2xl space-y-3">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-amber-100 text-xs font-bold border border-white/20 shadow-sm">
          <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
          <span>Fitur Matchmaking Pemain Acak (B2C)</span>
        </span>

        <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">
          Kurang Personil? Gak Jadi Batal Main! 
        </h1>

        <p class="text-amber-100 text-xs sm:text-sm leading-relaxed">
          Gabung room yang kekurangan pemain atau buka room sendiri untuk patungan biaya sewa lapangan secara otomatis via LapanganKita.
        </p>

        <!-- Stats Bar -->
        @php
          $totalJoinedPlayers = 0;
          foreach($rooms as $r) {
            $totalJoinedPlayers += $r->current_players;
          }
        @endphp
        <div class="pt-3 flex flex-wrap gap-3 sm:gap-4 text-xs font-semibold">
          <div class="bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-xl border border-white/10 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
            <span id="stat-active-rooms"><strong>{{ $rooms->count() }}</strong> Room Aktif Hari Ini</span>
          </div>
          <div class="bg-white/10 backdrop-blur-md px-3.5 py-2 rounded-xl border border-white/10 flex items-center gap-2">
            <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-amber-300"></i>
            <span><strong>{{ $totalJoinedPlayers }}</strong> Pemain Sudah Bergabung</span>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- ========================================================================= -->
  <!-- FILTER & SEARCH BAR                                                       -->
  <!-- ========================================================================= -->
  <div class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 py-4 shadow-sm sticky top-20 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3">
      <div class="flex items-center gap-2 overflow-x-auto w-full sm:w-auto pb-1 sm:pb-0 no-scrollbar">
        <button onclick="setFilter('all')" id="btn-all" class="room-filter-btn px-3.5 py-1.5 rounded-xl bg-amber-500 text-slate-950 font-black text-xs whitespace-nowrap shadow-sm cursor-pointer transition">
          Semua Room ({{ $rooms->count() }})
        </button>
        <button onclick="setFilter('night')" id="btn-night" class="room-filter-btn px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs whitespace-nowrap cursor-pointer transition">
          Malam Ini (19:00 - 23:00)
        </button>
        <button onclick="setFilter('urgent')" id="btn-urgent" class="room-filter-btn px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs whitespace-nowrap cursor-pointer transition">
           Sisa 1-2 Slot Cepat
        </button>
        <button onclick="setFilter('fun')" id="btn-fun" class="room-filter-btn px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs whitespace-nowrap cursor-pointer transition">
          Fun Football Saja
        </button>
      </div>

      <div class="relative w-full sm:w-72 shrink-0">
        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-2.5 text-slate-400"></i>
        <input type="text" id="searchInput" oninput="handleSearch(this.value)" placeholder="Cari nama room / venue..." class="w-full pl-9 pr-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-amber-500">
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MAIN ROOMS GRID & SIDEBAR (8 COLS : 4 COLS)                                -->
  <!-- ========================================================================= -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

      <!-- ================= LIST OF OPEN SLOT ROOMS (8 COLS) ================= -->
      <section class="lg:col-span-8 space-y-6" id="roomsListContainer">
        @forelse($rooms as $index => $room)
          @php
            $slotsLeft = max(0, $room->target_players - $room->current_players);
            $isFull = ($slotsLeft === 0);
            $isNight = false;
            $startTimeInt = 19;
            if (!empty($room->booking->start_time)) {
              $startTimeInt = (int) substr($room->booking->start_time, 0, 2);
              $isNight = ($startTimeInt >= 18);
            }
            $isCompetitive = str_contains(strtolower($room->title), 'kompetitif') || str_contains(strtolower($room->title), 'semi-pro');
            $levelText = $isCompetitive ? 'Medium Competitive' : 'Fun Match (Non-Kasar)';
            $isUrgent = ($slotsLeft > 0 && $slotsLeft <= 2);
            $totalCourtPrice = $room->booking->total_amount ?? ($room->price_per_person * $room->target_players);
          @endphp

          <div class="open-slot-card bg-white dark:bg-slate-900 rounded-3xl {{ $isUrgent ? 'border-2 border-amber-400 dark:border-amber-500/80 shadow-md hover:shadow-xl' : 'border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-lg' }} p-5 sm:p-7 transition relative group"
               data-title="{{ strtolower($room->title) }}"
               data-venue="{{ strtolower($room->booking->tenant->name ?? '') }}"
               data-is-night="{{ $isNight ? '1' : '0' }}"
               data-is-urgent="{{ $isUrgent ? '1' : '0' }}"
               data-is-fun="{{ $isCompetitive ? '0' : '1' }}">
            
            @if($isUrgent)
              <div class="absolute -top-3 left-6 bg-gradient-to-r from-amber-500 to-orange-500 text-slate-950 text-[10px] font-black uppercase tracking-wider px-3.5 py-0.5 rounded-full shadow">
                 Urgent - Sisa {{ $slotsLeft }} Slot Lagi!
              </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 pt-1">
              <div class="space-y-1.5 flex-1">
                <div class="flex items-center gap-2">
                  <span class="text-xs font-extrabold {{ $isCompetitive ? 'text-purple-700 bg-purple-50 dark:bg-purple-950/40 border-purple-200 dark:border-purple-800' : 'text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800' }} px-2.5 py-0.5 rounded-md border">
                    Room #OS-{{ 800 + $room->id }}
                  </span>
                  <span class="text-xs text-slate-400 font-medium">• {{ $levelText }}</span>
                </div>

                <h2 class="text-lg font-black text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition leading-snug">
                  {{ $room->title }}
                </h2>

                <p class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mt-1">
                  <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                  <strong class="text-slate-800 dark:text-slate-200">{{ $room->booking->tenant->name ?? 'Champion Futsal Arena' }}</strong>
                  <span>— {{ $room->booking->court->name ?? 'Lapangan Utama' }} ({{ $room->booking->court->surface_type ?? 'Sintetis / Vinyl' }})</span>
                </p>
              </div>

              <!-- Cost info -->
              <div class="sm:text-right bg-slate-50 dark:bg-slate-850 sm:bg-transparent p-3 sm:p-0 rounded-2xl w-full sm:w-auto shrink-0">
                <span class="text-[10px] text-slate-400 block font-medium">Patungan / Orang</span>
                <span class="text-xl font-black text-emerald-600 dark:text-emerald-400">
                  Rp {{ number_format($room->price_per_person, 0, ',', '.') }}
                </span>
                <span class="text-[10px] text-slate-400 block">Total Sewa: Rp {{ number_format($totalCourtPrice, 0, ',', '.') }}</span>
              </div>
            </div>

            <!-- TIME & SCHEDULE BADGES -->
            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-wrap items-center gap-2.5 sm:gap-3 text-xs">
              <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 font-bold text-slate-700 dark:text-slate-300">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-amber-600"></i>
                <span>{{ \Carbon\Carbon::parse($room->booking->booking_date ?? now())->isoFormat('dddd, D MMM Y') }}</span>
              </div>
              <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 font-extrabold text-amber-900 dark:text-amber-300">
                <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-600"></i>
                <span>{{ substr($room->booking->start_time ?? '19:00', 0, 5) }} - {{ substr($room->booking->end_time ?? '21:00', 0, 5) }} WIB</span>
              </div>
              <div class="text-xs text-slate-500 dark:text-slate-400 font-medium sm:ml-auto flex items-center gap-1">
                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i>
                <span>Host: <strong class="text-slate-800 dark:text-slate-200">{{ $room->host->name ?? 'Host LapanganKita' }}</strong>
                  @if(auth()->check() && $room->host_user_id === auth()->id())
                    <span class="text-amber-600 dark:text-amber-400 font-bold">(Anda)</span>
                  @endif
                </span>
              </div>
            </div>

            @php
              $bDateStr = \Carbon\Carbon::parse($room->booking->booking_date ?? now())->toDateString();
              $sTimeStr = substr($room->booking->start_time ?? '19:00', 0, 5);
              $matchDateTime = \Carbon\Carbon::parse($bDateStr . ' ' . $sTimeStr);
              $autoCancelDeadline = $matchDateTime->copy()->subHours(2);
            @endphp

            @if(!$isFull && $room->status === 'open')
              <div class="mt-3 flex items-center justify-between gap-2 px-3 py-1.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-[11px] font-bold text-rose-800 dark:text-rose-300">
                <div class="flex items-center gap-1.5">
                  <i data-lucide="timer" class="w-3.5 h-3.5 text-rose-600 shrink-0"></i>
                  <span>Batas Kuota: <strong>{{ $autoCancelDeadline->isoFormat('D MMM, HH:mm') }} WIB</strong> (H-2 Jam)</span>
                </div>
                <span class="text-rose-700/80 dark:text-rose-300/80 text-[10px] font-medium hidden sm:inline">Auto-Cancel &amp; Lapangan Bebas jika kuota kurang</span>
              </div>
            @endif

            <!-- STATUS KUOTA & PROGRESS BAR (BERSIH TANPA AVATAR) -->
            <div class="mt-5 space-y-2.5">
              <div class="flex justify-between items-center text-xs">
                <div class="flex items-center gap-2">
                  <span class="font-bold text-slate-700 dark:text-slate-300">
                    Kapasitas Pemain:
                  </span>
                  <span class="font-extrabold text-slate-900 dark:text-white px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800">
                    {{ $room->current_players }} / {{ $room->target_players }} Terisi
                  </span>
                </div>
                <span class="{{ $isFull ? 'text-rose-600 bg-rose-50 border-rose-200 dark:bg-rose-950/40 dark:border-rose-900/50' : 'text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800/60' }} font-bold text-xs px-2.5 py-0.5 rounded-full border">
                  {{ $isFull ? 'Room Sudah Penuh' : 'Sisa ' . $slotsLeft . ' Slot Kosong!' }}
                </span>
              </div>

              <!-- Progress Bar Kapasitas -->
              <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all duration-500 {{ $isFull ? 'bg-rose-500' : 'bg-gradient-to-r from-amber-500 to-orange-500' }}" 
                     style="width: {{ min(100, round(($room->current_players / max(1, $room->target_players)) * 100)) }}%"></div>
              </div>
            </div>

            @php
              $currentUserId = auth()->id();
              $isHost = $currentUserId && ($room->host_user_id === $currentUserId);
              $myMember = $currentUserId ? $room->members->firstWhere('user_id', $currentUserId) : null;
              $hasJoined = $myMember && in_array($myMember->status, ['paid', 'settlement']);
            @endphp

            <!-- ACTION BUTTON: LANGSUNG GABUNG & BAYAR SLOT / STATUS HOST / STATUS MEMBER -->
            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3">
              <span class="text-xs text-slate-500 dark:text-slate-400">
                @if($isHost)
                  <span class="text-amber-600 dark:text-amber-400 font-bold flex items-center gap-1.5">
                    <i data-lucide="crown" class="w-4 h-4 text-amber-500"></i>
                    Anda adalah Host room ini. Kuota Anda sudah lunas ({{ $myMember ? $myMember->slots_count : 1 }} orang dibayar di awal via Midtrans). Sisa slot khusus pemain lain.
                  </span>
                @elseif($hasJoined)
                  <span class="text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1.5">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i>
                    Anda sudah terdaftar dan lunas di room ini ({{ $myMember->slots_count }} slot).
                  </span>
                @else
                  Pembayaran patungan langsung dibagi rata & otomatis masuk invoice profil
                @endif
              </span>
              
              <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                @if($isHost)
                  <div class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-700 dark:text-amber-400 font-black text-xs">
                    <i data-lucide="crown" class="w-3.5 h-3.5 text-amber-500"></i>
                    <span>Host ({{ ($myMember && $myMember->slots_count > 1) ? $myMember->slots_count . ' Slot ' : '' }}Lunas)</span>
                  </div>
                  @if($myMember)
                    <a href="{{ route('community.open-slot.invoice.pdf', $myMember->id) }}" target="_blank" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white font-bold text-xs rounded-xl flex items-center gap-1.5 shadow-sm transition">
                      <i data-lucide="printer" class="w-3.5 h-3.5 text-amber-400"></i>
                      <span>Invoice Host{{ ($myMember && $myMember->slots_count > 1) ? ' (' . $myMember->slots_count . ' Slot)' : '' }}</span>
                    </a>
                  @endif
                  @if($room->status === 'open')
                    <form action="{{ route('community.open-slot.cancel', $room->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan room mabar ini?');" class="inline">
                      @csrf
                      <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition cursor-pointer" title="Batalkan Room">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                      </button>
                    </form>
                  @endif
                @elseif($hasJoined)
                  <div class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 font-extrabold text-xs">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i>
                    <span>Telah Bergabung (Lunas)</span>
                  </div>
                  <a href="{{ route('community.open-slot.invoice.pdf', $myMember->id) }}" target="_blank" class="px-3.5 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs rounded-xl flex items-center gap-1.5 shadow-sm transition">
                    <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                    <span>Tiket / Invoice</span>
                  </a>
                @elseif($isFull)
                  <button disabled class="w-full sm:w-auto px-6 py-2.5 bg-slate-200 dark:bg-slate-800 text-slate-400 font-bold text-xs rounded-xl cursor-not-allowed">
                    Room Penuh
                  </button>
                @else
                  <button onclick="openJoinModal({{ $room->id }}, '{{ addslashes($room->title) }}', '{{ addslashes($room->booking->tenant->name ?? 'Venue') }}', '{{ substr($room->booking->start_time ?? '19:00', 0, 5) }} - {{ substr($room->booking->end_time ?? '21:00', 0, 5) }} WIB', {{ $room->price_per_person }}, {{ $slotsLeft }})" 
                          class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-slate-950 font-black text-xs rounded-xl shadow-md shadow-amber-500/30 transition flex items-center justify-center gap-2 cursor-pointer">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>Gabung & Bayar Slot (Rp {{ number_format($room->price_per_person, 0, ',', '.') }})</span>
                  </button>
                @endif
              </div>
            </div>
          </div>
        @empty
          <div class="text-center py-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-3">
            <i data-lucide="users-2" class="w-12 h-12 text-slate-400 mx-auto"></i>
            <h3 class="text-base font-bold text-slate-700 dark:text-slate-300">Belum ada room open slot aktif</h3>
            <p class="text-xs text-slate-500">Buka room pertama Anda sekarang dan ajak pemain futsal lain bergabung!</p>
            <button onclick="openCreateRoomModal()" class="mt-2 px-5 py-2.5 rounded-xl bg-amber-500 text-slate-950 font-bold text-xs inline-flex items-center gap-1.5 cursor-pointer">
              <i data-lucide="plus-circle" class="w-4 h-4"></i>
              <span>Buka Room Sekarang</span>
            </button>
          </div>
        @endforelse

        <div id="openSlotEmptyState" class="hidden text-center py-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-3 p-6">
          <i data-lucide="search-x" class="w-12 h-12 text-slate-400 mx-auto"></i>
          <h3 class="text-base font-bold text-slate-700 dark:text-slate-300">Tidak ada room open slot yang sesuai</h3>
          <p class="text-xs text-slate-500">Coba ubah kata kunci pencarian atau ganti filter kategori.</p>
          <button type="button" onclick="resetAllFilters()" class="inline-block mt-2 px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs rounded-xl cursor-pointer transition">
            Reset Filter
          </button>
        </div>

      </section>

      <!-- ================= SIDEBAR INFO & QUICK ROOM CREATOR (4 COLS) ================= -->
      <aside class="lg:col-span-4 space-y-6">

        <!-- Cara Kerja Open Slot Card -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
          <h3 class="text-sm font-black text-slate-900 dark:text-white flex items-center gap-2">
            <i data-lucide="help-circle" class="w-4 h-4 text-amber-500"></i> 
            <span>Cara Kerja Open Slot</span>
          </h3>
          <ol class="space-y-3.5 text-xs text-slate-600 dark:text-slate-400">
            <li class="flex items-start gap-2.5">
              <span class="w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-400 font-bold flex items-center justify-center shrink-0 text-[10px]">1</span>
              <span><strong>Host Booking:</strong> Buat room saat reservasi lapangan dan tentukan kuota pemain serta jam tanding.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="w-5 h-5 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-400 font-bold flex items-center justify-center shrink-0 text-[10px]">2</span>
              <span><strong>Pemain Join & Bayar:</strong> Pemain lain klik "Gabung & Bayar Slot" via QRIS instan dan invoice patungan otomatis masuk profil.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-400 font-bold flex items-center justify-center shrink-0 text-[10px]">3</span>
              <span><strong>Penuh Langsung Main:</strong> Begitu kuota pemain terpenuhi, jadwal mabar otomatis terkunci dan seluruh tim siap bertanding di lapangan!</span>
            </li>
            <li class="flex items-start gap-2.5">
              <span class="w-5 h-5 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-800 dark:text-rose-400 font-bold flex items-center justify-center shrink-0 text-[10px]">4</span>
              <span><strong>Auto-Cancel (Risiko Host):</strong> Jika kuota belum terpenuhi hingga H-2 Jam kick-off, room otomatis batal &amp; jadwal lapangan dilepas ke publik. Pembayaran host non-refundable.</span>
            </li>
          </ol>
        </div>

        <!-- Quick CTA: Buka Room Sendiri -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-6 sm:p-7 rounded-3xl shadow-lg border border-slate-700">
          <div class="w-10 h-10 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center mb-3.5 shadow-inner">
            <i data-lucide="plus" class="w-5 h-5"></i>
          </div>
          <h3 class="font-black text-base">Punya Jadwal tapi Kurang Pemain?</h3>
          <p class="text-xs text-slate-300 mt-1.5 leading-relaxed">
            Buka room publik agar futsal lovers di sekitarmu bisa gabung dan bantu patungan sewa lapangan secara otomatis!
          </p>
          <button onclick="openCreateRoomModal()" class="w-full mt-5 py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs rounded-xl shadow-md transition cursor-pointer">
            Buka Room Open Slot
          </button>
        </div>

        <!-- Komitmen Bermain Card -->
        <div class="p-5 rounded-3xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 text-xs text-slate-600 dark:text-slate-400 space-y-2">
          <div class="font-bold flex items-center gap-2 text-slate-900 dark:text-white">
            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i>
            <span>Komitmen Mabar LapanganKita</span>
          </div>
          <p class="text-[11px] leading-relaxed">
            Pembayaran patungan bersifat resmi dan tercatat langsung di invoice profil pemain. Pastikan hadir tepat waktu 15 menit sebelum kick-off untuk kelancaran pertandingan bersama.
          </p>
        </div>

      </aside>

    </div>
  </main>

  <!-- ========================================================================= -->
  <!-- MODAL BUAT ROOM OPEN SLOT BARU                                            -->
  <!-- ========================================================================= -->
  <div id="modal-create-room" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 max-w-lg w-full rounded-3xl shadow-2xl overflow-hidden border border-slate-100 dark:border-slate-800 max-h-[92vh] overflow-y-auto">
      <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
        <div>
          <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase tracking-wider mb-1">
            <i data-lucide="sparkles" class="w-3 h-3"></i>
            <span>Buka Room Mabar &amp; Midtrans Gateway</span>
          </div>
          <h3 class="text-base font-black text-slate-900 dark:text-white">Buka Room Open Slot Baru</h3>
          <p class="text-xs text-slate-500">Tentukan kuota &amp; slot rombongan Anda. Bayar 1x di awal via Midtrans.</p>
        </div>
        <button onclick="closeCreateRoomModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form id="formCreateOpenSlot" onsubmit="event.preventDefault(); payCreateRoomHostMidtrans();" class="p-6 space-y-4">
        @csrf

        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Judul / Catatan Room</label>
          <input type="text" id="createTitle" name="title" placeholder="Contoh: Butuh 5 Orang Tambahan / Fun Match Santai" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Pilih Venue &amp; Unit Lapangan</label>
          <select id="createCourtId" name="court_id" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none">
            @foreach($venues as $v)
              <optgroup label="{{ $v->name }} ({{ $v->city }})">
                @foreach($v->courts as $c)
                  <option value="{{ $c->id }}">{{ $v->name }} - {{ $c->name }} ({{ $c->surface_type }})</option>
                @endforeach
              </optgroup>
            @endforeach
          </select>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Main</label>
            <input type="date" id="createBookingDate" name="booking_date" required value="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Jam Tanding</label>
            <select id="createTimeSlot" name="time_slot" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none">
              <optgroup label="Slot Malam (Paling Populer)">
                <option value="19:00 - 20:00">19:00 - 20:00 WIB (1 Jam)</option>
                <option value="19:00 - 21:00" selected>19:00 - 21:00 WIB (2 Jam)</option>
                <option value="20:00 - 21:00">20:00 - 21:00 WIB (1 Jam)</option>
                <option value="20:00 - 22:00">20:00 - 22:00 WIB (2 Jam)</option>
                <option value="21:00 - 22:00">21:00 - 22:00 WIB (1 Jam)</option>
                <option value="21:00 - 23:00">21:00 - 23:00 WIB (2 Jam)</option>
                <option value="22:00 - 23:00">22:00 - 23:00 WIB (1 Jam)</option>
              </optgroup>
              <optgroup label="Slot Sore">
                <option value="15:00 - 17:00">15:00 - 17:00 WIB (2 Jam)</option>
                <option value="16:00 - 18:00">16:00 - 18:00 WIB (2 Jam)</option>
                <option value="17:00 - 18:00">17:00 - 18:00 WIB (1 Jam)</option>
                <option value="17:00 - 19:00">17:00 - 19:00 WIB (2 Jam)</option>
                <option value="18:00 - 19:00">18:00 - 19:00 WIB (1 Jam)</option>
                <option value="18:00 - 20:00">18:00 - 20:00 WIB (2 Jam)</option>
              </optgroup>
              <optgroup label="Slot Pagi &amp; Siang">
                <option value="08:00 - 10:00">08:00 - 10:00 WIB (2 Jam)</option>
                <option value="10:00 - 12:00">10:00 - 12:00 WIB (2 Jam)</option>
                <option value="13:00 - 15:00">13:00 - 15:00 WIB (2 Jam)</option>
                <option value="14:00 - 16:00">14:00 - 16:00 WIB (2 Jam)</option>
              </optgroup>
            </select>
          </div>
        </div>

        <!-- Target Kuota & Slot Rombongan Host -->
        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 space-y-3.5">
          <div class="grid grid-cols-2 gap-3">
            <div>
              <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-black text-slate-800 dark:text-slate-200">Target Kuota Total</label>
                <span class="text-[10px] text-slate-400">Min 4</span>
              </div>
              <input type="number" id="createTargetPlayers" name="target_players" min="4" max="24" value="10" oninput="updateCreateRoomCalc()" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-black text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none text-center">
              <!-- Quick pills kuota -->
              <div class="flex items-center gap-1.5 pt-1.5 flex-wrap">
                <button type="button" onclick="setCreateTarget(8)" class="px-2 py-0.5 rounded-md bg-slate-200 dark:bg-slate-750 text-[10px] font-bold text-slate-700 dark:text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition cursor-pointer">8</button>
                <button type="button" onclick="setCreateTarget(10)" class="px-2 py-0.5 rounded-md bg-slate-200 dark:bg-slate-750 text-[10px] font-bold text-slate-700 dark:text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition cursor-pointer">10</button>
                <button type="button" onclick="setCreateTarget(12)" class="px-2 py-0.5 rounded-md bg-slate-200 dark:bg-slate-750 text-[10px] font-bold text-slate-700 dark:text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition cursor-pointer">12</button>
                <button type="button" onclick="setCreateTarget(14)" class="px-2 py-0.5 rounded-md bg-slate-200 dark:bg-slate-750 text-[10px] font-bold text-slate-700 dark:text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition cursor-pointer">14</button>
              </div>
            </div>

            <div>
              <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-black text-amber-600 dark:text-amber-400">Slot Rombongan Host</label>
                <span class="text-[10px] text-amber-500 font-bold" id="badgeHostSlotLabel">Bawa Rombongan</span>
              </div>
              <input type="number" id="createHostSlots" name="host_slots" min="1" max="9" value="1" oninput="updateCreateRoomCalc()" class="w-full px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-amber-300 dark:border-amber-700 rounded-xl text-xs font-black text-amber-600 dark:text-amber-400 focus:ring-2 focus:ring-amber-500 focus:outline-none text-center">
              <!-- Quick pills host slots -->
              <div class="flex items-center gap-1.5 pt-1.5 flex-wrap">
                <button type="button" onclick="setCreateHostSlots(1)" class="px-2 py-0.5 rounded-md bg-amber-500/15 text-[10px] font-bold text-amber-700 dark:text-amber-300 hover:bg-amber-500 hover:text-slate-950 transition cursor-pointer">1 (Sendiri)</button>
                <button type="button" onclick="setCreateHostSlots(2)" class="px-2 py-0.5 rounded-md bg-slate-200 dark:bg-slate-750 text-[10px] font-bold text-slate-700 dark:text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition cursor-pointer">2</button>
                <button type="button" onclick="setCreateHostSlots(5)" class="px-2 py-0.5 rounded-md bg-slate-200 dark:bg-slate-750 text-[10px] font-bold text-slate-700 dark:text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition cursor-pointer">5</button>
                <button type="button" onclick="setCreateHostSlots(6)" class="px-2 py-0.5 rounded-md bg-slate-200 dark:bg-slate-750 text-[10px] font-bold text-slate-700 dark:text-slate-300 hover:bg-amber-500 hover:text-slate-950 transition cursor-pointer">6</button>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-200/70 dark:border-slate-800">
            <div>
              <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Patungan / Orang</label>
              <input type="number" id="createPricePerPerson" name="price_per_person" step="5000" min="5000" value="25000" oninput="updateCreateRoomCalc()" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-black text-emerald-600 focus:ring-2 focus:ring-amber-500 focus:outline-none">
            </div>
            <div>
              <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Tingkat Permainan</label>
              <select id="createLevel" name="level" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none">
                <option value="Fun & Santai">Fun Match (Santai)</option>
                <option value="Semi-Pro">Semi-Pro (Medium)</option>
                <option value="Kompetitif">Kompetitif (Serius)</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Live Calculation Box -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-2 text-xs">
          <div class="flex items-center justify-between text-slate-500">
            <span>Slot Rombongan Host (Dibayar Sekarang):</span>
            <strong class="text-amber-600 dark:text-amber-400 font-bold" id="createSummaryHostSlots">1 Orang (Sendiri)</strong>
          </div>
          <div class="flex items-center justify-between text-slate-500">
            <span>Sisa Kuota Terbuka untuk Orang Lain:</span>
            <strong class="text-slate-800 dark:text-slate-200 font-bold" id="createSummaryOpenSlots">9 Slot Lagi</strong>
          </div>
          <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div>
              <span class="text-xs font-black text-slate-900 dark:text-white block uppercase tracking-wider">Total Pembayaran Host:</span>
              <span class="text-[10px] text-slate-400 block" id="createSummaryCalcFormula">1 Orang × Rp 25.000</span>
            </div>
            <strong class="text-xl font-black text-emerald-600 dark:text-emerald-400" id="createSummaryTotalPay">Rp 25.000</strong>
          </div>
        </div>

        <!-- Info Midtrans Notice & Ketentuan Risiko Host (Auto-Cancel) -->
        <div class="p-3.5 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-[11px] text-amber-900 dark:text-amber-300 leading-relaxed space-y-1.5">
          <div class="flex items-center gap-1.5 font-black text-amber-800 dark:text-amber-200">
            <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-500 shrink-0"></i>
            <span>Ketentuan Risiko Host (Auto-Cancel H-2 Jam):</span>
          </div>
          <p class="text-[11px] leading-relaxed text-amber-800/90 dark:text-amber-300/80">
            • Jika kuota mabar belum terpenuhi sampai <strong>H-2 Jam sebelum kick-off</strong>, room otomatis dibatalkan sistem dan <strong>jadwal lapangan dilepas kembali ke publik</strong> agar venue tidak rugi slot.<br>
            • Sebagai pemrakarsa, pembayaran slot rombongan host menjadi <strong>risiko host (non-refundable)</strong> sebagai jaminan penahanan jadwal lapangan.
          </p>
        </div>

        <!-- Action Button Midtrans Snap -->
        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:opacity-95 text-slate-950 font-black text-xs sm:text-sm rounded-xl shadow-lg shadow-amber-500/25 transition flex items-center justify-center gap-2 cursor-pointer">
          <i data-lucide="shield-check" class="w-4 h-4"></i>
          <span>Bayar Slot Host via Midtrans (<span id="createBtnPayTotal">Rp 25.000</span>)</span>
          <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
      </form>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MODAL KONFIRMASI GABUNG & BAYAR SLOT (TAMPILKAN QRIS INSTAN)               -->
  <!-- ========================================================================= -->
  <div id="joinRoomModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 max-w-md w-full rounded-3xl shadow-2xl overflow-hidden border border-slate-100 dark:border-slate-800 relative max-h-[92vh] overflow-y-auto">
      
      <!-- Modal Header -->
      <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
        <div class="space-y-0.5">
          <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-wider">
            <i data-lucide="shield-check" class="w-3 h-3"></i>
            <span>Midtrans Payment Gateway</span>
          </div>
          <h3 class="text-sm sm:text-base font-black text-slate-900 dark:text-white line-clamp-1" id="modalJoinTitle">Judul Room</h3>
          <p class="text-[11px] text-slate-500 line-clamp-1" id="modalJoinVenue">Venue & Jam</p>
        </div>
        <button type="button" onclick="closeJoinModal()" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 hover:text-slate-600 dark:hover:text-white flex items-center justify-center transition cursor-pointer shrink-0">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>

      <form action="{{ route('community.open-slot.join') }}" method="POST" class="p-5 sm:p-6 space-y-4">
        @csrf
        <input type="hidden" name="room_id" id="modalJoinRoomId" value="">
        <input type="hidden" name="player_role" id="modalJoinPlayerRole" value="player">

        <!-- Role Selection Pill: Pemain vs Kiper -->
        <div class="bg-slate-100 dark:bg-slate-800 p-1 rounded-xl flex items-center text-xs">
          <button type="button" id="btnRolePlayer" onclick="selectPlayerRole('player')" class="flex-1 py-1.5 rounded-lg text-xs font-bold transition bg-emerald-500 text-white shadow-sm cursor-pointer">
            Pemain Biasa
          </button>
          <button type="button" id="btnRoleGk" onclick="selectPlayerRole('goalkeeper')" class="flex-1 py-1.5 rounded-lg text-xs font-bold transition text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 cursor-pointer">
            Kiper (Subsidi)
          </button>
        </div>

        <!-- Multi-Person Custom Slot Selector -->
        <div class="bg-slate-50 dark:bg-slate-850 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <span class="text-xs font-black text-slate-900 dark:text-white block">Mau Bayar Untuk Berapa Orang?</span>
              <span class="text-[11px] text-slate-500 block">Satu orang bisa bayari teman / rombongan sekaligus</span>
            </div>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20" id="badgeSlotsRemaining">
              Maks: 2 Orang
            </span>
          </div>

          <!-- Quantity Counter Control -->
          <div class="flex items-center gap-3">
            <button type="button" onclick="changeJoinSlotCount(-1)" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 font-black text-lg flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-700 transition cursor-pointer shadow-sm">
              -
            </button>
            <div class="flex-1 relative">
              <input type="number" id="modalSlotCount" name="slot_count" min="1" max="10" value="1" oninput="onSlotCountInput(this.value)" class="w-full text-center py-2 px-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl font-black text-base text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
              <span class="absolute right-3 top-2.5 text-[11px] font-bold text-slate-400 pointer-events-none">Orang</span>
            </div>
            <button type="button" onclick="changeJoinSlotCount(1)" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 font-black text-lg flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-700 transition cursor-pointer shadow-sm">
              +
            </button>
          </div>

          <!-- Quick Preset Pills -->
          <div class="flex items-center gap-2 pt-1 flex-wrap">
            <button type="button" onclick="setJoinSlotCount(1)" class="px-2.5 py-1 rounded-lg bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 font-bold text-[11px] hover:bg-emerald-500/25 transition cursor-pointer">
              1 Orang
            </button>
            <button type="button" onclick="setJoinSlotCount(2)" class="px-2.5 py-1 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-[11px] hover:bg-slate-300 dark:hover:bg-slate-600 transition cursor-pointer" id="btnPill2">
              2 Orang
            </button>
            <button type="button" onclick="setJoinSlotCount(3)" class="px-2.5 py-1 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-[11px] hover:bg-slate-300 dark:hover:bg-slate-600 transition cursor-pointer" id="btnPill3">
              3 Orang
            </button>
            <button type="button" onclick="setJoinSlotCount('max')" class="px-2.5 py-1 rounded-lg bg-amber-500/15 text-amber-600 dark:text-amber-400 font-bold text-[11px] hover:bg-amber-500/25 transition cursor-pointer" id="btnPillMax">
              Borong Semua Slot
            </button>
          </div>

          <!-- Optional Notes / Participant names -->
          <div class="pt-2 border-t border-slate-200/80 dark:border-slate-700/60">
            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1">
              Nama Peserta / Teman Tambahan (Opsional):
            </label>
            <input type="text" name="notes" id="modalJoinNotes" placeholder="Contoh: Saya & Rian, atau Squad ABC" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
          </div>
        </div>

        <!-- Price Breakdown & Multiplication Box -->
        <div class="bg-white dark:bg-slate-900 p-3.5 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-2">
          <div class="flex items-center justify-between text-xs text-slate-500">
            <span>Biaya Asli Patungan / Orang:</span>
            <strong class="text-slate-800 dark:text-slate-200 font-bold" id="modalPricePerPerson">Rp 25.000</strong>
          </div>
          <div class="flex items-center justify-between text-xs text-slate-500">
            <span>Perhitungan Total:</span>
            <span class="font-bold text-slate-700 dark:text-slate-300" id="modalCalcDetail">1 Orang × Rp 25.000</span>
          </div>
          <div class="pt-2 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider">Total Pembayaran:</span>
            <strong class="text-xl font-black text-emerald-600 dark:text-emerald-400" id="modalTotalPay">Rp 25.000</strong>
          </div>
        </div>
        
        <!-- Supported Channels Banner -->
        <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 space-y-2">
          <div class="flex items-center justify-between text-[11px] font-bold text-slate-700 dark:text-slate-300">
            <span class="flex items-center gap-1.5">
              <i data-lucide="credit-card" class="w-3.5 h-3.5 text-emerald-500"></i>
              <span>Metode Pembayaran Resmi (Midtrans)</span>
            </span>
            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-extrabold">Verifikasi Otomatis</span>
          </div>
          <div class="flex flex-wrap items-center justify-between gap-1 text-[10px] text-slate-500 dark:text-slate-400">
            <span>QRIS (GoPay/ShopeePay/Dana)</span>
            <span>•</span>
            <span>BCA VA</span>
            <span>•</span>
            <span>Mandiri</span>
            <span>•</span>
            <span>BRI / BNI</span>
          </div>
        </div>

        <!-- Information Info -->
        <div class="flex items-start gap-2 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200/60 dark:border-emerald-800/40 text-[11px] text-emerald-800 dark:text-emerald-300 leading-relaxed">
          <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
          <span>Invoice resmi langsung terbit di profil akun Anda. Jadwal lapangan otomatis terkunci begitu kuota pemain terpenuhi.</span>
        </div>

        <!-- MIDTRANS SNAP UTAMA ACTION BUTTON -->
        <button type="button" onclick="payOpenSlotMidtrans()" 
                class="w-full py-3.5 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 hover:opacity-95 text-slate-950 font-black text-xs sm:text-sm rounded-xl shadow-lg shadow-emerald-500/25 transition flex items-center justify-center gap-2 cursor-pointer">
          <i data-lucide="shield-check" class="w-4 h-4"></i>
          <span>Bayar Slot via Midtrans (<span id="modalBtnMidtransTotal">Rp 25.000</span>)</span>
          <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
        
        <button type="button" onclick="closeJoinModal()" class="w-full py-2 text-xs text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 font-bold cursor-pointer">
          Batal
        </button>
      </form>
    </div>
  </div>

</div>

<!-- ========================================================================= -->
<!-- JAVASCRIPT LOGIC FILTER, SEARCH, & MODAL                                  -->
<!-- ========================================================================= -->
<script>
  let activeFilter = 'all';

  function setFilter(filter) {
    activeFilter = filter;
    
    // Update Button Styles
    document.querySelectorAll('.room-filter-btn').forEach(btn => {
      btn.className = 'room-filter-btn px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs whitespace-nowrap cursor-pointer transition';
    });

    const activeBtn = document.getElementById('btn-' + filter);
    if (activeBtn) {
      activeBtn.className = 'room-filter-btn px-3.5 py-1.5 rounded-xl bg-amber-500 text-slate-950 font-black text-xs whitespace-nowrap shadow-sm cursor-pointer transition';
    }

    applyFilters();
  }

  function handleSearch(query) {
    applyFilters();
  }

  function resetAllFilters() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) searchInput.value = '';
    setFilter('all');
  }

  function applyFilters() {
    const query = (document.getElementById('searchInput')?.value || '').toLowerCase().trim();
    const cards = document.querySelectorAll('.open-slot-card');
    let visibleCount = 0;

    cards.forEach(card => {
      const title = card.getAttribute('data-title') || '';
      const venue = card.getAttribute('data-venue') || '';
      const isNight = card.getAttribute('data-is-night') === '1';
      const isUrgent = card.getAttribute('data-is-urgent') === '1';
      const isFun = card.getAttribute('data-is-fun') === '1';

      // Text query match
      const textMatches = !query || title.includes(query) || venue.includes(query);

      // Pill category match
      let filterMatches = true;
      if (activeFilter === 'night') {
        filterMatches = isNight;
      } else if (activeFilter === 'urgent') {
        filterMatches = isUrgent;
      } else if (activeFilter === 'fun') {
        filterMatches = isFun;
      }

      if (textMatches && filterMatches) {
        card.style.display = 'block';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    const emptyBox = document.getElementById('openSlotEmptyState');
    if (emptyBox) {
      if (visibleCount === 0 && cards.length > 0) {
        emptyBox.classList.remove('hidden');
      } else {
        emptyBox.classList.add('hidden');
      }
    }
  }

  function openCreateRoomModal() {
    const modal = document.getElementById('modal-create-room');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    updateCreateRoomCalc();
    if (window.lucide) lucide.createIcons();
  }

  function closeCreateRoomModal() {
    const modal = document.getElementById('modal-create-room');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  function setCreateTarget(target) {
    const input = document.getElementById('createTargetPlayers');
    if (input) {
      input.value = target;
      updateCreateRoomCalc();
    }
  }

  function setCreateHostSlots(slots) {
    const input = document.getElementById('createHostSlots');
    if (input) {
      input.value = slots;
      updateCreateRoomCalc();
    }
  }

  function updateCreateRoomCalc() {
    const targetInput = document.getElementById('createTargetPlayers');
    const hostInput = document.getElementById('createHostSlots');
    const priceInput = document.getElementById('createPricePerPerson');

    let target = parseInt(targetInput?.value) || 10;
    if (target < 4) target = 4;

    let maxHost = Math.max(1, target - 1);
    if (hostInput) {
      hostInput.max = maxHost;
      let host = parseInt(hostInput.value) || 1;
      if (host < 1) host = 1;
      if (host > maxHost) {
        host = maxHost;
        hostInput.value = host;
      }
      
      let price = parseFloat(priceInput?.value) || 25000;
      let openSlots = Math.max(1, target - host);
      let totalHost = host * price;

      const summaryHostEl = document.getElementById('createSummaryHostSlots');
      if (summaryHostEl) {
        summaryHostEl.innerText = host + ' Orang ' + (host === 1 ? '(Sendiri)' : '(Rombongan)');
      }

      const summaryOpenEl = document.getElementById('createSummaryOpenSlots');
      if (summaryOpenEl) {
        summaryOpenEl.innerText = openSlots + ' Slot Terbuka untuk Umum';
      }

      const formulaEl = document.getElementById('createSummaryCalcFormula');
      if (formulaEl) {
        formulaEl.innerText = host + ' Orang × Rp ' + price.toLocaleString('id-ID');
      }

      const totalPayEl = document.getElementById('createSummaryTotalPay');
      if (totalPayEl) {
        totalPayEl.innerText = 'Rp ' + totalHost.toLocaleString('id-ID');
      }

      const btnPayEl = document.getElementById('createBtnPayTotal');
      if (btnPayEl) {
        btnPayEl.innerText = 'Rp ' + totalHost.toLocaleString('id-ID');
      }
    }
  }

  function payCreateRoomHostMidtrans() {
    const title = document.getElementById('createTitle')?.value?.trim();
    if (!title) {
      alert('Mohon isi judul room mabar terlebih dahulu.');
      return;
    }

    const courtId = document.getElementById('createCourtId')?.value;
    const bookingDate = document.getElementById('createBookingDate')?.value;
    const timeSlot = document.getElementById('createTimeSlot')?.value;
    const targetPlayers = parseInt(document.getElementById('createTargetPlayers')?.value) || 10;
    const hostSlots = parseInt(document.getElementById('createHostSlots')?.value) || 1;
    const pricePerPerson = parseFloat(document.getElementById('createPricePerPerson')?.value) || 25000;
    const level = document.getElementById('createLevel')?.value || 'Fun & Santai';

    if (hostSlots >= targetPlayers) {
      alert('Slot rombongan host tidak boleh sama dengan atau melebihi target kuota total. Sisakan minimal 1 slot untuk pemain umum.');
      return;
    }

    closeCreateRoomModal();

    initiateMidtransPayment('open_slot_host', {
      title: title,
      court_id: courtId,
      booking_date: bookingDate,
      time_slot: timeSlot,
      target_players: targetPlayers,
      host_slots: hostSlots,
      price_per_person: pricePerPerson,
      level: level,
    }, {
      onError: function() {
        openCreateRoomModal();
      }
    });
  }

  let currentRoomId = null;
  let currentRoomPrice = 25000;
  let currentGkPrice = 15000;
  let currentMaxSlots = 1;
  let currentSelectedRole = 'player';

  function selectPlayerRole(role) {
    currentSelectedRole = role;
    const roleInput = document.getElementById('modalJoinPlayerRole');
    if (roleInput) roleInput.value = role;

    const btnPlayer = document.getElementById('btnRolePlayer');
    const btnGk = document.getElementById('btnRoleGk');
    if (btnPlayer && btnGk) {
      if (role === 'goalkeeper') {
        btnGk.className = 'flex-1 py-1.5 rounded-lg text-xs font-bold transition bg-amber-500 text-slate-950 shadow-sm cursor-pointer';
        btnPlayer.className = 'flex-1 py-1.5 rounded-lg text-xs font-bold transition text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 cursor-pointer';
      } else {
        btnPlayer.className = 'flex-1 py-1.5 rounded-lg text-xs font-bold transition bg-emerald-500 text-white shadow-sm cursor-pointer';
        btnGk.className = 'flex-1 py-1.5 rounded-lg text-xs font-bold transition text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 cursor-pointer';
      }
    }
    const count = parseInt(document.getElementById('modalSlotCount')?.value) || 1;
    updateJoinCalculation(count);
  }

  function openJoinModal(roomId, title, venue, schedule, price, slotsLeft) {
    currentRoomId = roomId;
    currentRoomPrice = Number(price);
    currentGkPrice = Math.max(10000, currentRoomPrice - 10000);
    currentMaxSlots = Math.max(1, Number(slotsLeft));
    currentSelectedRole = 'player';

    // Reset role tab to Player
    const roleInput = document.getElementById('modalJoinPlayerRole');
    if (roleInput) roleInput.value = 'player';
    const btnPlayer = document.getElementById('btnRolePlayer');
    const btnGk = document.getElementById('btnRoleGk');
    if (btnPlayer && btnGk) {
      btnPlayer.className = 'flex-1 py-1.5 rounded-lg text-xs font-bold transition bg-emerald-500 text-white shadow-sm cursor-pointer';
      btnGk.className = 'flex-1 py-1.5 rounded-lg text-xs font-bold transition text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 cursor-pointer';
    }

    document.getElementById('modalJoinRoomId').value = roomId;
    document.getElementById('modalJoinTitle').innerText = title;
    document.getElementById('modalJoinVenue').innerText = venue + ' • ' + schedule;
    document.getElementById('modalPricePerPerson').innerText = 'Rp ' + currentRoomPrice.toLocaleString('id-ID');
    document.getElementById('badgeSlotsRemaining').innerText = 'Maks: ' + currentMaxSlots + ' Orang';

    // Hide or adjust presets based on available slots
    const btnPill2 = document.getElementById('btnPill2');
    const btnPill3 = document.getElementById('btnPill3');
    if (btnPill2) btnPill2.style.display = currentMaxSlots >= 2 ? 'inline-block' : 'none';
    if (btnPill3) btnPill3.style.display = currentMaxSlots >= 3 ? 'inline-block' : 'none';

    // Reset input count to 1
    const countInput = document.getElementById('modalSlotCount');
    countInput.max = currentMaxSlots;
    countInput.value = 1;

    // Reset notes
    const notesInput = document.getElementById('modalJoinNotes');
    if (notesInput) notesInput.value = '';

    updateJoinCalculation(1);

    const modal = document.getElementById('joinRoomModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    if (window.lucide) lucide.createIcons();
  }

  function changeJoinSlotCount(delta) {
    const input = document.getElementById('modalSlotCount');
    let val = parseInt(input.value) || 1;
    val += delta;
    if (val < 1) val = 1;
    if (val > currentMaxSlots) val = currentMaxSlots;
    input.value = val;
    updateJoinCalculation(val);
  }

  function setJoinSlotCount(count) {
    const input = document.getElementById('modalSlotCount');
    let val = count === 'max' ? currentMaxSlots : parseInt(count);
    if (val < 1) val = 1;
    if (val > currentMaxSlots) val = currentMaxSlots;
    input.value = val;
    updateJoinCalculation(val);
  }

  function onSlotCountInput(val) {
    let num = parseInt(val) || 1;
    if (num < 1) num = 1;
    if (num > currentMaxSlots) num = currentMaxSlots;
    updateJoinCalculation(num);
  }

  function updateJoinCalculation(count) {
    const activeUnitPrice = (currentSelectedRole === 'goalkeeper') ? currentGkPrice : currentRoomPrice;
    const roleLabel = (currentSelectedRole === 'goalkeeper') ? 'Posisi Kiper (Subsidi)' : 'Pemain Biasa';
    const total = count * activeUnitPrice;

    document.getElementById('modalPricePerPerson').innerText = 'Rp ' + activeUnitPrice.toLocaleString('id-ID') + ' / orang';
    document.getElementById('modalCalcDetail').innerText = count + ' Orang (' + roleLabel + ') × Rp ' + activeUnitPrice.toLocaleString('id-ID');
    document.getElementById('modalTotalPay').innerText = 'Rp ' + total.toLocaleString('id-ID');
    const btnTotal = document.getElementById('modalBtnTotal');
    if (btnTotal) btnTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
    const midtransBtnTotal = document.getElementById('modalBtnMidtransTotal');
    if (midtransBtnTotal) {
      midtransBtnTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    const qrisImg = document.getElementById('modalJoinQrisImg');
    if (qrisImg) {
      const qrisData = encodeURIComponent('LAPANGANKITA-OPENSLOT-ROOM-' + currentRoomId + '-ROLE-' + currentSelectedRole + '-SLOTS-' + count + '-TOTAL-' + total);
      qrisImg.src = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' + qrisData;
    }
  }

  function payOpenSlotMidtrans() {
    const roomId = document.getElementById('modalJoinRoomId').value;
    const slotCount = parseInt(document.getElementById('modalSlotCount').value) || 1;
    const notes = document.getElementById('modalJoinNotes').value || '';

    closeJoinModal();

    initiateMidtransPayment('open_slot', {
      room_id: roomId,
      player_role: currentSelectedRole,
      slot_count: slotCount,
      notes: notes,
    }, {
      onError: function() {
        const modal = document.getElementById('joinRoomModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    });
  }

  function closeJoinModal() {
    const modal = document.getElementById('joinRoomModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeCreateRoomModal();
      closeJoinModal();
    }
  });
</script>

@include('partials.midtrans-modal')
@endsection
