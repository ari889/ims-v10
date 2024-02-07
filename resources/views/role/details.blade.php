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
                <a href="{{ route('role') }}" class="btn btn-primary btn-sm"><i class="fas fa-angle-left"></i> Back</a>
            </div>
            <hr>
            <div class="row">
                <h6 class="mt-3"><strong>Role Name:</strong> {{ $permission_data['role']->role_name }}</h6>
                <h6 class="mb-5"><strong>Deletable:</strong> {{ DELETABLE[$permission_data['role']->deletable] }}</h6>
                <div class="col-md-12">
                    <ul id="permission" class="text-left" style="list-style: none;">
                        @if(!$data->isEmpty())
                            @foreach ($data as $menu)
                                @if($menu->submenu->isEmpty())
                                    <li>
                                        @if(collect($permission_data['role_module'])->contains($menu->id))
                                        <i class="fas fa-check-circle text-success"></i>
                                        @else
                                        <i class="fas fa-times-circle text-danger"></i>
                                        @endif
                                        {!! $menu->type == 1 ? $menu->divider_title.' <small>(Divider)</small>' : $menu->module_name !!}
                                        @if(!$menu->permission->isEmpty())
                                            <ul style="list-style: none;">
                                                @foreach ($menu->permission as $permission)
                                                    <li>
                                                        @if(collect($permission_data['role_permission'])->contains($permission->id))
                                                        <i class="fas fa-check-circle text-success"></i>
                                                        @else
                                                        <i class="fas fa-times-circle text-danger"></i>
                                                        @endif
                                                        {{ $permission->name }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @else
                                    <li>
                                        @if(collect($permission_data['role_module'])->contains($menu->id))
                                        <i class="fas fa-check-circle text-success"></i>
                                        @else
                                        <i class="fas fa-times-circle text-danger"></i>
                                        @endif
                                        {!! $menu->type == 1 ? $menu->divider_title.'<small>(Divider)</small>' : $menu->module_name !!}
                                        <ul style="list-style: none;">
                                            @foreach ($menu->submenu as $submenu)
                                                <li>
                                                    @if(collect($permission_data['role_module'])->contains($submenu->id))
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    @else
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                    @endif 
                                                    {{ $submenu->module_name }}
                                                    @if(!$submenu->permission->isEmpty())
                                                        <ul style="list-style: none;">
                                                            @foreach ($submenu->permission as $permission)
                                                                <li>
                                                                    @if(collect($permission_data['role_module'])->contains($permission->id))
                                                                    <i class="fas fa-check-circle text-success"></i>
                                                                    @else
                                                                    <i class="fas fa-times-circle text-danger"></i>
                                                                    @endif
                                                                    {{ $permission->name }}
                                                                </li>
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
            </div>

        </div>
    </div>
</div>
@endsection