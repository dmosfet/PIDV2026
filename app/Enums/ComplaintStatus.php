<?php

namespace App\Enums;

enum ComplaintStatus: int
{
    case NEW = 1;
    case ASSIGNED = 2;
    case ACKNOWLEDGED = 3;
    case EVALUATED = 4;

    case RESPONDED = 5;

    case CLOSED = 6;
    case REJECTED = 7;

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Nouvelle',
            self::ASSIGNED => 'Attribuée',
            self::ACKNOWLEDGED => 'Réceptionnée',
            self::EVALUATED => 'Évaluée',
            self::RESPONDED => 'Répondue',
            self::CLOSED => 'Clôturée',
            self::REJECTED => 'Rejetée',
        };
    }

    public function todoLabel(): string
    {
        return match ($this) {
            self::NEW => 'Créer',
            self::ASSIGNED => 'Attribuer',
            self::ACKNOWLEDGED => 'Accuser réception',
            self::EVALUATED => 'Évaluer',
            self::RESPONDED => 'Répondre',
            self::CLOSED => 'Clôturer',
            default => $this->label(),
        };
    }

    public function cssClass(): string
    {
        return match ($this) {
            self::NEW => 'status-badge-new',
            self::ACKNOWLEDGED => 'status-badge-acknowledged',
            self::EVALUATED => 'status-badge-evaluated',
            self::ASSIGNED => 'status-badge-assigned',
            self::RESPONDED => 'status-badge-responded',
            self::CLOSED => 'status-badge-closed',
            self::REJECTED => 'status-badge-rejected',
        };
    }


    public function icon(): string
    {
        return match ($this) {
            self::NEW => 'bi-file-earmark-plus',
            self::ACKNOWLEDGED => 'bi-envelope-check',
            self::EVALUATED => 'bi-clipboard-check',
            self::ASSIGNED => 'bi-person-check',
            self::RESPONDED => 'bi-chat-left-text',
            self::CLOSED => 'bi-check-circle',
            self::REJECTED => 'bi-x-circle',
        };
    }

    /**
     * Retourne les actions possibles depuis ce statut
     *
     * @return array<string> Noms des permissions/actions possibles
     */
    public function nextActions(): array
    {
        return match($this) {
            self::NEW          => ['assign_complaint'],
            self::ASSIGNED     => ['reassign_complaint','acknowledge_complaint'],
            self::ACKNOWLEDGED => ['evaluate_complaint'],
            self::EVALUATED    => ['respond_complaint'],
            self::RESPONDED    => ['close_complaint'],
            default            => [],
        };
    }

    /**
     * Permet de générer le Css du bouton d'action en fonction de l'action à réaliser. Texte à afficher, icone et couleur.
     *
     * @param string $action
     * @return string[]
     */
    public function getActionConfig(string $action): array
    {
        return match($action) {
            'assign_complaint'      => ['label' => 'Assigner', 'class' => "bg-workflow-assigned", 'icon' => 'bi-person-badge'],
            'reassign_complaint'    => ['label' => 'Réassigner','class' => "bg-workflow-assigned", 'icon' => 'bi-person-badge'],
            'acknowledge_complaint' => ['label' => 'Accuser réception', 'class' => "bg-workflow-acknowledged", 'icon' => 'bi-send-check'],
            'evaluate_complaint'    => ['label' => 'Évaluer', 'class' => "bg-workflow-evaluated", 'icon' => 'bi-clipboard2-check'],
            'respond_complaint'     => ['label' => 'Répondre', 'class' => "bg-workflow-responded", 'icon' => 'bi-chat-left-dots'],
            'close_complaint'       => ['label' => 'Clôturer', 'class' => "bg-workflow-closed", 'icon' => 'bi-archive'],
            'reject_complaint'      => ['label' => 'Rejeter', 'class' => "bg-workflow-rejected", 'icon' => 'bi-slash-circle'],
            default => ['label' => 'Action', 'class' => 'btn-secondary', 'icon' => 'bi-gear'],
        };
    }

    /**
     * Retourne la permission requise pour atteindre ce statut
     *
     * @return string|null
     */
    public function requiredPermission(): ?string
    {
        return match($this) {
            self::NEW => null, // Création de la plainte
            self::ASSIGNED => 'assign_complaint',
            self::ACKNOWLEDGED => 'acknowledge_complaint',
            self::EVALUATED => 'evaluate_complaint',
            self::RESPONDED => 'respond_complaint',
            self::CLOSED => 'close_complaint',
            self::REJECTED => 'evaluate_complaint',
        };
    }

    /**
     * Vérifie si ce statut est terminal (aucune action possible)
     *
     * @return bool
     */
    public function isTerminal(): bool
    {
        return in_array($this, [self::CLOSED, self::REJECTED]);
    }

    /**
     * Vérifie si ce statut permet encore des modifications
     *
     * @return bool
     */
    public function isEditable(): bool
    {
        return in_array($this, [self::NEW, self::ASSIGNED]);
    }

     /**
     * Vérifie si on peut passer à un statut donné
     *
     * @param self $targetStatus
     * @return bool
     */
    public function canTransitionTo(self $targetStatus): bool
    {
        // Les statuts terminaux ne peuvent plus transitionner
        if ($this->isTerminal()) {
            return false;
        }

        // Transitions autorisées
        return match($this) {
            self::NEW          => $targetStatus === self::ASSIGNED,
            self::ASSIGNED     => $targetStatus === self::ACKNOWLEDGED,
            self::ACKNOWLEDGED => $targetStatus === self::EVALUATED,
            self::EVALUATED    => in_array($targetStatus, [self::RESPONDED, self::REJECTED]),
            self::RESPONDED    => $targetStatus === self::CLOSED,
            default            => false,
        };
    }

    /**
     * Retourne tous les statuts comme options pour un select
     *
     * @return array<int, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($status) => [$status->value => $status->label()])
            ->toArray();
    }

    /**
     * Retourne une description détaillée du statut
     *
     * @return string
     */
    public function description(): string
    {
        return match($this) {
            self::NEW => 'La plainte vient d\'être créée et attend d\'être assignée',
            self::ASSIGNED => 'La plainte a été attribuée à un responsable de traitement',
            self::ACKNOWLEDGED => 'Un accusé de réception a été envoyé au plaignant',
            self::EVALUATED => 'La plainte a été évaluée et jugée recevable',
            self::RESPONDED => 'Une réponse a été apportée à la plainte',
            self::REJECTED => 'La plainte a été jugée non recevable',
            self::CLOSED => 'La plainte est clôturée',
        };
    }

    public function logDescription(): string
    {
        return match($this) {
            self::NEW          => 'Création de la plainte',
            self::ASSIGNED     => 'Assignation de la plainte à un service',
            self::ACKNOWLEDGED => 'Accusé de réception de la plainte',
            self::EVALUATED    => 'Évaluation juridique terminée',
            self::RESPONDED    => 'Réponse transmise au plaignant',
            self::REJECTED     => 'Rejet de la plainte',
            self::CLOSED       => 'Clôture définitive du dossier',
        };
    }

}
