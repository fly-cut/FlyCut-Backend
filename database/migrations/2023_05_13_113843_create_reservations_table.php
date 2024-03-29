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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('clients');
            $table->foreignId('barber_id')->constrained('barbers');
            $table->foreignId('barbershop_id')->constrained('barbershops');
            $table->integer('price')->default(0);
            $table->string('date')->nullable();
            $table->string('status')->default('Upcoming');
            $table->boolean('is_rated')->default(false);
            $table->string('payment_method')->default('Cash');
            $table->string('payment_status')->default('Unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
