@extends('layouts.admin')

@section('title', 'Edit Modifier')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('modifiers.index') }}" class="btn btn-outline-secondary mb-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
        <h2 class="fw-bold"><i class="fa-solid fa-edit me-2"></i> Edit Modifier: {{ $modifier->name }}</h2>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('modifiers.update', $modifier->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="mb-4">
                    <label class="form-label fw-bold">Nama Modifier <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $modifier->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tipe <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="optional" {{ old('type', $modifier->type) == 'optional' ? 'selected' : '' }}>Opsional</option>
                            <option value="required" {{ old('type', $modifier->type) == 'required' ? 'selected' : '' }}>Wajib</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mode Pilihan <span class="text-danger">*</span></label>
                        <select name="is_multiple" class="form-select" required>
                            <option value="0" {{ old('is_multiple', $modifier->is_multiple) == 0 ? 'selected' : '' }}>Pilih Satu</option>
                            <option value="1" {{ old('is_multiple', $modifier->is_multiple) == 1 ? 'selected' : '' }}>Pilih Banyak</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Assign ke Produk <span class="text-danger">*</span></label>
                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                        @foreach($products as $product)
                        <div class="form-check">
                            {{-- PERBAIKAN: name="products[]" diganti jadi name="product_ids[]" --}}
                            <input class="form-check-input" type="checkbox" name="product_ids[]" value="{{ $product->id }}" 
                                   id="product_{{ $product->id }}" 
                                   {{ $modifier->products->contains($product->id) ? 'checked' : '' }}>
                            <label class="form-check-label" for="product_{{ $product->id }}">
                                {{ $product->name }} <span class="text-muted small">(Rp {{ number_format($product->sell_price, 0, ',', '.') }})</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <small class="text-muted">Centang produk yang ingin memiliki modifier ini.</small>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">Opsi Pilihan <span class="text-danger">*</span></label>
                        <button type="button" class="btn btn-sm btn-success" onclick="addOption()">
                            <i class="fa-solid fa-plus"></i> Tambah Opsi
                        </button>
                    </div>
                    
                    <div id="optionsContainer">
                        @foreach($modifier->options as $index => $option)
                        <div class="option-row mb-2">
                            <div class="input-group">
                                <input type="text" name="options[{{ $index }}][name]" class="form-control" value="{{ $option->name }}" required>
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="options[{{ $index }}][price]" class="form-control" value="{{ $option->price }}" min="0" step="100" required>
                                <button type="button" class="btn btn-danger" onclick="removeOption(this)" {{ $index == 0 ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="{{ route('modifiers.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save me-1"></i> Update Modifier
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let optionIndex = {{ $modifier->options->count() }};

function addOption() {
    const container = document.getElementById('optionsContainer');
    const newOption = document.createElement('div');
    newOption.className = 'option-row mb-2';
    newOption.innerHTML = `
        <div class="input-group">
            <input type="text" name="options[${optionIndex}][name]" class="form-control" placeholder="Nama opsi" required>
            <span class="input-group-text">Rp</span>
            <input type="number" name="options[${optionIndex}][price]" class="form-control" value="0" min="0" step="100" required>
            <button type="button" class="btn btn-danger" onclick="removeOption(this)">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(newOption);
    optionIndex++;
}

function removeOption(button) {
    button.closest('.option-row').remove();
}
</script>
@endpush
@endsection