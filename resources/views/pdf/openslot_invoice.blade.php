<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice Patungan Mabar - {{ $member->payment->transaction_code ?? 'LK-SLOT-' . $member->id }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    @media print {
      body {
        background: white !important;
        color: black !important;
        padding: 0 !important;
      }
      .no-print {
        display: none !important;
      }
      .print-shadow-none {
        box-shadow: none !important;
        border: none !important;
      }
      @page {
        margin: 1.2cm;
        size: A4 portrait;
      }
    }
  </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased p-4 sm:p-8">

  <!-- Floating Print & Action Bar (Hidden on print) -->
  <div class="max-w-2xl mx-auto mb-6 flex items-center justify-between gap-4 p-4 rounded-2xl bg-slate-900 text-white shadow-xl no-print">
    <div class="flex items-center gap-3">
      <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-black text-sm">
        ✓
      </div>
      <div>
        <h1 class="text-sm font-bold">Bukti Pembayaran Patungan Mabar</h1>
        <p class="text-[11px] text-slate-400">Tunjukkan bukti bayar resmi ini kepada Host / Kasir saat tiba di venue.</p>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs transition shadow flex items-center gap-1.5 cursor-pointer">
        <span>Cetak / Unduh PDF</span>
      </button>
      <button onclick="window.close()" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs transition cursor-pointer">
        Tutup
      </button>
    </div>
  </div>

  @php
    $tenant = $member->room->booking->tenant ?? null;
    $court = $member->room->booking->court ?? null;
    $booking = $member->room->booking ?? null;
    $room = $member->room ?? null;
    $payment = $member->payment ?? null;
    $isHost = $room && ($member->user_id === $room->host_user_id);
    $totalAmount = $payment->amount ?? ($member->total_paid ?? ($room->price_per_person * ($member->slots_count ?? 1)));
    $slotsCount = $member->slots_count ?? ($payment->payload['slot_count'] ?? ($payment->payload['host_slots'] ?? 1));
    $paymentType = strtoupper($payment->payment_type ?? 'QRIS Instan');
    $trxCode = $payment->transaction_code ?? ('LK-INV-SLOT-' . $member->id);
    $paidAt = $payment?->paid_at ? \Carbon\Carbon::parse($payment->paid_at) : ($member->created_at ? \Carbon\Carbon::parse($member->created_at) : now());
  @endphp

  <!-- INVOICE CONTAINER -->
  <div class="max-w-2xl mx-auto bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-md space-y-6 print:border-none print:shadow-none print:p-0">
    
    <!-- 1. HEADER RESMI VENUE & STATUS PEMBAYARAN -->
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 border-b-2 border-slate-100 pb-5">
      <!-- Info Venue -->
      <div class="flex items-start gap-3.5">
        @if(!empty($tenant->logo_image))
          <img src="{{ $tenant->logo_image }}" 
               alt="{{ $tenant->name ?? 'Venue Logo' }}" 
               class="w-14 h-14 rounded-2xl object-cover border border-slate-200 shadow-sm shrink-0">
        @else
          <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center font-black text-2xl shadow-sm shrink-0">
            {{ strtoupper(substr($tenant->name ?? 'F', 0, 1)) }}
          </div>
        @endif
        
        <div class="space-y-0.5">
          <div class="flex items-center gap-2 flex-wrap">
            <h2 class="text-lg sm:text-xl font-black text-slate-900 leading-tight">
              {{ $tenant->name ?? 'Champion Futsal Arena' }}
            </h2>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
              Mitra Resmi
            </span>
          </div>
          <span class="text-[11px] font-black text-emerald-700 tracking-wider block">
            INVOICE &amp; BUKTI PATUNGAN MABAR RESMI
          </span>
          <p class="text-[11px] text-slate-500 leading-snug">
            {{ $tenant->address ?? 'Jl. Venue Futsal' }} ({{ $tenant->city ?? 'Indonesia' }})
            @if(!empty($tenant->phone_wa))
              • WA: {{ $tenant->phone_wa }}
            @endif
          </p>
        </div>
      </div>
      
      <!-- Status Badge & Transaksi (Aligned Right) -->
      <div class="text-left sm:text-right space-y-1.5 shrink-0 pt-1 sm:pt-0 border-t sm:border-t-0 border-slate-100">
        @if($member->status === 'paid' || optional($payment)->status === 'settlement')
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-sm">
            <span>●</span>
            <span>LUNAS (PAID)</span>
          </div>
        @elseif($member->status === 'pending' || optional($payment)->status === 'pending')
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-800 border border-amber-300">
            <span>●</span>
            <span>DITUNDA (PENDING)</span>
          </div>
        @elseif($member->status === 'refunded')
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-blue-100 text-blue-800 border border-blue-300">
            <span>●</span>
            <span>DI-REFUND</span>
          </div>
        @else
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-800 border border-rose-300">
            <span>●</span>
            <span>{{ strtoupper($member->status) }}</span>
          </div>
        @endif

        <span class="font-mono text-xs font-bold text-slate-700 block tracking-tight">
          {{ $trxCode }}
        </span>
        <div class="text-[10px] text-slate-400 space-y-0.5">
          <span>Metode: <strong class="text-slate-600">{{ $paymentType }}</strong></span>
          <span class="block">{{ $paidAt->isoFormat('D MMM Y, HH:mm') }} WIB</span>
        </div>
      </div>
    </div>

    <!-- 2. DETAIL ROOM MABAR & JADWAL TANDING -->
    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/90 space-y-3.5">
      <div class="flex items-center justify-between gap-2 flex-wrap">
        <div>
          <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Room Mabar Yang Diikuti</span>
          <h3 class="text-base font-black text-slate-900 mt-0.5">{{ $room->title ?? 'Mabar Open Slot' }}</h3>
        </div>
        <span class="px-2.5 py-1 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-700 font-black text-xs">
          Room #OS-{{ 800 + ($room->id ?? 1) }}
        </span>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-3 border-t border-slate-200/80 text-xs">
        <div>
          <span class="text-slate-400 block font-medium">Nama Peserta Terdaftar</span>
          <div class="flex items-center gap-1.5 mt-0.5">
            <strong class="text-slate-900 font-extrabold text-sm">{{ $member->user->name ?? 'Pemain' }}</strong>
            @if($isHost)
              <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 border border-amber-300">
                Host Room
              </span>
            @else
              <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700">
                Peserta Mabar
              </span>
            @endif
          </div>
          <span class="text-[11px] text-slate-500 block">{{ $member->user->email ?? '-' }}</span>
        </div>

        <div>
          <span class="text-slate-400 block font-medium">Unit Lapangan</span>
          <strong class="text-slate-900 font-extrabold block text-sm mt-0.5">{{ $court->name ?? 'Lapangan Utama' }}</strong>
          <span class="text-[11px] text-slate-500 block">{{ $court->surface_type ?? 'Vinyl / Interlock' }}</span>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-3 border-t border-slate-200/80 text-xs">
        <div>
          <span class="text-slate-400 block font-medium">Tanggal Main</span>
          <strong class="text-sm font-black text-emerald-700 block mt-0.5">
            {{ \Carbon\Carbon::parse($booking->booking_date ?? now())->isoFormat('dddd, D MMMM Y') }}
          </strong>
        </div>
        <div>
          <span class="text-slate-400 block font-medium">Jam Bermain (Kick-Off)</span>
          <strong class="text-sm font-black text-slate-900 block mt-0.5">
            {{ substr($booking->start_time ?? '19:00', 0, 5) }} - {{ substr($booking->end_time ?? '21:00', 0, 5) }} WIB
          </strong>
          <span class="text-[11px] text-slate-500">Durasi: {{ $booking->duration_hours ?? 2 }} Jam</span>
        </div>
      </div>
    </div>

    <!-- 3. RINCIAN BIAYA PATUNGAN & KOTAK VALIDASI TIKET -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 rounded-2xl bg-emerald-50/60 border border-emerald-200 gap-5">
      <div class="space-y-1.5 flex-1">
        <span class="text-[10px] text-emerald-800 uppercase font-black tracking-wider block">
          Total Biaya Patungan Anda
        </span>
        <div class="text-2xl sm:text-3xl font-black text-emerald-700 tracking-tight">
          Rp {{ number_format($totalAmount, 0, ',', '.') }}
        </div>
        <div class="text-xs text-slate-700 font-bold flex items-center gap-1.5 flex-wrap">
          <span>Rincian:</span>
          <span class="px-2 py-0.5 rounded-md bg-white border border-emerald-200 text-emerald-800 font-extrabold">
            {{ $slotsCount }} Slot / Orang × Rp {{ number_format($room->price_per_person ?? 25000, 0, ',', '.') }}
          </span>
        </div>
        @if(!empty($member->notes))
          <div class="text-[11px] text-slate-600 bg-white/90 px-2.5 py-1 rounded-lg border border-emerald-200 inline-block mt-1">
            Catatan / Rombongan: <strong>{{ $member->notes }}</strong>
          </div>
        @endif
        <p class="text-[10px] text-slate-500 pt-1">
          Status Pembayaran: <strong class="text-emerald-700">Lunas Terverifikasi</strong> • Transaksi ID: {{ $trxCode }}
        </p>
      </div>

      <!-- Kotak Simulasi Validasi QR / Barcode Tiket -->
      <div class="p-3.5 bg-white border-2 border-emerald-500/30 rounded-2xl text-center shrink-0 shadow-sm flex flex-col items-center justify-center">
        <!-- Visual Barcode Pattern SVG -->
        <svg class="w-24 h-12" viewBox="0 0 100 40" fill="currentColor">
          <rect x="0" y="0" width="3" height="32" class="text-slate-900" />
          <rect x="5" y="0" width="2" height="32" class="text-slate-900" />
          <rect x="9" y="0" width="4" height="32" class="text-slate-900" />
          <rect x="15" y="0" width="1" height="32" class="text-slate-900" />
          <rect x="18" y="0" width="5" height="32" class="text-slate-900" />
          <rect x="25" y="0" width="2" height="32" class="text-slate-900" />
          <rect x="29" y="0" width="3" height="32" class="text-slate-900" />
          <rect x="34" y="0" width="1" height="32" class="text-slate-900" />
          <rect x="37" y="0" width="6" height="32" class="text-slate-900" />
          <rect x="45" y="0" width="2" height="32" class="text-slate-900" />
          <rect x="49" y="0" width="3" height="32" class="text-slate-900" />
          <rect x="54" y="0" width="4" height="32" class="text-slate-900" />
          <rect x="60" y="0" width="2" height="32" class="text-slate-900" />
          <rect x="64" y="0" width="5" height="32" class="text-slate-900" />
          <rect x="71" y="0" width="1" height="32" class="text-slate-900" />
          <rect x="74" y="0" width="4" height="32" class="text-slate-900" />
          <rect x="80" y="0" width="2" height="32" class="text-slate-900" />
          <rect x="84" y="0" width="5" height="32" class="text-slate-900" />
          <rect x="91" y="0" width="3" height="32" class="text-slate-900" />
          <rect x="96" y="0" width="3" height="32" class="text-slate-900" />
        </svg>
        <span class="text-[9px] font-mono font-black text-slate-700 block mt-1 tracking-wider uppercase">
          [SLOT VALID #{{ $member->id }}]
        </span>
        <span class="text-[8px] font-mono text-slate-400 block mt-0.5">
          {{ substr($trxCode, -8) }}
        </span>
      </div>
    </div>

    <!-- 4. PETUNJUK & KETENTUAN PERTANDINGAN -->
    <div class="text-[11px] text-slate-500 space-y-1.5 pt-2 border-t border-slate-200">
      <strong class="text-slate-700 font-bold block">Petunjuk &amp; Ketentuan Mabar Komunitas:</strong>
      <ul class="list-disc list-inside space-y-1 text-slate-600">
        <li>Tunjukkan bukti invoice elektronik resmi ini kepada Host Room atau Kasir Venue saat tiba di lokasi.</li>
        <li>Wajib hadir di venue setidaknya <strong>15 menit sebelum kick-off</strong> dimulai.</li>
        <li>Wajib menggunakan sepatu futsal (sol karet tanpa pull lapangan besar) dan perlengkapan olahraga standar.</li>
        <li>Pembayaran patungan bersifat resmi dan final guna menjamin komitmen kehadiran pemain.</li>
      </ul>
    </div>

    <!-- 5. FOOTER RESMI & CO-BRANDING -->
    <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-2 text-[10px] text-slate-400">
      <div class="flex items-center gap-1">
        <span>Struk diterbitkan langsung via sistem resmi</span>
        <strong class="text-slate-600 font-bold">{{ $tenant->name ?? 'Venue Mitra' }}</strong>
      </div>
      <div class="flex items-center gap-1">
        <span>Didukung oleh:</span>
        <strong class="text-slate-700 font-bold tracking-tight">Lapangan<span class="text-emerald-600">Kita</span> SaaS</strong>
      </div>
    </div>

  </div>

</body>
</html>
