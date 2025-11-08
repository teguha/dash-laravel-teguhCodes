<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulse-slow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        @keyframes draw-search {
            0% { stroke-dashoffset: 100; }
            100% { stroke-dashoffset: 0; }
        }

        /* Bouncing Dots with Color */
        @keyframes bounce-dots {
            0%, 80%, 100% {
                transform: scale(0.8) translateY(0);
                opacity: 0.5;
            }
            40% {
                transform: scale(1.2) translateY(-20px);
                opacity: 1;
            }
        }

         .bouncing-dots {
            display: flex;
            gap: 12px;
        }

        .bouncing-dots .dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            animation: bounce-dots 1.4s infinite ease-in-out;
        }

        .bouncing-dots .dot:nth-child(1) {
            background: #3b82f6;
            animation-delay: -0.32s;
        }

        .bouncing-dots .dot:nth-child(2) {
            background: #8b5cf6;
            animation-delay: -0.16s;
        }

        .bouncing-dots .dot:nth-child(3) {
            background: #ec4899;
            animation-delay: 0s;
        }

        .bouncing-dots .dot:nth-child(4) {
            background: #f59e0b;
            animation-delay: 0.16s;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        .animate-pulse-slow {
            animation: pulse-slow 2s ease-in-out infinite;
        }
        
        .empty-icon {
            width: 120px;
            height: 120px;
        }
        
        /* Custom gradient backgrounds */
        .gradient-bg-1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-bg-2 {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .gradient-bg-3 {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .gradient-bg-4 {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
    </style>
</head>

<body class="bg-gray-100">

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-all duration-300 bg-white shadow-lg">
        <div class="h-full px-3 py-4 overflow-y-auto">
            <!-- Logo -->
            <div class="flex items-center justify-between mb-8 px-2">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xl"><i class="fa fa-paw text-white"></i></span>
                    </div>
                    <span id="logoText" class="ml-3 text-xl font-semibold text-gray-800 transition-opacity duration-300 whitespace-nowrap overflow-hidden">Dashboard</span>
                </div>
                <button id="closeSidebar" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Navigation Menu -->
            @include('App.Layout.sidebar')

        </div>
    </aside>

    <!-- Overlay untuk mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>

    <!-- Main Content -->
    <div id="mainContent" class="transition-all duration-300 lg:ml-64">
        <!-- Header -->
        @include('App.Layout.header')

        {{-- @yield('breadcrumb') --}}
        <!-- Content Area -->
        @yield('content')

        <!-- Footer -->


        <div id="footerContent" class="p-6">
            <div class="bg-gray flex flex-row justify-center border-t border-gray-200">
                <p class="text text-sm text-gray-400 mt-4">Copyright © 2025 &nbsp;<i class="fas fa-circle text-[10px] w-4 h-2"></i> Made by TeguhCodes</p>
            </div>
        </div> 
    </div>

    <!-- 1. jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 (untuk delete confirmation) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const toggleSidebarMobile = document.getElementById('toggleSidebarMobile');
        const toggleSidebarDesktop = document.getElementById('toggleSidebarDesktop');
        const closeSidebar = document.getElementById('closeSidebar');
        const mainContent = document.getElementById('mainContent');
        let isCollapsed = false;

        // Toggle sidebar untuk desktop (collapse/expand)
        toggleSidebarDesktop.addEventListener('click', () => {
            isCollapsed = !isCollapsed;
            
            if (isCollapsed) {
                sidebar.classList.add('lg:w-20');
                sidebar.classList.remove('lg:w-64');
                mainContent.classList.add('lg:ml-20');
                mainContent.classList.remove('lg:ml-64');
                
                // Sembunyikan text
                document.querySelectorAll('.menu-text').forEach(el => {
                    el.classList.add('lg:hidden');
                });

                document.querySelectorAll('.title-menu').forEach(el => {
                    el.classList.add('lg:hidden');
                });

                document.getElementById('logoText').classList.add('lg:hidden');
                document.querySelectorAll('.user-info').forEach(el => {
                    el.classList.add('lg:hidden');
                });
            } else {
                sidebar.classList.remove('lg:w-20');
                sidebar.classList.add('lg:w-64');
                mainContent.classList.remove('lg:ml-20');
                mainContent.classList.add('lg:ml-64');
                
                // Tampilkan text
                document.querySelectorAll('.menu-text').forEach(el => {
                    el.classList.remove('lg:hidden');
                });

                document.querySelectorAll('.title-menu').forEach(el => {
                    el.classList.remove('lg:hidden');
                });

                document.getElementById('logoText').classList.remove('lg:hidden');
                document.querySelectorAll('.user-info').forEach(el => {
                    el.classList.remove('lg:hidden');
                });
            }
        });

        // Toggle sidebar untuk mobile (buka/tutup)
        function openSidebarMobile() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay.classList.remove('hidden');
        }

        function closeSidebarMobile() {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            overlay.classList.add('hidden');
        }

        toggleSidebarMobile.addEventListener('click', openSidebarMobile);
        closeSidebar.addEventListener('click', closeSidebarMobile);
        overlay.addEventListener('click', closeSidebarMobile);

        // Submenu toggle
        document.querySelectorAll('.menu-parent > button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                const submenu = parent.querySelector('.submenu');
                const chevron = this.querySelector('.fa-chevron-down');
                
                // Toggle submenu
                submenu.classList.toggle('hidden');
                
                // Rotate chevron
                if (submenu.classList.contains('hidden')) {
                    chevron.style.transform = 'rotate(0deg)';
                } else {
                    chevron.style.transform = 'rotate(180deg)';
                }
            });
        });

        // Set initial state untuk mobile
        if (window.innerWidth < 1024) {
            sidebar.classList.add('-translate-x-full');
        }
    </script>

    @stack('custom-scripts')


</body>
</html>