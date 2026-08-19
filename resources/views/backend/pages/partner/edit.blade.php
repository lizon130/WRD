<form id="partnerEditForm" action="{{ route('admin.partner.update', $partner->company_id)}}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Partner</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="col-sm-12">
            <div class="server_side_error" role="alert">

            </div>
        </div>
        <div class="col-sm-12 tab-content" id="v-pills-tabContent">
            <div class="step step_1 tab-pane fade show active">
                <div class="w-100 d-flex gap-3">
                    <p>Partner Type:</p>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" @if($partner->type == 'Partner') checked @endif value="Partner" id="flexRadioDefault1" required>
                        <label class="form-check-label" for="flexRadioDefault1">Partner
                        </label>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-1 w-100">
                    <div class="form-floating mb-1 w-100">
                        <input type="text" class="form-control" placeholder="First Name" name="first_name" value="{{$partner->user->first_name}}" required>
                        <label >First Name<span class="text-danger">*</span></label>
                    </div>
                    <div class="form-floating mb-1 w-100">
                        <input type="text" class="form-control"  placeholder="Last Name" value="{{$partner->user->last_name}}" name="last_name" required>
                        <label >Last Name<span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="d-flex gap-1 mb-1 w-100">
                    <div class="form-floating mb-1 w-100">
                        <input type="text" class="form-control"  placeholder="Company Name" value="{{$partner->name}}" name="name" required>
                        <label >Company Name<span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-1 w-100">
                    <div class="form-floating mb-1 w-100">
                        <input type="text" class="form-control"  placeholder="Department" value="{{$partner->department}}" name="department" >
                        <label >Department</label>
                    </div>
                    <div class="form-floating mb-1 w-100">
                        <input type="text" class="form-control"  placeholder="VAT Reg No." value="{{$partner->vat_no}}" name="vat_no" >
                        <label >VAT Reg No.</label>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-1 w-100">
                    <div class="form-floating mb-1 w-100">
                        <input type="email" class="form-control"  name="email" value="{{$partner->email}}" placeholder="name@example.com" readonly>
                        <label >Email Address<span class="text-danger">*</span></label>
                    </div>
                    <div class="form-floating mb-1 w-100">
                        <input type="text" class="form-control"  placeholder="Phone No." value="{{$partner->phone_number}}" name="phone_no" required>
                        <label >Phone No.<span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="d-flex gap-1 mb-1 w-100">
                    <div class="form-floating mb-1 w-100">
                        <input type="text" class="form-control"  placeholder="Company Website URL" value="{{$partner->website_url}}" name="website_url">
                        <label >Company Website</label>
                    </div>
                </div>

                <div class="d-flex gap-2 mb-1 w-100">
                    <div class="form-group mb-1 w-100">
                        <select name="discount_type" class="form-control">
                            <option value="">Select Discount Type</option>
                            <option @if($partner->discount_type == 'percent') selected @endif value="percent">Percent</option>
                            <option @if($partner->discount_type == 'amount') selected @endif value="amount">Amount</option>
                        </select>
                    </div>
                    <div class="form-floating mb-1 w-100">
                        <input type="text" class="form-control"  placeholder="Discount" value="{{$partner->discount}}" name="discount" >
                        <label >discount</label>
                    </div>
                </div>
            </div>

            <div class="step step_2 tab-pane fade" >
                <div class="w-100 text-left">
                    <label for="">Address</label>
                    <hr>
                </div>
                <div class="d-flex gap-1 mb-1 w-100">
                    <div class="form-floating mb-1 w-100">
                        <input type="text" class="form-control"  placeholder="Company Name" value="{{$partner->address}}" name="address">
                        <label >Street Address<span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-1 w-100">
                    <div class="form-floating mb-1 w-100">
                        <input type="text" class="form-control"  placeholder="Postal Code" value="{{$partner->post_code}}" name="post_code" required>
                        <label >Postal Code<span class="text-danger">*</span></label>
                    </div>
                    <div class="form-floating w-100">
                        <input type="text" class="form-control"  placeholder="City" value="{{$partner->city}}" name="city" required>
                        <label >City<span class="text-danger">*</span></label>
                    </div>
                </div>

                <div class="d-flex w-100 gap-2 mb-1">
                    <div class="form-floating mb-1 w-100">
                        <input type="text" class="form-control"  placeholder="State" value="{{$partner->state}}" name="state" required>
                        <label >State<span class="text-danger">*</span></label>
                    </div>
                    <div class="form-floating w-100">
                        <input type="text" class="form-control"  placeholder="Country" value="{{$partner->country}}" name="country" required>
                        <label >Country<span class="text-danger">*</span></label>
                    </div>
                </div>
                
                <div class="w-100 text-left">
                    <label for="">Password</label>
                    <hr>
                </div>
                <div class="d-flex gap-2 mb-1 w-100">
                    <div class="form-floating mb-1 w-100">
                        <input type="password" class="form-control" autocomplete="false" placeholder="New Password" name="password" >
                        <label for="floatingPassword">New Password</label>
                    </div>
                    <div class="form-floating mb-1 w-100">
                        <input type="password" class="form-control" autocomplete="false" placeholder="Confirm Password" name="password_confirmation" >
                        <label for="floatingPassword">Confirm Password</label>
                    </div>
                </div>
                
                <div class="d-flex gap-2 mb-1 w-100">
                    <div class="form-group mb-1 w-100">
                        <input type="file" class="form-control" onchange="previewFile('editModal #category_image', 'editModal .preview_image')" name="image" id="category_image">
                        <img src="{{ ($partner->website_logo) ? asset('uploads/user-images/'.$partner->website_logo) :  asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
                    </div>
                    <div class="form-group mb-1 w-100">
                        <select name="status" class="form-control">
                            <option value="">Select Status</option>
                            <option @if($partner->status == 1) selected @endif value="1">Approve</option>
                            <option @if($partner->status == 2) selected @endif value="2">Rejected</option>
                            <option @if($partner->status == 0) selected @endif value="0">Pending</option>
                        </select>
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
            <button type="submit" id="editPartnerBtn" data-check-area="step_2" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>