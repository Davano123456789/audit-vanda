<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Subdomain yang dinilai dalam sebuah assessment
        Schema::create('assessment_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->string('process_code', 10);
            $table->unsignedTinyInteger('target_level')->default(3);
            $table->unsignedTinyInteger('result_level')->nullable(); // hasil akhir 0 - 5
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->timestamps();

            $table->foreign('process_code')->references('code')->on('itil_processes')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->unique(['assessment_id', 'process_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_processes');
    }
};
