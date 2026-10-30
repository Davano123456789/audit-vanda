<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itil_questions', function (Blueprint $table) {
            $table->id();
            $table->string('process_code', 10);
            $table->string('attribute_code', 10);
            $table->unsignedSmallInteger('number')->default(1); // nomor urut dalam satu atribut
            $table->text('question_text');
            $table->timestamps();

            $table->foreign('process_code')->references('code')->on('itil_processes')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('attribute_code')->references('code')->on('process_attributes')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->index(['process_code', 'attribute_code', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itil_questions');
    }
};
