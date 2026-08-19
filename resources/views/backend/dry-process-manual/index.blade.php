{{-- resources/views/backend/dry-process-manual/index.blade.php --}}
@extends('backend.layout.app')
@section('title', 'Dry Process Manual | ' . Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Dry Process Manual</h4>

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
                            <label for="plantFilter" class="form-label">Plant</label>
                            <select class="form-control" name="plantName" id="plantFilter">
                                <option value="">All Plants</option>
                                @foreach ($plants as $plant)
                                    <option value="{{ $plant }}">{{ $plant }}</option>
                                @endforeach
                            </select>
                        </div>
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
                            <h5 class="m-0">Dry Process Manual Data</h5>
                        </div>
                        <div>
                            <div class="input-group" style="width: 250px;">
                                <input type="date" class="form-control" id="createDatePicker" 
                                       value="{{ now()->toDateString() }}">
                                <button type="button" class="btn btn-primary btn-create-dryprocessmanual" 
                                        data-date="{{ now()->toDateString() }}">
                                    <i class="fa-solid fa-plus"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="dataTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Plant</th>
                            <th colspan="2" class="text-center bg-light">Whisker</th>
                            <th colspan="2" class="text-center bg-light">Hand Brush</th>
                            <th colspan="3" class="text-center bg-light">1st Dry Final</th>
                            <th colspan="3" class="text-center bg-light">2nd Dry Final</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Target</th>
                            <th>Production</th>
                            <th>Target</th>
                            <th>Production</th>
                            <th>Target</th>
                            <th>Production</th>
                            <th>Defect</th>
                            <th>Target</th>
                            <th>Production</th>
                            <th>Defect</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
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
            $('#createDatePicker').val(today);

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
                scrollX: true,
                ajax: {
                    url: "{{ route('admin.dry-process-manual.get.list') }}",
                    type: 'GET',
                    data: function(d) {
                        d.date = $('#dateFilter').val();
                        d.plantName = $('#plantFilter').val();
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
                columns: [
                    { data: 'date', name: 'date' },
                    { data: 'plantName', name: 'plantName' },
                    { data: 'whisker_target', name: 'whisker_target' },
                    { data: 'whisker_production', name: 'whisker_production' },
                    { data: 'handBrush_target', name: 'handBrush_target' },
                    { data: 'handBrush_production', name: 'handBrush_production' },
                    { data: 'FirstDryFinal_target', name: 'FirstDryFinal_target' },
                    { data: 'FirstDryFinal_production', name: 'FirstDryFinal_production' },
                    { data: 'FirstDryFinal_defectQty', name: 'FirstDryFinal_defectQty' },
                    { data: 'SecondDryFinal_target', name: 'SecondDryFinal_target' },
                    { data: 'SecondDryFinal_production', name: 'SecondDryFinal_production' },
                    { data: 'SecondDryFinal_defectQty', name: 'SecondDryFinal_defectQty' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: "text-center" }
                ],
                columnDefs: [
                    { targets: [2,3,4,5,6,7,8,9,10,11], className: "text-right" }
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
                $('#plantFilter').val('');
                $('#start_date').val('');
                $('#end_date').val('');
                table.draw();
            });

            // Set today's data
            $('#todayBtn').on('click', function() {
                $('#dateFilter').val(today);
                $('#plantFilter').val('');
                $('#start_date').val('');
                $('#end_date').val('');
                table.draw();
            });

            // Update create button date when picker changes
            $('#createDatePicker').on('change', function() {
                let selectedDate = $(this).val();
                $('.btn-create-dryprocessmanual').attr('data-date', selectedDate);
            });

            // Load create form modal with selected date
            $('.btn-create-dryprocessmanual').on('click', function() {
                let selectedDate = $(this).attr('data-date');
                
                $.ajax({
                    url: "{{ route('admin.dry-process-manual.create.form') }}",
                    type: "GET",
                    data: { date: selectedDate },
                    success: function(data) {
                        $('#createModal .modal-content').html(data);
                        $('#createModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Error loading form. Please try again.');
                    }
                });
            });

            // Edit record
            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');

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
                    </div>
                `);

                $('#editModal').modal('show');

                $.ajax({
                    url: "{{ url('admin/dry-process-manual/edit') }}/" + id,
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

            // Delete record
            $(document).on('click', '.delete_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');

                if (confirm('Are you sure you want to delete this record?')) {
                    $.ajax({
                        url: "{{ url('admin/dry-process-manual/delete') }}/" + id,
                        type: "GET",
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                table.draw();
                            } else {
                                alert(response.message || 'An error occurred');
                            }
                        },
                        error: function(xhr) {
                            alert('An error occurred while deleting');
                        }
                    });
                }
            });

            // Fix for modal close buttons
            $(document).on('click', '[data-dismiss="modal"]', function() {
                $(this).closest('.modal').modal('hide');
            });

            // Clear modal content when hidden
            $('#createModal, #editModal').on('hidden.bs.modal', function() {
                $(this).find('.modal-content').html('');
            });
        });
    </script>
@endpush