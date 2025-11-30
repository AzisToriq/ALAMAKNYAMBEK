@extends('layouts.admin')

@section('title', 'Laporan Transaksi')

@section('content')
<style>
    .transaction-card {
        transition: all 0.3s ease;
        border-left: 4px solid #e0e0e0;
    }
    .transaction-card:hover {
        border-left-color: #007bff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .addon-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin: 3px;
    }
    .filter-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 25px;
    }
    .stats-card {
        border-radius: 15px;
        border: none;
        transition: transform 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .invoice-badge {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: bold;
    }
    .timeline-badge {
        width: 10px;
        height: 10px;
        background: #007bff;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }
    @media (max-width: 768px) {
        .filter-card { padding: 15px; }
        .table-responsive { font-size: 0.85rem; }
        .addon-badge { font-size: 0.7rem; padding: 3px 8px; }
    }
</style>

<div class="container-fluid">
    <!-- Filter Card -->
    <div class="filter-card shadow-lg">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h4 class="mb-0 fw-bold">
                    <i class="fa-solid fa-chart-line me-2"></i>
                    Laporan Transaksi Penjualan
                </h4>
                <small class="opacity-75">Monitor dan analisis riwayat transaksi Anda</small>
            </div>
            <div class="col-md-6">
                <form method="GET" action="{{ route('transactions.index') }}" class="d-flex gap-2 flex-wrap">
                    <input type="date" name="date_from" class="form-control" 
                           value="{{ request('date_from') }}" 
                           style="background: rgba(255,255,255,0.9); border: none;">
                    <input type="date" name="date_to" class="form-control" 
                           value="{{ request('date_to') }}"
                           style="background: rgba(255,255,255,0.9); border: none;">
                    <button type="submit" class="btn btn-light px-4">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                    @if(request('date_from') || request('date_to'))
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-light">
                            <i class="fa-solid fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    @if($transactions->count() > 0)
    @php
        // Hitung total pendapatan untuk semua transaksi yang difilter
        $totalRevenue = \App\Models\Transaction::query()
            ->when(request('date_from'), function($q) {
                $q->whereDate('created_at', '>=', request('date_from'));
            })
            ->when(request('date_to'), function($q) {
                $q->whereDate('created_at', '<=', request('date_to'));
            })
            ->sum('grand_total');
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stats-card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75 small">Total Transaksi</p>
                            <h2 class="mb-0 fw-bold">{{ $transactions->total() }}</h2>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75 small">Total Pendapatan</p>
                            <h4 class="mb-0 fw-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card shadow-sm border-0 bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75 small">Halaman Saat Ini</p>
                            <h2 class="mb-0 fw-bold">{{ $transactions->currentPage() }} / {{ $transactions->lastPage() }}</h2>
                        </div>
                        <div class="fs-1 opacity-50">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Transaction List -->
    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="card-body p-0">
            @forelse($transactions as $trx)
            <div class="transaction-card p-4 border-bottom">
                <div class="row">
                    <!-- Header Info -->
                    <div class="col-lg-3 col-md-4 mb-3 mb-md-0">
                        <div class="d-flex flex-column h-100">
                            <span class="invoice-badge mb-2">
                                <i class="fa-solid fa-hashtag me-1"></i>
                                {{ $trx->invoice_code }}
                            </span>
                            <div class="d-flex align-items-center text-muted mb-2">
                                <i class="fa-solid fa-user-circle me-2"></i>
                                <small>{{ $trx->user->name ?? 'Kasir' }}</small>
                            </div>
                            <div class="d-flex align-items-center text-muted mb-2">
                                <span class="timeline-badge"></span>
                                <small>{{ date('d M Y, H:i', strtotime($trx->created_at)) }}</small>
                            </div>
                            @if($trx->table_number)
                                <span class="badge bg-info mt-auto" style="width: fit-content;">
                                    <i class="fa-solid fa-table me-1"></i>
                                    Meja {{ $trx->table_number }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Items Detail -->
                    <div class="col-lg-6 col-md-8 mb-3 mb-lg-0">
                        <h6 class="text-muted mb-3 fw-bold">
                            <i class="fa-solid fa-shopping-bag me-2"></i>Detail Pesanan
                        </h6>
                        
                        @foreach($trx->details as $detail)
                            <div class="mb-3 p-3 bg-light rounded-3">
                                <!-- Product Info -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-dark me-2">{{ $detail->qty }}x</span>
                                            <strong>{{ $detail->product ? $detail->product->name : 'Produk Terhapus' }}</strong>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="small text-muted">@ Rp {{ number_format($detail->sell_price, 0, ',', '.') }}</div>
                                        <strong class="text-primary">Rp {{ number_format($detail->sell_price * $detail->qty, 0, ',', '.') }}</strong>
                                    </div>
                                </div>

                                <!-- Add-ons -->
                                @php
                                    $mods = $detail->modifiers_data ?? [];
                                    $totalModsPrice = 0;
                                @endphp

                                @if(!empty($mods) && is_array($mods))
                                    <div class="mt-2 pt-2 border-top">
                                        <small class="text-muted fw-bold d-block mb-2">
                                            <i class="fa-solid fa-plus-circle me-1"></i>Add-ons Terpilih:
                                        </small>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($mods as $mod)
                                                @php 
                                                    $mName = is_array($mod) ? ($mod['name'] ?? '-') : ($mod->name ?? '-');
                                                    $mPrice = is_array($mod) ? ($mod['price'] ?? 0) : ($mod->price ?? 0);
                                                    $totalModsPrice += $mPrice * $detail->qty;
                                                @endphp
                                                <span class="addon-badge">
                                                    <i class="fa-solid fa-sparkles"></i>
                                                    {{ $mName }}
                                                    @if($mPrice > 0)
                                                        <small>(+Rp {{ number_format($mPrice, 0, ',', '.') }})</small>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                        @if($totalModsPrice > 0)
                                            <div class="text-end mt-2 pt-2 border-top">
                                                <small class="text-muted">Total Add-ons:</small>
                                                <strong class="text-success ms-2">
                                                    +Rp {{ number_format($totalModsPrice, 0, ',', '.') }}
                                                </strong>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Notes -->
                                @if($detail->notes)
                                    <div class="alert alert-warning border-0 mt-2 mb-0 py-2">
                                        <small>
                                            <i class="fa-solid fa-note-sticky me-1"></i>
                                            <strong>Catatan:</strong> {{ $detail->notes }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Payment Summary -->
                    <div class="col-lg-3">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <h6 class="text-muted mb-3 fw-bold">
                                    <i class="fa-solid fa-calculator me-2"></i>Ringkasan
                                </h6>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <small class="text-muted">Subtotal</small>
                                    <small>Rp {{ number_format($trx->total_price, 0, ',', '.') }}</small>
                                </div>
                                
                                @if($trx->tax_amount > 0)
                                <div class="d-flex justify-content-between mb-2 text-info">
                                    <small>Pajak</small>
                                    <small>+Rp {{ number_format($trx->tax_amount, 0, ',', '.') }}</small>
                                </div>
                                @endif
                                
                                <div class="d-flex justify-content-between pt-2 border-top mb-3">
                                    <strong>Total</strong>
                                    <strong class="text-success fs-5">Rp {{ number_format($trx->grand_total, 0, ',', '.') }}</strong>
                                </div>

                                <!-- Payment Method -->
                                @if($trx->payment_method == 'qris')
                                    <span class="badge bg-dark w-100 py-2">
                                        <i class="fa-solid fa-qrcode me-1"></i> QRIS
                                    </span>
                                @else
                                    <span class="badge bg-success w-100 py-2">
                                        <i class="fa-solid fa-money-bill-wave me-1"></i> TUNAI
                                    </span>
                                @endif

                                <!-- Print Button -->
                                <a href="{{ route('transactions.print', $trx->id) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary w-100 mt-3">
                                    <i class="fa-solid fa-print me-1"></i> Cetak Struk
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fa-solid fa-inbox fa-4x text-muted opacity-25"></i>
                </div>
                <h5 class="text-muted">Belum Ada Transaksi</h5>
                <p class="text-muted">Transaksi yang masuk akan tampil di sini</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($transactions->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $transactions->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection