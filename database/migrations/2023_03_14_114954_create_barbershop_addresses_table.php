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
        Schema::create('barbershop_addresses', function (Blueprint $table) {
            $table->id();
            $table->decimal('longitude');
            $table->decimal('latitude');
            $table->string('address');
            $table->foreignId('barbershop_id')->constrained('barbershops');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barbershop_addresses');
    }
};
