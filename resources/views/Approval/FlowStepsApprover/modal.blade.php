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
                <!-- Step Order  -->

                <!-- Module  -->
                <input type="hidden" name="approval_flow_step_id" value="{{ $id }}">

                <!-- Approval Type -->
                <div class="max-w-md">
                    <label class="custom-label">
                        <i class="fas fa-chess-king"></i>
                        Approval By
                    </label>
                    <select id="approval_type" name="approval_type" class="w-full" required>
                        <option value="">Select Approval By</option>
                        <option value="role">Role</option>
                        <option value="structure">Structure</option>
                        <option value="user">User</option>
                    </select>
                </div>


                @php
                    use App\Models\Auth\Role;
                    use App\Models\Master\Position;
                    use App\Models\Auth\User;
                    $roles = Role::all();
                    $structures = Position::all();
                    $users = User::where('status','active')->get();
                @endphp
                {{-- if approval by = role => maka select role ini active --}}
                {{-- approver role --}}
                <div class="max-w-md hidden" id="approver_role">
                    <label class="custom-label">
                        <i class="fas fa-chess-king"></i>
                        Approver By Role
                    </label>
                    <select id="approver_by_role" name="approver_by_role" class="w-full" >
                        @foreach ($roles as $r)
                            <option value="{{$r->id}}" >{{$r->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="max-w-md hidden" id="approver_structure">
                    <label class="custom-label">
                        <i class="fas fa-chess-king"></i>
                        Approver By Structure
                    </label>
                    <select id="approver_by_structure" name="approver_by_structure" class="w-full" >
                        @foreach ($structures as $s)
                            <option value="{{$s->id}}" >{{$s->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="max-w-md hidden" id="approver_user">
                    <label class="custom-label">
                        <i class="fas fa-chess-king"></i>
                        Approver By User
                    </label>
                    <select id="approver_by_user" name="approver_by_user" class="w-full" >
                        @foreach ($users as $u)
                            <option value="{{$u->id}}" >{{$u->name}}</option>
                        @endforeach
                    </select>
                </div>

                {{-- if approval by = structure => maka select structure ini active--}}
                {{-- approver structure --}}

                {{-- if  approval by = user => maka select user ini active--}}
                {{-- approver user --}}
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
