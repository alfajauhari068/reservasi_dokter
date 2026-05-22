@extends('layouts.admin')

@section('title', 'Daftar Dokter - Reservasi Dokter')

@section('content')
    <div class="admin-card mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-2">Daftar Dokter</h1>
                <p class="text-muted mb-0">Kelola data dokter dengan tampilan modern yang lebih rapi dan mudah diakses. Tambah dokter baru atau perbarui data dokter hanya dengan beberapa klik.</p>
            </div>
            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus me-2"></i> Tambah Dokter
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="admin-stat-card p-4">
                <div class="admin-stat-card__icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <div>
                    <div class="text-muted small mb-1">Total Dokter</div>
                    <strong class="h3 mb-0">{{ $doctors->count() }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="admin-card p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="mb-1">Pencarian Dokter</h6>
                        <p class="text-muted small mb-0">Cari nama dokter, spesialisasi, atau email dengan cepat.</p>
                    </div>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="search" class="form-control admin-form-control" placeholder="Cari nama dokter, spesialisasi, email..." aria-label="Cari dokter">
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card p-0">
        <div class="admin-card__header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 p-4 border-bottom">
            <div>
                <h2 class="mb-1">Daftar Dokter</h2>
                <p class="text-muted small mb-0">Ringkasan dokter saat ini dan status ketersediaan.</p>
            </div>
            <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-user-plus me-2"></i> Tambah Dokter
            </a>
        </div>
        <div class="table-responsive p-4">
            <table class="table admin-table mb-0">
                <thead>
                    <tr>
                        <th>Nama Dokter</th>
                        <th>Email</th>
                        <th>Spesialisasi</th>
                        <th>Lisensi</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:44px; height:44px;">
                                        <i class="fas fa-user-md text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $doctor->user->name }}</strong>
                                        <div class="text-muted small">Dokter Spesialis {{ $doctor->specialization->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $doctor->user->email }}</td>
                            <td>{{ $doctor->specialization->name }}</td>
                            <td>{{ $doctor->license_number }}</td>
                            <td>
                                @if($doctor->is_available)
                                    <span class="badge-status-success">Tersedia</span>
                                @else
                                    <span class="badge-status-warning">Tidak Tersedia</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.doctors.show', $doctor) }}" class="btn btn-ghost btn-sm">Lihat</a>
                                    <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokter ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm text-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada data dokter</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection