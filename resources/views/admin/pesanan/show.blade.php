@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Detail Pesanan #{{ $pesanan->id }}</h4>
        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    {{-- INFO PEMBELI --}}
    <p>
        <strong>Nama Pembeli:</strong>
        {{ $pesanan->pengguna->username ?? 'Guest' }}
    </p>

    {{-- ALAMAT --}}
    <div class="card mb-3">
        <div class="card-body">
            <h6 class="fw-semibold mb-2">Alamat Pengiriman</h6>
            <p class="mb-1">{{ $pesanan->alamat_pengiriman }}</p>

            @if($pesanan->no_telp_pengiriman)
                <small class="text-muted">
                    No. Telp: {{ $pesanan->no_telp_pengiriman }}
                </small>
            @endif
        </div>
    </div>

    {{-- STATUS --}}
    <p class="mb-2">
        <strong>Status:</strong>
        <span class="badge {{ $pesanan->status_badge }}">
            {{ $pesanan->status_label }}
        </span>
    </p>
    {{-- CHAT ADMIN KE CUSTOMER --}}
    @if(in_array($pesanan->status, ['diproses','dikirim','diterima','selesai']) &&
        $pesanan->chatAdmin)
        <div class="mb-3">
        <a href="{{ route('admin.chat.show', $pesanan->chatAdmin->id) }}"
        class="btn btn-outline-primary btn-sm position-relative">

            💬 Chat Customer

            @if($pesanan->chatAdmin->unread_count > 0)
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                    {{ $pesanan->chatAdmin->unread_count }}
                </span>
            @endif
        </a>
        @if($pesanan->status === 'selesai')
            @if($pesanan->chatMasihAktif())
                <div class="text-muted small mt-1">
                    💬 Chat masih aktif hingga 24 jam setelah pesanan selesai.
                </div>
            @else
                <div class="text-muted small mt-1">
                    🔒 Chat masih dapat dibuka tetapi sudah read-only.
                </div>
            @endif
        @endif
        </div>
    @endif

    {{-- ========================= --}}
    {{-- WAKTU PENGANTARAN --}}
    {{-- ========================= --}}
    <tr>
        <td class="fw-semibold">Waktu Pengantaran</td>
        <td>
            @if ($pesanan->deliverySlot)
                {{ substr($pesanan->deliverySlot->waktu_mulai,0,5) }}
                –
                {{ substr($pesanan->deliverySlot->waktu_selesai,0,5) }}
            @else
                <span class="text-muted">Secepatnya</span>
            @endif
        </td>
    </tr>
 
    @if($pesanan->refund && $pesanan->refund->status === 'disetujui')
        <div class="alert alert-warning mt-3">
            🔄 <strong>Pesanan ini telah diapprove untuk pengembalian dana</strong><br>
            Nominal pengembalian dana:
            <strong class="text-danger">
                Rp {{ number_format($pesanan->refund->refund_amount,0,',','.') }}
            </strong><br>
            Disetujui pada:
            {{ optional($pesanan->refund->approved_at)->format('d M Y H:i') }}
            <div>
             <a href="{{ route('admin.refund.show', $pesanan->refund->id) }}"
                class="btn btn-sm btn-outline-danger mt-2">
                    Lihat Detail Pengembalian Dana
            </a>
            </div>
        </div>
    @endif

    {{-- BATAS BAYAR --}}
    @if($pesanan->status === 'belum_dibayar')
        <div class="alert alert-danger">
            ⚠ Batas unggah bukti bayar:
            <strong>{{ $pesanan->created_at->addHours(24)->format('d M Y H:i') }}</strong>
        </div>
    @endif

    {{-- BUKTI BAYAR --}}
    @if($pesanan->status === 'menunggu_konfirmasi' && $pesanan->bukti_bayar)
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Bukti Pembayaran</h6>

            <img src="{{ asset('storage/'.$pesanan->bukti_bayar) }}"
                 class="img-thumbnail"
                 style="width:220px;height:220px;object-fit:cover;cursor:pointer"
                 data-bs-toggle="modal"
                 data-bs-target="#modalBuktiBayar">

            <p class="text-muted mt-2">Klik gambar untuk memperbesar</p>

            <form method="POST"
                  action="{{ route('admin.pesanan.verifikasi', $pesanan->id) }}"
                  class="d-flex gap-2 mt-3">
                @csrf
                <button name="aksi" value="terima" class="btn btn-success">
                    Verifikasi
                </button>
                <button name="aksi" value="tolak" class="btn btn-danger">
                    Tolak
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- BUKTI KIRIM KURIR --}}
    @if($pesanan->tugasKurir && $pesanan->tugasKurir->bukti_kirim)
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Bukti Pengiriman Kurir</h6>

            <img src="{{ asset('storage/'.$pesanan->tugasKurir->bukti_kirim) }}"
                 class="img-thumbnail"
                 style="width:220px;height:220px;object-fit:cover;cursor:pointer"
                 data-bs-toggle="modal"
                 data-bs-target="#modalBuktiKirim">

            <p class="text-muted mt-2">Klik gambar untuk memperbesar</p>

            <p class="mb-1">
                <strong>Waktu Kirim: </strong>
                {{ optional($pesanan->tugasKurir->waktu_kirim)->format('d M Y H:i') }}
            </p>

            @if($pesanan->tugasKurir->catatan_kurir)
                <p class="mb-0">
                    <strong>Catatan Kurir: </strong>
                    {{ $pesanan->tugasKurir->catatan_kurir }}
                </p>
            @endif
        </div>
    </div>
    @endif

    {{-- RINCIAN HARGA --}}
    <h5 class="mt-4">Perincian Harga</h5>

    @php
        $subtotal = $pesanan->detail->sum(fn($i) => $i->price * $i->quantity);

        $refundAmount = ($pesanan->refund && $pesanan->refund->status === 'disetujui')
            ? $pesanan->refund->refund_amount
            : 0;

        $totalBersih = $pesanan->total - $refundAmount;
    @endphp

    <ul class="list-unstyled ps-3">
        <li>Subtotal: Rp {{ number_format($subtotal,0,',','.') }}</li>
        <li>Diskon Promo: -Rp {{ number_format($pesanan->diskon_dari_promo ?? 0,0,',','.') }}</li>
        <li>Diskon Poin: -Rp {{ number_format($pesanan->diskon_dari_poin ?? 0,0,',','.') }}</li>

        <li>
            <strong>Total Dibayar:</strong>
            Rp {{ number_format($pesanan->total,0,',','.') }}
        </li>

        @if($refundAmount > 0)
            <li class="text-danger">
                Pengembalian Dana: -Rp {{ number_format($refundAmount,0,',','.') }}
            </li>
            <li>
                <strong>Total Bersih:</strong>
                <span class="fw-bold text-success">
                    Rp {{ number_format($totalBersih,0,',','.') }}
                </span>
            </li>
        @endif
    </ul>

    {{-- AKSI ADMIN --}}
    @if($pesanan->status === 'diproses')
        <form method="POST"
              action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}"
              class="mt-3">
            @csrf
            <input type="hidden" name="status" value="dikirim">

            <label class="fw-semibold">Pilih Kurir</label>
            <select name="kurir_id" class="form-select mb-2" required>
                <option value="">-- Pilih Kurir --</option>
                @foreach($kurirs as $kurir)
                    <option value="{{ $kurir->id }}">{{ $kurir->username }}</option>
                @endforeach
            </select>

            <button class="btn btn-success">Kirim Pesanan</button>
        </form>
    @endif

   @if($pesanan->status === 'dikirim')
        @if(
            $pesanan->tugasKurir &&
            $pesanan->tugasKurir->status === 'selesai' &&
            $pesanan->tugasKurir->bukti_kirim
        )
            <form method="POST"
                action="{{ route('admin.pesanan.updateStatus', $pesanan->id) }}">
                @csrf
                <input type="hidden" name="status" value="selesai">
                <button class="btn btn-success">
                    Selesaikan Pesanan
                </button>
            </form>
        @else
            <div class="alert alert-secondary mt-3">
                ⏳ Menunggu kurir menyelesaikan pengiriman dan mengunggah bukti kirim.
            </div>
        @endif

    @endif

    <hr class="my-4">

    {{-- DETAIL PRODUK --}}
    <h5>Detail Produk</h5>
    <div class="list-group">
        @foreach($pesanan->detail as $item)
            <div class="list-group-item py-3">
                <div class="d-flex justify-content-between align-items-center">

                    <div class="d-flex align-items-center">
                        @php
                            $image = $item->type === 'paket'
                                ? optional($item->paket)->image
                                : optional($item->produk)->image;
                        @endphp

                        @if($image)
                            <img src="{{ asset('storage/'.$image) }}"
                                 style="width:64px;height:64px;object-fit:cover;border-radius:8px;margin-right:16px">
                        @endif

                        <div>
                            <div class="fw-bold">
                                {{ $item->type === 'paket'
                                    ? $item->paket->nama_paket
                                    : $item->produk->nama_produk }}
                            </div>
                            <small class="text-muted">
                                Jumlah: {{ $item->quantity }}
                                @if($item->type === 'produk')
                                    | Ukuran: {{ $item->size->size ?? '-' }}
                                @endif
                            </small>
                        </div>
                    </div>

                    <div class="fw-semibold">
                        Rp {{ number_format($item->price * $item->quantity,0,',','.') }}
                    </div>

                </div>
            </div>
        @endforeach
    </div>

</div>

{{-- MODAL BUKTI BAYAR --}}
@if($pesanan->bukti_bayar)
<div class="modal fade" id="modalBuktiBayar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('storage/'.$pesanan->bukti_bayar) }}"
                     class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endif

{{-- MODAL BUKTI KIRIM --}}
@if($pesanan->tugasKurir && $pesanan->tugasKurir->bukti_kirim)
<div class="modal fade" id="modalBuktiKirim" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pengiriman Kurir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('storage/'.$pesanan->tugasKurir->bukti_kirim) }}"
                     class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endif

@endsection
