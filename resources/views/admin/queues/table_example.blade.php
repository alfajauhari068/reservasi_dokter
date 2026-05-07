{{-- TAMPILAN TABEL ANTRIAN HARIAN DENGAN SEARCH & PAGINATION --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light border-0">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-list-ol text-primary me-2"></i>
                    Antrian Harian - {{ now()->format('d F Y') }}
                </h5>
                <small class="text-muted">Pantau status antrian pasien hari ini</small>
            </div>
            <div class="text-end">
                <span class="badge bg-info">{{ $queues->total() }} pasien</span>
            </div>
        </div>
    </div>

    {{-- FORM PENCARIAN --}}
    <div class="card-header border-0 bg-light">
        <form method="GET" action="{{ request()->url() }}" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari nama pasien, dokter, atau kode booking..."
                           value="{{ $search ?? '' }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="per_page" class="form-select">
                    <option value="5" {{ ($perPage ?? 10) == 5 ? 'selected' : '' }}>5 per halaman</option>
                    <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10 per halaman</option>
                    <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25 per halaman</option>
                    <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50 per halaman</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Cari
                    </button>
                    @if(!empty($search ?? ''))
                        <a href="{{ request()->url() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- INFO PENCARIAN --}}
        @if(!empty($search ?? ''))
            <div class="mt-2">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Menampilkan {{ $queues->count() }} dari {{ $queues->total() }} hasil untuk "<strong>{{ $search }}</strong>"
                </small>
            </div>
        @endif
    </div>

    <div class="card-body p-0">
        @if($queues->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 fw-semibold text-center" style="width: 100px;">No Antrian</th>
                            <th class="border-0 fw-semibold">Nama Pasien</th>
                            <th class="border-0 fw-semibold">Dokter</th>
                            <th class="border-0 fw-semibold">Jam Kunjungan</th>
                            <th class="border-0 fw-semibold text-center" style="width: 150px;">Status Antrian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($queues as $queue)
                            <tr class="queue-row {{ $queue->queue_status === 'called' ? 'table-info' : ($queue->queue_status === 'served' ? 'table-success' : '') }}">
                                {{-- NO ANTRIAN --}}
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark fs-5 px-3 py-2 fw-bold">
                                        {{ $queue->queue_number }}
                                    </span>
                                </td>

                                {{-- NAMA PASIEN --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold">{{ $queue->appointment->patient->user->name }}</span>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-id-card me-1"></i>
                                                {{ $queue->appointment->patient->patient_id ?? 'ID: ' . $queue->appointment->patient->id }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                {{-- DOKTER --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user-md text-success"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold">{{ $queue->appointment->doctor->user->name }}</span>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-stethoscope me-1"></i>
                                                {{ $queue->appointment->doctor->specialization->name ?? 'Umum' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                {{-- JAM KUNJUNGAN/JADWAL --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">
                                            <i class="fas fa-clock text-muted me-2"></i>
                                            {{ \Carbon\Carbon::parse($queue->appointment->appointment_time)->format('H:i') }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($queue->appointment->appointment_date)->format('d/m/Y') }}
                                        </small>
                                    </div>
                                </td>

                                {{-- STATUS ANTRIAN --}}
                                <td class="text-center">
                                    @php
                                        $statusConfig = [
                                            'waiting' => ['class' => 'bg-warning text-dark', 'icon' => 'fas fa-clock', 'text' => 'Menunggu'],
                                            'called' => ['class' => 'bg-info', 'icon' => 'fas fa-bullhorn', 'text' => 'Dipanggil'],
                                            'served' => ['class' => 'bg-success', 'icon' => 'fas fa-check-circle', 'text' => 'Selesai'],
                                            'skipped' => ['class' => 'bg-danger', 'icon' => 'fas fa-times-circle', 'text' => 'Dilewati'],
                                        ];
                                        $config = $statusConfig[$queue->queue_status] ?? $statusConfig['waiting'];
                                    @endphp
                                    <span class="badge {{ $config['class'] }} fs-6 px-3 py-2">
                                        <i class="{{ $config['icon'] }} me-1"></i>
                                        {{ $config['text'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            {{-- KETIKA TIDAK ADA ANTRIAN --}}
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-users fa-4x text-muted"></i>
                </div>
                <h5 class="text-muted">Tidak ada antrian hari ini</h5>
                <p class="text-muted small">Antrian akan muncul setelah pasien melakukan reservasi</p>
            </div>
        @endif
    </div>

    {{-- PAGINATION --}}
    @if($queues->hasPages())
        <div class="card-footer bg-light border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Menampilkan {{ $queues->firstItem() }}-{{ $queues->lastItem() }} dari {{ $queues->total() }} data
                </div>
                <div>
                    {{ $queues->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

{{-- LEGENDA STATUS --}}
@if($queues->count() > 0)
<div class="mt-3">
    <h6 class="fw-bold mb-3">
        <i class="fas fa-info-circle text-info me-2"></i>
        Legenda Status:
    </h6>
    <div class="row g-2">
        <div class="col-auto">
            <span class="badge bg-warning text-dark px-3 py-2">
                <i class="fas fa-clock me-1"></i>Menunggu
            </span>
        </div>
        <div class="col-auto">
            <span class="badge bg-info px-3 py-2">
                <i class="fas fa-bullhorn me-1"></i>Dipanggil
            </span>
        </div>
        <div class="col-auto">
            <span class="badge bg-success px-3 py-2">
                <i class="fas fa-check-circle me-1"></i>Selesai
            </span>
        </div>
        <div class="col-auto">
            <span class="badge bg-danger px-3 py-2">
                <i class="fas fa-times-circle me-1"></i>Dilewati
            </span>
        </div>
    </div>
</div>
@endif