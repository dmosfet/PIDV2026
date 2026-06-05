<?php

namespace App\Enums;

enum Pathway: int
{
    case APP = 1;
    case FCE = 2;
    case PRE = 3;
    CASE COEN = 4;
    CASE FC = 5;

    /**
     * Retourne le nom usuel du type de filière de formation.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::APP => 'Apprentissage',
            self::PRE => "Préparatoire",
            self::FCE => 'Chef d\'entreprise',
            self::COEN => 'Coordination et d\'encadrement',
            self::FC => 'Formation Continue',
        };
    }

    /**
     * Retourne le code de la filière.
     *
     * @return string
     */
    public function code(): string
    {
        return match($this) {
            self::APP => 'AP',
            self::PRE => 'PRE',
            self::FCE => 'CE',
            self::COEN => 'CO',
            self::FC => 'FC',
        };
    }

    /**
     * Permet de faire la correspondance entre les valeurs du fichier Excel et l'Enum
     *
     * @param string $stade
     * @return self|null
     */
    public static function tryFromExcel(?string $stade): ?self
    {
        if (is_null($stade)) {
            return null;
        }

        foreach (self::cases() as $case) {
            if ($stade=='COEN') {
                return self::COEN;
            }
            // On compare les deux en minuscule pour éviter les rejets bêtes
            if ($case->label() === trim($stade)) {
                return $case;
            }
        }

        // Si on arrive ici, c'est que la formation n'est pas dans ton Enum
        // Elle sera donc ignorée par ton Service d'import.
        return null;
    }
}
