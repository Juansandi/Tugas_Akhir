@extends('layouts.admin')

@section('title', 'Tambah Paket')

@section('content')
<div class="container">
    <h4 class="mb-4">Tambah Paket Produk</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.paket.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- NAMA PAKET --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Paket</label>
            <input type="text" name="nama_paket" class="form-control" required>
        </div>

        {{-- DESKRIPSI --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Deskripsi Paket</label>
            <textarea name="deskripsi" rows="3" class="form-control"
                placeholder="Contoh: Paket kebutuhan pokok untuk 1 bulan"></textarea>
        </div>

        {{-- HARGA --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Harga Paket</label>
            <input type="number" name="harga_paket" class="form-control" min="0" required>
        </div>

        {{-- IMAGE --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Gambar Paket</label>
            <input type="file" name="image" class="form-control">
            <small class="text-muted">Opsional. JPG / PNG.</small>
        </div>

        {{-- STATUS --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Status Paket</label>
            <select name="status" class="form-select">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>

        <hr class="my-4">

        {{-- BUILDER PRODUK --}}
        <h5 class="mb-3">Produk Dalam Paket</h5>

        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Produk</label>
                <select id="produkSelect" class="form-select">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($produks as $produk)
                        <option value="{{ $produk->id }}"
                            data-sizes='@json($produk->sizes)'>
                            {{ $produk->nama_produk }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Ukuran</label>
                <select id="sizeSelect" class="form-select">
                    <option value="">-- Pilih Ukuran --</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Qty</label>
                <input type="number" id="qtyInput" class="form-control" min="1">
            </div>

            <div class="col-md-3">
                <button type="button" class="btn btn-secondary w-100" onclick="addItem()">
                    + Tambah ke Paket
                </button>
            </div>
        </div>

        {{-- PREVIEW --}}
        <div class="mt-4">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Produk</th>
                        <th>Ukuran</th>
                        <th>Qty</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="itemTable">
                    <tr id="emptyRow">
                        <td colspan="4" class="text-center text-muted">
                            Belum ada produk dalam paket
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- CONTAINER HIDDEN INPUT --}}
        <div id="itemsContainer"></div>

        <button type="submit" class="btn btn-success mt-3">
            Simpan Paket
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
let index = 0;

document.getElementById('produkSelect').addEventListener('change', function () {
    const sizes = JSON.parse(this.options[this.selectedIndex].dataset.sizes || '[]');
    const sizeSelect = document.getElementById('sizeSelect');
    sizeSelect.innerHTML = '<option value="">-- Pilih Ukuran --</option>';

    sizes.forEach(size => {
        if (size.stok > 0) {
            sizeSelect.innerHTML += `
                <option value="${size.id}">
                    ${size.size} (stok ${size.stok})
                </option>
            `;
        }
    });
});

function addItem() {
    const produkSelect = document.getElementById('produkSelect');
    const sizeSelect = document.getElementById('sizeSelect');
    const qtyInput = document.getElementById('qtyInput');

    if (!produkSelect.value || !sizeSelect.value || !qtyInput.value) {
        alert('Lengkapi produk, ukuran, dan qty');
        return;
    }

    const produkText = produkSelect.options[produkSelect.selectedIndex].text;
    const sizeText = sizeSelect.options[sizeSelect.selectedIndex].text;
    const qty = qtyInput.value;

    document.getElementById('emptyRow')?.remove();

    // tabel preview
    document.getElementById('itemTable').innerHTML += `
        <tr id="row-${index}">
            <td>${produkText}</td>
            <td>${sizeText}</td>
            <td>${qty}</td>
            <td>
                <button type="button" class="btn btn-sm btn-danger"
                    onclick="removeItem(${index})">Hapus</button>
            </td>
        </tr>
    `;

    // hidden input
    document.getElementById('itemsContainer').innerHTML += `
        <div id="item-${index}">
            <input type="hidden" name="items[${index}][produk_id]" value="${produkSelect.value}">
            <input type="hidden" name="items[${index}][product_size_id]" value="${sizeSelect.value}">
            <input type="hidden" name="items[${index}][quantity]" value="${qty}">
        </div>
    `;

    index++;
    qtyInput.value = '';
}

function removeItem(i) {
    document.getElementById(`row-${i}`).remove();
    document.getElementById(`item-${i}`).remove();

    if (document.querySelectorAll('#itemTable tr').length === 0) {
        document.getElementById('itemTable').innerHTML = `
            <tr id="emptyRow">
                <td colspan="4" class="text-center text-muted">
                    Belum ada produk dalam paket
                </td>
            </tr>
        `;
    }
}
</script>
@endsection
