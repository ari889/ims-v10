@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">{{ config('settings.title') }}</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row justify-content-between align-items-center">
                <h5 class="card-title"><i class="{{ $page_icon }} text-primary"></i> {{ $page_title }}</h5>
                @if(permission('permission-add'))
                <button type="button" class="btn btn-primary btn-sm" onclick="showFormModal('Add new menu', 'Save')">
                    <i class="fas fa-plus-square"></i> Add New
                </button>
                @endif
            </div>
            <hr>
            <form id="form-filter">
                <div class="row">
                    <div class="col-md-4">
                        <label for="name" class="mb-2">Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter permission name">
                    </div>
                    <div class="col-md-4">
                        <label for="module_id" class="mb-2 form-label">Module</label>
                        <select name="module_id" id="module_id" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" title="Choose one of the following">
                            <option value="">Select Please</option>
                            @if(!empty($data['modules']))
                                @foreach($data['modules'] as $key => $item)
                                    <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4 clearfix pt-24">
                        <button type="button" class="btn btn-danger btn-sm float-end" id="btn-reset" data-bs-toggle="tooltip" data-bs-placement="top" title="Reset Data"><i class="fas fa-redo-alt"></i></button>
                        <button type="button" class="btn btn-primary btn-sm float-end me-2" id="btn-filter" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter Data"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>

            <table id="dataTable" class="table table-stripped table-bordered table-hover mt-3">
                <thead class="bg-primary">
                    <tr>
                        @if(permission('permission-bulk-delete'))
                        <th>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                <label for="" class="custom-control-label" id="select_all"></label>
                            </div>
                        </th>
                        @endif
                        <th>SL</th>
                        <th>Module Name</th>
                        <th>Permission Name</th>
                        <th>Permission Slug</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- permission add modal start -->
@if(permission('permission-add'))
    @include('permission.modal')
@endif
<!-- permission add modal end -->

<!-- permission edit modal start -->
@if(permission('permission-edit'))
    @include('permission.edit-modal')
@endif
<!-- permission edit modal end -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            var table;
            table = $('#dataTable').DataTable({
                "processing" : true, // control processing indicator
                "serverSide" : true, // serverside processing
                "order" : [], // initial no order
                "responsive" : true, // responsive true
                "bInfo" : true, // to show total number of data
                "bFilter" : false, // hide search box
                "lengthMenu" : [
                    [5,10,15,25,50,100,1000,10000,-1],
                    [5,10,15,25,50,100,1000,10000,"All"],
                ],
                "pageLength" : 25, // per page data,
                "language" : {
                    processing : '<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i>',
                    emptyTable : '<strong class="text-danger">No data found</strong>',
                    infoEmpty : '',
                    zeroRecords : '<strong class="text-danger">No data found</strong>'
                },
                "ajax" : {
                    "url" : "{{ route('menu.module.permission.datatable.data') }}",
                    "type" : "POST",
                    "data" : function(data){
                        data.name = $('#form-filter #name').val();
                        data.module_id = $('#form-filter #module_id').val();
                        data._token    = _token;
                    }
                },
                "columnDefs" : [
                    {
                        @if(permission('permission-bulk-delete'))
                        "targets" : [0, 5],
                        @else
                        "targets" : [4],
                        @endif
                        "orderable" : false,
                        "className" : "text-center"
                    },
                    {
                        @if(permission('permission-bulk-delete'))
                        "targets" : [1],
                        @else
                        "targets": [0],
                        @endif
                        "className" : "text-center"
                    }
                ],
                "dom" : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
                "buttons" : [
                    @if(permission('permission-report'))
                    {
                        "extend" : "colvis",
                        "className" : "btn btn-secondary btn-sm text-white",
                        "text" : "Column"
                    },
                    {
                        "extend" : "print",
                        "text" : "Print",
                        "className" : "btn btn-secondary btn-sm text-white float-end",
                        "title" : "Permission List",
                        "orientation" : "landscape",
                        "pageSize" : "A4",
                        "exportOptions" : {
                            columns : function(index, data, node){
                                return table.column(index).visible();
                            }
                        },
                        customize : function(win){
                            $(win.document.body).addClass('bg-white');
                        }
                    },
                    {
                        "extend" : "csv",
                        "text" : "CSV",
                        "className" : "btn btn-secondary btn-sm text-white",
                        "title" : "Permission List",
                        "filename" : "permission-list",
                        "exportOptions" : {
                            columns : function(index, data, node){
                                return table.column(index).visible();
                            }
                        },
                    },
                    {
                        "extend" : "excel",
                        "text" : "Excel",
                        "className" : "btn btn-secondary btn-sm text-white",
                        "title" : "Permission List",
                        "filename" : "permission-list",
                        "exportOptions" : {
                            columns : function(index, data, node){
                                return table.column(index).visible();
                            }
                        },
                    },
                    {
                        "extend" : "pdf",
                        "text" : "PDF",
                        "className" : "btn btn-secondary btn-sm text-white",
                        "title" : "Permission List",
                        "filename" : "permission-list",
                        "orientation" : "landscape",
                        "exportOptions" : {
                            columns : [1, 2, 3, 4]
                        },
                    },
                    @endif
                    @if(permission('permission-bulk-delete'))
                    {
                        "className" : "btn btn-danger btn-sm delete_btn d-none text-white",
                        "text" : "Delete",
                        action: function(e, dt, node, config){
                            multi_delete();
                        }
                    }
                    @endif
                ]
            });
            
            // if user search
            $('#btn-filter').click(function(){
                table.ajax.reload();
            });
    
            // if user reset
            $('#btn-reset').click(function(){
                $('#form-filter')[0].reset();
                table.ajax.reload();
            });
    
            // if user add new menu
            $(document).on('click', '#save-btn', function(){
                let form = document.getElementById('store_or_update_form');
                let formData = new FormData(form);
                let url = "{{ route('menu.module.permission.store') }}";
                let id = $('#update_id').val();
                let method;
                if(id){
                    method = 'update';
                }else{
                    method = 'add';
                }
                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function () {
                        $('#save-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
                    },
                    complete: function () {
                        $('#save-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
                    },
                    success: function (data) {
                        $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                        $('#store_or_update_form').find('.error').remove();
                        if (data.status == false) {
                            $.each(data.errors, function (key, value) {
                                $('#store_or_update_form input#' + key).addClass('is-invalid');
                                $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                                $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                                $('#store_or_update_form select#' + key).parent().append(
                                    '<small class="error text-danger">' + value + '</small>'
                                )
                            });
                        } else {
                            notification(data.status, data.message);
                            if (data.status == 'success') {
                                if (method == 'update') {
                                    table.ajax.reload(null, false);
                                } else {
                                    table.ajax.reload();
                                }
                                myModal.hide();
                            }
                        }
                    }
                });
            });
    
            // edit menu data
            $(document).on('click', '.edit_data', function(e){
                e.preventDefault();
                let id = $(this).data('id');
                $.ajax({
                    url : "{{ route('menu.module.permission.edit') }}",
                    type : "POST",
                    data : {
                        id : id,
                        _token : _token
                    },
                    dataType : "JSON",
                    success: function(data){
                        $('#update_form #update_id').val(data.data.id);
                        $('#update_form #name').val(data.data.name);
                        $('#update_form #slug').val(data.data.slug);

                        myModal = new bootstrap.Modal(document.getElementById('update_modal'), {
                            keyboard: false,
                            backdrop: 'static'
                        });

                        myModal.show();

                        $("#update_modal .modal-title").html('<i class="fas fa-edit"></i> '+data.data.name);
                        $("#update_modal #update-btn").text("Update");
                    }
                });
            });
             

            // if user click update btn
            $(document).on('click', '#update-btn', function(){
                let form = document.getElementById('update_form');
                let formData = new FormData(form);
                let url = "{{ route('menu.module.permission.update') }}";
                let id = $('#update_id').val();
                let method = "update";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function () {
                        $('#update-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
                    },
                    complete: function () {
                        $('#update-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
                    },
                    success: function (data) {
                        $('#update_form').find('.is-invalid').removeClass('is-invalid');
                        $('#update_form').find('.error').remove();
                        if (data.status == false) {
                            $.each(data.errors, function (key, value) {
                                $('#update_form input#' + key).parent().addClass('is-invalid');
                                $('#update_form #' + key).parent().append(
                                    '<small class="error text-danger">' + value + '</small>'
                                )
                            });
                        } else {
                            notification(data.status, data.message);
                            if (data.status == 'success') {
                                table.ajax.reload(null, false);
                                myModal.hide();
                            }
                        }
                    }
                });
            })
            

            //  delete data
            $(document).on('click', '.delete_data', function(e){
                e.preventDefault();
                let id = $(this).data('id');
                let name = $(this).data('name');
                let row = table.row($(this).parent('tr'));
                let url = "{{ route('menu.module.permission.delete') }}";
                delete_data(id, url, table, row, name);
            });
            

            // bulk delete menu
            function multi_delete(){
                let ids = [];
                let rows;
                $('.select_data:checked').each(function(){
                    ids.push($(this).val());
                    rows = table.rows($('.select_data:checked').parents('tr'));
                });
                if(ids.length == 0){
                    Swal.fire({
                        type : 'error',
                        title : 'Error',
                        text : "Please checked at least one row if the table!",
                        icon : "warning"
                    });
                }else{
                    let url = "{{ route('menu.module.permission.bulk.delete') }}";
                    bulk_delete(ids, url, table, rows);
                }
            }

            // add new dynamic permission field
            var count = 1;
            function dynamic_permission_field(row){
                html = ` <tr>
                            <td>
                                <input type="text" name="permission[${row}][name]" id="permission_${row}_name" onkeyup="url_generator(this.value, 'permission_${row}_slug')" class="form-control" />
                            </td>
                            <td>
                                <input type="text" name="permission[${row}][slug]" id="permission_${row}_slug" class="form-control">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove_permission" data-toggle="tooltip" 
                                data-placement="top" data-original-title="Add More">
                                    <i class="fas fa-minus-square"></i>
                                </button>
                            </td>
                        </tr>`;
                $('#permission-table tbody').append(html);
            }

            // add new permission field
            $(document).on('click', '#add_permission', function(){
                count++;
                console.log(count)
                dynamic_permission_field(count);
            });

            // remove permission
            $(document).on('click', '.remove_permission', function(){
                count--;
                $(this).closest('tr').remove();
            });
        });

        // generate permission slug
        function url_generator(input_value, output_id){
            var value = input_value.toLowerCase().trim();
            var str = value.replace(/ +(?= )/g, '');
            var name = str.split(' ').join('-');
            $('#'+output_id).val(name);
        }
    </script>
@endpush
