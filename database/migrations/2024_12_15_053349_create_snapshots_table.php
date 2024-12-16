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
            Schema::create('snapshots', function (Blueprint $table) {
                $table->id();
                $table->uuid('aggregate_uuid');
                $table->unsignedBigInteger('aggregate_version');
                $table->jsonb('state');

                $table->timestamps();

                $table->index('aggregate_uuid');
            });
        } catch (\Illuminate\Database\QueryException $e) {}
    }

    public function down(): void
    {
        Schema::dropIfExists('snapshots');
    }
};
