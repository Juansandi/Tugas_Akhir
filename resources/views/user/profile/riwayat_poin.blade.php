@extends('layouts.app')

@section('title', 'Riwayat Poin')

@section('content')
<div class="container py-4">

    <h2 class="fw-bold mb-4">
        <i class="bi bi-clock-history text-primary"></i>
        Riwayat Poin
    </h2>

    @forelse($riwayat as $item)

        {{-- POIN DIPEROLEH --}}
        @if($item->poin_diperoleh > 0)

        <div class="card shadow-sm border-0 mb-3">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <h5 class="text-success mb-2">
                            <i class="bi bi-plus-circle-fill"></i>
                            Poin Diperoleh
                        </h5>

                        <p class="mb-1">
                            Poin diperoleh dari transaksi
                            <strong>#{{ $item->id }}</strong>
                        </p>

                        <small class="text-muted">
                            Total Belanja
                            <strong>
                                Rp {{ number_format($item->total,0,',','.') }}
                            </strong>
                        </small>

                        <br>

                        <small class="text-muted">
                            {{ $item->created_at->format('d F Y • H:i') }}
                        </small>

                    </div>

                    <div class="text-end">

                        <h3 class="text-success">
                            +{{ $item->poin_diperoleh }}
                        </h3>

                        <small>Poin</small>

                    </div>

                </div>

            </div>

        </div>

        @endif

        {{-- POIN DIGUNAKAN --}}
        @if($item->poin_digunakan > 0)

        <div class="card shadow-sm border-0 mb-3">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <h5 class="text-danger mb-2">
                            <i class="bi bi-dash-circle-fill"></i>
                            Poin Digunakan
                        </h5>

                        <p class="mb-1">
                            Poin digunakan sebagai potongan harga
                            pada transaksi
                            <strong>#{{ $item->id }}</strong>
                        </p>

                        <small class="text-muted">
                            Potongan
                            {{ $item->poin_digunakan }} poin
                        </small>

                        <br>

                        <small class="text-muted">
                            {{ $item->created_at->format('d F Y • H:i') }}
                        </small>

                    </div>

                    <div class="text-end">

                        <h3 class="text-danger">
                            -{{ $item->poin_digunakan }}
                        </h3>

                        <small>Poin</small>

                    </div>

                </div>

            </div>

        </div>

        @endif

    @empty

        <div class="alert alert-info">
            Belum ada riwayat poin.
        </div>

    @endforelse
     <div class="mt-4 d-flex justify-content-center">
        {{ $riwayat->links() }}
    </div>

</div>
@endsection