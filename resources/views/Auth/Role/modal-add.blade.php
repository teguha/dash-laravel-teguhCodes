<div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">
        
    <!-- Modal Container -->
    <div id="modalContainer" class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 transform transition-all duration-300 scale-95 opacity-0">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-plus text-blue-600"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Add New Data</h2>
            </div>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="formAdd" data-type="add" data-url="#">
            @csrf
            
            <div class="p-6 space-y-5 hidden" id="form-loading">
                <div class="text-center py-6 text-gray-500">
                    @include('App.Notif.loading-grow')
                </div>
            </div>

            <div class="p-6 space-y-5" id="form-body">
                <!-- Nama  -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 text-[14px]">
                        <i class="fas fa-user text-gray-400 mr-2"></i>Name
                    </label>
                    <input type="text" 
                            placeholder="Role Name"
                            id="name"
                            name="name"
                            class="w-full px-4 py-3 border border-gray-300 text-[12px] rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 outline-none" required>
                </div>
    
    
                <!-- Color -->
                <div class="max-w-md">
                    <label class="custom-label">
                        <i class="fas fa-palette"></i>
                        Color Selection
                    </label>
                    <select id="color-theme" name="color_theme" class="w-full" required>
                        <option value="" disabled selected>Choose a color...</option>
                        <option value="green" data-color="#10b981">Green</option>
                        <option value="blue" data-color="#3b82f6">Blue</option>
                        <option value="red" data-color="#ef4444">Red</option>
                        <option value="yellow" data-color="#eab308">Yellow</option>
                        <option value="purple" data-color="#a855f7">Purple</option>
                        <option value="black" data-color="#000000">Black</option>
                        <option value="white" data-color="#ffffff">White</option>
                        <option value="gray" data-color="#6b7280">Gray</option>
                        <option value="orange" data-color="#f97316">Orange</option>
                        <option value="pink" data-color="#ec4899">Pink</option>
                        <option value="brown" data-color="#92400e">Brown</option>
                        <option value="teal" data-color="#14b8a6">Teal</option>
                        <option value="lime" data-color="#84cc16">Lime</option>
                        <option value="cyan" data-color="#06b6d4">Cyan</option>
                        <option value="indigo" data-color="#6366f1">Indigo</option>
                        <option value="violet" data-color="#8b5cf6">Violet</option>
                        <option value="magenta" data-color="#d946ef">Magenta</option>
                        <option value="gold" data-color="#fbbf24">Gold</option>
                        <option value="silver" data-color="#d1d5db">Silver</option>
                        <option value="bronze" data-color="#b45309">Bronze</option>
                        <option value="emerald" data-color="#059669">Emerald</option>
                        <option value="amethyst" data-color="#9333ea">Amethyst</option>
                        <option value="sapphire" data-color="#1e40af">Sapphire</option>
                        <option value="ruby" data-color="#dc2626">Ruby</option>
                        <option value="peridot" data-color="#65a30d">Peridot</option>
                        <option value="topaz" data-color="#f59e0b">Topaz</option>
                        <option value="coral" data-color="#fb7185">Coral</option>
                        <option value="maroon" data-color="#7f1d1d">Maroon</option>
                    </select>
                </div>
    
                <!-- Alamat -->
                {{-- <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 text-[14px]">
                        <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>Alamat
                    </label>
                    <textarea rows="3" placeholder="Masukkan alamat lengkap" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 outline-none resize-none"></textarea>
                </div> --}}
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                <button onclick="closeModal()" type="button" 
                        class="px-5 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>Simpan Data
                </button>
            </div>

        </form>
    </div>
</div>
