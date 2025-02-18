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
        Schema::create('intern_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('internship_id')->constrained('internships')->cascadeOnDelete();
            $table->enum('type', ['surat jalan', 'laporan akhir']);
            $table->string('url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intern_documents');
    }
};
