@extends('layouts.kurir')

@section('title', 'Dashboard Kurir')

@section('content')
<h4>Dashboard</h4>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Pesanan Aktif</h6>
                <h2 class="fw-bold">{{ $pesananAktif }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Pesanan Selesai</h6>
                <h2 class="fw-bold">{{ $pesananSelesai }}</h2>
            </div>
        </div>
    </div>
</div>

<h5>Pesanan Terakhir</h5>
<ul class="list-group">
@foreach($pesananTerakhir as $item)
    <li class="list-group-item">
        Pesanan #{{ $item->pesanan->id }} -
        {{ ucfirst($item->status) }}
    </li>
@endforeach
</ul>

@endsection
