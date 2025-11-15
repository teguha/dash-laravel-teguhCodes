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
            }else{
                showAlert({
                    type: 'error',
                    title: 'Gagal!',
                    message: response.message || 'Terjadi kesalahan',
                    duration: 0
                });
            }
            loadTable(1);
        },
        error: function(xhr) {
            $('#form-loading').addClass('hidden');
            $('#form-body').removeClass('hidden');
            closeModal();
            let errMsg = '';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                // Ambil semua pesan error dari Laravel
                let errors = xhr.responseJSON.errors;
                // Gabungkan semua pesan error jadi satu string dengan <br>
                errMsg = Object.values(errors).flat().join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errMsg = xhr.responseJSON.message;
            } else {
                errMsg = 'Terjadi kesalahan yang tidak diketahui.';
            }
            showAlert({
                type: 'error',
                title: 'Gagal!',
                message: errMsg || 'Terjadi kesalahan',
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