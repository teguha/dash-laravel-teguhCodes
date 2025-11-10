// delete data
function deleteData(idx){
    dataToDelete = idx;
    const modal = $('#deleteModal');
    modal.removeClass('hidden');

    setTimeout(() => {
        modal.addClass('opacity-100');
    }, 10);
}

function closeModalDelete() {
    const modal = $('#deleteModal');
    modal.addClass('hidden');
    dataToDelete = null;
}

function confirmDelete() {
    if (dataToDelete !== null) {
        let idx = dataToDelete;
        let deleteUrl = routes['delete'].replace(':id', idx);
        let deleteData = {
            id : dataToDelete,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        sendDelete(deleteUrl, deleteData);
    }
}

$(document).on('click', '#deleteModal', function(e){
    if(e.target === this){
        closeModalDelete();
    }
});