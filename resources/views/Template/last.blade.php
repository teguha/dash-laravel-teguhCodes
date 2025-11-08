@extends('Dashboard.home.index')

@section('title')
    role-data
@endsection

@section('icon')
    <i class="fa fa-key text-danger"></i>
@endsection

@section('header-content')
    Role
@endsection

@section('header-desc')
    In this page you can manage data roles
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="col-lg-12">
    @include('Dashboard.partials.sweet-alert')
    
    <div class="card shadow-sm">
        <div class="card-body">
            
            @include('Dashboard.partials.loading');

            <div class="row g-2 align-items-end mb-3">
                
                {{-- filter --}}
                @include('Dashboard.partials.filter', [
                    'formId' => 'roleFilter',
                    'fields' => [
                        'search' => true,
                        'role'   => true,
                        'date'   => true,
                    ]
                ]);

                <!-- Tombol Aksi Tambahan -->
                @include('Dashboard.partials.action-buttons', [
                    'createRoute' => 'admin.setting.role.create',
                    'excel' => true,
                    'pdf' => true
                ]);

            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered" id="data-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all" /></th>
                            <th>#</th>
                            <th>Role</th>
                            <th>Permission</th>
                            <th>Created_at</th>
                            <th>Updated_at</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection


@push('custom-scripts')
    <script src="{{ asset('template/assets/js/helper/checkbox.js') }}"></script>
    <script src="{{ asset('template/assets/js/helper/delete.js') }}"></script>

    <script>
        $(function () {
            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false, // matikan search bawaan
                ajax: {
                    url: "{{ route('admin.setting.role.data') }}",
                    data: function (d) {
                        let form = $('#roleFilter'); // atau variabel dinamis
                        d.search = form.find('#search-table').val();
                        d.roles  = form.find('#filter-role').val();
                        d.date   = form.find('#filter-date').val();
                    }
                },

                columns: [
                    {
                        data: 'id',
                        render: function (data) {
                            return '<input type="checkbox" class="row-checkbox" value="'+data+'">';
                        },
                        orderable: false,
                        searchable: false
                    },
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name', orderable: true, searchable: true},
                    {data: 'permission', name: 'permission'},
                    {data: 'created_at', name: 'created_at', orderable: true, searchable: true},
                    {data: 'updated_at', name: 'updated_at', orderable: true, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'}
                ],
                lengthMenu: [ [5, 10, 20, 50], [5, 10, 20, 50] ],
                pageLength: 5 
            });

            // Filter apply
            $('#find').on('click', function () {
                table.ajax.reload();
            });

            // Reset filter
            $('#reset-filter').on('click', function () {
                $('#roleFilter')[0].reset();
                $('#filter-role').val('').trigger('change');
                table.ajax.reload();
            }); 
        });



        function exportExcel() {
            const checkedBoxes = document.querySelectorAll(".row-checkbox:checked");
            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            if (ids.length === 0) {
                alert("Tidak ada data yang dipilih!");
                return;
            }

            // Untuk contoh, kita alert dulu
            alert("ID yang dipilih: " + ids.join(", "));
        }

        function exportPDF() {

            const chakedBoxes = document.querySelectorAll(".row-checkbox:checked");
            const ids = Array.from(chakedBoxes).map(cb => cb.value);

            if(ids.length === 0){
                alert("Tidak ada data yang dipilih!");
                return;
            }
            alert("ID yang dipilih: " + ids.join(","));
        }        
    </script>

    <script>
        $(document).on('click', '.btn-show-detail', function () {
            const id = $(this).data('id');
            const url = `/admin/setting-role/show/${id}`; // Ganti sesuai endpoint detail-mu

            $('#modal-detail-content').html('<div class="text-center"><span class="spinner-border spinner-border-sm"></span> Loading...</div>');
            
            $.ajax({
                url: url,
                method: 'GET',
                success: function (res) {
                    let perms = JSON.parse(res.permission ?? '[]');
                    let count = perms.length;
                    $('#modal-detail-content').html(`
                        <form>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-user"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="role-show" placeholder="Role" value=${res.name} readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="perms">Permission</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fa fa-key"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="perms-show" placeholder="Permission" value=${count} readonly>
                                </div>
                            </div>
                        </form>
                        
                    `);
                },
                error: function () {
                    $('#modal-detail-content').html('<div class="alert alert-danger">Gagal memuat data.</div>');
                    $('#showModal').modal('show'); // Tetap tampilkan modal jika ingin info error
                }
            });
        });
    </script>
@endpush

@include('Dashboard.auth.role.show');