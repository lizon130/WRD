{{-- resources/views/backend/dryer-process-manual/create.blade.php --}}
@extends('backend.layout.app')
@section('title', 'Dryer Process Manual')
@section('content')
<div class="container-fluid px-4">
    <div class="card my-2">
        <div class="card-header">
            <h5 class="m-0">Dryer Process Manual - {{ $date }}</h5>
        </div>
        <div class="card-body">
            <form id="manualForm">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="unit" class="form-label">Plant *</label>
                        <select name="unit" id="unit" class="form-control" required>
                            <option value="">Select Plant</option>
                            @foreach($plants as $plant)
                                <option value="{{ $plant }}" {{ $unit == $plant ? 'selected' : '' }}>{{ $plant }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Process Stage</th>
                                <th>Quantity from System</th>
                                <th>Adjusted Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="stagesBody">
                            @foreach($processStages as $index => $stage)
                                @php
                                    $systemQty = $sqlServerData[$stage->Name]->TotalQuantity ?? 0;
                                    $existing = $existingData[$stage->Name] ?? null;
                                    $adjustedQty = $existing ? $existing->totalQty : '';
                                @endphp
                                <tr>
                                    <td>
                                        {{ $stage->Name }}
                                        <input type="hidden" name="items[{{ $index }}][process_stage_name]" value="{{ $stage->Name }}">
                                    </td>
                                    <td>
                                        <span class="system-qty" data-stage="{{ $stage->Name }}">{{ $systemQty }}</span>
                                        <input type="hidden" class="system-qty-hidden" data-stage="{{ $stage->Name }}" value="{{ $systemQty }}">
                                     </td>
                                    <td>
                                        <input type="number" name="items[{{ $index }}][total_qty]" 
                                               class="form-control adjusted-qty" 
                                               min="0" 
                                               value="{{ $adjustedQty !== '' ? $adjustedQty : $systemQty }}"
                                               data-stage="{{ $stage->Name }}">
                                     </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-secondary reset-qty" data-stage="{{ $stage->Name }}">Reset to System</button>
                                     </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                    <a href="{{ route('admin.dryer-process-manual.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('footer')
<!-- Toastr CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": 3000,
        "extendedTimeOut": 1000,
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>

<script>
$(document).ready(function() {
    // Check if there's a success message in session and display it
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif
    
    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif

    // Load data when plant changes
    $('#unit').on('change', function() {
        var unit = $(this).val();
        var date = $('input[name="date"]').val();
        
        if(unit && date) {
            window.location.href = "{{ route('admin.dryer-process-manual.create.form') }}?date=" + date + "&unit=" + unit;
        }
    });

    // Reset to system quantity
    $(document).on('click', '.reset-qty', function() {
        var stage = $(this).data('stage');
        var systemQty = $('.system-qty[data-stage="' + stage + '"]').text();
        $('.adjusted-qty[data-stage="' + stage + '"]').val(systemQty);
        toastr.info('Reset to system quantity: ' + systemQty);
    });

    $('#manualForm').on('submit', function(e) {
        e.preventDefault();
        
        // Disable save button to prevent double submission
        $('#saveBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: "{{ route('admin.dryer-process-manual.store') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    // Redirect after 2 seconds
                    setTimeout(function() {
                        window.location.href = "{{ route('admin.dryer-process-manual.index') }}";
                    }, 2000);
                } else {
                    toastr.error(response.message);
                    $('#saveBtn').prop('disabled', false).html('Save');
                }
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                } else {
                    toastr.error('An error occurred. Please try again.');
                }
                $('#saveBtn').prop('disabled', false).html('Save');
            }
        });
    });
});
</script>
@endpush