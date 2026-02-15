<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeavePermit; 
use Illuminate\Support\Facades\Auth;

class LeavePermitController extends Controller
{
    public function index()
    {
        $leaveHistory = LeavePermit::where('user_id', Auth::id())
        ->orderBy('created_at','desc')
        ->get();

        $stats = [
            'hadir'=> 1, 
            'terlambat'=>0,
            'cuti'=>$leaveHistory->where('status','Approved')->count(),
            'alpa'=>0
        ];
         
        return view('karyawan.pengajuanCuti.index', compact('leaveHistory','stats'));
    }

    public function store(Request $request){
        $request->validate([
            'leave_type'=>'required',
            'start_date'=>'required|date',
            'end_date'=>'required|date|after_or_equal:start_date',
            'reason'=>'required|string|min:5',
        ]);
        LeavePermit::create([
            'user_id'=>Auth::id(),
            'leave_type'=>$request->leave_type,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'reason'=>trim($request->reason),
            'status'=>'Pending',
        ]);
        return redirect()->back()->with('success','Pengajuan cuti berhasil diajukan.');
    }

    public function edit($id){
        $leave = LeavePermit::where('user_id', Auth::id())
        ->where('status','Pending')
        ->findOrFail($id);


        return view('karyawan.pengajuanCuti.edit', compact('leave'));
    }

    public function update(Request $request, $id){
        $leave = LeavePermit::where('user_id', Auth::id())
        ->where('status','Pending')
        ->findOrFail($id);

        $request->validate([
            'leave_type'=>'required',
            'start_date'=>'required|date',
            'end_date'=>'required|date|after_or_equal:start_date',
            'reason'=>'required|string|min:5',
        ]);

        $leave->update([
            'leave_type'=>$request->leave_type,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'reason'=>trim($request->reason),
        ]);

        return redirect()->route('karyawan.pengajuanCuti.index')->with('success','Pengajuan cuti berhasil diperbarui.');
    }

    public function destroy($id){
        $leave = LeavePermit::where('user_id',Auth::id())
        ->where('status','Pending')
        ->findOrFail($id);

        $leave->delete();

        return redirect()->back()->with('success','Pengajuan cuti berhasil dibatalkan.');
    }

    public function adminIndex()
{
    $allLeaves = LeavePermit::with('user')->orderBy('created_at', 'desc')->get();
    
    $stats = [
        'pending'  => $allLeaves->where('status', 'Pending')->count(),
        'approved' => $allLeaves->where('status', 'Approved')->count(),
        'rejected' => $allLeaves->where('status', 'Rejected')->count(),
    ];

    return view('pimpinan.pengajuanCuti.index', compact('allLeaves', 'stats'));
}

public function updateStatus(Request $request, $id){
    $leave = LeavePermit::findOrFail($id);

    $request->validate([
        'status'=>'required|in:Approved,Rejected',
    ]);

    $leave->update([
        'status'=>$request->status,
    ]);

    return redirect()->back()->with('success','Status pengajuan cuti berhasil diperbarui.');
}

}
