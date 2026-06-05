<?php

namespace App\Enums;

enum DocumentType: int
{
    case COMPLAINT = 1;
    case ACKNOWLEDGMENT = 2;
    CASE EVALUATION = 3;
    CASE RESPONSE = 4;
    CASE INFO = 5;
    CASE REPORT = 6;

    /**
     * Retourne le nom usuel du type de document
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::COMPLAINT => 'Plainte',
            self::ACKNOWLEDGMENT => 'Accusé de réception',
            self::EVALUATION => 'Rapport d\'évaluation',
            self::RESPONSE => 'Réponse définitive',
            self::INFO => 'Informations complémentaires',
            self::REPORT => 'Rapport du traitement de la plainte',
        };
    }

    /**
     * Retourne un code du type de document pour générer le nom du document.
     * Le code est généré sur 2 caractères.
     *
     * @return string
     */
    public function code(): string
    {
        return match($this) {
            self::COMPLAINT => 'PL',
            self::ACKNOWLEDGMENT => 'AR',
            self::EVALUATION => 'EV',
            self::RESPONSE => 'RE',
            self::INFO => 'IF',
            self::REPORT => 'RT',
        };
    }

    /**
     * Retourne le nom de la vue à utiliser pour générer le document.
     *
     * @return string
     */
    public function bladeView(): string {
        return match($this) {
            self::ACKNOWLEDGMENT => 'documents.complaints.type.acknowledgment',
        };
    }

    /**
     * Prépare les données recues par le formulaire de génération (modale) pour la personnalisation du document
     * en fonction de son type
     *
     * @param array $raw
     * @return object|\Illuminate\Support\HigherOrderTapProxy
     */
    public function prepareData(array $raw): object
    {
        $data = (object) $raw;

        return match($this) {
            self::EVALUATION => tap($data, function ($d) {
                $d->admissible_label   = match((int) $d->admissible)   { 1 => 'recevable',   0 => 'irrecevable' };
                $d->well_founded_label = match((int) $d->well_founded) { 1 => 'fondée', 0 => 'non fondée' };
            }),

            // Pour les types sans transformation particulière
            default => $data,
        };
    }

}
