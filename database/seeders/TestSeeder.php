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
        Test::factory(10)->create();
    }
}
