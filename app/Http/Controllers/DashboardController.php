<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\LeavePermit;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        
        $attendances = Attendance::with('user')
            ->whereDate('date', $today)
            ->get();

        
        $countHadir = $attendances->where('status', 'Hadir')->count();
        $countTerlambat = $attendances->where('status', 'Terlambat')->count();

        
        $countCutiIzin = LeavePermit::where('status', 'Approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        
        $countPending = LeavePermit::where('status', 'Pending')->count();

        $stats = [
            'hadir'     => $countHadir,
            'terlambat' => $countTerlambat,
            'cuti_izin' => $countCutiIzin,
            'pending'   => $countPending
        ];

       
        $todayAttendance = $attendances->map(function ($item) {
            return [
                'name'      => $item->user->name,
                'check_in'  => $item->check_in ? Carbon::parse($item->check_in)->format('H:i:s') : '-',
                'check_out' => $item->check_out ? Carbon::parse($item->check_out)->format('H:i:s') : '-',
                'status'    => $item->status
            ];
        });
        $hasPendingLeave = LeavePermit::where('status', 'Pending')->exists();

        return view('pimpinan.dashboard', compact('stats', 'todayAttendance'));
    }
}
