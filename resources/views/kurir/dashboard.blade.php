@extends('layouts.kurir')

@section('title', 'Dashboard Kurir')

@section('content')

<div class="mb-4">
    <h3 class="fw-bold mb-1">Beranda Kurir</h3>
    <p class="text-muted mb-0">
        Ringkasan aktivitas dan pesanan yang sedang Anda tangani.
    </p>
</div>

<div class="row g-4 mb-4">

    {{-- Pesanan Aktif --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <h6 class="text-muted mb-1">
                            Pesanan Aktif
                        </h6>

                        <h2 class="fw-bold mb-0">
                            {{ $pesananAktif }}
                        </h2>
                    </div>

                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                        style="width:60px;height:60px;">
                        <i class="bi bi-box-seam fs-3 text-primary"></i>
                    </div>

                </div>
        </div>
    </div>

    {{-- Pesanan Selesai --}}
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>
                        <h6 class="text-muted mb-1">
                            Pesanan Selesai
                        </h6>

                        <h2 class="fw-bold mb-0">
                            {{ $pesananSelesai }}
                        </h2>
                    </div>

                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                        style="width:60px;height:60px;">
                        <i class="bi bi-check-circle fs-3 text-success"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white">
        <h5 class="mb-0">
            <i class="bi bi-clock-history"></i>
            Pesanan Terakhir
        </h5>
    </div>

    <div class="card-body p-0">

        @if($pesananTerakhir->count())

        <ul class="list-group list-group-flush">

            @foreach($pesananTerakhir as $item)

            <li class="list-group-item d-flex justify-content-between align-items-center">

                <div>
                    <strong>Pesanan #{{ $item->pesanan->id }}</strong>

                    <br>

                    <small class="text-muted">
                        {{ $item->pesanan->pengguna->username ?? '-' }}
                    </small>
                </div>

                <span class="badge
                    @switch($item->status)
                        @case('ditugaskan')
                            bg-secondary
                            @break

                        @case('diambil')
                            bg-primary
                            @break

                        @case('dikirim')
                            bg-info text-dark
                            @break

                        @case('selesai')
                            bg-success
                            @break

                        @default
                            bg-secondary
                    @endswitch">

                    {{ ucwords(str_replace('_',' ',$item->status)) }}

                </span>

            </li>

            @endforeach

        </ul>

        @else

        <div class="text-center text-muted py-5">

            <i class="bi bi-inbox fs-1 d-block mb-2"></i>

            Belum ada riwayat pesanan.

        </div>

        @endif

    </div>

</div>

@endsection