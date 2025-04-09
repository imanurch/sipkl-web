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
        Schema::table('advisors', function (Blueprint $table) {
            // $table->unsignedBigInteger('position_id')->after('nip');
            // $table->unsignedBigInteger('level_id')->after('position_id');
            $table->foreignId('position_id')->constrained('advisor_position');
            $table->foreignId('level_id')->constrained('advisor_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advisors', function (Blueprint $table) {
            //
        });
    }
};
