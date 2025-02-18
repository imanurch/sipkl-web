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
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('internship_id')->constrained('internships')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->longText('activities')->nullable();
            $table->longText('feedback')->nullable();
            // status 0 unconfirmed, 1 accepted, 2 revised
            $table->enum('status', ['0', '1', '2'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};
