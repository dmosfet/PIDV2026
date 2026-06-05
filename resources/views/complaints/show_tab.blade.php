@php
    use App\Enums\ComplaintStatus;
    use App\Enums\ComplaintType;
@endphp

@extends('layouts.app')

@section('title', 'Détail de la Plainte #' . $complaint->complaint_id)

@section('breadcrumb')
    <a href="{{ route('complaints.index') }}" class="breadcrumb-link">Plaintes</a>
    <i class="bi bi-chevron-right"></i>
    <span
        class="breadcrumb-item active">Plainte: {{ $complaint->complaint_reference ?? $complaint->complaint_id }}</span>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-top">
                <div>
                    <h1 class="page-title">
                        @if($complaint->complaint_reference)
                            <span class="text-muted fw-light">Dossier :</span> {{ $complaint->complaint_reference }}
                        @else
                            {{ $complaint->complaint_type_id->label() }} sans référence: numéro
                            <span class="complaint-id">#{{ $complaint->complaint_id }}</span>
                        @endif
                        @if($complaint->complaint_type_id === ComplaintType::RECOURS && $complaint->appeal_about_id)
                            <span class="text-muted fw-light">
                                <i class="bi bi-link-45deg"></i>Plainte:
                            </span>
                        @endif

                    </h1>
                    @if ($complaint->status)
                        <span class="status-banner {{ $complaint->status->cssClass() }}">
                            <i class="bi {{ $complaint->status->icon() }}"></i>
                        {{ $complaint->status->label() }}
                        </span>
                    @endif
                </div>
                {{--Alerte visuelle sur le délai du traitement--}}
                <div>
                    @if($complaint->alert_level !== 'none')
                        <div class="alert alert-{{ $complaint->alert_level }} d-flex align-items-center border-0 shadow-sm mb-4">
                            <i class="bi {{ $complaint->alert_icon }} me-2"></i>
                            <span>{{ $complaint->deadline_label }}</span>
                        </div>
                    @endif
                </div>
            </div>
            @include('complaints.partials._workflow_actions')
        </div>

        <ul class="nav complaint-tabs" id="complaintTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details"
                        type="button">Détails
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="workflow-tab" data-bs-toggle="tab" data-bs-target="#workflows"
                        type="button">
                    Traitement
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs" type="button">
                    Historique
                </button>
            </li>
        </ul>

        <div class="tab-content" id="complaintTabContent">

            <div class="tab-pane fade show active" id="details" role="tabpanel">
                <div class="details-grid">
                    @include('complaints.partials.details')
                </div>
            </div>
            <div class="tab-pane fade" id="workflows" role="tabpanel">
                <div class="details-grid">
                    @include('complaints.partials.workflows')
                </div>
            </div>

            <div class="tab-pane fade" id="logs" role="tabpanel">
                <div class="details-grid">
                    @include('complaints.partials.logs')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/complaintdetails.css') }}">
@endpush
