<?php

use Database\OnDelete;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('referral_program_id');
            $table->text('name');
            $table->text('description');
            $table->integer('points');
            $table->timestamps();

            $table->foreign('referral_program_id')
                ->references('id')
                ->on('referral_programs')
                ->onDelete(OnDelete::CASCADE);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};