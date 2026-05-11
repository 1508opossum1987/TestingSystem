<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('userLogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained();
            $table->foreignId('resultId')->constrained();
            $table->string('filePath');
            $table->text('contentPreview')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('userLogs');
    }
};
