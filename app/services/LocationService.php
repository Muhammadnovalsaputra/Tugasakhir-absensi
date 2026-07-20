<?php

namespace App\Services;

use App\Models\AttendanceLocation;

class LocationService
{
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $theta = $lon1 - $lon2;

        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2))
              + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));

        return rad2deg(acos($dist)) * 60 * 1.1515 * 1609.344;
    }

    public function isWithinRadius(float $lat1, float $lon1, float $lat2, float $lon2, float $radiusMeters): bool
    {
        return $this->calculateDistance($lat1, $lon1, $lat2, $lon2) <= $radiusMeters;
    }

    /**
     * Cari lokasi kantor terdekat yang jaraknya masih dalam radius.
     * Return null kalau user di luar semua radius.
     */
    public function findMatchingLocation(float $userLat, float $userLon): ?array
    {
        $locations = AttendanceLocation::active()->get();
        $closest    = null;
        $minDistance = null;

        foreach ($locations as $location) {
            $distance = $this->calculateDistance(
                $location->latitude, $location->longitude, $userLat, $userLon
            );

            if ($distance <= $location->radius) {
                if ($minDistance === null || $distance < $minDistance) {
                    $minDistance = $distance;
                    $closest = ['location' => $location, 'distance' => $distance];
                }
            }
        }

        return $closest;
    }

    /**
     * Jarak minimum ke lokasi manapun (untuk pesan error, biar user tahu
     * seberapa jauh dia dari titik terdekat).
     */
    public function distanceToNearest(float $userLat, float $userLon): ?float
    {
        $locations = AttendanceLocation::active()->get();
        if ($locations->isEmpty()) return null;

        return $locations->min(fn($loc) => $this->calculateDistance(
            $loc->latitude, $loc->longitude, $userLat, $userLon
        ));
    }
}