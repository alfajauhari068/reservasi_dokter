@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-bell"></i> Notifikasi Anda
                    </h5>
                    @if($unreadCount > 0)
                        <span class="badge bg-danger">{{ $unreadCount }} Belum dibaca</span>
                    @endif
                </div>

                <div class="card-body">
                    {{-- Action Buttons --}}
                    @if($unreadCount > 0)
                        <div class="mb-3">
                            <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                                <i class="fas fa-check"></i> Tandai Semua Sebagai Dibaca
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteAll()">
                                <i class="fas fa-trash"></i> Hapus Semua
                            </button>
                        </div>
                    @endif

                    {{-- Notifications List --}}
                    @forelse($notifications as $notification)
                        @php 
                            $data = $notification->data;
                            $levelColors = [
                                'success'  => '#198754',
                                'info'     => '#0dcaf0',
                                'warning'  => '#ffc107',
                                'critical' => '#dc3545',
                            ];
                            $bgColor = $notification->read_at ? 'transparent' : '#f8f9fa';
                            $borderColor = $levelColors[$data['level'] ?? 'info'] ?? '#0dcaf0';
                        @endphp
                        
                        <div class="notification-item border-start ps-3 py-3" style="border-left: 4px solid {{ $borderColor }}; background-color: {{ $bgColor }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="{{ $data['icon'] ?? 'fas fa-bell' }}" style="color: {{ $borderColor }}"></i>
                                        <h6 class="mb-0">{{ $data['title'] ?? 'Notifikasi' }}</h6>
                                        @if(!$notification->read_at)
                                            <span class="badge bg-primary">Baru</span>
                                        @endif
                                    </div>
                                    <p class="mb-1 text-muted">{{ $data['message'] ?? '' }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                    
                                    {{-- Additional Info --}}
                                    @if($data['booking_code'] ?? null)
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <strong>Kode Booking:</strong> {{ $data['booking_code'] }}
                                            </small>
                                        </div>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div class="d-flex gap-1">
                                    @if($data['url'] ?? null)
                                        <a href="{{ $data['url'] }}" class="btn btn-sm btn-outline-primary" title="Lihat detail">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    @endif
                                    @if(!$notification->read_at)
                                        <button class="btn btn-sm btn-outline-secondary" onclick="markAsRead('{{ $notification->id }}')" title="Tandai sebagai dibaca">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteNotification('{{ $notification->id }}')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Tidak ada notifikasi</p>
                        </div>
                    @endforelse

                    {{-- Pagination --}}
                    @if($notifications->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .notification-item {
        transition: all 0.2s ease;
        border-radius: 0.25rem;
        margin-bottom: 0.5rem;
    }

    .notification-item:hover {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/mark-as-read`, {
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

    function markAllAsRead() {
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

    function deleteNotification(notificationId) {
        if (confirm('Yakin ingin menghapus notifikasi ini?')) {
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
    }

    function deleteAll() {
        if (confirm('Yakin ingin menghapus semua notifikasi?')) {
            fetch('/notifications', {
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
    }
</script>
@endsection
