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
        Schema::create('grammatic_part_of_speeches', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->string('name')->index();
            $table->string('code')->index();
            $table->text('description')->nullable();

            $table->uuid('language_id')->nullable()->index();
            $table->foreign('language_id')->references('uuid')->on('languages');

            $table->integer('order')->default(0);

            $table->timestamps();
        });

        Schema::create('grammatic_categories', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->string('name');
            $table->string('code')->index();
            $table->text('description')->nullable();

            $table->uuid('language_id')->index();
            $table->foreign('language_id')->references('uuid')->on('languages');

            $table->uuid('pos_id')->index();
            $table->foreign('pos_id')->references('uuid')->on('grammatic_part_of_speeches');

            $table->uuid('parent_uuid')->nullable()->index();

            $table->boolean('is_multiple')->default(true);
            $table->integer('order')->default(0);

            $table->timestamps();
        });

        Schema::table('grammatic_categories', function (Blueprint $table) {
            $table->foreign('parent_uuid')->references('uuid')->on('grammatic_categories');
        });

        Schema::create('grammatic_values', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->string('name');
            $table->string('code')->index();
            $table->text('description')->nullable();

            $table->uuid('language_id')->index();
            $table->foreign('language_id')->references('uuid')->on('languages');

            $table->uuid('category_id')->index();
            $table->foreign('category_id')->references('uuid')->on('grammatic_categories');

            $table->integer('order')->default(0);

            $table->timestamps();
        });


        Schema::create('grammatic_set', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->uuidMorphs('parent');
            $table->integer('group')->nullable();

            $table->uuid('value_id')->index();
            $table->foreign('value_id')->references('uuid')->on('grammatic_values');

            $table->boolean('is_changeable')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammatic_part_of_speeches');
        Schema::dropIfExists('grammatic_categories');
        Schema::dropIfExists('grammatic_values');
        Schema::dropIfExists('grammatic_set');
    }
};
