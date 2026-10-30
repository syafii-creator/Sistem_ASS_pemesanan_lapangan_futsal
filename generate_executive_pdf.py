import os
import base64
import subprocess

def get_base64_image(image_path):
    if not os.path.exists(image_path):
        return ""
    with open(image_path, "rb") as f:
        data = f.read()
    ext = os.path.splitext(image_path)[1].lower().replace(".", "")
    if ext == "jpg":
        ext = "jpeg"
    return f"data:image/{ext};base64,{base64.b64encode(data).decode('utf-8')}"

def generate_pdf():
    # Asset Base64 Encoded
    logo_b64 = get_base64_image("public/img/logo.png")
    landing_b64 = get_base64_image("scratch/landing.png")
    venue_b64 = get_base64_image("scratch/venue_detail.png")
    openslot_b64 = get_base64_image("scratch/open_slot.png")
    dashboard_b64 = get_base64_image("scratch/tenant_dashboard.png")
    analytics_b64 = get_base64_image("scratch/tenant_analytics.png")

    html_content = f"""<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>LapanganKita - Executive Presentation Deck PDF</title>
  <style>
    * {{
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }}

    @page {{
      size: 1920px 1080px;
      margin: 0;
    }}

    body {{
      font-family: 'Times New Roman', Times, serif;
      background-color: #060A14;
      color: #F8FAFC;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }}

    .slide {{
      width: 1920px;
      height: 1080px;
      position: relative;
      page-break-after: always;
      page-break-inside: avoid;
      overflow: hidden;
      background: radial-gradient(circle at 15% 20%, rgba(16, 185, 129, 0.08) 0%, transparent 45%),
                  radial-gradient(circle at 85% 80%, rgba(6, 182, 212, 0.08) 0%, transparent 45%),
                  #060A14;
      padding: 60px 100px 50px 100px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }}

    .top-neon-bar {{
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 8px;
      background: linear-gradient(90deg, #10B981, #06B6D4, #3B82F6, #10B981);
    }}

    /* Symmetrical Header */
    .slide-header {{
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 25px;
    }}

    .header-pill {{
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 6px 20px;
      border-radius: 9999px;
      background: rgba(16, 185, 129, 0.12);
      border: 1px solid rgba(16, 185, 129, 0.4);
      color: #34D399;
      font-size: 14px;
      font-weight: 800;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      margin-bottom: 10px;
    }}

    .slide-title {{
      font-size: 38px;
      font-weight: 800;
      color: #FFFFFF;
      letter-spacing: -0.3px;
      line-height: 1.2;
    }}

    .slide-subtitle {{
      font-size: 19px;
      color: #94A3B8;
      margin-top: 6px;
      font-style: italic;
    }}

    .mini-logo {{
      width: 70px;
      height: 70px;
      border-radius: 16px;
      border: 1.5px solid rgba(16, 185, 129, 0.5);
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
    }}

    /* Symmetrical Cards */
    .card {{
      background: rgba(18, 26, 43, 0.88);
      border: 1px solid rgba(39, 52, 77, 0.9);
      border-radius: 20px;
      padding: 32px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
    }}

    .card-emerald {{
      border-color: rgba(16, 185, 129, 0.55);
      box-shadow: 0 0 25px rgba(16, 185, 129, 0.15);
    }}
    .card-cyan {{
      border-color: rgba(6, 182, 212, 0.55);
      box-shadow: 0 0 25px rgba(6, 182, 212, 0.15);
    }}
    .card-amber {{
      border-color: rgba(245, 158, 11, 0.55);
      box-shadow: 0 0 25px rgba(245, 158, 11, 0.15);
    }}
    .card-red {{
      border-color: rgba(239, 68, 68, 0.55);
      box-shadow: 0 0 25px rgba(239, 68, 68, 0.15);
    }}

    /* Browser Mockup Window */
    .browser-frame {{
      background: #0B1120;
      border: 1.5px solid rgba(16, 185, 129, 0.5);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
      display: flex;
      flex-direction: column;
    }}
    .browser-header {{
      background: #111827;
      padding: 10px 18px;
      display: flex;
      align-items: center;
      gap: 8px;
      border-bottom: 1px solid #1F2937;
    }}
    .browser-dot {{
      width: 12px;
      height: 12px;
      border-radius: 50%;
    }}
    .dot-red {{ background: #EF4444; }}
    .dot-yellow {{ background: #F59E0B; }}
    .dot-green {{ background: #10B981; }}
    .browser-url {{
      margin-left: 10px;
      font-size: 13px;
      color: #64748B;
      font-family: 'Times New Roman', Times, serif;
    }}
    .browser-content {{
      flex: 1;
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: top;
    }}

    /* Symmetrical Footer */
    .slide-footer {{
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 15px;
      border-top: 1px solid rgba(51, 65, 85, 0.4);
      font-size: 14px;
      color: #64748B;
      font-weight: 600;
    }}

    .gradient-text-emerald {{
      background: linear-gradient(135deg, #34D399, #10B981, #06B6D4);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }}

    .grid-2 {{
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 36px;
      flex: 1;
      margin-bottom: 20px;
      align-items: center;
    }}

    .grid-3 {{
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 32px;
      flex: 1;
      margin-bottom: 20px;
    }}

    .grid-4 {{
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 24px;
      flex: 1;
      margin-bottom: 20px;
    }}

    .stat-chip {{
      background: rgba(18, 26, 43, 0.9);
      border-radius: 16px;
      padding: 22px;
      text-align: center;
    }}
    .stat-chip-num {{
      font-size: 44px;
      font-weight: 800;
      line-height: 1;
    }}
    .stat-chip-label {{
      font-size: 14px;
      font-weight: 700;
      color: #CBD5E1;
      text-transform: uppercase;
      margin-top: 8px;
    }}
  </style>
</head>
<body>

  <!-- =========================================================================
       PAGE 1: COVER SLIDE
       ========================================================================= -->
  <section class="slide" style="justify-content: center; align-items: center; text-align: center; padding: 80px 100px;">
    <div class="top-neon-bar"></div>
    
    <div style="margin-bottom: 25px;">
      <img src="{logo_b64}" alt="Logo LapanganKita" style="width: 150px; height: 150px; border-radius: 32px; border: 3px solid #10B981; box-shadow: 0 0 40px rgba(16, 185, 129, 0.4);">
    </div>

    <div style="display: inline-block; padding: 8px 26px; border-radius: 9999px; background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.4); color: #34D399; font-size: 15px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 18px;">
      ⚡ PLATFORM REVOLUSI EKOSISTEM FUTSAL MODERN 2026
    </div>

    <h1 style="font-size: 72px; font-weight: 900; letter-spacing: -0.5px; margin-bottom: 10px;">
      LAPANGAN<span class="gradient-text-emerald">KITA</span>
    </h1>
    <p style="font-size: 24px; color: #94A3B8; max-width: 1050px; line-height: 1.4; margin-bottom: 40px; font-style: italic;">
      Platform Reservasi Lapangan Futsal Real-Time, Komunitas Mabar &amp; SaaS Multi-Tenant Berbasis AI
    </p>

    <!-- 3 Symmetrical Metric Chips -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; width: 1050px; margin-bottom: 40px;">
      <div class="stat-chip card-emerald">
        <div class="stat-chip-num" style="color: #34D399;">0%</div>
        <div class="stat-chip-label">Bentrok Jadwal</div>
      </div>
      <div class="stat-chip card-cyan">
        <div class="stat-chip-num" style="color: #38BDF8;">&lt; 60s</div>
        <div class="stat-chip-label">Pesan &amp; Bayar Instan</div>
      </div>
      <div class="stat-chip card-amber">
        <div class="stat-chip-num" style="color: #FBBF24;">AI 24/7</div>
        <div class="stat-chip-label">Gemini Business Advisor</div>
      </div>
    </div>

    <div style="background: rgba(18, 26, 43, 0.85); border: 1px solid #334155; border-radius: 16px; padding: 16px 40px; font-size: 16px; color: #94A3B8; display: flex; gap: 40px; justify-content: center;">
      <span><strong>Pengembang:</strong> [Nama Anda / Tim]</span>
      <span><strong>Jurusan:</strong> Teknik Informatika / Sistem Informasi</span>
      <span><strong>Tech Stack:</strong> Laravel 11 • Tailwind • Midtrans • Gemini AI</span>
    </div>

    <div class="slide-footer" style="width: 100%; position: absolute; bottom: 30px; left: 0; padding: 0 100px;">
      <span>Dokumen Presentasi Eksekutif</span>
      <span>Halaman 1 dari 12</span>
    </div>
  </section>

  <!-- =========================================================================
       PAGE 2: PROBLEM STATEMENT
       ========================================================================= -->
  <section class="slide">
    <div class="top-neon-bar"></div>
    <div class="slide-header">
      <div>
        <div class="header-pill">Problem Statement</div>
        <div class="slide-title">Masalah Nyata: Kenapa Booking Lapangan Masih Ribet?</div>
        <div class="slide-subtitle">3 Hambatan utama yang dialami pemain dan pengelola lapangan futsal saat ini.</div>
      </div>
      <img src="{logo_b64}" class="mini-logo" alt="logo">
    </div>

    <div class="grid-3">
      <div class="card card-red" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <div style="font-size: 40px; margin-bottom: 15px;">💬</div>
          <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 15px; color: #FFF;">1. Chat WA Lambat &amp; Bentrok</h3>
          <p style="font-size: 16.5px; color: #CBD5E1; line-height: 1.6;">
            Pemain harus chat admin satu per satu dan menunggu lama. Pembukuan manual sering salah catat hingga dua tim datang bersamaan (Double Booking).
          </p>
        </div>
        <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); border-radius: 12px; padding: 12px 18px; font-size: 14px; font-weight: 700; color: #F87171;">
          ⚠️ Dampak: Pemain kecewa &amp; reputasi lapangan jatuh
        </div>
      </div>

      <div class="card card-amber" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <div style="font-size: 40px; margin-bottom: 15px;">🏃</div>
          <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 15px; color: #FFF;">2. Pemain Solo Susah Main</h3>
          <p style="font-size: 16.5px; color: #CBD5E1; line-height: 1.6;">
            Sering batal main futsal karena teman mendadak berhalangan. Belum ada platform yang memfasilitasi pemain perorangan untuk patungan sewa lapangan.
          </p>
        </div>
        <div style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.4); border-radius: 12px; padding: 12px 18px; font-size: 14px; font-weight: 700; color: #FBBF24;">
          ⚠️ Dampak: 40% rencana main futsal batal tiap minggu
        </div>
      </div>

      <div class="card card-red" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <div style="font-size: 40px; margin-bottom: 15px;">📒</div>
          <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 15px; color: #FFF;">3. Kasir Manual &amp; Jam Sepi</h3>
          <p style="font-size: 16.5px; color: #CBD5E1; line-height: 1.6;">
            Catatan sewa di buku tulis rentan hilang atau dimanipulasi. Lapangan di jam kerja (weekday siang) selalu sepi tanpa strategi promosi yang terarah.
          </p>
        </div>
        <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); border-radius: 12px; padding: 12px 18px; font-size: 14px; font-weight: 700; color: #F87171;">
          ⚠️ Dampak: Omset pengelola bocor &amp; lapangan nganggur
        </div>
      </div>
    </div>

    <div class="slide-footer">
      <span>LapanganKita • Platform Ekosistem Olahraga Terpadu</span>
      <span>Halaman 2 dari 12</span>
    </div>
  </section>

  <!-- =========================================================================
       PAGE 3: 3 PILAR SOLUSI
       ========================================================================= -->
  <section class="slide">
    <div class="top-neon-bar"></div>
    <div class="slide-header">
      <div>
        <div class="header-pill">Value Proposition</div>
        <div class="slide-title">Solusi Terpadu: 3 Pilar Utama LapanganKita</div>
        <div class="slide-subtitle">Menyelesaikan masalah end-to-end bagi pemain, komunitas, dan pemilik bisnis futsal.</div>
      </div>
      <img src="{logo_b64}" class="mini-logo" alt="logo">
    </div>

    <div class="grid-3">
      <div class="card card-emerald" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <div style="font-size: 13px; font-weight: 800; color: #34D399; text-transform: uppercase; margin-bottom: 5px;">Pilar 1</div>
          <h3 style="font-size: 26px; font-weight: 800; color: #FFF; margin-bottom: 20px;">Smart Booking &amp; Bayar</h3>
          <ul style="list-style: none; font-size: 16.5px; color: #CBD5E1; line-height: 1.8;">
            <li>✔ <strong>Jadwal Real-Time:</strong> Akurasi 100% anti-bentrok.</li>
            <li>✔ <strong>Midtrans Snap:</strong> Bayar instan QRIS, VA Bank &amp; e-Wallet.</li>
            <li>✔ <strong>E-Tiket PDF:</strong> Dilengkapi QR code untuk verifikasi lapangan.</li>
            <li>✔ <strong>Pilihan Tipe Lapangan:</strong> Vinyl Interlock atau Rumput Sintetis.</li>
          </ul>
        </div>
        <div style="border-top: 1px solid #1E293B; padding-top: 15px; font-size: 14px; font-weight: 700; color: #34D399;">
          ⏱️ Proses pemesanan selesai &lt; 60 detik
        </div>
      </div>

      <div class="card card-cyan" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <div style="font-size: 13px; font-weight: 800; color: #38BDF8; text-transform: uppercase; margin-bottom: 5px;">Pilar 2</div>
          <h3 style="font-size: 26px; font-weight: 800; color: #FFF; margin-bottom: 20px;">Community Hub (Mabar)</h3>
          <ul style="list-style: none; font-size: 16.5px; color: #CBD5E1; line-height: 1.8;">
            <li>✔ <strong>Open Slot Mabar:</strong> Main solo patungan biaya sewa per orang.</li>
            <li>✔ <strong>Lobby Sparing:</strong> Tantang klub lawan dengan sistem split 50:50.</li>
            <li>✔ <strong>Invoice PDF Perorangan:</strong> Transparan bagi setiap peserta.</li>
            <li>✔ <strong>Google SSO Login:</strong> Masuk 1-klik tanpa ribet kata sandi.</li>
          </ul>
        </div>
        <div style="border-top: 1px solid #1E293B; padding-top: 15px; font-size: 14px; font-weight: 700; color: #38BDF8;">
          ⚽ Bebas rasa takut kekurangan pemain
        </div>
      </div>

      <div class="card card-amber" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <div style="font-size: 13px; font-weight: 800; color: #FBBF24; text-transform: uppercase; margin-bottom: 5px;">Pilar 3</div>
          <h3 style="font-size: 26px; font-weight: 800; color: #FFF; margin-bottom: 20px;">SaaS Multi-Tenant Mitra</h3>
          <ul style="list-style: none; font-size: 16.5px; color: #CBD5E1; line-height: 1.8;">
            <li>✔ <strong>Kunci Slot Offline:</strong> Kunci instan jika ada tamu walk-in kasir.</li>
            <li>✔ <strong>Free Trial 7 Hari:</strong> Kemudahan mencoba sistem tanpa risiko.</li>
            <li>✔ <strong>Laporan Finansial PDF:</strong> Rekapitulasi omset dan transaksi.</li>
            <li>✔ <strong>Asisten Gemini AI:</strong> Rekomendasi bisnis &amp; promo jam sepi.</li>
          </ul>
        </div>
        <div style="border-top: 1px solid #1E293B; padding-top: 15px; font-size: 14px; font-weight: 700; color: #FBBF24;">
          📈 Digitalisasi operasional UMKM olahraga
        </div>
      </div>
    </div>

    <div class="slide-footer">
      <span>LapanganKita • Platform Ekosistem Olahraga Terpadu</span>
      <span>Halaman 3 dari 12</span>
    </div>
  </section>

  <!-- =========================================================================
       PAGE 4: ARSITEKTUR MULTI-ROLE
       ========================================================================= -->
  <section class="slide">
    <div class="top-neon-bar"></div>
    <div class="slide-header">
      <div>
        <div class="header-pill">Role Architecture</div>
        <div class="slide-title">Arsitektur Pengguna: 3 Role Terintegrasi</div>
        <div class="slide-subtitle">Pemisahan hak akses terproteksi menggunakan middleware Laravel resmi.</div>
      </div>
      <img src="{logo_b64}" class="mini-logo" alt="logo">
    </div>

    <div class="grid-3">
      <div class="card card-emerald">
        <div style="font-size: 40px; margin-bottom: 10px;">🏃</div>
        <h3 style="font-size: 24px; font-weight: 800; color: #FFF; margin-bottom: 5px;">PEMAIN (Player)</h3>
        <div style="font-size: 12px; font-weight: 800; color: #34D399; text-transform: uppercase; margin-bottom: 15px;">Pelanggan &amp; Komunitas</div>
        <ul style="list-style: none; font-size: 15.5px; color: #CBD5E1; line-height: 2;">
          <li>• Cari venue berdasarkan kota &amp; fasilitas</li>
          <li>• Booking lapangan &amp; bayar via Midtrans Snap</li>
          <li>• Unduh E-Tiket PDF ber-QR Code unik</li>
          <li>• Buat atau ikuti room Open Slot mabar</li>
          <li>• Kirim tantangan tanding ke tim lain di Sparing</li>
        </ul>
      </div>

      <div class="card card-cyan">
        <div style="font-size: 40px; margin-bottom: 10px;">🏢</div>
        <h3 style="font-size: 24px; font-weight: 800; color: #FFF; margin-bottom: 5px;">MITRA (Venue Owner)</h3>
        <div style="font-size: 12px; font-weight: 800; color: #38BDF8; text-transform: uppercase; margin-bottom: 15px;">Pengelola Bisnis Lapangan</div>
        <ul style="list-style: none; font-size: 15.5px; color: #CBD5E1; line-height: 2;">
          <li>• Kelola unit lapangan (Vinyl / Sintetis) &amp; tarif</li>
          <li>• Kunci slot kasir offline (walk-in) anti-bentrok</li>
          <li>• Monitor grafik omset harian &amp; bulanan</li>
          <li>• Cetak rekap laporan pembukuan finansial PDF</li>
          <li>• Konsultasi strategi jam sepi via asisten AI</li>
        </ul>
      </div>

      <div class="card card-amber">
        <div style="font-size: 40px; margin-bottom: 10px;">🛡️</div>
        <h3 style="font-size: 24px; font-weight: 800; color: #FFF; margin-bottom: 5px;">SUPER ADMIN</h3>
        <div style="font-size: 12px; font-weight: 800; color: #FBBF24; text-transform: uppercase; margin-bottom: 15px;">Pemilik Master Platform</div>
        <ul style="list-style: none; font-size: 15.5px; color: #CBD5E1; line-height: 2;">
          <li>• Monitoring metrik GMV &amp; transaksi nasional</li>
          <li>• Verifikasi, aktivasi, &amp; suspend akun mitra</li>
          <li>• Konfigurasi master paket langganan SaaS</li>
          <li>• Pengawasan audit trail &amp; keamanan log aktivitas</li>
        </ul>
      </div>
    </div>

    <div class="slide-footer">
      <span>LapanganKita • Platform Ekosistem Olahraga Terpadu</span>
      <span>Halaman 4 dari 12</span>
    </div>
  </section>

  <!-- =========================================================================
       PAGE 5: FITUR PEMAIN (2 SYMMETRICAL COLUMNS)
       ========================================================================= -->
  <section class="slide">
    <div class="top-neon-bar"></div>
    <div class="slide-header">
      <div>
        <div class="header-pill">Player Showcase</div>
        <div class="slide-title">Pengalaman Pemain: Reservasi &amp; Checkout Kilat</div>
        <div class="slide-subtitle">Pemesanan lapangan dalam 3 langkah mudah dengan ketersediaan jam real-time.</div>
      </div>
      <img src="{logo_b64}" class="mini-logo" alt="logo">
    </div>

    <div class="grid-2">
      <div style="display: flex; flex-direction: column; gap: 20px;">
        <div class="card card-emerald" style="padding: 22px;">
          <h4 style="font-size: 18.5px; font-weight: 800; color: #34D399; margin-bottom: 6px;">⚡ Matrix Jadwal Interaktif</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.5;">Pilih jam kosong layaknya memilih kursi bioskop. Slot yang sudah terisi otomatis terkunci secara presisi di database.</p>
        </div>
        <div class="card card-cyan" style="padding: 22px;">
          <h4 style="font-size: 18.5px; font-weight: 800; color: #38BDF8; margin-bottom: 6px;">💳 Midtrans Snap Multi-Channel</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.5;">Bayar langsung di pop-up layar tanpa pindah aplikasi: Scan QRIS (BCA, GoPay, OVO, Dana) atau transfer Virtual Account.</p>
        </div>
        <div class="card card-amber" style="padding: 22px;">
          <h4 style="font-size: 18.5px; font-weight: 800; color: #FBBF24; margin-bottom: 6px;">🎟️ E-Tiket PDF Ber-QR Code</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.5;">Begitu pembayaran terverifikasi, sistem menerbitkan tiket PDF resmi dengan QR code unik untuk ditunjukkan ke petugas di lokasi.</p>
        </div>
      </div>

      <div class="browser-frame" style="height: 520px;">
        <div class="browser-header">
          <div class="browser-dot dot-red"></div>
          <div class="browser-dot dot-yellow"></div>
          <div class="browser-dot dot-green"></div>
          <span class="browser-url">https://lapangankita.id/venues/champion-futsal-arena-bandung</span>
        </div>
        <img src="{venue_b64}" class="browser-content" alt="Venue Screenshot">
      </div>
    </div>

    <div class="slide-footer">
      <span>LapanganKita • Platform Ekosistem Olahraga Terpadu</span>
      <span>Halaman 5 dari 12</span>
    </div>
  </section>

  <!-- =========================================================================
       PAGE 6: KOMUNITAS (2 SYMMETRICAL COLUMNS)
       ========================================================================= -->
  <section class="slide">
    <div class="top-neon-bar"></div>
    <div class="slide-header">
      <div>
        <div class="header-pill">Community Hub</div>
        <div class="slide-title">Komunitas: Open Slot Mabar &amp; Lobby Sparing</div>
        <div class="slide-subtitle">Solusi cerdas bagi pemain solo dan klub yang mencari lawan tanding seimbang.</div>
      </div>
      <img src="{logo_b64}" class="mini-logo" alt="logo">
    </div>

    <div class="grid-2">
      <div class="browser-frame" style="height: 520px; border-color: rgba(6, 182, 212, 0.5);">
        <div class="browser-header">
          <div class="browser-dot dot-red"></div>
          <div class="browser-dot dot-yellow"></div>
          <div class="browser-dot dot-green"></div>
          <span class="browser-url">https://lapangankita.id/open-slot</span>
        </div>
        <img src="{openslot_b64}" class="browser-content" alt="Open Slot Screenshot">
      </div>

      <div style="display: flex; flex-direction: column; gap: 20px;">
        <div class="card card-cyan" style="padding: 24px;">
          <h4 style="font-size: 20px; font-weight: 800; color: #38BDF8; margin-bottom: 8px;">👥 Open Slot Patungan (Pemain Solo)</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.6;">
            Host membuka room, menentukan kebutuhan jumlah pemain, dan sistem otomatis membagi biaya sewa per orang. Tidak ada sesi yang batal karena kurang personil.
          </p>
        </div>
        <div class="card card-amber" style="padding: 24px;">
          <h4 style="font-size: 20px; font-weight: 800; color: #FBBF24; margin-bottom: 8px;">⚔️ Lobby Sparing Match (Split 50:50)</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.6;">
            Klub futsal dapat mempublikasikan tantangan bertanding. Biaya sewa lapangan langsung dibagi rata 50% untuk masing-masing tim secara otomatis.
          </p>
        </div>
        <div class="card card-emerald" style="padding: 24px;">
          <h4 style="font-size: 20px; font-weight: 800; color: #34D399; margin-bottom: 8px;">🧾 Invoice Transparan Per Anggota</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.6;">
            Tiap pemain yang membayar patungan mendapatkan invoice PDF resmi dengan kode unik verifikasi dan rincian transaksi yang sah.
          </p>
        </div>
      </div>
    </div>

    <div class="slide-footer">
      <span>LapanganKita • Platform Ekosistem Olahraga Terpadu</span>
      <span>Halaman 6 dari 12</span>
    </div>
  </section>

  <!-- =========================================================================
       PAGE 7: OPERASIONAL MITRA & SAAS (2 SYMMETRICAL COLUMNS)
       ========================================================================= -->
  <section class="slide">
    <div class="top-neon-bar"></div>
    <div class="slide-header">
      <div>
        <div class="header-pill">Tenant &amp; SaaS</div>
        <div class="slide-title">Panel Mitra: Kasir Offline &amp; Langganan SaaS</div>
        <div class="slide-subtitle">Digitalisasi lengkap untuk mengelola kasir offline dan meningkatkan omset lapangan.</div>
      </div>
      <img src="{logo_b64}" class="mini-logo" alt="logo">
    </div>

    <div class="grid-2">
      <div style="display: flex; flex-direction: column; gap: 20px;">
        <div class="card card-amber" style="padding: 22px;">
          <h4 style="font-size: 18.5px; font-weight: 800; color: #FBBF24; margin-bottom: 6px;">🔒 Kunci Slot Kasir Offline</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.5;">Tombol toggle 1-klik untuk memblokir jadwal jika ada penyewa tunai/walk-in. Terhindar dari bentrok dengan pemesan online.</p>
        </div>
        <div class="card card-emerald" style="padding: 22px;">
          <h4 style="font-size: 18.5px; font-weight: 800; color: #34D399; margin-bottom: 6px;">🏷️ Tarif Dinamis (Reguler vs Peak-Hour)</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.5;">Bebas menentukan tarif lebih terjangkau di siang hari kerja dan tarif premium di malam hari/weekend per unit lapangan.</p>
        </div>
        <div class="card card-cyan" style="padding: 22px;">
          <h4 style="font-size: 18.5px; font-weight: 800; color: #38BDF8; margin-bottom: 6px;">💎 Billing SaaS + 7 Hari Free Trial</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.5;">Mitra baru langsung menikmati 7 hari uji coba gratis. Tersedia paket Starter (Rp50rb), Pro (Rp150rb), dan Tahunan Juara.</p>
        </div>
      </div>

      <div class="browser-frame" style="height: 520px; border-color: rgba(245, 158, 11, 0.5);">
        <div class="browser-header">
          <div class="browser-dot dot-red"></div>
          <div class="browser-dot dot-yellow"></div>
          <div class="browser-dot dot-green"></div>
          <span class="browser-url">https://lapangankita.id/mitra/dashboard</span>
        </div>
        <img src="{dashboard_b64}" class="browser-content" alt="Dashboard Screenshot">
      </div>
    </div>

    <div class="slide-footer">
      <span>LapanganKita • Platform Ekosistem Olahraga Terpadu</span>
      <span>Halaman 7 dari 12</span>
    </div>
  </section>

  <!-- =========================================================================
       PAGE 8: INOVASI GEMINI AI (2 SYMMETRICAL COLUMNS)
       ========================================================================= -->
  <section class="slide">
    <div class="top-neon-bar"></div>
    <div class="slide-header">
      <div>
        <div class="header-pill">AI Innovation</div>
        <div class="slide-title">Inovasi Unggulan: Asisten Bisnis Gemini AI</div>
        <div class="slide-subtitle">Mengubah data transaksi mentah menjadi strategi bisnis konkret bagi pemilik lapangan.</div>
      </div>
      <img src="{logo_b64}" class="mini-logo" alt="logo">
    </div>

    <div class="grid-2">
      <div style="display: flex; flex-direction: column; gap: 20px;">
        <div class="card card-emerald" style="padding: 22px;">
          <h4 style="font-size: 18.5px; font-weight: 800; color: #34D399; margin-bottom: 6px;">🤖 Deteksi Jam Sepi Otomatis</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.5;">Gemini AI memindai data transaksi dan mengidentifikasi jam-jam dengan tingkat keterisian rendah secara mingguan.</p>
        </div>
        <div class="card card-cyan" style="padding: 22px;">
          <h4 style="font-size: 18.5px; font-weight: 800; color: #38BDF8; margin-bottom: 6px;">📈 Rekomendasi Diskon Dinamis</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.5;">Memberikan saran diskon spesifik (misal: Diskon 20% khusus Selasa-Kamis jam 13:00-16:00 untuk pelajar sekolah).</p>
        </div>
        <div class="card card-amber" style="padding: 22px;">
          <h4 style="font-size: 18.5px; font-weight: 800; color: #FBBF24; margin-bottom: 6px;">💬 Tanya Jawab Interaktif Real-Time</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.5;">Mitra dapat bertanya strategi apa saja langsung di halaman analitik; AI merespons berbasis data riil transaksi lapangan.</p>
        </div>
      </div>

      <div class="browser-frame" style="height: 520px;">
        <div class="browser-header">
          <div class="browser-dot dot-red"></div>
          <div class="browser-dot dot-yellow"></div>
          <div class="browser-dot dot-green"></div>
          <span class="browser-url">https://lapangankita.id/mitra/analytics</span>
        </div>
        <img src="{analytics_b64}" class="browser-content" alt="Analytics Screenshot">
      </div>
    </div>

    <div class="slide-footer">
      <span>LapanganKita • Platform Ekosistem Olahraga Terpadu</span>
      <span>Halaman 8 dari 12</span>
    </div>
  </section>

  <!-- =========================================================================
       PAGE 9: ALUR TRANSAKSI MIDTRANS (4 EQUAL COLUMNS)
       ========================================================================= -->
  <section class="slide">
    <div class="top-neon-bar"></div>
    <div class="slide-header">
      <div>
        <div class="header-pill">Payment Pipeline</div>
        <div class="slide-title">Alur Transaksi &amp; Keamanan Pembayaran (Midtrans)</div>
        <div class="slide-subtitle">Proses transaksi cepat 4 langkah yang dilindungi enkripsi SHA-512 Signature Key.</div>
      </div>
      <img src="{logo_b64}" class="mini-logo" alt="logo">
    </div>

    <div class="grid-4" style="align-items: stretch;">
      <div class="card card-cyan" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <span style="font-size: 13px; font-weight: 800; color: #38BDF8;">LANGKAH 1</span>
          <h4 style="font-size: 22px; font-weight: 800; color: #FFF; margin: 8px 0 12px 0;">Pilih Jadwal</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.6;">Pemain memilih tanggal, unit lapangan &amp; jam slot. Sistem mengunci sementara pilihan di database.</p>
        </div>
        <div style="font-size: 13px; font-weight: 700; color: #38BDF8;">🔒 Lock Sementara</div>
      </div>

      <div class="card card-emerald" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <span style="font-size: 13px; font-weight: 800; color: #34D399;">LANGKAH 2</span>
          <h4 style="font-size: 22px; font-weight: 800; color: #FFF; margin: 8px 0 12px 0;">Snap Token</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.6;">Laravel meminta Snap Token ke API Midtrans. Pop-up bayar interaktif langsung muncul di layar.</p>
        </div>
        <div style="font-size: 13px; font-weight: 700; color: #34D399;">⚡ Server-to-Server API</div>
      </div>

      <div class="card card-amber" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <span style="font-size: 13px; font-weight: 800; color: #FBBF24;">LANGKAH 3</span>
          <h4 style="font-size: 22px; font-weight: 800; color: #FFF; margin: 8px 0 12px 0;">Bayar Instan</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.6;">Pemain scan QRIS semua bank atau bayar lewat BCA, Mandiri, BRI Virtual Account &amp; e-Wallet.</p>
        </div>
        <div style="font-size: 13px; font-weight: 700; color: #FBBF24;">📱 Multi-Channel Support</div>
      </div>

      <div class="card card-emerald" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <span style="font-size: 13px; font-weight: 800; color: #FFF;">LANGKAH 4</span>
          <h4 style="font-size: 22px; font-weight: 800; color: #34D399; margin: 8px 0 12px 0;">Webhook &amp; Tiket</h4>
          <p style="font-size: 15.5px; color: #CBD5E1; line-height: 1.6;">Midtrans mengirim callback, sistem validasi SHA-512, status lunas otomatis dan E-Tiket PDF terbit.</p>
        </div>
        <div style="font-size: 13px; font-weight: 700; color: #34D399;">✔ 100% Terverifikasi</div>
      </div>
    </div>

    <!-- Security Certificate Card (Full-Width Symmetrical) -->
    <div class="card card-emerald" style="padding: 20px 30px; display: flex; align-items: center; justify-content: space-between;">
      <div style="display: flex; align-items: center; gap: 20px;">
        <div style="font-size: 36px;">🛡️</div>
        <div>
          <h4 style="font-size: 18.5px; font-weight: 800; color: #FFF;">Anti-Spoofing &amp; Validasi Programatik</h4>
          <p style="font-size: 14.5px; color: #94A3B8;">Tidak ada celah struk transfer palsu. Status bayar diverifikasi langsung secara server-to-server.</p>
        </div>
      </div>
      <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid #10B981; padding: 8px 24px; border-radius: 9999px; font-size: 14px; font-weight: 800; color: #34D399;">
        SHA-512 SIGNATURE CERTIFIED
      </div>
    </div>

    <div class="slide-footer">
      <span>LapanganKita • Platform Ekosistem Olahraga Terpadu</span>
      <span>Halaman 9 dari 12</span>
    </div>
  </section>

  <!-- =========================================================================
       PAGE 10: REAL UI GALLERY (SYMMETRICAL 2x2 GRID)
       ========================================================================= -->
  <section class="slide">
    <div class="top-neon-bar"></div>
    <div class="slide-header">
      <div>
        <div class="header-pill">Live UI Gallery</div>
        <div class="slide-title">Galeri Antarmuka Nyata (Live System Showcase)</div>
        <div class="slide-subtitle">Tangkapan layar aktual dari sistem LapanganKita yang telah berjalan aktif.</div>
      </div>
      <img src="{logo_b64}" class="mini-logo" alt="logo">
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; flex: 1; margin-bottom: 20px;">
      <div class="browser-frame" style="height: 270px;">
        <div class="browser-header">
          <div class="browser-dot dot-red"></div>
          <div class="browser-dot dot-yellow"></div>
          <div class="browser-dot dot-green"></div>
          <span class="browser-url">1. Beranda &amp; Pencarian (Landing Page)</span>
        </div>
        <img src="{landing_b64}" class="browser-content" alt="Landing">
      </div>

      <div class="browser-frame" style="height: 270px; border-color: rgba(6, 182, 212, 0.5);">
        <div class="browser-header">
          <div class="browser-dot dot-red"></div>
          <div class="browser-dot dot-yellow"></div>
          <div class="browser-dot dot-green"></div>
          <span class="browser-url">2. Pemilihan Jam &amp; Detail Venue</span>
        </div>
        <img src="{venue_b64}" class="browser-content" alt="Venue">
      </div>

      <div class="browser-frame" style="height: 270px; border-color: rgba(245, 158, 11, 0.5);">
        <div class="browser-header">
          <div class="browser-dot dot-red"></div>
          <div class="browser-dot dot-yellow"></div>
          <div class="browser-dot dot-green"></div>
          <span class="browser-url">3. Komunitas Mabar (Open Slot)</span>
        </div>
        <img src="{openslot_b64}" class="browser-content" alt="OpenSlot">
      </div>

      <div class="browser-frame" style="height: 270px;">
        <div class="browser-header">
          <div class="browser-dot dot-red"></div>
          <div class="browser-dot dot-yellow"></div>
          <div class="browser-dot dot-green"></div>
          <span class="browser-url">4. Dashboard &amp; Analitik Gemini AI</span>
        </div>
        <img src="{analytics_b64}" class="browser-content" alt="Analytics">
      </div>
    </div>

    <div class="slide-footer">
      <span>LapanganKita • Platform Ekosistem Olahraga Terpadu</span>
      <span>Halaman 10 dari 12</span>
    </div>
  </section>

  <!-- =========================================================================
       PAGE 11: METRIK (4 SYMMETRICAL CHIPS + COMPARISON CARD)
       ========================================================================= -->
  <section class="slide">
    <div class="top-neon-bar"></div>
    <div class="slide-header">
      <div>
        <div class="header-pill">Impact &amp; Metrics</div>
        <div class="slide-title">Dampak &amp; Nilai Tambah: Sebelum vs Sesudah</div>
        <div class="slide-subtitle">Hasil evaluasi terukur dari penerapan platform LapanganKita.</div>
      </div>
      <img src="{logo_b64}" class="mini-logo" alt="logo">
    </div>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 25px;">
      <div class="stat-chip card-emerald">
        <div class="stat-chip-num" style="color: #34D399;">0%</div>
        <div class="stat-chip-label">Double Booking</div>
      </div>
      <div class="stat-chip card-cyan">
        <div class="stat-chip-num" style="color: #38BDF8;">&lt; 60s</div>
        <div class="stat-chip-label">Waktu Pemesanan</div>
      </div>
      <div class="stat-chip card-amber">
        <div class="stat-chip-num" style="color: #FBBF24;">50:50</div>
        <div class="stat-chip-label">Split Bill Sparing</div>
      </div>
      <div class="stat-chip card-emerald">
        <div class="stat-chip-num" style="color: #FFF;">7 Hari</div>
        <div class="stat-chip-label">Free Trial SaaS</div>
      </div>
    </div>

    <div class="card" style="flex: 1; padding: 28px; display: flex; flex-direction: column; justify-content: space-around;">
      <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #1E293B; padding-bottom: 12px; font-size: 16.5px;">
        <span style="width: 25%; font-weight: 800; color: #94A3B8;">Pengecekan Jadwal</span>
        <span style="width: 35%; color: #F87171; font-weight: 600;">❌ Chat WA manual (10-15 menit)</span>
        <span style="width: 40%; color: #34D399; font-weight: 700;">✅ Matrix live online (&lt; 10 detik)</span>
      </div>
      <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #1E293B; padding-bottom: 12px; font-size: 16.5px;">
        <span style="width: 25%; font-weight: 800; color: #94A3B8;">Metode Pembayaran</span>
        <span style="width: 35%; color: #F87171; font-weight: 600;">❌ Kirim foto struk transfer manual</span>
        <span style="width: 40%; color: #34D399; font-weight: 700;">✅ Midtrans QRIS/VA otomatis terverifikasi</span>
      </div>
      <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #1E293B; padding-bottom: 12px; font-size: 16.5px;">
        <span style="width: 25%; font-weight: 800; color: #94A3B8;">Komunitas Mabar</span>
        <span style="width: 35%; color: #F87171; font-weight: 600;">❌ Sering batal karena personil kurang</span>
        <span style="width: 40%; color: #34D399; font-weight: 700;">✅ Open Slot patungan per orang</span>
      </div>
      <div style="display: flex; justify-content: space-between; align-items: center; font-size: 16.5px;">
        <span style="width: 25%; font-weight: 800; color: #94A3B8;">Pembukuan Mitra</span>
        <span style="width: 35%; color: #F87171; font-weight: 600;">❌ Buku tulis rentan hilang/manipulasi</span>
        <span style="width: 40%; color: #34D399; font-weight: 700;">✅ Laporan omset otomatis, PDF &amp; Gemini AI</span>
      </div>
    </div>

    <div class="slide-footer">
      <span>LapanganKita • Platform Ekosistem Olahraga Terpadu</span>
      <span>Halaman 11 dari 12</span>
    </div>
  </section>

  <!-- =========================================================================
       PAGE 12: ROADMAP (SIMPLER) & QNA (2 SYMMETRICAL COLUMNS)
       ========================================================================= -->
  <section class="slide">
    <div class="top-neon-bar"></div>
    <div class="slide-header">
      <div>
        <div class="header-pill">Roadmap &amp; QnA</div>
        <div class="slide-title">Rencana Fitur Tambahan &amp; Sesi Tanya Jawab</div>
        <div class="slide-subtitle">Peluang peningkatan layanan berikutnya dan sesi diskusi dewan penguji.</div>
      </div>
      <img src="{logo_b64}" class="mini-logo" alt="logo">
    </div>

    <div class="grid-2" style="align-items: stretch;">
      <div class="card card-cyan" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <h3 style="font-size: 25px; font-weight: 800; color: #38BDF8; margin-bottom: 20px;">💡 Rencana Fitur Tambahan (Mudah &amp; Praktis)</h3>
          <ul style="list-style: none; font-size: 16.5px; color: #CBD5E1; line-height: 2.2;">
            <li><strong>1. Pengingat WhatsApp Otomatis:</strong> Pesan konfirmasi &amp; pengingat H-2 jam sebelum main via WA Gateway agar pemain tidak lupa.</li>
            <li><strong>2. Poin Loyalitas &amp; Voucher Diskon:</strong> Pemain mengumpulkan poin di tiap booking yang dapat ditukar dengan voucher potongan harga.</li>
            <li><strong>3. Sewa Perlengkapan (Rompi &amp; Bola):</strong> Opsi checklist sewa rompi tim (Rp15.000) atau bola tambahan langsung saat checkout.</li>
            <li><strong>4. Ulasan &amp; Rating Bintang Venue:</strong> Fitur rating bintang 1–5 dan testimoni pemain untuk menjaga mutu fasilitas &amp; rumput lapangan.</li>
          </ul>
        </div>
        <div style="font-size: 14.5px; font-weight: 700; color: #38BDF8;">
          ✨ Fitur Tambahan yang Realistis &amp; Mudah Dikembangkan
        </div>
      </div>

      <div class="card card-emerald" style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 40px;">
        <img src="{logo_b64}" alt="Official Logo" style="width: 120px; height: 120px; border-radius: 28px; border: 3px solid #10B981; box-shadow: 0 0 30px rgba(16, 185, 129, 0.4); margin-bottom: 20px;">
        <h2 style="font-size: 42px; font-weight: 900; color: #FFF; margin-bottom: 8px;">TERIMA KASIH!</h2>
        <div style="font-size: 26px; font-weight: 800; color: #34D399; margin-bottom: 15px;">Sesi Tanya Jawab (Q &amp; A)</div>
        <p style="font-size: 16.5px; color: #94A3B8; max-width: 450px; line-height: 1.6; margin-bottom: 25px;">
          Kami siap menerima pertanyaan, masukan, dan evaluasi dari Bapak/Ibu Dewan Penguji serta rekan-rekan sekalian.
        </p>
        <div style="font-size: 18.5px; font-weight: 800; color: #FBBF24;">
          ⚽ LapanganKita: Main Futsal Tanpa Ribet!
        </div>
      </div>
    </div>

    <div class="slide-footer">
      <span>LapanganKita • Platform Ekosistem Olahraga Terpadu</span>
      <span>Halaman 12 dari 12</span>
    </div>
  </section>

</body>
</html>
"""

    temp_html_path = os.path.abspath("scratch/executive_deck.html")
    pdf_out_path = os.path.abspath("LapanganKita_Presentasi_Executive.pdf")

    with open(temp_html_path, "w", encoding="utf-8") as f:
        f.write(html_content)

    print(f"HTML rendered to {temp_html_path}")

    edge_path = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
    cmd = [
        edge_path,
        "--headless=new",
        "--disable-gpu",
        "--no-pdf-header-footer",
        f"--print-to-pdf={pdf_out_path}",
        temp_html_path
    ]
    subprocess.run(cmd, check=True)
    print(f"Executive PDF generated successfully at: {pdf_out_path}")

if __name__ == "__main__":
    generate_pdf()
