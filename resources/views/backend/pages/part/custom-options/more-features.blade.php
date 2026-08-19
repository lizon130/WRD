<div class="w-100 text-left">
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
</div>