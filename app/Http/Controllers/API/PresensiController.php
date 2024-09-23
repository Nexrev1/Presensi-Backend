<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validator;

date_default_timezone_set("Asia/Jakarta");

class PresensiController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => null
            ], 400);
        }

        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Verifikasi lokasi
        $locationStatus = $this->verifyLocation($request->latitude, $request->longitude);

        // Log data untuk debugging
        \Log::info('Latitude: ' . $request->latitude . ', Longitude: ' . $request->longitude);
        \Log::info('Location Status: ' . $locationStatus);

        if ($locationStatus === 'Unknown') {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak dikenali. Anda tidak dapat melakukan presensi di luar radius kampus.',
                'requiresLocationPage' => true,
                'data' => null
            ], 400);
        }

        // Cek presensi hari ini untuk user
        $presensi = Presensi::where('user_id', $user->id)
                            ->whereDate('tanggal', $today)
                            ->first();

        if ($presensi === null) {
            // Jika belum ada presensi, buat presensi masuk baru
            $presensi = Presensi::create([
                'user_id' => $user->id,
                'tanggal' => $today,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'masuk' => now()->format('H:i:s'),
                'location' => $locationStatus,
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Sukses absen untuk masuk',
                'data' => $presensi
            ]);
        } else {
            if ($presensi->pulang === null) {
                // Jika sudah ada presensi masuk tapi belum ada presensi pulang
                $presensi->update([
                    'pulang' => now()->format('H:i:s'),
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'location' => $locationStatus,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Sukses absen untuk pulang',
                    'data' => $presensi
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melakukan presensi pulang hari ini',
                    'data' => null
                ], 400);
            }
        }
    }

    public function getPresensis()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        // Dapatkan semua presensi user
        $presensis = Presensi::where('user_id', $user->id)->get();

        // Format data untuk response
        $data = $presensis->map(function($item) use ($today) {
            $item->is_hari_ini = $item->tanggal == $today;

            $tanggal = Carbon::parse($item->tanggal)->locale('id');
            $item->tanggal = $tanggal->isoFormat('dddd, D MMMM YYYY');

            $item->masuk = $item->masuk ? Carbon::parse($item->masuk)->format('H:i') : '-';
            $item->pulang = $item->pulang ? Carbon::parse($item->pulang)->format('H:i') : '-';

            return $item;
        });

        return response()->json([
            'success' => true,
            'message' => 'Data presensi berhasil diambil',
            'data' => $data
        ]);
    }

    private function verifyLocation($latitude, $longitude)
    {
        $thehokLatitude = -1.6185483;
        $thehokLongitude = 103.6255088;
        $kobarLatitude = -1.6416138;
        $kobarLongitude = 103.614928;
        $radius = 0.5; // Radius dalam kilometer

        // Mengecek lokasi
        if ($this->isWithinRadius($latitude, $longitude, $thehokLatitude, $thehokLongitude, $radius)) {
            return 'Kampus Thehok';
        } elseif ($this->isWithinRadius($latitude, $longitude, $kobarLatitude, $kobarLongitude, $radius)) {
            return 'Kampus Kobar';
        } else {
            return 'Unknown'; // Menampilkan lokasi yang tidak diketahui jika di luar radius
        }
    }

    private function isWithinRadius($lat1, $lon1, $lat2, $lon2, $radius)
    {
        $earthRadius = 6371; // Radius bumi dalam kilometer

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDiff / 2) * sin($lonDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance <= $radius;
    }
}
