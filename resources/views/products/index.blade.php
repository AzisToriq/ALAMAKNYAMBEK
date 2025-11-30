@extends('layouts.admin')

@section('title', 'Daftar Produk')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-box-open me-2"></i> Data Produk</h6>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fa-solid fa-plus"></i> Tambah Produk
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Gambar</th>
                        <th>Info Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th class="text-center">Stok</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="rounded" width="50" height="50" style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold">{{ $product->name }}</div>
                            <small class="text-muted"><i class="fa-solid fa-barcode"></i> {{ $product->code }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info bg-opacity-10 text-info border border-info">{{ $product->category->name ?? '-' }}</span>
                        </td>
                        <td>
                            <small class="d-block text-muted">Modal: Rp {{ number_format($product->buy_price, 0, ',', '.') }}</small>
                            <span class="fw-bold text-success">Jual: Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                        </td>
                        
                        <td class="text-center">
                            @if($product->stock <= $product->min_stock)
                                <span class="badge bg-danger">Sisa: {{ $product->stock }}</span>
                            @else
                                <span class="badge bg-success">{{ $product->stock }}</span>
                            @endif
                            <small class="d-block text-muted mt-1">{{ $product->unit->short_name ?? '' }}</small>
                        </td>

                        <td class="text-end">
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline on-delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- --- MODAL EDIT (SEKARANG BISA EDIT STOK) --- -->
                    <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Produk: {{ $product->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Produk</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Kode (SKU)</label>
                                                        <input type="text" class="form-control bg-light" value="{{ $product->code }}" readonly>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Barcode</label>
                                                        <div class="input-group">
                                                            <input type="text" name="barcode" id="barcodeEdit{{ $product->id }}" class="form-control" value="{{ $product->barcode }}">
                                                            <button type="button" class="btn btn-secondary" onclick="startScan('barcodeEdit{{ $product->id }}')">
                                                                <i class="fa-solid fa-camera"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Kategori</label>
                                                        <select name="category_id" class="form-select">
                                                            @foreach($categories as $cat)
                                                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Satuan</label>
                                                        <select name="unit_id" class="form-select">
                                                            @foreach($units as $unit)
                                                                <option value="{{ $unit->id }}" {{ $product->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 bg-light p-3 rounded">
                                                <!-- [FITUR] EDIT STOK LANGSUNG -->
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-warning">Koreksi Stok (Manual)</label>
                                                    <input type="number" name="stock" class="form-control border-warning" value="{{ $product->stock }}" required>
                                                    <small class="text-muted">Ubah angka ini untuk revisi stok.</small>
                                                </div>
                                                <!-- END FITUR -->

                                                <div class="mb-3">
                                                    <label class="form-label">Harga Modal</label>
                                                    <input type="number" name="buy_price" class="form-control" value="{{ $product->buy_price }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Harga Jual</label>
                                                    <input type="number" name="sell_price" class="form-control" value="{{ $product->sell_price }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ganti Gambar</label>
                                                    <input type="file" name="image" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- --- END MODAL EDIT --- -->
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Belum ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH PRODUK (Sama seperti sebelumnya) -->
<div class="modal fade" id="addProductModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg"> 
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Produk Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kode / SKU</label>
                                    <input type="text" name="code" class="form-control" placeholder="Auto">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Barcode</label>
                                    <div class="input-group">
                                        <input type="text" name="barcode" id="barcodeAdd" class="form-control" placeholder="Scan...">
                                        <button type="button" class="btn btn-secondary" onclick="startScan('barcodeAdd')">
                                            <i class="fa-solid fa-camera"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select name="category_id" class="form-select" required>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Satuan <span class="text-danger">*</span></label>
                                    <select name="unit_id" class="form-select" required>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 bg-light p-3 rounded">
                            <!-- INPUT STOK AWAL -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-primary">Stok Awal (Tersedia)</label>
                                <input type="number" name="initial_stock" class="form-control border-primary" value="0" min="0">
                                <small class="text-muted">Isi untuk stok perdana.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Harga Modal <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="buy_price" class="form-control" required min="0">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="sell_price" class="form-control" required min="0">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gambar</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL SCANNER -->
<div class="modal fade" id="scanModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Scan Barcode</h5><button type="button" class="btn-close" onclick="stopScan()" data-bs-dismiss="modal"></button></div><div class="modal-body"><div id="reader"></div></div></div></div></div>

@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    let html5QrcodeScanner;
    let targetInputId = '';
    function startScan(inputId) {
        targetInputId = inputId;
        new bootstrap.Modal(document.getElementById('scanModal')).show();
        html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: {width: 250, height: 250} }, false);
        html5QrcodeScanner.render((decoded) => {
            document.getElementById(targetInputId).value = decoded;
            stopScan();
            bootstrap.Modal.getInstance(document.getElementById('scanModal')).hide();
        });
    }
    function stopScan() { if(html5QrcodeScanner) html5QrcodeScanner.clear(); }
    
    $('.on-delete-form').on('submit', function(e){
        e.preventDefault();
        Swal.fire({title:'Hapus?', icon:'warning', showCancelButton:true, confirmButtonColor:'#d33', confirmButtonText:'Ya'}).then((r)=>{if(r.isConfirmed)this.submit()});
    });
</script>
@endpush