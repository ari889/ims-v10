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
                <div>
                    <a href="{{ route('menu.builder', ['id' => $data['menu']->id]) }}" class="btn btn-warning btn-sm"><i class="fas fa-arrow-circle-left"></i> Back</a>
                </div>
            </div>
            <hr>
            <h5 class="mt-3">Manage Menu Module/Item</h5>
            <form action="{{ route('menu.module.store.or.update') }}" method="POST">
                @csrf
                <input type="hidden" name="update_id" id="update_id" value="{{ isset($data['module']) ? $data['module']->id : '' }}" onchange="setItemType(this.value)" />
                <input type="hidden" name="menu_id" id="menu_id" value="{{ $data['menu']->id }}">
                <div class="required">
                    <label for="type" class="form-label">Type</label>
                    <select name="type" id="type" class="w-100 selectpicker @error('type') is-invalid @enderror mb-3" onchange="setItemType(this.value)">
                        <option value="">Select Please</option>
                        <option value="1" @isset($data['module']) {{ $data['module']->type == 1 ? 'selected' : '' }} @endisset
                            {{ old('type') == 1 ? 'selected' : '' }}>Divider</option>
                       <option value="2" @isset($data['module']) {{ $data['module']->type == 2 ? 'selected' : '' }} @endisset
                           {{ old('type') == 2 ? 'selected' : '' }}>Module/Item</option>
                    </select>
                    @error('type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <!-- divider fields start -->
                <div class="divider_fields d-none">
                    <div class="required">
                        <label for="divider_title" class="mb-2">Divider Title</label>
                        <input type="text" class="form-control @error('divider_title') is-invalid @enderror mb-3" name="divider_title" id="divider_title" placeholder="Enter divider title" value="{{ isset($data['module']) ? $data['module']->divider_title : old('divider_title') }}">
                        @error('divider_title')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
                <!-- divider fields end -->
    
                <!-- module/items add start -->
                <div class="item_fields d-none">
                    <div class="required">
                        <label for="module_name" class="mb-2">Module Name</label>
                        <input type="text" class="form-control @error('module_name') is-invalid @enderror mb-3" name="module_name" id="module_name" placeholder="Enter module name" value="{{ isset($data['module']) ? $data['module']->module_name : old('module_name') }}">
                        @error('module_name')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="required">
                        <label for="url" class="mb-2">Url for the module</label>
                        <input type="text" class="form-control @error('url') is-invalid @enderror mb-3" name="url" id="url" placeholder="Enter module name" value="{{ isset($data['module']) ? $data['module']->url : old('url') }}">
                        @error('url')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="required">
                        <label for="icon_class" class="mb-2">Icon</label>
                        <input type="text" class="form-control @error('icon_class') is-invalid @enderror mb-3" name="icon_class" id="icon_class" placeholder="Enter module name" value="{{ isset($data['module']) ? $data['module']->icon_class : old('icon_class') }}">
                        @error('icon_class')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                    <div class="required">
                        <label for="target" class="form-label" class="mb-2">Open In</label>
                        <select name="target" id="target" class="w-100 selectpicker @error('target') is-invalid @enderror mb-3">
                            <option value="">Select Please</option>
                            <option value="_self" @isset($data['module']) {{ $data['module']->target == '_self' ? 'selected' : '' }} @endisset
                                {{ old('target') == '_self' ? 'selected' : '' }}>Same Tab</option>
                            <option value="_blank" @isset($data['module']) {{ $data['module']->target == '_blank' ? 'selected' : '' }} @endisset
                                {{ old('target') == '_blank' ? 'selected' : '' }}>New Tab</option>
                        </select>
                        @error('target')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <!-- module/items add end -->

                <button type="reset" class="btn btn-danger btn-sm"><i class="fas fa-redo"></i> Reset</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    @isset($data['module'])
                    <i class="fas fa-arrow-circle-up"></i> Update 
                    @else
                    <i class="fas fa-plus-square"></i> Create
                    @endisset
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        var type = $('#type option:selected').val();
        if(type){
            setItemType(type);
        }

        function setItemType(type){
            if(type == 1){
                $('.divider_fields').removeClass('d-none');
                $('.item_fields').addClass('d-none');
            }else{
                $('.divider_fields').addClass('d-none');
                $('.item_fields').removeClass('d-none');
            }
        }
    </script>
@endpush
