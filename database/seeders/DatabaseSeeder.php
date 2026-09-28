<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            ConfigurationSeeder::class,
            BonusTypeSeeder::class,
            RegimenSeeder::class,
            UserSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}