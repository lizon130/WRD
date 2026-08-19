@extends('backend.layout.app')
@section('title', 'Dryer Management | ' . Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Dryer Management</h4>

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
                                @foreach (['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL'] as $unit)
                                    <option value="{{ $unit }}">{{ $unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group text-end mt-2">
                                <button type="submit" id="filterBtn" class="btn btn-primary">
                                    <i class="fa fa-search mr-2"></i> Search
                                </button>
                                <button type="button" id="resetBtn" class="btn btn-secondary">
                                    <i class="fa fa-refresh mr-2"></i> Reset
                                </button>
                                <button type="button" id="todayBtn" class="btn btn-info">
                                    <i class="fa fa-calendar mr-2"></i> Today
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
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Dryers Data</h5>
                        <button type="button" class="btn btn-primary btn-sm btn-create-dryer" data-toggle="modal"
                            data-target="#createModal">
                            <i class="fa fa-plus"></i> Add Dryer Data
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="dataTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Unit</th>
                                <th>No. of Dryers</th>
                                <th>Capacity (KG)</th>
                                <th>Avg Dryer Time</th>
                                <th>Avg Batch</th>
                                <th>Working Hr</th>
                                {{-- <th>Target Qty</th> --}}
                                <th>First Wash</th>
                                <th>Cold Dryer</th>
                                <th>Measurement Corr.</th>
                                <th>Final Wash</th>
                                {{-- <th>Delivery Dev.</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

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
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.dryer.get.list') }}",
                        type: 'GET',
                        data: function(d) {
                            d.date = $('#dateFilter').val();
                            d.unit = $('#unitFilter').val();
                            d.start_date = $('#start_date').val();
                            d.end_date = $('#end_date').val();
                        }
                    },
                    pageLength: 25,
                    lengthMenu: [
                        [25, 50, 100, 500, -1],
                        [25, 50, 100, 500, "All"]
                    ],
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
                            data: 'num_dryer',
                            name: 'num_dryer'
                        },
                        {
                            data: 'capacity',
                            name: 'capacity'
                        },
                        {
                            data: 'avg_dryer_time',
                            name: 'avg_dryer_time'
                        },
                        {
                            data: 'avg_batch',
                            name: 'avg_batch'
                        },
                        {
                            data: 'working_hr',
                            name: 'working_hr'
                        },
                        // {
                        //     data: 'targetQty',
                        //     name: 'targetQty'
                        // },
                        {
                            data: 'first_wash_dryer',
                            name: 'first_wash_dryer'
                        },
                        {
                            data: 'cold_dryer',
                            name: 'cold_dryer'
                        },
                        {
                            data: 'measurement_correction',
                            name: 'measurement_correction'
                        },
                        {
                            data: 'final_wash_dryer',
                            name: 'final_wash_dryer'
                        },
                        // { data: 'delivery_deviation', name: 'delivery_deviation' },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
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
                    $('#start_date').val('');
                    $('#end_date').val('');
                    table.draw();
                });

                // Set today's data
                $('#todayBtn').on('click', function() {
                    $('#dateFilter').val(today);
                    $('#start_date').val('');
                    $('#end_date').val('');
                    $('#unitFilter').val('');
                    table.draw();
                });

                // Load create form modal
                $('[data-target="#createModal"]').on('click', function() {
                    $.ajax({
                        url: "{{ route('admin.dryer.create.form') }}",
                        type: "GET",
                        success: function(data) {
                            $('#createModal .modal-content').html(data);
                            $('#createModal').modal('show');

                            // Set today's date as default in create form
                            $('#createModal input[name="date"]').val(today);

                            // Initialize create modal events
                            initCreateModalEvents();
                        },
                        error: function(xhr) {
                            console.log('Error loading create form:', xhr);
                        }
                    });
                });

                // Initialize create modal events
                function initCreateModalEvents() {
                    // Auto-set num_dryer based on unit selection
                    $('#createModal select[name="unit"]').on('change', function() {
                        var unit = $(this).val();
                        var dryerCounts = {
                            'Unit 1': 8,
                            'Unit 2': 7,
                            'Unit 3': 7,
                            'Unit 4': 5,
                            'Unit 5': 4,
                            'Unit TWL': 8
                        };

                        if (unit && dryerCounts[unit]) {
                            $('#createModal input[name="num_dryer"]').val(dryerCounts[unit]);
                        } else {
                            $('#createModal input[name="num_dryer"]').val('');
                        }
                    });

                    // Create dryer form submission
                    $(document).off('click', '#createDryerBtn').on('click', '#createDryerBtn', function(e) {
                        e.preventDefault();
                        let form = $('#createDryerForm')[0];
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
                                $.toast({
                                    heading: 'Success',
                                    text: response.message,
                                    position: 'top-center',
                                    icon: 'success'
                                });
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

                                $('#createDryerForm .server_side_error').html(
                                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                    errorMessage +
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                    '<span aria-hidden="true">&times;</span>' +
                                    '</button></div>'
                                );
                            }
                        });
                    });
                }

                // Edit dryer
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
                            <p class="mt-2">Loading dryer data...</p>
                        </div>
                    `);

                    $('#editModal').modal('show');

                    $.ajax({
                        url: "{{ url('admin/dryer/edit') }}/" + id,
                        type: "GET",
                        success: function(data) {
                            $('#editModal .modal-content').html(data);
                            initEditModalEvents();
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
                                        Error loading dryer data. Please try again.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            `);
                        }
                    });
                });

                // Initialize edit modal events
                function initEditModalEvents() {
                    // Auto-set num_dryer when unit is changed
                    $('#editModal select[name="unit"]').on('change', function() {
                        var unit = $(this).val();
                        var dryerCounts = {
                            'Unit 1': 8,
                            'Unit 2': 7,
                            'Unit 3': 7,
                            'Unit 4': 5,
                            'Unit 5': 4,
                            'Unit TWL': 8
                        };

                        if (unit && dryerCounts[unit]) {
                            $('#editModal input[name="num_dryer"]').val(dryerCounts[unit]);
                        }
                    });

                    // Update dryer form submission
                    $(document).off('click', '#editDryerBtn').on('click', '#editDryerBtn', function(e) {
                        e.preventDefault();

                        let form = document.getElementById('editDryerForm');
                        if (!form) return;

                        var formData = new FormData(form);
                        var submitBtn = $(this);
                        var originalText = submitBtn.html();

                        submitBtn.prop('disabled', true).html(
                            '<i class="fa fa-spinner fa-spin"></i> Updating...');

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
                                $.toast({
                                    heading: 'Success',
                                    text: response.message,
                                    position: 'top-center',
                                    icon: 'success'
                                });
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

                                $('#editDryerForm .server_side_error').html(
                                    '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                    errorMessage +
                                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                    '<span aria-hidden="true">&times;</span>' +
                                    '</button></div>'
                                );
                            },
                            complete: function() {
                                submitBtn.prop('disabled', false).html(originalText);
                            }
                        });
                    });
                }

                // Handle create form submission
                $(document).on('submit', '#createDryerForm', function(e) {
                    e.preventDefault();
                    let form = this;
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
                            $.toast({
                                heading: 'Success',
                                text: response.message,
                                position: 'top-center',
                                icon: 'success'
                            });
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

                            $('#createDryerForm .server_side_error').html(
                                '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                errorMessage +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                '<span aria-hidden="true">&times;</span>' +
                                '</button></div>'
                            );
                        }
                    });
                });

                // Delete dryer
                $(document).on('click', '.delete_btn', function(e) {
                    e.preventDefault();
                    let id = $(this).attr('data-id');

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
                            $.ajax({
                                url: "{{ url('admin/dryer/delete') }}/" + id,
                                type: "GET",
                                success: function(response) {
                                    if (response.success) {
                                        $.toast({
                                            heading: 'Success',
                                            text: response.message,
                                            position: 'top-center',
                                            icon: 'success'
                                        });
                                        table.draw();
                                    }
                                },
                                error: function() {
                                    $.toast({
                                        heading: 'Error',
                                        text: 'An error occurred while deleting',
                                        position: 'top-center',
                                        icon: 'error'
                                    });
                                }
                            });
                        }
                    });
                });

                // Clear modal content when hidden
                $('#createModal, #editModal').on('hidden.bs.modal', function() {
                    $(this).find('.modal-content').html('');
                });
            });
        </script>
    @endpush
@endsection
