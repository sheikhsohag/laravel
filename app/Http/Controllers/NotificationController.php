<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Notifications\Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        
    }

    public function readNotification($user_id){
        $user = User::find($user_id);

        if(!$user){
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'notifications' => $user->notifications,
            'unread_notifications' => $user->unreadNotifications
        ]);
    }

    public function markAsRead($user_id, $notification_id){
        $user = User::find($user_id);
        $notification = $user->Notifications->where('id', $notification_id)->first();
        if (!$notification) {
        return response()->json(['message' => 'Notification not found'], 404);
        }

        if ($notification->read_at) {
            return response()->json(['message' => 'Notification already marked as read']);
        }

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read successfully',
            'notification_id' => $notification_id
        ]);
    }
}