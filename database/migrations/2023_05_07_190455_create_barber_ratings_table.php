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
        Schema::create('barber_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barber_id')->constrained('barbers');
            $table->foreignId('client_id')->constrained('clients');
            $table->integer('rating');
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
        Schema::dropIfExists('barber_ratings');
    }
};
