<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminNotification;

class AdminNotificationController extends Controller
{
    /**
     * Menampilkan semua notifikasi admin
     */
    public function index()
    {
        $notifications = AdminNotification::latest()->paginate(10);
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Menandai satu notifikasi sebagai dibaca dan redirect ke URL tujuannya
     */
    public function markAsRead($id)
    {
        $notification = AdminNotification::findOrFail($id);

        $notification->is_read = true;
        $notification->save();

        return redirect($notification->url ?? route('admin.notifications.index'));
    }

    /**
     * Menandai semua notifikasi sebagai dibaca
     */
    public function markAllAsRead()
    {
        AdminNotification::where('is_read', false)->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
    
}
