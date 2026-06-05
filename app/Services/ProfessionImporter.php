<?php

namespace App\Services;

namespace App\Services;

use App\Models\Profession;
use App\Enums\Pathway;
use Spatie\SimpleExcel\SimpleExcelReader;

class ProfessionImporter
{
    /**
     * Importe l'export Excel de Walter pour ajouter ou mettre à jour les métiers dans l'application.
     *
     * @param string $filePath Chemin complet du fichier Excel à lire.
     * @return array
     */
    public function import(string $filePath): array
    {
        // On initialise les variables
        $rows = SimpleExcelReader::create($filePath)->getRows();
        $dataToUpsert = [];
        $errors = [];
        $count = 0;

        // Pour chaque ligne du fichier Excel
        foreach ($rows as $index => $row) {

            // On filtre sur les métiers des filières existantes dans l'Enum
            $pathway = Pathway::tryFromExcel($row['Stade'] ?? '');

            if (!$pathway) {
                $errors[] = "Ligne " . ($index + 2) . " ignorée : Stade inconnu ('{$row['Stade']}')";
                continue;
            }

            // On génère le code unique basé sur l'indice métier et le code du stade
            $computedCode = trim($row['Indice']) . '-' . $pathway->code();

            // On prépare le tableau des métiers à insérer
            $dataToUpsert[] = [
                'code'       => $computedCode,
                'name'       => trim($row['Libellé au masculin']),
                'active'     => $this->parseActive($row['actif'] ?? '0'),
                'pathway_id' => $pathway->value,
                'updated_at' => now(),
                'created_at' => now(),
            ];

            $count++;
        }

        if (!empty($dataToUpsert)) {
            // UPSERT :
            // 1. Cherche si 'code' existe déjà.
            // 2. Si OUI : met à jour 'name', 'active', 'pathway_id' et 'updated_at'.
            // 3. Si NON : insère la ligne et crée un nouveau 'profession_id'.
            Profession::upsert(
                $dataToUpsert,
                ['code'], // La colonne unique de référence
                ['name', 'active', 'pathway_id', 'updated_at'] // Colonnes à modifier si doublon
            );
        }

        return [
            'imported_count' => $count,
            'errors'         => $errors,
        ];
    }

    /**
     * Convertit les différentes valeurs Excel en booléen propre.
     */
    private function parseActive($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
