@extends('backend.layout.app')
@section('title', 'Unit Management | ' . Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Unit Management</h4>

        <div class="card my-2">
            <div class="card-body pb-0">
                <form method="" id="filter_form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="unitName" id="unitName"
                                    placeholder="Unit Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="number" class="form-control" name="machineCount" id="machineCount"
                                    placeholder="Machine Count">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group text-end mt-2">
                                <button type="submit" id="filterBtn" name="submit" class="btn btn-primary"><i
                                        class="feather icon-file mr-2"></i> Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card my-2">
            <div class="card-header">
                <div class="row ">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <h5 class="m-0">Units List</h5>
                        </div>
                        <button type="button" class="btn btn-primary btn-create-unit create_form_btn"
                            data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa-solid fa-plus"></i> Add
                            Unit</button>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th>Machine Count</th>
                            <th>Initial Target</th>
                            <th>Management Target</th>
                            <th>Capacity (KG)</th>
                            <th>Capacity (Pieces)</th>
                            <th>Sewing Lines</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('backend.pages.unit.modal')
    @push('footer')
        <script type="text/javascript">
            function getUnits(unitName = null, machineCount = null) {
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ url('admin/unit/get/list') }}",
                        type: 'GET',
                        data: function(d) {
                            d.unitName = unitName;
                            d.machineCount = machineCount;
                        },
                        error: function(xhr, error, thrown) {
                            console.log('Ajax error:', error);
                            console.log('Server response:', xhr.responseText);
                        }
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500, 5000, -1],
                        [25, 50, 100, 500, 5000, "All"]
                    ],
                    iDisplayLength: 25,
                    "order": [
                        [0, 'asc']
                    ],
                    columns: [{
                            data: 'unitName',
                            name: 'unitName'
                        },
                        {
                            data: 'machineCount',
                            name: 'machineCount'
                        },
                        {
                            data: 'initialTarget',
                            name: 'initialTarget'
                        },
                        {
                            data: 'mgTarget',
                            name: 'mgTarget'
                        },
                        {
                            data: 'capacity_kg',
                            name: 'capacity_kg'
                        },
                        {
                            data: 'capacity_pieces',
                            name: 'capacity_pieces'
                        },

                        {
                            data: 'sewing_lines',
                            name: 'sewing_lines'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: "text-center w-10"
                        }
                    ]
                });
            }
            getUnits();

            // Real-time calculation for create form
            $(document).ready(function() {
                function calculatePieces() {
                    const capacityKg = parseFloat($('#createModal input[name="capacity_kg"]').val()) || 0;
                    const pieceWeightGram = parseFloat($('#createModal input[name="piece_weight_gram"]').val()) || 0;

                    if (pieceWeightGram > 0) {
                        const pieceWeightKg = pieceWeightGram / 1000;
                        const calculatedPieces = capacityKg / pieceWeightKg;
                        $('#createModal #calculated_pieces').val(calculatedPieces.toFixed(0));
                    } else {
                        $('#createModal #calculated_pieces').val('0');
                    }
                }

                // Real-time calculation for edit form
                function calculateEditPieces() {
                    const capacityKg = parseFloat($('#editModal input[name="capacity_kg"]').val()) || 0;
                    const pieceWeightGram = parseFloat($('#editModal input[name="piece_weight_gram"]').val()) || 0;

                    if (pieceWeightGram > 0) {
                        const pieceWeightKg = pieceWeightGram / 1000;
                        const calculatedPieces = capacityKg / pieceWeightKg;
                        $('#editModal #edit_calculated_pieces').val(calculatedPieces.toFixed(0));
                    } else {
                        $('#editModal #edit_calculated_pieces').val('0');
                    }
                }

                // Bind events when create modal is shown
                $('#createModal').on('shown.bs.modal', function() {
                    $('#createModal input[name="capacity_kg"], #createModal input[name="piece_weight_gram"]')
                        .on('input', calculatePieces);
                });

                // Bind events when edit modal is shown
                $('#editModal').on('shown.bs.modal', function() {
                    $('#editModal input[name="capacity_kg"], #editModal input[name="piece_weight_gram"]')
                        .on('input', calculateEditPieces);
                });

                // Reset calculated field when modals are hidden
                $('#createModal').on('hidden.bs.modal', function() {
                    $('#createModal #calculated_pieces').val('');
                    $('#createModal input[name="capacity_kg"], #createModal input[name="piece_weight_gram"]')
                        .off('input', calculatePieces);
                });

                $('#editModal').on('hidden.bs.modal', function() {
                    $('#editModal input[name="capacity_kg"], #editModal input[name="piece_weight_gram"]')
                        .off('input', calculateEditPieces);
                });

                // Initial calculation if modals are already open
                if ($('#createModal').hasClass('show')) {
                    calculatePieces();
                }
                if ($('#editModal').hasClass('show')) {
                    calculateEditPieces();
                }
            });

            $(document).on('click', '#createUnitBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#createModal .' + $(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    let form = document.getElementById('createUnitForm');
                    var formData = new FormData(form);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: $('#createUnitForm').attr('action'),
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
                            })
                            $('#dataTable').DataTable().destroy();
                            getUnits();
                            $('#createModal').modal('hide');
                            $('#createUnitForm')[0].reset();
                        },
                        error: function(xhr) {
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errorMessage += ('' + value + '<br>');
                            });
                            $('#createUnitForm .server_side_error').html(
                                '<div class="alert alert-danger" role="alert">' + errorMessage +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                            );
                        },
                    })
                }
            })

            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{ url('/admin/unit/edit/') }}/" + id,
                    type: "GET",
                    dataType: "html",
                    success: function(data) {
                        $('#editModal .modal-content').html(data);
                        $('#editModal').modal('show');
                    }
                })
            });

            $(document).on('click', '#editUnitBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#editModal .' + $(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    let form = document.getElementById('editUnitForm');
                    var formData = new FormData(form);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: $('#editUnitForm').attr('action'),
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
                            })
                            $('#dataTable').DataTable().destroy();
                            getUnits();
                            $('#editModal').modal('hide');
                        },
                        error: function(xhr) {
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errorMessage += ('' + value + '<br>');
                            });
                            $('#editUnitForm .server_side_error').html(
                                '<div class="alert alert-danger" role="alert">' + errorMessage +
                                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                            );
                        },
                    })
                }
            })

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
                            url: "{{ url('/admin/unit/delete/') }}/" + id,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
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
                                getUnits();
                            }
                        })
                    }
                })
            })
        </script>
    @endpush
@endsection
