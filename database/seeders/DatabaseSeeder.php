<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@booking.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // Create Regular User
        User::create([
            'name' => 'John Doe',
            'email' => 'user@booking.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'phone' => '081234567891',
        ]);

        // Create Sample Packages
        Package::create([
            'name' => 'Paket Weekend Getaway',
            'max_capacity' => 4,
            'min_capacity' => 2,
            'week_type' => 'weekends',
            'price' => 1500000,
            'price_type' => 'pack',
            'benefits' => [
                'Akomodasi 2 malam',
                'Sarapan untuk 4 orang',
                'Free Wi-Fi',
                'Akses kolam renang'
            ],
            'total_stays' => '2 hari 1 malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Paket Liburan Keluarga',
            'max_capacity' => 6,
            'min_capacity' => 4,
            'week_type' => 'weekdays',
            'price' => 800000,
            'price_type' => 'night',
            'benefits' => [
                'Villa untuk 6 orang',
                'Sarapan & makan malam',
                'BBQ area',
                'Kids playground'
            ],
            'total_stays' => '3 hari 2 malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Romantic Escape',
            'max_capacity' => 2,
            'min_capacity' => 2,
            'week_type' => 'weekends',
            'price' => 2500000,
            'price_type' => 'pack',
            'benefits' => [
                'Suite room dengan jacuzzi',
                'Candlelight dinner',
                'Couple spa treatment',
                'Welcome drink & fruit basket'
            ],
            'total_stays' => '2 hari 1 malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Budget Backpacker',
            'max_capacity' => 2,
            'min_capacity' => 1,
            'week_type' => 'weekdays',
            'price' => 350000,
            'price_type' => 'night',
            'benefits' => [
                'Kamar standard',
                'Sarapan',
                'Free Wi-Fi'
            ],
            'total_stays' => 'Fleksibel',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Corporate Team Building',
            'max_capacity' => 20,
            'min_capacity' => 10,
            'week_type' => 'weekdays',
            'price' => 5000000,
            'price_type' => 'pack',
            'benefits' => [
                'Meeting room dengan proyektor',
                'Team building activities',
                '3x meals untuk semua peserta',
                'Coffee break',
                'Akomodasi group'
            ],
            'total_stays' => '2 hari 1 malam',
            'is_published' => false,
        ]);
    }
}
