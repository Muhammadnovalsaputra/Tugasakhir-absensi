<?php

namespace App\Console\Commands;

use App\Services\AttendanceCorrectionService;
use Illuminate\Console\Command;

class MarkAbsentAsAlpa extends Command
{
    protected $signature   = 'attendance:mark-alpa';
    protected $description = 'Menandai karyawan yang tidak absen setelah threshold sebagai Alpa';

    public function handle(AttendanceCorrectionService $service): int
    {
        $count = $service->markAbsentAsAlpa();

        $this->info("Selesai. {$count} karyawan ditandai sebagai Alpa.");

        return self::SUCCESS;
    }
}