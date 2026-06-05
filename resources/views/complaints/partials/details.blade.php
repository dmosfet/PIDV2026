<!-- Informations Générales -->
<div class="detail-card">
    <h2 class="card-title">
        <i class="bi bi-info-circle-fill"></i>
        Informations Générales
    </h2>
    <div class="detail-row">
        <div class="detail-item">
            <div class="detail-label">Date de la plainte</div>
            <div class="detail-value">
                {{ $complaint->complaint_date ? $complaint->complaint_date->format('d/m/Y à H:i') : '-' }}
                @if($complaint->complaint_date)
                    <small
                        class="text-muted d-block mt-1">{{ $complaint->complaint_date->diffForHumans() }}</small>
                @endif
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Date de réception</div>
            <div class="detail-value">
                {{ $complaint->reception_date ? $complaint->reception_date->format('d/m/Y à H:i') : '-' }}
                @if($complaint->reception_date)
                    <small
                        class="text-muted d-block mt-1">{{ $complaint->reception_date->diffForHumans() }}</small>
                @endif
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Type de plainte</div>
            <div class="detail-value">
                {{ $complaint->complaint_type_id ? $complaint->complaint_type_id->label() : '-' }}
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Canal de Réception</div>
            <div class="detail-value">
                {{ $complaint->channel_id ? $complaint->channel_id->label() : '-' }}
            </div>
        </div>

    </div>
</div>

<!-- Parties Concernées -->
<div class="detail-card">
    <h2 class="card-title">
        <i class="bi bi-people-fill"></i>
        Parties Concernées
    </h2>
    <div class="detail-row">
            @if($complaint->complainant)
                <livewire:complainant-badge
                    :id="$complaint->complainant_id"
                    :initials="$complaint->complainant->initials"
                    :name="$complaint->complainant->name"
                    :key="'badge-'.$complaint->complainant_id"
                />
            @else
                <div class="detail-item">
                    <div class="detail-label">Plaignant</div>
                    <div class="detail-value empty">Non renseigné</div>
                </div>
            @endif
        <div class="detail-item">
            <div class="detail-label">Client Concerné</div>
            @if($complaint->customer)
                <div class="person-card">
                    <div class="person-avatar">ID</div>
                    <div class="person-info">
                        <div class="person-name">Identifiant: {{ $complaint->customer->walter_hope_smile_id }}</div>
                        <div class="person-role">Utilisateur Smarter</div>
                    </div>
                </div>
            @else
                <div class="detail-value empty">Non renseigné</div>
            @endif
        </div>
        <div class="detail-item">
            <div class="detail-label">Profession</div>
            <div class="person-card">
                {{ $complaint->profession->name ?? 'Non renseigné' }}
            </div>
        </div>
    </div>
    <div class="detail-row">
        <div class="detail-item">
            <div class="detail-label">Responsable du traitement</div>
            @if ($complaint->employee)
                <div class="person-card">
                    <div class="person-avatar">
                        {{ $complaint->employee->initials }}
                    </div>
                    <div class="person-info">
                        <div class="person-name">{{ $complaint->employee->name}}</div>
                        <div class="person-role">Responsable</div>
                    </div>
                </div>
            @else
                <div class="detail-value empty">Non renseigné</div>
            @endif
        </div>
        <div class="detail-item">
            <div class="detail-label">Service/centre concerné</div>
            @if ($complaint->department)
                <div class="person-card">
                    <div class="person-info">
                        <div class="person-name">{{ $complaint->department->name }}</div>
                        <div
                            class="person-role">{{ $complaint->department->department_type_id->label() }}</div>
                    </div>
                </div>
            @else
                <div class="detail-value empty">Non renseigné</div>
            @endif
        </div>
        <div class="detail-item"></div>
    </div>
</div>

<!-- Objet -->
<div class="detail-card">
    <h2 class="card-title">
        <i class="bi bi-chat-left-text-fill"></i>
        Objet
    </h2>
    <div class="detail-row">
        <div class="detail-item">
            <div class="detail-label">Catégorie</div>
            <div class="detail-value">
                {{ $complaint->object_category_id ? $complaint->object_category_id->label() : '-' }}
            </div>
        </div>
    </div>
    <div class="detail-row">
        <div class="detail-item">
            <div class="detail-label">Description de la plainte</div>
            <div class="detail-value {{ !$complaint->object ? 'empty' : '' }}">
                {{ $complaint->object ?? 'Pas de description encodée pour cette plainte'  }}
            </div>
        </div>
    </div>
</div>
