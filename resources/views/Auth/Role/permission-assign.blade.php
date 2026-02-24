@extends('App.Layout.index')

@section('title')
    assign-permission-data
@endsection

@section('content')
    <main class="p-6">

        {{-- breadcrumb --}}
        @include('App.Partials.breadcrumb', [
            'fields' => [
                'icon' => 'fas fa-cog',
                'parent' => 'Setting',
                'child1' => 'Assign Permission',
                'child2' => ''
            ]
        ])

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 mb-2">Role Permission Management</h1>
                    <p class="text-slate-600">Assign permissions for role: 
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{$role->color}}-100 text-{{$role->color}}-800 ml-2">
                            {{ $role->name }}
                        </span>
                    </p>
                </div>
                <div class="hidden md:block">
                    <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.setting.role.storePermission', $id) }}" class="space-y-6">
            @csrf

            @php
                $grouped = collect($permissions)->groupBy('header_menu')->map(function ($items) {
                    return $items->groupBy('child_menu');
                });
            @endphp

            @foreach ($grouped as $header => $childMenus)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white tracking-wide">
                            {{ strtoupper($header) }}
                        </h2>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="px-6 py-4 text-left text-xs font-semibold bg-purple-100 text-purple-700 uppercase tracking-wider w-1/4">
                                        Menu
                                    </th>
                                    <th class="px-4 py-4 text-center w-24">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-purple-100 text-purple-700">
                                                Check All
                                            </span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-4 text-center w-24">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-700">
                                            View
                                        </span>
                                    </th>
                                    <th class="px-4 py-4 text-center w-24">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-700">
                                            Add
                                        </span>
                                    </th>
                                    <th class="px-4 py-4 text-center w-24">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-amber-100 text-amber-700">
                                            Edit
                                        </span>
                                    </th>
                                    <th class="px-4 py-4 text-center w-24">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-red-100 text-red-700">
                                            Delete
                                        </span>
                                    </th>
                                    <th class="px-4 py-4 text-center w-24">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-700">
                                            Approved
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($childMenus as $child => $permissionsList)
                                    @php
                                        $names = $permissionsList->pluck('name')->toArray();
                                        $slugs = $permissionsList->pluck('slug')->toArray();
                                        $isChild = !empty($child);
                                        $rowKey = $isChild ? Str::slug($header . '-' . $child) : Str::slug($header);
                                        $base = $isChild ? Str::slug($header) . '-' . Str::slug($child) : Str::slug($header);
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-slate-900">
                                                {{ ucfirst($child) ?: ucfirst($header) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <div class="flex justify-center">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer check-all" data-row="{{ $rowKey }}">
                                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                </label>
                                            </div>
                                        </td>
                                        @foreach (['view', 'add','edit', 'delete', 'approve'] as $perm)
                                            @php 
                                                $permSlug = $perm . '-' . $base; 
                                            @endphp
                                            <td class="px-4 py-4 text-center">
                                                @if (in_array($permSlug, $slugs))
                                                    <div class="flex justify-center">
                                                        <input type="checkbox" 
                                                        name="permissions[]" 
                                                        value="{{ $permSlug }}"
                                                        {{ in_array($permSlug, $lastPermissions) ? 'checked' : '' }}
                                                        class="w-5 h-5 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer perm-{{ $rowKey }}">
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            <!-- Action Buttons -->
            <div class="flex items-center justify-between bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <a href="{{route('admin.setting.role.index')}}" 
                    class="inline-flex items-center px-6 py-3 bg-white border-2 border-slate-300 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl text-sm font-semibold text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-500/30 transition-all duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Save Permissions
                </button>
            </div>
        </form>

        {{-- alert --}}
        <div id="alertContainer"></div>
    </main>


@endsection


@push('custom-scripts')

    <script>
        $(document).on('change', '.check-all', function () {
            const row = $(this).data('row');
            // console.log(row);
            $(`.perm-${row}:not(:disabled)`).prop('checked', $(this).is(':checked'));
        });
    </script>

    {{-- send data modal --}}
    <script src="{{asset('js/send-data.js')}}"></script>

    {{-- table action --}}
    <script src="{{asset('js/table-action.js')}}"></script>

    {{-- table sort --}}
    <script src="{{asset('js/table-sort.js')}}"></script>

    {{-- modal add edit show track --}}
    <script src="{{asset('js/modal-add.js')}}"></script>

    {{-- modal delete --}}
    <script src="{{asset('js/modal-delete.js')}}"></script>

    
@endpush

