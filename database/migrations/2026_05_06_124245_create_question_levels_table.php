<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('question_levels', function (Blueprint $table) {
            $table->id();
            $table->enum('question_level', [1, 2, 3, 4]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_levels');
    }
};
