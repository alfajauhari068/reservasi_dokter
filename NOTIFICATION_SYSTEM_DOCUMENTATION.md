# Dokumentasi Sistem Notifikasi Database Laravel

## Daftar Isi
- [Pengenalan](#pengenalan)
- [Struktur Notifikasi](#struktur-notifikasi)
- [Notification Classes](#notification-classes)
- [Service Layer](#service-layer)
- [Integrasi di Controller](#integrasi-di-controller)
- [Blade Templates](#blade-templates)
- [API Routes](#api-routes)
- [Menambah Event Notifikasi Baru](#menambah-event-notifikasi-baru)
- [Best Practices](#best-practices)
- [Testing](#testing)

---

## Pengenalan

Sistem notifikasi database memungkinkan Anda mengirim notifikasi ke user berdasarkan role (admin, dokter, pasien) dan event tertentu. Semua notifikasi disimpan di database dengan struktur JSON yang konsisten.

**File-file utama:**
- `app/Notifications/` - Notification classes
- `app/Services/NotificationService.php` - Logika notifikasi terpusat
- `app/Http/Controllers/NotificationsController.php` - Controller untuk notifikasi
- `resources/views/components/notification-dropdown.blade.php` - Dropdown notifikasi di header
- `resources/views/notifications/index.blade.php` - Halaman daftar notifikasi lengkap

---

## Struktur Notifikasi

Setiap notifikasi memiliki struktur JSON standar dalam kolom `data`:

```php
[
    'role'           => 'admin|dokter|pasien',      // Siapa penerima
    'event'          => 'reservation_created|...',  // Jenis event
    'title'          => 'Judul singkat',             // Untuk dropdown
    'message'        => 'Deskripsi ringkas',         // Untuk dropdown
    'icon'           => 'fas fa-calendar-plus',      // Font Awesome class
    'level'          => 'info|success|warning|critical', // Warna/prioritas
    'appointment_id' => (int) nullable,
    'queue_id'       => (int) nullable,
    'patient_id'     => (int) nullable,
    'doctor_id'      => (int) nullable,
    'booking_code'   => (string) nullable,
    'scheduled_at'   => 'Y-m-d H:i:s',               // Waktu appointment
    'url'            => '/path/to/detail',           // Link ke halaman terkait
]
```

### Penjelasan Field

| Field | Tipe | Contoh | Keterangan |
|-------|------|--------|-----------|
| `role` | string | 'admin' | Role penerima notifikasi |
| `event` | string | 'reservation_created' | Tipe event yang terjadi |
| `title` | string | 'Reservasi baru dibuat' | Judul untuk dropdown (max 50 char) |
| `message` | string | 'Pasien X membuat reservasi' | Pesan ringkas (max 100 char) |
| `icon` | string | 'fas fa-calendar-plus' | [Font Awesome class](https://fontawesome.com) |
| `level` | string | 'info' | Prioritas: info, success, warning, critical |
| `appointment_id` | int | 123 | ID appointment jika relevan |
| `queue_id` | int | 456 | ID queue jika relevan |
| `patient_id` | int | 789 | ID patient untuk filter |
| `doctor_id` | int | 101 | ID doctor untuk filter |
| `booking_code` | string | 'BOOK-2024-001' | Kode booking appointment |
| `scheduled_at` | string | '2024-05-18 14:30:00' | Format: Y-m-d H:i:s |
| `url` | string | '/admin/appointments/123' | Route untuk detail halaman |

---

## Notification Classes

### Struktur Umum

Semua Notification class meng-extend `Illuminate\Notifications\Notification`:

```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class YourNotification extends Notification
{
    public function __construct(private $model)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];  // Simpan ke database
    }

    public function toArray(object $notifiable): array
    {
        return [
            'role'       => 'admin',          // Tentukan role
            'event'      => 'your_event',     // Event name
            'title'      => 'Judul',
            'message'    => 'Pesan',
            'icon'       => 'fas fa-bell',
            'level'      => 'info',
            'url'        => route('path'),
            // ... field lainnya
        ];
    }
}
```

### Notification Classes yang Tersedia

#### 1. **NewReservationCreatedForAdmin**
Dikirim ke admin ketika pasien membuat reservasi baru.

```php
$admin->notify(new NewReservationCreatedForAdmin($appointment));
```

**Data yang disertakan:**
- appointment_id
- patient_id
- doctor_id
- booking_code
- scheduled_at
- url → `/admin/appointments/{id}`

---

#### 2. **QueueStatusUpdatedForAdmin**
Dikirim ke admin ketika status antrian berubah.

```php
$admin->notify(new QueueStatusUpdatedForAdmin($queue));
```

**Status yang dapat berubah:**
- `waiting` → menunggu
- `called` → dipanggil
- `served` → dilayani
- `skipped` → dilewati

---

#### 3. **ScheduleChangedForDoctor**
Dikirim ke dokter ketika jadwal praktik diubah.

```php
$doctor->user->notify(new ScheduleChangedForDoctor($schedule));
```

---

#### 4. **NewReservationForDoctor**
Dikirim ke dokter ketika ada reservasi baru untuk dia.

```php
$doctor->user->notify(new NewReservationForDoctor($appointment));
```

---

#### 5. **ReservationApprovedForPatient**
Dikirim ke pasien ketika reservasi disetujui admin.

```php
$patient->user->notify(new ReservationApprovedForPatient($appointment));
```

---

#### 6. **QueueCalledForPatient**
Dikirim ke pasien ketika antrian dipanggil.

```php
$patient->user->notify(new QueueCalledForPatient($queue));
```

---

#### 7. **ReservationCancelledForPatient**
Dikirim ke pasien ketika reservasi dibatalkan.

```php
$patient->user->notify(new ReservationCancelledForPatient($appointment));
```

---

## Service Layer

### NotificationService

Gunakan `NotificationService` untuk operasi notifikasi terpusat:

```php
use App\Services\NotificationService;

class YourController
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}
    
    public function someMethod()
    {
        // Kirim notifikasi
        $this->notificationService->notifyAdminsNewReservation($appointment);
        
        // Operasi lainnya
        $this->notificationService->markAllAsRead();
        $this->notificationService->deleteNotification($notificationId);
    }
}
```

### Method yang Tersedia

| Method | Deskripsi |
|--------|-----------|
| `notifyAdminsNewReservation($appointment)` | Kirim ke semua admin |
| `notifyDoctorNewReservation($appointment)` | Kirim ke dokter |
| `notifyAdminQueueStatusChanged($queue)` | Kirim ke admin |
| `notifyPatientQueueCalled($queue)` | Kirim ke pasien |
| `notifyDoctorScheduleChanged($schedule)` | Kirim ke dokter |
| `notifyPatientReservationApproved($appointment)` | Kirim ke pasien |
| `notifyPatientReservationCancelled($appointment)` | Kirim ke pasien |
| `markAsRead($notificationId)` | Tandai satu notifikasi |
| `markAllAsRead()` | Tandai semua notifikasi |
| `deleteNotification($notificationId)` | Hapus satu notifikasi |
| `deleteAllNotifications()` | Hapus semua notifikasi |
| `getUnreadCount()` | Hitung notifikasi belum dibaca |
| `getLatestNotifications($limit)` | Ambil notifikasi terbaru |

---

## Integrasi di Controller

### Contoh 1: Membuat Reservasi

```php
// app/Http/Controllers/AppointmentsController.php

class AppointmentsController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'      => 'required|exists:patients,id',
            'doctor_id'       => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:today',
            'complaint'       => 'required|string',
        ]);

        $appointment = Appointment::create($validated);

        // ✅ KIRIM NOTIFIKASI
        $this->notificationService->notifyAdminsNewReservation($appointment);
        $this->notificationService->notifyDoctorNewReservation($appointment);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Reservasi berhasil dibuat');
    }
}
```

### Contoh 2: Menyetujui Reservasi

```php
public function approve(Appointment $appointment)
{
    $appointment->update([
        'approval_status' => 'approved',
        'approved_by'     => auth()->id(),
        'approved_at'     => now(),
    ]);

    // ✅ KIRIM NOTIFIKASI KE PASIEN
    $this->notificationService->notifyPatientReservationApproved($appointment);

    return back()->with('success', 'Reservasi disetujui');
}
```

### Contoh 3: Update Status Antrian

```php
public function updateQueueStatus(Request $request, Queue $queue)
{
    $validated = $request->validate([
        'queue_status' => 'required|in:waiting,called,served,skipped',
    ]);

    $queue->update(['queue_status' => $validated['queue_status']]);

    // ✅ KIRIM NOTIFIKASI KE ADMIN
    $this->notificationService->notifyAdminQueueStatusChanged($queue);

    // ✅ JIKA DIPANGGIL, KIRIM KE PASIEN JUGA
    if ($validated['queue_status'] === 'called') {
        $this->notificationService->notifyPatientQueueCalled($queue);
    }

    return back()->with('success', 'Status antrian diperbarui');
}
```

---

## Blade Templates

### Dropdown Notifikasi di Header

Gunakan komponen yang sudah dibuat di `resources/views/components/notification-dropdown.blade.php`:

```blade
{{-- Dalam layout Anda (misal resources/views/layouts/app.blade.php) --}}

<header>
    <nav class="navbar">
        <div class="container-fluid">
            {{-- Brand --}}
            <a class="navbar-brand" href="/">Reservasi Dokter</a>

            {{-- Notification Dropdown --}}
            @include('components.notification-dropdown')

            {{-- User Menu --}}
            <div class="user-menu">
                {{-- ...user menu items --}}
            </div>
        </div>
    </nav>
</header>
```

### Halaman Notifikasi Lengkap

Akses halaman lengkap di `/notifications`:

```blade
{{-- resources/views/notifications/index.blade.php --}}

@extends('layouts.app')

@section('content')
    {{-- Lihat file resources/views/notifications/index.blade.php --}}
@endsection
```

### Custom Rendering Notifikasi

Jika ingin customize tampilan dropdown:

```blade
@forelse(auth()->user()->unreadNotifications as $notification)
    @php 
        $data = $notification->data;
        $levelMap = [
            'success'  => 'text-success',
            'info'     => 'text-info',
            'warning'  => 'text-warning',
            'critical' => 'text-danger',
        ];
    @endphp
    
    <a href="{{ $data['url'] ?? '#' }}" class="notification-item">
        <i class="{{ $data['icon'] }} {{ $levelMap[$data['level']] }}"></i>
        <div>
            <strong>{{ $data['title'] }}</strong>
            <p>{{ $data['message'] }}</p>
            <small>{{ $notification->created_at->diffForHumans() }}</small>
        </div>
    </a>
@empty
    <div class="text-muted text-center p-3">
        Tidak ada notifikasi
    </div>
@endforelse
```

---

## API Routes

### Tambahkan ke `routes/web.php`

```php
Route::middleware('auth')->group(function () {
    // Notification routes
    Route::prefix('notifications')->group(function () {
        // Lihat daftar notifikasi
        Route::get('/', [NotificationsController::class, 'index'])->name('notifications.index');
        
        // Get jumlah unread
        Route::get('/unread-count', [NotificationsController::class, 'getUnreadCount'])
            ->name('notifications.unreadCount');
        
        // Get notifikasi terbaru
        Route::get('/latest/{limit?}', [NotificationsController::class, 'getLatest'])
            ->name('notifications.latest');
        
        // Tandai satu sebagai dibaca
        Route::put('/{notificationId}/mark-as-read', [NotificationsController::class, 'markAsRead'])
            ->name('notifications.markAsRead');
        
        // Tandai semua sebagai dibaca
        Route::put('/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])
            ->name('notifications.markAllAsRead');
        
        // Lihat detail notifikasi
        Route::get('/{notificationId}', [NotificationsController::class, 'show'])
            ->name('notifications.show');
        
        // Hapus notifikasi
        Route::delete('/{notificationId}', [NotificationsController::class, 'destroy'])
            ->name('notifications.destroy');
        
        // Hapus semua notifikasi
        Route::delete('/', [NotificationsController::class, 'deleteAll'])
            ->name('notifications.deleteAll');
    });
});
```

---

## Menambah Event Notifikasi Baru

### Step 1: Buat Notification Class

```bash
php artisan make:notification YourNewNotification
```

### Step 2: Implement Class

```php
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class PaymentReceivedForPatient extends Notification
{
    public function __construct(private $payment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'role'           => 'pasien',
            'event'          => 'payment_received',
            'title'          => 'Pembayaran Diterima',
            'message'        => "Pembayaran Anda sebesar Rp. {$this->payment->amount} telah diterima.",
            'icon'           => 'fas fa-money-bill-wave',
            'level'          => 'success',
            'appointment_id' => $this->payment->appointment_id,
            'url'            => route('patient.payments.show', $this->payment->id),
        ];
    }
}
```

### Step 3: Tambah Method di NotificationService

```php
// app/Services/NotificationService.php

public function notifyPatientPaymentReceived(Payment $payment): void
{
    $patient = $payment->appointment->patient;
    if ($patient && $patient->user) {
        $patient->user->notify(new PaymentReceivedForPatient($payment));
    }
}
```

### Step 4: Gunakan di Controller

```php
class PaymentsController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function markAsPaid(Payment $payment)
    {
        $payment->update(['status' => 'paid']);
        
        // ✅ KIRIM NOTIFIKASI
        $this->notificationService->notifyPatientPaymentReceived($payment);
        
        return back()->with('success', 'Pembayaran ditandai sebagai terima');
    }
}
```

---

## Best Practices

### 1. **Selalu Gunakan NotificationService**
```php
// ✅ BAIK
$this->notificationService->notifyAdminsNewReservation($appointment);

// ❌ HINDARI - Terlalu verbose
foreach (User::where('role', 'admin')->get() as $admin) {
    $admin->notify(new NewReservationCreatedForAdmin($appointment));
}
```

### 2. **Validasi Notifiable**
```php
public function notifyDoctorNewReservation(Appointment $appointment): void
{
    $doctor = $appointment->doctor;
    if ($doctor && $doctor->user) {  // ✅ Check relasi
        $doctor->user->notify(new NewReservationForDoctor($appointment));
    }
}
```

### 3. **Gunakan Queue untuk Performance**
```php
// Jika notifikasi banyak, gunakan queue:
public function via(object $notifiable): array
{
    return ['database', 'queue'];  // Queue untuk proses async
}
```

### 4. **Konsisten dengan Event Names**
Jangan gunakan event name random:
```php
// ✅ Gunakan enum atau constants
const EVENT_RESERVATION_CREATED = 'reservation_created';
const EVENT_QUEUE_CALLED = 'queue_called';

'event' => self::EVENT_RESERVATION_CREATED,

// ❌ Hindari typo
'event' => 'reservattion_created',
```

### 5. **Field URL Selalu Valid**
```php
// ✅ Selalu check apakah route terdaftar
'url' => route('admin.appointments.show', $appointment->id),

// ❌ Hindari hardcode URL
'url' => "/admin/appointments/{$appointment->id}",
```

### 6. **Format DateTime Konsisten**
```php
// ✅ Selalu gunakan Y-m-d H:i:s
'scheduled_at' => $appointment->appointment_date?->format('Y-m-d H:i:s'),

// ❌ Hindari format tidak konsisten
'scheduled_at' => $appointment->appointment_date->toDateTimeString(),
```

---

## Testing

### Unit Test untuk Notification

```php
// tests/Unit/Notifications/NewReservationCreatedForAdminTest.php

namespace Tests\Unit\Notifications;

use App\Models\Appointment;
use App\Models\User;
use App\Notifications\NewReservationCreatedForAdmin;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NewReservationCreatedForAdminTest extends TestCase
{
    public function test_notification_has_correct_structure()
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $appointment = Appointment::factory()->create();

        $admin->notify(new NewReservationCreatedForAdmin($appointment));

        Notification::assertSentTo(
            [$admin],
            NewReservationCreatedForAdmin::class,
            function ($notification) {
                $data = $notification->toArray(null);
                
                return $data['role'] === 'admin'
                    && $data['event'] === 'reservation_created'
                    && isset($data['title'])
                    && isset($data['message'])
                    && isset($data['icon'])
                    && isset($data['level'])
                    && isset($data['appointment_id'])
                    && isset($data['url']);
            }
        );
    }

    public function test_notification_has_valid_data()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $appointment = Appointment::factory()->create();

        $notification = new NewReservationCreatedForAdmin($appointment);
        $data = $notification->toArray($admin);

        $this->assertNotEmpty($data['title']);
        $this->assertNotEmpty($data['message']);
        $this->assertNotEmpty($data['icon']);
        $this->assertIn($data['level'], ['info', 'success', 'warning', 'critical']);
    }
}
```

### Feature Test untuk NotificationService

```php
// tests/Feature/NotificationServiceTest.php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    protected NotificationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(NotificationService::class);
    }

    public function test_notify_admins_new_reservation()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $appointment = Appointment::factory()->create();

        $this->service->notifyAdminsNewReservation($appointment);

        $this->assertNotNull(
            $admin->notifications()->first()
        );
    }

    public function test_mark_as_read()
    {
        $user = User::factory()->create();
        $notification = DatabaseNotification::create([
            'id' => '00000000-0000-0000-0000-000000000000',
            'notifiable_id' => $user->id,
            'notifiable_type' => User::class,
            'type' => 'App\\Notifications\\TestNotification',
            'data' => [],
        ]);

        $this->actingAs($user);
        $this->service->markAsRead($notification->id);

        $this->assertNotNull($notification->refresh()->read_at);
    }
}
```

---

## Troubleshooting

### Notifikasi tidak muncul
- **Check:** User model punya trait `Notifiable`
- **Check:** Notifications table sudah ada (jalankan `php artisan migrate`)
- **Check:** Route sudah terdaftar

### Data JSON tidak konsisten
- **Check:** Semua Notification class gunakan struktur sama
- **Check:** Field URL valid dan route terdaftar

### Performance issue
- Gunakan `queue` untuk proses async
- Implement pagination di halaman notifikasi
- Archive old notifications secara periodik

---

## Referensi

- [Laravel Notifications Documentation](https://laravel.com/docs/notifications)
- [Database Notifications](https://laravel.com/docs/notifications#database-notifications)
- [Font Awesome Icons](https://fontawesome.com/icons)

---

**Dibuat:** 18 May 2026  
**Version:** 1.0  
**Status:** Production Ready
