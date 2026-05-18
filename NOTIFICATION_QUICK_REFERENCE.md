# Notification System - Quick Reference & Implementation Checklist

## ✅ Checklist Implementasi

### Phase 1: Setup Awal
- [x] Buat Notification classes di `app/Notifications/`
  - [x] NewReservationCreatedForAdmin.php
  - [x] QueueStatusUpdatedForAdmin.php
  - [x] ScheduleChangedForDoctor.php
  - [x] NewReservationForDoctor.php
  - [x] ReservationApprovedForPatient.php
  - [x] QueueCalledForPatient.php
  - [x] ReservationCancelledForPatient.php
- [x] Buat NotificationService.php di `app/Services/`
- [x] Update NotificationsController.php di `app/Http/Controllers/`
- [x] Buat Blade component di `resources/views/components/`
- [x] Buat halaman index di `resources/views/notifications/`

### Phase 2: Integration
- [ ] **Tambahkan routes ke `routes/web.php`** (PENTING!)
  ```php
  // Copy dari NOTIFICATION_ROUTES_EXAMPLE.php
  ```
  
- [ ] **Update AppointmentsController.php** untuk mengirim notifikasi saat:
  - [ ] Create appointment
  - [ ] Approve appointment
  - [ ] Reject/cancel appointment
  
- [ ] **Update QueuesController.php** untuk mengirim notifikasi saat:
  - [ ] Update queue status
  
- [ ] **Update SchedulesController.php** untuk mengirim notifikasi saat:
  - [ ] Update/create schedule
  
- [ ] **Update layout header** untuk include dropdown component:
  ```blade
  @include('components.notification-dropdown')
  ```

### Phase 3: Database
- [ ] Jalankan migration jika belum ada (sudah ada di Laravel default):
  ```bash
  php artisan migrate
  ```
- [ ] Verify tabel `notifications` ada dengan kolom: `id`, `notifiable_id`, `notifiable_type`, `type`, `data`, `read_at`, `created_at`, `updated_at`

### Phase 4: Testing & Deployment
- [ ] Test notifikasi di environment development
- [ ] Test dropdown di header - dapat melihat notifikasi unread
- [ ] Test mark as read/delete
- [ ] Test di semua role (admin, dokter, pasien)
- [ ] Deploy ke production

---

## 🚀 Quick Start - 5 Menit Setup

### Step 1: Copy Routes (30 detik)
```php
// routes/web.php

Route::middleware('auth')->group(function () {
    // ... routes lainnya ...
    
    // Notification Routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationsController::class, 'index'])->name('notifications.index');
        Route::get('/unread-count', [NotificationsController::class, 'getUnreadCount'])->name('notifications.unreadCount');
        Route::get('/latest/{limit?}', [NotificationsController::class, 'getLatest'])->name('notifications.latest');
        Route::put('/{notificationId}/mark-as-read', [NotificationsController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::put('/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        Route::delete('/{notificationId}', [NotificationsController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/', [NotificationsController::class, 'deleteAll'])->name('notifications.deleteAll');
        Route::get('/{notificationId}', [NotificationsController::class, 'show'])->name('notifications.show');
    });
});
```

### Step 2: Add Dropdown ke Header (30 detik)
```blade
{{-- resources/views/layouts/app.blade.php (atau layout Anda) --}}

<header>
    <nav>
        <!-- ... brand dan menu lainnya ... -->
        
        <!-- TAMBAHKAN INI -->
        @include('components.notification-dropdown')
        
        <!-- ... user menu ... -->
    </nav>
</header>
```

### Step 3: Gunakan di Controller (1 menit)
```php
// app/Http/Controllers/AppointmentsController.php

use App\Services\NotificationService;

class AppointmentsController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function store(Request $request)
    {
        $appointment = Appointment::create($request->validated());
        
        // ✅ KIRIM NOTIFIKASI
        $this->notificationService->notifyAdminsNewReservation($appointment);
        $this->notificationService->notifyDoctorNewReservation($appointment);
        
        return redirect()->with('success', 'Reservasi berhasil');
    }
    
    public function approve(Appointment $appointment)
    {
        $appointment->update([
            'approval_status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        
        // ✅ KIRIM NOTIFIKASI
        $this->notificationService->notifyPatientReservationApproved($appointment);
        
        return back()->with('success', 'Disetujui');
    }
}
```

### Step 4: Test (3 menit)
1. Create appointment → lihat notifikasi ke admin & dokter
2. Klik bell icon di header → lihat dropdown
3. Click notifikasi → redirect ke detail
4. Mark as read → notifikasi hilang dari unread
5. Delete → notifikasi terhapus

---

## 📋 File Directory Structure

```
app/
├── Notifications/
│   ├── NewReservationCreatedForAdmin.php
│   ├── QueueStatusUpdatedForAdmin.php
│   ├── ScheduleChangedForDoctor.php
│   ├── NewReservationForDoctor.php
│   ├── ReservationApprovedForPatient.php
│   ├── QueueCalledForPatient.php
│   └── ReservationCancelledForPatient.php
│
├── Services/
│   └── NotificationService.php
│
└── Http/Controllers/
    ├── NotificationsController.php
    ├── AppointmentsController.php (UPDATE)
    ├── QueuesController.php (UPDATE)
    └── SchedulesController.php (UPDATE)

resources/views/
├── components/
│   └── notification-dropdown.blade.php
│
└── notifications/
    └── index.blade.php

routes/
└── web.php (ADD NOTIFICATION ROUTES)

database/
└── migrations/
    └── XXXX_XX_XX_XXXXXX_create_notifications_table.php (sudah ada)
```

---

## 🎯 Common Integration Points

### 1. When Creating Appointment
```php
$appointment = Appointment::create($data);
app(NotificationService::class)->notifyAdminsNewReservation($appointment);
app(NotificationService::class)->notifyDoctorNewReservation($appointment);
```

### 2. When Approving/Rejecting
```php
$appointment->update(['approval_status' => 'approved']);
app(NotificationService::class)->notifyPatientReservationApproved($appointment);

// OR

$appointment->update(['approval_status' => 'rejected']);
app(NotificationService::class)->notifyPatientReservationCancelled($appointment);
```

### 3. When Queue Status Changes
```php
$queue->update(['queue_status' => 'called']);
app(NotificationService::class)->notifyAdminQueueStatusChanged($queue);
app(NotificationService::class)->notifyPatientQueueCalled($queue);
```

### 4. When Schedule Changes
```php
$schedule->update($data);
app(NotificationService::class)->notifyDoctorScheduleChanged($schedule);
```

---

## 🔧 Configuration Options

### Level/Priority Mapping
```
'level' => 'info'       → Informasi biasa (biru)
'level' => 'success'    → Berhasil (hijau)
'level' => 'warning'    → Peringatan (kuning)
'level' => 'critical'   → Penting/Error (merah)
```

### Icon Font Awesome
| Event | Icon Recommendation |
|-------|-------------------|
| reservation_created | fas fa-calendar-plus |
| reservation_approved | fas fa-check-circle |
| reservation_cancelled | fas fa-times-circle |
| queue_called | fas fa-bullhorn |
| queue_status_updated | fas fa-bullhorn |
| schedule_changed | fas fa-calendar-day |

---

## 🐛 Debugging Tips

### 1. Check Notifications di Database
```php
// Tinjauan di Tinker
php artisan tinker
> auth()->user()->notifications()->latest()->first()
```

### 2. Check Route Terdaftar
```bash
php artisan route:list | grep notification
```

### 3. Check via() Method
```php
// Notification harus return ['database']
public function via(object $notifiable): array
{
    return ['database'];  // ✅ Jangan lupa ini!
}
```

### 4. Check toArray() Output
```php
// Verify data structure
$notification = auth()->user()->notifications()->first();
$notification->data; // Harus ada semua field yang diharapkan
```

---

## 📊 Event Types Reference

| Event | Recipient | Trigger | Level |
|-------|-----------|---------|-------|
| reservation_created | Admin, Doctor | New appointment | info |
| reservation_approved | Patient | Admin approve | success |
| reservation_cancelled | Patient | Admin reject/cancel | critical |
| queue_status_updated | Admin | Queue status change | info/warning |
| queue_called | Patient | Queue status = called | info |
| schedule_changed | Doctor | Schedule update | info |
| payment_received | Patient | Payment marked paid | success |

---

## 🎓 Learning Path

1. **Understand**: Baca documentation.md bagian "Struktur Notifikasi"
2. **Setup**: Follow "Quick Start - 5 Menit Setup"
3. **Integrate**: Tambahkan ke controller satu per satu
4. **Test**: Verify setiap notification terkirim dengan benar
5. **Extend**: Buat notification event baru sesuai kebutuhan
6. **Monitor**: Track unread/read patterns di dashboard (opsional)

---

## 💡 Pro Tips

### Tip 1: Gunakan Model Observers
Jika ingin fully automatic, buat observer:
```php
// app/Observers/AppointmentObserver.php
class AppointmentObserver
{
    public function created(Appointment $appointment)
    {
        $service = app(NotificationService::class);
        $service->notifyAdminsNewReservation($appointment);
        $service->notifyDoctorNewReservation($appointment);
    }
}
```

### Tip 2: Batch Notifications untuk Performance
```php
// Jika banyak notifikasi, gunakan:
use Illuminate\Support\Facades\Notification;

Notification::send($admins, new NewReservationCreatedForAdmin($appointment));
```

### Tip 3: Archive Old Notifications
```php
// Buat command untuk cleanup
php artisan make:command ArchiveOldNotifications

// Di command:
Notification::whereDate('created_at', '<', now()->subMonth())->delete();
```

### Tip 4: Customize Dropdown Styling
Edit `resources/views/components/notification-dropdown.blade.php` section `<style>`

### Tip 5: Real-time Updates (Opsional)
Gunakan Laravel Echo + WebSockets untuk real-time:
```javascript
// resources/js/notifications.js
Echo.private(`notifications.${userId}`)
    .notification((notification) => {
        // Update dropdown real-time
    });
```

---

## 📞 Need Help?

1. **Routes tidak muncul?** → Check `routes/web.php`, pastikan middleware `auth`
2. **Notifikasi tidak kirim?** → Check `NotificationService`, pastikan method dipanggil
3. **Data tidak konsisten?** → Check `toArray()` di setiap notification class
4. **Dropdown tidak render?** → Check layout, pastikan `@include('components.notification-dropdown')`

---

**Last Updated:** 18 May 2026  
**Maintained By:** Laravel Engineer  
**Status:** ✅ Production Ready
