<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="createServiceForm" action="{{ route('admin.service.store') }}" method="post" enctype="multipart/form-data">
                @csrf 
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Service</h5>
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
                                    <label for="" class="col-sm-3 col-form-label">Service Code<span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="code" class="form-control" placeholder="Service Code" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Service Title<span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="title" class="form-control" placeholder="Service Title" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Product</label>
                                    <div class="col-sm-9">
                                        <select name="product_id" id="" class="form-control">
                                            <option value="">-- Select --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Short Description</label>
                                    <div class="col-sm-9">
                                        <textarea name="short_description" class="form-control"  cols="30" rows="8" required></textarea>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Visibility</label>
                                    <div class="col-sm-9">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="step step_2 tab-pane fade" >

                                <div class="form-group row">
                                    <label for="" class="col-sm-3 col-form-label">Description</label>
                                    <div class="col-sm-9">
                                        <textarea id="descriptions" class="tinymceText form-control" ></textarea>
                                    </div>
                                </div>

                                <div class="form-group  row">
                                    <label for="" class="col-sm-3 col-form-label">Image</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" onchange="previewFile('createModal #service_image', 'createModal .preview_image')" name="image" id="service_image">
                                        <img src="{{asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
                                    </div>
                                </div>

                            </div>
							
							<div class="step step_3 tab-pane fade" >
								<div class="w-100 text-left">
									<div class="col-12 d-flex justify-content-between">
										<label for="">More Descriptions</label>
										<button type="button" class="btn btn-primary" onclick="incrementRow('more_description_area', 'itwillbecoppy'); return false;"><i class="fa-solid fa-plus"></i></button>
									</div>
									<hr>
								</div>
								
								<div class="more_description_area">
									<div class="itwillbecoppy"  data-row-no="1">
										<div class="form-group row">
											<label for="" class="col-sm-3 col-form-label">Description</label>
											<div class="col-sm-9">
												<textarea id="descriptions" class="tinymceText aditional_descriptions form-control" ></textarea>
											</div>
										</div>

										<div class="form-group  row">
											<label for="" class="col-sm-3 col-form-label">Image</label>
											<div class="col-sm-9">
												<input type="file" class="form-control" onchange="previewFile('createModal #aditional_image', 'createModal .preview_aditional_image')" name="aditional_image[]" id="aditional_image">
												
											</div>
										</div>
										<a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-block step_btn step_btn_1">
                        <button type="button" data-step-open="step_2" data-step-button="step_btn_2" class="btn btn-primary next_btn" data-check-area="step_1">Next</button>
                    </div>
					<div class="d-none step_btn step_btn_2">
                        <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_1" data-step-button="step_btn_1">Previous</a>
                        <button type="button" data-step-open="step_3" data-step-button="step_btn_3" class="btn btn-primary next_btn" data-check-area="step_2">Next</button>
                    </div>
                    <div class="d-none step_btn step_btn_3">
                        <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_2" data-step-button="step_btn_2">Previous</a>
                        <button type="submit" id="createServiceBtn" class="btn btn-primary" data-check-area="step_3">Add</button>
                    </div>
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
