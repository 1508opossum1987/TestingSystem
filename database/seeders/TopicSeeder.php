<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            'Математика',
            'Окружающий мир',
        ];

        foreach ($topics as $topic) {
            Topic::query()
                ->firstOrCreate(['name' => $topic],
                    ['name' => $topic,
                        'slug' => Str::slug($topic),
                ]);
        }
    }
}
