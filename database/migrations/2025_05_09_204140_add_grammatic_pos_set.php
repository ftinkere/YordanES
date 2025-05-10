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
        Schema::create('grammatic_pos_set', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->uuidMorphs('parent');
            $table->integer('group')->nullable();

            $table->uuid('pos_id')->index();
            $table->foreign('pos_id')->references('uuid')->on('grammatic_part_of_speeches');

            $table->timestamps();

            $table->unique(['parent_type', 'parent_id', 'group', 'pos_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammatic_pos_set');
    }
};
