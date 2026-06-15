<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\LeavePermit;
use Carbon\Carbon;

class DashboardService
{
    public function getTodayStats(): array
    {
        $todayDate = Carbon::today();
        $attendances = Attendance::whereDate('date', $todayDate)->get();

        return [
            'hadir'     => $attendances->where('status', 'Hadir')->count(),
            'terlambat' => $attendances->where('status', 'Terlambat')->count(),
            'cuti_izin' => $this->countActiveLeavesToday(),
            'pending'   => $this->countPendingLeaves(),
        ];
    }

    public function getTodayAttendance()
    {
        return $this->getTodayAttendanceRecords()->through(fn ($item) => [
            'name'      => $item->user->name,
            'photo'     => $item->user->photo,
            'check_in'  => $item->check_in  ? Carbon::parse($item->check_in)->format('H:i:s')  : '-',
            'check_out' => $item->check_out ? Carbon::parse($item->check_out)->format('H:i:s') : '-',
            'status'    => $item->status,
        ]);
    }

    private function getTodayAttendanceRecords()
    {
        return Attendance::with('user')
            ->whereDate('date', Carbon::today())
            ->latest('check_in') 
            ->paginate(10);    
    }

    private function countActiveLeavesToday(): int
    {
        $today = Carbon::today();

        return LeavePermit::where('status', 'Approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();
    }

    private function countPendingLeaves(): int
    {
        return LeavePermit::where('status', 'Pending')->count();
    }
}