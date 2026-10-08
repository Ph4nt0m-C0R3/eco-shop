/* ===============================
   ECO COMMON JS
================================ */

/**
 * Open eco modal by id
 */
function openEcoModalById(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

/**
 * Close eco modal by id
 */
function closeEcoModalById(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.classList.remove('show');
    document.body.style.overflow = '';

    // Reset form inside this modal ONLY
    const form = modal.querySelector('form');
    if (form) {
        form.reset();

        // Clear Laravel validation states
        form.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });

        // Clear validation messages
        form.querySelectorAll('.text-danger').forEach(el => {
            el.remove();
        });
    }
}

/**
 * Auto-bind overlay click to close
 */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.eco-modal-overlay').forEach(modal => {
        modal.addEventListener('click', e => {
            if (e.target === modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';

                const form = modal.querySelector('form');
                if (form) {
                    form.reset();
                    form.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                    });
                    form.querySelectorAll('.text-danger').forEach(el => {
                        el.remove();
                    });
                }
            }
        });
    });
});

/**
 * Toggle password visibility
 */
function togglePassword(id, icon) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
    icon.classList.toggle("fa-eye-slash");
    icon.classList.toggle("fa-eye");
}

/**
 * Auto-hide alerts
 */
document.addEventListener('DOMContentLoaded', () => {

    const alerts = document.querySelectorAll('.alert');

    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';

            setTimeout(() => {
                alert.remove();
            }, 500);

        }, 3000);
    });

});

// ECO DROPDOWN GLOBAL
document.addEventListener('click', function (e) {
    document.querySelectorAll('.eco-dropdown').forEach(dropdown => {
        if (!dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
        }
    });
});

document.querySelectorAll('.eco-dropdown-toggle').forEach(toggle => {
    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        this.closest('.eco-dropdown').classList.toggle('open');
    });
});

document.addEventListener('DOMContentLoaded', () => {

    let deleteUrl = null;

    /* Avatar preview */
    window.previewAvatar = function (event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = () => {
            const preview = document.getElementById('modalAvatarPreview');
            if (preview) preview.src = reader.result;

            const btn = document.getElementById('avatarUpdateBtn');
            if (btn) btn.disabled = false;
        };
        reader.readAsDataURL(file);
    };

    /* Show confirm modal */
    window.showDeleteConfirm = function (url) {
        deleteUrl = url;
        console.log('Delete URL:', deleteUrl);

        const overlay = document.getElementById('deleteConfirmOverlay');
        if (!overlay) {
            console.error('deleteConfirmOverlay not found');
            return;
        }

        overlay.classList.remove('d-none');
    };

    /* Hide confirm modal */
    window.hideDeleteConfirm = function () {
        document
            .getElementById('deleteConfirmOverlay')
            ?.classList.add('d-none');
    };

    /* Confirm delete */
    window.confirmDeleteAvatar = function () {
        if (!deleteUrl) {
            console.error('Delete URL missing');
            return;
        }

        fetch(deleteUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':
                    document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(res => {
            console.log('Delete response:', res.status);
            location.reload();
        })
        .catch(err => console.error(err));
    };

});

document.addEventListener('DOMContentLoaded', () => {

    const imageInput = document.querySelector('[data-image-preview="product"]');
    const previewContainer = document.getElementById('imagePreview');

    if (!imageInput || !previewContainer) return;

    let selectedFiles = [];

    imageInput.addEventListener('change', function () {
        selectedFiles = Array.from(this.files);
        renderPreviews();
    });

    function renderPreviews() {
        previewContainer.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();

            reader.onload = function (e) {

                const wrapper = document.createElement('div');
                wrapper.classList.add('position-relative');

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('rounded', 'border');
                img.style.width = '120px';
                img.style.height = '120px';
                img.style.objectFit = 'cover';

                /* Remove button */
                const removeBtn = document.createElement('button');
                removeBtn.type = "button";
                removeBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                removeBtn.classList.add('image-remove-btn');

                removeBtn.addEventListener('click', () => {
                    selectedFiles = selectedFiles.filter((_, i) => i !== index);
                    updateFileInput();
                    renderPreviews();
                });

                /* Primary selector */
                const showPrimary = imageInput.dataset.showPrimary === "true";
                let radioWrapper = null;

                if (showPrimary) {

                    radioWrapper = document.createElement('div');
                    radioWrapper.classList.add('form-check', 'text-center', 'mt-1');

                    const radio = document.createElement('input');
                    radio.type = "radio";
                    radio.name = "primary_image";
                    radio.value = `new_${index}`;
                    radio.classList.add("form-check-input");

                    if (index === 0 && !document.querySelector('input[name="primary_image"]:checked')) {
                        radio.checked = true;
                    }

                    const label = document.createElement('label');
                    label.classList.add("form-check-label", "small");
                    label.innerText = "Primary";

                    radioWrapper.appendChild(radio);
                    radioWrapper.appendChild(label);
                }

                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);

                if (radioWrapper) {
                    wrapper.appendChild(radioWrapper);
                }

                previewContainer.appendChild(wrapper);
            };

            reader.readAsDataURL(file);
        });
    }

    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files;
    }

});

