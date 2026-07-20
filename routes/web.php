<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeavePermitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManageEmployesController;
use App\Http\Controllers\AttendanceCorrectionController; 
use App\Models\Attendance;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schedule;
use Carbon\Carbon;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Schedule::command('leave:reset-quota')->yearlyOn(1, 1, '00:00');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user->role === 'Pimpinan' ) {
            return redirect()->route('pimpinan.dashboard');
        }

        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', Carbon::today())
            ->first();

        $setting = \App\Models\AttendanceSetting::getActive();


        return app(\App\Http\Controllers\AttendanceController::class)->index();
        })->name('dashboard');

    Route::middleware(['role:Pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/pengajuan-cuti', [LeavePermitController::class, 'adminIndex'])->name('pengajuanCuti.index');
        Route::patch('/pengajuan-cuti/{id}', [LeavePermitController::class, 'updateStatus'])->name('pengajuanCuti.update');

        Route::get('/rekap-absensi', [AttendanceController::class, 'leaderIndex'])->name('rekapAbsensi.index');
        Route::get('/rekap-absensi/export', [AttendanceController::class, 'exportExcel'])->name('rekapAbsensi.export');
        Route::get('rekap-absensi/export-pdf', [AttendanceController::class, 'exportPdf'])->name('rekapAbsensi.exportPdf');

        // ─── Setting Absensi (global: jam kerja, jadwal) ────────────────
        Route::get('/setting-absensi', [ManageEmployesController::class, 'settingAttendance'])->name('settingAbsensi.index');
        Route::post('/setting-absensi', [ManageEmployesController::class, 'updateAttendanceSetting'])->name('settingAbsensi.update');

        // ─── Lokasi Absensi (multi-titik) ────────────────────────────────
        Route::prefix('setting-absensi/lokasi')->name('settingAbsensi.lokasi.')->group(function () {
            Route::post('/', [ManageEmployesController::class, 'storeLocation'])->name('store');
            Route::put('/{location}', [ManageEmployesController::class, 'updateLocation'])->name('update');
            Route::delete('/{location}', [ManageEmployesController::class, 'destroyLocation'])->name('destroy');
        });

        Route::get('/kelola-karyawan', [ManageEmployesController::class, 'index'])->name('kelolaKaryawan.index');
        Route::post('/kelola-karyawan', [ManageEmployesController::class, 'store'])->name('kelolaKaryawan.store');
        Route::put('/kelola-karyawan/{id}', [ManageEmployesController::class, 'update'])->name('kelolaKaryawan.update');
        Route::delete('/kelola-karyawan/{id}', [ManageEmployesController::class, 'destroy'])->name('kelolaKaryawan.destroy');

       
     Route::prefix('koreksi-absen')->name('koreksiAbsen.')->group(function () {
            Route::get('', [AttendanceCorrectionController::class, 'leaderIndex'])->name('index'); 
            Route::get('{correction}', [AttendanceCorrectionController::class, 'show'])->name('show'); 
            Route::post('{correction}', [AttendanceCorrectionController::class, 'review'])->name('review'); 
        });
    });
    

    //  ROUTES KARYAWAN

    Route::post('/attendance/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('karyawan.riwayatKerja.index');

    Route::prefix('karyawan/koreksi-absen')->name('karyawan.koreksiAbsen.')->group(function () {
        Route::get('create', [AttendanceCorrectionController::class, 'create'])->name('create'); 
        Route::post('', [AttendanceCorrectionController::class, 'store'])->name('store'); 
    });

    Route::prefix('leave-permit')->name('karyawan.pengajuanCuti.')->group(function () {
        Route::get('/', [LeavePermitController::class, 'index'])->name('index');
        Route::post('/', [LeavePermitController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [LeavePermitController::class, 'edit'])->name('edit');
        Route::put('/{id}', [LeavePermitController::class, 'update'])->name('update');
        Route::delete('/{id}', [LeavePermitController::class, 'destroy'])->name('destroy');
    });

    Route::get('/work-schedule', [AttendanceController::class, 'showSchedule'])->name('karyawan.jadwal');

    Route::get('/profile/app', [ProfileController::class, 'index'])->name('profile.app');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';