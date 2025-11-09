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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search by Name -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Search Name</label>
                    <input type="text" id="searchName" placeholder="Search by name..." class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
                
                <!-- Filter by Role -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Filter Role</label>
                    <select id="filterRole" class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">All Roles</option>
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                        <option value="User">User</option>
                    </select>
                </div>
                
                <!-- Search by Email -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Search Email</label>
                    <input type="text" id="searchEmail" placeholder="Search by email..." class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
                
                <!-- Filter by Status -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Filter Status</label>
                    <select id="filterStatus" class="w-full px-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
            </div>
        
        </div>

        <!-- Simple Clean Table -->
        <div class="px-6 py-4 overflow-x-auto">
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full h-[50vh] border-collapse" id="usersTable">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <th class="px-6 py-4 text-left border-r border-gray-200">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4 text-left border-r border-gray-200">
                            <div class="flex items-center gap-2 text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:text-blue-600" data-sort="id">
                                ID
                                <i class="fas fa-sort text-gray-400"></i>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left border-r border-gray-200">
                            <div class="flex items-center gap-2 text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:text-blue-600" data-sort="name">
                                Name
                                <i class="fas fa-sort text-gray-400"></i>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left border-r border-gray-200">
                            <div class="flex items-center gap-2 text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:text-blue-600" data-sort="email">
                                Email
                                <i class="fas fa-sort text-gray-400"></i>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left border-r border-gray-200">
                            <div class="flex items-center gap-2 text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:text-blue-600" data-sort="role">
                                Role
                                <i class="fas fa-sort text-gray-400"></i>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left border-r border-gray-200">
                            <div class="flex items-center gap-2 text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:text-blue-600" data-sort="status">
                                Status
                                <i class="fas fa-sort text-gray-400"></i>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left border-r border-gray-200">
                            <div class="flex items-center gap-2 text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:text-blue-600" data-sort="date">
                                Joined Date
                                <i class="fas fa-sort text-gray-400"></i>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 -->
                    <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors" data-id="1" data-name="john smith" data-email="john.smith@example.com" data-role="Admin" data-status="Active" data-date="2024-01-15">
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
                    </tr>
                    
                    <!-- Row 2 -->
                    <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors" data-id="2" data-name="sarah johnson" data-email="sarah.j@example.com" data-role="Manager" data-status="Active" data-date="2024-02-20">
                        <td class="px-6 py-4 text-left border-r border-gray-200">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="text-sm font-semibold text-gray-900">2</span>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=10b981&color=fff" class="w-8 h-8 rounded-full shadow-sm" alt="User">
                                <span class="text-sm font-medium text-gray-900">Sarah Johnson</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="text-sm text-gray-600">sarah.j@example.com</span>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                Manager
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
                    </tr>

                    <!-- Row 3 -->
                    <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors" data-id="3" data-name="mike brown" data-email="mike.brown@example.com" data-role="User" data-status="Pending" data-date="2024-03-10">
                        <td class="px-6 py-4 text-left border-r border-gray-200">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="text-sm font-semibold text-gray-900">3</span>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=Mike+Brown&background=f59e0b&color=fff" class="w-8 h-8 rounded-full shadow-sm" alt="User">
                                <span class="text-sm font-medium text-gray-900">Mike Brown</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="text-sm text-gray-600">mike.brown@example.com</span>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                User
                            </span>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                Pending
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
                    </tr>

                    <!-- Row 4 -->
                    <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors" data-id="4" data-name="emily davis" data-email="emily.d@example.com" data-role="Manager" data-status="Active" data-date="2024-04-05">
                        <td class="px-6 py-4 text-left border-r border-gray-200">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="text-sm font-semibold text-gray-900">4</span>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=Emily+Davis&background=ec4899&color=fff" class="w-8 h-8 rounded-full shadow-sm" alt="User">
                                <span class="text-sm font-medium text-gray-900">Emily Davis</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="text-sm text-gray-600">emily.d@example.com</span>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                Manager
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
                    </tr>

                    <!-- Row 5 -->
                    <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors" data-id="5" data-name="david wilson" data-email="david.w@example.com" data-role="User" data-status="Inactive" data-date="2024-05-12">
                        <td class="px-6 py-4 text-left border-r border-gray-200">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="text-sm font-semibold text-gray-900">5</span>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name=David+Wilson&background=8b5cf6&color=fff" class="w-8 h-8 rounded-full shadow-sm" alt="User">
                                <span class="text-sm font-medium text-gray-900">David Wilson</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="text-sm text-gray-600">david.w@example.com</span>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                User
                            </span>
                        </td>
                        <td class="px-6 py-4 border-r border-gray-200">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                Inactive
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
                    </tr>
                    
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
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
        </div>

    </div>
</main>

@endsection
@push('custom-scripts')
@endpush

