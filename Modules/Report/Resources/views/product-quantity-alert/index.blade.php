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
                <form id="form-filter">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Enter product name">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="code" class="form-label">Barcode</label>
                            <input type="text" class="form-control" name="code" id="code"
                                placeholder="Enter barcode">
                        </div>
                        <x-form.selectbox labelName="Brand" name="brand_id" col="col-md-3" class="selectpicker">
                            @if (!$brands->isEmpty())
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Category" name="catgory_id" col="col-md-3" class="selectpicker">
                            @if (!$categories->isEmpty())
                                @foreach ($categories as $catgory)
                                    <option value="{{ $catgory->id }}">{{ $catgory->name }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <div class="col-md-12 pt-24">
                            <button type="button" class="btn btn-danger btn-sm float-right" id="btn-reset"
                                data-toggle="tooltip" data-placement="top" data-original-title="Reset Data">
                                <i class="fas fa-redo-alt"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm float-right mr-2" id="btn-filter"
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
                            <th>Image</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th>Qty</th>
                            <th>Alert Qty</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="js/spartan-multi-image-picker-min.js"></script>
    <script>
        var table;
        $(document).ready(function() {

            table = $('#dataTable').DataTable({
                "processing": true, //Feature control the processing indicator
                "serverSide": true, //Feature control DataTable server side processing mode
                "order": [], //Initial no order
                "responsive": true, //Make table responsive in mobile device
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
                "ajax": {
                    "url": "{{ route('product.quantity.alert.datatable.data') }}",
                    "type": "POST",
                    "data": function(data) {
                        data.name = $("#form-filter #name").val();
                        data.code = $("#form-filter #code").val();
                        data.brand_id = $("#form-filter #brand_id").val();
                        data.category_id = $("#form-filter #category_id").val();
                        data._token = _token;
                    }
                },
                "columnDefs": [{

                    "targets": [0, 1, 3, 4, 5, 6, 7, 8],
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
                        "filename": "product-list",
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
                        "filename": "product-list",
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
                        "filename": "product-list",
                        "orientation": "landscape", //portrait
                        "pageSize": "A4", //A3,A5,A6,legal,letter
                        "exportOptions": {
                            columns: [1, 2, 3]
                        },
                    },
                ],
            });

            $('#btn-filter').click(function() {
                table.ajax.reload();
            });

            $('#btn-reset').click(function() {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });

        });
    </script>
@endpush
