<!-- ========================================================================= -->
<!-- MIDTRANS SNAP SDK & UNIFIED PAYMENT GATEWAY MODAL                         -->
<!-- ========================================================================= -->
<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

<!-- MODAL SIMULATOR MIDTRANS SNAP (Digunakan saat Mock Sandbox / Demo Presentasi) -->
<div id="midtransSimulatorModal" class="fixed inset-0 z-[9999] bg-slate-950/80 backdrop-blur-md hidden items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl w-full max-w-md shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
    
    <!-- Top Header: Midtrans Official Style -->
    <div class="bg-[#002855] text-white p-5 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center font-black text-sm tracking-wider border border-white/20">
          <span class="text-[#00B4D8]">mid</span>trans
        </div>
        <div>
          <span class="text-[10px] font-bold text-slate-300 block uppercase tracking-wider">Merchant LapanganKita</span>
          <h4 class="text-sm font-black text-white" id="midtransModalMerchant">LapanganKita Futsal SaaS</h4>
        </div>
      </div>
      <button type="button" onclick="closeMidtransSimulator()" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center cursor-pointer transition">
        <i data-lucide="x" class="w-4 h-4"></i>
      </button>
    </div>

    <!-- Order Summary Bar -->
    <div class="bg-slate-50 dark:bg-slate-850 p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
      <div>
        <span class="text-[10px] text-slate-400 block font-medium">Order ID Midtrans:</span>
        <strong class="font-mono text-slate-700 dark:text-slate-300 font-bold" id="midtransModalOrderId">LK-TRX-XXXXXX</strong>
      </div>
      <div class="text-right">
        <span class="text-[10px] text-slate-400 block font-medium">Total Pembayaran:</span>
        <strong class="text-base font-black text-emerald-600 dark:text-emerald-400" id="midtransModalAmount">Rp 0</strong>
      </div>
    </div>

    <!-- Body / Payment Channels Selection -->
    <div class="p-6 space-y-4">
      <div>
        <label class="block text-xs font-black text-slate-900 dark:text-white mb-2">
          Pilih Metode Pembayaran Midtrans Snap:
        </label>
        
        <div class="space-y-2">
          <!-- QRIS (Gopay, ShopeePay, Dana, Semua Bank) -->
          <label class="flex items-center justify-between p-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 cursor-pointer hover:border-emerald-500 transition has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-500/10">
            <div class="flex items-center gap-3">
              <input type="radio" name="midtrans_channel" value="qris" checked onchange="onMidtransChannelChange('qris')" class="text-emerald-600 focus:ring-emerald-500">
              <div class="space-y-0.5">
                <span class="text-xs font-bold text-slate-900 dark:text-white block">QRIS / GoPay / ShopeePay</span>
                <span class="text-[10px] text-slate-500 block">Scan instan via e-wallet atau m-banking</span>
              </div>
            </div>
            <span class="text-[10px] font-black px-2 py-0.5 rounded bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">INSTAN</span>
          </label>

          <!-- Virtual Account BCA -->
          <label class="flex items-center justify-between p-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 cursor-pointer hover:border-blue-500 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-500/10">
            <div class="flex items-center gap-3">
              <input type="radio" name="midtrans_channel" value="bca_va" onchange="onMidtransChannelChange('bca_va')" class="text-blue-600 focus:ring-blue-500">
              <div class="space-y-0.5">
                <span class="text-xs font-bold text-slate-900 dark:text-white block">BCA Virtual Account</span>
                <span class="text-[10px] text-slate-500 block">BCA Mobile / myBCA / KlikBCA</span>
              </div>
            </div>
            <span class="text-[10px] font-bold text-slate-400">VA Otomatis</span>
          </label>

          <!-- Virtual Account Mandiri -->
          <label class="flex items-center justify-between p-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 cursor-pointer hover:border-amber-500 transition has-[:checked]:border-amber-500 has-[:checked]:bg-amber-500/10">
            <div class="flex items-center gap-3">
              <input type="radio" name="midtrans_channel" value="mandiri_va" onchange="onMidtransChannelChange('mandiri_va')" class="text-amber-600 focus:ring-amber-500">
              <div class="space-y-0.5">
                <span class="text-xs font-bold text-slate-900 dark:text-white block">Mandiri Bill Payment</span>
                <span class="text-[10px] text-slate-500 block">Livin' by Mandiri / ATM Mandiri</span>
              </div>
            </div>
            <span class="text-[10px] font-bold text-slate-400">VA Otomatis</span>
          </label>

          <!-- Virtual Account BRI / BNI -->
          <label class="flex items-center justify-between p-3 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 cursor-pointer hover:border-cyan-500 transition has-[:checked]:border-cyan-500 has-[:checked]:bg-cyan-500/10">
            <div class="flex items-center gap-3">
              <input type="radio" name="midtrans_channel" value="bni_bri_va" onchange="onMidtransChannelChange('bni_bri_va')" class="text-cyan-600 focus:ring-cyan-500">
              <div class="space-y-0.5">
                <span class="text-xs font-bold text-slate-900 dark:text-white block">BRI / BNI / Permata VA</span>
                <span class="text-[10px] text-slate-500 block">BRImo / BNI Mobile / ATM</span>
              </div>
            </div>
            <span class="text-[10px] font-bold text-slate-400">VA Otomatis</span>
          </label>
        </div>
      </div>

      <!-- DYNAMIC CODE DISPLAY BOX SESUAI CHANNEL TERPILIH -->
      <div id="midtransChannelDetailsBox" class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-750 space-y-3">
        
        <!-- 1. Detail QRIS -->
        <div id="detailPanelQris" class="text-center space-y-2">
          <div class="inline-block p-3 bg-white rounded-2xl shadow-sm border border-slate-200">
            <img id="midtransQrisImg" src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=LAPANGANKITA-MIDTRANS-QRIS" alt="QRIS" class="w-36 h-36 mx-auto rounded-xl object-contain">
          </div>
          <div class="space-y-1">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold text-[11px]">
              <i data-lucide="qr-code" class="w-3.5 h-3.5"></i> QRIS Dinamis Aktif
            </span>
            <p class="text-[11px] text-slate-500">Scan QRIS di atas via GoPay, BCA, ShopeePay, Dana, OVO, atau m-Banking</p>
          </div>
        </div>

        <!-- 2. Detail BCA VA -->
        <div id="detailPanelBca" class="hidden space-y-2.5">
          <div class="flex items-center justify-between">
            <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nomor Virtual Account BCA:</span>
            <span class="text-[10px] font-bold text-blue-600 bg-blue-500/10 px-2 py-0.5 rounded">BCA Sandbox</span>
          </div>
          <div class="p-3 bg-white dark:bg-slate-950 border border-blue-200 dark:border-blue-900/50 rounded-xl flex items-center justify-between shadow-sm">
            <div>
              <span class="text-[9px] text-slate-400 block font-medium">BCA VA Number:</span>
              <strong class="font-mono text-base font-black text-blue-600 dark:text-blue-400 tracking-wider" id="midtransBcaVaCode">7001224782001</strong>
            </div>
            <button type="button" onclick="copyMidtransCode('midtransBcaVaCode', this)" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs rounded-lg transition cursor-pointer flex items-center gap-1.5 shadow-sm">
              <i data-lucide="copy" class="w-3.5 h-3.5"></i>
              <span>Salin</span>
            </button>
          </div>
          <div class="p-2.5 rounded-lg bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/30 text-[10px] text-slate-600 dark:text-slate-300 leading-relaxed">
            <span class="font-bold text-blue-600 dark:text-blue-400">Cara Tes di Tab Simulator:</span> Buka tab <em>Midtrans Payment Simulator</em> &gt; pilih menu <strong>BCA Virtual Account</strong> &gt; tempel nomor VA di atas lalu klik <strong>Inquire &amp; Pay</strong>.
          </div>
        </div>

        <!-- 3. Detail Mandiri VA -->
        <div id="detailPanelMandiri" class="hidden space-y-2.5">
          <div class="flex items-center justify-between">
            <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">Mandiri Bill Payment:</span>
            <span class="text-[10px] font-bold text-amber-600 bg-amber-500/10 px-2 py-0.5 rounded">Mandiri Sandbox</span>
          </div>
          <div class="grid grid-cols-2 gap-2 text-xs">
            <div class="p-2.5 bg-white dark:bg-slate-950 border border-amber-200 dark:border-amber-900/50 rounded-xl">
              <span class="text-[9px] text-slate-400 block font-bold">Company Code:</span>
              <strong class="font-mono text-sm font-black text-slate-900 dark:text-white block mt-0.5" id="midtransMandiriCompany">70012</strong>
            </div>
            <div class="p-2.5 bg-white dark:bg-slate-950 border border-amber-200 dark:border-amber-900/50 rounded-xl flex items-center justify-between">
              <div>
                <span class="text-[9px] text-slate-400 block font-bold">Bill Key:</span>
                <strong class="font-mono text-sm font-black text-amber-600 dark:text-amber-400 block mt-0.5" id="midtransMandiriBillKey">24782001</strong>
              </div>
              <button type="button" onclick="copyMidtransCode('midtransMandiriBillKey', this)" class="px-2 py-1 bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-[10px] rounded transition cursor-pointer">
                Salin
              </button>
            </div>
          </div>
          <div class="p-2.5 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-100 dark:border-amber-900/30 text-[10px] text-slate-600 dark:text-slate-300 leading-relaxed">
            <span class="font-bold text-amber-600 dark:text-amber-400">Cara Tes di Tab Simulator:</span> Masukkan <strong>Company Code: 70012</strong> dan <strong>Bill Key</strong> ke tab <em>Midtrans Payment Simulator &gt; Mandiri Bill</em>.
          </div>
        </div>

        <!-- 4. Detail BNI / BRI VA -->
        <div id="detailPanelBniBri" class="hidden space-y-2.5">
          <div class="flex items-center justify-between">
            <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider">Virtual Account BRI / BNI:</span>
            <span class="text-[10px] font-bold text-cyan-600 bg-cyan-500/10 px-2 py-0.5 rounded">BRI &amp; BNI Sandbox</span>
          </div>
          <div class="space-y-2 text-xs">
            <div class="p-2.5 bg-white dark:bg-slate-950 border border-cyan-200 dark:border-cyan-900/50 rounded-xl flex items-center justify-between">
              <div>
                <span class="text-[9px] text-slate-400 block font-bold">Nomor BRIVA (Bank BRI):</span>
                <strong class="font-mono text-xs font-black text-cyan-600 dark:text-cyan-400" id="midtransBrivaCode">8888024782001</strong>
              </div>
              <button type="button" onclick="copyMidtransCode('midtransBrivaCode', this)" class="px-2.5 py-1 bg-cyan-600 hover:bg-cyan-500 text-white font-extrabold text-[10px] rounded-lg transition cursor-pointer flex items-center gap-1">
                <i data-lucide="copy" class="w-3 h-3"></i>
                <span>Salin BRI</span>
              </button>
            </div>
            <div class="p-2.5 bg-white dark:bg-slate-950 border border-cyan-200 dark:border-cyan-900/50 rounded-xl flex items-center justify-between">
              <div>
                <span class="text-[9px] text-slate-400 block font-bold">Nomor BNI VA (Bank BNI):</span>
                <strong class="font-mono text-xs font-black text-cyan-600 dark:text-cyan-400" id="midtransBniVaCode">9888024782001</strong>
              </div>
              <button type="button" onclick="copyMidtransCode('midtransBniVaCode', this)" class="px-2.5 py-1 bg-cyan-600 hover:bg-cyan-500 text-white font-extrabold text-[10px] rounded-lg transition cursor-pointer flex items-center gap-1">
                <i data-lucide="copy" class="w-3 h-3"></i>
                <span>Salin BNI</span>
              </button>
            </div>
          </div>
          <div class="p-2.5 rounded-lg bg-cyan-50 dark:bg-cyan-950/40 border border-cyan-100 dark:border-cyan-900/30 text-[10px] text-slate-600 dark:text-slate-300 leading-relaxed">
            <span class="font-bold text-cyan-600 dark:text-cyan-400">Cara Tes di Tab Simulator:</span> Buka tab <em>Midtrans Payment Simulator</em> &gt; pilih menu <strong>BRI / BNI VA</strong> &gt; tempel nomor VA di atas.
          </div>
        </div>

      </div>

      <!-- Action Button: Instant Pay / Callback Simulator -->
      <button type="button" id="btnConfirmMidtransSimulator" onclick="executeMidtransSimulation()" 
              class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-black text-xs sm:text-sm rounded-xl shadow-lg shadow-emerald-500/25 transition flex items-center justify-center gap-2 cursor-pointer active:scale-[0.99]">
        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
        <span>Bayar Sekarang (Konfirmasi Lunas)</span>
      </button>

      <p class="text-[10px] text-center text-slate-400">
        Terhubung dengan sistem callback Midtrans. Klik tombol di atas untuk menyelesaikan transaksi langsung tanpa perlu membuka tab simulator.
      </p>

      <button type="button" onclick="closeMidtransSimulator()" class="w-full py-1 text-center text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 font-bold cursor-pointer">
        Batalkan Pembayaran
      </button>
    </div>

  </div>
</div>

<script>
  let currentMidtransOrderId = null;

  /**
   * Fungsi Ganti Panel Channel Pembayaran (QRIS, BCA, Mandiri, BRI/BNI)
   */
  function onMidtransChannelChange(channel) {
    const panels = {
      'qris': document.getElementById('detailPanelQris'),
      'bca_va': document.getElementById('detailPanelBca'),
      'mandiri_va': document.getElementById('detailPanelMandiri'),
      'bni_bri_va': document.getElementById('detailPanelBniBri'),
    };

    Object.keys(panels).forEach(key => {
      const el = panels[key];
      if (el) {
        if (key === channel) {
          el.classList.remove('hidden');
        } else {
          el.classList.add('hidden');
        }
      }
    });

    if (window.lucide) lucide.createIcons();
  }

  /**
   * Salin Kode VA / Biller ke Clipboard
   */
  function copyMidtransCode(elementId, btnElement) {
    const codeEl = document.getElementById(elementId);
    if (!codeEl) return;
    const textToCopy = codeEl.innerText.trim();

    navigator.clipboard.writeText(textToCopy).then(() => {
      showCopySuccess(btnElement);
    }).catch(() => {
      // Fallback manual copy
      const tempInput = document.createElement('input');
      tempInput.value = textToCopy;
      document.body.appendChild(tempInput);
      tempInput.select();
      document.execCommand('copy');
      document.body.removeChild(tempInput);
      showCopySuccess(btnElement);
    });
  }

  function showCopySuccess(btn) {
    if (!btn) return;
    const origHtml = btn.innerHTML;
    btn.innerHTML = '<i data-lucide="check" class="w-3.5 h-3.5 inline"></i> Tersalin!';
    btn.classList.add('!bg-emerald-600', '!text-white');
    if (window.lucide) lucide.createIcons();
    setTimeout(() => {
      btn.innerHTML = origHtml;
      btn.classList.remove('!bg-emerald-600', '!text-white');
      if (window.lucide) lucide.createIcons();
    }, 2000);
  }

  /**
   * Fungsi Global: Memulai Pembayaran Midtrans Snap
   * @param {string} type - 'subscription', 'booking', 'open_slot', 'sparring'
   * @param {object} payload - data spesifik (court_id, package_id, room_id, dll)
   * @param {object} callbacks - onSuccess, onError, onPending, onClose
   */
  async function initiateMidtransPayment(type, payload = {}, callbacks = {}) {
    try {
      // 1. Request Snap Token dari backend LapanganKita
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                     || '{{ csrf_token() }}';

      const response = await fetch('{{ route("midtrans.snap") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify(Object.assign({ type: type }, payload))
      });

      const data = await response.json();

      if (!response.ok || !data.success) {
        alert(data.message || 'Gagal memproses transaksi Midtrans. Silakan coba lagi.');
        if (callbacks.onError) callbacks.onError(data);
        return;
      }

      currentMidtransOrderId = data.order_id;

      // 2. Jika Snap resmi aktif dan token bukan mock, buka Snap Popup resmi
      if (!data.is_mock && window.snap && typeof window.snap.pay === 'function') {
        window.snap.pay(data.snap_token, {
          onSuccess: function(result) {
            window.location.href = '{{ route("midtrans.finish") }}?order_id=' + data.order_id;
          },
          onPending: function(result) {
            window.location.href = '{{ route("midtrans.finish") }}?order_id=' + data.order_id;
          },
          onError: function(result) {
            alert('Pembayaran Midtrans gagal atau dibatalkan.');
            if (callbacks.onError) callbacks.onError(result);
          },
          onClose: function() {
            if (callbacks.onClose) callbacks.onClose();
          }
        });
      } else {
        // 3. Fallback ke Simulator Midtrans Snap yang elegan jika offline atau mock token
        openMidtransSimulator(data);
      }
    } catch (err) {
      console.error('Midtrans Exception:', err);
      alert('Terjadi kendala koneksi ke payment gateway: ' + err.message);
      if (callbacks.onError) callbacks.onError(err);
    }
  }

  function openMidtransSimulator(data) {
    document.getElementById('midtransModalOrderId').innerText = data.order_id;
    document.getElementById('midtransModalAmount').innerText = 'Rp ' + Number(data.amount || 0).toLocaleString('id-ID');
    document.getElementById('midtransModalMerchant').innerText = data.title || 'LapanganKita Futsal SaaS';

    // Buat kode VA angka yang unik berbasis order_id
    const orderNumMatch = (data.order_id || '').match(/\d+/);
    const orderNum = orderNumMatch ? orderNumMatch[0] : Math.floor(10000 + Math.random() * 90000).toString();
    const cleanNum = orderNum.padStart(5, '0');

    // 1. BCA VA
    const bcaEl = document.getElementById('midtransBcaVaCode');
    if (bcaEl) bcaEl.innerText = '70012' + cleanNum + '001';

    // 2. Mandiri Bill Key
    const mandiriKeyEl = document.getElementById('midtransMandiriBillKey');
    if (mandiriKeyEl) mandiriKeyEl.innerText = cleanNum + '001';

    // 3. BRI BRIVA
    const brivaEl = document.getElementById('midtransBrivaCode');
    if (brivaEl) brivaEl.innerText = '88880' + cleanNum + '001';

    // 4. BNI VA
    const bniEl = document.getElementById('midtransBniVaCode');
    if (bniEl) bniEl.innerText = '98880' + cleanNum + '001';

    // 5. QRIS Image
    const qrisImg = document.getElementById('midtransQrisImg');
    if (qrisImg) {
      const qrisData = encodeURIComponent('00020101021226600016ID.CO.MIDTRANS5144' + data.order_id + '520458125303360540' + (data.amount || 0));
      qrisImg.src = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + qrisData;
    }

    // Aktifkan panel sesuai radio yang terpilih
    const selectedRadio = document.querySelector('input[name="midtrans_channel"]:checked');
    const channel = selectedRadio ? selectedRadio.value : 'qris';
    onMidtransChannelChange(channel);

    const modal = document.getElementById('midtransSimulatorModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    if (window.lucide) lucide.createIcons();
  }

  function closeMidtransSimulator() {
    const modal = document.getElementById('midtransSimulatorModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  async function executeMidtransSimulation() {
    if (!currentMidtransOrderId) return;
    const btn = document.getElementById('btnConfirmMidtransSimulator');
    btn.disabled = true;
    btn.innerHTML = '<span class="animate-spin inline-block w-4 h-4 border-2 border-slate-950 border-t-transparent rounded-full mr-2"></span> Memproses Midtrans...';

    const selectedChannel = document.querySelector('input[name="midtrans_channel"]:checked')?.value || 'qris';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    try {
      const response = await fetch('{{ route("midtrans.simulate") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          order_id: currentMidtransOrderId,
          payment_type: selectedChannel,
        })
      });

      const res = await response.json();
      if (res.success && res.redirect_url) {
        window.location.href = res.redirect_url;
      } else {
        window.location.href = '{{ route("midtrans.finish") }}?order_id=' + currentMidtransOrderId;
      }
    } catch (e) {
      window.location.href = '{{ route("midtrans.finish") }}?order_id=' + currentMidtransOrderId;
    }
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeMidtransSimulator();
  });
</script>
