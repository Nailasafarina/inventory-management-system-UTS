@extends('layouts.app')

@section('title', 'Manajemen Pengguna & Hak Akses (RBAC)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="h4 fw-bold mb-1 text-dark">Manajemen Pengguna & RBAC</h2>
        <p class="text-muted small mb-0">Kelola akun Admin, Staff, dan Customer dengan pembatasan hak akses server-side</p>
    </div>
    <div>
        <a href="{{ route('users.create') }}" class="btn btn-terracotta">
            <i class="bi bi-person-plus me-1"></i> Tambah Pengguna Baru
        </a>
    </div>
</div>

<div class="card card-custom mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <label for="search" class="form-label small fw-semibold text-muted mb-1">Cari Nama atau Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" id="search" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email...">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <label for="role" class="form-label small fw-semibold text-muted mb-1">Filter Role</label>
                <select class="form-select" id="role" name="role">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin / Owner</option>
                    <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-terracotta flex-fill">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'role']))
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-custom-header">
                    <tr>
                        <th style="width: 60px;" class="ps-3">No</th>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th class="text-center">Role / Hak Akses</th>
                        <th>Terdaftar Sejak</th>
                        <th class="text-center pe-3" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-3">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td class="fw-bold text-dark">
                            {{ $user->name }}
                            @if($user->id === auth()->id())
                                <span class="badge rounded-pill bg-secondary ms-1">Anda</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            @if($user->role === 'admin')
                                <span class="badge rounded-pill" style="background-color: #f7ede4; color: #b77747; border: 1px solid #e7dacd; font-weight: 600;">
                                    <i class="bi bi-award-fill me-1"></i>ADMIN
                                </span>
                            @elseif($user->role === 'staff')
                                <span class="badge rounded-pill" style="background-color: #e8f0fe; color: #2d68c4; border: 1px solid #d2e3fc; font-weight: 600;">
                                    <i class="bi bi-person-workspace me-1"></i>STAFF
                                </span>
                            @else
                                <span class="badge rounded-pill" style="background-color: #e6f4ea; color: #21774b; border: 1px solid #ceead6; font-weight: 600;">
                                    <i class="bi bi-person me-1"></i>CUSTOMER
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center pe-3">
                            <div class="d-inline-flex gap-1 text-nowrap">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil me-1"></i> Edit
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-2 d-block mb-2 text-muted"></i>
                            Belum ada data pengguna.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-transparent py-3 px-4 border-top">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
