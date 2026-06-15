<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService)
    {
    }

    public function index()
    {
        $stats           = $this->dashboardService->getTodayStats();
        $todayAttendance = $this->dashboardService->getTodayAttendance();

        return view('pimpinan.dashboard', compact('stats', 'todayAttendance'));
    }
}