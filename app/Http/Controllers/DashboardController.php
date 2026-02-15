<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LeavePermit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){

        $countCutiIzin = LeavePermit::where('status','Approved')
        ->whereDate('start_date','<=',now())
        ->whereDate('end_date','>=',now())
        ->count();

        $countPending = LeavePermit::where('status','Pending')->count();

        $stats = [
            'hadir' => 2,         
            'terlambat' => 1,     
            'cuti_izin' => $countCutiIzin,
            'pending' => $countPending    
        ];

        $todayAttendance = [
            [
                'name' => 'John Doe',
                'check_in' => '08:05:00',
                'check_out' => '00:17:01',
                'status' => 'Hadir'
            ],
            [
                'name' => 'Jane Smith',
                'check_in' => '08:25:00',
                'check_out' => '-',
                'status' => 'Terlambat'
            ]
        ];

        return view('pimpinan.dashboard', compact('stats', 'todayAttendance'));
    }
}
