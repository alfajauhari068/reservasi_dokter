@extends('layouts.app')

@section('title', 'Daftar Dokter - Reservasi Dokter')

@section('content')
    <div class="page-heading">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1>Daftar Dokter</h1>
                <p>Kelola data dokter dengan tampilan modern yang lebih rapi dan mudah diakses. Tambah dokter baru atau perbarui data dokter hanya dengan beberapa klik.</p>
            </div>
            <div class="col-md-4 text-md-end mt-4 mt-md-0">
                <a href="{{ route('admin.doctors.create') }}" class="btn btn-gradient btn-lg">
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
            <div class="glass-card stats-pill">
                <strong>{{ $doctors->count() }}</strong>
                Total dokter terdaftar
            </div>
        </div>
        <div class="col-md-8">
            <div class="glass-card search-box">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="search" class="form-control" placeholder="Cari nama dokter, spesialisasi, email..." aria-label="Cari dokter">
                </div>
            </div>
        </div>
    </div>

    <div class="glass-card table-modern">
        <div class="table-responsive">
            <table class="table mb-0">
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
                                    <span class="badge-pill available">Tersedia</span>
                                @else
                                    <span class="badge-pill unavailable">Tidak Tersedia</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.doctors.show', $doctor) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                                    <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokter ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
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