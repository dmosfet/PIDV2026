<?php

namespace Database\Seeders;

    use Illuminate\Database\Seeder;
    use App\Services\ProfessionImporter;

class ProfessionSeeder extends Seeder
{
    protected $importer;

    public function __construct(ProfessionImporter $importer)
    {
        $this->importer = $importer;
    }

    public function run()
    {
        // Chemin vers votre fichier de test dans storage/app
        $path = storage_path(config('services.import.professions_path'));

        if (!file_exists($path)) {
            $this->command->error("Le fichier de test est introuvable dans : $path");
            return;
        }

        $this->command->info("Début de l'importation de test...");

        $result = $this->importer->import($path);

        // Affichage du compte-rendu dans la console
        $this->command->table(['Statut', 'Quantité'], [
            ['Succès (Importés/Mis à jour)', $result['imported_count']],
            ['Erreurs (Lignes ignorées)', count($result['errors'])],
        ]);

        if (count($result['errors']) > 0) {
            $this->command->warn("Détail des lignes ignorées :");
            foreach ($result['errors'] as $error) {
                $this->command->line("- $error");
            }
        }
    }
}
