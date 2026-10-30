<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('openslot:auto-cancel', function () {
    $count = app(\App\Http\Controllers\CommunityController::class)->autoCancelExpiredRooms();
    $this->info("Pemeriksaan selesai. Berhasil membatalkan {$count} room mabar kedaluwarsa & melepas jadwal lapangan ke publik.");
})->purpose('Auto cancel open slot rooms where quota is unfulfilled H-2 hours before kick-off')->everyTenMinutes();

