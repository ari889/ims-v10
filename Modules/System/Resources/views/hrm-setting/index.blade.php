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
                </div>
                <hr>
                <form id="store_or_update_form" method="POST">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="update_id" value="{{ $data ? $data->id : '' }}">
                        <div class="form-group col-md-4">
                            <label for="check_in">Check In Time</label>
                            <input type="text" class="form-control time" name="check_in" id="check_in"
                                value="{{ $data ? $data->check_in : '' }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="check_out">Check Out Time</label>
                            <input type="text" class="form-control time" name="check_out" id="check_out"
                                value="{{ $data ? $data->check_out : '' }}">
                        </div>

                        <div class="form-group col-md-4 text-center" style="padding-top: 24px;">
                            <button type="button" class="btn btn-danger btn-sm" id="reset-btn">Reset</button>
                            <button type="button" class="btn btn-primary btn-sm" id="save-btn">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="js/moment.min.js"></script>
    <script src="js/bootstrap-datetimepicker.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.time').datetimepicker({
                format: 'hh:mm A',
                ignoreReadonly: true
            });
            $(document).on('click', '#save-btn', function(e) {
                e.preventDefault();
                let form = document.getElementById('store_or_update_form');
                let formData = new FormData(form);
                $.ajax({
                    url: "{{ url('hrm-setting/store') }}",
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
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
                                var key = key.split('.').join('_');
                                $('#store_or_update_form input#' + key).addClass(
                                    'is-invalid');
                                $('#store_or_update_form #' + key).parent().append(
                                    '<small class="error text-danger">' + value +
                                    '</small>');

                            });
                        } else {
                            notification(data.status, data.message);
                            if (data.status == 'success') {
                                window.location.replace('{{ url('hrm-setting') }}');
                            }
                        }

                    },
                    error: function(xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr
                            .responseText);
                    }
                });
            });


        });
    </script>
@endpush
