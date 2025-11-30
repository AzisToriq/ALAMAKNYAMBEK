@extends('layouts.admin')

@section('title', 'Biaya Operasional')

@section('content')
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-plus-circle me-2"></i> Input Pengeluaran</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('expenses.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pengeluaran</label>
                        <input type="text" name="name" class="form-control" placeholder="Cth: Token Listrik / Gaji" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="number" name="amount" class="form-control" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea name="note" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-save me-1"></i> Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-wallet me-2"></i> Riwayat Pengeluaran</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Nominal</th>
                                <th>User</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                            <tr>
                                <td>{{ date('d M Y', strtotime($expense->date)) }}</td>
                                <td>
                                    <span class="fw-bold">{{ $expense->name }}</span><br>
                                    <small class="text-muted">{{ $expense->note }}</small>
                                </td>
                                <td class="fw-bold text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                <td><small>{{ $expense->user->name }}</small></td>
                                <td class="text-end">
                                    <!-- TOMBOL EDIT (KUNING) -->
                                    <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editExpenseModal{{ $expense->id }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <!-- TOMBOL HAPUS (MERAH) -->
                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="d-inline on-delete-form">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm text-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- MODAL EDIT (POPUP) -->
                            <div class="modal fade" id="editExpenseModal{{ $expense->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                                        @csrf
                                        @method('PUT') <!-- Method Update -->
                                        
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Pengeluaran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Tanggal</label>
                                                    <input type="date" name="date" class="form-control" value="{{ $expense->date }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Pengeluaran</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $expense->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nominal (Rp)</label>
                                                    <input type="number" name="amount" class="form-control" value="{{ $expense->amount }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Catatan</label>
                                                    <textarea name="note" class="form-control" rows="2">{{ $expense->note }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Update Perubahan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- END MODAL -->

                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data pengeluaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $expenses->links() }}</div>
            </div>
        </div>
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
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
</script>
@endpush