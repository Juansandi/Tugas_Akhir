<!-- Modal Edit Profil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('profile.update') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProfileModalLabel">Ubah Profil</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <div class="mb-3">
              <label for="username" class="form-label">Nama Pengguna</label>
              <input type="text" name="username" id="username" class="form-control" value="{{ $user->username }}" required>
          </div>

          <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
          </div>

          <div class="mb-3">
              <label for="no_telp" class="form-label">No. Telepon</label>
              <input type="text" name="no_telp" id="no_telp" class="form-control" value="{{ $user->no_telp }}">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </div>
    </form>
  </div>
</div>
