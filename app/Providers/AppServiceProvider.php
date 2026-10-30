<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Memastikan format mata uang (ribuan rata kanan) dan tanggal Indonesia
     */
    public function boot(): void
    {
        // Set Default Locale ke Bahasa Indonesia
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id', 'ind');

        // Blade Directive untuk Format Mata Uang Rupiah (e.g. Rp 50.000 atau 50.000)
        Blade::directive('rupiah', function ($expression) {
            return "<?php echo 'Rp ' . number_format($expression, 0, ',', '.'); ?>";
        });

        // Blade Directive untuk Format Angka Ribuan
        Blade::directive('ribuan', function ($expression) {
            return "<?php echo number_format($expression, 0, ',', '.'); ?>";
        });

        // Blade Directive untuk Tanggal Indonesia (e.g. 20 Oktober 2026)
        Blade::directive('tanggalIndo', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse($expression)->locale('id')->translatedFormat('d F Y'); ?>";
        });

        // Blade Directive untuk Tanggal & Waktu Lengkap Indonesia (e.g. 20 Oktober 2026, 14:30 WIB)
        Blade::directive('waktuIndo', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse($expression)->locale('id')->translatedFormat('d F Y, H:i') . ' WIB'; ?>";
        });
    }
}
