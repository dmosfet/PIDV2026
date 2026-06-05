<?php

namespace App\Models\Scopes;

use App\Enums\ComplaintStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class DepartmentFilterScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (app()->runningInConsole() || !Auth::check()) return;

        $user = Auth::user();
        if ($user->hasRole('admin')) return;

        $employee = $user->employee;
        if (!$employee || !$employee->department_id) {
            $builder->whereRaw('1 = 0');
            return;
        }

        $deptId = $employee->department_id;

        $filterLogic = function ($query) use ($deptId, $employee, $user) {

            $query->where(function($q) use ($deptId, $employee, $user) {

                // --- LOGIQUE DÉPARTEMENT (utilisateurs classiques + dispatcher) ---
                    $q->Where(function ($dept) use ($deptId) {
                        $dept->where('complaints.department_id', $deptId)
                            ->orWhere(function ($created) use ($deptId) {
                                $created->where('complaints.created_by_department', $deptId)
                                    ->where(function ($statusFilter) {
                                        $statusFilter
                                            ->where(function ($classic) {
                                                $classic->where('complaints.complaint_type_id', '!=', \App\Enums\ComplaintType::RECOURS)
                                                    ->whereIn('complaints.status', [
                                                        \App\Enums\ComplaintStatus::NEW,
                                                        \App\Enums\ComplaintStatus::ASSIGNED,
                                                        \App\Enums\ComplaintStatus::ACKNOWLEDGED,
                                                    ]);
                                            });
                                    });
                            });
                    });
            });
        };

        // --- APPLICATION ---
        if ($model instanceof \App\Models\Complaint) {
            $builder->where($filterLogic);
        }
    }

}
