@extends('layouts.app')

@section('title', $venue->name . ' - Jadwal & Booking Lapangan')
@section('meta_description', 'Pesan jadwal lapangan ' . $venue->name . ' real-time anti bentrok dengan pilihan lapangan sintetis & vinyl.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
  
  <!-- BREADCRUMB NAVIGATION -->
  <nav class="flex items-center gap-2 text-xs text-slate-500 font-medium">
    <a href="{{ route('home') }}" class="hover:text-emerald-500 transition flex items-center gap-1">
      <i data-lucide="home" class="w-3.5 h-3.5"></i>
      <span>Beranda</span>
    </a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
    <a href="{{ route('venues.index') }}" class="hover:text-emerald-500 transition">Katalog Venue</a>
    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
    <span class="text-slate-900 dark:text-white font-bold truncate max-w-[200px] sm:max-w-none">{{ $venue->name }}</span>
  </nav>

  <!-- Venue Header Hero Banner -->
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-lg">
    <div class="relative h-64 sm:h-80 w-full overflow-hidden">
      <img src="{{ $venue->banner_image ?? 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $venue->name }}" class="w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
      
      <div class="absolute bottom-6 left-6 right-6 text-white flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
          <div class="flex items-center gap-2 mb-2">
            <span class="bg-emerald-500 text-slate-950 font-black text-xs px-3 py-1 rounded-full"> Mitra Resmi</span>
            <span class="bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-full text-xs font-bold text-amber-300 flex items-center gap-1">
              <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-300"></i>
              <span>4.9 Bintang</span>
            </span>
          </div>
          <h1 class="text-2xl sm:text-4xl font-black text-white">{{ $venue->name }}</h1>
          <p class="text-xs sm:text-sm text-slate-300 flex items-center gap-1.5 mt-1">
            <i data-lucide="map-pin" class="w-4 h-4 text-emerald-400"></i>
            <span>{{ $venue->address }} ({{ $venue->city }})</span>
          </p>
        </div>

        <div class="flex items-center gap-2">
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $venue->phone_wa) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-md text-white font-bold text-xs border border-white/20 transition flex items-center gap-2">
            <i data-lucide="message-circle" class="w-4 h-4 text-emerald-400"></i>
            <span>Chat WhatsApp</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Venue Facilities & Description Bar -->
    <div class="p-6 bg-slate-50 dark:bg-slate-900/60 border-t border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
      <div class="flex flex-wrap items-center gap-2">
        <span class="text-xs font-bold text-slate-500 dark:text-slate-400 mr-2">Fasilitas:</span>
        @if(is_array($venue->facilities))
          @foreach($venue->facilities as $fac)
            <span class="text-xs bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-3 py-1 rounded-lg border border-slate-200 dark:border-slate-700 font-medium"> {{ $fac }}</span>
          @endforeach
        @else
          <span class="text-xs bg-white dark:bg-slate-800 px-3 py-1 rounded-lg"> Parkir Luas</span>
          <span class="text-xs bg-white dark:bg-slate-800 px-3 py-1 rounded-lg"> Musholla AC</span>
          <span class="text-xs bg-white dark:bg-slate-800 px-3 py-1 rounded-lg"> Shower Air Hangat</span>
        @endif
      </div>
    </div>
  </div>

  <!-- Interactive Booking Interface: Courts & Time Matrix -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left 2 Cols: Slot Selector Matrix -->
    <div class="lg:col-span-2 space-y-6">
      
      <!-- Court Selector Tabs -->
      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 space-y-4">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <i data-lucide="layers" class="w-5 h-5 text-emerald-500"></i>
            <span>1. Pilih Unit Lapangan</span>
          </h2>
          <span class="text-xs text-slate-500">Tersedia {{ $venue->courts->count() }} Lapangan</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          @foreach($venue->courts as $index => $court)
            <button onclick="selectCourt('{{ $court->id }}', '{{ $court->name }}', '{{ $court->surface_type }}', {{ $court->price_regular }}, {{ $court->price_peak }})" 
                    id="court-btn-{{ $court->id }}"
                    class="p-4 rounded-2xl border text-left transition {{ $index === 0 ? 'border-emerald-500 bg-emerald-500/10 ring-2 ring-emerald-500' : 'border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-850 hover:border-slate-300' }}">
              <div class="flex items-center justify-between mb-1">
                <span class="font-extrabold text-sm text-slate-900 dark:text-white">{{ $court->name }}</span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300">{{ $court->surface_type }}</span>
              </div>
              <div class="text-xs text-slate-500 dark:text-slate-400">
                Siang: <strong class="text-slate-700 dark:text-slate-200">Rp {{ number_format($court->price_regular, 0, ',', '.') }}</strong> | Malam: <strong class="text-emerald-500">Rp {{ number_format($court->price_peak, 0, ',', '.') }}</strong>
              </div>
            </button>
          @endforeach
        </div>
      </div>

      <!-- Date Selector -->
      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 space-y-4">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="calendar" class="w-5 h-5 text-emerald-500"></i>
          <span>2. Pilih Tanggal Reservasi</span>
        </h2>

        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2.5">
          @php
            $days = [
              ['label' => 'Hari Ini', 'date' => now()->format('d M'), 'full' => now()->toDateString(), 'active' => true],
              ['label' => 'Besok', 'date' => now()->addDay()->format('d M'), 'full' => now()->addDay()->toDateString(), 'active' => false],
              ['label' => now()->addDays(2)->isoFormat('dddd'), 'date' => now()->addDays(2)->format('d M'), 'full' => now()->addDays(2)->toDateString(), 'active' => false],
              ['label' => now()->addDays(3)->isoFormat('dddd'), 'date' => now()->addDays(3)->format('d M'), 'full' => now()->addDays(3)->toDateString(), 'active' => false],
              ['label' => now()->addDays(4)->isoFormat('dddd'), 'date' => now()->addDays(4)->format('d M'), 'full' => now()->addDays(4)->toDateString(), 'active' => false],
            ];
          @endphp
          @foreach($days as $idx => $d)
            <button onclick="selectDate(this, '{{ $d['full'] }}')" class="p-3 rounded-2xl border text-center transition {{ $idx === 0 ? 'border-emerald-500 bg-emerald-500/10 font-bold text-emerald-600 dark:text-emerald-400' : 'border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 text-slate-600 dark:text-slate-400' }}">
              <span class="text-[11px] block">{{ $d['label'] }}</span>
              <span class="text-sm font-extrabold block mt-0.5 text-slate-900 dark:text-white">{{ $d['date'] }}</span>
            </button>
          @endforeach
        </div>
      </div>

      <!-- Time Matrix Slots (08:00 - 23:00) -->
      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
          <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <i data-lucide="clock" class="w-5 h-5 text-emerald-500"></i>
            <span>3. Pilih Jam Main (Jadwal Terkini)</span>
          </h2>
          <!-- Legend -->
          <div class="flex items-center gap-3 text-[11px] font-bold">
            <span class="flex items-center gap-1 text-slate-600 dark:text-slate-400"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Tersedia</span>
            <span class="flex items-center gap-1 text-amber-500"><span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span> Dipilih</span>
            <span class="flex items-center gap-1 text-rose-500"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Terisi</span>
          </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-2" id="slot-matrix-grid">
          @php
            $firstCourt = $venue->courts->first();
            $defaultReg = $firstCourt?->price_regular ?? 120000;
            $defaultPeak = $firstCourt?->price_peak ?? 180000;
            $slots = [
              ['time' => '08:00 - 09:00', 'is_peak' => false, 'status' => 'available'],
              ['time' => '09:00 - 10:00', 'is_peak' => false, 'status' => 'available'],
              ['time' => '10:00 - 11:00', 'is_peak' => false, 'status' => 'booked'],
              ['time' => '11:00 - 12:00', 'is_peak' => false, 'status' => 'available'],
              ['time' => '13:00 - 14:00', 'is_peak' => false, 'status' => 'available'],
              ['time' => '14:00 - 15:00', 'is_peak' => false, 'status' => 'available'],
              ['time' => '15:00 - 16:00', 'is_peak' => false, 'status' => 'booked'],
              ['time' => '16:00 - 17:00', 'is_peak' => true, 'status' => 'available'],
              ['time' => '17:00 - 18:00', 'is_peak' => true, 'status' => 'available'],
              ['time' => '18:00 - 19:00', 'is_peak' => true, 'status' => 'booked'],
              ['time' => '19:00 - 20:00', 'is_peak' => true, 'status' => 'available'],
              ['time' => '20:00 - 21:00', 'is_peak' => true, 'status' => 'available'],
              ['time' => '21:00 - 22:00', 'is_peak' => true, 'status' => 'available'],
              ['time' => '22:00 - 23:00', 'is_peak' => true, 'status' => 'available'],
            ];
          @endphp
          @foreach($slots as $s)
            @php
              $initialPrice = $s['is_peak'] ? $defaultPeak : $defaultReg;
            @endphp
            @if($s['status'] === 'booked')
              <div class="p-3 rounded-2xl bg-slate-100 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800/80 text-center opacity-60 cursor-not-allowed">
                <span class="text-xs font-bold text-slate-400 block">{{ $s['time'] }}</span>
                <span class="text-[10px] text-rose-500 font-bold block mt-1"> Sudah Terisi</span>
              </div>
            @else
              <button type="button" 
                      onclick="toggleSlot(this, '{{ $s['time'] }}')" 
                      data-time="{{ $s['time'] }}"
                      data-peak="{{ $s['is_peak'] ? '1' : '0' }}"
                      data-price="{{ $initialPrice }}"
                      class="slot-btn p-3 rounded-2xl bg-slate-50 dark:bg-slate-850 hover:border-emerald-500 border border-slate-200 dark:border-slate-800 text-center transition cursor-pointer">
                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">{{ $s['time'] }}</span>
                <span class="slot-price-label text-[10px] text-emerald-600 dark:text-emerald-400 font-extrabold block mt-1">Rp {{ number_format($initialPrice, 0, ',', '.') }}</span>
              </button>
            @endif
          @endforeach
        </div>
      </div>

    </div>

    <!-- Right Col: Floating Live Checkout Widget -->
    <div class="lg:col-span-1">
      <div class="sticky top-28 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
        <div class="border-b border-slate-100 dark:border-slate-800 pb-4">
          <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Ringkasan Pemesanan</h3>
          <p class="text-xs text-slate-500 mt-0.5" id="summary-venue-name">{{ $venue->name }}</p>
        </div>

        <div class="space-y-3 text-xs">
          <div class="flex justify-between text-slate-500 dark:text-slate-400">
            <span>Lapangan:</span>
            <span class="font-bold text-slate-800 dark:text-slate-200" id="summary-court-name">{{ $venue->courts->first()->name ?? 'Lapangan A' }}</span>
          </div>
          <div class="flex justify-between text-slate-500 dark:text-slate-400">
            <span>Tanggal:</span>
            <span class="font-bold text-slate-800 dark:text-slate-200" id="summary-date">{{ now()->format('d M Y') }}</span>
          </div>
          <div class="flex justify-between text-slate-500 dark:text-slate-400">
            <span>Slot Dipilih:</span>
            <span class="font-bold text-emerald-500" id="summary-slot-count">0 Jam</span>
          </div>
          <div class="p-2.5 bg-slate-50 dark:bg-slate-850 rounded-xl space-y-1.5 border border-slate-100 dark:border-slate-800 text-[11px]" id="summary-selected-list">
            <span class="text-slate-400 italic">Belum ada jam yang dipilih.</span>
          </div>
        </div>

        <!-- Price Breakdown -->
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-2 text-xs">
          <div class="flex justify-between text-slate-500">
            <span>Subtotal Sewa:</span>
            <span class="font-bold text-slate-800 dark:text-slate-200" id="summary-subtotal">Rp 0</span>
          </div>
          <div class="flex justify-between text-slate-500">
            <span>Biaya Layanan Sistem:</span>
            <span class="font-bold text-slate-800 dark:text-slate-200">Rp 2.500</span>
          </div>
          <div class="flex justify-between items-center pt-3 border-t border-slate-100 dark:border-slate-800">
            <span class="text-xs font-bold text-slate-900 dark:text-white">Total Bayar:</span>
            <span class="text-xl font-black text-emerald-600 dark:text-emerald-400" id="summary-total">Rp 2.500</span>
          </div>
        </div>

        @php
          $isOwner = auth()->check() && (
              $venue->owner_user_id == auth()->id() || 
              (auth()->user()->tenant_id && auth()->user()->tenant_id == $venue->id) ||
              (auth()->user()->role === 'tenant' && auth()->user()->id == $venue->owner_user_id)
          );
        @endphp

        <!-- Buttons CTA -->
        <div class="space-y-2.5">
          @if($isOwner)
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-800 dark:text-amber-300 text-xs space-y-2.5">
              <div class="font-extrabold flex items-center gap-2 text-amber-700 dark:text-amber-400">
                <i data-lucide="shield-alert" class="w-4 h-4 shrink-0 text-amber-500"></i>
                <span>Anda adalah Pemilik Venue Ini</span>
              </div>
              <p class="text-[11px] text-slate-600 dark:text-slate-300 leading-relaxed">
                Akun pemilik tidak dapat menyewa lapangan miliknya sendiri melalui formulir pelanggan. Untuk mengelola atau mengunci jadwal bagi pemain yang pesan di kasir (offline), silakan gunakan menu <strong>Jadwal & Lapangan</strong>.
              </p>
              <a href="{{ route('tenant.courts') }}" class="w-full py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs transition flex items-center justify-center gap-2 shadow mt-1">
                <i data-lucide="calendar" class="w-4 h-4"></i>
                <span>Buka Pengaturan Jadwal Kasir &rarr;</span>
              </a>
            </div>
          @else
            <button type="button" onclick="goToCheckout()" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs sm:text-sm rounded-xl transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 cursor-pointer">
              <i data-lucide="credit-card" class="w-4 h-4"></i>
              <span>Lanjut ke Pembayaran</span>
            </button>

            <a href="{{ route('community.open-slot') }}" class="w-full py-3 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-xs rounded-xl transition flex items-center justify-center gap-2">
              <i data-lucide="users" class="w-4 h-4 text-emerald-500"></i>
              <span>Buka Sebagai Mabar Terbuka (Patungan)</span>
            </a>
          @endif
        </div>

      </div>
    </div>

  </div>

</div>
@endsection

@section('scripts')
<script>
  let selectedSlots = [];
  let selectedCourtId = '{{ $venue->courts->first()->id ?? 1 }}';
  let selectedDate = '{{ now()->toDateString() }}';
  let courtPriceReg = {{ $venue->courts->first()->price_regular ?? 120000 }};
  let courtPricePeak = {{ $venue->courts->first()->price_peak ?? 180000 }};

  const activeBookings = @json($activeBookings ?? []);

  const slotDefinitions = [
    { time: '08:00 - 09:00', isPeak: false },
    { time: '09:00 - 10:00', isPeak: false },
    { time: '10:00 - 11:00', isPeak: false },
    { time: '11:00 - 12:00', isPeak: false },
    { time: '13:00 - 14:00', isPeak: false },
    { time: '14:00 - 15:00', isPeak: false },
    { time: '15:00 - 16:00', isPeak: false },
    { time: '16:00 - 17:00', isPeak: true },
    { time: '17:00 - 18:00', isPeak: true },
    { time: '18:00 - 19:00', isPeak: true },
    { time: '19:00 - 20:00', isPeak: true },
    { time: '20:00 - 21:00', isPeak: true },
    { time: '21:00 - 22:00', isPeak: true },
    { time: '22:00 - 23:00', isPeak: true },
  ];

  function getBookedSlotInfo(courtId, date, timeStr) {
    const parts = timeStr.split(' - ');
    const slotStart = parts[0].trim();
    const slotEnd = parts[1].trim();

    return activeBookings.find(b => {
      if (String(b.court_id) !== String(courtId) || b.date !== date) {
        return false;
      }
      return (b.start_time < slotEnd && b.end_time > slotStart);
    });
  }

  function isSlotBooked(courtId, date, timeStr) {
    return !!getBookedSlotInfo(courtId, date, timeStr);
  }

  function renderSlotMatrix() {
    const grid = document.getElementById('slot-matrix-grid');
    if (!grid) return;

    grid.innerHTML = slotDefinitions.map(s => {
      const bookedBooking = getBookedSlotInfo(selectedCourtId, selectedDate, s.time);
      const price = s.isPeak ? courtPricePeak : courtPriceReg;
      const isSelected = selectedSlots.some(sel => sel.time === s.time);

      if (bookedBooking) {
        let tag = ' Sudah Terisi';
        if (bookedBooking.booking_type === 'open_slot') {
          tag = ' Open Slot Mabar';
        } else if (bookedBooking.booking_type === 'sparring') {
          tag = ' Match Sparing';
        }
        return `
          <div class="p-3 rounded-2xl bg-rose-50/70 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 text-center opacity-85 cursor-not-allowed">
            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 block">${s.time}</span>
            <span class="text-[10px] text-rose-600 dark:text-rose-400 font-extrabold block mt-1">${tag}</span>
          </div>
        `;
      }

      const activeClass = isSelected 
        ? 'bg-amber-400/20 border-2 border-amber-400 text-center font-bold shadow-md' 
        : 'bg-slate-50 dark:bg-slate-850 hover:border-emerald-500 border border-slate-200 dark:border-slate-800 text-center';

      return `
        <button type="button" 
                onclick="toggleSlot(this, '${s.time}')" 
                data-time="${s.time}"
                data-peak="${s.isPeak ? '1' : '0'}"
                data-price="${price}"
                class="slot-btn p-3 rounded-2xl ${activeClass} transition cursor-pointer">
          <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">${s.time}</span>
          <span class="slot-price-label text-[10px] text-emerald-600 dark:text-emerald-400 font-extrabold block mt-1">Rp ${price.toLocaleString('id-ID')}</span>
        </button>
      `;
    }).join('');
  }

  function selectCourt(id, name, surface, priceReg, pricePeak) {
    selectedCourtId = id;
    courtPriceReg = priceReg;
    courtPricePeak = pricePeak;

    document.querySelectorAll('[id^="court-btn-"]').forEach(btn => {
      btn.className = 'p-4 rounded-2xl border text-left transition border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-850 hover:border-slate-300';
    });
    const activeBtn = document.getElementById('court-btn-' + id);
    if (activeBtn) {
      activeBtn.className = 'p-4 rounded-2xl border text-left transition border-emerald-500 bg-emerald-500/10 ring-2 ring-emerald-500';
    }
    document.getElementById('summary-court-name').innerText = name + ' (' + surface + ')';

    selectedSlots = [];
    updateSummary();
    renderSlotMatrix();
  }

  function selectDate(btn, fullDate) {
    selectedDate = fullDate;
    btn.parentElement.querySelectorAll('button').forEach(b => {
      b.className = 'p-3 rounded-2xl border text-center transition border-slate-200 dark:border-slate-800 hover:border-slate-300 text-slate-600 dark:text-slate-400';
    });
    btn.className = 'p-3 rounded-2xl border text-center transition border-emerald-500 bg-emerald-500/10 font-bold text-emerald-600 dark:text-emerald-400';
    document.getElementById('summary-date').innerText = fullDate;

    selectedSlots = [];
    updateSummary();
    renderSlotMatrix();
  }

  function toggleSlot(btn, time) {
    const price = parseInt(btn.dataset.price, 10) || courtPriceReg;
    const idx = selectedSlots.findIndex(s => s.time === time);
    if (idx >= 0) {
      selectedSlots.splice(idx, 1);
      btn.className = 'slot-btn p-3 rounded-2xl bg-slate-50 dark:bg-slate-850 hover:border-emerald-500 border border-slate-200 dark:border-slate-800 text-center transition cursor-pointer';
    } else {
      selectedSlots.push({ time, price });
      btn.className = 'slot-btn p-3 rounded-2xl bg-amber-400/20 border-2 border-amber-400 text-center transition font-bold shadow-md cursor-pointer';
    }
    updateSummary();
  }

  function updateSummary() {
    const countEl = document.getElementById('summary-slot-count');
    const listEl = document.getElementById('summary-selected-list');
    const subtotalEl = document.getElementById('summary-subtotal');
    const totalEl = document.getElementById('summary-total');

    countEl.innerText = selectedSlots.length + ' Jam';
    if (selectedSlots.length === 0) {
      listEl.innerHTML = '<span class="text-slate-400 italic">Belum ada jam yang dipilih.</span>';
      subtotalEl.innerText = 'Rp 0';
      totalEl.innerText = 'Rp 2.500';
      return;
    }

    let subtotal = 0;
    listEl.innerHTML = selectedSlots.map(s => {
      subtotal += s.price;
      return `<div class="flex justify-between"><span>${s.time}</span><span class="font-bold text-emerald-500">Rp ${s.price.toLocaleString('id-ID')}</span></div>`;
    }).join('');

    subtotalEl.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
    totalEl.innerText = 'Rp ' + (subtotal + 2500).toLocaleString('id-ID');
  }

  function goToCheckout() {
    if ({{ $isOwner ? 'true' : 'false' }}) {
      alert('Perhatian: Anda adalah pemilik tempat futsal ini. Pemilik tidak dapat menyewa lapangan miliknya sendiri. Silakan kelola jadwal lewat menu Jadwal & Lapangan.');
      window.location.href = "{{ route('tenant.courts') }}";
      return;
    }

    if (selectedSlots.length === 0) {
      alert('Silakan pilih minimal 1 slot jam bermain terlebih dahulu!');
      return;
    }

    const courtPrice = selectedSlots.reduce((sum, s) => sum + s.price, 0);
    const totalAmount = courtPrice + 2500;
    const duration = selectedSlots.length;

    const sortedSlots = [...selectedSlots].sort((a, b) => a.time.localeCompare(b.time));
    const startTime = sortedSlots[0].time.split(' - ')[0] + ':00';
    const endTime = sortedSlots[sortedSlots.length - 1].time.split(' - ')[1] + ':00';

    const url = "{{ route('booking.payment') }}" + 
      `?court_id=${encodeURIComponent(selectedCourtId)}` +
      `&date=${encodeURIComponent(selectedDate)}` +
      `&start_time=${encodeURIComponent(startTime)}` +
      `&end_time=${encodeURIComponent(endTime)}` +
      `&duration=${encodeURIComponent(duration)}` +
      `&court_price=${encodeURIComponent(courtPrice)}` +
      `&total_amount=${encodeURIComponent(totalAmount)}`;

    window.location.href = url;
  }

  document.addEventListener('DOMContentLoaded', () => {
    renderSlotMatrix();
  });
</script>
@endsection
