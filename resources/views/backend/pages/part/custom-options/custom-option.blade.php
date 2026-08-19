<form id="customProductForm" action="{{ route('admin.part.update.custom.option', $part->id) }}" method="post"
    enctype="multipart/form-data">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Add Custom Options</h5>
        <button type="button" class="close p-2 flex-shrink-1" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body row">
        <div class="col-sm-12">
            <div class="server_side_error" role="alert">

            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="list-group b-0">
                @foreach($custom_options as $row)
                    <li class="list-group-item list-group-item-action d-flex justify-content-between">
                        <a href="#" >{{ $row->sub_option }}</a>
                        <div class="d-flex">
                            <a href="#" type="button" onclick="editCustomOption('{{$row->custom_field_id}}', '{{$row->sub_option}}', '{{$row->part_id}}'); return false;" class="edit_custom_option_btn"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="#" type="button" onclick="deleteCustomOption('{{$row->custom_field_id}}', '{{$row->id}}', '{{$row->part_id}}'); return false;" class="delete_custom_option text-danger ms-1"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </li>
                @endforeach
            </div>
        </div>
        <div class="col-lg-8">
            <div class="back_btn_area">
                <button class="btn btn-sm btn-dark" id="go_back_btn" type="button"> <i class="fa-solid fa-angles-left"></i> Back</button>
            </div>
            <div class="row select_custom_field_area">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label for="">Select what you want to add:</label>
                        <select name="custom_field_id" class="form-control custom_field_id">
                            <option value="">-- Select --</option>
                            @foreach ($custom_fields as $row)
                                <option value="{{ $row->id }}">{{ $row->field_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <label for="">Select Sub-option:</label>
                        <select name="sub_option" class="form-control select2 custom_field_sub_option" style="width: 100%;">
                            <option value="">-- Select --</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-2 d-flex align-items-center">
                    <button type="button" class="btn btn-primary generate_html_btn" data-part-id="{{ $part->id }}">Add</button>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="generated_html_form_area">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="d-block ">
            <button type="button" id="updateCustomOptionBtn" class="btn btn-primary next_btn">Submit</button>
        </div>
    </div>
</form>



