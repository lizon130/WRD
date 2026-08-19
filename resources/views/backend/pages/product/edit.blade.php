<form id="editProductForm" action="{{ route('admin.course.update', $product->id)}}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Course</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body row">
        <div class="row">
            <div class="col-sm-12">
                <div class="server_side_error" role="alert">

                </div>
            </div>
            <div class="col-sm-12 tab-content" id="v-pills-tabContent">
                <div class="step step_1 tab-pane fade show active" id="v-pills-edithome" role="tabpanel" aria-labelledby="v-pills-edithome-tab">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="">Company<span class="text-danger">*</span></label>
                            <select name="company" class="form-control company" style="width: 100%" required>
                                <option value="">Select</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->company_id}}" @if($product->company_id == $company->company_id) selected @endif>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="">Referance Type</label>
                            <input type="text" name="referance_type" class="form-control" value="{{ $product->referance_type ?? '' }}" placeholder="eg. Event" >
                        </div>
                        <div class="col-lg-6">
                            <label for="">Referance Code<span class="text-danger">*</span></label>
                            <input type="text" name="referance_code" class="form-control" value="{{ $product->referance_code ?? '' }}" placeholder="eg. event007" >
                        </div>
                        <div class="col-lg-6">
                            <label for="">Product Code<span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" placeholder="Product code" value="{{ $product->code }}"  required>
                        </div>
                        <div class="col-lg-12">
                            <label for="">Product Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Product Name" value="{{ $product->getTranslation(Session::get('admin_language') ?? 'en', 'name') ?? '' }}" required>
                        </div>
                        
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Category<span class="text-danger">*</span></label>
                            <select name="category" class="form-control" required>
                                <option value="">Select</option>
                                @foreach ($category as $cat)
                                    <option @if($product->category_id == $cat->id) selected @endif value="{{ $cat->id}}">{{ $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>Sub-Category</label>
                            <select name="sub_category_id" class="form-control edit_category_select2" style="width: 100%;" >
                                <option value="">Select</option>
                                @foreach ($sub_category as $cat)
                                    <option @if($product->sub_category_id == $cat->id) selected @endif value="{{ $cat->id}}">{{ $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-lg-6">
                            <label>Brand</label>
                            <select name="brand" class="form-control">
                                <option value="">Select</option>
                                @foreach ($brands as $brand)
                                    <option @if($product->brand_id == $brand->id) selected @endif value="{{ $brand->id}}">{{ $brand->title }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                </div>
                <div class="step step_2 tab-pane fade">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="">Duration (Hours)<span class="text-danger">*</span></label>
                            <input type="number" step=".1" name="duration"  value="{{ $product->duration ?? 0 }}" class="form-control" placeholder="Duration"  required>
                        </div>
                        <div class="col-lg-6">
                            <label for="">Price</label>
                            <input type="text" name="price" class="form-control" placeholder="Product price" value="{{ $product->price }}"  >
                        </div>
                        <div class="col-lg-6">
                            <label for="" class="form-label mt-1">Is Free</label>
                            <div class="d-flex align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" @if($product->is_free == 1) checked @endif name="is_free" id="flexSwitchCheckDefault">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="" class="mt-1">Visibility</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" @if($product->status == 1) checked @endif name="status" id="flexSwitchCheckDefault">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="">Discount Type</label>
                            <select name="discount_type" id="" class="form-control">
                                <option value="">Discount Type</option>
                                <option @if($product->discount_type == 'percent') selected @endif value="percent">Percent</option>
                                <option @if($product->discount_type == 'amount') selected @endif value="amount">Amount</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="">Discount</label>
                            <input type="text" name="discount" class="form-control" placeholder="Product discount" value="{{ $product->discount }}" >
                        </div>
                    </div>
                </div>
                <div class="step step_3 tab-pane fade">
                    <div class="form-group">
                        <label for="">Short Description</label>
                        <textarea class="form-control" id="key_features" rows="3">{!! $product->getTranslation(Session::get('admin_language') ?? 'en', 'key_features') ?? '' !!}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Long Description</label>
                        <textarea class="tinymceText form-control" id="further_information" rows="5">{!! $product->getTranslation(Session::get('admin_language') ?? 'en', 'further_information') ?? '' !!}</textarea>
                    </div>
                </div>
                <div class="step step_4 tab-pane fade">
                    <div class="form-group">
                        <label >Thumbnail Image<span class="text-danger">*</span></label>
                        <input type="file" class="form-control" onchange="previewFile('editModal #thumbnail', 'editModal .preview_image')" name="thumbnail" id="thumbnail" >

                        <img src="{{ ($product->thumbnail) ? asset('uploads/product-images/'.$product->thumbnail) :  asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
                        
                    </div>
                    <div class="form-group">
                        <label >Gallery Image</label>
                        <input type="file" multiple class="form-control" onchange="previewFile('editModal #gallery', 'editModal .gallery_preview_image')" name="gallery[]" id="gallery">

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
            <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_1"  data-step-button="step_btn_1">Previous</a>
            <button type="button" class="btn btn-primary next_btn" data-step-open="step_3" data-step-button="step_btn_3" data-check-area="step_2">Next</button>
        </div>
        <div class="d-none step_btn step_btn_3">
            <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_2" data-step-button="step_btn_2">Previous</a>
            <button type="button" class="btn btn-primary next_btn" data-step-open="step_4" data-step-button="step_btn_4" data-check-area="step_3">Next</button>
        </div>
        <div class="d-none step_btn step_btn_4">
            <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_3" data-step-button="step_btn_3">Previous</a>
            <button type="submit" id="editProductBtn" data-check-area="step_4" class="btn btn-primary">Update</button>
        </div>
        
    </div>
</form>