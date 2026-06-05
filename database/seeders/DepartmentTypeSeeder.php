<?php

namespace Database\Seeders;

use App\Enums\DepartmentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentTypeSeeder extends Seeder
{
    public function run(): void
    {
        // On récupère tous les cas de l'Enum
        foreach (DepartmentType::cases() as $case) {
            DB::table('department_types')->updateOrInsert(
                ['department_type_id' => $case->value],
                [
                    'label' => $case->label(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('✅ Table "departments_type" mise à jour via l\'Enum \\n');
    }
}

