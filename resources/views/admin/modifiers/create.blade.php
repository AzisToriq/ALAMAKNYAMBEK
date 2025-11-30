@extends('layouts.admin')

@section('title', 'Tambah Modifier')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('modifiers.index') }}" class="btn btn-outline-secondary mb-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
        <h2 class="fw-bold"><i class="fa-solid fa-plus-circle me-2"></i> Tambah Modifier Baru</h2>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('modifiers.store') }}" method="POST">
        @csrf
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Nama Modifier -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Nama Modifier <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                           placeholder="Contoh: Ukuran, Level Pedas, Topping" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Type & Is Multiple -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tipe <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="optional" {{ old('type') == 'optional' ? 'selected' : '' }}>Opsional (Boleh tidak dipilih)</option>
                            <option value="required" {{ old('type') == 'required' ? 'selected' : '' }}>Wajib (Harus dipilih)</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Mode Pilihan <span class="text-danger">*</span></label>
                        <select name="is_multiple" class="form-select @error('is_multiple') is-invalid @enderror" required>
                            <option value="0" {{ old('is_multiple') == '0' ? 'selected' : '' }}>Pilih Satu (Radio)</option>
                            <option value="1" {{ old('is_multiple') == '1' ? 'selected' : '' }}>Pilih Banyak (Checkbox)</option>
                        </select>
                        @error('is_multiple')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Assign ke Produk <span class="text-danger">*</span></label>
                    <small class="text-muted d-block mb-2">Pilih produk mana saja yang akan menggunakan modifier ini</small>
                    <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                        @foreach($products as $product)
                        <div class="form-check">
                            {{-- PERBAIKAN: Ganti name="products[]" jadi name="product_ids[]" --}}
                            <input class="form-check-input" type="checkbox" name="product_ids[]" value="{{ $product->id }}" 
                                    id="product_{{ $product->id }}" 
                                    {{-- Cek error validation old input --}}
                                    {{ is_array(old('product_ids')) && in_array($product->id, old('product_ids')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="product_{{ $product->id }}">
                                {{ $product->name }} <span class="text-muted small">(Rp {{ number_format($product->sell_price, 0, ',', '.') }})</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Options (Pilihan dalam Modifier) -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">Opsi Pilihan <span class="text-danger">*</span></label>
                        <button type="button" class="btn btn-sm btn-success" onclick="addOption()">
                            <i class="fa-solid fa-plus"></i> Tambah Opsi
                        </button>
                    </div>
                    <small class="text-muted d-block mb-3">Tambahkan pilihan yang tersedia untuk modifier ini</small>
                    
                    <div id="optionsContainer">
                        <!-- Default 2 options -->
                        <div class="option-row mb-2">
                            <div class="input-group">
                                <input type="text" name="options[0][name]" class="form-control" placeholder="Nama opsi (Contoh: Small)" required>
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="options[0][price]" class="form-control" placeholder="0" min="0" step="100" value="0" required>
                                <button type="button" class="btn btn-danger" onclick="removeOption(this)" disabled>
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="option-row mb-2">
                            <div class="input-group">
                                <input type="text" name="options[1][name]" class="form-control" placeholder="Nama opsi (Contoh: Medium)" required>
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="options[1][price]" class="form-control" placeholder="5000" min="0" step="100" value="0" required>
                                <button type="button" class="btn btn-danger" onclick="removeOption(this)">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light d-flex justify-content-end gap-2">
                <a href="{{ route('modifiers.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-times me-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save me-1"></i> Simpan Modifier
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let optionIndex = 2; // Start from 2 karena sudah ada 2 default

function addOption() {
    const container = document.getElementById('optionsContainer');
    const newOption = document.createElement('div');
    newOption.className = 'option-row mb-2';
    newOption.innerHTML = `
        <div class="input-group">
            <input type="text" name="options[${optionIndex}][name]" class="form-control" placeholder="Nama opsi" required>
            <span class="input-group-text">Rp</span>
            <input type="number" name="options[${optionIndex}][price]" class="form-control" placeholder="0" min="0" step="100" value="0" required>
            <button type="button" class="btn btn-danger" onclick="removeOption(this)">
                <i class="fa-solid fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(newOption);
    optionIndex++;
}

function removeOption(button) {
    const optionRow = button.closest('.option-row');
    optionRow.remove();
}
</script>
@endpush
@endsection