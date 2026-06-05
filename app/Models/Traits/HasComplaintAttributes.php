<?php

namespace App\Models\Traits;

use App\Enums\ComplaintStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

trait HasComplaintAttributes
{
    const DEADLINE_TOTAL_DAYS = 30;
    const DEADLINE_WARNING_DAYS = 15;
    const DEADLINE_DANGER_DAYS = 7;
    /**
     * Calcul la date la plus récente parmis les dates utilisées par le workflow. Elle sera la date de référence 'min'
     * pour les formulaires
     *
     * @return Attribute
     */
    protected function minDate(): Attribute
    {
        return Attribute::get(function () {
            $latestDate = collect([
                $this->created_at,
                $this->reception_date,
                $this->transmission_date,
                $this->acknowledgment_date,
                $this->evaluation_date,
            ])->filter()->max();

            return $latestDate ? $latestDate->format('Y-m-d') : now()->format('Y-m-d');
        });
    }

    /**
     * Calcul une deadline pour terminer la gestion de plainte basée sur la date de réception.
     *
     * @return Attribute
     */
    protected function deadlineDays(): Attribute
    {
        return Attribute::get(function () {
            if (!$this->acknowledgment_date) return 0;

            $deadline = $this->acknowledgment_date->copy()->addDays(self::DEADLINE_TOTAL_DAYS);

            if (in_array($this->status, [ComplaintStatus::RESPONDED, ComplaintStatus::CLOSED])) {
                return 0;
            }

            return (int) now()->diffInDays($deadline, false);
        });
    }

    /**
     * Défini un niveau d'urgence en fonction du nombre de jours restants pour traiter la plainte
     *
     * @return Attribute
     */
    protected function alertLevel(): Attribute
    {
        return Attribute::get(function () {
            if (in_array($this->status, [ComplaintStatus::NEW, ComplaintStatus::ASSIGNED, ComplaintStatus::RESPONDED, ComplaintStatus::CLOSED])) {
                return 'none';
            }

            $remaining = $this->deadline_days;

            return match(true) {
                $remaining <= self::DEADLINE_DANGER_DAYS  => 'danger',
                $remaining <= self::DEADLINE_WARNING_DAYS => 'warning',
                $remaining <= self::DEADLINE_TOTAL_DAYS => 'success',
                default          => 'info',
            };
        });
    }

    /**
     * Défini si on affiche un nombre de jours positifs ou négatifs
     *
     * @return Attribute
     */
    protected function deadlineLabel(): Attribute
    {
        return Attribute::get(function () {
            $days = $this->deadline_days;
            return $days < 0 ? "J+" . abs($days) : "J-" . $days;
        });
    }

    /**
     * Défini l'icône Bootstrap à utiliser lors de l'affichage du délai
     *
     * @return Attribute
     */
    protected function alertIcon(): Attribute
    {
        return Attribute::get(fn () => match($this->alert_level) {
            'danger'  => 'bi-exclamation-octagon-fill',
            'warning' => 'bi-exclamation-triangle-fill',
            'success' => 'bi-check-circle-fill',
            default   => 'bi-info-circle-fill',
        });
    }
}
