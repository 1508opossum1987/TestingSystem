<?php

namespace Database\Seeders;

use App\Models\QuestionLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [1, 2, 3, 4];

        foreach ($levels as $level) {
            QuestionLevel::firstOrCreate(
                ['level' => $level],
                ['level' => $level]
            );
        }
    }
}
