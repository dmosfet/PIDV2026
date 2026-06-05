@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
<div class="auth-container two-columns">

    <!-- Partie gauche -->
    <div class="auth-left">
        <div class="logo-section">
            <div class="logo">
                <div class="logo-icon">I</div>
                IFAPME
            </div>
        </div>

        <h2>Plateforme de Gestion des Plaintes</h2>
        <p>Accédez à votre espace sécurisé pour gérer et suivre les plaintes au sein du réseau IFAPME.</p>

        <ul class="features">
            <li>Suivi en temps réel des plaintes</li>
            <li>Communication transparente</li>
            <li>Interface intuitive et sécurisée</li>
            <li>Gestion centralisée des dossiers</li>
        </ul>
    </div>

    <!-- Partie droite -->
    <div class="auth-right">
        <div class="form-header">
            <h1>Connexion</h1>
            <p>Veuillez vous identifier pour accéder à votre espace</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <!-- Boutons d'actions' -->
        <div class="auth-actions">
            <a href="{{ route('login') }}" class="btn-auth">Se connecter</a>
            <a href="/admin" class="btn-auth">Accès administrateur</a>
        </div>

    </div>
</div>

@endsection
