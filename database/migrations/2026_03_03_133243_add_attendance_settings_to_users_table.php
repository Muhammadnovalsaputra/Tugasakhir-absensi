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
        Schema::table('users', function (Blueprint $table) {
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->time('startTime')->default('08:00');
            $table->time('quitTime')->default('17:00');
            $table->json('workSchedule')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom yang baru saja ditambahkan jika di-rollback
            $table->dropColumn(['latitude', 'longitude', 'startTime', 'quitTime', 'workSchedule']);
        });
    }
};