<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="" id="partnerCreateForm" method="post" enctype="multipart/form-data">
                @csrf 
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Partner</h5>
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
                                    <input class="form-check-input" type="radio" name="type" @if(old('type') == 'Partner') checked @endif value="Partner" checked id="flexRadioDefault1" required>
                                    <label class="form-check-label" for="flexRadioDefault1">Partner
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mb-1 w-100">
                                <div class="form-floating mb-1 w-100">
                                    <input type="text" class="form-control" placeholder="First Name" name="first_name" value="{{old('first_name')}}" required>
                                    <label for="">First Name<span class="text-danger">*</span></label>
                                </div>
                                <div class="form-floating mb-1 w-100">
                                    <input type="text" class="form-control"  placeholder="Last Name" value="{{old('last_name')}}" name="last_name" required>
                                    <label for="">Last Name<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="d-flex gap-1 mb-1 w-100">
                                <div class="form-floating mb-1 w-100">
                                    <input type="text" class="form-control"  placeholder="Company Name" value="{{old('name')}}" name="name" required>
                                    <label for="">Company Name<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mb-1 w-100">
                                <div class="form-floating mb-1 w-100">
                                    <input type="text" class="form-control"  placeholder="Department" value="{{old('department')}}" name="department" >
                                    <label for="">Department</label>
                                </div>
                                <div class="form-floating mb-1 w-100">
                                    <input type="text" class="form-control"  placeholder="VAT Reg No." value="{{old('vat_no')}}" name="vat_no" >
                                    <label for="">VAT Reg No.</label>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mb-1 w-100">
                                <div class="form-floating mb-1 w-100">
                                    <input type="email" class="form-control"  name="email" value="{{old('email')}}" placeholder="name@example.com" required>
                                    <label for="">Email Address<span class="text-danger">*</span></label>
                                </div>
                                <div class="form-floating mb-1 w-100">
                                    <input type="text" class="form-control"  placeholder="Phone No." value="{{old('phone_no')}}" name="phone_no" required>
                                    <label for="">Phone No.<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="d-flex gap-1 mb-1 w-100">
                                <div class="form-floating mb-1 w-100">
                                    <input type="text" class="form-control"  placeholder="Company Website URL" value="{{old('website_url')}}" name="website_url">
                                    <label for="">Company Website</label>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mb-1 w-100">
                                <div class="form-group mb-1 w-100">
                                    <select name="discount_type"  class="form-control">
                                        <option value="">Select Discount Type</option>
                                        <option value="percent">Percent</option>
                                        <option value="amount">Amount</option>
                                    </select>
                                </div>
                                <div class="form-floating mb-1 w-100">
                                    <input type="text" class="form-control"  placeholder="Discount" value="{{old('discount')}}" name="discount" >
                                    <label for="">discount</label>
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
                                    <input type="text" class="form-control"  placeholder="Company Name" value="{{old('address')}}" name="address" required>
                                    <label for="">Street Address<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mb-1 w-100">
                                <div class="form-floating mb-1 w-100">
                                    <input type="text" class="form-control"  placeholder="Postal Code" value="{{old('post_code')}}" name="post_code" required>
                                    <label for="">Postal Code<span class="text-danger">*</span></label>
                                </div>
                                <div class="form-floating w-100">
                                    <input type="text" class="form-control"  placeholder="City" value="{{old('city')}}" name="city" required>
                                    <label for="">City<span class="text-danger">*</span></label>
                                </div>
                            </div>
            
                            <div class="d-flex w-100 gap-2 mb-1">
                                <div class="form-floating mb-1 w-100">
                                    <input type="text" class="form-control"  placeholder="State" value="{{old('state')}}" name="state" required>
                                    <label for="">State<span class="text-danger">*</span></label>
                                </div>
                                <div class="form-floating w-100">
                                    <input type="text" class="form-control"  placeholder="Country" value="{{old('country')}}" name="country" required>
                                    <label for="">Country<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            
                            <div class="w-100 text-left">
                                <label for="">Password</label>
                                <hr>
                            </div>
                            <div class="d-flex gap-2 mb-1 w-100">
                                <div class="form-floating mb-1 w-100">
                                    <input type="password" class="form-control" placeholder="New Password" name="password" autocomplete="false" required>
                                    <label for="floatingPassword">New Password<span class="text-danger">*</span></label>
                                </div>
                                <div class="form-floating mb-1 w-100">
                                    <input type="password" class="form-control" placeholder="Confirm Password" autocomplete="false" name="password_confirmation" required>
                                    <label for="floatingPassword">Confirm Password<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            
                            <div class="d-flex w-100 gap-2 mb-1">
                                <div class="form-group mb-1 w-100">
                                    <input type="file" class="form-control" onchange="previewFile('createModal #category_image', 'createModal .preview_image')" name="image" id="category_image">
                                    <img src="{{asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
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
                        <button type="submit" id="addPartnerBtn" data-check-area="step_2" class="btn btn-primary">Add</button>
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

{{-- edit modal  --}}
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            
        </div>
    </div>
</div>