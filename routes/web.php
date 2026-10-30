<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MidtransController;

// =========================================================================
// 1. ROUTE PUBLIK (Bisa Diakses Pengunjung Siapapun Tanpa Login)
// =========================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Midtrans Webhook Notification & Finish Return URL
Route::post('/midtrans/notification', [MidtransController::class, 'handleNotification'])->name('midtrans.notification');
Route::get('/midtrans/finish', [MidtransController::class, 'finish'])->name('midtrans.finish');

// Route Portal Auth (Login & Register)
Route::get('/auth', [AuthController::class, 'portal'])->name('auth.portal');
Route::get('/login', [AuthController::class, 'portal'])->name('login'); // Alias login resmi Laravel
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Route Google OAuth SSO (Login with Google)
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

use App\Http\Controllers\TenantController;

// =========================================================================
// 2. ROUTE TERPROTEKSI MIDDLEWARE AUTH (PEMAIN & MITRA)
// =========================================================================
Route::middleware('auth')->group(function () {
    // =========================================================================
    // FITUR KHUSUS PEMAIN (DILARANG DIAKSES OLEH AKUN TENANT / MITRA VENUE)
    // =========================================================================
    Route::middleware('player.only')->group(function () {
        // Dashboard Pemain
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('player.dashboard');
        Route::post('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('player.profile.update');
        Route::post('/dashboard/booking/{id}/check-status', [DashboardController::class, 'checkBookingStatus'])->name('player.booking.check-status');
        Route::post('/dashboard/booking/{id}/cancel', [DashboardController::class, 'cancelBooking'])->name('player.booking.cancel');
        
        // Fitur Komunitas: Open Slot Mabar
        Route::get('/open-slot', [CommunityController::class, 'openSlot'])->name('community.open-slot');
        Route::post('/open-slot/store', [CommunityController::class, 'storeOpenSlot'])->name('community.open-slot.store');
        Route::post('/open-slot/join', [CommunityController::class, 'joinOpenSlot'])->name('community.open-slot.join');
        Route::post('/open-slot/cancel/{id}', [CommunityController::class, 'cancelOpenSlot'])->name('community.open-slot.cancel');

        // Fitur Komunitas: Sparing Match
        Route::get('/sparring', [CommunityController::class, 'sparring'])->name('community.sparring');
        Route::post('/sparring/store', [CommunityController::class, 'storeSparring'])->name('community.sparring.store');
        Route::post('/sparring/accept', [CommunityController::class, 'acceptSparring'])->name('community.sparring.accept');
        Route::post('/sparring/cancel/{id}', [CommunityController::class, 'cancelSparring'])->name('community.sparring.cancel');
        
        // Pembayaran Reservasi / Checkout Pemain
        Route::get('/payment', [BookingController::class, 'payment'])->name('booking.payment');
        Route::post('/payment/confirm', [BookingController::class, 'confirmPayment'])->name('booking.payment.confirm');
    });

    // Endpoint Terpadu Midtrans Snap & Simulator (Pemain & Mitra)
    Route::post('/midtrans/snap-token', [MidtransController::class, 'getSnapToken'])->name('midtrans.snap');
    Route::post('/midtrans/simulate-payment', [MidtransController::class, 'simulatePayment'])->name('midtrans.simulate');

    // Fitur Publik / Akses Bersama
    Route::get('/venues', [VenueController::class, 'index'])->name('venues.index');
    Route::get('/venues/{slug}', [VenueController::class, 'show'])->name('venues.show');
    Route::get('/open-slot/invoice/{id}/pdf', [CommunityController::class, 'printOpenSlotInvoicePdf'])->name('community.open-slot.invoice.pdf');
    Route::get('/sparring/invoice/{paymentId}/pdf', [CommunityController::class, 'printSparringInvoicePdf'])->name('community.sparring.invoice.pdf');
    Route::get('/booking/ticket/{code}/pdf', [BookingController::class, 'printTicketPdf'])->name('booking.ticket.pdf');

    // =====================================================================
    // FITUR MITRA TENANT (OPERASIONAL LAPANGAN & SAAS BILLING 7 HARI TRIAL)
    // =====================================================================
    Route::prefix('mitra')->name('tenant.')->middleware('tenant.only')->group(function () {
        Route::get('/dashboard', [TenantController::class, 'dashboard'])->name('dashboard');
        Route::get('/analytics', [TenantController::class, 'analytics'])->name('analytics');
        Route::get('/analytics/pdf', [TenantController::class, 'exportAnalyticsPdf'])->name('analytics.pdf');
        Route::post('/analytics/gemini-ask', [TenantController::class, 'askGemini'])->name('analytics.gemini-ask');
        Route::get('/billing', [TenantController::class, 'billing'])->name('billing');
        Route::get('/billing/checkout/{package}', [TenantController::class, 'checkoutSubscription'])->name('billing.checkout');
        Route::get('/billing/invoice/{id}/pdf', [TenantController::class, 'exportInvoicePdf'])->name('billing.invoice.pdf');
        Route::post('/billing/pay', [TenantController::class, 'paySubscription'])->name('billing.pay');
        Route::get('/courts', [TenantController::class, 'courts'])->name('courts');
        Route::post('/courts', [TenantController::class, 'storeCourt'])->name('courts.store');
        Route::post('/courts/toggle-slot', [TenantController::class, 'toggleSlot'])->name('courts.toggle-slot');
        Route::delete('/courts/{id}', [TenantController::class, 'destroyCourt'])->name('courts.destroy');
        Route::post('/courts/{id}/restore', [TenantController::class, 'restoreCourt'])->name('courts.restore');
        Route::get('/profile', [TenantController::class, 'profile'])->name('profile');
        Route::post('/profile', [TenantController::class, 'updateProfile'])->name('profile.update');
    });

    // =====================================================================
    // FITUR SUPER ADMIN / PEMILIK WEB (ANALITIK PLATFORM & KONTROL MASTER)
    // =====================================================================
    Route::prefix('admin')->name('admin.')->middleware('superadmin.only')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/analytics', [\App\Http\Controllers\AdminController::class, 'analytics'])->name('analytics');
        Route::post('/tenants', [\App\Http\Controllers\AdminController::class, 'storeTenant'])->name('tenants.store');
        Route::delete('/tenants/{id}', [\App\Http\Controllers\AdminController::class, 'destroyTenant'])->name('tenants.destroy');
        Route::post('/tenants/{id}/restore', [\App\Http\Controllers\AdminController::class, 'restoreTenant'])->name('tenants.restore');
        Route::post('/tenants/{id}/toggle-status', [\App\Http\Controllers\AdminController::class, 'toggleTenantStatus'])->name('tenants.toggle-status');
        Route::post('/tenants/{id}/reset-password', [\App\Http\Controllers\AdminController::class, 'resetTenantPassword'])->name('tenants.reset-password');
        Route::post('/tenants/{id}/extend', [\App\Http\Controllers\AdminController::class, 'extendSubscription'])->name('tenants.extend');
        Route::post('/packages/{id}', [\App\Http\Controllers\AdminController::class, 'updatePackage'])->name('packages.update');
        Route::delete('/packages/{id}', [\App\Http\Controllers\AdminController::class, 'destroyPackage'])->name('packages.destroy');
        Route::post('/packages/{id}/restore', [\App\Http\Controllers\AdminController::class, 'restorePackage'])->name('packages.restore');
    });
});

