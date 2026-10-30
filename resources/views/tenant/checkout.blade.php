@extends('layouts.admin')

@section('title', 'Pembayaran Paket SaaS - LapanganKita')
@section('meta_description', 'Selesaikan pembayaran langganan aplikasi venue futsal LapanganKita via Midtrans Payment Gateway.')

@section('content')
<div class="py-8 md:py-12 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

  <!-- BREADCRUMB & BACK -->
  <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
    <a href="{{ route('tenant.billing') }}" class="hover:text-emerald-500 transition flex items-center gap-1">
      <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
      <span>Pilihan Paket Langganan</span>
    </a>
    <span>/</span>
    <span class="text-slate-900 dark:text-white">Checkout &amp; Pembayaran</span>
  </div>

  <!-- HEADER -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
    <div class="space-y-1">
      <div class="flex items-center gap-2">
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
          Midtrans Payment Gateway
        </span>
        <span class="text-xs text-slate-400">Trx: <strong>{{ $trxCode }}</strong></span>
      </div>
      <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
        Checkout &amp; Pembayaran Paket Langganan
      </h1>
      <p class="text-xs text-slate-500">
        Pilih metode pembayaran dan lakukan pelunasan untuk mengaktifkan paket secara otomatis.
      </p>
    </div>

    <!-- Countdown Timer Batas Pembayaran -->
    <div class="flex items-center gap-2.5 px-4 py-2.5 bg-amber-500/10 border border-amber-500/20 rounded-2xl text-amber-500 font-black text-xs shrink-0 self-start sm:self-auto">
      <i data-lucide="timer" class="w-4 h-4"></i>
      <span>Sisa Waktu: <span id="countdown">14:59</span></span>
    </div>
  </div>

  <!-- GRID CHECKOUT (PAYMENT METHOD & SUMMARY) -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- LEFT (7 COLS): METODE PEMBAYARAN & SIMULATOR GATEWAY -->
    <div class="lg:col-span-7 space-y-6">
      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-7 space-y-6 shadow-sm">
        
        <div class="flex items-center justify-between">
          <h2 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
            <i data-lucide="wallet" class="w-5 h-5 text-emerald-500"></i>
            <span>Pilih Metode Pembayaran</span>
          </h2>
          <span class="text-[11px] text-emerald-500 font-semibold flex items-center gap-1">
            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
            Terenkripsi 256-bit
          </span>
        </div>

        <!-- MIDTRANS GATEWAY SHOWCASE -->
        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 space-y-3">
          <div class="flex items-center justify-between text-xs font-bold text-slate-800 dark:text-slate-200">
            <span class="flex items-center gap-1.5">
              <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i>
              <span>Channel Pembayaran Resmi Midtrans</span>
            </span>
            <span class="text-[10px] bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 px-2 py-0.5 rounded-full font-black">Online 24/7</span>
          </div>
          <p class="text-xs text-slate-500">
            Pilih metode pembayaran favorit Anda di popup Midtrans: QRIS (GoPay, ShopeePay, Dana, OVO, m-Banking), BCA VA, Mandiri Bill, BRI, BNI Virtual Account, atau Kartu Kredit.
          </p>
        </div>

        <!-- MIDTRANS SNAP UTAMA ACTION BOX -->
        <div class="p-5 rounded-3xl bg-gradient-to-br from-[#002855] via-[#003B73] to-[#0077B6] text-white space-y-4 shadow-xl">
          <div class="flex items-center justify-between">
            <span class="px-3 py-1 rounded-full bg-white/20 text-white text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5">
              <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
              Gateway Resmi: Midtrans Snap
            </span>
            <span class="text-[11px] text-slate-300 font-bold">Semua Bank &amp; E-Wallet</span>
          </div>

          <div>
            <h3 class="text-base font-black text-white">Bayar Cepat via Midtrans</h3>
            <p class="text-xs text-slate-200 mt-0.5">
              Dukungan pembayaran lengkap: QRIS, GoPay, ShopeePay, BCA, Mandiri, BRI, BNI Virtual Account, &amp; Kartu Kredit.
            </p>
          </div>

          <button type="button" onclick="paySubscriptionMidtrans({{ $package->id }})" 
                  class="w-full py-3.5 px-5 bg-gradient-to-r from-emerald-400 to-teal-400 hover:from-emerald-300 hover:to-teal-300 text-slate-950 font-black text-xs sm:text-sm rounded-xl shadow-lg shadow-emerald-500/30 transition flex items-center justify-center gap-2.5 cursor-pointer">
            <i data-lucide="shield-check" class="w-4 h-4"></i>
            <span>Bayar Sekarang via Midtrans (Rp {{ number_format($package->price, 0, ',', '.') }})</span>
          </button>
        </div>

        <div class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800">
          <a href="{{ route('tenant.billing') }}" class="text-xs font-bold text-slate-500 hover:text-rose-500 transition flex items-center gap-1.5">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Batal &amp; Ganti Pilihan Paket</span>
          </a>
          <span class="text-[11px] text-slate-400 flex items-center gap-1">
            <i data-lucide="lock" class="w-3 h-3 text-emerald-500"></i>
            <span>Transaksi Resmi &amp; Terenkripsi</span>
          </span>
        </div>

      </div>
    </div>

    <!-- RIGHT (5 COLS): RINGKASAN PAKET YANG DIDAPAT -->
    <div class="lg:col-span-5 space-y-6">
      
      <!-- KARTU PAKET TERPILIH -->
      <div class="bg-white dark:bg-slate-900 border-2 border-emerald-500 rounded-3xl p-6 sm:p-7 space-y-6 shadow-xl relative overflow-hidden">
        
        <!-- Badge Populer / Promo -->
        <div class="flex items-center justify-between">
          <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $package->billing_cycle === 'tahunan' ? 'bg-amber-500/20 text-amber-500 border border-amber-500/30' : 'bg-emerald-500/20 text-emerald-500 border border-emerald-500/30' }}">
            {{ $package->billing_cycle === 'tahunan' ? 'Paket Tahunan' : 'Paket Bulanan' }}
          </span>
          @if($package->badge_text)
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500 text-slate-950 shadow">
              {{ $package->badge_text }}
            </span>
          @endif
        </div>

        <div>
          <h3 class="text-xl font-black text-slate-900 dark:text-white">{{ $package->name }}</h3>
          <p class="text-xs text-slate-500 mt-1">
            Kapasitas: <strong class="text-emerald-600 dark:text-emerald-400">Maksimal {{ $package->max_courts }} Unit Lapangan</strong>
          </p>
        </div>

        <!-- TOTAL HARGA -->
        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-850 border border-slate-100 dark:border-slate-800 space-y-1">
          <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Tagihan Paket:</span>
          <div class="text-3xl font-black text-slate-900 dark:text-white">
            Rp {{ number_format($package->price, 0, ',', '.') }}
            <span class="text-xs font-normal text-slate-400">/ {{ $package->billing_cycle === 'tahunan' ? 'tahun' : 'bulan' }}</span>
          </div>
          <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold mt-1">
            {{ $package->billing_cycle === 'tahunan' ? 'Aktif selama 365 Hari (1 Tahun Penuh)' : 'Aktif selama 30 Hari' }}
          </p>
        </div>

        <!-- DAFTAR FITUR OTOMATIS AKTIF -->
        <div class="space-y-3">
          <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400 block">
            Fitur Langsung Didapatkan:
          </span>
          @php
            $features = is_array($package->features) ? $package->features : json_decode($package->features, true);
          @endphp
          @if($features)
            <ul class="space-y-2.5 text-xs text-slate-600 dark:text-slate-300">
              @foreach($features as $feat)
                <li class="flex items-start gap-2.5">
                  <div class="w-4 h-4 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0 mt-0.5">
                    <i data-lucide="check" class="w-3 h-3"></i>
                  </div>
                  <span>{{ $feat }}</span>
                </li>
              @endforeach
            </ul>
          @endif
        </div>

        <!-- JAMINAN AKTIVASI OTOMATIS -->
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-2 text-xs text-slate-500">
          <div class="flex items-center gap-2">
            <i data-lucide="sparkles" class="w-4 h-4 text-emerald-500 shrink-0"></i>
            <span>Masa uji coba otomatis diupgrade ke <strong>Resmi Aktif</strong>.</span>
          </div>
          <div class="flex items-center gap-2">
            <i data-lucide="file-check" class="w-4 h-4 text-emerald-500 shrink-0"></i>
            <span>Invoice PDF resmi langsung diterbitkan &amp; dapat diunduh.</span>
          </div>
        </div>

      </div>

    </div>

  </div>

</div>
@endsection

@section('scripts')
<script>
  let currentMethod = 'QRIS';

  function setPaymentMethod(method) {
    currentMethod = method;
    const input = document.getElementById('input-payment-method');
    if (input) input.value = method;

    // Reset styles
    const tabQris = document.getElementById('tab-qris');
    const tabBca = document.getElementById('tab-bca');
    const tabMandiri = document.getElementById('tab-mandiri');

    const pQris = document.getElementById('method-panel-qris');
    const pBca = document.getElementById('method-panel-bca');
    const pMandiri = document.getElementById('method-panel-mandiri');

    const activeClass = 'py-2.5 rounded-xl text-xs font-bold transition bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm flex items-center justify-center gap-1.5 cursor-pointer';
    const inactiveClass = 'py-2.5 rounded-xl text-xs font-bold transition text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white flex items-center justify-center gap-1.5 cursor-pointer';

    if (tabQris) tabQris.className = (method === 'QRIS') ? activeClass : inactiveClass;
    if (tabBca) tabBca.className = (method === 'BCA_VA') ? activeClass : inactiveClass;
    if (tabMandiri) tabMandiri.className = (method === 'MANDIRI_VA') ? activeClass : inactiveClass;

    if (pQris) pQris.classList.toggle('hidden', method !== 'QRIS');
    if (pBca) pBca.classList.toggle('hidden', method !== 'BCA_VA');
    if (pMandiri) pMandiri.classList.toggle('hidden', method !== 'MANDIRI_VA');
  }

  function copyVa(text) {
    navigator.clipboard.writeText(text).then(function() {
      alert('Nomor Virtual Account ' + text + ' berhasil disalin!');
    }).catch(function() {
      alert('Nomor Virtual Account: ' + text);
    });
  }

  // Countdown timer 15 menit
  let timeRemaining = 15 * 60;
  const countdownEl = document.getElementById('countdown');
  const timerInterval = setInterval(function() {
    timeRemaining--;
    if (timeRemaining <= 0) {
      clearInterval(timerInterval);
      countdownEl.innerText = "00:00";
      return;
    }
    const mins = Math.floor(timeRemaining / 60);
    const secs = timeRemaining % 60;
    countdownEl.innerText = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
  }, 1000);

  function paySubscriptionMidtrans(packageId) {
    initiateMidtransPayment('subscription', { package_id: packageId });
  }
</script>

@include('partials.midtrans-modal')
@endsection
