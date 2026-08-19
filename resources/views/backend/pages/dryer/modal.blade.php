<div class="modal-header">
    <h5 class="modal-title">Add New Dryer Data</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="createDryerForm" action="{{ route('admin.dryer.store') }}" method="POST">
        @csrf
        <div class="server_side_error mb-3"></div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}"
                    required>
                <div class="invalid-feedback">Please select a date.</div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="unit" class="form-label">Unit <span class="text-danger">*</span></label>
                <select name="unit" id="unit" class="form-control" required>
                    <option value="">Select Unit</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit }}">{{ $unit }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Please select a unit.</div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="num_dryer" class="form-label">Number of Dryers</label>
                <input type="number" name="num_dryer" id="num_dryer" class="form-control" readonly>
                <small class="text-muted">Auto-calculated based on unit</small>
            </div>

            <!-- STATIC FIELDS (One-time entry per unit) -->
            <div class="col-md-6 mb-3">
                <label for="capacity" class="form-label">Capacity (KG) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="capacity" id="capacity" class="form-control" required
                    min="0">
                <div class="invalid-feedback">Please enter capacity.</div>
                <small class="text-muted capacity-help">Static value for this unit</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="avg_dryer_time" class="form-label">Avg Dryer Time</label>
                <input type="number" step="0.01" name="avg_dryer_time" id="avg_dryer_time" class="form-control"
                    min="0">
                <small class="text-muted avg-time-help">Static value for this unit</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="avg_batch" class="form-label">Avg Batch</label>
                <input type="number" step="0.01" name="avg_batch" id="avg_batch" class="form-control"
                    min="0">
                <small class="text-muted avg-batch-help">Static value for this unit</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="working_hr" class="form-label">Working Hours</label>
                <input type="number" step="0.01" name="working_hr" id="working_hr" class="form-control"
                    min="0">
                <small class="text-muted working-hr-help">Static value for this unit</small>
            </div>

            <!-- DAILY FIELDS (Change every day) -->
            <div class="col-md-6 mb-3">
                <label for="first_wash_dryer" class="form-label">First Wash Dryer <span
                        class="text-danger">*</span></label>
                <input type="number" step="0.01" name="first_wash_dryer" id="first_wash_dryer" class="form-control"
                    required min="0">
                <div class="invalid-feedback">Please enter first wash dryer value.</div>
                <small class="text-muted">Daily entry</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="cold_dryer" class="form-label">Cold Dryer <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="cold_dryer" id="cold_dryer" class="form-control" required
                    min="0">
                <div class="invalid-feedback">Please enter cold dryer value.</div>
                <small class="text-muted">Daily entry</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="measurement_correction" class="form-label">Measurement Correction <span
                        class="text-danger">*</span></label>
                <input type="number" step="0.01" name="measurement_correction" id="measurement_correction"
                    class="form-control" required min="0">
                <div class="invalid-feedback">Please enter measurement correction.</div>
                <small class="text-muted">Daily entry</small>
            </div>

            <div class="col-md-6 mb-3">
                <label for="final_wash_dryer" class="form-label">Final Wash Dryer <span
                        class="text-danger">*</span></label>
                <input type="number" step="0.01" name="final_wash_dryer" id="final_wash_dryer"
                    class="form-control" required min="0">
                <div class="invalid-feedback">Please enter final wash dryer value.</div>
                <small class="text-muted">Daily entry</small>
            </div>
        </div>

        <div class="modal-footer px-0 pb-0">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" id="createDryerBtn" class="btn btn-primary">Save Dryer Data</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Unit change handler for auto-filling static fields
        $('#unit').on('change', function() {
            var unit = $(this).val();

            // Set number of dryers
            var dryerCounts = {
                'Unit 1': 8,
                'Unit 2': 7,
                'Unit 3': 7,
                'Unit 4': 5,
                'Unit 5': 4,
                'Unit TWL': 8
            };

            if (unit && dryerCounts[unit]) {
                $('#num_dryer').val(dryerCounts[unit]);
            } else {
                $('#num_dryer').val('');
            }

            // Get static values for this unit
            if (unit) {
                $.ajax({
                    url: "{{ route('admin.dryer.get.latest') }}",
                    type: "GET",
                    data: {
                        unit: unit
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            // Auto-fill static fields with previous values
                            $('#capacity').val(response.data.capacity).prop('readonly',
                                true);
                            $('.capacity-help').html('Static value (previously set on ' +
                                response.data.date + ')');

                            if (response.data.avg_dryer_time) {
                                $('#avg_dryer_time').val(response.data.avg_dryer_time).prop(
                                    'readonly', true);
                                $('.avg-time-help').html(
                                    'Static value (previously set on ' + response.data
                                    .date + ')');
                            } else {
                                $('#avg_dryer_time').prop('readonly', false).val('');
                                $('.avg-time-help').html('Static value for this unit');
                            }

                            if (response.data.avg_batch) {
                                $('#avg_batch').val(response.data.avg_batch).prop(
                                    'readonly', true);
                                $('.avg-batch-help').html(
                                    'Static value (previously set on ' + response.data
                                    .date + ')');
                            } else {
                                $('#avg_batch').prop('readonly', false).val('');
                                $('.avg-batch-help').html('Static value for this unit');
                            }

                            if (response.data.working_hr) {
                                $('#working_hr').val(response.data.working_hr).prop(
                                    'readonly', true);
                                $('.working-hr-help').html(
                                    'Static value (previously set on ' + response.data
                                    .date + ')');
                            } else {
                                $('#working_hr').prop('readonly', false).val('');
                                $('.working-hr-help').html('Static value for this unit');
                            }

                            // Daily fields remain editable
                            $('#first_wash_dryer, #cold_dryer, #measurement_correction, #final_wash_dryer')
                                .prop('readonly', false).val('');

                        } else {
                            // First time entry for this unit - all static fields are editable
                            $('#capacity, #avg_dryer_time, #avg_batch, #working_hr')
                                .prop('readonly', false).val('');

                            $('.capacity-help').html('Static value for this unit');
                            $('.avg-time-help').html('Static value for this unit');
                            $('.avg-batch-help').html('Static value for this unit');
                            $('.working-hr-help').html('Static value for this unit');
                        }
                    },
                    error: function() {
                        console.log('Error fetching static values');
                    }
                });
            } else {
                // Reset all fields
                $('#capacity, #avg_dryer_time, #avg_batch, #working_hr, #first_wash_dryer, #cold_dryer, #measurement_correction, #final_wash_dryer')
                    .prop('readonly', false).val('');

                $('.capacity-help').html('Static value for this unit');
                $('.avg-time-help').html('Static value for this unit');
                $('.avg-batch-help').html('Static value for this unit');
                $('.working-hr-help').html('Static value for this unit');
            }
        });
    });
</script>
