<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeavePermitRequest;
use App\Http\Requests\LeaveStatusUpdateRequest;
use App\Services\LeavePermitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Models\LeavePermit; 

class LeavePermitController extends Controller
{
    public function __construct(
        private LeavePermitService $leavePermitService
    ) {
        
    }

    public function index(Request $request): View
    {
         $user = auth()->user();

        $data = $this->leavePermitService->getUserLeaveDashboard($user);

        return view('karyawan.pengajuanCuti.index', $data);
    }

    public function store(LeavePermitRequest $request): RedirectResponse
    {
        Log::info('Store request data:', $request->all());

        $user = auth()->user();
        
        try {
            $duration = $this->leavePermitService->storeLeaveRequest($user, $request->validated());
            
            return redirect()
                ->back()
                ->with('success', "Pengajuan cuti {$duration} hari berhasil diajukan.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function edit(int $id): View
    {
        $leave = $this->leavePermitService->getPendingLeaveRequest($id, auth()->id());
        
        return view('karyawan.pengajuanCuti.edit', compact('leave'));
    }

    public function update(LeavePermitRequest $request, int $id): RedirectResponse
    {
        $user = auth()->user();
        
        try {
            $this->leavePermitService->updateLeaveRequest($user, $id, $request->validated());
            
            return redirect()
                ->route('karyawan.pengajuanCuti.index')
                ->with('success', 'Pengajuan cuti berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->leavePermitService->cancelLeaveRequest($id, auth()->id());
            
            return redirect()
                ->back()
                ->with('success', 'Pengajuan cuti berhasil dibatalkan.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function adminIndex(Request $request): View
    {
       $search = $request->query('search');

        $allLeaves = $this->leavePermitService->getAllLeaveRequests($search);
        $stats = $this->leavePermitService->getLeaveStatistics();

        return view('pimpinan.pengajuanCuti.index', compact('allLeaves', 'stats', 'search'));
    }

    public function updateStatus(LeaveStatusUpdateRequest $request, int $id): RedirectResponse
    {
        try {
            $this->leavePermitService->updateLeaveStatus($id, $request->status);
            
            return redirect()
                ->back()
                ->with('success', 'Status pengajuan cuti berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}