<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kalnoy\Nestedset\NestedSet;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grammatic_categories', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('language_id')
                ->nullable()
                ->index()
                ->references('uuid')
                ->on('languages');

            $table->string('name');
            $table->text('description')->nullable();

            NestedSet::columns($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammatic_categories');
    }
};
