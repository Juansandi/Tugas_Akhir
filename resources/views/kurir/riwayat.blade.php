@extends('layouts.kurir')

@section('title', 'Riwayat Pengiriman')

@section('content')

<div class="mb-4">
    <h3 class="fw-bold mb-1">Riwayat Pengiriman</h3>
    <p class="text-muted mb-0">
        Daftar pengiriman yang telah berhasil Anda selesaikan.
    </p>
</div>

<div class="table-responsive">

    <table class="table table-bordered align-middle">

        <thead class="table-light">
            <tr>
                <th width="70" class="text-center">No</th>
                <th class="text-center">ID Pesanan</th>
                <th class="text-center">Status</th>
                <th class="text-center">Tanggal Selesai</th>
                <th width="220" class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>

        @forelse($tugas as $item)

            <tr>

                <td class="text-center">
                    {{ $tugas->firstItem() + $loop->index }}
                </td>

                <td class="text-center">
                    Pesanan #{{ $item->pesanan->id }}
                </td>

                <td class="text-center">
                    <span class="badge bg-success">
                        Selesai
                    </span>
                </td>

                <td class="text-center">
                    {{ $item->updated_at->format('d M Y H:i') }}
                </td>

                <td>

                    <div class="d-flex justify-content-center gap-2 flex-wrap">

                        <a href="{{ route('kurir.pesanan.detail', $item->id) }}"
                           class="btn btn-sm btn-outline-primary">

                            <i class="bi bi-box-seam"></i>
                            Detail

                        </a>

                        @if($item->pesanan->chatKurir)

                            <a href="{{ route('kurir.chat.show', $item->pesanan->chatKurir->id) }}"
                               class="btn btn-sm btn-outline-success">

                                <i class="bi bi-chat-dots"></i>
                                Pesan

                            </a>

                        @endif

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="5" class="text-center text-muted py-4">

                    <i class="bi bi-clock-history fs-2 d-block mb-2"></i>

                    Belum ada riwayat pengiriman.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

<div class="d-flex justify-content-center mt-3">
    {{ $tugas->links() }}
</div>

@endsection