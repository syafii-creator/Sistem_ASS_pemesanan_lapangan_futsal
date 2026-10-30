<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Faktur Invoice Langganan - LK-INV-{{ $subscription->id }}</title>
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
  <div class="max-w-3xl mx-auto mb-6 flex items-center justify-between gap-4 p-4 rounded-2xl bg-slate-900 text-white shadow-lg no-print">
    <div class="flex items-center gap-2">
      <span class="text-xl"></span>
      <div>
        <h1 class="text-sm font-bold">Faktur Resmi Langganan Software LapanganKita</h1>
        <p class="text-[11px] text-slate-400">Bukti pembayaran langganan SaaS aplikasi venue futsal.</p>
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

  <!-- INVOICE CONTAINER -->
  <div class="max-w-3xl mx-auto bg-white border border-slate-200 rounded-3xl p-8 sm:p-12 shadow-md space-y-8 print:border-none print:shadow-none print:p-0">
    
    <!-- KOP INVOICE -->
    <div class="flex items-start justify-between border-b border-slate-200 pb-6 gap-6">
      <div class="space-y-1">
        <div class="flex items-center gap-2">
          <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black">
            
          </div>
          <span class="text-xl font-black text-slate-900">Lapangan<span class="text-emerald-600">Kita</span></span>
        </div>
        <p class="text-xs text-slate-500 pt-1">
          PT Lapangan Kita Indonesia<br>
          NPWP: 01.345.678.9-012.000<br>
          Billing Support: finance@lapangankita.id
        </p>
      </div>

      <div class="text-right space-y-1">
        <span class="text-2xl font-black tracking-tight text-slate-900 block">FAKTUR / INVOICE</span>
        <span class="font-mono text-xs font-bold text-emerald-600 block">#LK-INV-{{ str_pad($subscription->id, 5, '0', STR_PAD_LEFT) }}-{{ date('Y') }}</span>
        <span class="text-xs text-slate-400 block">Tanggal: {{ optional($subscription->created_at)->format('d F Y') ?? now()->format('d F Y') }}</span>
      </div>
    </div>

    <!-- INFORMASI PELANGGAN & DETAIL TAGIHAN -->
    <div class="grid grid-cols-2 gap-6 text-xs">
      <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1">
        <span class="text-[10px] text-slate-400 uppercase font-bold block">Ditagihkan Kepada:</span>
        <strong class="text-sm font-black text-slate-900 block">{{ $tenant->name }}</strong>
        <p class="text-slate-600">
          Pemilik: {{ auth()->user()->name }}<br>
          Email: {{ auth()->user()->email }}<br>
          Kota: {{ $tenant->city }} • Telp: {{ $tenant->phone_wa }}
        </p>
      </div>

      <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-1">
        <span class="text-[10px] text-slate-400 uppercase font-bold block">Status Pembayaran:</span>
        <div class="pt-1 flex items-center gap-2">
          @if($subscription->status === 'trial')
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-300">
              Masa Uji Coba Gratis (7 Hari)
            </span>
          @else
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-300">
               LUNAS / RESMI AKTIF
            </span>
          @endif
        </div>
        <p class="text-slate-500 text-[11px] pt-1">
          Periode Aktif: <strong>{{ optional($subscription->start_date)->format('d M Y') }}</strong> s/d <strong>{{ optional($subscription->end_date)->format('d M Y') }}</strong>
        </p>
      </div>
    </div>

    <!-- ITEM TABLE -->
    <table class="w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
      <thead class="bg-slate-100 text-slate-600 uppercase font-bold border-b border-slate-200">
        <tr>
          <th class="p-3">Deskripsi Layanan</th>
          <th class="p-3 text-center">Durasi Siklus</th>
          <th class="p-3 text-center">Batas Lapangan</th>
          <th class="p-3 text-right">Nominal Tagihan</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-200">
        <tr>
          <td class="p-3">
            <strong class="text-slate-900 font-bold block">{{ $subscription->package->name ?? 'Paket Bulanan Pro' }}</strong>
            <span class="text-slate-500 text-[11px]">Akses penuh sistem booking online, modul analitik omset, dan matriks jadwal kasir.</span>
          </td>
          <td class="p-3 text-center font-medium capitalize">{{ $subscription->package->billing_cycle ?? 'Bulanan' }}</td>
          <td class="p-3 text-center">{{ $subscription->package->max_courts ?? 'Tanpa Batas' }} Unit</td>
          <td class="p-3 text-right font-black text-slate-900">
            Rp {{ number_format($subscription->price_paid, 0, ',', '.') }}
          </td>
        </tr>
      </tbody>
      <tfoot class="bg-slate-50 font-bold border-t border-slate-200 text-xs">
        <tr>
          <td colspan="3" class="p-3 text-right text-slate-600">Subtotal:</td>
          <td class="p-3 text-right text-slate-900">Rp {{ number_format($subscription->price_paid, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td colspan="3" class="p-3 text-right text-slate-600">PPN (0% Edukasi SaaS):</td>
          <td class="p-3 text-right text-slate-900">Rp 0</td>
        </tr>
        <tr class="text-sm">
          <td colspan="3" class="p-3 text-right font-black text-slate-900">TOTAL DIBAYAR:</td>
          <td class="p-3 text-right font-black text-emerald-600 text-base">Rp {{ number_format($subscription->price_paid, 0, ',', '.') }}</td>
        </tr>
      </tfoot>
    </table>

    <!-- FOOTER STEMPEL & VERIFIKASI -->
    <div class="pt-6 border-t border-slate-200 flex justify-between items-center text-xs">
      <div class="space-y-1">
        <span class="text-slate-400 block text-[10px]">VERIFIKASI SISTEM DIGITAL</span>
        <span class="font-mono text-emerald-600 font-bold">DIGITAL-SIGNATURE-VERIFIED-OK</span>
        <p class="text-slate-500 text-[11px]">Terima kasih telah mempercayakan bisnis futsal Anda kepada LapanganKita.</p>
      </div>

      <div class="text-center p-3 border-2 border-emerald-500/40 rounded-2xl bg-emerald-50/50">
        <span class="text-emerald-700 font-black text-xs block uppercase">STEMPEL RESMI DIGITAL</span>
        <span class="text-[10px] text-emerald-600 font-bold">LAPANGANKITA INDONESIA</span>
      </div>
    </div>

  </div>

</body>
</html>
