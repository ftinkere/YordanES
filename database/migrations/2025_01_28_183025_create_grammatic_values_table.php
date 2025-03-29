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
        Schema::create('grammatic_values', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('grammatic_uuid')
                ->index()
                ->references('uuid')
                ->on('grammatic_categories');

            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();

            $table->integer('order')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammatic_values');
    }
};
