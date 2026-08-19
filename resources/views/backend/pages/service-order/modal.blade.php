<!-- Modal -->
<div class="modal fade" id="createModal"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <form id="createServiceOrderForm" action="{{ route('admin.service.order.store') }}" method="post" enctype="multipart/form-data">
                @csrf 
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Service Order</h5>
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
                                    <div class="col-lg-12">
                                        <label for="">Company Name<span class="text-danger">*</span></label>
                                        <input type="text" name="company_name" class="form-control" placeholder="Company Name" required>
                                    </div>
                                </div>
								
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="">Client Name<span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="Name" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="">Client Email<span class="text-danger">*</span></label>
                                        <input type="text" name="email" class="form-control" placeholder="Email" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="">Client Location<span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control" placeholder="Location" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-12">
                                        <label for="">Customer Message<span class="text-danger">*</span></label>
                                        <textarea name="message" id="" class="form-control" cols="5" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="step step_2 tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                
                                <div class="form-group service_area">
                                    <table class="w-100">
                                        <thead>
                                            <tr>
                                                <th>Service</th>
                                                <th>File</th>
                                                <th><a href="" type="button" id="addService" class="btn btn-sm btn-primary"><i class="fa-solid fa-plus"></i></a></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="service1" data-row="1">
                                                <td class="w-50">
                                                    <select name="service[]" class="form-control service_select service_select1" data-row="1" required>
                                                        <option value="">Service Select </option>
                                                        @foreach ($services as $service)
                                                            <option value="{{ $service->id}}">{{ $service->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="file" class="form-control note note1" name="files[]" >
                                                </td>
                                                <td><a href="" type="button" data-row="1" class="btn btn-sm btn-danger remove_service" id="service1"><i class="fa fa-trash"></i></a></td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                        <button type="submit" id="createServiceOrderBtn" class="btn btn-primary" data-check-area="step_2">Add</button>
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

{{-- status modal  --}}
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            
        </div>
    </div>
</div>