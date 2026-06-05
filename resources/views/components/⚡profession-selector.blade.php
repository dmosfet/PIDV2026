<?php

use App\Models\Profession;
use Livewire\Component;

new class extends Component {
    public $search = '';
    public $selectedId = null;
    public $showDropdown = false;

    public function mount() {
        if (old('profession_id')) {
            // Utilisation de profession_id ici aussi
            $p = Profession::find(old('profession_id'));
            if($p) {
                $this->selectedId = $p->profession_id;
                $this->search = $p->name;
            }
        }
    }

    public function updatedSearch()
    {
        $this->selectedId = null;
        $this->showDropdown = strlen($this->search) >= 2;
    }

    public function selectProfession($id, $name)
    {
        // On vérifie que l'ID arrive bien
        $this->selectedId = $id;
        $this->search = $name;
        $this->showDropdown = false;

        $this->dispatch('profession-selected', id: $id);
    }

    public function clear()
    {
        $this->search = '';
        $this->selectedId = null;
        $this->showDropdown = false;
    }

    public function with()
    {
        $results = [];
        if (strlen($this->search) >= 2 && !$this->selectedId) {
            $results = Profession::query()
                ->where('active', true)
                ->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('code', 'like', "%{$this->search}%");
                })
                ->take(6)
                ->get();
        }

        return [
            'professions' => $results,
        ];
    }
};
?>

<div>
    <div class="position-relative">
        <label for="profession_search" class="form-label fw-semibold">Formation liée (Métier)</label>

        <div class="input-group">
            <span class="input-group-text bg-white border-end-0 shadow-none text-muted">
                @if($selectedId)
                    <i class="bi bi-check-circle-fill text-success"></i>
                @else
                    <i class="bi bi-search"></i>
                @endif
            </span>

            <input
                type="text"
                id="profession_search"
                class="form-control border-start-0 shadow-none @if($selectedId) border-success @endif"
                wire:model.live.debounce.300ms="search"
                placeholder="Indice, nom ou code..."
                autocomplete="off"
                @if($selectedId) readonly @endif
            >

            @if($search || $selectedId)
                <button class="btn btn-outline-secondary border-start-0 shadow-none" type="button" wire:click="clear">
                    <i class="bi bi-x"></i>
                </button>
            @endif
        </div>

        @if(count($professions) > 0)
            <div class="position-absolute w-100 shadow-lg rounded-3 mt-1 bg-white border overflow-hidden" style="z-index: 1060;">
                <div class="list-group list-group-flush">
                    @foreach($professions as $p)
                        <button type="button"
                                {{-- ON UTILISE ICI $p->profession_id --}}
                                wire:click.prevent="selectProfession({{ $p->profession_id }}, '{{ addslashes($p->name) }}')"
                                class="list-group-item list-group-item-action py-2 d-flex justify-content-between align-items-center text-start border-0 border-bottom">
                            <div>
                                <div class="fw-bold small text-dark">{{ $p->name }}</div>
                                <small class="text-muted font-monospace" style="font-size: 0.7rem;">{{ $p->code }}</small>
                            </div>
                            <i class="bi bi-plus-circle text-primary opacity-50"></i>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <input type="hidden" name="profession_id" value="{{ $selectedId }}">
</div>
