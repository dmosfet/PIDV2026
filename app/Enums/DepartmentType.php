<?php

namespace App\Enums;

enum DepartmentType: int
{
    case CENTRE = 1;
    case SALT = 2;
    CASE DT = 3;
    CASE SPV = 4;
    CASE ADMIN = 5;

    /**
     * Retourne le nom usuel du type de département.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::CENTRE => 'Centre de formation IFAPME',
            self::SALT => 'Service Alternance',
            self::DT => 'Direction Territoriale',
            self::SPV => 'Direction/Service Place Verte',
            self::ADMIN => 'Administration générale',

        };
    }
}
