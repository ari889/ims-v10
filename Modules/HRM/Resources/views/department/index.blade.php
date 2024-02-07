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
                    @if (permission('department-add'))
                        <button type="button" class="btn btn-primary btn-sm"
                            onclick="showFormModal('Add new Department', 'Save')">
                            <i class="fas fa-plus-square"></i> Add New
                        </button>
                    @endif
                </div>
                <hr>
                <form id="form-filter">
                    <div class="row">
                        <x-form.textbox labelName="Department name" name="name" id="name" required="required"
                            col="col-md-4 mb-3" placeholder="Enter name" />
                        <div class="form-group col-md-8 pt-24">
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
                            @if (permission('department-bulk-delete'))
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select_all"
                                            onchange="select_all()">
                                        <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                </th>
                            @endif
                            <th>Sl</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- menu add/edit modal start -->
    @if (permission('department-edit') || permission('department-add'))
        @include('hrm::department.modal')
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
                    "url": "{{ route('department.datatable.data') }}",
                    "type": "POST",
                    "data": function(data) {
                        data.name = $("#form-filter #name").val();
                        data._token = _token;
                    }
                },
                "columnDefs": [{
                        @if (permission('department-bulk-delete'))
                            "targets": [0, 4],
                        @else
                            "targets": [3],
                        @endif
                        "orderable": false,
                        "className": "text-center"
                    },
                    {
                        @if (permission('department-bulk-delete'))
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
                    @if (permission('department-report'))
                        {
                            "extend": "colvis",
                            "className": "btn btn-secondary btn-sm text-white",
                            "text": "Column"
                        }, {
                            "extend": "print",
                            "text": "Print",
                            "className": "btn btn-secondary btn-sm text-white float-end",
                            "title": "Department List",
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
                            "title": "Department List",
                            "filename": "department-list",
                            "exportOptions": {
                                columns: function(index, data, node) {
                                    return table.column(index).visible();
                                }
                            },
                        }, {
                            "extend": "excel",
                            "text": "Excel",
                            "className": "btn btn-secondary btn-sm text-white",
                            "title": "Department List",
                            "filename": "department-list",
                            "exportOptions": {
                                columns: function(index, data, node) {
                                    return table.column(index).visible();
                                }
                            },
                        }, {
                            "extend": "pdf",
                            "text": "PDF",
                            "className": "btn btn-secondary btn-sm text-white",
                            "title": "Department List",
                            "filename": "department-list",
                            "orientation": "landscape",
                            "exportOptions": {
                                columns: [1, 2, 3]
                            },
                        },
                    @endif
                    @if (permission('department-bulk-delete'))
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

            $(document).on('click', '#save-btn', function() {
                let form = document.getElementById('store_or_update_form');
                let formData = new FormData(form);
                let url = "{{ route('department.store.or.update') }}";
                let id = $('#update_id').val();
                let method;
                if (id) {
                    method = 'update';
                } else {
                    method = 'add';
                }
                store_or_update_data(table, method, url, formData);
            });

            $(document).on('click', '.edit_data', function() {
                let id = $(this).data('id');
                $('#store_or_update_form')[0].reset();
                $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                $('#store_or_update_form').find('.error').remove();
                if (id) {
                    $.ajax({
                        url: "{{ route('department.edit') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: _token
                        },
                        dataType: "JSON",
                        success: function(data) {
                            $('#store_or_update_form #update_id').val(data.id);
                            $('#store_or_update_form #name').val(data.name);

                            myModal = new bootstrap.Modal(document.getElementById(
                                'store_or_update_modal'), {
                                keyboard: false,
                                backdrop: 'static'
                            });

                            myModal.show();
                            $('#store_or_update_modal .modal-title').html(
                                '<i class="fas fa-edit"></i> <span>Edit ' + data.name +
                                '</span>');
                            $('#store_or_update_modal #save-btn').text('Update');

                        },
                        error: function(xhr, ajaxOption, thrownError) {
                            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr
                                .responseText);
                        }
                    });
                }
            });

            $(document).on('click', '.delete_data', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let row = table.row($(this).parent('tr'));
                let url = "{{ route('department.delete') }}";
                delete_data(id, url, table, row, name);
            });

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
                        text: 'Please checked at least one row of table!',
                        icon: 'warning',
                    });
                } else {
                    let url = "{{ route('department.bulk.delete') }}";
                    bulk_delete(ids, url, table, rows);
                }
            }

            $(document).on('click', '.change_status', function() {
                let id = $(this).data('id');
                let status = $(this).data('status');
                let name = $(this).data('name');
                let row = table.row($(this).parent('tr'));
                let url = "{{ route('department.change.status') }}";
                Swal.fire({
                    title: 'Are you sure to change ' + name + ' status?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!'
                }).then((result) => {
                    if (result.value) {
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
                            if (response.status == "success") {
                                Swal.fire("Status Changed", response.message, "success")
                                    .then(function() {
                                        table.ajax.reload(null, false);
                                    });
                            }
                            if (response.status == "error") {
                                Swal.fire('Oops...', response.message, "error");
                            }
                        }).fail(function() {
                            Swal.fire('Oops...', "Somthing went wrong with ajax!", "error");
                        });
                    }
                });

            });
        });
    </script>
@endpush
