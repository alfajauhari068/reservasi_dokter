# ✅ Implementation Checklist - Sistem Notifikasi Database

**Tanggal:** 18 May 2026  
**Status:** Ready for Implementation  
**Estimated Time:** 30-45 minutes  

---

## 📋 Pre-Implementation

### Understanding
- [ ] Read `DOCUMENTATION_INDEX.md` (this helps you decide which docs to read)
- [ ] Read `NOTIFICATION_SYSTEM_SUMMARY.md` (overview)
- [ ] Read `NOTIFICATION_QUICK_REFERENCE.md` → Section "Quick Start"
- [ ] Review `NOTIFICATION_ARCHITECTURE_DIAGRAMS.md` (understand flow)

### Verification
- [ ] Laravel migration for `notifications` table exists
- [ ] User model has `Notifiable` trait
- [ ] `app/Notifications/` directory exists
- [ ] `app/Services/` directory exists

---

## 🔧 Phase 1: Routes Setup (5 minutes)

### Task 1.1: Copy Routes Configuration
```bash
📝 TODO:
1. Open: routes/web.php
2. Open: ROUTES_WEB_EXAMPLE.php (reference)
3. Copy the import line at the top:
   use App\Http\Controllers\NotificationsController;
4. Paste to top of routes/web.php
```

- [ ] Import NotificationsController added
- [ ] Verify import statement: `use App\Http\Controllers\NotificationsController;`

### Task 1.2: Add Notification Routes
```bash
📝 TODO:
1. In routes/web.php, find Route::middleware('auth')->group(function () {
2. Copy entire notification routes group from ROUTES_WEB_EXAMPLE.php
3. Paste inside the middleware('auth') group
4. Save file
```

- [ ] Notification routes added inside `middleware('auth')` group
- [ ] All 8 routes added:
  - [ ] GET /notifications
  - [ ] GET /notifications/unread-count
  - [ ] GET /notifications/latest/{limit}
  - [ ] GET /notifications/{notificationId}
  - [ ] PUT /notifications/{notificationId}/mark-as-read
  - [ ] PUT /notifications/mark-all-as-read
  - [ ] DELETE /notifications/{notificationId}
  - [ ] DELETE /notifications

### Task 1.3: Verify Routes
```bash
php artisan route:list | grep notification
```

- [ ] Routes listed and accessible
- [ ] All 8 notification routes showing

---

## 🎨 Phase 2: Views Setup (5 minutes)

### Task 2.1: Update Layout Header
```bash
📝 TODO:
1. Open your main layout: resources/views/layouts/app.blade.php (or equivalent)
2. Find the header/navbar section
3. Open: resources/views/components/notification-dropdown.blade.php (reference)
4. Add this line in header where you want the bell icon:
   @include('components.notification-dropdown')
5. Save file
```

- [ ] Layout file opened
- [ ] Found appropriate location in header/navbar
- [ ] Added `@include('components.notification-dropdown')`
- [ ] Saved layout file
- [ ] Tested: Refresh page, see bell icon appear

### Task 2.2: Verify Blade Component
```bash
📝 Manual Test:
1. Login as any user
2. Navigate to dashboard
3. Look at top-right corner of header
4. Bell icon should be visible with badge (if unread exists)
```

- [ ] Bell icon visible in header
- [ ] Badge shows unread count (if any)
- [ ] Dropdown opens when clicked

---

## 🔌 Phase 3: Controller Integration (15 minutes)

### Task 3.1: Update AppointmentsController
```bash
📝 TODO:
1. Open: app/Http/Controllers/AppointmentsController.php
2. Add at top:
   use App\Services\NotificationService;
3. In constructor or add new:
   protected NotificationService $notificationService;
4. In store() method, after creating appointment:
   $this->notificationService->notifyAdminsNewReservation($appointment);
   $this->notificationService->notifyDoctorNewReservation($appointment);
5. Add approve() method with:
   $appointment->update(['approval_status' => 'approved', ...]);
   $this->notificationService->notifyPatientReservationApproved($appointment);
6. Add reject/cancel() method with:
   $appointment->update(['approval_status' => 'rejected', ...]);
   $this->notificationService->notifyPatientReservationCancelled($appointment);
```

Reference: `NotificationImplementationExamplesController.php` (Examples 1-3)

- [ ] Import added: `use App\Services\NotificationService;`
- [ ] Constructor injection added
- [ ] store() method updated with notifications
- [ ] approve() method has notification
- [ ] reject/cancel() method has notification
- [ ] No PHP errors when checking syntax

### Task 3.2: Update QueuesController
```bash
📝 TODO:
1. Open: app/Http/Controllers/QueuesController.php
2. Add import & injection (same as above)
3. In update() or updateStatus() method:
   $queue->update($validated);
   $this->notificationService->notifyAdminQueueStatusChanged($queue);
   if ($validated['queue_status'] === 'called') {
       $this->notificationService->notifyPatientQueueCalled($queue);
   }
```

Reference: `NotificationImplementationExamplesController.php` (Example 4)

- [ ] Import added
- [ ] Injection added
- [ ] Status update method has notifications
- [ ] Both admin & patient notifications sent
- [ ] No PHP errors

### Task 3.3: Update SchedulesController
```bash
📝 TODO:
1. Open: app/Http/Controllers/SchedulesController.php
2. Add import & injection
3. In update() method after updating:
   $this->notificationService->notifyDoctorScheduleChanged($schedule);
```

Reference: `NotificationImplementationExamplesController.php` (Example 5)

- [ ] Import added
- [ ] Injection added
- [ ] update() method has notification
- [ ] No PHP errors

---

## 🧪 Phase 4: Testing (10 minutes)

### Test 4.1: Routes Testing
```bash
php artisan artisan route:list | grep notification
```

- [ ] All 8 notification routes showing

### Test 4.2: Create Appointment Test
```bash
📝 Manual Test:
1. Login as patient
2. Create an appointment (or use API)
3. Check: Admin user should see notification in dropdown
4. Check: Doctor should see notification
5. Check: Notifications saved in database
```

```bash
php artisan tinker
> auth()->user()->notifications()->latest()->first()
```

- [ ] Admin received notification
- [ ] Doctor received notification
- [ ] Data in database shows correct structure
- [ ] All fields present (title, message, icon, etc.)

### Test 4.3: Approve/Reject Test
```bash
📝 Manual Test:
1. As admin, approve/reject an appointment
2. Login as patient
3. Patient should see notification in dropdown
4. Click notification → should redirect to appointment page
```

- [ ] Patient received approval notification
- [ ] Notification title/message correct
- [ ] Link works and redirects correctly

### Test 4.4: Queue Status Test
```bash
📝 Manual Test:
1. Update queue status (waiting → called)
2. Admin should see update notification
3. Patient should see "queue called" notification
4. Check notifications page: /notifications
```

- [ ] Admin notification received
- [ ] Patient notification received
- [ ] Notifications page working at /notifications
- [ ] Can mark as read
- [ ] Can delete

### Test 4.5: Dropdown Functionality
```bash
📝 Manual Test:
1. Click bell icon
2. See list of unread notifications
3. Click notification
4. Should mark as read & redirect
5. Click "Lihat semua notifikasi" link
6. Should go to /notifications page
```

- [ ] Dropdown shows unread
- [ ] Clicking redirects correctly
- [ ] Marked as read after click
- [ ] Can delete from dropdown
- [ ] Full page link works

---

## 🚀 Phase 5: Production Ready (5 minutes)

### Task 5.1: Database Check
```bash
php artisan migrate:status
```

- [ ] All migrations completed
- [ ] notifications table migrated

### Task 5.2: Cache & Config Clear
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

- [ ] All cleared

### Task 5.3: Final Verification
```bash
📝 Final Checks:
1. No console errors
2. No database errors in logs
3. All notifications working
4. All tests passing
```

- [ ] Storage/logs shows no errors
- [ ] No 500 errors in app
- [ ] All features working

---

## 📊 Verification Checklist

### Routes
- [ ] 8 notification routes registered
- [ ] Routes accessible without 404
- [ ] Routes require auth middleware

### Notification Classes
- [ ] 7 classes exist in `app/Notifications/`
- [ ] All have `via()` returning `['database']`
- [ ] All have `toArray()` with correct structure
- [ ] All compile without errors

### Service
- [ ] `NotificationService.php` exists
- [ ] All 7 notify methods exist
- [ ] All utility methods exist (mark read, delete, count)
- [ ] No syntax errors

### Controllers
- [ ] NotificationsController implements all methods
- [ ] AppointmentsController calls notify methods
- [ ] QueuesController calls notify methods
- [ ] SchedulesController calls notify methods

### Views
- [ ] Dropdown component renders without errors
- [ ] Notifications index page renders
- [ ] CSS styling applied correctly
- [ ] JavaScript functions work

### Database
- [ ] notifications table exists
- [ ] Notifications saving correctly
- [ ] JSON data valid
- [ ] read_at timestamp updating correctly

---

## 🐛 Troubleshooting Quick Links

| Problem | Solution |
|---------|----------|
| Routes 404 | Run `php artisan route:clear`, check routes/web.php |
| Component not showing | Check `@include` in layout, clear view cache |
| Notifications not sending | Check controller calls NotificationService method |
| Data missing | Check `toArray()` method is complete |
| Dropdown not functioning | Check JavaScript, verify routes work |
| Database errors | Check migrations run, notifications table exists |

**Detailed troubleshooting:** See `NOTIFICATION_QUICK_REFERENCE.md` → Debugging Tips

---

## ✨ Success Criteria

You will know it's working when:

✅ Bell icon appears in header  
✅ Create appointment → notifications appear in dropdown  
✅ Click notification → redirects to correct page  
✅ Mark as read → disappears from unread  
✅ Delete → removes from list  
✅ Full notifications page shows all (paginated)  
✅ All roles (admin, doctor, patient) receive correct notifications  
✅ No errors in console or logs  

---

## 📋 Sign-Off Checklist

### Before Deployment
- [ ] All phases completed
- [ ] All tests passing
- [ ] No errors in logs
- [ ] Routes working
- [ ] Views rendering
- [ ] Notifications sending
- [ ] Database saving correctly

### After Deployment
- [ ] Monitor logs for 24 hours
- [ ] Test in production environment
- [ ] Verify notifications still working
- [ ] Check database for orphaned records
- [ ] Plan for notification archival

### Documentation
- [ ] Team briefed on notification system
- [ ] Documentation linked in README
- [ ] Dev team knows how to extend
- [ ] Support team knows how to debug

---

## 📝 Notes & Comments

```
[Use this space for your notes during implementation]

Date Started: _______________
Date Completed: _______________

Issues Encountered:
_________________________________________________
_________________________________________________
_________________________________________________

Custom Modifications:
_________________________________________________
_________________________________________________
_________________________________________________

Next Steps/Future Enhancements:
_________________________________________________
_________________________________________________
_________________________________________________
```

---

## 📞 Quick Reference

**All Documentation Files:**
- `DOCUMENTATION_INDEX.md` ← Start here for file guide
- `NOTIFICATION_SYSTEM_SUMMARY.md` ← Overview
- `NOTIFICATION_QUICK_REFERENCE.md` ← Quick start
- `NOTIFICATION_SYSTEM_DOCUMENTATION.md` ← Full docs
- `NOTIFICATION_ARCHITECTURE_DIAGRAMS.md` ← Visual guide

**Implementation Examples:**
- `NotificationImplementationExamplesController.php` ← Copy-paste ready

**Configuration:**
- `ROUTES_WEB_EXAMPLE.php` ← Routes setup
- `NOTIFICATION_ROUTES_EXAMPLE.php` ← Routes detail

---

## 🎉 Completion!

When all checkboxes are ✅, you're done!

**Congratulations on implementing the Database Notification System! 🚀**

---

**Checklist Version:** 1.0  
**Last Updated:** 18 May 2026  
**Status:** Ready to Use
