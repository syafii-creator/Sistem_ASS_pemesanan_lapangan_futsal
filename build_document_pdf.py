import os
import base64
import subprocess
import tempfile

def get_base64_img(path):
    if os.path.exists(path):
        with open(path, "rb") as f:
            data = base64.b64encode(f.read()).decode("utf-8")
            ext = os.path.splitext(path)[1].replace(".", "")
            if ext == "jpg": ext = "jpeg"
            return f"data:image/{ext};base64,{data}"
    return ""

logo_b64 = get_base64_img("public/img/logo.png")
landing_b64 = get_base64_img("scratch/landing.png")
venue_b64 = get_base64_img("scratch/venue_detail.png")
openslot_b64 = get_base64_img("scratch/open_slot.png")
analytics_b64 = get_base64_img("scratch/tenant_analytics.png")

html_content = f"""<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dokumentasi Proyek LapanganKita - Sistem Informasi Reservasi & SaaS Multi-Tenant</title>
<style>
    @page {{
        size: A4 portrait;
        margin: 14mm 16mm 14mm 16mm;
    }}

    * {{
        box-sizing: border-box;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }}

    body {{
        font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
        background-color: #FFFFFF;
        color: #1E293B;
        line-height: 1.5;
        font-size: 10pt;
        margin: 0;
        padding: 0;
    }}

    .page {{
        page-break-after: always;
        break-after: page;
        height: 269mm;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
    }}

    .page:last-child {{
        page-break-after: avoid;
        break-after: avoid;
    }}

    .content-wrap {{
        flex: 1;
    }}

    /* HEADER & BRAND */
    .header-bar {{
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 2px solid #059669;
        padding-bottom: 10px;
        margin-bottom: 18px;
    }}

    .header-logo-wrap {{
        display: flex;
        align-items: center;
        gap: 12px;
    }}

    .header-logo-wrap img {{
        height: 44px;
        border-radius: 8px;
    }}

    .brand-title {{
        font-size: 17pt;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: #0F172A;
        margin: 0;
    }}

    .brand-subtitle {{
        font-size: 8.5pt;
        color: #059669;
        font-weight: 600;
        margin: 0;
    }}

    .badge-year {{
        background-color: #0F172A;
        color: #10B981;
        font-weight: 700;
        font-size: 8pt;
        padding: 4px 10px;
        border-radius: 9999px;
        border: 1px solid #10B981;
    }}

    /* DEVELOPER CARD */
    .author-card {{
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
        color: #F8FAFC;
        padding: 14px 18px;
        border-radius: 8px;
        border-left: 5px solid #10B981;
        margin-bottom: 18px;
    }}

    .author-grid {{
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 8px;
        font-size: 9pt;
    }}

    .author-item span.label {{
        color: #94A3B8;
        display: block;
        font-size: 7.5pt;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }}

    .author-item span.value {{
        font-weight: 600;
        color: #FFFFFF;
    }}

    /* METRIC HIGHLIGHTS */
    .kpi-container {{
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }}

    .kpi-card {{
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        border-top: 3px solid #10B981;
    }}

    .kpi-value {{
        font-size: 17pt;
        font-weight: 800;
        color: #059669;
        line-height: 1.1;
        margin-bottom: 2px;
    }}

    .kpi-title {{
        font-size: 7.5pt;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }}

    /* SECTION TITLES */
    h2.section-heading {{
        font-size: 12pt;
        color: #0F172A;
        font-weight: 800;
        border-left: 4px solid #059669;
        padding-left: 10px;
        margin-top: 18px;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }}

    h3.sub-heading {{
        font-size: 10pt;
        color: #1E293B;
        font-weight: 700;
        margin-top: 10px;
        margin-bottom: 6px;
    }}

    p {{
        margin: 0 0 8px 0;
        color: #334155;
    }}

    /* GRIDS */
    .grid-2 {{
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 14px;
    }}

    .grid-3 {{
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 14px;
    }}

    .card {{
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 12px 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }}

    .card-primary {{ border-top: 3px solid #059669; }}
    .card-cyan {{ border-top: 3px solid #0284C7; }}
    .card-indigo {{ border-top: 3px solid #6366F1; }}

    .card-title {{
        font-size: 10pt;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 4px;
    }}

    .card-tag {{
        display: inline-block;
        font-size: 7pt;
        font-weight: 700;
        text-transform: uppercase;
        padding: 2px 7px;
        border-radius: 4px;
        margin-bottom: 6px;
    }}

    .tag-emerald {{ background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }}
    .tag-blue {{ background: #EFF6FF; color: #1E40AF; border: 1px solid #BFDBFE; }}
    .tag-indigo {{ background: #EEF2FF; color: #3730A3; border: 1px solid #C7D2FE; }}

    ul.clean-list {{
        list-style: none;
        padding-left: 0;
        margin: 6px 0 0 0;
    }}

    ul.clean-list li {{
        position: relative;
        padding-left: 16px;
        margin-bottom: 6px;
        font-size: 9pt;
        color: #334155;
        line-height: 1.4;
    }}

    ul.clean-list li::before {{
        content: "•";
        position: absolute;
        left: 3px;
        color: #059669;
        font-weight: bold;
        font-size: 12pt;
        line-height: 1;
    }}

    /* STEP FLOW ARCHITECTURE */
    .steps-row {{
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin: 14px 0;
    }}

    .step-box {{
        background: #F8FAFC;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        padding: 12px;
        position: relative;
    }}

    .step-badge {{
        font-size: 7pt;
        font-weight: 800;
        background: #059669;
        color: #FFFFFF;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 6px;
    }}

    .step-title {{
        font-size: 9pt;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 4px;
    }}

    .step-desc {{
        font-size: 8pt;
        color: #475569;
        line-height: 1.35;
    }}

    .security-banner {{
        background: #F0FDF4;
        border: 1px solid #86EFAC;
        border-radius: 8px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 14px;
    }}

    .security-badge {{
        background: #166534;
        color: #DCFCE7;
        font-size: 7.5pt;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 6px;
        white-space: nowrap;
    }}

    /* TABLE */
    table.data-table {{
        width: 100%;
        border-collapse: collapse;
        margin: 12px 0;
        font-size: 8.5pt;
    }}

    table.data-table th, table.data-table td {{
        border: 1px solid #CBD5E1;
        padding: 8px 10px;
        text-align: left;
    }}

    table.data-table th {{
        background-color: #0F172A;
        color: #FFFFFF;
        font-weight: 700;
        font-size: 8pt;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }}

    table.data-table tr:nth-child(even) {{
        background-color: #F8FAFC;
    }}

    .text-bad {{ color: #B91C1C; font-weight: 600; }}
    .text-good {{ color: #15803D; font-weight: 700; }}

    /* GALLERY IMAGES */
    .gallery-grid {{
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-top: 10px;
    }}

    .gallery-item {{
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        overflow: hidden;
        background: #F8FAFC;
    }}

    .gallery-item img {{
        width: 100%;
        height: 185px;
        object-fit: cover;
        display: block;
        border-bottom: 1px solid #E2E8F0;
    }}

    .gallery-caption {{
        padding: 8px 12px;
        font-size: 8.5pt;
        font-weight: 700;
        color: #1E293B;
        background: #FFFFFF;
    }}

    .gallery-subtext {{
        font-size: 7.5pt;
        color: #64748B;
        padding: 0 12px 8px 12px;
        background: #FFFFFF;
    }}

    /* FOOTER */
    .doc-footer {{
        border-top: 1px solid #E2E8F0;
        padding-top: 8px;
        display: flex;
        justify-content: space-between;
        font-size: 8pt;
        color: #94A3B8;
    }}
</style>
</head>
<body>

<!-- ==================== HALAMAN 1: COVER & SOLUSI TERPADU ==================== -->
<div class="page">
    <div class="content-wrap">
        <div class="header-bar">
            <div class="header-logo-wrap">
                <img src="{logo_b64}" alt="Logo LapanganKita">
                <div>
                    <h1 class="brand-title">LAPANGANKITA</h1>
                    <p class="brand-subtitle">Platform Reservasi Lapangan Futsal Real-Time, Komunitas Mabar & SaaS Multi-Tenant</p>
                </div>
            </div>
            <div class="badge-year">TAHUN 2026</div>
        </div>

        <div class="author-card">
            <div style="font-weight: 700; font-size: 10.5pt; color: #34D399; margin-bottom: 2px;">INFORMASI PRESENTASI & PENGEMBANG SISTEM</div>
            <div class="author-grid">
                <div class="author-item">
                    <span class="label">Nama Pengembang</span>
                    <span class="value">Muhammad Syafii Maarif</span>
                </div>
                <div class="author-item">
                    <span class="label">Program Studi & Institusi</span>
                    <span class="value">Sistem Informasi — Universitas Duta Bangsa</span>
                </div>
                <div class="author-item" style="grid-column: span 2;">
                    <span class="label">Teknologi Utama (Tech Stack)</span>
                    <span class="value">Laravel 11 • Tailwind CSS • Midtrans Snap API • MySQL Relational Database</span>
                </div>
            </div>
        </div>

        <div class="kpi-container">
            <div class="kpi-card">
                <div class="kpi-value">0%</div>
                <div class="kpi-title">Bentrok Jadwal</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-value">&lt; 60s</div>
                <div class="kpi-title">Waktu Pemesanan</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-value">50:50</div>
                <div class="kpi-title">Patungan Otomatis</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-value">100%</div>
                <div class="kpi-title">Rekap Laporan PDF</div>
            </div>
        </div>

        <h2 class="section-heading">Solusi Terpadu: Tiga Modul Utama LapanganKita</h2>
        <p style="font-size: 9pt; color: #475569; margin-bottom: 12px;">Dirancang terintegrasi guna menjawab kebutuhan operasional pemain publik, komunitas futsal, dan pemilik venue bisnis secara efisien.</p>

        <div class="grid-3">
            <div class="card card-primary">
                <span class="card-tag tag-emerald">MODUL 1</span>
                <div class="card-title">Reservasi & Pembayaran</div>
                <ul class="clean-list">
                    <li>Pilih tanggal dan slot jam secara visual & interaktif.</li>
                    <li>Jadwal real-time anti-bentrok (100% akurat).</li>
                    <li>Pembayaran instan via Midtrans (QRIS & Virtual Account).</li>
                    <li>E-Tiket resmi ber-QR Code terbit seketika.</li>
                </ul>
            </div>
            <div class="card card-cyan">
                <span class="card-tag tag-blue">MODUL 2</span>
                <div class="card-title">Pusat Komunitas (Mabar)</div>
                <ul class="clean-list">
                    <li>Open Slot Patungan: Main solo tanpa takut kurang kawan.</li>
                    <li>Kalkulator biaya patungan otomatis per individu.</li>
                    <li>Lobi Sparing: Tantang tim lawan dengan sistem bagi rata 50:50.</li>
                    <li>Akses instan 1-klik menggunakan Akun Google (SSO).</li>
                </ul>
            </div>
            <div class="card card-indigo">
                <span class="card-tag tag-indigo">MODUL 3</span>
                <div class="card-title">Manajemen Pengelola (SaaS)</div>
                <ul class="clean-list">
                    <li>Dashboard kasir offline: Kunci slot langsung di tempat (walk-in).</li>
                    <li>Pengaturan tarif fleksibel & paket langganan sistem.</li>
                    <li>Ekspor laporan omset dan rekapan finansial format PDF.</li>
                    <li>Analisis statistik keterisian jam operasional (okupansi).</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="doc-footer">
        <span>LapanganKita — Sistem Informasi Reservasi Lapangan Futsal Real-Time & Multi-Tenant</span>
        <span>Halaman 1 dari 6</span>
    </div>
</div>

<!-- ==================== HALAMAN 2: RBAC & PENGALAMAN PEMAIN ==================== -->
<div class="page">
    <div class="content-wrap">
        <div class="header-bar">
            <div class="header-logo-wrap">
                <img src="{logo_b64}" alt="Logo LapanganKita">
                <div>
                    <h1 class="brand-title">LAPANGANKITA</h1>
                    <p class="brand-subtitle">Hak Akses Pengguna (RBAC) & Alur Pengalaman Pemain</p>
                </div>
            </div>
            <div class="badge-year">ARSITEKTUR PERAN</div>
        </div>

        <h2 class="section-heading">Hak Akses & Peran: Tiga Peran Pengguna (RBAC)</h2>
        <p style="font-size: 9pt; color: #475569; margin-bottom: 12px;">Pemisahan hak akses yang ketat, terisolasi, dan aman menggunakan layer Middleware Laravel 11.</p>

        <div class="grid-3">
            <div class="card card-primary">
                <span class="card-tag tag-emerald">PENGGUNA UTAMA</span>
                <div class="card-title">Pemain (Customer)</div>
                <ul class="clean-list">
                    <li>Cari venue dan periksa fasilitas lapangan.</li>
                    <li>Pesan jadwal lapangan dan bayar online.</li>
                    <li>Unduh E-Tiket dan invoice bukti bayar PDF.</li>
                    <li>Gabung room Open Slot patungan pemain solo.</li>
                    <li>Kirim dan terima tantangan laga Sparing antar klub.</li>
                </ul>
            </div>
            <div class="card card-cyan">
                <span class="card-tag tag-blue">PENGELOLA BISNIS VENUE</span>
                <div class="card-title">Mitra (Tenant Lapangan)</div>
                <ul class="clean-list">
                    <li>Manajemen data lapangan dan pengaturan tarif dinamis.</li>
                    <li>Kunci slot jadwal kasir langsung di tempat (walk-in).</li>
                    <li>Pantau rekapitulasi omset harian, mingguan, dan bulanan.</li>
                    <li>Cetak dokumen laporan keuangan resmi siap audit format PDF.</li>
                    <li>Pantau statistik jam ramai dan jam sepi lapangan.</li>
                </ul>
            </div>
            <div class="card card-indigo">
                <span class="card-tag tag-indigo">ADMINISTRATOR PUSAT</span>
                <div class="card-title">Super Admin (Platform)</div>
                <ul class="clean-list">
                    <li>Monitoring seluruh aktivitas transaksi platform secara global.</li>
                    <li>Aktivasi, verifikasi legalitas, atau penangguhan akun mitra.</li>
                    <li>Kelola master paket langganan SaaS (Starter, Pro, Tahunan).</li>
                    <li>Audit trail catatan aktivitas keamanan dan error log sistem.</li>
                </ul>
            </div>
        </div>

        <h2 class="section-heading">Pengalaman Pemain: Reservasi & Pembayaran Kilat</h2>
        <p style="font-size: 9pt; color: #475569; margin-bottom: 12px;">Memberikan kenyamanan pemesanan lapangan dalam 3 langkah mudah dengan konfirmasi pembayaran otomatis.</p>

        <div class="grid-2">
            <div class="card">
                <div class="card-title">1. Matriks Jadwal Real-Time</div>
                <p style="font-size: 8.5pt; color: #475569; margin-bottom: 10px;">Pilih jam kosong visual layaknya memilih kursi bioskop. Slot yang sudah terisi otomatis terkunci secara presisi oleh sistem database tanpa risiko bentrok ganda (double-booking).</p>
                <div class="card-title">2. Integrasi Midtrans Payment Gateway</div>
                <p style="font-size: 8.5pt; color: #475569; margin-bottom: 0;">Pembayaran langsung di layar tanpa repot pindah aplikasi atau kirim foto resi: Cukup Scan QRIS (BCA, GoPay, OVO, Dana) atau Virtual Account bank terkemuka.</p>
            </div>
            <div class="card">
                <div class="card-title">3. E-Tiket PDF Ber-QR Code</div>
                <p style="font-size: 8.5pt; color: #475569; margin-bottom: 10px;">Tiket resmi terbit detik itu juga saat status transaksi lunas. Pengguna cukup menunjukkan barcode QR Code ke petugas di lokasi lapangan untuk validasi check-in instan.</p>
                <div class="card-title">4. Autentikasi Akun Google SSO</div>
                <p style="font-size: 8.5pt; color: #475569; margin-bottom: 0;">Daftar dan masuk dalam 1 ketukan cepat menggunakan Single Sign-On (SSO) Google tanpa perlu repot menghafal kata sandi baru atau verifikasi manual.</p>
            </div>
        </div>
    </div>

    <div class="doc-footer">
        <span>LapanganKita — Sistem Informasi Reservasi Lapangan Futsal Real-Time & Multi-Tenant</span>
        <span>Halaman 2 dari 6</span>
    </div>
</div>

<!-- ==================== HALAMAN 3: KOMUNITAS & OPERASIONAL MITRA ==================== -->
<div class="page">
    <div class="content-wrap">
        <div class="header-bar">
            <div class="header-logo-wrap">
                <img src="{logo_b64}" alt="Logo LapanganKita">
                <div>
                    <h1 class="brand-title">LAPANGANKITA</h1>
                    <p class="brand-subtitle">Fitur Komunitas Olahraga & Operasional SaaS Pemilik Lapangan</p>
                </div>
            </div>
            <div class="badge-year">FITUR UNGGULAN</div>
        </div>

        <h2 class="section-heading">Fitur Komunitas: Open Slot Mabar & Laga Sparing</h2>
        <p style="font-size: 9pt; color: #475569; margin-bottom: 12px;">Solusi praktis dan inklusif bagi pemain solo serta klub olahraga yang mencari lawan tanding sehat.</p>

        <div class="grid-3">
            <div class="card card-primary">
                <div class="card-title">1. Open Slot Mabar (Pemain Solo)</div>
                <p style="font-size: 8.5pt; color: #475569;">Penyelenggara membuka sesi mabar, tentukan kuota peserta, dan sistem secara otomatis menghitung tarif patungan per individu. Sesi futsal tidak pernah batal meski pemain awal kurang personil.</p>
            </div>
            <div class="card card-cyan">
                <div class="card-title">2. Lobi Sparing (Tantang Tim Lawan)</div>
                <p style="font-size: 8.5pt; color: #475569;">Klub futsal dapat mempublikasikan tantangan bertanding di platform. Total biaya sewa lapangan dibagi rata 50% untuk masing-masing tim secara otomatis melalui mekanisme Split-Bill.</p>
            </div>
            <div class="card card-indigo">
                <div class="card-title">3. Invoice Transparan Per Anggota</div>
                <p style="font-size: 8.5pt; color: #475569;">Setiap peserta yang membayar patungan mendapatkan invoice PDF resmi dengan rincian biaya yang sah dan transparan, menghapus kecurigaan dalam pengelolaan uang kas olahraga.</p>
            </div>
        </div>

        <h2 class="section-heading">Operasional Mitra: Kasir Langsung & Pengelolaan Lapangan</h2>
        <p style="font-size: 9pt; color: #475569; margin-bottom: 12px;">Digitalisasi menyeluruh bagi pemilik venue untuk mengontrol operasional harian dan memaksimalkan pendapatan.</p>

        <div class="grid-2">
            <div class="card">
                <div class="card-title">1. Kunci Slot Kasir Offline (Anti-Bentrok)</div>
                <p style="font-size: 8.5pt; color: #475569; margin-bottom: 10px;">Satu klik cepat bagi petugas kasir untuk mengunci slot jam di sistem jika ada penyewa tunai / walk-in yang datang langsung ke meja kasir, mencegah rebutan dengan pemesan online.</p>
                <div class="card-title">2. Tarif Dinamis (Reguler vs Jam Ramai)</div>
                <p style="font-size: 8.5pt; color: #475569; margin-bottom: 0;">Bebas mengatur strategi tarif lebih hemat di siang hari kerja (low hours) dan tarif premium di malam hari atau akhir pekan (peak hours) untuk mengoptimalkan tingkat okupansi.</p>
            </div>
            <div class="card">
                <div class="card-title">3. Sistem Langganan SaaS Fleksibel</div>
                <p style="font-size: 8.5pt; color: #475569; margin-bottom: 10px;">Mitra baru mendapatkan uji coba gratis 7 hari (Free Trial). Tersedia pilihan paket Starter, Pro, dan Tahunan sesuai dengan skala jumlah unit lapangan yang dikelola.</p>
                <div class="card-title">4. Laporan Finansial Siap Cetak PDF</div>
                <p style="font-size: 8.5pt; color: #475569; margin-bottom: 0;">Pantau ringkasan omset berkala dan ekspor dokumen pembukuan resmi dalam format PDF yang rapi, akurat, dan siap untuk bahan pertanggungjawaban audit usaha.</p>
            </div>
        </div>
    </div>

    <div class="doc-footer">
        <span>LapanganKita — Sistem Informasi Reservasi Lapangan Futsal Real-Time & Multi-Tenant</span>
        <span>Halaman 3 dari 6</span>
    </div>
</div>

<!-- ==================== HALAMAN 4: ANALITIK & MIDTRANS SECURITY ==================== -->
<div class="page">
    <div class="content-wrap">
        <div class="header-bar">
            <div class="header-logo-wrap">
                <img src="{logo_b64}" alt="Logo LapanganKita">
                <div>
                    <h1 class="brand-title">LAPANGANKITA</h1>
                    <p class="brand-subtitle">Modul Analitik Bisnis & Arsitektur Keamanan Transaksi Midtrans</p>
                </div>
            </div>
            <div class="badge-year">KEAMANAN & ANALITIK</div>
        </div>

        <h2 class="section-heading">Modul Analitik: Statistik Keterisian & Pendapatan Lapangan</h2>
        <p style="font-size: 9pt; color: #475569; margin-bottom: 12px;">Membantu pengelola memantau performa bisnis dan mendeteksi tren keterisian lapangan berbasis data nyata (Data-Driven Decision).</p>

        <div class="grid-2">
            <div class="card">
                <div class="card-title">1. Pemetaan Jam Okupansi Riil</div>
                <p style="font-size: 8.5pt; color: #475569; margin-bottom: 10px;">Sistem memetakan jam-jam dengan tingkat keterisian tinggi (ramai) dan jam sepi berdasarkan riwayat transaksi nyata di database, memberikan gambaran jelas jam operasional terbaik.</p>
                <div class="card-title">2. Pengaturan Tarif Khusus Jam Sepi</div>
                <p style="font-size: 8.5pt; color: #475569; margin-bottom: 0;">Pengelola dapat membuat strategi tarif pelajar atau diskon khusus di jam kerja (weekday siang) agar lapangan tidak kosong dan pendapatan terus terdistribusi merata.</p>
            </div>
            <div class="card">
                <div class="card-title">3. Grafik Tren Pendapatan Berkala</div>
                <p style="font-size: 8.5pt; color: #475569; margin-bottom: 10px;">Menampilkan grafik omset mingguan dan bulanan secara visual guna mengevaluasi pertumbuhan bisnis tanpa rekapan manual atau kalkulasi kalkulator konvensional.</p>
                <div class="card-title" style="margin-top: 10px;">4. Ekspor Rekap Finansial PDF</div>
                <p style="font-size: 8.5pt; color: #475569; margin-bottom: 0;">Mencetak bukti laporan pendapatan dan rincian transaksi per periode waktu dalam bentuk file PDF resmi siap audit yang dapat diunduh kapan saja oleh pengelola.</p>
            </div>
        </div>

        <h2 class="section-heading">Alur Transaksi & Keamanan Pembayaran (Midtrans Snap)</h2>
        <p style="font-size: 9pt; color: #475569; margin-bottom: 8px;">Proses transaksi cepat 4 langkah yang dilindungi enkripsi SHA-512 Signature Key antar server.</p>

        <div class="steps-row">
            <div class="step-box">
                <span class="step-badge">LANGKAH 1</span>
                <div class="step-title">Pilih Jadwal</div>
                <div class="step-desc">Pemain memilih tanggal, unit lapangan & jam slot. Database mengunci sementara pilihan.</div>
            </div>
            <div class="step-box">
                <span class="step-badge">LANGKAH 2</span>
                <div class="step-title">Token Snap API</div>
                <div class="step-desc">Laravel meminta Snap Token ke API Midtrans. Jendela pembayaran instan muncul di layar.</div>
            </div>
            <div class="step-box">
                <span class="step-badge">LANGKAH 3</span>
                <div class="step-title">Bayar Instan</div>
                <div class="step-desc">Pengguna scan QRIS atau transfer via Virtual Account. Transaksi lunas dalam hitungan detik.</div>
            </div>
            <div class="step-box">
                <span class="step-badge">LANGKAH 4</span>
                <div class="step-title">Verifikasi Webhook</div>
                <div class="step-desc">Midtrans kirim webhook HTTP POST. Sistem validasi signature SHA-512, tiket PDF terbit.</div>
            </div>
        </div>

        <div class="security-banner">
            <span class="security-badge">KEAMANAN TINGGI</span>
            <div style="font-size: 8.5pt; color: #166534; line-height: 1.45;">
                <strong>Jaminan Keamanan: Dilindungi Verifikasi Tanda Tangan Digital SHA-512.</strong><br>
                Status pembayaran diverifikasi langsung oleh server Midtrans secara otomatis menggunakan perbandingan cryptographic hash. Bebas 100% dari risiko manipulasi bukti transfer palsu atau order injeksi liar.
            </div>
        </div>
    </div>

    <div class="doc-footer">
        <span>LapanganKita — Sistem Informasi Reservasi Lapangan Futsal Real-Time & Multi-Tenant</span>
        <span>Halaman 4 dari 6</span>
    </div>
</div>

<!-- ==================== HALAMAN 5: GALERI ANTARMUKA SISTEM ==================== -->
<div class="page">
    <div class="content-wrap">
        <div class="header-bar">
            <div class="header-logo-wrap">
                <img src="{logo_b64}" alt="Logo LapanganKita">
                <div>
                    <h1 class="brand-title">LAPANGANKITA</h1>
                    <p class="brand-subtitle">Galeri Antarmuka Sistem (Implementasi Nyata Hasil Pengembangan)</p>
                </div>
            </div>
            <div class="badge-year">DOKUMENTASI UI</div>
        </div>

        <h2 class="section-heading">Implementasi Antarmuka Sistem (Hasil Pengembangan)</h2>
        <p style="font-size: 9pt; color: #475569; margin-bottom: 10px;">Dokumentasi tangkapan layar dari sistem LapanganKita yang telah berjalan aktif dan siap digunakan secara luas:</p>

        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="{landing_b64}" alt="Beranda & Pencarian Venue">
                <div class="gallery-caption">lapangankita.id — 1. Beranda & Pencarian Venue</div>
                <div class="gallery-subtext">Halaman katalog venue interaktif dengan filter lokasi, rating fasilitas, dan rentang harga lapangan futsal.</div>
            </div>
            <div class="gallery-item">
                <img src="{venue_b64}" alt="Pemilihan Jam & Detail Venue">
                <div class="gallery-caption">lapangankita.id — 2. Pemilihan Jam & Detail Venue</div>
                <div class="gallery-subtext">Matriks visual slot jam real-time yang membedakan status slot kosong, sedang dipilih, atau telah terisi.</div>
            </div>
            <div class="gallery-item">
                <img src="{openslot_b64}" alt="Komunitas Mabar Open Slot">
                <div class="gallery-caption">lapangankita.id — 3. Komunitas Mabar Open Slot</div>
                <div class="gallery-subtext">Lobi pencarian room patungan untuk pemain solo lengkap dengan kuota pemain dan tarif split otomatis.</div>
            </div>
            <div class="gallery-item">
                <img src="{analytics_b64}" alt="Dashboard Analitik Pengelola">
                <div class="gallery-caption">lapangankita.id — 4. Dashboard Analitik Pengelola</div>
                <div class="gallery-subtext">Visualisasi tingkat okupansi jam ramai/sepi harian serta grafik tren omset finansial bulanan pengelola venue.</div>
            </div>
        </div>
    </div>

    <div class="doc-footer">
        <span>LapanganKita — Sistem Informasi Reservasi Lapangan Futsal Real-Time & Multi-Tenant</span>
        <span>Halaman 5 dari 6</span>
    </div>
</div>

<!-- ==================== HALAMAN 6: EVALUASI, ROADMAP & DISKUSI ==================== -->
<div class="page">
    <div class="content-wrap">
        <div class="header-bar">
            <div class="header-logo-wrap">
                <img src="{logo_b64}" alt="Logo LapanganKita">
                <div>
                    <h1 class="brand-title">LAPANGANKITA</h1>
                    <p class="brand-subtitle">Evaluasi Dampak Sistem, Rencana Pengembangan & Lembar Diskusi</p>
                </div>
            </div>
            <div class="badge-year">PENUTUP & ROADMAP</div>
        </div>

        <h2 class="section-heading">Evaluasi Sistem: Perbandingan Metode Konvensional vs LapanganKita</h2>
        <p style="font-size: 8.5pt; color: #475569; margin-bottom: 8px;">Efisiensi dan nilai tambah yang diperoleh setelah penerapan arsitektur digital LapanganKita secara terukur:</p>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 20%;">Aspek Layanan</th>
                    <th style="width: 40%;">Metode Konvensional (Sebelum)</th>
                    <th style="width: 40%;">Platform LapanganKita (Sesudah)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Pengecekan Jadwal</strong></td>
                    <td class="text-bad">Chat WhatsApp manual, menunggu admin membalas (10–15 menit), rentan jadwal bentrok.</td>
                    <td class="text-good">Matriks Real-Time interaktif: slot kosong terlihat seketika (&lt; 10 detik), anti-bentrok 100%.</td>
                </tr>
                <tr>
                    <td><strong>Metode Pembayaran</strong></td>
                    <td class="text-bad">Transfer rekening bank manual, wajib kirim foto resi transfer via WA yang rawan diedit/dipalsukan.</td>
                    <td class="text-good">Payment Gateway Midtrans (QRIS / VA) otomatis, verifikasi SHA-512, tiket PDF terbit seketika.</td>
                </tr>
                <tr>
                    <td><strong>Komunitas Mabar</strong></td>
                    <td class="text-bad">Sulit mencari kawan main solo, sering batal jadwal karena kurang pemain, split uang kas ribet.</td>
                    <td class="text-good">Room Open Slot patungan otomatis & Lobi Sparing match dengan sistem bagi rata biaya sewa 50:50.</td>
                </tr>
                <tr>
                    <td><strong>Pembukuan Mitra</strong></td>
                    <td class="text-bad">Buku tulis fisik atau spreadsheet manual yang rawan hilang, rusak, dan rentan salah hitung.</td>
                    <td class="text-good">Rekapitulasi omset otomatis, visualisasi grafik okupansi jam operasional & ekspor laporan PDF resmi.</td>
                </tr>
            </tbody>
        </table>

        <h2 class="section-heading">Rencana Pengembangan Lanjutan (Roadmap Fitur Mendatang)</h2>
        <div class="grid-2">
            <div class="card">
                <div class="card-title">1. Pengingat WhatsApp Otomatis (WA Reminder)</div>
                <p style="font-size: 8pt; color: #475569; margin-bottom: 8px;">Broadcast pesan pengingat jadwal booking H-2 jam sebelum bermain via WhatsApp Gateway API agar pemain tidak terlambat datang ke lapangan.</p>
                <div class="card-title">2. Poin Loyalitas & Voucher Diskon</div>
                <p style="font-size: 8pt; color: #475569; margin-bottom: 0;">Pengguna mengumpulkan poin setiap transaksi reservasi yang dapat ditukarkan dengan kupon diskon sewa lapangan pada pemesanan selanjutnya.</p>
            </div>
            <div class="card">
                <div class="card-title">3. Sewa Perlengkapan Tambahan (Add-On)</div>
                <p style="font-size: 8pt; color: #475569; margin-bottom: 8px;">Opsi checklist saat checkout untuk menyewa rompi tim, bola futsal resmi, atau sarung tangan kiper langsung dalam satu nota pembayaran terpadu.</p>
                <div class="card-title">4. Ulasan & Rating Bintang Venue</div>
                <p style="font-size: 8pt; color: #475569; margin-bottom: 0;">Fitur rating 1–5 bintang dan review komunitas untuk menjaga kualitas fasilitas, kebersihan rumput sintetis/vinyl, dan layanan staf venue.</p>
            </div>
        </div>

        <div class="author-card" style="margin-top: 14px; text-align: center; border-left: none; border-top: 3px solid #10B981; padding: 12px 16px;">
            <div style="font-size: 13pt; font-weight: 800; color: #34D399; margin-bottom: 2px;">TERIMA KASIH</div>
            <div style="font-size: 9.5pt; color: #F8FAFC; font-weight: 700;">Sesi Tanya Jawab & Diskusi Terbuka</div>
            <p style="font-size: 8pt; color: #94A3B8; margin-top: 4px; margin-bottom: 0;">Kami menyambut baik pertanyaan, masukan konstruktif, dan evaluasi dari Bapak/Ibu Dewan Penguji serta rekan-rekan sekalian demi pengembangan platform LapanganKita.</p>
        </div>
    </div>

    <div class="doc-footer">
        <span>LapanganKita — Sistem Informasi Reservasi Lapangan Futsal Real-Time & Multi-Tenant</span>
        <span>Halaman 6 dari 6</span>
    </div>
</div>

</body>
</html>
"""

html_path = os.path.abspath("scratch/Dokumentasi_Sistem_LapanganKita.html")
pdf_path = os.path.abspath("LapanganKita_Dokumentasi_Sistem.pdf")

with open(html_path, "w", encoding="utf-8") as f:
    f.write(html_content)

print(f"HTML updated at: {html_path}")

user_dir = tempfile.mkdtemp()
chrome_exe = r"C:\Program Files\Google\Chrome\Application\chrome.exe"

cmd = [
    chrome_exe,
    "--headless",
    "--disable-gpu",
    "--no-sandbox",
    f"--user-data-dir={user_dir}",
    "--no-pdf-header-footer",
    f"--print-to-pdf={pdf_path}",
    html_path
]

print("Rendering PDF with Chrome headless...")
res = subprocess.run(cmd, capture_output=True, timeout=25)
print(f"Return code: {res.returncode}")
print(f"PDF exists: {os.path.exists(pdf_path)}")
if os.path.exists(pdf_path):
    print(f"PDF size: {os.path.getsize(pdf_path)} bytes")
