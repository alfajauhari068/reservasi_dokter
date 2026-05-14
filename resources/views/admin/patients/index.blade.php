@extends('layouts.admin')

@section('page-title', 'Kelola Pasien')
@section('page-subtitle', 'Daftar pasien yang tersedia untuk pembuatan appointment')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-4">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <div>
                <h4 class="mb-2 fw-bold">Data Pasien</h4>
                <p class="text-muted mb-0">Kelola pasien untuk appointment oleh admin.</p>
            </div>
            <div>
                <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>Tambah Pasien
                </a>
            </div>
        </div>
    </div>

    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 18%">ID</th>
                        <th style="width: 18%">Gender</th>
                        <th style="width: 18%">Tanggal Lahir</th>
                        <th style="width: 18%">Gol. Darah</th>
                        <th style="width: 28%">Identitas & Alamat</th>
                        <th style="width: 16%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr>
                            <td class="text-muted">{{ $patient->id }}</td>
                            <td>{{ $patient->gender ?? '-' }}</td>
                            <td>{{ $patient->date_of_birth ? 
                                
                                
                                \Carbon\Carbon::parse($patient->date_of_birth)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $patient->blood_type ?? '-' }}</td>
                            <td>
                                <div class="fw-semibold">{{ $patient->identity_number ?? '-' }}</div>
                                <div class="text-muted small" style="max-width: 420px;">{{ $patient->address ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('Hapus pasien ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-secondary btn-sm text-danger border-danger">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">Belum ada data pasien.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $patients->links() }}
        </div>
    </div>
</div>
@endsection

