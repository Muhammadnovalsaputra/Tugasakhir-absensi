<?php

namespace App\Repositories;

use App\Models\LeavePermit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class LeavePermitRepository implements LeavePermitRepositoryInterface
{
    public function create(array $data)
    {
        return LeavePermit::create($data);
    }

    public function update(int $id, array $data)
    {
        $leave = LeavePermit::findOrFail($id);
        $leave->update($data);
        
        return $leave;
    }

    public function delete(int $id)
    {
        return LeavePermit::destroy($id);
    }

    public function find(int $id)
    {
        return LeavePermit::find($id);
    }

    public function findForUpdate(int $id)
    {
        return LeavePermit::with('user')
            ->lockForUpdate()
            ->find($id);
    }

    public function findUserPendingLeave(int $leaveId, int $userId)
    {
        return LeavePermit::where('user_id', $userId)
            ->where('status', 'Pending')
            ->find($leaveId);
    }

    public function getUserLeaveHistory(int $userId): Collection
    {
        return LeavePermit::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUsedQuota(int $userId): int
    {
        return LeavePermit::where('user_id', $userId)
            ->where('status', 'Approved')
            ->selectRaw('SUM(DATEDIFF(end_date, start_date) + 1) as total_days')
            ->value('total_days') ?? 0;
    }

    public function getAllWithUser(): LengthAwarePaginator
    {
        return LeavePermit::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getStatusCounts(): array
    {
        return LeavePermit::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
}