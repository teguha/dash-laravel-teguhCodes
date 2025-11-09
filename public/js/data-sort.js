let currentSortBy = null;
let currentSortDir = 'asc';
//Fungsi utama load data
function loadTable(page = 1) {
    $.ajax({
        url: "{{ route('admin.setting.role.data') }}",
        type: "GET",
        data: {
            search: $('#search-table').val(),
            role: $('#filter-role').val(),
            date: $('#filter-date').val(),
            sort_by: currentSortBy,
            sort_dir: currentSortDir,
            page: page
        },
        beforeSend: function() {
            $('#table-body').html(`
                <tr><td colspan="${lengthHead.length + 3}" class="text-center py-6 text-gray-500">
                    @include('App.Notif.loading-grow')
                </td></tr>
            `);
        },
        success: function(res) {
            renderTable(res.data, res.pagination);
            renderPagination(res.pagination);
            renderPaginationInfo(res.pagination);
        },
        error: function(err) {
            console.log(err);
            $('#table-body').html(`
                <tr><td colspan="${lengthHead.length + 3}" class="text-center text-red-500">
                    @include('App.Notif.error')
                </td></tr
            `);
        }
    });
}

//Render data ke tabel
function renderTable(data, pagination) {
    let html = '';
    if (data.length === 0) {
        html = `
        <tr><td colspan="${lengthHead.length + 3}" class="text-center py-6 text-gray-500">
            @include('App.Notif.nodata')
        </td></tr>
        `;
        
    } else {
        const startNo = (pagination.current_page - 1) * pagination.per_page + 1;
        data.forEach((item, i) => {

            html += `
            <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors">
                <td class="px-6 py-4 text-left border-r border-gray-200">
                    <input type="checkbox" value="${item.id}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </td>
                <td class="px-6 py-4 border-r border-gray-200">
                    <span class="text-sm font-semibold text-gray-900">${ startNo + i}</span>
                </td>
                <td class="px-6 py-4 border-r border-gray-200">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-${item.color}-100 text-${item.color}-700">
                        ${item.name}
                    </span>
                </td>
                <td class="px-6 py-4 border-r border-gray-200">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                        <i class="fas fa-key text-green-600 text-xs mr-2"></i>
                        
                        ${item.permission_count}
                    </span>
                </td>
                ${item.created_at}
                ${item.updated_at}
                <td class="px-6 py-4">
                    <div class="flex justify-center">
                        <div class="action-dropdown relative inline-block">
                            <button 
                                data-id="${item.id}" 
                                class="toggleMenuAction inline-flex items-center justify-center w-9 h-9 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            
                            <div class="dropdown-menu hidden absolute left-1/2 -translate-x-1/2 w-52 bg-white rounded-xl shadow-xl border border-gray-200 z-[9999] overflow-hidden">
                                <a href="/users/${item.id}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-eye text-blue-600 text-xs"></i>
                                </div>
                                <span class="font-medium">View Details</span>
                                </a>
                                <a href="/users/${item.id}/track" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-green-600 text-xs"></i>
                                </div>
                                <span class="font-medium">Track Activity</span>
                                </a>
                                <a href="/users/${item.id}/edit" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-yellow-50 transition-colors">
                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-edit text-yellow-600 text-xs"></i>
                                </div>
                                <span class="font-medium">Edit User</span>
                                </a>
                                <div class="border-t border-gray-200 my-1"></div>
                                <button onclick="deleteUser(${item.id})" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-trash text-red-600 text-xs"></i>
                                </div>
                                <span class="font-medium">Delete User</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>`;
        });

    }
    $('#table-body').html(html);
}

// Render pagination custom
function renderPagination(pagination) {
    let html = '';
    if (pagination.last_page > 1) {
        html += `<div class="flex items-center justify-center gap-2">`;

        // Prev
        if (pagination.current_page > 1) {
            html += `<button class="px-3 py-1 border rounded" data-page="${pagination.current_page - 1}">&laquo;</button>`;
        }

        // Pages
        for (let i = 1; i <= pagination.last_page; i++) {
            let active = (i === pagination.current_page)
                ? 'bg-blue-500 text-white'
                : 'border hover:bg-gray-100';
            html += `<button class="px-3 py-1 rounded ${active}" data-page="${i}">${i}</button>`;
        }

        // Next
        if (pagination.current_page < pagination.last_page) {
            html += `<button class="px-3 py-1 border rounded" data-page="${pagination.current_page + 1}">&raquo;</button>`;
        }

        html += `</div>`;
    }

    $('#pagination').html(html);
}

// Render pagination info
function renderPaginationInfo(pagination) {
    if (!pagination || pagination.total === 0) {
        $('#pagination-info').html('');
        return;
    }

    let html = `Showing ${pagination.from} to ${pagination.to} of ${pagination.total} results`;
    $('#pagination-info').html(html);
}

// Event klik pagination
$(document).on('click', '#pagination button', function() {
    const page = $(this).data('page');
    loadTable(page);
});

// Sorting click handler
$(document).on('click', '[data-sort]', function() {
    const field = $(this).data('sort');

    // Toggle arah sort
    if (currentSortBy === field) {
        currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
    } else {
        currentSortBy = field;
        currentSortDir = 'asc';
    }

    // console.log(currentSortBy, currentSortDir);
    // Reset semua ikon
    $('[data-sort] i').removeClass('fa-sort-amount-asc fa-sort-amount-desc text-blue-600').addClass('fa-sort-amount-asc text-gray-400');

    // Update ikon aktif
    const icon = $(this).find('i');
    icon.removeClass('fa-sort-amount-asc text-gray-400');
    if (currentSortDir === 'asc') {
        icon.addClass('fa-sort-amount-asc text-blue-600');
    } else {
        icon.addClass('fa-sort-amount-desc text-blue-600');
    }

    // Reload data dengan sort baru
    loadTable(1);
});