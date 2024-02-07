<div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <!-- Modal Content -->
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="model-1"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <!-- /modal header -->
            <form id="payment_form" method="post">
                @csrf
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="payment_id" id="payment_id" />
                        <input type="hidden" name="purchase_id" id="purchase_id" />
                        <input type="hidden" name="balance" id="balance" />
                        <x-form.textbox labelName="Received Amount" name="paying_amount" required="required"
                            col="col-md-12" />
                        <div class="col-md-12 required mt-2">
                            <label for="payable_amount" class="form-label">Paying Amount</label>
                            <input type="text" class="form-control" name="amount" id="amount">
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="change_amount" class="form-label">Change Amount</label>
                            <input type="text" class="form-control" name="change_amount" id="change_amount" readonly>
                        </div>
                        <x-form.selectbox labelName="Payment Method" name="payment_method" required="required"
                            col="col-md-12 mt-2" class="selectpicker">
                            @foreach (PAYMENT_METHOD as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Account" name="account_id" required="required" col="col-md-12 mt-2"
                            class="selectpicker">
                            @if (!$accounts->isEmpty())
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->name . ' - ' . $account->account_no }}</option>
                                @endforeach
                            @endif
                        </x-form.selectbox>
                        <div class="col-md-12 payment_no d-none mt-2">
                            <label for="payment_no" class="form-label"><span id="method-name"></span> No</label>
                            <input type="text" class="form-control" name="payment_no" id="payment_no">
                        </div>
                        <x-form.textarea labelName="Payment Note" name="payment_note" col="col-md-12 mt-2" />
                    </div>
                </div>
                <!-- /modal body -->

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bd-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-sm" id="payment-save-btn">Save</button>
                </div>
                <!-- /modal footer -->
            </form>
        </div>
        <!-- /modal content -->

    </div>
</div>
