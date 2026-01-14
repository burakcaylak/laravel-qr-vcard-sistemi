// Initialize KTMenu
KTMenu.init();

// Add click event listener to delete buttons
document.querySelectorAll('[data-kt-action="delete_qr"]').forEach(function (element) {
    element.addEventListener('click', function (e) {
        e.preventDefault();
        const qrId = this.getAttribute('data-kt-qr-id');
        Swal.fire({
            text: window.__('common.delete_qr_code_confirm'),
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: window.__('common.yes'),
            cancelButtonText: window.__('common.no'),
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/qr-code/' + qrId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                }).then(() => {
                    window.LaravelDataTables['qr-codes-table'].ajax.reload();
                    Swal.fire({
                        text: window.__('common.qr_code_deleted'),
                        icon: 'success',
                        buttonsStyling: false,
                        confirmButtonText: window.__('common.ok'),
                        customClass: {
                            confirmButton: 'btn btn-primary',
                        }
                    });
                });
            }
        });
    });
});


