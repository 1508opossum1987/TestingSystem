<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->string('answers_file_path')->nullable()->after('grade');
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn('answers_file_path');
        });
    }
};
