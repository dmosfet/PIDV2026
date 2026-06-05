<?php

namespace Database\Seeders;

use App\Enums\ComplaintStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintStatusSeeder extends Seeder
{
    public function run(): void
    {
        // On récupère tous les cas de l'Enum
        foreach (ComplaintStatus::cases() as $case) {
            DB::table('complaint_status')->updateOrInsert(
                ['complaint_status_id' => $case->value],
                [
                    'label' => $case->label(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('✅ Table "complaint_status" mise à jour via l\'Enum (sans modèle).');
    }
}
