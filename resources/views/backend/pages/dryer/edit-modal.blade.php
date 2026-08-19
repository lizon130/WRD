<div class="modal-header">
    <h5 class="modal-title">Edit Dryer Data</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    @if (isset($dryer) && $dryer)
        <form id="editDryerForm" action="{{ route('admin.dryer.update', $dryer->id) }}" method="POST">
            @csrf
            <div class="server_side_error mb-3"></div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" name="date" id="edit_date" class="form-control"
                        value="{{ $dryer->date ? (is_string($dryer->date) ? $dryer->date : $dryer->date->format('Y-m-d')) : '' }}"
                        required>
                    <div class="invalid-feedback">Please select a date.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="unit" class="form-label">Unit <span class="text-danger">*</span></label>
                    <select name="unit" id="edit_unit" class="form-control" required>
                        <option value="">Select Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit }}" {{ $dryer->unit == $unit ? 'selected' : '' }}>
                                {{ $unit }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Please select a unit.</div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Number of Dryers</label>
                    <input type="number" name="num_dryer" id="edit_num_dryer" class="form-control"
                        value="{{ $dryer->num_dryer }}" readonly>
                    <small class="text-muted">Auto-calculated based on unit</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Capacity (KG) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="capacity" id="edit_capacity" class="form-control"
                        value="{{ old('capacity', $dryer->capacity) }}" required min="0">
                    <div class="invalid-feedback">Please enter capacity.</div>
                    <small class="text-muted">One-time entry - affects all records for this unit</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Avg Dryer Time</label>
                    <input type="number" step="0.01" name="avg_dryer_time" id="edit_avg_dryer_time"
                        class="form-control" value="{{ old('avg_dryer_time', $dryer->avg_dryer_time) }}" min="0">
                    <small class="text-muted">One-time entry</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Avg Batch</label>
                    <input type="number" step="0.01" name="avg_batch" id="edit_avg_batch" class="form-control"
                        value="{{ old('avg_batch', $dryer->avg_batch) }}" min="0">
                    <small class="text-muted">One-time entry</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Working Hours</label>
                    <input type="number" step="0.01" name="working_hr" id="edit_working_hr" class="form-control"
                        value="{{ old('working_hr', $dryer->working_hr) }}" min="0">
                    <small class="text-muted">Daily entry</small>
                </div>

                {{-- <div class="col-md-6 mb-3">
                    <label class="form-label">Target Quantity</label>
                    <input type="number" step="0.01" name="targetQty" id="edit_targetQty" class="form-control"
                        value="{{ old('targetQty', $dryer->targetQty) }}" min="0">
                    <small class="text-muted">One-time entry</small>
                </div> --}}

                <div class="col-md-6 mb-3">
                    <label class="form-label">First Wash Dryer <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="first_wash_dryer" id="edit_first_wash_dryer"
                        class="form-control" value="{{ old('first_wash_dryer', $dryer->first_wash_dryer) }}" required
                        min="0">
                    <div class="invalid-feedback">Please enter first wash dryer value.</div>
                    <small class="text-muted">Daily entry</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Cold Dryer <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="cold_dryer" id="edit_cold_dryer" class="form-control"
                        value="{{ old('cold_dryer', $dryer->cold_dryer) }}" required min="0">
                    <div class="invalid-feedback">Please enter cold dryer value.</div>
                    <small class="text-muted">Daily entry</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Measurement Correction <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="measurement_correction"
                        id="edit_measurement_correction" class="form-control"
                        value="{{ old('measurement_correction', $dryer->measurement_correction) }}" required
                        min="0">
                    <div class="invalid-feedback">Please enter measurement correction.</div>
                    <small class="text-muted">Daily entry</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Final Wash Dryer <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="final_wash_dryer" id="edit_final_wash_dryer"
                        class="form-control" value="{{ old('final_wash_dryer', $dryer->final_wash_dryer) }}" required
                        min="0">
                    <div class="invalid-feedback">Please enter final wash dryer value.</div>
                    <small class="text-muted">Daily entry</small>
                </div>
            </div>

            <div class="modal-footer px-0 pb-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="editDryerBtn" class="btn btn-primary">Update</button>
            </div>
        </form>
    @else
        <div class="alert alert-danger">
            Error: Could not load dryer data. Please try again.
            @if (isset($error))
                <br><small>{{ $error }}</small>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    @endif
</div>
