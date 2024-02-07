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
            <form id="saveDataForm" method="POST">
                @csrf
                <input type="hidden" name="update_id" value="{{ $permission_data['role']->id }}" id="update_id">
                <div class="row">
                    <x-form.textbox labelName="Role Name" name="role_name" required="required" col="col-md-12 mb-3" placeholder="Enter role name" value="{{ $permission_data['role']->role_name }}" />
                    <x-form.selectbox labelName="Deletable" name="deletable" required="required"
                        col="col-md-12 mb-3" class="selectpicker">
                        @foreach (DELETABLE as $key => $item)
                        <option value="{{ $key }}" {{ $permission_data['role']->deletable == $key ? 'selected' : '' }}>{{ $item }}</option>
                        @endforeach
                    </x-form.selectbox>
                    <div class="col-md-12">
                        <ul id="permission" class="text-left">
                            @if(!$data->isEmpty())
                                @foreach ($data as $menu)
                                    @if($menu->submenu->isEmpty())
                                        <li>
                                            <input type="checkbox" name="module[]" class="module" value="{{ $menu->id }}" @if(collect($permission_data['role_module'])->contains($menu->id)) {{ 'checked' }} @endif />
                                            {!! $menu->type == 1 ? $menu->divider_title.' <small>(Divider)</small>' : $menu->module_name !!}
                                            @if(!$menu->permission->isEmpty())
                                                <ul>
                                                    @foreach ($menu->permission as $permission)
                                                        <li><input type="checkbox" name="permission[]" value="{{ $permission->id }}" @if(collect($permission_data['role_permission'])->contains($permission->id)) {{ 'checked' }} @endif /> {{ $permission->name }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @else
                                        <li>
                                            <input type="checkbox" name="module[]" class="module" value="{{ $menu->id }}" @if(collect($permission_data['role_module'])->contains($menu->id)) {{ 'checked' }} @endif />
                                            {!! $menu->type == 1 ? $menu->divider_title.'<small>(Divider)</small>' : $menu->module_name !!}
                                            <ul>
                                                @foreach ($menu->submenu as $submenu)
                                                    <li>
                                                        <input type="checkbox" name="module[]" class="module" value="{{ $submenu->id }}" @if(collect($permission_data['role_module'])->contains($submenu->id)) {{ 'checked' }} @endif> {{ $submenu->module_name }}
                                                        @if(!$submenu->permission->isEmpty())
                                                            <ul>
                                                                @foreach ($submenu->permission as $permission)
                                                                    <li><input type="checkbox" name="permission[]" value="{{ $permission->id }}" @if(collect($permission_data['role_permission'])->contains($permission->id)) {{ 'checked' }} @endif /> {{ $permission->name }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-12 mt-3">
                        <button type="reset" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Reset</button>
                        <button type="button" class="btn btn-primary btn-sm" id="save-btn">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="js/tree.js"></script>
    <script>
        $(document).ready(function(){
            // make child will be cheked if parent are checked
            $('input[type=checkbox]').click(function(){
                $(this).next().find('input[type=checkbox]').prop('checked',this.checked);
                $(this).parents('ul').prev('input[type=checkbox]').prop('checked', function(){
                    return $(this).next().find(':checked').length;
                });
            });

            // applied tree js on permission
            $('#permission').treed();

            //save form
            $(document).on('click', '#save-btn', function () {
                let form = document.getElementById('saveDataForm');
                let formData = new FormData(form);
                if($('.module:checked').length >= 1){
                    $.ajax({
                        url: "{{route('role.store.or.update')}}",
                        type: "POST",
                        data: formData,
                        dataType: "JSON",
                        contentType: false,
                        processData: false,
                        cache: false,
                        beforeSend: function(){
                            $('#save-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
                        },
                        complete: function(){
                            $('#save-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
                        },
                        success: function (data) {
                            $('#saveDataForm').find('.is-invalid').removeClass('is-invalid');
                            $('#saveDataForm').find('.error').remove();
                            if (data.status == false) {
                            $.each(data.errors, function (key, value) {
                                $('#saveDataForm input#' + key).addClass('is-invalid');
                                $('#saveDataForm select#' + key).parent().addClass('is-invalid');
                                $('#saveDataForm #' + key).parent().append(
                                    '<small class="error text-danger">' + value + '</small>');
                                });
                        } else {
                            notification(data.status, data.message);
                            if (data.status == 'success') {
                                window.location.replace("{{ route('role') }}");
                            }
                        }

                        },
                        error: function (xhr, ajaxOption, thrownError) {
                            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                        }
                    });
                }else{
                    notification('error','Please check at least one menu');
                }
                
            });
        });
    </script>
@endpush
