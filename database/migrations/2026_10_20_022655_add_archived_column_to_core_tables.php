<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menerapkan mekanisme soft delete pada backend (archived=1 saat hapus data)
     */
    public function up(): void
    {
        $tables = ['tenants', 'courts', 'saas_packages', 'bookings', 'users'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'archived')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->tinyInteger('archived')->default(0)->index()->after('id');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['tenants', 'courts', 'saas_packages', 'bookings', 'users'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'archived')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('archived');
                });
            }
        }
    }
};
