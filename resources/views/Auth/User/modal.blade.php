<div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">
        
    <!-- Modal Container -->
    <div id="modalContainer" class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 transform transition-all duration-300 scale-95 opacity-0">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div id="modal-title-bg" class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i id="modal-title-icon" class="fas fa-plus text-blue-600"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800" id="modal-title">Edit Data</h2>
            </div>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="formModal" data-type="add" data-url="#">
            @csrf
            
            <div class="p-6 space-y-5 hidden" id="form-loading">
                <div class="text-center py-6 text-gray-500">
                    @include('App.Notif.loading-grow')
                </div>
            </div>

            <div class="p-6 space-y-5 hidden" id="form-track">
            </div>

            <div class="p-6 space-y-5" id="form-body">
                <!-- Nama  -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 text-[14px]">
                        <i class="fas fa-user text-gray-400 mr-2"></i>Name
                    </label>
                    <input type="text" placeholder="Name" id="name" name="name" class="w-full px-4 py-3 border border-gray-300 text-[12px] rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 outline-none" required>
                </div>

                <!-- Email  -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 text-[14px]">
                        <i class="fas fa-envelope text-gray-400 mr-2"></i>Email
                    </label>
                    <input type="text" placeholder="example@gmail.com" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 text-[12px] rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 outline-none" required>
                </div>

                <!-- Phone  -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 text-[14px]">
                        <i class="fas fa-phone text-gray-400 mr-2"></i>Phone
                    </label>
                    <input type="text" placeholder="+6285.." id="phone" name="phone" class="w-full px-4 py-3 border border-gray-300 text-[12px] rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 outline-none" required>
                </div>

                <!-- Password  -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 text-[14px]">
                        <i class="fas fa-key text-gray-400 mr-2"></i>Password
                    </label>
                    <input type="text" placeholder="password" id="password" name="password" class="w-full px-4 py-3 border border-gray-300 text-[12px] rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 outline-none" required>
                </div>

                @php 
                    $roles =  App\Models\Auth\Role::all();
                @endphp
                <!-- Color -->
                <div class="max-w-md">
                    <label class="custom-label">
                        <i class="fas fa-user-secret"></i>
                        Role
                    </label>
                    <select id="role-select" name="role" class="w-full" required>
                        <option value="" disabled selected>Choose role ...</option>
                        @foreach($roles as $role)
                            <option value="{{$role->id}}">{{$role->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl" id="modal-footer">
                <button onclick="closeModal()" type="button" 
                        class="px-5 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>

                <button id="btn-submit" type="submit" class="px-5 py-2.5 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors duration-200 font-medium shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>Save Data
                </button>

            </div>

        </form>
    </div>
</div>
