<?php

namespace App\Services;

use App\Models\AttendanceSetting;
use App\Models\AttendanceLocation;

class AttendanceSettingService
{
    public function getActiveSetting(): AttendanceSetting
    {
        return AttendanceSetting::getActive() ?? new AttendanceSetting();
    }

    public function updateOrCreateSetting(array $data): AttendanceSetting
    {
        return AttendanceSetting::updateOrCreate(['id' => 1], $data);
    }

    public function getAllLocations()
    {
        return AttendanceLocation::orderBy('name')->get();
    }

    public function createLocation(array $data): AttendanceLocation
    {
        return AttendanceLocation::create($data);
    }

    public function updateLocation(AttendanceLocation $location, array $data): AttendanceLocation
    {
        $location->update($data);
        return $location;
    }

    public function deleteLocation(AttendanceLocation $location): void
    {
        $location->delete();
    }
}