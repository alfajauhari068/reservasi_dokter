{{--
    Notification Dropdown Component

    Query database notifications and render a dropdown in the header.
    Used for Admin, Dokter, and Pasien layouts.
--}}

@auth
@php
    $user = auth()->user();
    $notifications = $user
        ? $user->notifications()->latest()->limit(10)->get()
        : collect();
    $unreadCount = $user
        ? $user->unreadNotifications()->count()
        : 0;
@endphp

<div class="dropdown admin-notification-dropdown">
    <button class="btn btn-outline-secondary btn-notification position-relative p-0" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="notification-icon-wrapper d-inline-flex align-items-center justify-content-center">
            <i class="fas fa-bell fs-5"></i>
        </span>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem; padding: 0.25rem 0.4rem;">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    <div class="dropdown-menu dropdown-menu-end shadow-lg animate-in fade-in duration-200 fixed top-16 right-4 left-4 w-auto sm:static sm:absolute sm:top-auto sm:right-0 sm:left-auto sm:w-80 fixed-mobile" aria-labelledby="notificationDropdown" style="max-height: 500px; overflow-y: auto;">

        <div class="dropdown-header border-bottom d-flex justify-content-between align-items-center">
            <strong class="mb-0">Notifikasi</strong>
            @if($unreadCount > 0)
                <small>
                    <button class="btn btn-link btn-sm p-0 text-primary text-decoration-none" onclick="markAllAsRead(event)">
                        Baca Semua
                    </button>
                </small>
            @endif
        </div>

        @forelse($notifications as $notification)
            @php
                $data = $notification->data;
                $levelClass = match($data['level'] ?? 'info') {
                    'success'  => 'text-success',
                    'warning'  => 'text-warning',
                    'critical' => 'text-danger',
                    default    => 'text-info',
                };
                $bgClass = $notification->read_at ? '' : 'bg-light';
            @endphp

            <a href="{{ $data['url'] ?? '#' }}" class="dropdown-item py-3 px-3 border-bottom text-decoration-none {{ $bgClass }}" style="transition: background-color 0.2s;">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="{{ $data['icon'] ?? 'fas fa-bell' }} {{ $levelClass }}"></i>
                            <span class="fw-semibold small">{{ $data['title'] ?? 'Notifikasi' }}</span>
                            @if(!$notification->read_at)
                                <span class="badge bg-primary rounded-pill" style="font-size: 0.6rem;">Baru</span>
                            @endif
                        </div>
                        <p class="small text-muted mb-1" style="line-height: 1.3;">
                            {{ $data['message'] ?? '' }}
                        </p>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </div>
                    <button class="btn btn-close btn-sm" type="button" onclick="deleteNotification(event, '{{ $notification->id }}')"></button>
                </div>
            </a>
        @empty
            <div class="dropdown-item text-center text-muted py-4">
                <i class="fas fa-inbox fs-5 mb-2 d-block text-secondary"></i>
                <small>Tidak ada notifikasi</small>
            </div>
        @endforelse

        @if($notifications->count() > 0)
            <div class="dropdown-divider"></div>
            <a href="{{ route('notifications.index') }}" class="dropdown-item text-center py-2 small text-primary text-decoration-none fw-semibold">
                Lihat Semua Notifikasi
            </a>
        @endif
    </div>
</li>

<script>
    function deleteNotification(event, notificationId) {
        event.preventDefault();
        event.stopPropagation();

        if (!confirm('Hapus notifikasi ini?')) return;

        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function markAllAsRead(event) {
        event.preventDefault();
        event.stopPropagation();

        fetch('/notifications/mark-all-as-read', {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endauth
