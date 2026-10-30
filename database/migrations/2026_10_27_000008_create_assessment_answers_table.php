<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('itil_questions')->cascadeOnDelete();
            $table->enum('answer', ['Y', 'P', 'N']);   // Yes / Partially / No
            $table->decimal('score', 3, 2);            // 1 / 0.5 / 0
            $table->text('notes')->nullable();
            $table->string('evidence_file')->nullable();
            $table->timestamps();

            $table->unique(['assessment_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_answers');
    }
};
