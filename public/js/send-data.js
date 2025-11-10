function sendData(url, method, data) {
    $.ajax({
        url: url,
        type: method,
        data: data,
        beforeSend: function() {
            $('#form-body').addClass('hidden');
            $('#form-loading').removeClass('hidden');
        },
        success: function(response) {
            $('#form-loading').addClass('hidden');
            $('#form-body').removeClass('hidden');
            $('#formModal')[0].reset();
            closeModal(); 
            if(response.success) {
                showAlert({
                    type: 'success',
                    title: 'Berhasil!',
                    message: response.message,
                    duration: 0 // 0 = manual close, atau set 3000 untuk 3 detik
                });
            }
            loadTable(1);
        },
        error: function(xhr) {
            $('#form-loading').addClass('hidden');
            $('#form-body').removeClass('hidden');
            closeModal();
            showAlert({
                type: 'error',
                title: 'Gagal!',
                message: xhr.responseJSON.message || 'Terjadi kesalahan',
                duration: 0
            });
        }
    });
}

function sendDelete(url, data) {
    $.ajax({
        url: url,
        type: 'DELETE',
        data: data,
        beforeSend: function() {
            $('#delete-body').addClass('hidden');
            $('#delete-footer').addClass('hidden');
            $('#form-loading-delete').removeClass('hidden');
        },
        success: function(response) {
            $('#form-loading-delete').addClass('hidden');
            $('#delete-body').removeClass('hidden');
            $('#delete-footer').removeClass('hidden');
            closeModalDelete(); 
            if(response.success) {
                showAlert({
                    type: 'success',
                    title: 'Berhasil!',
                    message: response.message,
                    duration: 0 // 0 = manual close, atau set 3000 untuk 3 detik
                });
            }
            loadTable(1);
        },
        error: function(xhr) {
            $('#form-loading-delete').addClass('hidden');
            $('#delete-body').removeClass('hidden');
            $('#delete-footer').removeClass('hidden');
            closeModalDelete(); 
            showAlert({
                type: 'error',
                title: 'Gagal!',
                message: xhr.responseJSON.message || 'Terjadi kesalahan',
                duration: 0
            });
        }
    });
}