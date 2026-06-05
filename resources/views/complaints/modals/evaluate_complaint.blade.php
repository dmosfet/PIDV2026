@use('App\Enums\DocumentType')

<div class="modal fade" id="modal-{{ $action }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('complaints.evaluate', $complaint->complaint_id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header {{ $buttonconfig['class'] }}">
                    <h5 class="modal-title"><i class="bi bi-clipboard-check me-2"></i>Évaluer la plainte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-0">
                        <label class="form-label fw-bold">Date de l'évaluation'</label>
                        <input type="date"
                               name="evaluation_date"
                               class="form-control"
                               min="{{ $complaint->min_date }}"
                               max="{{ now()->format('Y-m-d') }}"
                               value="{{ now()->format('Y-m-d')}}"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Recevabilité</label>
                        <select name="admissible" class="form-select" required>
                            <option value="">Choisir...</option>
                            <option value="1">Recevable</option>
                            <option value="0">Irrecevable</option>
                        </select>
                        <div class="form-text">La plainte remplit-elle les critères formels ?</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Bien-fondé</label>
                        <select name="well_founded" class="form-select" required>
                            <option value="">Choisir...</option>
                            <option value="1">Fondée</option>
                            <option value="0">Non fondée</option>
                        </select>
                        <div class="form-text">Les faits reprochés sont-ils avérés ?</div>
                    </div>
                    {{--<div class="mb-0">
                        <label class="form-label fw-bold">Commentaires techniques</label>
                        <textarea name="evaluation_comment" class="form-control" rows="3" placeholder="Justifiez votre décision ici..."></textarea>
                    </div>--}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    @can('executeTask', [$complaint, $action])
                        {{-- Bouton de VALIDATION FINALE --}}
                        <button type="submit" class="btn {{ $buttonconfig['class'] }}">
                            Évaluer la plainte
                        </button>
                    @endcan
                </div>
            </div>
        </form>
    </div>
</div>
