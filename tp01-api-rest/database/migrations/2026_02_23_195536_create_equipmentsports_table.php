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
        Schema::create('equipmentsports', function (Blueprint $table) {
            $table->unsignedBigInteger('sportId');
            $table->unsignedBigInteger('equipmentId');

            $table->primary(['sportId', 'equipmentId']); // https://laravel.com/docs/12.x/migrations

            $table->foreign('sportId')->references('id')->on('sports');

            $table->foreign('equipmentId')->references('id')->on('equipment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipmentsports');
    }
};
