@extends('layouts.app')

@section('title', 'Lobby Sparing Antar Tim - LapanganKita')
@section('meta_description', 'Lobby Matchmaking Komunitas Antar Tim Futsal: temukan lawan tanding selevel dengan biaya sewa lapangan dibagi rata 50:50 secara transparan.')

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
  <!-- HERO SECTION SPARING (SESUAI DESAIN USER)                                 -->
  <!-- ========================================================================= -->
  <header class="bg-gradient-to-r from-slate-950 via-slate-900 to-red-950 text-white py-10 sm:py-12 relative overflow-hidden border-b border-red-900/30 shadow-inner">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div>
          <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-red-500/20 text-red-300 text-xs font-bold mb-3 border border-red-500/30 shadow-sm">
            <i data-lucide="shield" class="w-3.5 h-3.5 text-red-400"></i>
            <span>Matchmaking Komunitas Antar Tim Futsal</span>
          </div>
          <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">
            Lobby Sparing: Temukan Lawan Tanding Selevel! 
          </h1>
          <p class="text-xs sm:text-sm text-slate-300 mt-2 max-w-2xl leading-relaxed">
            Tim kamu sudah punya jadwal lapangan tapi belum ada lawan? Pasang tantangan di sini atau terima tantangan tim lain. Biaya sewa lapangan dibagi rata 50:50 secara transparan.
          </p>
        </div>

        <!-- Metric Badges & Action -->
        <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 shrink-0">
          <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md p-3.5 rounded-2xl border border-white/10 text-center">
            <div class="px-3">
              <span class="text-2xl font-black text-red-400 block">{{ $challenges->where('status', 'open')->count() ?: $challenges->count() }}</span>
              <span class="text-[10px] text-slate-300 uppercase font-bold tracking-wider">Tantangan Open</span>
            </div>
            <div class="h-8 w-px bg-white/20"></div>
            <div class="px-3">
              <span class="text-2xl font-black text-emerald-400 block">45</span>
              <span class="text-[10px] text-slate-300 uppercase font-bold tracking-wider">Match Selesai</span>
            </div>
          </div>

          <button onclick="openPostChallengeModal()" class="px-5 py-3.5 bg-red-600 hover:bg-red-700 active:scale-[0.99] text-white font-extrabold text-xs sm:text-sm rounded-2xl shadow-lg shadow-red-600/30 transition flex items-center gap-2 cursor-pointer shrink-0">
            <i data-lucide="swords" class="w-4 h-4"></i>
            <span>Posting Tantangan Baru</span>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- ========================================================================= -->
  <!-- FILTER & SEARCH BAR                                                       -->
  <!-- ========================================================================= -->
  <div class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 py-3.5 shadow-sm sticky top-20 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
      
      <!-- Filter Chips -->
      <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0 no-scrollbar">
        <button onclick="setSparingFilter('all')" id="sparing-filter-all" class="sparing-chip px-3.5 py-1.5 rounded-xl bg-red-600 text-white font-bold text-xs whitespace-nowrap shadow-sm cursor-pointer transition">
          Semua Tantangan ({{ $challenges->count() }})
        </button>
        <button onclick="setSparingFilter('fun')" id="sparing-filter-fun" class="sparing-chip px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs whitespace-nowrap cursor-pointer transition">
          Casual / Fun (Non-Emosi)
        </button>
        <button onclick="setSparingFilter('semipro')" id="sparing-filter-semipro" class="sparing-chip px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs whitespace-nowrap cursor-pointer transition">
          Semi-Pro / Menengah
        </button>
        <button onclick="setSparingFilter('kompetitif')" id="sparing-filter-kompetitif" class="sparing-chip px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs whitespace-nowrap cursor-pointer transition">
          Kompetitif / Liga
        </button>
      </div>

      <!-- Area Search -->
      <div class="relative w-full md:w-72 shrink-0">
        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-2.5 text-slate-400"></i>
        <input type="text" id="sparingSearchInput" oninput="handleSparingSearch(this.value)" placeholder="Cari nama tim / lokasi..." class="w-full pl-9 pr-3.5 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:outline-none">
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MAIN LIST CARDS (8 COLS : 4 COLS)                                         -->
  <!-- ========================================================================= -->
  <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

      <!-- ================= LIST OF CHALLENGES (8 COLS) ================= -->
      <div class="lg:col-span-8 space-y-5" id="sparingCardsContainer">

        @forelse($challenges as $index => $ch)
          @php
            $tier = strtolower($ch->skill_tier ?? 'menengah');
            $isFun = ($tier === 'amatir' || str_contains($tier, 'fun'));
            $isSemiPro = ($tier === 'menengah' || $tier === 'semipro');
            $isKompetitif = ($tier === 'kompetitif' || str_contains($tier, 'liga'));
            
            $tierBadgeClass = $isFun 
              ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/40 border-blue-200 dark:border-blue-800' 
              : ($isSemiPro 
                  ? 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 border-red-200 dark:border-red-800' 
                  : 'text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800');
            
            $tierLabel = $isFun ? 'Level: Fun / Casual' : ($isSemiPro ? 'Level: Semi-Pro' : 'Level: Kompetitif / Liga');
            $subNote = $isFun ? '• Bebas Emosi' : ($isSemiPro ? '• Win Rate 68%' : '• Taktik Tinggi');

            $totalSewa = $ch->booking->total_amount ?? 175000;
            $bebanTamu = round($totalSewa / 2);
            $isHost = auth()->check() && ($ch->booking?->user_id === auth()->id());
            $bebanLabel = $isHost ? 'Porsi Anda (50% Lunas)' : 'Beban Patungan Tim Lawan (50%)';

            $teamInitials = strtoupper(substr($ch->host_team_name, 0, 2));
            $isMatched = ($ch->status === 'matched' || !empty($ch->challenger_team_name));

            // Quote variatif
            $sampleQuotes = [
              '"Cari lawan santai tapi taktik jalan. Menjunjung tinggi fair play."',
              '"Main senang-senang cari keringat habis pulang kantor. No tackle keras."',
              '"Uji coba persiapan turnamen regional. Butuh tim yang main cepat dan disiplin."',
              '"Sparing persahabatan antar alumni, santai dan menjunjung tinggi fair play."',
            ];
            $quote = $sampleQuotes[$index % count($sampleQuotes)];
          @endphp

          <!-- CARD TANTANGAN -->
          <div class="sparing-card bg-white dark:bg-slate-900 rounded-3xl border {{ $isHost ? 'border-red-400/50 dark:border-red-500/40 ring-1 ring-red-500/20' : 'border-slate-200 dark:border-slate-800' }} p-6 sm:p-7 shadow-sm hover:shadow-xl transition relative group"
               data-team="{{ strtolower($ch->host_team_name) }}"
               data-venue="{{ strtolower($ch->booking->tenant->name ?? '') }}"
               data-tier="{{ $isFun ? 'fun' : ($isSemiPro ? 'semipro' : 'kompetitif') }}">
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
              
              <!-- Tim Info -->
              <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-600 via-rose-600 to-amber-600 text-white flex items-center justify-center font-black text-lg shadow-md shrink-0">
                  {{ $teamInitials }}
                </div>
                <div>
                  <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-black px-2.5 py-0.5 rounded-md border {{ $tierBadgeClass }}">
                      {{ $tierLabel }}
                    </span>
                    @if($isHost)
                      <span class="text-[10px] font-black px-2.5 py-0.5 rounded-md bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 flex items-center gap-1">
                        <i data-lucide="user-check" class="w-3 h-3"></i>
                        <span>Tantangan Anda</span>
                      </span>
                    @endif
                    <span class="text-xs text-slate-400 font-medium">{{ $subNote }}</span>
                  </div>
                  <h2 class="text-lg font-black text-slate-900 dark:text-white group-hover:text-red-600 dark:group-hover:text-red-400 transition mt-1">
                    {{ $ch->host_team_name }}
                  </h2>
                  <p class="text-xs text-slate-500 dark:text-slate-400">
                    Narahubung: <strong>{{ $ch->contact_wa }}</strong> • Jersey: <strong>Merah / Netral</strong>
                  </p>
                </div>
              </div>

              <!-- Biaya Patungan Lapangan 50:50 -->
              <div class="bg-slate-50 dark:bg-slate-850 sm:bg-transparent p-3.5 sm:p-0 rounded-2xl w-full sm:w-auto text-left sm:text-right border sm:border-0 border-slate-100 dark:border-slate-800 shrink-0">
                <span class="text-[10px] text-slate-400 block font-medium">{{ $bebanLabel }}</span>
                <span class="text-xl font-black {{ $isHost ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-900 dark:text-white' }}">
                  Rp {{ number_format($bebanTamu, 0, ',', '.') }}
                </span>
                <span class="text-[10px] text-slate-400 block">Total Biaya Lapangan: Rp {{ number_format($totalSewa, 0, ',', '.') }} (Bagi 50:50)</span>
              </div>
            </div>

            <!-- DETAIL JADWAL YANG SUDAH DISEWA OLEH HOST -->
            <div class="mt-5 p-4 rounded-2xl bg-slate-50 dark:bg-slate-850 border border-slate-200/80 dark:border-slate-800 grid sm:grid-cols-3 gap-3 text-xs">
              <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase block">Venue & Lapangan</span>
                <div class="font-extrabold text-slate-800 dark:text-slate-200 flex items-center gap-1.5 mt-0.5 truncate">
                  <i data-lucide="map-pin" class="w-3.5 h-3.5 text-red-500 shrink-0"></i> 
                  <span class="truncate">{{ $ch->booking->tenant->name ?? 'Champion Arena' }} ({{ $ch->booking->court->name ?? 'Lap. 1' }})</span>
                </div>
              </div>

              <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase block">Hari & Tanggal</span>
                <div class="font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5 mt-0.5">
                  <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i> 
                  <span>{{ \Carbon\Carbon::parse($ch->booking->booking_date ?? now())->isoFormat('dddd, D MMM Y') }}</span>
                </div>
              </div>

              <div>
                <span class="text-[10px] text-slate-400 font-bold uppercase block">Slot Jam Tanding</span>
                <div class="font-black text-red-600 dark:text-red-400 flex items-center gap-1.5 mt-0.5">
                  <i data-lucide="clock" class="w-3.5 h-3.5 text-red-600 shrink-0"></i> 
                  <span>{{ substr($ch->booking->start_time ?? '20:00', 0, 5) }} - {{ substr($ch->booking->end_time ?? '21:00', 0, 5) }} WIB</span>
                </div>
              </div>
            </div>

            <!-- CATATAN KHUSUS & TOMBOL ACTION -->
            <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
              <p class="text-xs text-slate-500 dark:text-slate-400 italic">
                {{ $quote }}
              </p>

              @if($isMatched)
                <div class="flex items-center gap-2 w-full sm:w-auto flex-wrap">
                  <span class="px-3 py-1.5 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 text-xs font-bold border border-purple-200 dark:border-purple-800 flex items-center gap-1.5">
                    <i data-lucide="check-circle" class="w-3.5 h-3.5 text-purple-500"></i>
                    <span>Tantangan Diterima: <strong>{{ $ch->challenger_team_name ?? 'Tim Lawan' }}</strong></span>
                  </span>
                  @if($isHost)
                    <a href="{{ route('player.dashboard') }}" class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold flex items-center gap-1.5 transition">
                      <i data-lucide="ticket" class="w-3.5 h-3.5"></i>
                      <span>Lihat Tiket</span>
                    </a>
                  @endif
                </div>
              @elseif($isHost)
                <div class="flex items-center gap-2 w-full sm:w-auto flex-wrap justify-end">
                  <span class="px-3.5 py-2 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 text-xs font-bold border border-amber-500/25 flex items-center gap-1.5">
                    <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-500 animate-pulse"></i>
                    <span>Tantangan Anda (Menunggu Tim Lawan)</span>
                  </span>
                  <form action="{{ route('community.sparring.cancel', $ch->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan posting tantangan sparring ini?')">
                    @csrf
                    <button type="submit" class="px-3 py-2 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 dark:bg-slate-800 dark:hover:bg-rose-950/40 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold transition flex items-center gap-1 cursor-pointer" title="Batalkan tantangan">
                      <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                      <span>Batalkan</span>
                    </button>
                  </form>
                </div>
              @else
                <button onclick="openChallengeModal({{ $ch->id }}, '{{ addslashes($ch->host_team_name) }}', '{{ addslashes($ch->booking->tenant->name ?? 'Venue') }}', '{{ substr($ch->booking->start_time ?? '20:00', 0, 5) }} - {{ substr($ch->booking->end_time ?? '21:00', 0, 5) }} WIB', 'Rp {{ number_format($bebanTamu, 0, ',', '.') }}')" 
                        class="w-full sm:w-auto px-5 py-2.5 bg-red-600 hover:bg-red-700 active:scale-[0.99] text-white font-extrabold text-xs rounded-xl shadow-md shadow-red-600/30 transition flex items-center justify-center gap-2 cursor-pointer shrink-0">
                  <i data-lucide="swords" class="w-4 h-4"></i>
                  <span>Terima Tantangan (Bayar 50%)</span>
                </button>
              @endif
            </div>

          </div>
        @empty
          <div class="text-center py-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-3">
            <i data-lucide="swords" class="w-12 h-12 text-slate-400 mx-auto"></i>
            <h3 class="text-base font-bold text-slate-700 dark:text-slate-300">Belum ada tantangan sparring terbuka</h3>
            <p class="text-xs text-slate-500">Jadilah tim pertama yang memposting tantangan dan undang klub lain bertanding!</p>
            <button onclick="openPostChallengeModal()" class="mt-2 px-5 py-2.5 rounded-xl bg-red-600 text-white font-bold text-xs inline-flex items-center gap-1.5 cursor-pointer">
              <i data-lucide="plus-circle" class="w-4 h-4"></i>
              <span>Posting Tantangan Sekarang</span>
            </button>
          </div>
        @endforelse

        <div id="sparingEmptyState" class="hidden text-center py-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-3 p-6">
          <i data-lucide="search-x" class="w-12 h-12 text-slate-400 mx-auto"></i>
          <h3 class="text-base font-bold text-slate-700 dark:text-slate-300">Tidak ada tantangan sparring yang sesuai</h3>
          <p class="text-xs text-slate-500">Coba ubah kata kunci pencarian tim / venue atau pilih level kategori lain.</p>
          <button type="button" onclick="resetAllSparingFilters()" class="inline-block mt-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl cursor-pointer transition">
            Reset Filter
          </button>
        </div>

      </div>

      <!-- ================= SIDEBAR INFO & RULES (4 COLS) ================= -->
      <aside class="lg:col-span-4 space-y-6">

        <!-- Standar Peraturan Sparing -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
          <div class="flex items-center gap-2 text-slate-900 dark:text-white font-black text-sm">
            <i data-lucide="scale" class="w-4 h-4 text-red-600"></i>
            <span>Kode Etik Sparing LapanganKita</span>
          </div>
          <ul class="space-y-3.5 text-xs text-slate-600 dark:text-slate-400">
            <li class="flex items-start gap-2.5">
              <i data-lucide="check" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
              <span><strong>Respek & Fair Play:</strong> Menjunjung tinggi sportifitas tanpa kekerasan fisik atau provokasi emosi.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <i data-lucide="check" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
              <span><strong>Bagi Biaya 50:50:</strong> Tim penantang membayar porsi sewa lapangan via pembayaran platform secara transparan.</span>
            </li>
            <li class="flex items-start gap-2.5">
              <i data-lucide="check" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5"></i>
              <span><strong>Tiba Tepat Waktu:</strong> Hadir 15 menit sebelum peluit kick-off dibunyikan di resepsionis venue.</span>
            </li>
          </ul>
        </div>

        <!-- Leaderboard Mini Komunitas -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-6 sm:p-7 rounded-3xl shadow-lg border border-slate-700 space-y-4">
          <div class="flex items-center justify-between pb-1">
            <h3 class="font-bold text-sm text-white flex items-center gap-2">
              <i data-lucide="award" class="w-4 h-4 text-amber-400"></i> 
              <span>Top Tim Bulan Ini</span>
            </h3>
            <span class="text-[10px] text-amber-400 font-bold bg-amber-400/10 border border-amber-400/20 px-2 py-0.5 rounded">
              Leaderboard
            </span>
          </div>
          <div class="space-y-3 text-xs">
            @forelse($leaderboard ?? [] as $index => $team)
              <div class="flex items-center justify-between {{ !$loop->last ? 'pb-2.5 border-b border-slate-700/60' : '' }}">
                <div class="flex items-center gap-2.5 min-w-0">
                  <span class="font-black text-sm shrink-0 {{ $index === 0 ? 'text-amber-400' : ($index === 1 ? 'text-slate-300' : ($index === 2 ? 'text-amber-600' : 'text-slate-500')) }}">
                    #{{ $index + 1 }}
                  </span>
                  <span class="font-bold text-white truncate" title="{{ $team['name'] }}">{{ $team['name'] }}</span>
                </div>
                <div class="shrink-0 text-right pl-2">
                  <span class="text-slate-300 font-semibold">{{ $team['matches'] }} Match</span>
                  <span class="text-slate-500 text-[11px]">({{ $team['tier'] }})</span>
                </div>
              </div>
            @empty
              <div class="text-center py-4 text-slate-400 text-xs space-y-1">
                <i data-lucide="shield" class="w-6 h-6 mx-auto text-slate-600"></i>
                <p>Belum ada pertandingan selesai bulan ini.</p>
              </div>
            @endforelse
          </div>
        </div>



      </aside>

    </div>
  </main>

  <!-- ========================================================================= -->
  <!-- MODAL ACTION: FORM INPUT TIM PENANTANG (SESUAI DESAIN USER)               -->
  <!-- ========================================================================= -->
  <div id="modal-sparing-challenge" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 max-w-lg w-full rounded-3xl shadow-2xl overflow-hidden border border-slate-100 dark:border-slate-800 max-h-[90vh] overflow-y-auto">
      
      <!-- Modal Header -->
      <div class="p-6 bg-slate-900 text-white flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center shadow">
            <i data-lucide="swords" class="w-5 h-5"></i>
          </div>
          <div>
            <h3 class="text-base font-black">Ajukan Tantangan Sparing</h3>
            <p class="text-xs text-slate-300">Lawan Tim: <strong id="modal-opponent-name" class="text-red-400">Garuda Muda FC</strong></p>
          </div>
        </div>
        <button onclick="closeChallengeModal()" class="text-slate-400 hover:text-white cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <!-- Match Info Pill -->
      <div class="p-4 bg-slate-50 dark:bg-slate-850 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between text-xs">
        <div>
          <span class="text-slate-400 block text-[10px]">Lokasi & Jam:</span>
          <span id="modal-match-details" class="font-bold text-slate-800 dark:text-slate-200">Champion Arena • 20:00 - 21:00 WIB</span>
        </div>
        <div class="text-right">
          <span class="text-slate-400 block text-[10px]">Biaya Patungan Tim Kamu (50%):</span>
          <span id="modal-match-cost" class="font-black text-emerald-600 dark:text-emerald-400 text-sm">Rp 87.500</span>
        </div>
      </div>

      <!-- Challenge Form -->
      <form action="{{ route('community.sparring.accept') }}" method="POST" class="p-6 space-y-4">
        @csrf
        <input type="hidden" name="challenge_id" id="modal-challenge-id" value="">

        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Tim Penantang</label>
          <input type="text" name="challenger_team" id="modal-challenger-team" placeholder="Contoh: Thunder FC" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:outline-none" value="Elang Merah FC">
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Kapten / Narahubung</label>
            <input type="text" placeholder="Nama Kapten" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:outline-none" value="Rian Pratama">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor WhatsApp Kapten</label>
            <input type="tel" placeholder="08xxxxxxxxxx" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:outline-none" value="08123456789">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Warna Jersey / Rompi</label>
            <input type="text" placeholder="Contoh: Biru Putih" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:outline-none" value="Kuning Hitam">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Perkiraan Jumlah Pemain</label>
            <input type="number" value="10" min="5" max="15" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:outline-none">
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Pesan untuk Kapten Lawan (Opsional)</label>
          <textarea rows="2" placeholder="Tuliskan catatan ramah atau perkenalan tim..." class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:outline-none">Siap tanding santai dan sportif!</textarea>
        </div>

        <div class="pt-2">
          <!-- MIDTRANS GATEWAY ACTION BUTTON -->
          <button type="button" onclick="paySparringMidtrans()" 
                  class="w-full py-3.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white font-black text-xs sm:text-sm rounded-xl shadow-lg shadow-red-600/25 transition flex items-center justify-center gap-2 cursor-pointer">
            <i data-lucide="shield-check" class="w-4 h-4"></i>
            <span>Bayar Patungan 50% via Midtrans Gateway</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
          </button>
        </div>
      </form>

    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MODAL POSTING TANTANGAN BARU                                              -->
  <!-- ========================================================================= -->
  <div id="modal-post-challenge" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 max-w-lg w-full rounded-3xl shadow-2xl overflow-hidden border border-slate-100 dark:border-slate-800 max-h-[90vh] overflow-y-auto">
      <div class="p-6 bg-slate-900 text-white flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-red-600 text-white flex items-center justify-center shadow">
            <i data-lucide="plus" class="w-5 h-5"></i>
          </div>
          <div>
            <h3 class="text-base font-black">Posting Tantangan Baru</h3>
            <p class="text-xs text-slate-300">Pasang jadwal tanding tim futsal Anda di lobby</p>
          </div>
        </div>
        <button onclick="closePostChallengeModal()" class="text-slate-400 hover:text-white cursor-pointer">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form action="{{ route('community.sparring.store') }}" method="POST" class="p-6 space-y-4">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Tim Anda</label>
            <input type="text" name="team_name" id="post-team-name" required placeholder="Contoh: Garuda Muda FC" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:outline-none">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Level Permainan</label>
            <select name="skill_tier" id="post-skill-tier" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:outline-none">
              <option value="amatir">Casual / Fun (Non-Emosi)</option>
              <option value="menengah" selected>Semi-Pro / Menengah</option>
              <option value="semipro">Kompetitif / Liga</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Pilih Venue &amp; Lapangan</label>
            <select name="court_id" id="post-court-id" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:outline-none">
              @foreach($venues as $v)
                <optgroup label="{{ $v->name }} ({{ $v->city }})">
                  @foreach($v->courts as $c)
                    <option value="{{ $c->id }}">{{ $v->name }} - {{ $c->name }} ({{ $c->surface_type }})</option>
                  @endforeach
                </optgroup>
              @endforeach
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Ketentuan Biaya Lapangan</label>
            <div class="px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white flex items-center justify-between">
              <span>Bagi Rata (50 : 50)</span>
              <span class="text-[10px] font-black px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Fair Play</span>
            </div>
            <input type="hidden" name="split_rule" value="50_50">
          </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Main</label>
            <input type="date" name="booking_date" id="post-booking-date" required value="{{ date('Y-m-d', strtotime('+2 days')) }}" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:outline-none">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Jam Tanding</label>
            <select name="time_slot" id="post-time-slot" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:outline-none">
              <optgroup label="Slot Malam (Paling Populer)">
                <option value="19:00 - 20:00">19:00 - 20:00 WIB (1 Jam)</option>
                <option value="19:00 - 21:00">19:00 - 21:00 WIB (2 Jam)</option>
                <option value="20:00 - 21:00" selected>20:00 - 21:00 WIB (1 Jam)</option>
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

        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor WhatsApp Kapten</label>
          <input type="tel" name="contact_wa" id="post-contact-wa" required placeholder="08xxxxxxxxxx" value="081234567890" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:outline-none">
        </div>

        <!-- Note Pembagian 50:50 -->
        <div class="p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-xs space-y-1">
          <div class="flex items-center gap-1.5 font-bold text-emerald-700 dark:text-emerald-400">
            <i data-lucide="scale" class="w-4 h-4 text-emerald-500"></i>
            <span>Sistem Patungan 50 : 50 Fair Play</span>
          </div>
          <p class="text-[11px] text-slate-600 dark:text-slate-300 leading-relaxed">
            Biaya sewa lapangan dibagi rata <strong>50% Tim Anda</strong> dan <strong>50% Tim Lawan</strong>. Anda melunasi porsi 50% Anda sekarang via Midtrans Gateway. Porsi 50% sisanya akan dilunasi tim lawan saat menerima tantangan di lobby.
          </p>
        </div>

        <div class="pt-2">
          <!-- MIDTRANS GATEWAY ACTION BUTTON UNTUK HOST -->
          <button type="button" onclick="paySparringHostMidtrans()" 
                  class="w-full py-3.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white font-black text-xs sm:text-sm rounded-xl shadow-lg shadow-red-600/25 transition flex items-center justify-center gap-2 cursor-pointer">
            <i data-lucide="shield-check" class="w-4 h-4"></i>
            <span>Bayar Patungan 50% Host via Midtrans Gateway</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
          </button>
        </div>
      </form>
    </div>
  </div>

</div>

<!-- ========================================================================= -->
<!-- JAVASCRIPT LOGIC FILTER, SEARCH, & MODAL                                  -->
<!-- ========================================================================= -->
<script>
  let activeSparingFilter = 'all';

  function setSparingFilter(filter) {
    activeSparingFilter = filter;

    document.querySelectorAll('.sparing-chip').forEach(btn => {
      btn.className = 'sparing-chip px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs whitespace-nowrap cursor-pointer transition';
    });

    const activeBtn = document.getElementById('sparing-filter-' + filter);
    if (activeBtn) {
      activeBtn.className = 'sparing-chip px-3.5 py-1.5 rounded-xl bg-red-600 text-white font-bold text-xs whitespace-nowrap shadow-sm cursor-pointer transition';
    }

    applySparingFilters();
  }

  function handleSparingSearch(query) {
    applySparingFilters();
  }

  function resetAllSparingFilters() {
    const input = document.getElementById('sparingSearchInput');
    if (input) input.value = '';
    setSparingFilter('all');
  }

  function applySparingFilters() {
    const query = (document.getElementById('sparingSearchInput')?.value || '').toLowerCase().trim();
    const cards = document.querySelectorAll('.sparing-card');
    let visibleCount = 0;

    cards.forEach(card => {
      const team = card.getAttribute('data-team') || '';
      const venue = card.getAttribute('data-venue') || '';
      const tier = card.getAttribute('data-tier') || '';

      const textMatches = !query || team.includes(query) || venue.includes(query);
      const tierMatches = (activeSparingFilter === 'all') || (tier === activeSparingFilter);

      if (textMatches && tierMatches) {
        card.style.display = 'block';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    const emptyBox = document.getElementById('sparingEmptyState');
    if (emptyBox) {
      if (visibleCount === 0 && cards.length > 0) {
        emptyBox.classList.remove('hidden');
      } else {
        emptyBox.classList.add('hidden');
      }
    }
  }

  function openChallengeModal(id, opponent, venue, time, cost) {
    document.getElementById('modal-challenge-id').value = id;
    document.getElementById('modal-opponent-name').innerText = opponent;
    document.getElementById('modal-match-details').innerText = `${venue} • ${time}`;
    document.getElementById('modal-match-cost').innerText = cost;

    const modal = document.getElementById('modal-sparing-challenge');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    if (window.lucide) lucide.createIcons();
  }

  function closeChallengeModal() {
    const modal = document.getElementById('modal-sparing-challenge');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  function openPostChallengeModal() {
    const modal = document.getElementById('modal-post-challenge');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    if (window.lucide) lucide.createIcons();
  }

  function closePostChallengeModal() {
    const modal = document.getElementById('modal-post-challenge');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  function paySparringMidtrans() {
    const challengeId = document.getElementById('modal-challenge-id').value;
    const teamName = document.getElementById('modal-challenger-team')?.value || 'Tim Penantang FC';

    closeChallengeModal();

    initiateMidtransPayment('sparring', {
      challenge_id: challengeId,
      challenger_team: teamName,
    }, {
      onError: function() {
        const modal = document.getElementById('modal-sparing-challenge');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    });
  }

  function paySparringHostMidtrans() {
    const teamName = document.getElementById('post-team-name')?.value?.trim();
    const courtId = document.getElementById('post-court-id')?.value;
    const skillTier = document.getElementById('post-skill-tier')?.value;
    const bookingDate = document.getElementById('post-booking-date')?.value;
    const timeSlot = document.getElementById('post-time-slot')?.value?.trim();
    const contactWa = document.getElementById('post-contact-wa')?.value?.trim();

    if (!teamName || !courtId || !bookingDate || !timeSlot || !contactWa) {
      alert('Harap lengkapi semua kolom data formulir tantangan sparring.');
      return;
    }

    closePostChallengeModal();

    initiateMidtransPayment('sparring_host', {
      team_name: teamName,
      court_id: courtId,
      skill_tier: skillTier,
      booking_date: bookingDate,
      time_slot: timeSlot,
      contact_wa: contactWa,
    }, {
      onError: function() {
        const modal = document.getElementById('modal-post-challenge');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }
    });
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeChallengeModal();
      closePostChallengeModal();
    }
  });
</script>

@include('partials.midtrans-modal')
@endsection
