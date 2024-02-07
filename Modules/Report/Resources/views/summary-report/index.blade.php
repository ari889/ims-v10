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
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="name" class="form-label">Choose Your Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control daterangepicker-filed"
                                    value="{{ date('Y-m-') . '-01' }} To {{ date('Y-m-d') }}">
                                <input type="hidden" name="start_date" value="{{ date('Y-m-') . '-01' }}">
                                <input type="hidden" name="end_date" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="form-group col-md-1 pt-24">
                            <button type="button" class="btn btn-primary" id="btn-filter" data-toggle="tooltip"
                                data-placement="top" data-original-title="Filter Data">
                                Search
                            </button>
                        </div>
                    </div>
                </form>
                <div class="col-md-12">
                    <div class="row" id="report">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="js/moment.min.js"></script>
    <script src="/daterange/js/knockout-3.4.2.js"></script>
    <script src="/daterange/js/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.daterangepicker-filed').daterangepicker({
                callback: function(startDate, endDate, period) {
                    var start_date = startDate.format('YYYY-MM-DD');
                    var end_date = endDate.format('YYYY-MM-DD');
                    var title = start_date + ' To ' + end_date;
                    $(this).val(title);
                    $('input[name="start_date"]').val(start_date);
                    $('input[name="end_date"]').val(end_date);
                }
            });
            report();
            $('#btn-filter').click(function() {
                report();
            });

            function report() {
                var start_date = $('input[name="start_date"]').val();
                var end_date = $('input[name="end_date"]').val();
                $.ajax({
                    url: "{{ route('summary.report.details') }}",
                    type: "POST",
                    data: {
                        start_date: start_date,
                        end_date: end_date,
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
