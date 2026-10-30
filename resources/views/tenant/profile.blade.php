@extends('layouts.admin')

@section('title', 'Profil & Foto Tempat Futsal - LapanganKita')
@section('meta_description', 'Kelola informasi tempat futsal, ganti foto banner, logo lapangan, dan fasilitas olahraga.')

@section('content')
<div class="py-8 md:py-12 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

  <!-- ALERT NOTIFIKASI SUKSES / ERROR -->
  @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300 text-xs flex items-center gap-2.5">
      <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500 shrink-0"></i>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  @if(isset($errors) && $errors->any())
    <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-300 text-xs space-y-1">
      <div class="font-bold flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-500 shrink-0"></i>
        <span>Ada kesalahan pada data yang Anda kirim:</span>
      </div>
      <ul class="list-disc list-inside pl-1 text-[11px]">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- HEADER -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
    <div>
      <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white flex items-center gap-2">
        <i data-lucide="image" class="w-6 h-6 text-emerald-500"></i>
        <span>Profil & Foto Tempat Futsal</span>
      </h1>
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
        Atur identitas tempat futsal, ganti logo, foto banner utama, dan fasilitas yang tampil kepada pemain di katalog.
      </p>
    </div>

    <div class="flex items-center gap-2.5">
      <a href="{{ route('tenant.dashboard') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 font-bold text-xs transition flex items-center gap-1.5">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>Kembali ke Dashboard</span>
      </a>
    </div>
  </div>

  <!-- FORM UTAMA CMS PROFILE -->
  <form action="{{ route('tenant.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm space-y-8">
    @csrf

    <!-- SECTION 1: MEDIA BANNER & LOGO RESMI -->
    <div class="space-y-5">
      <div class="border-b border-slate-200 dark:border-slate-800 pb-3 flex items-center justify-between">
        <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="camera" class="w-4 h-4 text-emerald-500"></i>
          <span>Foto Banner & Logo Tempat Futsal</span>
        </h2>
        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">Tampil di Katalog Pemain</span>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        
        <!-- LOGO SECTION (4 COLS) -->
        <div class="md:col-span-4 space-y-3">
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Logo Lapangan (Format Kotak 1:1)</label>
          
          <div class="flex items-center gap-4">
            <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 shadow-md shrink-0">
              <img id="logoPreview" 
                   src="{{ $tenant->logo_image ?: 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=200' }}" 
                   alt="Logo Preview" 
                   class="w-full h-full object-cover">
            </div>
            
            <div class="space-y-1.5 flex-1 text-xs">
              <label for="logo_file" class="inline-block px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold text-[11px] text-slate-800 dark:text-white cursor-pointer transition border border-slate-300 dark:border-slate-700">
                <i data-lucide="upload" class="w-3.5 h-3.5 inline mr-1"></i> Unggah Foto
              </label>
              <input type="file" name="logo_file" id="logo_file" accept="image/*" class="hidden" onchange="previewFile(this, 'logoPreview')">
              <span class="text-[10px] text-slate-400 block">Maks. 2MB (JPG, PNG, WebP)</span>
            </div>
          </div>

          <div>
            <label class="block text-[11px] text-slate-500 dark:text-slate-400 mb-1">Atau Masukkan Tautan / Link Foto Logo:</label>
            <input type="url" 
                   name="logo_url" 
                   value="{{ filter_var($tenant->logo_image, FILTER_VALIDATE_URL) ? $tenant->logo_image : '' }}" 
                   placeholder="https://..." 
                   oninput="previewUrl(this.value, 'logoPreview')"
                   class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
          </div>

          <div class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-[11px] text-emerald-800 dark:text-emerald-300 flex items-start gap-2">
            <i data-lucide="printer" class="w-4 h-4 shrink-0 text-emerald-600 mt-0.5"></i>
            <span><strong>Kustomisasi Struk:</strong> Logo resmi ini otomatis tercetak pada Struk Pembayaran & E-Tiket Pemain saat reservasi lapangan di venue Anda!</span>
          </div>
        </div>

        <!-- BANNER SECTION (8 COLS) -->
        <div class="md:col-span-8 space-y-3">
          <div class="flex items-center justify-between">
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Foto Spanduk / Banner Utama (Memanjang 16:9)</label>
            <label for="banner_file" class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold text-[11px] text-slate-800 dark:text-white cursor-pointer transition border border-slate-300 dark:border-slate-700 flex items-center gap-1.5">
              <i data-lucide="upload" class="w-3.5 h-3.5"></i>
              <span>Ganti Foto Banner</span>
            </label>
            <input type="file" name="banner_file" id="banner_file" accept="image/*" class="hidden" onchange="previewFile(this, 'bannerPreview')">
          </div>

          <div class="relative h-44 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 shadow-inner">
            <img id="bannerPreview" 
                 src="{{ $tenant->banner_image ?: 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=1200' }}" 
                 alt="Banner Preview" 
                 class="w-full h-full object-cover">
          </div>

          <div>
            <label class="block text-[11px] text-slate-500 dark:text-slate-400 mb-1">Atau Masukkan Tautan / Link Foto Banner:</label>
            <input type="url" 
                   name="banner_url" 
                   value="{{ filter_var($tenant->banner_image, FILTER_VALIDATE_URL) ? $tenant->banner_image : '' }}" 
                   placeholder="https://images.unsplash.com/photo-..." 
                   oninput="previewUrl(this.value, 'bannerPreview')"
                   class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
          </div>
        </div>

      </div>
    </div>

    <!-- SECTION 2: INFORMASI DASAR VENUE -->
    <div class="space-y-4 pt-4 border-t border-slate-200 dark:border-slate-800">
      <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
        <i data-lucide="info" class="w-4 h-4 text-emerald-500"></i>
        <span>Informasi Detail Tempat Futsal</span>
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
            Nama Tempat Futsal <span class="text-rose-500 font-bold">*</span>
          </label>
          <input type="text" 
                 name="name" 
                 required 
                 autofocus
                 placeholder="Contoh: Champion Futsal Arena"
                 value="{{ old('name', $tenant->name) }}" 
                 class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none font-bold">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
            Kota / Wilayah <span class="text-rose-500 font-bold">*</span>
          </label>
          <input type="text" 
                 name="city" 
                 required 
                 placeholder="Contoh: Bandung"
                 value="{{ old('city', $tenant->city) }}" 
                 class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
            Nomor WhatsApp Pengelola / Kasir <span class="text-rose-500 font-bold">*</span>
          </label>
          <input type="text" 
                 name="phone_wa" 
                 required 
                 value="{{ old('phone_wa', $tenant->phone_wa) }}" 
                 placeholder="Contoh: 081234567890" 
                 class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
            Alamat Lengkap <span class="text-rose-500 font-bold">*</span>
          </label>
          <input type="text" 
                 name="address" 
                 required 
                 placeholder="Contoh: Jl. Soekarno Hatta No. 12, Bandung"
                 value="{{ old('address', $tenant->address) }}" 
                 class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi & Keunggulan Tempat</label>
        <textarea name="description" 
                  rows="3" 
                  placeholder="Ceritakan keunggulan tempat Anda, peraturan booking, atau fasilitas parkir..." 
                  class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('description', $tenant->description) }}</textarea>
      </div>
    </div>

    <!-- SECTION 3: FASILITAS TEMPAT FUTSAL -->
    <div class="space-y-4 pt-4 border-t border-slate-200 dark:border-slate-800">
      <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
        <i data-lucide="check-square" class="w-4 h-4 text-emerald-500"></i>
        <span>Fasilitas yang Tersedia</span>
      </h2>

      @php
        $currentFacs = is_array($tenant->facilities) ? $tenant->facilities : (json_decode($tenant->facilities, true) ?: []);
        $allFacs = [
          'Rumput Sintetis Premium',
          'Lantai Interlock Vinyl FIFA',
          'Parkir Mobil & Motor Luas',
          'Musholla Bersih',
          'Kantin & Minuman Dingin',
          'Kamar Ganti & Shower Panas',
          'Papan Skor Digital',
          'Ruang Tunggu Ber-AC',
          'Wi-Fi Gratis Cepat'
        ];
      @endphp

      <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
        @foreach($allFacs as $fac)
          <label class="flex items-center gap-2.5 p-3 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-850 hover:border-emerald-500 cursor-pointer transition select-none text-xs">
            <input type="checkbox" 
                   name="facilities[]" 
                   value="{{ $fac }}" 
                   {{ in_array($fac, $currentFacs) ? 'checked' : '' }} 
                   class="w-4 h-4 rounded text-emerald-500 focus:ring-emerald-500">
            <span class="font-medium text-slate-700 dark:text-slate-300">{{ $fac }}</span>
          </label>
        @endforeach
      </div>
    </div>

    <!-- SECTION 4: KEAMANAN & KATA SANDI AKUN -->
    <div class="space-y-4 pt-4 border-t border-slate-200 dark:border-slate-800">
      <div class="flex items-center justify-between">
        <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="lock" class="w-4 h-4 text-emerald-500"></i>
          <span>Keamanan & Kata Sandi Akun Login</span>
        </h2>
        <span class="text-[11px] text-slate-400">Kosongkan jika tidak ingin mengubah sandi</span>
      </div>

      <div class="max-w-md">
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
          Kata Sandi Baru <span class="text-slate-400 font-normal">(Opsional)</span>
        </label>
        <input type="password" 
               name="new_password" 
               minlength="6"
               placeholder="Masukkan kata sandi baru (min. 6 karakter)..." 
               class="w-full bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        <p class="text-[10px] text-slate-400 mt-1">Anda bebas menentukan kata sandi sesuai keinginan Anda (minimal 6 karakter).</p>
      </div>
    </div>

    <!-- SUBMIT BUTTON -->
    <div class="pt-4 flex justify-end">
      <button type="submit" class="px-7 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs sm:text-sm shadow-xl shadow-emerald-500/25 transition transform hover:-translate-y-0.5 flex items-center gap-2">
        <i data-lucide="save" class="w-4 h-4"></i>
        <span>Simpan Perubahan Profil & Pengaturan</span>
      </button>
    </div>

  </form>

  <!-- SECTION: LOG AKTIVITAS & PERUBAHAN DATA SENSITIF (AUDIT TRAIL PER TENANT) -->
  <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6 sm:p-8 shadow-sm space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 dark:border-slate-800 pb-4">
      <div>
        <h2 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
          <i data-lucide="shield-check" class="w-5 h-5 text-emerald-500"></i>
          <span>Log Aktivitas &amp; Audit Trail Venue</span>
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
          Rekam jejak otomatis setiap perubahan data sensitif (unit lapangan, kunci jadwal kasir, profil venue, dan pembayaran paket).
        </p>
      </div>
      <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20 self-start sm:self-auto">
        <i data-lucide="activity" class="w-3.5 h-3.5"></i>
        <span>{{ isset($activityLogs) ? $activityLogs->count() : 0 }} Aksi Terakhir</span>
      </span>
    </div>

    @if(isset($activityLogs) && $activityLogs->isNotEmpty())
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
            <tr>
              <th class="py-3 px-4">Waktu</th>
              <th class="py-3 px-4">Pengguna</th>
              <th class="py-3 px-4">Aksi</th>
              <th class="py-3 px-4">Deskripsi</th>
              <th class="py-3 px-4">IP Address</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-medium">
            @foreach($activityLogs as $log)
              @php
                $badgeColor = match($log->action) {
                  'court_created' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
                  'court_archived' => 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20',
                  'court_restored' => 'bg-teal-500/10 text-teal-600 dark:text-teal-400 border-teal-500/20',
                  'slot_locked_cashier' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
                  'slot_unlocked_cashier' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20',
                  'profile_updated' => 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-indigo-500/20',
                  'subscription_paid' => 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-500/20',
                  default => 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-500/20',
                };
              @endphp
              <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                <td class="py-3 px-4 whitespace-nowrap text-slate-500 dark:text-slate-400">
                  <div class="font-bold text-slate-800 dark:text-slate-200">{{ $log->created_at->format('d M Y') }}</div>
                  <div class="text-[10px] text-slate-400">{{ $log->created_at->format('H:i:s') }} WIB</div>
                </td>
                <td class="py-3 px-4 whitespace-nowrap">
                  <div class="font-bold text-slate-800 dark:text-slate-200">{{ $log->user->name ?? 'Sistem' }}</div>
                  <div class="text-[10px] text-slate-400">{{ $log->user->email ?? '-' }}</div>
                </td>
                <td class="py-3 px-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badgeColor }}">
                    {{ str_replace('_', ' ', strtoupper($log->action)) }}
                  </span>
                </td>
                <td class="py-3 px-4 text-slate-700 dark:text-slate-300">
                  <p class="line-clamp-2">{{ $log->description }}</p>
                </td>
                <td class="py-3 px-4 whitespace-nowrap text-slate-400 text-[11px] font-mono">
                  {{ $log->ip_address ?? '127.0.0.1' }}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="py-10 text-center space-y-2">
        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto">
          <i data-lucide="shield" class="w-6 h-6"></i>
        </div>
        <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Belum Ada Aktivitas Sensitif Tercatat</p>
        <p class="text-[11px] text-slate-400 max-w-sm mx-auto">Setiap kali Anda menambah lapangan, mengunci jadwal kasir, atau memperbarui profil, riwayatnya akan tersimpan di sini.</p>
      </div>
    @endif
  </div>

</div>
@endsection

@section('scripts')
<script>
  function previewFile(input, targetId) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById(targetId).src = e.target.result;
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  function previewUrl(url, targetId) {
    if (url && url.startsWith('http')) {
      document.getElementById(targetId).src = url;
    }
  }
</script>
@endsection
