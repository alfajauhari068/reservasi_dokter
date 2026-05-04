@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Data Dokter</h1>
    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary mb-3">+ Tambah Dokter</a>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Spesialisasi</th>
                    <th>Lisensi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                    <tr>
                        <td><strong>{{ $doctor->user->name }}</strong></td>
                        <td>{{ $doctor->user->email }}</td>
                        <td>{{ $doctor->specialization->name }}</td>
                        <td>{{ $doctor->license_number }}</td>
                        <td>
                            @if($doctor->is_available)
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-danger">Tidak Tersedia</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.doctors.show', $doctor) }}" class="btn btn-info">Lihat</a>
                                <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus dokter ini?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data dokter</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection