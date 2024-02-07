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
                        <x-form.textbox labelName="Name" name="name" required="required" col="col-md-12 mb-3"
                            placeholder="Enter name" />
                        <x-form.textbox labelName="Email" name="email" required="required" col="col-md-12 mb-3"
                            placeholder="Enter email" />
                        <x-form.textbox labelName="Mobile No" name="mobile_no" required="required" col="col-md-12 mb-3"
                            placeholder="Enter mobile no" />
                        <x-form.selectbox labelName="Gender" name="gender" required="required"
                            col="col-md-12 mb-3" class="selectpicker">
                            @foreach (GENDER as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Role" name="role_id" required="required"
                            col="col-md-12 mb-3" class="selectpicker">
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </x-form.selectbox>
                        <div class="form-group col-md-12 mb-3 required">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password">
                                <span class="input-group-text bg-warning" id="generate_password" data-toggle="tooltip" data-placement="top" data-original-title="Generate Password">
                                    <i class="fas fa-lock text-white" style="cursor: pointer;"></i>
                                </span>
                                <span class="input-group-text bg-primary">
                                    <i class="fas fa-eye toggle-password text-white" toggle="#password" style="cursor: pointer;"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-md-12 required">
                            <label for="password_confirmation">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                                <span class="input-group-text bg-primary">
                                    <i class="fas fa-eye toggle-password text-white" toggle="#password_confirmation" style="cursor: pointer;"></i>
                                </span>
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
