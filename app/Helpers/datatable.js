
// Helper Functions untuk DataTable Modern

// Toggle Dropdown Menu
function toggleDropdown(button) {
    const dropdown = button.nextElementSibling;
    const allDropdowns = document.querySelectorAll('.dropdown-menu');
    
    // Close all other dropdowns
    allDropdowns.forEach(menu => {
        if (menu !== dropdown) {
            menu.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
    
    // Prevent event bubbling
    event.stopPropagation();
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-dropdown')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});

// Delete Role Function dengan SweetAlert2
function deleteRole(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-lg',
            confirmButton: 'px-5 py-2.5 rounded-lg font-medium',
            cancelButton: 'px-5 py-2.5 rounded-lg font-medium'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send DELETE request
            fetch(`/admin/setting/role/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: data.message || 'Role has been deleted.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload DataTable
                        $('#data-table').DataTable().ajax.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to delete role.',
                        icon: 'error',
                        confirmButtonColor: '#3b82f6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while deleting the role.',
                    icon: 'error',
                    confirmButtonColor: '#3b82f6'
                });
            });
        }
    });
}

// Bulk Delete Function
function bulkDelete() {
    const checkedBoxes = document.querySelectorAll('#data-table tbody input[type="checkbox"]:checked');
    const ids = Array.from(checkedBoxes).map(cb => cb.value);
    
    if (ids.length === 0) {
        Swal.fire({
            title: 'No Selection',
            text: 'Please select at least one item to delete',
            icon: 'info',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete ${ids.length} item(s)!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete them!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('/admin/setting/role/bulk-delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ ids: ids })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: `${ids.length} item(s) have been deleted.`,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#data-table').DataTable().ajax.reload();
                        document.querySelector('#data-table thead input[type="checkbox"]').checked = false;
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Failed to delete items.',
                        icon: 'error',
                        confirmButtonColor: '#3b82f6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while deleting items.',
                    icon: 'error',
                    confirmButtonColor: '#3b82f6'
                });
            });
        }
    });
}

// Export Function
function exportData(format = 'excel') {
    const form = $('#roleFilter');
    const params = new URLSearchParams({
        format: format,
        search: form.find('#search-table').val(),
        roles: form.find('#filter-role').val(),
        date: form.find('#filter-date').val()
    });
    
    window.location.href = `/admin/setting/role/export?${params.toString()}`;
}

// Print Function
function printTable() {
    const printContent = document.getElementById('data-table').outerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    
    printWindow.document.write('<html><head><title>Print Table</title>');
    printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2/dist/tailwind.min.css" rel="stylesheet">');
    printWindow.document.write('<style>@media print { body { -webkit-print-color-adjust: exact; } }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 250);
}

// Add loading state to table
function setTableLoading(isLoading) {
    const wrapper = document.querySelector('.dataTables_wrapper');
    if (isLoading) {
        wrapper.classList.add('loading');
    } else {
        wrapper.classList.remove('loading');
    }
}

// Show notification toast
function showToast(message, type = 'success') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500',
        warning: 'bg-yellow-500'
    };
    
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Initialize Select2 for filters (optional)
$(document).ready(function() {
    if ($.fn.select2) {
        $('#filter-role').select2({
            placeholder: 'Select role...',
            allowClear: true,
            minimumResultsForSearch: Infinity
        });
    }
});

