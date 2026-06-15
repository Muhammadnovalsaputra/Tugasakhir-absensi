<?php

namespace App\Services;

use App\Models\AttendanceSetting;

class AttendanceSettingService
{
    public function getActiveSetting(): AttendanceSetting
    {
        return AttendanceSetting::getActive() ?? new AttendanceSetting();
    }

    public function getSettingById(int $id): AttendanceSetting
    {
        return AttendanceSetting::findOrFail($id);
    }

    public function updateOrCreateSetting(array $data): AttendanceSetting
    {
        return AttendanceSetting::updateOrCreate(
            ['id' => 1],
            $data
        );
    }
}