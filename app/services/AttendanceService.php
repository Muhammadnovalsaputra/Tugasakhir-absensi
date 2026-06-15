<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\User;
use Carbon\Carbon;

class AttendanceService
{
    public function __construct(
        protected LocationService            $locationService,
        protected PhotoService               $photoService,
        protected AttendanceCorrectionService $correctionService,
    ) {
    }

    // ─── Index & Schedule ─────────────────────────────────────────

    public function getIndexData(): array
    {
        return [
            'attendance' => $this->getTodayAttendance(auth()->id()),
            'setting'    => AttendanceSetting::getActive(),
        ];
    }

    public function getScheduleData(User $user): array
    {
        $setting = AttendanceSetting::getActive();

        $history = Attendance::where('user_id', $user->id)
            ->select('date', 'check_in', 'check_out', 'status')
            ->get()
            ->map(fn($item) => [
                'date'      => $item->date,
                'check_in'  => $item->check_in  ? Carbon::parse($item->check_in)->format('H:i')  : null,
                'check_out' => $item->check_out ? Carbon::parse($item->check_out)->format('H:i') : null,
                'status'    => $item->status,
            ]);

        return [
            'user'         => $user,
            'history'      => $history,
            'setting'      => $setting,
            'workSchedule' => array_values($setting?->work_schedule ?? []),
            'latitude'     => $setting?->latitude,   
            'longitude'    => $setting?->longitude,  
        ];
    }

    // ─── Check-in ─────────────────────────────────────────────────

    public function checkin(User $user, array $data): array
    {
        $now     = Carbon::now();
        $setting = AttendanceSetting::getActive();

        if (! $setting) {
            return $this->error('Pengaturan absensi belum dikonfigurasi oleh admin.');
        }

        if (! $this->isWorkDay($now, $setting)) {
            $jadwal = implode(', ', $setting->work_schedule ?? []);
            return $this->error("Hari ini ({$now->translatedFormat('l')}) bukan hari kerja. Jadwal: {$jadwal}");
        }

        $distance = $this->locationService->calculateDistance(
            $setting->latitude, $setting->longitude,
            $data['latitude_in'], $data['longitude_in']
        );

        if ($distance > $setting->radius) {
            return $this->error(
                'Anda di luar jangkauan! Jarak Anda: ' . round($distance) . 'm. Maksimal: ' . $setting->radius . 'm.'
            );
        }

        $entryTime    = Carbon::parse($setting->start_time);
        $earliestTime = $entryTime->copy()->subHour();

        if ($now->lt($earliestTime)) {
            return $this->error('Terlalu pagi! Absensi hanya bisa dilakukan mulai ' . $earliestTime->format('H:i') . '.');
        }

        if ($this->getTodayAttendance($user->id)) {
            return $this->error('Anda sudah melakukan absen masuk hari ini.');
        }

        $status   = $now->greaterThan($entryTime) ? 'Terlambat' : 'Hadir';
        $fileName = $this->photoService->store($data['image_in'], $user->id);

        Attendance::create([
            'user_id'      => $user->id,
            'date'         => Carbon::today(),
            'check_in'     => $now->toTimeString(),
            'image_in'     => $fileName,
            'latitude_in'  => $data['latitude_in'],
            'longitude_in' => $data['longitude_in'],
            'status'       => $status,
        ]);

        return $this->success("Berhasil absen masuk! Status: {$status}");
    }

    // ─── Check-out ────────────────────────────────────────────────

    public function checkout(User $user, array $data): array
    {
        $now        = Carbon::now();
        $setting    = AttendanceSetting::getActive();
        $attendance = $this->getTodayAttendance($user->id);

        //Validasi: apakah status attendance mengizinkan checkout?
        if (! $this->correctionService->canCheckout($user->id)) {
            
            if (! $attendance) {
                return $this->error('Anda belum melakukan absen masuk.');
            }
            if ($attendance->status === 'Setengah Hari') {
                return $this->error('Absen pulang tidak dapat dilakukan karena pengajuan koreksi Anda ditolak (status: Setengah Hari).');
            }
            if ($attendance->status === 'Alpa') {
                return $this->error('Anda tidak dapat absen pulang karena status kehadiran Alpa.');
            }
            if ($attendance->check_out) {
                return $this->error('Anda sudah melakukan absen keluar hari ini.');
            }
            return $this->error('Anda tidak dapat melakukan absen pulang saat ini.');
        }

        // Validasi jam pulang minimum
        if ($setting?->quit_time) {
            $minExitTime = Carbon::parse($setting->quit_time);
            if ($now->lessThan($minExitTime)) {
                return $this->error('Belum waktunya pulang. Jam pulang: ' . $minExitTime->format('H:i') . '.');
            }
        }

        $attendance->update([
            'check_out'     => $now->toTimeString(),
            'latitude_out'  => $data['latitude_out'],
            'longitude_out' => $data['longitude_out'],
        ]);

        return $this->success('Berhasil melakukan absen keluar!');
    }

    // ─── History ──────────────────────────────────────────────────

    public function getHistory(User $user, array $filters): array
    {
        $startDate = $filters['start_date'] ?? now()->startOfMonth()->format('Y-m-d');
        $endDate   = $filters['end_date']   ?? now()->endOfMonth()->format('Y-m-d');

        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        return [
            'attendances' => $attendances,
            'startDate'   => $startDate,
            'endDate'     => $endDate,
            'stats'       => $this->buildStats($attendances),
        ];
    }

    // ─── Leader ───────────────────────────────────────────────────

    public function getFilteredAttendances(array $filters)
    {
        return Attendance::with('user')
            ->when(! empty($filters['search']),
                fn($q) => $q->whereHas('user', fn($u) => $u->where('name', 'like', '%' . $filters['search'] . '%'))
            )
            ->when(! empty($filters['status']),     fn($q) => $q->where('status', $filters['status']))
            ->when(! empty($filters['start_date']), fn($q) => $q->whereDate('date', '>=', $filters['start_date']))
            ->when(! empty($filters['end_date']),   fn($q) => $q->whereDate('date', '<=', $filters['end_date']))
            ->orderBy('date', 'desc')
            ->paginate(10)
            ->withQueryString();
    }

    // ─── Private Helpers ──────────────────────────────────────────

    private function getTodayAttendance(int $userId): ?Attendance
    {
        return Attendance::where('user_id', $userId)
            ->whereDate('date', Carbon::today())
            ->first();
    }

    private function isWorkDay(Carbon $date, AttendanceSetting $setting): bool
    {
        return in_array($date->translatedFormat('l'), $setting->work_schedule ?? []);
    }

    private function buildStats($attendances): array
    {
        return [
            'hadir'       => $attendances->where('status', 'Hadir')->count(),
            'terlambat'   => $attendances->where('status', 'Terlambat')->count(),
            'koreksi'     => $attendances->where('status', 'Hadir (Koreksi)')->count(),
            'setengah'    => $attendances->where('status', 'Setengah Hari')->count(),
            'cuti'        => $attendances->whereIn('status', ['Cuti', 'Izin'])->count(),
            'alpa'        => $attendances->where('status', 'Alpa')->count(),
        ];
    }

    private function success(string $message): array
    {
        return ['status' => 'success', 'message' => $message];
    }

    private function error(string $message): array
    {
        return ['status' => 'error', 'message' => $message];
    }
}