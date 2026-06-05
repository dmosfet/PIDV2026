@extends('layouts.app')

@section('title', 'Accueil')

@section('breadcrumb')
    <span>Accueil</span>
@endsection

@section('content')
    <div class="home-page">
        <!-- Welcome Hero -->
        <div class="home-hero">
            <div class="hero-content">
                <h1>Bienvenue sur votre espace IFAPME</h1>
                <p class="hero-subtitle">Gérez vos plaintes et suivez leur traitement en toute transparence</p>
            </div>
        </div>

        <!-- Quick Start Section -->
        <div class="quick-start-section">
            <h2 class="section-title">
                <i class="bi bi-rocket-takeoff"></i>
                Démarrage rapide
            </h2>

            <div class="quick-start-grid">
                <div class="quick-start-card card-primary">
                    <div class="card-number">1</div>
                    <div class="card-icon">
                        <i class="bi bi-file-earmark-plus"></i>
                    </div>
                    <h3>Créer une plainte</h3>
                    <p>Décrivez votre problème et fournissez les informations nécessaires</p>
                    <a href="{{ route('complaints.create') }}" class="card-action">
                        Commencer <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="quick-start-card">
                    <div class="card-number">2</div>
                    <div class="card-icon">
                        <i class="bi bi-eye"></i>
                    </div>
                    <h3>Suivre le traitement</h3>
                    <p>Recevez des notifications et consultez l'état d'avancement</p>
                    <a href="{{ route('complaints.index') }}" class="card-action">
                        Voir mes plaintes <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="quick-start-card">
                    <div class="card-number">3</div>
                    <div class="card-icon">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h3>Échanger</h3>
                    <p>Communiquez avec l'équipe et obtenez des réponses</p>
                    <a href="{{ route('notifications') }}" class="card-action">
                        Notifications <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Overview Cards -->
        <div class="overview-section">
            <div class="overview-grid">
                <div class="overview-card">
                    <div class="overview-header">
                        <h3>
                            <i class="bi bi-file-earmark-text"></i>
                            Mes plaintes récentes (-15 j)
                        </h3>
                        <a href="{{ route('complaints.index') }}" class="view-all">Tout voir</a>
                    </div>
                    <div class="row">
                        @if($recentComplaints->isEmpty())
                            {{-- SCÉNARIO : AUCUNE PLAINTE RÉCENTE --}}
                            <div class="col-12 mb-4">
                                <div class="p-5 bg-light rounded text-center border">
                                    <i class="bi bi-clock-history fs-1 text-muted mb-3"></i>
                                    <h5 class="text-muted">Aucune plainte récente (15 derniers jours)</h5>
                                    <a href="{{ route('complaints.index') }}" class="btn btn-outline-secondary mt-3">
                                        <i class="bi bi-collection-fill me-2"></i>Consulter vos plaintes
                                    </a>
                                </div>
                            </div>
                        @else
                            {{-- SCÉNARIO : AFFICHAGE DES CARTES (MAX 4 COLONNES) --}}

                            @php
                                $totalCount = $recentComplaints->count();
                                // On prend les 4 premières (si la 4ème n'est pas un "Voir plus")
                                // ou les 3 premières (si on doit mettre un "Voir plus" en 4ème)
                                $displayLimit = ($totalCount > 4) ? 3 : 4;
                                $itemsToShow = $recentComplaints->take($displayLimit);
                            @endphp

                            {{-- 1. Affichage des plaintes réelles --}}
                            @foreach($itemsToShow as $complaint)
                                <div class="col-md-3 mb-4">
                                    <div class="overview-stats {{ $complaint->status->cssClass() }} p-4 shadow-sm rounded border-0 d-flex flex-column align-items-center text-center h-100 position-relative">
                                        <div class="mb-3">
                                            <i class="bi {{ $complaint->status->icon() }} fs-1 d-block mb-2"></i>
                                            <span class="fw-bold text-uppercase small">{{ $complaint->complaint_reference }}</span>
                                        </div>
                                        <div class="flex-grow-1 d-flex flex-column justify-content-center">
                                            <h6 class="mb-1 fw-bold">{{ Str::limit($complaint->complainant?->name, 20) }}</h6>
                                            <div class="small opacity-75">{{ $complaint->complaint_date->diffForHumans() }}</div>
                                        </div>
                                        <a href="{{ route('complaints.show', $complaint->complaint_id) }}" class="stretched-link"></a>
                                    </div>
                                </div>
                            @endforeach

                            {{-- 2. Gestion de la 4ème colonne ou du complément --}}
                            @if($totalCount > 4)
                                {{-- Cas spécifique : On a plus de 4 plaintes, la 4ème est forcément "Voir plus" --}}
                                <div class="col-md-3 mb-4">
                                    <a href="{{ route('complaints.index') }}" class="text-decoration-none h-100">
                                        <div class="overview-stats bg-light border p-4 shadow-sm rounded d-flex flex-column align-items-center justify-content-center text-center h-100 text-muted">
                                            <i class="bi bi-plus-circle fs-1 mb-2"></i>
                                            <span class="fw-bold">Voir les {{ $totalCount - 3 }} autres</span>
                                        </div>
                                    </a>
                                </div>
                            @else
                                {{-- Cas où on a entre 1 et 4 plaintes : on complète jusqu'à 4 avec des cases "Disponible" --}}
                                @for ($i = 0; $i < (4 - $totalCount); $i++)
                                    <div class="col-md-3 mb-4 d-none d-md-block">
                                        <div class="overview-stats bg-white border border-dashed p-4 rounded d-flex flex-column align-items-center justify-content-center text-center h-100 text-muted opacity-50" style="border-style: dashed !important;">
                                            <i class="bi bi-plus-lg fs-1 mb-2 opacity-25"></i>
                                            <span class="small fw-bold text-uppercase">Disponible</span>
                                        </div>
                                    </div>
                                @endfor
                            @endif
                        @endif
                    </div>
                    @can('create', \App\Models\Complaint::class)
                        {{-- Bouton Action Principale --}}
                        <a href="{{ route('complaints.create') }}" class="overview-cta">
                            <i class="bi bi-plus-circle"></i>
                            Créer une nouvelle plainte
                        </a>
                    @else
                        {{-- Bouton de Redirection (Alternative) --}}
                        <a href="{{ route('complaints.index') }}" class="overview-cta">
                            <i class="bi bi-collection-fill me-2"></i>
                            Consulter vos plaintes
                        </a>
                    @endcan

                </div>

                <div class="overview-card">
                    <div class="overview-header">
                        <h3>
                            <i class="bi bi-bell"></i>
                            Notifications récentes
                        </h3>
                        <a href="{{ route('notifications') }}" class="view-all">Tout voir</a>
                    </div>
                    <div class="notification-list">
                        @foreach ($notifications as $notification)
                            <div class="notification-item">
                                <div class="notif-icon notif-success">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="notif-content">
                                    <div class="notif-title"></div>
                                    <div class="notif-time"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="help-section">
            <div class="help-banner">
                <div class="help-content">
                    <i class="bi bi-question-circle-fill"></i>
                    <div>
                        <h3>Besoin d'aide pour démarrer ?</h3>
                        <p>Consultez notre guide ou contactez le support</p>
                    </div>
                </div>
                <div class="help-actions">
                    <a href="{{ route('help') }}" class="btn btn-outline-light">
                        <i class="bi bi-book"></i>
                        Guide d'utilisation
                    </a>
                    <a href="mailto:support@ifapme.be" class="btn btn-light">
                        <i class="bi bi-envelope"></i>
                        Contacter le support
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush
