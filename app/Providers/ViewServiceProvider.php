<?php

namespace App\Providers;

use App\Enums\ObjectCategory;
use App\Models\Department;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Ajoute automatiquement les plaintes éligibles à être liées à un recours lorsque je génère la modale.
        view()->composer('complaints.modals.appeal_complaint', function ($view) {
            $complaint = $view->getData()['complaint'] ?? null;

            $eligibleComplaints = null;
            if ($complaint && $complaint->complaint_type_id === \App\Enums\ComplaintType::RECOURS
                && is_null($complaint->appeal_about_id)) {
                $eligibleComplaints = \App\Models\Complaint::getEligibleComplaintsForAppeals();
            }

            $view->with('eligibleComplaints', $eligibleComplaints);
        });

        // On ajoute la table département avec sa relation employee (manager) lorsqu'on génère la vue du formulaire d'assignation
        view()->composer('complaints.modals.assign_complaint', function ($view) {
            $departments = Department::with('employee')
                ->orderBy('name', 'asc')
                ->get()
                ->groupBy(fn($dept) => $dept->department_type_id?->label() ?? 'Autre');

            $view->with('departments', $departments);
        });

    }
}
