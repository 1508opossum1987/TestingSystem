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
            'question_count' => $this->faker->numberBetween(10, 10),
        ];
    }
}
