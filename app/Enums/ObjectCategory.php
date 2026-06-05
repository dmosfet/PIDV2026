<?php

namespace App\Enums;

use App\Models\Department;

enum ObjectCategory: int
{
    case ACCOMPAGNEMENT = 1;
    case AGTENT = 2;
    CASE DISCRIMINATION = 3;
    CASE ATTESTATION = 4;
    CASE CONDITIONFCE = 5;
    CASE CONDITIONDISPENSE = 6;
    CASE COURSCONTENU = 7;
    CASE COURSMATERIEL = 8;
    CASE COURSHORAIRE = 9;


    /**
     * Retourne le nom usuel de la catégorie de plainte.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::ACCOMPAGNEMENT => 'Accompagnement',
            self::AGTENT => 'Agrément entreprise',
            self::DISCRIMINATION => 'Discrimination',
            self::ATTESTATION => 'Attestation',
            self::CONDITIONFCE => 'Condition d\'accès aux formations FCE',
            self::CONDITIONDISPENSE => 'Conditions d\'admissions : Dispenses',
            self::COURSCONTENU => 'Cours: Contenu',
            self::COURSMATERIEL => 'Cours: Matériel',
            self::COURSHORAIRE => 'Cours: Horaire',
        };
    }

    /**
     * Vérifie si le département de l'utilisateur connecté peut afficher certains choix du menu déroulant.
     *
     * @param int|null $deptTypeId
     * @return bool
     */
    public function isDisabled(?int $deptId): bool
    {

        // Si l'ID est null (compte mal configuré / pas d'employé), on bloque tout par sécurité
        if (is_null($deptId)) {
            return true;
        }

        // On récupère le type de département pour déterminer si l'option doit apparaitre
        $deptTypeId = Department::where("department_id", $deptId)->first()->department_type_id;

        // Les types recours et médiateur sont réservé à l'administration générale.
        return match($this) {
            self::CONDITIONFCE, self::CONDITIONDISPENSE, self::COURSCONTENU, self::COURSHORAIRE, self::COURSMATERIEL => ($deptTypeId !== DepartmentType::CENTRE->value),
            self::AGTENT => ($deptTypeId !== DepartmentType::SALT->value),
            default => false,
        };
    }
}
