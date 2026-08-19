<form id="editCategoryForm" action="{{ route('admin.event.update', $event->id)}}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Event</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="col-sm-12">
            <div class="server_side_error" role="alert">

            </div>
        </div>

        <div class="form-group row">
            <label for="typeof" class="col-sm-3 col-form-label">Type<span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <select name="typeof" id="typeof" class="form-control" required>
                    <option value="" disabled>Select Type</option>
                    <option value="Alumni" {{ $event->type == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                    <option value="Forum" {{ $event->type == 'Forum' ? 'selected' : '' }}>Forum</option>
                    <option value="Event" {{ $event->type == 'Event' ? 'selected' : '' }}>Event</option>
                    <option value="Blog" {{ $event->type == 'Blog' ? 'selected' : '' }}>Blog</option>
                    <option value="Article" {{ $event->type == 'Article' ? 'selected' : '' }}>Article</option>
                </select>
            </div>
        </div>        

        <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">Event Title<span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="text" name="name" class="form-control" placeholder="Event Title" value="{{ $event->name }}" required>
            </div>
        </div>


        {{-- Subtitle Section --}}
        <div class="form-group row" id="subtitle">
            <label for="" class="col-sm-3 col-form-label">Subtitle<span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="text" name="sub_nameor_title" class="form-control" placeholder="Sub Title" value="{{ $event->sub_nameor_title }}" required>
            </div>
        </div>


        <div class="form-group row">
            <label for="details" class="col-sm-3 col-form-label">Details<span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <textarea name="details" id="details" class="form-control" rows="4" placeholder="Event details" required>{{ $event->details }}</textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="company" class="col-sm-3 col-form-label">Company Name<span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="text" name="company" class="form-control" placeholder="Company Name" value="{{ $event->company }}" required>
            </div>
        </div>

        <div class="form-group row" id="startDate">
            <label for="start_date" class="col-sm-3 col-form-label">Start Date<span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="date" name="start_date" class="form-control" value="{{ $event->start_date }}" required>
            </div>
        </div>

        <div class="form-group row" id="endDate">
            <label for="end_date" class="col-sm-3 col-form-label">End Date<span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="date" name="end_date" class="form-control" value="{{ $event->end_date }}" required>
            </div>
        </div>


         {{-- Publish Date Section --}}
         {{-- <div class="form-group row d-none" id="publishedDate">
            <label for="" class="col-sm-3 col-form-label">Publish Date<span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="date" name="published_date" class="form-control" required>
            </div>
        </div> --}}

     

        {{-- Publish User Section --}}
        {{-- <div class="form-group row d-none" id="publishedUser">
            <label for="published_user" class="col-sm-3 col-form-label">Publish User<span class="text-danger">*</span></label>
            <div class="col-sm-7">
                <input type="hidden" name="published_user" class="form-control"  value="{{ auth()->id() }}">
            </div>
        </div> --}}


        <div class="form-group row">
            <label for="category_image" class="col-sm-3 col-form-label">Image</label>
            <div class="col-sm-9">
                <input type="file" class="form-control" onchange="previewFile('editModal #category_image', 'editModal .preview_image')" name="image" id="category_image">
                <img src="{{ $event->image ? asset($event->image) : asset('assets/img/no-img.jpg') }}" height="80px" width="100px" 
                class="preview_image mt-1 border" alt="">
            </div>     
        </div>



        <div class="form-group row">
            <label for="status" class="col-sm-3 col-form-label">Status</label>
            <div class="col-sm-3 d-flex align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckDefault" {{ $event->status ? 'checked' : '' }}>
                </div>
            </div>
        </div>

        {{-- Upcoming Event Setup  --}}
        {{-- <div class="form-group  row">
            <label for="" class="col-sm-3 col-form-label">Upcoming Event?</label>
            <div class="col-sm-3 d-flex align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_upcoming" id="flexSwitchCheckDefault" {{ $event->is_upcoming ? 'checked' : '' }}>
                </div>
            </div>
        </div> --}}

         {{-- Featured Event Setup  --}}
         <div class="form-group  row">
            <label for="" class="col-sm-3 col-form-label">Featured Event?</label>
            <div class="col-sm-3 d-flex align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_featured" id="flexSwitchCheckDefault" {{ $event->is_featured ? 'checked' : ''}}>
                </div>
            </div>
        </div>
        


    </div>
    <div class="modal-footer">
        <a type="button" class="modal__btn_space" data-bs-dismiss="modal">Close</a>
        <button type="submit" id="editCategoryBtn" class="btn btn-primary" data-check-area="modal-body">Update</button>
    </div>
</form>