@use('App\Enums\DocumentType')

<div class="modal fade" id="modal-{{ $action }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('complaints.close', $complaint) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header {{ $buttonconfig['class'] }}">
                    <h5 class="modal-title"><i class="bi {{ $buttonconfig['icon'] }} me-2"></i>Clôturer la plainte</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-0">
                        <label class="form-label fw-bold">Souhaitez-vous clôturer la plainte ?</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    @can('executeTask', [$complaint, $action])
                        <button type="submit" class="btn {{ $buttonconfig['class'] }}">Clôturer</button>
                    @endcan
                </div>
            </div>
        </form>
    </div>
</div>

