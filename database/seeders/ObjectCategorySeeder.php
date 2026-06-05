<?php

namespace Database\Seeders;

use App\Enums\Channel;
use App\Enums\ObjectCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObjectCategorySeeder extends Seeder
{
    public function run(): void
    {
        // On récupère tous les cas de l'Enum
        foreach (ObjectCategory::cases() as $case) {
            DB::table('object_categories')->updateOrInsert(
                ['object_category_id' => $case->value],
                [
                    'label' => $case->label(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('✅ Table "object_categories" mise à jour via l\'Enum \\n');
    }
}

