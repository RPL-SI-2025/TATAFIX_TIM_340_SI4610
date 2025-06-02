<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('pages.notifications.index', compact('notifications'));
    }
    
    /**
     * Get unread notifications count for the authenticated user.
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->notifications()->unread()->count();
        return response()->json(['count' => $count]);
    }
    
    /**
     * Get all notifications for the authenticated user (for AJAX).
     */
    public function getNotifications()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Auth::user()->notifications()->unread()->count()
        ]);
    }
    
    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        // Check if the notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()->unread()->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification)
    {
        // Check if the notification belongs to the authenticated user
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $notification->delete();
        
        return response()->json(['success' => true]);
    }
}
