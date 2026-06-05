<?php

namespace App\Http\Controllers\Complaint;

use App\Enums\ComplaintStatus;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComplaintWorkflowController extends Controller
{

    /**
     * @param Request $request
     * @param Complaint $complaint
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assign(Request $request, Complaint $complaint)
    {
        // On passe un flag pour mettre à jour le statut uniquement à l'assignation initiale
        return $this->assignation($request, $complaint, true);
    }

    /**
     * @param Request $request
     * @param Complaint $complaint
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reassign(Request $request, Complaint $complaint)
    {
        // Pour une réassignation, on ne touche pas forcément au statut
        return $this->assignation($request, $complaint, false);
    }


    /**
     * Accuser réception
     */
    public function acknowledge(Request $request, Complaint $complaint)
    {
        // La validation et l'autorisation (Gate) sont gérées par la Request ci-dessus

        $complaint->update([
            'acknowledgment_date' => $request->acknowledgment_date,
            'status'              => ComplaintStatus::ACKNOWLEDGED->value,
        ]);

        return redirect()
            ->route('complaints.show', $complaint)
            ->with('success', 'Accusé de réception enregistré avec succès.');
    }

    /**
     * Évaluer la plainte
     */
    public function evaluate(Request $request, Complaint $complaint)
    {
        // Le statut dépend uniquement de l'admissibilité
        $newStatus = $request->boolean('admissible')
            ? ComplaintStatus::EVALUATED
            : ComplaintStatus::REJECTED;

        $complaint->update([
            'evaluation_date' => $request->evaluation_date,
            'admissible'      => $request->admissible,
            'well_founded'    => $request->boolean('admissible') ? $request->well_founded : null,
            'status'          => $newStatus->value,
        ]);

        return redirect()
            ->route('complaints.show', $complaint)
            ->with('success', 'Évaluation enregistrée avec succès.');
    }

    /**
     * Répondre à la plainte
     */
    public function respond(Request $request, Complaint $complaint)
    {
        $complaint->update([
            'response'      => $request->response,
            'response_date' => $request->response_date,
            'status'        => ComplaintStatus::RESPONDED->value,
            'responded_by'  => $request->user()->employee_id,
        ]);

        return redirect()
            ->route('complaints.show', $complaint)
            ->with('success', 'Réponse enregistrée avec succès.');
    }

    /**
     * Clôturer la plainte
     */
    public function close(Request $request, Complaint $complaint)
    {
        // Vérifier que l'utilisateur peut effectuer cette action
        if ($request->user()->cannot('executeTask',[$complaint, 'close_complaint'])) {
            abort(403, 'Vous ne pouvez pas clôturer cette plainte');
        }

        $complaint->update([
            'status' => ComplaintStatus::CLOSED->value,
            'closed_at' => now(),
            'closed_by' => $request->user()->employee_id,
        ]);

        return redirect()
            ->route('complaints.show', $complaint->complaint_id)
            ->with('success', 'Plainte clôturée avec succès.');
    }

    /**
     * @param Request $request
     * @param Complaint $complaint
     * @param bool $updateStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    private function assignation(Request $request, Complaint $complaint, bool $updateStatus)
    {
        // Sur base du formulaire validée par le AssignComplaintRequest
        $department = Department::findOrFail($request->department_id);

        // Capture des anciennes valeurs AVANT la mise à jour (pour la réassignation)
        $previousDepartmentId = $complaint->department_id;
        $previousEmployeeId   = $complaint->employee_id;


        $data = [
            'department_id'     => $request->department_id,
            'employee_id'       => $department->manager_id,
            'transmission_date' => $request->transmission_date,
        ];

        // Si c'est la première assignation, on change le statut de la plainte
        if ($updateStatus) {
            $data['status'] = ComplaintStatus::ASSIGNED->value;
        }

        $complaint->update($data);

        // Vérification de la visibilité (Global Scope)
        $isStillVisible = Complaint::where('complaint_id', $complaint->complaint_id)->exists();
        $message = "Plainte attribuée avec succès au département : {$department->name}.";

        if ($isStillVisible) {
            return redirect()->route('complaints.show', $complaint->complaint_id)->with('success', $message);
        }

        return redirect()->route('complaints.index')
            ->with('success', $message . " Elle n'est désormais plus visible dans votre interface.");
    }

}
