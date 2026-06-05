@props([
    'complaint' => null,
    'allowedComplaintTypes',
    'channels',
])

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center mb-4">
            <i class="bi bi-info-circle fs-4 text-primary me-2"></i>
            <h2 class="h5 mb-0 fw-bold">Informations Générales</h2>
        </div>

        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Date de la plainte <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('complaint_date') is-invalid @enderror"
                       name="complaint_date"
                       value="{{ old('complaint_date', $complaint?->complaint_date?->format('Y-m-d') ?? date('Y-m-d')) }}"
                       required>
                @error('complaint_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Date de Réception <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('reception_date') is-invalid @enderror"
                       name="reception_date"
                       value="{{ old('reception_date', $complaint?->reception_date?->format('Y-m-d') ?? date('Y-m-d')) }}"
                       required>
                @error('reception_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Type de plainte <span class="text-danger">*</span></label>
                <select class="form-select @error('complaint_type_id') is-invalid @enderror"
                        name="complaint_type_id" required>
                    @foreach($allowedComplaintTypes as $type)
                        <option
                            value="{{ $type->value }}"
                            @selected(old('complaint_type_id', $complaint?->complaint_type_id->value ?? \App\Enums\ComplaintType::PLAINTE->value) == $type->value)>
                            {{ $type->label() }}
                        </option>
                    @endforeach
                </select>
                @error('complaint_type_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Canal <span class="text-danger">*</span></label>
                <select class="form-select @error('channel_id') is-invalid @enderror"
                        name="channel_id" required>
                    <option value="">-- Sélectionner --</option>
                    @foreach($channels as $channel)
                        <option
                            value="{{ $channel->value }}"
                            @selected(old('channel_id', $complaint?->channel_id?->value) == $channel->value)>
                            {{ $channel->label() }}
                        </option>
                    @endforeach
                </select>
                @error('channel_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>
