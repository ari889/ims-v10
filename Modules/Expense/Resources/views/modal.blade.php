<div class="modal fade" id="store_or_update_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
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
                        <x-form.selectbox labelName="Expense Category" name="expense_category_id" required="required"
                            col="col-md-12" class="selectpicker">
                            @if (!$categories->isEmpty())
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Warehouse" name="warehouse_id" required="required" col="col-md-12"
                            class="selectpicker">
                            @if (!$warehouses->isEmpty())
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Account" name="account_id" required="required" col="col-md-12"
                            class="selectpicker">
                            @if (!$accounts->isEmpty())
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->name . ' - ' . $account->account_no }}
                                    </option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <x-form.textbox labelName="Amount" name="amount" required="required" col="col-md-12"
                            placeholder="0" />
                        <x-form.textarea labelName="Expense Note" name="note" required="required" col="col-md-12"
                            placeholder="Enter expense note" />
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
