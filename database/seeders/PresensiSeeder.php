<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PresensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('presensis')->insert([
            [
                'user_id' => 1,
                'latitude' => -1.6185483, // Sesuaikan dengan nilai latitude
                'longitude' => 103.6255088, // Sesuaikan dengan nilai longitude
                'tanggal' => Carbon::today()->toDateString(), // Atau tanggal yang sesuai
                'masuk' => '08:00:00',
                'pulang' => '17:00:00',
                'ip_address' => '192.168.1.1',
                'user_agent' => 'Mozilla/5.0',
                'location' => 'Location Example',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Anda dapat menambahkan lebih banyak data di sini jika diperlukan
        ]);
    }
}
