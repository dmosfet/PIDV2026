<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Sélectionner les départements existants
        $departments = Department::all();

        foreach ($departments as $dept) {
            $this->command->info("Création des utilisateurs pour : {$dept->name}");

            // --- CRÉATION D'UN USER AVEC UN ROLE MANAGER ---
            $managerUser = User::create([
                'name' => "Manager " . $dept->code,
                'email' => strtolower($dept->code) . ".manager@ifapme.be",
                'password' => Hash::make('manager'),
                'email_verified_at' => now(),
            ]);

            $managerEmployee = Employee::create([
                'department_id' => $dept->department_id,
                'first_name' => 'Manager',
                'last_name' => $dept->code,
                'function' => 'Manager',
                'email' => $managerUser->email,
                'is_active' => true,
            ]);

            // Liaison inverse (si vous avez employee_id dans users)
            $managerUser->update(['employee_id' => $managerEmployee->employee_id]);

            // Assigner le rôle
            $managerUser->assignRole('manager');

            // Ajouter l'employé comme manager du département
            $dept->update(['manager_id' => $managerEmployee->employee_id]);


            // --- CRÉATION DU SECRÉTARIAT ---
            $secUser = User::create([
                'name' => "Secrétariat " . $dept->code,
                'email' => strtolower($dept->code) . ".sec@ifapme.be",
                'password' => Hash::make('secretariat'),
                'email_verified_at' => now(),
            ]);

            $secEmployee = Employee::create([
                'department_id' => $dept->department_id,
                'first_name' => 'Secrétaire',
                'last_name' => $dept->code,
                'function' => 'Secretariat',
                'email'=> $secUser->email,
                'is_active' => true,
            ]);

            $secUser->update(['employee_id' => $secEmployee->employee_id]);
            $secUser->assignRole('secretariat');
        }

        $this->command->info('✅ Utilisateurs tests créés (Managers / Secrétaires).');
    }
}
