<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CDN untuk Select2 - Tambahkan di head section -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <!-- Custom CSS untuk styling Select2 -->
    <style>
        /* Select2 Container Styling */
        .select2-container--default .select2-selection--single {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            height: 48px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
            background: white;
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #3b82f6;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        /* Selected Value Styling */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 1rem;
            padding-right: 1rem;
            line-height: 46px;
            font-size: 14px;
            color: #1f2937;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #9ca3af;
        }

        /* Arrow Styling */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px;
            right: 10px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #6b7280 transparent transparent transparent;
            border-width: 6px 5px 0 5px;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #6b7280 transparent;
            border-width: 0 5px 6px 5px;
        }

        /* Dropdown Styling */
        .select2-container--default .select2-results__option {
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.15s;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #eff6ff;
            color: #1f2937;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #dbeafe;
            color: #1e40af;
            font-weight: 500;
        }

        /* Dropdown Container */
        .select2-dropdown {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin-top: 4px;
        }

        /* Search Box Styling */
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 8px 12px;
            font-size: 14px;
            outline: none;
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Custom width for select2 */
        .select2-container {
            width: 100% !important;
        }

        /* Color badge in options */
        .color-badge {
            display: inline-block;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            margin-right: 8px;
            vertical-align: middle;
            border: 2px solid #e5e7eb;
        }

        /* Label styling */
        .custom-label {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .custom-label i {
            color: #9ca3af;
            margin-right: 8px;
        }
    </style>

    {{-- animation table --}}
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

    <style>
        /* Style 1: Minimal with Icon */
        .date-input-wrapper {
            position: relative;
        }

        .date-input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #858585;
            pointer-events: none;
        }

        .date-input-minimal {
            width: 100%;
            padding: 12px 14px 12px 48px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #2d3748;
            transition: all 0.3s ease;
            background: white;
            cursor: pointer;
        }

        .date-input-minimal:hover {
            border-color: #cbd5e0;
        }

        .date-input-minimal:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Custom Daterangepicker Styles */
        .daterangepicker {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            font-family: inherit;
        }

        .daterangepicker td.active,
        .daterangepicker td.active:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .daterangepicker .ranges li.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        svg {
            width: 20px;
            height: 20px;
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
                    <div class="w-10 h-10 bg-blue-300 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xl"><i class="fa fa-snowflake text-white"></i></span>
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
 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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

    <script src="{{asset('js/alert.js')}}"></script>

    @stack('custom-scripts')

    <script>
        $('#filter-role').select2({
            placeholder: 'Select Role...',
            allowClear: true,
            // width: '100%',
            // templateResult: formatColor,
            // templateSelection: formatColor,
            minimumResultsForSearch: 5 // Show search box if more than 5 items
        });
    </script>

    <!-- Moment.js -->
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <!-- Daterangepicker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Daterangepicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(function() {

            $('input[name="datefilter"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });

            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

        });
    </script>

</body>
</html>