<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Subdomain Service Operation: EM, IM, PM, RF, AM
        Schema::create('itil_processes', function (Blueprint $table) {
            $table->string('code', 10)->primary(); // IM
            $table->string('domain_code', 10);
            $table->string('name');                // Incident Management
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('domain_code')->references('code')->on('itil_domains')
                ->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itil_processes');
    }
};
