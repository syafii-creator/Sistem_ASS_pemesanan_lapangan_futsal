with open('scratch/executive_deck.html', 'r', encoding='utf-8') as f:
    content = f.read()

replacements = {
    'NEXT-GEN SPORTS ECOSYSTEM &amp; SAAS 2026': 'EKOSISTEM OLAHRAGA DIGITAL &amp; SAAS 2026',
    'NEXT-GEN SPORTS ECOSYSTEM & SAAS 2026': 'EKOSISTEM OLAHRAGA DIGITAL & SAAS 2026',
    '<div class="header-pill">Problem Statement</div>': '<div class="header-pill">Rumusan Masalah</div>',
    '<div class="header-pill">Value Proposition</div>': '<div class="header-pill">Solusi Terpadu</div>',
    '<div class="header-pill">Role Architecture</div>': '<div class="header-pill">Hak Akses &amp; Peran Pengguna</div>',
    '<div class="header-pill">Player Showcase</div>': '<div class="header-pill">Pengalaman Pemain</div>',
    '<div class="header-pill">Community Hub</div>': '<div class="header-pill">Fitur Komunitas</div>',
    '<div class="header-pill">Tenant &amp; SaaS</div>': '<div class="header-pill">Operasional Mitra &amp; SaaS</div>',
    '<div class="header-pill">AI Innovation</div>': '<div class="header-pill">Inovasi Kecerdasan Buatan (AI)</div>',
    '<div class="header-pill">Payment Pipeline</div>': '<div class="header-pill">Alur &amp; Keamanan Pembayaran</div>',
    '<div class="header-pill">Live UI Gallery</div>': '<div class="header-pill">Galeri Antarmuka Sistem</div>',
    '<div class="header-pill">Impact &amp; Metrics</div>': '<div class="header-pill">Dampak &amp; Hasil Terukur</div>',
    '<div class="header-pill">Roadmap &amp; QnA</div>': '<div class="header-pill">Rencana Fitur &amp; Diskusi</div>',
    'DOUBLE BOOKING': 'BENTROK JADWAL',
    'SPLIT BILL OTOMATIS': 'PATUNGAN OTOMATIS',
    'Sesi Tanya Jawab (Q &amp; A)': 'Sesi Tanya Jawab &amp; Diskusi',
    'Sesi Tanya Jawab (Q & A)': 'Sesi Tanya Jawab & Diskusi',
    'Live System Showcase': 'Tampilan Sistem Nyata',
}

for old, new in replacements.items():
    if old in content:
        content = content.replace(old, new)
        print(f'Replaced: {old[:30]} -> {new[:30]}')
    else:
        print(f'Not found: {old[:30]}')

with open('scratch/executive_deck.html', 'w', encoding='utf-8') as f:
    f.write(content)
print('Updated executive_deck.html successfully!')

# Now also update presentation_gg.html
with open('presentation_gg.html', 'r', encoding='utf-8') as f:
    gg_content = f.read()

gg_replacements = {
    'Problem Statement': 'Rumusan Masalah',
    'Value Proposition': 'Solusi Terpadu',
    'Role Architecture': 'Hak Akses & Peran Pengguna',
    'Player Showcase': 'Pengalaman Pemain',
    'Community Hub': 'Fitur Komunitas',
    'Tenant & SaaS': 'Operasional Mitra & SaaS',
    'AI Innovation': 'Inovasi Kecerdasan Buatan (AI)',
    'Payment Pipeline': 'Alur & Keamanan Pembayaran',
    'Live UI Gallery': 'Galeri Antarmuka Sistem',
    'Impact & Metrics': 'Dampak & Hasil Terukur',
    'Roadmap & QnA': 'Rencana Fitur & Diskusi',
    'NEXT-GEN SPORTS ECOSYSTEM & SAAS 2026': 'EKOSISTEM OLAHRAGA DIGITAL & SAAS 2026',
    'DOUBLE BOOKING': 'BENTROK JADWAL',
    'SPLIT BILL OTOMATIS': 'PATUNGAN OTOMATIS',
    'Sesi Tanya Jawab (Q & A)': 'Sesi Tanya Jawab & Diskusi',
}

for old, new in gg_replacements.items():
    if old in gg_content:
        gg_content = gg_content.replace(old, new)
        print(f'Replaced in presentation_gg: {old[:30]} -> {new[:30]}')

with open('presentation_gg.html', 'w', encoding='utf-8') as f:
    f.write(gg_content)
print('Updated presentation_gg.html successfully!')
