document.addEventListener('submit', function (event) {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || !form.matches('[data-confirm]')) {
        return;
    }

    event.preventDefault();

    const title = form.dataset.confirmTitle || 'Are you sure?';
    const text = form.dataset.confirmText || 'Please confirm this action.';
    const confirmText = form.dataset.confirmButton || 'Confirm';
    const cancelText = form.dataset.cancelButton || 'Cancel';

    window.Swal.fire({
        title,
        text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        reverseButtons: true,
        focusCancel: true,
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

document.addEventListener('change', function (event) {
    const field = event.target;

    if (!(field instanceof HTMLSelectElement)) {
        return;
    }

    const form = field.form;

    if (!(form instanceof HTMLFormElement) || !form.matches('[data-auto-submit]')) {
        return;
    }

    form.requestSubmit();
});
