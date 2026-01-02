<div class="modal fade" id="editAlamatModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content" id="editAlamatForm">
      @csrf
      @method('PUT')

      <div class="modal-header">
        <h5 class="modal-title">Edit Alamat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Label Alamat</label>
          <input type="text" name="label" id="editLabel" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Alamat Lengkap</label>
          <textarea name="alamat" id="editAlamat" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">No. Telepon Penerima</label>
          <input type="text" name="no_telp" id="editTelp" class="form-control" required>
        </div>

        <div class="form-check">
          <input type="checkbox"
                 name="is_default"
                 value="1"
                 id="editDefault"
                 class="form-check-input">
          <label class="form-check-label">
            Jadikan alamat utama
          </label>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-success">Update</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('editAlamatModal');

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const id = button.dataset.id;
        const label = button.dataset.label;
        const alamat = button.dataset.alamat;
        const telp = button.dataset.telp;
        const isDefault = button.dataset.default == 1;

        const form = document.getElementById('editAlamatForm');
        form.action = `/alamat/${id}`;

        document.getElementById('editLabel').value = label;
        document.getElementById('editAlamat').value = alamat;
        document.getElementById('editTelp').value = telp;
        document.getElementById('editDefault').checked = isDefault;
    });
});
</script>
