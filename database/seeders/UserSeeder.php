<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Администратор',
                'email' => 'admin@test.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'Учитель',
                'email' => 'teacher@test.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'is_active' => true,
            ],
            [
                'name' => 'Обычный пользователь',
                'email' => 'user@test.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
