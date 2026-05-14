@extends('layouts.admin')

@section('page-title', 'Manajemen Antrian')
@section('page-subtitle', 'Pantau dan kelola antrian pasien hari ini')

@section('content')
{{-- STATISTIK ANTRIAN --}}
<div class="row g-3 mb-4">
    {{-- Total Antrian --}}
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-users fa-2x text-primary"></i>
                </div>
                <h4 class="mb-1 text-primary fw-bold">{{ $queueStats->total_queues }}</h4>
                <p class="mb-0 text-muted small">Total Antrian Hari Ini</p>
            </div>
        </div>
    </div>

    {{-- Menunggu --}}
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-clock fa-2x text-warning"></i>
                </div>
                <h4 class="mb-1 text-warning fw-bold">{{ $queueStats->waiting }}</h4>
                <p class="mb-0 text-muted small">Menunggu</p>
            </div>
        </div>
    </div>

    {{-- Dipanggil --}}
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-bullhorn fa-2x text-info"></i>
                </div>
                <h4 class="mb-1 text-info fw-bold">{{ $queueStats->called }}</h4>
                <p class="mb-0 text-muted small">Dipanggil</p>
            </div>
        </div>
    </div>

    {{-- Selesai --}}
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                </div>
                <h4 class="mb-1 text-success fw-bold">{{ $queueStats->served }}</h4>
                <p class="mb-0 text-muted small">Selesai</p>
            </div>
        </div>
    </div>
</div>

{{-- KONTROL ANTRIAN --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light border-0">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-cogs text-primary me-2"></i>
            Kontrol Antrian
        </h5>
    </div>
    <div class="card-body">
            <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary w-100">
                    <i class="fas fa-calendar-plus me-2"></i>Buat Appointment
                </a>
            </div>
            <div class="col-md-4">

                <form method="POST" action="{{ route('admin.queues.reset') }}" class="d-inline"
                      onsubmit="return confirm('Apakah Anda yakin ingin reset semua antrian hari ini?')">
                    @csrf
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-undo me-2"></i>Reset Antrian Hari Ini
                    </button>
                </form>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-info w-100" onclick="refreshQueues()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh Data
                </button>
            </div>
        </div>
    </div>
</div>

{{-- TABEL ANTRIAN --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-list-ol text-primary me-2"></i>
                Daftar Antrian Hari Ini
            </h5>
            <small class="text-muted">{{ $todayQueues->count() }} pasien dalam antrian</small>
        </div>
        <div class="text-end">
            <span class="badge bg-info">{{ now()->format('d/m/Y') }}</span>
        </div>
    </div>
    <div class="card-body p-0">
        @if($todayQueues->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="queueTable">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 fw-semibold text-center">No. Antrian</th>
                            <th class="border-0 fw-semibold">Kode Booking</th>
                            <th class="border-0 fw-semibold">Pasien</th>
                            <th class="border-0 fw-semibold">Dokter</th>
                            <th class="border-0 fw-semibold">Spesialisasi</th>
                            <th class="border-0 fw-semibold">Jam Kunjungan</th>
                            <th class="border-0 fw-semibold text-center">Status Antrian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todayQueues as $queue)
                            <tr id="queue-row-{{ $queue['id'] }}"
                                class="queue-row {{ $queue['queue_status'] === 'called' ? 'table-info' : ($queue['queue_status'] === 'served' ? 'table-success' : '') }}">
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark fs-5 px-3 py-2">
                                        {{ $queue['queue_number'] }}
                                    </span>
                                </td>
                                <td>
                                    <code class="bg-light px-2 py-1 rounded small">
                                        {{ $queue['booking_code'] }}
                                    </code>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                            <i class="fas fa-user text-info"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold">{{ $queue['patient_name'] }}</span>
                                            <br><small class="text-muted">ID: {{ $queue['patient_id'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                            <i class="fas fa-user-md text-success"></i>
                                        </div>
                                        <span>{{ $queue['doctor_name'] }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $queue['doctor_specialization'] }}</span>
                                </td>
                                <td>
                                    <i class="fas fa-calendar text-muted me-2"></i>
                                    {{ \Carbon\Carbon::parse($queue['appointment_time'])->format('d/m/Y H:i') }}
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusConfig = [
                                            'waiting' => ['class' => 'bg-warning text-dark', 'icon' => 'fas fa-clock', 'text' => 'Menunggu'],
                                            'called' => ['class' => 'bg-info', 'icon' => 'fas fa-bullhorn', 'text' => 'Dipanggil'],
                                            'served' => ['class' => 'bg-success', 'icon' => 'fas fa-check-circle', 'text' => 'Selesai'],
                                            'skipped' => ['class' => 'bg-danger', 'icon' => 'fas fa-times-circle', 'text' => 'Dilewati'],
                                        ];
                                        $config = $statusConfig[$queue['queue_status']] ?? $statusConfig['waiting'];
                                    @endphp
                                    <span class="badge {{ $config['class'] }} status-badge" id="status-{{ $queue['id'] }}">
                                        <i class="{{ $config['icon'] }} me-1"></i>
                                        {{ $config['text'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @if($queue['queue_status'] === 'waiting')
                                            <button type="button" class="btn btn-sm btn-info"
                                                    onclick="updateQueueStatus({{ $queue['id'] }}, 'called')">
                                                <i class="fas fa-bullhorn"></i>
                                            </button>
                                        @elseif($queue['queue_status'] === 'called')
                                            <button type="button" class="btn btn-sm btn-success"
                                                    onclick="updateQueueStatus({{ $queue['id'] }}, 'served')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning"
                                                    onclick="updateQueueStatus({{ $queue['id'] }}, 'waiting')">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif
                                        @if(in_array($queue['queue_status'], ['waiting', 'called']))
                                            <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="updateQueueStatus({{ $queue['id'] }}, 'skipped')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">Belum ada antrian hari ini</h6>
                <p class="text-muted small mb-3">Antrian akan muncul setelah pasien melakukan reservasi dan disetujui</p>
                <form method="POST" action="{{ route('admin.queues.generate') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Generate Antrian dari Reservasi
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.queue-row.called {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.queue-row.served {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.status-badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.btn-group .btn {
    margin-right: 2px;
}

.table-responsive {
    border-radius: 0 0 0.375rem 0.375rem;
}
</style>
@endpush

@push('scripts')
<script>
function updateQueueStatus(queueId, newStatus) {
    if (!confirm('Apakah Anda yakin ingin mengubah status antrian ini?')) {
        return;
    }

    fetch(`{{ url('/admin/queues') }}/${queueId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            updateQueueRow(queueId, newStatus);
            showToast('Status antrian berhasil diperbarui', 'success');

            // Refresh statistics
            refreshStats();
        } else {
            showToast('Gagal memperbarui status antrian', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat memperbarui status', 'error');
    });
}

function updateQueueRow(queueId, newStatus) {
    const row = document.getElementById(`queue-row-${queueId}`);
    const statusBadge = document.getElementById(`status-${queueId}`);

    // Remove existing classes
    row.classList.remove('called', 'served');
    statusBadge.classList.remove('bg-warning', 'text-dark', 'bg-info', 'bg-success', 'bg-danger');

    // Update based on new status
    const statusConfig = {
        'waiting': { class: 'bg-warning text-dark', icon: 'fas fa-clock', text: 'Menunggu' },
        'called': { class: 'bg-info', icon: 'fas fa-bullhorn', text: 'Dipanggil' },
        'served': { class: 'bg-success', icon: 'fas fa-check-circle', text: 'Selesai' },
        'skipped': { class: 'bg-danger', icon: 'fas fa-times-circle', text: 'Dilewati' }
    };

    const config = statusConfig[newStatus];
    statusBadge.className = `badge ${config.class} status-badge`;
    statusBadge.innerHTML = `<i class="${config.icon} me-1"></i>${config.text}`;

    if (newStatus === 'called') {
        row.classList.add('called');
    } else if (newStatus === 'served') {
        row.classList.add('served');
    }
}

function refreshQueues() {
    location.reload();
}

function refreshStats() {
    // Simple refresh for now - could be enhanced with AJAX
    location.reload();
}

function showToast(message, type) {
    // Simple alert for now - could be enhanced with toast library
    alert(message);
}
</script>
@endpush