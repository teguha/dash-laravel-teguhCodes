<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Dashboard</title>
    <script src="{{asset('js/tailwind.js')}}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- alert --}}
    <style>
        @keyframes slideIn {
            from {
                transform: translateY(-100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .alert-overlay {
            animation: fadeIn 0.3s ease-out;
        }

        .alert-content {
            animation: scaleIn 0.3s ease-out;
        }

        .icon-success {
            animation: scaleIn 0.5s ease-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen">
    <div class="min-h-screen flex">


        <div id="alertContainer"></div>
        
        <!-- Left Side - Login Form -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Logo & Header -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-lg mb-4">
                        <i class="fas fa-layer-group text-white text-2xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">App Name!</h1>
                    <p class="text-gray-600">Sign in to continue to your dashboard</p>
                </div>

                <!-- Login Form -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                    <form id="loginForm" action="{{ route('auth.login') }}" class="space-y-6" method="POST">
                        @csrf
                        <!-- Email Input -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id= "email"
                                    required 
                                    class="block w-full pl-11 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-gray-900 placeholder-gray-400" 
                                    placeholder="Enter your email"
                                >
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="hidden" id="password-form">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    required 
                                    class="block w-full pl-11 pr-12 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-gray-900 placeholder-gray-400" 
                                    placeholder="Enter your password"
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword()" 
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center"
                                >
                                    <i id="toggleIcon" class="fas fa-eye text-gray-400 hover:text-gray-600 transition-colors"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between hidden" id="forget-password-form">
                            <label class="flex items-center cursor-pointer group">
                                <input 
                                    type="checkbox" 
                                    name="remember" 
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                >
                                <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900 transition-colors">Remember me</span>
                            </label>
                            <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                Forgot password?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button 
                            id ="btn-submit"
                            type="submit" 
                            class="w-full py-3.5 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-200"
                        disabled>
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Sign In
                        </button>
                    </form>
                </div>

                <!-- Footer -->
                <div class="text-center mt-8 text-sm text-gray-500">
                    <p>&copy; 2025 TeguhCodes. All rights reserved.</p>
                    <div class="flex items-center justify-center gap-4 mt-2">
                        <a href="#" class="hover:text-gray-700 transition-colors">Privacy Policy</a>
                        <span>•</span>
                        <a href="#" class="hover:text-gray-700 transition-colors">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Decorative Panel -->
        <div class="hidden lg:flex flex-1 bg-gradient-to-br from-blue-500 via-blue-600 to-purple-600 p-12 items-center justify-center relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/2 translate-y-1/2"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 text-white max-w-md">
                <div class="mb-8">
                    <div class="w-20 h-20 bg-white bg-opacity-20 backdrop-blur-lg rounded-2xl flex items-center justify-center mb-6 shadow-xl">
                        <i class="fas fa-chart-line text-4xl"></i>
                    </div>
                    <h2 class="text-4xl font-bold mb-4">Manage Everything in One Place</h2>
                    <p class="text-lg text-blue-100 leading-relaxed">
                        Access your powerful dashboard with advanced analytics, real-time monitoring, and comprehensive tools to grow your business.
                    </p>
                </div>

                <!-- Features -->
                <div class="space-y-4 mb-8">
                    <div class="flex items-center gap-3 p-4 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-lg"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Real-time Analytics</p>
                            <p class="text-sm text-blue-100">Track your performance instantly</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shield-alt text-lg"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Secure & Reliable</p>
                            <p class="text-sm text-blue-100">Bank-level security for your data</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-white bg-opacity-10 backdrop-blur-sm rounded-xl">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-bolt text-lg"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Lightning Fast</p>
                            <p class="text-sm text-blue-100">Optimized for speed and performance</p>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center">
                        <p class="text-3xl font-bold mb-1">10K+</p>
                        <p class="text-sm text-blue-100">Active Users</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold mb-1">99.9%</p>
                        <p class="text-sm text-blue-100">Uptime</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl font-bold mb-1">4.9★</p>
                        <p class="text-sm text-blue-100">Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-8 shadow-2xl text-center">
            <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
            <p class="text-gray-700 font-semibold">Signing you in...</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @if (session('success'))
        <script src="{{asset('js/alert.js')}}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showAlert({
                    type: 'success',
                    title: 'Berhasil!',
                    message: "{{ session('success') }}",
                    duration: 0
                });
            });
        </script>
    @endif
    <script>
        // Toggle Password Visibility
        $('#email').on('change keyup', function() {
            let data ={
                email : $('#email').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            }
            checkEmail(data);
        });

        function checkEmail(data){
            $.ajax({
                url     : "{{ route('auth.check.login') }}",
                method  : "POST",
                data    :  data,
                success: function(response) {
                    if(response.success) {
                        $('#forget-password-form').removeClass('hidden');
                        $('#password-form').removeClass('hidden');
                        $('#btn-submit').removeClass('from-gray-500 to-gray-600').addClass('from-blue-500 to-blue-600');
                        $('#btn-submit').prop('disabled', false);
                    }else{
                        $('#password-form').addClass('hidden');
                        $('#forget-password-form').addClass('hidden');
                        $('#btn-submit').removeClass('from-blue-500 to-blue-600').addClass('from-gray-500 to-gray-600');
                        $('#btn-submit').prop('disabled', true);
                    }
                },
                error: function(xhr) {
                    $('#forget-password-form').addClass('hidden');
                    $('#password-form').addClass('hidden');
                    $('#btn-submit').removeClass('from-blue-500 to-blue-600').addClass('from-gray-500 to-gray-600');
                    $('#btn-submit').prop('disabled', true);
                }
            });
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form Submit Handler
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let data    = $('#loginForm').serialize();
            let method  = 'POST';
            let url     = "{{ route('auth.login') }}";
            sendData( url, method, data);
        
        });

        function sendData(url, method, data) {
            $.ajax({
                url: url,
                type: method,
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    document.getElementById('loadingOverlay').classList.remove('hidden');
                },
                success: function(response) {
                    if(response.success) {
                        window.location.href = response.route;
                    }
                },
                error: function(xhr) {
                    setTimeout(() => {
                        document.getElementById('loadingOverlay').classList.add('hidden');
                    }, 1500);

                    let errMsg = '';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
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
        
    </script>

    
</body>
</html>