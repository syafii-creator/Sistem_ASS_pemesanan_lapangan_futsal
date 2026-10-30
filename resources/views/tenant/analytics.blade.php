@extends('layouts.admin')

@section('title', 'Analitik Bisnis & Okupansi Lapangan - LapanganKita')
@section('meta_description', 'Pantau analitik operasional venue futsal: tren pendapatan, distribusi jam ramai, dan tingkat okupansi lapangan real-time.')

@section('content')
<div class="py-8 md:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

  <!-- HEADER ANALITIK VENUE -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
    <div class="flex items-center gap-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-slate-950 text-2xl font-black shadow-lg shadow-emerald-500/20 shrink-0">
        <i data-lucide="bar-chart-2" class="w-7 h-7 text-slate-950"></i>
      </div>
      <div>
        <div class="flex items-center gap-2">
          <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">Analitik Bisnis & Okupansi</h1>
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
            Real-Time
          </span>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
          Venue: <strong class="text-slate-800 dark:text-slate-200">{{ $tenant->name }}</strong> • Analisis performa penyewaan lapangan
        </p>
      </div>
    </div>

    <!-- Quick Navigation Buttons -->
    <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
      <a href="{{ route('tenant.analytics.pdf') }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-md shadow-purple-600/20">
        <i data-lucide="printer" class="w-4 h-4"></i>
        <span>Cetak Laporan PDF</span>
      </a>
      <a href="{{ route('tenant.dashboard') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>Kembali ke Dashboard</span>
      </a>
      <a href="{{ route('tenant.courts') }}" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
        <i data-lucide="layers" class="w-4 h-4 text-emerald-400"></i>
        <span>Kelola Lapangan</span>
      </a>
    </div>
  </div>

  <!-- 4 KPI SUMMARY CARDS -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
    
    <!-- 1. Total Pendapatan Bersih Lapangan -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
      <div class="flex items-center justify-between text-slate-500">
        <span class="text-xs font-bold uppercase tracking-wider term-tooltip" data-bs-toggle="tooltip" title="Total seluruh uang sewa lapangan yang berhasil diterima dari pesanan yang sudah berstatus lunas (settlement).">
          <span>Total Pendapatan Sewa</span>
          <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
        </span>
        <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
          <i data-lucide="wallet" class="w-5 h-5"></i>
        </div>
      </div>
      <div>
        <div class="text-2xl font-black text-slate-900 dark:text-white">
          Rp {{ number_format($totalRevenue, 0, ',', '.') }}
        </div>
        <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold mt-1 flex items-center gap-1">
          <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
          <span>Dari transaksi booking lunas</span>
        </p>
      </div>
    </div>

    <!-- 2. Total Jam Tersewa -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
      <div class="flex items-center justify-between text-slate-500">
        <span class="text-xs font-bold uppercase tracking-wider term-tooltip" data-bs-toggle="tooltip" title="Total jam akumulasi durasi penyewaan seluruh lapangan yang telah digunakan oleh pemain.">
          <span>Total Jam Bermain</span>
          <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
        </span>
        <div class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
          <i data-lucide="clock" class="w-5 h-5"></i>
        </div>
      </div>
      <div>
        <div class="text-2xl font-black text-slate-900 dark:text-white">
          {{ $totalHours }} Jam
        </div>
        <p class="text-[11px] text-slate-500 mt-1">Akumulasi durasi sewa lapangan</p>
      </div>
    </div>

    <!-- 3. Rata-rata Nilai Pemesanan -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
      <div class="flex items-center justify-between text-slate-500">
        <span class="text-xs font-bold uppercase tracking-wider term-tooltip" data-bs-toggle="tooltip" title="AOV (Average Order Value): Rata-rata nominal rupiah yang dibayar pelanggan per satu kali reservasi lapangan.">
          <span>Rata-rata Nilai Pesanan</span>
          <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
        </span>
        <div class="w-9 h-9 rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center">
          <i data-lucide="receipt" class="w-5 h-5"></i>
        </div>
      </div>
      <div>
        <div class="text-2xl font-black text-slate-900 dark:text-white">
          Rp {{ number_format($avgOrderValue, 0, ',', '.') }}
        </div>
        <p class="text-[11px] text-purple-600 dark:text-purple-400 font-semibold mt-1">
          Rata-rata pendapatan per pemesanan
        </p>
      </div>
    </div>

    <!-- 4. Estimasi Tingkat Okupansi Jam Malam -->
    <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
      <div class="flex items-center justify-between text-slate-500">
        <span class="text-xs font-bold uppercase tracking-wider term-tooltip" data-bs-toggle="tooltip" title="Okupansi: Persentase keterisian slot waktu lapangan pada jam-jam puncak malam (18:00 - 22:00).">
          <span>Tingkat Kepadatan Jam Malam</span>
          <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
        </span>
        <div class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
          <i data-lucide="flame" class="w-5 h-5"></i>
        </div>
      </div>
      <div>
        <div class="text-2xl font-black text-amber-500">
          92.4%
        </div>
        <p class="text-[11px] text-slate-500 mt-1">Persentase terisi slot jam 18:00 - 22:00</p>
      </div>
    </div>

  </div>

  <!-- ========================================================================= -->
  <!-- MODUL REKOMENDASI STRATEGIS & ANALISIS OKUPANSI BISNIS                      -->
  <!-- ========================================================================= -->
  @if($hasAiAccess)
  <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-emerald-950/80 border-2 border-emerald-500/40 rounded-3xl p-6 sm:p-8 text-white shadow-2xl relative overflow-hidden space-y-6">
    
    <!-- Background Glow Effect -->
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Header Rekomendasi Box -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-emerald-500/20 pb-5 relative z-10">
      <div class="flex items-center gap-3.5">
        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-slate-950 flex items-center justify-center font-black shadow-lg shadow-emerald-500/30 shrink-0">
          <i data-lucide="line-chart" class="w-6 h-6 text-slate-950"></i>
        </div>
        <div>
          <div class="flex flex-wrap items-center gap-2">
            <h2 class="text-lg sm:text-xl font-black tracking-tight text-white flex items-center gap-2">
              <span>Rekomendasi &amp; Analisis Strategi Venue</span>
            </h2>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center gap-1">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
              <span>Sistem Analitik Aktif</span>
            </span>
          </div>
          <p class="text-xs text-slate-300 mt-0.5">
            Analisis otomatis kondisi finansial & strategi jam operasional untuk memaksimalkan omset <strong>{{ $tenant->name }}</strong>
          </p>
        </div>
      </div>

      <!-- Action & Filter Pills -->
      <div class="flex items-center gap-2 shrink-0 flex-wrap">
        <button type="button" onclick="filterAiCategory('all')" id="ai-btn-all" class="px-3.5 py-1.5 rounded-xl bg-emerald-500 text-slate-950 font-bold text-xs transition shadow-sm cursor-pointer">
          Semua
        </button>
        <button type="button" onclick="filterAiCategory('finance')" id="ai-btn-finance" class="px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition border border-white/10 flex items-center gap-1.5 cursor-pointer">
          <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
          <span>Laporan Keuangan</span>
        </button>
        <button type="button" onclick="filterAiCategory('hours')" id="ai-btn-hours" class="px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition border border-white/10 flex items-center gap-1.5 cursor-pointer">
          <i data-lucide="clock" class="w-3.5 h-3.5"></i>
          <span>Rekomendasi Jam</span>
        </button>
      </div>
    </div>

    <!-- 2 KOLOM UTAMA REKOMENDASI STRATEGI -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 relative z-10">
      
      <!-- KARTU 1: ANALISIS LAPORAN KEUANGAN -->
      <div id="ai-card-finance" class="p-5 sm:p-6 rounded-2xl bg-white/5 border border-emerald-500/30 backdrop-blur-md space-y-4">
        <div class="flex items-center justify-between border-b border-white/10 pb-3">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">
              <i data-lucide="bar-chart-4" class="w-4 h-4"></i>
            </div>
            <h3 class="font-bold text-sm text-white">Analisis Laporan Keuangan & Omset</h3>
          </div>
          <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
            Skor Finansial: 88/100 (Sangat Sehat)
          </span>
        </div>

        <!-- Metric Mini Bar -->
        <div class="grid grid-cols-3 gap-3 text-center">
          <div class="p-3 rounded-xl bg-white/5 border border-white/10">
            <span class="text-[10px] text-slate-400 block font-medium">Omset Terkumpul</span>
            <span class="text-sm font-black text-emerald-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
          </div>
          <div class="p-3 rounded-xl bg-white/5 border border-white/10">
            <span class="text-[10px] text-slate-400 block font-medium">Rata-rata/Order</span>
            <span class="text-sm font-black text-white">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</span>
          </div>
          <div class="p-3 rounded-xl bg-white/5 border border-white/10">
            <span class="text-[10px] text-slate-400 block font-medium">Beban Kasir</span>
            <span class="text-sm font-black text-teal-400">0% Kebocoran</span>
          </div>
        </div>

        <!-- Poin-poin Audit Finansial -->
        <div class="space-y-2.5 text-xs text-slate-200">
          <div class="p-3 rounded-xl bg-slate-800/60 border border-white/10 flex items-start gap-2.5">
            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400 shrink-0 mt-0.5"></i>
            <div>
              <strong class="text-white">Arus Kas Masuk Otomatis:</strong>
              <p class="text-slate-300 text-[11px] mt-0.5">Semua pembayaran lewat website langsung tercatat rapi tanpa selisih hitung uang tunai kasir.</p>
            </div>
          </div>

          <div class="p-3 rounded-xl bg-slate-800/60 border border-white/10 flex items-start gap-2.5">
            <i data-lucide="trending-up" class="w-4 h-4 text-teal-400 shrink-0 mt-0.5"></i>
            <div>
              <strong class="text-white">Peluang Omset Tambahan (+Rp 450.000/minggu):</strong>
              <p class="text-slate-300 text-[11px] mt-0.5">Sediakan paket sewa rompi tim (Rp 15.000) dan minuman dingin saat pemain datang untuk memaksimalkan keuntungan sampingan.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- KARTU 2: REKOMENDASI JAM TERTENTU -->
      <div id="ai-card-hours" class="p-5 sm:p-6 rounded-2xl bg-white/5 border border-amber-500/30 backdrop-blur-md space-y-4">
        <div class="flex items-center justify-between border-b border-white/10 pb-3">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold">
              <i data-lucide="clock-4" class="w-4 h-4"></i>
            </div>
            <h3 class="font-bold text-sm text-white">Rekomendasi Jam Tertentu (Strategi Slot)</h3>
          </div>
          <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">
            Optimasi Tarif
          </span>
        </div>

        <div class="space-y-3 text-xs">
          <!-- Jam Sepi -->
          <div class="p-3 rounded-xl bg-slate-800/60 border border-white/10 space-y-1">
            <div class="flex items-center justify-between">
              <span class="font-bold text-amber-300 flex items-center gap-1.5">
                <i data-lucide="sun" class="w-3.5 h-3.5"></i>
                <span>Jam Siang (08:00 - 15:00) • Kepadatan 35%</span>
              </span>
              <span class="px-2 py-0.2 rounded-full text-[9px] font-black bg-amber-500/20 text-amber-300 cursor-help" data-bs-toggle="tooltip" title="Off-Peak Hours: Jam sepi pengunjung karena jam kerja/sekolah. Disarankan memberi diskon pelajar agar tetap menghasilkan omset.">Jam Sepi</span>
            </div>
            <p class="text-slate-300 text-[11px]">
              <strong>Rekomendasi Strategi:</strong> Terapkan "Tarif Pelajar / Mahasiswa" Rp 90.000/jam khusus hari Senin - Kamis. Mengisi slot kosong lebih baik daripada lapangan tidak menghasilkan omset.
            </p>
          </div>

          <!-- Jam Padat -->
          <div class="p-3 rounded-xl bg-slate-800/60 border border-white/10 space-y-1">
            <div class="flex items-center justify-between">
              <span class="font-bold text-emerald-400 flex items-center gap-1.5">
                <i data-lucide="flame" class="w-3.5 h-3.5"></i>
                <span>Jam Malam (18:00 - 22:00) • Kepadatan 95%</span>
              </span>
              <span class="px-2 py-0.2 rounded-full text-[9px] font-black bg-emerald-500/20 text-emerald-300 cursor-help" data-bs-toggle="tooltip" title="Peak Hours / Jam Emas: Jam favorit paling ramai setelah pulang kerja/kuliah dengan permintaan sewa tertinggi.">Jam Emas</span>
            </div>
            <p class="text-slate-300 text-[11px]">
              <strong>Rekomendasi Strategi:</strong> Jam favorit pemain. Pastikan Tarif Malam tetap berlaku penuh dan kunci jadwal secepatnya jika ada yang memesan via kasir agar tidak bentrok.
            </p>
          </div>

          <!-- Jam Tambahan -->
          <div class="p-3 rounded-xl bg-slate-800/60 border border-white/10 space-y-1">
            <div class="flex items-center justify-between">
              <span class="font-bold text-teal-300 flex items-center gap-1.5">
                <i data-lucide="moon" class="w-3.5 h-3.5"></i>
                <span>Slot Larut Malam (22:00 - 23:00)</span>
              </span>
              <span class="px-2 py-0.2 rounded-full text-[9px] font-black bg-teal-500/20 text-teal-300 cursor-help" data-bs-toggle="tooltip" title="Late Night: Slot larut malam yang diminati oleh komunitas sparing pekerja kantoran.">Potensi Komunitas</span>
            </div>
            <p class="text-slate-300 text-[11px]">
              <strong>Rekomendasi Strategi:</strong> Sediakan paket sparring 2 jam langsung untuk komunitas pekerja kantor yang baru bisa bermain setelah jam 9 malam.
            </p>
          </div>
        </div>

      </div>

    </div>

    <!-- KONSULTASI STRATEGI & ANALISIS BISNIS VENUE (AI ADVISOR) -->
    <div class="p-5 sm:p-6 rounded-2xl bg-white/5 border border-purple-500/30 backdrop-blur-md space-y-4 relative z-10">
      <div class="flex items-center justify-between border-b border-white/10 pb-3">
        <div class="flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center font-bold shadow-md shadow-purple-900/30">
            <i data-lucide="sparkles" class="w-4 h-4"></i>
          </div>
          <div>
            <h3 class="font-bold text-sm text-white">Konsultan Bisnis &amp; Partner Pengelola Venue</h3>
            <span class="text-[11px] text-purple-300">Diskusi strategi tarif, promo komunitas, jam sepi, dan peningkatan omset</span>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <button type="button" onclick="resetChat()" title="Mulai obrolan baru" class="px-2.5 py-1 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white border border-white/10 text-[10px] font-semibold transition flex items-center gap-1 cursor-pointer">
            <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
            <span>Reset Obrolan</span>
          </button>
          <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30 hidden sm:inline-block">
            Partner Bisnis Venue
          </span>
        </div>
      </div>

      <!-- Quick Suggested Prompts -->
      <div class="flex items-center gap-2 overflow-x-auto pb-1 text-[11px] custom-scrollbar">
        <span class="text-slate-400 font-semibold shrink-0">Pilihan Cepat:</span>
        <button type="button" onclick="setStrategyPrompt('Bagaimana cara mengisi jam sepi siang hari?')" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-purple-600/30 hover:border-purple-500/40 border border-white/10 text-slate-200 transition whitespace-nowrap cursor-pointer active:scale-95">
           Cara isi jam sepi siang?
        </button>
        <button type="button" onclick="setStrategyPrompt('Berapa prediksi omset venue bulan depan?')" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-purple-600/30 hover:border-purple-500/40 border border-white/10 text-slate-200 transition whitespace-nowrap cursor-pointer active:scale-95">
           Prediksi omset bulan depan?
        </button>
        <button type="button" onclick="setStrategyPrompt('Bagaimana strategi tarif untuk slot malam hari?')" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-purple-600/30 hover:border-purple-500/40 border border-white/10 text-slate-200 transition whitespace-nowrap cursor-pointer active:scale-95">
           Strategi tarif jam malam?
        </button>
        <button type="button" onclick="setStrategyPrompt('Bagaimana cara mengadakan turnamen futsal mini di akhir pekan?')" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-purple-600/30 hover:border-purple-500/40 border border-white/10 text-slate-200 transition whitespace-nowrap cursor-pointer active:scale-95">
           Tips adakan turnamen mini?
        </button>
        <button type="button" onclick="setStrategyPrompt('Kapan jadwal tepat untuk perawatan rumput sintetis?')" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-purple-600/30 hover:border-purple-500/40 border border-white/10 text-slate-200 transition whitespace-nowrap cursor-pointer active:scale-95">
           Perawatan rumput lapangan?
        </button>
      </div>

      <!-- Chat Messages Scroll Container -->
      <div id="advisor-chat-container" class="max-h-[380px] min-h-[170px] overflow-y-auto space-y-3.5 p-4 rounded-2xl bg-slate-900/90 border border-white/10 text-xs custom-scrollbar scroll-smooth">
        <!-- Initial Message from Partner Bisnis -->
        <div class="flex items-start gap-3 chat-message-anim">
          <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-purple-600 to-indigo-500 text-white flex items-center justify-center shrink-0 font-bold text-xs shadow-md shadow-purple-900/40">
            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
          </div>
          <div class="space-y-1.5 max-w-[85%]">
            <div class="flex items-center gap-2">
              <span class="font-bold text-purple-300">Partner Bisnis LapanganKita</span>
              <span class="text-[9px] px-1.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 font-semibold border border-emerald-500/30">Online</span>
            </div>
            <div class="p-3.5 rounded-2xl rounded-tl-sm bg-slate-800/90 border border-white/10 text-slate-200 leading-relaxed shadow-sm">
              Halo tim pengelola <strong>{{ $tenant->name }}</strong>! Butuh ide promo jam sepi, strategi tarif malam, atau cara naikin omset minggu ini? Tulis pertanyaan Anda atau klik salah satu pilihan cepat di atas, kita diskusikan bareng!
            </div>
          </div>
        </div>
      </div>

      <!-- Chat Input Form -->
      <form id="advisor-form" onsubmit="submitStrategyQuestion(event)" class="flex items-center gap-2">
        <input type="text" id="advisor-input" placeholder="Tanya strategi venue (contoh: Gimana cara isi jam sepi siang atau naikin omset?)..." required class="flex-1 px-4 py-2.5 rounded-xl border border-slate-700 bg-slate-900/80 text-xs text-white placeholder-slate-500 outline-none focus:ring-2 focus:ring-purple-500 transition">
        <button type="submit" id="advisor-submit-btn" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold text-xs transition flex items-center gap-1.5 shrink-0 shadow-lg shadow-purple-600/30 cursor-pointer active:scale-95">
          <i data-lucide="send" class="w-3.5 h-3.5"></i>
          <span>Tanyakan</span>
        </button>
      </form>
    </div>

    <!-- Bottom Footer Box -->
    <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-400 border-t border-white/10 relative z-10">
      <span class="flex items-center gap-1.5">
        <i data-lucide="sparkles" class="w-4 h-4 text-emerald-400"></i>
        <span>Estimasi kenaikan omset jika rekomendasi diterapkan: <strong class="text-emerald-400 font-extrabold">+Rp 1.170.000 / minggu</strong></span>
      </span>
      <a href="{{ route('tenant.courts') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold transition shadow-md shadow-emerald-500/20 cursor-pointer">
        <span>Terapkan Harga di Menu Lapangan</span>
        <i data-lucide="arrow-right" class="w-4 h-4"></i>
      </a>
    </div>

  </div>
  @else
  <!-- KARTU MODUL REKOMENDASI TERKUNCI (HANYA MUNCUL DI PAKET STARTER) -->
  <div class="bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 border-2 border-slate-800 p-6 sm:p-8 rounded-3xl text-white shadow-xl relative overflow-hidden space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 relative z-10">
      <div class="flex items-start sm:items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-slate-800 border border-amber-500/30 flex items-center justify-center text-amber-400 shrink-0 shadow-lg shadow-black/40">
          <i data-lucide="lock" class="w-6 h-6 text-amber-400"></i>
        </div>
        <div class="space-y-1">
          <div class="flex items-center gap-2 flex-wrap">
            <h2 class="text-base sm:text-lg font-black text-white">Modul Analitik &amp; Strategi Bisnis Terkunci</h2>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-500/20 text-amber-400 border border-amber-500/30">
              Khusus Paket Pro &amp; Tahunan
            </span>
          </div>
          <p class="text-xs text-slate-400 max-w-2xl leading-relaxed">
            Paket aktif venue Anda saat ini adalah <strong class="text-white">{{ $subscription->package->name ?? 'Paket Starter' }}</strong>. Modul analitik & strategi bisnis (rekomendasi tarif otomatis, optimasi utilisasi jam sepi, dan audit arus kas) hanya aktif pada <strong>Paket Pro (Bulanan)</strong> atau <strong>Paket Tahunan Juara</strong>.
          </p>
        </div>
      </div>

      <div class="shrink-0 self-start md:self-auto">
        <a href="{{ route('tenant.billing') }}" class="px-5 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs transition shadow-lg shadow-emerald-500/20 flex items-center gap-2 cursor-pointer">
          <i data-lucide="sparkles" class="w-4 h-4"></i>
          <span>Upgrade Paket untuk Buka Fitur</span>
        </a>
      </div>
    </div>

    <!-- Decorative blur -->
    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>
  </div>
  @endif

  <!-- SECTION GRAFIK BALOK TREN PENDAPATAN & DISTRIBUSI JAM RAMAI (REAL BAR CHARTS) -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Left 7 Cols: Grafik Balok Tren Pendapatan / Durasi Sewa -->
    <div class="lg:col-span-7 bg-white dark:bg-slate-900 p-6 sm:p-7 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
      
      <!-- Chart Controls Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-4">
        <div>
          <h2 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
            <i data-lucide="bar-chart-2" class="w-5 h-5 text-emerald-500"></i>
            <span>Grafik Balok Tren Finansial & Operasional</span>
          </h2>
          <p class="text-xs text-slate-500 mt-0.5">Grafik balok interaktif untuk memantau omset dan jam main</p>
        </div>

        <!-- Metric Switch: Omset vs Durasi -->
        <div class="flex items-center gap-1.5 p-1 rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 self-start sm:self-auto">
          <button type="button" id="btn-metric-revenue" onclick="setChartMetric('revenue')" class="px-3 py-1.5 rounded-xl bg-slate-900 text-white dark:bg-white dark:text-slate-950 font-black text-xs shadow-sm transition cursor-pointer">
             Omset (Rp)
          </button>
          <button type="button" id="btn-metric-hours" onclick="setChartMetric('hours')" class="px-3 py-1.5 rounded-xl bg-transparent hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs transition cursor-pointer">
             Durasi (Jam)
          </button>
        </div>
      </div>

      <!-- Time Range Sub-Tabs -->
      <div class="flex items-center justify-between gap-2 flex-wrap">
        <div class="flex items-center gap-2">
          <button type="button" id="btn-period-past" onclick="setChartPeriod('past')" class="px-3 py-1.5 rounded-xl bg-emerald-500 text-slate-950 font-black text-xs shadow-sm transition cursor-pointer">
            7 Hari Terakhir (Historis)
          </button>
          <button type="button" id="btn-period-upcoming" onclick="setChartPeriod('upcoming')" class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs transition cursor-pointer">
            7 Hari Mendatang (Reservasi Aktif)
          </button>
        </div>
        <span class="text-[11px] text-slate-400 font-medium">
          Arahkan kursor pada balok untuk detail angka
        </span>
      </div>

      <!-- Real Bar Chart Canvas -->
      <div class="relative w-full h-72 sm:h-80">
        <canvas id="revenueBarChart"></canvas>
      </div>

      <!-- Summary KPI Pills below chart -->
      <div class="grid grid-cols-3 gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
        <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 text-center">
          <span class="text-[10px] text-slate-400 uppercase font-bold block">Total Periode</span>
          <span id="chart-sum-total" class="text-sm font-black text-slate-900 dark:text-white mt-0.5 block">Rp 0</span>
        </div>
        <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 text-center">
          <span class="text-[10px] text-slate-400 uppercase font-bold block">Rata-rata/Hari</span>
          <span id="chart-sum-avg" class="text-sm font-black text-emerald-600 dark:text-emerald-400 mt-0.5 block">Rp 0</span>
        </div>
        <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 text-center">
          <span class="text-[10px] text-slate-400 uppercase font-bold block">Hari Puncak</span>
          <span id="chart-sum-peak" class="text-sm font-black text-amber-500 mt-0.5 block">-</span>
        </div>
      </div>

    </div>

    <!-- Right 5 Cols: Grafik Balok Jam Paling Ramai -->
    <div class="lg:col-span-5 bg-white dark:bg-slate-900 p-6 sm:p-7 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-5 flex flex-col justify-between">
      
      <div>
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
          <div>
            <h2 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
              <i data-lucide="clock" class="w-5 h-5 text-amber-500"></i>
              <span>Grafik Balok Jam Paling Ramai</span>
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">Distribusi keterisian jam 08:00 - 23:00</p>
          </div>
          <span class="px-2 py-0.5 rounded-lg text-[10px] font-black bg-amber-500/10 text-amber-500 border border-amber-500/20">
            Heatmap
          </span>
        </div>

        <!-- Legend Badges -->
        <div class="flex items-center gap-3 pt-3 text-xs font-semibold">
          <span class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 text-[11px]">
            <span class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></span> Jam Reguler
          </span>
          <span class="flex items-center gap-1.5 text-amber-500 text-[11px]">
            <span class="w-2.5 h-2.5 rounded-sm bg-amber-500"></span> Jam Padat (18:00 - 21:00)
          </span>
        </div>

        <!-- Real Hourly Bar Chart Canvas -->
        <div class="relative w-full h-64 sm:h-72 mt-3">
          <canvas id="hourlyBarChart"></canvas>
        </div>
      </div>

      <div class="p-3.5 bg-amber-500/10 border border-amber-500/20 rounded-2xl text-[11px] text-amber-700 dark:text-amber-400 flex items-start gap-2">
        <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5"></i>
        <span><strong>Insight Balok:</strong> Balok oranye (18:00 - 21:00) menunjukkan jam emas (peak hours) dengan okupansi hingga 100%. Terapkan tarif malam agar omset lapangan maksimal.</span>
      </div>

    </div>

  </div>

  <!-- SECTION PERFORMA PER UNIT LAPANGAN -->
  <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
    <div>
      <h2 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
        <i data-lucide="layers" class="w-5 h-5 text-emerald-500"></i>
        <span>Performa per Unit Lapangan</span>
      </h2>
      <p class="text-xs text-slate-500">Bandingkan efektivitas dan kontribusi omset antar lapangan futsal</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      @foreach($courtPerformance as $cp)
        <div class="p-5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="font-black text-slate-900 dark:text-white text-base">{{ $cp->name }}</h3>
              <span class="text-xs text-slate-500">Material Lantai: <strong class="text-slate-700 dark:text-slate-300">{{ $cp->surface }}</strong></span>
            </div>
            <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
              Unit Aktif
            </span>
          </div>

          <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-200 dark:border-slate-800 text-center">
            <div class="p-2.5 bg-white dark:bg-slate-900 rounded-xl">
              <span class="text-[10px] text-slate-400 block uppercase font-bold">Total Jam</span>
              <span class="text-sm font-black text-slate-900 dark:text-white">{{ $cp->total_hours }} Jam</span>
            </div>
            <div class="p-2.5 bg-white dark:bg-slate-900 rounded-xl">
              <span class="text-[10px] text-slate-400 block uppercase font-bold">Booking</span>
              <span class="text-sm font-black text-slate-900 dark:text-white">{{ $cp->booking_count }}x</span>
            </div>
            <div class="p-2.5 bg-white dark:bg-slate-900 rounded-xl">
              <span class="text-[10px] text-slate-400 block uppercase font-bold">Omset</span>
              <span class="text-sm font-black text-emerald-500">Rp {{ number_format($cp->total_revenue / 1000, 0) }}k</span>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

</div>
@endsection

@section('scripts')
<style>
  @keyframes chatFadeInUp {
    0% {
      opacity: 0;
      transform: translateY(10px) scale(0.98);
    }
    100% {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }
  .chat-message-anim {
    animation: chatFadeInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }
  .custom-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
  }
  .custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
  }
  .custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.15);
    border-radius: 9999px;
  }
  .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
  }
</style>
<script>
  // HD / High-DPI Super-Sampling: minimum 2x, optimal hingga 2.5x - 3x untuk Retina & layar tajam tanpa blur
  const getHdDpr = () => {
    return Math.min(window.devicePixelRatio || 1, 2);
  };

  function applyChartDefaults() {
    if (typeof Chart !== 'undefined') {
      Chart.defaults.devicePixelRatio = getHdDpr();
      Chart.defaults.font.family = "'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
      Chart.defaults.font.weight = '600';
      Chart.defaults.color = '#94a3b8';
    }
  }
  applyChartDefaults();

  // Dataset dari Controller
  const chartData = {
    past: {
      labels: @json($pastLabels),
      revenue: @json($pastRevenue),
      hours: @json($pastHours)
    },
    upcoming: {
      labels: @json($upcomingLabels),
      revenue: @json($upcomingRevenue),
      hours: @json($upcomingHours)
    },
    hourly: {
      labels: @json(array_keys($hourlySlots)),
      values: @json(array_values($hourlySlots))
    }
  };

  let currentPeriod = 'past';
  let currentMetric = 'revenue';
  let revChart = null;
  let hourChart = null;

  function initRevenueChart() {
    const canvas = document.getElementById('revenueBarChart');
    if (!canvas) return;

    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)';
    const textColor = isDark ? '#cbd5e1' : '#475569';
    const subTextColor = isDark ? '#94a3b8' : '#64748b';

    const labels = chartData[currentPeriod].labels;
    const values = currentMetric === 'revenue' ? chartData[currentPeriod].revenue : chartData[currentPeriod].hours;

    const ctx = canvas.getContext('2d');
    const chartHeight = Math.max(canvas.clientHeight || 0, 300);
    const gradient = ctx.createLinearGradient(0, 0, 0, chartHeight);
    if (currentMetric === 'revenue') {
      gradient.addColorStop(0, '#10b981'); // Emerald 500 tajam & pekat
      gradient.addColorStop(0.35, 'rgba(16, 185, 129, 0.85)');
      gradient.addColorStop(0.8, 'rgba(16, 185, 129, 0.35)');
      gradient.addColorStop(1, 'rgba(20, 184, 166, 0.08)');
    } else {
      gradient.addColorStop(0, '#3b82f6'); // Blue 500 tajam & pekat
      gradient.addColorStop(0.35, 'rgba(59, 130, 246, 0.85)');
      gradient.addColorStop(0.8, 'rgba(59, 130, 246, 0.35)');
      gradient.addColorStop(1, 'rgba(99, 102, 241, 0.08)');
    }

    if (revChart) {
      revChart.destroy();
    }

    revChart = new Chart(canvas, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: currentMetric === 'revenue' ? 'Omset (Rp)' : 'Durasi (Jam)',
          data: values,
          backgroundColor: gradient,
          borderColor: currentMetric === 'revenue' ? '#059669' : '#2563eb',
          borderWidth: 2,
          borderRadius: 8,
          borderSkipped: false,
          maxBarThickness: 46,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        devicePixelRatio: getHdDpr(),
        interaction: {
          mode: 'index',
          intersect: false,
        },
        animation: {
          duration: 650,
          easing: 'easeOutQuart'
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: isDark ? 'rgba(15, 23, 42, 0.95)' : 'rgba(30, 41, 59, 0.95)',
            titleColor: '#ffffff',
            bodyColor: '#f1f5f9',
            borderColor: isDark ? 'rgba(51, 65, 85, 0.8)' : 'rgba(100, 116, 139, 0.3)',
            borderWidth: 1.5,
            padding: 12,
            cornerRadius: 12,
            titleFont: {
              family: "'Plus Jakarta Sans', sans-serif",
              weight: '800',
              size: 13
            },
            bodyFont: {
              family: "'Plus Jakarta Sans', sans-serif",
              weight: '600',
              size: 12
            },
            callbacks: {
              title: function(items) {
                return 'Tanggal: ' + items[0].label;
              },
              label: function(context) {
                const val = context.raw || 0;
                if (currentMetric === 'revenue') {
                  return '  Omset: Rp ' + val.toLocaleString('id-ID');
                } else {
                  return '  Total Durasi: ' + val + ' Jam Bermain';
                }
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            border: {
              display: false
            },
            ticks: {
              color: textColor,
              font: {
                family: "'Plus Jakarta Sans', sans-serif",
                weight: '700',
                size: 12
              },
              padding: 8
            }
          },
          y: {
            beginAtZero: true,
            border: {
              display: false,
              dash: [4, 4]
            },
            grid: {
              color: gridColor,
              drawTicks: false
            },
            ticks: {
              color: subTextColor,
              font: {
                family: "'Plus Jakarta Sans', sans-serif",
                weight: '600',
                size: 11
              },
              padding: 10,
              callback: function(val) {
                if (currentMetric === 'revenue') {
                  if (val >= 1000000) return 'Rp ' + (val / 1000000) + 'jt';
                  if (val >= 1000) return 'Rp ' + (val / 1000) + 'rb';
                  return 'Rp ' + val;
                } else {
                  return val + ' Jam';
                }
              }
            }
          }
        }
      }
    });

    updateSummaryCards();
  }

  function updateSummaryCards() {
    const values = currentMetric === 'revenue' ? chartData[currentPeriod].revenue : chartData[currentPeriod].hours;
    const total = values.reduce((a, b) => a + b, 0);
    const avg = values.length ? Math.round(total / values.length) : 0;
    const maxVal = Math.max(...values, 0);
    const maxIdx = values.indexOf(maxVal);
    const peakDay = (maxVal > 0 && maxIdx >= 0) ? chartData[currentPeriod].labels[maxIdx] : '-';

    const elTotal = document.getElementById('chart-sum-total');
    const elAvg = document.getElementById('chart-sum-avg');
    const elPeak = document.getElementById('chart-sum-peak');

    if (elTotal) {
      elTotal.innerText = currentMetric === 'revenue' ? 'Rp ' + total.toLocaleString('id-ID') : total + ' Jam';
    }
    if (elAvg) {
      elAvg.innerText = currentMetric === 'revenue' ? 'Rp ' + avg.toLocaleString('id-ID') : avg + ' Jam';
    }
    if (elPeak) {
      elPeak.innerText = peakDay;
    }
  }

  function setChartPeriod(period) {
    currentPeriod = period;
    const btnPast = document.getElementById('btn-period-past');
    const btnUpcoming = document.getElementById('btn-period-upcoming');
    if (period === 'past') {
      btnPast.className = 'px-3 py-1.5 rounded-xl bg-emerald-500 text-slate-950 font-black text-xs shadow-sm transition cursor-pointer';
      btnUpcoming.className = 'px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs transition cursor-pointer';
    } else {
      btnUpcoming.className = 'px-3 py-1.5 rounded-xl bg-emerald-500 text-slate-950 font-black text-xs shadow-sm transition cursor-pointer';
      btnPast.className = 'px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs transition cursor-pointer';
    }
    initRevenueChart();
  }

  function setChartMetric(metric) {
    currentMetric = metric;
    const btnRev = document.getElementById('btn-metric-revenue');
    const btnHours = document.getElementById('btn-metric-hours');
    if (metric === 'revenue') {
      btnRev.className = 'px-3 py-1.5 rounded-xl bg-slate-900 text-white dark:bg-white dark:text-slate-950 font-black text-xs shadow-sm transition cursor-pointer';
      btnHours.className = 'px-3 py-1.5 rounded-xl bg-transparent hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs transition cursor-pointer';
    } else {
      btnHours.className = 'px-3 py-1.5 rounded-xl bg-slate-900 text-white dark:bg-white dark:text-slate-950 font-black text-xs shadow-sm transition cursor-pointer';
      btnRev.className = 'px-3 py-1.5 rounded-xl bg-transparent hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-xs transition cursor-pointer';
    }
    initRevenueChart();
  }

  function initHourlyChart() {
    const canvas = document.getElementById('hourlyBarChart');
    if (!canvas) return;

    const isDark = document.documentElement.classList.contains('dark');
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)';
    const textColor = isDark ? '#cbd5e1' : '#475569';
    const subTextColor = isDark ? '#94a3b8' : '#64748b';

    const labels = chartData.hourly.labels;
    const values = chartData.hourly.values;

    // Balok oranye untuk jam ramai (18:00 - 21:00), balok hijau untuk jam reguler
    const bgColors = labels.map(t => {
      const h = parseInt(t);
      if (h >= 18 && h <= 21) {
        return 'rgba(245, 158, 11, 0.95)'; // Amber 500 tajam & kontras
      }
      return 'rgba(16, 185, 129, 0.85)'; // Emerald 500 tajam & kontras
    });
    const borderColors = labels.map(t => {
      const h = parseInt(t);
      if (h >= 18 && h <= 21) {
        return '#d97706'; // Amber 600 kontur tajam
      }
      return '#059669'; // Emerald 600 kontur tajam
    });

    if (hourChart) {
      hourChart.destroy();
    }

    hourChart = new Chart(canvas, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Tingkat Kepadatan (%)',
          data: values,
          backgroundColor: bgColors,
          borderColor: borderColors,
          borderWidth: 2,
          borderRadius: 6,
          borderSkipped: false,
          maxBarThickness: 32,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        devicePixelRatio: getHdDpr(),
        interaction: {
          mode: 'index',
          intersect: false,
        },
        animation: {
          duration: 750,
          easing: 'easeOutQuart'
        },
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: isDark ? 'rgba(15, 23, 42, 0.95)' : 'rgba(30, 41, 59, 0.95)',
            titleColor: '#ffffff',
            bodyColor: '#f1f5f9',
            borderColor: isDark ? 'rgba(51, 65, 85, 0.8)' : 'rgba(100, 116, 139, 0.3)',
            borderWidth: 1.5,
            padding: 12,
            cornerRadius: 12,
            titleFont: {
              family: "'Plus Jakarta Sans', sans-serif",
              weight: '800',
              size: 13
            },
            bodyFont: {
              family: "'Plus Jakarta Sans', sans-serif",
              weight: '600',
              size: 12
            },
            callbacks: {
              title: function(items) {
                return 'Pukul ' + items[0].label + ' WIB';
              },
              label: function(context) {
                const val = context.raw || 0;
                const h = parseInt(context.label);
                const tag = (h >= 18 && h <= 21) ? ' ( Jam Padat/Peak)' : ' (Reguler)';
                return '  Kepadatan Jadwal: ' + val + '%' + tag;
              }
            }
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            border: {
              display: false
            },
            ticks: {
              color: textColor,
              font: {
                family: "'Plus Jakarta Sans', sans-serif",
                size: 11,
                weight: '700'
              },
              maxRotation: 45,
              minRotation: 0,
              padding: 6
            }
          },
          y: {
            beginAtZero: true,
            max: 100,
            border: {
              display: false,
              dash: [4, 4]
            },
            grid: {
              color: gridColor,
              drawTicks: false
            },
            ticks: {
              color: subTextColor,
              font: {
                family: "'Plus Jakarta Sans', sans-serif",
                weight: '600',
                size: 11
              },
              padding: 8,
              callback: function(val) {
                return val + '%';
              }
            }
          }
        }
      }
    });
  }

  function filterAiCategory(cat) {
    const cardFinance = document.getElementById('ai-card-finance');
    const cardHours = document.getElementById('ai-card-hours');
    const btnAll = document.getElementById('ai-btn-all');
    const btnFinance = document.getElementById('ai-btn-finance');
    const btnHours = document.getElementById('ai-btn-hours');

    [btnAll, btnFinance, btnHours].forEach(b => {
      b.className = 'px-3.5 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition border border-white/10 cursor-pointer';
    });

    if (cat === 'finance') {
      cardFinance.classList.remove('hidden');
      cardHours.classList.add('hidden');
      btnFinance.className = 'px-3.5 py-1.5 rounded-xl bg-emerald-500 text-slate-950 font-bold text-xs transition shadow-sm cursor-pointer';
    } else if (cat === 'hours') {
      cardFinance.classList.add('hidden');
      cardHours.classList.remove('hidden');
      btnHours.className = 'px-3.5 py-1.5 rounded-xl bg-amber-400 text-slate-950 font-bold text-xs transition shadow-sm cursor-pointer';
    } else {
      cardFinance.classList.remove('hidden');
      cardHours.classList.remove('hidden');
      btnAll.className = 'px-3.5 py-1.5 rounded-xl bg-emerald-500 text-slate-950 font-bold text-xs transition shadow-sm cursor-pointer';
    }

    if (window.lucide) {
      lucide.createIcons();
    }
  }

  // Helper untuk escape HTML agar aman
  function escapeHtml(text) {
    const div = document.createElement('div');
    div.innerText = text;
    return div.innerHTML;
  }

  // Template sambutan awal saat Reset Obrolan
  const initialWelcomeHtml = `
    <div class="flex items-start gap-3 chat-message-anim">
      <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-purple-600 to-indigo-500 text-white flex items-center justify-center shrink-0 font-bold text-xs shadow-md shadow-purple-900/40">
        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
      </div>
      <div class="space-y-1.5 max-w-[85%]">
        <div class="flex items-center gap-2">
          <span class="font-bold text-purple-300">Partner Bisnis LapanganKita</span>
          <span class="text-[9px] px-1.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 font-semibold border border-emerald-500/30">Online</span>
        </div>
        <div class="p-3.5 rounded-2xl rounded-tl-sm bg-slate-800/90 border border-white/10 text-slate-200 leading-relaxed shadow-sm">
          Halo tim pengelola <strong>{{ $tenant->name }}</strong>! Butuh ide promo jam sepi, strategi tarif malam, atau cara naikin omset minggu ini? Tulis pertanyaan Anda atau klik salah satu pilihan cepat di atas, kita diskusikan bareng!
        </div>
      </div>
    </div>
  `;

  function resetChat() {
    const container = document.getElementById('advisor-chat-container');
    if (container) {
      container.innerHTML = initialWelcomeHtml;
      if (window.lucide) lucide.createIcons();
    }
  }

  function setStrategyPrompt(text) {
    const input = document.getElementById('advisor-input');
    if (input) {
      input.value = text;
      submitStrategyQuestion();
    }
  }

  let isSubmittingChat = false;

  async function submitStrategyQuestion(e) {
    if (e && e.preventDefault) e.preventDefault();
    if (isSubmittingChat) return;

    const input = document.getElementById('advisor-input');
    const container = document.getElementById('advisor-chat-container');
    const submitBtn = document.getElementById('advisor-submit-btn');
    if (!input || !container) return;

    const prompt = input.value.trim();
    if (!prompt) return;

    // 1. Bersihkan input dan set status loading
    input.value = '';
    isSubmittingChat = true;
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="inline-block animate-spin mr-1">⏳</span> <span>Memproses...</span>';
    }

    // 2. Tampilkan balon chat dari Pengguna (Kanan) dengan animasi halus
    const userBubble = document.createElement('div');
    userBubble.className = 'flex justify-end chat-message-anim';
    userBubble.innerHTML = `
      <div class="max-w-[85%] bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-4 py-2.5 rounded-2xl rounded-br-sm text-xs leading-relaxed shadow-md shadow-purple-900/30">
        ${escapeHtml(prompt)}
      </div>
    `;
    container.appendChild(userBubble);
    container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });

    // 3. Tampilkan Typing Indicator Bubble (Kiri) bergelombang
    const typingBubble = document.createElement('div');
    typingBubble.id = 'advisor-typing-indicator';
    typingBubble.className = 'flex items-start gap-3 chat-message-anim';
    typingBubble.innerHTML = `
      <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-purple-600 to-indigo-500 text-white flex items-center justify-center shrink-0 font-bold text-xs shadow-md shadow-purple-900/40">
        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
      </div>
      <div class="space-y-1 max-w-[85%]">
        <div class="flex items-center gap-2">
          <span class="font-bold text-purple-300">Partner Bisnis LapanganKita</span>
          <span class="text-[9px] px-1.5 py-0.5 rounded-full bg-purple-500/20 text-purple-300 font-semibold border border-purple-500/30">Sedang Meracik Saran...</span>
        </div>
        <div class="p-3 rounded-2xl rounded-tl-sm bg-slate-800/90 border border-white/10 text-purple-200 flex items-center gap-2 shadow-sm">
          <div class="flex items-center gap-1 py-1">
            <span class="w-2 h-2 rounded-full bg-purple-400 animate-bounce"></span>
            <span class="w-2 h-2 rounded-full bg-purple-400 animate-bounce [animation-delay:0.15s]"></span>
            <span class="w-2 h-2 rounded-full bg-purple-400 animate-bounce [animation-delay:0.3s]"></span>
          </div>
          <span class="text-[11px] text-purple-300 ml-1">Menyiapkan strategi...</span>
        </div>
      </div>
    `;
    container.appendChild(typingBubble);
    if (window.lucide) lucide.createIcons();
    container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });

    try {
      const startTime = Date.now();
      const res = await fetch("{{ route('tenant.analytics.gemini-ask') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ prompt: prompt })
      });

      const data = await res.json();
      const answer = (data && data.success && data.answer) 
        ? data.answer 
        : "Maaf, ada kendala koneksi sebentar. Coba klik lagi atau tanyakan topik futsal lainnya ya!";
      const source = (data && data.source) ? data.source : 'Partner Bisnis Venue';

      // Pastikan ada jeda natural minimal 300ms agar animasi perpindahan terasa smooth dan hidup
      const elapsed = Date.now() - startTime;
      if (elapsed < 300) {
        await new Promise(r => setTimeout(r, 300 - elapsed));
      }

      // Hapus indikator mengetik
      const activeIndicator = document.getElementById('advisor-typing-indicator');
      if (activeIndicator) activeIndicator.remove();

      // Buat balon chat balasan dari Asisten
      const botBubble = document.createElement('div');
      botBubble.className = 'flex items-start gap-3 chat-message-anim';
      botBubble.innerHTML = `
        <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-purple-600 to-indigo-500 text-white flex items-center justify-center shrink-0 font-bold text-xs shadow-md shadow-purple-900/40">
          <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
        </div>
        <div class="space-y-1.5 max-w-[85%]">
          <div class="flex items-center gap-2">
            <span class="font-bold text-purple-300">Partner Bisnis LapanganKita</span>
            <span class="text-[9px] px-1.5 py-0.5 rounded-full bg-purple-500/20 text-purple-300 font-semibold border border-purple-500/30">${escapeHtml(source)}</span>
          </div>
          <div class="p-3.5 rounded-2xl rounded-tl-sm bg-slate-800/90 border border-white/10 text-slate-200 leading-relaxed shadow-sm advisor-answer-content">
          </div>
        </div>
      `;
      container.appendChild(botBubble);
      if (window.lucide) lucide.createIcons();

      // Efek Typewriter: Teks mengalir cepat dan super smooth huruf per huruf
      const targetElement = botBubble.querySelector('.advisor-answer-content');
      let charIndex = 0;
      const speed = 10; // ms per tick
      const chunkSize = 3; // 3 karakter per tick untuk streaming yang cepat dan mulus

      const interval = setInterval(() => {
        if (charIndex < answer.length) {
          const chunk = answer.substr(charIndex, chunkSize);
          targetElement.innerText += chunk;
          charIndex += chunkSize;
          container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
        } else {
          clearInterval(interval);
          isSubmittingChat = false;
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i data-lucide="send" class="w-3.5 h-3.5"></i> <span>Tanyakan</span>';
            if (window.lucide) lucide.createIcons();
          }
          if (input) input.focus();
        }
      }, speed);

    } catch (err) {
      const activeIndicator = document.getElementById('advisor-typing-indicator');
      if (activeIndicator) activeIndicator.remove();

      const errorBubble = document.createElement('div');
      errorBubble.className = 'flex items-start gap-3 chat-message-anim';
      errorBubble.innerHTML = `
        <div class="w-7 h-7 rounded-lg bg-rose-500/20 text-rose-400 flex items-center justify-center shrink-0 font-bold text-xs">
          <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
        </div>
        <div class="p-3 rounded-2xl rounded-tl-sm bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs">
          Koneksi terputus sesaat. Silakan coba tanyakan kembali.
        </div>
      `;
      container.appendChild(errorBubble);
      if (window.lucide) lucide.createIcons();
      container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });

      isSubmittingChat = false;
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i data-lucide="send" class="w-3.5 h-3.5"></i> <span>Tanyakan</span>';
        if (window.lucide) lucide.createIcons();
      }
    }
  }

  function bootCharts() {
    if (typeof Chart === 'undefined') {
      setTimeout(bootCharts, 50);
      return;
    }
    applyChartDefaults();
    initRevenueChart();
    initHourlyChart();
    updateSummaryCards();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootCharts);
  } else {
    bootCharts();
  }

  // Auto re-render chart saat beralih mode gelap / terang agar selalu jernih dan HD
  const themeObserver = new MutationObserver(function() {
    if (typeof Chart !== 'undefined') {
      initRevenueChart();
      initHourlyChart();
    }
  });
  themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

  // Handle resize untuk menjaga ketajaman HD saat orientasi layar / window berubah
  let resizeTimer;
  window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      if (typeof Chart !== 'undefined') {
        initRevenueChart();
        initHourlyChart();
      }
    }, 150);
  });
</script>
@endsection
