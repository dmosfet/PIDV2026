
<!-- Timeline -->
<div class="detail-card">
    <h2 class="card-title">
        <i class="bi bi-clock-history"></i>
        Chronologie
    </h2>

    @if($complaint->reception_date)
        <div class="timeline-item">
            <div class="timeline-icon blue">
                <i class="bi bi-inbox-fill"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-title">Plainte reçue</div>
                <div class="timeline-date">{{ $complaint->reception_date->format('d/m/Y à H:i') }}</div>
            </div>
        </div>
    @endif

    @if($complaint->acknowledgment_date)
        <div class="timeline-item">
            <div class="timeline-icon green">
                <i class="bi bi-envelope-check-fill"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-title">Accusé de réception envoyé</div>
                <div
                    class="timeline-date">{{ $complaint->acknowledgment_date->format('d/m/Y à H:i') }}</div>
            </div>
        </div>
    @endif

    @if($complaint->transmission_date)
        <div class="timeline-item">
            <div class="timeline-icon purple">
                <i class="bi bi-send-fill"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-title">Transmise au service concerné</div>
                <div class="timeline-date">{{ $complaint->transmission_date->format('d/m/Y à H:i') }}</div>
            </div>
        </div>
    @endif

    @if($complaint->response_date)
        <div class="timeline-item">
            <div class="timeline-icon orange">
                <i class="bi bi-chat-left-text-fill"></i>
            </div>
            <div class="timeline-content">
                <div class="timeline-title">Réponse apportée</div>
                <div class="timeline-date">{{ $complaint->response_date->format('d/m/Y à H:i') }}</div>
            </div>
        </div>
    @endif

    @if(!$complaint->reception_date && !$complaint->acknowledgment_date && !$complaint->transmission_date && !$complaint->response_date)
        <div class="info-box">
            <i class="bi bi-info-circle-fill"></i>
            <div class="info-box-content">
                <div class="info-box-title">Aucun événement</div>
                <div class="info-box-text">Aucun événement n'a encore été enregistré pour cette plainte.
                </div>
            </div>
        </div>
    @endif
</div>
