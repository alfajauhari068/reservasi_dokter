<?php

/**
 * NOTIFICATION ROUTES
 * 
 * Tambahkan routes ini ke routes/web.php (dalam middleware auth)
 * 
 * Contoh:
 * --------
 * Route::middleware('auth')->group(function () {
 *     // ... routes lainnya
 *     
 *     // Notifications
 *     Route::prefix('notifications')->group(function () {
 *         Route::get('/', [NotificationsController::class, 'index'])->name('notifications.index');
 *         Route::get('/unread-count', [NotificationsController::class, 'getUnreadCount'])->name('notifications.unreadCount');
 *         Route::get('/latest/{limit?}', [NotificationsController::class, 'getLatest'])->name('notifications.latest');
 *         Route::put('/{notificationId}/mark-as-read', [NotificationsController::class, 'markAsRead'])->name('notifications.markAsRead');
 *         Route::put('/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
 *         Route::delete('/{notificationId}', [NotificationsController::class, 'destroy'])->name('notifications.destroy');
 *         Route::delete('/', [NotificationsController::class, 'deleteAll'])->name('notifications.deleteAll');
 *         Route::get('/{notificationId}', [NotificationsController::class, 'show'])->name('notifications.show');
 *     });
 * });
 */

// ============================================
// ROUTES UNTUK API (OPSIONAL)
// ============================================
// 
// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('notifications', NotificationsController::class);
//     Route::get('notifications/unread/count', [NotificationsController::class, 'getUnreadCount']);
// });
