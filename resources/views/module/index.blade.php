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
                    <a href="{{ route('menu') }}" class="btn btn-danger btn-sm"><i class="fas fa-arrow-circle-left"></i> Back</a>
                    <a href="{{ route('menu.module.create', ['menu' => $data['menu']->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-plus-square"></i> Add New</a>
                </div>
            </div>
            <hr>
            <div class="dd">
                <x-menu-builder :menuItems="$data['menu']->menuItems" />
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function(){
            $('.dd').nestable({maxDepth:2}); // define nestable

            // save change menu on database
            $('.dd').on('change', function(e){
                $.post('{{ route("menu.order", ["menu" => $data["menu"]->id]) }}', {
                    order: JSON.stringify($('.dd').nestable('serialize')),
                    _token : _token,
                }, function(data){
                    notification('success', 'Menu order updated successfully!')
                });
            })
        });

        // delete menu item
        function deleteItem(id){
            Swal.fire({
                title: 'Are you sure to delete?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete_form_'+id).submit();
                }
            });
        }

        // show session message
        $(document).ready(function(){
            @if(session('success'))
            notification('success', "{{ session('success') }}");
            @endif
            @if(session('error'))
            notification('error', "{{ session('error') }}");
            @endif
        });
    </script>
@endpush
