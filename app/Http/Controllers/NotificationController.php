<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        $unreadCount = $this->notificationService->getUnreadCount($user->id);

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        $this->notificationService->markAsRead($id, $user->id);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        $this->notificationService->markAllAsRead($user->id);

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = $this->notificationService->getUnreadCount($user->id);

        return response()->json(['count' => $count]);
    }

    public function getRecent()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return response()->json($notifications);
    }
}
