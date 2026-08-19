<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="createCategoryForm" action="{{ route('admin.question.store') }}" method="post" enctype="multipart/form-data">
                @csrf 
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Question</h5>
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
                        <div class="col-lg-4">
                            <label for="" >Partner<span class="text-danger">*</span></label>
                            <select name="partner_id" class="form-control partner_select" required>
                                <option value="">Partner Select </option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->company_id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label for="" >Segmentation<span class="text-danger">*</span></label>
                            <select name="segmentation[]" class="form-control segmentation_select" multiple style="width: 100%; padding: 10px;" required>
                                <option value="">Segmentation Select </option>
                                @foreach ($segmentation as $segment)
                                    <option value="{{ $segment->id }}">{{ $segment->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 mb-2">
                            <label for="" >Marks<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" min="1" name="marks" required>
                        </div>

                        {{-- @foreach ($preferances['modules'] as $module)
                            <div class="col-lg-3">
                                <input type="hidden" name="custom_data[module][]" value="{{ $module['module'] }}">
                                <label for="">{{ $module['name'] }} @if($module['is_required'])<span class="text-danger">*</span>@endif</label>
                                <select name="custom_data[{{ strtolower($module['name']) }}][]" class="form-control" @if($module['is_required']) required @endif>
                                    <option value="">Select</option>
                                    @foreach (app($module['module'])->all() as $item)
                                        <option value="{{ $item[$module['key']] }}">{{ $item[$module['value']] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach --}}

                        <div class="col-lg-3">
                            <label for="" >Type<span class="text-danger">*</span></label>
                            <select name="type" id="" class="form-control" required>
                                <option value="">Select</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="" >Difficulty Level</label>
                            <select name="difficulty_level" id="" class="form-control" >
                                <option value="">Select</option>
                                @foreach ($difficulty_level as $lavel)
                                    <option value="{{ $lavel->id }}">{{ $lavel->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="" >Exam Purpose</label>
                            <select name="exam_purpose" id="" class="form-control">
                                <option value="">Select</option>
                                @foreach ($exam_purpose as $purpose)
                                    <option value="{{ $purpose->id }}">{{ $purpose->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="" >Media Type</label>
                            <select name="media_type" id="" class="form-control" required>
                                <option value="">Select</option>
                                @foreach ($media_type as $mtype)
                                    <option value="{{ $mtype->id }}">{{ $mtype->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-12 mt-2">
                            <label for="">Question</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="">
                            <table class="table">
                                <thead>
                                    <th class="w-10">Is Correct</th>
                                    <th>Option</th>
                                    <th class="w-5"></th>
                                </thead>
                                <tbody>
                                    <tr class="copy_this">
                                        <td>
                                            <select name="is_correct[]" class="form-control" id="">
                                                <option value="0">Select</option>
                                                <option value="1">Correct</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="options[]" class="form-control" required>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-secondary" onclick="copyThisDiv(this);" type="button">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <label for="" class="mt-1">Visibility</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckDefault">
                            </div>
                        </div>
                    </div>
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
