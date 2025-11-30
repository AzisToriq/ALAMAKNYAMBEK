@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="row">
    <div class="col-md-6">
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- CARD 1: IDENTITAS -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-store me-2"></i> Identitas Usaha</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Usaha / Toko</label>
                        <input type="text" name="shop_name" class="form-control" value="{{ $setting->shop_name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telepon (Utk Struk)</label>
                        <input type="text" name="shop_phone" class="form-control" value="{{ $setting->shop_phone }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="shop_address" class="form-control" rows="3">{{ $setting->shop_address }}</textarea>
                    </div>
                </div>
            </div>

            <!-- CARD 3: MANAJEMEN MODUL (BARU) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-cubes me-2"></i> Kelola Modul (Fitur)</h6>
                </div>
                <div class="card-body bg-light rounded-bottom">
                    <p class="small text-muted mb-3">Aktifkan fitur yang Anda butuhkan saja agar tampilan lebih bersih.</p>

                    <!-- TOGGLE SUPPLIER -->
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="enable_supplier" id="switchSupplier" {{ $setting->enable_supplier ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="switchSupplier">Modul Supplier</label>
                        <div class="text-muted small">Menu data pemasok barang.</div>
                    </div>

                    <!-- TOGGLE INVENTORY -->
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="enable_inventory" id="switchInventory" {{ $setting->enable_inventory ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="switchInventory">Modul Inventory (Stok)</label>
                        <div class="text-muted small">Menu riwayat stok masuk, keluar, & opname.</div>
                    </div>

                    <!-- TOGGLE KEUANGAN -->
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="enable_finance" id="switchFinance" {{ $setting->enable_finance ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="switchFinance">Modul Keuangan</label>
                        <div class="text-muted small">Menu biaya operasional & laporan laba rugi.</div>
                    </div>
                </div>
            </div>
    </div>

    <div class="col-md-6">
            <!-- CARD 2: KONFIGURASI POS -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fa-solid fa-toggle-on me-2"></i> Tampilan Kasir (POS)</h6>
                </div>
                <div class="card-body">
                    
                    <!-- TOGGLE NO MEJA -->
                    <div class="form-check form-switch mb-3 p-3 border rounded">
                        <input class="form-check-input" type="checkbox" name="enable_table_number" id="switchTable" {{ $setting->enable_table_number ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="switchTable">Input Nomor Meja</label>
                        <div class="text-muted small">Cocok untuk Dine-in (Resto/Cafe).</div>
                    </div>

                    <!-- TOGGLE PAJAK -->
                    <div class="form-check form-switch mb-3 p-3 border rounded">
                        <input class="form-check-input" type="checkbox" name="enable_tax" id="switchTax" {{ $setting->enable_tax ? 'checked' : '' }} onchange="toggleTaxInput()">
                        <label class="form-check-label fw-bold" for="switchTax">Aktifkan Pajak (PPN / Service)</label>
                        
                        <div class="mt-2" id="taxInputGroup" style="{{ $setting->enable_tax ? '' : 'display:none' }}">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Persentase (%)</span>
                                <input type="number" name="tax_rate" class="form-control" value="{{ $setting->tax_rate }}">
                            </div>
                        </div>
                    </div>

                    <!-- TOGGLE STOK BADGE -->
                    <div class="form-check form-switch mb-3 p-3 border rounded">
                        <input class="form-check-input" type="checkbox" name="enable_stock_badge" id="switchStock" {{ $setting->enable_stock_badge ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="switchStock">Tampilkan Sisa Stok di Kasir</label>
                        <div class="text-muted small">Matikan jika stok bahan baku tidak ingin dilihat kasir.</div>
                    </div>

                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="fa-solid fa-save me-2"></i> Simpan Pengaturan
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleTaxInput() {
        const isChecked = document.getElementById('switchTax').checked;
        const taxGroup = document.getElementById('taxInputGroup');
        taxGroup.style.display = isChecked ? 'block' : 'none';
    }
</script>
@endpush