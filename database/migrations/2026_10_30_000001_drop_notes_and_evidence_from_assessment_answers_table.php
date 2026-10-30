<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Catatan dan file bukti jawaban tidak dipakai.
     */
    public function up(): void
    {
        Schema::table('assessment_answers', function (Blueprint $table) {
            $table->dropColumn(['notes', 'evidence_file']);
        });
    }

    public function down(): void
    {
        Schema::table('assessment_answers', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('score');
            $table->string('evidence_file')->nullable()->after('notes');
        });
    }
};
