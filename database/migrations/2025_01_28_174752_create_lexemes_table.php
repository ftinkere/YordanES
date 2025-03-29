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
        Schema::create('lexemes', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('language_uuid')
                ->index()
                ->references('uuid')
                ->on('languages');

            $table->foreignUuid('article_uuid')
                ->index()
                ->references('uuid')
                ->on('dictionary_articles');

            $table->integer('group')->default(1);
            $table->integer('order')->default(1);
            $table->integer('suborder')->default(1);

            $table->text('short');
            $table->text('full');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lexemes');
    }
};
