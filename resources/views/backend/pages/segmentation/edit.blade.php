<form id="editCategoryForm" action="{{ route('admin.segmentation.update', $single_segmentation->id)}}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Segment</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="col-sm-12">
            <div class="server_side_error" role="alert">

            </div>
        </div>
        <div class="form-group  row">
            <label for="" class="col-sm-3 col-form-label">Parent Segment</label>
            <div class="col-sm-9">
                <select name="parent_segmentation" class="form-control edit_parent_category" style="width: 100%">
                    <option value="">Select</option>
                    @foreach ($parent_segmentation as $segmentation)
                        <option @if($single_segmentation->ancestor_id == $segmentation->id) selected @endif value="{{ $segmentation->id}}">{{ $segmentation->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
       
        <div class="form-group row">
            <label for="" class="col-sm-3 col-form-label">Segment Name<span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="text" name="name" class="form-control" placeholder="Segment Name" value="{{ $single_segmentation->getTranslation(Session::get('admin_language') ?? 'en', 'name') ?? $single_segmentation->title }}" required>
            </div>
        </div>
        <div class="form-group  row">
            <label for="" class="col-sm-3 col-form-label">Visibility</label>
            <div class="col-sm-3 d-flex align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" @if($single_segmentation->status == 1) checked @endif type="checkbox" name="status" id="flexSwitchCheckDefault">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a type="button" class="modal__btn_space" data-bs-dismiss="modal">Close</a>
        <button type="submit" id="editCategoryBtn" class="btn btn-primary" data-check-area="modal-body">Update</button>
    </div>
</form>