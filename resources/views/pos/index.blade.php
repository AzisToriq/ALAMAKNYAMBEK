@extends('layouts.admin')

@section('title', 'Kasir (POS)')

@section('content')
<style>
    /* --- VARIABLES & UTILS --- */
    :root {
        --pos-bg: #f3f4f6;
        --card-border: #e5e7eb;
        --primary-color: #4f46e5; /* Indigo Modern */
        --primary-hover: #4338ca;
        --secondary-text: #6b7280;
    }

    body { background-color: var(--pos-bg); overflow: hidden; } /* Lock body scroll */

    /* --- LAYOUT UTAMA --- */
    .pos-layout {
        display: flex;
        height: calc(100vh - 70px); /* Full height minus header */
        gap: 0;
    }

    /* --- BAGIAN KIRI: KATALOG PRODUK --- */
    .catalog-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 1.5rem;
        overflow-y: hidden; /* Scroll handle inside content */
    }

    .catalog-header {
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1rem;
        background: white;
        padding: 1rem;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        align-items: center;
    }

    .search-box {
        flex-grow: 1;
        position: relative;
    }
    .search-box input {
        padding-left: 2.5rem;
        border-radius: 8px;
        border: 1px solid var(--card-border);
        background-color: #f9fafb;
    }
    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--secondary-text);
    }

    .category-select {
        min-width: 180px;
        border-radius: 8px;
        border: 1px solid var(--card-border);
        background-color: #f9fafb;
    }

    .product-grid-wrapper {
        flex: 1;
        overflow-y: auto;
        padding-right: 5px;
    }

    /* --- KARTU PRODUK --- */
    .product-card {
        background: white;
        border: 1px solid var(--card-border);
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }

    .product-img {
        height: 140px;
        width: 100%;
        object-fit: cover;
        background-color: #e5e7eb;
    }
    
    .product-info {
        padding: 1rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stock-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        backdrop-filter: blur(4px);
        font-weight: 600;
    }

    /* --- BAGIAN KANAN: KERANJANG --- */
    .cart-section {
        width: 400px;
        background: white;
        border-left: 1px solid var(--card-border);
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: -4px 0 15px rgba(0,0,0,0.03);
    }

    .cart-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: white;
    }

    .cart-items-wrapper {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        background-color: #f9fafb;
    }

    /* Item Keranjang */
    .cart-item {
        background: white;
        border: 1px solid var(--card-border);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        position: relative;
        transition: border-color 0.2s;
    }
    
    .cart-item:hover { border-color: #d1d5db; }

    .cart-item-title { font-weight: 600; color: #1f2937; margin-bottom: 0.25rem; }
    .cart-item-mods { font-size: 0.8rem; color: #10b981; margin-bottom: 0.5rem; line-height: 1.4; }
    .cart-item-note { font-size: 0.75rem; color: #ef4444; font-style: italic; background: #fef2f2; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-bottom: 0.5rem; }

    .qty-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: #f3f4f6;
        padding: 4px;
        border-radius: 8px;
    }
    .qty-btn {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: none;
        background: white;
        color: #374151;
        font-weight: bold;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.1s;
    }
    .qty-btn:hover { background: #e5e7eb; }
    .qty-btn:active { transform: scale(0.95); }

    .cart-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--card-border);
        background: white;
        z-index: 10;
    }

    .summary-row { display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--secondary-text); }
    .summary-total { display: flex; justify-content: space-between; margin-top: 1rem; margin-bottom: 1rem; font-size: 1.25rem; font-weight: 800; color: #111827; }

    .btn-checkout {
        width: 100%;
        padding: 1rem;
        border-radius: 10px;
        background-color: var(--primary-color);
        color: white;
        font-weight: 700;
        border: none;
        font-size: 1rem;
        letter-spacing: 0.5px;
        transition: background-color 0.2s;
    }
    .btn-checkout:hover:not(:disabled) { background-color: var(--primary-hover); }
    .btn-checkout:disabled { background-color: #d1d5db; cursor: not-allowed; }

    /* --- MODAL CUSTOMIZATION --- */
    .modifier-group { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; margin-bottom: 1rem; }
    .modifier-header { padding: 0.75rem 1rem; background: #f3f4f6; font-weight: 600; font-size: 0.9rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; }
    .modifier-body { padding: 0.5rem 1rem; }
    .modifier-option-label { display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px dashed #e5e7eb; cursor: pointer; }
    .modifier-option-label:last-child { border-bottom: none; }

    /* MOBILE RESPONSIVE */
    @media (max-width: 992px) {
        .pos-layout { flex-direction: column; overflow: visible; height: auto; }
        .catalog-section { overflow: visible; padding: 1rem; }
        .product-grid-wrapper { overflow: visible; }
        .cart-section {
            position: fixed; bottom: 0; left: 0; right: 0; width: 100%; height: 90vh;
            border-radius: 20px 20px 0 0; transform: translateY(110%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 2000; box-shadow: 0 -10px 25px rgba(0,0,0,0.1);
        }
        .cart-section.show { transform: translateY(0); }
        .mobile-float-btn { display: flex; }
    }

    .mobile-float-btn {
        display: none; position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
        width: 90%; max-width: 400px; background: #111827; color: white;
        padding: 12px 20px; border-radius: 50px; align-items: center; justify-content: space-between;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 1500; cursor: pointer;
    }
</style>

<div class="pos-layout">
    
    <!-- 1. BAGIAN KIRI: KATALOG PRODUK -->
    <div class="catalog-section">
        <!-- Header: Search & Filter -->
        <div class="catalog-header">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="productSearch" class="form-control shadow-none" placeholder="Cari menu favorit..." onkeyup="filterProducts()">
            </div>
            <select id="categoryFilter" class="form-select category-select shadow-none" onchange="filterProducts()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Grid Produk -->
        <div class="product-grid-wrapper">
            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3" id="productList">
                @foreach($products as $product)
                    <div class="col product-item" 
                         data-category="{{ $product->category_id }}" 
                         data-name="{{ strtolower($product->name) }}"
                         data-code="{{ strtolower($product->code) }}">
                        
                        <div class="product-card" onclick="handleProductClick({{ $product->id }})">
                            <div class="product-img">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                        <i class="fa-solid fa-image fa-2x opacity-25"></i>
                                    </div>
                                @endif
                                
                                @if($setting->enable_stock_badge)
                                    <span class="stock-badge">{{ $product->stock }} Left</span>
                                @endif
                            </div>
                            
                            <div class="product-info">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                                    <small class="text-muted">{{ $product->category->name ?? 'Umum' }}</small>
                                </div>
                                <div class="d-flex justify-content-between align-items-end mt-3">
                                    <span class="text-primary fw-bold fs-6">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                                    @if($product->modifiers->count() > 0)
                                        <i class="fa-solid fa-layer-group text-warning" title="Tersedia Add-on"></i>
                                    @else
                                        <div class="btn btn-sm btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:24px; height:24px;">
                                            <i class="fa-solid fa-plus text-muted" style="font-size: 10px;"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 2. BAGIAN KANAN: KERANJANG (CART) -->
    <div class="cart-section" id="cartSection">
        <div class="cart-header">
            <div>
                <h5 class="fw-bold mb-0">Pesanan Baru</h5>
                <small class="text-muted" id="orderDate">{{ date('d M Y') }}</small>
            </div>
            <button class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2" onclick="clearCart()" title="Bersihkan">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>

        <div class="cart-items-wrapper" id="cartList">
            <!-- Item Keranjang akan dirender di sini via JS -->
            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center text-muted opacity-50">
                <i class="fa-solid fa-basket-shopping fa-3x mb-3"></i>
                <p>Belum ada item dipilih</p>
            </div>
        </div>

        <div class="cart-footer shadow-lg">
            <div class="summary-row">
                <span>Subtotal</span>
                <span id="subTotalDisplay" class="fw-bold">Rp 0</span>
            </div>
            @if($setting->enable_tax)
            <div class="summary-row text-danger">
                <span>Pajak ({{ $setting->tax_rate }}%)</span>
                <span id="taxAmountText">Rp 0</span>
            </div>
            @endif
            
            <div class="summary-total">
                <span>Total</span>
                <span class="text-primary" id="finalTotalDisplay">Rp 0</span>
            </div>

            <input type="hidden" id="taxRate" value="{{ $setting->enable_tax ? $setting->tax_rate : 0 }}">
            
            <button class="btn-checkout" id="checkoutBtn" onclick="prepareCheckout()" disabled>
                Proses Pembayaran <i class="fa-solid fa-arrow-right ms-2"></i>
            </button>
            <button class="btn btn-light w-100 mt-2 d-lg-none text-muted" onclick="toggleCart()">Tutup</button>
        </div>
    </div>

</div>

<!-- TOMBOL MOBILE FLOAT (Untuk membuka keranjang di HP) -->
<div class="mobile-float-btn" onclick="toggleCart()">
    <div class="d-flex align-items-center gap-2">
        <span class="bg-white text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-weight:800; font-size: 0.8rem;" id="mobileCount">0</span>
        <span class="fw-bold">Lihat Pesanan</span>
    </div>
    <span class="fw-bold fs-6" id="mobileTotal">Rp 0</span>
</div>

<!-- 3. MODAL POP-UP ADD-ON -->
<div class="modal fade" id="modifierModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-white border-bottom">
                <div>
                    <h5 class="modal-title fw-bold" id="modifierProductName">Nama Produk</h5>
                    <small class="text-muted">Base Price: <span id="modifierProductBasePrice">Rp 0</span></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light" style="max-height: 60vh; overflow-y: auto;">
                <div id="modifierOptionsContainer">
                    <!-- Opsi Modifier Dirender JS -->
                </div>
                
                <div class="modifier-group p-3 bg-white border">
                    <label class="form-label small fw-bold text-muted mb-2"><i class="fa-regular fa-comment-dots me-1"></i> Catatan Khusus</label>
                    <textarea id="itemNotes" class="form-control bg-light" rows="2" placeholder="Contoh: Jangan terlalu pedas, pisahkan saus..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-white border-top justify-content-between">
                <div>
                    <small class="text-muted d-block">Extra</small>
                    <span class="h5 fw-bold text-success mb-0" id="modifierExtraPrice">Rp 0</span>
                </div>
                <button type="button" class="btn btn-primary px-4 fw-bold rounded-pill" id="confirmModifierBtn">
                    Simpan Pesanan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 4. MODAL CHECKOUT -->
<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('pos.store') }}" method="POST" id="checkoutForm" class="w-100">
            @csrf
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Konfirmasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center py-4 bg-light rounded-3 mb-4">
                        <small class="text-uppercase text-muted fw-bold ls-1">Total Tagihan</small>
                        <h1 class="display-5 fw-bold text-primary mb-0 mt-1" id="modalFinalTotal">Rp 0</h1>
                    </div>

                    @if($setting->enable_table_number)
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nomor Meja</label>
                        <input type="text" name="table_number" class="form-control form-control-lg text-center fw-bold bg-light border-0" placeholder="--">
                    </div>
                    @endif

                    <!-- Tab Metode Pembayaran -->
                    <ul class="nav nav-pills nav-fill mb-3 gap-2" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold border" id="pills-cash-tab" data-bs-toggle="pill" data-bs-target="#pills-cash" type="button" role="tab" onclick="togglePaymentMethod('cash')">
                                <i class="fa-solid fa-money-bill me-2"></i> Tunai
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold border" id="pills-qris-tab" data-bs-toggle="pill" data-bs-target="#pills-qris" type="button" role="tab" onclick="togglePaymentMethod('qris')">
                                <i class="fa-solid fa-qrcode me-2"></i> QRIS
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <!-- TUNAI -->
                        <div class="tab-pane fade show active" id="pills-cash" role="tabpanel">
                            <label class="form-label small fw-bold text-muted">Uang Diterima</label>
                            <input type="number" id="cashReceived" class="form-control form-control-lg text-center fw-bold" placeholder="0" oninput="calculateChange()">
                            <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                                <span class="fw-bold text-muted">Kembalian</span>
                                <span class="fw-bold text-success h4 mb-0" id="changeDisplay">Rp 0</span>
                            </div>
                        </div>
                        <!-- QRIS -->
                        <div class="tab-pane fade text-center" id="pills-qris" role="tabpanel">
                            <div class="p-4 bg-light rounded-3 mb-3 border">
                                <i class="fa-solid fa-qrcode fa-4x mb-3 text-dark"></i>
                                <div class="fw-bold small text-muted">Silakan Scan QRIS di Mesin EDC / Stand</div>
                            </div>
                            <input type="text" name="payment_ref" id="paymentRef" class="form-control text-center" placeholder="Masukkan No. Referensi (Opsional)">
                        </div>
                    </div>

                    <!-- Hidden Inputs -->
                    <input type="hidden" name="cart_data" id="cartDataInput">
                    <input type="hidden" name="total_amount" id="totalAmountInput"> 
                    <input type="hidden" name="pay_amount" id="payAmountInput">
                    <input type="hidden" name="change_amount" id="changeAmountInput">
                    <input type="hidden" name="payment_method" id="paymentMethodInput" value="cash">
                </div>
                <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow-sm fs-5" id="processPaymentBtn">
                        Bayar & Cetak <i class="fa-solid fa-print ms-2"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- DATABASE GLOBAL ---
    const productsDB = {
        @foreach($products as $p)
            {{ $p->id }}: @json($p),
        @endforeach
    };

    let cart = [];
    let currentProduct = {};
    let selectedModifiers = [];

    // --- 1. HANDLE CLICK PRODUK ---
    function handleProductClick(id) {
        const product = productsDB[id];
        if(!product) return;

        currentProduct = {
            id: product.id,
            name: product.name,
            basePrice: parseFloat(product.sell_price),
            maxStock: parseInt(product.stock),
            modifiers: product.modifiers || []
        };

        // Reset Modal UI
        selectedModifiers = [];
        document.getElementById('modifierProductName').innerText = currentProduct.name;
        document.getElementById('modifierProductBasePrice').innerText = formatRp(currentProduct.basePrice);
        document.getElementById('modifierExtraPrice').innerText = 'Rp 0';
        document.getElementById('modifierOptionsContainer').innerHTML = '';
        document.getElementById('itemNotes').value = '';

        if (currentProduct.modifiers && currentProduct.modifiers.length > 0) {
            renderModifierOptions(currentProduct.modifiers);
            new bootstrap.Modal(document.getElementById('modifierModal')).show();
        } else {
            addToCart(currentProduct.id, currentProduct.name, currentProduct.basePrice, currentProduct.maxStock, [], '');
        }
    }

    function renderModifierOptions(modifiers) {
        const container = document.getElementById('modifierOptionsContainer');
        let html = '';

        modifiers.forEach(mod => {
            if (mod.options && mod.options.length > 0) {
                let optionsHtml = '';
                mod.options.forEach(opt => {
                    const inputType = mod.is_multiple ? 'checkbox' : 'radio';
                    const inputName = mod.is_multiple ? `mod_opt_${mod.id}[]` : `mod_grp_${mod.id}`;
                    
                    optionsHtml += `
                        <label class="modifier-option-label">
                            <div class="d-flex align-items-center">
                                <input class="form-check-input me-2 modifier-input" 
                                       type="${inputType}" 
                                       name="${inputName}" 
                                       value="${opt.id}" 
                                       data-price="${opt.price}" 
                                       data-name="${opt.name}"
                                       data-group-id="${mod.id}"
                                       onchange="calculateModifierTotal()">
                                <span>${opt.name}</span>
                            </div>
                            <span class="badge bg-light text-dark border">${parseFloat(opt.price) > 0 ? '+' + formatRp(opt.price) : 'Gratis'}</span>
                        </label>
                    `;
                });

                html += `
                    <div class="modifier-group">
                        <div class="modifier-header">
                            <span>${mod.name}</span>
                            <span class="badge ${mod.type === 'required' ? 'bg-danger' : 'bg-success'} rounded-pill" style="font-size: 0.7rem;">
                                ${mod.type === 'required' ? 'Wajib' : 'Opsional'}
                            </span>
                        </div>
                        <div class="modifier-body">${optionsHtml}</div>
                    </div>
                `;
            }
        });
        container.innerHTML = html || '<p class="text-center text-muted">Tidak ada opsi.</p>';
    }

    function calculateModifierTotal() {
        let totalExtra = 0;
        selectedModifiers = [];
        document.querySelectorAll('.modifier-input:checked').forEach(input => {
            let price = parseFloat(input.dataset.price) || 0;
            totalExtra += price;
            selectedModifiers.push({
                id: input.value, name: input.dataset.name, price: price, group_id: input.dataset.groupId
            });
        });
        document.getElementById('modifierExtraPrice').innerText = formatRp(totalExtra);
    }

    document.getElementById('confirmModifierBtn').addEventListener('click', function() {
        const requiredGroups = currentProduct.modifiers.filter(m => m.type === 'required');
        let isValid = true;
        requiredGroups.forEach(group => {
            if (!selectedModifiers.some(m => m.group_id == group.id)) isValid = false;
        });

        if (!isValid) {
            Swal.fire({ title: 'Ups!', text: 'Mohon lengkapi pilihan yang WAJIB diisi.', icon: 'warning', confirmButtonColor: '#4f46e5' });
            return;
        }

        const notes = document.getElementById('itemNotes').value;
        const totalUnit = currentProduct.basePrice + selectedModifiers.reduce((sum, m) => sum + m.price, 0);
        
        bootstrap.Modal.getInstance(document.getElementById('modifierModal')).hide();
        addToCart(currentProduct.id, currentProduct.name, totalUnit, currentProduct.maxStock, selectedModifiers, notes);
    });

    // --- KERANJANG ---
    function addToCart(id, name, price, maxStock, modifiers, notes) {
        if(maxStock > 0) {
            const inCart = cart.filter(i => i.id === id).reduce((sum, i) => sum + i.qty, 0);
            if (inCart >= maxStock) {
                Swal.fire({ title: 'Stok Habis', icon: 'error', timer: 1500, showConfirmButton: false });
                return;
            }
        }

        const signature = JSON.stringify({ m: modifiers.map(m=>m.id).sort(), n: notes });
        const existing = cart.find(i => i.id === id && i.signature === signature);

        if (existing) existing.qty++;
        else cart.push({ id, name, basePrice: currentProduct.basePrice, price, qty: 1, maxStock, modifiers, notes, signature });
        
        renderCart();
    }

    function renderCart() {
        const list = document.getElementById('cartList');
        const btn = document.getElementById('checkoutBtn');
        let subTotal = 0;
        list.innerHTML = '';

        if (cart.length === 0) {
            list.innerHTML = `<div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted opacity-50">
                <i class="fa-solid fa-basket-shopping fa-3x mb-3"></i><p>Pesanan Kosong</p>
            </div>`;
            btn.disabled = true;
            updateSummary(0);
            return;
        }

        btn.disabled = false;

        cart.forEach((item, index) => {
            const itemTotal = item.price * item.qty;
            subTotal += itemTotal;

            let modHtml = '';
            if (item.modifiers.length > 0) {
                modHtml = `<div class="cart-item-mods">`;
                item.modifiers.forEach(m => { modHtml += `+ ${m.name} (${formatRp(m.price)})<br>`; });
                modHtml += `</div>`;
            }
            let noteHtml = item.notes ? `<div class="cart-item-note">"${item.notes}"</div>` : '';

            list.innerHTML += `
                <div class="cart-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div style="flex:1;">
                            <div class="cart-item-title">${item.name}</div>
                            ${modHtml} ${noteHtml}
                            <div class="text-muted small">@ ${formatRp(item.price)}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold mb-2">${formatRp(itemTotal)}</div>
                            <div class="qty-control">
                                <button class="qty-btn" onclick="updateQty(${index}, -1)">-</button>
                                <span style="font-size:0.9rem; min-width:20px; text-align:center;">${item.qty}</span>
                                <button class="qty-btn" onclick="updateQty(${index}, 1)">+</button>
                            </div>
                        </div>
                    </div>
                </div>`;
        });
        updateSummary(subTotal);
    }

    function updateQty(index, change) {
        const item = cart[index];
        const newQty = item.qty + change;
        if (newQty < 1) {
            Swal.fire({
                title: 'Hapus Item?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Batal', confirmButtonColor: '#ef4444'
            }).then(res => { if(res.isConfirmed) { cart.splice(index, 1); renderCart(); } });
        } else if (item.maxStock > 0 && newQty > item.maxStock) {
            Swal.fire({ title: 'Stok Terbatas', icon: 'warning', timer: 1000, showConfirmButton: false });
        } else {
            item.qty = newQty; renderCart();
        }
    }

    function clearCart() {
        if(cart.length > 0) {
            Swal.fire({ title: 'Hapus Semua?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya', confirmButtonColor: '#ef4444' }).then(res => {
                if(res.isConfirmed) { cart = []; renderCart(); }
            });
        }
    }

    function updateSummary(subTotal) {
        const taxRate = parseFloat(document.getElementById('taxRate').value) || 0;
        const taxAmount = subTotal * (taxRate / 100);
        const total = subTotal + taxAmount;

        document.getElementById('subTotalDisplay').innerText = formatRp(subTotal);
        if(document.getElementById('taxAmountText')) document.getElementById('taxAmountText').innerText = formatRp(taxAmount);
        document.getElementById('finalTotalDisplay').innerText = formatRp(total);
        
        // Modal Update
        document.getElementById('modalFinalTotal').innerText = formatRp(total);
        document.getElementById('totalAmountInput').value = total;
        
        // Mobile Float
        document.getElementById('mobileTotal').innerText = formatRp(total);
        document.getElementById('mobileCount').innerText = cart.reduce((a, b) => a + b.qty, 0);
        document.querySelector('.mobile-float-btn').style.display = cart.length > 0 ? 'flex' : 'none';
    }

    // --- CHECKOUT ---
    function prepareCheckout() {
        document.getElementById('cashReceived').value = '';
        document.getElementById('changeDisplay').innerText = 'Rp 0';
        
        // Reset Tabs Style (Bootstrap 5 bug workaround if needed)
        const triggerEl = document.querySelector('#pills-cash-tab');
        bootstrap.Tab.getOrCreateInstance(triggerEl).show();
        togglePaymentMethod('cash');
        
        new bootstrap.Modal(document.getElementById('checkoutModal')).show();
        setTimeout(() => document.getElementById('cashReceived').focus(), 500);
    }

    function togglePaymentMethod(method) {
        document.getElementById('paymentMethodInput').value = method;
        calculateChange();
    }

    function calculateChange() {
        const total = parseFloat(document.getElementById('totalAmountInput').value) || 0;
        const cash = parseFloat(document.getElementById('cashReceived').value) || 0;
        const method = document.getElementById('paymentMethodInput').value;
        
        if (method === 'cash') {
            const change = cash - total;
            const el = document.getElementById('changeDisplay');
            el.innerText = formatRp(change);
            el.className = change >= 0 ? "fw-bold text-success h4 mb-0" : "fw-bold text-danger h4 mb-0";
            document.getElementById('processPaymentBtn').disabled = change < 0;
        } else {
            document.getElementById('processPaymentBtn').disabled = false;
        }
    }

    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const method = document.getElementById('paymentMethodInput').value;
        const total = parseFloat(document.getElementById('totalAmountInput').value);
        const cash = parseFloat(document.getElementById('cashReceived').value);

        if (method === 'cash' && cash < total) return Swal.fire('Uang Kurang', '', 'error');

        // PREPARE DATA
        const cartData = cart.map(item => ({
            product_id: item.id,
            product_name: item.name,
            qty: item.qty,
            base_price: item.basePrice,
            sell_price: item.price,
            subtotal: item.price * item.qty,
            notes: item.notes,
            modifiers: item.modifiers
        }));

        document.getElementById('cartDataInput').value = JSON.stringify(cartData);
        document.getElementById('payAmountInput').value = method === 'cash' ? cash : total;
        document.getElementById('changeAmountInput').value = method === 'cash' ? (cash - total) : 0;
        this.submit();
    });

    // Helper
    function formatRp(num) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(num); }
    function filterProducts() {
        const key = document.getElementById('productSearch').value.toLowerCase();
        const cat = document.getElementById('categoryFilter').value;
        document.querySelectorAll('.product-item').forEach(item => {
            const matchKey = item.dataset.name.includes(key) || item.dataset.code.includes(key);
            const matchCat = !cat || item.dataset.category == cat;
            item.style.display = matchKey && matchCat ? 'block' : 'none';
        });
    }
    function toggleCart() { document.getElementById('cartSection').classList.toggle('show'); }
</script>
@endpush