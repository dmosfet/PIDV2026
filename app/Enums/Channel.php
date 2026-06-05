<?php

namespace App\Enums;

use App\Models\Department;

enum Channel: int
{
    case PLAINTES = 1;
    case RECOURS = 2;
    CASE CCO = 3;
    CASE SPW = 4;
    CASE MINISTRE = 5;
    CASE MEDIATEUR = 6;
    CASE FORMULAIRE = 7;
    CASE AUTRES = 8;

    /**
     * Retourne le nom usuel du canal de réception de la plainte.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::PLAINTES => 'E-mail plaintes@ifapme.be',
            self::RECOURS => 'E-mail recours@ifapme.be',
            self::CCO=> 'Centre de Contact',
            self::SPW => 'SPW',
            self::MINISTRE => 'Ministre de tutelle',
            self::MEDIATEUR => 'Médiateur de la Région wallonne',
            self::FORMULAIRE => 'Formulaire en ligne',
            self::AUTRES => 'Autres',
        };
    }

    /**
     * Vérifie si le département de l'utilisateur connecté peut afficher certains choix du menu déroulant.
     *
     * @param int|null $deptId
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

        // Les types recours, médiateur, ministre et formulaire sont réservés à l'administration générale.
        return match($this) {
            self::RECOURS, self::MEDIATEUR, self::MINISTRE, self::FORMULAIRE => ($deptTypeId !== DepartmentType::ADMIN->value),
            default => false,
        };
    }
}
