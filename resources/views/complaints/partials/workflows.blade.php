<!-- Réception et assignation-->
<div class="detail-card">
    <h2 class="card-title">
        <i class="bi bi-envelope-paper-fill"></i>
        Réception et assignation
    </h2>
    <div class="detail-row">
        <div class="detail-item">
            <div class="detail-label">Date de Transmission</div>
            <div class="detail-value {{ !$complaint->transmission_date ? 'empty' : '' }}">
                {{ $complaint->transmission_date ? $complaint->transmission_date->format('d/m/Y à H:i') : 'Non transmis' }}
                @if($complaint->transmission_date)
                    <small
                        class="text-muted d-block mt-1">{{ $complaint->transmission_date->diffForHumans() }}</small>
                @endif
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Date d'Accusé de Réception</div>
            <div class="detail-value {{ !$complaint->acknowledgment_date ? 'empty' : '' }}">
                {{ $complaint->acknowledgment_date ? $complaint->acknowledgment_date->format('d/m/Y à H:i') : 'Non envoyé' }}
                @if($complaint->acknowledgment_date)
                    <small
                        class="text-muted d-block mt-1">{{ $complaint->acknowledgment_date->diffForHumans() }}</small>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Évaluation -->
<div class="detail-card">
    <h2 class="card-title">
        <i class="bi bi-clipboard-check-fill"></i>
        Évaluation
    </h2>
    <div class="detail-row">
        <div class="detail-item">
            <div class="detail-label">Recevabilité</div>
            <div class="detail-value">
                @if($complaint->admissible === true)
                    <span class="badge-yes">
                                <i class="bi bi-check-circle-fill"></i>
                                Recevable
                            </span>
                @elseif($complaint->admissible === false)
                    <span class="badge-no">
                                <i class="bi bi-x-circle-fill"></i>
                                Non recevable
                            </span>
                @else
                    <span class="badge-neutral">
                                <i class="bi bi-dash-circle"></i>
                                Non évalué
                            </span>
                @endif
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Bien-Fondé</div>
            <div class="detail-value">
                @if($complaint->well_founded === true)
                    <span class="badge-yes">
                                <i class="bi bi-check-circle-fill"></i>
                                Bien fondée
                            </span>
                @elseif($complaint->well_founded === false)
                    <span class="badge-no">
                                <i class="bi bi-x-circle-fill"></i>
                                Non fondée
                            </span>
                @else
                    <span class="badge-neutral">
                                <i class="bi bi-dash-circle"></i>
                                Non évalué
                            </span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Réponse -->
<div class="detail-card mb-4">
    <h2 class="card-title">
        <i class="bi bi-chat-left-text-fill"></i>
        Réponse
    </h2>
    <div class="detail-row">
        <div class="detail-item">
            <div class="detail-label">Date de Réponse</div>
            <div class="detail-value {{ !$complaint->response_date ? 'empty' : '' }}">
                {{ $complaint->response_date ? $complaint->response_date->format('d/m/Y à H:i') : 'Pas encore de réponse' }}
                @if($complaint->response_date)
                    <small
                        class="text-muted d-block mt-1">{{ $complaint->response_date->diffForHumans() }}</small>
                @endif
            </div>
        </div>
    </div>

    @if($complaint->response)
        <div class="response-box">
            <div class="response-text">{{ $complaint->response }}</div>
        </div>
    @else
        <div class="info-box">
            <i class="bi bi-info-circle-fill"></i>
            <div class="info-box-content">
                <div class="info-box-title">Aucune réponse</div>
                <div class="info-box-text">Aucune réponse n'a encore été apportée à cette plainte.</div>
            </div>
        </div>
    @endif
</div>
