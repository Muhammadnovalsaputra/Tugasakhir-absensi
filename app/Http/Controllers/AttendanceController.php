<?php

namespace App\Http\Controllers;
use App\Models\Attendance;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\AttendanceExport;
use App\Http\Requests\CheckinRequest;
use App\Http\Requests\CheckoutRequest;
use App\Services\AttendanceCorrectionService;
use App\Services\AttendanceService;
use App\Models\KoreksiAbsen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService           $attendanceService,
        protected AttendanceCorrectionService $correctionService,
    ) {
    }

    public function index(): View
    {
        return view('karyawan.index', [
            ...$this->attendanceService->getIndexData(),
            'state' => $this->correctionService->getTodayState(Auth::id()),
        ]);
    }

    public function checkin(CheckinRequest $request): RedirectResponse
    {
        $result = $this->attendanceService->checkin(Auth::user(), $request->validated());

        return back()->with($result['status'], $result['message']);
    }

    public function checkout(CheckoutRequest $request): RedirectResponse
    {
        $result = $this->attendanceService->checkout(Auth::user(), $request->validated());

        return back()->with($result['status'], $result['message']);
    }

    public function history(Request $request): View
    {
        return view('karyawan.riwayatKerja.index',
            $this->attendanceService->getHistory(Auth::user(), $request->only('start_date', 'end_date'))
        );
    }

    public function showSchedule(): View
    {
        return view('karyawan.jadwalKerja.index',
            $this->attendanceService->getScheduleData(Auth::user())
        );
    }

    //  Pimpinan 

    public function leaderIndex(Request $request): View
    {
        $attendances = $this->attendanceService->getFilteredAttendances($request->only(
            'search', 'status', 'start_date', 'end_date'
        ));

        return view('pimpinan.rekapAbsensi.index', compact('attendances'));
    }

    public function exportExcel(Request $request)
    {
        $fileName = 'rekap_absensi_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new AttendanceExport($request->query('start_date'), $request->query('end_date')),
            $fileName
        );
    }

    public function exportPdf(Request $request)
{
    $startDate = $request->query('start_date', now()->startOfMonth()->format('Y-m-d'));
    $endDate   = $request->query('end_date',   now()->format('Y-m-d'));

    $attendances = Attendance::whereBetween('date', [$startDate, $endDate])
        ->with('user')        
        ->orderBy('date')
        ->get();

    $fileName = 'rekap_absensi_' . $startDate . '_s.d_' . $endDate . '.pdf';

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pimpinan.rekapAbsensi.rekapAbsensiPdf', [
        'attendances' => $attendances,
        'startDate'   => $startDate,
        'endDate'     => $endDate,
    ])->setPaper('a4', 'landscape');

    return $pdf->download($fileName);
}

}