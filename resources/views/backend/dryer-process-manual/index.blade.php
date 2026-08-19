{{-- resources/views/backend/dryer-process-manual/index.blade.php --}}
@extends('backend.layout.app')
@section('title', 'Dryer Process Manual')
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
                                <button type="submit" id="filterBtn" class="btn btn-primary">Search</button>
                                <button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
                                <button type="button" id="todayBtn" class="btn btn-info">Today</button>
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
                        <h5 class="m-0">Dry Process Manual Data</h5>
                        <div>
                            <div class="input-group" style="width: 250px;">
                                <input type="date" class="form-control" id="createDatePicker" value="{{ now()->toDateString() }}">
                                <button type="button" class="btn btn-primary btn-create">
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
                            <th>Process Stage</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
        "timeOut": 3000
    };
</script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ route('admin.dryer-process-manual.get.list') }}",
            type: "GET",
            data: function(d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
                d.date = $('#dateFilter').val();
                d.plantName = $('#plantFilter').val();
            }
        },
        columns: [
            { data: 'TransactionDate', name: 'TransactionDate' },
            { data: 'Unit', name: 'Unit' },
            { data: 'ProcessStageName', name: 'ProcessStageName' },
            { data: 'TotalQuantity', name: 'TotalQuantity' },
            {
                data: null,
                render: function(data) {
                    if(data.is_manual) {
                        return '<button class="btn btn-sm btn-danger btn-delete" data-id="'+data.manual_id+'">Delete</button>';
                    }
                    return '-';
                }
            }
        ],
        order: [[0, 'desc']],
        pageLength: 10
    });

    $('#filterBtn').on('click', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });

    $('#resetBtn').on('click', function() {
        $('#start_date').val('');
        $('#end_date').val('');
        $('#dateFilter').val('');
        $('#plantFilter').val('');
        table.ajax.reload();
    });

    $('#todayBtn').on('click', function() {
        var today = new Date().toISOString().split('T')[0];
        $('#dateFilter').val(today);
        $('#start_date').val('');
        $('#end_date').val('');
        table.ajax.reload();
    });

    $('.btn-create').on('click', function() {
        var date = $('#createDatePicker').val();
        window.location.href = "{{ route('admin.dryer-process-manual.create.form') }}?date=" + date;
    });

    $('#dataTable').on('click', '.btn-delete', function() {
        if(confirm('Are you sure you want to delete this data?')) {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ url('admin/dryer-process-manual/delete') }}/" + id,
                type: 'GET',
                success: function(result) {
                    if(result.success) {
                        toastr.success(result.message);
                        table.ajax.reload();
                    } else {
                        toastr.error(result.message);
                    }
                }
            });
        }
    });
});
</script>
@endpush