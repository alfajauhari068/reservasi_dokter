<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@klinik.test'],
            [
                'name' => 'Admin Klinik',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'dokter@klinik.test'],
            [
                'name' => 'Dr. Contoh',
                'password' => Hash::make('password'),
                'role' => 'dokter',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pasien@klinik.test'],
            [
                'name' => 'Pasien Contoh',
                'password' => Hash::make('password'),
                'role' => 'pasien',
            ]
        );
    }
}