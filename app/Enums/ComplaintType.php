<?php

namespace App\Enums;

use App\Models\Department;

enum ComplaintType: int
{
    case RECLAMATION = 1;
    case PLAINTE = 2;
    CASE RECOURS = 3;
    CASE MEDIATEUR = 4;

    /**
     * Retourne un nom usuel du type de plainte
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::RECLAMATION => 'Réclamation',
            self::PLAINTE => 'Plainte',
            self::RECOURS => 'Recours',
            self::MEDIATEUR => 'Médiateur',
        };
    }

    /**
     * Retourne un code du type de plainte pour générer la référence d'une plainte ou le type de document.
     * Le code est généré sur 2 caractères.
     *
     * @return string
     */
    public function code(): string
    {
        return match($this) {
            self::RECLAMATION => 'RN',
            self::PLAINTE => 'PL',
            self::RECOURS => 'RS',
            self::MEDIATEUR => 'MD',
        };
    }

    /**
     * Vérifie si le département de l'utilisateur connecté peut afficher certains choix du menu déroulant.
     *
     * @param int|null $deptId Département de l'utilisateur, si existant.
     * @return bool
     */
    public function isDisabled(?int $deptId): bool
    {
        // Si l'ID est null, on désactive tout
        if (is_null($deptId)) {
            return true;
        }

        // On récupère le type de département pour déterminer si l'option doit apparaitre
        // Attention que le type de département est casté en Enum dans le modèle département.
        $deptTypeId = Department::where("department_id", $deptId)->first()->department_type_id;

        // Les types recours et médiateur sont réservés à l'administration générale.
        // Les réclamations sont toujours désactivées
        return match($this) {
            self::RECLAMATION => true,
            self::RECOURS, self::MEDIATEUR => ($deptTypeId !== DepartmentType::ADMIN),
            default => false,
        };
    }
}
