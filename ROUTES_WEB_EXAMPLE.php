<?php

/**
 * ROUTES WEB CONFIGURATION - NOTIFICATION ROUTES EXAMPLE
 * 
 * FILE: routes/web.php
 * 
 * INSTRUKSI:
 * 1. Buka file routes/web.php
 * 2. Cari bagian Route::middleware('auth') yang sudah ada
 * 3. Copy dan paste routes di bawah ke dalam middleware group tersebut
 * 4. Jangan lupa import NotificationsController di bagian atas file
 * 
 * IMPORT (Tambahkan di bagian atas routes/web.php):
 * use App\Http\Controllers\NotificationsController;
 * 
 * ============================================
 * COMPLETE EXAMPLE STRUKTUR
 * ============================================
 */

// ============ BAGIAN ATAS FILE routes/web.php ============

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\QueuesController;
use App\Http\Controllers\SchedulesController;
use App\Http\Controllers\NotificationsController;  // ✅ IMPORT INI
// ... import controllers lainnya ...

// ============ ROUTES UNAUTHENTICATED ============

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ============ ROUTES AUTHENTICATED ============

Route::middleware('auth')->group(function () {
    
    // ========================================
    // APPOINTMENT ROUTES (Contoh yang sudah ada)
    // ========================================
    Route::apiResource('appointments', AppointmentsController::class);
    Route::post('appointments/{appointment}/approve', [AppointmentsController::class, 'approve'])
        ->name('appointments.approve');
    Route::post('appointments/{appointment}/reject', [AppointmentsController::class, 'reject'])
        ->name('appointments.reject');

    // ========================================
    // QUEUE ROUTES (Contoh yang sudah ada)
    // ========================================
    Route::apiResource('queues', QueuesController::class);

    // ========================================
    // SCHEDULE ROUTES (Contoh yang sudah ada)
    // ========================================
    Route::apiResource('schedules', SchedulesController::class);

    // ========================================
    // ✅ NOTIFICATION ROUTES (TAMBAHKAN BAGIAN INI)
    // ========================================
    Route::prefix('notifications')->group(function () {
        // List semua notifikasi (paginated)
        Route::get('/', [NotificationsController::class, 'index'])
            ->name('notifications.index');
        
        // Get jumlah unread notifications (untuk AJAX)
        Route::get('/unread-count', [NotificationsController::class, 'getUnreadCount'])
            ->name('notifications.unreadCount');
        
        // Get notifikasi terbaru (untuk AJAX dropdown)
        Route::get('/latest/{limit?}', [NotificationsController::class, 'getLatest'])
            ->name('notifications.latest');
        
        // Lihat detail notifikasi
        Route::get('/{notificationId}', [NotificationsController::class, 'show'])
            ->name('notifications.show');
        
        // Tandai satu notifikasi sebagai dibaca
        Route::put('/{notificationId}/mark-as-read', [NotificationsController::class, 'markAsRead'])
            ->name('notifications.markAsRead');
        
        // Tandai semua notifikasi sebagai dibaca
        Route::put('/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])
            ->name('notifications.markAllAsRead');
        
        // Hapus satu notifikasi
        Route::delete('/{notificationId}', [NotificationsController::class, 'destroy'])
            ->name('notifications.destroy');
        
        // Hapus semua notifikasi
        Route::delete('/', [NotificationsController::class, 'deleteAll'])
            ->name('notifications.deleteAll');
    });
    // ========================================
    // END NOTIFICATION ROUTES
    // ========================================

    // ... routes lainnya ...
});

/**
 * ============================================
 * OPSI ALTERNATIF: GROUPED DENGAN PREFIX
 * ============================================
 * 
 * Jika ingin dengan admin/doctor/patient prefix:
 * 
 * Route::middleware('auth', 'role:admin')->prefix('admin')->group(function () {
 *     Route::get('/notifications', [NotificationsController::class, 'index'])->name('admin.notifications.index');
 *     Route::delete('/notifications/{id}', [NotificationsController::class, 'destroy'])->name('admin.notifications.destroy');
 * });
 * 
 * Route::middleware('auth', 'role:doctor')->prefix('doctor')->group(function () {
 *     Route::get('/notifications', [NotificationsController::class, 'index'])->name('doctor.notifications.index');
 *     Route::delete('/notifications/{id}', [NotificationsController::class, 'destroy'])->name('doctor.notifications.destroy');
 * });
 * 
 * Route::middleware('auth', 'role:patient')->prefix('patient')->group(function () {
 *     Route::get('/notifications', [NotificationsController::class, 'index'])->name('patient.notifications.index');
 *     Route::delete('/notifications/{id}', [NotificationsController::class, 'destroy'])->name('patient.notifications.destroy');
 * });
 */

/**
 * ============================================
 * TESTING ROUTES (OPSIONAL - untuk development)
 * ============================================
 * 
 * Route::get('/test-notification', function () {
 *     $user = auth()->user();
 *     $appointment = \App\Models\Appointment::first();
 *     
 *     // Trigger notifikasi untuk testing
 *     $service = app(\App\Services\NotificationService::class);
 *     $service->notifyAdminsNewReservation($appointment);
 *     
 *     return 'Notifikasi terkirim!';
 * })->middleware('auth');
 */
