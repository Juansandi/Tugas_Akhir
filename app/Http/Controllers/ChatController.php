<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\DetailChat;
use App\Models\Pesanan;
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

        DetailChat::create([
            'chat_id' => $chat->id,
            'sender_id' => auth()->id(),
            'sender_type' => auth()->user()->role,
            'message' => $request->message,
            'is_read' => false,
        ]);

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
