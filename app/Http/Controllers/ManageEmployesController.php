<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceSettingRequest;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\AttendanceSetting;
use App\Models\User;
use App\Services\AttendanceSettingService;
use App\Services\EmployeeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManageEmployesController extends Controller
{
    public function __construct(
        private AttendanceSettingService $attendanceSettingService,
        private EmployeeService $employeeService
    ) {}

    public function settingAttendance(): View
    {
        $setting = $this->attendanceSettingService->getActiveSetting();
        
        return view('pimpinan.settingAbsensi.index', compact('setting'));
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

    public function index(): View
    {
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