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

    public function getAllLeaveRequests(?string $search = null)
    {
        return LeavePermit::with('user')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })
                ->orWhere('leave_type', 'like', "%{$search}%")
                ->orWhere('reason', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString();
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

            $wasApproved = $leave->isApproved();

            $leave->update(['status' => $status]);

            if ($status === 'Approved') {
                $this->syncAttendanceForLeave($leave);
            } elseif ($wasApproved) {
                $this->removeAutoLeaveAttendance($leave);
            }
        });
    }

    private function syncAttendanceForLeave(LeavePermit $leave): void
    {
        $attendanceStatus = $leave->leave_type === 'IzinMendesak' ? 'Izin' : 'Cuti';

        $period = \Carbon\CarbonPeriod::create($leave->start_date, $leave->end_date);

        foreach ($period as $date) {
            $existing = \App\Models\Attendance::where('user_id', $leave->user_id)
                ->whereDate('date', $date->toDateString())
                ->exists();

            
            if ($existing) {
                continue;
            }

            \App\Models\Attendance::create([
                'user_id' => $leave->user_id,
                'date'    => $date->toDateString(),
                'status'  => $attendanceStatus,
            ]);
        }
    }

    private function removeAutoLeaveAttendance(LeavePermit $leave): void
    {
        \App\Models\Attendance::where('user_id', $leave->user_id)
            ->whereBetween('date', [$leave->start_date, $leave->end_date])
            ->whereIn('status', ['Cuti', 'Izin'])
            ->whereNull('check_in') 
            ->delete();
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