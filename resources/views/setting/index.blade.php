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
            <ul class="nav nav-tabs nav-success mt-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#general" role="tab" aria-selected="true">
                        <div class="d-flex align-items-center">
                            <div class="tab-title">General Setting</div>
                        </div>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#mail" role="tab" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <div class="tab-title">Mail Setting</div>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content py-3">
                <div class="tab-pane fade active show" id="general" role="tabpanel">
                    <form class="col-md-12" method="POST" enctype="multipart/form-data" id="general-form">
                        @csrf
                        <x-form.textbox labelName="Title" name="title" required="required" col="col-md-12 mb-3"
                            placeholder="Enter title" value="{{ config('settings.title') }}" />
                        <x-form.textbox labelName="Address" name="address" required="required" col="col-md-12 mb-3"
                            placeholder="Enter address" value="{{ config('settings.address') }}" />
                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group col-md-6 required">
                                    <label for="logo" class="form-label">Logo</label>
                                    <div class="col-md-12 px-0 text-center">
                                        <div id="logo">

                                        </div>
                                    </div>
                                    <input type="hidden" name="old_logo" id="old_logo" value="{{ config('settings.logo') }}">
                                </div>
                                <div class="form-group col-md-6 required">
                                    <label for="favicon" class="form-label">Favicon</label>
                                    <div class="col-md-12 px-0 text-center">
                                        <div id="favicon">

                                        </div>
                                    </div>
                                    <input type="hidden" name="old_favicon" id="old_favicon" value="{{ config('settings.favicon') }}">
                                </div>
                            </div>
                        </div>
                        <x-form.textbox labelName="Currency Code" name="currency_code" required="required"
                            col="col-md-12 mb-3" placeholder="Enter currency code" value="{{ config('settings.currency_code') }}" />
                        <x-form.textbox labelName="Currency Symbol" name="currency_symbol" required="required"
                            col="col-md-12 mb-3" placeholder="Enter currency symbol" value="{{ config('settings.currency_symbol') }}" />
                        <div class="col-md-12 required">
                            <label for="" class="form-label d-block">Currency Symbol</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="currency_position" value="prefix" id="prefix" {{ config('settings.currency_position') == 'prefix' ? 'checked' : '' }}>
                                <label class="form-check-label" for="prefix">
                                    Prefix
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="currency_position" value="suffix" id="suffix"
                                {{ config('settings.currency_position') == 'suffix' ? 'checked' : '' }}>
                                <label class="form-check-label" for="suffix">
                                    Suffix
                                </label>
                            </div>
                        </div>
                        <x-form.selectbox labelName="Timezone" name="timezone" required="required"
                            col="col-md-12 mb-3" class="selectpicker">
                            @foreach ($zones_array as $zone)
                            <option value="{{ $zone['zone'] }}" {{ config('settings.timezone') == $zone['zone'] ? 'selected' : '' }}>{{ $zone['diff_from_GMT'].' - '.$zone['zone'] }}</option>
                            @endforeach
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Date Format" name="date_format" required="required"
                            col="col-md-12 mb-3" class="selectpicker">
                            <option value="F j, Y" {{ config('settings.date_format') == 'F j, Y' ? 'selected' : '' }}>{{ date('F j, Y') }}</option>
                            <option value="M j, Y" {{ config('settings.date_format') == 'M j, Y' ? 'selected' : '' }}>{{ date('M j, Y') }}</option>
                            <option value="j F, Y" {{ config('settings.date_format') == 'j F, Y' ? 'selected' : '' }}>{{ date('j F, Y') }}</option>
                            <option value="j M, Y" {{ config('settings.date_format') == 'j M, Y' ? 'selected' : '' }}>{{ date('j M, Y') }}</option>
                            <option value="Y-m-d" {{ config('settings.date_format') == 'Y-m-d' ? 'selected' : '' }}>{{ date('Y-m-d') }}</option>
                            <option value="Y-M-d" {{ config('settings.date_format') == 'Y-M-d' ? 'selected' : '' }}>{{ date('Y-M-d') }}</option>
                            <option value="Y/m/d" {{ config('settings.date_format') == 'Y/m/d' ? 'selected' : '' }}>{{ date('Y/m/d') }}</option>
                            <option value="m/d/Y" {{ config('settings.date_format') == 'm/d/Y' ? 'selected' : '' }}>{{ date('m/d/Y') }}</option>
                            <option value="d/m/Y" {{ config('settings.date_format') == 'd/m/Y' ? 'selected' : '' }}>{{ date('d/m/Y') }}</option>
                            <option value="d.m.Y" {{ config('settings.date_format') == 'd.m.Y' ? 'selected' : '' }}>{{ date('d.m.Y') }}</option>
                            <option value="d-m-Y" {{ config('settings.date_format') == 'd-m-Y' ? 'selected' : '' }}>{{ date('d-m-Y') }}</option>
                            <option value="d-M-Y" {{ config('settings.date_format') == 'd-M-Y' ? 'selected' : '' }}>{{ date('d-M-Y') }}</option>
                        </x-form.selectbox>
                        <x-form.textbox labelName="Invoice Prefix" name="invoice_prefix" required="required"
                            col="col-md-12 mb-3" placeholder="Enter invoice prefix" value="{{ config('settings.invoice_prefix') }}" />
                        <x-form.textbox labelName="Invoice Number" name="invoice_number" required="required"
                            col="col-md-12 mb-3" placeholder="Enter invoice number" value="{{ config('settings.invoice_number') }}" />
                        <div class="col-md-12">
                            <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                            <button type="button" class="btn btn-primary btn-sm" id="general-save-btn" onclick="save_data('general')">Save</button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="mail" role="tabpanel">
                    <form class="col-md-12" method="POST" id="mail-form">
                        @csrf
                        <div class="row">
                            <x-form.selectbox labelName="Mail Driver (Protocol)" name="mail_mailer" required="required"
                            col="col-md-12 mb-3" class="selectpicker">
                                @foreach (MAIL_MAILER as $driver)
                                <option value="{{ $driver }}" {{ config('settings.mail_mailer') == $driver ? 'selected' : '' }}>{{ $driver }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <x-form.textbox labelName="Host name" name="mail_host" required="required"
                            col="col-md-12 mb-3" placeholder="Enter host name" value="{{ config('settings.mail_host') }}" />
                            <x-form.textbox labelName="Mail Address" name="mail_username" required="required"
                            col="col-md-12 mb-3" placeholder="Enter user name" value="{{ config('settings.mail_username') }}" />
                            <x-form.textbox labelName="Mail Password" name="mail_password" required="required"
                            col="col-md-12 mb-3" placeholder="Enter mail password" value="{{ config('settings.mail_password') }}" />
                            <x-form.textbox labelName="Mail From Name" name="mail_from_name" required="required"
                            col="col-md-12 mb-3" placeholder="Enter mail from name" value="{{ config('settings.mail_from_name') }}" />
                            <x-form.textbox labelName="Port" name="mail_port" required="required"
                            col="col-md-12 mb-3" placeholder="Enter mail port" value="{{ config('settings.mail_port') }}" />
                            <x-form.selectbox labelName="Mail Encryption" name="mail_encryption" required="required"
                            col="col-md-12 mb-3" class="selectpicker">
                                @foreach (MAIL_ENCRYPTION as $key => $value)
                                <option value="{{ $value }}" {{ config('settings.mail_encryption') == $value ? 'selected' : '' }}>{{ $key }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <div class="col-md-12">
                                <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                                <button type="button" class="btn btn-primary btn-sm" id="mail-save-btn" onclick="save_data('mail')">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="js/spartan-multi-image-picker-min.js"></script>
<script>
    $(document).ready(function () {
        // dropify for logo
        $('#logo').spartanMultiImagePicker({
            fieldName: 'logo',
            maxCount: 1,
            rowHeight: '200px',
            groupClassName: 'col-md-12 col-sm-12 col-xs-12',
            maxFileSize: '',
            dropFileLabel: 'Drop Here',
            allowExt: 'png|jpg|jpeg',
            onExtensionErr: function (index, file) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Only png, jpg and jpeg file format allowed!'
                });
            }
        });

        // dropify for favicon
        $('#favicon').spartanMultiImagePicker({
            fieldName: 'favicon',
            maxCount: 1,
            rowHeight: '200px',
            groupClassName: 'col-md-12 col-sm-12 col-xs-12',
            maxFileSize: '',
            dropFileLabel: 'Drop Here',
            allowExt: 'png',
            onExtensionErr: function (index, file) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Only png file format allowed!'
                });
            }
        });

        $('input[name="logo"],input[name="favicon"]').prop('required', true);

        $('.remove-files').on('click', function(){
            $(this).parents('.col-md-12').remove();
        });

        // set logo from config
        @if(config('settings.logo'))
        $('#logo img.spartan_image_placeholder').css('display', 'none');
        $('#logo .spartan_remove_row').css('display', 'none');
        $('#logo .img_').css('display', 'block');
        $('#logo .img_').attr('src', '{{ asset("storage/".LOGO_PATH.config("settings.logo")) }}');
        @endif

        // set favicon from config
        @if(config('settings.logo'))
        $('#favicon img.spartan_image_placeholder').css('display', 'none');
        $('#favicon .spartan_remove_row').css('display', 'none');
        $('#favicon .img_').css('display', 'block');
        $('#favicon .img_').attr('src', '{{ asset("storage/".FAVICON_PATH.config("settings.favicon")) }}');
        @endif

    });

    // save general and mail form
    function save_data(form_id){
        let form = document.getElementById(form_id+'-form');
        let formData = new FormData(form);
        let url;
        if(form_id == 'general'){
            url = "{{ route('general.setting') }}";
        }else{
            url = "{{ route('mail.setting') }}";
        }
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('#'+form_id+'-save-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
            },
            complete: function () {
                $('#'+form_id+'-save-btn').removeClass(
                    'kt-spinner kt-spinner--md kt-spinner--light');
            },
            success: function (data) {
                $('#'+form_id+'-form').find('.is-invalid').removeClass(
                'is-invalid');
                $('#'+form_id+'-form').find('.error').remove();
                if (data.status == false) {
                    $.each(data.errors, function (key, value) {
                        $('#'+form_id+'-form input#' + key).addClass(
                            'is-invalid');
                        $('#'+form_id+'-form textarea#' + key).addClass(
                            'is-invalid');
                        $('#'+form_id+'-form select#' + key).parent()
                            .addClass('is-invalid');
                            $('#'+form_id+'-form #' + key).parent().append(
                                '<small class="error text-danger">' +
                                value + '</small>'
                            );
                    });
                } else {
                    notification(data.status, data.message);
                }
            }
        });
    }

</script>
@endpush
