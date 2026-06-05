<?php

namespace Database\Seeders;

use App\Enums\BoaSessionType;
use App\Enums\ComplaintType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplainantTypeSeeder extends Seeder
{
    public function run(): void
    {
        // On récupère tous les cas de l'Enum
        foreach (ComplaintType::cases() as $case) {
            DB::table('complainant_types')->updateOrInsert(
                ['complainant_type_id' => $case->value],
                [
                    'label' => $case->label(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        $this->command->info('✅ Table "complainant_types" mise à jour via l\'Enum');
    }
}
