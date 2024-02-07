@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('styles')
    <link rel="stylesheet" href="daterange/css/daterangepicker.min.css">
@endpush

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
                <form id="form-filter" class="mb-3">
                    <div class="row justify-content-center">
                        <x-form.selectbox labelName="Warehouse" name="warehouse_id" required="required" col="col-md-3"
                            class="selectpicker">
                            <option value="0" selected>All Warehouses</option>
                            @if (!$warehouses->isEmpty())
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                    </div>
                </form>

                <div class="col-md-12" id="report">

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            daily_report(warehouse_id = 0, year = '{{ date('Y') }}', date = '{{ date('m') }}');

            $(document).on('click', '.previous', function() {
                var year = $('#prev_year').val();
                var warehouse_id = $('#warehouse_id option:selected').val();
                daily_report(warehouse_id, year);
            });
            $(document).on('click', '.next', function() {
                var year = $('#next_year').val();
                var warehouse_id = $('#warehouse_id option:selected').val();
                daily_report(warehouse_id, year);
            });

            $('#warehouse_id').change(function() {
                var warehouse_id = $('#warehouse_id option:selected').val();
                daily_report(warehouse_id, year = '{{ date('Y') }}');
            });

            function daily_report(warehouse_id, year) {
                $.ajax({
                    url: "{{ url('monthly-purchase-report') }}",
                    type: "POST",
                    data: {
                        warehouse_id: warehouse_id,
                        year: year,
                        _token: _token
                    },
                    success: function(data) {
                        $('#report').html();
                        $('#report').html(data);
                    },
                    error: function(xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
                });
            }

        });
    </script>
@endpush
