@props([
    'complaint' => null,
    'objectCategories',
])

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center mb-3">
            <i class="bi bi-chat-left-text fs-4 text-primary me-2"></i>
            <h2 class="h5 mb-0 fw-bold">Objet de la plainte</h2>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                <select class="form-select @error('object_category_id') is-invalid @enderror"
                        name="object_category_id" required>
                    <option value="">-- Sélectionner --</option>
                    @foreach($objectCategories as $cat)
                        <option
                            value="{{ $cat->value }}"
                            @selected(old('object_category_id', $complaint?->object_category_id?->value) == $cat->value)>
                            {{ $cat->label() }}
                        </option>
                    @endforeach
                </select>
                @error('object_category_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-12 pt-4">
                <textarea class="form-control @error('object') is-invalid @enderror"
                          name="object" rows="6"
                          placeholder="Saisissez la description détaillée de la plainte...">{{ old('object', $complaint?->object) }}</textarea>
                @error('object')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>
