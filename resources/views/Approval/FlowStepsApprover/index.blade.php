@extends('App.Layout.index')

@section('title')
    approval-flow-steps-approver
@endsection

@section('content')
    <main class="p-6">

        {{-- breadcrumb --}}
        @include('App.Partials.breadcrumb', [
            'fields' => [
                'icon' => 'fas fa-coeg',
                'parent' => 'Approval',
                'child1' => 'Flow',
                'child1_href' => 'admin.setting.approval.flow.index',
                'child2' => 'Steps',
                'child2_href' => 'admin.setting.approval.flow.steps.index',
                'child3' => 'Approver',
            ]
        ])

        @php
            $user = Auth::user();
            $re_role = App\Models\Auth\Role::find($user->role_id ?? 26);
            $userPerms = json_decode($re_role->permission ?? '[]', true);
            $menu = 'setting-approval';
        @endphp

        <!-- Page Header with Title and Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-4">
            <div class="flex flex-col lg:flex-row md:items-left md:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Approval Flow Steps Approver</h2>
                    <p class="text-sm text-gray-500 mt-1">Manage and view all approval flow steps approver in the system</p>
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
                    ['field' => 'module', 'label' => 'Module', 'sortable' => true],
                    ['field' => 'step_order',  'label' => 'Step Order', 'sortable' => true],
                    ['field' => 'approval_by',  'label' => 'Approval By', 'sortable' => false],
                    ['field' => 'approver',  'label' => 'Approver', 'sortable' => false],
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
    @include('Approval.FlowStepsApprover.modal')
    @include('App.Partials.delete-modal')

    {{-- @php
        $parent = App\Models\Master\Structure::where('level', 'direksi')->get();
    @endphp --}}

    
@endsection


@push('custom-scripts')
    {{-- table pagination --}}
    <script src="{{asset('js/table-pagination.js')}}"></script>

    {{-- datatable --}}
    <script>

        const routes = {
            add     : "{{ route('admin.setting.approval.flow.steps.approver.store') }}",
            edit    : "{{ route('admin.setting.approval.flow.steps.approver.edit', ['id' => ':id']) }}",
            show    : "{{ route('admin.setting.approval.flow.steps.approver.edit', ['id' => ':id']) }}",
            delete  : "{{ route('admin.setting.approval.flow.steps.approver.delete', ['id' => ':id']) }}",
        };

        const idData = @json($id);
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
                let url_get = '{{ route('admin.setting.approval.flow.steps.approver.data', ['id' => ':idData']) }}'.replace(':idData', idData);
                $.ajax({
                    url: url_get,
                    type: "GET",
                    data: {
                        id          : idData,
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
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    ${item.module}
                                </span>
                            </td>

                            <td class="px-6 py-4 border-r border-gray-200">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    ${item.step_order}
                                </span>
                            </td>

                            <td class="px-6 py-4 border-r border-gray-200">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    ${item.approver_type}
                                </span>
                            </td>

                            <td class="px-6 py-4 border-r border-gray-200">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white text-gray-700">
                                    ${item.approver_value}
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
                        </a>

                        
                        `;
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
                }else if(type =='assign'){
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

                    // $('#step_order').val('');
                    $('#approval_type').val('').change();

                    $('#form-track').addClass('hidden');
                    $('#form-body').removeClass('hidden');

                    //disabled 
                    $('#approval_type').prop('disabled', false);
                    $('#approval_by').prop('disabled', false);
                    // $('#approver_value').prop('disabled', false);


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

                                const approvalType = res.approval_type;
                                $('#approval_type').val(approvalType).change();

                                $('#approver_by_user').addClass('hidden');
                                $('#approver_by_role').addClass('hidden');
                                $('#approver_by_structure').addClass('hidden');
                                if(approvalType == 'user'){
                                    $('#approver_by_user').removeClass('hidden');
                                    $('#approver_by_user').val(res.approver_value).change();
                                }else if(approvalType == 'role'){
                                    $('#approver_by_role').removeClass('hidden');
                                    $('#approver_by_role').val(res.approver_value).change();
                                }else{
                                    $('#approver_by_structure').removeClass('hidden');
                                    $('#approver_by_structure').val(res.approver_value).change();
                                }
                                // $('#approver_value').val(res.approver_value);

                                //disabled
                                $('#step_order').prop('disabled', false);
                                $('#min_approve').prop('disabled', false);
                                $('#approval_type').prop('disabled', false);

                                // $('#approval_by').prop('disabled', false);
                                // $('#approver_value').prop('disabled', false);
                                $('#form-track').addClass('hidden');
                                $('#modal-footer').removeClass('hidden');
                                $('#btn-submit').removeClass('bg-blue-600 hover:bg-blue-700').addClass('bg-yellow-600 hover:bg-yellow-700');
                                
                                // form Modal
                                let urlEdit = '{{ route('admin.setting.approval.flow.steps.approver.update', ['id' => ':idx']) }}'.replace(':idx', idx);
                                $('#formModal').data('type', 'edit');
                                $('#formModal').data('url', urlEdit);
                            }else if(type =='show'){
                                $('#modal-title').text('Show Data');
                                $('#modal-title-bg').removeClass('bg-yellow-100').addClass('bg-blue-100');
                                icon.removeClass().addClass('fas fa-eye text-blue-600');
                                $('#form-body').removeClass('hidden');

                                //disabled
                                const approvalType = res.approval_type;
                                $('#approval_type').val(approvalType).change();

                                $('#approver_by_user').addClass('hidden');
                                $('#approver_by_role').addClass('hidden');
                                $('#approver_by_structure').addClass('hidden');
                                if(approvalType == 'user'){
                                    $('#approver_by_user').removeClass('hidden');
                                    $('#approver_by_user').val(res.approver_value).change();
                                }else if(approvalType == 'role'){
                                    $('#approver_by_role').removeClass('hidden');
                                    $('#approver_by_role').val(res.approver_value).change();
                                }else{
                                    $('#approver_by_structure').removeClass('hidden');
                                    $('#approver_by_structure').val(res.approver_value).change();
                                }
                                // $('#approver_value').val(res.approver_value);

                                //disabled
                                $('#step_order').prop('disabled', false);
                                $('#min_approve').prop('disabled', false);
                                $('#approval_type').prop('disabled', false);

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
            $('#approval_type').select2({
                placeholder: 'Select Approval Type...',
                allowClear: true,
                width: '100%',
                // templateResult: formatColor,
                // templateSelection: formatColor,
                minimumResultsForSearch: 5 // Show search box if more than 5 items
            });

            $('#approver_by_user').select2({
                placeholder: 'Select User Approval...',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 5 // Show search box if more than 5 items
            });

            $('#approver_by_role').select2({
                placeholder: 'Select User Approval...',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 5 // Show search box if more than 5 items
            });

            $('#approver_by_structure').select2({
                placeholder: 'Select User Approval...',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 5 // Show search box if more than 5 items
            });

            $('#approval_type').on('change', function(){
                const approvalBy = $(this).val();

                if(approvalBy == 'user'){
                    $('#approver_user').prop('disabled', false);
                    $('#approver_user').removeClass('hidden');

                    $('#approver_role').addClass('hidden');
                    $('#approver_structure').addClass('hidden');
                }else if(approvalBy == 'role'){
                    $('#approver_role').prop('disabled', true);
                    $('#approver_role').removeClass('hidden');

                    $('#approver_user').addClass('hidden');
                    $('#approver_structure').addClass('hidden');
                }else{
                    $('#approver_structure').prop('disabled', true);
                    $('#approver_structure').removeClass('hidden');

                    $('#approver_role').addClass('hidden');
                    $('#approver_user').addClass('hidden');
                }
            });
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

