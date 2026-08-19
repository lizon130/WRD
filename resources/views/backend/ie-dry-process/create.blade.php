<div class="modal-header">
    <h5 class="modal-title" id="createModalLabel">
        <i class="fa-solid fa-plus-circle"></i> Add Dry Process Data
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form method="POST" action="{{ route('admin.dryprocessie.store') }}" id="createDryProcessForm" novalidate>
        @csrf

        <div class="server_side_error mb-3"></div>

        <div class="row">
            <!-- Plant Selection -->
            <div class="col-md-6 mb-3">
                <label for="plant" class="form-label">Plant <span class="text-danger">*</span></label>
                <select class="form-control @error('plant') is-invalid @enderror" name="plant" id="plant"
                    required>
                    <option value="">Select Plant</option>
                    @foreach ($plants as $plant)
                        <option value="{{ $plant }}">{{ $plant }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Please select a plant</div>
            </div>

            <!-- Date -->
            <div class="col-md-6 mb-3">
                <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('date') is-invalid @enderror" name="date"
                    id="date" required>
                <div class="invalid-feedback">Please select a date</div>
            </div>

            <!-- Process Type -->
            <div class="col-md-12 mb-3">
                <label for="processType" class="form-label">Process Type <span class="text-danger">*</span></label>
                <select class="form-control @error('processType') is-invalid @enderror" name="processType"
                    id="processType" required>
                    <option value="">Select Process Type</option>
                    @foreach ($processTypes as $processType)
                        <option value="{{ $processType }}">{{ $processType }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Please select a process type</div>
            </div>

            <!-- Man Power -->
            <div class="col-md-4 mb-3">
                <label for="manPower" class="form-label">Man Power <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('manPower') is-invalid @enderror" name="manPower"
                    id="manPower" placeholder="Enter number of workers" min="1" step="1" required>
                <div class="invalid-feedback">Please enter valid man power (minimum 1)</div>
            </div>

            <!-- Working Hours -->
            <div class="col-md-4 mb-3">
                <label for="workingHr" class="form-label">Working Hours <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('workingHr') is-invalid @enderror" name="workingHr"
                    id="workingHr" placeholder="Enter working hours" min="0.5" max="24" step="0.5"
                    required>
                <div class="invalid-feedback">Please enter valid working hours (0.5 - 24)</div>
            </div>

            <!-- SMV -->
            <div class="col-md-4 mb-3">
                <label for="smv" class="form-label">SMV <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('smv') is-invalid @enderror" name="smv"
                    id="smv" placeholder="Enter SMV value" min="0" step="0.01" required>
                <div class="invalid-feedback">Please enter valid SMV</div>
            </div>
        </div>

        <div class="text-end mt-3">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fa-solid fa-times"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary" id="saveDryProcessBtn">
                <i class="fa-solid fa-save"></i> Save Data
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Form validation
        $('#createDryProcessForm').on('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            $(this).addClass('was-validated');
        });

        // Number input validation
        $('#manPower, #workingHr, #smv').on('input', function() {
            let value = parseFloat($(this).val());
            let min = parseFloat($(this).attr('min'));
            let max = parseFloat($(this).attr('max'));

            if ($(this).val() && !isNaN(value)) {
                if (min && value < min) {
                    $(this).val(min);
                } else if (max && value > max) {
                    $(this).val(max);
                }
            }
        });

        // Auto-format working hours to nearest 0.5
        $('#workingHr').on('blur', function() {
            let value = parseFloat($(this).val());
            if (!isNaN(value)) {
                let rounded = Math.round(value * 2) / 2;
                $(this).val(rounded);
            }
        });
    });
</script>
