<div class="w-100 text-left">
    @if($custom_fields->field_name == 'Benefits')
        <div class="d-flex justify-content-between">
            <label for="">{{ $sub_option }}:</label>
            <a href="#" type="button" onclick="incrementRow('{{str_replace(' ', '_',$sub_option)}}', 'itwillbecoppy'); return false;" class="btn btn-sm btn-primary">
                <i class="fa-solid fa-plus"></i>
            </a>
        </div>
        <div class="{{str_replace(' ', '_',$sub_option)}}">
            <div class="">
                @if (count($attributes) > 0)
                    @forelse($attributes as $row)
                        <div class="row itwillbecoppy align-items-center" data-row-no="1">
                            <div class="col-lg-11">
                                <div class="col-lg-12 form-group">
                                    <label for="">Title</label>
                                    <input type="text" class="form-control" name="custom_option_name[]" value="{{ $row->title }}" placeholder="Title">
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label for="">Description</label>
                                    <textarea name="custom_option_details[]" class="form-control" id="" cols="30" rows="3">{{ $row->details }}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    @empty
                    @endforelse
                @else 
                    <div class="row itwillbecoppy align-items-center" data-row-no="1">
                        <div class="col-lg-11">
                            <div class="col-lg-12 form-group">
                                <label for="">Title</label>
                                <input type="text" class="form-control" name="custom_option_name[]" placeholder="Title">
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Description</label>
                                <textarea name="custom_option_details[]" class="form-control" id="" cols="30" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @elseif($custom_fields->field_name == 'Modules' || $custom_fields->field_name == 'Topics')
        <div class="d-flex justify-content-between">
            <label for="">{{ $sub_option ?? $custom_fields->field_name }}:</label>
            <a href="#" type="button" onclick="incrementRow('{{str_replace(' ', '_',$sub_option)}}', 'itwillbecoppy'); return false;" class="btn btn-sm btn-primary">
                <i class="fa-solid fa-plus"></i>
            </a>
        </div>
        <div class="{{str_replace(' ', '_',$sub_option)}}">
            <div class="">
                @if (count($attributes) > 0)
                    @forelse($attributes as $row)
                        <div class="row itwillbecoppy align-items-center" data-row-no="1">
                            <div class="col-lg-11 row">
                                @if($custom_fields->field_name == 'Topics')
                                    <div class="col-lg-12 form-group">
                                        <label for="">Modules</label>
                                        <select name="custom_option_modules[]" class="form-control" id="">
                                            <option value="">-- Select --</option>
                                            @foreach ($modules as $module)
                                                <option value="{{ $module->id }}" @if($module->id == $row->module_id) selected @endif>{{ $module->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-lg-12 form-group">
                                    <label for="">Title</label>
                                    <input type="text" class="form-control" name="custom_option_name[]" value="{{ $row->title ?? '' }}" placeholder="Title">
                                </div>
                                <div class="col-lg-6">
                                    <label for="">Price</label>
                                    <input type="number" step="0.1" name="custom_option_price[]" value="{{ $row->price ?? '' }}" class="form-control" placeholder="Price" >
                                </div>
                                <div class="col-lg-6">
                                    <label for="">Is Free?</label>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="custom_option_is_free[]" @if($row->is_free == 1) checked @endif id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="">Discount Type</label>
                                    <select name="custom_option_discount_type[]" id="" class="form-control">
                                        <option value="">Discount Type</option>
                                        <option value="percent" @if($row->discount_type == 'percent') selected @endif >Percent</option>
                                        <option value="amount" @if($row->discount_type == 'percent') selected @endif >Amount</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label for="">Discount</label>
                                    <input type="text" name="custom_option_discount[]" value="{{ $row->discount_amount ?? '' }}" class="form-control" placeholder="Discount Amount" >
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label for="">Short Description</label>
                                    <textarea name="custom_option_short_details[]" class="form-control" id="" cols="30" rows="2">{{ $row->short_description ?? '' }}</textarea>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label for="">Long Description</label>
                                    <textarea name="custom_option_details[]" class="form-control tinymceText" id="" cols="30" rows="5">{!! $row->details !!}</textarea>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label >Thumbnail Image</label>
                                    <input type="file" class="form-control" name="custom_option_image[]" >
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label >Intro</label>
                                    <input type="file" class="form-control" name="custom_option_intro[]" >
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    @empty
                    @endforelse
                @else  
                    <div class="row itwillbecoppy align-items-center" data-row-no="1">
                        <div class="col-lg-11 row">
                            @if($custom_fields->field_name == 'Topics')
                                <div class="col-lg-12 form-group">
                                    <label for="">Modules</label>
                                    <select name="custom_option_modules[]" class="form-control" id="">
                                        <option value="">-- Select --</option>
                                        @foreach ($modules as $module)
                                            <option value="{{ $module->id }}">{{ $module->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-lg-12 form-group">
                                <label for="">Title</label>
                                <input type="text" class="form-control" name="custom_option_name[]" placeholder="Title">
                            </div>
                            <div class="col-lg-6">
                                <label for="">Price</label>
                                <input type="number" step="0.1" name="custom_option_price[]" class="form-control" placeholder="Price" >
                            </div>
                            <div class="col-lg-6">
                                <label for="">Is Free?</label>
                                <div class="d-flex align-items-center">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="custom_option_is_free[]" id="flexSwitchCheckDefault">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="">Discount Type</label>
                                <select name="custom_option_discount_type[]" id="" class="form-control">
                                    <option value="">Discount Type</option>
                                    <option value="percent">Percent</option>
                                    <option value="amount">Amount</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label for="">Discount</label>
                                <input type="text" name="custom_option_discount[]" class="form-control" placeholder="Discount Amount" >
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Short Description</label>
                                <textarea name="custom_option_short_details[]" class="form-control" id="" cols="30" rows="2"></textarea>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Long Description</label>
                                <textarea name="custom_option_details[]" class="form-control tinymceText" id="" cols="30" rows="5"></textarea>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label >Thumbnail Image</label>
                                <input type="file" class="form-control" name="custom_option_image[]" >
                                <input type="hidden" name="old_image[]" value="{{ $row->image ?? '' }}">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label >Intro</label>
                                <input type="file" class="form-control" name="custom_option_intro[]" >
                                <input type="hidden" name="old_intro[]" value="{{ $row->intro_video ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-1">
                            <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @elseif ($custom_fields->field_name == 'Instructor')
        <label for="">{{ $sub_option }}:</label>
        <div class="{{str_replace(' ', '_',$sub_option)}}">
            <div class="">
                <table class="w-100">
                    <thead>
                        <tr>
                            <th class="w-25">Choose Instructor</th>
                            <th class="w-5 text-center">
                                <a href="#" type="button" onclick="incrementRow('{{str_replace(' ', '_',$sub_option)}}', 'itwillbecoppy'); return false;" class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-plus"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($attributes) > 0)
                            @forelse($attributes as $row)
                                <tr class="itwillbecoppy" data-row-no="1">
                                    <input type="hidden" class="form-control" name="custom_option_name[]" value="{{ $row->title }}">
                                    <td>
                                        <select name="custom_option_value[]" id="" class="form-control">
                                            <option value="">-- Select --</option>
                                            @foreach ($instructors as $instructor)
                                                <option value="{{ $instructor->id }}" @if($instructor->id == $row->value) selected @endif>{{ $instructor->first_name }} {{ $instructor->last_name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        @else
                            <tr class="itwillbecoppy" data-row-no="1">
                                <input type="hidden" class="form-control" name="custom_option_name[]" value="Instructor">
                                <td>
                                    <select name="custom_option_value[]" id="" class="form-control">
                                        <option value="">-- Select --</option>
                                        @foreach ($instructors as $instructor)
                                            <option value="{{ $instructor->id }}">{{ $instructor->first_name }} {{ $instructor->last_name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-center">
                                    <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr> 
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @else 
        <label for="">{{ $sub_option }}:</label>
        <div class="{{str_replace(' ', '_',$sub_option)}}">
            <div class="">
                <table class="w-100">
                    <thead>
                        <tr>
                            <th class="w-25">Title</th>
                            <th class="w-20">Image</th>
                            <th class="w-20">Value</th>
                            <th class="w-30">Details</th>
                            <th class="w-5 text-center">
                                <a href="#" type="button" onclick="incrementRow('{{str_replace(' ', '_',$sub_option)}}', 'itwillbecoppy'); return false;" class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-plus"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($attributes) > 0)
                            @forelse($attributes as $row)
                                <tr class="itwillbecoppy" data-row-no="1">
                                    <td>
                                        <input type="text" class="form-control" name="custom_option_name[]" value="{{ $row->title }}" placeholder="Title">
                                    </td>
                                    <td>
                                        <input type="file" class="form-control" name="custom_option_image[]"  value="{{ $row->image }}">
                                        <input type="hidden" name="old_image[]" value="{{ $row->image ?? '' }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="custom_option_value[]" value="{{ $row->value }}" placeholder="Value">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="custom_option_details[]" value="{{ $row->details }}" placeholder="Details">
                                    </td>
                                    <td class="text-center">
                                        <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="itwillbecoppy" data-row-no="1">
                                <td>
                                    <input type="text" class="form-control" name="custom_option_name[]" placeholder="Title">
                                </td>
                                <td>
                                    <input type="file" class="form-control" name="custom_option_image[]" >
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="custom_option_value[]" placeholder="Value">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="custom_option_details[]" placeholder="Details">
                                </td>
                                <td class="text-center">
                                    <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endif 
</div>