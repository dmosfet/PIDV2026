@use('App\Enums\DocumentType')

<div class="modal fade" id="modal-{{ $action }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('complaints.acknowledge', $complaint->complaint_id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header {{ $buttonconfig['class'] }}">
                    <h5 class="modal-title"><i class="bi {{ $buttonconfig['icon'] }} me-2"></i>Accuser réception de la plainte</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-0">
                        <label class="form-label fw-bold">Date d'accusé de réception'</label>
                        <input type="date"
                               name="acknowledgment_date"
                               class="form-control"
                               min="{{ $complaint->min_date }}"
                               max="{{ now()->format('Y-m-d') }}"
                               value="{{ now()->format('Y-m-d') }}"
                               required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" >Annuler</button>
                    @can('executeTask', [$complaint, $action])
                        <a href="{{ route('documents.pdf', ['complaint' => $complaint, 'type' => DocumentType::ACKNOWLEDGMENT->value]) }}"
                           target="_blank" class="btn btn-info py-2">
                            <i class="bi bi-cloud-arrow-up me-2 d-inline-block"></i>Générer
                        </a>
                    <button type="submit" class="btn {{ $buttonconfig['class'] }}">Valider l'envoi</button>
                    @endcan
                </div>
            </div>
        </form>
    </div>
</div>
