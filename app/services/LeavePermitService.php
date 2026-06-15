<?php

namespace App\Services;

use App\Models\LeavePermit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeavePermitService
{
    public function getUserLeaveDashboard(User $user): array
    {
        $leaveHistory = LeavePermit::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalQuota = $user->leave_quota ?? 12;
        $remainingQuota = $this->getRemainingQuota($user);
        $usedQuota = max(0, $totalQuota - $remainingQuota);

        return compact('leaveHistory', 'usedQuota', 'totalQuota', 'remainingQuota');
    }

    public function storeLeaveRequest(User $user, array $data): int
    {
        $duration = $this->calculateDuration($data['start_date'], $data['end_date']);
        $remainingQuota = $this->getRemainingQuota($user);

        if ($duration > $remainingQuota) {
            throw new \Exception(
                "Kuota cuti tidak cukup! Sisa kuota Anda: {$remainingQuota} hari, pengajuan: {$duration} hari."
            );
        }

        $leaveData = [
            'user_id' => $user->id,
            'leave_type' => $data['leave_type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'],
            'status' => 'Pending',
        ];

        LeavePermit::create($leaveData);

        return $duration;
    }

    public function getPendingLeaveRequest(int $leaveId, int $userId): LeavePermit
    {
        $leave = LeavePermit::where('user_id', $userId)
            ->where('status', 'Pending')
            ->find($leaveId);
        
        if (!$leave) {
            throw new \Exception('Pengajuan cuti tidak ditemukan atau sudah diproses.');
        }
        
        return $leave;
    }

    public function updateLeaveRequest(User $user, int $leaveId, array $data): void
    {
        $leave = $this->getPendingLeaveRequest($leaveId, $user->id);
        
        $duration = $this->calculateDuration($data['start_date'], $data['end_date']);
        $remainingQuota = $this->getRemainingQuota($user);

        if ($duration > $remainingQuota) {
            throw new \Exception(
                "Kuota cuti tidak cukup! Sisa kuota: {$remainingQuota} hari."
            );
        }

        $leave->update([
            'leave_type' => $data['leave_type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'],
        ]);
    }

    public function cancelLeaveRequest(int $leaveId, int $userId): void
    {
        $leave = $this->getPendingLeaveRequest($leaveId, $userId);
        $leave->delete();
    }

    public function getAllLeaveRequests()
    {
        return LeavePermit::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getLeaveStatistics(): array
    {
        $rawStats = LeavePermit::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        return [
            'pending' => $rawStats['Pending'] ?? 0,
            'approved' => $rawStats['Approved'] ?? 0,
            'rejected' => $rawStats['Rejected'] ?? 0,
        ];
    }

    public function updateLeaveStatus(int $leaveId, string $status): void
    {
        DB::transaction(function () use ($leaveId, $status) {
            $leave = LeavePermit::with('user')
                ->lockForUpdate()
                ->find($leaveId);
            
            if (!$leave) {
                throw new \Exception('Pengajuan cuti tidak ditemukan.');
            }
            
            if ($leave->status === $status) {
                throw new \Exception('Status pengajuan cuti sudah ' . $status);
            }

            if ($status === 'Approved') {
                $this->validateQuotaForApproval($leave);
            }

            $leave->update(['status' => $status]);
        });
    }

    private function validateQuotaForApproval(LeavePermit $leave): void
    {
        $duration = $this->calculateDuration($leave->start_date, $leave->end_date);
        $remainingQuota = $this->getRemainingQuota($leave->user);

        if ($duration > $remainingQuota) {
            throw new \Exception(
                "Tidak bisa approve! Sisa kuota karyawan: {$remainingQuota} hari, dibutuhkan: {$duration} hari."
            );
        }
    }

    private function calculateDuration(string $startDate, string $endDate): int
    {
        return Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
    }

    private function getRemainingQuota(User $user): int
    {
        $usedQuota = LeavePermit::where('user_id', $user->id)
            ->where('status', 'Approved')
            ->selectRaw('SUM(DATEDIFF(end_date, start_date) + 1) as total_days')
            ->value('total_days') ?? 0;
            
        $totalQuota = $user->leave_quota ?? 12;

        return max(0, $totalQuota - $usedQuota);
    }
}