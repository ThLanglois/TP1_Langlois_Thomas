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
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->date('startDate');
            $table->date('endDate');
            $table->decimal('totalPrice', 10);
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('equipmentId');

            $table->foreign('userId')->references('id')->on('users');

            $table->foreign('equipmentId')->references('id')->on('equipment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
