 $(document).on('click', '[data-sort]', function() {
    const field = $(this).data('sort');

    // Toggle arah sort
    if (currentSortBy === field) {
        currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
    } else {
        currentSortBy = field;
        currentSortDir = 'asc';
    }

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