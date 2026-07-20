<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceSettingRequest;
use App\Http\Requests\AttendanceLocationRequest;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\AttendanceSetting;
use App\Models\User;
use App\Services\AttendanceSettingService;
use App\Services\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\AttendanceLocation;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ManageEmployesController extends Controller
{
    public function __construct(
        private AttendanceSettingService $attendanceSettingService,
        private EmployeeService $employeeService
    ) {}

    public function settingAttendance(): View
    {
        $setting   = $this->attendanceSettingService->getActiveSetting();
        $locations = AttendanceLocation::orderBy('name')->get();

        return view('pimpinan.settingAbsensi.index', compact('setting', 'locations'));
    }

    public function editAttendanceSetting(int $id): View
    {
        $setting = $this->attendanceSettingService->getSettingById($id);
        
        return view('pimpinan.settingAbsensi.edit', compact('setting'));
    }

    public function updateAttendanceSetting(AttendanceSettingRequest $request): RedirectResponse
    {
        $this->attendanceSettingService->updateOrCreateSetting($request->validated());

        return redirect()
            ->route('pimpinan.settingAbsensi.index')
            ->with('success', 'Konfigurasi absensi global berhasil diperbarui!');
    }

    // ─── Lokasi Absensi (Multi) ─────────────────────────────────

    public function storeLocation(AttendanceLocationRequest $request): RedirectResponse
    {
        AttendanceLocation::create($request->validated());

        return redirect()
            ->route('pimpinan.settingAbsensi.index')
            ->with('success', 'Lokasi absensi berhasil ditambahkan.');
    }

    public function updateLocation(AttendanceLocationRequest $request, AttendanceLocation $location): RedirectResponse
    {
        $location->update($request->validated());

        return redirect()
            ->route('pimpinan.settingAbsensi.index')
            ->with('success', 'Lokasi absensi berhasil diperbarui.');
    }

    public function destroyLocation(AttendanceLocation $location): RedirectResponse
    {
        $location->delete();

        return redirect()
            ->route('pimpinan.settingAbsensi.index')
            ->with('success', 'Lokasi absensi berhasil dihapus.');
    }

    // ─── Kelola Karyawan ─────────────────────────────────────────

    public function index(Request $request): View|JsonResponse
        {
            if ($request->wantsJson()) {
                $employees = $this->employeeService->getAllEmployeesExcept(
                    auth()->id(), 
                    $request->query('search')
                );
                return response()->json($employees);
            }

            $employees = $this->employeeService->getAllEmployeesExcept(auth()->id());
            
            return view('pimpinan.kelolaKaryawan.index', compact('employees'));
        }

    public function store(EmployeeStoreRequest $request): RedirectResponse
    {
        $this->employeeService->createEmployee($request->validated());

        return redirect()
            ->back()
            ->with('success', 'Karyawan berhasil ditambahkan');
    }


    public function update(EmployeeUpdateRequest $request, int $id): RedirectResponse
    {
        $this->employeeService->updateEmployee($id, $request->validated());

        return redirect()
            ->back()
            ->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->employeeService->deleteEmployee($id, auth()->id());
            
            return redirect()
                ->back()
                ->with('success', 'Karyawan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}