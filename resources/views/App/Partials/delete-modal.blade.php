<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 transition-opacity">
    <!-- Modal Container -->
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        

        <div class="p-6 space-y-5 hidden" id="form-loading-delete">
            <div class="text-center py-6 text-gray-500">
                @include('App.Notif.loading-grow')
            </div>
        </div>

        <!-- Icon & Title -->
        <div class="p-6 text-center" id="delete-body">
            <!-- Warning Icon -->
            <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            
            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-900 mb-2">
                Hapus Data
            </h3>
            
            <!-- Description -->
            <p class="text-gray-600 mb-6">
                Apakah Anda yakin akan menghapus data ini? Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 px-6 pb-6" id="delete-footer">
            <!-- Button No -->
            <button onclick="closeModalDelete()" class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">
                Tidak
            </button>
            
            <!-- Button Yes -->
            <button onclick="confirmDelete()" class="flex-1 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                Ya, Hapus
            </button>
        </div>

    </div>
</div>