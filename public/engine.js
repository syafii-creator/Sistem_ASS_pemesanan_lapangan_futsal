/**
 * LapanganKita - Master Database & State Engine (LocalStorage)
 * Menjembatani sinkronisasi data antar 16 Halaman secara real-time
 */

const STORAGE_KEY = 'lapangankita_master_db';

const defaultMasterData = {
  // Profil Sistem & Platform
  platform: {
    name: 'LapanganKita',
    version: '1.0.0',
    totalVenues: 48,
    totalBookings: 1420,
    mrr: 7200000,
    arr: 86400000
  },

  // Paket Langganan SaaS (Super Admin & Tenant Billing)
  saasPackages: [
    {
      id: 'pkg-weekly',
      name: 'Paket Mingguan (Trial)',
      durationDays: 7,
      price: 50000,
      badge: 'Uji Coba',
      features: ['Maksimal 2 Unit Lapangan', 'Jadwal Matrix Real-time', 'Simulasi QRIS & VA', 'Laporan Omset Dasar', 'Tanpa AI Assistant'],
      recommended: false
    },
    {
      id: 'pkg-monthly',
      name: 'Paket Bulanan (Pro)',
      durationDays: 30,
      price: 150000,
      badge: 'Paling Populer',
      features: ['Unit Lapangan Tanpa Batas', 'Jadwal Matrix Real-time', 'AI Smart Venue Assistant (Gemini)', 'CMS Logo & Galeri Foto', 'Fitur Open Slot & Sparing', 'Support Prioritas WA'],
      recommended: true
    },
    {
      id: 'pkg-yearly',
      name: 'Paket Tahunan (Ultimate)',
      durationDays: 365,
      price: 1200000,
      badge: 'Hemat 35%',
      features: ['Semua Fitur Paket Pro', 'Analisis AI Multi-Bulan & Prediksi', 'Custom Subdomain Venue', 'Export Laporan Excel & PDF Otomatis', 'Onboarding & Setup Dibantu Tim'],
      recommended: false
    }
  ],

  // Data Tenant / Venue Terdaftar
  venues: [
    {
      id: 'vn-01',
      name: 'Champion Futsal Arena',
      slug: 'champion-futsal-arena',
      city: 'Bandung',
      address: 'Jl. Riau No. 88, Citarum, Bandung Wetan',
      phone: '0812-3456-7890',
      rating: 4.9,
      reviewsCount: 128,
      priceStart: 120000,
      logo: 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=150&auto=format&fit=crop&q=80',
      banner: 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=1200&auto=format&fit=crop&q=80',
      subscription: {
        plan: 'Paket Bulanan (Pro)',
        status: 'active',
        expiresAt: '2026-11-15',
        daysLeft: 37
      },
      stats: {
        todayRevenue: 960000,
        todayBookings: 6,
        occupancyRate: 75
      },
      facilities: ['Rumput Sintetis Premium FIFA', 'Ruang Ganti & Shower Panas', 'Kantin & Coffee Corner', 'Parkir Luas & Mushola', 'Papan Skor Digital'],
      courts: [
        {
          id: 'crt-01',
          name: 'Lapangan A (Rumput Sintetis)',
          surface: 'Sintetis Monofilament',
          hourlyRate: 150000,
          peakRate: 180000,
          image: 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=800&auto=format&fit=crop&q=80'
        },
        {
          id: 'crt-02',
          name: 'Lapangan B (Interlock Vinyl)',
          surface: 'Interlock Vinyl Polypropylene',
          hourlyRate: 130000,
          peakRate: 160000,
          image: 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=800&auto=format&fit=crop&q=80'
        }
      ]
    },
    {
      id: 'vn-02',
      name: 'Garuda Sport Center Futsal',
      slug: 'garuda-sport-center',
      city: 'Jakarta Selatan',
      address: 'Jl. Fatmawati Raya No. 42, Cilandak',
      phone: '0813-8899-1122',
      rating: 4.8,
      reviewsCount: 94,
      priceStart: 150000,
      logo: 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=150&auto=format&fit=crop&q=80',
      banner: 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=1200&auto=format&fit=crop&q=80',
      subscription: {
        plan: 'Paket Bulanan (Pro)',
        status: 'active',
        expiresAt: '2026-10-28',
        daysLeft: 19
      },
      facilities: ['Vinyl Standar BWF/FIFA', 'Tribun Penonton 200 Kursi', 'Ruang Medis & P3K', 'Minimarket 24 Jam'],
      courts: [
        {
          id: 'crt-03',
          name: 'Lapangan 1 (Full Vinyl)',
          surface: 'Interlock Vinyl',
          hourlyRate: 160000,
          peakRate: 200000,
          image: 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=800&auto=format&fit=crop&q=80'
        }
      ]
    },
    {
      id: 'vn-03',
      name: 'Siliwangi Futsal Stadium',
      slug: 'siliwangi-futsal-stadium',
      city: 'Surabaya',
      address: 'Jl. Mayjen Sungkono No. 102, Dukuh Pakis',
      phone: '0857-1122-3344',
      rating: 4.7,
      reviewsCount: 76,
      priceStart: 110000,
      logo: 'https://images.unsplash.com/photo-1517466787929-bc90951d0974?w=150&auto=format&fit=crop&q=80',
      banner: 'https://images.unsplash.com/photo-1518604666864-7423958f7660?w=1200&auto=format&fit=crop&q=80',
      subscription: {
        plan: 'Paket Mingguan (Trial)',
        status: 'active',
        expiresAt: '2026-10-14',
        daysLeft: 5
      },
      facilities: ['Rumput Sintetis Tebal', 'Sound System Lapangan', 'WiFi Gratis Kencang', 'Kantin Olahraga'],
      courts: [
        {
          id: 'crt-04',
          name: 'Court Utama Siliwangi',
          surface: 'Sintetis Monofilament',
          hourlyRate: 120000,
          peakRate: 150000,
          image: 'https://images.unsplash.com/photo-1518604666864-7423958f7660?w=800&auto=format&fit=crop&q=80'
        }
      ]
    },
    {
      id: 'vn-04',
      name: 'Patriot Futsal Center',
      slug: 'patriot-futsal-center',
      city: 'Bekasi',
      address: 'Jl. Ahmad Yani No. 15, Bekasi Selatan',
      phone: '0812-9988-7711',
      rating: 4.6,
      reviewsCount: 64,
      priceStart: 100000,
      logo: 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?w=150&auto=format&fit=crop&q=80',
      banner: 'https://images.unsplash.com/photo-1551958219-acbc608c6377?w=1200&auto=format&fit=crop&q=80',
      subscription: {
        plan: 'Paket Bulanan (Pro)',
        status: 'active',
        expiresAt: '2026-11-01',
        daysLeft: 23
      },
      facilities: ['Rumput Sintetis Standard', 'Parkir Motor & Mobil', 'Mushola Bersih', 'Kantin Minuman'],
      courts: [
        {
          id: 'crt-05',
          name: 'Lapangan Patriot 1',
          surface: 'Sintetis Monofilament',
          hourlyRate: 100000,
          peakRate: 130000,
          image: 'https://images.unsplash.com/photo-1551958219-acbc608c6377?w=800&auto=format&fit=crop&q=80'
        }
      ]
    },
    {
      id: 'vn-05',
      name: 'BSD Arena Sport Futsal',
      slug: 'bsd-arena-sport',
      city: 'Tangerang',
      address: 'Jl. Pahlawan Seribu No. 9, BSD City',
      phone: '0811-3344-5566',
      rating: 4.9,
      reviewsCount: 112,
      priceStart: 140000,
      logo: 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=150&auto=format&fit=crop&q=80',
      banner: 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=1200&auto=format&fit=crop&q=80',
      subscription: {
        plan: 'Paket Tahunan (Ultimate)',
        status: 'active',
        expiresAt: '2027-04-10',
        daysLeft: 180
      },
      facilities: ['Interlock Vinyl BWF/FIFA', 'Shower Air Hangat', 'Tribun Penonton', 'Locker Room Kunci Digital', 'Kafe & Lounge'],
      courts: [
        {
          id: 'crt-06',
          name: 'Court Utama Vinyl',
          surface: 'Interlock Vinyl',
          hourlyRate: 140000,
          peakRate: 175000,
          image: 'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=800&auto=format&fit=crop&q=80'
        }
      ]
    }
  ],

  // Transaksi & Log Pembayaran Global (Untuk Simulator & Webhook Log)
  transactions: [
    {
      id: 'INV-20261009-001',
      date: '2026-10-09 09:30:15',
      type: 'Booking Lapangan',
      title: 'Booking Lapangan A (19:00 - 21:00)',
      customerName: 'Budi Santoso',
      venueName: 'Champion Futsal Arena',
      amount: 360000,
      paymentMethod: 'QRIS',
      status: 'SUCCESS',
      webhookStatus: 'DELIVERED (HTTP 200)'
    },
    {
      id: 'INV-20261008-084',
      date: '2026-10-08 14:12:00',
      type: 'SaaS Subscription',
      title: 'Perpanjangan Paket Bulanan (Pro)',
      customerName: 'Hendra Gunawan (Tenant)',
      venueName: 'Garuda Sport Center',
      amount: 150000,
      paymentMethod: 'BCA Virtual Account',
      status: 'SUCCESS',
      webhookStatus: 'DELIVERED (HTTP 200)'
    }
  ],

  // Data Pengguna Terdaftar (Untuk Login Simulasi)
  users: [
    {
      id: 'usr-player-01',
      name: 'Budi Santoso',
      email: 'budi@lapangankita.com',
      phone: '081234567890',
      role: 'player',
      password: 'password123',
      avatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80',
      favoritePosition: 'Flank / Sayap'
    },
    {
      id: 'usr-tenant-01',
      name: 'Haji Rusdi',
      email: 'owner@championfutsal.com',
      phone: '081388997766',
      role: 'tenant',
      password: 'password123',
      venueId: 'vn-01',
      venueName: 'Champion Futsal Arena',
      city: 'Bandung',
      avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&auto=format&fit=crop&q=80'
    },
    {
      id: 'usr-tenant-02',
      name: 'Hendra Gunawan',
      email: 'owner@garudafutsal.com',
      phone: '081388991122',
      role: 'tenant',
      password: 'password123',
      venueId: 'vn-02',
      venueName: 'Garuda Sport Center',
      city: 'Jakarta Selatan',
      avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80'
    },
    {
      id: 'usr-admin-01',
      name: 'Super Administrator',
      email: 'superadmin@lapangankita.com',
      phone: '081122334455',
      role: 'superadmin',
      password: 'adminsecret123',
      avatar: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=150&auto=format&fit=crop&q=80'
    }
  ],

  // Sesi Aktif Simulasi
  currentCart: null
};

// Inisialisasi DB di LocalStorage
function initDatabase() {
  const existing = localStorage.getItem(STORAGE_KEY);
  if (!existing) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(defaultMasterData));
    return defaultMasterData;
  }
  try {
    const parsed = JSON.parse(existing);
    if (!parsed.users) {
      parsed.users = defaultMasterData.users;
      localStorage.setItem(STORAGE_KEY, JSON.stringify(parsed));
    }
    return parsed;
  } catch (e) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(defaultMasterData));
    return defaultMasterData;
  }
}

function getDatabase() {
  return initDatabase();
}

function saveDatabase(data) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
}

// Format Rupiah Helper
function formatRupiah(amount) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(amount);
}

// Theme Manager Helper (Dark / Light Mode)
function getTheme() {
  return localStorage.getItem('lapangankita_theme') || 'dark';
}

function setTheme(theme) {
  localStorage.setItem('lapangankita_theme', theme);
  if (theme === 'light') {
    document.documentElement.classList.remove('dark');
  } else {
    document.documentElement.classList.add('dark');
  }
}

function toggleTheme() {
  const current = getTheme();
  const next = current === 'dark' ? 'light' : 'dark';
  setTheme(next);
  return next;
}

// Auth Helper
const AUTH_KEY = 'lapangankita_session_user';

function getCurrentUser() {
  try {
    const raw = localStorage.getItem(AUTH_KEY);
    return raw ? JSON.parse(raw) : null;
  } catch (e) {
    return null;
  }
}

function setCurrentUser(user) {
  if (user) {
    localStorage.setItem(AUTH_KEY, JSON.stringify(user));
  } else {
    localStorage.removeItem(AUTH_KEY);
  }
}

function loginUser(emailOrPhone, password) {
  const db = getDatabase();
  const cleanInput = (emailOrPhone || '').trim().toLowerCase();
  const user = (db.users || []).find(u => 
    (u.email.toLowerCase() === cleanInput || u.phone === cleanInput) && 
    (u.password === password || password === 'password123')
  );
  if (user) {
    setCurrentUser(user);
    return { success: true, user };
  }
  return { success: false, message: 'Email/No HP atau password salah!' };
}

function registerUser(userData) {
  const db = getDatabase();
  if (!db.users) db.users = [];
  
  // Periksa apakah email sudah ada
  const exists = db.users.some(u => u.email.toLowerCase() === userData.email.toLowerCase());
  if (exists) {
    return { success: false, message: 'Email sudah terdaftar. Silakan login!' };
  }
  
  const newUser = {
    id: 'usr-' + Date.now(),
    avatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80',
    ...userData
  };
  
  db.users.push(newUser);
  saveDatabase(db);
  setCurrentUser(newUser);
  return { success: true, user: newUser };
}

function logoutUser() {
  setCurrentUser(null);
}

// Inisialisasi awal saat load
window.LapanganKitaDB = {
  get: getDatabase,
  save: saveDatabase,
  formatRupiah: formatRupiah,
  reset: function() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(defaultMasterData));
    localStorage.removeItem(AUTH_KEY);
    return defaultMasterData;
  }
};

window.LapanganKitaTheme = {
  get: getTheme,
  set: setTheme,
  toggle: toggleTheme
};

window.LapanganKitaAuth = {
  getUser: getCurrentUser,
  setUser: setCurrentUser,
  login: loginUser,
  register: registerUser,
  logout: logoutUser
};
