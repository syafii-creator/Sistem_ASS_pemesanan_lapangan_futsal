@extends('layouts.app')

@section('title', 'Pembayaran Reservasi - Sandbox Payment Gateway')
@section('meta_description', 'Selesaikan pembayaran sewa lapangan futsal melalui QRIS dinamis atau Virtual Account resmi.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 space-y-8">
  
  <!-- Header Title & Countdown -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800">
    <div>
      <span class="text-xs font-bold tracking-widest text-emerald-600 dark:text-emerald-400 uppercase">Payment Gateway</span>
      <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">Pembayaran Reservasi Futsal</h1>
      <p class="text-xs text-slate-500 mt-0.5">Kode Booking: <strong class="text-slate-800 dark:text-slate-200">{{ $booking->booking_code ?? 'LK-BKG-' . strtoupper(Str::random(6)) }}</strong></p>
    </div>

    <!-- Countdown Timer -->
    <div class="flex items-center gap-2 px-4 py-2 bg-amber-500/10 border border-amber-500/30 rounded-2xl text-amber-600 dark:text-amber-400 font-black text-sm">
      <i data-lucide="timer" class="w-4 h-4 animate-spin"></i>
      <span>Sisa Waktu: <span id="countdown">09:59</span></span>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
    
    <!-- Left 7 Cols: Payment Methods (QRIS & VA) -->
    <div class="md:col-span-7 space-y-6">
      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 space-y-6 shadow-md">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
          <div>
            <h2 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
              <i data-lucide="shield-check" class="w-5 h-5 text-emerald-500"></i>
              <span>Metode Pembayaran Resmi</span>
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Semua transaksi diproses aman melalui payment gateway Midtrans</p>
          </div>
          <span class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Midtrans Gateway
          </span>
        </div>

        <!-- MIDTRANS GATEWAY SHOWCASE CARD -->
        <div class="p-5 rounded-2xl bg-gradient-to-br from-[#002855] via-[#003B73] to-[#0077B6] text-white space-y-4 shadow-xl">
          <div class="flex items-center justify-between">
            <span class="text-xs font-black text-white uppercase tracking-wider flex items-center gap-2">
              <i data-lucide="credit-card" class="w-4 h-4 text-emerald-400"></i>
              <span>Pilihan Channel Otomatis</span>
            </span>
            <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded text-white font-bold">Verifikasi Instan</span>
          </div>

          <div class="grid grid-cols-2 gap-2 text-xs">
            <div class="p-2.5 rounded-xl bg-white/10 backdrop-blur-sm border border-white/15 flex items-center gap-2">
              <i data-lucide="qr-code" class="w-4 h-4 text-emerald-300 shrink-0"></i>
              <div>
                <span class="font-bold text-[11px] block">QRIS Dinamis</span>
                <span class="text-[9px] text-slate-200 block">GoPay, ShopeePay, Dana, BCA</span>
              </div>
            </div>
            <div class="p-2.5 rounded-xl bg-white/10 backdrop-blur-sm border border-white/15 flex items-center gap-2">
              <i data-lucide="building-2" class="w-4 h-4 text-cyan-300 shrink-0"></i>
              <div>
                <span class="font-bold text-[11px] block">Virtual Account</span>
                <span class="text-[9px] text-slate-200 block">BCA, Mandiri, BRI, BNI</span>
              </div>
            </div>
          </div>

          <button type="button" onclick="payBookingMidtrans()" 
                  class="w-full py-3.5 bg-gradient-to-r from-emerald-400 to-teal-400 hover:from-emerald-300 hover:to-teal-300 text-slate-950 font-black text-xs sm:text-sm rounded-xl shadow-lg shadow-emerald-500/25 transition flex items-center justify-center gap-2 cursor-pointer">
            <i data-lucide="shield-check" class="w-4 h-4"></i>
            <span>Bayar Sekarang via Midtrans (Rp {{ number_format($booking->total_amount ?? 122500, 0, ',', '.') }})</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
          </button>
        </div>

        <div class="pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
          <a href="{{ route('venues.index') }}" class="font-bold text-slate-500 hover:text-rose-500 transition flex items-center gap-1.5">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Batal &amp; Cari Jadwal Lain</span>
          </a>
          <span class="text-[11px] text-slate-400 flex items-center gap-1">
            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-500"></i>
            <span>Midtrans Payment Gateway</span>
          </span>
        </div>

      </div>
    </div>

    <!-- Right 5 Cols: Invoice Detail Summary -->
    <div class="md:col-span-5 space-y-6">
      <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-md space-y-5">
        <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Rincian Tagihan</h3>

        <div class="space-y-3 text-xs">
          <div class="flex justify-between text-slate-500">
            <span>Venue:</span>
            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $booking->tenant?->name ?? 'Champion Futsal Arena' }}</span>
          </div>
          <div class="flex justify-between text-slate-500">
            <span>Lapangan:</span>
            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $booking->court?->name ?? 'Lapangan A (Sintetis)' }}</span>
          </div>
          <div class="flex justify-between text-slate-500">
            <span>Tanggal:</span>
            <span class="font-bold text-slate-800 dark:text-slate-200">{{ \Carbon\Carbon::parse($booking->booking_date ?? now())->format('d M Y') }}</span>
          </div>
          <div class="flex justify-between text-slate-500">
            <span>Jam Main:</span>
            <span class="font-bold text-slate-800 dark:text-slate-200">{{ substr($booking->start_time ?? '09:00', 0, 5) }} - {{ substr($booking->end_time ?? '10:00', 0, 5) }} WIB</span>
          </div>
        </div>

        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-2 text-xs">
          <div class="flex justify-between text-slate-500">
            <span>Sewa Lapangan ({{ $booking->duration_hours ?? 1 }} Jam):</span>
            <span class="font-bold text-slate-800 dark:text-slate-200">Rp {{ number_format($booking->court_price ?? 120000, 0, ',', '.') }}</span>
          </div>
          <div class="flex justify-between text-slate-500">
            <span>Biaya Layanan Platform:</span>
            <span class="font-bold text-slate-800 dark:text-slate-200">Rp {{ number_format($booking->service_fee ?? 2500, 0, ',', '.') }}</span>
          </div>
          <div class="flex justify-between items-center pt-3 border-t border-slate-100 dark:border-slate-800">
            <span class="text-xs font-bold text-slate-900 dark:text-white">Total Tagihan:</span>
            <span class="text-xl font-black text-emerald-600 dark:text-emerald-400">
              Rp {{ number_format($booking->total_amount ?? 122500, 0, ',', '.') }}
            </span>
          </div>
        </div>

        @if(($booking->status ?? '') === 'paid')
          <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
            <a href="{{ route('booking.ticket.pdf', $booking->booking_code) }}" target="_blank" class="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs rounded-xl transition flex items-center justify-center gap-2 shadow-md shadow-emerald-500/20">
              <i data-lucide="printer" class="w-4 h-4"></i>
              <span>Cetak Bukti Pembayaran & E-Tiket PDF</span>
            </a>
          </div>
        @else
          <div class="mt-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400">
            <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500 shrink-0"></i>
            <span>Setelah pembayaran berhasil, Bukti Pembayaran & E-Tiket resmi dapat langsung diunduh / dicetak dalam format PDF.</span>
          </div>
        @endif

      </div>
    </div>

  </div>

</div>
@endsection

@section('scripts')
<script>
  // 10 Min Countdown
  let seconds = 599;
  setInterval(() => {
    if (seconds <= 0) return;
    seconds--;
    const m = Math.floor(seconds / 60).toString().padStart(2, '0');
    const s = (seconds % 60).toString().padStart(2, '0');
    const el = document.getElementById('countdown');
    if (el) {
      el.innerText = `${m}:${s}`;
    }
  }, 1000);

  function payBookingMidtrans() {
    const payload = {
      court_id: '{{ $booking->court_id ?? 1 }}',
      tenant_id: '{{ $booking->tenant_id ?? 1 }}',
      booking_date: '{{ $booking->booking_date ?? now()->toDateString() }}',
      start_time: '{{ $booking->start_time ?? "09:00:00" }}',
      end_time: '{{ $booking->end_time ?? "10:00:00" }}',
      duration_hours: '{{ $booking->duration_hours ?? 1 }}',
      court_price: '{{ $booking->court_price ?? 120000 }}',
      service_fee: '{{ $booking->service_fee ?? 2500 }}',
      total_amount: '{{ $booking->total_amount ?? 122500 }}'
    };
    initiateMidtransPayment('booking', payload);
  }
</script>

@include('partials.midtrans-modal')
@endsection
