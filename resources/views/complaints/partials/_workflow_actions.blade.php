<div class="header-actions d-flex gap-2 w-100">
    <div class="gap-2">
        {{-- Groupe Gauche : Navigation + Actions de base si encore disponible --}}
        <a class="btn btn-outline-secondary" href="{{ $backRoute ?? route('complaints.index') }}">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
        @if($complaint->status->isEditable())
            <a href="{{ route('complaints.edit', $complaint->complaint_id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
        @endif
        @can('delete', $complaint)
            <form action="{{ route('complaints.destroy', $complaint->complaint_id) }}" method="POST"
                  onsubmit="return confirm('Supprimer définitivement ?');" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        @endcan
    </div>
    {{-- Groupe Droite : Workflow  --}}
    <div class="ms-auto d-flex gap-2">
        @foreach($complaint->status->nextActions() as $action)
            @can('executeTask', [$complaint, $action])
                {{-- 2. L'Enum fournit les visuels --}}
                @php $buttonconfig = $complaint->status->getActionConfig($action); @endphp

                <button type="button" class="btn {{$buttonconfig['class']}} shadow-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-{{ $action }}">
                    <i class="bi {{ $buttonconfig['icon'] }}"></i> {{ $buttonconfig['label'] }}
                </button>
                @includeIf("complaints.modals.{$action}", [$complaint, $action])
            @endcan
        @endforeach
    </div>
</div>
