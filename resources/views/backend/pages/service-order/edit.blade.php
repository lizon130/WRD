<form id="editServiceOrderForm" action="{{ route('admin.service.order.update', $order->id)}}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Service Order</h5>
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
							<input type="text" name="company_name" class="form-control" placeholder="Company Name" value="{{$order->company_name}}" required>
						</div>
					</div>
								
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for="">Client Name<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Name" value="{{$order->name}}" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="">Client Email<span class="text-danger">*</span></label>
                            <input type="text" name="email" class="form-control" value="{{$order->email}}" placeholder="Email" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label for="">Client Location<span class="text-danger">*</span></label>
                            <input type="text" name="address" class="form-control" value="{{$order->address}}" placeholder="Location" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label for="">Customer Message<span class="text-danger">*</span></label>
                            <textarea name="message" id="" class="form-control" cols="5" rows="5">{{$order->message}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Status</label>
                        <select name="status" id="" class="form-control">
                            <option @if($order->status == 0) selected @endif value="0">New</option>
                            <option @if($order->status == 1) selected @endif value="1">Complete</option>
                            <option @if($order->status == 2) selected @endif value="2">On Process</option>
                            <option @if($order->status == 3) selected @endif value="3">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="step step_2 tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    
                    <div class="form-group service_area">
                        <table class="w-100">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>File</th>
                                    <td>Uploaded</td>
                                    <th><a href="" type="button" id="addService" class="btn btn-sm btn-primary"><i class="fa-solid fa-plus"></i></a></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($service_item as $item)
                                    <tr class="service{{$loop->iteration}}" data-row="{{$loop->iteration}}">
                                        <td class="w-50">
                                            <select name="service[]" class="form-control service_select service_select{{$loop->iteration}}" data-row="{{$loop->iteration}}" required>
                                                <option value="">Service Select </option>
                                                @foreach ($services as $service)
                                                    <option @if($service->id == $item->service_id) selected @endif value="{{ $service->id}}">{{ $service->title }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="file" class="form-control" name="files[]" >
                                            <input type="hidden" value="{{$item->file ?? ''}}" name="hidden_file[]">
                                        </td>
                                        <td>
                                            <a target="_blank" href="{{asset('uploads/service-order/'.$item->file)}}">Download</a>
                                        </td>
                                        <td><a href="" type="button" data-row="{{$loop->iteration}}" class="btn btn-sm btn-danger remove_service" id="service{{$loop->iteration}}"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                @empty
                                    <p class="text-center">No service selected</p>
                                @endforelse
                                
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
            <button type="submit" id="editServiceOrderBtn" class="btn btn-primary" data-check-area="step_2">Update</button>
        </div>
    </div>
</form>