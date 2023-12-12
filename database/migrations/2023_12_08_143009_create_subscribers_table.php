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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('newsletter_id');
            $table->uuid('referer_subscriber_id')->nullable()->index();
            $table->text('email');
            $table->text('ref_code');
            $table->text('ref_link');
            $table->text('is_ref');
            $table->integer('ref_count');
            $table->text('status');
            $table->timestamps();

            $table->foreign('newsletter_id')
                ->references('id')
                ->on('newsletters')
                ->onDelete(OnDelete::CASCADE);
        });

        Schema::table('subscribers', function (Blueprint $table) {
            $table->foreign('referer_subscriber_id')
                ->references('id')
                ->on('subscribers')
                ->onDelete(OnDelete::SET_NULL);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
