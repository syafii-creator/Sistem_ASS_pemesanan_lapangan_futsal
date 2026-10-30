<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Tiket Booking Futsal - {{ $booking->booking_code }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @media print {
      body {
        background: white !important;
        color: black !important;
      }
      .no-print {
        display: none !important;
      }
      @page {
        margin: 1.5cm;
        size: A4 portrait;
      }
    }
  </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased p-4 sm:p-8">

  <!-- Floating Print Bar -->
  <div class="max-w-2xl mx-auto mb-6 flex items-center justify-between gap-4 p-4 rounded-2xl bg-slate-900 text-white shadow-lg no-print">
    <div class="flex items-center gap-2">
      <span class="text-xl"></span>
      <div>
        <h1 class="text-sm font-bold">E-Tiket Reservasi Lapangan Futsal</h1>
        <p class="text-[11px] text-slate-400">Tunjukkan bukti PDF / QR ini kepada kasir saat tiba di lokasi.</p>
      </div>
    </div>
    <div class="flex items-center gap-2.5">
      <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs transition shadow flex items-center gap-1.5 cursor-pointer">
        <span> Cetak / Unduh PDF</span>
      </button>
      <button onclick="window.close()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs transition cursor-pointer">
        Tutup
      </button>
    </div>
  </div>

  <!-- TICKET CONTAINER -->
  <div class="max-w-2xl mx-auto bg-white border border-slate-200 rounded-3xl p-8 shadow-md space-y-6 print:border-none print:shadow-none print:p-0">
    
    <!-- HEADER STRUK & E-TIKET SESUAI IDENTITAS TENANT -->
    <div class="flex items-center justify-between border-b-2 border-slate-200 pb-5">
      <div class="flex items-center gap-3.5">
        @if(!empty($booking->tenant->logo_image))
          <!-- Logo Kustom Resmi Tenant -->
          <img src="{{ $booking->tenant->logo_image }}" 
               alt="{{ $booking->tenant->name ?? 'Venue Logo' }}" 
               class="w-14 h-14 rounded-2xl object-cover border border-slate-200 shadow-sm shrink-0">
        @else
          <!-- Fallback Badge Elegan jika Tenant belum upload logo khusus -->
          <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center font-black text-2xl shadow-sm shrink-0">
            {{ strtoupper(substr($booking->tenant->name ?? 'F', 0, 1)) }}
          </div>
        @endif
        
        <div>
          <div class="flex items-center gap-2">
            <h2 class="text-xl font-black text-slate-900 leading-tight">
              {{ $booking->tenant->name ?? 'Venue Futsal' }}
            </h2>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
              Mitra Resmi
            </span>
          </div>
          <span class="text-[11px] font-black text-emerald-700 tracking-wider block mt-0.5">
            STRUK PEMBAYARAN & BUKTI RESERVASI RESMI
          </span>
          <span class="text-[11px] text-slate-500 block">
            {{ $booking->tenant->address ?? '-' }} ({{ $booking->tenant->city ?? 'Indonesia' }})
            @if(!empty($booking->tenant->phone_wa))
              • Telp/WA: {{ $booking->tenant->phone_wa }}
            @endif
          </span>
        </div>
      </div>
      
      <div class="text-right space-y-1">
        @if($booking->status === 'paid')
          <span class="inline-block px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
             LUNAS (PAID)
          </span>
        @elseif($booking->status === 'pending')
          <span class="inline-block px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-300">
             DITUNDA (BELUM LUNAS)
          </span>
        @else
          <span class="inline-block px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-800 border border-rose-300">
             {{ strtoupper($booking->status) }}
          </span>
        @endif
        <span class="font-mono text-xs font-bold text-slate-600 block">No: #{{ $booking->booking_code }}</span>
        <span class="text-[9px] text-slate-400 block">Platform: LapanganKita SaaS</span>
      </div>
    </div>

    <!-- DETAIL LAPANGAN & JADWAL -->
    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
      <div>
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tempat Futsal & Alamat</span>
        <h3 class="text-base font-black text-slate-900 mt-0.5">{{ $booking->tenant->name ?? 'Venue Futsal' }}</h3>
        <p class="text-xs text-slate-600">{{ $booking->tenant->address ?? '-' }} ({{ $booking->tenant->city ?? 'Kota' }})</p>
      </div>

      <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-200 text-xs">
        <div>
          <span class="text-slate-400 block font-medium">Unit Lapangan</span>
          <strong class="text-slate-900 font-bold block">{{ $booking->court->name ?? 'Lapangan A' }}</strong>
          <span class="text-[11px] text-slate-500">{{ $booking->court->surface_type ?? 'Vinyl' }}</span>
        </div>
        <div>
          <span class="text-slate-400 block font-medium">Nama Pemesan</span>
          <strong class="text-slate-900 font-bold block">{{ $booking->user->name ?? 'Pemain' }}</strong>
          <span class="text-[11px] text-slate-500">{{ $booking->user->email ?? '-' }}</span>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-200 text-xs">
        <div>
          <span class="text-slate-400 block font-medium">Tanggal Main</span>
          <strong class="text-sm font-black text-emerald-700 block">{{ \Carbon\Carbon::parse($booking->booking_date)->isoFormat('dddd, D MMMM Y') }}</strong>
        </div>
        <div>
          <span class="text-slate-400 block font-medium">Jam Bermain</span>
          <strong class="text-sm font-black text-slate-900 block">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</strong>
          <span class="text-[11px] text-slate-500">Durasi: {{ $booking->duration_hours }} Jam</span>
        </div>
      </div>
    </div>

    <!-- QR CODE & RINCIAN BIAYA -->
    <div class="flex items-center justify-between p-5 rounded-2xl bg-emerald-50/50 border border-emerald-200 gap-6">
      <div class="space-y-1">
        <span class="text-[10px] text-emerald-800 uppercase font-bold tracking-wider block">Total Biaya Pemesanan</span>
        <div class="text-2xl font-black text-emerald-700">
          Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
        </div>
        <p class="text-[11px] text-slate-500">
          Sewa: Rp {{ number_format($booking->court_price, 0, ',', '.') }} + Biaya Layanan: Rp {{ number_format($booking->service_fee, 0, ',', '.') }}
        </p>
      </div>

      <!-- Simulasi Visual QR Code Resmi -->
      <div class="p-3 bg-white border border-slate-300 rounded-xl text-center shrink-0">
        <div class="w-16 h-16 bg-slate-900 text-white flex items-center justify-center font-mono text-[9px] font-bold p-1 leading-none rounded">
          [QR VALID]
        </div>
        <span class="text-[9px] font-mono font-bold text-slate-500 block mt-1">{{ substr($booking->booking_code, -6) }}</span>
      </div>
    </div>

    <!-- PETUNJUK & PERATURAN -->
    <div class="text-[11px] text-slate-500 space-y-1 pt-2 border-t border-slate-200">
      <strong class="text-slate-700 block">Petunjuk Pemain:</strong>
      <ul class="list-disc list-inside space-y-0.5">
        <li>Harap tiba di lokasi setidaknya 15 menit sebelum jam kick-off dimulai.</li>
        <li>Wajib memakai sepatu futsal (sol karet tanpa pull sepakbola luar ruangan).</li>
        <li>Tunjukkan dokumen ini / layar ponsel Anda kepada staf kasir lapangan.</li>
      </ul>
    </div>

    <!-- FOOTER RESMI & CO-BRANDING -->
    <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-2 text-[10px] text-slate-400">
      <div class="flex items-center gap-1.5">
        <span>Struk diterbitkan langsung oleh <strong>{{ $booking->tenant->name ?? 'Mitra Venue' }}</strong></span>
      </div>
      <div class="flex items-center gap-1">
        <span>Sistem Reservasi & Tiket Resmi:</span>
        <strong class="text-slate-600 font-bold">Lapangan<span class="text-emerald-600">Kita</span> SaaS</strong>
      </div>
    </div>

  </div>

</body>
</html>
