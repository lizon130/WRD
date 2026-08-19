<div class="modal-header">
    <h5 class="modal-title" id="editModalLabel">
        <i class="fa-solid fa-pen-to-square"></i> Edit Dry Process Manual Entry
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form method="POST" action="{{ route('admin.dry-process-manual.update', $dryProcessManual->id) }}"
        id="editDryProcessManualForm">
        @csrf

        <div class="server_side_error mb-3"></div>

        <div class="row">
            <!-- Plant Selection -->
            <div class="col-md-6 mb-3">
                <label for="plantName" class="form-label">Plant <span class="text-danger">*</span></label>
                <select class="form-control" name="plantName" id="plantName" required>
                    <option value="">Select Plant</option>
                    @foreach ($plants as $plant)
                        <option value="{{ $plant }}" {{ $dryProcessManual->plantName == $plant ? 'selected' : '' }}>
                            {{ $plant }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Please select a plant</div>
            </div>

            <!-- Date -->
            <div class="col-md-6 mb-3">
                <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="date" id="date"
                    value="{{ $dryProcessManual->date->format('Y-m-d') }}" required>
                <div class="invalid-feedback">Please select a date</div>
            </div>
        </div>

        <!-- TPL Form - Only shows for TPL -->
        <div id="tplForm" class="plant-form" data-plant="TPL" style="{{ $dryProcessManual->plantName == 'TPL' ? 'display: block;' : 'display: none;' }}">
            <div class="card mb-3 border-primary">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">TPL Plant - Process Data</h6>
                </div>
                <div class="card-body">
                    <!-- Whisker Process -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="bg-light p-2">Whisker Process</h6>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Target Quantity</label>
                            <input type="number" class="form-control tpl-input" name="whisker_target" 
                                   value="{{ $dryProcessManual->whisker_target }}" 
                                   min="0" step="1">
                            <small class="text-muted">Enter value for TPL</small>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control tpl-input" name="whisker_production" 
                                   value="{{ $dryProcessManual->whisker_production }}" 
                                   min="0" step="1">
                        </div>
                    </div>

                    <!-- Hand Brush Process -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="bg-light p-2">Hand Brush Process</h6>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Target Quantity</label>
                            <input type="number" class="form-control tpl-input" name="handBrush_target" 
                                   value="{{ $dryProcessManual->handBrush_target }}" 
                                   min="0" step="1">
                            <small class="text-muted text-warning">Enter value for TPL</small>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control tpl-input" name="handBrush_production" 
                                   value="{{ $dryProcessManual->handBrush_production }}" 
                                   min="0" step="1">
                        </div>
                    </div>

                    <!-- First Dry Final -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="bg-light p-2">First Dry Final Process <span class="text-danger">*</span></h6>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Target Quantity</label>
                            <input type="number" class="form-control tpl-input" name="FirstDryFinal_target" 
                                   value="{{ $dryProcessManual->FirstDryFinal_target }}" 
                                   min="0" step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control tpl-input" name="FirstDryFinal_production" 
                                   value="{{ $dryProcessManual->FirstDryFinal_production }}" 
                                   min="0" step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Defect Quantity</label>
                            <input type="number" class="form-control tpl-input" name="FirstDryFinal_defectQty" 
                                   value="{{ $dryProcessManual->FirstDryFinal_defectQty }}" 
                                   min="0" step="1">
                            <small class="text-muted">Required if production > 0</small>
                        </div>
                    </div>

                    <!-- Second Dry Final -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="bg-light p-2">Second Dry Final Process <span class="text-danger">*</span></h6>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Target Quantity</label>
                            <input type="number" class="form-control tpl-input" name="SecondDryFinal_target" 
                                   value="{{ $dryProcessManual->SecondDryFinal_target }}" 
                                   min="0" step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control tpl-input" name="SecondDryFinal_production" 
                                   value="{{ $dryProcessManual->SecondDryFinal_production }}" 
                                   min="0" step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Defect Quantity</label>
                            <input type="number" class="form-control tpl-input" name="SecondDryFinal_defectQty" 
                                   value="{{ $dryProcessManual->SecondDryFinal_defectQty }}" 
                                   min="0" step="1">
                            <small class="text-muted">Required if production > 0</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TWL Form - Only shows for TWL -->
        <div id="twlForm" class="plant-form" data-plant="TWL" style="{{ $dryProcessManual->plantName == 'TWL' ? 'display: block;' : 'display: none;' }}">
            <div class="card mb-3 border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">TWL Plant - Process Data</h6>
                </div>
                <div class="card-body">
                    <!-- Whisker Process -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="bg-light p-2">Whisker Process <span class="text-danger">*</span></h6>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Target Quantity</label>
                            <input type="number" class="form-control twl-input" name="whisker_target" 
                                   value="{{ $dryProcessManual->whisker_target }}" 
                                   min="0" step="1" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control twl-input" name="whisker_production" 
                                   value="{{ $dryProcessManual->whisker_production }}" 
                                   min="0" step="1" required>
                        </div>
                    </div>

                    <!-- Hand Brush Process -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="bg-light p-2">Hand Brush Process <span class="text-danger">*</span></h6>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Target Quantity</label>
                            <input type="number" class="form-control twl-input" name="handBrush_target" 
                                   value="{{ $dryProcessManual->handBrush_target }}" 
                                   min="0" step="1" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control twl-input" name="handBrush_production" 
                                   value="{{ $dryProcessManual->handBrush_production }}" 
                                   min="0" step="1" required>
                        </div>
                    </div>

                    <!-- First Dry Final -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="bg-light p-2">First Dry Final Process <span class="text-danger">*</span></h6>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Target Quantity</label>
                            <input type="number" class="form-control twl-input" name="FirstDryFinal_target" 
                                   value="{{ $dryProcessManual->FirstDryFinal_target }}" 
                                   min="0" step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control twl-input" name="FirstDryFinal_production" 
                                   value="{{ $dryProcessManual->FirstDryFinal_production }}" 
                                   min="0" step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Defect Quantity</label>
                            <input type="number" class="form-control twl-input" name="FirstDryFinal_defectQty" 
                                   value="{{ $dryProcessManual->FirstDryFinal_defectQty }}" 
                                   min="0" step="1">
                            <small class="text-muted">Required if production > 0</small>
                        </div>
                    </div>

                    <!-- Second Dry Final -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="bg-light p-2">Second Dry Final Process <span class="text-danger">*</span></h6>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Target Quantity</label>
                            <input type="number" class="form-control twl-input" name="SecondDryFinal_target" 
                                   value="{{ $dryProcessManual->SecondDryFinal_target }}" 
                                   min="0" step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control twl-input" name="SecondDryFinal_production" 
                                   value="{{ $dryProcessManual->SecondDryFinal_production }}" 
                                   min="0" step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Defect Quantity</label>
                            <input type="number" class="form-control twl-input" name="SecondDryFinal_defectQty" 
                                   value="{{ $dryProcessManual->SecondDryFinal_defectQty }}" 
                                   min="0" step="1">
                            <small class="text-muted">Required if production > 0</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mt-3">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fa-solid fa-times"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary" id="updateDryProcessManualBtn">
                <i class="fa-solid fa-save"></i> Update Data
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Function to toggle plant forms and handle input states
        function togglePlantForm(selectedPlant) {
            // Hide all plant forms first
            $('.plant-form').hide();
            
            // Disable all plant-specific inputs
            $('.tpl-input, .twl-input').prop('disabled', true);
            
            // Show and enable the selected plant's form
            if (selectedPlant === 'TPL') {
                $('#tplForm').show();
                $('.tpl-input').prop('disabled', false);
            } else if (selectedPlant === 'TWL') {
                $('#twlForm').show();
                $('.twl-input').prop('disabled', false);
            }
        }

        // Initialize form state based on current plant selection
        var currentPlant = $('#plantName').val();
        if (currentPlant) {
            togglePlantForm(currentPlant);
        }

        // Handle plant selection change in edit mode
        $('#plantName').on('change', function() {
            let plant = $(this).val();
            togglePlantForm(plant);
        });

        // Form submission - CRITICAL: Disable hidden form inputs before submit
        $('#editDryProcessManualForm').on('submit', function(e) {
            e.preventDefault();

            // Reset previous errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            $('.server_side_error').empty();

            // CRITICAL FIX: Disable all inputs in hidden forms
            // This ensures only the visible form's inputs are submitted
            $('.plant-form').each(function() {
                if ($(this).css('display') === 'none') {
                    $(this).find('input').prop('disabled', true);
                }
            });

            var submitBtn = $('#updateDryProcessManualBtn');
            var originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

            var formData = new FormData(this);

            // Log form data for debugging
            console.log('Edit Form Data being submitted:');
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

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
                    $('#editModal').modal('hide');
                },
                error: function(xhr) {
                    // Re-enable inputs in visible form if there's an error
                    var selectedPlant = $('#plantName').val();
                    if (selectedPlant === 'TPL') {
                        $('.tpl-input').prop('disabled', false);
                    } else if (selectedPlant === 'TWL') {
                        $('.twl-input').prop('disabled', false);
                    }
                    
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            var input = $('[name="' + key + '"]:not(:disabled)');
                            input.addClass('is-invalid');
                            input.after('<div class="invalid-feedback">' + value + '</div>');
                        });
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Fix for modal close button
        $('[data-dismiss="modal"]').on('click', function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>
