@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold"><i class="fa-solid fa-users-gear me-2"></i> Daftar Pengguna Sistem</h6>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fa-solid fa-user-plus"></i> Tambah User
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama & Email</th>
                        <th>Role (Jabatan)</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $user->name }}</div>
                            <small class="text-muted">{{ $user->email }}</small>
                        </td>
                        <td>
                            @if($user->role == 'owner')
                                <span class="badge bg-primary">Owner</span>
                            @elseif($user->role == 'admin')
                                <span class="badge bg-info text-dark">Admin Gudang</span>
                            @else
                                <span class="badge bg-secondary">Kasir</span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">Aktif</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Non-Aktif (Banned)</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            
                            @if(Auth::id() != $user->id)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline on-delete-form">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>

                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('users.update', $user->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User: {{ $user->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email Login</label>
                                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password Baru <small class="text-muted">(Kosongkan jika tidak diganti)</small></label>
                                            <input type="password" name="password" class="form-control" placeholder="******">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Role</label>
                                                <select name="role" class="form-select">
                                                    <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
                                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin Gudang</option>
                                                    <option value="cashier" {{ $user->role == 'cashier' ? 'selected' : '' }}>Kasir</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Status Login</label>
                                                <select name="is_active" class="form-select">
                                                    <option value="1" {{ $user->is_active ? 'selected' : '' }} class="text-success fw-bold">Aktif (Bisa Login)</option>
                                                    <option value="0" {{ !$user->is_active ? 'selected' : '' }} class="text-danger fw-bold">Non-Aktif (Dilarang)</option>
                                                </select>
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Login</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role (Jabatan)</label>
                        <select name="role" class="form-select" required>
                            <option value="cashier">Kasir</option>
                            <option value="admin">Admin Gudang</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan User</button>
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
            title: 'Hapus User?',
            text: "User ini tidak akan bisa login lagi.",
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