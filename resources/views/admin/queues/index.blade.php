@extends('layouts.admin')

@section('page-title', 'Manajemen Antrian')
@section('page-subtitle', 'Pantau dan kelola antrian pasien hari ini')

@section('content')
{{-- STATISTIK ANTRIAN --}}
<div class="row g-3 mb-4">
    {{-- Total Antrian --}}
    <div class="col-lg-3 col-md-6">
        <div class="admin-stat-card admin-stat-card--accent-primary text-center">
            <div class="admin-stat-card__icon mb-3">
                <i class="fas fa-users"></i>
            </div>
            <h4 class="mb-1">{{ $queueStats->total_queues }}</h4>
            <p class="mb-0 text-muted small">Total Antrian Hari Ini</p>
        </div>
    </div>

    {{-- Menunggu --}}
    <div class="col-lg-3 col-md-6">
        <div class="admin-stat-card admin-stat-card--accent-warning text-center">
            <div class="admin-stat-card__icon mb-3 text-warning bg-warning-soft">
                <i class="fas fa-clock"></i>
            </div>
            <h4 class="mb-1 text-warning fw-bold">{{ $queueStats->waiting }}</h4>
            <p class="mb-0 text-muted small">Menunggu</p>
        </div>
    </div>

    {{-- Dipanggil --}}
    <div class="col-lg-3 col-md-6">
        <div class="admin-stat-card admin-stat-card--accent-info text-center">
            <div class="admin-stat-card__icon mb-3 text-info bg-info-soft">
                <i class="fas fa-bullhorn"></i>
            </div>
            <h4 class="mb-1 text-info fw-bold">{{ $queueStats->called }}</h4>
            <p class="mb-0 text-muted small">Dipanggil</p>
        </div>
    </div>

    {{-- Selesai --}}
    <div class="col-lg-3 col-md-6">
        <div class="admin-stat-card admin-stat-card--accent-success text-center">
            <div class="admin-stat-card__icon mb-3 text-success bg-success-soft">
                <i class="fas fa-check-circle"></i>
            </div>
            <h4 class="mb-1 text-success fw-bold">{{ $queueStats->served }}</h4>
            <p class="mb-0 text-muted small">Selesai</p>
        </div>
    </div>
</div>

{{-- KONTROL ANTRIAN --}}
<div class="admin-card mb-4">
    <div class="admin-card__header d-flex align-items-center justify-content-between gap-3 p-4">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-cogs text-primary me-2"></i>
            Kontrol Antrian
        </h5>
    </div>
    <div class="admin-card__body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary w-100">
                    <i class="fas fa-calendar-plus me-2"></i>Buat Appointment
                </a>
            </div>
            <div class="col-md-4">
                <form method="POST" action="{{ route('admin.queues.reset') }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin reset semua antrian hari ini?')">
                    @csrf
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="fas fa-undo me-2"></i>Reset Antrian Hari Ini
                    </button>
                </form>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-ghost w-100" onclick="refreshQueues()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh Data
                </button>
            </div>
        </div>
    </div>
</div>

{{-- TABEL ANTRIAN --}}
<div class="admin-card">
    <div class="admin-card__header d-flex justify-content-between align-items-center gap-3 p-4">
        <div>
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-list-ol text-primary me-2"></i>
                Daftar Antrian Hari Ini
            </h5>
            <small class="text-muted">{{ $todayQueues->count() }} pasien dalam antrian</small>
        </div>
        <div class="text-end">
            <span class="badge badge-status-info">{{ now()->format('d/m/Y') }}</span>
        </div>
    </div>
    <div class="admin-card__body p-0">
        @if($todayQueues->count() > 0)
            @php
                $groupedQueues = collect($todayQueues)->groupBy('doctor_specialization');
            @endphp
            <div class="accordion" id="specializationQueues">
                @foreach($groupedQueues as $specialization => $queues)
                    <div class="accordion-item border-0">
                        <h2 class="accordion-header" id="heading-{{ \Illuminate\Support\Str::slug($specialization ?: 'umum') }}">
                            <button class="accordion-button collapsed py-3 rounded-3 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ \Illuminate\Support\Str::slug($specialization ?: 'umum') }}" aria-expanded="false" aria-controls="collapse-{{ \Illuminate\Support\Str::slug($specialization ?: 'umum') }}">
                                <div class="d-flex align-items-center justify-content-between w-100">
                                    <div>
                                        <h6 class="mb-1">Spesialisasi: {{ $specialization }}</h6>
                                        <small class="text-muted">{{ $queues->count() }} pasien dalam antrian</small>
                                    </div>
                                    <span class="badge badge-status-info rounded-pill">{{ $queues->count() }}</span>
                                </div>
                            </button>
                        </h2>
                        <div id="collapse-{{ Str::slug($specialization ?: 'umum') }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ Str::slug($specialization ?: 'umum') }}" data-bs-parent="#specializationQueues">
                            <div class="accordion-body p-0">
                                <div class="table-responsive admin-table-wrapper">
                                    <table class="table admin-table table-hover align-middle mb-0" id="queueTable-{{ Str::slug($specialization ?: 'umum') }}">
                                        <thead>
                                            <tr>
                                                <th class="border-0 fw-semibold text-center">No. Antrian</th>
                                                <th class="border-0 fw-semibold">Kode Booking</th>
                                                <th class="border-0 fw-semibold">Pasien</th>
                                                <th class="border-0 fw-semibold">Dokter</th>
                                                <th class="border-0 fw-semibold">Jam Kunjungan</th>
                                                <th class="border-0 fw-semibold text-center">Status</th>
                                                <th class="border-0 fw-semibold text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($queues as $queue)
                                                <tr id="queue-row-{{ $queue['id'] }}" class="admin-queue-row {{ $queue['queue_status'] === 'called' ? 'admin-queue-row--called' : ($queue['queue_status'] === 'served' ? 'admin-queue-row--served' : '') }}">
                                                    <td class="text-center">
                                                        <span class="badge badge-status-warning fs-5 px-3 py-2">{{ $queue['queue_number'] }}</span>
                                                    </td>
                                                    <td>
                                                        <code class="bg-light px-2 py-1 rounded small">{{ $queue['booking_code'] }}</code>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                                                <i class="fas fa-user text-info"></i>
                                                            </div>
                                                            <div>
                                                                <span class="fw-semibold">{{ $queue['patient_name'] }}</span><br>
                                                                <small class="text-muted">ID: {{ $queue['patient_id'] }}</small>
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
                                                        <i class="fas fa-calendar text-muted me-2"></i>
                                                        {{ \Carbon\Carbon::parse($queue['appointment_time'])->format('d/m/Y H:i') }}
                                                    </td>
                                                    <td class="text-center">
                                                        @php
                                                            $statusConfig = [
                                                                'waiting' => ['class' => 'badge-status-warning', 'icon' => 'fas fa-clock', 'text' => 'Menunggu'],
                                                                'called' => ['class' => 'badge-status-info', 'icon' => 'fas fa-bullhorn', 'text' => 'Dipanggil'],
                                                                'served' => ['class' => 'badge-status-success', 'icon' => 'fas fa-check-circle', 'text' => 'Selesai'],
                                                                'skipped' => ['class' => 'badge-status-critical', 'icon' => 'fas fa-times-circle', 'text' => 'Dilewati'],
                                                            ];
                                                            $config = $statusConfig[$queue['queue_status']] ?? $statusConfig['waiting'];
                                                        @endphp
                                                        <span class="badge {{ $config['class'] }} text-capitalize" id="status-{{ $queue['id'] }}">
                                                            <i class="{{ $config['icon'] }} me-1"></i>
                                                            {{ $config['text'] }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group">
                                                            @if($queue['queue_status'] === 'waiting')
                                                                <button type="button" class="btn btn-ghost btn-sm" onclick="updateQueueStatus({{ $queue['id'] }}, 'called')">
                                                                    <i class="fas fa-bullhorn"></i>
                                                                </button>
                                                            @elseif($queue['queue_status'] === 'called')
                                                                <button type="button" class="btn btn-primary btn-sm" onclick="updateQueueStatus({{ $queue['id'] }}, 'served')">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-secondary btn-sm" onclick="updateQueueStatus({{ $queue['id'] }}, 'waiting')">
                                                                    <i class="fas fa-undo"></i>
                                                                </button>
                                                            @endif
                                                            @if(in_array($queue['queue_status'], ['waiting', 'called']))
                                                                <button type="button" class="btn btn-ghost btn-sm text-danger" onclick="updateQueueStatus({{ $queue['id'] }}, 'skipped')">
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
                            </div>
                        </div>
                    </div>
                @endforeach
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
    row.classList.remove('admin-queue-row--called', 'admin-queue-row--served');
    statusBadge.classList.remove('badge-status-warning', 'badge-status-info', 'badge-status-success', 'badge-status-critical');

    // Update based on new status
    const statusConfig = {
        'waiting': { class: 'badge-status-warning', icon: 'fas fa-clock', text: 'Menunggu' },
        'called': { class: 'badge-status-info', icon: 'fas fa-bullhorn', text: 'Dipanggil' },
        'served': { class: 'badge-status-success', icon: 'fas fa-check-circle', text: 'Selesai' },
        'skipped': { class: 'badge-status-critical', icon: 'fas fa-times-circle', text: 'Dilewati' }
    };

    const config = statusConfig[newStatus];
    statusBadge.className = `badge ${config.class}`;
    statusBadge.innerHTML = `<i class="${config.icon} me-1"></i>${config.text}`;

    if (newStatus === 'called') {
        row.classList.add('admin-queue-row--called');
    } else if (newStatus === 'served') {
        row.classList.add('admin-queue-row--served');
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
    // Premium toast (frosted glass) without layout shift.
    // type: 'success' | 'error' | 'info'
    const existing = document.getElementById('adminToast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'adminToast';
    toast.setAttribute('role', 'status');

    const icon = {
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
        info: 'fas fa-info-circle'
    }[type] || 'fas fa-info-circle';

    const tone = {
        success: 'toast--success',
        error: 'toast--error',
        info: 'toast--info'
    }[type] || 'toast--info';

    toast.className = `admin-toast ${tone}`;
    toast.innerHTML = `
        <div class="admin-toast__inner">
            <span class="admin-toast__icon"><i class="${icon}"></i></span>
            <div class="admin-toast__message">${message}</div>
            <button class="admin-toast__close" type="button" aria-label="Close toast">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
    `;

    document.body.appendChild(toast);

    const closeBtn = toast.querySelector('.admin-toast__close');
    closeBtn?.addEventListener('click', () => {
        toast.classList.add('admin-toast--hide');
        setTimeout(() => toast.remove(), 180);
    });

    setTimeout(() => {
        toast.classList.add('admin-toast--hide');
        setTimeout(() => toast.remove(), 180);
    }, 2600);
}
</script>
@endpush