<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('data/departments.csv');

        if (!file_exists($filePath)) {
            $this->command->error("Fichier CSV introuvable à l'adresse : $filePath");
            return;
        }

        $file = fopen($filePath, 'r');
        $header = fgetcsv($file); // On lit la première ligne (les entêtes)

        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);

            DB::table('departments')->updateOrInsert(
                ['department_id' => $data['department_id']], // Clé stable pour l'ID
                [
                    'name'               => $data['name'],
                    'code'               => $data['code'],
                    'address'            => $data['address'],
                    'city'               => $data['city'],
                    'zip_code'           => $data['zip_code'],
                    'department_type_id' => $data['department_type_id'],
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]
            );
            $count++;
        }

        fclose($file);
        $this->command->info("✅ $count départements importés depuis le CSV.");
    }
}
