<tr class="service{{$number}}" data-row="{{$number}}">
    <td class="w-50">
        <select name="service[]" class="form-control service_select service_select{{$number}}" data-row="{{$number}}" required>
            <option value="">Service Select </option>
            @foreach ($services as $service)
                <option value="{{ $service->id}}">{{ $service->title }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="file" class="form-control files files{{$number}}" name="files[]" >
    </td>
    <td><a href="" type="button" data-row="{{$number}}" class="btn btn-sm btn-danger remove_service" id="service{{$number}}"><i class="fa fa-trash"></i></a></td>
</tr>