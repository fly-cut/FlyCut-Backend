<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\BarbershopOwner;
use Illuminate\Database\Seeder;
use Database\Seeders\ClientSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BarbershopSeeder::class,
            ClientSeeder::class,
            AdminSeeder::class
        ]);
    }
}
