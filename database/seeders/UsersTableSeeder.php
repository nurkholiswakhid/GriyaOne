<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 test users with varied roles
        $users = [
            // 8 Regular Users
            ['name' => 'User Test', 'email' => 'user@test.com', 'role' => 'user'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@test.com', 'role' => 'user'],
            ['name' => 'Ahmad Wijaya', 'email' => 'ahmad.wijaya@test.com', 'role' => 'user'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@test.com', 'role' => 'user'],
            ['name' => 'Rinto Harahap', 'email' => 'rinto.harahap@test.com', 'role' => 'user'],
            ['name' => 'Maya Kusuma', 'email' => 'maya.kusuma@test.com', 'role' => 'user'],
            ['name' => 'Eko Subagyo', 'email' => 'eko.subagyo@test.com', 'role' => 'user'],
            ['name' => 'Ratna Sari', 'email' => 'ratna.sari@test.com', 'role' => 'user'],

            // 7 Marketing Users
            ['name' => 'Marketing Test', 'email' => 'marketing@test.com', 'role' => 'marketing'],
            ['name' => 'Hendra Gunawan', 'email' => 'hendra.gunawan@test.com', 'role' => 'marketing'],
            ['name' => 'Linda Wijaya', 'email' => 'linda.wijaya@test.com', 'role' => 'marketing'],
            ['name' => 'Bambang Sutrisno', 'email' => 'bambang.sutrisno@test.com', 'role' => 'marketing'],
            ['name' => 'Citra Handoko', 'email' => 'citra.handoko@test.com', 'role' => 'marketing'],
            ['name' => 'Fabian Tanjaya', 'email' => 'fabian.tanjaya@test.com', 'role' => 'marketing'],
            ['name' => 'Gita Ariana', 'email' => 'gita.ariana@test.com', 'role' => 'marketing'],

            // 5 Admin Users
            ['name' => 'Admin Test', 'email' => 'admin@test.com', 'role' => 'admin'],
            ['name' => 'Supratman Andi', 'email' => 'supratman.andi@test.com', 'role' => 'admin'],
            ['name' => 'Ira Gunawan', 'email' => 'ira.gunawan@test.com', 'role' => 'admin'],
            ['name' => 'Joko Pratama', 'email' => 'joko.pratama@test.com', 'role' => 'admin'],
            ['name' => 'Kusuma Dewi', 'email' => 'kusuma.dewi@test.com', 'role' => 'admin'],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'),
                'role' => $user['role'],
            ]);
        }
    }
}
