<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasNameAttributes
{
    /**
     * Récupère les initiales de l'utilisateur.
     */
    protected function initials(): Attribute
    {
        return Attribute::make(
            get: fn () => strtoupper(
                mb_substr($this->last_name ?? '', 0, 1) .
                mb_substr($this->first_name ?? '', 0, 1)
            ),
        );
    }

    /**
     * Récupère le nom complet.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->last_name} {$this->first_name}",
        );
    }
}
