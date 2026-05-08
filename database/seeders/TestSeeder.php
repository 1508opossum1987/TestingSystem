<?php

namespace Database\Seeders;

use App\Models\QuestionLevel;
use App\Models\Test;
use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        $testCountForTest = 1;

        $question_level = QuestionLevel::query()->get();
        $topic = Topic::query()->get();
        $question_count = 10;

        for ($i = 0; $i < $testCountForTest; $i++) {
            Test::factory([
                    'level_id' => $question_level->id,
                    'topic_id' => $topic->id,
                    'question_count' => $question_count]
            );
        }
    }
}
