<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Solo llamamos al maestro que acabas de configurar
        $this->call(RolesPermisosSeeder::class);
    }
}