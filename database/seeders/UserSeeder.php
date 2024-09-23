<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate([
            'email' => 'user1@gmail.com',
        ], [
            'name' => 'User',
            'password' => Hash::make('userpass123'),
            'role' => 'user',
        ]);
    }
}
