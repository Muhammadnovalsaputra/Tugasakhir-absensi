<?php

namespace App\Services;

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
}