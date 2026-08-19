<form id="editCustomFieldForm" action="{{ route('admin.course.custom.update', $custom_field->id) }}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Custom Field</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="server_side_error" role="alert">

                </div>
            </div>
            <div class="col-sm-12 tab-content" id="v-pills-tabContent">
                <div class="step step_1 tab-pane fade show active">
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label"> Field Name<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control" placeholder="Custom Field Title" value="{{ $custom_field->getTranslation(Session::get('admin_language') ?? 'en', 'field_name') ?? '' }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Visibility</label>
                        <div class="col-sm-9">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" @if($custom_field->status == 1) checked @endif name="status" id="flexSwitchCheckDefault">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="step_btn step_btn_1">
            <button type="submit" id="editCustomFieldBtn" class="btn btn-primary" data-check-area="step_1">Add</button>
        </div>
    </div>
</form>