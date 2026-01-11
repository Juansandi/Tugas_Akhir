@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:500px">

    <h4 class="mb-3">Upload Bukti Pembayaran</h4>

    <div class="alert alert-warning">
        Silakan upload bukti pembayaran sebelum batas waktu.
    </div>

    <p class="mb-1">Total yang harus dibayar:</p>
    <h5 class="text-success mb-4">
        Rp {{ number_format($pesanan->total,0,',','.') }}
    </h5>

    <form method="POST"
      action="{{ route('pesanan.uploadBukti', $pesanan->id) }}"
      enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">Bukti Transfer</label>
        <input type="file" name="bukti_bayar" class="form-control" required>
    </div>

    <button class="btn btn-primary w-100">
        Kirim Bukti Pembayaran
    </button>
</form>

</div>
@endsection
