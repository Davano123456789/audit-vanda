<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name'); // PT. Askarasoft Global Teknologi
            $table->text('description')->nullable();
            $table->foreignId('asesor_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['draft', 'in_progress', 'completed'])->default('draft');
            $table->decimal('maturity_index', 3, 2)->nullable(); // nilai kematangan domain, contoh 2.00
            $table->dateTime('expired_at')->nullable();          // batas waktu pengisian
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
