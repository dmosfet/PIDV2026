<div class="modal fade" id="modal-{{ $action }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        @php
            // On détermine la route dynamiquement selon l'action
            $route = ($action === 'reassign_complaint')
                     ? route('complaints.reassign', $complaint->complaint_id)
                     : route('complaints.assign', $complaint->complaint_id);
        @endphp
        <form action="{{ $route }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header {{ $buttonconfig['class'] }}">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus me-2">
                            {{ $action === 'reassign_complaint' ? 'Réassigner le dossier' : 'Assigner le département' }}
                        </i>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Département cible</label>
                        <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                            <option value="">-- Choisir un département --</option>
                            @foreach($departments as $groupLabel => $groupDepts)
                                <optgroup label="{{ $groupLabel }}">
                                    @foreach($groupDepts as $dept)
                                        @php
                                            $userDeptId = auth()->user()->employee->department_id ?? null;
                                            $currentDeptId = $dept->department_id;
                                            $isSelected = ($userDeptId == $currentDeptId);
                                        @endphp
                                        <option value="{{ $currentDeptId }}" {{ $isSelected ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                            @if($dept->manager)
                                                (Manager: {{ $dept->manager->name }})
                                            @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">Date de transmission</label>
                        <input type="date"
                               name="transmission_date"
                               class="form-control"
                               min="{{ $complaint->min_date }}"
                               max="{{ now()->format('Y-m-d') }}"
                               value="{{ now()->format('Y-m-d') }}"
                               required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn {{ $buttonconfig['class'] }}">Confirmer l'assignation</button>
                </div>
            </div>
        </form>
    </div>
</div>
