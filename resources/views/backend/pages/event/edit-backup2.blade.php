<!-- Modal -->
<form id="editCategoryForm" action="{{ route('admin.event.update', $event->id)}}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Event</h5>
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
                
                {{-- step button 1 --}}
                <div class="step step_1 tab-pane fade show active">
                    
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
                    <div class="form-group row d-none" id="publishedDate">
                        <label for="" class="col-sm-3 col-form-labe">Publish Date<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="date" name="published_date" class="form-control" value="" required>
                        </div>
                    </div>

                </div>

                {{-- step button 2 --}}
                <div class="step step_2 tab-pane fade" >

                    {{-- Start Time and End Time --}}
                    <div class="form-group row" id="startDate">
                        <label for="" class="col-sm-3 col-form-label">Start Time<span class="text-danger">*</span></label>
                        <div class="col-sm-3">
                            <input type="time" name="start_time" class="form-control" required value="{{ $event->start_time }}">
                        </div>
                    </div>

                    <div class="form-group row" id="endDate">
                        <label for="" class="col-sm-3 col-form-label">End Time<span class="text-danger">*</span></label>
                        <div class="col-sm-3">
                            <input type="time" name="end_time" class="form-control" required value="{{ $event->start_time }}">
                        </div>
                    </div>
                    

                    {{-- Speaker Info Section --}}
                    <div class="step step_2 tab-pane fade border" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <div class="form-group p-3">
                            <label for="create_hobbies">Speaker Info</label>
                            <div id="speakersInfoContainer">
                                @php
                                    $speakers = json_decode($event->speakers_info, true) ?? [];
                                @endphp
                    
                                @foreach($speakers as $index => $speaker)
                                    <div class="input-group mb-2">
                                        <input type="text" name="speakers_info[]" class="form-control addmissionrequired" value="{{ $speaker }}" placeholder="Speakers Name">
                                        @if($index == 0)
                                            <button type="button" id="addSpeakerInputField" class="btn btn-success">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-danger removeSpeakerInputField">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                    
                                @if(empty($speakers)) 
                                    <!-- Default input field if no speakers exist -->
                                    <div class="input-group mb-2">
                                        <input type="text" name="speakers_info[]" class="form-control addmissionrequired" placeholder="Speakers Name">
                                        <button type="button" id="addSpeakerInputField" class="btn btn-success">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    
                    

                    {{-- Workshop Info Section --}}
                    <div class="step step_2 tab-pane fade border" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <div class="form-group p-3">
                            <label for="create_hobbies">Workshop Info</label>
                            <div id="eventInfoContainer">
                                @php
                                    $workshops = json_decode($event->workshops_info, true) ?? [];
                                @endphp

                                @foreach($workshops as $index => $workshop)
                                    <div class="input-group mb-2">
                                        <input type="text" name="workshops_info[]" class="form-control addmissionrequired" value="{{ $workshop }}" placeholder="Workshop">
                                        @if($index == 0)
                                            <button type="button" id="addWorkshopInputField" class="btn btn-success">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-danger removeWorkshopInputField">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach

                                @if(empty($workshops)) 
                                    <!-- Default input field if no workshops exist -->
                                    <div class="input-group mb-2">
                                        <input type="text" name="workshops_info[]" class="form-control addmissionrequired" placeholder="Workshop">
                                        <button type="button" id="addWorkshopInputField" class="btn btn-success">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>



                    {{-- Network Info Section --}}
                    <div class="step step_2 tab-pane fade border" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <div class="form-group p-3">
                            <label for="create_hobbies">Network Info</label>
                            <div id="networkingInfoContainer">
                                @php
                                    $networks = json_decode($event->networks_info, true) ?? [];
                                @endphp

                                @foreach($networks as $index => $network)
                                    <div class="input-group mb-2">
                                        <input type="text" name="networks_info[]" class="form-control addmissionrequired" value="{{ $network }}" placeholder="Network">
                                        @if($index == 0)
                                            <button type="button" id="addNetworkingInputField" class="btn btn-success">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-danger removeNetworkingInputField">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach

                                @if(empty($networks)) 
                                    <!-- Default input field if no networks exist -->
                                    <div class="input-group mb-2">
                                        <input type="text" name="networks_info[]" class="form-control addmissionrequired" placeholder="Network">
                                        <button type="button" id="addNetworkingInputField" class="btn btn-success">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>




                    {{-- Publish User Section --}}
                    <div class="form-group row d-none" id="publishedUser">
                        <label for="published_user" class="col-sm-3 col-form-label d-none">Publish User<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <input type="hidden" name="published_user" class="form-control"  value="{{ auth()->id() }}">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="category_image" class="col-sm-3 col-form-label">Image</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" onchange="previewFile('editModal #category_image', 'editModal .preview_image')" name="image" id="category_image">
                            <img src="{{ $event->image ? asset($event->image) : asset('assets/img/no-img.jpg') }}" height="80px" width="100px" 
                            class="preview_image mt-1 border" alt="">
                        </div>     
                    </div>


                    {{-- Multiple Image Upload --}}
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Event Images</label>
                        <div class="col-sm-9">
                            <input type="file" multiple class="form-control" onchange="previewFiles('event_images', 'preview_event_images')" name="event_images[]" id="event_images" >
                            <div id="preview_event_images" class="mt-1">
                                <img src="{{ asset('assets/img/no-img.jpg') }}" height="80px" width="100px" class="preview_event_image mt-1 border" alt="">
                            </div>
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
                                <input class="form-check-input" type="checkbox" name="is_upcoming" id="flexSwitchCheckDefault">
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
            </div>
        </div>
    </div>
    {{-- <div class="modal-footer">
        <div class="d-block step_btn step_btn_1">
            <button type="button" data-step-open="step_2" data-step-button="step_btn_2" class="btn btn-primary next_btn" data-check-area="step_1">Next</button>
        </div>
        <div class="d-none step_btn step_btn_2">
            <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_1" data-step-button="step_btn_1">Previous</a>
            <button type="submit" id="editCategoryBtn" class="btn btn-primary " data-check-area="step_2">Update</button>
        </div>
    </div> --}}

    <div class="modal-footer-edit">
        <!-- Step 1 Button (Next) -->
        <div class="d-block step_btn step_btn_1">
            <button type="button" data-step-open="step_2" data-step-button="step_btn_2" class="btn btn-primary next_btn" data-check-area="step_1">Next</button>
        </div>
    
        <!-- Step 2 Button (Previous and Submit) -->
        <div class="d-none step_btn step_btn_2">
            <a type="button" class="btn m-pr-btn modal__btn_space next_btn" data-step-open="step_1" data-step-button="step_btn_1">Previous</a>
            <button type="submit" id="editCategoryBtn" class="btn btn-primary" data-check-area="step_2">Update</button>
        </div>
    </div>
    
    
    
</form>






