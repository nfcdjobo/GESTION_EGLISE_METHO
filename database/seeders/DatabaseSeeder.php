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
        // Ordre important : les permissions d'abord, puis les utilisateurs
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            ContactSeeder::class,
            TypeReunionSeeder::class,
            // ProgrammeSeeder::class,
        ]);

        $this->command->info('🎉 Base de données initialisée avec succès pour l\'église méthodiste !');
    }
}
