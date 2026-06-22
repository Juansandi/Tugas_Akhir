@extends('layouts.admin')

@section('title', 'Riwayat Perubahan Harga')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Riwayat Perubahan Harga</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Ukuran</th>
                        <th>Harga Lama</th>
                        <th>Harga Baru</th>
                        <th>Diubah Oleh</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->produk->nama_produk }}</td>
                            <td>{{ $item->size->size ?? '-' }}</td>
                            <td>Rp {{ number_format($item->harga_lama, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->harga_baru, 0, ',', '.') }}</td>
                            <td>{{ $item->pengguna->username }}</td>
                            <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada riwayat perubahan harga
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $histories->links() }}
        </div>
    </div>

</div>
@endsection
