@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Ringkasan performa bisnis Anda hari ini')

@section('content')

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <!-- Total Products -->
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #FF6B35;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Total Menu</p>
                        <h2 class="fw-bold mb-0" style="color: #FF6B35;">{{ $totalProducts }}</h2>
                    </div>
                    <div class="bg-gradient rounded-3 p-3" style="background: linear-gradient(135deg, #FF6B35 0%, #E85A2A 100%);">
                        <i class="fa-solid fa-bowl-food fa-lg text-white"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-circle text-success me-2" style="font-size: 8px;"></i>
                    <small class="text-muted">Item terdaftar</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #F59E0B;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Stok Menipis</p>
                        <h2 class="fw-bold mb-0" style="color: #F59E0B;">{{ $lowStock }}</h2>
                    </div>
                    <div class="bg-gradient rounded-3 p-3" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
                        <i class="fa-solid fa-triangle-exclamation fa-lg text-white"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-circle {{ $lowStock > 0 ? 'text-danger' : 'text-success' }} me-2" style="font-size: 8px;"></i>
                    <small class="text-muted">
                        @if($lowStock > 0)
                            <strong class="text-danger">Perlu restock!</strong>
                        @else
                            Stok aman
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Sales -->
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #10B981;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Penjualan Hari Ini</p>
                        <h4 class="fw-bold mb-0" style="color: #10B981; font-size: 1.5rem;">
                            Rp {{ number_format($todaySales, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="bg-gradient rounded-3 p-3" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%);">
                        <i class="fa-solid fa-cash-register fa-lg text-white"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-calendar-day me-2 text-muted"></i>
                    <small class="text-muted">{{ date('d M Y') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Net Profit -->
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #004E89;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Laba Bersih (Bulan)</p>
                        <h4 class="fw-bold mb-0 {{ $netProfit < 0 ? 'text-danger' : '' }}" style="color: #004E89; font-size: 1.5rem;">
                            Rp {{ number_format($netProfit, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="bg-gradient rounded-3 p-3" style="background: linear-gradient(135deg, #004E89 0%, #003A63 100%);">
                        <i class="fa-solid fa-chart-line fa-lg text-white"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-calendar me-2 text-muted"></i>
                    <small class="text-muted">{{ date('F Y') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts & Top Products -->
<div class="row g-4">
    <!-- Sales Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Grafik Penjualan</h5>
                        <p class="text-muted mb-0 small">7 Hari terakhir</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-calendar-days me-1"></i> Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">7 Hari</a></li>
                            <li><a class="dropdown-item" href="#">30 Hari</a></li>
                            <li><a class="dropdown-item" href="#">90 Hari</a></li>
                        </ul>
                    </div>
                </div>
                <div style="height: 300px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Menu Terlaris</h5>
                        <p class="text-muted mb-0 small">Top 5 bestseller</p>
                    </div>
                    <i class="fa-solid fa-crown text-warning fa-lg"></i>
                </div>

                <div class="top-products-list">
                    @forelse($topProducts as $item)
                    <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="rank-badge me-3">
                            <div class="bg-gradient text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" 
                                 style="width: 44px; height: 44px; font-size: 18px; 
                                 background: linear-gradient(135deg, 
                                 {{ $loop->iteration == 1 ? '#FFD700, #FFA500' : ($loop->iteration == 2 ? '#C0C0C0, #A8A8A8' : ($loop->iteration == 3 ? '#CD7F32, #B8860B' : '#FF6B35, #E85A2A')) }}) !important;">
                                #{{ $loop->iteration }}
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold" style="font-size: 14px;">{{ $item->product->name }}</h6>
                            <small class="text-muted">{{ $item->product->code }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge rounded-pill px-3 py-2" 
                                  style="background: linear-gradient(135deg, #FF6B35 0%, #E85A2A 100%); font-size: 12px;">
                                {{ $item->total_qty }} Sold
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fa-solid fa-chart-simple fa-3x text-muted opacity-25 mb-3"></i>
                        <p class="text-muted mb-0">Belum ada penjualan</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">
                    <i class="fa-solid fa-bolt text-warning me-2"></i>
                    Quick Actions
                </h5>
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <a href="{{ route('pos.index') }}" class="btn btn-lg w-100 border-0 text-start shadow-sm" style="background: linear-gradient(135deg, #FF6B35 0%, #E85A2A 100%); color: white;">
                            <i class="fa-solid fa-cash-register d-block mb-2 fa-2x"></i>
                            <span class="d-block fw-semibold">Buka Kasir</span>
                            <small class="opacity-75">Point of Sale</small>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('products.index') }}" class="btn btn-lg w-100 border-0 text-start shadow-sm" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white;">
                            <i class="fa-solid fa-bowl-food d-block mb-2 fa-2x"></i>
                            <span class="d-block fw-semibold">Kelola Menu</span>
                            <small class="opacity-75">Produk & Add-ons</small>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('transactions.index') }}" class="btn btn-lg w-100 border-0 text-start shadow-sm" style="background: linear-gradient(135deg, #004E89 0%, #003A63 100%); color: white;">
                            <i class="fa-solid fa-receipt d-block mb-2 fa-2x"></i>
                            <span class="d-block fw-semibold">Riwayat</span>
                            <small class="opacity-75">Transaksi</small>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('reports.profit_loss') }}" class="btn btn-lg w-100 border-0 text-start shadow-sm" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: white;">
                            <i class="fa-solid fa-chart-pie d-block mb-2 fa-2x"></i>
                            <span class="d-block fw-semibold">Laporan</span>
                            <small class="opacity-75">Laba Rugi</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labels = @json($chartLabels);
    const data = @json($chartData);

    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Gradient for chart
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(255, 107, 53, 0.3)');
    gradient.addColorStop(1, 'rgba(255, 107, 53, 0.01)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Penjualan',
                data: data,
                backgroundColor: gradient,
                borderColor: '#FF6B35',
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#FF6B35',
                pointBorderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    borderColor: '#FF6B35',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { 
                        borderDash: [5, 5],
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000) + 'k';
                        },
                        font: { size: 11 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
</script>
@endpush