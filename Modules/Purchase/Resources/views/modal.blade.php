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
                        <input type="hidden" name="update_id" id="update_id">
                        <x-form.textbox labelName="Name" name="name" required="required" col="col-md-6 mb-3"
                            placeholder="Enter name" />
                        <x-form.textbox labelName="Company Name" name="company_name" col="col-md-6 mb-3"
                            placeholder="Enter company name" />
                        <x-form.textbox labelName="Vat Number" name="vat_number" col="col-md-6 mb-3"
                            placeholder="Enter vat number" />
                        <x-form.textbox labelName="Phone" name="phone" col="col-md-6 mb-3"
                            placeholder="Enter phone" />
                        <x-form.textbox labelName="Email" name="email" col="col-md-6 mb-3"
                            placeholder="Enter email" />
                        <x-form.textbox labelName="Address" name="address" col="col-md-6 mb-3"
                            placeholder="Enter address" />
                        <x-form.textbox labelName="City" name="city" col="col-md-6 mb-3"
                            placeholder="Enter city" />
                        <x-form.textbox labelName="State" name="state" col="col-md-6 mb-3"
                            placeholder="Enter state" />
                        <x-form.textbox labelName="Postal Code" name="postal_code" col="col-md-6 mb-3"
                            placeholder="Enter postal code" />
                        <x-form.textbox labelName="Country" name="country" col="col-md-6 mb-3"
                            placeholder="Enter country" />
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
