<?php

namespace App\Helpers;

class LocationHelper
{
    /**
     * Hitung jarak antara dua koordinat dalam kilometer.
     *
     * @param float $lat1 Latitude pertama
     * @param float $lon1 Longitude pertama
     * @param float $lat2 Latitude kedua
     * @param float $lon2 Longitude kedua
     * @return float
     */
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius Bumi dalam kilometer

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
