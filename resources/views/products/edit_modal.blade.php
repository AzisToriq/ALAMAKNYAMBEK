<div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Produk: {{ $product->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- KOLOM KIRI -->
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
                                
                                <!-- INPUT BARCODE DENGAN SCANNER -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Barcode</label>
                                    <div class="input-group">
                                        <!-- ID unik menggunakan product id -->
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
                        
                        <!-- KOLOM KANAN -->
                        <div class="col-md-6 bg-light p-3 rounded">
                            
                            <!-- [FITUR UTAMA] KOREKSI STOK MANUAL -->
                            <!-- Input ini WAJIB ADA agar controller tidak error -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-warning">Koreksi Stok (Manual)</label>
                                <input type="number" name="stock" class="form-control border-warning" value="{{ $product->stock }}" required>
                                <small class="text-muted">Ubah angka ini jika stok fisik tidak sesuai.</small>
                            </div>
                            <!-- END FITUR UTAMA -->

                            <div class="mb-3">
                                <label class="form-label">Harga Modal</label>
                                <input type="number" name="buy_price" class="form-control" value="{{ $product->buy_price }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga Jual</label>
                                <input type="number" name="sell_price" class="form-control" value="{{ $product->sell_price }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Minimal Stok (Alert)</label>
                                <input type="number" name="min_stock" class="form-control" value="{{ $product->min_stock }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ganti Gambar (Opsional)</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>