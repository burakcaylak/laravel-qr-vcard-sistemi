// Initialize KTMenu after table draw
if (typeof KTMenu !== 'undefined') {
    KTMenu.init();
}

// Use event delegation for delete buttons (works with dynamically loaded content)
document.addEventListener('click', function(e) {
    if (e.target.closest('[data-kt-action="delete_row"]')) {
        e.preventDefault();
        const element = e.target.closest('[data-kt-action="delete_row"]');
        const userId = element.getAttribute('data-kt-user-id');
        
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                text: window.__('common.delete_user_confirm'),
                icon: 'warning',
                buttonsStyling: false,
                showCancelButton: true,
                confirmButtonText: window.__('common.delete'),
                cancelButtonText: window.__('common.cancel'),
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form and submit it
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/user-management/users/' + userId;
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken.getAttribute('content');
                        form.appendChild(csrfInput);
                    }
                    
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    }
});
