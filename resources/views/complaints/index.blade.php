@php
    use App\Enums\ComplaintStatus;
@endphp
@extends('layouts.app')

@section('title', 'Mes Plaintes')

@section('breadcrumb')
    <span class="breadcrumb-item active">Plaintes</span>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="custom-page-hero">
            <div class="hero-main-content">
                <div class="hero-text">
                    <h1>
                        <i class="bi bi-file-earmark-text-fill me-2"></i>
                        Mes Plaintes
                    </h1>
                    <p>
                        @if(auth()->user()->employee && auth()->user()->employee->department)
                            <span class="badge-dept">
                        <i class="bi bi-building me-1"></i>
                        {{ auth()->user()->employee->department->name }}
                    </span>
                        @else
                            Gérez et consultez toutes vos plaintes en temps réel
                        @endif
                    </p>
                </div>

                <div class="hero-actions">
                    @can('create', App\Models\Complaint::class)
                        <a href="{{ route('complaints.create') }}" class="btn-hero">
                            <i class="bi bi-plus-circle-fill me-2"></i>
                            Nouvelle plainte
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistics -->
        <div class="stats-row">
            @foreach($statsByStatus as $stat)
                <div class="stat-card shadow-sm border-0">
                    <div class="stat-icon {{ $stat['class'] }}">
                        <i class="bi {{ $stat['icon'] }}"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label text-muted small">{{ $stat['label'] }}</div>
                        <div class="stat-value fw-bold fs-4">{{ $stat['count'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Filtres (optionnel) -->
        <div class="filter-bar mb-3">
            <form method="GET" action="{{ route('complaints.index') }}" class="row g-3">
                <div class="col-md-3">
                    <select name="type" class="form-select">
                        <option value="">Tous les types de plaintes</option>
                        @foreach($complaintTypes as $type)
                            <option value="{{ $type->value }}" {{ request('type') == $type->value ? 'selected' : '' }}>
                                {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        @foreach($complaintStatuses as $status)
                            <option value="{{ $status->value }}"
                                {{ request('status') == $status->value ? 'selected' : '' }}>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="department" class="form-select">
                        <option value="">Tous les départements</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->department_id }}"
                                {{ request('department') == $department->department_id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                </div>
                @if(request()->hasAny(['status', 'department']))
                    <div class="col-md-1">
                        <a href="{{ route('complaints.index') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Complaints Table -->
        <div class="complaints-card">
            @if($complaints->count() > 0)
                <div class="table-container">
                    <table class="complaints-table">
                        <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Plaignant</th>
                            <th>Département</th>
                            <th>Date de la plainte</th>
                            <th>Responsable</th>
                            <th>Statut</th>
                            <th>Evolution</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($complaints as $complaint)
                            <tr>
                                <td>
                                    <span class="bg-light text-dark fs-6">
                                        {{ $complaint->complaint_reference ?? $complaint->complaint_id }}
                                    </span>
                                </td>
                                <td>
                                    <div class="complainant-info">
                                        @if($complaint->complainant)
                                            <div class=" complainant-avatar me-2 ">
                                                {{ $complaint->complainant->initials }}
                                            </div>
                                            <div class="complainant-details">
                                                <div class="complainant-name">
                                                    {{ $complaint->complainant->name }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Non renseigné</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($complaint->department)
                                        <span class="status-badge bg-badge-department">
                                                {{ $complaint->department->name }}
                                            </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="date-display">
                                        <div class="date-value fs-6">
                                            {{ $complaint->complaint_date ? $complaint->complaint_date->format('d/m/Y') : '-' }}
                                        </div>
                                        @if($complaint->complaint_date)
                                            <div class="date-relative">
                                                {{ $complaint->complaint_date->diffForHumans() }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    @if($complaint->employee)
                                        <div class="d-flex align-items-center">
                                            <div class="complainant-avatar me-2">
                                                {{ $complaint->employee->initials }}
                                            </div>
                                            <div class="complainant-details">
                                                <div class="complainant-name">
                                                    {{ $complaint->employee->name }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Non attribué</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($complaint->status)
                                        <span class="status-badge {{ $complaint->status->cssClass() }}">
                                            <i class="bi {{ $complaint->status->icon() }}"></i>
                                            {{ $complaint->status->label() }}
                                        </span>
                                    @else
                                        <span class="badge status-pending">
                                            <i class="bi bi-question-circle me-1"></i> Non défini
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        @foreach(ComplaintStatus::cases() as $status)
                                            <i class="bi {{ $status->icon() }} {{ $complaint->getStepColor($status) }} fs-4"
                                               title="{{ $complaint->getStepLabel($status) }}"></i>
                                        @endforeach
                                        @if($complaint->status === ComplaintStatus::REJECTED)
                                            <i class="bi bi-x-circle-fill text-danger fs-4" title="Rejeté"></i>
                                        @elseif($complaint->status === ComplaintStatus::CLOSED || $complaint->status === ComplaintStatus::RESPONDED)
                                            <i class="bi bi-check-all text-success fs-4" title="Terminé"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end">
                                    @can('view', $complaint)
                                        <a href="{{ route('complaints.show', $complaint) }}"
                                           class="btn-ifapme-accent btn-sm">
                                            <i class="bi bi-eye"></i>Voir
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h3 class="empty-state-title">Aucune plainte</h3>
                    <p class="empty-state-description">
                        @if(auth()->user()->employee && auth()->user()->employee->department)
                            Aucune plainte trouvée pour votre département.
                        @else
                            Vous n'avez pas encore de plainte enregistrée.
                        @endif
                    </p>
                    @can('create', App\Models\Complaint::class)
                        <a href="{{ route('complaints.create') }}" class="bt btn-ifapme-accent">
                            <i class="bi bi-plus-circle"></i>
                            Créer une nouvelle plainte
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/complaint.css') }}">
    <style>
        .filter-bar {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush
