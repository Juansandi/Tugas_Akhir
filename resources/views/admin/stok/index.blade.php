@extends('layouts.admin')

@section('title', 'Manajemen Stok')

@section('content')
<div class="container py-4">

<h3 class="mb-4">Manajemen Stok Produk</h3>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
{{-- SEARCH --}}
<form method="GET" action="{{ route('admin.stok.index') }}" class="mb-3">
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
                <a href="{{ route('admin.stok.index') }}" class="btn btn-link">
                    Reset
                </a>
            </div>
        @endif
    </div>
</form>

<form method="POST" action="{{ route('admin.stok.update') }}">
@csrf

<table class="table table-bordered align-middle">
    <thead class="table-light">
        <tr>
            <th>Produk</th>
            <th>Ukuran</th>
            <th>Stok Saat Ini</th>
            <th>Tambah Stok</th>
        </tr>
    </thead>
    <tbody>

@foreach($produks as $produk)
<tr>
    <td>{{ $produk->nama_produk }}</td>

    {{-- DROPDOWN UKURAN --}}
    <td>
        <select class="form-select size-select"
                data-produk="{{ $produk->id }}">
            @foreach($produk->sizes as $size)
                <option value="{{ $size->id }}"
                        data-stok="{{ $size->stok }}">
                    {{ $size->size }}
                </option>
            @endforeach
        </select>
    </td>

    {{-- STOK --}}
    <td>
        <span id="stok-{{ $produk->id }}">
            {{ $produk->sizes->first()->stok ?? 0 }}
        </span>
    </td>

    {{-- INPUT STOK --}}
    <td>
        @foreach($produk->sizes as $size)
            <div class="input-group mb-1 size-input"
                data-size="{{ $size->id }}"
                style="display: none;">
                <span class="input-group-text">
                    {{ $size->size }}
                </span>
                <input type="number"
                    name="stok[{{ $size->id }}]"
                    class="form-control"
                    min="0"
                    placeholder="0">
            </div>
        @endforeach
    </td>

</tr>
@endforeach

    </tbody>
</table>

<button class="btn btn-primary">
    Simpan Perubahan Stok
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
document.querySelectorAll('.size-select').forEach(select => {

    // tampilkan size pertama saat load
    const row = select.closest('tr');
    row.querySelector('.size-input').style.display = 'flex';

    select.addEventListener('change', function () {

        const row = this.closest('tr');
        const sizeId = this.value;
        const stok = this.options[this.selectedIndex].dataset.stok;

        // update stok display
        row.querySelector('[id^="stok-"]').innerText = stok;

        // sembunyikan semua input size
        row.querySelectorAll('.size-input').forEach(div => {
            div.style.display = 'none';
        });

        // tampilkan input sesuai size
        row.querySelector('.size-input[data-size="' + sizeId + '"]')
            .style.display = 'flex';
    });

});
</script>
@endsection
