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
        Schema::create('lexeme_blocks', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('lexeme_id')
                ->index()
                ->references('uuid')
                ->on('lexemes')
                ->cascadeOnDelete();
            $table->string('name');
            $table->longText('content');

            $table->integer('order')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lexeme_blocks');
    }
};
