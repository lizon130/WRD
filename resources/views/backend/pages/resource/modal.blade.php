<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="createResourceForm" action="{{ route('admin.resource.store') }}" method="post" enctype="multipart/form-data">
                @csrf 
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Resource</h5>
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
                                            <option value="banner">Banner</option>
                                            <option value="partner">Partner</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="" class="form-label">Resource Title</label>
                                        <div class="input-group">
                                            <input type="text" name="title" class="form-control" placeholder="Resource Title">
                                            <div class="input-group-append">
                                                <input type="color" id="favcolor" name="title_color" style="height: 45.99px;padding: 6px;border: 1px solid #d4d4d4;" value="#088395">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row banner_element_area d-none">
                                    <div class="form-group col-lg-12">
                                        <label for="">Button Text</label>
                                        <div class="input-group">
                                            <input type="text" name="button_text" class="form-control">
                                            <div class="input-group-append">
                                                <input type="color" id="" name="button_color" style="height: 44.99px;padding: 6px;border: 1px solid #d4d4d4;" value="#088395">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label for="" class="col-sm-3 col-form-label">Details</label>
                                        <div class="input-group">
                                            <textarea name="details" class="form-control"  cols="30" rows="2"></textarea>
                                            <div class="input-group-append">
                                                <input type="color" id="" name="details_color" style="height: 70.99px;padding: 6px;border: 1px solid #d4d4d4;" value="#088395">
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label for="" class="form-label">Link</label>
                                        <input type="text" name="link" id="" class="form-control" placeholder="https:://machinetool.com">

                                        <label for="" class="form-label mt-1">Visibility</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="" class="form-label">Image</label>
                                        <input type="file" class="form-control" onchange="previewFile('createModal #resource_image', 'createModal .preview_image')" name="image" id="resource_image">
                                        <img src="{{asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" data-bs-dismiss="modal" aria-label="Close" class="modal__btn_space next_btn" >Close</a>
                    <button type="submit" id="createResourceBtn" class="btn btn-primary" data-check-area="modal-body">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- edit modal  --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            
        </div>
    </div>
</div>
