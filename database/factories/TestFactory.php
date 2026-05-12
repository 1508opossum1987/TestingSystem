<?php

namespace Database\Factories;

use App\Models\QuestionLevel;
use App\Models\Test;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Test>
 */
class TestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'level_id' => QuestionLevel::inRandomOrder()->first()->id,
            'topic_id' => Topic::inRandomOrder()->first()->id,
            'question_count' => 10,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Test $test) {
            $questions = \App\Models\Question::factory(10)->make([
                'topic_id' => $test->topic_id,
                'level_id' => $test->level_id,
            ]);
            $test->questions()->saveMany($questions);
        });
    }
}
