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
        Schema::create('descriptions', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->foreignUuid('language_uuid')
                ->references('uuid')
                ->on('languages')
                ->cascadeOnDelete();
            $table->string('title');
            $table->text('description');

            $table->timestamps();

            $table->unique(['language_uuid', 'title']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descriptions');
    }
};
