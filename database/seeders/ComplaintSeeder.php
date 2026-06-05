<?php

namespace Database\Seeders;

use App\Models\Complaint;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        Complaint::factory()
            ->count(15)
            ->create();

        $this->command->info('✅ 15 plaintes DRAFT créées (user_id: 1, department_id: 3)');
    }
}
