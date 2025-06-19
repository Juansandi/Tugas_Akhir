<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{
    public function index()
    {
        $notifications = UserNotification::where('user_id', Auth::id())
                            ->latest()
                            ->paginate(10);

        return view('user.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notif = UserNotification::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $notif->is_read = true;
        $notif->save();

        return redirect($notif->url ?? '/');
    }

    public function markAllAsRead()
    {
        UserNotification::where('user_id', auth()->id())->where('is_read', false)->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }
}
