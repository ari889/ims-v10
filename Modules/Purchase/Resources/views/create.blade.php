@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('styles')
    <link rel="stylesheet" href="css/jquery-ui.css">
    <style>
        .ui-menu .ui-menu-item {
            padding: 10px !important;
        }
    </style>
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
                    @if (permission('purchase-access'))
                        <a href="{{ route('purchase') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    @endif
                </div>
                <hr>
                <form id="purchase-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <x-form.selectbox labelName="Warehouse" name="warehouse_id" required="required" col="col-md-6 mb-3"
                            class="selectpicker">
                            @if (!$warehouses->isEmpty())
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Supplier" name="supplier_id" required="required" col="col-md-6 mb-3"
                            class="selectpicker">
                            @if (!$suppliers->isEmpty())
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name . ' - ' . $supplier->phone }}
                                    </option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Purchase Status" name="purchase_status" required="required"
                            col="col-md-6 mb-3" class="selectpicker">
                            @foreach (PURCHASE_STATUS as $key => $value)
                                <option value="{{ $key }}" {{ $key == 1 ? 'selected' : '' }}>{{ $value }}
                                </option>
                            @endforeach
                        </x-form.selectbox>
                        <div class="col-md-6 mb-3">
                            <label for="document" class="form-label">Attach Document</label>
                            <input type="file" class="form-control" name="document" id="document">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="product_code_name" class="form-label">Select Product</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-barcode"></i></span>
                                <input type="text" class="form-control" name="product_code_name" id="product_code_name"
                                    placeholder="Enter product name or barcode and select product">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered" id="product-list">
                                <thead class="bg-primary">
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th class="text-center">Unit</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center d-none received-product-qty">Received</th>
                                    <th class="text-center">Net Unit Cost</th>
                                    <th class="text-center">Discount</th>
                                    <th class="text-center">Tax</th>
                                    <th class="text-center">Subtotal</th>
                                    <th></th>
                                </thead>
                                <tbody></tbody>
                                <tfoot class="bg-primary">
                                    <th colspan="3">Total</th>
                                    <th id="total-qty" class="text-center">0</th>
                                    <th class="d-none received-product-qty"></th>
                                    <th></th>
                                    <th id="total-discount" class="text-right">0.00</th>
                                    <th id="total-tax" class="text-right">0.00</th>
                                    <th id="total" class="text-right">0.00</th>
                                    <th></th>
                                </tfoot>
                            </table>
                        </div>
                        <x-form.selectbox labelName="Order Tax" name="order_tax_rate" required="required"
                            col="col-md-4 mb-3" class="selectpicker">
                            <option value="0" selected>No Tax</option>
                            @if (!$taxes->isEmpty())
                                @foreach ($taxes as $tax)
                                    <option value="{{ $tax->id }}">{{ $tax->name }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <div class="col-md-4 mb-3">
                            <label for="order_discount" class="form-label">Order Discount</label>
                            <input type="text" class="form-control" name="order_discount" id="order_discount"
                                placeholder="Order discount">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="shipping_cost" class="form-label">Shipping Cost</label>
                            <input type="text" class="form-control" name="shipping_cost" id="shipping_cost"
                                placeholder="Order discount">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea rows="10" cols="10" class="form-control" name="note" id="note"
                                placeholder="Enter order note"></textarea>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead class="bg-primary text-white">
                                    <td><strong>Items</strong><span class="float-end" id="item">0.00</span></td>
                                    <td><strong>Total</strong><span class="float-end" id="subtotal">0.00</span></td>
                                    <td><strong>Order Tax</strong><span class="float-end" id="order_total_tax">0.00</span>
                                    </td>
                                    <td><strong>Order Discount</strong><span class="float-end"
                                            id="order_total_discount">0.00</span></td>
                                    <td><strong>Shipping Cost</strong><span class="float-end"
                                            id="shipping_total_cost">0.00</span></td>
                                    <td><strong>Grand Total</strong><span class="float-end" id="grand_total">0.00</span>
                                    </td>
                                </thead>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <input type="hidden" name="total_qty">
                            <input type="hidden" name="total_discount">
                            <input type="hidden" name="total_tax">
                            <input type="hidden" name="total_cost">
                            <input type="hidden" name="item">
                            <input type="hidden" name="order_tax">
                            <input type="hidden" name="grand_total">
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-danger btn-sm" id="reset-btn" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Reset Data">Reset</button>
                            <button type="button" class="btn btn-primary btn-sm me-2" id="save-btn"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Save Data">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- edit modal start -->
    <div class="modal fade" id="editModal" aria-hidden="true" aria-labelledby="editModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <x-form.textbox labelName="Quantity" name="edit_qty" required="required" col="col-md-12 mb-3" />
                    <x-form.textbox labelName="Unit Discount" name="edit_discount" required="required"
                        col="col-md-12 mb-3" />
                    <x-form.textbox labelName="Unit Cost" name="edit_unit_cost" required="required"
                        col="col-md-12 mb-3" />
                    @php
                        $tax_name_all[] = 'No Tax';
                        $tax_rate_all[] = 0;
                        foreach ($taxes as $tax) {
                            $tax_name_all[] = $tax->name;
                            $tax_rate_all[] = $tax->rate;
                        }
                    @endphp
                    <div class="col-md-12 mb-3">
                        <label for="edit_tax_rate" class="form-label">Tax Rate</label>
                        <select name="edit_tax_rate" id="edit_tax_rate" class="form-control selectpicker">
                            @foreach ($tax_name_all as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="edit_unit" class="form-label">Product Unit</label>
                        <select name="edit_unit" id="edit_unit" class="form-control selectpicker"></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="update-btn">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- edit modal end -->
@endsection

{{-- @push('scripts')
    <script src="js/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            var editModal = new bootstrap.Modal(document.getElementById('editModal'), {
                keyboard: false,
                backdrop: 'static'
            });
            $('#product_code_name').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ url('product-autocomplete-search') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            _token: _token,
                            search: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                response: function(event, ui) {
                    if (ui.content.length == 1) {
                        var data = ui.content[0].value;
                        $(this).autocomplete('close');
                        product_search(data);
                    }
                },
                select: function(event, ui) {
                    var data = ui.item.value;
                    product_search(data);
                }

            }).data('ui-autocomplete')._renderItem = function(ul, item) {
                return $("<li class='ui-autocomplete-row'></li>")
                    .data("item.autocomplete", item)
                    .append(item.label)
                    .appendTo(ul);
            };

            // array data append on warehouse
            var product_array = [];
            var product_code = [];
            var product_name = [];
            var product_qty = [];

            // array data with selection
            var product_cost = [];
            var product_discount = [];
            var tax_rate = [];
            var tax_name = [];
            var tax_method = [];
            var unit_name = [];
            var unit_operator = [];
            var unit_operation_value = [];

            // temporary array
            var temp_unit_name = [];
            var temp_unit_operator = [];
            var temp_unit_operation_value = [];

            var rowindex;
            var row_product_cost;

            // edit product
            $('#product-list').on('click', '.edit-product', function() {
                rowindex = $(this).closest('tr').index();
                var row_product_name = $('#product-list tr:nth-child(' + (rowindex + 1) + ')').find(
                    'td:nth-child(1)').text();
                var row_product_code = $('#product-list tr:nth-child(' + (rowindex + 1) + ')').find(
                    'td:nth-child(2)').text();

                $('#editModal #modal-title').text(row_product_name + '(' + row_product_code + ')');
                var qty = $(this).closest('tr').find('.qty').val();
                $('#edit_qty').val(qty);
                $('#edit_discount').val(parseFloat(product_discount[rowindex]).toFixed(2));

                unitConversion();
                $('#edit_unit_cost').val(row_product_cost.toFixed(2));

                var tax_name_all = <?php echo json_encode($tax_name_all); ?>;
                var pos = tax_name_all.indexOf(tax_name[rowindex]);
                $('#edit_tax_rate').val(pos);

                temp_unit_name = (unit_name[rowindex]).split(',');
                temp_unit_name.pop();
                temp_unit_operator = (unit_operator[rowindex]).split(',');
                temp_unit_operator.pop();
                temp_unit_operation_value = (unit_operation_value[rowindex]).split(',');
                temp_unit_operation_value.pop();

                $('#edit_unit').empty();
                $.each(temp_unit_name, function(key, value) {
                    $('#edit_unit').append('<option value="' + key + '">' + value + '</option>');
                });
                $('.selectpicker').selectpicker('refresh');
            });

            // if user click update button on edit product
            $('#update-btn').on('click', function() {
                var edit_discount = $('#edit_discount').val();
                var edit_qty = $('#edit_qty').val();
                var edit_unit_cost = $('#edit_unit_cost').val();

                if (parseFloat(edit_discount) > parseFloat(edit_unit_cost)) {
                    notification('error', 'Invalid discount input');
                    return;
                }

                if (edit_qty < 1) {
                    $('#edit_qty').val(1);
                    edit_qty = 1;
                    notification('error', 'Quantity can\' be less than 1');
                }

                var row_unit_operator = unit_operator[rowindex].slice(0, unit_operator[rowindex].indexOf(
                    ','));
                var row_unit_operation_value = unit_operation_value[rowindex].slice(0, unit_operation_value[
                    rowindex].indexOf(','));
                row_unit_operation_value = parseFloat(row_unit_operation_value);
                var tax_rate_all = <?php echo json_encode($tax_rate_all); ?>

                tax_rate[rowindex] = parseFloat(tax_rate_all[$('#edit_tax_rate option:selected').val()]);
                tax_name[rowindex] = $('#edit_tax_rate option:selected').text();

                if (row_unit_operator == '*') {
                    product_cost[rowindex] = $('#edit_unit_cost').val() / row_unit_operation_value;
                } else {
                    product_cost[rowindex] = $('#edit_unit_cost').val() * row_unit_operation_value;
                }

                product_discount[rowindex] = $('#edit_discount').val();
                var position = $('#edit_unit').val();
                var temp_operator = temp_unit_operator[position];
                var temp_operation_value = temp_unit_operation_value[position];
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.purchase-unit').val(
                    temp_unit_name[position]);
                temp_unit_name.splice(position, 1);
                temp_unit_operator.splice(position, 1);
                temp_unit_operation_value.splice(position, 1);

                temp_unit_name.unshift($('#edit_unit option:selected').text());
                temp_unit_operator.unshift(temp_operator);
                temp_unit_operation_value.unshift(temp_operation_value);

                unit_name[rowindex] = temp_unit_name.toString() + ',';
                unit_operator[rowindex] = temp_unit_operator.toString() + ',';
                unit_operation_value[rowindex] = temp_unit_operation_value.toString() + ',';
                checkQuantity(edit_qty, false);
            });

            // remove product
            $('#product-list').on('click', '.remove-product', function() {
                rowindex = $(this).closest('tr').index();
                product_cost.splice(rowindex, 1);
                product_discount.splice(rowindex, 1);
                tax_rate.splice(rowindex, 1);
                tax_name.splice(rowindex, 1);
                tax_method.splice(rowindex, 1);
                unit_name.splice(rowindex, 1);
                unit_operator.splice(rowindex, 1);
                unit_operation_value.splice(rowindex, 1);
                $(this).closest('tr').remove();
                calculateTotal();
            });

            // change product quantity
            $('#product-list').on('keyup', '.qty', function() {
                rowindex = $(this).closest('tr').index();
                if ($(this).val() < 1 && $(this).val() != '') {
                    $('#product-list tbody tr:nth-child(' + rowindex + ') .qty').val(1);
                    notification('error', 'Quantity can\'t be less than 1');
                }

                checkQuantity($(this).val(), true);
            });

            // search product by name or barcode
            var count = 1;

            function product_search(data) {
                $.ajax({
                    url: '{{ route('product.search') }}',
                    type: "POST",
                    data: {
                        data: data,
                        _token: _token,
                        type: 'purchase'
                    },
                    success: function(data) {
                        var flag = 1;
                        $('.product-code').each(function(i) {
                            if ($(this).val() == data.code) {
                                rowindex = 1;
                                var qty = parseFloat($('#product-list tbody tr:nth-child(' + (
                                    rowindex + 1) + ') .qty').val()) + 1;
                                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')')
                                    .val(qty);
                                calculateProductData(qty);
                                flag = 0;
                            }
                        });
                        $('#product_code_name').val('');
                        if (flag) {
                            temp_unit_name = data.unit_name.split(',');
                            var newRow = $('<tr>');
                            var cols = '';
                            cols += `<td>${data.name}</td>`;
                            cols += `<td>${data.code}</td>`;
                            cols += `<td class="unit_name"></td>`;
                            cols +=
                                `<td><input type="text" class="form-control qty text-center" name="products[${count}][qty]" id="products_${count}_qty" value="1"></td>`;
                            if ($('#purchase_status option:selected').val() == 1) {
                                cols +=
                                    `<td class="received-product-qty d-none"><input type="text" class="form-control received text-center" name="products[${count}][received]" value="1"></td>`;
                            } else if ($('#purchase_status option:selected').val() == 2) {
                                cols +=
                                    `<td class="received-product-qty"><input type="text" class="form-control received text-center" name="products[${count}][received]" value="1"></td>`;
                            } else {
                                cols +=
                                    `<td class="received-product-qty d-none"><input type="text" class="form-control received text-center" name="products[${count}][received]" value="0"></td>`;
                            }

                            cols += `<td class="net_unit_cost text-right"></td>`;
                            cols += `<td class="discount text-right"></td>`;
                            cols += `<td class="tax text-right"</td>`;
                            cols += `<td class="sub-total text-right"></td>`;
                            cols += `<td>
                                    <button type="button" class="edit-product btn btn-sm btn-primary mr-2" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fas fa-edit"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger mr-2 remove-product"><i class="fas fa-trash"></i></button>
                                </td>`;
                            cols +=
                                `<input type="hidden" class="product-id" name="products[${count}][id]" value="${data.id}">`;
                            cols +=
                                `<input type="hidden" class="product-code" name="products[${count}][code]" value="${data.code}">`;
                            cols +=
                                `<input type="hidden" class="product-unit" name="products[${count}][unit]" value="${temp_unit_name[0]}">`;
                            cols +=
                                `<input type="hidden" class="net_unit_cost" name="products[${count}][net_unit_cost]">`;
                            cols +=
                                `<input type="hidden" class="discount-value" name="products[${count}][discount]">`;
                            cols +=
                                `<input type="hidden" class="tax-rate" name="products[${count}][tax_rate]" value="${data.tax_rate}">`;
                            cols +=
                                `<input type="hidden" class="tax-value" name="products[${count}][tax]">`;
                            cols +=
                                `<input type="hidden" class="subtotal-value" name="products[${count}][subtotal]">`;

                            newRow.append(cols);
                            $('#product-list tbody').append(newRow);
                            product_cost.push(parseFloat(data.cost));
                            product_discount.push('0.00');
                            tax_rate.push(parseFloat(data.tax_rate));
                            tax_name.push(data.tax_name);
                            tax_method.push(data.tax_method);
                            unit_name.push(data.unit_name);
                            unit_operator.push(data.unit_operator);
                            unit_operation_value.push(data.unit_operation_value);
                            rowindex = newRow.index();
                            calculateProductData(1);
                            count++;
                        }
                    }
                });
            }

            // check product quantity
            function checkQuantity(purchase_qty, flag) {
                var row_product_code = $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                    'td:nth-child(2)').text();
                var pos = product_code.indexOf(row_product_code);
                var operator = unit_operator[rowindex].split(',');
                var operation_value = unit_operation_value[rowindex].split(',');

                if (operator[0] == '*') {
                    total_qty = purchase_qty * operation_value[0];
                } else if (operator[0] == '/') {
                    total_qty = purchase_qty / operation_value[0];
                }

                editModal.hide();
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(purchase_qty);
                var status = $('#purchase_status option:selected').val();
                if (status == '1' || status == '2') {
                    $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.received').val(
                        purchase_qty);
                }
                calculateProductData(purchase_qty);
            }

            // calculate product data
            function calculateProductData(quantity) {
                unitConversion();
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(7)').text((
                    product_discount[rowindex] * quantity).toFixed(2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.discount-value').val((
                    product_discount[rowindex] * quantity).toFixed(2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-rate').val(tax_rate[
                    rowindex].toFixed(2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.unit-name').text(unit_name[
                    rowindex].slice(0, unit_name[rowindex].indexOf(",")));

                if (tax_method[rowindex] == 1) {
                    var net_unit_cost = row_product_cost - product_discount[rowindex];
                    var tax = net_unit_cost * quantity * (tax_rate[rowindex] / 100);
                    var sub_total = (net_unit_cost * quantity) + tax;
                } else {
                    var subtotal_unit = row_product_cost - product_discount[rowindex];
                    var net_unit_cost = (100 / (100 + tax_rate[rowindex])) * sub_total_unit;
                    var tax = (sub_total_unit - net_unit_cost) * quantity;
                    var sub_total = sub_total_unit * quantity;
                }

                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(6)').text(
                    net_unit_cost.toFixed(2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_cost').val(
                    net_unit_cost.toFixed(2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(8)').text(tax
                    .toFixed(2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-value').val(tax.toFixed(
                    2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(9)').text(sub_total
                    .toFixed(2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.subtotal-value').val(sub_total
                    .toFixed(2));

                calculateTotal();
            }

            //unit conversion
            function unitConversion() {
                var row_unit_operator = unit_operator[rowindex].slice(0, unit_operator[rowindex].indexOf(','));
                var row_unit_operation_value = unit_operation_value[rowindex].slice(0, unit_operation_value[
                    rowindex].indexOf(","));
                row_unit_operation_value = parseFloat(row_unit_operation_value);
                if (row_unit_operation_value == '*') {
                    row_product_cost = product_cost[rowindex] * row_unit_operation_value;
                } else {
                    row_product_cost = product_cost[rowindex] / row_unit_operation_value;
                }
            }

            // calculate total
            function calculateTotal() {

                // sum of quantity
                var total_qty = 0;
                $('.qty').each(function() {
                    if ($(this).val() == '') {
                        total_qty += 0;
                    } else {
                        total_qty += parseFloat($(this).val());
                    }
                });
                $('#total-qty').text(total_qty);
                $('input[name="total_qty"]').val(total_qty);

                // sum of discount
                var total_discount = 0;
                $('.discount').each(function() {
                    total_discount += parseFloat($(this).text());
                });
                $('#total-discount').text(total_discount.toFixed(2));
                $('input[name="total_discount"]').val(total_discount.toFixed(2));

                // sum of tax
                var total_tax = 0;
                $('.tax').each(function() {
                    total_tax += parseFloat($(this).text());
                })
                $('#total-tax').text(total_tax.toFixed(2));
                $('input[name="total_tax"]').val(total_tax.toFixed(2));

                // sub of subtotal
                var total = 0;
                $('.sub-total').each(function() {
                    total += parseFloat($(this).text());
                });
                $('#total').text(total.toFixed(2));
                $('input[name="total_cost"]').val(total.toFixed(2));

                calculateGrandTotal();
            }

            // calculate grand total
            function calculateGrandTotal() {
                var item = $('#product-list tbody tr:last').index();
                var total_qty = parseFloat($('#total-qty').text());
                var subtotal = parseFloat($('#total').text());
                var order_tax = parseFloat($('select[name="order_tax_rate"]').val());
                var order_discount = parseFloat($('#order_discount').val());
                var shipping_cost = parseFloat($('#shipping_cost').val());

                if (!order_discount) {
                    order_discount = 0.00;
                }
                if (!shipping_cost) {
                    shipping_cost = 0.00;
                }

                item = ++item + '(' + total_qty + ')';
                order_tax = (subtotal - order_discount) * (order_tax / 100);
                var grand_total = (subtotal + order_tax + shipping_cost) - order_discount;

                $('#item').text(item);
                $('input[name="item"]').val($('#product-list tbody tr:last').index() + 1);
                $('#subtotal').text(subtotal.toFixed(2));
                $('#order_total_tax').text(order_tax.toFixed(2));
                $('input[name="order_tax"]').val(order_tax.toFixed(2));
                $('#order_total_discount').text(order_discount.toFixed(2));
                $('#shipping_total_cost').text(shipping_cost.toFixed(2));
                $('#grand_total').text(grand_total.toFixed(2));
                $('input[name="grand_total"]').val(grand_total.toFixed(2));
            }

            // if user change order discount
            $('input[name="order_discount"]').on('input', function() {
                calculateGrandTotal();
            });

            // if user change shipping cost
            $('input[name="shipping_cost"]').on('input', function() {
                calculateGrandTotal();
            });

            // if user change order tax
            $('select[name="order_tax_rate"]').on('change', function() {
                calculateGrandTotal();
            });


            // if user change purchase status
            $('#purchase_status').on('change', function() {
                var status = $('#purchase_status option:selected').val();
                if (status == 2) {
                    $('.received-product-qty').removeClass('d-none');
                    $('.qty').each(function() {
                        rowindex = $(this).closest('tr').index();
                        $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                            '.received').val($(this).val());
                    });
                } else if (status == 3 || status == 4) {
                    $('.received-product-qty').addClass('d-none');
                    $('.received').each(function() {
                        $(this).val(0);
                    });
                } else {
                    $('.received-product-qty').addClass('d-none');
                    $('.qty').each(function() {
                        rowindex = $(this).closest('tr').index();
                        $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                            '.redeived').val($(this).val());
                    });
                }
            });

            // store purchase data
            $(document).on('click', '#save-btn', function(e) {
                e.preventDefault();
                var rownumber = $('#product-list tbody tr:last').index();
                if (rownumber < 0) {
                    notification('error', 'Please add product to order table');
                } else {
                    let form = document.getElementById('purchase-form');
                    let formData = new FormData(form);
                    $.ajax({
                        url: "{{ route('purchase.store') }}",
                        type: "POST",
                        data: formData,
                        dataType: 'JSON',
                        contentType: false,
                        processData: false,
                        cache: false,
                        beforeSend: function() {
                            $('#save-btn').addClass(
                                'kt-spinner kt-spinner--md kt-spinner--light');
                        },
                        complete: function() {
                            $('#save-btn').removeClass(
                                'kt-spinner kt-spinner--md kt-spinner--light');
                        },
                        success: function(data) {
                            $('#purchase-form').find('.is-invalid').removeClass('is-invalid');
                            $('#purchase-form').find('.error').remove();
                            if (data.status == false) {
                                $.each(data.errors, function(key, value) {
                                    var key = key.split('.').join('_');
                                    $('#purchase-form input#' + key).addClass(
                                        'is-invalid');
                                    $('#purchase-form textarea#' + key).addClass(
                                        'is-invalid');
                                    $('#purchase-form select#' + key).parent().addClass(
                                        'is-invalid');
                                    $('#purchase-form #' + key).parent().append(
                                        '<small class="error text-danger">' +
                                        value + '</small>');

                                });
                            } else {
                                notification(data.status, data.message);
                                if (data.status == 'success') {
                                    window.location.replace('{{ route('purchase') }}');
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush --}}

@push('scripts')
    <script src="js/jquery-ui.js"></script>
    <script>
        var editModal = new bootstrap.Modal(document.getElementById('editModal'), {
            keyboard: false,
            backdrop: 'static'
        });
        $(document).ready(function() {
            $('#product_code_name').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ url('product-autocomplete-search') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            _token: _token,
                            search: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                response: function(event, ui) {
                    if (ui.content.length == 1) {
                        var data = ui.content[0].value;
                        $(this).autocomplete('close');
                        product_search(data);
                    }
                },
                select: function(event, ui) {
                    var data = ui.item.value;
                    product_search(data);
                }

            }).data('ui-autocomplete')._renderItem = function(ul, item) {
                return $("<li class='ui-autocomplete-row'></li>")
                    .data("item.autocomplete", item)
                    .append(item.label)
                    .appendTo(ul);
            };

            //array data depend on warehouse
            var product_array = [];
            var product_code = [];
            var product_name = [];
            var product_qty = [];

            // array data with selection
            var product_cost = [];
            var product_discount = [];
            var tax_rate = [];
            var tax_name = [];
            var tax_method = [];
            var unit_name = [];
            var unit_operator = [];
            var unit_operation_value = [];

            //temporary array
            var temp_unit_name = [];
            var temp_unit_operator = [];
            var temp_unit_operation_value = [];

            var rowindex;
            var row_product_cost;

            //Edit Product
            $('#product-list').on('click', '.edit-product', function() {
                rowindex = $(this).closest('tr').index();
                var row_product_name = $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                    'td:nth-child(1)').text();
                var row_product_code = $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                    'td:nth-child(2)').text();
                $('#model-title').text(row_product_name + '(' + row_product_code + ')');

                var qty = $(this).closest('tr').find('.qty').val();
                $('#edit_qty').val(qty);
                $('#edit_discount').val(parseFloat(product_discount[rowindex]).toFixed(2));

                unitConversion();
                $('#edit_unit_cost').val(row_product_cost.toFixed(2));

                var tax_name_all = <?php echo json_encode($tax_name_all); ?>;
                var pos = tax_name_all.indexOf(tax_name[rowindex]);
                $('#edit_tax_rate').val(pos);

                temp_unit_name = (unit_name[rowindex]).split(',');
                temp_unit_name.pop();
                temp_unit_operator = (unit_operator[rowindex]).split(',');
                temp_unit_operator.pop();
                temp_unit_operation_value = (unit_operation_value[rowindex]).split(',');
                temp_unit_operation_value.pop();

                $('#edit_unit').empty();

                $.each(temp_unit_name, function(key, value) {
                    $('#edit_unit').append('<option value="' + key + '">' + value + '</option>');
                });
                $('.selectpicker').selectpicker('refresh');
            });

            //Update Edit Product Data
            $('#update-btn').on('click', function() {
                var edit_discount = $('#edit_discount').val();
                var edit_qty = $('#edit_qty').val();
                var edit_unit_cost = $('#edit_unit_cost').val();

                if (parseFloat(edit_discount) > parseFloat(edit_unit_cost)) {
                    notification('error', 'Invalid discount input');
                    return;
                }

                if (edit_qty < 1) {
                    $('#edit_qty').val(1);
                    edit_qty = 1;
                    notification('error', 'Quantity can\'t be less than 1');
                }

                var row_unit_operator = unit_operator[rowindex].slice(0, unit_operator[rowindex].indexOf(
                    ','));
                var row_unit_operation_value = unit_operation_value[rowindex].slice(0, unit_operation_value[
                    rowindex].indexOf(','));
                row_unit_operation_value = parseFloat(row_unit_operation_value);
                var tax_rate_all = <?php echo json_encode($tax_rate_all); ?>;

                tax_rate[rowindex] = parseFloat(tax_rate_all[$('#edit_tax_rate option:selected').val()]);
                tax_name[rowindex] = $('#edit_tax_rate option:selected').text();

                if (row_unit_operator == '*') {
                    product_cost[rowindex] = $('#edit_unit_cost').val() / row_unit_operation_value;
                } else {
                    product_cost[rowindex] = $('#edit_unit_cost').val() * row_unit_operation_value;
                }

                product_discount[rowindex] = $('#edit_discount').val();
                var position = $('#edit_unit').val();
                var temp_operator = temp_unit_operator[position];
                var temp_operation_value = temp_unit_operation_value[position];
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.purchase-unit').val(
                    temp_unit_name[position]);
                temp_unit_name.splice(position, 1);
                temp_unit_operator.splice(position, 1);
                temp_unit_operation_value.splice(position, 1);

                temp_unit_name.unshift($('#edit_unit option:selected').text());
                temp_unit_operator.unshift(temp_operator);
                temp_unit_operation_value.unshift(temp_operation_value);

                unit_name[rowindex] = temp_unit_name.toString() + ',';
                unit_operator[rowindex] = temp_unit_operator.toString() + ',';
                unit_operation_value[rowindex] = temp_unit_operation_value.toString() + ',';
                checkQuantity(edit_qty, false);
            });

            $('#product-list').on('keyup', '.qty', function() {
                rowindex = $(this).closest('tr').index();
                if ($(this).val() < 1 && $(this).val() != '') {
                    $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(1);
                    notification('error', 'Qunatity can\'t be less than 1');
                }

                checkQuantity($(this).val(), true);
            });

            $('#product-list').on('click', '.remove-product', function() {
                rowindex = $(this).closest('tr').index();
                product_cost.splice(rowindex, 1);
                product_discount.splice(rowindex, 1);
                tax_rate.splice(rowindex, 1);
                tax_name.splice(rowindex, 1);
                tax_method.splice(rowindex, 1);
                unit_name.splice(rowindex, 1);
                unit_operator.splice(rowindex, 1);
                unit_operation_value.splice(rowindex, 1);
                $(this).closest('tr').remove();
                calculateTotal();
            });

            var count = 1;

            function product_search(data) {
                $.ajax({
                    url: "{{ url('product-search') }}",
                    type: "POST",
                    data: {
                        data: data,
                        _token: _token,
                        type: 'purchase'
                    },
                    success: function(data) {
                        var flag = 1;
                        $('.product-code').each(function(i) {
                            if ($(this).val() == data.code) {
                                rowindex = i;
                                var qty = parseFloat($('#product-list tbody tr:nth-child(' + (
                                    rowindex + 1) + ') .qty').val()) + 1;
                                $('#product-list tbody tr:nth-child(' + (rowindex + 1) +
                                    ') .qty').val(qty);
                                calculateProductData(qty);
                                flag = 0;
                            }
                        });
                        $('#product_code_name').val('');
                        if (flag) {
                            temp_unit_name = data.unit_name.split(',');
                            var newRow = $('<tr>');
                            var cols = '';
                            cols += `<td>` + data.name + `</td>`;

                            cols += `<td>` + data.code + `</td>`;
                            cols += `<td class="unit-name"></td>`;
                            cols +=
                                `<td><input type="text" class="form-control qty text-center" name="products[` +
                                count + `][qty]"
                        id="products_` + count + `_qty" value="1"></td>`;

                            if ($('#purchase_status option:selected').val() == 1) {
                                cols += `<td class="received-product-qty d-none"><input type="text" class="form-control received text-center"
                            name="products[` + count + `][received]" value="1"></td>`;

                            } else if ($('#purchase_status option:selected').val() == 2) {

                                cols += `<td class="received-product-qty"><input type="text" class="form-control received text-center"
                            name="products[` + count + `][received]" value="1"></td>`;
                            } else {
                                cols += `<td class="received-product-qty d-none"><input type="text" class="form-control received text-center"
                            name="products[` + count + `][received]" value="0"></td>`;
                            }

                            cols += `<td class="net_unit_cost text-right"></td>`;
                            cols += `<td class="discount text-right"></td>`;
                            cols += `<td class="tax text-right"></td>`;
                            cols += `<td class="sub-total text-right"></td>`;
                            cols +=
                                `<td><button type="button" class="edit-product btn btn-sm btn-primary mr-2" data-bs-toggle="modal"
                        data-bs-target="#editModal"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm remove-product"><i class="fas fa-trash"></i></button></td>`;
                            cols += `<input type="hidden" class="product-id" name="products[` + count +
                                `][id]"  value="` + data.id + `">`;
                            cols += `<input type="hidden" class="product-code" name="products[` +
                                count + `][code]" value="` + data.code + `">`;
                            cols += `<input type="hidden" class="product-unit" name="products[` +
                                count + `][unit]" value="` + temp_unit_name[0] + `">`;
                            cols += `<input type="hidden" class="net_unit_cost" name="products[` +
                                count + `][net_unit_cost]">`;
                            cols += `<input type="hidden" class="discount-value" name="products[` +
                                count + `][discount]">`;
                            cols += `<input type="hidden" class="tax-rate" name="products[` + count +
                                `][tax_rate]" value="` + data.tax_rate + `">`;
                            cols += `<input type="hidden" class="tax-value" name="products[` + count +
                                `][tax]">`;
                            cols += `<input type="hidden" class="subtotal-value" name="products[` +
                                count + `][subtotal]">`;

                            newRow.append(cols);
                            $('#product-list tbody').append(newRow);

                            product_cost.push(parseFloat(data.cost));
                            product_discount.push('0.00');
                            tax_rate.push(parseFloat(data.tax_rate));
                            tax_name.push(data.tax_name);
                            tax_method.push(data.tax_method);
                            unit_name.push(data.unit_name);
                            unit_operator.push(data.unit_operator);
                            unit_operation_value.push(data.unit_operation_value);
                            rowindex = newRow.index();
                            calculateProductData(1);
                            count++;
                        }

                    }
                });
            }

            function checkQuantity(purchase_qty, flag) {
                var row_product_code = $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                    'td:nth-child(2)').text();
                var pos = product_code.indexOf(row_product_code);
                var operator = unit_operator[rowindex].split(',');
                var operation_value = unit_operation_value[rowindex].split(',');

                if (operator[0] == '*') {
                    total_qty = purchase_qty * operation_value[0];
                } else if (operator[0] == '/') {
                    total_qty = purchase_qty / operation_value[0];
                }

                editModal.hide();
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(purchase_qty);
                var status = $('#purchase_status option:selected').val();
                if (status == '1' || status == '2') {
                    $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.received').val(
                        purchase_qty);
                }
                calculateProductData(purchase_qty);

            }

            function calculateProductData(quantity) {
                unitConversion();
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(7)').text((
                    product_discount[rowindex] * quantity).toFixed(2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.discount-value').val((
                    product_discount[rowindex] * quantity).toFixed(2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-rate').val(tax_rate[
                    rowindex].toFixed(2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.unit-name').text(unit_name[
                    rowindex].slice(0, unit_name[rowindex].indexOf(",")));

                if (tax_method[rowindex] == 1) {
                    var net_unit_cost = row_product_cost - product_discount[rowindex];
                    var tax = net_unit_cost * quantity * (tax_rate[rowindex] / 100);
                    var sub_total = (net_unit_cost * quantity) + tax;

                } else {
                    var sub_total_unit = row_product_cost - product_discount[rowindex];
                    var net_unit_cost = (100 / (100 + tax_rate[rowindex])) * sub_total_unit;
                    var tax = (sub_total_unit - net_unit_cost) * quantity;
                    var sub_total = sub_total_unit * quantity;
                }
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(6)').text(
                    net_unit_cost.toFixed(2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_cost').val(
                    net_unit_cost.toFixed(2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(8)').text(tax
                    .toFixed(2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-value').val(tax.toFixed(
                    2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(9)').text(sub_total
                    .toFixed(2));
                $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.subtotal-value').val(sub_total
                    .toFixed(2));

                calculateTotal();
            }

            function unitConversion() {
                var row_unit_operator = unit_operator[rowindex].slice(0, unit_operator[rowindex].indexOf(','));
                var row_unit_operation_value = unit_operation_value[rowindex].slice(0, unit_operation_value[
                    rowindex].indexOf(','));
                row_unit_operation_value = parseFloat(row_unit_operation_value);
                if (row_unit_operator == '*') {
                    row_product_cost = product_cost[rowindex] * row_unit_operation_value;
                } else {
                    row_product_cost = product_cost[rowindex] / row_unit_operation_value;
                }
            }

            function calculateTotal() {
                //sum of qty
                var total_qty = 0;
                $('.qty').each(function() {
                    if ($(this).val() == '') {
                        total_qty += 0;
                    } else {
                        total_qty += parseFloat($(this).val());
                    }
                });
                $('#total-qty').text(total_qty);
                $('input[name="total_qty"]').val(total_qty);

                //sum of discount
                var total_discount = 0;
                $('.discount').each(function() {
                    total_discount += parseFloat($(this).text());
                });
                $('#total-discount').text(total_discount.toFixed(2));
                $('input[name="total_discount"]').val(total_discount.toFixed(2));

                //sum of tax
                var total_tax = 0;
                $('.tax').each(function() {
                    total_tax += parseFloat($(this).text());
                });
                $('#total-tax').text(total_tax.toFixed(2));
                $('input[name="total_tax"]').val(total_tax.toFixed(2));

                //sum of subtotal
                var total = 0;
                $('.sub-total').each(function() {
                    total += parseFloat($(this).text());
                });
                $('#total').text(total.toFixed(2));
                $('input[name="total_cost"]').val(total.toFixed(2));

                calculateGrandTotal();
            }

            function calculateGrandTotal() {
                var item = $('#product-list tbody tr:last').index();
                var total_qty = parseFloat($('#total-qty').text());
                var subtotal = parseFloat($('#total').text());
                var order_tax = parseFloat($('select[name="order_tax_rate"]').val());
                var order_discount = parseFloat($('#order_discount').val());
                var shipping_cost = parseFloat($('#shipping_cost').val());

                if (!order_discount) {
                    order_discount = 0.00;
                }
                if (!shipping_cost) {
                    shipping_cost = 0.00;
                }

                item = ++item + '(' + total_qty + ')';
                order_tax = (subtotal - order_discount) * (order_tax / 100);
                var grand_total = (subtotal + order_tax + shipping_cost) - order_discount;

                $('#item').text(item);
                $('input[name="item"]').val($('#product-list tbody tr:last').index() + 1);
                $('#subtotal').text(subtotal.toFixed(2));
                $('#order_total_tax').text(order_tax.toFixed(2));
                $('input[name="order_tax"]').val(order_tax.toFixed(2));
                $('#order_total_discount').text(order_discount.toFixed(2));
                $('#shipping_total_cost').text(shipping_cost.toFixed(2));
                $('#grand_total').text(grand_total.toFixed(2));
                $('input[name="grand_total"]').val(grand_total.toFixed(2));
            }

            $('input[name="order_discount"]').on('input', function() {
                calculateGrandTotal();
            });
            $('input[name="shipping_cost"]').on('input', function() {
                calculateGrandTotal();
            });
            $('select[name="order_tax_rate"]').on('change', function() {
                calculateGrandTotal();
            });


            $('#purchase_status').on('change', function() {
                var status = $('#purchase_status option:selected').val();
                if (status == 2) {
                    $('.received-product-qty').removeClass('d-none');
                    $('.qty').each(function() {
                        rowindex = $(this).closest('tr').index();
                        $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                            '.received').val($(this).val());
                    });
                } else if (status == 3 || status == 4) {
                    $('.received-product-qty').addClass('d-none');
                    $('.received').each(function() {
                        $(this).val(0)
                    });
                } else {
                    $('.received-product-qty').addClass('d-none');
                    $('.qty').each(function() {
                        rowindex = $(this).closest('tr').index();
                        $('#product-list tbody tr:nth-child(' + (rowindex + 1) + ')').find(
                            '.received').val($(this).val());
                    });
                }
            });

            $(document).on('click', '#save-btn', function(e) {
                e.preventDefault();

                var rownumber = $('#product-list tbody tr:last').index();
                if (rownumber < 0) {
                    notification('error', 'Please add product to order table');
                } else {
                    let form = document.getElementById('purchase-form');
                    let formData = new FormData(form);
                    $.ajax({
                        url: "{{ route('purchase.store') }}",
                        type: "POST",
                        data: formData,
                        dataType: "JSON",
                        contentType: false,
                        processData: false,
                        cache: false,
                        beforeSend: function() {
                            $('#save-btn').addClass(
                                'kt-spinner kt-spinner--md kt-spinner--light');
                        },
                        complete: function() {
                            $('#save-btn').removeClass(
                                'kt-spinner kt-spinner--md kt-spinner--light');
                        },
                        success: function(data) {
                            $('#purchase-form').find('.is-invalid').removeClass('is-invalid');
                            $('#purchase-form').find('.error').remove();
                            if (data.status == false) {
                                $.each(data.errors, function(key, value) {
                                    var key = key.split('.').join('_');
                                    $('#purchase-form input#' + key).addClass(
                                        'is-invalid');
                                    $('#purchase-form textarea#' + key).addClass(
                                        'is-invalid');
                                    $('#purchase-form select#' + key).parent().addClass(
                                        'is-invalid');
                                    $('#purchase-form #' + key).parent().append(
                                        '<small class="error text-danger">' +
                                        value + '</small>');

                                });
                            } else {
                                notification(data.status, data.message);
                                if (data.status == 'success') {
                                    window.location.replace('{{ route('purchase') }}');
                                }
                            }

                        },
                        error: function(xhr, ajaxOption, thrownError) {
                            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr
                                .responseText);
                        }
                    });
                }
            });


        });
    </script>
@endpush
