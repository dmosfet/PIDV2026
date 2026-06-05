<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Role, Permissions et utilisateur admin
            RolePermissionSeeder::class,
            AdminUserSeeder::class,

            // Données extraites d'Enum pour les catégories
            ChannelSeeder::class,
            ComplaintTypeSeeder::class,
            ComplaintStatusSeeder::class,
            DepartmentTypeSeeder::class,
            ObjectCategorySeeder::class,
            PathwaySeeder::class,

            // Autres données de base qui ne provient pas d'une enum
            DepartmentSeeder::class,
            ProfessionSeeder::class,

            // Création des utilisateurs test
            TestUserSeeder::class,
        ]);
    }
}
