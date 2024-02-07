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
                @if(permission('customer-add'))
                <button type="button" class="btn btn-primary btn-sm" onclick="showFormModal('Add new customer', 'Save')">
                    <i class="fas fa-plus-square"></i> Add New
                </button>
                @endif
            </div>
            <hr>
            <form id="form-filter">
                <div class="row">
                    <x-form.selectbox labelName="Customer Group" name="customer_group_id" required="required" col="col-md-4 mb-3" class="selectpicker">
                        @if(!$customer_groups->isEmpty())
                            @foreach ($customer_groups as $customer_group)
                                <option value="{{ $customer_group->id }}">{{ $customer_group->group_name }}</option>
                            @endforeach
                        @endif
                    </x-form.selectbox>
                    <div class="col-md-4">
                        <label for="name" class="mb-2">Customer Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter name">
                    </div>
                    <div class="col-md-4">
                        <label for="phone" class="mb-2">Customer Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter phone">
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="mb-2">Customer Email</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Enter email">
                    </div>
                    <div class="col-md-4 pt-24">
                        <button type="button" class="btn btn-danger btn-sm" id="btn-reset"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Reset Data"><i
                                class="fas fa-redo-alt"></i></button>
                        <button type="button" class="btn btn-primary btn-sm me-2" id="btn-filter"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Filter Data"><i
                                class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>

            <table id="dataTable" class="table table-stripped table-bordered table-hover">
                <thead class="bg-primary">
                    <tr>
                        @if(permission('customer-bulk-delete'))
                        <th>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="select_all"
                                    onchange="select_all()">
                                <label for="" class="custom-control-label" id="select_all"></label>
                            </div>
                        </th>
                        @endif
                        <th>SL</th>
                        <th>Customer Group</th>
                        <th>Name</th>
                        <th>Company Name</th>
                        <th>Tax Number</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Poostal Code</th>
                        <th>Country</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- supplier add/edit modal start -->
@if(permission('customer-edit') || permission('customer-add'))
@include('customer::modal')
@endif
<!-- supplier add/edit modal end -->

<!-- supplier view modal start -->
@if(permission('customer-view'))
@include('customer::view-modal')
@endif
<!-- supplier view modal end -->
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        var table;
        table = $('#dataTable').DataTable({
            "processing": true, // control processing indicator
            "serverSide": true, // serverside processing
            "order": [], // initial no order
            "responsive": true, // responsive true
            "bInfo": true, // to show total number of data
            "bFilter": false, // hide search box
            "lengthMenu": [
                [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
                [5, 10, 15, 25, 50, 100, 1000, 10000, "All"],
            ],
            "pageLength": 25, // per page data,
            "language": {
                processing: '<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i>',
                emptyTable: '<strong class="text-danger">No data found</strong>',
                infoEmpty: '',
                zeroRecords: '<strong class="text-danger">No data found</strong>'
            },
            "ajax": {
                "url": "{{ route('customer.datatable.data') }}",
                "type": "POST",
                "data": function (data) {
                    data.customer_group_id = $('#form-filter #customer_group_id').val();
                    data.name              = $('#form-filter #name').val();
                    data.phone             = $('#form-filter #phone').val();
                    data.email             = $('#form-filter #email').val();
                    data._token = _token;
                }
            },
            "columnDefs": [{
                    @if(permission('customer-bulk-delete'))
                    "targets": [0, 14],
                    @else "targets": [13],
                    @endif "orderable": false,
                    "className": "text-center"
                },
                {
                    @if(permission('customer-bulk-delete'))
                    "targets": [1,2,4,5,6,7,8,9,10,11,12],
                    @else "targets": [0,1,3,4,5,6,7,8,9,10,11],
                    @endif "className": "text-center"
                }
            ],
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
            "buttons": [
                @if(permission('customer-report')) {
                    "extend": "colvis",
                    "className": "btn btn-secondary btn-sm text-white",
                    "text": "Column"
                },
                {
                    "extend": "print",
                    "text": "Print",
                    "className": "btn btn-secondary btn-sm text-white float-end",
                    "title": "Customer List",
                    "orientation": "landscape",
                    "pageSize": "A4",
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    },
                    customize: function (win) {
                        $(win.document.body).addClass('bg-white');
                    }
                },
                {
                    "extend": "csv",
                    "text": "CSV",
                    "className": "btn btn-secondary btn-sm text-white",
                    "title": "Customer List",
                    "filename": "customer-list",
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    },
                },
                {
                    "extend": "excel",
                    "text": "Excel",
                    "className": "btn btn-secondary btn-sm text-white",
                    "title": "Customer List",
                    "filename": "customer-list",
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    },
                },
                {
                    "extend": "pdf",
                    "text": "PDF",
                    "className": "btn btn-secondary btn-sm text-white",
                    "title": "Customer List",
                    "filename": "customer-list",
                    "orientation": "landscape",
                    "exportOptions": {
                        columns: [1, 2, 3]
                    },
                },
                @endif
                @if(permission('customer-bulk-delete')) {
                    "className": "btn btn-danger btn-sm delete_btn d-none text-white",
                    "text": "Delete",
                    action: function (e, dt, node, config) {
                        multi_delete();
                    }
                }
                @endif
            ]
        });

        // if user search
        $('#btn-filter').click(function () {
            table.ajax.reload();
        });

        // if user reset
        $('#btn-reset').click(function () {
            $('#form-filter')[0].reset();
            table.ajax.reload();
        });

        // if user add new menu
        $(document).on('click', '#save-btn', function () {
            let form = document.getElementById('store_or_update_form');
            let formData = new FormData(form);
            let url = "{{ route('customer.store.or.update') }}";
            let id = $('#update_id').val();
            let method;
            if (id) {
                method = 'update';
            } else {
                method = 'add';
            }
            store_or_update_data(table, method, url, formData);
        });

        // edit menu data
        $(document).on('click', '.edit_data', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            $('#store_or_update_form')[0].reset();
            $('#store_or_update_form #update_id').val('');
            $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
            $('#store_or_update_form').find('.error').remove();
            $('#store_or_update_form .selectpicker').selectpicker('refresh');
            $('#store_or_update_form table tbody').find('tr:gt(0)').remove();
            if (id) {
                $.ajax({
                    url: "{{ route('customer.edit') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: _token
                    },
                    dataType: "JSON",
                    success: function (data) {
                        $('#store_or_update_form #update_id').val(data.id);
                        $('#store_or_update_form #customer_group_id').val(data.customer_group_id);
                        $('#store_or_update_form #name').val(data.name);
                        $('#store_or_update_form #company_name').val(data.company_name);
                        $('#store_or_update_form #tax_number').val(data.tax_number);
                        $('#store_or_update_form #phone').val(data.phone);
                        $('#store_or_update_form #email').val(data.email);
                        $('#store_or_update_form #address').val(data.address);
                        $('#store_or_update_form #city').val(data.city);
                        $('#store_or_update_form #state').val(data.state);
                        $('#store_or_update_form #postal_code').val(data.postal_code);
                        $('#store_or_update_form #country').val(data.country);

                        myModal = new bootstrap.Modal(document.getElementById(
                            'store_or_update_modal'), {
                            keyboard: false,
                            backdrop: 'static'
                        });

                        myModal.show();

                        $("#store_or_update_modal .modal-title").html(
                            '<i class="fas fa-edit"></i> ' + data.name);
                        $("#store_or_update_modal #save-btn").text("Update");
                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr
                            .responseText);
                    }
                });
            }
        });

        //  delete data
        $(document).on('click', '.delete_data', function (e) {
            e.preventDefault();
            let id   = $(this).data('id');
            let name = $(this).data('name');
            let row  = table.row($(this).parent('tr'));
            let url  = "{{ route('customer.delete') }}";
            delete_data(id, url, table, row, name);
        });


        // bulk delete menu
        function multi_delete() {
            let ids = [];
            let rows;
            $('.select_data:checked').each(function () {
                ids.push($(this).val());
                rows = table.rows($('.select_data:checked').parents('tr'));
            });
            if (ids.length == 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error',
                    text: "Please checked at least one row if the table!",
                    icon: "warning"
                });
            } else {
                let url = "{{ route('customer.bulk.delete') }}";
                bulk_delete(ids, url, table, rows);
            }
        }

        // change status
        $(document).on('click', '.change_status', function () {
            let id = $(this).data('id');
            let status = $(this).data('status');
            let name = $(this).data('name');
            let row = table.row($(this).parent('tr'));
            let url = "{{ route('customer.change.status') }}";
            Swal.fire({
                title: 'Are you sure to change ' + name + ' status?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            id: id,
                            status: status,
                            _token: _token
                        },
                        dataType: "JSON",

                    }).done(function (response) {
                        if (response.status == 'success') {
                            Swal.fire("Status Changed!", response.message, "success")
                                .then(function () {
                                    table.row(row).remove().draw(false);
                                });
                        }
                        if (response.status == 'error') {
                            Swal.fire('Opps...', "Something went wrong!", "error");
                        }
                    }).fail(function () {
                        Swal.fire('Opps...', "Something went wrong with ajax!",
                        "error");
                    });
                }
            })
        });

        // view data
        $(document).on('click', '.view_data', function(e){
            e.preventDefault();
            let id = $(this).data('id');
            if(id){
                $.ajax({
                    url: "{{ route('customer.show') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: _token
                    },
                    success : function(data){
                        $('#view_modal .details').html('');
                        $('#view_modal .details').html(data);

                        let viewModal = new bootstrap.Modal(document.getElementById(
                            'view_modal'), {
                            keyboard: false,
                            backdrop: 'static'
                        });

                        viewModal.show();
                        $('#view_modal .modal-title').html(
                        '<i class="fas fa-eye"></i> <span>Supplier Details</span>');
                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
                });
            }
        });
    });
</script>
@endpush
