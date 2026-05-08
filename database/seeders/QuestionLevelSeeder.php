<?php

namespace Database\Seeders;

use App\Models\QuestionLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionLevelSeeder extends Seeder
{
    public function run(): void
    {
        $question_levels = [
            1, 2, 3, 4
        ];

        foreach ($question_levels as $question_level) {
            QuestionLevel:: query()
                ->firstOrCreate(['question_level' => $question_level], ['question_level' => $question_level]);
        }
    }
}
