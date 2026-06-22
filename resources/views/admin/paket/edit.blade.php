@extends('layouts.admin')

@section('title', 'Ubah Paket')

@section('content')
<div class="container">
    <h4 class="mb-4">Ubah Paket</h4>

    <form action="{{ route('admin.paket.update', $paket->id) }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- NAMA --}}
        <div class="mb-3">
            <label class="form-label">Nama Paket</label>
            <input type="text" name="nama_paket"
                   value="{{ $paket->nama_paket }}"
                   class="form-control" required>
        </div>

        {{-- DESKRIPSI --}}
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" rows="3"
                class="form-control">{{ $paket->deskripsi }}</textarea>
        </div>

        {{-- HARGA --}}
        <div class="mb-3">
            <label class="form-label">Harga Paket</label>
            <input type="number" name="harga_paket"
                   value="{{ $paket->harga_paket }}"
                   class="form-control" required>
        </div>

        {{-- IMAGE --}}
        <div class="mb-3">
            <label class="form-label">Gambar Paket</label>
            <input type="file" name="image" class="form-control">
            @if($paket->image)
                <img src="{{ asset('storage/'.$paket->image) }}"
                     class="mt-2 rounded" style="width:120px">
            @endif
        </div>

        {{-- STATUS --}}
        <div class="mb-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="aktif" {{ $paket->status=='aktif'?'selected':'' }}>
                    Aktif
                </option>
                <option value="nonaktif" {{ $paket->status=='nonaktif'?'selected':'' }}>
                    Nonaktif
                </option>
            </select>
        </div>

        <hr>

        {{-- BUILDER --}}
        <h5>Produk Dalam Paket</h5>

        <div class="row g-2 align-items-end mb-3">
            <div class="col-md-4">
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
                <select id="sizeSelect" class="form-select">
                    <option value="">-- Pilih Ukuran --</option>
                </select>
            </div>

            <div class="col-md-2">
                <input type="number" id="qtyInput"
                       class="form-control" min="1" placeholder="Qty">
            </div>

            <div class="col-md-3">
                <button type="button" class="btn btn-secondary w-100"
                        onclick="addItem()">+ Tambah</button>
            </div>
        </div>

        {{-- TABLE --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Ukuran</th>
                    <th>Qty</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="itemTable"></tbody>
        </table>

        {{-- HIDDEN INPUT --}}
        <div id="itemsContainer"></div>

        <button class="btn btn-primary mt-3">Ubah Paket</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
/* ===============================
   DATA EXISTING (AMAN)
================================ */
const existingItems = @json($existingItems);

/* ===============================
   BUILDER
================================ */
let index = 0;
const itemTable = document.getElementById('itemTable');
const itemsContainer = document.getElementById('itemsContainer');
const produkSelect = document.getElementById('produkSelect');
const sizeSelect   = document.getElementById('sizeSelect');
const qtyInput     = document.getElementById('qtyInput');

function rowHtml(i,p,s,q){
    return `<tr id="row-${i}">
        <td>${p}</td>
        <td>${s ?? '-'}</td>
        <td>${q}</td>
        <td>
            <button type="button"
                class="btn btn-danger btn-sm"
                onclick="removeItem(${i})">
                Hapus
            </button>
        </td>
    </tr>`;
}

function inputHtml(i,pid,sid,q){
    return `<div id="item-${i}">
        <input type="hidden" name="items[${i}][produk_id]" value="${pid}">
        <input type="hidden" name="items[${i}][product_size_id]" value="${sid ?? ''}">
        <input type="hidden" name="items[${i}][quantity]" value="${q}">
    </div>`;
}

function removeItem(i){
    document.getElementById(`row-${i}`)?.remove();
    document.getElementById(`item-${i}`)?.remove();
}

/* ===============================
   LOAD EXISTING
================================ */
existingItems.forEach(item => {
    itemTable.insertAdjacentHTML(
        'beforeend',
        rowHtml(index, item.produk, item.size, item.qty)
    );

    itemsContainer.insertAdjacentHTML(
        'beforeend',
        inputHtml(index, item.produk_id, item.size_id, item.qty)
    );

    index++;
});

/* ===============================
   SELECT SIZE
================================ */
produkSelect.addEventListener('change', function () {
    const sizes = JSON.parse(
        this.options[this.selectedIndex]?.dataset.sizes || '[]'
    );

    sizeSelect.innerHTML = '<option value="">-- Pilih Ukuran --</option>';

    sizes.forEach(s => {
        if (s.stok > 0) {
            sizeSelect.innerHTML +=
                `<option value="${s.id}">${s.size} (stok ${s.stok})</option>`;
        }
    });
});

/* ===============================
   ADD ITEM
================================ */
function addItem(){
    if(!produkSelect.value || !sizeSelect.value || !qtyInput.value){
        alert('Lengkapi data');
        return;
    }

    itemTable.insertAdjacentHTML(
        'beforeend',
        rowHtml(
            index,
            produkSelect.options[produkSelect.selectedIndex].text,
            sizeSelect.options[sizeSelect.selectedIndex].text,
            qtyInput.value
        )
    );

    itemsContainer.insertAdjacentHTML(
        'beforeend',
        inputHtml(index, produkSelect.value, sizeSelect.value, qtyInput.value)
    );

    index++;
    qtyInput.value = '';
}
</script>
@endsection
