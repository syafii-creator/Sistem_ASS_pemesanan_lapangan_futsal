@extends('layouts.app')

@section('title', 'LapanganKita - Platform Reservasi Lapangan Futsal & Ekosistem Olahraga')
@section('meta_description', 'Sewa lapangan futsal online real-time anti bentrok, cari teman mabar di Open Slot, dan tantang tim lawan di Lobby Sparing.')

@section('content')
<!-- Ambient Background Lighting -->
<div class="relative overflow-hidden">
  <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[400px] bg-emerald-500/10 rounded-full blur-[140px] pointer-events-none"></div>
  <div class="absolute top-10 right-10 w-96 h-96 bg-teal-500/10 rounded-full blur-[120px] pointer-events-none"></div>

  <!-- HERO SECTION -->
  <section id="beranda" class="relative pt-12 pb-20 md:pt-20 md:pb-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

      <!-- Top Announcement Pill -->
      <div class="flex justify-center mb-6">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white dark:bg-slate-900/90 border border-emerald-500/30 text-xs font-semibold text-emerald-700 dark:text-emerald-300 shadow-sm">
          <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
          <span>Platform Futsal Modern Terintegrasi 2026</span>
          <i data-lucide="arrow-right" class="w-3.5 h-3.5 text-emerald-500 dark:text-emerald-400"></i>
        </div>
      </div>

      <!-- Main Headline -->
      <div class="text-center max-w-4xl mx-auto space-y-6">
        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight leading-[1.15] text-slate-900 dark:text-white">
          Main Futsal Tanpa Ribet, <br>
          <span class="gradient-text">Pesan Lapangan Real-Time</span>
        </h1>
        <p class="text-base sm:text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto font-normal leading-relaxed">
          Cek jadwal kosong anti-bentrok, cari teman mabar lewat <strong class="text-slate-900 dark:text-white font-bold">Open Slot Patungan</strong>, atau ajak tanding di <strong class="text-slate-900 dark:text-white font-bold">Lobby Sparing</strong>.
        </p>

        <!-- CTA Action Buttons -->
        <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
          <a href="{{ route('venues.index') }}" class="px-7 py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-sm sm:text-base shadow-xl shadow-emerald-500/25 transition transform hover:-translate-y-0.5 flex items-center gap-2.5">
            <i data-lucide="calendar-check" class="w-5 h-5"></i>
            <span>Cari &amp; Booking Lapangan</span>
          </a>
          <a href="{{ route('community.open-slot') }}" class="px-7 py-3.5 rounded-2xl bg-white dark:bg-slate-900/90 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-800 dark:text-white font-bold text-sm sm:text-base border border-slate-200 dark:border-slate-700/80 transition shadow-sm flex items-center gap-2.5">
            <i data-lucide="users" class="w-5 h-5 text-emerald-500 dark:text-emerald-400"></i>
            <span>Ikut Mabar Open Slot</span>
          </a>
        </div>
      </div>

      <!-- QUICK SEARCH BAR WIDGET -->
      <div class="mt-14 max-w-4xl mx-auto">
        <form action="{{ route('venues.index') }}" method="GET" class="p-3 sm:p-4 rounded-3xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 shadow-2xl backdrop-blur-xl grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
          <!-- City -->
          <div class="sm:col-span-4 px-3 py-2 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200/60 dark:border-slate-700/50">
            <label class="text-[11px] font-bold text-slate-500 dark:text-slate-400 block mb-0.5 flex items-center gap-1.5">
              <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-500"></i>
              <span>Pilih Kota</span>
            </label>
            <select name="city" class="w-full bg-transparent text-sm font-bold text-slate-900 dark:text-white focus:outline-none cursor-pointer">
              <option value="Semua">Semua Kota</option>
              @php
                $landingCities = \App\Models\Tenant::where('status', 'active')->pluck('city')->unique()->toArray();
              @endphp
              @foreach($landingCities as $lc)
                <option value="{{ $lc }}">{{ $lc }}</option>
              @endforeach
            </select>
          </div>

          <!-- Keyword -->
          <div class="sm:col-span-5 px-3 py-2 bg-slate-50 dark:bg-slate-800/60 rounded-2xl border border-slate-200/60 dark:border-slate-700/50">
            <label class="text-[11px] font-bold text-slate-500 dark:text-slate-400 block mb-0.5 flex items-center gap-1.5">
              <i data-lucide="search" class="w-3.5 h-3.5 text-emerald-500"></i>
              <span>Nama Lapangan / Lokasi</span>
            </label>
            <input type="text" name="q" placeholder="Contoh: Champion, Sintetis, Fatmawati..." class="w-full bg-transparent text-sm font-semibold text-slate-900 dark:text-white focus:outline-none placeholder:text-slate-400">
          </div>

          <!-- Submit Button -->
          <div class="sm:col-span-3">
            <button type="submit" class="w-full h-14 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-extrabold text-sm rounded-2xl shadow-lg shadow-emerald-500/25 transition flex items-center justify-center gap-2">
              <i data-lucide="search" class="w-4 h-4"></i>
              <span>Temukan</span>
            </button>
          </div>
        </form>
      </div>

    </div>
  </section>

  <!-- VENUE PILIHAN -->
  <section class="py-16 bg-slate-100/50 dark:bg-slate-900/40 border-y border-slate-200 dark:border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
        <div>
          <span class="text-xs font-bold tracking-widest text-emerald-600 dark:text-emerald-400 uppercase">Venue Resmi Terdaftar</span>
          <h2 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white mt-1">Pilihan Terbaik Minggu Ini</h2>
          <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Jelajahi venue unggulan mitra resmi LapanganKita.</p>
        </div>
        <a href="{{ route('venues.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-emerald-600 dark:text-emerald-400 hover:underline shrink-0">
          <span>Lihat Semua Venue</span>
          <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
        @forelse($venues as $venue)
          <div class="reveal bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden hover:border-emerald-500 dark:hover:border-slate-700 transition duration-300 group shadow-md flex flex-col justify-between">
            <div>
              <div class="relative h-56 overflow-hidden">
                <img src="{{ $venue->banner_image ?? 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $venue->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                <div class="absolute top-4 left-4">
                  <span class="bg-emerald-500 text-slate-950 font-black text-xs px-3 py-1 rounded-full shadow-md">&#10003; Terverifikasi</span>
                </div>
                <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between text-xs font-bold text-white">
                  <span class="flex items-center gap-1.5 bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-lg text-amber-300">
                    <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-300"></i>
                    <span>4.9 (120+ Ulasan)</span>
                  </span>
                  <span class="bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-lg text-slate-200 flex items-center gap-1">
                    <i data-lucide="map-pin" class="w-3 h-3 text-emerald-400"></i>
                    <span>{{ $venue->city }}</span>
                  </span>
                </div>
              </div>
              <div class="p-6 space-y-3">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white group-hover:text-emerald-500 transition">{{ $venue->name }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">{{ $venue->address }}</p>
                <div class="flex flex-wrap gap-1.5 pt-2">
                  @if(is_array($venue->facilities))
                    @foreach(array_slice($venue->facilities, 0, 3) as $fac)
                      <span class="text-[11px] bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2 py-0.5 rounded-md font-medium">{{ $fac }}</span>
                    @endforeach
                  @else
                    <span class="text-[11px] bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2 py-0.5 rounded-md font-medium">Rumput Sintetis</span>
                    <span class="text-[11px] bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2 py-0.5 rounded-md font-medium">Parkir Luas</span>
                  @endif
                </div>
              </div>
            </div>
            <div class="p-6 pt-0">
              <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                  <span class="text-[11px] text-slate-500 dark:text-slate-400 block font-medium">Mulai dari</span>
                  <span class="text-lg font-black text-emerald-600 dark:text-emerald-400">
                    Rp {{ number_format($venue->courts->min('price_regular') ?? 120000, 0, ',', '.') }}<span class="text-xs text-slate-400 font-normal">/jam</span>
                  </span>
                </div>
                <a href="{{ route('venues.show', $venue->slug) }}" class="px-4 py-2.5 bg-slate-900 text-white dark:bg-slate-800 hover:bg-emerald-500 hover:text-slate-950 dark:hover:bg-emerald-500 dark:hover:text-slate-950 font-extrabold text-xs rounded-xl transition flex items-center gap-1.5">
                  <span>Pilih Jadwal</span>
                  <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
              </div>
            </div>
          </div>
        @empty
          <div class="col-span-2 text-center py-12 text-slate-500">
            <p>Belum ada venue terdaftar.</p>
          </div>
        @endforelse
      </div>
    </div>
  </section>

  <!-- 3 PILAR KEUNGGULAN -->
  <section id="keunggulan" class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
        <span class="text-xs font-bold tracking-widest text-emerald-600 dark:text-emerald-400 uppercase">Fitur Andalan</span>
        <h2 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white">Ekosistem Lengkap Pecinta Futsal</h2>
        <p class="text-slate-600 dark:text-slate-400 text-sm">Kemudahan reservasi dan komunitas mabar dalam satu aplikasi.</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="reveal p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-emerald-500/50 transition duration-300 space-y-4 shadow-sm">
          <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
            <i data-lucide="calendar" class="w-6 h-6"></i>
          </div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white">Jadwal Matrix Real-Time</h3>
          <p class="text-slate-600 dark:text-slate-400 text-xs leading-relaxed">
            Pilih tanggal dan jam dengan visual interaktif: Hijau (Tersedia), Kuning (Dipilih), dan Merah (Terisi). 100% bebas bentrok.
          </p>
          <a href="{{ route('venues.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-500 hover:underline">
            <span>Lihat Lapangan</span>
            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
          </a>
        </div>

        <div class="reveal p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-teal-500/50 transition duration-300 space-y-4 shadow-sm">
          <div class="w-12 h-12 rounded-2xl bg-teal-500/10 text-teal-400 flex items-center justify-center">
            <i data-lucide="users" class="w-6 h-6"></i>
          </div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white">Mabar Open Slot (Patungan)</h3>
          <p class="text-slate-600 dark:text-slate-400 text-xs leading-relaxed">
            Kurang orang buat sewa lapangan? Gabung ke room mabar terbuka dan bayar patungan adil secara praktis tanpa ribet.
          </p>
          <a href="{{ route('community.open-slot') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-400 hover:underline">
            <span>Buka Lobby Mabar</span>
            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
          </a>
        </div>

        <div class="reveal p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-purple-500/50 transition duration-300 space-y-4 shadow-sm">
          <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-400 flex items-center justify-center">
            <i data-lucide="swords" class="w-6 h-6"></i>
          </div>
          <h3 class="text-lg font-bold text-slate-900 dark:text-white">Lobby Tantangan Sparing</h3>
          <p class="text-slate-600 dark:text-slate-400 text-xs leading-relaxed">
            Cari lawan tanding sepadan berdasarkan tier (Amatir, Menengah, Semi-Pro) dengan opsi bagi rata 50:50 atau kalah bayar.
          </p>
          <a href="{{ route('community.sparring') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-400 hover:underline">
            <span>Tantang Tim Sekarang</span>
            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- PAKET SAAS -->
  <section id="paket-saas" class="py-24 bg-slate-100/60 dark:bg-slate-900/40 border-t border-slate-200 dark:border-slate-800 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-bold tracking-wide">
          <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i>
          <span>Paket Langganan Pengelola Venue</span>
        </div>
        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tight">Investasi Terbaik untuk Bisnis Futsal Anda</h2>
        <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base leading-relaxed">
          Mulai dari Rp 50.000. Nikmati aplikasi reservasi modern, pembayaran otomatis QRIS &amp; Virtual Account, manajemen kasir terintegrasi, dan laporan analitik bisnis.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
        @forelse($packages as $pkg)
          <div class="reveal rounded-3xl p-8 flex flex-col justify-between transition-all duration-300 relative {{ $pkg->is_popular ? 'bg-slate-900 text-white ring-2 ring-emerald-500 shadow-2xl scale-105 z-20 dark:bg-slate-850' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white shadow-md' }}">
            @if($pkg->is_popular)
              <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-gradient-to-r from-emerald-500 to-teal-400 text-slate-950 font-black text-xs shadow-md uppercase tracking-wider flex items-center gap-1.5">
                <i data-lucide="flame" class="w-3.5 h-3.5"></i>
                <span>{{ $pkg->badge_text ?? 'Paling Laris' }}</span>
              </div>
            @elseif($pkg->badge_text)
              <div class="absolute -top-3.5 right-6 px-3 py-0.5 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-[10px] border border-slate-300 dark:border-slate-700">
                {{ $pkg->badge_text }}
              </div>
            @endif

            <div class="space-y-6">
              <div>
                <h3 class="text-xl font-extrabold {{ $pkg->is_popular ? 'text-white' : 'text-slate-900 dark:text-white' }}">{{ $pkg->name }}</h3>
                <p class="text-xs {{ $pkg->is_popular ? 'text-slate-300' : 'text-slate-500 dark:text-slate-400' }} mt-1">
                  Maksimal {{ $pkg->max_courts }} Unit Lapangan
                </p>
              </div>
              <div class="pt-4 border-t {{ $pkg->is_popular ? 'border-slate-800' : 'border-slate-100 dark:border-slate-800' }}">
                <span class="text-xs {{ $pkg->is_popular ? 'text-slate-400' : 'text-slate-500 dark:text-slate-400' }} block font-medium">Biaya Investasi</span>
                <div class="flex items-baseline gap-1 mt-1">
                  <span class="text-3xl sm:text-4xl font-black text-emerald-400">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                  <span class="text-xs text-slate-400 font-normal">/ {{ $pkg->billing_cycle }}</span>
                </div>
              </div>
              <div class="space-y-3 pt-2">
                <span class="text-xs font-bold {{ $pkg->is_popular ? 'text-slate-200' : 'text-slate-700 dark:text-slate-300' }} block">Fitur yang didapatkan:</span>
                <ul class="space-y-2.5 text-xs {{ $pkg->is_popular ? 'text-slate-300' : 'text-slate-600 dark:text-slate-400' }}">
                  @php $feats = is_array($pkg->features) ? $pkg->features : json_decode($pkg->features, true); @endphp
                  @if($feats)
                    @foreach($feats as $f)
                      <li class="flex items-center gap-2.5">
                        <div class="w-4 h-4 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0 mt-0.5"><i data-lucide="check" class="w-3 h-3 text-emerald-400"></i></div>
                        <span>{{ $f }}</span>
                      </li>
                    @endforeach
                  @endif
                  <li class="flex items-center gap-2.5">
                    <div class="w-4 h-4 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0 mt-0.5"><i data-lucide="check" class="w-3 h-3 text-emerald-400"></i></div>
                    <span>Dukungan WhatsApp &amp; Cloud Backup</span>
                  </li>
                </ul>
              </div>
            </div>

            <div class="pt-8 mt-6 border-t {{ $pkg->is_popular ? 'border-slate-800' : 'border-slate-100 dark:border-slate-800' }}">
              <a href="{{ route('auth.portal') }}?mode=register&role=tenant" class="w-full py-3.5 rounded-2xl font-black text-xs sm:text-sm text-center transition flex items-center justify-center gap-2 shadow-lg {{ $pkg->is_popular ? 'bg-emerald-500 hover:bg-emerald-400 text-slate-950 shadow-emerald-500/25' : 'bg-slate-100 hover:bg-emerald-500 hover:text-slate-950 text-slate-800 dark:bg-slate-800 dark:text-white dark:hover:bg-emerald-500 dark:hover:text-slate-950' }}">
                <span>Pilih Paket Ini</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
              </a>
            </div>
          </div>
        @empty
          <div class="col-span-3 text-center py-12 text-slate-500">
            <p>Data paket SaaS belum dimuat.</p>
          </div>
        @endforelse
      </div>
    </div>
  </section>

</div>

<style>
  .reveal {
    opacity: 0;
    transform: translateY(28px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }
  .reveal.visible {
    opacity: 1;
    transform: translateY(0);
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const revEls = document.querySelectorAll('.reveal');
  const ro = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('visible'); ro.unobserve(e.target); }
    });
  }, { threshold: 0.1 });
  revEls.forEach(el => ro.observe(el));
});
</script>

@endsection
