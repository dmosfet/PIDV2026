<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Créer les permissions
        $permissions = [
            ['name' => 'view_complaints', 'label' => 'Voir les plaintes', 'category' => 'complaint'],
            ['name' => 'create_complaint', 'label' => 'Créer une plainte', 'category' => 'complaint'],
            ['name' => 'edit_complaint', 'label' => 'Modifier une plainte', 'category' => 'complaint'],
            ['name' => 'delete_complaint', 'label' => 'Supprimer une plainte', 'category' => 'complaint'],
            ['name' => 'print_complaint', 'label' => 'Imprimer des documents d\'une plainte', 'category' => 'complaint'],

            // Plaintes et recours
            ['name' => 'assign_complaint', 'label' => 'Assigner', 'category' => 'workflow'],
            ['name' => 'reassign_complaint', 'label' => 'Réassigner', 'category' => 'workflow'],
            ['name' => 'appeal_complaint', 'label' => 'Lier', 'category' => 'workflow'],
            ['name' => 'acknowledge_complaint', 'label' => 'Accuser réception', 'category' => 'workflow'],
            ['name' => 'evaluate_complaint', 'label' => 'Évaluer', 'category' => 'workflow'],
            ['name' => 'respond_complaint', 'label' => 'Répondre', 'category' => 'workflow'],
            ['name' => 'close_complaint', 'label' => 'Clôturer', 'category' => 'workflow'],

            // Les permissions liées à la gestion des utilisateurs (rôle et permissions)
            ['name' => 'manage_users', 'label' => 'Gérer les utilisateurs', 'category' => 'admin'],
            ['name' => 'manage_roles', 'label' => 'Gérer les rôles', 'category' => 'admin'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $this->command->info('✅ ' . count($permissions) . ' permissions créées');

        // Création d'une role "Admin"
        $admin = Role::create([
            'name' => 'admin',
            'label' => 'Administrateur',
            'description' => 'Accès complet',
        ]);

        // Création d'un role "Secrétariat" classique
        $secretariat = Role::create([
            'name' => 'secretariat',
            'label' => 'Secrétariat',
            'description' => 'Encodage de la plainte et accusé de réception',
        ]);

        // Création d'un role "Manager"
        $manager = Role::create([
            'name' => 'manager',
            'label' => 'Responsable du traitement',
            'description' => 'Évaluation de la plainte et traitement',
        ]);


        $this->command->info('✅ 4 rôles créés');

        // Associer les permissions aux roles

        // Admin : TOUTES les permissions
        $allPermissions = Permission::all();

        $admin->permissions()->attach($allPermissions->pluck('permission_id'));
        $this->command->info('✅ Admin : ' . $allPermissions->count() . ' permissions attachées');

        // Secrétariat : permissions limitées
        $secretariatPermissions = Permission::whereIn('name', [
            'view_complaints',
            'create_complaint',
            'edit_complaint',
            'assign_complaint',
            'reassign_complaint',
            'acknowledge_complaint',
            'print_complaint',
        ])->get();

        $secretariat->permissions()->attach($secretariatPermissions->pluck('permission_id'));
        $this->command->info('✅ Secrétariat : ' . $secretariatPermissions->count() . ' permissions attachées');


        // Manager : permissions de traitement
        $managerPermissions = Permission::whereIn('name', [
            'view_complaints',
            'edit_complaint',
            'evaluate_complaint',
            'respond_complaint',
            'close_complaint',
            'print_complaint',
        ])->get();

        $manager->permissions()->attach($managerPermissions->pluck('permission_id'));
        $this->command->info('✅ Manager : ' . $managerPermissions->count() . ' permissions attachées');

        $this->command->info('🎉 Rôles et permissions configurés avec succès !');

    }
}
