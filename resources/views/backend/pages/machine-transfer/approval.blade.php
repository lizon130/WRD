@extends('backend.layout.app')
@section('title', 'Transfer Verification | ' . Helper::getSettings('application_name') ?? 'Tusuka Wash')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Machine Transfer Verification</h4>

        <div class="card my-2">
            <div class="card-header bg-dark">
                <div class="row">
                    <div class="col-12">
                        <h5 class="m-0 text-white"><i class="fa-solid fa-clock me-2"></i>Pending Transfer Verification</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="approvalTable">
                        <thead class="table-warning">
                            <tr>
                                <th>Transfer Date</th>
                                <th>From Unit</th>
                                <th>To Unit</th>
                                <th>Machines</th>
                                <th>Hours</th>
                                <th>Machine Change (From)</th>
                                <th>Machine Change (To)</th>
                                <th>MG Target Change (From)</th>
                                <th>MG Target Change (To)</th>
                                <th>Production</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTable will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectionModalLabel">Reject Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="rejectionForm">
                    <div class="modal-body">
                        <input type="hidden" id="reject_transfer_id" name="id">
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3"
                                placeholder="Please provide a reason for rejection..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('footer')
        <script type="text/javascript">
            function getApprovals() {
                $('#approvalTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ route('machine-transfer.pending-list') }}",
                        type: 'GET',
                    },
                    aLengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    iDisplayLength: 10,
                    "order": [
                        [0, 'desc']
                    ],
                    "autoWidth": false,
                    columns: [{
                            data: 'transfer_date',
                            name: 'transfer_date',
                            className: 'text-nowrap'
                        },
                        {
                            data: 'from_unit_name',
                            name: 'from_unit_name'
                        },
                        {
                            data: 'to_unit_name',
                            name: 'to_unit_name'
                        },
                        {
                            data: 'machine_count',
                            name: 'machine_count',
                            className: 'text-center fw-bold'
                        },
                        {
                            data: 'hours',
                            name: 'hours',
                            className: 'text-center'
                        },
                        {
                            data: 'from_unit_change',
                            name: 'from_unit_change',
                            className: 'text-center text-danger'
                        },
                        {
                            data: 'to_unit_change',
                            name: 'to_unit_change',
                            className: 'text-center text-success'
                        },
                        {
                            data: 'mg_target_from_change',
                            name: 'mg_target_from_change',
                            className: 'text-center'
                        },
                        {
                            data: 'mg_target_to_change',
                            name: 'mg_target_to_change',
                            className: 'text-center'
                        },
                        {
                            data: 'calculated_production',
                            name: 'calculated_production',
                            className: 'text-center'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            className: 'text-nowrap'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: "text-center",
                            width: '150px'
                        }
                    ]
                });
            }

            $(document).ready(function() {
                getApprovals();

                // Approve transfer
                $(document).on('click', '.approve_btn', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('data-id');

                    Swal.fire({
                        title: 'Approve Transfer?',
                        text: "Are you sure you want to approve this machine transfer?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, approve it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ url('admin/machine-transfer/approve/') }}/" + id,
                                type: "POST",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    $.toast({
                                        heading: 'Success',
                                        text: response.message,
                                        position: 'top-center',
                                        icon: 'success'
                                    });

                                    // Refresh the table
                                    $('#approvalTable').DataTable().destroy();
                                    getApprovals();
                                },
                                error: function(xhr) {
                                    let errorMessage = 'An error occurred.';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                    }
                                    $.toast({
                                        heading: 'Error',
                                        text: errorMessage,
                                        position: 'top-center',
                                        icon: 'error'
                                    });
                                }
                            });
                        }
                    });
                });

                // Reject transfer - open modal
                $(document).on('click', '.reject_btn', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('data-id');
                    $('#reject_transfer_id').val(id);
                    $('#rejectionModal').modal('show');
                });

                // Handle rejection form submission
                $('#rejectionForm').on('submit', function(e) {
                    e.preventDefault();
                    let id = $('#reject_transfer_id').val();
                    let reason = $('#rejection_reason').val();

                    $.ajax({
                        url: "{{ url('admin/machine-transfer/reject/') }}/" + id,
                        type: "POST",
                        data: {
                            rejection_reason: reason
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#rejectionModal').modal('hide');
                            $('#rejectionForm')[0].reset();

                            $.toast({
                                heading: 'Success',
                                text: response.message,
                                position: 'top-center',
                                icon: 'success'
                            });

                            // Refresh the table
                            $('#approvalTable').DataTable().destroy();
                            getApprovals();
                        },
                        error: function(xhr) {
                            let errorMessage = 'An error occurred.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            $.toast({
                                heading: 'Error',
                                text: errorMessage,
                                position: 'top-center',
                                icon: 'error'
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection