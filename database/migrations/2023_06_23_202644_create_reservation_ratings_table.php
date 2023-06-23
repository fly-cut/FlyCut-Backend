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
        Schema::create('reservation_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbershop_id')->constrained('barbershops');
            $table->foreignId('barber_id')->constrained('barbers');
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('reservation_id')->constrained('reservations');
            $table->integer('barber_rating');
            $table->integer('barbershop_rating');
            $table->string('review')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_ratings');
    }
};
