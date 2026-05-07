<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ResetLeaveQuota extends Command
{
    
    /**
     * The name and signature of the console command.
     * 
     *
     * @var string
     */
    protected $signature = 'leave:reset-quota';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset kuota cuti semua karyawan menjadi 12 hari (jalankan tiap 1 Januari)';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::query()->update(['leave_quota' => 12]);
        $this->info('Kuota cuti semua karyawan berhasil direset ke 12 hari.');
    }
}
