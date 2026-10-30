<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Tenant;
use App\Models\Court;
use App\Models\SaasPackage;
use App\Models\TenantSubscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed master packages, user accounts for login, and 2 venues.
     * All transactional data (bookings, payments, open slot, sparring) are left completely empty.
     */
    public function run(): void
    {
        // =========================================================================
        // 1. MASTER PAKET SAAS
        // =========================================================================
        $pkgStarter = SaasPackage::create([
            'name' => 'Paket Starter (Bulanan)',
            'billing_cycle' => 'bulanan',
            'price' => 50000,
            'max_courts' => 2,
            'features' => json_encode([
                'Maksimal 2 Unit Lapangan',
                'Jadwal Online & Kunci Kasir Offline',
                'Laporan Omset Bulanan Standar',
                'Dukungan CS WhatsApp',
            ]),
            'is_popular' => false,
            'badge_text' => 'Starter',
        ]);

        $pkgPro = SaasPackage::create([
            'name' => 'Paket Pro (Bulanan)',
            'billing_cycle' => 'bulanan',
            'price' => 150000,
            'max_courts' => 5,
            'features' => json_encode([
                'Maksimal 5 Unit Lapangan',
                'Modul Analitik & Konsultasi Bisnis',
                'Fitur Komunitas Mabar & Sparing',
                'Cetak Laporan Keuangan PDF',
                'Dukungan CS Prioritas 24/7',
            ]),
            'is_popular' => true,
            'badge_text' => 'Paling Populer',
        ]);

        $pkgYearly = SaasPackage::create([
            'name' => 'Paket Tahunan Juara',
            'billing_cycle' => 'tahunan',
            'price' => 1200000,
            'max_courts' => 99,
            'features' => json_encode([
                'Unlimited Lapangan (Tanpa Batas)',
                'Masa Aktif 1 Tahun Penuh (365 Hari)',
                'Hemat Biaya 4 Bulan Penuh',
                'Modul Analitik Bisnis Prioritas',
                'Dedicated CS & Prioritas Sistem',
            ]),
            'is_popular' => false,
            'badge_text' => 'Hemat 4 Bulan',
        ]);

        // =========================================================================
        // 2. AKUN LOGIN (SUPERADMIN, 2 PEMILIK VENUE, & PEMAIN BUDI)
        // =========================================================================
        $superAdmin = User::create([
            'name' => 'Master Administrator',
            'email' => 'admin@lapangankita.id',
            'role' => 'superadmin',
            'phone' => '08119999888',
            'password' => Hash::make('password123'),
        ]);

        $ownerChampion = User::create([
            'name' => 'H. Rusdi (Owner Champion)',
            'email' => 'owner@championfutsal.com',
            'role' => 'tenant',
            'phone' => '081321456789',
            'password' => Hash::make('password123'),
        ]);

        $ownerGaruda = User::create([
            'name' => 'Hendra Gunawan (Owner Garuda)',
            'email' => 'owner@garudafutsal.com',
            'role' => 'tenant',
            'phone' => '082198765432',
            'password' => Hash::make('password123'),
        ]);

        $budi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'role' => 'pemain',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
        ]);

        $dimas = User::create([
            'name' => 'Dimas Pratama',
            'email' => 'dimas@gmail.com',
            'role' => 'pemain',
            'phone' => '081298765432',
            'password' => Hash::make('password123'),
        ]);

        // =========================================================================
        // 3. DUA MITRA VENUE BESERTA UNIT LAPANGAN
        // =========================================================================
        
        // --- VENUE 1: Champion Futsal Arena (Bandung) ---
        $tenant1 = Tenant::create([
            'owner_user_id' => $ownerChampion->id,
            'name' => 'Champion Futsal Arena',
            'slug' => 'champion-futsal-arena-bandung',
            'city' => 'Bandung',
            'address' => 'Jl. Soekarno Hatta No. 428, Batununggal, Kota Bandung',
            'phone_wa' => '081321456789',
            'description' => 'Venue futsal standar nasional dengan lantai Vinyl Interlock FIFA dan Rumput Sintetis Monofilamen tebal 5cm. Dilengkapi shower air hangat, musholla AC, kantin, dan area parkir kapasitas 40 unit.',
            'facilities' => json_encode(['Parkir Mobil & Motor Luas', 'Musholla Ber-AC', 'Kantin & Refreshment Bar', 'Locker Room & Shower Air Hangat', 'Tribun Penonton 200 Kursi', 'Free WiFi High-Speed']),
            'banner_image' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=1200&q=80',
            'logo_image' => 'https://images.unsplash.com/photo-1522778119026-d647f0596c20?auto=format&fit=crop&w=300&q=80',
            'status' => 'active',
        ]);
        $ownerChampion->update(['tenant_id' => $tenant1->id]);

        TenantSubscription::create([
            'tenant_id' => $tenant1->id,
            'package_id' => $pkgPro->id,
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(28),
            'status' => 'active',
            'price_paid' => 150000,
        ]);

        Court::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Lapangan 1 (Vinyl Interlock FIFA)',
            'surface_type' => 'Vinyl Interlock Pro',
            'price_regular' => 120000,
            'price_peak' => 180000,
            'status' => 'active',
        ]);

        Court::create([
            'tenant_id' => $tenant1->id,
            'name' => 'Lapangan 2 (Rumput Sintetis)',
            'surface_type' => 'Rumput Sintetis Monofilamen',
            'price_regular' => 100000,
            'price_peak' => 150000,
            'status' => 'active',
        ]);

        // --- VENUE 2: Garuda Sport Center (Jakarta Selatan) ---
        $tenant2 = Tenant::create([
            'owner_user_id' => $ownerGaruda->id,
            'name' => 'Garuda Sport Center',
            'slug' => 'garuda-sport-center-jakarta',
            'city' => 'Jakarta Selatan',
            'address' => 'Jl. RS Fatmawati Raya No. 18, Cilandak, Jakarta Selatan',
            'phone_wa' => '082198765432',
            'description' => 'Pusat futsal dan mini soccer terlengkap di Jakarta Selatan. Menggunakan sistem pendingin sirkulasi modern dan pencahayaan lampu LED 1000 Lux berstandar turnamen internasional.',
            'facilities' => json_encode(['Parkir Basement Luas', 'Musholla Bersih', 'Kantin Sehat & Isotonik', 'Kamar Mandi & Shower', 'Papan Skor Digital']),
            'banner_image' => 'https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&w=1200&q=80',
            'logo_image' => 'https://images.unsplash.com/photo-1508098682722-e99c43a406b2?auto=format&fit=crop&w=300&q=80',
            'status' => 'active',
        ]);
        $ownerGaruda->update(['tenant_id' => $tenant2->id]);

        TenantSubscription::create([
            'tenant_id' => $tenant2->id,
            'package_id' => $pkgStarter->id,
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => Carbon::now()->addDays(25),
            'status' => 'active',
            'price_paid' => 50000,
        ]);

        Court::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Court Utama (Taraflex Vinyl)',
            'surface_type' => 'Taraflex Vinyl 7mm',
            'price_regular' => 150000,
            'price_peak' => 220000,
            'status' => 'active',
        ]);

        Court::create([
            'tenant_id' => $tenant2->id,
            'name' => 'Court 2 (Sintetis Rubber Infill)',
            'surface_type' => 'Rumput Sintetis Rubber Infill',
            'price_regular' => 130000,
            'price_peak' => 190000,
            'status' => 'active',
        ]);

        // =========================================================================
        // DATA TRANSAKSIONAL (BOOKINGS, PAYMENTS, OPEN SLOT, SPARRING) DIBIARKAN KOSONG
        // =========================================================================
    }
}