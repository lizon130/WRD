<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="createCategoryForm" action="{{ route('admin.event.store') }}" method="post" enctype="multipart/form-data">
                @csrf 
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Event</h5>
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
                                <option value="" disabled selected>Select Type</option>
                                <option value="Alumni">Alumni</option>
                                <option value="Forum">Forum</option>
                                <option value="Event">Event</option>
                                {{-- // new added blog type --}}
                                <option value="Blog">Blog</option>
                                <option value="Article">Article</option>
                            </select>
                        </div>
                    </div>
                    
                   
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="text" name="name" class="form-control" placeholder="Title" required>
                        </div>
                    </div>


                    {{-- Subtitle Section --}}
                    <div class="form-group row" id="subtitle">
                        <label for="" class="col-sm-3 col-form-label">Subtitle<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="text" name="sub_nameor_title" class="form-control" placeholder="Sub Title" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="details" class="col-sm-3 col-form-label">Details<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <textarea name="details" id="details" class="form-control" rows="4" placeholder="Event details" required></textarea>
                        </div>
                    </div>
                    

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Company Name<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="text" name="company" class="form-control" placeholder="company" required>
                        </div>
                    </div>

                    <div class="form-group row" id="startDate">
                        <label for="" class="col-sm-3 col-form-label">Start date<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row" id="endDate">
                        <label for="" class="col-sm-3 col-form-label">End date<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>


                    {{-- Publish Date Section --}}
                    <div class="form-group row d-none" id="publishedDate">
                        <label for="" class="col-sm-3 col-form-labe">Publish Date<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="date" name="published_date" class="form-control" required>
                        </div>
                    </div>


                    @php
                        
                        $publish_user = auth()->id(); 
                        // var_dump($publish_user);
                    @endphp

                    {{-- Publish User Section --}}
                    <div class="form-group row d-none" id="publishedUser">
                        <label for="published_user" class="col-sm-3 col-form-label d-none">Publish User<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="hidden" name="published_user" class="form-control"  value="{{ auth()->id() }}">
                        </div>
                    </div>


                    <div class="form-group  row">
                        <label for="" class="col-sm-3 col-form-label">Image</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" onchange="previewFile('createModal #category_image', 'createModal .preview_image')" name="image" id="category_image">

                            <img src="{{asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
                        </div>     
                    </div>

                    <div class="form-group  row">
                        <label for="" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-3 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckDefault">
                            </div>
                        </div>
                    </div>


                    {{-- Upcoming Event Setup  --}}
                    {{-- <div class="form-group  row">
                        <label for="" class="col-sm-3 col-form-label">Upcoming Event?</label>
                        <div class="col-sm-3 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_upcoming" id="flexSwitchCheckDefault">
                            </div>
                        </div>
                    </div> --}}

                    {{-- Featured Event Setup  --}}
                    <div class="form-group  row">
                        <label for="" class="col-sm-3 col-form-label">Featured?</label>
                        <div class="col-sm-3 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="flexSwitchCheckDefault">
                            </div>
                        </div>
                    </div>


                    {{-- Upcoming Event Setup  --}}
                    {{-- @php
                        $upcomingEventSelection = $is_upcoming_events;
                    @endphp
                    <div class="form-group row">
                        <label for="typeof" class="col-sm-3 col-form-label">Upcoming<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <select name="is_upcoming" id="is_upcoming" class="form-control" required>
                                <option value="" disabled selected>Select Type</option>
                                @foreach($upcomingEventSelection as $key => $value)
                                    <option value="{{ $key }}" {{ old('is_upcoming') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

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
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        // Use event delegation for elements inside a modal
        $(document).on('change', '#typeof', function(){
            console.log(2);
            const typeofValue = $(this).val();
            
            if (typeofValue === 'Blog') {
                $('#subtitle').show();
                $('#publishedDate').show();
                $('#publishedUser').show();
                $('#startDate').hide();
                $('#endDate').hide();
            } else {
                $('#subtitle').hide();
                $('#startDate').show();
                $('#endDate').show();
                $('#publishedDate').hide();
                $('#publishedUser').hide();
            }
        });

        // Ensure the modal is loaded correctly before attaching event handlers
        $('#createModal').on('shown.bs.modal', function(){
            $('#typeof').trigger('change'); // Ensure the change event runs on modal open
        });
    });
</script>



