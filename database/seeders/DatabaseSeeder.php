<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ConfiguracionGeneralSeeder::class,
            LineaCreditoSeeder::class,
            AsociadoSeeder::class,
            SorteoSeeder::class,
            PremioSeeder::class,
            CreditoSeeder::class,
        ]);
    }
}