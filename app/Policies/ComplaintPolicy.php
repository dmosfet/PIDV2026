<?php

namespace App\Policies;

use App\Enums\ComplaintStatus;
use App\Enums\ComplaintType;
use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    /**
     * Permet de vérifier si l'utilisateur peut afficher les plaintes.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // On vérifie juste si l'utilisateur a la permission générale
        // Le Global Scope s'occupera de filtrer les données dans la requête SQL
        return $user->hasPermission('view_complaints');
    }

    /**
     * Permet de vérifier si l'utilisateur connecté à l'autorisation de visualiser une plainte.
     *
     * @param User $user L'utilisateur pour lequel on vérifie ses permissions.
     * @param Complaint $complaint La plainte à consulter.
     * @return bool
     */
    public function view(User $user, Complaint $complaint): bool
    {
        // 1. Admin : accès total
        if ($user->hasRole('admin')) return true;

        // 2. Permission de base
        if (!$user->hasPermission('view_complaints')) return false;

        // 5. Récupération de l'employé
        $employee = $user->employee;
        if (!$employee || !$employee->department_id) return false;

        $userDeptId = $employee->department_id;

        // 7. LOGIQUE DÉPARTEMENT
        // Département responsable du traitement
        if ($complaint->department_id == $userDeptId) return true;

        // Département auteur : limité selon le type
        if ($complaint->created_by_department == $userDeptId) {
            // Plainte classique : NEW et ASSIGNED seulement
            if ($complaint->complaint_type_id !== \App\Enums\ComplaintType::RECOURS) {
                return in_array($complaint->status, [
                    \App\Enums\ComplaintStatus::NEW,
                    \App\Enums\ComplaintStatus::ASSIGNED,
                ]);
            }
            // Recours : tout sauf CLOSED
            if ($complaint->complaint_type_id == \App\Enums\ComplaintType::RECOURS) {
                return $complaint->status != \App\Enums\ComplaintStatus::CLOSED;
            }
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user) {
        return $user->hasRole('admin') || $user->hasPermission('create_complaint');
    }

    /**
     * Permet de vérifier si l'utilisateur peut mettre à jour une plainte.
     *
     * @param User $user L'utilisateur pour lequel on vérifie ses permissions.
     * @param Complaint $complaint La plainte qui doit être mise à jour.
     * @return bool
     */
    public function update(User $user, Complaint $complaint): bool
    {
        // 1. Sécurité de base : Permission requise
        if (!$user->hasPermission('edit_complaint')) {
            return false;
        }

        // 2. Logique de Département : Doit pouvoir voir la plainte pour la modifier
        if (!$this->view($user, $complaint)) {
            return false;
        }

        // 3. Ne peut pas modifier une plainte clôturée ou rejetée
        if ($complaint->status->isTerminal() && !$user->isAdmin()) {
            return false;
        }

        return true;
    }

    /**
     * Permet de vérifier si un utilisateur peut supprimer une plainte.
     *
     * @param User $user L'utilisateur pour lequel on vérifie ses permissions.
     * @param Complaint $complaint
     * @return bool
     */
    public function delete(User $user, Complaint $complaint): bool
    {
        if ($user->isAdmin()) return true;

        // On peut pas supprimer une plainte qui est répondue ou rejetée
        if ($complaint->status->isTerminal() && !$user->isAdmin()) {
            return false;
        }

        return $user->hasAnyRole( ['boa_sec', 'boa_pres']) && $user->hasPermission('delete_complaint');
    }


    /**
     * Permet de vérifier si l'utilisateur a l'autorisation de réaliser une action dans le cadre du workflow d'une plainte.
     *
     * @param User $user L'utilisateur pour lequel on vérifie ses permissions.
     * @param Complaint $complaint Le contexte de l'action.
     * @param string $action L'action à réaliser.
     * @return bool
     */
    public function executeTask(User $user, Complaint $complaint, string $action): bool
    {
        // --- Logique de base ---
        // Il faut que l'action existe dans la liste des actions qui suivent le status actuel
        if (!in_array($action, $complaint->status->nextActions())) return false;

        // Il faut la permission de réaliser l'action ou être admin
        if (!$user->hasPermission($action)) return false;
        if ($user->hasRole('admin')) return true;


        // --- CONTEXTE ---
        $isAssignee  = $complaint->employee_id === $user->employee->employee_id;
        $isCreator   = $complaint->created_by_user === $user->user_id;

        // Rôles et Périmètres

        $isSameDept  = $user->employee->department_id === $complaint->department_id;
        $isSec       = $user->hasRole('secretariat');

        return match ($action) {

            // Aiguillage : Créateur ou Secrétariat du département
            'assign_complaint', 'reassign_complaint' =>
                $isCreator || ($isSec && $isSameDept),

            // Réception (Accusé) :
            // - Plainte : Secrétariat du département
            // - Recours : Staff BOA (peu importe l'assignation à ce stade)
            'acknowledge_complaint' => $isSec && $isSameDept,


            // Traitement (Évaluation / Rejet / Réponse) :
            // - Plainte : Uniquement le Manager assigné
            // - Recours : Staff BOA assigné (Exception : boa_sec peut agir pour boa_pres)
            'evaluate_complaint', 'respond_complaint', 'reject_complaint' =>  $isAssignee,

            // Clôture
            'close_complaint' => $isAssignee,

            default => false,
        };
    }

}
