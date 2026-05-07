<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeavePermit; 
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class LeavePermitController extends Controller
{

    private function getUsedQuota(int $userId): int
    {
        $approvedLeaves = LeavePermit::where('user_id', $userId)
        ->where('status', 'Approved')
        ->get();

        return $approvedLeaves->sum(function ($leave){
            return Carbon::parse($leave->start_date)
            ->diffInDays(Carbon::parse($leave->end_date))+1;
        });
    }

    public function index()
    {
       $user = Auth::user();

        $leaveHistory = LeavePermit::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $usedQuota      = $this->getUsedQuota($user->id);
        $totalQuota     = $user->leave_quota ?? 12;
        $remainingQuota = max(0, $totalQuota - $usedQuota);

        return view('karyawan.pengajuanCuti.index', compact(
            'leaveHistory',
            'usedQuota',
            'totalQuota',
            'remainingQuota'
        ));
    }

    public function store(Request $request){
        $user = Auth::user();

        $request->validate([
            'leave_type' => 'required',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|min:5',
        ]);

        // Hitung durasi pengajuan baru
        $duration = Carbon::parse($request->start_date)
            ->diffInDays(Carbon::parse($request->end_date)) + 1;

        // Cek sisa kuota
        $usedQuota      = $this->getUsedQuota($user->id);
        $totalQuota     = $user->leave_quota ?? 12;
        $remainingQuota = $totalQuota - $usedQuota;

        if ($duration > $remainingQuota) {
            return back()
                ->withInput()
                ->with('error', "Kuota cuti tidak cukup! Sisa kuota Anda: {$remainingQuota} hari, pengajuan: {$duration} hari.");
        }

        LeavePermit::create([
            'user_id'    => $user->id,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'reason'     => trim($request->reason),
            'status'     => 'Pending',
        ]);

        return redirect()->back()->with('success', "Pengajuan cuti {$duration} hari berhasil diajukan.");
    }

    public function edit($id){
         $leave = LeavePermit::where('user_id', Auth::id())
            ->where('status', 'Pending')
            ->findOrFail($id);

        return view('karyawan.pengajuanCuti.edit', compact('leave'));
    }

    public function update(Request $request, $id){
        $user  = Auth::user();
        $leave = LeavePermit::where('user_id', $user->id)
            ->where('status', 'Pending')
            ->findOrFail($id);

        $request->validate([
            'leave_type' => 'required',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|min:5',
        ]);

        $duration = Carbon::parse($request->start_date)
            ->diffInDays(Carbon::parse($request->end_date)) + 1;

        $usedQuota      = $this->getUsedQuota($user->id);
        $totalQuota     = $user->leave_quota ?? 12;
        $remainingQuota = $totalQuota - $usedQuota;

        if ($duration > $remainingQuota) {
            return back()
                ->withInput()
                ->with('error', "Kuota cuti tidak cukup! Sisa kuota: {$remainingQuota} hari.");
        }

        $leave->update([
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'reason'     => trim($request->reason),
        ]);

        return redirect()->route('karyawan.pengajuanCuti.index')
            ->with('success', 'Pengajuan cuti berhasil diperbarui.');
    }

     public function destroy($id)
    {
        $leave = LeavePermit::where('user_id', Auth::id())
            ->where('status', 'Pending')
            ->findOrFail($id);

        $leave->delete();

        return redirect()->back()->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }

    public function adminIndex()
{
    $allLeaves = LeavePermit::with('user')->orderBy('created_at', 'desc')->paginate(10);
    
     return view('pimpinan.pengajuanCuti.index', compact('allLeaves'));
}

public function updateStatus(Request $request, $id){
    $leave = LeavePermit::with('user')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:Approved,Rejected',
        ]);

        // Cek kuota saat approve
        if ($request->status === 'Approved') {
            $duration  = Carbon::parse($leave->start_date)
             ->diffInDays(Carbon::parse($leave->end_date)) + 1;
            $used      = $this->getUsedQuota($leave->user_id);
            $total     = $leave->user->leave_quota ?? 12;
            $remaining = $total - $used;

            if ($duration > $remaining) {
                return back()->with('error',
                    "Tidak bisa approve! Sisa kuota karyawan: {$remaining} hari, dibutuhkan: {$duration} hari."
                );
            }
        }

        $leave->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pengajuan cuti berhasil diperbarui.');
    
}

}
