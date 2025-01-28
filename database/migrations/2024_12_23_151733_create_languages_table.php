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
        Schema::create('languages', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->foreignUuid('creator_uuid')->references('uuid')->on('users');

            $table->string('name');
            $table->string('autoname')->nullable();
            $table->string('autoname_transcription')->nullable();
            $table->string('flag')->nullable();

            $table->boolean('published')->default(false);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
