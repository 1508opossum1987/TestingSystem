<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema:: create('question_test', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('test_id')->constrained('tests');
            $table->foreignId('question_id')->constrained('questions');
            $table->integer('order')->nullable();
            $table->timestamps();
            $table->unique(['test_id', 'question_id']);
        });
    }

    public function down(): void
    {
        //
    }
};
