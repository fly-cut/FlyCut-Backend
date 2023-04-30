<?php

namespace Database\Seeders;

use App\Models\Barbershop;
use Illuminate\Database\Seeder;

class BarbershopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Barbershop::factory()->count(25)->hasBarbers(5)->hasBarbershopAddresses(2)->create();
    }
}
