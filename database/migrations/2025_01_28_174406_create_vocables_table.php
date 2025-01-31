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
        Schema::create('vocables', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('article_uuid')
                ->unique()
                ->references('uuid')
                ->on('dictionary_articles');

            $table->text('vocabula');
            $table->text('adaptation')->nullable();
            $table->text('transcription')->nullable();
            $table->text('image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocables');
    }
};
