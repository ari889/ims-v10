<div class="modal fade" id="update_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-1"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" id="update_form">
          @csrf
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="update_id" id="update_id">
                    <x-form.textbox labelName="Name" name="name" required="required" col="col-md-12" placeholder="Enter name" onkeyup="url_generator(this.value, 'slug')" />
                    <x-form.textbox labelName="Slug" name="slug" required="required" col="col-md-12" placeholder="Enter slug"/>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary btn-sm" id="update-btn"></button>
            </div>
        </form>
      </div>
    </div>
  </div>