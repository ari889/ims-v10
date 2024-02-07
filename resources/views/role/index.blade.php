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
                @if(permission('role-add'))
                    <a href="{{ route('role.create') }}" type="button" class="btn btn-primary btn-sm" onclick="showFormModal('Add new role', 'Save')">
                        <i class="fas fa-plus-square"></i> Add New
                    </a>
                @endif
            </div>
            <hr>
            <form id="form-filter">
                <div class="row">
                    <div class="col-md-4">
                        <label for="role_name" class="mb-2">Menu Name</label>
                        <input type="text" name="role_name" id="role_name" class="form-control" placeholder="Enter Role Name">
                    </div>
                    <div class="col-md-8 clearfix pt-24">
                        <button type="button" class="btn btn-danger btn-sm float-end" id="btn-reset" data-bs-toggle="tooltip" data-bs-placement="top" title="Reset Data"><i class="fas fa-redo-alt"></i></button>
                        <button type="button" class="btn btn-primary btn-sm float-end me-2" id="btn-filter" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter Data"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>

            <table id="dataTable" class="table table-stripped table-bordered table-hover">
                <thead class="bg-primary">
                    <tr>
                        @if(permission('role-bulk-delete'))
                            <th>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                    <label for="" class="custom-control-label" id="select_all"></label>
                                </div>
                        </th>
                        @endif
                        <th>SL</th>
                        <th>Role Name</th>
                        <th>Datatable</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

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
                    "url" : "{{ route('role.datatable.data') }}",
                    "type" : "POST",
                    "data" : function(data){
                        data.role_name = $('#form-filter #role_name').val();
                        data._token    = _token;
                    }
                },
                "columnDefs" : [
                    {
                        @if(permission('role-bulk-delete'))
                        "targets" : [0, 4],
                        @else
                        "targets" : [3],
                        @endif
                        "orderable" : false,
                        "className" : "text-center"
                    },
                    {
                        @if(permission('role-bulk-delete'))
                        "targets" : [1, 3],
                        @else
                        "targets" : [0, 2],
                        @endif
                        "className" : "text-center"
                    }
                ],
                "dom" : "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
                "buttons" : [
                    @if(permission('role-report'))
                    {
                        "extend" : "colvis",
                        "className" : "btn btn-secondary btn-sm text-white",
                        "text" : "Column"
                    },
                    {
                        "extend" : "print",
                        "text" : "Print",
                        "className" : "btn btn-secondary btn-sm text-white float-end",
                        "title" : "Role List",
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
                        "title" : "Role List",
                        "filename" : "role-list",
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
                        "title" : "Role List",
                        "filename" : "role-list",
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
                        "title" : "Role List",
                        "filename" : "role-list",
                        "orientation" : "landscape",
                        "exportOptions" : {
                            columns : [1, 2, 3]
                        },
                    },
                    @endif
                    @if(permission('role-bulk-delete'))
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

            //  delete data
            $(document).on('click', '.delete_data', function(e){
                e.preventDefault();
                let id = $(this).data('id');
                let name = $(this).data('name');
                let row = table.row($(this).parent('tr'));
                let url = "{{ route('role.delete') }}";
                delete_data(id, url, table, row, name);
            });
            

            // bulk delete role
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
                    let url = "{{ route('role.bulk.delete') }}";
                    bulk_delete(ids, url, table, rows);
                }
            }
        });
    </script>
@endpush
