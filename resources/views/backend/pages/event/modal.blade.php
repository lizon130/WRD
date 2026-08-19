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

                            </div>

                            {{-- step button 2 --}}
                            <div class="step step_2 tab-pane fade" >

                                {{-- Start Time and End Time --}}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                            <input type="time" name="start_time" id="start_time" class="form-control" required>
                                        </div>
                                    </div>
                                
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                            <input type="time" name="end_time" id="end_time" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                
                                                            

                                {{-- Speaker Info Section --}}
                                <div class="step step_2 tab-pane fade border" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                    <div class="form-group p-3">
                                        <label for="create_hobbies">Speaker Info</label>
                                        <div id="speakersInfoContainer">
                                            <div class="input-group mb-2">
                                                <input type="text" name="speakers_info[]"
                                                    class="form-control addmissionrequired"
                                                    placeholder="Speakers Name">
                                                <button type="button" id="addSpeakerInputField" class="btn btn-success">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Workshop Info Section --}}
                                <div class="step step_2 tab-pane fade border" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                    <div class="form-group p-3">
                                        <label for="create_hobbies">Workshop Info</label>
                                        <div id="eventInfoContainer">
                                            <div class="input-group mb-2">
                                                <input type="text" name="workshops_info[]"
                                                    class="form-control addmissionrequired"
                                                    placeholder="Workshop">
                                                <button type="button" id="addWorkshopInputField" class="btn btn-success">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                {{-- Speaker Info Section --}}
                                <div class="step step_2 tab-pane fade border" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                    <div class="form-group p-3">
                                        <label for="create_hobbies">Network Info</label>
                                        <div id="networkingInfoContainer">
                                            <div class="input-group mb-2">
                                                <input type="text" name="networks_info[]"
                                                    class="form-control addmissionrequired"
                                                    placeholder="Network">
                                                <button type="button" id="addNetworkingInputField" class="btn btn-success">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
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
            
            
                                <div class="form-group  row">
                                    <label for="" class="col-sm-3 col-form-label">Banner Image</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" onchange="previewFile('createModal #category_image', 'createModal .preview_image')" name="image" id="category_image">
            
                                        <img src="{{asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="preview_image mt-1 border" alt="">
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
                        <button type="submit" id="createCategoryBtn" class="btn btn-primary " data-check-area="step_2">Add</button>
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




