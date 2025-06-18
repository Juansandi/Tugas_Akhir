@extends('layouts.admin')

@section('title', 'Daftar Refund')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Daftar Pengajuan Refund</h4>

    @if($refunds->isEmpty())
        <p>Belum ada pengajuan refund.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Pesanan</th>
                    <th>Pengguna</th>
                    <th>Alasan</th>
                    <th>Status</th>
                    <th>Diajukan Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($refunds as $refund)
                <tr>
                    <td>{{ $refund->id }}</td>
                    <td>#{{ $refund->pesanan->id }}</td>
                    <td>{{ $refund->pengguna->username }}</td>
                    <td>{{ Str::limit($refund->alasan, 30) }}</td>
                    <td>
                        @php
                            $badgeClass = match($refund->status) {
                                'diajukan'  => 'bg-warning text-dark',
                                'disetujui' => 'bg-success text-white',
                                'ditolak'   => 'bg-danger text-white',
                                default     => 'bg-secondary text-white'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            {{ ucfirst($refund->status) }}
                        </span>
                    </td>
                    <td>{{ $refund->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.refund.show', $refund->id) }}" class="btn btn-sm btn-primary">Lihat</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
