<?php

use Livewire\Component as BaseComponent;
use App\Models\Complainant;

new class extends BaseComponent {
    public $complainantId;
    public $initials;
    public $name;
    public $details = null;
    public bool $showModal = false;

    public function mount($id, $initials, $name)
    {
        $this->complainantId = $id;
        $this->initials = $initials;
        $this->name = $name;
    }

    public function showDetails()
    {
        // On ne charge les données sensibles (email, tel) qu'au clic
        $this->details = Complainant::select('complainant_id', 'first_name', 'last_name', 'date_of_birth', 'email', 'phone', 'address', 'city', 'zip_code', 'country')
            ->find($this->complainantId);
        $this->showModal = true;
    }

    public function getCleanPhoneProperty()
    {
        if (!$this->details || !$this->details->phone) return null;
        // Supprime tout ce qui n'est pas chiffre ou le signe +
        return preg_replace('/[^0-9+]/', '', $this->details->phone);
    }

    public function closeModal()
    {
        $this->showModal = false;
    }
}; ?>

<div class="detail-item">
    <div class="detail-label">Plaignant</div>
    <div class="person-card" wire:click="showDetails" style="cursor: pointer; transition: border-color 0.2s;"
         onmouseover="this.style.borderColor='#0d6efd'" onmouseout="this.style.borderColor=''">
        <div class="person-avatar">
            {{ $initials }}
        </div>
        <div class="person-info">
            <div class="person-name">{{ $name }}</div>
            <div class="person-role">Plaignant</div>
        </div>
        <div class="ms-2 px-2">
            <i class="bi bi-search detail-search-icon"></i>
        </div>
    </div>

    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 1050;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow border-0">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold">Fiche détaillée:
                            <small class="text-muted">ID Plaignant: #{{ $details->complainant_id }}</small>
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        @if($details)
                            <div class="d-flex align-items-center mb-4">
                                <div class="person-avatar me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $details->first_name }} {{ $details->last_name }}</h5>
                                    <small class="text-muted">Date de
                                        naissance: {{ $details->date_of_birth ? $details->date_of_birth->format('d-m-Y') : 'N/A' }}</small>
                                </div>
                            </div>

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <span class="text-muted d-block small">Adresse</span>
                                        <span class="fw-medium text-dark">{{ $details->address ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <span class="text-muted d-block small">Code postal</span>
                                        <span class="fw-medium text-dark">{{ $details->zip_code ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <span class="text-muted d-block small">Localité</span>
                                        <span class="fw-medium text-dark">{{ $details->city ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <span class="text-muted d-block small">Pays</span>
                                        <span class="fw-medium text-dark">{{ $details->country ?? 'N/A' }}</span>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <span class="text-muted d-block small">Email</span>
                                        <span class="fw-medium text-dark">{{ $details->email ?? 'N/A' }}</span>
                                    </div>
                                    @if($details->email)
                                        <a href="mailto:{{ $details->email }}" class="btn btn-sm btn-outline-primary"
                                           title="Envoyer un mail">
                                            <i class="bi bi-envelope-at-fill me-1"></i>
                                        </a>
                                    @endif
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <span class="text-muted d-block small">Téléphone</span>
                                        <span class="fw-medium text-dark">{{ $details->phone ?? 'N/A' }}</span>
                                    </div>
                                    @if($details->phone)
                                        <div class="btn-group btn-group-sm">
                                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $details->phone) }}"
                                               class="btn btn-outline-secondary" title="Appel standard">
                                                <i class="bi bi-telephone"></i>
                                            </a>
                                            <a href="msteams:l/call/0/args?users={{ preg_replace('/[^0-9+]/', '', $details->phone) }}"
                                               class="btn btn-outline-primary" title="Appeler via Teams">
                                                <i class="bi bi-microsoft-teams"></i>
                                            </a>
                                        </div>
                                    @endif
                                </li>
                            </ul>
                        @else
                            <div class="text-center p-4">
                                <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                                <span class="ms-2">Récupération des données sécurisées...</span>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-light border" wire:click="closeModal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <style>body {
                overflow: hidden;
            }</style>
    @endif
</div>
