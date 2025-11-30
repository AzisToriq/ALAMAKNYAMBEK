@extends('layouts.admin')

@section('title', 'Data Supplier')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-truck-field me-2"></i> Daftar Supplier</h6>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
            <i class="fa-solid fa-plus"></i> Tambah Supplier
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Supplier</th>
                        <th>Kontak (HP)</th>
                        <th>Alamat</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-bold">{{ $supplier->name }}</td>
                        <td>
                            @if($supplier->phone)
                                <a href="https://wa.me/{{ $supplier->phone }}" target="_blank" class="text-decoration-none text-success">
                                    <i class="fa-brands fa-whatsapp"></i> {{ $supplier->phone }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{Str::limit($supplier->address, 50) ?? '-' }}</td>
                        <td class="text-end">
                            <button class="btn btn-warning btn-sm me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editSupplierModal{{ $supplier->id }}">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline on-delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Supplier</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Supplier</label>
                                            <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">No. HP / WA</label>
                                            <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Alamat Lengkap</label>
                                            <textarea name="address" class="form-control" rows="3">{{ $supplier->address }}</textarea>
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
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data supplier</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addSupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Supplier Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="PT. Maju Mundur">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP / WA</label>
                        <input type="text" name="phone" class="form-control" placeholder="0812...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Jl. Raya..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('.on-delete-form').on('submit', function(e){
        e.preventDefault();
        var form = this;
        Swal.fire({
            title: 'Hapus Data?',
            text: "Data tidak bisa kembali!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
</script>
@endpush