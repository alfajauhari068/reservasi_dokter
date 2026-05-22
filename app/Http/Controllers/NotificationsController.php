<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

/**
 * Controller for managing user notifications
 *
 * Handles viewing, marking as read, and deleting notifications.
 *
 * @refactor Implemented database notification management
 */
class NotificationsController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of all user notifications
     */
    public function index()
    {
        $user = auth()->user();
        
        $notifications = $user
            ? $user->notifications()->latest()->paginate(20)
            : collect();

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $user ? $user->unreadNotifications()->count() : 0,
        ]);
    }

    /**
     * Get unread notifications count (for AJAX)
     */
    public function getUnreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * Get latest notifications (for dropdown)
     */
    public function getLatest($limit = 10)
    {
        $notifications = auth()->user()->notifications()
            ->latest()
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification && !$notification->read_at) {
            $notification->markAsRead();
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sebagai dibaca',
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sebagai dibaca',
        ]);
    }

    /**
     * Delete a specific notification
     */
    public function destroy($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi dihapus',
        ]);
    }

    /**
     * Delete all notifications
     */
    public function deleteAll()
    {
        auth()->user()->notifications()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi dihapus',
        ]);
    }

    /**
     * Show details of a specific notification
     */
    public function show($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if (!$notification) {
            return response()->json(['error' => 'Notifikasi tidak ditemukan'], 404);
        }

        // Mark as read if not already
        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $notification->id,
                'type' => $notification->type,
                'data' => $notification->data,
                'created_at' => $notification->created_at,
                'read_at' => $notification->read_at,
            ],
        ]);
    }
}
