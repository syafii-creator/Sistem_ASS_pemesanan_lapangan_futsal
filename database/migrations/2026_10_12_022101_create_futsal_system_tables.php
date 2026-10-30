<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modifikasi tabel users untuk menambahkan role & info futsal
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('pemain')->after('password'); // pemain, tenant, superadmin
            $table->string('phone')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('phone');
            $table->unsignedBigInteger('tenant_id')->nullable()->after('avatar');
        });

        // 2. Tabel SaaS Packages (Master Paket Berlangganan)
        Schema::create('saas_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Starter, Pro Bisnis, Enterprise
            $table->string('billing_cycle'); // mingguan, bulanan, tahunan
            $table->decimal('price', 15, 2);
            $table->integer('max_courts')->default(2);
            $table->json('features')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->string('badge_text')->nullable();
            $table->timestamps();
        });

        // 3. Tabel Tenants (Mitra Lapangan / Venue Futsal)
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_user_id')->nullable();
            $table->string('name'); // Champion Futsal Arena, Garuda Sport Center, dll.
            $table->string('slug')->unique();
            $table->string('city');
            $table->text('address');
            $table->string('phone_wa');
            $table->text('description')->nullable();
            $table->json('facilities')->nullable(); // Parkir, Musholla, Kantin, Locker, Shower
            $table->string('banner_image')->nullable();
            $table->string('logo_image')->nullable();
            $table->string('status')->default('active'); // active, grace, suspended
            $table->timestamps();
        });

        // 4. Tabel Langganan Tenant (Tenant Subscriptions)
        Schema::create('tenant_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('saas_packages')->onDelete('cascade');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('status')->default('active'); // active, grace, expired
            $table->decimal('price_paid', 15, 2);
            $table->timestamps();
        });

        // 5. Tabel Courts (Unit Lapangan per Tenant)
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->string('name'); // Lapangan A, Lapangan B
            $table->string('surface_type'); // Rumput Sintetis, Vinyl, Interlock
            $table->decimal('price_regular', 12, 2)->default(120000); // 08:00 - 16:00
            $table->decimal('price_peak', 12, 2)->default(180000); // 17:00 - 23:00
            $table->string('status')->default('active'); // active, maintenance
            $table->timestamps();
        });

        // 6. Tabel Bookings (Reservasi Lapangan)
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // LK-BKG-XXXXXX
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('court_id')->constrained('courts')->onDelete('cascade');
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_hours')->default(1);
            $table->decimal('court_price', 12, 2);
            $table->decimal('service_fee', 12, 2)->default(2500);
            $table->decimal('total_amount', 12, 2);
            $table->string('booking_type')->default('regular'); // regular, open_slot, sparring
            $table->string('status')->default('pending'); // pending, paid, cancelled, completed
            $table->timestamps();
        });

        // 7. Tabel Payments & Sandbox Gateway Log
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // LK-TRX-XXXXXX
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->foreignId('subscription_id')->nullable()->constrained('tenant_subscriptions')->onDelete('set null');
            $table->string('payment_type'); // qris, bca_va, mandiri_va, bni_va, ewallet
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('pending'); // pending, success, expired, failed
            $table->json('payload')->nullable(); // Data respons simulasi gateway Midtrans/Xendit
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();
        });

        // 8. Tabel Komunitas Open Slot (Mabar Patungan)
        Schema::create('open_slot_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('host_user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->integer('target_players')->default(10);
            $table->integer('current_players')->default(1);
            $table->decimal('price_per_person', 12, 2);
            $table->string('status')->default('open'); // open, full, cancelled
            $table->timestamps();
        });

        // 9. Tabel Anggota Peserta Open Slot
        Schema::create('open_slot_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('open_slot_rooms')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->string('status')->default('paid'); // paid, refunded
            $table->timestamps();
        });

        // 10. Tabel Komunitas Sparing (Tantangan Antar Tim)
        Schema::create('sparring_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('host_team_name');
            $table->string('challenger_team_name')->nullable();
            $table->foreignId('challenger_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('skill_tier')->default('menengah'); // amatir, menengah, semipro
            $table->string('split_rule')->default('50_50'); // 50_50, kalah_bayar
            $table->string('contact_wa');
            $table->string('status')->default('open'); // open, accepted, completed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sparring_challenges');
        Schema::dropIfExists('open_slot_members');
        Schema::dropIfExists('open_slot_rooms');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('courts');
        Schema::dropIfExists('tenant_subscriptions');
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('saas_packages');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'avatar', 'tenant_id']);
        });
    }
};
