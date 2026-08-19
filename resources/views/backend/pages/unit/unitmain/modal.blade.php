<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="createResourceForm" action="{{ route('admin.alumni-resource.store') }}" method="post" enctype="multipart/form-data">
                @csrf 
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Alumni</h5>
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
                                    <div class="col-lg-12 form-group">
                                        <label for="" class="form-label">Title</label>
                                        <div class="input-group">
                                            <input type="text" name="title" class="form-control" placeholder="Title">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label for="" class="form-label">Short Description</label>
                                        <div class="input-group">
                                            <textarea name="short_description" id="short_description" class="form-control" rows="4" placeholder=""></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label for="" class="form-label">Descriptions</label>
                                        <div class="input-group">
                                            <textarea  id="details" class="form-control tinymceText" rows="4" placeholder="Event details"></textarea>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label for="" class="form-label">Banner</label>
                                        <input type="file" class="form-control" onchange="previewFile('createModal #resource_image', 'createModal .preview_image')" name="image" id="resource_image">
                                        <img src="{{asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="" class="form-label mt-1">Visibility</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckDefault">
                                        </div>
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
