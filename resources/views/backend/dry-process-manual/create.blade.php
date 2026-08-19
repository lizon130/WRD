<div class="modal-header">
    <h5 class="modal-title" id="createModalLabel">
        <i class="fa-solid fa-plus-circle"></i> Add Dry Process Manual Entry
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form method="POST" action="{{ route('admin.dry-process-manual.store') }}" id="createDryProcessManualForm">
        @csrf

        <div class="server_side_error mb-3"></div>

        <!-- Info Alert -->
        <div class="alert alert-info">
            <i class="fa-solid fa-info-circle"></i>
            The form is pre-populated with data from external sources for the selected date.
            You can modify the values before saving.
        </div>

        <div class="row">
            <!-- Plant Selection -->
            <div class="col-md-6 mb-3">
                <label for="plantName" class="form-label">Plant <span class="text-danger">*</span></label>
                <select class="form-control" name="plantName" id="plantName" required>
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
                <input type="date" class="form-control" name="date" id="date" value="{{ $selectedDate }}"
                    required>
                <div class="invalid-feedback">Please select a date</div>
                <small class="text-muted">Changing date will reload data from external sources</small>
            </div>
        </div>

        <!-- TPL Form - Only shows when TPL is selected -->
        <div id="tplForm" class="plant-form" data-plant="TPL" style="display: none;">
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
                                value="{{ $defaultData['TPL']['whisker_target'] ?? 0 }}" min="0" step="1">
                            <small class="text-muted">Values from external source - you can modify</small>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control tpl-input" name="whisker_production"
                                value="{{ $defaultData['TPL']['whisker_production'] ?? 0 }}" min="0"
                                step="1">
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
                                value="{{ $defaultData['TPL']['handBrush_target'] ?? 0 }}" min="0"
                                step="1">
                            <small class="text-muted text-warning">Enter value for TPL</small>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control tpl-input" name="handBrush_production"
                                value="{{ $defaultData['TPL']['handBrush_production'] ?? 0 }}" min="0"
                                step="1">
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
                                value="{{ $defaultData['TPL']['FirstDryFinal_target'] ?? 0 }}" min="0"
                                step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control tpl-input" name="FirstDryFinal_production"
                                value="{{ $defaultData['TPL']['FirstDryFinal_production'] ?? 0 }}" min="0"
                                step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Defect Quantity</label>
                            <input type="number" class="form-control tpl-input" name="FirstDryFinal_defectQty"
                                value="{{ $defaultData['TPL']['FirstDryFinal_defectQty'] ?? 0 }}" min="0"
                                step="1">
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
                                value="{{ $defaultData['TPL']['SecondDryFinal_target'] ?? 0 }}" min="0"
                                step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control tpl-input" name="SecondDryFinal_production"
                                value="{{ $defaultData['TPL']['SecondDryFinal_production'] ?? 0 }}" min="0"
                                step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Defect Quantity</label>
                            <input type="number" class="form-control tpl-input" name="SecondDryFinal_defectQty"
                                value="{{ $defaultData['TPL']['SecondDryFinal_defectQty'] ?? 0 }}" min="0"
                                step="1">
                            <small class="text-muted">Required if production > 0</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TWL Form - Only shows when TWL is selected -->
        <div id="twlForm" class="plant-form" data-plant="TWL" style="display: none;">
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
                                value="{{ $defaultData['TWL']['whisker_target'] ?? 0 }}" min="0"
                                step="1" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control twl-input" name="whisker_production"
                                value="{{ $defaultData['TWL']['whisker_production'] ?? 0 }}" min="0"
                                step="1" required>
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
                                value="{{ $defaultData['TWL']['handBrush_target'] ?? 0 }}" min="0"
                                step="1" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control twl-input" name="handBrush_production"
                                value="{{ $defaultData['TWL']['handBrush_production'] ?? 0 }}" min="0"
                                step="1" required>
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
                                value="{{ $defaultData['TWL']['FirstDryFinal_target'] ?? 0 }}" min="0"
                                step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control twl-input" name="FirstDryFinal_production"
                                value="{{ $defaultData['TWL']['FirstDryFinal_production'] ?? 0 }}" min="0"
                                step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Defect Quantity</label>
                            <input type="number" class="form-control twl-input" name="FirstDryFinal_defectQty"
                                value="{{ $defaultData['TWL']['FirstDryFinal_defectQty'] ?? 0 }}" min="0"
                                step="1">
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
                                value="{{ $defaultData['TWL']['SecondDryFinal_target'] ?? 0 }}" min="0"
                                step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Production Quantity</label>
                            <input type="number" class="form-control twl-input" name="SecondDryFinal_production"
                                value="{{ $defaultData['TWL']['SecondDryFinal_production'] ?? 0 }}" min="0"
                                step="1" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Defect Quantity</label>
                            <input type="number" class="form-control twl-input" name="SecondDryFinal_defectQty"
                                value="{{ $defaultData['TWL']['SecondDryFinal_defectQty'] ?? 0 }}" min="0"
                                step="1">
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
            <button type="submit" class="btn btn-primary" id="saveDryProcessManualBtn">
                <i class="fa-solid fa-save"></i> Save Data
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        // Function to reload data when date changes
        function reloadFormData(date) {
            $.ajax({
                url: "{{ route('admin.dry-process-manual.create.form') }}",
                type: "GET",
                data: {
                    date: date
                },
                success: function(data) {
                    $('#createModal .modal-content').html(data);
                },
                error: function(xhr) {
                    alert('Error reloading data. Please try again.');
                }
            });
        }

        // Handle date change
        $('#date').on('change', function() {
            let newDate = $(this).val();
            reloadFormData(newDate);
        });

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

        // Handle plant selection
        $('#plantName').on('change', function() {
            let plant = $(this).val();
            togglePlantForm(plant);
        });

        // If plant is pre-selected, show the correct form
        let selectedPlant = $('#plantName').val();
        if (selectedPlant) {
            togglePlantForm(selectedPlant);
        }

        // Form submission - CRITICAL: Disable hidden form inputs before submit
        $('#createDryProcessManualForm').on('submit', function(e) {
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

            var submitBtn = $('#saveDryProcessManualBtn');
            var originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            var formData = new FormData(this);

            // Log form data for debugging
            console.log('Create Form Data being submitted:');
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
                    $('#createModal').modal('hide');
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
