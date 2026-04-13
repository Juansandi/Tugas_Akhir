@extends('layouts.kurir')

@section('title', 'Profil Kurir')

@section('content')
<h4>Profil Kurir</h4>

<div class="card">
    <div class="card-body">
        <p><strong>Username:</strong> {{ auth()->user()->username }}</p>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
        <p><strong>No. Telepon:</strong> {{ auth()->user()->no_telp }}</p>

        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#editProfilKurirModal">
                ✏️ Ubah Profil
            </button>

            <button class="btn btn-sm btn-secondary"
                    data-bs-toggle="modal"
                    data-bs-target="#editPasswordKurirModal">
                🔐 Ubah Password
            </button>
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
            <label class="form-label">Username</label>
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
          <h5 class="modal-title">Ubah Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Password Lama</label>
            <input type="password"
                   name="password_lama"
                   class="form-control"
                   required>
          </div>

          <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="password"
                   name="password_baru"
                   class="form-control"
                   required>
          </div>

          <div class="mb-3">
            <label class="form-label">Konfirmasi Password Baru</label>
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

