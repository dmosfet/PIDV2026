<?php

namespace Database\Seeders;

use App\Enums\Channel;
use App\Enums\Pathway;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PathwaySeeder extends Seeder
{
    public function run(): void
    {
        // On récupère tous les cas de l'Enum
        foreach (Pathway::cases() as $case) {
            DB::table('pathways')->updateOrInsert(
                ['pathway_id' => $case->value],
                [
                    'label' => $case->label(),
                    'code' => $case->code(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('✅ Table "pathways" mise à jour via l\'Enum \\n');
    }
}
