<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SuperAdminUserSeeder::class,
            CountrySeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            OccupationSeeder::class,
            NativeVillageSeeder::class,
        ]);
    }
}
