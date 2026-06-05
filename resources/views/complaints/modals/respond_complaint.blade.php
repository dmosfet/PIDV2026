@use('App\Enums\DocumentType')

<div class="modal fade" id="modal-{{ $action }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('complaints.respond', $complaint) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header {{ $buttonconfig['class'] }}">
                    <h5 class="modal-title"><i class="bi {{ $buttonconfig['icon'] }} me-2"></i>Répondre à la plainte</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-0">
                        <label class="form-label fw-bold">Date de la réponse</label>
                        <input type="date"
                               name="response_date"
                               class="form-control"
                               min="{{ $complaint->min_date }}"
                               max="{{ now()->format('Y-m-d') }}"
                               value="{{ now()->format('Y-m-d')}}"
                               required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Réponse</label>
                        <input type="text" name="response" class="form-control" value="" placeholder="Résumé de votre réponse" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    @can('executeTask', [$complaint, $action])
                        <button type="submit" class="btn {{ $buttonconfig['class'] }}">Répondre</button>
                    @endcan
                </div>
            </div>
        </form>
    </div>
</div>

