@props([
    'complaint' => null,
])

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center mb-4">
            <i class="bi bi-people fs-4 text-primary me-2"></i>
            <h2 class="h5 mb-0 fw-bold">Parties Concernées</h2>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('complainant_lastname') is-invalid @enderror"
                       name="complainant_lastname"
                       value="{{ old('complainant_lastname', $complaint?->complainant?->last_name) }}"
                       placeholder="Ex: Dupont" required>
                @error('complainant_lastname')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('complainant_firstname') is-invalid @enderror"
                       name="complainant_firstname"
                       value="{{ old('complainant_firstname', $complaint?->complainant?->first_name) }}"
                       placeholder="Ex: Jean" required>
                @error('complainant_firstname')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Date de naissance</label>
                <input type="date" class="form-control @error('complainant_dateofbirth') is-invalid @enderror"
                       name="complainant_dateofbirth"
                       value="{{ old('complainant_dateofbirth', $complaint?->complainant?->date_of_birth?->format('Y-m-d')) }}">
                @error('complainant_dateofbirth')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('complainant_email') is-invalid @enderror"
                       name="complainant_email"
                       value="{{ old('complainant_email', $complaint?->complainant?->email) }}"
                       placeholder="jean.dupont@email.com" required>
                @error('complainant_email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Téléphone</label>
                <input type="text" class="form-control @error('complainant_phone') is-invalid @enderror"
                       name="complainant_phone"
                       value="{{ old('complainant_phone', $complaint?->complainant?->phone) }}"
                       placeholder="+32495239218">
                @error('complainant_phone')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Adresse <span class="text-danger"></span></label>
                <input type="text" class="form-control @error('complainant_address') is-invalid @enderror"
                       name="complainant_address"
                       value="{{ old('complainant_address', $complaint?->complainant?->address) }}"
                       placeholder="Place Verte, 15" required>
                @error('complainant_address')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Code postal</label>
                <input type="text" class="form-control @error('complainant_zip_code') is-invalid @enderror"
                       name="complainant_zip_code"
                       value="{{ old('complainant_zip_code', $complaint?->complainant?->zip_code) }}"
                       placeholder="B-6000">
                @error('complainant_zip_code')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Localité</label>
                <input type="text" class="form-control @error('complainant_city') is-invalid @enderror"
                       name="complainant_city"
                       value="{{ old('complainant_city', $complaint?->complainant?->city) }}"
                       placeholder="Charleroi">
                @error('complainant_city')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Pays</label>
                <input type="text" class="form-control @error('complainant_country') is-invalid @enderror"
                       name="complainant_country"
                       value="{{ old('complainant_country', $complaint?->complainant?->country) }}"
                       placeholder="Belgique">
                @error('complainant_country')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row g-3 align-items-end">
            <div class="col-md-12 pb-4">
                @livewire('profession-selector')
            </div>
        </div>
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">ID Smarter (optionnel)</label>
                <livewire:client-search />
            </div>
        </div>
    </div>
</div>
