<form id="roleFilter" class="grid grid-cols-1 md:grid-cols-4 gap-4">
    @csrf
    <!-- Search -->
    @if(!empty($fields['search']))
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" 
                id="search-table" 
                class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" 
                placeholder="Search roles...">
        </div>
    @endif

    <!-- Filter Role -->
    @if(!empty($fields['role']))
        <div>
            <select id="filter-role" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="manager">Manager</option>
                <option value="user">User</option>
            </select>
        </div>
    @endif


    <!-- Filter Date -->
    @if(!empty($fields['date']))
        <div>
            <input type="date" 
                id="filter-date" 
                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
        </div>
    @endif

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
                class="px-4 py-2 text-sm font-medium text-white bg-red-400 border border-red-500 rounded-lg hover:bg-red-400 transition-colors">
            <i class="fas fa-redo"></i>
            Reset
        </button>
    </div>
</form>