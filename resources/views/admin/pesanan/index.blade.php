@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manajemen Pesanan</h2>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">-- Semua Status --</option>

                <option value="belum_dibayar"
                    {{ request('status') === 'belum_dibayar' ? 'selected' : '' }}>
                    Belum Dibayar
                </option>

                <option value="menunggu_konfirmasi"
                    {{ request('status') === 'menunggu_konfirmasi' ? 'selected' : '' }}>
                    Menunggu Konfirmasi
                </option>

                <option value="diproses"
                    {{ request('status') === 'diproses' ? 'selected' : '' }}>
                    Diproses
                </option>

                <option value="dikirim"
                    {{ request('status') === 'dikirim' ? 'selected' : '' }}>
                    Dikirim
                </option>

                <option value="diterima"
                    {{ request('status') === 'diterima' ? 'selected' : '' }}>
                    Diterima
                </option>

                <option value="selesai"
                    {{ request('status') === 'selesai' ? 'selected' : '' }}>
                    Selesai
                </option>

                <option value="dibatalkan"
                    {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>
                    Dibatalkan
                </option>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-outline-primary w-100">
                Filter
            </button>
        </div>

        @if(request('status'))
            <div class="col-md-2">
                <a href="{{ route('admin.pesanan.index') }}"
                class="btn btn-outline-secondary w-100">
                    Reset
                </a>
            </div>
        @endif

    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-secondary">
                <tr>
                    <th width="60">No</th>
                    <th>Nama Pembeli</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesananList as $index => $pesanan)
                <tr>

                    {{-- NO --}}
                   <td>{{ $pesananList->firstItem() + $index }}</td>

                    {{-- USER --}}
                    <td>{{ $pesanan->pengguna->username ?? 'Guest' }}</td>

                    {{-- STATUS --}}
                    <td>
                        <div class="d-flex justify-content-center align-items-center gap-1 flex-wrap">

                            {{-- STATUS UTAMA --}}
                            <span class="badge {{ $pesanan->status_badge }}">
                                {{ $pesanan->status_label }}
                            </span>

                            {{-- 📦 INDIKATOR BUKTI KIRIM --}}
                            @if(
                                $pesanan->status === 'dikirim' &&
                                $pesanan->tugasKurir &&
                                $pesanan->tugasKurir->bukti_kirim
                            )
                                <span class="badge bg-success"
                                    title="Kurir sudah upload bukti kirim">
                                    📦 Bukti Kirim
                                </span>
                            @endif

                        </div>
                    </td>
                    {{-- TOTAL --}}
                    <td class="fw-semibold text-success">
                        Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                    </td>

                    {{-- AKSI --}}
                    <td>
                        <a href="{{ route('admin.pesanan.show', $pesanan->id) }}"
                           class="btn btn-sm btn-outline-primary position-relative">

                            <i class="bi bi-eye"></i> Detail

                            @if(optional($pesanan->chatAdmin)->unread_count > 0)
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                    {{ $pesanan->chatAdmin->unread_count }}
                                </span>
                            @endif
                        </a>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-muted">
                        Belum ada pesanan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $pesananList->withQueryString()->links() }}
        </div>
    </div>

</div>
@endsection
