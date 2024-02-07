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
                    @if (permission('category-add'))
                        <button type="button" class="btn btn-primary btn-sm"
                            onclick="showFormModal('Add new category', 'Save')">
                            <i class="fas fa-plus-square"></i> Add New
                        </button>
                    @endif
                </div>
                <hr>
                <form id="form-filter">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="name" class="form-label">Choose Your Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control daterangepicker-field">
                                <input type="hidden" name="from_date" id="from_date">
                                <input type="hidden" name="to_date" id="to_date">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="sale_no" class="form-label">Sale No</label>
                            <input type="text" class="form-control" name="sale_no" id="sale_no"
                                placeholder="Enter sale no">
                        </div>
                        <x-form.selectbox labelName="Customer" name="customer_id" col="col-md-3" class="selectpicker">
                            @if (!$customers->isEmpty())
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name . ' - ' . $customer->phone }}
                                    </option>
                                @endforeach
                            @endif
                        </x-form.selectbox>

                        <div class="col-md-3 pt-24">
                            <button type="button" class="btn btn-danger btn-sm float-end" id="btn-reset"
                                data-toggle="tooltip" data-placement="top" data-original-title="Reset Data">
                                <i class="fas fa-redo-alt"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm float-end mr-2" id="btn-filter"
                                data-toggle="tooltip" data-placement="top" data-original-title="Filter Data">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <table id="dataTable" class="table table-striped table-bordered table-hover">
                    <thead class="bg-primary">
                        <tr>
                            <th>Sl</th>
                            <th>Customer</th>
                            <th>Sale No</th>
                            <th>Date</th>
                            <th>Grand Total</th>
                            <th>Paid Amount</th>
                            <th>Due Amount</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr class="bg-primary">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-right">Total</th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="js/moment.min.js"></script>
    <script src="/daterange/js/knockout-3.4.2.js"></script>
    <script src="/daterange/js/daterangepicker.min.js"></script>
    <script>
        $('.daterangepicker-field').daterangepicker({
            callback: function(startDate, endDate, period) {
                console.log(startDate)
                var start_date = startDate.format('YYYY-MM-DD');
                var end_date = endDate.format('YYYY-MM-DD');
                var title = start_date + ' To ' + end_date;
                $(this).val(title);
                $('input[name="from_date"]').val(start_date);
                $('input[name="to_date"]').val(end_date);
            }
        });
        $(document).ready(function() {
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
                    "url": "{{ route('customer.report.datatable.data') }}",
                    "type": "POST",
                    "data": function(data) {
                        data.sale_no = $("#form-filter #sale_no").val();
                        data.customer_id = $("#form-filter #customer_id option:selected").val();
                        data.from_date = $("#form-filter #from_date").val();
                        data.to_date = $("#form-filter #to_date").val();
                        data._token = _token;
                    }
                },
                "columnDefs": [{
                        "targets": [6],
                        "orderable": false,
                        "className": "text-right"
                    },
                    {
                        "targets": [2, 3],
                        "className": "text-center"
                    },
                    {
                        "targets": [4, 5, 6],
                        "className": "text-right"
                    }
                ],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
                "buttons": [{
                        'extend': 'colvis',
                        'className': 'btn btn-secondary btn-sm text-white',
                        'text': 'Column'
                    },
                    {
                        "extend": 'print',
                        'text': 'Print',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "Customer Report",
                        "orientation": "landscape", //portrait
                        "pageSize": "A4", //A3,A5,A6,legal,letter
                        "exportOptions": {
                            columns: function(index, data, node) {
                                return table.column(index).visible();
                            }
                        },
                        customize: function(win) {
                            $(win.document.body).addClass('bg-white');
                        },
                    },
                    {
                        "extend": 'csv',
                        'text': 'CSV',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "Customer Report",
                        "filename": "sale-list",
                        "exportOptions": {
                            columns: function(index, data, node) {
                                return table.column(index).visible();
                            }
                        }
                    },
                    {
                        "extend": 'excel',
                        'text': 'Excel',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "Customer Report",
                        "filename": "customer-report",
                        "exportOptions": {
                            columns: function(index, data, node) {
                                return table.column(index).visible();
                            }
                        }
                    },
                    {
                        "extend": 'pdf',
                        'text': 'PDF',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "Customer Report",
                        "filename": "customer-report",
                        "orientation": "landscape", //portrait
                        "pageSize": "A4", //A3,A5,A6,legal,letter
                        "exportOptions": {
                            columns: [1, 2, 3]
                        },
                    },

                ],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api(),
                        data;

                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i ===
                            'number' ? i : 0;
                    };

                    for (var index = 4; index <= 6; index++) {
                        total = api.column(index).data().reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                        pageTotal = api.column(index, {
                            page: 'current'
                        }).data().reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                        $(api.column(index).footer()).html('= ' + parseFloat(pageTotal).toFixed(2) +
                            ' (' + parseFloat(total).toFixed(2) + ' Total)');
                    }
                }
            });

            // if user search
            $('#btn-filter').click(function() {
                table.ajax.reload();
            });

            // if user reset
            $('#btn-reset').click(function() {
                $('#form-filter')[0].reset();
                $('input[name="from_date"]').val('');
                $('input[name="to_date"]').val('');
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
        });
    </script>
@endpush
