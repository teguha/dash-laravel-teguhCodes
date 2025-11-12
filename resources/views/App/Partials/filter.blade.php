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
                class="block w-full pl-10 pr-3 py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" 
                placeholder="Search roles...">
        </div>
    @endif

    <!-- Filter Role -->
    @if(!empty($fields['role']))
        @php
            $roles =  App\Models\Auth\Role::all();
        @endphp

        <div>
            <select id="filter-role" class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                <option value="">All Roles</option>
                @forEach($roles as $role)
                    <option value="{{$role->id}}">{{ucfirst($role->name)}}</option>
                @endforeach
            </select>
        </div>
    @endif


    <!-- Filter Date -->
    @if(!empty($fields['date']))
        <div class="date-input-wrapper">
            <svg class="date-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <input type="text" 
                id="filter-date"
                name="datefilter" 
                placeholder="Select date range"
                class="date-input-minimal"
                readonly
                >
        </div>
    @endif

    <!-- Filter Buttons -->
    <div class="flex items-center gap-2">
        <button type="button" 
                id="find" 
                class="flex-1 inline-flex items-center justify-center px-4 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-filter mr-2"></i>
            Filter
        </button>
        <button type="button" 
                id="reset-filter" 
                class="px-4 py-3 text-sm font-medium text-white bg-red-600 border border-red-500 rounded-lg hover:bg-red-700 transition-colors">
            <i class="fas fa-redo"></i>
            Reset
        </button>
    </div>
</form>