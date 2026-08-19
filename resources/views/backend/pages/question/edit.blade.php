<form id="editQuestionForm" action="{{ route('admin.question.update', $question->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT') <!-- Use PUT for updates -->
    <div class="modal-header">
        <h5 class="modal-title">Edit Question</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="col-sm-12">
            <div class="server_side_error" role="alert"></div>
        </div>
        <div class="form-group row">
            <!-- Partner Selection -->
            <div class="col-lg-4">
                <label for="">Partner<span class="text-danger">*</span></label>
                <select name="partner_id" class="form-control" required>
                    <option value="">Select Partner</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->company_id }}" {{ $question->company_id == $company->company_id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Segmentation Selection -->
            <div class="col-lg-6">
                <label for="">Segmentation<span class="text-danger">*</span></label>
                <select name="segmentation[]" class="form-control edit_segment_select2" multiple style="width: 100%;" required>
                    @foreach ($segmentation as $segment)
                        <option value="{{ $segment->id }}" 
                            {{ in_array($segment->id, json_decode($question->segmentation, true) ?? []) ? 'selected' : '' }}>
                            {{ $segment->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Marks -->
            <div class="col-lg-2">
                <label for="">Marks<span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="marks" value="{{ $question->marks }}" min="1" required>
            </div>

            <!-- Type -->
            <div class="col-lg-3">
                <label for="">Type<span class="text-danger">*</span></label>
                <select name="type" class="form-control" required>
                    <option value="">Select Type</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" {{ $question->question_type == $type->id ? 'selected' : '' }}>
                            {{ $type->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Difficulty Level -->
            <div class="col-lg-3">
                <label for="">Difficulty Level</label>
                <select name="difficulty_level" class="form-control">
                    <option value="">Select</option>
                    @foreach ($difficulty_level as $level)
                        <option value="{{ $level->id }}" {{ $question->difficulty_level == $level->id ? 'selected' : '' }}>
                            {{ $level->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Exam Purpose -->
            <div class="col-lg-3">
                <label for="">Exam Purpose</label>
                <select name="exam_purpose" class="form-control">
                    <option value="">Select</option>
                    @foreach ($exam_purpose as $purpose)
                        <option value="{{ $purpose->id }}" {{ $question->exam_purpose == $purpose->id ? 'selected' : '' }}>
                            {{ $purpose->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Media Type -->
            <div class="col-lg-3">
                <label for="">Media Type</label>
                <select name="media_type" class="form-control" required>
                    <option value="">Select</option>
                    @foreach ($media_type as $mtype)
                        <option value="{{ $mtype->id }}" {{ $question->media_type == $mtype->id ? 'selected' : '' }}>
                            {{ $mtype->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Question Title -->
            <div class="col-lg-12 mt-2">
                <label for="">Question</label>
                <input type="text" name="title" class="form-control" value="{{ $question->title }}" required>
            </div>

            <!-- Options -->
            <div class="col-lg-12 mt-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-10">Is Correct</th>
                            <th>Option</th>
                            <th class="w-5"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($question->options as $key => $option)
                            <tr class="copy_this">
                                <td>
                                    <select name="is_correct[]" class="form-control">
                                        <option value="0" {{ $option->is_correct == 0 ? 'selected' : '' }}>Select</option>
                                        <option value="1" {{ $option->is_correct == 1 ? 'selected' : '' }}>Correct</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="options[]" class="form-control" value="{{ $option->title }}" required>
                                </td>
                                <td>
                                    <button class="btn btn-outline-secondary" onclick="copyThisDiv(this);" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty 
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
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-lg-6">
                <label for="" class="mt-1">Visibility</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" @if($question->status == 1) checked @endif name="status" id="flexSwitchCheckDefault">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary " id="editQuestionBtn" data-check-area="modal-body">Update Question</button>
    </div>
</form>
