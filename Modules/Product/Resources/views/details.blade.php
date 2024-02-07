<div class="col-md-12">
    @if(!empty($product->image))
    <div class="col-md-12 text-center">
        <img style="width:100px" src="{{ asset('storage/'.PRODUCT_IMAGE_PATH.$product->image) }}" alt="{{ $product->name }}">
    </div>
    @endif
    <div class="table-responsive">
        <table class="table table-borderless">
            <tr>
                <td><b>Product Name</b></td>
                <td><b>:</b></td>
                <td>{{ $product->name }}</td>
            </tr>
            <tr>
                <td><b>Code</b></td>
                <td><b>:</b></td>
                <td>{{ $product->code }}</td>
            </tr>
            <tr>
                <td><b>Brand</b></td>
                <td><b>:</b></td>
                <td>{{ $product->brand->title }}</td>
            </tr>
            <tr>
                <td><b>Category</b></td>
                <td><b>:</b></td>
                <td>{{ $product->category->name }}</td>
            </tr>
            <tr>
                <td><b>Cost</b></td>
                <td><b>:</b></td>
                <td>{{ number_format($product->cost, 2) }}</td>
            </tr>
            <tr>
                <td><b>Price</b></td>
                <td><b>:</b></td>
                <td>{{ number_format($product->price, 2) }}</td>
            </tr>
            <tr>
                <td><b>Quantity</b></td>
                <td><b>:</b></td>
                <td>{{ $product->qty }}</td>
            </tr>
            <tr>
                <td><b>Alert Quantity</b></td>
                <td><b>:</b></td>
                <td>{{ $product->alert_qty }}</td>
            </tr>
            <tr>
                <td><b>Unite</b></td>
                <td><b>:</b></td>
                <td>{{ $product->unit->unit_name }}</td>
            </tr>
            <tr>
                <td><b>Purchase Unit</b></td>
                <td><b>:</b></td>
                <td>{{ $product->purchase_unit->unit_name }}</td>
            </tr>
            <tr>
                <td><b>Sale Unit</b></td>
                <td><b>:</b></td>
                <td>{{ $product->sale_unit->unit_name }}</td>
            </tr>
            <tr>
                <td><b>Tax</b></td>
                <td><b>:</b></td>
                <td>{{ $product->tax->name }}</td>
            </tr>
            <tr>
                <td><b>Tax Method</b></td>
                <td><b>:</b></td>
                <td>{{ TAX_METHOD[$product->tax_method] }}</td>
            </tr>
            <tr>
                <td><b>Status</b></td>
                <td><b>:</b></td>
                <td>{{ STATUS[$product->status] }}</td>
            </tr>
            <tr>
                <td><b>Created By</b></td>
                <td><b>:</b></td>
                <td>{{ $product->created_by }}</td>
            </tr>
            <tr>
                <td><b>Updated By</b></td>
                <td><b>:</b></td>
                <td>{{ $product->updated_by }}</td>
            </tr>
            <tr>
                <td><b>Joining Date</b></td>
                <td><b>:</b></td>
                <td>{{ date(config('settings.date_format'), strtotime($product->created_at)) }}</td>
            </tr>
            <tr>
                <td><b>Updated Date</b></td>
                <td><b>:</b></td>
                <td>{{ date(config('settings.date_format'), strtotime($product->updated_at)) }}</td>
            </tr>
        </table>
    </div>
</div>