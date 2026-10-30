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
        Schema::table('open_slot_members', function (Blueprint $table) {
            $table->integer('slots_count')->default(1)->after('user_id');
            $table->string('notes')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('open_slot_members', function (Blueprint $table) {
            $table->dropColumn(['slots_count', 'notes']);
        });
    }
};
