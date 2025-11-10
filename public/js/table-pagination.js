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

