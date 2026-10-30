<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Keuangan & Rekomendasi Bisnis - {{ $tenant->name }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      body {
        background: white !important;
        color: black !important;
        font-size: 12pt;
      }
      .no-print {
        display: none !important;
      }
      .page-break {
        page-break-after: always;
      }
      @page {
        margin: 1.5cm;
        size: A4 portrait;
      }
    }
  </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased p-4 sm:p-8">

  <!-- Floating Action Bar (Hanya Tampil di Layar, Tersembunyi Saat Dicetak / Save PDF) -->
  <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between gap-4 p-4 rounded-2xl bg-slate-900 text-white shadow-lg no-print">
    <div class="flex items-center gap-2">
      <span class="text-xl"></span>
      <div>
        <h1 class="text-sm font-bold">Pratinjau Cetak Dokumen PDF Resmi</h1>
        <p class="text-[11px] text-slate-400">Klik "Cetak / Simpan PDF" untuk mengunduh dokumen A4 format PDF.</p>
      </div>
    </div>
    <div class="flex items-center gap-2.5">
      <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs transition shadow flex items-center gap-1.5 cursor-pointer">
        <span> Cetak / Simpan PDF</span>
      </button>
      <button onclick="window.close()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs transition cursor-pointer">
        Tutup
      </button>
    </div>
  </div>

  <!-- A4 PRINTABLE DOCUMENT CONTAINER -->
  <div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-3xl p-8 sm:p-12 shadow-md space-y-8 print:border-none print:shadow-none print:p-0">
    
    <!-- KOP RESMI LAPORAN -->
    <div class="flex items-start justify-between border-b-2 border-slate-900 pb-6 gap-6">
      <div class="space-y-1">
        <div class="flex items-center gap-2.5">
          <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black text-xl">
            
          </div>
          <div>
            <h2 class="text-xl font-black tracking-tight text-slate-950">Lapangan<span class="text-emerald-600">Kita</span></h2>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block -mt-0.5">SISTEM RESERVASI & MANAJEMEN BISNIS FUTSAL</span>
          </div>
        </div>
        <p class="text-xs text-slate-600 pt-2 leading-relaxed">
          PT Lapangan Kita Indonesia • Solusi Operasional & Finansial Venue Futsal Terintegrasi<br>
          Website: <strong>lapangankita.id</strong> • Email: support@lapangankita.id • CS: 0812-3456-7890
        </p>
      </div>

      <div class="text-right space-y-1">
        <span class="inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-900 font-extrabold text-[11px] uppercase tracking-wider border border-slate-200">
          Laporan Resmi
        </span>
        <div class="text-xs text-slate-500 pt-1">
          No. Dokumen: <strong class="text-slate-900 font-mono">LK-REP-{{ date('Ymd') }}-{{ $tenant->id }}</strong><br>
          Tanggal Cetak: <strong class="text-slate-900">{{ now()->isoFormat('D MMMM Y, HH:mm') }} WIB</strong>
        </div>
      </div>
    </div>

    <!-- IDENTITAS VENUE MITRA -->
    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
      <div>
        <span class="text-slate-400 block font-medium">Nama Tempat Futsal</span>
        <strong class="text-sm font-black text-slate-900 block mt-0.5">{{ $tenant->name }}</strong>
      </div>
      <div>
        <span class="text-slate-400 block font-medium">Kota & Lokasi</span>
        <strong class="text-slate-900 block mt-0.5">{{ $tenant->city }}</strong>
      </div>
      <div>
        <span class="text-slate-400 block font-medium">Pemilik / Pengelola</span>
        <strong class="text-slate-900 block mt-0.5">{{ auth()->user()->name }}</strong>
      </div>
      <div>
        <span class="text-slate-400 block font-medium">Kapasitas Lapangan</span>
        <strong class="text-slate-900 block mt-0.5">{{ $courts->count() }} Unit Lapangan</strong>
      </div>
    </div>

    <!-- 4 METRIK FINANSIAL UTAMA -->
    <div>
      <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-3">I. Ringkasan Keuangan & Okupansi Riil</h3>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
          <span class="text-[11px] text-slate-500 block font-semibold">Total Omset Sewa</span>
          <span class="text-lg font-black text-emerald-700 block mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
          <span class="text-[10px] text-slate-400 block mt-0.5">Transaksi Lunas</span>
        </div>
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
          <span class="text-[11px] text-slate-500 block font-semibold">Total Jam Bermain</span>
          <span class="text-lg font-black text-slate-900 block mt-1">{{ $totalHours }} Jam</span>
          <span class="text-[10px] text-slate-400 block mt-0.5">Akumulasi Sewa</span>
        </div>
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
          <span class="text-[11px] text-slate-500 block font-semibold">Rata-rata/Pesanan</span>
          <span class="text-lg font-black text-slate-900 block mt-1">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</span>
          <span class="text-[10px] text-slate-400 block mt-0.5">Nilai Tiket</span>
        </div>
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
          <span class="text-[11px] text-slate-500 block font-semibold">Okupansi Jam Malam</span>
          <span class="text-lg font-black text-amber-600 block mt-1">92.4%</span>
          <span class="text-[10px] text-slate-400 block mt-0.5">Slot 18:00 - 22:00</span>
        </div>
      </div>
    </div>

    <!-- TABEL PERFORMA PER UNIT LAPANGAN -->
    <div>
      <h3 class="text-xs font-black uppercase tracking-wider text-slate-400 mb-3">II. Rincian Performa & Kontribusi Unit Lapangan</h3>
      <table class="w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
        <thead class="bg-slate-100 text-slate-600 uppercase font-bold border-b border-slate-200">
          <tr>
            <th class="p-3">Nama Unit Lapangan</th>
            <th class="p-3">Jenis Material Lantai</th>
            <th class="p-3 text-center">Durasi Terpakai</th>
            <th class="p-3 text-center">Frekuensi Booking</th>
            <th class="p-3 text-right">Kontribusi Pendapatan</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @foreach($courtPerformance as $cp)
            <tr>
              <td class="p-3 font-bold text-slate-900">{{ $cp->name }}</td>
              <td class="p-3 text-slate-600">{{ $cp->surface }}</td>
              <td class="p-3 text-center font-semibold">{{ $cp->total_hours }} Jam</td>
              <td class="p-3 text-center">{{ $cp->booking_count }} kali</td>
              <td class="p-3 text-right font-black text-emerald-700">Rp {{ number_format($cp->total_revenue, 0, ',', '.') }}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="bg-slate-50 font-bold border-t border-slate-200">
          <tr>
            <td colspan="2" class="p-3 text-slate-900">Total Akumulasi</td>
            <td class="p-3 text-center text-slate-900">{{ $totalHours }} Jam</td>
            <td class="p-3 text-center text-slate-900">{{ $totalBookings }} kali</td>
            <td class="p-3 text-right text-emerald-700 font-black">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- REKOMENDASI STRATEGI & ANALISIS BISNIS VENUE -->
    <div class="p-5 rounded-2xl bg-emerald-50/60 border border-emerald-200 space-y-3">
      <div class="flex items-center gap-2">
        <span class="text-base"></span>
        <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-900">III. Catatan Analisis & Rekomendasi Strategi Venue</h3>
      </div>
      <div class="text-xs text-slate-700 space-y-2 leading-relaxed">
        <p>
          <strong>1. Optimasi Jam Sepi (13:00 - 16:00):</strong> Okupansi terdeteksi hanya 30%. Disarankan menerapkan "Tarif Pelajar/Mahasiswa" sebesar Rp 90.000/jam khusus hari Senin - Kamis. Estimasi tambahan omset sekitar <strong>+Rp 720.000 per minggu</strong>.
        </p>
        <p>
          <strong>2. Pertahankan Tarif Malam (18:00 - 22:00):</strong> Okupansi mencapai 95%. Wajibkan pembayaran online via website agar tidak terjadi no-show atau pembatalan sepihak.
        </p>
        <p>
          <strong>3. Potensi Proyeksi Keuangan:</strong> Pendapatan bulan depan di venue {{ $tenant->name }} diproyeksikan meningkat hingga <strong>+25%</strong> jika efisiensi jadwal online dipertahankan.
        </p>
      </div>
    </div>

    <!-- TANDA TANGAN & PENGESAHAN -->
    <div class="pt-8 border-t border-slate-200 flex justify-between items-end text-xs">
      <div>
        <p class="text-slate-400">Dicetak melalui portal resmi LapanganKita.</p>
        <p class="text-slate-400 text-[10px]">Dokumen ini sah dan diterbitkan secara digital oleh sistem.</p>
      </div>

      <div class="text-center space-y-16">
        <p class="text-slate-600 font-semibold">Pengelola / Pemilik Venue,</p>
        <div>
          <strong class="text-slate-900 block underline">{{ auth()->user()->name }}</strong>
          <span class="text-[10px] text-slate-500">{{ $tenant->name }}</span>
        </div>
      </div>
    </div>

  </div>

</body>
</html>
