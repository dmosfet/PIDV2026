<?php

namespace Database\Seeders;

use App\Enums\Channel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        // On récupère tous les cas de l'Enum
        foreach (Channel::cases() as $case) {
            DB::table('channels')->updateOrInsert(
                ['channel_id' => $case->value],
                [
                    'label' => $case->label(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('✅ Table "channels" mise à jour via l\'Enum');
    }
}
