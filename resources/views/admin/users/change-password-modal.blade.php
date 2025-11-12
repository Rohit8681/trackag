<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="changePasswordLabel">Change Password</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="changePasswordForm">
        @csrf
        <div class="modal-body">
          <!-- Current Password -->
          <div class="mb-3 position-relative">
            <label class="form-label">Current Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="password" name="current_password" class="form-control" id="current_password">
              <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password">
                <i class="bi bi-eye"></i>
              </button>
            </div>
            <span id="current_password_error" class="text-danger small"></span>
          </div>

          <!-- New Password -->
          <div class="mb-3 position-relative">
            <label class="form-label">New Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="password" name="new_password" class="form-control" id="new_password">
              <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password">
                <i class="bi bi-eye"></i>
              </button>
            </div>
            <span id="new_password_error" class="text-danger small"></span>
          </div>

          <!-- Confirm Password -->
          <div class="mb-3 position-relative">
            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="password" name="new_password_confirmation" class="form-control" id="new_password_confirmation">
              <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password_confirmation">
                <i class="bi bi-eye"></i>
              </button>
            </div>
            <span id="new_password_confirmation_error" class="text-danger small"></span>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Password</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('changePasswordForm');

    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const target = document.getElementById(this.dataset.target);
            const icon = this.querySelector('i');
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                target.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // 🔹 Clear all old errors
        document.querySelectorAll('span[id$="_error"]').forEach(span => span.textContent = '');

        fetch("{{ route('change-password') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: new FormData(form)
        })
        .then(async response => {
            const data = await response.json();

            if (!response.ok) {
                // 🔹 Display field-specific validation errors
                if (data.errors) {
                    for (const key in data.errors) {
                        const errorSpan = document.getElementById(`${key}_error`);
                        if (errorSpan) {
                            errorSpan.textContent = data.errors[key][0];
                        }
                    }
                }
                return;
            }

            // 🔹 On success
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
            alert(data.message);
        })
        .catch(() => {
            alert('Something went wrong. Please try again.');
        });
    });
});
</script>
