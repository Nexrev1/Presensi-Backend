<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Config;

class ConfigSeeder extends Seeder
{
    public function run()
    {
        Config::create([
            'jam_masuk' => '08:00:00',
            'jam_keluar' => '17:00:00',
            'radius' => 500, // radius dalam meter
            'latitude' => -1.6185483, // Kampus Thehok
            'longitude' => 103.6255088, // Kampus Thehok
        ]);

        Config::create([
            'jam_masuk' => '08:00:00',
            'jam_keluar' => '17:00:00',
            'radius' => 500, // radius dalam meter
            'latitude' => -1.6416138, // Kampus Kobar
            'longitude' => 103.6123531, // Kampus Kobar
        ]);
    }
}
