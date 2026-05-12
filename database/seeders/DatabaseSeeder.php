<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        $users=[
            [
                'name' => 'TestUser',
                'email' => 'test@example.com',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ],
        ];

        foreach ($users as $user) {
            User::query()->updateOrCreate(
                [
                    'email' => $user['email'],
                ],
                [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => $user['password'],
                    'role' => $user['role'],
                ]
            );
        }

        $this->call([
            TopicSeeder::class,
            QuestionLevelSeeder::class,
            QuestionSeeder::class,
            TestSeeder::class,
            ResultSeeder::class,
            UserLogSeeder::class,
            UserSeeder::class,
        ]);
    }
}
