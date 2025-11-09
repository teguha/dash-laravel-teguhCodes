<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        
        <li class="inline-flex items-center">
            <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                <i class="{{$fields['icon']}} mr-2"></i>
                {{$fields['parent']}}
            </a>
        </li>
        

        @if(!empty($fields['child1']))
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <a href="#" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Role</a>
                </div>
            </li>
        @endif

        @if(!empty($fields['child2']))
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">All Roles</span>
                </div>
            </li>
        @endif
    </ol>
</nav>