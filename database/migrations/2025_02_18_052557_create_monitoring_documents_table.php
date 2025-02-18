<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monitoring_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_id')->constrained('monitoring')->cascadeOnDelete();
            $table->enum('type', ['surat pengantar', 'surat penarikan', 'surat tugas', 'sppd']);
            $table->string('url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_documents');
    }
};
