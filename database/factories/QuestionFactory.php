<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Topic;
use App\Models\QuestionLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'topic_id' => Topic::inRandomOrder()->first()->id ?? 1,
            'level_id' => QuestionLevel::inRandomOrder()->first()->id ?? 1,
            'question_text' => $this->faker->sentence(6),
            'options' => json_encode([
                'A' => $this->faker->word,
                'B' => $this->faker->word,
                'C' => $this->faker->word,
                'D' => $this->faker->word,
            ]),
            'correct_answer' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'type' => 'single_choice',
        ];
    }
}
