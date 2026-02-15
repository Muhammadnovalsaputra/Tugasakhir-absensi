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
        Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->date('date');
        $table->time('check_in')->nullable();
        $table->time('check_out')->nullable();
        $table->string('latitude_in')->nullable();
        $table->string('longitude_in')->nullable();
        $table->string('latitude_out')->nullable();
        $table->string('longitude_out')->nullable();
        $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Terlambat'])->default('Hadir');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
