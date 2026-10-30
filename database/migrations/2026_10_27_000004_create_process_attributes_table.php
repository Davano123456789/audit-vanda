<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Atribut proses tiap level, contoh: PP (Level 1 & 2), WPM (Level 3), PC (Level 4)
        Schema::create('process_attributes', function (Blueprint $table) {
            $table->string('code', 10)->primary();       // WPM
            $table->string('name');                      // Work Product Management
            $table->unsignedTinyInteger('level');        // 2 = Level 1 & 2, 3, 4, 5
            $table->unsignedTinyInteger('sort_order')->default(1); // urutan dalam satu level
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['level', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('process_attributes');
    }
};
