@extends('layouts.admin')

@section('title', 'Riwayat Stok')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="{{ url()->previous() }}" class="btn btn-light btn-sm me-3 rounded-circle border shadow-sm d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            
            <h6 class="mb-0 fw-bold">
                <i class="fa-solid fa-clock-rotate-left me-2"></i> Log Mutasi Stok
            </h6>
        </div>

        <a href="{{ route('inventory.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus-minus me-1"></i> Input Stok Baru
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Produk</th>
                        <th>Tipe</th>
                        <th class="text-center">Jml</th>
                        <th class="text-center">Sisa Stok</th>
                        <th>User</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>
                            <small class="fw-bold">{{ date('d M Y', strtotime($log->date)) }}</small><br>
                            <small class="text-muted">{{ $log->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $log->product->name }}</div>
                            <small class="text-muted">{{ $log->product->code }}</small>
                        </td>
                        <td>
                            @if($log->type == 'in')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-3">Masuk</span>
                            @elseif($log->type == 'out')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3">Keluar (Rusak)</span>
                            @elseif($log->type == 'sale')
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3">Terjual</span>
                            @elseif($log->type == 'adjustment')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3">Opname</span>
                            @endif
                        </td>
                        <td class="text-center fw-bold">
                            @if(in_array($log->type, ['in', 'return']))
                                <span class="text-success">+{{ $log->qty }}</span>
                            @else
                                <span class="text-danger">-{{ $log->qty }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $log->last_stock }}</span>
                        </td>
                        <td><small>{{ $log->user->name }}</small></td>
                        <td><small class="text-muted">{{ $log->note ?? '-' }}</small></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-clipboard-list fa-3x mb-3 opacity-50"></i>
                            <p>Belum ada riwayat stok.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection