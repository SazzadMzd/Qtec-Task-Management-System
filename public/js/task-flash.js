document.addEventListener('DOMContentLoaded', function () {
    const flashNode = document.getElementById('swal-flash-message');

    if (!flashNode || !flashNode.dataset.message) {
        return;
    }

    window.Swal.fire({
        title: 'Success',
        text: flashNode.dataset.message,
        icon: 'success',
        confirmButtonText: 'OK',
    });
});
