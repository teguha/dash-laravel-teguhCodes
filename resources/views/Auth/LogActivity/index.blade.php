@extends('App.Layout.index')

@section('title')
    log-ativity-data
@endsection

@section('content')
    <main class="p-6">

        {{-- breadcrumb --}}
        @include('App.Partials.breadcrumb', [
            'fields' => [
                'icon' => 'fas fa-cog',
                'parent' => 'Setting',
                'child1' => 'Log Activity',
                'child2' => ''
            ]
        ])

        <!-- Page Header with Title and Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-4">
            <div class="flex flex-col lg:flex-row md:items-left md:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Log Activity</h2>
                    <p class="text-sm text-gray-500 mt-1">Manage and view all permission in the system</p>
                    {{-- <button class="text-sm text-gray-500 mt-2"><i class="fa fas fa-download text-[14px] w-2 mr-4"></i>Format Excel Example</button> --}}
                </div>

                @include('App.Partials.action', [
                    'fields' => [
                        'add' => false,
                        'export' => false,
                        'import' => true,
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
                    ['field' => 'user', 'label' => 'User', 'sortable' => true],
                    ['field' => 'action', 'label' => 'Action', 'sortable' => true],
                    ['field' => 'menu', 'label' => 'Menu', 'sortable' => false],
                    ['field' => 'before', 'label' => 'Data', 'sortable' => false],
                    ['field' => 'after', 'label' => 'Update', 'sortable' => false],
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
    @include('Auth.LogActivity.modal')
    @include('App.Partials.delete-modal')
@endsection


@push('custom-scripts')
    {{-- table pagination --}}
    <script src="{{asset('js/table-pagination.js')}}"></script>

    {{-- datatable --}}
    <script>

        const routes = {
            // add     : "{{ route('admin.setting.permission.store') }}",
            // edit    : "{{ route('admin.setting.permission.edit', ['id' => ':id']) }}",
            show    : "{{ route('admin.setting.log.show', ['id' => ':id']) }}",
            // track   : "{{ route('admin.setting.permission.track', ['id' => ':id']) }}",
            // delete  : "{{ route('admin.setting.permission.delete', ['id' => ':id']) }}"
        };

        // $(function() {
            let currentSortBy = null;
            let currentSortDir = 'desc';
            let dataToDelete = null;
            const lengthHead = @json($columns);

            // load table first
            loadTable(1); 

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
                    url: "{{ route('admin.setting.log.data') }}",
                    type: "GET",
                    data: {
                        search      : $('#search-table').val(),
                        // role        : $('#filter-role').val(),
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
                                <div class="flex items-center space-x-1">
                                    <!-- Initial berbentuk lingkaran -->
                                    <span class="flex items-center justify-center w-8 h-8 text-[12px] rounded-full bg-${item.user_color}-500 text-white font-semibold">
                                        ${item.initial}
                                    </span>

                                    <!-- Nama -->
                                    <span class="inline-flex items-center px-3 py-1 font-[arial] tracking-normal rounded-full text-xs font-semibold  text-gray-600">
                                        ${item.user}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 border-r border-gray-200">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-${item.color}-100 text-${item.color}-700">
                                    ${item.action}
                                </span>
                            </td>
                            <td class="px-6 py-4 border-r border-gray-200">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 ">
                                    ${item.menu}
                                </span>
                            </td>
                            <td class="px-6 py-4 border-r border-gray-200">
                            ${
                                item.last_data != '' ? `<i class="fa fa-file text-md ${item.action === 'Delete' ? 'text-red-500' : 'text-yellow-500' }"></i>` : `-`
                            }
                            </td>
                           
                            <td class="px-6 py-4 border-r border-gray-200">
                            ${
                                item.update != '' ? `<i class="fa fa-file text-md ${item.action === 'Create' ? 'text-blue-500' : 'text-green-500' } "></i>` : `-`
                            }
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
                                            <a href='#' data-id="${item.id}" data-type="show" class="btn-action flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition-colors">
                                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-eye text-blue-600 text-xs"></i>
                                                </div>
                                                <span class="font-medium">View Details</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>`;
                    });

                }
                $('#table-body').html(html);
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

                    openModal(idx, type);
                
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
                        
                        $('#modal-title').text('Show Data');
                        $('#modal-title-bg').removeClass('bg-yellow-100').addClass('bg-blue-100');
                        icon.removeClass().addClass('fas fa-eye text-blue-600');
                        $('#form-body').removeClass('hidden');
                        
                            // form modal
                        $('#user').val(res.user);
                        $('#action').val(res.action);
                        $('#menu').val(res.menu);
                        $('#before').val(JSON.stringify(res.before, null, 2));
                        $('#after').val(JSON.stringify(res.after, null, 2));


                        //disabled
                        $('#user').prop('disabled', true);
                        $('#action').prop('disabled', true);
                        $('#menu').prop('disabled', true);

                        $('#modal-footer').addClass('hidden');
                        $('#form-track').addClass('hidden');

                        // form Modal
                        $('#formModal').data('type', 'show');
                        $('#formModal').removeData('url');


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

        // });
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

