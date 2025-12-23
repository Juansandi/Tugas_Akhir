@extends('layouts.kurir')

@section('title', 'Dashboard Kurir')

@section('content')
<h4>Dashboard</h4>

<div class="row g-3 mt-2">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6>Total Pesanan</h6>
                <h3>12</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6>Pesanan Aktif</h6>
                <h3>5</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6>Pesanan Selesai</h6>
                <h3>7</h3>
            </div>
        </div>
    </div>
</div>
@endsection
