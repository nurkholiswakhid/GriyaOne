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


            // 7 Marketing Users
            ['name' => 'Marketing Test', 'email' => 'marketing@test.com', 'role' => 'marketing'],


            // 5 Admin Users
            ['name' => 'Admin Test', 'email' => 'admin@test.com', 'role' => 'admin'],

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
