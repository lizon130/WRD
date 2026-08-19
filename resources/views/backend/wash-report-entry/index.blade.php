@extends('backend.layout.app')
@section('title', 'Wash Report Entry | ' . Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Wash Report Entry</h4>

        <!-- Filter Card -->
        <div class="card my-2">
            <div class="card-body pb-0">
                <form method="GET" id="filter_form">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="start_date">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date" id="end_date">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="dateFilter" class="form-label">Specific Date</label>
                            <input type="date" class="form-control" name="date" id="dateFilter">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="unitFilter" class="form-label">Unit</label>
                            <select class="form-control" name="unit" id="unitFilter">
                                <option value="">All Units</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit }}">{{ $unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-6 mb-2">
                            <label for="sewingLineFilter" class="form-label">Sewing Line</label>
                            <input type="text" class="form-control" name="sewing_line" id="sewingLineFilter"
                                placeholder="Enter sewing line">
                        </div> --}}
                        <div class="col-md-12">
                            <div class="form-group text-end mt-2">
                                <button type="submit" id="filterBtn" name="submit" class="btn btn-primary">
                                    <i class="feather icon-file mr-2"></i> Search
                                </button>
                                <button type="button" id="resetBtn" class="btn btn-secondary ml-2">
                                    <i class="feather icon-refresh-cw mr-2"></i> Reset
                                </button>
                                <button type="button" id="todayBtn" class="btn btn-info ml-2">
                                    <i class="feather icon-calendar mr-2"></i> Today
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- DataTable Card -->
        <div class="card my-2">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <h5 class="m-0">Wash Report Entries</h5>
                        </div>
                        <button type="button" class="btn btn-primary btn-create-entry" data-toggle="modal"
                            data-target="#createModal">
                            <i class="fa-solid fa-plus"></i> Add New Entry
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Unit</th>
                            <th>First Wash Qty</th>
                            <th>Acid Wash Qty</th>
                            <th>Final Wash Qty</th>
                            <th>Re-Wash Qty</th>
                            <th>Total Qty</th>
                            <th>In Hand Balance</th>
                            <th>Rework Dry Proc</th>
                            <th>Machine Work Hrs</th>
                            {{-- <th>Sewing Line</th>
                            <th>Remarks</th> --}}
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Modal (Static) -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Edit Modal (Static) -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script type="text/javascript">
        $(document).ready(function() {
            // Set today's date as default for specific date filter
            var today = new Date().toISOString().split('T')[0];
            $('#dateFilter').val(today);

            // Set default date range (last 7 days)
            var lastWeek = new Date();
            lastWeek.setDate(lastWeek.getDate() - 7);
            $('#start_date').val(lastWeek.toISOString().split('T')[0]);
            $('#end_date').val(today);

            // Initialize DataTable
            var table = $('#dataTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.wash-report-entry.get.list') }}",
                    type: 'GET',
                    data: function(d) {
                        d.date = $('#dateFilter').val();
                        d.unit = $('#unitFilter').val();
                        d.sewing_line = $('#sewingLineFilter').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                aLengthMenu: [
                    [25, 50, 100, 500, 5000, -1],
                    [25, 50, 100, 500, 5000, "All"]
                ],
                iDisplayLength: 25,
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'unit',
                        name: 'unit'
                    },
                    {
                        data: 'FirstWashQty',
                        name: 'FirstWashQty'
                    },
                    {
                        data: 'AcidWashQty',
                        name: 'AcidWashQty'
                    },
                    {
                        data: 'FinalWashQty',
                        name: 'FinalWashQty'
                    },
                    {
                        data: 'ReWashQty',
                        name: 'ReWashQty'
                    },
                    {
                        data: 'total_qty',
                        name: 'total_qty',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'in_hand_balance',
                        name: 'in_hand_balance'
                    },
                    {
                        data: 'rework_dry_proc',
                        name: 'rework_dry_proc'
                    },
                    {
                        data: 'machine_work_hr',
                        name: 'machine_work_hr'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    }
                ]
            });

            // Filter form submission
            $('#filter_form').on('submit', function(e) {
                e.preventDefault();
                table.draw();
            });

            // Reset filter
            $('#resetBtn').on('click', function() {
                $('#dateFilter').val('');
                $('#unitFilter').val('');
                $('#sewingLineFilter').val('');
                $('#start_date').val('');
                $('#end_date').val('');
                table.draw();
            });

            // Set today's data
            $('#todayBtn').on('click', function() {
                $('#dateFilter').val(today);
                $('#unitFilter').val('');
                $('#sewingLineFilter').val('');
                $('#start_date').val('');
                $('#end_date').val('');
                table.draw();
            });

            // Load create form modal
            $('.btn-create-entry').on('click', function() {
                $.ajax({
                    url: "{{ route('admin.wash-report-entry.create.form') }}",
                    type: "GET",
                    success: function(data) {
                        $('#createModal .modal-content').html(data);
                        $('#createModal').modal('show');

                        // Set today's date as default in create form
                        $('#createModal input[name="date"]').val(today);
                    },
                    error: function(xhr) {
                        console.log('Error loading create form:', xhr);
                        alert('Error loading form. Please try again.');
                    }
                });
            });

            // Create entry form submission
            $(document).on('submit', '#createEntryForm', function(e) {
                e.preventDefault();
                let form = this;

                if (!form.checkValidity()) {
                    e.stopPropagation();
                    $(form).addClass('was-validated');
                    return false;
                }

                var formData = new FormData(form);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: $(form).attr('action'),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (typeof $.toast === 'function') {
                            $.toast({
                                heading: 'Success',
                                text: response.message,
                                position: 'top-center',
                                icon: 'success',
                                loader: false
                            });
                        } else {
                            alert('Success: ' + response.message);
                        }
                        table.draw();
                        $('#createModal').modal('hide');
                    },
                    error: function(xhr) {
                        let errorMessage = '';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errorMessage += value + '<br>';
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else {
                            errorMessage = 'An error occurred. Please try again.';
                        }

                        $('#createEntryForm .server_side_error').html(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            errorMessage +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>'
                        );
                    }
                });
            });

            // Edit entry
            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');

                // Clear previous content
                $('#editModal .modal-content').html('');

                // Show loading
                $('#editModal .modal-content').html(`
                    <div class="modal-header">
                        <h5 class="modal-title">Loading...</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2">Loading entry data...</p>
                    </div>
                `);

                $('#editModal').modal('show');

                $.ajax({
                    url: "{{ url('admin/wash-report-entry/edit') }}/" + id,
                    type: "GET",
                    success: function(data) {
                        $('#editModal .modal-content').html(data);
                    },
                    error: function(xhr) {
                        $('#editModal .modal-content').html(`
                            <div class="modal-header">
                                <h5 class="modal-title">Error</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger">
                                    Error loading data. Please try again.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        `);
                    }
                });
            });

            // Update entry form submission
            $(document).on('submit', '#editEntryForm', function(e) {
                e.preventDefault();
                let form = this;

                if (!form.checkValidity()) {
                    e.stopPropagation();
                    $(form).addClass('was-validated');
                    return false;
                }

                var submitBtn = $('#updateEntryBtn');
                var originalText = submitBtn.html();
                submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

                var formData = new FormData(form);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: $(form).attr('action'),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (typeof $.toast === 'function') {
                            $.toast({
                                heading: 'Success',
                                text: response.message,
                                position: 'top-center',
                                icon: 'success',
                                loader: false
                            });
                        } else {
                            alert('Success: ' + response.message);
                        }
                        table.draw();
                        $('#editModal').modal('hide');
                    },
                    error: function(xhr) {
                        let errorMessage = '';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errorMessage += value + '<br>';
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else {
                            errorMessage = 'An error occurred. Please try again.';
                        }

                        $('#editEntryForm .server_side_error').html(
                            '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                            errorMessage +
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>'
                        );
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Delete entry
            $(document).on('click', '.delete_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');

                if (typeof Swal === 'function') {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteEntry(id, table);
                        }
                    });
                } else {
                    if (confirm('Are you sure you want to delete this record?')) {
                        deleteEntry(id, table);
                    }
                }
            });

            function deleteEntry(id, table) {
                $.ajax({
                    url: "{{ url('admin/wash-report-entry/delete') }}/" + id,
                    type: "GET",
                    success: function(response) {
                        if (response.success) {
                            if (typeof $.toast === 'function') {
                                $.toast({
                                    heading: 'Success',
                                    text: response.message,
                                    position: 'top-center',
                                    icon: 'success',
                                    loader: false
                                });
                            } else {
                                alert('Success: ' + response.message);
                            }
                            table.draw();
                        } else {
                            if (typeof $.toast === 'function') {
                                $.toast({
                                    heading: 'Error',
                                    text: response.message || 'An error occurred',
                                    position: 'top-center',
                                    icon: 'error',
                                    loader: false
                                });
                            } else {
                                alert('Error: ' + (response.message || 'An error occurred'));
                            }
                        }
                    },
                    error: function(xhr) {
                        if (typeof $.toast === 'function') {
                            $.toast({
                                heading: 'Error',
                                text: 'An error occurred while deleting',
                                position: 'top-center',
                                icon: 'error',
                                loader: false
                            });
                        } else {
                            alert('Error: An error occurred while deleting');
                        }
                    }
                });
            }

            // Fix for modal close buttons
            $(document).on('click', '[data-dismiss="modal"]', function(e) {
                e.preventDefault();
                $(this).closest('.modal').modal('hide');
            });

            // Clear modal content when hidden
            $('#createModal, #editModal').on('hidden.bs.modal', function() {
                $(this).find('.modal-content').html('');
            });
        });
    </script>
@endpush
