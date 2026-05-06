<!-- Error Modal Component -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="errorModalLabel">
                    <i class="fa fa-exclamation-circle me-2"></i> Validation Errors
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="errorList" class="list-unstyled mb-0">
                    <!-- Errors will be populated here -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="fa fa-times me-2"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal Component -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">
                    <i class="fa fa-check-circle me-2"></i> Success
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="successMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                    <i class="fa fa-check me-2"></i> OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Warning Modal Component -->
<div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-warning">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="warningModalLabel">
                    <i class="fa fa-exclamation-triangle me-2"></i> Warning
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="warningMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">
                    <i class="fa fa-check me-2"></i> OK
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Global error handling for validation errors
function showErrorModal(errors) {
    const errorList = document.getElementById('errorList');
    errorList.innerHTML = '';
    
    if (Array.isArray(errors)) {
        errors.forEach(error => {
            const li = document.createElement('li');
            li.className = 'mb-2';
            li.innerHTML = `<i class="fa fa-times-circle text-danger me-2"></i><strong>${error}</strong>`;
            errorList.appendChild(li);
        });
    } else if (typeof errors === 'object') {
        for (const field in errors) {
            errors[field].forEach(message => {
                const li = document.createElement('li');
                li.className = 'mb-2';
                li.innerHTML = `<i class="fa fa-times-circle text-danger me-2"></i><strong>${message}</strong>`;
                errorList.appendChild(li);
            });
        }
    }
    
    const modal = new bootstrap.Modal(document.getElementById('errorModal'));
    modal.show();
}

function showSuccessModal(message) {
    document.getElementById('successMessage').textContent = message;
    const modal = new bootstrap.Modal(document.getElementById('successModal'));
    modal.show();
}

function showWarningModal(message) {
    document.getElementById('warningMessage').textContent = message;
    const modal = new bootstrap.Modal(document.getElementById('warningModal'));
    modal.show();
}

// Auto-show error modal if there are validation errors on page load
document.addEventListener('DOMContentLoaded', function() {
    const errorsElement = document.getElementById('validationErrors');
    if (errorsElement) {
        try {
            const errors = JSON.parse(errorsElement.textContent);
            if (Object.keys(errors).length > 0) {
                showErrorModal(errors);
            }
        } catch (e) {
            console.error('Error parsing validation errors:', e);
        }
    }
});
</script>
