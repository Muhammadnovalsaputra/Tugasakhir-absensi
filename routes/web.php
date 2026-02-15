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

// 1. Rute Root: Langsung ke login jika belum login, ke dashboard jika sudah
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// 2. Rute yang Membutuhkan Login
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

    // Rute Absensi
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
    Route::post('/attendance/checkout', [AttendanceController::class, 'checkout'])->name('attendance.checkout');

    // Profile
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

    // Kelola Karyawan
    Route::middleware(['auth'])->group(function () {
    Route::get('/pimpinan/kelolaKaryawan', [ManageEmployesController::class, 'index'])->name('kelolaKaryawan.index');
    Route::post('/pimpinan/kelolaKaryawan', [ManageEmployesController::class, 'store'])->name('kelolaKaryawan.store');
    Route::put('/pimpinan/kelolaKaryawan/{id}', [ManageEmployesController::class, 'update'])->name('kelolaKaryawan.update');
Route::delete('/pimpinan/kelolaKaryawan/{id}', [ManageEmployesController::class, 'destroy'])->name('kelolaKaryawan.destroy');
});

    // Riwayat
    Route::get('/Attendance', [AttendanceController::class, 'history'])
    ->name('karyawan.riwayat');
    
});

// 3. Load rute bawaan (login, logout, register)
require __DIR__.'/auth.php';