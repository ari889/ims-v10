@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Applications</div>
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

<div class="error-404 d-flex align-items-center justify-content-center py-4 h-auto">
    <div class="container">
        <div class="card py-5">
            <div class="row g-0">
                <div class="col col-xl-5">
                    <div class="card-body p-4">
                        <h1 class="display-1"><span class="text-primary">4</span><span class="text-danger">0</span><span class="text-success">1</span></h1>
                        <h2 class="font-weight-bold display-4">Unauthorized Access Blocked</h2>
                        <p>You have no permission to access this content.
                            <br>If you want to access this content please contact with admin.
                            <br>Dont'worry and return to the previous page.</p>
                        <div class="mt-5"> <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-md-5 radius-30">Go Home</a>
                            <a href="{{ url()->previous(); }}" class="btn btn-outline-dark btn-lg ms-3 px-md-5 radius-30">Back</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7">
                    <img src="https://cdn.searchenginejournal.com/wp-content/uploads/2019/03/shutterstock_1338315902.png" class="img-fluid" alt="">
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
</div>
@endsection

