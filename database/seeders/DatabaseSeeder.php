<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $users = [
            ['name' => 'John Doe', 'phone' => '12345678', 'phone_verified_at' => now(), 'password' => Hash::make('admin123'), 'role' => 'admin'],
            ['name' => 'Jane Smith', 'phone' => '123456789', 'phone_verified_at' => now(), 'password' => Hash::make('petrol123'),'role' => 'petrol'],
            ['name' => 'Michael Johnson', 'phone' => '1234567890', 'phone_verified_at' => now(), 'password' => Hash::make('hotel123'),'role' => 'hotel'],
        ];

        DB::table('users')->insert($users);


    }
}
