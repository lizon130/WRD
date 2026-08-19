{{-- resources/views/backend/dryer-process-manual/edit.blade.php --}}
@extends('backend.layout.app')
@section('title', 'Edit Dryer Process Manual')
@section('content')
<div class="container-fluid px-4">
    <div class="card my-2">
        <div class="card-header">
            <h5 class="m-0">Edit Dryer Process Manual for {{ $manualData->TransactionDate }} - {{ $manualData->unit }}</h5>
        </div>
        <div class="card-body">
            <form id="editForm">
                @csrf
                <input type="hidden" name="date" value="{{ $manualData->TransactionDate }}">
                <input type="hidden" name="unit" value="{{ $manualData->unit }}">
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Process Stage</th>
                                <th>Production Quantity</th>
                                <th>Target Quantity</th>
                                <th>Defect Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($processStages as $index => $stage)
                                @php
                                    $washDataForStage = $washData->where('ProcessStageName', $stage->Name)->first();
                                    $productionQty = $washDataForStage ? $washDataForStage->TotalQuantity : 0;
                                    $manualRecord = $allManualData[$stage->Name] ?? null;
                                @endphp
                                <tr>
                                    <td>
                                        {{ $stage->Name }}
                                        <input type="hidden" name="processes[{{ $index }}][process_stage_name]" value="{{ $stage->Name }}">
                                        <input type="hidden" name="processes[{{ $index }}][production_qty]" value="{{ $productionQty }}">
                                    </td>
                                    <td>{{ $productionQty }}</td>
                                    <td>
                                        <input type="number" name="processes[{{ $index }}][target_qty]" 
                                               class="form-control" min="0" value="{{ $manualRecord ? $manualRecord->target_qty : '' }}">
                                    </td>
                                    <td>
                                        <input type="number" name="processes[{{ $index }}][defect_qty]" 
                                               class="form-control" min="0" value="{{ $manualRecord ? $manualRecord->defect_qty : '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.dryer-process-manual.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('footer')
<script>
$(document).ready(function() {
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var id = {{ $manualData->id }};
        
        $.ajax({
            url: "{{ url('admin/dryer-process-manual/update') }}/" + id,
            type: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = "{{ route('admin.dryer-process-manual.index') }}";
                    }, 1500);
                } else {
                    toastr.error(response.message);
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
            }
        });
    });
});
</script>
@endpush
@endsection