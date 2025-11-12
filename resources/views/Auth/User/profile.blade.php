@extends('App.Layout.index')

@section('title')
    profile-user
@endsection

@section('content')

    <main class="p-6">

        
        {{-- content --}}
        <div class="min-h-screen">

            <!-- Profile Content -->
            <div class="max-w-10xl mx-auto px-4 sm:px-6 lg:px-4 mt-2">

                <!-- Tabs & Content -->
                <div class="mt-6 grid grid-cols-1 gap-6">
                    
                    <!-- Top Column -->
                    <div class="lg:col-span-1 ">
                        <!-- About Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hidden" id="loading">
                            @include('App.Notif.loading-grow')
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" id="detail-user">
                            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center" id="user-name">
                                <i class="fas fa-user-circle mr-2 text-blue-500"></i>
                                Teguh Arthana
                            </h2>
                            <p class="text-gray-600 text-sm leading-relaxed mb-4" id="user-desc">
                                Main Director at PT Pandawa 5 With Role Super User
                                <!-- Passionate designer with 8+ years of experience creating beautiful and functional digital products. Love working with creative teams and solving complex problems. -->
                            </p>
                            <div class="space-y-3">
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-envelope w-5 text-gray-400"></i>
                                    <span class="text-gray-600 ml-3" id="email-user">john.doe@example.com</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-phone w-5 text-gray-400"></i>
                                    <span class="text-gray-600 ml-3" id="phone-user">+62 812-3456-7890</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-globe w-5 text-gray-400"></i>
                                    <span class="text-gray-600 ml-3" id="website-user">johndoe.com</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-birthday-cake w-5 text-gray-400"></i>
                                    <span class="text-gray-600 ml-3" id="birth-date">March 15, 1990</span>
                                </div>
                                <input type="hidden" id="id-profile" name="id-profile" value="1">
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Column -->
                    <div class="lg:col-span-1 ">
                        <!-- Tab Navigation -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex border-b border-gray-200 overflow-x-auto">
                                <button onclick="showTab('timeline')" class="tab-btn px-6 py-4 text-sm font-semibold border-b-2 border-blue-500 text-blue-600 hover:text-blue-900 active whitespace-nowrap">
                                    <i class="fas fa-clock mr-2"></i>
                                    Activity
                                </button>
                                <button onclick="showTab('settings')" class="tab-btn px-6 py-4 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:text-gray-900 whitespace-nowrap">
                                    <i class="fas fa-cog mr-2"></i>
                                    Settings
                                </button>

                                <button onclick="showTab('password')" class="tab-btn px-6 py-4 text-sm font-semibold border-b-2 border-transparent text-gray-600 hover:text-gray-900 whitespace-nowrap">
                                    <i class="fas fa-key mr-2"></i>
                                    Reset Password 
                                </button>
                            </div>

                            <!-- Tab Content -->
                            <div class="p-6 hidden" id="tab-content-loading">
                                @include('App.Notif.loading-grow')
                            </div>

                            <div class="p-6" id="tab-content-data">

                                <!-- Timeline Tab -->
                                <div id="timeline" class="tab-content">
                                    <div class="relative">
                                        <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                                        <div class="space-y-6">
                                            <div class="relative flex gap-4">
                                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold z-10">
                                                    2024
                                                </div>
                                                <div class="flex-1 pb-8">
                                                    <h3 class="font-bold text-gray-900 mb-2">Senior Product Designer</h3>
                                                    <p class="text-sm text-gray-600 mb-2">TechCorp Inc. • Full-time</p>
                                                    <p class="text-sm text-gray-500">Leading design team and creating innovative solutions for enterprise clients.</p>
                                                </div>
                                            </div>

                                            <div class="relative flex gap-4">
                                                <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold z-10">
                                                    2022
                                                </div>
                                                <div class="flex-1 pb-8">
                                                    <h3 class="font-bold text-gray-900 mb-2">Product Designer</h3>
                                                    <p class="text-sm text-gray-600 mb-2">StartupXYZ • Full-time</p>
                                                    <p class="text-sm text-gray-500">Designed core features for mobile and web applications.</p>
                                                </div>
                                            </div>

                                            <div class="relative flex gap-4">
                                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold z-10">
                                                    2020
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="font-bold text-gray-900 mb-2">UI/UX Designer</h3>
                                                    <p class="text-sm text-gray-600 mb-2">DesignStudio • Freelance</p>
                                                    <p class="text-sm text-gray-500">Created beautiful interfaces for various clients and projects.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- profile settings-->
                                <div id="settings" class="tab-content hidden">
                                    <div class="space-y-6">

                                        <form action="" id="form-update-profile">
                                            @csrf

                                            <div>
                                                <!-- <h3 class="font-bold text-gray-900 mb-4">Account Settings</h3> -->
                                                <div class="space-y-4">
    
                                                    <div class="md:col-span-2">
                                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                            Full Name <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[12px]">
                                                                <i class="fas fa-user text-gray-400"></i>
                                                            </div>
                                                            <input type="text" name="fullname" required class="block w-full pl-10 pr-4 py-3 text-[14px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Enter full name">
                                                        </div>
                                                    </div>
    
                                                    <!-- Email Input -->
                                                    <div>
                                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                            Email Address <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[12px]">
                                                                <i class="fas fa-envelope text-gray-400"></i>
                                                            </div>
                                                            <input type="email" name="email" required class="block w-full pl-10 pr-4 py-3 text-[14px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="example@email.com">
                                                        </div>
                                                    </div>
    
                                                    <!-- Phone Input -->
                                                    <div>
                                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                            Phone Number <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[12px]">
                                                                <i class="fas fa-phone text-gray-400"></i>
                                                            </div>
                                                            <input type="tel" name="phone" required class="block w-full pl-10 pr-4 py-3 text-[14px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="+62 812-3456-7890">
                                                        </div>
                                                    </div>
    
                                                    <!-- Date Birth -->
                                                    <div>
                                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                            Date of Birth <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[12px]">
                                                                <i class="fas fa-calendar text-gray-400"></i>
                                                            </div>
                                                            <input type="date" name="dob" required class="block w-full pl-10 pr-4 py-3 text-[14px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div>
                                                <!-- <h3 class="font-bold text-gray-900 mb-4 text-red-600">Danger Zone</h3> -->
                                                <div class="space-y-3 mt-4">
                                                    <button class="w-full p-4 border-2 border-blue-200 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors font-medium">
                                                        <i class="fas fa-file mr-2"></i>
                                                        Update Profile
                                                    </button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>

                                <!-- password settings-->
                                <div id="password" class="tab-content hidden">
                                    <div class="space-y-6">
                                        <div>
                                            <!-- <h3 class="font-bold text-gray-900 mb-4">Account Settings</h3> -->
                                            <div class="space-y-4">
                                                <form action="" id="form-update-password">
                                                    @csrf
                                                    <!-- Password Input -->
                                                    <div>
                                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                            Password <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[12px]">
                                                                <i class="fas fa-lock text-gray-400"></i>
                                                            </div>
                                                            <input type="password" id="password" name="password" required class="block w-full pl-10 pr-12 py-3 text-[14px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Enter password">
                                                            <button type="button" onclick="togglePassword('password', 'toggleIcon1')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                                <i id="toggleIcon1" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                                            </button>
                                                        </div>
                                                    </div>
    
                                                    <!-- Confirm Password -->
                                                    <div>
                                                        <label class="block text-sm font-semibold text-gray-700 mb-2 mt-4">
                                                            Confirm Password <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="relative">
                                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[12px]">
                                                                <i class="fas fa-lock text-gray-400"></i>
                                                            </div>
                                                            <input type="password" id="confirmPassword" name="confirm_password" required class="block w-full pl-10 pr-12 py-3 text-[14px] border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" placeholder="Confirm password">
                                                            <button type="button" onclick="togglePassword('confirmPassword', 'toggleIcon2')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                                <i id="toggleIcon2" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>

                                        </div>

                                        <div>
                                            <!-- <h3 class="font-bold text-gray-900 mb-4 text-red-600">Danger Zone</h3> -->
                                            <div class="space-y-3">
                                                <button class="w-full p-4 border-2 border-blue-200 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors font-medium">
                                                    <i class="fas fa-key mr-2"></i>
                                                    Update Password
                                                </button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer Spacing -->
            <div class="h-20"></div>
        </div>
    

        {{-- alert --}}
        <div id="alertContainer"></div>
    </main>


@endsection


@push('custom-scripts')

    <script>
        // showtab
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });

            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-600');
            });

            // Show selected tab
            document.getElementById(tabName).classList.remove('hidden');

            // Add active class to clicked button
            event.target.classList.add('active', 'border-blue-500', 'text-blue-600');
            event.target.classList.remove('border-transparent', 'text-gray-600');
        }
        
        // Toggle Password Visibility
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // load when update data user
        function loadDataUser(){
            $.ajax({
                url     : 'update-profile-user/{id}',
                type    : 'GET',
                beforeSend: function() {
                    $('#detail-user').addClass('hidden');
                    $('#loading').removeClass('hidden');
                }, success: function(res) {
                    $('#detail-user').removeClass('hidden');
                    $('#loading').addClass('hidden');

                    // update data
                    $('#email-user').text(res.email);
                    $('#phone-user').text(res.phone);
                    $('#website-user').text(res.website);
                    $('#birth-date').text(res.birth);
                    $('#id-profile').val(res.id);
                    // id="id-profile" name="id-profile"

                },error: function(err) {
                    $('#detail-user').removeClass('hidden');
                    $('#loading').addClass('hidden');
                    showAlert({
                        type: 'error',
                        title: 'Gagal!',
                        message: xhr.responseJSON.message || 'Terjadi kesalahan',
                        duration: 0
                    });
                }

            });
        }

        $('#form-update-profile').on('submit', function(e){
            e.preventDefault();
            let data    = $('#form-update-profile').serialize();
            let idx     = $('#id-profile').val();
            let url     = "{{ route('admin.auth.profile.update', ['id' => ':id']) }}".replace(':id', idx);
            sendData(url, data);
        });

        $('#form-update-profile').on('submit', function(e){
            e.preventDefault();
            let data    = $('#form-update-profile').serialize();
            let idx     = $('#id-profile').val();
            let url     = "{{ route('admin.auth.password.update', ['id' => ':id']) }}".replace(':id', idx);
            sendData(url, data);
        });

        function sendData(url, data){
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                beforeSend: function() {
                    $('#tab-content-data').addClass('hidden');
                    $('#tab-content-loading').removeClass('hidden');
                },
                success: function(response) {
                    $('#tab-content-data').removeClass('hidden');
                    $('#tab-content-loading').addClass('hidden');
                    $('#form-update-profile')[0].reset();
                    $('#form-update-password')[0].reset();
                    if(response.success) {
                        showAlert({
                            type: 'success',
                            title: 'Berhasil!',
                            message: response.message,
                            duration: 0 // 0 = manual close, atau set 3000 untuk 3 detik
                        });
                    }
                    loadDataUser();
                },
                error: function(xhr) {
                    $('#tab-content-data').removeClass('hidden');
                    $('#tab-content-loading').addClass('hidden');
                    $('#form-update-profile')[0].reset();
                    $('#form-update-password')[0].reset();
                    showAlert({
                        type: 'error',
                        title: 'Gagal!',
                        message: xhr.responseJSON.message || 'Terjadi kesalahan',
                        duration: 0
                    });
                }
            });
        }

        


    </script>

    <script>

    </script>
@endpush

