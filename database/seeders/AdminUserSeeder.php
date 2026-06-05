<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            $this->command->error("Le rôle 'admin' n'existe pas. Lancez d'abord RolePermissionSeeder.");
            return;
        }

        $user = User::firstOrCreate(
            ['email' => 'jistace.admin@ifapme.be'],
            [
                'name' => 'Administrateur (Jonathan)',
                'password' => Hash::make('Supermot2passe!'),
                'is_active' => true,
            ]
        );

        // Si vous n'utilisez pas de package type Spatie, utilisez ceci :
        $user->roles()->sync([$adminRole->role_id]);

        $this->command->info('✅ Admin créé et rôle assigné : ' . $user->email);
    }
}
