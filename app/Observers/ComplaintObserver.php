<?php

namespace App\Observers;

use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Models\Department;
use Illuminate\Support\Facades\Cache;

class ComplaintObserver
{

    /**
     * Gère l'événement "creating" (avant l'insertion en BDD).
     * On fige l'auteur et le département d'origine.
     */
    public function creating(Complaint $complaint): void
    {
        $user = auth()->user();

        if (!$user) return;

        $complaint->created_by_user = $user->user_id;
        $complaint->created_by_department = $user->employee?->department_id
            ?? Cache::rememberForever('default_department_id', fn() => Department::where('code', 'AGE')->value('department_id')
            );
    }

    /**
     * Actions réalisées avant l'update de la plainte
     * @param Complaint $complaint
     * @return void
     */
    public function updating(Complaint $complaint): void
    {
        // Si le département est assigné. (La référence est écrasée en cas d'existant)
        if ($complaint->isDirty('department_id')) {

            $complaint->complaint_reference = $this->generateComplaintReference($complaint);
        }
    }

    /**
     * @param Complaint $complaint
     * @return string
     */
    private function generateComplaintReference(Complaint $complaint): string
    {
        // 1. On récupère le code du type de plainte
        $cmpltCode = $complaint->complaint_type_id->code();

        // 2. On récupère le code du département
        $deptId = $complaint->department_id;
        $department = Department::withoutGlobalScopes()->find($deptId);
        $deptCode = $department->code ?? 'GEN';

        // 3. On récupère l'année civile de réception
        $year = ($complaint->complaint_date ?? $complaint->reception_date)->format('Y');

        // 4. Calcul du numéro de dossier sur 4 chiffres
        // On compte les plaintes du MÊME département pour la MÊME année ayant DÉJÀ une référence
        $count = Complaint::withoutGlobalScopes()
            ->where('department_id', $deptId)
            ->whereYear('reception_date', $year)
            ->whereNotNull('complaint_reference')
            ->where('complaint_id', '!=', $complaint->complaint_id)
            ->count();

        $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        // Résultat : TYPE/DEPT/2026/0001
        return "{$cmpltCode}/{$deptCode}/{$year}/{$sequence}";
    }
}
