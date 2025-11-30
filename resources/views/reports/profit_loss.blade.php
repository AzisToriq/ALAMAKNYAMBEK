@extends('layouts.admin')

@section('title', 'Laporan Laba Rugi')

@section('content')
<!-- CSS KHUSUS PRINT -->
<style>
    @media print {
        .sidebar, .top-navbar, .btn, .no-print, form, .nav-tabs {
            display: none !important;
        }
        
        .main-content {
            margin-left: 0 !important; padding: 0 !important; width: 100% !important;
        }
        body {
            background-color: white !important;
            -webkit-print-color-adjust: exact;
        }
        .card {
            box-shadow: none !important; border: 1px solid #ddd !important;
        }

        .tab-pane {
            display: block !important; opacity: 1 !important; visibility: visible !important;
            margin-bottom: 30px; page-break-inside: avoid;
        }

        body.print-sales-only #expenses, 
        body.print-sales-only #summary-cards { display: none !important; }
        body.print-sales-only #sales { display: block !important; }

        body.print-expenses-only #sales, 
        body.print-expenses-only #summary-cards { display: none !important; }
        body.print-expenses-only #expenses { display: block !important; }

        body.print-summary-only .tab-content { display: none !important; }
    }
</style>

<div class="row mb-4 no-print"> 
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <form action="{{ route('reports.profit_loss') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fa-solid fa-filter me-2"></i> Tampilkan
                            </button>
                            
                            <div class="btn-group flex-fill">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-print me-2"></i> Cetak / Export
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item" type="button" onclick="printReport('all')"><i class="fa-solid fa-file-lines me-2"></i> Laporan Lengkap</button></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><button class="dropdown-item" type="button" onclick="printReport('summary')"><i class="fa-solid fa-chart-pie me-2"></i> Ringkasan Saja</button></li>
                                    <li><button class="dropdown-item" type="button" onclick="printReport('sales')"><i class="fa-solid fa-cart-shopping me-2"></i> Rincian Penjualan</button></li>
                                    <li><button class="dropdown-item" type="button" onclick="printReport('expenses')"><i class="fa-solid fa-wallet me-2"></i> Rincian Pengeluaran</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- HEADER CETAK -->
<div class="d-none d-print-block text-center mb-4">
    <h3 id="printTitle">LAPORAN LABA RUGI</h3>
    <p class="mb-0">{{ $setting->shop_name ?? 'POS System' }}</p>
    <small>Periode: {{ date('d M Y', strtotime($startDate)) }} - {{ date('d M Y', strtotime($endDate)) }}</small>
</div>

<!-- RINGKASAN KEUANGAN -->
<div class="row mb-4" id="summary-cards">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 text-white-50">Total Omzet Barang</h6>
                    <i class="fa-solid fa-money-bill-wave opacity-50"></i>
                </div>
                <h4 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                <small class="text-white-50">Pemasukan (Tanpa Pajak)</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 text-muted">HPP (Modal Barang)</h6>
                    <i class="fa-solid fa-boxes-packing text-muted opacity-50"></i>
                </div>
                <h4 class="fw-bold mb-0 text-danger">Rp {{ number_format($totalHpp, 0, ',', '.') }}</h4>
                <small class="text-muted">Modal Awal Barang Terjual</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 text-muted">Biaya Operasional</h6>
                    <i class="fa-solid fa-wallet text-muted opacity-50"></i>
                </div>
                <h4 class="fw-bold mb-0 text-danger">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h4>
                <small class="text-muted">Listrik, Gaji, Sewa, dll</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm h-100 {{ $netProfit >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 text-white-50">Laba Bersih (Net Profit)</h6>
                    <i class="fa-solid fa-chart-line opacity-50"></i>
                </div>
                <h3 class="fw-bold mb-0">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
                <small class="text-white-50">{{ $netProfit >= 0 ? 'Untung' : 'Rugi' }}</small>
            </div>
        </div>
    </div>
</div>

<!-- BAGIAN DETAIL -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white p-0 no-print"> 
        <ul class="nav nav-tabs card-header-tabs m-0" id="reportTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-bold py-3 px-4 border-top-0 border-start-0 border-end-0" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales">
                    <i class="fa-solid fa-cart-shopping me-2"></i> Rincian Penjualan
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold py-3 px-4 border-top-0 border-start-0 border-end-0" id="expenses-tab" data-bs-toggle="tab" data-bs-target="#expenses">
                    <i class="fa-solid fa-receipt me-2"></i> Rincian Pengeluaran
                </button>
            </li>
        </ul>
    </div>
    
    <div class="card-body p-4">
        <div class="tab-content" id="reportTabsContent">
            
            <!-- TAB 1: RINCIAN PENJUALAN -->
            <div class="tab-pane fade show active" id="sales" role="tabpanel">
                <h5 class="d-none d-print-block mb-3 fw-bold text-uppercase border-bottom pb-2">Rincian Penjualan</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>No. Invoice</th>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Modal</th>
                                <th class="text-end">Harga Jual</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-end">Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salesData as $item)
                            @php
                                // Hitung modal dan profit dinamis
                                $currentModal = $item->product ? $item->product->buy_price : $item->base_price;
                                $currentProfit = ($item->sell_price - $currentModal) * $item->qty;
                                
                                // ✅ PERBAIKAN: Langsung pakai array, TIDAK perlu json_decode
                                $modifiers = $item->modifiers_data ?? [];
                            @endphp

                            <tr>
                                <td>{{ date('d/m/Y H:i', strtotime($item->created_at)) }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $item->transaction->invoice_code }}</span></td>
                                <td>
                                    <strong>{{ $item->product ? $item->product->name : 'Produk Terhapus' }}</strong>
                                    
                                    {{-- TAMPILKAN ADD-ONS --}}
                                    @if(!empty($modifiers) && is_array($modifiers))
                                        <br><small class="text-muted" style="font-size: 0.75rem;">
                                            <i class="fa-solid fa-plus-circle me-1"></i>
                                            @foreach($modifiers as $mod)
                                                @php
                                                    $mName = is_array($mod) ? ($mod['name'] ?? '-') : ($mod->name ?? '-');
                                                @endphp
                                                {{ $mName }}{{ !$loop->last ? ', ' : '' }}
                                            @endforeach
                                        </small>
                                    @endif
                                    
                                    {{-- TAMPILKAN NOTES --}}
                                    @if($item->notes)
                                        <br><small class="text-danger fst-italic" style="font-size: 0.7rem;">
                                            <i class="fa-solid fa-note-sticky me-1"></i>{{ $item->notes }}
                                        </small>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ $item->qty }}</td>
                                <td class="text-end text-muted">Rp {{ number_format($currentModal, 0, ',', '.') }}</td>
                                <td class="text-end text-muted">Rp {{ number_format($item->sell_price, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                <td class="text-end {{ $currentProfit >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                    {{ $currentProfit >= 0 ? '+' : '' }}Rp {{ number_format($currentProfit, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center py-5 text-muted">Tidak ada data penjualan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 2: RINCIAN PENGELUARAN -->
            <div class="tab-pane fade" id="expenses" role="tabpanel">
                <h5 class="d-none d-print-block mt-4 mb-3 fw-bold text-uppercase border-bottom pb-2">Rincian Pengeluaran</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Pengeluaran</th>
                                <th>Catatan</th>
                                <th class="text-end">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expensesData as $exp)
                            <tr>
                                <td>{{ date('d M Y', strtotime($exp->date)) }}</td>
                                <td class="fw-bold">{{ $exp->name }}</td>
                                <td class="text-muted small">{{ $exp->note ?? '-' }}</td>
                                <td class="text-end fw-bold text-danger">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">Tidak ada pengeluaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function printReport(type) {
        document.body.classList.remove('print-all', 'print-sales-only', 'print-expenses-only', 'print-summary-only');
        
        let titleEl = document.getElementById('printTitle');
        
        if (type === 'all') {
            document.body.classList.add('print-all');
            titleEl.innerText = 'LAPORAN LABA RUGI LENGKAP';
        } 
        else if (type === 'sales') {
            document.body.classList.add('print-sales-only');
            titleEl.innerText = 'LAPORAN RINCIAN PENJUALAN';
        } 
        else if (type === 'expenses') {
            document.body.classList.add('print-expenses-only');
            titleEl.innerText = 'LAPORAN BIAYA OPERASIONAL';
        }
        else if (type === 'summary') {
            document.body.classList.add('print-summary-only');
            titleEl.innerText = 'RINGKASAN LABA RUGI';
        }

        window.print();
    }
</script>
@endpush