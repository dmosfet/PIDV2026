@extends('layouts.app')

@section('title', 'Gestion des plaintes')

@section('breadcrumb')
    <span>Encoder une plainte</span>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-plus-circle-fill me-2" style="color: #3b82f6;"></i>
                Modifier une Plainte
            </h1>
            <p class="page-description">
                Remplissez le formulaire ci-dessous pour compléter le dossier de plainte nouvelle plainte. Les champs marqués d'un astérisque (*) sont obligatoires.
            </p>
        </div>

        <!-- Errors Display -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <h6 class="mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Des erreurs ont été détectées :</h6>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('complaints.update', $complaint) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Informations Générales -->
            <div class="form-card">
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="bi bi-info-circle me-2"></i>
                        Informations Générales
                    </h2>
                    <p class="section-subtitle">Informations de base sur la plainte</p>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="reception_date" class="form-label required">Date de Réception</label>
                            <input type="date"
                                   class="form-control @error('reception_date') is-invalid @enderror"
                                   id="reception_date"
                                   name="reception_date"
                                   value="{{ old('reception_date', $complaint->reception_date?->format('Y-m-d')) }}"
                                   required>
                            <div class="form-text">Date à laquelle la plainte a été reçue</div>
                            @error('reception_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="complaint_type_id" class="form-label required">Type de plainte</label>
                            <select class="form-select @error('complaint_type_id') is-invalid @enderror"
                                    id="complaint_type_id"
                                    name="complaint_type_id"
                                    required>
                                @foreach($complaintTypes as $complaint_type)
                                    <option value="{{ $complaint_type->value }}" {{ old('complaint_type_id', $complaint->complaint_type_id->value) == $complaint_type->value ? 'selected' : '' }}>
                                        {{ $complaint_type->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="channel_id" class="form-label required">Canal de Réception</label>
                            <select class="form-select @error('channel_id') is-invalid @enderror"
                                    id="channel_id"
                                    name="channel_id"
                                    required>
                                @foreach($channels as $channel)
                                    <option value="{{ $channel->value }}" {{ old('channel_id') == $channel->value? 'selected' : '' }}>
                                        {{ $channel->label() }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Par quel moyen la plainte a-t-elle été reçue ?</div>
                            @error('channel_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="category_id" class="form-label required">Catégorie</label>
                            <select class="form-select @error('category_id') is-invalid @enderror"
                                    id="category_id"
                                    name="category_id"
                                    required>
                                @foreach($object_categories as $category)
                                    <option value="{{ $category->value }}" {{ old('category_id') == $category->value ? 'selected' : '' }}>
                                        {{ $category->label() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Parties Concernées -->
            <div class="form-card">
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="bi bi-people me-2"></i>
                        Parties Concernées
                    </h2>
                    <p class="section-subtitle">Informations sur le plaignant et les parties impliquées</p>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="complainant_lastname" class="form-label required">Nom du Plaignant</label>
                            <input type="text"
                                   class="form-control @error('complainant_lastname') is-invalid @enderror"
                                   id="complainant_lastname"
                                   name="complainant_lastname"
                                   value="{{ old('complainant_lastname', $complaint->complainant->last_name) }}"
                                   placeholder="Ex: Dupont"
                                   required>
                            <div class="form-text">Le plaignant sera créé s'il n'existe pas encore</div>
                            @error('complainant_lastname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="complainant_firstname" class="form-label required">Prénom du Plaignant</label>
                            <input type="text"
                                   class="form-control @error('complainant_firstname') is-invalid @enderror"
                                   id="complainant_firstname"
                                   name="complainant_firstname"
                                   value="{{ old('complainant_firstname', $complaint->complainant->first_name) }}"
                                   placeholder="Ex: Jean"
                                   required>
                            <div class="form-text">Le plaignant sera créé s'il n'existe pas encore</div>
                            @error('complainant_firstname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="complainant_email" class="form-label required">Email du Plaignant</label>
                            <input type="email"
                                   class="form-control @error('complainant_email') is-invalid @enderror"
                                   id="complainant_email"
                                   name="complainant_email"
                                   value="{{ old('complainant_email', $complaint->complainant->email) }}"
                                   placeholder="Ex: jean.dupont@email.com"
                                   required>
                            <div class="form-text">Utilisé pour identifier le plaignant de manière unique</div>
                            @error('complainant_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">

                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="customer_id" class="form-label">Client Concerné</label>
                            <input class="form-control @error('customer_id') is-invalid @enderror"
                                   id="customer_id"
                                   name="customer_id"
                                   value="{{ old('customer_id', $complaint->customer_id) }}"
                                   placeholder="Ex: 4856">

                            <div class="form-text">Id éventuelle du plaignant dans Smarter </div>
                            @error('customer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="profession_id" class="form-label">Formation</label>
                            <select class="form-select @error('profession_id') is-invalid @enderror"
                                    id="profession_id"
                                    name="profession_id">
                                <option value="">-- Sélectionner une formation --</option>
                                @foreach($professions as $profession)
                                    <option value="{{ $profession->profession_id }}" {{ old('profession_id') == $complaint->profession_id ? 'selected' : '' }}>
                                        {{ $profession->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('profession_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="entity_id" class="form-label">Service/Centre</label>
                            <select class="form-select @error('entity_id') is-invalid @enderror"
                                    id="entity_id"
                                    name="entity_id">
                                <option value="">-- Sélectionner une entité --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}" {{ old('entity_id', $complaint->department_id) == $department->department_id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('entity_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <hr>

            <!-- Objet de la plainte -->
            <div class="form-card">
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="bi bi-chat-left-text me-2"></i>
                        Objet
                    </h2>
                    <p class="section-subtitle">Description de la plainte</p>

                    <div class="mb-3">
                        <textarea class="form-control @error('object') is-invalid @enderror"
                                  id="object"
                                  name="object"
                                  rows="6"
                                  placeholder="Saisissez la description de la plainte ...">{{ old('object', $complaint->object) }}</textarea>
                        @error('object')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>

            <!-- Traitement et Suivi -->
            <div class="form-card">
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="bi bi-clipboard-check me-2"></i>
                        Traitement et Suivi
                    </h2>
                    <p class="section-subtitle">Dates et informations de traitement</p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="acknowledgment_date" class="form-label">Date d'Accusé de Réception</label>
                            <input type="date" class="form-control @error('acknowledgment_date') is-invalid @enderror"
                                   id="acknowledgment_date" name="acknowledgment_date" value="{{ old('acknowledgment_date', $complaint->acknowledgement_date) }}">
                            @error('acknowledgment_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="transmission_date" class="form-label">Date de Transmission</label>
                            <input type="date" class="form-control @error('transmission_date') is-invalid @enderror"
                                   id="transmission_date" name="transmission_date" value="{{ old('transmission_date', $complaint->transmission_date) }}">
                            @error('transmission_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label d-block text-primary fw-bold">Recevabilité/fondement de la plainte</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="statut_decision" id="status_pending" value="0"
                                       {{ old('statut_decision', '0') == '0' ? 'checked' : '' }} onchange="toggleDecisionFields(false)">
                                <label class="form-check-label" for="status_pending">Non statué</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="statut_decision" id="status_decided" value="1"
                                       {{ old('statut_decision') == '1' ? 'checked' : '' }} onchange="toggleDecisionFields(true)">
                                <label class="form-check-label" for="status_decided">Statué</label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="decision-fields" style="{{ old('statut_decision') == '1' ? '' : 'display: none;' }}">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Recevabilité</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="admissible" name="admissible" value="1"
                                    {{ old('admissible') ? 'checked' : '' }}>
                                <label class="form-check-label" for="admissible">La plainte est recevable</label>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Bien-Fondé</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="well_founded" name="well_founded" value="1"
                                    {{ old('well_founded') ? 'checked' : '' }}>
                                <label class="form-check-label" for="well_founded">La plainte est bien fondée</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Réponse -->
            <div class="form-card">
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="bi bi-chat-left-text me-2"></i>
                        Réponse
                    </h2>
                    <p class="section-subtitle">Réponse apportée à la plainte</p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="response_date" class="form-label">Date de Réponse</label>
                            <input type="date"
                                   class="form-control @error('response_date') is-invalid @enderror"
                                   id="response_date"
                                   name="response_date"
                                   value="{{ old('response_date', $complaint->response_date) }}">
                            @error('response_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="response" class="form-label">Texte de la Réponse</label>
                        <textarea class="form-control @error('response') is-invalid @enderror"
                                  id="response"
                                  name="response"
                                  rows="6"
                                  placeholder="Saisissez la réponse apportée à la plainte...">{{ old('response', $complaint->response) }}</textarea>
                        <div class="form-text">Description détaillée de la réponse ou de la résolution</div>
                        @error('response')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <hr>

            <!-- Form Actions -->
            <div class="form-card">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('complaints.index') }}" class="btn btn-cancel">
                        <i class="bi bi-x-circle me-2"></i>
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-submit">
                        <i class="bi bi-check-circle me-2"></i>
                        Enregistrer la Plainte
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function toggleDecisionFields(isDecided) {
            const fields = document.getElementById('decision-fields');
            fields.style.display = isDecided ? 'flex' : 'none';

            // Optionnel : Décocher les cases si on repasse en "Non statué"
            if(!isDecided) {
                document.getElementById('admissible').checked = false;
                document.getElementById('well_founded').checked = false;
            }
        }
    </script>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

