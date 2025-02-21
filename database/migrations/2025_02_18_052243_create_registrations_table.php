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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups');
            $table->foreignId('industry_id')->constrained('industries');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('batch_id')->constrained('batches');
            // status 0 unconfirmed, 1 accepted, 2 rejected
            $table->enum('status', ['0', '1', '2'])->default('0');
            $table->enum('step', ['1', '2', '3', '4', '5'])->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
