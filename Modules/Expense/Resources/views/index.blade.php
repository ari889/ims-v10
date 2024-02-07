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
                    @if (permission('expense-add'))
                        <button type="button" class="btn btn-primary btn-sm"
                            onclick="showFormModal('Add new Expense', 'Save')">
                            <i class="fas fa-plus-square"></i> Add New
                        </button>
                    @endif
                </div>
                <hr>
                <form id="form-filter">
                    <div class="row">
                        <x-form.selectbox labelName="Expense Category" name="expense_category_id" required="required"
                            col="col-md-3" class="selectpicker">
                            @if (!$categories->isEmpty())
                                @foreach ($categories as $Expense)
                                    <option value="{{ $Expense->id }}">{{ $Expense->name }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Warehouse" name="warehouse_id" required="required" col="col-md-3"
                            class="selectpicker">
                            @if (!$warehouses->isEmpty())
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Account" name="account_id" required="required" col="col-md-3"
                            class="selectpicker">
                            @if (!$accounts->isEmpty())
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->name . ' - ' . $account->account_no }}
                                    </option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <div class="form-group col-md-3 pt-24">
                            <button type="button" class="btn btn-danger btn-sm float-right" id="btn-reset"
                                data-toggle="tooltip" data-placement="top" data-original-title="Reset Data">
                                <i class="fas fa-redo-alt"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm float-right mr-2" id="btn-filter"
                                data-toggle="tooltip" data-placement="top" data-original-title="Filter Data">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <table id="dataTable" class="table table-stripped table-bordered table-hover">
                    <thead class="bg-primary">
                        <tr>
                            @if (permission('expense-bulk-delete'))
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select_all"
                                            onchange="select_all()">
                                        <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                </th>
                            @endif
                            <th>Sl</th>
                            <th>Expense</th>
                            <th>Warehose</th>
                            <th>Account</th>
                            <th>Amount</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- menu add/edit modal start -->
    @if (permission('expense-edit') || permission('expense-add'))
        @include('expense::modal')
    @endif
    <!-- menu add/edit modal end -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
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
                    "url": "{{ route('expense.datatable.data') }}",
                    "type": "POST",
                    "data": function(data) {
                        data.expense_category_id = $("#form-filter #expense_category_id").val();
                        data.warehouse_id = $("#form-filter #warehouse_id").val();
                        data.account_id = $("#form-filter #account_id").val();
                        data._token = _token;
                    }
                },
                "columnDefs": [{
                        @if (permission('expense-bulk-delete'))
                            "targets": [0, 4],
                        @else
                            "targets": [3],
                        @endif
                        "orderable": false,
                        "className": "text-center"
                    },
                    {
                        @if (permission('expense-bulk-delete'))
                            "targets": [1, 3],
                        @else
                            "targets": [0, 2],
                        @endif
                        "className": "text-center"
                    }
                ],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
                "buttons": [
                    @if (permission('expense-report'))
                        {
                            "extend": "colvis",
                            "className": "btn btn-secondary btn-sm text-white",
                            "text": "Column"
                        }, {
                            "extend": "print",
                            "text": "Print",
                            "className": "btn btn-secondary btn-sm text-white float-end",
                            "title": "Expense List",
                            "orientation": "landscape",
                            "pageSize": "A4",
                            "exportOptions": {
                                columns: function(index, data, node) {
                                    return table.column(index).visible();
                                }
                            },
                            customize: function(win) {
                                $(win.document.body).addClass('bg-white');
                            }
                        }, {
                            "extend": "csv",
                            "text": "CSV",
                            "className": "btn btn-secondary btn-sm text-white",
                            "title": "Expense List",
                            "filename": "expense-list",
                            "exportOptions": {
                                columns: function(index, data, node) {
                                    return table.column(index).visible();
                                }
                            },
                        }, {
                            "extend": "excel",
                            "text": "Excel",
                            "className": "btn btn-secondary btn-sm text-white",
                            "title": "Expense List",
                            "filename": "expense-list",
                            "exportOptions": {
                                columns: function(index, data, node) {
                                    return table.column(index).visible();
                                }
                            },
                        }, {
                            "extend": "pdf",
                            "text": "PDF",
                            "className": "btn btn-secondary btn-sm text-white",
                            "title": "Expense List",
                            "filename": "expense-list",
                            "orientation": "landscape",
                            "exportOptions": {
                                columns: [1, 2, 3]
                            },
                        },
                    @endif
                    @if (permission('expense-bulk-delete'))
                        {
                            "className": "btn btn-danger btn-sm delete_btn d-none text-white",
                            "text": "Delete",
                            action: function(e, dt, node, config) {
                                multi_delete();
                            }
                        }
                    @endif
                ]
            });

            // if user search
            $('#btn-filter').click(function() {
                table.ajax.reload();
            });

            // if user reset
            $('#btn-reset').click(function() {
                $('#form-filter')[0].reset();
                table.ajax.reload();
            });

            // if user add new menu
            $(document).on('click', '#save-btn', function() {
                let form = document.getElementById('store_or_update_form');
                let formData = new FormData(form);
                let url = "{{ route('expense.store.or.update') }}";
                let id = $('#update_id').val();
                let method;
                if (id) {
                    method = 'update';
                } else {
                    method = 'add';
                }
                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function() {
                        $('#save-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
                    },
                    complete: function() {
                        $('#save-btn').removeClass(
                            'kt-spinner kt-spinner--md kt-spinner--light');
                    },
                    success: function(data) {
                        $('#store_or_update_form').find('.is-invalid').removeClass(
                            'is-invalid');
                        $('#store_or_update_form').find('.error').remove();
                        if (data.status == false) {
                            $.each(data.errors, function(key, value) {
                                $('#store_or_update_form input#' + key).addClass(
                                    'is-invalid');
                                $('#store_or_update_form #' + key).parent().append(
                                    '<small class="error text-danger">' + value +
                                    '</small>'
                                );
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
            $(document).on('click', '.edit_data', function() {
                let id = $(this).data('id');
                $('#store_or_update_form')[0].reset();
                $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                $('#store_or_update_form').find('.error').remove();
                if (id) {
                    $.ajax({
                        url: "{{ route('expense.edit') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: _token
                        },
                        dataType: "JSON",
                        success: function(data) {
                            $('#store_or_update_form #update_id').val(data.id);
                            $('#store_or_update_form #expense_category_id').val(data
                                .expense_category_id);
                            $('#store_or_update_form #warehouse_id').val(data.warehouse_id);
                            $('#store_or_update_form #account_id').val(data.account_id);
                            $('#store_or_update_form #amount').val(data.amount);
                            $('#store_or_update_form #note').val(data.note);
                            $('#store_or_update_form .selectpicker').selectpicker('refresh');

                            myModal = new bootstrap.Modal(document.getElementById(
                                'store_or_update_modal'), {
                                keyboard: false,
                                backdrop: 'static'
                            });

                            myModal.show();
                            $('#store_or_update_modal .modal-title').html(
                                '<i class="fas fa-edit"></i> <span>Edit Expense</span>');
                            $('#store_or_update_modal #save-btn').text('Update');

                        },
                        error: function(xhr, ajaxOption, thrownError) {
                            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr
                                .responseText);
                        }
                    });
                }
            });

            //  delete data
            $(document).on('click', '.delete_data', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let name = $(this).data('name');
                let row = table.row($(this).parent('tr'));
                let url = "{{ route('expense.delete') }}";
                delete_data(id, url, table, row, name);
            });


            // bulk delete menu
            function multi_delete() {
                let ids = [];
                let rows;
                $('.select_data:checked').each(function() {
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
                    let url = "{{ route('expense.bulk.delete') }}";
                    bulk_delete(ids, url, table, rows);
                }
            }

            // change status
            $(document).on('click', '.change_status', function() {
                let id = $(this).data('id');
                let status = $(this).data('status');
                let name = $(this).data('name');
                let row = table.row($(this).parent('tr'));
                let url = "{{ route('expense.change.status') }}";
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

                        }).done(function(response) {
                            if (response.status == 'success') {
                                Swal.fire("Status Changed!", response.message, "success")
                                    .then(function() {
                                        table.row(row).remove().draw(false);
                                    });
                            }
                            if (response.status == 'error') {
                                Swal.fire('Opps...', "Something went wrong!", "error");
                            }
                        }).fail(function() {
                            Swal.fire('Opps...', "Something went wrong with ajax!",
                                "error");
                        });
                    }
                })
            });
        });
    </script>
@endpush
