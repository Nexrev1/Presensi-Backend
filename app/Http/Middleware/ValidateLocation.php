<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateLocation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        if (!$this->isValidLocation($latitude, $longitude)) {
            return response()->json(['error' => 'Invalid location'], 400);
        }

        return $next($request);
    }

    /**
     * Cek apakah lokasi valid.
     *
     * @param  float|null  $latitude
     * @param  float|null  $longitude
     * @return bool
     */
    private function isValidLocation($latitude, $longitude)
    {
        // Validasi input
        if (!is_numeric($latitude) || !is_numeric($longitude)) {
            return false;
        }

        // Lokasi-lokasi yang diizinkan
        $locations = [
            ['lat' => -1.6416138, 'lon' => 103.614928], // Kampus Thehok
            ['lat' => -1.6185483, 'lon' => 103.6255088], // Kampus Kobar
            // Tambahkan lokasi lain di sini jika perlu
        ];

        $radius = 1; // Radius deteksi sekitar 1 km

        foreach ($locations as $location) {
            if ($this->calculateDistance($latitude, $longitude, $location['lat'], $location['lon']) < $radius) {
                return true;
            }
        }

        return false;
    }

    /**
     * Menghitung jarak antara dua koordinat.
     *
     * @param  float  $lat1
     * @param  float  $lon1
     * @param  float  $lat2
     * @param  float  $lon2
     * @return float
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius bumi dalam km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
