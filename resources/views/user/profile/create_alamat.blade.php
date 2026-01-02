<div class="modal fade" id="createAlamatModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('alamat.store') }}" method="POST" class="modal-content">
      @csrf

      <div class="modal-header">
        <h5 class="modal-title">Tambah Alamat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Label Alamat</label>
          <input type="text" name="label" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Alamat Lengkap</label>
          <textarea name="alamat" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">No. Telepon Penerima</label>
          <input type="text" name="no_telp" class="form-control" required>
        </div>

        <div class="form-check">
          <input type="checkbox"
                 name="is_default"
                 value="1"
                 class="form-check-input"
                 id="create_is_default">
          <label for="create_is_default" class="form-check-label">
            Jadikan alamat utama
          </label>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-success">Simpan</button>
      </div>
    </form>
  </div>
</div>
