<?php

use Livewire\Component;

new class extends Component {

    public string $query = '';
    public ?int $selectedId = null;
    public string $selectedName = '';
    public bool $showResults = false;
    public array $results = []; // ← ajouter cette propriété

    protected function searchClients(): array
    {
        $fakeClients = [
            ['id' => 1, 'name' => 'Dupont Jean', 'email' => 'jean.dupont@mail.be', 'phone' => '0471 12 34 56'],
            ['id' => 2, 'name' => 'Martin Sophie', 'email' => 'sophie.martin@mail.be', 'phone' => '0472 23 45 67'],
            ['id' => 3, 'name' => 'Lecomte Pierre', 'email' => 'p.lecomte@mail.be', 'phone' => '0473 34 56 78'],
            ['id' => 4, 'name' => 'Dubois Marie', 'email' => 'marie.dubois@mail.be', 'phone' => '0474 45 67 89'],
            ['id' => 5, 'name' => 'Simon Lucas', 'email' => 'lucas.simon@mail.be', 'phone' => '0475 56 78 90'],
        ];

        return array_values(array_filter($fakeClients, fn($c) => str_contains(strtolower($c['name']), strtolower($this->query)) ||
            str_contains(strtolower($c['email']), strtolower($this->query))
        ));
    }

    public function search(): void
    {
        $this->results = $this->searchClients(); // ← remplir la propriété
        $this->showResults = true;
    }

    public function selectClient(int $id, string $name): void
    {
        $this->selectedId = $id;
        $this->selectedName = $name;
        $this->showResults = false;
        $this->query = '';
    }

    public function clear(): void
    {
        $this->selectedId = null;
        $this->selectedName = '';
    }

}; ?>

<div>
    <input type="hidden" name="customer_id" value="{{ $selectedId }}">

    @if($selectedId)
        <div class="input-group">
            <input type="text" class="form-control" value="{{ $selectedName }}" readonly>
            <button type="button" class="btn btn-outline-danger" wire:click="clear">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @else
        <div class="input-group mb-2">
            <input type="text"
                   class="form-control"
                   wire:model="query"
                   wire:keydown.enter="search"
                   placeholder="Nom, prénom, email...">
            <button type="button" class="btn btn-outline-primary" wire:click="search">
                <i class="bi bi-search"></i> Rechercher
            </button>
        </div>

        @if($showResults)
            @if(strlen($query) < 2)
                <p class="text-warning small">Saisissez au moins 2 caractères.</p>
            @elseif(empty($results))
                <p class="text-muted small">Aucun résultat trouvé.</p>
            @else
                <div class="border rounded">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $client)
                            <tr>
                                <td>{{ $client['name'] }}</td>
                                <td>{{ $client['email'] }}</td>
                                <td>{{ $client['phone'] }}</td>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-success"
                                            wire:click="selectClient({{ $client['id'] }}, '{{ $client['name'] }}')">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif
    @endif
</div>
