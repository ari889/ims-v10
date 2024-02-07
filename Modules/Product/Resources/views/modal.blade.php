<div class="modal fade" id="store_or_update_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-1"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="store_or_update_form">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="update_id" id="update_id" />

                        <div class="col-md-9">
                            <div class="row">
                              <x-form.textbox labelName="Name" name="name" required="required" col="col-md-6 mb-2"
                                placeholder="Enter name" />
                            <x-form.selectbox labelName="Barcode Symbology" name="barcode_symbology" required="required" col="col-md-6 mb-2"
                                class="selectpicker">
                                @foreach (BARCODE_SYMBOLOGY as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <div class="form-group col-md-6 mb-2 required">
                                <label for="code">Barcode</label>
                                <div class="input-group">
                                    <input type="code" class="form-control" name="code" id="code" placeholder="Generated Barcode">
                                    <span class="input-group-text bg-primary" id="generate_barcode" data-toggle="tooltip" data-placement="top" data-original-title="Generate Password">
                                        <i class="fas fa-retweet text-white" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>
                            <x-form.selectbox labelName="Brand" name="brand_id" col="col-md-6 mb-2" class="selectpicker">
                                @if (!$brands->isEmpty())
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->title }}</option>
                                @endforeach
                                @endif
                            </x-form.selectbox>
                            <x-form.selectbox labelName="Category" name="category_id" required="required" col="col-md-6 mb-2"
                                class="selectpicker">
                                @if (!$categories->isEmpty())
                                @foreach ($categories as $catgory)
                                <option value="{{ $catgory->id }}">{{ $catgory->name }}</option>
                                @endforeach
                                @endif
                            </x-form.selectbox>
                            <x-form.selectbox labelName="Unit" name="unit_id" col="col-md-6 mb-2" required="required"
                                class="selectpicker" onchange="populate_unit(this.value)">
                                @if (!$units->isEmpty())
                                @foreach ($units as $unit)
                                @if ($unit->base_unit == null)
                                <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                @endif
                                @endforeach
                                @endif
                            </x-form.selectbox>
                            
                            <x-form.selectbox labelName="Purchase Unit" name="purchase_unit_id" required="required" col="col-md-6 mb-2"
                                class="selectpicker"></x-form.selectbox>
                            <x-form.selectbox labelName="Sale Unit" name="sale_unit_id" required="required" col="col-md-6 mb-2"
                                class="selectpicker"></x-form.selectbox>

                                <x-form.textbox labelName="Cost" name="cost" required="required" col="col-md-6 mb-2"
                                placeholder="0.00" />

                                <x-form.textbox labelName="Price" name="price" required="required" col="col-md-6 mb-2"
                                placeholder="0.00" />

                                <x-form.textbox labelName="Quantity" name="qty" col="col-md-6 mb-2"
                                placeholder="0.00" />

                                <x-form.textbox labelName="Alert Quantity" name="alert_qty" col="col-md-6 mb-2"
                                placeholder="0.00" />


                                <div class="form-group col-md-6 mb-2">
                                  <label for="">Tax</label>
                                  <select name="tax_id" id="tax_id" class="form-control selectpicker" required="required" data-live-search="true" 
                                  data-live-search-placeholder="Search">
                                    <option value="">No Tax</option>
                                    @if (!$taxes->isEmpty())
                                    @foreach ($taxes as $tax)
                                    <option value="{{ $tax->id }}">{{ $tax->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                </div>

                                <x-form.selectbox labelName="Tax Method" name="tax_method" required="required" col="col-md-6 mb-2"
                                class="selectpicker">
                                @foreach (TAX_METHOD as $key => $method)
                                <option value="{{ $key }}" {{ $key == 1 ? 'selected' : '' }}>{{ $method }}</option>
                                @endforeach
                            </x-form.selectbox>

                            <x-form.textarea labelName="Description" name="description" col="col-md-12" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group col-md-12 required">
                                <label for="image">Product Image</label>
                                <div class="col-md-12 px-0 text-center">
                                    <div id="image">

                                    </div>
                                </div>
                                <input type="hidden" name="old_image" id="old_image">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
                </div>
            </form>
        </div>
    </div>
</div>
