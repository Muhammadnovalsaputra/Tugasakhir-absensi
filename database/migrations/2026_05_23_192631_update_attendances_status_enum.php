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
         DB::statement("
            ALTER TABLE attendances 
            MODIFY COLUMN status ENUM(
                'Hadir',
                'Izin',
                'Sakit',
                'Terlambat',
                'Alpa',
                'Cuti',
                'Hadir (Koreksi)',
                'Setengah Hari'
            ) NOT NULL DEFAULT 'Hadir'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE attendances 
            MODIFY COLUMN status ENUM(
                'Hadir',
                'Izin',
                'Sakit',
                'Terlambat'
            ) NOT NULL DEFAULT 'Hadir'
        ");
    }
};
