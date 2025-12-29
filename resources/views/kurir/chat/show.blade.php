@extends('layouts.kurir')

@section('title', 'Chat Customer')

<style>
.chat-wrapper {
    height: 420px;
    overflow-y: auto;
    background: #f8f9fa;
    padding: 16px;
}
.chat-bubble {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 16px;
    font-size: 14px;
}
.chat-bubble.me {
    background: #198754;
    color: #fff;
    border-bottom-right-radius: 4px;
}
.chat-bubble.other {
    background: #e9ecef;
}
.chat-time {
    font-size: 11px;
    opacity: .7;
}
</style>

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between mb-3">
        <div>
            <h5 class="mb-0">💬 Chat Customer</h5>
            <small class="text-muted">Pesanan #{{ $pesanan->id }}</small>
        </div>

        <a href="{{ route('kurir.pesanan') }}"
           class="btn btn-outline-secondary btn-sm">
            ← Kembali
        </a>
    </div>

    {{-- CHAT --}}
    <div id="chatBox" class="chat-wrapper border rounded mb-3">
        @foreach($chat->messages as $msg)
            @php
                $isMe = $msg->sender_id === auth()->id();
            @endphp

            <div class="d-flex {{ $isMe ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                <div class="chat-bubble {{ $isMe ? 'me' : 'other' }}">
                    <div class="fw-semibold mb-1">
                        {{ $isMe ? 'Saya' : ucfirst($msg->sender_type) }}
                    </div>

                    <div>{{ $msg->message }}</div>

                    <div class="chat-time text-end mt-1">
                        {{ $msg->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($isReadOnly)
        <div class="alert alert-info">
            🔒 Chat ditutup karena pesanan telah selesai.
        </div>
    @else
        <form action="{{ route('chat.send', $chat->id) }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="text"
                    name="message"
                    class="form-control"
                    placeholder="Ketik pesan ke customer..."
                    required>
                <button class="btn btn-success">Kirim</button>
            </div>
        </form>
    @endif
</div>

<script>
    const box = document.getElementById('chatBox');
    box.scrollTop = box.scrollHeight;
</script>
@endsection
