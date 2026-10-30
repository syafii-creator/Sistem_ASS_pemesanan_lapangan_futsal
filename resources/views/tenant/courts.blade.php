@extends('layouts.admin')

@section('title', 'Pengaturan Lapangan & Jadwal Sewa - LapanganKita')
@section('meta_description', 'Kelola unit lapangan futsal, atur tarif sewa siang & malam, serta kunci jadwal untuk pemain yang pesan langsung di tempat (kasir).')

@section('content')
<div class="py-8 md:py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

  <!-- ALERT NOTIFIKASI -->
  @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-xs flex items-center gap-2.5">
      <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500 shrink-0"></i>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  @if(session('error'))
    <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-300 text-xs flex items-center gap-2.5">
      <i data-lucide="alert-circle" class="w-5 h-5 text-rose-500 shrink-0"></i>
      <span>{{ session('error') }}</span>
    </div>
  @endif

  <!-- HEADER -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
    <div>
      <div class="flex items-center gap-2 flex-wrap">
        <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">Pengaturan Lapangan & Jadwal Sewa</h1>
        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold cursor-help {{ (isset($maxCourts) && $courts->count() >= $maxCourts) ? 'bg-amber-500/10 text-amber-500 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' }}" data-bs-toggle="tooltip" title="Batas maksimal unit lapangan yang diizinkan untuk didaftarkan sesuai paket kemitraan SaaS Anda.">
          Kuota: {{ $courts->count() }} / {{ (isset($maxCourts) && $maxCourts >= 90) ? 'Unlimited' : ($maxCourts ?? 2) }} Lapangan
        </span>
      </div>
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
        Atur harga per jam, kelola unit lapangan, dan kunci jadwal manual jika ada pemain yang pesan langsung di tempat (kasir / offline).
      </p>
    </div>

    <div class="flex items-center gap-2.5 flex-wrap">
      <button type="button" onclick="document.getElementById('modal-add-court').classList.remove('hidden')" class="px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs transition shadow-md shadow-emerald-500/20 flex items-center gap-2 cursor-pointer">
        <i data-lucide="plus-circle" class="w-4 h-4"></i>
        <span>Tambah Lapangan Baru</span>
      </button>
      <a href="{{ route('tenant.dashboard') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>Kembali ke Dashboard</span>
      </a>
    </div>
  </div>

  <!-- SECTION 1: UNIT LAPANGAN YANG AKTIF -->
  <div class="space-y-4">
    <div class="flex items-center justify-between">
      <h2 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
        <i data-lucide="layers" class="w-5 h-5 text-emerald-500"></i>
        <span>Unit Lapangan Aktif ({{ $courts->count() }})</span>
      </h2>
      <span class="text-xs text-slate-500">Klik lapangan di bawah untuk mengontrol matriks jamnya</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($courts as $court)
        @php
          $isSelected = $court->id === $selectedCourtId;
        @endphp
        <div class="bg-white dark:bg-slate-900 rounded-3xl border {{ $isSelected ? 'border-emerald-500 ring-2 ring-emerald-500/30' : 'border-slate-200 dark:border-slate-800' }} p-6 shadow-sm space-y-4 transition">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center font-black">
                {{ substr($court->name, -1) == 'B' ? 'B' : 'A' }}
              </div>
              <div>
                <h3 class="font-bold text-base text-slate-900 dark:text-white">{{ $court->name }}</h3>
                <p class="text-xs text-slate-400 cursor-help" data-bs-toggle="tooltip" title="Material lantai: Rumput Sintetis (empuk), Vinyl (standar turnamen internasional), atau Interlock Polypropylene (awet).">{{ $court->surface_type }}</p>
              </div>
            </div>
            <div class="flex items-center gap-1.5">
              <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                Aktif
              </span>
              <form method="POST" action="{{ route('tenant.courts.destroy', $court->id) }}" onsubmit="return confirm('Nonaktifkan (soft delete) unit {{ $court->name }}? Data historis transaksi booking masa lalu tetap aman.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-1 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-500/10 transition cursor-pointer" data-bs-toggle="tooltip" title="Soft Delete: Mengarsipkan unit lapangan tanpa menghapus riwayat pesanan sebelumnya.">
                  <i data-lucide="archive" class="w-3.5 h-3.5"></i>
                </button>
              </form>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3 pt-2">
            <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800">
              <span class="text-[10px] text-slate-400 block font-semibold uppercase term-tooltip" data-bs-toggle="tooltip" title="Tarif sewa per jam untuk jadwal siang hari pukul 08:00 hingga 16:00 WIB.">
                <span>Tarif Siang (08:00 - 16:00)</span>
                <i data-lucide="help-circle" class="w-3 h-3 inline text-slate-400"></i>
              </span>
              <span class="text-sm font-extrabold text-slate-900 dark:text-white">Rp {{ number_format($court->price_regular, 0, ',', '.') }}/jam</span>
            </div>
            <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800">
              <span class="text-[10px] text-amber-500 block font-semibold uppercase term-tooltip" data-bs-toggle="tooltip" title="Tarif sewa per jam untuk jadwal malam hari (jam ramai / peak hours) pukul 17:00 hingga 23:00 WIB.">
                <span>Tarif Malam (17:00 - 23:00)</span>
                <i data-lucide="help-circle" class="w-3 h-3 inline text-amber-500/80"></i>
              </span>
              <span class="text-sm font-extrabold text-slate-900 dark:text-white">Rp {{ number_format($court->price_peak, 0, ',', '.') }}/jam</span>
            </div>
          </div>

          <div>
            <a href="{{ route('tenant.courts', ['court_id' => $court->id, 'date' => $selectedDate]) }}" class="w-full py-2.5 rounded-xl {{ $isSelected ? 'bg-emerald-500 text-slate-950 font-black' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-200' }} text-xs transition flex items-center justify-center gap-1.5" data-bs-toggle="tooltip" title="Tampilkan jadwal operasional jam untuk lapangan ini.">
              <i data-lucide="calendar" class="w-4 h-4"></i>
              <span>{{ $isSelected ? ' Matriks Aktif Terpilih' : 'Pilih Lapangan Ini' }}</span>
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <!-- SECTION 2: MATRIX CONTROLLER & BLOCKING MANUAL REAL DATABASE -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-7 shadow-sm space-y-6">
    
    <!-- Top Filter Controls (Court & Date) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-5">
      <div>
        <h2 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="lock" class="w-5 h-5 text-amber-500"></i>
          <span class="term-tooltip" data-bs-toggle="tooltip" title="Fitur Kunci Jam: Mengunci jadwal secara realtime saat pemain membayar langsung di tempat, sehingga slot di website tertutup dan mencegah bentrok jadwal.">
            <span>Matriks Jadwal & Kunci Jam (Pesan di Tempat / Kasir)</span>
            <i data-lucide="help-circle" class="w-4 h-4 inline text-slate-400"></i>
          </span>
        </h2>
        <p class="text-xs text-slate-500">
          Klik jam untuk mengunci jika ada pemain yang bayar langsung di kasir (offline), atau klik lagi untuk membuka kembali agar bisa dipesan di website.
        </p>
      </div>

      <!-- Legend -->
      <div class="flex items-center gap-3 text-xs font-semibold flex-wrap">
        <span class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
          <span class="w-3 h-3 rounded-md bg-emerald-500"></span> Tersedia (Bisa Dipesan Web)
        </span>
        <span class="flex items-center gap-1.5 text-rose-500">
          <span class="w-3 h-3 rounded-md bg-rose-500"></span> Dipesan via Web (Lunas)
        </span>
        <span class="flex items-center gap-1.5 text-slate-500">
          <span class="w-3 h-3 rounded-md bg-slate-500"></span> Pesan di Kasir (Offline)
        </span>
      </div>
    </div>

    <!-- Date Selector Bar -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2">
      <span class="text-xs font-bold text-slate-500 shrink-0 mr-2">Pilih Tanggal:</span>
      @php
        $days = [
          ['label' => 'Hari Ini', 'date' => now()->format('d M'), 'full' => now()->toDateString()],
          ['label' => 'Besok', 'date' => now()->addDay()->format('d M'), 'full' => now()->addDay()->toDateString()],
          ['label' => now()->addDays(2)->isoFormat('dddd'), 'date' => now()->addDays(2)->format('d M'), 'full' => now()->addDays(2)->toDateString()],
          ['label' => now()->addDays(3)->isoFormat('dddd'), 'date' => now()->addDays(3)->format('d M'), 'full' => now()->addDays(3)->toDateString()],
          ['label' => now()->addDays(4)->isoFormat('dddd'), 'date' => now()->addDays(4)->format('d M'), 'full' => now()->addDays(4)->toDateString()],
        ];
      @endphp
      @foreach($days as $d)
        @php
          $isDateActive = $d['full'] === $selectedDate;
        @endphp
        <a href="{{ route('tenant.courts', ['court_id' => $selectedCourtId, 'date' => $d['full']]) }}" class="px-4 py-2 rounded-xl text-xs font-bold border transition whitespace-nowrap {{ $isDateActive ? 'border-emerald-500 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:border-slate-300' }}">
          <span>{{ $d['label'] }} ({{ $d['date'] }})</span>
        </a>
      @endforeach
    </div>

    <!-- Slots Grid for Operator (Connected to Database) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-3 pt-2">
      @foreach($matrixSlots as $slot)
        @php
          $status = $slot['status'];
          $booking = $slot['booking'];
        @endphp

        <form action="{{ route('tenant.courts.toggle-slot') }}" method="POST" class="inline slot-toggle-form" onsubmit="toggleSlotAjax(event, this)" data-timerange="{{ $slot['time_range'] }}">
          @csrf
          <input type="hidden" name="court_id" value="{{ $selectedCourtId }}">
          <input type="hidden" name="date" value="{{ $selectedDate }}">
          <input type="hidden" name="start_time" value="{{ $slot['start'] }}">

          @if($status === 'online')
            <!-- Slot Online / Tersedia: Klik untuk kunci manual pesan di tempat -->
            <button type="submit" title="Klik untuk mengunci jam ini jika ada tim yang bayar di kasir" class="slot-btn w-full p-3.5 rounded-2xl border-2 border-emerald-500/40 bg-emerald-50/50 dark:bg-emerald-950/20 text-center cursor-pointer transition hover:scale-105 hover:border-emerald-500">
              <div class="text-xs font-black text-emerald-700 dark:text-emerald-300">{{ $slot['time_range'] }}</div>
              <div class="slot-badge text-[10px] font-bold mt-1 uppercase text-emerald-600 dark:text-emerald-400 flex items-center justify-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>Tersedia</span>
              </div>
            </button>
          @elseif($status === 'blocked_manual')
            <!-- Slot Kunci Kasir: Klik untuk buka kunci -->
            <button type="submit" title="Jadwal dikunci kasir (Pesan di Tempat). Klik untuk membuka kembali agar bisa dipesan di website" class="slot-btn w-full p-3.5 rounded-2xl border-2 border-slate-400 dark:border-slate-600 bg-slate-200 dark:bg-slate-800 text-center cursor-pointer transition hover:scale-105 hover:border-rose-400">
              <div class="text-xs font-black text-slate-800 dark:text-slate-200">{{ $slot['time_range'] }}</div>
              <div class="slot-badge text-[10px] font-bold mt-1 uppercase text-slate-600 dark:text-slate-400 flex items-center justify-center gap-1">
                <i data-lucide="lock" class="w-2.5 h-2.5"></i>
                <span>Pesan di Kasir</span>
              </div>
            </button>
          @elseif($status === 'booked_web')
            <!-- Slot Web Paid: Sudah dibayar pemain via web -->
            <button type="button" onclick="alert('Jadwal jam {{ $slot['time_range'] }} sudah dipesan dan dibayar resmi oleh pemain: {{ $booking?->user?->name ?? 'Pemain Website' }} (Kode Booking: {{ $booking?->booking_code }}).');" class="slot-btn w-full p-3.5 rounded-2xl border-2 border-rose-500/40 bg-rose-50/50 dark:bg-rose-950/20 text-center cursor-pointer transition hover:scale-105">
              <div class="text-xs font-black text-rose-600 dark:text-rose-400">{{ $slot['time_range'] }}</div>
              <div class="slot-badge text-[10px] font-bold mt-1 uppercase text-rose-500 flex items-center justify-center gap-1">
                <span> Dipesan via Website</span>
              </div>
            </button>
          @endif
        </form>
      @endforeach
    </div>

    <div class="p-3 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-800 rounded-2xl text-[11px] text-slate-500 flex items-center gap-2">
      <i data-lucide="info" class="w-4 h-4 text-emerald-500 shrink-0"></i>
      <span>Jadwal ini terhubung langsung ke website pemain. Jika Anda mengunci jam untuk <strong>Pesan di Kasir (Offline)</strong>, pemain di website otomatis tidak bisa memesan jam tersebut agar jadwal tidak bentrok.</span>
    </div>

  </div>

  <!-- SECTION 3: ARSIP LAPANGAN NONAKTIF (SOFT DELETED / archived=1) -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-7 shadow-sm space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 dark:border-slate-800 pb-4">
      <div>
        <h2 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="archive" class="w-5 h-5 text-amber-500"></i>
          <span>Arsip Lapangan Nonaktif (Soft Deleted)</span>
        </h2>
        <p class="text-xs text-slate-500">Unit lapangan yang diarsipkan (archived=1). Data transaksi dan riwayat booking pemain tetap aman tersimpan.</p>
      </div>
      <span class="text-xs font-bold text-amber-600 dark:text-amber-400 bg-amber-500/10 px-3 py-1 rounded-full border border-amber-500/20">
        {{ isset($trashedCourts) ? $trashedCourts->count() : 0 }} Lapangan Terarsip
      </span>
    </div>

    <div class="overflow-x-auto">
      <table class="datatable w-full text-left text-xs" id="tableTrashedCourts">
        <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
          <tr>
            <th class="py-3 px-4">Nama Lapangan</th>
            <th class="py-3 px-4">Jenis Lantai</th>
            <th class="py-3 px-4 text-right">Tarif Siang</th>
            <th class="py-3 px-4 text-right">Tarif Malam</th>
            <th class="py-3 px-4">Waktu Soft Delete</th>
            <th class="py-3 px-4">Status</th>
            <th class="py-3 px-4 text-center">Tindakan</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
          @if(isset($trashedCourts) && $trashedCourts->isNotEmpty())
            @foreach($trashedCourts as $tc)
              <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-200 line-through">
                  {{ $tc->name }}
                </td>
                <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400">
                  {{ $tc->surface_type }}
                </td>
                <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-800 dark:text-slate-200">
                  Rp {{ number_format($tc->price_regular, 0, ',', '.') }}
                </td>
                <td class="py-3.5 px-4 text-right font-mono font-bold text-amber-600 dark:text-amber-400">
                  Rp {{ number_format($tc->price_peak, 0, ',', '.') }}
                </td>
                <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400">
                  {{ \Carbon\Carbon::parse($tc->deleted_at)->locale('id')->translatedFormat('d F Y, H:i') }} WIB
                </td>
                <td class="py-3.5 px-4">
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                    Archived (1)
                  </span>
                </td>
                <td class="py-3.5 px-4 text-center">
                  <form method="POST" action="{{ route('tenant.courts.restore', $tc->id) }}">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs transition flex items-center gap-1.5 mx-auto shadow-sm cursor-pointer" title="Pulihkan Lapangan (Restore)">
                      <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                      <span>Pulihkan (Restore)</span>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- MODAL TAMBAH LAPANGAN BARU (VALIDASI FORM LENGKAP) -->
<div id="modal-add-court" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-5">
    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
      <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
        <i data-lucide="plus-circle" class="w-5 h-5 text-emerald-500"></i>
        <span>Tambah Unit Lapangan</span>
      </h3>
      <button type="button" onclick="document.getElementById('modal-add-court').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 dark:hover:text-white cursor-pointer">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <form action="{{ route('tenant.courts.store') }}" method="POST" class="space-y-4">
      @csrf

      <div>
        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
          Nama Lapangan <span class="text-rose-500 font-bold">*</span>
        </label>
        <input type="text" 
               name="name" 
               required 
               autofocus
               placeholder="Contoh: Lapangan C (Vinyl Interlock)" 
               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold">
      </div>

      <div>
        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
          Jenis Lantai <span class="text-rose-500 font-bold">*</span>
        </label>
        <select name="surface_type" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none cursor-pointer">
          <option value="Rumput Sintetis">Rumput Sintetis</option>
          <option value="Vinyl Standar FIFA">Vinyl Standar FIFA</option>
          <option value="Interlock Polypropylene">Interlock Polypropylene</option>
          <option value="Semen Halus (Hardcourt)">Semen Halus (Hardcourt)</option>
        </select>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
            Tarif Siang (Rp) <span class="text-rose-500 font-bold">*</span>
          </label>
          <input type="number" 
                 name="price_regular" 
                 required 
                 value="120000" 
                 step="5000" 
                 placeholder="Contoh: 120000"
                 class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono">
        </div>

        <div>
          <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">
            Tarif Malam (Rp) <span class="text-rose-500 font-bold">*</span>
          </label>
          <input type="number" 
                 name="price_peak" 
                 required 
                 value="180000" 
                 step="5000" 
                 placeholder="Contoh: 180000"
                 class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono">
        </div>
      </div>

      <div class="pt-2 flex items-center justify-end gap-2">
        <button type="button" onclick="document.getElementById('modal-add-court').classList.add('hidden')" class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 cursor-pointer">
          Batal
        </button>
        <button type="submit" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs rounded-xl transition shadow-md shadow-emerald-500/20 cursor-pointer">
          Simpan Lapangan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<div id="slot-toast" class="fixed bottom-6 right-6 z-50 transform translate-y-20 opacity-0 transition-all duration-300 pointer-events-none">
  <div class="px-5 py-3 rounded-2xl bg-slate-900/95 dark:bg-white/95 text-white dark:text-slate-900 text-xs font-bold shadow-2xl backdrop-blur-xl border border-slate-700 dark:border-slate-200 flex items-center gap-2.5">
    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
    <span id="slot-toast-msg">Jadwal berhasil diperbarui!</span>
  </div>
</div>

<script>
  function showToast(msg, isSuccess = true) {
    const toast = document.getElementById('slot-toast');
    const toastMsg = document.getElementById('slot-toast-msg');
    if (!toast) return;
    toastMsg.textContent = msg;
    toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
    setTimeout(() => {
      toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
    }, 3000);
  }

  async function toggleSlotAjax(event, form) {
    event.preventDefault();
    const btn = form.querySelector('button[type="submit"]');
    if (!btn || btn.disabled) return;

    const originalHtml = btn.innerHTML;
    const timeRange = form.dataset.timerange || '';

    // Show visual loading indicator
    btn.disabled = true;
    btn.innerHTML = `
      <div class="text-xs font-black text-slate-500">${timeRange}</div>
      <div class="text-[10px] font-bold mt-1 text-slate-400 flex items-center justify-center gap-1">
        <i data-lucide="loader-2" class="w-3 h-3 animate-spin text-emerald-500"></i>
        <span>Menyimpan...</span>
      </div>
    `;
    if (window.lucide) lucide.createIcons();

    try {
      const formData = new FormData(form);
      const response = await fetch(form.action, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
      });

      const data = await response.json();

      if (data.success) {
        if (data.new_status === 'blocked_manual') {
          // Status updated to Pesan di Kasir
          btn.className = "slot-btn w-full p-3.5 rounded-2xl border-2 border-slate-400 dark:border-slate-600 bg-slate-200 dark:bg-slate-800 text-center cursor-pointer transition hover:scale-105 hover:border-rose-400";
          btn.title = "Jadwal dikunci kasir (Pesan di Tempat). Klik untuk membuka kembali agar bisa dipesan di website";
          btn.innerHTML = `
            <div class="text-xs font-black text-slate-800 dark:text-slate-200">${timeRange}</div>
            <div class="slot-badge text-[10px] font-bold mt-1 uppercase text-slate-600 dark:text-slate-400 flex items-center justify-center gap-1">
              <i data-lucide="lock" class="w-2.5 h-2.5"></i>
              <span>Pesan di Kasir</span>
            </div>
          `;
        } else if (data.new_status === 'online') {
          // Status updated to Tersedia
          btn.className = "slot-btn w-full p-3.5 rounded-2xl border-2 border-emerald-500/40 bg-emerald-50/50 dark:bg-emerald-950/20 text-center cursor-pointer transition hover:scale-105 hover:border-emerald-500";
          btn.title = "Klik untuk mengunci jam ini jika ada tim yang bayar di kasir";
          btn.innerHTML = `
            <div class="text-xs font-black text-emerald-700 dark:text-emerald-300">${timeRange}</div>
            <div class="slot-badge text-[10px] font-bold mt-1 uppercase text-emerald-600 dark:text-emerald-400 flex items-center justify-center gap-1">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
              <span>Tersedia</span>
            </div>
          `;
        }
        showToast(data.message || 'Status jadwal berhasil diperbarui via AJAX!');
      } else {
        alert(data.message || 'Gagal mengubah status jadwal.');
        btn.innerHTML = originalHtml;
      }
    } catch (err) {
      console.error('AJAX Error:', err);
      // Fallback submit form standard jika fetch bermasalah
      form.submit();
    } finally {
      btn.disabled = false;
      if (window.lucide) lucide.createIcons();
    }
  }
</script>
@endsection
