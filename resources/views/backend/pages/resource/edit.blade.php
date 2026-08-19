<form id="editResourceForm" action="{{ route('admin.resource.update', $resource->id)}}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Resource</h5>
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
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label for="" class="form-label">Resource Type<span class="text-danger">*</span></label>
                            <select name="type" id="resource_type" class="form-control" required>
                                <option value="">Select</option>
                                <option @if($resource->type == 'banner') selected @endif value="banner">Banner</option>
                                <option @if($resource->type == 'partner') selected @endif value="partner">Partner</option>
                            </select>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label for="" class="form-label">Resource Title</label>
                            <div class="input-group">
                                <input type="text" name="title" value="{{$resource->title}}" class="form-control" placeholder="Resource Title">
                                <div class="input-group-append">
                                    <input type="color" id="favcolor" name="title_color" style="height: 45.99px;padding: 6px;border: 1px solid #d4d4d4;" value="{{ $resource->title_color }}">
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="row banner_element_area @if($resource->type == 'banner') d-block @else d-none @endif">
                        <div class="form-group col-lg-12">
                            <label for="">Button Text</label>
                            <div class="input-group">
                                <input type="text" value="{{$resource->button_text}}" name="button_text" class="form-control">
                                <div class="input-group-append">
                                    <input type="color" id="" name="button_color" style="height: 44.99px;padding: 6px;border: 1px solid #d4d4d4;" value="{{ $resource->button_color ?? '#088395' }}">
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="" class="col-sm-3 col-form-label">Details</label>
                            <div class="input-group">
                                <textarea name="details" class="form-control"  cols="30" rows="2">{{$resource->details}}</textarea>
                                <div class="input-group-append">
                                    <input type="color" id="" name="details_color" style="height: 70.99px;padding: 6px;border: 1px solid #d4d4d4;" value="{{ $resource->details_color ?? '#088395' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="" class="form-label">Link</label>
                            <input type="text" name="link" id="" class="form-control" value="{{$resource->link}}" placeholder="https:://machinetool.com">

                            <label for="" class="form-label mt-1">Visibility</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" @if($resource->status == 1) checked @endif name="status" id="flexSwitchCheckDefault">
                            </div>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="" class="form-label">Image</label>
                            <input type="file" class="form-control" onchange="previewFile('editModal #resource_image', 'editModal .preview_image')" name="image" id="resource_image">
                            <img src="{{ ($resource->image) ? asset('uploads/resource-images/'.$resource->image) :  asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a type="button" data-bs-dismiss="modal" aria-label="Close" class="modal__btn_space next_btn" >Close</a>
        <button type="submit" id="editResourceBtn" class="btn btn-primary" data-check-area="modal-body">Update</button>
    </div>
</form>