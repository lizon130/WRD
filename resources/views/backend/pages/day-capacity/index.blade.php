@extends('backend.layout.app')
@section('title', 'Day Capacity | '.Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Day Capacity Management</h4>

        <div class="card my-2">
            <div class="card-header">
                <div class="row ">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center"><h5 class="m-0">Set Day Capacity</h5></div>
                        <a href="{{ route('admin.dayCapacity.dashboard') }}" class="btn btn-info"><i class="fa-solid fa-chart-bar"></i> View Dashboard</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="capacityForm" action="{{ route('admin.dayCapacity.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="date" id="date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="unit_id" class="form-label">Unit <span class="text-danger">*</span></label>
                                <select class="form-control" name="unit_id" id="unit_id" required>
                                    <option value="">-- Select Unit --</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" data-machines="{{ $unit->machineCount }}">
                                            {{ $unit->unitName }} ({{ $unit->machineCount }} machines)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Per Hour Targets</label>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        Initial: <strong id="initialPerHour">41.67</strong> | 
                                        MG: <strong id="mgPerHour">39.86</strong> per machine
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Hourly Capacity (0-1 scale, where 1 = 100% capacity)</h6>
                            <div class="row" id="hourlyInputs">
                                @for($hour = 0; $hour < 24; $hour++)
                                    <div class="col-md-2 mb-2">
                                        <label class="form-label small">{{ sprintf('%02d:00', $hour) }}</label>
                                        <input type="number" class="form-control form-control-sm" 
                                               name="hour_{{ sprintf('%02d', $hour) }}" 
                                               value="1" min="0" max="1" step="0.01"
                                               placeholder="0-1">
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group text-end">
                                <button type="submit" id="capacityBtn" class="btn btn-primary"><i class="fa-solid fa-save mr-2"></i> Save Capacity</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card my-2">
            <div class="card-header">
                <div class="row ">
                    <div class="col-12">
                        <h5 class="m-0">Capacity History</h5>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Unit</th>
                            <th>Total Initial Target</th>
                            <th>Total MG Target</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('footer')
        <script type="text/javascript">
            function getCapacities(){
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ url('admin/day-capacity/get/list') }}",
                        type: 'GET',
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500, 5000, -1],
                        [25, 50, 100, 500, 5000, "All"]
                    ],
                    iDisplayLength: 25,
                    "order": [[0, 'desc']],
                    columns: [
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'unit_name',
                            name: 'unit_name'
                        },
                        {
                            data: 'total_initial_target',
                            name: 'total_initial_target'
                        },
                        {
                            data: 'total_mg_target',
                            name: 'total_mg_target'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            "className": "text-center w-10"
                        },
                    ]
                });
            }
            getCapacities();

            $(document).ready(function () {
                // Update per hour targets when unit changes
                $(document).on('change', '#unit_id', function() {
                    const machineCount = $(this).find('option:selected').data('machines') || 0;
                    const initialPerHour = (1000 / 24 * machineCount).toFixed(2);
                    const mgPerHour = (956.52 / 24 * machineCount).toFixed(2);
                    
                    $('#initialPerHour').text(initialPerHour);
                    $('#mgPerHour').text(mgPerHour);
                });

                // Capacity form submission
                $(document).on('submit', '#capacityForm', function(e) {
                    e.preventDefault();
                    
                    let form = document.getElementById('capacityForm');
                    var formData = new FormData(form);
                    
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#capacityForm').attr('action'),
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $.toast({
                                heading: 'Success',
                                text: response.message,
                                position: 'top-center',
                                icon: 'success'
                            })
                            $('#dataTable').DataTable().destroy();
                            getCapacities();
                            $('#capacityForm')[0].reset();
                        },
                        error: function (xhr) {
                            let errorMessage = 'An error occurred.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMessage = '';
                                $.each(xhr.responseJSON.errors, function(key,value) {
                                    errorMessage +=(''+value+'<br>');
                                });
                            }
                            $.toast({
                                heading: 'Error',
                                text: errorMessage,
                                position: 'top-center',
                                icon: 'error'
                            })
                        },
                    })
                });
            });

            // Edit and Delete functions
            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{  url('admin/day-capacity/edit/') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#editModal .modal-content').html(data);
                        $('#editModal').modal('show');
                    }
                })
            });

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
                            url: "{{  url('admin/day-capacity/delete/') }}/"+id,
                            type: "GET",
                            dataType: "json",
                            success: function (data) {
                                if (data.success) {
                                    $.toast({
                                        heading: 'Success',
                                        text: data.success,
                                        position: 'top-center',
                                        icon: 'success'
                                    })
                                } else {
                                    $.toast({
                                        heading: 'Error',
                                        text: data.error,
                                        position: 'top-center',
                                        icon: 'error'
                                    })
                                }
                                $('#dataTable').DataTable().destroy();
                                getCapacities();
                            }
                        })
                    }
                })
            });
        </script>
    @endpush
@endsection