<?php

namespace Database\Seeders;

use App\Enums\ComplaintType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintTypeSeeder extends Seeder
{
    public function run(): void
    {
        // On récupère tous les cas de l'Enum
        foreach (ComplaintType::cases() as $case) {
            DB::table('complaint_types')->updateOrInsert(
                ['complaint_type_id' => $case->value],
                [
                    'label' => $case->label(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('✅ Table "complaint_types" mise à jour via l\'Enum');
    }
}

