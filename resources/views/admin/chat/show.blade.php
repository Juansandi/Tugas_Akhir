@extends('layouts.admin')

@section('title', 'Pesan Pesanan')

@section('content')

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
    background: #0d6efd;
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

<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between mb-3">
        <div>
            <h5 class="mb-0">💬 Pesan Pembeli</h5>
            <small class="text-muted">Pesanan #{{ $pesanan->id }}</small>
            @if($isReadOnly)
                <span class="badge bg-secondary ms-2">Read Only</span>
            @endif
        </div>

        <a href="{{ route('admin.pesanan.show', $pesanan->id) }}"
           class="btn btn-outlixne-secondary btn-sm">
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
                        {{ $isMe ? 'Admin' : 'Customer' }}
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
         <div class="alert alert-secondary mt-3">
            🔒 Chat ditutup karena sudah melewati 24 jam setelah pesanan selesai.
        </div>
    @else
        @if(!$isReadOnly && $pesanan->status === 'selesai')
        <div class="alert alert-warning py-2">
            💬 Chat masih aktif hingga 24 jam setelah pesanan selesai.
        </div>
        @endif

        
        <form action="{{ route('chat.send', $chat->id) }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="text"
                    name="message"
                    class="form-control"
                    placeholder="Ketik balasan..."
                    required>
                <button class="btn btn-primary">Kirim</button>
            </div>
        </form>
    @endif
</div>

<script>
    document.getElementById('chatBox').scrollTop =
        document.getElementById('chatBox').scrollHeight;
</script>

@endsection
