<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\DetailChat;
use App\Models\Pesanan;
use App\Models\UserNotification;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function show(Request $request, Pesanan $pesanan)
    {
        $type = $request->query('type'); // admin / kurir

        if (!in_array($type, ['admin', 'kurir'])) {
            abort(404);
        }

        $chat = Chat::firstOrCreate([
            'pesanan_id' => $pesanan->id,
            'type' => $type
        ]);

        $chat->messages()
            ->whereIn('sender_type', ['admin', 'kurir'])
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $isReadOnly = $pesanan->status === 'selesai';

        return view('chat.show', compact('chat', 'pesanan', 'type', 'isReadOnly'));
    }


    public function send(Request $request, Chat $chat)
    {
        // ⛔ BLOK CHAT JIKA PESANAN SELESAI
        if ($chat->pesanan->status === 'selesai') {
            abort(403, 'Chat sudah ditutup.');
        }

        $request->validate([
            'message' => 'required|string'
        ]);

        $message = DetailChat::create([
            'chat_id' => $chat->id,
            'sender_id' => auth()->id(),
            'sender_type' => auth()->user()->role, // user | admin | kurir
            'message' => $request->message,
            'is_read' => false,
        ]);

        // ==============================
        // 🔔 NOTIFIKASI KE CUSTOMER
        // ==============================
        if (in_array(auth()->user()->role, ['admin', 'kurir'])) {

            $pesanan = $chat->pesanan;

            UserNotification::create([
                'user_id' => $pesanan->user_id, // customer
                'tipe'    => auth()->user()->role === 'admin'
                    ? 'chat_admin'
                    : 'chat_kurir',
                'pesan'   => auth()->user()->role === 'admin'
                    ? 'Admin mengirim pesan pada pesanan #' . $pesanan->id
                    : 'Kurir mengirim pesan pada pesanan #' . $pesanan->id,
                'url'     => route('chat.show', [
                    'pesanan' => $pesanan->id,
                    'type' => $chat->type
                ]),
            ]);
        }

        // ==============================
        // 🔔 NOTIFIKASI KE ADMIN
        // ==============================
        if (auth()->user()->role === 'user') {

            AdminNotification::create([
                'tipe'  => 'chat_user',
                'pesan' => 'Pesan baru dari ' . auth()->user()->username .
                        ' pada pesanan #' . $chat->pesanan_id,
                'url'   => route('admin.chat.show', $chat->id),
            ]);
        }

        return back();
    }

    public function adminShow(Chat $chat)
    {
        $chat->messages()
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $isReadOnly = $chat->pesanan->status === 'selesai';

        return view('admin.chat.show', [
            'chat' => $chat,
            'pesanan' => $chat->pesanan,
            'type' => $chat->type,
            'isReadOnly' => $isReadOnly
        ]);
    }

    public function kurirShow(Chat $chat)
    {
        $chat->messages()
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $chat->load('messages');

        $isReadOnly = $chat->pesanan->status === 'selesai';

        return view('kurir.chat.show', [
            'chat' => $chat,
            'pesanan' => $chat->pesanan,
            'type' => $chat->type,
            'isReadOnly' => $isReadOnly
        ]);
    }
}
