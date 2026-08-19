
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Partner Details</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="d-flex gap-2 w-100">
            <div class="form-group w-100">
                <img src="{{ ($partner->website_logo) ? asset('uploads/user-images/'.$partner->website_logo) :  asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
            </div>
            <div class="form-group w-100">
                <label for="floatingInput">Partner Type</label><br>
                {{$partner->type}}
            </div>
            <div class="form-group w-100">
                <label for="floatingInput">Status</label><br>
                @if($partner->status == 1) Approve
                @elseif($partner->status == 2) Rejected
                @else Pending
                @endif
            </div>
        </div>
        <div class="d-flex gap-2 w-100">
            <div class="form-group w-100">
                <label for="floatingInput">First Name</label><br>
                {{$partner->user->first_name}}
            </div>
            <div class="form-group  w-100">
                <label for="floatingInput">Last Name</label><br>
                {{$partner->user->last_name}}
            </div>
            <div class="form-group  w-100">
                <label for="floatingInput">Company Name</label><br>
                {{$partner->name}}
            </div>
        </div>
        <div class="d-flex gap-2 w-100">
            <div class="form-group  w-100">
                <label for="floatingInput">Department</label><br>
                {{$partner->department}}
            </div>
            <div class="form-group  w-100">
                <label for="floatingInput">VAT Reg No.</label><br>
                {{$partner->vat_no}}
            </div>
        </div>
        <div class="d-flex gap-2  w-100">
            <div class="form-group  w-100">
                <label for="floatingInput">Email Address</label><br>
                {{$partner->email}}
            </div>
            <div class="form-group  w-100">
                <label for="floatingInput">Phone No.</label><br>
                {{$partner->phone_number}}
            </div>
        </div>
        <div class="d-flex gap-1  w-100">
            <div class="form-group  w-100">
                <label for="floatingInput">Company Website</label><br>
                {{$partner->website_url}}
            </div>
        </div>
        <div class="w-100 text-left">
            <label for="">Discount</label>
            <hr>
        </div>
        <div class="d-flex gap-2  w-100">
            <div class="form-group  w-100">
                <label >Discount Type</label><br>
                {{$partner->discount_type}}
            </div>
            <div class="form-group  w-100">
                <label for="floatingInput">Discount</label><br>
                {{$partner->discount}}
            </div>
        </div>
        <div class="w-100 text-left">
            <label for="">Address</label>
            <hr>
        </div>
        <div class="d-flex gap-1  w-100">
            <div class="form-group  w-100">
                <label for="floatingInput">Street Address</label><br>
                {{$partner->address}}
            </div>
        </div>
        <div class="d-flex gap-2  w-100">
            <div class="form-group  w-100">
                <label for="floatingInput">Postal Code</label><br>
                {{$partner->post_code}}
            </div>
            <div class="form-group w-100">
                <label for="floatingInput">City</label><br>
                {{$partner->city}}
            </div>
            <div class="form-group  w-100">
                <label for="floatingInput">State</label><br>
                {{$partner->state}}
            </div>
            <div class="form-group w-100">
                <label for="floatingInput">Country</label><br>
                {{$partner->country}}
            </div>
        </div>
    </div>
    @if($partner->status == 0)
    <div class="modal-footer">
        <a href="{{ route('admin.partner.status', ['id' => $partner->company_id, 'status' => 2])}}" class="modal__btn_space" >Reject</a>
        <a href="{{ route('admin.partner.status', ['id' => $partner->company_id, 'status' => 1])}}" class="btn btn-primary">Approve</a>
    </div>
    @endif