<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="createCategoryForm" action="{{ route('admin.segmentation.store') }}" method="post" enctype="multipart/form-data">
                @csrf 
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Segment</h5>
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
                            <select name="parent_segmentation" class="form-control parrent_category" style="width: 100%">
                                <option value="">Select</option>
                                @foreach ($parent_segmentation as $segmentation)
                                    <option value="{{ $segmentation->id}}">{{ $segmentation->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                   
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Segment Name<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control" placeholder="Segment Name" required>
                        </div>
                    </div>
                    
                    <div class="form-group  row">
                        <label for="" class="col-sm-3 col-form-label">Visibility</label>
                        <div class="col-sm-3 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckDefault">
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <a type="button" class="modal__btn_space" data-bs-dismiss="modal">Close</a>
                    <button type="submit" id="createCategoryBtn" class="btn btn-primary" data-check-area="modal-body">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- edit modal  --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            
        </div>
    </div>
</div>
