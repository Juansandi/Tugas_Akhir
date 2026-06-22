@extends('layouts.app')

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
    word-wrap: break-word;
}

.chat-bubble.me {
    background: #0d6efd;
    color: #fff;
    border-bottom-right-radius: 4px;
}

.chat-bubble.other {
    background: #e9ecef;
    color: #000;
    border-bottom-left-radius: 4px;
}

.chat-time {
    font-size: 11px;
    opacity: .7;
}
</style>
@php
$isReadOnly = !$pesanan->chatMasihAktif();
@endphp
<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0">
                💬 Pesan {{ $type === 'admin' ? 'Admin' : 'Kurir' }}
            </h5>
            <small class="text-muted">
                Pesanan #{{ $pesanan->id }}
            </small>
        </div>

        {{-- TOMBOL BACK --}}
        <a href="{{ route('pesanan.show', $pesanan->id) }}"
           class="btn btn-outline-secondary btn-sm">
            ← Kembali
        </a>
    </div>

    {{-- CHAT BOX --}}
    <div id="chatBox" class="chat-wrapper border rounded mb-3">

        @forelse($chat->messages as $msg)
            @php
                $isMe = $msg->sender_id === auth()->id();
            @endphp

            <div class="d-flex {{ $isMe ? 'justify-content-end' : 'justify-content-start' }} mb-2">

                <div class="chat-bubble {{ $isMe ? 'me' : 'other' }}">
                    <div class="fw-semibold mb-1">
                        {{ $isMe ? 'Saya' : ucfirst($msg->sender_role) }}
                    </div>

                    <div>{{ $msg->message }}</div>

                    <div class="chat-time text-end mt-1">
                        {{ $msg->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">
                Belum ada pesan.
            </p>
        @endforelse

    </div>

    

    {{-- FORM INPUT --}}
    @if($pesanan->status === 'selesai' && !$isReadOnly)
    <div class="alert alert-warning py-2">
        💬 Chat masih tersedia hingga 24 jam setelah pesanan selesai.
    </div>
    @endif
   @if(!$isReadOnly)
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

{{-- AUTO SCROLL --}}
<script>
    const chatBox = document.getElementById('chatBox');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>

@endsection
