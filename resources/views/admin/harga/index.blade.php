@extends('layouts.admin')

@section('title', 'Manajemen Harga')

@section('content')
<div class="container py-4">

<h3 class="mb-4">Manajemen Harga Produk</h3>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
{{-- SEARCH --}}
<form method="GET" action="{{ route('admin.harga.index') }}" class="mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text"
                   name="q"
                   class="form-control"
                   placeholder="Cari produk..."
                   value="{{ request('q') }}">
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-secondary">
                Cari
            </button>
        </div>
        @if(request('q'))
            <div class="col-auto">
                <a href="{{ route('admin.harga.index') }}" class="btn btn-link">
                    Reset
                </a>
            </div>
        @endif
    </div>
</form>

<form method="POST" action="{{ route('admin.harga.update') }}">
@csrf

<table class="table table-bordered align-middle">
<thead>
<tr>
    <th>Produk</th>
    <th>Ukuran</th>
    <th>Harga Saat Ini</th>
    <th>Harga Baru</th>
</tr>
</thead>

<tbody>
@foreach($produks as $produk)
<tr>
    <td>{{ $produk->nama_produk }}</td>

    <td>
        <select class="form-select size-select">
            @foreach($produk->sizes as $size)
                <option value="{{ $size->id }}"
                        data-harga="{{ $size->harga }}">
                    {{ $size->size }}
                </option>
            @endforeach
        </select>
    </td>

    <td>
        Rp <span class="harga-text">
            {{ number_format($produk->sizes->first()->harga ?? 0) }}
        </span>
    </td>

    <td>
        @foreach($produk->sizes as $size)
            <input type="number"
                   name="harga[{{ $size->id }}]"
                   class="form-control harga-input mb-1"
                   data-size="{{ $size->id }}"
                   style="display:none">
        @endforeach
    </td>
</tr>
@endforeach
</tbody>
</table>

<button class="btn btn-primary" id="btnSimpanHarga" disabled>
    Simpan Perubahan Harga
</button>
</form>

<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        Menampilkan {{ $produks->count() }} dari {{ $produks->total() }} produk
    </div>
    <div>
        {{ $produks->links() }}
    </div>
</div>
</div>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const btn = document.getElementById('btnSimpanHarga');

    // SET DEFAULT (saat halaman load)
    document.querySelectorAll('.size-select').forEach(select => {
        const row = select.closest('tr');

        // ambil size pertama
        const firstOption = select.options[0];
        const firstSizeId = firstOption.value;
        const firstHarga = firstOption.dataset.harga;

        // tampilkan harga
        row.querySelector('.harga-text').innerText =
            Number(firstHarga).toLocaleString('id-ID');

        // tampilkan input pertama
        row.querySelectorAll('.harga-input').forEach(i => i.style.display = 'none');
        const inputAwal = row.querySelector('.harga-input[data-size="' + firstSizeId + '"]');
        if (inputAwal) inputAwal.style.display = 'block';
    });

    // EVENT CHANGE SIZE
    document.querySelectorAll('.size-select').forEach(select => {
        select.addEventListener('change', function () {

            const row = this.closest('tr');
            const selected = this.options[this.selectedIndex];
            const sizeId = selected.value;
            const harga = selected.dataset.harga;

            // update harga saat ini
            row.querySelector('.harga-text').innerText =
                Number(harga).toLocaleString('id-ID');

            // sembunyikan semua input
            row.querySelectorAll('.harga-input').forEach(i => i.style.display = 'none');

            // tampilkan input sesuai size
            const inputAktif = row.querySelector('.harga-input[data-size="' + sizeId + '"]');
            if (inputAktif) inputAktif.style.display = 'block';
        });
    });

    // ENABLE / DISABLE BUTTON
    function cekHarga() {
        let ada = false;
        document.querySelectorAll('.harga-input').forEach(i => {
            if (i.value !== '' && parseFloat(i.value) >= 0) ada = true;
        });
        btn.disabled = !ada;
    }

    document.querySelectorAll('.harga-input').forEach(i => {
        i.addEventListener('input', cekHarga);
    });

});
</script>
@endsection
