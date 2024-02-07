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
                        <div class="col-md-4">
                            <label for="name" class="form-label">Choose Your Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control daterangepicker-field">
                                <input type="hidden" name="from_date" id="from_date">
                                <input type="hidden" name="to_date" id="to_date">
                            </div>
                        </div>
                        <x-form.selectbox labelName="Warehouse" name="warehouse_id" required="required" col="col-md-4"
                            class="selectpicker">
                            <option value="0" selected>All Warehouses</option>
                            @if (!$warehouses->isEmpty())
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>

                        <div class="col-md-4 pt-24">
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
                            <th>Product Name</th>
                            <th>Purchase Amount</th>
                            <th>Purchased Qty</th>
                            <th>Sold Amount</th>
                            <th>Sold Qty</th>
                            <th>Profit</th>
                            <th>In Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($product_id))
                            @foreach ($product_id as $key => $pro_id)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $product_name[$key] }}</td>
                                    @php
                                        if ($warehouse_id == 0) {
                                            $purchased_cost = DB::table('purchase_products')
                                                ->where('product_id', $pro_id)
                                                ->whereDate('created_at', '>=', $start_date)
                                                ->whereDate('created_at', '<=', $end_date)
                                                ->sum('total');
                                            $product_purchased_data = DB::table('purchase_products')
                                                ->where('product_id', $pro_id)
                                                ->whereDate('created_at', '>=', $start_date)
                                                ->whereDate('created_at', '<=', $end_date)
                                                ->get();
                                            $sold_price = DB::table('sale_products')
                                                ->where('product_id', $pro_id)
                                                ->whereDate('created_at', '>=', $start_date)
                                                ->whereDate('created_at', '<=', $end_date)
                                                ->sum('total');
                                            $product_sale_data = DB::table('sale_products')
                                                ->where('product_id', $pro_id)
                                                ->whereDate('created_at', '>=', $start_date)
                                                ->whereDate('created_at', '<=', $end_date)
                                                ->get();
                                        } else {
                                            $purchased_cost = DB::table('purchases as p')
                                                ->join('purchase_products as pp', 'p.id', '=', 'pp.purchase_id')
                                                ->where([['pp.product_id', $pro_id], ['p.warehouse_id', $warehouse_id]])
                                                ->whereDate('p.created_at', '>=', $start_date)
                                                ->whereDate('p.created_at', '<=', $end_date)
                                                ->sum('total');
                                            $product_purchased_data = DB::table('purchases as p')
                                                ->join('purchase_products as pp', 'p.id', '=', 'pp.purchase_id')
                                                ->where([['pp.product_id', $pro_id], ['p.warehouse_id', $warehouse_id]])
                                                ->whereDate('p.created_at', '>=', $start_date)
                                                ->whereDate('p.created_at', '<=', $end_date)
                                                ->get();
                                            $sold_price = DB::table('sales as s')
                                                ->join('sale_products as sp', 's.id', '=', 'sp.sale_id')
                                                ->where([['sp.product_id', $pro_id], ['s.warehouse_id', $warehouse_id]])
                                                ->whereDate('s.created_at', '>=', $start_date)
                                                ->whereDate('s.created_at', '<=', $end_date)
                                                ->sum('total');
                                            $product_sale_data = DB::table('sales as s')
                                                ->join('sale_products as sp', 's.id', '=', 'sp.sale_id')
                                                ->where([['sp.product_id', $pro_id], ['s.warehouse_id', $warehouse_id]])
                                                ->whereDate('s.created_at', '>=', $start_date)
                                                ->whereDate('s.created_at', '<=', $end_date)
                                                ->get();
                                        }
                                        $purchased_qty = 0;
                                        foreach ($product_purchased_data as $product_purchase) {
                                            $unit = DB::table('units')->find($product_purchase->unit_id);
                                            if ($unit->operator == '*') {
                                                $purchased_qty += $product_purchase->qty * $unit->operation_value;
                                            } elseif ($unit->operator == '/') {
                                                $purchased_qty += $product_purchase->qty / $unit->operation_value;
                                            }
                                        }
                                        $sold_qty = 0;
                                        foreach ($product_sale_data as $product_sale) {
                                            $unit = DB::table('units')->find($product_sale->sale_unit_id);
                                            if ($unit) {
                                                if ($unit->operator == '*') {
                                                    $sold_qty += $product_sale->qty * $unit->operation_value;
                                                } elseif ($unit->operator == '/') {
                                                    $sold_qty += $product_sale->qty / $unit->operation_value;
                                                }
                                            } else {
                                                $sold_qty += $product_sale->qty;
                                            }
                                        }
                                        
                                        if ($purchased_qty > 0) {
                                            $profit = $sold_price - ($purchased_cost / $purchased_qty) * $sold_qty;
                                        } else {
                                            $profit = $sold_price;
                                        }
                                    @endphp
                                    <td>{{ number_format($purchased_cost, 2) }}</td>
                                    <td>{{ $purchased_qty }}</td>
                                    <td>{{ number_format($sold_price, 2) }}</td>
                                    <td>{{ $sold_qty }}</td>
                                    <td>{{ number_format($profit, 2) }}</td>
                                    <td>{{ $product_qty[$key] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="bg-primary">
                            <th colspan="2" class="text-right">Total</th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
                            <th class="text-right"></th>
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

            table = $('#dataTable').DataTable({
                "processing": false, //Feature control the processing indicator
                "serverSide": false, //Feature control DataTable server side processing mode
                "order": [], //Initial no order
                "responsive": false, //Make table responsive in mobile device
                "bInfo": true, //TO show the total number of data
                "bFilter": false, //For datatable default search box show/hide
                "lengthMenu": [
                    [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
                    [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
                ],
                "pageLength": 25, //number of data show per page
                "language": {
                    processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                    emptyTable: '<strong class="text-danger">No Data Found</strong>',
                    infoEmpty: '',
                    zeroRecords: '<strong class="text-danger">No Data Found</strong>'
                },

                "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                    "className": "text-center"
                }, ],
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
                        "title": "Menu List",
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
                        "title": "Menu List",
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
                        "title": "Menu List",
                        "filename": "sale-list",
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
                        "title": "Menu List",
                        "filename": "sale-list",
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

                    for (var index = 2; index <= 7; index++) {
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
        });
    </script>
@endpush
