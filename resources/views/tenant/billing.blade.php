@extends('layouts.admin')

@section('title', 'Paket Langganan Aplikasi - LapanganKita')
@section('meta_description', 'Kelola paket langganan aplikasi venue futsal Anda, pilih paket bulanan atau tahunan, dan lakukan pembayaran paket langganan secara instan.')

@section('content')
<div class="py-8 md:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

  <!-- ALERT NOTIFIKASI SUKSES / ERROR -->
  @if(session('success'))
    <div class="p-4 sm:p-5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 dark:text-emerald-300 text-xs sm:text-sm flex items-center gap-3 shadow-sm">
      <div class="w-8 h-8 rounded-xl bg-emerald-500 text-slate-950 flex items-center justify-center font-black shrink-0">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
      </div>
      <div class="min-w-0 flex-1">
        <strong class="font-extrabold block">Aktivasi Berhasil!</strong>
        <span>{{ session('success') }}</span>
      </div>
    </div>
  @endif

  @if(session('error'))
    <div class="p-4 sm:p-5 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-800 dark:text-rose-300 text-xs sm:text-sm flex items-center gap-3 shadow-sm">
      <div class="w-8 h-8 rounded-xl bg-rose-500 text-white flex items-center justify-center font-black shrink-0">
        <i data-lucide="alert-circle" class="w-5 h-5"></i>
      </div>
      <div class="min-w-0 flex-1">
        <strong class="font-extrabold block">Pemberitahuan:</strong>
        <span>{{ session('error') }}</span>
      </div>
    </div>
  @endif

  <!-- BANNER STATUS LANGGANAN SAAT INI -->
  <div class="bg-gradient-to-r from-emerald-950 via-slate-900 to-teal-950 border border-emerald-500/30 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
    
    <div class="space-y-2.5 relative z-10">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $isTrial ? 'bg-amber-500/20 border-amber-500/30 text-amber-400' : 'bg-emerald-500/20 border-emerald-500/30 text-emerald-400' }} border text-xs font-bold cursor-help" data-bs-toggle="tooltip" title="{{ $isTrial ? 'Trial 7 Hari: Evaluasi seluruh fitur tanpa biaya sebelum paket resmi diaktifkan.' : 'Lisensi Aktif: Venue Anda terdaftar secara resmi dan aktif menerima pemesanan online.' }}">
        <span class="w-2 h-2 rounded-full {{ $isTrial ? 'bg-amber-400' : 'bg-emerald-400' }} animate-pulse"></span>
        <span>Status Paket: {{ $isTrial ? 'Masa Uji Coba Gratis (7 Hari)' : 'Langganan Resmi Aktif' }}</span>
      </div>
      
      <div class="flex flex-wrap items-center gap-3">
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight">
          {{ $subscription->package->name ?? 'Paket Belum Dipilih' }}
        </h1>
        @if($subscription && $subscription->package)
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 cursor-help" data-bs-toggle="tooltip" title="Batas maksimal jumlah arena lapangan yang boleh didaftarkan ke sistem pada paket ini.">
            {{ $subscription->package->max_courts >= 90 ? 'Unlimited Lapangan' : 'Maks ' . $subscription->package->max_courts . ' Lapangan' }}
          </span>
        @endif
      </div>

      <p class="text-xs sm:text-sm text-slate-300">
        @if($isTrial)
          Masa uji coba gratis berlaku sampai: <strong class="text-white">{{ optional($subscription?->end_date)->format('d F Y') ?? '-' }}</strong> 
          (<span class="text-amber-400 font-bold">Sisa {{ (int) round($daysLeft) }} Hari Lagi</span>)
        @else
          Masa aktif langganan sampai: <strong class="text-white">{{ optional($subscription?->end_date)->format('d F Y') ?? '-' }}</strong>
          (<span class="text-emerald-400 font-bold">Sisa {{ (int) round($daysLeft) }} Hari</span>)
        @endif
      </p>
    </div>

    <div class="flex items-center gap-3 shrink-0 relative z-10">
      <a href="#pilihan-paket" class="px-5 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs sm:text-sm shadow-lg shadow-emerald-500/20 transition flex items-center gap-2">
        <i data-lucide="zap" class="w-4 h-4"></i>
        <span>{{ $isTrial ? 'Pilih Paket & Aktivasi' : 'Perpanjang / Upgrade Paket' }}</span>
      </a>
    </div>

    <!-- Decorative background glow -->
    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
  </div>

  <!-- DAFTAR PILIHAN 3 PAKET (LANGSUNG TAMPIL 3 KARTU SIDE-BY-SIDE) -->
  <div id="pilihan-paket" class="space-y-6 pt-2">
    
    <div>
      <div class="flex items-center gap-2.5">
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">Pilihan Paket Langganan Venue</h2>
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
          Aktivasi Otomatis
        </span>
      </div>
      <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
        Pilih paket bulanan fleksibel atau paket tahunan hemat sesuai kapasitas lapangan futsal Anda.
      </p>
    </div>

    <!-- 3 COLUMNS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @foreach($packages->take(3) as $pkg)
        <div class="bg-white dark:bg-slate-900 rounded-3xl border {{ $pkg->is_popular ? 'border-2 border-emerald-500 shadow-xl ring-2 ring-emerald-500/20' : ($pkg->billing_cycle === 'tahunan' ? 'border-2 border-amber-500/60 shadow-lg' : 'border border-slate-200 dark:border-slate-800') }} p-6 sm:p-7 flex flex-col justify-between shadow-sm relative space-y-6">
          
          <!-- Badge Atas -->
          @if($pkg->badge_text)
            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3.5 py-1 rounded-full {{ $pkg->is_popular ? 'bg-emerald-500 text-slate-950 shadow-emerald-500/30' : ($pkg->billing_cycle === 'tahunan' ? 'bg-amber-500 text-slate-950 shadow-amber-500/30' : 'bg-slate-800 text-slate-200 border border-slate-700') }} font-black text-[10px] uppercase tracking-wider shadow-md whitespace-nowrap">
              {{ $pkg->badge_text }}
            </div>
          @endif

          <div class="space-y-4 pt-1">
            <div class="flex items-center justify-between">
              <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $pkg->billing_cycle === 'tahunan' ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }} cursor-help" data-bs-toggle="tooltip" title="{{ $pkg->billing_cycle === 'tahunan' ? 'Siklus 365 hari: Pembayaran hemat untuk 1 tahun penuh tanpa perlu repot perpanjang tiap bulan.' : 'Siklus 30 hari: Pembayaran fleksibel per bulan yang dapat diperpanjang sewaktu-waktu.' }}">
                {{ $pkg->billing_cycle === 'tahunan' ? 'Siklus Tahunan (1 Tahun)' : 'Siklus Bulanan' }}
              </span>
              <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 cursor-help" data-bs-toggle="tooltip" title="Maksimal unit lapangan futsal yang diizinkan untuk didaftarkan pada paket ini.">
                {{ $pkg->max_courts >= 90 ? 'Unlimited Lapangan' : 'Maks ' . $pkg->max_courts . ' Lapangan' }}
              </span>
            </div>

            <div>
              <h3 class="font-extrabold text-xl text-slate-900 dark:text-white">{{ $pkg->name }}</h3>
              <p class="text-xs text-slate-500 mt-0.5">
                @if($pkg->billing_cycle === 'tahunan')
                  Komitmen 1 tahun penuh, hemat biaya 2 bulan operasional.
                @elseif($pkg->is_popular)
                  Fitur lengkap dengan modul analitik bisnis untuk operasional futsal ramai.
                @else
                  Cocok untuk tempat futsal baru dengan 1-2 unit lapangan.
                @endif
              </p>
            </div>

            <!-- Harga Paket -->
            <div class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
              Rp {{ number_format($pkg->price, 0, ',', '.') }}
              <span class="text-xs font-semibold text-slate-400">/ {{ $pkg->billing_cycle === 'tahunan' ? 'tahun' : 'bulan' }}</span>
            </div>
            
            <!-- List Fitur -->
            @php
              $features = is_array($pkg->features) ? $pkg->features : json_decode($pkg->features, true);
            @endphp
            @if($features)
              <ul class="space-y-2.5 text-xs text-slate-600 dark:text-slate-300 border-t border-slate-100 dark:border-slate-800 pt-4">
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

          <!-- Tombol Pilih & Bayar Paket -->
          <a href="{{ route('tenant.billing.checkout', $pkg->id) }}" 
             class="w-full py-3.5 rounded-2xl {{ $pkg->is_popular ? 'bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold shadow-lg shadow-emerald-500/20' : ($pkg->billing_cycle === 'tahunan' ? 'bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold shadow-lg shadow-amber-500/20' : 'border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 hover:bg-emerald-500 hover:text-slate-950 hover:border-emerald-500 text-slate-800 dark:text-white font-bold') }} text-xs sm:text-sm transition flex items-center justify-center gap-2 cursor-pointer">
            <i data-lucide="credit-card" class="w-4 h-4"></i>
            <span>Pilih &amp; Bayar Paket</span>
          </a>

        </div>
      @endforeach
    </div>

  </div>

  <!-- RIWAYAT TRANSAKSI / INVOICE LANGGANAN -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-7 shadow-sm space-y-4">
    <div class="flex items-center justify-between">
      <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
        <i data-lucide="receipt" class="w-4 h-4 text-emerald-500"></i>
        <span>Riwayat Langganan &amp; Invoice Resmi</span>
      </h2>
      <span class="text-xs text-slate-400">Total: {{ $paymentHistory->count() }} Transaksi</span>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 font-bold uppercase tracking-wider">
            <th class="pb-3">Paket Terpilih</th>
            <th class="pb-3">Siklus</th>
            <th class="pb-3">Tanggal Mulai</th>
            <th class="pb-3">Tanggal Berakhir</th>
            <th class="pb-3">Nominal Bayar</th>
            <th class="pb-3 text-center">Status</th>
            <th class="pb-3 text-right">Invoice PDF</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-medium">
          @forelse($paymentHistory as $hist)
            <tr>
              <td class="py-3.5 font-bold text-slate-900 dark:text-white">
                {{ $hist->package->name ?? 'Paket SaaS' }}
              </td>
              <td class="py-3.5 text-slate-500 capitalize">
                {{ $hist->package->billing_cycle ?? 'Bulanan' }}
              </td>
              <td class="py-3.5 text-slate-500">{{ optional($hist->start_date)->format('d M Y') }}</td>
              <td class="py-3.5 text-slate-500">{{ optional($hist->end_date)->format('d M Y') }}</td>
              <td class="py-3.5 font-bold text-emerald-600 dark:text-emerald-400">
                Rp {{ number_format($hist->price_paid, 0, ',', '.') }}
              </td>
              <td class="py-3.5 text-center">
                @if($hist->status === 'trial')
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-500 border border-amber-500/20">Uji Coba 7 Hari</span>
                @elseif($hist->status === 'active')
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">Langganan Aktif</span>
                @else
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500">{{ ucfirst($hist->status) }}</span>
                @endif
              </td>
              <td class="py-3.5 text-right">
                <a href="{{ route('tenant.billing.invoice.pdf', $hist->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-500/10 hover:bg-emerald-500 text-emerald-600 hover:text-slate-950 font-bold text-[10px] transition border border-emerald-500/30">
                  <i data-lucide="file-text" class="w-3 h-3"></i>
                  <span>Unduh Invoice</span>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-6 text-center text-slate-400">Belum ada riwayat tagihan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection
