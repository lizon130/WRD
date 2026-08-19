<form id="editPartForm" action="{{ route('admin.part.update', $part->id)}}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Product Parts</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body row">
        <div class="col-sm-12">
            <div class="server_side_error" role="alert">

            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 tab-content" id="v-pills-tabContent">
                <div class="step step_1 tab-pane fade show active" id="v-pills-edithome" role="tabpanel" aria-labelledby="v-pills-edithome-tab">
                    
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label>Product<span class="text-danger">*</span></label>
                            <select name="product" class="select2 form-control" required style="width: 100%;">
                                <option value="">Select</option>
                                @foreach ($products as $product)
                                    <option @if($part->product_id == $product->id) selected @endif value="{{ $product->id}}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label>Brand</label>
                            <select name="brand" class="form-control">
                                <option value="">Select</option>
                                @foreach ($brands as $brand)
                                    <option @if($part->brand_id == $brand->id) selected @endif value="{{ $brand->id}}">{{ $brand->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="">Parts Name<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Parts Name" value="{{ $part->getTranslation(Session::get('admin_language') ?? 'en', 'name') ?? '' }}" required>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="">Parts Code<span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control" placeholder="Parts code" value="{{ $part->code }}"  required>
                        </div>
                        <div class="col-lg-6">
                            <label for="">Price<span class="text-danger">*</span></label>
                            <input type="text" name="price" class="form-control" placeholder="Product price" value="{{ $part->price }}"  required>
                        </div>

                        <div class="col-lg-6">
                            <label for="" class="form-label mt-1">Type</label>
                            <select name="parts_type" id="" class="form-control">
                                <option @if($part->parts_type == 'parts') selected @endif value="parts">Parts</option>
                                <option @if($part->parts_type == 'accessories') selected @endif value="accessories">Accessories</option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label for="" class="mt-1">Visibility</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" @if($part->status == 1) checked @endif name="status" id="flexSwitchCheckDefault">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="step step_2 tab-pane fade">
                    
                    <div class="form-group">
                        <label for="">Key Features</label>
                        <textarea class="tinymceText form-control" id="key_features" rows="5">{!! $part->getTranslation(Session::get('admin_language') ?? 'en', 'key_features') ?? '' !!}</textarea>
                    </div>
                </div>
                <div class="step step_3 tab-pane fade">
                    <div class="form-group">
                        <label for="">Further Information</label>
                        <textarea class="tinymceText form-control" id="further_information" rows="5">{!! $part->getTranslation(Session::get('admin_language') ?? 'en', 'further_information') ?? '' !!}</textarea>
                    </div>
                </div>
                <div class="step step_4 tab-pane fade" >
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="">Discount Type</label>
                            <select name="discount_type" id="" class="form-control">
                                <option value="">Discount Type</option>
                                <option @if($part->discount_type == 'percent') selected @endif value="percent">Percent</option>
                                <option @if($part->discount_type == 'amount') selected @endif value="amount">Amount</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="">Discount</label>
                            <input type="text" name="discount" class="form-control" placeholder="Product discount" value="{{ $part->discount }}" >
                        </div>
                    </div>

                    <div class="form-group attributes_area">
                        <table class="w-100">
                            <thead>
                                <tr>
                                    <th class="w-5">Filter</th>
                                    <th class="w-50">Attributes Name</th>
                                    <th class="w-40">Attributes Value</th>
                                    <th class="w-5 text-center"><a href="" type="button" id="addPartAttributes" class="btn btn-sm btn-primary"><i class="fa-solid fa-plus"></i></a></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($part->attributes as $row)
                                    <tr class="attributes{{$loop->iteration}}">
                                        <td><input type="checkbox" class="attributes_filterable" name="filterable[]" value="1" @if($row->is_filter == 1) checked @endif></td>
                                        <td><input type="text" class="form-control" name="attributes_name[]" value="{{$row->attribute_name}}" placeholder="Attributes name"></td>
                                        <td><input type="text" class="form-control" name="attributes_value[]" value="{{$row->value}}" placeholder="Attributes value"></td>
                                        <td class="text-center"><a href="" type="button" data-row="{{$loop->iteration}}" class="btn btn-sm btn-danger remove_attributes" id="attributes{{$loop->iteration}}"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="step step_5 tab-pane fade" id="v-pills-editimage" role="tabpanel" aria-labelledby="v-pills-editimage-tab">
                    <div class="form-group">
                        <label >Thumbnail Image<span class="text-danger">*</span></label>
                        <input type="file" class="form-control" onchange="previewFile('editModal #thumbnail', 'editModal .preview_image')" name="thumbnail" id="thumbnail">

                        <img src="{{ ($part->thumbnail) ? asset('uploads/part-images/'.$part->thumbnail) :  asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
                        
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
            <button type="button" data-step-open="step_2" data-step-button="step_btn_2" class="btn btn-primary next_btn" data-check-area="step_1">Next</button>
        </div>
        <div class="d-none step_btn step_btn_2">
            <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_1" data-step-button="step_btn_1">Previous</a>
            <button type="button" class="btn btn-primary next_btn" data-step-open="step_3" data-step-button="step_btn_3" data-check-area="step_2">Next</button>
        </div>
        <div class="d-none step_btn step_btn_3">
            <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_2" data-step-button="step_btn_2">Previous</a>
            <button type="button" class="btn btn-primary next_btn" data-step-open="step_4" data-step-button="step_btn_4" data-check-area="step_3">Next</button>
        </div>
        <div class="d-none step_btn step_btn_4">
            <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_3" data-step-button="step_btn_3">Previous</a>
            <button type="button" class="btn btn-primary next_btn" data-step-open="step_5" data-step-button="step_btn_5" data-check-area="step_4">Next</button>
        </div>
        <div class="d-none step_btn step_btn_5">
            <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_4" data-step-button="step_btn_4">Previous</a>
            <button type="submit" id="editParttBtn" class="btn btn-primary" data-check-area="step_5">Update</button>
        </div>
    </div>
</form>