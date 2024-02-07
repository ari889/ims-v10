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
                @if(permission('user-add'))
                <button type="button" class="btn btn-primary btn-sm" onclick="showFormModal('Add new user', 'Save')">
                    <i class="fas fa-plus-square"></i> Add New
                </button>
                @endif
            </div>
            <hr>
            <form id="form-filter">
                <div class="row">
                    <div class="col-md-4">
                        <label for="name" class="mb-2">User Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter user name">
                    </div>
                    <x-form.selectbox labelName="Role" name="role_id" required="required" col="col-md-4 mb-3"
                        class="selectpicker">
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                        @endforeach
                    </x-form.selectbox>
                    <div class="col-md-4">
                        <label for="email" class="mb-2">Email</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Enter user email">
                    </div>
                    <div class="col-md-4">
                        <label for="mobile_no" class="mb-2">Mobile No</label>
                        <input type="text" name="mobile_no" id="mobile_no" class="form-control"
                            placeholder="Enter mobile no">
                    </div>
                    <x-form.selectbox labelName="Status" name="status" required="required" col="col-md-4 mb-3"
                        class="selectpicker">
                        @foreach (STATUS as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </x-form.selectbox>
                    <div class="col-md-4 clearfix pt-24">
                        <button type="button" class="btn btn-danger btn-sm float-end" id="btn-reset"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Reset Data"><i
                                class="fas fa-redo-alt"></i></button>
                        <button type="button" class="btn btn-primary btn-sm float-end me-2" id="btn-filter"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Filter Data"><i
                                class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>

            <table id="dataTable" class="table table-stripped table-bordered table-hover">
                <thead class="bg-primary">
                    <tr>
                        @if(permission('user-bulk-delete'))
                        <th>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="select_all"
                                    onchange="select_all()">
                                <label for="" class="custom-control-label" id="select_all"></label>
                            </div>
                        </th>
                        @endif
                        <th>SL</th>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Mobile No</th>
                        <th>Gender</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- menu add/edit modal start -->
@if(permission('user-edit') || permission('user-add'))
@include('user.modal')
@endif
<!-- menu add/edit modal end -->
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
                "url": "{{ route('user.datatable.data') }}",
                "type": "POST",
                "data": function (data) {
                    data.name = $('#form-filter #name').val();
                    data.email = $('#form-filter #email').val();
                    data.mobile_no = $('#form-filter #mobile_no').val();
                    data.role_id = $('#form-filter #role_id').val();
                    data.status = $('#form-filter #status').val();
                    data._token = _token;
                }
            },
            "columnDefs": [{
                    @if(permission('user-bulk-delete'))
                    "targets": [0, 9],
                    @else "targets": [8],
                    @endif "orderable": false,
                    "className": "text-center"
                },
                {
                    @if(permission('user-bulk-delete'))
                    "targets": [1, 2, 3, 4, 5, 6, 7, 8],
                    @else "targets": [0, 1, 2, 3, 6, 7, 8],
                    @endif "className": "text-center"
                }
            ],
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
            "buttons": [
                @if(permission('user-report')) {
                    "extend": "colvis",
                    "className": "btn btn-secondary btn-sm text-white",
                    "text": "Column"
                },
                {
                    "extend": "print",
                    "text": "Print",
                    "className": "btn btn-secondary btn-sm text-white float-end",
                    "title": "User List",
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
                    "title": "User List",
                    "filename": "user-list",
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
                    "title": "User List",
                    "filename": "user-list",
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
                    "title": "User List",
                    "filename": "user-list",
                    "orientation": "landscape",
                    "exportOptions": {
                        columns: [1, 2, 3]
                    },
                },
                @endif
                @if(permission('user-bulk-delete')) {
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
            let url = "{{ route('user.store.or.update') }}";
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
                    if(key == 'password' || key == 'password_confirmation'){
                        $('#store_or_update_form #' + key).parents('.form-group').append(
                            '<small class="error text-danger">' + value + '</small>'
                        );
                    }else{
                        $('#store_or_update_form #' + key).parent().append(
                            '<small class="error text-danger">' + value + '</small>'
                        );
                    }
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
                    url: "{{ route('user.edit') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: _token
                    },
                    dataType: "JSON",
                    success: function (data) {
                        $('#store_or_update_form #update_id').val(data.data.id);
                        $('#store_or_update_form #name').val(data.data.name);
                        $('#store_or_update_form #email').val(data.data.email);
                        $('#store_or_update_form #mobile_no').val(data.data.mobile_no);
                        $('#store_or_update_form #gender').val(data.data.gender);
                        $('#store_or_update_form #role_id').val(data.data.role_id);
                        $('#store_or_update_form .selectpicker').selectpicker('refresh');
                        $('#password, #password_confirmation').parents('.form-group')
                            .removeClass('required');

                        myModal = new bootstrap.Modal(document.getElementById(
                            'store_or_update_modal'), {
                            keyboard: false,
                            backdrop: 'static'
                        });

                        myModal.show();

                        $("#store_or_update_modal .modal-title").html(
                            '<i class="fas fa-edit"></i> ' + data.data.name);
                        $("#store_or_update_modal #save-btn").text("Update");
                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr
                            .responseText);
                    }
                });
            }
        });

        // view data
        $(document).on('click', '.view_data', function () {
            let id = $(this).data('id');
            if (id) {
                $.ajax({
                    url: "{{ route('user.show') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: _token
                    },
                    dataType: "JSON",
                    success: function (data) {
                        $('#view_modal .user-details').html();
                        $('#view_modal .user-details').html(data);

                        myModal = new bootstrap.Modal(document.getElementById(
                        'view_modal'), {
                            keyboard: false,
                            backdrop: 'static'
                        });

                        myModal.show();

                        $("#store_or_update_modal .modal-title").html(
                            '<i class="fas fa-eye"></i> <span>User Details</span>');
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
            let url  = "{{ route('user.delete') }}";
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
                let url = "{{ route('user.bulk.delete') }}";
                bulk_delete(ids, url, table, rows);
            }
        }

        // change status
        $(document).on('click', '.change_status', function () {
            let id = $(this).data('id');
            let status = $(this).data('status');
            let name = $(this).data('name');
            let row = table.row($(this).parent('tr'));
            let url = "{{ route('user.change.status') }}";
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

        // show hide password
        $('.toggle-password').click(function () {
            $(this).toggleClass('fas fa-eye-slash');
            var input = $($(this).attr('toggle'));
            if (input.attr('type') == 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password');
            }
        });
    });

    /****************
     * generate random password
     * ******************/
    const randomFunc = {
        upper : getRandomUpperCase,
        lower : getRandomLowerCase,
        number : getRandomNumber,
        symbol : getRandomSymbol,
    };

    function getRandomUpperCase(){
        return String.fromCharCode(Math.floor(Math.random()*26)+65)
    }

    function getRandomLowerCase(){
        return String.fromCharCode(Math.floor(Math.random()*26)+97);
    }

    function getRandomNumber(){
        return String.fromCharCode(Math.floor(Math.random()*10)+48);
    }

    function getRandomSymbol(){
        var symbol = "!@#$%^&*=~?";
        return symbol[Math.floor(Math.random()*symbol.length)];
    }

    // generate password
    document.getElementById('generate_password').addEventListener('click', () => {
        const length =10;
        const hasUpper = true;
        const hasLower = true;
        const hasSymbol = true;
        const hasNumber = true;

        let password = generatePassword(hasUpper, hasLower, hasNumber, hasSymbol, length);
        document.getElementById('password').value = password;
        document.getElementById('password_confirmation').value = password;
    });

    // generate passsword function
    function generatePassword(upper, lower, number, symbol, length){
        let generatedPassword = '';
        const typeCount = upper + lower + number + symbol;
        const typeArr = [{upper}, {lower}, {number}, {symbol}].filter(item => Object.values(item)[0]);
        if(typeCount === 0){
            return '';
        }

        for(let i = 0; i < length; i += typeCount){
            typeArr.forEach(type => {
                const funcName = Object.keys(type)[0];
                generatedPassword += randomFunc[funcName]();
            });
        }
        const finalPassword = generatedPassword.slice(0, length);
        return finalPassword;
    }

</script>
@endpush
