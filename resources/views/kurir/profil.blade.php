@extends('layouts.kurir')

@section('title', 'Profil Kurir')

@section('content')

<div class="mb-4">
    <h3 class="fw-bold mb-1">Profil Kurir</h3>
    <p class="text-muted mb-0">
        Kelola informasi akun dan keamanan akun Anda.
    </p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8 col-xl-7">

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                {{-- Header Profil --}}
                <div class="text-center mb-4">

                    <i class="bi bi-person-circle text-success mb-2"
                       style="font-size:64px;"></i>

                    <h4 class="mb-1 fw-bold">
                        {{ auth()->user()->username }}
                    </h4>

                    <span class="badge bg-success">
                        Kurir
                    </span>

                </div>

                <hr>

                {{-- Informasi Profil --}}
                <div class="row gy-3 mt-2">

                    <div class="col-md-4 text-muted">
                        Nama Pengguna
                    </div>

                    <div class="col-md-8 fw-semibold">
                        {{ auth()->user()->username }}
                    </div>

                    <div class="col-md-4 text-muted">
                        Email
                    </div>

                    <div class="col-md-8 fw-semibold">
                        {{ auth()->user()->email }}
                    </div>

                    <div class="col-md-4 text-muted">
                        Nomor Telepon
                    </div>

                    <div class="col-md-8 fw-semibold">
                        {{ auth()->user()->no_telp }}
                    </div>

                </div>

                <hr>

                {{-- Tombol --}}
                <div class="d-flex justify-content-center gap-3 mt-4">

                    <button
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#editProfilKurirModal">

                        <i class="bi bi-pencil-square"></i>
                        Ubah Profil

                    </button>

                    <button
                        class="btn btn-outline-secondary"
                        data-bs-toggle="modal"
                        data-bs-target="#editPasswordKurirModal">

                        <i class="bi bi-key"></i>
                        Ubah Kata Sandi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- MODAL EDIT PROFIL --}}
<div class="modal fade" id="editProfilKurirModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('kurir.profile.update') }}">
      @csrf
      @method('PUT')

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah Profil Kurir</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Pengguna</label>
            <input type="text"
                   name="username"
                   class="form-control"
                   value="{{ auth()->user()->username }}"
                   required>
          </div>

          <div class="mb-3">
            <label class="form-label">No. Telepon</label>
            <input type="text"
                   name="no_telp"
                   class="form-control"
                   value="{{ auth()->user()->no_telp }}">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Batal
          </button>
          <button type="submit" class="btn btn-primary">
            Simpan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- MODAL EDIT PASSWORD --}}
<div class="modal fade" id="editPasswordKurirModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('kurir.password.update') }}">
      @csrf
      @method('PUT')

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ubah Kata Sandi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Kata Sandi Lama</label>
            <input type="password"
                   name="password_lama"
                   class="form-control"
                   required>
          </div>

          <div class="mb-3">
            <label class="form-label">Kata Sandi Baru</label>
            <input type="password"
                   name="password_baru"
                   class="form-control"
                   required>
          </div>

          <div class="mb-3">
            <label class="form-label">Konfirmasi Kata Sandi Baru</label>
            <input type="password"
                   name="password_baru_confirmation"
                   class="form-control"
                   required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Batal
          </button>
          <button type="submit" class="btn btn-primary">
            Simpan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

