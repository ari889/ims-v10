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
                    <x-form.selectbox labelName="Module" name="module_id" required="required" col="col-md-12 mb-3" class="selectpicker">
                        @if(!empty($data['modules']))
                            @foreach ($data['modules'] as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                            @endforeach
                        @endif
                    </x-form.selectbox>
                    <div class="col-md-12">
                        <table class="table table-borderless" id="permission-table">
                            <thead class="bg-primary">
                                <tr>
                                    <th width="45%">Permission Name</th>
                                    <th width="45%">Permission Slug</th>
                                    <th width="10%%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" name="permission[1][name]" id="permission_1_name" onkeyup="url_generator(this.value, 'permission_1_slug')" class="form-control" />
                                    </td>
                                    <td>
                                        <input type="text" name="permission[1][slug]" id="permission_1_slug" class="form-control">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" id="add_permission" data-toggle="tooltip" 
                                        data-placement="top" data-original-title="Add More">
                                            <i class="fas fa-plus-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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