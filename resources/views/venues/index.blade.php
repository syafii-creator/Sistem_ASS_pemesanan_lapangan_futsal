@extends('layouts.app')

@section('title', 'Katalog Venue Futsal - Cari & Booking Lapangan')
@section('meta_description', 'Temukan venue futsal terbaik di kotamu dengan fasilitas lengkap dan jadwal real-time.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  
  <!-- BREADCRUMB NAVIGATION -->
  <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium mb-4">
    <a href="{{ route('home') }}" class="hover:text-emerald-500 transition flex items-center gap-1">
      <i data-lucide="home" class="w-3.5 h-3.5"></i>
      <span>Beranda</span>
    </a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
    <span class="text-slate-900 dark:text-white font-bold">Katalog Lapangan Futsal</span>
  </nav>

  <!-- Header Title & Quick Search -->
  <div class="mb-8 space-y-4">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
      <div>
        <span class="text-xs font-bold tracking-widest text-emerald-600 dark:text-emerald-400 uppercase">Eksplorasi Venue</span>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white mt-1">Katalog Lapangan Futsal</h1>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Ditemukan {{ $venues->count() }} venue siap dipesan online real-time</p>
      </div>

      <!-- Search Input Form -->
      <form action="{{ route('venues.index') }}" method="GET" class="flex items-center gap-2 max-w-md w-full">
        @if(request('city') && request('city') !== 'Semua')
          <input type="hidden" name="city" value="{{ request('city') }}">
        @endif
        @if(request('surface'))
          @foreach((array)request('surface') as $s)
            <input type="hidden" name="surface[]" value="{{ $s }}">
          @endforeach
        @endif
        @if(request('facility'))
          @foreach((array)request('facility') as $f)
            <input type="hidden" name="facility[]" value="{{ $f }}">
          @endforeach
        @endif
        <div class="relative flex-1">
          <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5"></i>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari venue, jalan, atau area..." class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl pl-10 pr-4 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>
        <button type="submit" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl transition">
          Cari
        </button>
      </form>
    </div>

    <!-- Quick City Filter Pills -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
      @php
        $activeCities = \App\Models\Tenant::where('status', 'active')->pluck('city')->unique()->toArray();
        $cities = array_merge(['Semua'], $activeCities);
        $currentCity = request('city', 'Semua');
      @endphp
      @foreach($cities as $c)
        @php
          $queryParam = request()->query();
          if ($c === 'Semua') {
            unset($queryParam['city']);
          } else {
            $queryParam['city'] = $c;
          }
        @endphp
        <a href="{{ route('venues.index', $queryParam) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold shrink-0 transition {{ $currentCity === $c ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/20' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-800' }}">
          {{ $c }}
        </a>
      @endforeach
    </div>
  </div>

  <!-- Layout Grid: Sidebar Filters & Venue Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    
    <!-- Sidebar Filters Form -->
    <div class="lg:col-span-1 space-y-6">
      <form action="{{ route('venues.index') }}" method="GET" id="sidebarFilterForm" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 space-y-5">
        @if(request('q'))
          <input type="hidden" name="q" value="{{ request('q') }}">
        @endif
        @if(request('city') && request('city') !== 'Semua')
          <input type="hidden" name="city" value="{{ request('city') }}">
        @endif

        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
          <span class="font-bold text-xs text-slate-900 dark:text-white flex items-center gap-2">
            <i data-lucide="filter" class="w-4 h-4 text-emerald-500"></i>
            <span>Filter Tambahan</span>
          </span>
          <a href="{{ route('venues.index') }}" class="text-[11px] text-emerald-500 hover:underline">Reset</a>
        </div>

        @php
          $selectedSurfaces = (array) request('surface', []);
          $selectedFacilities = (array) request('facility', []);
        @endphp

        <!-- Surface Type -->
        <div>
          <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-2.5">Jenis Lapangan</label>
          <div class="space-y-2 text-xs text-slate-600 dark:text-slate-400">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="surface[]" value="Sintetis" onchange="this.form.submit()" {{ in_array('Sintetis', $selectedSurfaces) ? 'checked' : '' }} class="rounded text-emerald-500 focus:ring-emerald-500">
              <span>Rumput Sintetis Pro</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="surface[]" value="Vinyl" onchange="this.form.submit()" {{ in_array('Vinyl', $selectedSurfaces) ? 'checked' : '' }} class="rounded text-emerald-500 focus:ring-emerald-500">
              <span>Vinyl Taraflex</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="surface[]" value="Interlock" onchange="this.form.submit()" {{ in_array('Interlock', $selectedSurfaces) ? 'checked' : '' }} class="rounded text-emerald-500 focus:ring-emerald-500">
              <span>Interlock Modular</span>
            </label>
          </div>
        </div>

        <!-- Facilities -->
        <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
          <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-2.5">Fasilitas Venue</label>
          <div class="space-y-2 text-xs text-slate-600 dark:text-slate-400">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="facility[]" value="Parkir" onchange="this.form.submit()" {{ in_array('Parkir', $selectedFacilities) ? 'checked' : '' }} class="rounded text-emerald-500 focus:ring-emerald-500">
              <span>Parkir Luas Mobil/Motor</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="facility[]" value="Musholla" onchange="this.form.submit()" {{ in_array('Musholla', $selectedFacilities) ? 'checked' : '' }} class="rounded text-emerald-500 focus:ring-emerald-500">
              <span>Musholla Ber-AC</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="facility[]" value="Kantin" onchange="this.form.submit()" {{ in_array('Kantin', $selectedFacilities) ? 'checked' : '' }} class="rounded text-emerald-500 focus:ring-emerald-500">
              <span>Kantin & Cafe</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="facility[]" value="Skor" onchange="this.form.submit()" {{ in_array('Skor', $selectedFacilities) ? 'checked' : '' }} class="rounded text-emerald-500 focus:ring-emerald-500">
              <span>Papan Skor Digital</span>
            </label>
          </div>
        </div>

        <noscript>
          <button type="submit" class="w-full py-2 bg-emerald-500 text-slate-950 font-bold text-xs rounded-xl">Terapkan Filter</button>
        </noscript>
      </form>
    </div>

    <!-- Venue Cards Grid -->
    <div class="lg:col-span-3">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($venues as $venue)
          <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden hover:border-emerald-500 dark:hover:border-slate-700 transition duration-300 group shadow-md flex flex-col justify-between">
            <div>
              <div class="relative h-52 overflow-hidden">
                <img src="{{ $venue->banner_image ?? 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $venue->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                <div class="absolute top-4 left-4 flex gap-2">
                  <span class="bg-emerald-500 text-slate-950 font-black text-xs px-3 py-1 rounded-full shadow-md">
                     Terverifikasi
                  </span>
                </div>
                <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between text-xs font-bold text-white">
                  <span class="flex items-center gap-1.5 bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-lg text-amber-300">
                    <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-300"></i>
                    <span>4.9 (100+ Review)</span>
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
          <div class="col-span-2 text-center py-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-3">
            <i data-lucide="search-x" class="w-12 h-12 text-slate-400 mx-auto"></i>
            <h3 class="text-base font-bold text-slate-700 dark:text-slate-300">Tidak ada venue yang sesuai pencarian</h3>
            <p class="text-xs text-slate-500">Coba ganti filter kota atau kata kunci pencarian kamu.</p>
            <a href="{{ route('venues.index') }}" class="inline-block mt-2 px-4 py-2 bg-emerald-500 text-slate-950 font-bold text-xs rounded-xl">Lihat Semua Venue</a>
          </div>
        @endforelse
      </div>
    </div>

  </div>

</div>
@endsection
