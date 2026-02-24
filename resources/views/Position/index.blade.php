@extends('App.Layout.index')

@section('title')
    position-data
@endsection

@section('content')
    <main class="p-6">

        {{-- breadcrumb --}}
        @include('App.Partials.breadcrumb', [
            'fields' => [ 
                'icon' => 'fas fa-chess-king',
                'parent' => 'Master',
                'child1' => 'Position',
                'child2' => ''
            ]
        ])

        @php
            $user = Auth::user();
            $re_role = App\Models\Auth\Role::find($user->role_id ?? 26);
            $userPerms = json_decode($re_role->permission ?? '[]', true);
            $menu = 'structure-position';
        @endphp

        <!-- Page Header with Title and Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-4">
            <div class="flex flex-col lg:flex-row md:items-left md:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Position</h2>
                    <p class="text-sm text-gray-500 mt-1">Manage and view all position in the system</p>
                    <button class="text-sm text-gray-500 mt-2"><i class="fa fas fa-download text-[14px] w-2 mr-4"></i>Format Excel Example</button>
                </div>

                @include('App.Partials.action', [
                    'fields' => [
                        'add' => in_array('add-'.$menu, $userPerms),
                        'export'    => true,
                        'import'    => false,
                    ]
                ])
                
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Filters Table -->
            <div class="p-6 border-gray-200">
                @include('App.Partials.filter', [
                    'formId' => 'roleFilter',
                    'fields' => [
                        'search' => true,
                        // 'role'   => true,
                        'date'   => true,
                    ]
                ])
            </div>

            @php
                // Head Table
                $columns = [
                    ['field' => 'name', 'label' => 'Name', 'sortable' => true],
                    ['field' => 'level', 'label' => 'Level', 'sortable' => false],
                    ['field' => 'structure', 'label' => 'Structure', 'sortable' => false],
                    ['field' => 'updated_at', 'label' => 'Updated At', 'sortable' => true],
                ];
            @endphp

            <!-- Simple Clean Table -->
            <div class="px-6 py-4 overflow-x-auto">
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="w-full border-collapse" id="data-table">
                    @include('App.Components.table-head', ['columns' => $columns])
                    
                    {{-- Body Table --}}
                    <tbody id="table-body">
                    </tbody>
                </table>
            </div>

            @include('App.Components.pagination')
        </div>

        {{-- alert --}}
        <div id="alertContainer"></div>
    </main>

    {{-- modal --}}
    @include('Position.modal')
    @include('App.Partials.delete-modal')

    
@endsection


@push('custom-scripts')
    {{-- table pagination --}}
    <script src="{{asset('js/table-pagination.js')}}"></script>

    {{-- datatable --}}
    <script>

        const routes = {
            add     : "{{ route('admin.structure.position.store') }}",
            edit    : "{{ route('admin.structure.position.edit', ['id' => ':id']) }}",
            show    : "{{ route('admin.structure.position.edit', ['id' => ':id']) }}",
            track   : "{{ route('admin.structure.position.track', ['id' => ':id']) }}",
            delete  : "{{ route('admin.structure.position.delete', ['id' => ':id']) }}"
        };

        const userPerms = @json($userPerms);
        const menu = @json($menu);
        // $(function() {
            let currentSortBy = null;
            let currentSortDir = 'asc';
            let dataToDelete = null;
            const lengthHead = @json($columns);

            // load table first
            loadTable(1); 

            // Ketika user mengetik atau klik tombol filter
            $('#filter-role').on('change keyup', function() {
                loadTable(1);
            });

            // Event ketika user klik "Apply"
            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
                // Set tampilan ke input
                let start = picker.startDate.format('YYYY-MM-DD');
                let end = picker.endDate.format('YYYY-MM-DD');

                loadTable(page = 1, start= start , end = end); 
            });

            // Event ketika user klik "Clear"
            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                loadTable(1);
            });

            // find button di klik
            $('#find').on('click', function() {
                loadTable(1);
            });

            // reset filter
            $('#reset-filter').on('click', function() {
                $('#search-table').val('');
                // $('#filter-role').val('');
                $('#filter-date').val('');
                loadTable(1);
            });

            //Fungsi utama load data
            function loadTable(page = 1, start= '' , end = '') {
                $.ajax({
                    url: "{{ route('admin.structure.position.data') }}",
                    type: "GET",
                    data: {
                        search      : $('#search-table').val(),
                        role        : $('#filter-role').val(),
                        date_start  : start,
                        date_end    : end,
                        sort_by     : currentSortBy,
                        sort_dir    : currentSortDir,
                        page        : page
                    },
                    beforeSend: function() {
                        $('#table-body').html(`
                            <tr>
                                <td colspan="${lengthHead.length + 3}" class="text-center py-6 text-gray-500">
                                    @include('App.Notif.loading-grow')
                                </td>
                            </tr>
                        `);
                    },
                    success: function(res) {
                        renderTable(res.data, res.pagination);
                        renderPagination(res.pagination);
                        renderPaginationInfo(res.pagination);
                    },
                    error: function(err) {
                        $('#table-body').html(`
                            <tr>
                                <td colspan="${lengthHead.length + 3}" class="text-center text-red-500">
                                    @include('App.Notif.error')
                                </td>
                            </tr
                        `);
                    }
                });
            }

            //Render data ke tabel
            function renderTable(data, pagination) {
                let html = '';
                if (data && data.length === 0) {
                    html = `
                        <tr>
                            <td colspan="${lengthHead.length + 3}" class="text-center py-6 text-gray-500">
                                @include('App.Notif.nodata')
                            </td>
                        </tr>
                    `;
                    
                } else {
                    const startNo = (pagination.current_page - 1) * pagination.per_page + 1;
                    data.forEach((item, i) => {

                        html += `
                        <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors">
                            <td class="px-6 py-4 text-left border-r border-gray-200">
                                <input type="checkbox" value="${item.id}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 border-r border-gray-200">
                                <span class="text-sm font-semibold text-gray-900">${ startNo + i}</span>
                            </td>
                            <td class="px-6 py-4 border-r border-gray-200">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                    ${item.name}
                                </span>
                            </td>

                            <td class="px-6 py-4 border-r border-gray-200">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    ${item.level}
                                </span>
                            </td>

                            <td class="px-6 py-4 border-r border-gray-200">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    ${item.structure}
                                </span>
                            </td>
                
                            ${item.created_at}
                            ${item.updated_at}
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    <div class="action-dropdown relative inline-block">
                                        <button 
                                            data-id="${item.id}" 
                                            class="toggleMenuAction inline-flex items-center justify-center w-9 h-9 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        
                                        <div class="dropdown-menu hidden absolute left-1/2 -translate-x-1/2 w-52 bg-white rounded-xl shadow-xl border border-gray-200 z-[9999] overflow-hidden">
                                            ${generateDropdown(item)}
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>`;
                    });
                }

                $('#table-body').html(html);

                function generateDropdown(item) {
                    let dropdownHtml = '';
                    if(userPerms.includes(`view-${menu}`)){
                        dropdownHtml += `
                        <a href='#' data-id="${item.id}" data-type="show" class="btn-action flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-eye text-blue-600 text-xs"></i>
                            </div>
                            <span class="font-medium">View Details</span>
                        </a>`;
                    }

                    if(userPerms.includes(`edit-${menu}`)){
                        dropdownHtml += `
                        <a href='#' data-id="${item.id}" data-type="edit" class="btn-action flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-yellow-50 transition-colors">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-edit text-yellow-600 text-xs"></i>
                            </div>
                            <span class="font-medium">Edit Data</span>
                        </a>`;
                    }

                    if(userPerms.includes(`delete-${menu}`)){
                        dropdownHtml += `
                        <div class="border-t border-gray-200 my-1"></div>
                        <button data-id="${item.id}" data-type="delete" class="btn-action w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-trash text-red-600 text-xs"></i>
                            </div>
                            <span class="font-medium">Delete Data</span>
                        </button>`;
                    }
                    return dropdownHtml;
                }
            }

            // Event klik pagination
            $(document).on('click', '#pagination button', function() {
                const page = $(this).data('page');
                loadTable(page);
            });

            // on click add data
            $('#formModal').submit(function(e) {
                e.preventDefault();
                let url     = $(this).data('url');
                let method  = $(this).data('type') == 'add' ?  "POST" : "PUT";
                let data    = $('#formModal').serialize();

                sendData( url, method, data);
            });

            // on click action 
            $(document).on('click', '.btn-action', function(e) {
                e.preventDefault();
                const   idx  = $(this).data('id');
                let     type = $(this).data('type');
                if(type == 'delete'){
                    deleteData(idx);
                }else if(type =='perms'){
                    window.location.href = routes[type].replace(':id', idx);
                }else{
                    openModal(idx, type);
                }
            });

            // modal action
            function openModal(idx, type) {
                document.querySelectorAll(".dropdown-menu").forEach(menu => menu.classList.add("hidden"));
                const overlay = document.getElementById('modalOverlay');
                const container = document.getElementById('modalContainer');
                
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');

                setTimeout(() => {
                    container.classList.remove('scale-95', 'opacity-0');
                    container.classList.add('scale-100', 'opacity-100');
                }, 10);

                // modal add
                if(idx == null && type =='add'){
                    const icon = $('#modal-title-icon');
                    $('#modal-title').text('Add New Data');
                    $('#modal-title-bg').removeClass('bg-yellow-100 bg-green-100').addClass('bg-blue-100');
                    icon.removeClass().addClass('fas fa-plus text-blue-600');

                    $('#name').val('');
                    $('#level').val('').change();
                    $('#structure').val('').change();

                    $('#form-track').addClass('hidden');
                    $('#form-body').removeClass('hidden');

                    //disabled
                    $('#name').prop('disabled', false);
                    $('#level').prop('disabled', false);
                    $('#structure').prop('disabled', false);

                    $('#modal-footer').removeClass('hidden');
                    $('#btn-submit').removeClass('bg-yellow-600 hover:bg-yellow-700').addClass('bg-blue-600 hover:bg-blue-700');
                
                    // form Modal
                    $('#formModal').data('type', 'add');
                    $('#formModal').data('url',  routes['add']);

                }else{
                    $.ajax({
                        url: routes[type].replace(':id', idx),
                        type: "GET",
                        beforeSend: function() {
                            $('#form-body').addClass('hidden');
                            $('#form-loading').removeClass('hidden');
                        },
                        success: function(res) {
                            $('#form-body').removeClass('hidden');
                            $('#form-loading').addClass('hidden');
                            const icon = $('#modal-title-icon');
                            if(type == 'edit'){
                                $('#modal-title').text('Edit Data');
                                $('#modal-title-bg').removeClass('bg-blue-100').addClass('bg-yellow-100');
                                icon.removeClass().addClass('fas fa-edit text-yellow-600');
                                $('#form-body').removeClass('hidden');

                                // form modal
                                $('#name').val(res.name);
                                const levelValue = level[res.level] || res.level;
                                $('#level').val(levelValue).change();
                                setTimeout(function() {

                                    const structValue = structure[res.structure] || res.structure;
                                    const structText  = res.structure_text;

                                    let data = {
                                        id: structValue,
                                        text: structText
                                    };

                                    // Set data select2 secara benar
                                    $('#structure').select2('trigger', 'select', {
                                        data: data
                                    });

                                }, 300);

                                //disabled
                                $('#name').prop('disabled', false);
                                $('#level').prop('disabled', false);
                                $('#structure').prop('disabled', false);

                                $('#form-track').addClass('hidden');
                                $('#modal-footer').removeClass('hidden');
                                $('#btn-submit').removeClass('bg-blue-600 hover:bg-blue-700').addClass('bg-yellow-600 hover:bg-yellow-700');
                                
                                // form Modal
                                let urlEdit = '{{ route('admin.structure.position.update', ['id' => ':idx']) }}'.replace(':idx', idx);
                                $('#formModal').data('type', 'edit');
                                $('#formModal').data('url', urlEdit);
                            }else if(type =='show'){
                                $('#modal-title').text('Show Data');
                                $('#modal-title-bg').removeClass('bg-yellow-100').addClass('bg-blue-100');
                                icon.removeClass().addClass('fas fa-eye text-blue-600');
                                $('#form-body').removeClass('hidden');
                                
                                // form
                                $('#name').val(res.name);
                                const levelValue = level[res.level] || res.level;
                                $('#level').val(levelValue).change();
                                // const structureValue = structure[res.structure] || res.structure;
                                // $('#structure').val(structureValue).change();

                                callStructure();
                                setTimeout(function() {

                                    const structValue = structure[res.structure] || res.structure;
                                    const structText  = res.structure_text;

                                    let data = {
                                        id: structValue,
                                        text: structText
                                    };

                                    // Set data select2 secara benar
                                    $('#structure').select2('trigger', 'select', {
                                        data: data
                                    });

                                }, 300);
                                
                                //disabled
                                $('#name').prop('disabled', false);
                                $('#level').prop('disabled', false);
                                $('#structure').prop('disabled', false);

                                // form disable
                                $('#modal-footer').addClass('hidden');
                                $('#form-track').addClass('hidden');

                                // form Modal
                                $('#formModal').data('type', 'show');
                                $('#formModal').removeData('url');
                            }else{
                                $('#modal-title').text('Show Tracking');
                                $('#modal-title-bg').removeClass('bg-yellow-100').addClass('bg-green-100');
                                icon.removeClass().addClass('fas fa-history text-green-600');
                                $('#form-body').addClass('hidden');
                                $('#form-track').removeClass('hidden');
                                $('#modal-footer').addClass('hidden');
                                $('#form-track').empty().append(res.data);
                            }

                        },
                        error: function(err) {
                            $('#form-loading').addClass('hidden');
                            $('#form-body').removeClass('hidden');
                            closeModal();
                            showAlert({
                                type: 'error',
                                title: 'Gagal!',
                                message: xhr.responseJSON.message || 'Terjadi kesalahan',
                                duration: 0
                            });
                        }
                    });
                }
            }

        // });
    </script>

    <script>
        $(document).ready(function() {
            $('#level').select2({
                placeholder: 'Select Level...',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 5 // Show search box if more than 5 items
            });

            $('#structure').select2({
                placeholder: 'Select Structure...',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 5 // Show search box if more than 5 items
            });

            callStructure();
    

            function callStructure(){
                $('#structure').select2({
                    placeholder: "select Struktur",
                    templateSelection: function (data) {
                        return data.text || data.id;
                    },
                    ajax: {
                        url: "{{ route('ajax.search.structure') }}", // URL ke controller Anda
                        dataType: 'json',
                        delay: 250, // Penundaan pencarian
                        data: function (params) {
                            return {
                                q: params.term // Mengirimkan parameter pencarian
                            };
                        },
                        processResults: function (data) {
                            var results = [];

                            // Proses data menjadi format yang diperlukan untuk select2
                            $.each(data, function(index, group) {
                                results.push({
                                    text: group.text,  // Nama grup (sub_corp)
                                    children: group.children.map(function(item) {
                                        return {
                                            id: item.id,
                                            text: item.text
                                        };
                                    })
                                });
                            });

                            return {
                                results: results
                            };
                        },
                        cache: true
                    }
                });
            }
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

