@extends('layouts.app')

@section('title', 'Encoder une plainte')

@section('breadcrumb')
    <a href="{{ route('complaints.index') }}" class="text-decoration-none">Plaintes</a>
    <i class="bi bi-chevron-right mx-2 text-muted" style="font-size: 0.8rem;"></i>
    <span>Encoder un nouveau dossier</span>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="custom-page-hero">
            <div class="hero-main-content">
                <div class="hero-text">
                    <h1>
                        <i class="bi bi-file-earmark-plus-fill me-3"></i>
                        Encoder une nouvelle Plainte
                    </h1>
                    <p>
                        Remplissez les informations ci-dessous pour initialiser le dossier. Les champs marqués d'un
                        <span class="fw-bold text-white">*</span> sont obligatoires.
                    </p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <h6 class="fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Des erreurs ont été détectées :</h6>
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('complaints.store') }}" method="POST">
            @csrf

            <x-complaint.info
                :allowedComplaintTypes="$complaintTypes"
                :channels="$channels"
            />

            <x-complaint.stakeholders/>

            <x-complaint.subject
                :objectCategories="$objectCategories"
            />

            <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 shadow-sm mb-5">
                <a href="{{ route('complaints.index') }}"
                   class="btn btn-outline-secondary d-flex align-items-center justify-content-center fw-semibold"
                   style="padding: 14px; border-radius: 10px; gap: 10px;">
                    <i class="bi bi-x-circle"></i>
                    Annuler
                </a>

                <button type="submit" class="btn-ifapme-accent border-0 px-5">
                    <i class="bi bi-check-circle"></i>
                    Enregistrer la Plainte
                </button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/complaint.css') }}">
@endpush
