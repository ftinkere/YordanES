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
        Schema::create('lexeme_blocks_scheme', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('language_id')
                ->index()
                ->references('uuid')
                ->on('languages');

            $table->string('name');
            //
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lexeme_blocks_scheme');
    }
};
