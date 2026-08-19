<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <form id="createProductForm" action="{{ route('admin.course.store') }}" method="post" enctype="multipart/form-data">
                @csrf 
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Course</h5>
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
                            <div class="step step_1 tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="">Company<span class="text-danger">*</span></label>
                                        <select name="company" class="form-control company" style="width: 100%" required>
                                            <option value="">Select</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->company_id}}">{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">Referance Type</label>
                                        <input type="text" name="referance_type" class="form-control" placeholder="eg. Event" >
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">Referance Code<span class="text-danger">*</span></label>
                                        <input type="text" name="referance_code" class="form-control" placeholder="eg. event007" >
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="">Code<span class="text-danger">*</span></label>
                                        <input type="text" name="code" class="form-control" placeholder="Product code" {{ old('code')}}  required>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="">Title<span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="Title" {{ old('name')}} required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {{-- <div class="col-lg-6">
                                        <label>Brand</label>
                                        <select name="brand" class="form-control">
                                            <option value="">Select</option>
                                            @foreach ($brands as $brand)
                                                <option @if(old('brand') == $brand->id) selected @endif value="{{ $brand->id}}">{{ $brand->title }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="col-lg-6">
                                        <label>Category<span class="text-danger">*</span></label>
                                        <select name="category" class="form-control " required>
                                            <option value="">Select</option>
                                            @foreach ($category as $cat)
                                                <option @if(old('category') == $cat->id) selected @endif value="{{ $cat->id}}">{{ $cat->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Sub-Category</label>
                                        <select name="sub_category_id" class="form-control create_category_select2" style="width: 100%;" >
                                            <option value="">Select</option>
                                            @foreach ($sub_category as $cat)
                                                <option @if(old('sub_category_id') == $cat->id) selected @endif value="{{ $cat->id}}">{{ $cat->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="step step_2 tab-pane fade" id="v-pills-price" role="tabpanel" aria-labelledby="v-pills-price-tab">
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="">Duration (Hours)<span class="text-danger">*</span></label>
                                        <input type="number" step=".1" name="duration"  class="form-control" placeholder="Duration"  required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">Price</label>
                                        <input type="number" step="0.1" name="price" class="form-control" placeholder="Product price" >
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="" class="form-label mt-1">Is Free</label>
                                        <div class="d-flex align-items-center">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_free" id="flexSwitchCheckDefault">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="" class="form-label mt-1">Visibility</label>
                                        <div class="d-flex align-items-center">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckDefault">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="">Discount Type</label>
                                        <select name="discount_type" id="" class="form-control">
                                            <option value="">Discount Type</option>
                                            <option @if(old('brand') == 'percent') selected @endif value="percent">Percent</option>
                                            <option @if(old('brand') == 'amount') selected @endif value="amount">Amount</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">Discount</label>
                                        <input type="text" name="discount" class="form-control" placeholder="Product discount" >
                                    </div>
                                </div>
                            </div>
                            <div class="step step_3 tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <div class="form-group">
                                    <label for="">Sort Description</label>
                                    <textarea class="form-control" id="key_features" rows="3">{!! old('key_features') !!}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Long Description</label>
                                    <textarea class="tinymceText form-control" id="further_information" rows="5">{!! old('further_information') !!}</textarea>
                                </div>
                            </div>
                            <div class="step step_4 tab-pane fade" id="v-pills-image" role="tabpanel" aria-labelledby="v-pills-image-tab">
                                <div class="form-group">
                                    <label >Thumbnail Image<span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" onchange="previewFile('createModal #thumbnail', 'createModal .preview_image')" name="thumbnail" id="thumbnail" required>
            
                                    <img src="{{asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
                                    
                                </div>
                                <div class="form-group">
                                    <label >Gallery Image</label>
                                    <input type="file" multiple class="form-control" onchange="previewFile('createModal #gallery', 'createModal .gallery_preview_image')" name="gallery[]" id="gallery">
                                    <img src="{{asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="gallery_preview_image mt-1 border" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-block step_btn step_btn_1">
                        <button type="button" data-step-open="step_2" data-step-button="step_btn_2" data-check-area="step_1" class="btn btn-primary next_btn">Next</button>
                    </div>
                    <div class="d-none step_btn step_btn_2">
                        <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_1" data-step-button="step_btn_1">Previous</a>
                        <button type="button" class="btn btn-primary next_btn" data-step-open="step_3" data-step-button="step_btn_3" data-check-area="step_2">Next</button>
                    </div>
                    <div class="d-none step_btn step_btn_3">
                        <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_2" data-step-button="step_btn_2">Previous</a>
                        <button type="button" class="btn btn-primary next_btn" data-step-open="step_4" data-step-button="step_btn_4" data-check-area="step_2">Next</button>
                    </div>
                    <div class="d-none step_btn step_btn_4">
                        <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_3" data-step-button="step_btn_3">Previous</a>
                        <button type="submit" id="createProductBtn" class="btn btn-primary" data-check-area="step_4">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- edit modal  --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            
        </div>
    </div>
</div>


<div class="modal fade" id="customModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl " role="document">
        <div class="modal-content">
            
        </div>
    </div>
</div>
