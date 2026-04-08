<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeavePermitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManageEmployesController;
use App\Models\Attendance;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});


Route::middleware(['auth', 'verified'])->group(function () {
    
    
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'Pimpinan') {
            return redirect()->route('pimpinan.dashboard');
        }

        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', Carbon::today())
            ->first();

        return view('karyawan.index', compact('attendance'));
    })->name('dashboard');

    

    // Dashboard Pimpinan
    Route::get('/pimpinan/dashboard', [DashboardController::class, 'index'])->name('pimpinan.dashboard');
    Route::get('/pimpinan/pengajuanCuti', [LeavePermitController::class, 'adminIndex'])
    ->name('pimpinan.pengajuanCuti.index');
    Route::patch('/pimpinan/pengajuan-cuti/{id}/update', [LeavePermitController::class, 'updateStatus'])
    ->name('pimpinan.pengajuanCuti.update');
    Route::get('/rekapAbsensi', [AttendanceController::class, 'leaderIndex'])
    ->name('pimpinan.rekapAbsensi.index');
    Route::get('/rekapAbsensi/export', [AttendanceController::class, 'exportExcel'])
    ->name('pimpinan.rekapAbsensi.export');

    
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');

    
    Route::get('/profile/app', [ProfileController::class, 'index'])->name('profile.app');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pengajuan Cuti Karyawan
    Route::get('/LeavePermit', [LeavePermitController::class, 'index'])
    ->name('karyawan.pengajuanCuti.index');
    Route::post('/LeavePermit', [LeavePermitController::class, 'store'])->name('karyawan.pengajuanCuti.store');
    Route::get('/LeavePermit/{id}/edit', [LeavePermitController::class, 'edit'])->name('karyawan.pengajuanCuti.edit');
    Route::put('/LeavePermit/{id}', [LeavePermitController::class, 'update'])->name('karyawan.pengajuanCuti.update');
    Route::delete('/LeavePermit/{id}', [LeavePermitController::class, 'destroy'])->name('karyawan.pengajuanCuti.destroy');

    Route::get('/workSchedule', [AttendanceController::class, 'showSchedule'])->name('karyawan.jadwal');

    // Kelola Karyawan
    Route::middleware(['auth'])->group(function () {
    Route::get('/pimpinan/kelolaKaryawan', [ManageEmployesController::class, 'index'])->name('kelolaKaryawan.index');
    Route::post('/pimpinan/kelolaKaryawan', [ManageEmployesController::class, 'store'])->name('kelolaKaryawan.store');
    Route::put('/pimpinan/kelolaKaryawan/{id}', [ManageEmployesController::class, 'update'])->name('kelolaKaryawan.update');
    Route::delete('/pimpinan/kelolaKaryawan/{id}', [ManageEmployesController::class, 'destroy'])->name('kelolaKaryawan.destroy');
    });

    Route::prefix('pimpinan')->name('pimpinan.')->group(function () {
    
    Route::get('/setting-absensi', [ManageEmployesController::class, 'settingAttendance'])->name('settingAbsensi.index');
    Route::get('/setting-absensi/{id}/edit', [ManageEmployesController::class, 'editAttendanceSetting'])->name('settingAbsensi.edit');
    Route::put('/setting-absensi/{id}', [ManageEmployesController::class, 'updateAttendanceSetting'])->name('settingAbsensi.update');
});

    
    Route::get('/Attendance', [AttendanceController::class, 'history'])
    ->name('karyawan.riwayat');

    
});

// 3. Load rute bawaan (login, logout, register)
require __DIR__.'/auth.php';