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
                @if(permission('product-access'))
                <a href="{{ route('product') }}" class="btn btn-primary btn-sm"><i class="fas fa-th-list"></i>Manage
                    Product</a>
                @endif
            </div>
            <hr>
            <form id="form-barcode">
                <div class="row">
                    <x-form.selectbox labelName="Products" name="product" col="col-md-3 mb-2" class="selectpicker">
                        @if (!$products->isEmpty())
                            @foreach ($products as $product)
                                <option value="{{ $product->code }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-barcode="{{ $product->barcode_symbology }}">{{ $product->name }} - {{ $product->code }}</option>
                            @endforeach
                        @endif
                    </x-form.selectbox>
                    <div class="col-md-3 mb-2">
                        <label for="barcode_qty" class="form-label">No. of Barcode</label>
                        <input type="text" name="barcode_qty" id="barcode_qty" class="form-control mb-2"
                            placeholder="Enter number of barcode">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="row_qty" class="form-label">Quantity Each ROw</label>
                        <input type="text" name="row_qty" id="row_qty" class="form-control mb-2"
                            placeholder="Enter quantity each row">
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="" class="form-label">Print With</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="product_name">
                            <label class="form-check-label" for="product_name">
                                Product Name
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="price">
                            <label class="form-check-label" for="price">
                                Price
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="" class="form-label">Barcode Size</label>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="width" id="width" placeholder="Width">
                            <input type="text" class="form-control" name="height" id="height" placeholder="Height">
                            <select name="unit" id="unit" class="form-control selectpicker">
                                <option value="mm">MM</option>
                                <option value="cm">CM</option>
                                <option value="in">In</option>
                                <option value="px">PX</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 pt-24">
                        <button type="button" class="btn btn-primary me-2" id="generate_barcode"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Filter Data"><i
                                class="fas fa-barcode"></i> Generate Barcode</button>
                    </div>
                </div>
            </form>

            <div class="row" id="barcode-section">

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="js/jquery.printarea.js"></script>
<script>
    $(document).ready(function () {

        // if user add new menu
        $(document).on('click', '#generate_barcode', function () {
            let code              = $('#product option:selected').val();
            let barcode_symbology = $('#product option:selected').data('barcode');
            let name              = '';
            let price             = '';
            let barcode_qty       = $('#barcode_qty').val();
            let row_qty           = $('#row_qty').val();
            let width             = $('#width').val();
            let height            = $('#height').val();
            let unit              = $('#unit option:selected').val();
            if($('#product_name').prop('checked') == true){
                name = $('#product option:selected').data('name');
            }
            if($('#price').prop('checked') == true){
                price = $('#product option:selected').data('price');
            }
            $.ajax({
                url: "{{ route('generate.barcode') }}",
                type: "POST",
                data: {
                    code             : code,
                    name             : name,
                    price            : price,
                    barcode_qty      : barcode_qty,
                    barcode_symbology: barcode_symbology,
                    row_qty          : row_qty,
                    width            : width,
                    height           : height,
                    unit             : unit,
                    _token           : _token
                },
                beforeSend: function () {
                    $('#generate_barcode').addClass('kt-spinner kt-spinner--md kt-spinner--light');
                },
                complete: function () {
                    $('#generate_barcode').removeClass(
                        'kt-spinner kt-spinner--md kt-spinner--light');
                },
                success: function (data) {
                    $('#form-barcode').find('.is-invalid').removeClass(
                    'is-invalid');
                    $('#form-barcode').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            $('#form-barcode input#' + key).addClass(
                                'is-invalid');
                            $('#form-barcode textarea#' + key).addClass(
                                'is-invalid');
                            $('#form-barcode select#' + key).parent()
                                .addClass('is-invalid');
                            if(key == 'code'){
                                $('#form-barcode select#product').parent().addClass('is-invalid');
                                $('#form-barcode #product').parent().append(
                                '<small class="error text-danger">' + value + '</small>');
                            }
                        });
                    } else {
                        $('#barcode-section').html('');
                        $('#barcode-section').html(data);
                    }
                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        });

        // print barcode
        $(document).on('click', '#print-barcode', function(){
            var mode = 'popup';
            var close = mode == 'popup';
            var options = {
                mode : mode,
                popClose : close
            };
            $('#printableArea').printArea(options);
        });
    });

</script>
@endpush
