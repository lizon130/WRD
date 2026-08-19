<div class="modal-header">
    <h5 class="modal-title" id="createModalLabel">
        <i class="fa-solid fa-plus-circle"></i> Add Second Dry Process Entry
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form method="POST" action="{{ route('admin.second-dry-process.store') }}" id="createSecondDryProcessForm">
        @csrf

        <div class="server_side_error mb-3"></div>

        <div class="row">
            <!-- Plant Selection -->
            <div class="col-md-6 mb-3">
                <label for="plant" class="form-label">Plant <span class="text-danger">*</span></label>
                <select class="form-control" name="plant" id="plant" required>
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
                <input type="date" class="form-control" name="date" id="date" required>
                <div class="invalid-feedback">Please select a date</div>
            </div>

            <!-- Process Type -->
            <div class="col-md-12 mb-3">
                <label for="processType" class="form-label">Process Type <span class="text-danger">*</span></label>
                <select class="form-control" name="processType" id="processType" required disabled>
                    <option value="">First select a plant</option>
                </select>
                <div class="invalid-feedback">Please select a process type</div>
                <small class="text-muted" id="processTypeHint"></small>
            </div>

            <!-- Target Quantity -->
            <div class="col-md-6 mb-3">
                <label for="TargetQty" class="form-label">Target Quantity <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="TargetQty" id="TargetQty"
                    placeholder="Enter target quantity" min="0" step="1" required>
                <div class="invalid-feedback">Please enter valid target quantity</div>
            </div>

            <!-- Production Quantity -->
            <div class="col-md-6 mb-3">
                <label for="ProductionQty" class="form-label">Production Quantity <span
                        class="text-danger">*</span></label>
                <input type="number" class="form-control" name="ProductionQty" id="ProductionQty"
                    placeholder="Enter production quantity" min="0" step="1" required>
                <div class="invalid-feedback">Please enter valid production quantity</div>
            </div>

            <!-- Defect Quantity (Shows for 1st Dry Final and 2nd Dry Final) -->
            <div class="col-md-6 mb-3" id="defectQtyField" style="display: none;">
                <label for="defectQty" class="form-label">
                    Defect Quantity <span class="text-danger">*</span>
                </label>
                <input type="number" class="form-control" name="defectQty" id="defectQty"
                    placeholder="Enter defect quantity" min="0" step="1">
                <div class="invalid-feedback">Please enter valid defect quantity</div>
                <small class="text-muted">Required for 1st Dry Final and 2nd Dry Final</small>
            </div>
        </div>

        <!-- Achievement Preview -->
        <div class="row mb-3" id="achievementPreview" style="display: none;">
            <div class="col-12">
                <div class="alert alert-info">
                    <strong>Achievement:</strong> <span id="previewPercentage">0</span>%
                    <div class="progress mt-2" style="height: 20px;">
                        <div class="progress-bar" id="previewProgressBar" role="progressbar" style="width: 0%;">0%</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mt-3">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fa-solid fa-times"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary" id="saveSecondDryProcessBtn">
                <i class="fa-solid fa-save"></i> Save Data
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Process types by plant
        const processTypesByPlant = {
            'TPL': ['Laser', 'PP Spray'],
            'TWL': ['Laser', 'PP Spray', '2nd Dry Final', 'Whisker', 'Hand Brush', '1st Dry Final']
        };

        // Set today's date
        var today = new Date().toISOString().split('T')[0];
        $('#date').val(today);

        // Function to check if defect quantity should be shown
        function shouldShowDefectQty(processType) {
            return processType === '1st Dry Final' || processType === '2nd Dry Final';
        }

        // Plant change handler
        $('#plant').on('change', function() {
            const plant = $(this).val();
            const $processType = $('#processType');

            // Hide defect field initially
            $('#defectQtyField').hide();
            $('#defectQty').prop('required', false);
            $('#defectQty').val('');

            // Clear current options
            $processType.empty();

            if (plant) {
                $processType.append('<option value="">Select Process Type</option>');

                const processTypes = processTypesByPlant[plant] || [];
                processTypes.forEach(function(type) {
                    $processType.append('<option value="' + type + '">' + type + '</option>');
                });

                $processType.prop('disabled', false);

                if (plant === 'TPL') {
                    $('#processTypeHint').text('TPL plant: Laser, PP Spray only');
                } else {
                    $('#processTypeHint').text('TWL plant: All process types available');
                }
            } else {
                $processType.append('<option value="">First select a plant</option>');
                $processType.prop('disabled', true);
                $('#processTypeHint').text('');
            }
        });

        // Process Type change handler
        $('#processType').on('change', function() {
            const processType = $(this).val();

            if (shouldShowDefectQty(processType)) {
                $('#defectQtyField').show();
                $('#defectQty').prop('required', true);
            } else {
                $('#defectQtyField').hide();
                $('#defectQty').prop('required', false);
                $('#defectQty').val('');
            }
        });

        // Form submission
        $('#createSecondDryProcessForm').on('submit', function(e) {
            e.preventDefault();

            // Reset previous errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('.server_side_error').empty();

            var formData = new FormData(this);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response.message);
                    $('#dataTable').DataTable().ajax.reload();
                    $('#createModal').modal('hide');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            var input = $('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.after('<div class="invalid-feedback">' + value +
                                '</div>');
                        });
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });

        // Achievement preview
        function updateAchievementPreview() {
            let target = parseInt($('#TargetQty').val()) || 0;
            let production = parseInt($('#ProductionQty').val()) || 0;

            if (target > 0) {
                let percentage = (production / target) * 100;
                percentage = Math.min(percentage, 100);

                $('#previewPercentage').text(percentage.toFixed(2));
                $('#previewProgressBar').css('width', percentage + '%');
                $('#previewProgressBar').text(percentage.toFixed(2) + '%');

                if (percentage >= 100) {
                    $('#previewProgressBar').removeClass('bg-warning bg-danger').addClass('bg-success');
                } else if (percentage >= 80) {
                    $('#previewProgressBar').removeClass('bg-success bg-danger').addClass('bg-warning');
                } else {
                    $('#previewProgressBar').removeClass('bg-success bg-warning').addClass('bg-danger');
                }

                $('#achievementPreview').show();
            } else {
                $('#achievementPreview').hide();
            }
        }

        $('#TargetQty, #ProductionQty').on('input', function() {
            updateAchievementPreview();
        });

        // Fix for modal close button
        $('[data-dismiss="modal"]').on('click', function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>