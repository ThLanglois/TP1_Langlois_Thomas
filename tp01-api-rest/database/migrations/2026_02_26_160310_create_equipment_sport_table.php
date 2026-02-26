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
        Schema::create('equipment_sport', function (Blueprint $table) {
            $table->unsignedBigInteger('sport_id');
            $table->unsignedBigInteger('equipment_id');

            $table->primary(['sport_id', 'equipment_id']); // https://laravel.com/docs/12.x/migrations

            $table->foreign('sport_id')->references('id')->on('sports');

            $table->foreign('equipment_id')->references('id')->on('equipment');
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_sport');
    }
};
