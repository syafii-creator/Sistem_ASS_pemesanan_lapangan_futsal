<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice Patungan Sparing 50:50 - {{ $payment->transaction_code }}</title>
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
      <div>
        <h1 class="text-sm font-bold">Bukti Pembayaran Patungan Sparing (50:50)</h1>
        <p class="text-[11px] text-slate-400">Tunjukkan bukti bayar patungan ini kepada wasit / pengelola venue saat tiba di lokasi.</p>
      </div>
    </div>
    <div class="flex items-center gap-2.5">
      <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-black text-xs transition shadow flex items-center gap-1.5 cursor-pointer">
        <span>Cetak / Unduh PDF</span>
      </button>
      <button onclick="window.close()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs transition cursor-pointer">
        Tutup
      </button>
    </div>
  </div>

  <!-- INVOICE CONTAINER -->
  <div class="max-w-2xl mx-auto bg-white border border-slate-200 rounded-3xl p-8 shadow-md space-y-6 print:border-none print:shadow-none print:p-0">
    
    <!-- HEADER STRUK & INVOICE -->
    <div class="flex items-center justify-between border-b-2 border-slate-200 pb-5">
      <div class="flex items-center gap-3.5">
        @if(!empty($booking->tenant->logo_image))
          <img src="{{ $booking->tenant->logo_image }}" 
               alt="{{ $booking->tenant->name ?? 'Venue Logo' }}" 
               class="w-14 h-14 rounded-2xl object-cover border border-slate-200 shadow-sm shrink-0">
        @else
          <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-red-600 to-rose-500 text-white flex items-center justify-center font-black text-2xl shadow-sm shrink-0">
            {{ strtoupper(substr($booking->tenant->name ?? 'S', 0, 1)) }}
          </div>
        @endif
        
        <div>
          <div class="flex items-center gap-2">
            <h2 class="text-xl font-black text-slate-900 leading-tight">
              {{ $booking->tenant->name ?? 'Venue Futsal Arena' }}
            </h2>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
              Mitra Resmi
            </span>
          </div>
          <span class="text-[11px] font-black text-red-600 tracking-wider block mt-0.5">
            INVOICE PATUNGAN SPARING 50:50 RESMI
          </span>
          <span class="text-[11px] text-slate-500 block">
            {{ $booking->tenant->address ?? '-' }} ({{ $booking->tenant->city ?? 'Indonesia' }})
          </span>
        </div>
      </div>
      
      <div class="text-right space-y-1">
        @if($payment->status === 'settlement')
          <span class="inline-block px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
             LUNAS (PORSI 50%)
          </span>
        @else
          <span class="inline-block px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-300">
             MENUNGGU PEMBAYARAN
          </span>
        @endif
        <span class="font-mono text-xs font-bold text-slate-600 block">
          {{ $payment->transaction_code }}
        </span>
        <span class="text-[10px] text-slate-400 block">Metode: {{ strtoupper($payment->payment_type) }}</span>
      </div>
    </div>

    <!-- DETAIL MATCH SPARING -->
    <div class="p-4 rounded-2xl bg-gradient-to-r from-red-50 via-rose-50 to-amber-50 border border-red-100 space-y-3">
      <div class="flex items-center justify-between">
        <span class="text-[10px] font-black uppercase tracking-wider text-red-700">Pertandingan Sparing Futsal</span>
        <span class="text-xs font-bold px-2 py-0.5 rounded bg-white text-slate-700 border border-red-200">
          Aturan: 50% Host : 50% Lawan (Fair Play)
        </span>
      </div>

      <div class="flex items-center justify-between py-2 border-y border-red-200/60 text-center">
        <div class="w-5/12">
          <span class="text-[10px] text-slate-400 font-bold block uppercase">Tim Penyelenggara (Host)</span>
          <span class="text-base font-black text-slate-900 block mt-0.5">
            {{ $challenge->host_team_name ?? ($booking->user->name ?? 'Tim Host') }}
          </span>
          <span class="text-[11px] font-semibold text-emerald-600">Porsi 50% Lunas</span>
        </div>
        <div class="w-2/12 flex flex-col items-center justify-center">
          <span class="w-8 h-8 rounded-full bg-red-600 text-white font-black text-xs flex items-center justify-center shadow">VS</span>
        </div>
        <div class="w-5/12">
          <span class="text-[10px] text-slate-400 font-bold block uppercase">Tim Penantang (Tamu)</span>
          <span class="text-base font-black text-slate-900 block mt-0.5">
            {{ $challenge->challenger_team_name ?? '(Menunggu Lawan)' }}
          </span>
          <span class="text-[11px] font-semibold {{ $challenge && $challenge->status === 'matched' ? 'text-emerald-600' : 'text-amber-600' }}">
            {{ $challenge && $challenge->status === 'matched' ? 'Porsi 50% Lunas' : 'Menunggu Lawan Bayar' }}
          </span>
        </div>
      </div>
    </div>

    <!-- JADWAL & LOKASI -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
      <div>
        <span class="text-[10px] text-slate-400 uppercase font-bold block">Lapangan</span>
        <span class="font-extrabold text-slate-900 block mt-0.5">{{ $booking->court->name ?? 'Lapangan Futsal' }}</span>
        <span class="text-[10px] text-slate-500">{{ $booking->court->surface_type ?? 'Vinyl' }}</span>
      </div>
      <div>
        <span class="text-[10px] text-slate-400 uppercase font-bold block">Tanggal Main</span>
        <span class="font-extrabold text-slate-900 block mt-0.5">
          {{ \Carbon\Carbon::parse($booking->booking_date)->format('d F Y') }}
        </span>
      </div>
      <div>
        <span class="text-[10px] text-slate-400 uppercase font-bold block">Jam Tanding</span>
        <span class="font-extrabold text-red-600 block mt-0.5">
          {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB
        </span>
      </div>
      <div>
        <span class="text-[10px] text-slate-400 uppercase font-bold block">Status Match</span>
        <span class="font-extrabold {{ $challenge && $challenge->status === 'matched' ? 'text-emerald-600' : 'text-blue-600' }} block mt-0.5">
          {{ $challenge && $challenge->status === 'matched' ? 'Lunas 100% (Terkunci)' : 'Terkunci (Menunggu Lawan)' }}
        </span>
      </div>
    </div>

    <!-- RINCIAN BIAYA & PATUNGAN 50:50 -->
    <div class="space-y-3">
      <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Rincian Pembagian Biaya Sewa (50 : 50)</h3>
      <table class="w-full text-xs">
        <thead>
          <tr class="border-b border-slate-200 text-slate-400">
            <th class="text-left py-2 font-bold uppercase text-[10px]">Komponen Biaya</th>
            <th class="text-center py-2 font-bold uppercase text-[10px]">Keterangan</th>
            <th class="text-right py-2 font-bold uppercase text-[10px]">Nominal</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr>
            <td class="py-2.5 font-bold text-slate-800">Biaya Sewa Lapangan Penuh (100%)</td>
            <td class="py-2.5 text-center text-slate-500">{{ $booking->duration_hours }} Jam Sesi Tanding</td>
            <td class="py-2.5 text-right font-medium text-slate-700">Rp {{ number_format($booking->court_price, 0, ',', '.') }}</td>
          </tr>
          <tr>
            <td class="py-2.5 font-bold text-slate-800">Biaya Layanan & Pemeliharaan</td>
            <td class="py-2.5 text-center text-slate-500">Platform LapanganKita</td>
            <td class="py-2.5 text-right font-medium text-slate-700">Rp {{ number_format($booking->service_fee, 0, ',', '.') }}</td>
          </tr>
          <tr class="bg-slate-50 font-bold">
            <td class="py-2.5 px-2 text-slate-700">Total Biaya Lapangan Keseluruhan</td>
            <td class="py-2.5 text-center text-slate-500">100% Penuh</td>
            <td class="py-2.5 text-right px-2 text-slate-900">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
          </tr>
          <tr class="bg-red-50/60 font-black">
            <td class="py-3 px-2 text-red-900">
              Porsi Bayar Anda (50% Patungan Resmi)
              <span class="block text-[10px] font-semibold text-red-600">
                Dibayar oleh: {{ $payment->payload['paid_by'] ?? ($user->name ?? 'Pemain') }} ({{ $isHost ? 'Penyelenggara Host' : 'Tim Penantang' }})
              </span>
            </td>
            <td class="py-3 text-center text-red-700 font-bold">50% FAIR PLAY</td>
            <td class="py-3 text-right px-2 text-red-700 text-sm">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- STEMPEL DAN VERIFIKASI -->
    <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
      <div class="space-y-1 text-center sm:text-left">
        <span class="text-[10px] font-bold text-slate-400 block uppercase">Verifikasi Sistem Pembayaran</span>
        <span class="font-mono text-[11px] text-slate-700 block">Waktu Bayar: {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y H:i:s') . ' WIB' : '-' }}</span>
        <span class="text-[10px] text-emerald-600 font-semibold block">Tervalidasi Resmi via Midtrans Payment Gateway</span>
      </div>

      <div class="text-center sm:text-right">
        <div class="inline-block px-4 py-2 border-2 border-emerald-600 rounded-xl text-emerald-700 font-black tracking-widest uppercase text-xs rotate-[-3deg]">
          VERIFIED 50% PAID
        </div>
      </div>
    </div>

    <!-- FOOTER NOTE -->
    <div class="p-3 rounded-xl bg-slate-50 text-[10px] text-slate-500 text-center leading-relaxed">
      Harap hadir di lokasi venue sekurang-kurangnya 15 menit sebelum waktu kick-off. Porsi 50% lawan dilunasi secara otomatis melalui sistem saat menerima tantangan di lobby platform.
    </div>

  </div>

</body>
</html>
