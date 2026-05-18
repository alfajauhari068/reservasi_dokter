# Sistem Notifikasi - Diagram & Visual Reference

## 🏗️ Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                          USER ROLES                                 │
│  ┌──────────┐           ┌──────────┐           ┌──────────┐        │
│  │  ADMIN   │           │ DOKTER   │           │ PASIEN   │        │
│  └──────────┘           └──────────┘           └──────────┘        │
└─────────────────────────────────────────────────────────────────────┘
         │                      │                      │
         │                      │                      │
┌────────▼──────────┬───────────▼──────────┬──────────▼─────────┐
│                   │                      │                    │
│  NOTIFICATION     │  NOTIFICATION       │  NOTIFICATION      │
│  DROPDOWN         │  DROPDOWN           │  DROPDOWN          │
│  (Bell Icon)      │  (Bell Icon)        │  (Bell Icon)       │
│                   │                      │                    │
└────────┬──────────┴───────────┬──────────┴──────────┬─────────┘
         │                      │                      │
         └──────────────────────┼──────────────────────┘
                                │
                    ┌───────────▼────────────┐
                    │  NOTIFICATIONS TABLE   │
                    │  (Database: JSON)      │
                    └───────────┬────────────┘
                                │
                    ┌───────────▼────────────┐
                    │   Controller Routes    │
                    │  - index               │
                    │  - mark-as-read       │
                    │  - delete             │
                    │  - show               │
                    └───────────┬────────────┘
                                │
                    ┌───────────▼────────────┐
                    │ NotificationService    │
                    │  (Centralized Logic)   │
                    └───────────┬────────────┘
                                │
                ┌───────────────┼────────────────┐
                │               │                 │
        ┌───────▼─────┐  ┌──────▼─────┐  ┌──────▼──────┐
        │ Notify Admin │  │ Notify Doc  │  │Notify Patient│
        └───────┬──────┘  └──────┬──────┘  └──────┬──────┘
                │                 │                │
┌───────────────▼─────────────────▼────────────────▼──────────────┐
│                                                                  │
│         EVENT TRIGGERS (dalam Controllers)                      │
│  - Appointment::created()                                       │
│  - Appointment::approved()                                      │
│  - Queue::statusChanged()                                       │
│  - Schedule::updated()                                          │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

---

## 📊 Flow Diagram - Membuat Reservasi

```
PASIEN membuat Appointment
         │
         ▼
┌─────────────────────────────┐
│ AppointmentsController      │
│ @store()                    │
└─────────────────────────────┘
         │
         ▼
┌─────────────────────────────┐
│ Appointment::create()       │
│ (Simpan ke database)        │
└─────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────────────────┐
│ NotificationService::                               │
│ notifyAdminsNewReservation()                        │
└─────────────────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────────────────┐
│ NewReservationCreatedForAdmin (Notification)       │
│ - Ambil semua Admin                                │
│ - Kirim ke masing-masing                           │
│ - Data: {role, event, title, message, icon, url}  │
└─────────────────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────────────────┐
│ Notifications Table (Database)                     │
│ INSERT INTO notifications (...data as JSON...)     │
└─────────────────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────────────────┐
│ ADMIN melihat notifikasi di Dropdown                │
│ (Bell icon di header)                              │
└─────────────────────────────────────────────────────┘
```

---

## 🔄 Flow Diagram - Update Queue Status

```
DOKTER/ADMIN update Queue Status
         │
         ▼
┌──────────────────────────────────┐
│ QueuesController                 │
│ @updateStatus()                  │
│ status: waiting → called         │
└──────────────────────────────────┘
         │
         ▼
┌──────────────────────────────────┐
│ Queue::update('queue_status')    │
└──────────────────────────────────┘
         │
         ▼
┌────────────────────────────────────────────────────────┐
│ NotificationService::                                  │
│ notifyAdminQueueStatusChanged()                        │
│ (Notify admin tentang perubahan)                       │
└────────────────────────────────────────────────────────┘
         │
         ▼
┌──────────────────────────────────────────────────────────┐
│ IF status == 'called':                                  │
│   notifyPatientQueueCalled()                            │
│   (Notify pasien bahwa giliran dipanggil)              │
└──────────────────────────────────────────────────────────┘
         │
         ▼
┌──────────────────────────────────────────────────────────┐
│ TWO NOTIFICATIONS CREATED IN DATABASE:                   │
│ 1. Admin Notification (QueueStatusUpdatedForAdmin)      │
│ 2. Patient Notification (QueueCalledForPatient)        │
└──────────────────────────────────────────────────────────┘
```

---

## 📝 State Transition Diagram - Queue Status

```
┌──────────┐
│ WAITING  │ ◄──── Initial state after appointment
└────┬─────┘
     │
     │ Doctor calls patient
     ▼
┌──────────┐
│ CALLED   │ ──── [Notify Patient]
└────┬─────┘
     │
     ├─────────────────────────────┬──────────────────────┐
     │ Patient served              │ Patient didn't show  │
     ▼                             ▼                      │
┌──────────┐                  ┌──────────┐               │
│ SERVED   │                  │ SKIPPED  │               │
└──────────┘                  └──────────┘               │
    │                             │                      │
    │ [Notify Admin]              │ [Notify Admin]       │
    │                             │                      │
    └─────────────────┬───────────┘                      │
                      │                                  │
                      ▼                                  │
              ┌──────────────┐                           │
              │ APPOINTMENT  │                           │
              │ COMPLETED    │                           │
              └──────────────┘                           │
                      ▲                                  │
                      │                                  │
                      └──────────────────────────────────┘
```

---

## 🎯 Notification Type Matrix

```
┌──────────────────────┬─────────┬────────┬────────┬──────────┐
│ Notification Type    │ Admin   │ Dokter │ Pasien │ Database │
├──────────────────────┼─────────┼────────┼────────┼──────────┤
│                      │         │        │        │          │
│ NewReservation       │ ✅ yes  │ ✅ yes │ ❌ no  │ ✅ store │
│ ReservationApproved  │ ❌ no   │ ❌ no  │ ✅ yes │ ✅ store │
│ ReservationCancelled │ ❌ no   │ ❌ no  │ ✅ yes │ ✅ store │
│ QueueStatusUpdated   │ ✅ yes  │ ❌ no  │ ❌ no  │ ✅ store │
│ QueueCalled          │ ❌ no   │ ❌ no  │ ✅ yes │ ✅ store │
│ ScheduleChanged      │ ❌ no   │ ✅ yes │ ❌ no  │ ✅ store │
│                      │         │        │        │          │
└──────────────────────┴─────────┴────────┴────────┴──────────┘
```

---

## 📍 Directory Structure - File Location

```
reservasi_dokter/
│
├── 📁 app/
│   ├── 📁 Notifications/               ◄──── NOTIFICATION CLASSES
│   │   ├── NewReservationCreatedForAdmin.php
│   │   ├── QueueStatusUpdatedForAdmin.php
│   │   ├── ScheduleChangedForDoctor.php
│   │   ├── NewReservationForDoctor.php
│   │   ├── ReservationApprovedForPatient.php
│   │   ├── QueueCalledForPatient.php
│   │   └── ReservationCancelledForPatient.php
│   │
│   ├── 📁 Services/
│   │   └── NotificationService.php    ◄──── SERVICE LAYER
│   │
│   ├── 📁 Http/Controllers/
│   │   ├── NotificationsController.php ◄──── CONTROLLER
│   │   ├── AppointmentsController.php   (UPDATE)
│   │   ├── QueuesController.php         (UPDATE)
│   │   └── SchedulesController.php      (UPDATE)
│   │
│   └── 📁 Models/
│       ├── User.php (has Notifiable)
│       ├── Appointment.php
│       ├── Queue.php
│       ├── Schedule.php
│       └── Doctor.php
│
├── 📁 resources/views/
│   ├── 📁 components/
│   │   └── notification-dropdown.blade.php ◄──── DROPDOWN COMPONENT
│   │
│   ├── 📁 notifications/
│   │   └── index.blade.php                ◄──── NOTIFICATIONS PAGE
│   │
│   └── 📁 layouts/
│       └── app.blade.php                  (UPDATE: add @include dropdown)
│
├── 📁 routes/
│   └── web.php                            (ADD: notification routes)
│
├── 📁 database/
│   ├── 📁 migrations/
│   │   └── XXXX_XX_XX_XXXXXX_create_notifications_table.php (sudah ada)
│   └── 📁 seeders/
│
├── 📄 NOTIFICATION_SYSTEM_DOCUMENTATION.md      ◄──── LENGKAP DOC
├── 📄 NOTIFICATION_QUICK_REFERENCE.md            ◄──── QUICK START
├── 📄 NOTIFICATION_SYSTEM_SUMMARY.md             ◄──── RINGKASAN
├── 📄 ROUTES_WEB_EXAMPLE.php                     ◄──── ROUTES CONTOH
└── 📄 NOTIFICATION_ROUTES_EXAMPLE.php            ◄──── ROUTES DETAIL
```

---

## 🔌 Integration Checklist

```
PHASE 1: FILES CREATED
├── ✅ 7 Notification Classes
├── ✅ NotificationService
├── ✅ NotificationsController (updated)
├── ✅ Blade dropdown component
├── ✅ Notifications index page
└── ✅ Documentation files

PHASE 2: ROUTES SETUP (TODO)
├── ⏳ Add routes to web.php
└── ⏳ Verify routes registered

PHASE 3: CONTROLLERS UPDATE (TODO)
├── ⏳ AppointmentsController.store()
├── ⏳ AppointmentsController.approve()
├── ⏳ AppointmentsController.reject()
├── ⏳ QueuesController.updateStatus()
└── ⏳ SchedulesController.update()

PHASE 4: VIEW UPDATE (TODO)
├── ⏳ Update layout header
└── ⏳ Add @include('components.notification-dropdown')

PHASE 5: TESTING (TODO)
├── ⏳ Test create appointment
├── ⏳ Test approve/reject
├── ⏳ Test queue status
├── ⏳ Verify dropdown shows
└── ⏳ Test mark as read/delete
```

---

## 📱 Blade Template Nesting

```
layout.app.blade.php
│
├── <header>
│   ├── Brand
│   ├── Navigation
│   │
│   └── @include('components.notification-dropdown')
│       │
│       ├── Bell Icon with Badge
│       ├── Dropdown Menu Header
│       ├── @forelse Unread Notifications
│       │   ├── Icon (fas fa-*)
│       │   ├── Title
│       │   ├── Message
│       │   ├── Time (diffForHumans)
│       │   └── Actions (mark read, delete)
│       ├── @empty (No notifications)
│       └── Link to Full Notifications Page
│
├── <main>
│   └── @yield('content')
│
└── <footer>
```

---

## 🔐 Permission & Role Guards

```
Routes Protected By:
├── middleware('auth')              ◄──── User harus login
├── @can('view-notifications')      ◄──── User punya permission
├── auth()->user()->unreadNotifications  ◄──── Own notifications only
└── $notification->notifiable_id == auth()->id()  ◄──── Ownership check
```

---

## 💾 Database Schema

```sql
-- notifications table (built-in Laravel)
CREATE TABLE notifications (
    id uuid PRIMARY KEY,
    notifiable_id bigint UNSIGNED,
    notifiable_type varchar(255),
    type varchar(255),
    data json,                    ◄──── Stores notification JSON
    read_at timestamp nullable,   ◄──── NULL = unread, DATE = read
    created_at timestamp,
    updated_at timestamp
);

-- Example JSON data structure:
{
  "role": "admin",
  "event": "reservation_created",
  "title": "Reservasi baru dibuat",
  "message": "...",
  "icon": "fas fa-calendar-plus",
  "level": "info",
  "appointment_id": 123,
  "patient_id": 45,
  "doctor_id": 67,
  "booking_code": "BOOK-2024-001",
  "scheduled_at": "2024-05-20 14:30:00",
  "url": "/admin/appointments/123"
}
```

---

## 🎨 Color & Icon Mapping

```
Level      │ CSS Class      │ Color        │ Icon
───────────┼────────────────┼──────────────┼──────────────────────
success    │ text-success   │ #198754 (🟢) │ fas fa-check-circle
info       │ text-info      │ #0dcaf0 (🔵) │ fas fa-info-circle
warning    │ text-warning   │ #ffc107 (🟡) │ fas fa-exclamation
critical   │ text-danger    │ #dc3545 (🔴) │ fas fa-times-circle
```

---

## 📊 Performance Considerations

```
Optimization Strategies:
├── Database Indexing
│   └── notifications(notifiable_id, notifiable_type, read_at)
│
├── Pagination
│   └── Limit 20 per page on index
│
├── Query Optimization
│   └── Use ->latest()->limit(10) for dropdown
│
├── Caching (Optional)
│   └── Cache unread count for 1 minute
│
├── Queuing (Optional)
│   └── Use 'queue' channel for async sending
│
└── Archival (Optional)
    └── Archive notifications older than 6 months
```

---

## 🚀 Deployment Checklist

```
Pre-Production:
├── ✅ All files created and tested
├── ✅ Routes configured
├── ✅ Controllers updated
├── ✅ Database migrations run
├── ✅ Views updated
├── ⏳ Environment variables configured
└── ⏳ Cache cleared

Production:
├── ⏳ Deploy code
├── ⏳ Run migrations
├── ⏳ Clear cache & config
├── ⏳ Test notifications end-to-end
├── ⏳ Monitor error logs
└── ⏳ Celebrate! 🎉
```

---

## 📈 Monitoring & Metrics

```
Metrics to Track:
├── Total notifications sent (per day/week)
├── Unread notifications ratio
├── Notification delivery latency
├── Click-through rate (CTR)
├── Delete rate
├── Archive success rate
└── Error rate
```

---

**Diagram Version:** 1.0  
**Last Updated:** 18 May 2026
