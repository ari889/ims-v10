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
                    @if (permission('employee-add'))
                        <button type="button" class="btn btn-primary btn-sm"
                            onclick="showFormModal('Add new Department', 'Save')">
                            <i class="fas fa-plus-square"></i> Add New
                        </button>
                    @endif
                </div>
                <hr>
                <form id="form-filter">
                    <div class="row">
                        <x-form.textbox labelName="Employee Name" name="name" id="name" required="required"
                            col="col-md-3 mb-2" placeholder="Enter name" />
                        <x-form.textbox labelName="Phone No." name="phone" id="phone" required="required"
                            col="col-md-3 mb-2" placeholder="Enter employee phone" />
                        <x-form.selectbox labelName="Department" name="department_id" required="required"
                            col="col-md-3 mb-2" class="selectpicker">
                            @if (!$departments->isEmpty())
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <div class="col-md-3 pt-24">
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
                            @if (permission('employee-bulk-delete'))
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select_all"
                                            onchange="select_all()">
                                        <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                </th>
                            @endif
                            <th>Sl</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Phone No.</th>
                            <th>Department</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Postal Code</th>
                            <th>Country</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- menu add/edit modal start -->
    @if (permission('employee-edit') || permission('employee-add'))
        @include('hrm::employee.modal')
    @endif
    <!-- menu add/edit modal end -->
@endsection

@push('scripts')
    <script src="js/spartan-multi-image-picker-min.js"></script>
    <script>
        var table;
        $(document).ready(function() {

            table = $('#dataTable').DataTable({
                "processing": true, //Feature control the processing indicator
                "serverSide": true, //Feature control DataTable server side processing mode
                "order": [], //Initial no order
                "responsive": true, //Make table responsive in mobile device
                "bInfo": true, //TO show the total number of data
                "bFilter": false, //For datatable default search box show/hide
                "lengthMenu": [
                    [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
                    [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
                ],
                "pageLength": 25, //number of data show per page
                "language": {
                    processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                    emptyTable: '<strong class="text-danger">No Data Found</strong>',
                    infoEmpty: '',
                    zeroRecords: '<strong class="text-danger">No Data Found</strong>'
                },
                "ajax": {
                    "url": "{{ route('employee.datatable.data') }}",
                    "type": "POST",
                    "data": function(data) {
                        data.department_id = $("#form-filter #department_id option:selected").val();
                        data.name = $("#form-filter #name").val();
                        data.phone = $("#form-filter #phone").val();
                        data._token = _token;
                    }
                },
                "columnDefs": [{
                        @if (permission('employee-bulk-delete'))
                            "targets": [0, 11],
                        @else
                            "targets": [10],
                        @endif
                        "orderable": false,
                        "className": "text-center"
                    },
                    {
                        @if (permission('employee-bulk-delete'))
                            "targets": [1, 2, 4, 5, 6, 7, 8, 9, 10],
                        @else
                            "targets": [0, 1, 3, 4, 5, 6, 7, 8, 9],
                        @endif
                        "className": "text-center"
                    }
                ],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

                "buttons": [
                    @if (permission('employee-report'))
                        {
                            'extend': 'colvis',
                            'className': 'btn btn-secondary btn-sm text-white',
                            'text': 'Column'
                        }, {
                            "extend": 'print',
                            'text': 'Print',
                            'className': 'btn btn-secondary btn-sm text-white',
                            "title": "Employee List",
                            "orientation": "landscape", //portrait
                            "pageSize": "A4", //A3,A5,A6,legal,letter
                            "exportOptions": {
                                columns: function(index, data, node) {
                                    return table.column(index).visible();
                                }
                            },
                            customize: function(win) {
                                $(win.document.body).addClass('bg-white');
                            },
                        }, {
                            "extend": 'csv',
                            'text': 'CSV',
                            'className': 'btn btn-secondary btn-sm text-white',
                            "title": "Employee List",
                            "filename": "employee-list",
                            "exportOptions": {
                                columns: function(index, data, node) {
                                    return table.column(index).visible();
                                }
                            }
                        }, {
                            "extend": 'excel',
                            'text': 'Excel',
                            'className': 'btn btn-secondary btn-sm text-white',
                            "title": "Employee List",
                            "filename": "employee-list",
                            "exportOptions": {
                                columns: function(index, data, node) {
                                    return table.column(index).visible();
                                }
                            }
                        }, {
                            "extend": 'pdf',
                            'text': 'PDF',
                            'className': 'btn btn-secondary btn-sm text-white',
                            "title": "Employee List",
                            "filename": "employee-list",
                            "orientation": "landscape", //portrait
                            "pageSize": "A4", //A3,A5,A6,legal,letter
                            "exportOptions": {
                                columns: [1, 2, 3]
                            },
                        },
                    @endif
                    @if (permission('employee-bulk-delete'))
                        {
                            'className': 'btn btn-danger btn-sm delete_btn d-none text-white',
                            'text': 'Delete',
                            action: function(e, dt, node, config) {
                                multi_delete();
                            }
                        }
                    @endif
                ],
            });

            $('#btn-filter').click(function() {
                table.ajax.reload();
            });

            $('#btn-reset').click(function() {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });

            $(document).on('click', '#save-btn', function() {
                let form = document.getElementById('store_or_update_form');
                let formData = new FormData(form);
                let url = "{{ route('employee.store.or.update') }}";
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
                        url: "{{ route('employee.edit') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: _token
                        },
                        dataType: "JSON",
                        success: function(data) {
                            $('#store_or_update_form #update_id').val(data.id);
                            $('#store_or_update_form #department_id').val(data.department_id);
                            $('#store_or_update_form #name').val(data.name);
                            $('#store_or_update_form #phone').val(data.phone);
                            $('#store_or_update_form #email').val(data.email);
                            $('#store_or_update_form #address').val(data.address);
                            $('#store_or_update_form #city').val(data.city);
                            $('#store_or_update_form #state').val(data.state);
                            $('#store_or_update_form #postal_code').val(data.postal_code);
                            $('#store_or_update_form #country').val(data.country);
                            $('#store_or_update_form #old_image').val(data.image);
                            $('#store_or_update_form .selectpicker').selectpicker('refresh');
                            if (data.image) {
                                var image = "{{ asset('storage/' . EMPLOYEE_IMAGE_PATH) }}/" +
                                    data.image;
                                $('#store_or_update_form #image img.spartan_image_placeholder')
                                    .css('display', 'none');
                                $('#store_or_update_form #image .spartan_remove_row').css(
                                    'display', 'none');
                                $('#store_or_update_form #image .img_').css('display', 'block');
                                $('#store_or_update_form #image .img_').attr('src', image);
                            } else {
                                $('#store_or_update_form #image img.spartan_image_placeholder')
                                    .css('display', 'block');
                                $('#store_or_update_form #image .spartan_remove_row').css(
                                    'display', 'none');
                                $('#store_or_update_form #image .img_').css('display', 'none');
                                $('#store_or_update_form #image .img_').attr('src', '');
                            }
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

            $(document).on('click', '.view_data', function() {
                let id = $(this).data('id');
                if (id) {
                    $.ajax({
                        url: "{{ route('employee.show') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: _token
                        },
                        success: function(data) {

                            $('#view_modal .details').html();
                            $('#view_modal .details').html(data);

                            $('#view_modal').modal({
                                keyboard: false,
                                backdrop: 'static',
                            });
                            $('#view_modal .modal-title').html(
                                '<i class="fas fa-eye"></i> <span>Employee Details</span>');
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
                let url = "{{ route('employee.delete') }}";
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
                    let url = "{{ route('employee.bulk.delete') }}";
                    bulk_delete(ids, url, table, rows);
                }
            }

            $(document).on('click', '.change_status', function() {
                let id = $(this).data('id');
                let status = $(this).data('status');
                let name = $(this).data('name');
                // let row   = table.row($(this).parent('tr'));
                let url = "{{ route('employee.change.status') }}";
                change_status(id, status, name, table, url);
            });

            $('#image').spartanMultiImagePicker({
                fieldName: 'image',
                maxCount: 1,
                rowHeight: '150px',
                groupClassName: 'col-md-12 com-sm-12 com-xs-12',
                maxFileSize: '',
                dropFileLabel: 'Drop Here',
                allowExt: 'png|jpg|jpeg',
                onExtensionErr: function(index, file) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Only png,jpg,jpeg file format allowed!'
                    });
                }
            });

            $('input[name="image"]').prop('required', true);

            $('.remove-files').on('click', function() {
                $(this).parents('.col-md-12').remove();
            });

        });
    </script>
@endpush
