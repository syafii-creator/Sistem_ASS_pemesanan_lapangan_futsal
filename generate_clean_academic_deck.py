import os
import re
from pptx import Presentation
from pptx.util import Inches, Pt
from pptx.enum.text import PP_ALIGN
from pptx.dml.color import RGBColor
from pptx.enum.shapes import MSO_SHAPE
from pptx.oxml import parse_xml
from pptx.oxml.ns import nsdecls

FONT_FAMILY = "Times New Roman"

def build_formal_academic_pptx():
    prs = Presentation()
    prs.slide_width = Inches(13.333)
    prs.slide_height = Inches(7.5)
    blank_layout = prs.slide_layouts[6]

    LOGO_PATH = os.path.abspath("public/img/logo.png")
    IMG_LANDING = os.path.abspath("scratch/landing.png")
    IMG_VENUE = os.path.abspath("scratch/venue_detail.png")
    IMG_OPENSLOT = os.path.abspath("scratch/open_slot.png")
    IMG_DASHBOARD = os.path.abspath("scratch/tenant_dashboard.png")
    IMG_ANALYTICS = os.path.abspath("scratch/tenant_analytics.png")

    # Formal Academic Color Palette
    BG_DARK = RGBColor(10, 15, 29)          # #0A0F1D (Deep Navy)
    BG_CARD = RGBColor(22, 31, 51)          # #161F33 (Card Surface)
    BORDER_EMERALD = RGBColor(16, 185, 129) # #10B981
    BORDER_CYAN = RGBColor(6, 182, 212)      # #06B6D4
    BORDER_MUTED = RGBColor(39, 52, 77)      # #27344D
    BORDER_AMBER = RGBColor(245, 158, 11)    # #F59E0B
    BORDER_RED = RGBColor(239, 68, 68)       # #EF4444

    COLOR_MINT = RGBColor(52, 211, 153)      # #34D399
    COLOR_CYAN = RGBColor(56, 189, 248)      # #38BDF8
    COLOR_WHITE = RGBColor(255, 255, 255)
    COLOR_SLATE = RGBColor(226, 232, 240)    # #E2E8F0
    COLOR_MUTED = RGBColor(148, 163, 184)    # #94A3B8
    COLOR_AMBER = RGBColor(251, 191, 36)     # #FBBF24

    def add_transition(slide, trans_type="push", direction="l"):
        if trans_type == "push":
            xml = f'<p:transition {nsdecls("p")} spd="fast"><p:push dir="{direction}"/></p:transition>'
        elif trans_type == "fade":
            xml = f'<p:transition {nsdecls("p")} spd="med"><p:fade/></p:transition>'
        elif trans_type == "zoom":
            xml = f'<p:transition {nsdecls("p")} spd="fast"><p:zoom/></p:transition>'
        else:
            xml = f'<p:transition {nsdecls("p")} spd="med"><p:fade/></p:transition>'
        slide._element.append(parse_xml(xml))

    def create_base_slide(pill_text, title_text, subtitle_text="", trans_type="push", trans_dir="l"):
        slide = prs.slides.add_slide(blank_layout)
        add_transition(slide, trans_type, trans_dir)

        # 1. Background Shape
        bg = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, 0, 0, Inches(13.333), Inches(7.5))
        bg.fill.solid()
        bg.fill.fore_color.rgb = BG_DARK
        bg.line.fill.background()

        # 2. Top Accent Line
        top_neon = slide.shapes.add_shape(MSO_SHAPE.RECTANGLE, 0, 0, Inches(13.333), Inches(0.08))
        top_neon.fill.solid()
        top_neon.fill.fore_color.rgb = BORDER_EMERALD
        top_neon.line.fill.background()

        # 3. Logo (Top Right)
        if os.path.exists(LOGO_PATH):
            slide.shapes.add_picture(LOGO_PATH, Inches(11.633), Inches(0.40), Inches(0.70), Inches(0.70))

        # 4. Header Pill Badge (No Emojis!)
        if pill_text:
            pill = slide.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(1.00), Inches(0.38), Inches(3.20), Inches(0.32))
            pill.fill.solid()
            pill.fill.fore_color.rgb = BG_CARD
            pill.line.color.rgb = BORDER_EMERALD
            pill.line.width = Pt(1.2)
            tf = pill.text_frame
            tf.word_wrap = True
            p = tf.paragraphs[0]
            p.text = pill_text.upper()
            p.font.name = FONT_FAMILY
            p.font.size = Pt(10)
            p.font.bold = True
            p.font.color.rgb = COLOR_MINT
            p.alignment = PP_ALIGN.CENTER

        # 5. Header Titles
        if title_text:
            tb = slide.shapes.add_textbox(Inches(1.00), Inches(0.75), Inches(10.33), Inches(0.85))
            tf = tb.text_frame
            tf.word_wrap = True
            tf.margin_left = Inches(0)
            tf.margin_top = Inches(0)

            p = tf.paragraphs[0]
            p.text = title_text
            p.font.name = FONT_FAMILY
            p.font.size = Pt(24)
            p.font.bold = True
            p.font.color.rgb = COLOR_WHITE

            if subtitle_text:
                p2 = tf.add_paragraph()
                p2.text = subtitle_text
                p2.font.name = FONT_FAMILY
                p2.font.size = Pt(12.5)
                p2.font.italic = True
                p2.font.color.rgb = COLOR_MUTED
                p2.space_before = Pt(3)

        # 6. Symmetrical Footer
        tb_f = slide.shapes.add_textbox(Inches(1.00), Inches(6.95), Inches(11.333), Inches(0.35))
        tf_f = tb_f.text_frame
        tf_f.margin_left = Inches(0)
        tf_f.margin_top = Inches(0)
        pf = tf_f.paragraphs[0]
        pf.text = "LapanganKita  •  Sistem Informasi Reservasi Lapangan Futsal Real-Time & Multi-Tenant"
        pf.font.name = FONT_FAMILY
        pf.font.size = Pt(9.5)
        pf.font.color.rgb = COLOR_MUTED

        return slide

    def create_card(slide, left, top, width, height, title="", title_color=COLOR_MINT, border_color=BORDER_MUTED, bg_color=BG_CARD):
        card = slide.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, left, top, width, height)
        card.fill.solid()
        card.fill.fore_color.rgb = bg_color
        card.line.color.rgb = border_color
        card.line.width = Pt(1.2)
        
        tf = card.text_frame
        tf.word_wrap = True
        tf.margin_left = Inches(0.25)
        tf.margin_right = Inches(0.25)
        tf.margin_top = Inches(0.25)
        tf.margin_bottom = Inches(0.25)

        if title:
            p = tf.paragraphs[0]
            p.text = title
            p.font.name = FONT_FAMILY
            p.font.size = Pt(15)
            p.font.bold = True
            p.font.color.rgb = title_color
            p.space_after = Pt(8)

        return card

    def create_stat_chip(slide, left, top, width, height, stat_num, stat_label, color=COLOR_MINT, border_color=BORDER_EMERALD):
        chip = slide.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, left, top, width, height)
        chip.fill.solid()
        chip.fill.fore_color.rgb = BG_CARD
        chip.line.color.rgb = border_color
        chip.line.width = Pt(1.5)

        tf = chip.text_frame
        tf.word_wrap = True
        tf.margin_left = Inches(0.1)
        tf.margin_right = Inches(0.1)
        tf.margin_top = Inches(0.15)
        tf.margin_bottom = Inches(0.1)

        p1 = tf.paragraphs[0]
        p1.text = stat_num
        p1.font.name = FONT_FAMILY
        p1.font.size = Pt(28)
        p1.font.bold = True
        p1.font.color.rgb = color
        p1.alignment = PP_ALIGN.CENTER

        p2 = tf.add_paragraph()
        p2.text = stat_label
        p2.font.name = FONT_FAMILY
        p2.font.size = Pt(10.5)
        p2.font.bold = True
        p2.font.color.rgb = COLOR_WHITE
        p2.alignment = PP_ALIGN.CENTER
        p2.space_before = Pt(2)
        return chip

    def embed_browser_screenshot(slide, left, top, width, height, image_path, url_text):
        frame = slide.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, left, top, width, height)
        frame.fill.solid()
        frame.fill.fore_color.rgb = RGBColor(15, 23, 42)
        frame.line.color.rgb = BORDER_EMERALD
        frame.line.width = Pt(1.5)

        hbar = slide.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, left, top, width, Inches(0.38))
        hbar.fill.solid()
        hbar.fill.fore_color.rgb = RGBColor(22, 31, 51)
        hbar.line.fill.background()

        d1 = slide.shapes.add_shape(MSO_SHAPE.OVAL, left + Inches(0.14), top + Inches(0.12), Inches(0.12), Inches(0.12))
        d1.fill.solid()
        d1.fill.fore_color.rgb = RGBColor(239, 68, 68)
        d1.line.fill.background()

        d2 = slide.shapes.add_shape(MSO_SHAPE.OVAL, left + Inches(0.32), top + Inches(0.12), Inches(0.12), Inches(0.12))
        d2.fill.solid()
        d2.fill.fore_color.rgb = RGBColor(245, 158, 11)
        d2.line.fill.background()

        d3 = slide.shapes.add_shape(MSO_SHAPE.OVAL, left + Inches(0.50), top + Inches(0.12), Inches(0.12), Inches(0.12))
        d3.fill.solid()
        d3.fill.fore_color.rgb = RGBColor(16, 185, 129)
        d3.line.fill.background()

        tb_url = slide.shapes.add_textbox(left + Inches(0.72), top + Inches(0.04), width - Inches(0.85), Inches(0.30))
        p = tb_url.text_frame.paragraphs[0]
        p.text = url_text
        p.font.name = FONT_FAMILY
        p.font.size = Pt(9)
        p.font.color.rgb = COLOR_MUTED

        if os.path.exists(image_path):
            img_top = top + Inches(0.38)
            img_height = height - Inches(0.40)
            slide.shapes.add_picture(image_path, left + Inches(0.02), img_top, width - Inches(0.04), img_height)

    # =========================================================================
    # SLIDE 1: COVER
    # =========================================================================
    s1 = prs.slides.add_slide(blank_layout)
    add_transition(s1, "fade")

    bg = s1.shapes.add_shape(MSO_SHAPE.RECTANGLE, 0, 0, Inches(13.333), Inches(7.5))
    bg.fill.solid()
    bg.fill.fore_color.rgb = BG_DARK
    bg.line.fill.background()

    top_neon = s1.shapes.add_shape(MSO_SHAPE.RECTANGLE, 0, 0, Inches(13.333), Inches(0.12))
    top_neon.fill.solid()
    top_neon.fill.fore_color.rgb = BORDER_EMERALD
    top_neon.line.fill.background()

    if os.path.exists(LOGO_PATH):
        logo_bg = s1.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(1.00), Inches(0.95), Inches(2.20), Inches(2.20))
        logo_bg.fill.solid()
        logo_bg.fill.fore_color.rgb = BG_CARD
        logo_bg.line.color.rgb = BORDER_EMERALD
        logo_bg.line.width = Pt(2.0)
        s1.shapes.add_picture(LOGO_PATH, Inches(1.10), Inches(1.05), Inches(2.00), Inches(2.00))

    pill1 = s1.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(3.50), Inches(0.95), Inches(4.50), Inches(0.36))
    pill1.fill.solid()
    pill1.fill.fore_color.rgb = BG_CARD
    pill1.line.color.rgb = BORDER_EMERALD
    pill1.line.width = Pt(1.2)
    p = pill1.text_frame.paragraphs[0]
    p.text = "SISTEM INFORMASI RESERVASI & SAAS 2026"
    p.font.name = FONT_FAMILY
    p.font.size = Pt(10)
    p.font.bold = True
    p.font.color.rgb = COLOR_MINT
    p.alignment = PP_ALIGN.CENTER

    tb = s1.shapes.add_textbox(Inches(3.50), Inches(1.35), Inches(8.833), Inches(1.80))
    tf = tb.text_frame
    tf.word_wrap = True
    tf.margin_left = Inches(0)
    tf.margin_top = Inches(0)

    p = tf.paragraphs[0]
    p.text = "LAPANGANKITA"
    p.font.name = FONT_FAMILY
    p.font.size = Pt(52)
    p.font.bold = True
    p.font.color.rgb = COLOR_WHITE

    p2 = tf.add_paragraph()
    p2.text = "Platform Reservasi Lapangan Futsal Real-Time, Komunitas Mabar & SaaS Multi-Tenant"
    p2.font.name = FONT_FAMILY
    p2.font.size = Pt(16.5)
    p2.font.bold = True
    p2.font.color.rgb = COLOR_CYAN
    p2.space_before = Pt(4)

    create_stat_chip(s1, Inches(1.000), Inches(3.40), Inches(3.500), Inches(1.35), "0%", "BENTROK JADWAL", COLOR_MINT, BORDER_EMERALD)
    create_stat_chip(s1, Inches(4.916), Inches(3.40), Inches(3.500), Inches(1.35), "< 60 Detik", "WAKTU PEMESANAN", COLOR_CYAN, BORDER_CYAN)
    create_stat_chip(s1, Inches(8.833), Inches(3.40), Inches(3.500), Inches(1.35), "100%", "REKAP LAPORAN OTOMATIS", COLOR_AMBER, BORDER_AMBER)

    c_pres = s1.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(1.00), Inches(5.00), Inches(11.333), Inches(1.90))
    c_pres.fill.solid()
    c_pres.fill.fore_color.rgb = BG_CARD
    c_pres.line.color.rgb = BORDER_MUTED
    tf_p = c_pres.text_frame
    tf_p.word_wrap = True
    tf_p.margin_left = Inches(0.40)
    tf_p.margin_top = Inches(0.25)

    p = tf_p.paragraphs[0]
    p.text = "INFORMASI PRESENTASI & PENGEMBANG SISTEM"
    p.font.name = FONT_FAMILY
    p.font.size = Pt(11)
    p.font.bold = True
    p.font.color.rgb = COLOR_MINT
    p.space_after = Pt(6)

    lines = [
        ("Nama Pengembang", "[Nama Mahasiswa / Tim Pengembang]"),
        ("Program Studi / Institusi", "Teknik Informatika / Sistem Informasi - [Nama Perguruan Tinggi]"),
        ("Teknologi Utama", "Laravel 11 • Tailwind CSS • Midtrans Snap API • MySQL • DomPDF")
    ]
    for label, val in lines:
        pb = tf_p.add_paragraph()
        pb.text = f"{label} :  {val}"
        pb.font.name = FONT_FAMILY
        pb.font.size = Pt(12)
        pb.font.color.rgb = COLOR_SLATE
        pb.space_after = Pt(3)

    # =========================================================================
    # SLIDE 2: MASALAH NYATA
    # =========================================================================
    s2 = create_base_slide(
        "Rumusan Masalah",
        "Latar Belakang: Kendala Pengelolaan & Pemesanan Lapangan",
        "Tiga kendala utama yang dihadapi pemain dan pemilik lapangan saat menggunakan cara konvensional.",
        "push", "l"
    )

    problems = [
        ("1. Konfirmasi Manual & Risiko Jadwal Ganda", "Pengecekan jadwal via pesan manual memakan waktu. Pencatatan di buku kasir sering terlewat hingga terjadi bentrok jadwal dua tim pada jam yang sama (double booking).", BORDER_RED, "Dampak: Pemain merasa kecewa dan reputasi operasional lapangan menurun."),
        ("2. Pemain Solo Sulit Bermain (Kurang Anggota)", "Banyak rencana bermain futsal batal karena rekan berhalangan hadir. Belum tersedia platform yang memfasilitasi pemain solo untuk bergabung dan patungan sewa lapangan.", BORDER_AMBER, "Dampak: Sekitar 40% jadwal bermain komunitas batal setiap pekannya."),
        ("3. Pembukuan Kasir Manual & Okupansi Rendah", "Pencatatan keuangan di buku kertas rentan hilang atau salah hitung. Lapangan pada jam kerja (siang hari) sering kosong tanpa adanya penyesuaian tarif dinamis.", BORDER_RED, "Dampak: Pendapatan pengelola tidak tercatat rapi dan potensi omset hilang.")
    ]

    col_positions = [Inches(1.000), Inches(4.916), Inches(8.833)]
    for idx, (head, body, border, impact) in enumerate(problems):
        c = create_card(s2, col_positions[idx], Inches(1.80), Inches(3.500), Inches(5.00), head, title_color=COLOR_WHITE, border_color=border)
        tf = c.text_frame
        
        p = tf.add_paragraph()
        p.text = body
        p.font.name = FONT_FAMILY
        p.font.size = Pt(12.5)
        p.font.color.rgb = COLOR_SLATE
        p.space_after = Pt(14)

        p2 = tf.add_paragraph()
        p2.text = impact
        p2.font.name = FONT_FAMILY
        p2.font.size = Pt(11)
        p2.font.bold = True
        p2.font.color.rgb = COLOR_AMBER
        p2.space_before = Pt(8)

    # =========================================================================
    # SLIDE 3: SOLUSI 3 PILAR TERPADU
    # =========================================================================
    s3 = create_base_slide(
        "Solusi Terpadu",
        "Solusi Sistem: Tiga Modul Utama LapanganKita",
        "Menjawab kebutuhan operasional pemain, komunitas olahraga, dan pemilik lapangan.",
        "push", "l"
    )

    pillars = [
        ("MODUL 1", "Reservasi & Pembayaran Online", [
            "Pilih tanggal dan jam secara visual dan interaktif",
            "Jadwal real-time anti-bentrok (100% akurat)",
            "Pembayaran instan via Midtrans (QRIS & Virtual Account)",
            "E-Tiket resmi ber-QR Code terbit otomatis"
        ], COLOR_MINT, BORDER_EMERALD),
        ("MODUL 2", "Pusat Komunitas (Mabar)", [
            "Open Slot Patungan: Main solo tanpa takut kurang pemain",
            "Kalkulator biaya patungan otomatis per individu",
            "Lobi Sparing: Tantang tim lawan dengan bagi rata 50:50",
            "Akses cepat 1-klik menggunakan Akun Google (SSO)"
        ], COLOR_CYAN, BORDER_CYAN),
        ("MODUL 3", "Manajemen Pengelola (SaaS)", [
            "Dashboard kasir offline: Kunci slot langsung di tempat",
            "Pengaturan tarif fleksibel & paket langganan sistem",
            "Ekspor laporan omset dan rekapan finansial format PDF",
            "Analisis statistik keterisian jam operasional (okupansi)"
        ], COLOR_AMBER, BORDER_AMBER)
    ]

    for idx, (tag, head, points, col, border) in enumerate(pillars):
        c = create_card(s3, col_positions[idx], Inches(1.80), Inches(3.500), Inches(5.00), f"{tag}: {head}", title_color=col, border_color=border)
        tf = c.text_frame
        for pt in points:
            p = tf.add_paragraph()
            p.text = "-  " + pt
            p.font.name = FONT_FAMILY
            p.font.size = Pt(12)
            p.font.color.rgb = COLOR_SLATE
            p.space_after = Pt(7)

    # =========================================================================
    # SLIDE 4: ROLES
    # =========================================================================
    s4 = create_base_slide(
        "Hak Akses & Peran",
        "Arsitektur Hak Akses: Tiga Peran Pengguna (RBAC)",
        "Pemisahan hak akses yang ketat dan aman menggunakan middleware Laravel.",
        "push", "l"
    )

    roles = [
        ("PEMAIN (Pengguna Utama)", "Akses Publik & Pengguna Utama", [
            "Cari venue dan periksa fasilitas lapangan",
            "Pesan jadwal lapangan dan bayar online",
            "Unduh E-Tiket dan invoice bukti bayar PDF",
            "Gabung room Open Slot patungan pemain solo",
            "Kirim dan terima tantangan laga Sparing"
        ], COLOR_MINT, BORDER_EMERALD),
        ("MITRA (Pengelola Lapangan)", "Pengelola Bisnis Venue Futsal", [
            "Manajemen data lapangan dan pengaturan tarif",
            "Kunci slot jadwal kasir langsung (walk-in)",
            "Pantau rekapitulasi omset harian dan bulanan",
            "Cetak dokumen laporan keuangan resmi PDF",
            "Pantau statistik jam ramai dan jam sepi"
        ], COLOR_CYAN, BORDER_CYAN),
        ("SUPER ADMIN (Administrator)", "Pengelola Platform Pusat", [
            "Monitoring seluruh aktivitas transaksi platform",
            "Aktivasi, verifikasi, atau penangguhan akun mitra",
            "Kelola master paket langganan SaaS",
            "Audit trail catatan aktivitas keamanan sistem"
        ], COLOR_AMBER, BORDER_AMBER)
    ]

    for idx, (rname, rsub, rbullets, col, border) in enumerate(roles):
        c = create_card(s4, col_positions[idx], Inches(1.80), Inches(3.500), Inches(5.00), rname, title_color=col, border_color=border)
        tf = c.text_frame
        p_sub = tf.add_paragraph()
        p_sub.text = rsub.upper()
        p_sub.font.name = FONT_FAMILY
        p_sub.font.size = Pt(10)
        p_sub.font.bold = True
        p_sub.font.color.rgb = COLOR_MUTED
        p_sub.space_after = Pt(10)
        for b in rbullets:
            p = tf.add_paragraph()
            p.text = "-  " + b
            p.font.name = FONT_FAMILY
            p.font.size = Pt(12)
            p.font.color.rgb = COLOR_SLATE
            p.space_after = Pt(6)

    # =========================================================================
    # SLIDE 5: FITUR PEMAIN
    # =========================================================================
    s5 = create_base_slide(
        "Pengalaman Pemain",
        "Pengalaman Pemain: Reservasi & Pembayaran Kilat",
        "Pemesanan lapangan dalam 3 langkah mudah dengan konfirmasi pembayaran otomatis.",
        "push", "l"
    )

    c_left = create_card(s5, Inches(1.000), Inches(1.80), Inches(5.400), Inches(5.00), "Kemudahan Pemesanan Lapangan", title_color=COLOR_MINT, border_color=BORDER_EMERALD)
    tf = c_left.text_frame
    feats = [
        ("Matriks Jadwal Real-Time", "Pilih jam kosong layaknya memilih kursi bioskop. Slot yang sudah terisi otomatis terkunci secara presisi oleh sistem database."),
        ("Integrasi Midtrans Payment Gateway", "Pembayaran langsung di layar tanpa repot pindah aplikasi: Scan QRIS (BCA, Gopay, OVO, Dana) atau Virtual Account bank."),
        ("E-Tiket PDF Ber-QR Code", "Tiket resmi terbit detik itu juga. Cukup tunjukkan QR Code ke petugas di lokasi lapangan untuk validasi check-in."),
        ("Autentikasi Akun Google SSO", "Daftar dan masuk dalam 1 ketukan tanpa perlu menghafal kata sandi baru.")
    ]
    for idx_f, (title, desc) in enumerate(feats):
        p = tf.add_paragraph()
        p.text = f"{idx_f+1}. {title}"
        p.font.name = FONT_FAMILY
        p.font.bold = True
        p.font.size = Pt(13)
        p.font.color.rgb = COLOR_WHITE
        p.space_before = Pt(4)

        p2 = tf.add_paragraph()
        p2.text = desc
        p2.font.name = FONT_FAMILY
        p2.font.size = Pt(11.5)
        p2.font.color.rgb = COLOR_SLATE
        p2.space_after = Pt(5)

    embed_browser_screenshot(s5, Inches(6.933), Inches(1.80), Inches(5.400), Inches(5.00), IMG_VENUE, "https://lapangankita.id/venues/champion-futsal")

    # =========================================================================
    # SLIDE 6: KOMUNITAS
    # =========================================================================
    s6 = create_base_slide(
        "Fitur Komunitas",
        "Fitur Komunitas: Open Slot Mabar & Laga Sparing",
        "Solusi praktis bagi pemain solo dan klub yang mencari lawan tanding.",
        "push", "l"
    )

    embed_browser_screenshot(s6, Inches(1.000), Inches(1.80), Inches(5.400), Inches(5.00), IMG_OPENSLOT, "https://lapangankita.id/open-slot")

    c_right = create_card(s6, Inches(6.933), Inches(1.80), Inches(5.400), Inches(5.00), "Nilai Tambah untuk Komunitas Olahraga", title_color=COLOR_CYAN, border_color=BORDER_CYAN)
    tf = c_right.text_frame
    comms = [
        ("Open Slot Mabar (Solusi Pemain Solo)", "Penyelenggara membuka sesi mabar, tentukan kuota peserta, dan sistem secara otomatis menghitung tarif patungan per orang. Sesi futsal tidak batal meski pemain awal kurang."),
        ("Lobi Sparing Match (Tantang Tim Lawan)", "Klub futsal dapat mempublikasikan tantangan bertanding. Total biaya sewa lapangan dibagi rata 50% untuk masing-masing tim secara otomatis (Split-Bill)."),
        ("Invoice Transparan Per Anggota", "Setiap peserta yang membayar patungan mendapatkan invoice PDF resmi dengan rincian biaya yang sah dan transparan.")
    ]
    for idx_c, (title, desc) in enumerate(comms):
        p = tf.add_paragraph()
        p.text = f"{idx_c+1}. {title}"
        p.font.name = FONT_FAMILY
        p.font.bold = True
        p.font.size = Pt(13)
        p.font.color.rgb = COLOR_WHITE
        p.space_before = Pt(6)

        p2 = tf.add_paragraph()
        p2.text = desc
        p2.font.name = FONT_FAMILY
        p2.font.size = Pt(11.5)
        p2.font.color.rgb = COLOR_SLATE
        p2.space_after = Pt(7)

    # =========================================================================
    # SLIDE 7: OPERASIONAL MITRA
    # =========================================================================
    s7 = create_base_slide(
        "Operasional Mitra",
        "Panel Pengelola: Operasional Kasir & Pengaturan Lapangan",
        "Digitalisasi menyeluruh untuk kasir langsung dan pengelolaan tarif lapangan.",
        "push", "l"
    )

    c_left = create_card(s7, Inches(1.000), Inches(1.80), Inches(5.400), Inches(5.00), "Fitur Utama Pemilik Lapangan", title_color=COLOR_AMBER, border_color=BORDER_AMBER)
    tf = c_left.text_frame
    tenant_pts = [
        ("Kunci Slot Kasir Offline (Anti-Bentrok)", "Satu klik untuk mengunci jam jika ada penyewa tunai/walk-in yang datang langsung ke meja kasir."),
        ("Tarif Dinamis (Reguler vs Jam Ramai)", "Bebas mengatur tarif lebih hemat di siang hari dan tarif premium di malam hari atau akhir pekan."),
        ("Sistem Langganan SaaS Fleksibel", "Mitra baru mendapatkan uji coba gratis 7 hari. Tersedia paket Starter, Pro, dan Tahunan untuk skala bisnis yang berbeda."),
        ("Laporan Finansial Siap Cetak PDF", "Pantau grafik tren pendapatan bulanan dan ekspor bukti pembukuan resmi dalam format PDF siap audit.")
    ]
    for idx_t, (title, desc) in enumerate(tenant_pts):
        p = tf.add_paragraph()
        p.text = f"{idx_t+1}. {title}"
        p.font.name = FONT_FAMILY
        p.font.bold = True
        p.font.size = Pt(13)
        p.font.color.rgb = COLOR_WHITE
        p.space_before = Pt(4)

        p2 = tf.add_paragraph()
        p2.text = desc
        p2.font.name = FONT_FAMILY
        p2.font.size = Pt(11.5)
        p2.font.color.rgb = COLOR_SLATE
        p2.space_after = Pt(5)

    embed_browser_screenshot(s7, Inches(6.933), Inches(1.80), Inches(5.400), Inches(5.00), IMG_DASHBOARD, "https://lapangankita.id/mitra/dashboard")

    # =========================================================================
    # SLIDE 8: MODUL ANALITIK
    # =========================================================================
    s8 = create_base_slide(
        "Modul Analitik & Laporan",
        "Modul Analitik: Statistik Keterisian & Pendapatan Lapangan",
        "Membantu pengelola memantau performa bisnis dan mendeteksi tren keterisian lapangan.",
        "push", "l"
    )

    c_left = create_card(s8, Inches(1.000), Inches(1.80), Inches(5.400), Inches(5.00), "Fitur Pengambilan Keputusan Pengelola", title_color=COLOR_MINT, border_color=BORDER_EMERALD)
    tf = c_left.text_frame
    ai_pts = [
        ("Pemetaan Jam Okupansi Riil", "Sistem memetakan jam-jam dengan tingkat keterisian tinggi (ramai) dan jam sepi berdasarkan riwayat transaksi nyata di database."),
        ("Pengaturan Tarif Khusus Jam Sepi", "Pengelola dapat membuat strategi tarif pelajar atau diskon khusus di jam kerja (weekday siang) agar lapangan tidak kosong."),
        ("Grafik Tren Pendapatan Berkala", "Menampilkan grafik omset mingguan dan bulanan secara visual guna mengevaluasi pertumbuhan bisnis tanpa rekapan manual."),
        ("Ekspor Rekap Finansial PDF", "Mencetak bukti laporan pendapatan dan rincian transaksi per periode dalam bentuk file PDF resmi siap audit.")
    ]
    for idx_a, (title, desc) in enumerate(ai_pts):
        p = tf.add_paragraph()
        p.text = f"{idx_a+1}. {title}"
        p.font.name = FONT_FAMILY
        p.font.bold = True
        p.font.size = Pt(13)
        p.font.color.rgb = COLOR_WHITE
        p.space_before = Pt(4)

        p2 = tf.add_paragraph()
        p2.text = desc
        p2.font.name = FONT_FAMILY
        p2.font.size = Pt(11.5)
        p2.font.color.rgb = COLOR_SLATE
        p2.space_after = Pt(5)

    embed_browser_screenshot(s8, Inches(6.933), Inches(1.80), Inches(5.400), Inches(5.00), IMG_ANALYTICS, "https://lapangankita.id/mitra/analytics")

    # =========================================================================
    # SLIDE 9: MIDTRANS
    # =========================================================================
    s9 = create_base_slide(
        "Arsitektur Pembayaran",
        "Alur Transaksi & Keamanan Pembayaran (Midtrans)",
        "Proses transaksi cepat 4 langkah yang dilindungi enkripsi SHA-512 Signature Key.",
        "push", "l"
    )

    steps = [
        ("LANGKAH 1", "Pilih Jadwal", "Pemain memilih tanggal, unit lapangan & jam slot. Database mengunci sementara pilihan.", COLOR_CYAN, BORDER_CYAN),
        ("LANGKAH 2", "Token Bayar", "Laravel meminta Token Snap ke API Midtrans. Jendela pembayaran muncul di layar pengguna.", COLOR_MINT, BORDER_EMERALD),
        ("LANGKAH 3", "Bayar Instan", "Pengguna scan QRIS atau transfer via Virtual Account bank. Transaksi selesai dalam hitungan detik.", COLOR_AMBER, BORDER_AMBER),
        ("LANGKAH 4", "Verifikasi & Tiket", "Midtrans kirim webhook HTTP POST. Sistem validasi signature SHA-512, status lunas, tiket PDF terbit.", COLOR_WHITE, BORDER_EMERALD)
    ]

    col4_positions = [Inches(1.000), Inches(3.927), Inches(6.855), Inches(9.783)]
    for idx, (step_tag, step_title, step_desc, col, border) in enumerate(steps):
        c = create_card(s9, col4_positions[idx], Inches(1.80), Inches(2.550), Inches(4.00), f"{step_tag}\n{step_title}", title_color=col, border_color=border)
        tf = c.text_frame
        p = tf.add_paragraph()
        p.text = step_desc
        p.font.name = FONT_FAMILY
        p.font.size = Pt(11.5)
        p.font.color.rgb = COLOR_SLATE
        p.space_after = Pt(4)

    sec_banner = s9.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(1.00), Inches(6.00), Inches(11.333), Inches(0.85))
    sec_banner.fill.solid()
    sec_banner.fill.fore_color.rgb = BG_CARD
    sec_banner.line.color.rgb = BORDER_EMERALD
    sec_banner.line.width = Pt(1.5)
    tf_s = sec_banner.text_frame
    p = tf_s.paragraphs[0]
    p.text = "JAMINAN KEAMANAN TRANSAKSI: DILINDUNGI VERIFIKASI TANDA TANGAN DIGITAL SHA-512"
    p.font.name = FONT_FAMILY
    p.font.size = Pt(11.5)
    p.font.bold = True
    p.font.color.rgb = COLOR_MINT
    p.alignment = PP_ALIGN.CENTER

    p2 = tf_s.add_paragraph()
    p2.text = "Status pembayaran diverifikasi langsung oleh server Midtrans secara otomatis. Bebas resiko manipulasi bukti transfer palsu."
    p2.font.name = FONT_FAMILY
    p2.font.size = Pt(10.5)
    p2.font.color.rgb = COLOR_SLATE
    p2.alignment = PP_ALIGN.CENTER
    p2.space_before = Pt(2)

    # =========================================================================
    # SLIDE 10: REAL UI GALLERY
    # =========================================================================
    s10 = create_base_slide(
        "Galeri Antarmuka",
        "Implementasi Antarmuka Sistem (Hasil Pengembangan)",
        "Dokumentasi tangkapan layar dari sistem LapanganKita yang telah berjalan aktif.",
        "push", "l"
    )

    gallery_items = [
        ("1. Beranda & Pencarian Venue", IMG_LANDING, Inches(1.000), Inches(1.80)),
        ("2. Pemilihan Jam & Detail Venue", IMG_VENUE, Inches(6.933), Inches(1.80)),
        ("3. Komunitas Mabar Open Slot", IMG_OPENSLOT, Inches(1.000), Inches(4.35)),
        ("4. Dashboard Analitik Pengelola", IMG_ANALYTICS, Inches(6.933), Inches(4.35)),
    ]
    for title, img_path, left_pos, top_pos in gallery_items:
        embed_browser_screenshot(s10, left_pos, top_pos, Inches(5.400), Inches(2.40), img_path, f"lapangankita.id - {title}")

    # =========================================================================
    # SLIDE 11: METRIK
    # =========================================================================
    s11 = create_base_slide(
        "Dampak & Hasil Terukur",
        "Evaluasi Sistem: Perbandingan Metode Konvensional vs LapanganKita",
        "Efisiensi yang diperoleh setelah penerapan sistem terintegrasi.",
        "push", "l"
    )

    create_stat_chip(s11, col4_positions[0], Inches(1.80), Inches(2.550), Inches(1.50), "0%", "BENTROK JADWAL", COLOR_MINT, BORDER_EMERALD)
    create_stat_chip(s11, col4_positions[1], Inches(1.80), Inches(2.550), Inches(1.50), "< 60 Detik", "WAKTU PEMESANAN", COLOR_CYAN, BORDER_CYAN)
    create_stat_chip(s11, col4_positions[2], Inches(1.80), Inches(2.550), Inches(1.50), "50:50", "PATUNGAN OTOMATIS", COLOR_AMBER, BORDER_AMBER)
    create_stat_chip(s11, col4_positions[3], Inches(1.80), Inches(2.550), Inches(1.50), "100%", "TRANSPARAN FINANSIAL", COLOR_WHITE, BORDER_EMERALD)

    c_comp = create_card(s11, Inches(1.000), Inches(3.50), Inches(11.333), Inches(3.30), "Tabel Perbandingan Sebelum vs Sesudah Implementasi", title_color=COLOR_MINT, border_color=BORDER_MUTED)
    tf = c_comp.text_frame
    comparisons = [
        ("Pengecekan Jadwal", "Chat WhatsApp manual, tunggu admin membalas (10-15 menit)", "Matriks Real-Time, langsung melihat jam kosong (< 10 detik)"),
        ("Metode Pembayaran", "Transfer bank manual & kirim foto resi transfer via WA", "Midtrans otomatis (QRIS / VA), tiket PDF terbit seketika"),
        ("Komunitas Mabar", "Sulit mencari teman bermain, sering batal main", "Room Open Slot patungan & Lobi Sparing bagi rata 50:50"),
        ("Pembukuan Pengelola", "Buku tulis / spreadsheet rentan hilang & salah hitung", "Laporan omset otomatis, ekspor PDF & grafik okupansi jam")
    ]
    for label, konv, lk in comparisons:
        p = tf.add_paragraph()
        p.text = f"- {label}:  "
        p.font.name = FONT_FAMILY
        p.font.bold = True
        p.font.size = Pt(12)
        p.font.color.rgb = COLOR_AMBER

        p_k = tf.add_paragraph()
        p_k.text = f"   [Sebelum]: {konv}  -->  [LapanganKita]: {lk}"
        p_k.font.name = FONT_FAMILY
        p_k.font.size = Pt(11.5)
        p_k.font.color.rgb = COLOR_SLATE
        p_k.space_after = Pt(4)

    # =========================================================================
    # SLIDE 12: ROADMAP & QNA
    # =========================================================================
    s12 = create_base_slide(
        "Rencana Pengembangan & Tanya Jawab",
        "Rencana Fitur Tambahan & Sesi Tanya Jawab",
        "Peluang peningkatan layanan berikutnya dan sesi diskusi dewan penguji.",
        "zoom"
    )

    c_road = create_card(s12, Inches(1.000), Inches(1.80), Inches(5.400), Inches(5.00), "Rencana Fitur Tambahan (Pengembangan Lanjutan)", title_color=COLOR_CYAN, border_color=BORDER_CYAN)
    tf = c_road.text_frame
    roads = [
        ("1. Pengingat WhatsApp Otomatis (WA Reminder)", "Pesan pengingat jadwal H-2 jam sebelum main via WA Gateway agar pemain tidak terlambat datang ke lapangan."),
        ("2. Poin Loyalitas & Voucher Diskon", "Pengguna mengumpulkan poin dari transaksi booking yang dapat ditukar dengan voucher potongan harga sewa."),
        ("3. Sewa Perlengkapan Tambahan (Add-on)", "Opsi checklist tambahan untuk sewa rompi tim atau bola futsal langsung saat checkout pemesanan."),
        ("4. Ulasan & Rating Bintang Venue", "Fitur rating bintang 1–5 dan ulasan pemain untuk menjaga mutu kebersihan fasilitas & rumput lapangan.")
    ]
    for title, desc in roads:
        p = tf.add_paragraph()
        p.text = title
        p.font.name = FONT_FAMILY
        p.font.bold = True
        p.font.size = Pt(12.5)
        p.font.color.rgb = COLOR_WHITE
        p.space_before = Pt(4)

        p2 = tf.add_paragraph()
        p2.text = desc
        p2.font.name = FONT_FAMILY
        p2.font.size = Pt(11)
        p2.font.color.rgb = COLOR_SLATE
        p2.space_after = Pt(4)

    c_qna = s12.shapes.add_shape(MSO_SHAPE.ROUNDED_RECTANGLE, Inches(6.933), Inches(1.80), Inches(5.400), Inches(5.00))
    c_qna.fill.solid()
    c_qna.fill.fore_color.rgb = BG_CARD
    c_qna.line.color.rgb = BORDER_EMERALD
    c_qna.line.width = Pt(2.0)

    if os.path.exists(LOGO_PATH):
        s12.shapes.add_picture(LOGO_PATH, Inches(8.883), Inches(2.10), Inches(1.50), Inches(1.50))

    tf_q = c_qna.text_frame
    tf_q.word_wrap = True
    tf_q.margin_top = Inches(1.90)

    p = tf_q.paragraphs[0]
    p.text = "TERIMA KASIH"
    p.font.name = FONT_FAMILY
    p.font.size = Pt(32)
    p.font.bold = True
    p.font.color.rgb = COLOR_WHITE
    p.alignment = PP_ALIGN.CENTER

    p2 = tf_q.add_paragraph()
    p2.text = "Sesi Tanya Jawab & Diskusi"
    p2.font.name = FONT_FAMILY
    p2.font.size = Pt(20)
    p2.font.bold = True
    p2.font.color.rgb = COLOR_MINT
    p2.alignment = PP_ALIGN.CENTER
    p2.space_before = Pt(6)

    p3 = tf_q.add_paragraph()
    p3.text = "Kami menyambut baik pertanyaan, masukan, dan evaluasi dari Bapak/Ibu Dewan Penguji serta rekan-rekan sekalian."
    p3.font.name = FONT_FAMILY
    p3.font.size = Pt(11.5)
    p3.font.color.rgb = COLOR_SLATE
    p3.alignment = PP_ALIGN.CENTER
    p3.space_before = Pt(10)

    p4 = tf_q.add_paragraph()
    p4.text = "LapanganKita: Platform Reservasi Lapangan Futsal Real-Time"
    p4.font.name = FONT_FAMILY
    p4.font.size = Pt(12.5)
    p4.font.bold = True
    p4.font.color.rgb = COLOR_AMBER
    p4.alignment = PP_ALIGN.CENTER
    p4.space_before = Pt(14)

    s12.notes_slide.notes_text_frame.text = (
        "Sekian presentasi dari kami. Sistem LapanganKita dirancang untuk menyelesaikan masalah reservasi ganda, "
        "memfasilitasi komunitas pemain, dan mendigitalisasi kasir pengelola secara akurat. "
        "Untuk pengembangan ke depan, kami merencanakan pengingat WhatsApp, poin loyalitas, dan ulasan rating venue. "
        "Terima kasih atas waktu dan perhatiannya, sesi tanya jawab kami persilakan."
    )

    out_file = os.path.abspath("LapanganKita_Presentasi_Formal_Akademik.pptx")
    prs.save(out_file)
    print(f"Formal Academic PPTX saved successfully at: {out_file}")

if __name__ == "__main__":
    build_formal_academic_pptx()
