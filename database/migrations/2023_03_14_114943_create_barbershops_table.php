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
        Schema::create('barbershops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->double('rating')->default(5.0);
            $table->integer('rating_count')->default(0);
            $table->string('description')->nullable();
            $table->double('longitude');
            $table->double('latitude');
            $table->string('address');
            $table->string('city');
            $table->foreignId('barbershop_owner_id')->constrained('barbershop_owners');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barbershops');
    }
};
