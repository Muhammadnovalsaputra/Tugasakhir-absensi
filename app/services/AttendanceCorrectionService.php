<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceCorrection;
use App\Models\AttendanceSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class AttendanceCorrectionService
{
    const ALPA_THRESHOLD_MINUTES = 120;
    public function getTodayState(int $userId): string
    {
        $today      = Carbon::today();
        $attendance = $this->getAttendanceByDate($userId, $today);
        $correction = AttendanceCorrection::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        if ($correction) {
            if ($correction->isApproved()) {
                return $attendance?->check_out ? 'done' : 'can_checkout_after_correction';
            }
            if ($correction->isRejected()) {
                return 'correction_rejected';
            }
            return 'correction_pending';
        }

        if ($attendance) {
            if ($attendance->status === 'Alpa')  return 'show_correction';
            if ($attendance->check_out)          return 'done';
            return 'already_checkin';
        }

        return $this->isAlpaTime() ? 'show_correction' : 'can_checkin';
    }

    public function isAlpaTime(): bool
    {
        $setting = AttendanceSetting::getActive();
        if (! $setting?->start_time) return false;

        return Carbon::now()->greaterThan(
            Carbon::parse($setting->start_time)->addMinutes(self::ALPA_THRESHOLD_MINUTES)
        );
    }

    

    public function markAbsentAsAlpa(): int
    {
        $setting = AttendanceSetting::getActive();
        if (! $setting) return 0;

        $today    = Carbon::today();
        $alpaTime = Carbon::parse($setting->start_time)->addMinutes(self::ALPA_THRESHOLD_MINUTES);

        if (Carbon::now()->lessThan($alpaTime)) return 0;

        $usersWithAttendance = Attendance::whereDate('date', $today)->pluck('user_id');
        $absentUsers = User::where('role', '!=', 'pimpinan')
            ->whereNotIn('id', $usersWithAttendance)->get();

        $count = 0;
        foreach ($absentUsers as $user) {
            Attendance::create(['user_id' => $user->id, 'date' => $today, 'check_in' => null, 'status' => 'Alpa']);
            $count++;
        }
        return $count;
    }

    

    public function submitCorrection(User $user, array $data, UploadedFile $photo): array
        {
            $today = Carbon::today();

            if (AttendanceCorrection::where('user_id', $user->id)->whereDate('date', $today)->exists()) {
                return $this->error('Anda sudah mengajukan koreksi untuk hari ini.');
            }
            if (! $this->isAlpaTime()) {
                return $this->error('Belum melewati batas waktu pengajuan koreksi.');
            }

            $attendance = $this->getAttendanceByDate($user->id, $today);
            $photoPath  = $photo->store('koreksi/' . now()->format('Y-m'), 'public');

            
            $reasonCategory = $data['reason_category'] ?? null;
            $reasonDetail   = $data['reason'] ?? null;

            $finalReason = $reasonCategory;
            if ($reasonCategory === 'Lainnya' && $reasonDetail) {
                $finalReason = 'Lainnya: ' . $reasonDetail;
            }

            AttendanceCorrection::create([
                'user_id'          => $user->id,
                'attendance_id'    => $attendance?->id,
                'date'             => $today,
                'claimed_check_in' => $data['claimed_check_in'],
                'proof_photo'      => $photoPath,
                'reason'           => $finalReason, 
                'status'           => AttendanceCorrection::STATUS_PENDING,
            ]);

            return $this->success('Pengajuan koreksi berhasil dikirim. Menunggu persetujuan Admin.');
        }


    public function approve(AttendanceCorrection $correction, User $reviewer, ?string $note = null): array
    {
        if (! $correction->isPending()) {
            return $this->error('Pengajuan ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($correction, $reviewer, $note) {
            $attendance = $this->resolveAttendance($correction);

            $attendance->update([
                'check_in'  => $correction->claimed_check_in,
                'check_out' => null,
                'status'    => 'Hadir (Koreksi)',
            ]);

            $correction->update([
                'status'        => AttendanceCorrection::STATUS_APPROVED,
                'reviewed_by'   => $reviewer->id,
                'reviewed_at'   => now(),
                'reviewer_note' => $note,
                'attendance_id' => $attendance->id,
            ]);
        });

        return $this->success('Koreksi disetujui. Karyawan dapat melakukan absen pulang.');
    }

    public function reject(AttendanceCorrection $correction, User $reviewer, ?string $note = null): array
    {
        if (! $correction->isPending()) {
            return $this->error('Pengajuan ini sudah diproses sebelumnya.');
        }

        DB::transaction(function () use ($correction, $reviewer, $note) {
            $attendance = $this->resolveAttendance($correction);

            $attendance->update([
                'status' => 'Setengah Hari',
            ]);

            $correction->update([
                'status'        => AttendanceCorrection::STATUS_REJECTED,
                'reviewed_by'   => $reviewer->id,
                'reviewed_at'   => now(),
                'reviewer_note' => $note,
            ]);
        });

        return $this->success('Pengajuan ditolak. Status kehadiran diubah menjadi "Setengah Hari".');
    }


    public function canCheckout(int $userId): bool
    {
        $attendance = $this->getAttendanceByDate($userId, Carbon::today());

        if (! $attendance || $attendance->check_out) return false;

        return in_array($attendance->status, [
            'Hadir',
            'Terlambat',
            'Hadir (Koreksi)',
        ]);
    }


    private function resolveAttendance(AttendanceCorrection $correction): Attendance
    {
        return $correction->attendance
            ?? Attendance::firstOrCreate(
                ['user_id' => $correction->user_id, 'date' => $correction->date],
                ['check_in' => null, 'status' => 'Alpa']
            );
    }

    private function getAttendanceByDate(int $userId, $date): ?Attendance
    {
        return Attendance::where('user_id', $userId)->whereDate('date', $date)->first();
    }

    private function success(string $message): array { return ['status' => 'success', 'message' => $message]; }
    private function error(string $message): array   { return ['status' => 'error',   'message' => $message]; }
}