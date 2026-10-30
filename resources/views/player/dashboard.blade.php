@extends('layouts.app')

@section('title', 'Dashboard Pemain - LapanganKita')
@section('meta_description', 'Kelola jadwal reservasi lapangan, tiket masuk QRIS, dan mabar open slot kamu.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
  
  <!-- Real Player Profile Card -->
  <div class="p-6 sm:p-7 rounded-3xl bg-slate-900 border border-slate-200 dark:border-slate-800 text-white shadow-xl relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6">
    <!-- Ambient glow -->
    <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="flex items-center gap-4 sm:gap-5 relative z-10">
      <!-- Profile Picture Avatar -->
      <div class="relative shrink-0">
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-tr from-emerald-500 via-teal-400 to-emerald-600 text-slate-950 font-black text-2xl sm:text-3xl flex items-center justify-center shadow-lg shadow-emerald-500/25 border-2 border-white/20">
          <span>{{ strtoupper(substr($user->name, 0, 2)) }}</span>
        </div>
        <span class="w-4 h-4 rounded-full bg-emerald-400 border-2 border-slate-900 absolute -bottom-1 -right-1 shadow-sm" title="Online Aktif"></span>
      </div>

      <!-- Profile Info Details -->
      <div class="space-y-1.5">
        <div class="flex items-center gap-2.5 flex-wrap">
          <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">{{ $user->name }}</h1>
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center gap-1">
            <i data-lucide="badge-check" class="w-3.5 h-3.5 text-emerald-400"></i>
            <span>Akun Terverifikasi</span>
          </span>
          <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/10 text-slate-300 border border-white/10 font-mono">
            ID: #PL-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
          </span>
        </div>
        <div class="flex items-center gap-2 sm:gap-3 text-xs text-slate-400 flex-wrap">
          <span class="flex items-center gap-1 text-slate-300">
            <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400"></i>
            <span>{{ $user->email }}</span>
          </span>
          <span class="text-slate-600 hidden sm:inline">•</span>
          <span class="flex items-center gap-1 text-slate-300">
            <i data-lucide="phone" class="w-3.5 h-3.5 text-slate-400"></i>
            <span>{{ $user->phone ?? '0812-3456-7890' }}</span>
          </span>
          <span class="text-slate-600 hidden sm:inline">•</span>
          <span class="px-2 py-0.5 rounded-md bg-emerald-500/20 text-[10px] font-semibold text-emerald-300 border border-emerald-500/30">
            Pemain Komunitas
          </span>
        </div>
      </div>
    </div>

    <div class="flex items-center gap-2.5 relative z-10 shrink-0">
      <button onclick="document.getElementById('edit-profile-modal').classList.remove('hidden')" class="px-4 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm transition flex items-center gap-2 border border-white/10">
        <i data-lucide="edit-3" class="w-4 h-4 text-emerald-400"></i>
        <span>Ubah Profil</span>
      </button>
      <a href="{{ route('venues.index') }}" class="px-5 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs sm:text-sm transition shadow-lg shadow-emerald-500/25 flex items-center gap-2 cursor-pointer">
        <i data-lucide="plus-circle" class="w-4 h-4 text-slate-950"></i>
        <span>Pesan Lapangan Baru</span>
      </a>
    </div>
  </div>

  <!-- Metric Quick Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
    <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-2">
      <div class="flex items-center justify-between text-slate-500">
        <span class="text-xs font-bold">Total Reservasi</span>
        <i data-lucide="calendar-check" class="w-4 h-4 text-emerald-500"></i>
      </div>
      <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ $bookings->count() }} <span class="text-xs text-slate-400 font-normal">Sesi</span></div>
    </div>

    <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-2">
      <div class="flex items-center justify-between text-slate-500">
        <span class="text-xs font-bold">Mabar Diikuti</span>
        <i data-lucide="users" class="w-4 h-4 text-teal-400"></i>
      </div>
      <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">{{ $openSlots->count() }} <span class="text-xs text-slate-400 font-normal">Room</span></div>
    </div>

    <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-2">
      <div class="flex items-center justify-between text-slate-500">
        <span class="text-xs font-bold">Status Akun</span>
        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i>
      </div>
      <div class="text-lg font-black text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
        <span>Member Aktif</span>
      </div>
    </div>
  </div>

  <!-- Main Section: Daftar Reservasi Lapangan Saya -->
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-md space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-slate-800">
      <div>
        <h2 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="ticket" class="w-5 h-5 text-emerald-500"></i>
          <span>Tiket & Jadwal Booking Saya</span>
        </h2>
        <p class="text-xs text-slate-500 mt-0.5">Tunjukkan kode tiket ini saat tiba di resepsionis venue futsal.</p>
      </div>
    </div>

    <div class="space-y-4">
      @forelse($bookings as $b)
        <div class="p-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-850 hover:border-emerald-500/50 transition duration-300 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-2">
            <div class="flex items-center gap-2 flex-wrap">
              @if($b->booking_type === 'sparring')
                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-red-500/15 text-red-600 dark:text-red-400 border border-red-500/30">
                  SPARING 50:50
                </span>
                @if($b->status === 'paid')
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-purple-500/15 text-purple-600 dark:text-purple-400 border border-purple-500/30">
                     LUNAS • MATCH TERKUNCI
                  </span>
                @elseif($b->status === 'pending')
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30 animate-pulse">
                     PORSI 50% LUNAS • MENUNGGU LAWAN
                  </span>
                @else
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20">
                     {{ strtoupper($b->status) }}
                  </span>
                @endif
              @elseif($b->booking_type === 'open_slot')
                <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30">
                  OPEN SLOT MABAR
                </span>
                @php
                  $osRoom = \App\Models\OpenSlotRoom::where('booking_id', $b->id)->first();
                @endphp
                @if($b->status === 'paid' || ($osRoom && $osRoom->status === 'full'))
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                     LUNAS • KUOTA LENGKAP & JADWAL TERKUNCI
                  </span>
                @elseif($b->status === 'cancelled' || ($osRoom && $osRoom->status === 'cancelled'))
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-slate-500/15 text-slate-600 dark:text-slate-400 border border-slate-500/30">
                     ROOM DIBATALKAN
                  </span>
                @else
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30 animate-pulse">
                     SLOT HOST LUNAS • MENUNGGU PEMAIN ({{ $osRoom ? $osRoom->current_players . '/' . $osRoom->target_players : 'TERISI' }})
                  </span>
                @endif
              @else
                @if($b->status === 'paid')
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                     LUNAS (PAID)
                  </span>
                @elseif($b->status === 'pending')
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30 animate-pulse">
                     MENUNGGU PEMBAYARAN
                  </span>
                @else
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20">
                     {{ strtoupper($b->status) }}
                  </span>
                @endif
              @endif

              <span class="text-xs font-mono font-bold text-slate-500">#{{ $b->booking_code }}</span>
            </div>
            
            <h3 class="text-base font-black text-slate-900 dark:text-white">
              {{ $b->tenant->name ?? 'Champion Futsal Arena' }} — <span class="text-emerald-600 dark:text-emerald-400">{{ $b->court->name ?? 'Lapangan A' }}</span>
              @if($b->booking_type === 'sparring' && $b->sparringChallenge)
                <span class="text-xs font-bold text-slate-500 block sm:inline sm:ml-2">
                  (Tim Anda: <strong>{{ $b->sparringChallenge->host_team_name }}</strong> vs <strong>{{ $b->sparringChallenge->challenger_team_name ?? 'Menunggu Lawan' }}</strong>)
                </span>
              @elseif($b->booking_type === 'open_slot')
                @php
                  $osRoom = \App\Models\OpenSlotRoom::where('booking_id', $b->id)->first();
                @endphp
                @if($osRoom)
                  <span class="text-xs font-bold text-amber-600 dark:text-amber-400 block sm:inline sm:ml-2">
                    (Mabar: <strong>{{ $osRoom->title }}</strong>)
                  </span>
                @endif
              @endif
            </h3>

            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
              <span class="flex items-center gap-1.5">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                <span>{{ \Carbon\Carbon::parse($b->booking_date)->format('d F Y') }}</span>
              </span>
              <span class="flex items-center gap-1.5">
                <i data-lucide="clock" class="w-3.5 h-3.5 text-slate-400"></i>
                <span>{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }} WIB ({{ $b->duration_hours }} Jam)</span>
              </span>
              <span class="flex items-center gap-1.5">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                <span>{{ $b->tenant->city ?? 'Bandung' }}</span>
              </span>
            </div>
          </div>

          <div class="flex items-center gap-4 pt-3 md:pt-0 border-t md:border-t-0 border-slate-200 dark:border-slate-800 justify-between md:justify-end">
            <div class="text-left md:text-right">
              @if($b->booking_type === 'sparring')
                <span class="text-[10px] text-slate-400 block font-medium">Porsi Bayar Kamu (50% Lunas)</span>
                <span class="text-base font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format(round($b->total_amount / 2), 0, ',', '.') }}</span>
                <span class="text-[10px] text-slate-400 block">Total Lapangan: Rp {{ number_format($b->total_amount, 0, ',', '.') }} (Bagi 50:50)</span>
              @elseif($b->booking_type === 'open_slot')
                @php
                  $osRoom = \App\Models\OpenSlotRoom::where('booking_id', $b->id)->first();
                  $hostMember = $osRoom ? \App\Models\OpenSlotMember::where('room_id', $osRoom->id)->where('user_id', $user->id)->first() : null;
                  $hostPaid = $hostMember ? ($hostMember->slots_count * ($osRoom->price_per_person ?? 25000)) : ($osRoom->price_per_person ?? 25000);
                @endphp
                <span class="text-[10px] text-slate-400 block font-medium">Porsi Host (1x Bayar Lunas)</span>
                <span class="text-base font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($hostPaid, 0, ',', '.') }}</span>
                <span class="text-[10px] text-slate-400 block">Total Lapangan: Rp {{ number_format($b->total_amount, 0, ',', '.') }} (Patungan)</span>
              @else
                <span class="text-[10px] text-slate-400 block font-medium">Total Biaya</span>
                <span class="text-base font-black text-slate-900 dark:text-white">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</span>
              @endif
            </div>

            @if($b->status === 'paid')
              <a href="{{ route('booking.ticket.pdf', $b->booking_code) }}" target="_blank" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs rounded-xl transition flex items-center gap-1.5 shadow-sm cursor-pointer">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                <span>Cetak E-Tiket PDF</span>
              </a>
            @elseif($b->booking_type === 'sparring')
              <div class="flex items-center gap-2">
                <a href="{{ route('community.sparring') }}" class="px-3.5 py-2 bg-red-600 hover:bg-red-500 text-white font-extrabold text-xs rounded-xl transition flex items-center gap-1.5 shadow-sm cursor-pointer" title="Cek status tantangan di Lobby Sparing">
                  <i data-lucide="swords" class="w-3.5 h-3.5"></i>
                  <span>Lobby Sparing</span>
                </a>
                @if($b->sparringChallenge && $b->sparringChallenge->status === 'open')
                  <form action="{{ route('community.sparring.cancel', $b->sparringChallenge->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan tantangan sparring ini?')">
                    @csrf
                    <button type="submit" class="px-3 py-2 bg-slate-200 dark:bg-slate-800 hover:bg-rose-100 hover:text-rose-600 dark:hover:bg-rose-950/40 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition cursor-pointer" title="Batalkan tantangan">
                      <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    </button>
                  </form>
                @endif
              </div>
            @elseif($b->booking_type === 'open_slot')
              @php
                $osRoom = \App\Models\OpenSlotRoom::where('booking_id', $b->id)->first();
                $hostMember = $osRoom ? \App\Models\OpenSlotMember::where('room_id', $osRoom->id)->where('user_id', $user->id)->first() : null;
              @endphp
              <div class="flex items-center gap-2">
                <a href="{{ route('community.open-slot') }}" class="px-3.5 py-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs rounded-xl transition flex items-center gap-1.5 shadow-sm cursor-pointer" title="Pantau peserta di Lobby Mabar">
                  <i data-lucide="users" class="w-3.5 h-3.5"></i>
                  <span>Lobby Mabar</span>
                </a>
                @if($hostMember)
                  <a href="{{ route('community.open-slot.invoice.pdf', $hostMember->id) }}" target="_blank" class="px-3 py-2 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl transition flex items-center gap-1 cursor-pointer" title="Cetak bukti bayar patungan host">
                    <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                    <span>Invoice Host</span>
                  </a>
                @endif
              </div>
            @else
              <div class="flex items-center gap-2">
                <form action="{{ route('player.booking.check-status', $b->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="px-3 py-2 bg-amber-500/15 hover:bg-amber-500/25 text-amber-600 dark:text-amber-400 font-extrabold text-xs rounded-xl transition flex items-center gap-1.5 border border-amber-500/30 cursor-pointer" title="Cek apakah pembayaran sudah masuk di Midtrans">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                    <span>Cek Status</span>
                  </button>
                </form>
                <a href="{{ route('booking.payment', ['court_id' => $b->court_id, 'date' => $b->booking_date, 'start_time' => $b->start_time, 'end_time' => $b->end_time, 'duration_hours' => $b->duration_hours]) }}" class="px-3.5 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs rounded-xl transition flex items-center gap-1.5 shadow-sm cursor-pointer">
                  <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                  <span>Bayar</span>
                </a>
                <form action="{{ route('player.booking.cancel', $b->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan booking ini?')">
                  @csrf
                  <button type="submit" class="px-2.5 py-2 bg-slate-100 hover:bg-rose-50 text-slate-500 hover:text-rose-600 dark:bg-slate-800 dark:hover:bg-rose-950/40 dark:text-slate-400 dark:hover:text-rose-400 font-bold text-xs rounded-xl transition border border-slate-200 dark:border-slate-700 cursor-pointer" title="Batalkan booking">
                    <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                  </button>
                </form>
              </div>
            @endif
          </div>
        </div>
      @empty
        <div class="text-center py-12 space-y-3">
          <i data-lucide="calendar-x" class="w-12 h-12 text-slate-400 mx-auto"></i>
          <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum ada riwayat booking</h4>
          <p class="text-xs text-slate-500">Kamu belum memesan jadwal lapangan futsal saat ini.</p>
          <a href="{{ route('venues.index') }}" class="inline-block mt-2 px-4 py-2 bg-emerald-500 text-slate-950 font-bold text-xs rounded-xl">Jelajahi Lapangan</a>
        </div>
      @endforelse
    </div>
  </div>

  <!-- Section 2: Room Mabar & Invoice Patungan Saya -->
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-md space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-slate-800">
      <div>
        <h2 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="receipt" class="w-5 h-5 text-emerald-500"></i>
          <span>Room Mabar & Invoice Patungan Saya</span>
        </h2>
        <p class="text-xs text-slate-500 mt-0.5">Daftar room open slot yang kamu ikuti beserta bukti pembayaran patungan resmi.</p>
      </div>

      <a href="{{ route('community.open-slot') }}" class="text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1">
        <span>Lobby Open Slot</span>
        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
      </a>
    </div>

    <div class="space-y-4">
      @forelse($openSlots as $slot)
        <div class="p-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-850 hover:border-emerald-500/50 transition duration-300 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-2">            <div class="flex items-center gap-2">
              @if($slot->status === 'cancelled' || $slot->room->status === 'cancelled')
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-slate-500/10 text-slate-600 dark:text-slate-400 border border-slate-500/20">
                  ROOM DIBATALKAN
                </span>
              @else
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                  LUNAS (PAID)
                </span>
              @endif
              <span class="text-xs font-mono font-bold text-slate-500">
                #{{ $slot->payment->transaction_code ?? ('LK-INV-SLOT-' . $slot->id) }}
              </span>
              <span class="text-[10px] text-slate-400">QRIS Instan</span>
            </div>
            
            <h3 class="text-base font-black text-slate-900 dark:text-white">
              {{ $slot->room->title }}
            </h3>

            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
              <span class="flex items-center gap-1.5 font-semibold text-slate-700 dark:text-slate-300">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-500"></i>
                <span>{{ $slot->room->booking->tenant->name ?? 'Champion Futsal' }} ({{ $slot->room->booking->tenant->city ?? 'Bandung' }})</span>
              </span>
              <span class="flex items-center gap-1.5">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                <span>{{ \Carbon\Carbon::parse($slot->room->booking->booking_date ?? now())->format('d F Y') }} • {{ substr($slot->room->booking->start_time ?? '19:00', 0, 5) }} - {{ substr($slot->room->booking->end_time ?? '21:00', 0, 5) }} WIB</span>
              </span>
              <span class="flex items-center gap-1.5">
                <i data-lucide="users" class="w-3.5 h-3.5 text-slate-400"></i>
                <span>Slot: {{ $slot->room->current_players }} / {{ $slot->room->target_players }} Pemain</span>
                <span class="px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-black border border-emerald-500/20">
                  {{ $slot->slots_count ?? 1 }} Orang Anda
                </span>
              </span>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-3 pt-3 md:pt-0 border-t md:border-t-0 border-slate-200 dark:border-slate-800 justify-between md:justify-end">
            <div class="text-left md:text-right mr-2">
              <span class="text-[10px] text-slate-400 block font-medium">Biaya Patungan ({{ $slot->slots_count ?? 1 }} Orang)</span>
              <span class="text-base font-black text-emerald-600 dark:text-emerald-400">
                Rp {{ number_format($slot->payment->amount ?? ($slot->room->price_per_person * ($slot->slots_count ?? 1)), 0, ',', '.') }}
              </span>
            </div>

            @if($slot->room->status === 'open' && $slot->room->host_user_id === auth()->id())
              <form action="{{ route('community.open-slot.cancel', $slot->room->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menutup room mabar ini?');">
                @csrf
                <button type="submit" class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5 cursor-pointer">
                  <i data-lucide="x-circle" class="w-3.5 h-3.5"></i>
                  <span>Tutup Room</span>
                </button>
              </form>
            @endif

            @if($slot->status === 'paid' || optional($slot->payment)->status === 'settlement')
              <a href="{{ route('community.open-slot.invoice.pdf', $slot->id) }}" target="_blank" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white dark:text-slate-950 font-black text-xs rounded-xl transition flex items-center gap-1.5 shadow-sm cursor-pointer">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                <span>Cetak Invoice</span>
              </a>
            @else
              <span class="px-3.5 py-2 bg-amber-500/10 text-amber-600 dark:text-amber-400 font-bold text-xs rounded-xl flex items-center gap-1.5 border border-amber-500/20" title="Pembayaran slot masih tertunda">
                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                <span>Invoice Ditunda</span>
              </span>
            @endif
          </div>
        </div>
      @empty
        <div class="text-center py-10 space-y-3">
          <i data-lucide="users" class="w-12 h-12 text-slate-400 mx-auto"></i>
          <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum ada mabar yang kamu ikuti</h4>
          <p class="text-xs text-slate-500">Cari room mabar santai di lobby dan bayar patungan tanpa repot cari 10 pemain sendiri.</p>
          <a href="{{ route('community.open-slot') }}" class="inline-block mt-2 px-4 py-2 bg-emerald-500 text-slate-950 font-bold text-xs rounded-xl">Cari Room Mabar</a>
        </div>
      @endforelse
    </div>
  </div>

  <!-- Section 3: Match Sparing & Invoice Patungan (50 : 50) Saya -->
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 shadow-md space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-slate-800">
      <div>
        <h2 class="text-lg font-black text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="swords" class="w-5 h-5 text-red-500"></i>
          <span>Match Sparing &amp; Invoice Patungan 50:50 Saya</span>
        </h2>
        <p class="text-xs text-slate-500 mt-0.5">Daftar pertandingan sparring yang kamu ikuti (sebagai Penyelenggara Host maupun Tim Penantang) beserta invoice resmi 50%.</p>
      </div>

      <a href="{{ route('community.sparring') }}" class="text-xs font-bold text-red-600 dark:text-red-400 hover:underline flex items-center gap-1">
        <span>Lobby Sparing</span>
        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
      </a>
    </div>

    <div class="space-y-4">
      @forelse($sparringMatches as $m)
        @php
          $isUserHost = $m->booking && $m->booking->user_id === auth()->id();
          $totalAmount = $m->booking ? $m->booking->total_amount : 182500;
          $userShare = round($totalAmount / 2);

          // Cari payment milik user (host atau guest)
          $userPayment = null;
          if ($m->booking) {
            if ($isUserHost) {
              $userPayment = $m->booking->payments->first(function($p) {
                return isset($p->payload['type']) && $p->payload['type'] === 'sparring_host';
              });
            } else {
              $userPayment = $m->booking->payments->first(function($p) {
                return isset($p->payload['type']) && $p->payload['type'] === 'sparring_guest';
              });
            }
          }
          $isPaid = $userPayment && $userPayment->status === 'settlement';
        @endphp

        <div class="p-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-850 hover:border-red-500/50 transition duration-300 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="space-y-2">
            <div class="flex items-center gap-2 flex-wrap">
              @if($isUserHost)
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-red-500/15 text-red-600 dark:text-red-400 border border-red-500/30">
                  Penyelenggara (Host)
                </span>
              @else
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-purple-500/15 text-purple-600 dark:text-purple-400 border border-purple-500/30">
                  Tim Penantang (Tamu)
                </span>
              @endif

              @if($m->status === 'matched')
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                  LUNAS 100% (MATCH TERKUNCI)
                </span>
              @elseif($m->status === 'open')
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                  PORSI 50% LUNAS • MENUNGGU LAWAN
                </span>
              @elseif($m->status === 'pending_payment')
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30 animate-pulse">
                  MENUNGGU PEMBAYARAN 50% HOST
                </span>
              @else
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-slate-500/10 text-slate-600 dark:text-slate-400 border border-slate-500/20">
                  {{ strtoupper($m->status) }}
                </span>
              @endif

              <span class="text-xs font-mono font-bold text-slate-500">
                #{{ $userPayment->transaction_code ?? ($m->booking->booking_code ?? 'LK-SPAR-' . $m->id) }}
              </span>
            </div>
            
            <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
              <span class="text-red-600 dark:text-red-400">{{ $m->host_team_name }}</span>
              <span class="text-xs text-slate-400 font-bold">VS</span>
              <span class="{{ $m->challenger_team_name ? 'text-purple-600 dark:text-purple-400' : 'text-slate-400 italic font-medium text-sm' }}">
                {{ $m->challenger_team_name ?? '(Menunggu Lawan di Lobby)' }}
              </span>
            </h3>

            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
              <span class="flex items-center gap-1.5 font-semibold text-slate-700 dark:text-slate-300">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-red-500"></i>
                <span>{{ $m->booking->tenant->name ?? 'Champion Futsal' }} ({{ $m->booking->court->name ?? 'Lap. 1' }})</span>
              </span>
              <span class="flex items-center gap-1.5">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                <span>{{ \Carbon\Carbon::parse($m->booking->booking_date ?? now())->format('d F Y') }} • {{ substr($m->booking->start_time ?? '20:00', 0, 5) }} - {{ substr($m->booking->end_time ?? '21:00', 0, 5) }} WIB</span>
              </span>
              <span class="flex items-center gap-1.5">
                <i data-lucide="scale" class="w-3.5 h-3.5 text-slate-400"></i>
                <span>Split Bill 50:50 Fair Play</span>
              </span>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-3 pt-3 md:pt-0 border-t md:border-t-0 border-slate-200 dark:border-slate-800 justify-between md:justify-end">
            <div class="text-left md:text-right mr-2">
              <span class="text-[10px] text-slate-400 block font-medium">Porsi Bayar Kamu (50%)</span>
              <span class="text-base font-black {{ $isPaid ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                Rp {{ number_format($userShare, 0, ',', '.') }}
              </span>
              <span class="text-[10px] text-slate-400 block">Total Lapangan: Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
            </div>

            @if($m->status === 'pending_payment' && $isUserHost)
              <button type="button" onclick="payPendingSparring({{ $m->id }})" class="px-4 py-2.5 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 text-white font-black text-xs rounded-xl shadow-md transition flex items-center gap-1.5 cursor-pointer">
                <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                <span>Bayar 50% Sekarang</span>
              </button>
            @endif

            @if($isPaid && $userPayment)
              <a href="{{ route('community.sparring.invoice.pdf', $userPayment->id) }}" target="_blank" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white dark:text-slate-950 font-black text-xs rounded-xl transition flex items-center gap-1.5 shadow-sm cursor-pointer" title="Cetak struk resmi bukti bayar patungan 50%">
                <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                <span>Cetak Invoice 50%</span>
              </a>
            @endif

            @if($m->status === 'matched' && $m->booking)
              <a href="{{ route('booking.ticket.pdf', $m->booking->booking_code) }}" target="_blank" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs rounded-xl transition flex items-center gap-1.5 shadow-sm cursor-pointer" title="Cetak E-Tiket pertandingan resmi">
                <i data-lucide="ticket" class="w-3.5 h-3.5"></i>
                <span>E-Tiket Match</span>
              </a>
            @endif

            @if($m->status === 'open' && $isUserHost)
              <form action="{{ route('community.sparring.cancel', $m->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan tantangan sparring ini?');">
                @csrf
                <button type="submit" class="px-3.5 py-2.5 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 dark:bg-slate-800 dark:hover:bg-rose-950/40 text-slate-600 dark:text-slate-300 border border-slate-300 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-1.5 cursor-pointer" title="Batalkan posting tantangan">
                  <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                  <span>Batalkan</span>
                </button>
              </form>
            @endif
          </div>
        </div>
      @empty
        <div class="text-center py-10 space-y-3">
          <i data-lucide="swords" class="w-12 h-12 text-slate-400 mx-auto"></i>
          <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">Belum ada match sparring</h4>
          <p class="text-xs text-slate-500">Pasang tantangan tim futsal Anda di lobby sparring atau tantang klub lain dengan sistem patungan 50:50.</p>
          <a href="{{ route('community.sparring') }}" class="inline-block mt-2 px-4 py-2 bg-red-600 text-white font-bold text-xs rounded-xl">Jelajahi Lobby Sparing</a>
        </div>
      @endforelse
    </div>
  </div>

</div>

<script>
  function payPendingSparring(challengeId) {
    initiateMidtransPayment('sparring_pending_host', {
      challenge_id: challengeId
    });
  }
</script>

<!-- Modal Ubah Data Pemain -->
<div id="edit-profile-modal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl relative">
    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
      <div class="flex items-center gap-2.5">
        <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center font-bold">
          <i data-lucide="user-check" class="w-5 h-5"></i>
        </div>
        <div>
          <h3 class="text-base font-bold text-slate-900 dark:text-white">Ubah Data Pemain</h3>
          <p class="text-xs text-slate-500">Perbarui nama dan kontak pemain kamu</p>
        </div>
      </div>
      <button type="button" onclick="document.getElementById('edit-profile-modal').classList.add('hidden')" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-white rounded-xl">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <form action="{{ route('player.profile.update') }}" method="POST" class="mt-5 space-y-4">
      @csrf
      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap / Nama Panggung Lapangan</label>
        <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nomor WhatsApp / HP</label>
        <input type="text" name="phone" value="{{ $user->phone }}" placeholder="Contoh: 081234567890" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
      </div>

      <div class="pt-3 flex items-center justify-end gap-2.5">
        <button type="button" onclick="document.getElementById('edit-profile-modal').classList.add('hidden')" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
          Batal
        </button>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs shadow-md shadow-emerald-500/20 transition">
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>

@include('partials.midtrans-modal')
@endsection
