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
            'name' => 'Camp Tenda Standar',
            'package_type' => 'camp',
            'max_capacity' => 4,
            'min_capacity' => 1,
            'week_type' => 'weekends',
            'price' => 750000,
            'price_type' => 'night',
            'benefits' => [
                'Tenda standar untuk 4 orang',
                'Matras dan sleeping bag',
                'Api unggun malam hari',
                'Sarapan pagi'
            ],
            'total_stays' => '1 malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Camp Tenda Standar',
            'package_type' => 'camp',
            'max_capacity' => 4,
            'min_capacity' => 1,
            'week_type' => 'weekends',
            'price' => 800000,
            'price_type' => 'night',
            'benefits' => [
                'Tenda standar untuk 4 orang',
                'Matras dan sleeping bag',
                'Api unggun malam hari',
                'Sarapan pagi'
            ],
            'total_stays' => '1 malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Camp Tenda VIP',
            'package_type' => 'camp',
            'max_capacity' => 4,
            'min_capacity' => 1,
            'week_type' => 'weekdays',
            'price' => 850000,
            'price_type' => 'night',
            'benefits' => [
                'Tenda VIP untuk 4 orang',
                'Matras, sleeping bag, dan bantal',
                'Api unggun malam hari',
                'Sarapan pagi dan makan malam'
            ],
            'total_stays' => '1 malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Camp Tenda VIP',
            'package_type' => 'camp',
            'max_capacity' => 4,
            'min_capacity' => 1,
            'week_type' => 'weekends',
            'price' => 950000,
            'price_type' => 'night',
            'benefits' => [
                'Tenda VIP untuk 4 orang',
                'Matras, sleeping bag, dan bantal',
                'Api unggun malam hari',
                'Sarapan pagi dan makan malam'
            ],
            'total_stays' => '1 malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Cabin Japanese',
            'package_type' => 'camp',
            'max_capacity' => 10,
            'min_capacity' => 1,
            'week_type' => 'weekdays',
            'price' => 3800000,
            'price_type' => 'night',
            'benefits' => [
                'Cabin bergaya Jepang untuk 10 orang',
                'Futon dan bantal tradisional Jepang',
                'Akses ke onsen alami',
                'Sarapan pagi dan makan malam ala Jepang'
            ],
            'total_stays' => '1 malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Cabin Japanese',
            'package_type' => 'camp',
            'max_capacity' => 10,
            'min_capacity' => 1,
            'week_type' => 'weekends',
            'price' => 4000000,
            'price_type' => 'night',
            'benefits' => [
                'Cabin bergaya Jepang untuk 10 orang',
                'Futon dan bantal tradisional Jepang',
                'Akses ke onsen alami',
                'Sarapan pagi dan makan malam ala Jepang'
            ],
            'total_stays' => '1 malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Cabin',
            'package_type' => 'camp',
            'max_capacity' => 6,
            'min_capacity' => 1,
            'week_type' => 'weekdays',
            'price' => 2200000,
            'price_type' => 'night',
            'benefits' => [
                'Cabin untuk 6 orang',
                'Futon dan bantal',
                'Akses ke onsen alami',
                'Sarapan pagi dan makan malam'
            ],
            'total_stays' => '1 malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Cabin',
            'package_type' => 'camp',
            'max_capacity' => 6,
            'min_capacity' => 1,
            'week_type' => 'weekends',
            'price' => 2500000,
            'price_type' => 'night',
            'benefits' => [
                'Cabin untuk 6 orang',
                'Futon dan bantal',
                'Akses ke onsen alami',
                'Sarapan pagi dan makan malam'
            ],
            'total_stays' => '1 malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Paket Camp A',
            'package_type' => 'camp',
            'max_capacity' => 1,
            'min_capacity' => 12,
            'week_type' => 'weekends',
            'price' => 420000,
            'price_type' => 'pack',
            'benefits' => [
                'camping',
                'rafting 4,8km (90 menit)',
                'makan 2x (makan siang dan makan malam)',
                'api unggun',
                'jagung bakar',
            ],
            'total_stays' => '2 Hari 1 Malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Paket Camp B',
            'package_type' => 'camp',
            'max_capacity' => 1,
            'min_capacity' => 12,
            'week_type' => 'weekends',
            'price' => 520000,
            'price_type' => 'pack',
            'benefits' => [
                'camping',
                'rafting 4,8km (90 menit)',
                'makan 2x (makan siang dan makan malam)',
                'api unggun',
                'jagung bakar',
                'paintball war game',
            ],
            'total_stays' => '2 Hari 1 Malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Paket Camp C',
            'package_type' => 'camp',
            'max_capacity' => 1,
            'min_capacity' => 12,
            'week_type' => 'weekends',
            'price' => 600000,
            'price_type' => 'pack',
            'benefits' => [
                'camping',
                'rafting 4,8km (90 menit)',
                'makan 2x (makan siang dan makan malam)',
                'api unggun',
                'jagung bakar',
                'paintball war game',
                'flying fox 300m lintas Danau',
                'fun game',
            ],
            'total_stays' => '2 Hari 1 Malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Paket Camp D',
            'package_type' => 'camp',
            'max_capacity' => 1,
            'min_capacity' => 24,
            'week_type' => 'weekends',
            'price' => 700000,
            'price_type' => 'pack',
            'benefits' => [
                'camping',
                'rafting 4,8km (90 menit)',
                'makan 2x (makan siang dan makan malam)',
                'api unggun',
                'jagung bakar',
                'paintball war game',
                'flying fox 300m lintas Danau',
                'fun game',
                'kambing guling',
            ],
            'total_stays' => '2 Hari 1 Malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Paket Camp E',
            'package_type' => 'camp',
            'max_capacity' => 1,
            'min_capacity' => 24,
            'week_type' => 'weekends',
            'price' => 1100000,
            'price_type' => 'pack',
            'benefits' => [
                'camping',
                'rafting 4,8km (90 menit)',
                'makan 2x (makan siang dan makan malam)',
                'api unggun',
                'jagung bakar',
                'paintball war game',
                'flying fox 300m lintas Danau',
                'fun game',
                'kambing guling',
                'fun offroad (land rover)',
                'live music',
            ],
            'total_stays' => '2 Hari 1 Malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Paket Camp A',
            'package_type' => 'camp',
            'max_capacity' => 1,
            'min_capacity' => 12,
            'week_type' => 'weekdays',
            'price' => 395000,
            'price_type' => 'pack',
            'benefits' => [
                'camping',
                'rafting 4,8km (90 menit)',
                'makan 2x (makan siang dan makan malam)',
                'api unggun',
                'jagung bakar',
            ],
            'total_stays' => '2 Hari 1 Malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Paket Camp B',
            'package_type' => 'camp',
            'max_capacity' => 1,
            'min_capacity' => 12,
            'week_type' => 'weekdays',
            'price' => 485000,
            'price_type' => 'pack',
            'benefits' => [
                'camping',
                'rafting 4,8km (90 menit)',
                'makan 2x (makan siang dan makan malam)',
                'api unggun',
                'jagung bakar',
                'paintball war game',
            ],
            'total_stays' => '2 Hari 1 Malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Paket Camp C',
            'package_type' => 'camp',
            'max_capacity' => 1,
            'min_capacity' => 12,
            'week_type' => 'weekdays',
            'price' => 575000,
            'price_type' => 'pack',
            'benefits' => [
                'camping',
                'rafting 4,8km (90 menit)',
                'makan 2x (makan siang dan makan malam)',
                'api unggun',
                'jagung bakar',
                'paintball war game',
                'flying fox 300m lintas Danau',
                'fun game',
            ],
            'total_stays' => '2 Hari 1 Malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Paket Camp D',
            'package_type' => 'camp',
            'max_capacity' => 1,
            'min_capacity' => 24,
            'week_type' => 'weekdays',
            'price' => 675000,
            'price_type' => 'pack',
            'benefits' => [
                'camping',
                'rafting 4,8km (90 menit)',
                'makan 2x (makan siang dan makan malam)',
                'api unggun',
                'jagung bakar',
                'paintball war game',
                'flying fox 300m lintas Danau',
                'fun game',
                'kambing guling',
            ],
            'total_stays' => '2 Hari 1 Malam',
            'is_published' => true,
            'photo' => null,
        ]);

        Package::create([
            'name' => 'Paket Camp E',
            'package_type' => 'camp',
            'max_capacity' => 1,
            'min_capacity' => 24,
            'week_type' => 'weekdays',
            'price' => 995000,
            'price_type' => 'pack',
            'benefits' => [
                'camping',
                'rafting 4,8km (90 menit)',
                'makan 2x (makan siang dan makan malam)',
                'api unggun',
                'jagung bakar',
                'paintball war game',
                'flying fox 300m lintas Danau',
                'fun game',
                'kambing guling',
                'fun offroad (land rover)',
                'live music',
            ],
            'total_stays' => '2 Hari 1 Malam',
            'is_published' => true,
            'photo' => null,
        ]);
    }
}
