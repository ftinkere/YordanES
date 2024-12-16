<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{

    protected $connection = 'mysql';

    public function up(): void
    {
        try {
            Schema::create('stored_events', function (Blueprint $table) {
                $table->id();
                $table->uuid('aggregate_uuid')->nullable();
                $table->unsignedBigInteger('aggregate_version')->nullable();
                $table->unsignedTinyInteger('event_version')->default(1);
                $table->string('event_class');
                $table->jsonb('event_properties');
                $table->jsonb('meta_data');
                $table->timestamp('created_at');
                $table->index('event_class');
                $table->index('aggregate_uuid');

                $table->unique(['aggregate_uuid', 'aggregate_version']);
            });
        } catch (\Illuminate\Database\QueryException $e) {}
    }

    public function down(): void
    {
        Schema::dropIfExists('stored_events');
    }
};
