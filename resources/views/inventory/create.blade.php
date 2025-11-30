@extends('layouts.admin')

@section('title', 'Input Stok')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">Form Penyesuaian Stok</h6>
            </div>
            <div class="card-body p-4">
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Transaksi</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Transaksi</label>
                            <select name="type" class="form-select" id="typeSelect" required>
                                <option value="in" class="text-success fw-bold">➕ Barang Masuk (Pembelian)</option>
                                <option value="out" class="text-danger fw-bold">➖ Barang Keluar (Rusak/Exp)</option>
                                <option value="adjustment" class="text-warning fw-bold">⚖️ Penyesuaian (Stok Opname)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Pilih Produk</label>
                        <select name="product_id" class="form-select" size="5" required>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->code }} - {{ $product->name }} (Sisa Stok: {{ $product->stock }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">*Klik salah satu produk di atas</small>
                    </div>

                    <div class="mb-4" id="supplierInput" style="display: none;"> 
                        <label class="form-label fw-bold text-success">Pilih Supplier (Sumber Barang)</label>
                        <select name="supplier_id" class="form-select border-success">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jumlah (Qty)</label>
                            <input type="number" name="qty" class="form-control" min="1" placeholder="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Catatan / Keterangan</label>
                            <input type="text" name="note" class="form-control" placeholder="Cth: Pembelian dari Supplier A / Barang pecah">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('inventory.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-save me-1"></i> Simpan Stok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Logika Javascript:
    // Jika user pilih "Barang Masuk" (value='in'), tampilkan dropdown Supplier.
    // Selain itu, sembunyikan.
    
    const typeSelect = document.getElementById('typeSelect');
    const supplierInput = document.getElementById('supplierInput');

    function checkType() {
        if (typeSelect.value === 'in') {
            // Munculkan dengan efek animasi slide down sederhana
            supplierInput.style.display = 'block';
        } else {
            supplierInput.style.display = 'none';
        }
    }

    // Jalankan saat user mengganti pilihan dropdown
    typeSelect.addEventListener('change', checkType);
    
    // Jalankan sekali saat halaman pertama kali dibuka
    checkType(); 
</script>
@endpush