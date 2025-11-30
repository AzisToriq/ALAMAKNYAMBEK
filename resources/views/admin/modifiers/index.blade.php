@extends('layouts.admin')

@section('title', 'Manajemen Add-ons / Modifier')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fa-solid fa-puzzle-piece me-2"></i> Manajemen Add-ons / Modifier</h2>
        <a href="{{ route('modifiers.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Tambah Modifier
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fa-solid fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($modifiers->count() > 0)
        <div class="row">
            @foreach($modifiers as $modifier)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $modifier->name }}</h5>
                        <div>
                            <span class="badge bg-light text-dark">
                                {{ $modifier->type === 'required' ? 'Wajib' : 'Opsional' }}
                            </span>
                            <span class="badge bg-light text-dark">
                                {{ $modifier->is_multiple ? 'Multi-Pilih' : 'Pilih Satu' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Options List -->
                        <h6 class="fw-bold text-muted mb-2">Pilihan:</h6>
                        <ul class="list-group list-group-flush mb-3">
                            @foreach($modifier->options as $option)
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>{{ $option->name }}</span>
                                <span class="text-success fw-bold">
                                    {{ $option->price > 0 ? '+Rp ' . number_format($option->price, 0, ',', '.') : 'Gratis' }}
                                </span>
                            </li>
                            @endforeach
                        </ul>

                        <!-- Produk yang Menggunakan -->
                        <h6 class="fw-bold text-muted mb-2">Digunakan di:</h6>
                        <div class="mb-3">
                            @if($modifier->products->count() > 0)
                                @foreach($modifier->products as $product)
                                    <span class="badge bg-info me-1 mb-1">{{ $product->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted small">Belum di-assign ke produk</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-light d-flex justify-content-between">
                        <a href="{{ route('modifiers.edit', $modifier->id) }}" class="btn btn-sm btn-warning">
                            <i class="fa-solid fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('modifiers.destroy', $modifier->id) }}" method="POST" onsubmit="return confirm('Yakin hapus modifier ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fa-solid fa-puzzle-piece fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada modifier</h5>
                <p class="text-muted">Klik tombol "Tambah Modifier" untuk membuat add-ons pertama Anda</p>
                <a href="{{ route('modifiers.create') }}" class="btn btn-primary mt-2">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Modifier
                </a>
            </div>
        </div>
    @endif
</div>
@endsection