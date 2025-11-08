@extends('App.Layout.index')

@section('title')
    role-data
@endsection

@section('content')
<main class="p-6">
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-cog mr-2"></i>
                    Setting
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <a href="#" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Role</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">All Roles</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Page Header with Title and Actions -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-4">
        <div class="flex flex-col lg:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">User Role</h2>
                <p class="text-sm text-gray-500 mt-1">Manage and view all roles in the system</p>
                <button class="text-sm text-gray-500 mt-2"><i class="fa fas fa-download text-[14px] w-2 mr-4"></i>Format Excel Example</button>
            </div>

            <div class="flex flex-row lg:flex-wrap items-center gap-2 justify-end">
                <button class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all text-[8px] md:text-xs lg:text-xs font-medium">
                    <i class="fas fa-file mr-2"></i>
                    Add New
                </button>

                <button class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all text-[8px] md:text-xs lg:text-xs font-medium">
                    <i class="fas fa-file-excel mr-2"></i>
                    Export Excel
                </button>
                <button class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all text-[8px] md:text-xs lg:text-xs font-medium">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Export PDF
                </button>
                <label class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-all text-[8px] md:text-xs lg:text-xs font-medium cursor-pointer">
                    <i class="fas fa-file-import mr-2"></i>
                    Import Excel
                    <input type="file" accept=".xlsx,.xls" class="hidden" id="importExcel">
                </label>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Table Header with Filters -->
        <div class="p-6 border-gray-200">
            <form id="roleFilter" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                        id="search-table" 
                        class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" 
                        placeholder="Search roles...">
                </div>

                <!-- Filter Role -->
                <div>
                    <select id="filter-role" 
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">All Roles</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <!-- Filter Date -->
                <div>
                    <input type="date" 
                        id="filter-date" 
                        class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <!-- Filter Buttons -->
                <div class="flex items-center gap-2">
                    <button type="button" 
                            id="find" 
                            class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                    <button type="button" 
                            id="reset-filter" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-redo"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Simple Clean Table -->
        <div class="px-6 py-4 overflow-x-auto">
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full border-collapse" id="data-table">
                <thead>
                    <tr class="bg-gradient-to-r border-b border-gray-200 from-gray-50 to-gray-100">
                        <th class="px-6 py-4 text-left border-r border-gray-200">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4 text-left border-r border-gray-200 ">
                            <div class="flex items-center gap-2 font-[sans-serif] text-[12px]   font-bold text-black uppercase tracking-wider cursor-pointer hover:text-blue-600" data-sort="id">
                                #
                                {{-- <i class="fa fa-sort-amount-asc text-gray-400"></i> --}}
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left border-r border-gray-200 ">
                            <div class="flex items-center gap-2 font-[sans-serif] text-[12px]   font-bold text-black  tracking-wider cursor-pointer hover:text-blue-600" data-sort="name">
                                Name
                                <i class="fa fa-sort-amount-asc text-gray-400"></i>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left border-r border-gray-200 ">
                            <div class="flex items-center gap-2 font-[sans-serif] text-[12px]   font-bold text-black  tracking-wider cursor-pointer hover:text-blue-600" data-sort="role">
                                Role
                                {{-- <i class="fa fa-sort-amount-asc text-gray-400"></i> --}}
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left border-r border-gray-200 ">
                            <div class="flex items-center gap-2 font-[sans-serif] text-[12px]   font-bold text-black  tracking-wider cursor-pointer hover:text-blue-600" data-sort="status">
                                Permission
                                {{-- <i class="fa fa-sort-amount-asc text-gray-400"></i> --}}
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left border-r border-gray-200 ">
                            <div class="flex items-center gap-2 font-[sans-serif] text-[12px]   font-bold text-black tracking-wider cursor-pointer hover:text-blue-600" data-sort="status">
                                Created at
                                <i class="fa fa-sort-amount-asc text-gray-400"></i>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left border-r border-gray-200 ">
                            <div class="flex items-center gap-2 font-[sans-serif] text-[12px]   font-bold text-black  tracking-wider cursor-pointer hover:text-blue-600" data-sort="status">
                                Updated at
                                <i class="fa fa-sort-amount-asc text-gray-400"></i>
                            </div>
                        </th>
                        {{-- <th class="px-6 py-4 text-center text-xs font-bold text-black  tracking-wider">
                            Action
                        </th> --}}
                    </tr>
                </thead>

                <tbody id="table-body">
                    <!-- Row 1 -->
                    {{-- <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors" data-id="1" data-name="john smith" data-email="john.smith@example.com" data-role="Admin" data-status="Active" data-date="2024-01-15">
                        <td class="px-6 py-4 text-left border-r border-gray-200">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="text-sm font-semibold text-gray-900">1</span>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=John+Smith&background=3b82f6&color=fff" class="w-8 h-8 rounded-full shadow-sm" alt="User">
                                <span class="text-sm font-medium text-gray-900">John Smith</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="text-sm text-gray-600">john.smith@example.com</span>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                Admin
                            </span>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                Active
                            </span>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200 flex flex-col justify-start items-left gap-1">
                            <span class="text-xs text-gray-500"><i class="fa fa-clock w-5 h-5"></i>May 12, 2024</span>
                            <span class="text-xs text-gray-500"><i class="fa fa-user w-5 h-5"></i>Teguh Arthana</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center">
                                <div class="relative action-dropdown">
                                    
                                    <button id="toggleMenuAction" class="inline-flex items-center justify-center w-9 h-9 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    
                                    <div id="menuAction" class="dropdown-menu hidden absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-gray-200 z-10 overflow-hidden">
                                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-eye text-blue-600 text-xs"></i>
                                            </div>
                                            <span class="font-medium">View Details</span>
                                        </a>
                                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-map-marker-alt text-green-600 text-xs"></i>
                                            </div>
                                            <span class="font-medium">Track Activity</span>
                                        </a>
                                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-yellow-50 transition-colors">
                                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-edit text-yellow-600 text-xs"></i>
                                            </div>
                                            <span class="font-medium">Edit User</span>
                                        </a>
                                        <div class="border-t border-gray-200 my-1"></div>
                                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-trash text-red-600 text-xs"></i>
                                            </div>
                                            <span class="font-medium">Delete User</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr> --}}
                    
                </tbody>
            
            </table>
        </div>

        <!-- Pagination -->
        {{-- <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-600">
                    Showing <span class="font-medium text-gray-900">1</span> to <span class="font-medium text-gray-900">5</span> of <span class="font-medium text-gray-900">50</span> results
                </div>
                <div class="flex items-center gap-1">
                    <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-4 py-2 text-sm bg-blue-500 text-white rounded-lg font-medium">1</button>
                    <button class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-white transition-colors">2</button>
                    <button class="px-4 py-2 text-sm border border-gray-300 rounded-lg hover:bg-white transition-colors">3</button>
                    <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-white transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div> --}}

    </div>
</main>

@endsection

@push('custom-scripts')

<script>

$(function() {
    let currentSortBy = null;
    let currentSortDir = 'asc';

    loadTable(1); // panggil pertama kali

    // 🔍 Ketika user mengetik atau klik tombol filter
    $('#find, #filter-role, #filter-date').on('change keyup', function() {
        loadTable(1);
    });

    // 🔁 Fungsi utama load data
    function loadTable(page = 1) {
        $.ajax({
            url: "{{ route('admin.setting.role.data') }}",
            type: "GET",
            data: {
                search: $('#search-table').val(),
                role: $('#filter-role').val(),
                date: $('#filter-date').val(),
                sort_by: currentSortBy,
                sort_dir: currentSortDir,
                page: page
            },
            beforeSend: function() {
                $('#table-body').html(`
                    <tr><td colspan="8" class="text-center py-6 text-gray-500">
                        <!-- 5. Bouncing Dots -->
                        <div class="flex flex-col items-center justify-center text-center animate-fade-in">
                            <div class="spinner-container">
                                <div class="bouncing-dots">
                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                    <div class="dot"></div>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Loading ...</h3>
                                <p class="text-sm text-gray-600 text-center">Get data</p>
                            </div>
                        </div>
                    </td></tr>
                `);
            },
            success: function(res) {
                renderTable(res.data);
                renderPagination(res.pagination);
            },
            error: function(err) {
                console.log(err);
                $('#table-body').html(`<tr><td colspan="8" class="text-center text-red-500">
                    <div class="flex flex-col items-center justify-center text-center animate-fade-in">
                        <!-- Animated Cloud Icon -->
                        <div class="relative mb-6">
                            <div class="absolute inset-0 bg-cyan-100 rounded-full blur-xl opacity-50 animate-pulse-slow"></div>
                            <div class="relative">
                                <svg class="w-32 h-32 animate-float" viewBox="0 0 200 200" fill="none">
                                    <!-- Rocket -->
                                    <path d="M100 30 Q110 40 110 60 L110 100 Q110 110 100 115 Q90 110 90 100 L90 60 Q90 40 100 30 Z" 
                                        fill="#06b6d4" stroke="#0891b2" stroke-width="2"/>
                                    <ellipse cx="100" cy="60" rx="10" ry="15" fill="#67e8f9"/>
                                    <path d="M110 100 L120 120 L110 115 Z" fill="#f87171"/>
                                    <path d="M90 100 L80 120 L90 115 Z" fill="#f87171"/>
                                    
                                    <!-- Flames -->
                                    <g transform="translate(100, 115)">
                                        <ellipse cx="-5" cy="15" rx="8" ry="15" fill="#fb923c" opacity="0.7">
                                            <animate attributeName="ry" values="15;20;15" dur="0.5s" repeatCount="indefinite"/>
                                        </ellipse>
                                        <ellipse cx="5" cy="15" rx="8" ry="15" fill="#fbbf24" opacity="0.7">
                                            <animate attributeName="ry" values="15;22;15" dur="0.6s" repeatCount="indefinite"/>
                                        </ellipse>
                                        <ellipse cx="0" cy="18" rx="6" ry="18" fill="#fef08a" opacity="0.8">
                                            <animate attributeName="ry" values="18;25;18" dur="0.4s" repeatCount="indefinite"/>
                                        </ellipse>
                                    </g>
                                    
                                    <!-- Stars -->
                                    <g opacity="0.6">
                                        <circle cx="40" cy="50" r="2" fill="#fbbf24">
                                            <animate attributeName="opacity" values="1;0.3;1" dur="1.5s" repeatCount="indefinite"/>
                                        </circle>
                                        <circle cx="160" cy="70" r="2" fill="#fbbf24">
                                            <animate attributeName="opacity" values="0.3;1;0.3" dur="2s" repeatCount="indefinite"/>
                                        </circle>
                                        <circle cx="50" cy="120" r="2" fill="#fbbf24">
                                            <animate attributeName="opacity" values="1;0.5;1" dur="1.8s" repeatCount="indefinite"/>
                                        </circle>
                                    </g>
                                </svg>
                            </div>
                        </div>
                
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Connection Error</h3>
                        <p class="text-gray-600 mb-6 max-w-md">
                            We're having trouble loading your data. Please check your connection and try again.
                        </p>
                    </div>
                </td></tr>`);
            }
        });
    }

    // 🧩 Render data ke tabel
    function renderTable(data) {
        let html = '';
        if (data.length === 0) {
            html = `
                <tr><td colspan="8" class="text-center py-6 text-gray-500">
                    <div class="flex flex-col items-center justify-center text-center animate-fade-in">
                        <!-- Animated Empty Box -->
                        <div class="relative mb-6">
                            <div class="absolute inset-0 bg-purple-100 rounded-full blur-xl opacity-50 animate-pulse-slow"></div>
                            <div class="relative">
                                <svg class="w-32 h-32 animate-float" viewBox="0 0 200 200" fill="none">
                                    <!-- Box -->
                                    <rect x="40" y="60" width="120" height="100" rx="8" fill="#f3f4f6" stroke="#d1d5db" stroke-width="2"/>
                                    <rect x="40" y="60" width="120" height="30" rx="8" fill="#e5e7eb"/>
                                    
                                    <!-- Floating items -->
                                    <circle cx="70" cy="110" r="8" fill="#a855f7" opacity="0.6">
                                        <animate attributeName="cy" values="110;100;110" dur="2s" repeatCount="indefinite"/>
                                    </circle>
                                    <circle cx="100" cy="120" r="10" fill="#ec4899" opacity="0.6">
                                        <animate attributeName="cy" values="120;110;120" dur="2.5s" repeatCount="indefinite"/>
                                    </circle>
                                    <circle cx="130" cy="105" r="6" fill="#3b82f6" opacity="0.6">
                                        <animate attributeName="cy" values="105;95;105" dur="1.8s" repeatCount="indefinite"/>
                                    </circle>
                                    
                                    <!-- Lines in box -->
                                    <line x1="60" y1="140" x2="110" y2="140" stroke="#d1d5db" stroke-width="3" stroke-linecap="round"/>
                                    <line x1="60" y1="150" x2="140" y2="150" stroke="#d1d5db" stroke-width="3" stroke-linecap="round"/>
                                </svg>
                            </div>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">No Data Available</h3>
                        <p class="text-gray-600 mb-6 max-w-md">
                            There's no data to display yet. Start by adding your first entry to see it appear here.
                        </p>
                    
                    </div>
                </td></tr>`;
        } else {
            data.forEach((item, i) => {
                html += `
                <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors">
                    <td class="px-6 py-4 text-left border-r border-gray-200"><input type="checkbox" value="${item.id}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"></td>
                    <td class="px-6 py-4 border-r border-gray-200">${i}</td>
                    <td class="px-6 py-4 border-r border-gray-200">${item.name}</td>
                    <td class="px-6 py-4 border-r border-gray-200">${item.email}</td>
                    <td class="px-6 py-4 border-r border-gray-200">${item.role ?? '-'}</td>
                    <td class="px-6 py-4 border-r border-gray-200">${item.status ?? '-'}</td>
                    <td class="px-6 py-4 border-r border-gray-200">${item.created_at}</td>
                    <td class="px-6 py-4 text-center">Action</td>
                </tr>`;
            });
        }
        $('#table-body').html(html);
    }

    // 📄 Render pagination custom
    function renderPagination(pagination) {
        let html = '';
        if (pagination.last_page > 1) {
            html += `<div class="flex items-center justify-center gap-2">`;

            // Prev
            if (pagination.current_page > 1) {
                html += `<button class="px-3 py-1 border rounded" data-page="${pagination.current_page - 1}">&laquo;</button>`;
            }

            // Pages
            for (let i = 1; i <= pagination.last_page; i++) {
                let active = (i === pagination.current_page)
                    ? 'bg-blue-500 text-white'
                    : 'border hover:bg-gray-100';
                html += `<button class="px-3 py-1 rounded ${active}" data-page="${i}">${i}</button>`;
            }

            // Next
            if (pagination.current_page < pagination.last_page) {
                html += `<button class="px-3 py-1 border rounded" data-page="${pagination.current_page + 1}">&raquo;</button>`;
            }

            html += `</div>`;
        }

        $('#pagination').html(html);
    }

    // 📎 Event klik pagination
    $(document).on('click', '#pagination button', function() {
        const page = $(this).data('page');
        loadTable(page);
    });

    // 🧭 Sorting click handler
    $(document).on('click', '[data-sort]', function() {
        const field = $(this).data('sort');

        // Toggle arah sort
        if (currentSortBy === field) {
            currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
        } else {
            currentSortBy = field;
            currentSortDir = 'asc';
        }

        // console.log(currentSortBy, currentSortDir);
        // Reset semua ikon
        $('[data-sort] i').removeClass('fa-sort-amount-asc fa-sort-amount-desc text-blue-600').addClass('fa-sort-amount-asc text-gray-400');

        // Update ikon aktif
        const icon = $(this).find('i');
        icon.removeClass('fa-sort-amount-asc text-gray-400');
        if (currentSortDir === 'asc') {
            icon.addClass('fa-sort-amount-asc text-blue-600');
        } else {
            icon.addClass('fa-sort-amount-desc text-blue-600');
        }

        // Reload data dengan sort baru
        loadTable(1);
    });
});
</script>
@endpush

