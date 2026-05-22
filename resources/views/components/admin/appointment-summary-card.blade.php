@props([
    'title' => 'Appointment Summary',
    'appointmentsCount' => 0,
    'statusCounts' => [], // e.g. ['approved'=>10,'done'=>5,'cancelled'=>2]
    'href' => '#',
])

<section class="ds-card appointment-summary-card" aria-label="{{ $title }}">
    <div class="appointment-summary-card__header d-flex align-items-start justify-content-between gap-3">
        <div>
            <div class="appointment-summary-card__kicker">Daftar Reservasi</div>
            <h2 class="ds-h2 mb-2">{{ $title }}</h2>
            <p class="ds-body" style="margin-bottom: 0;">
                Total appointment: <strong>{{ $appointmentsCount }}</strong>
            </p>
        </div>

        <a href="{{ $href }}" class="btn btn-primary btn-nowrap">
            Lihat Semua
        </a>
    </div>

    <div class="appointment-summary-card__grid">
        @php
            $approved = $statusCounts['approved'] ?? 0;
            $done = $statusCounts['done'] ?? 0;
            $cancelled = $statusCounts['cancelled'] ?? 0;
        @endphp

        <div class="appointment-summary-card__item">
            <div class="appointment-summary-card__value">{{ $approved }}</div>
            <div class="appointment-summary-card__label">Approved</div>
        </div>
        <div class="appointment-summary-card__item">
            <div class="appointment-summary-card__value">{{ $done }}</div>
            <div class="appointment-summary-card__label">Selesai</div>
        </div>
        <div class="appointment-summary-card__item">
            <div class="appointment-summary-card__value">{{ $cancelled }}</div>
            <div class="appointment-summary-card__label">Dibatalkan</div>
        </div>
    </div>
</section>

