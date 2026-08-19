@extends('backend.layout.app')
@section('title', 'Machine Transfer | ' . Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <style>
        /* Keep all your existing styles */
        .card.border-danger {
            border-left: 4px solid #dc3545 !important;
        }

        .card.border-success {
            border-left: 4px solid #198754 !important;
        }

        /* Table header styling */
        .bg-danger {
            background: linear-gradient(135deg, #dc3545, #c82333) !important;
        }

        .bg-success {
            background: linear-gradient(135deg, #198754, #157347) !important;
        }

        /* Table row styling */
        .from-unit-row:hover {
            background-color: #f8d7da !important;
        }

        .to-unit-row:hover {
            background-color: #d1e7dd !important;
        }

        /* Compact table styling */
        #fromUnitTable th,
        #toUnitTable th {
            font-size: 0.75rem;
            padding: 8px 4px;
            text-align: center;
            white-space: nowrap;
        }

        #fromUnitTable td,
        #toUnitTable td {
            font-size: 0.8rem;
            padding: 6px 4px;
            vertical-align: middle;
        }

        /* Badge styling */
        .badge.bg-danger {
            font-size: 0.7rem;
            padding: 4px 8px;
        }

        .badge.bg-success {
            font-size: 0.7rem;
            padding: 4px 8px;
        }

        /* Text colors */
        .text-danger {
            color: #dc3545 !important;
            font-weight: 600;
        }

        .text-success {
            color: #198754 !important;
            font-weight: 600;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .col-lg-6 {
                margin-bottom: 20px;
            }

            #fromUnitTable th,
            #toUnitTable th {
                font-size: 0.7rem;
                padding: 6px 2px;
            }

            #fromUnitTable td,
            #toUnitTable td {
                font-size: 0.75rem;
                padding: 4px 2px;
            }
        }

        /* Action buttons styling */
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        /* Column specific styling */
        .text-nowrap {
            white-space: nowrap;
        }

        .small {
            font-size: 0.8rem;
        }
    </style>
    <div class="container-fluid px-4">
        <h4 class="mt-2">Machine Transfer Management</h4>

        <div class="card my-2">
            <div class="card-header">
                <div class="row ">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <h5 class="m-0">Transfer Machines</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="transferForm" action="{{ route('admin.machineTrans.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="transfer_date" class="form-label">Transfer Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="transfer_date" id="transfer_date"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="from_unit_id" class="form-label">From Unit <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" name="from_unit_id" id="from_unit_id" required>
                                    <option value="">-- Select From Unit --</option>
                                    @foreach ($unitsWithCurrentCounts as $unitData)
                                        <option value="{{ $unitData['unit']->id }}"
                                            data-machine-count="{{ $unitData['current_machine_count'] }}"
                                            data-base-machine-count="{{ $unitData['unit']->machineCount }}"
                                            data-mg-target="{{ $unitData['unit']->mgTarget }}"
                                            data-capacity-kg="{{ $unitData['unit']->capacity_kg }}"
                                            data-capacity-pieces="{{ $unitData['unit']->capacity_pieces }}">
                                            {{ $unitData['unit']->unitName }} ({{ $unitData['current_machine_count'] }}
                                            machines)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="to_unit_id" class="form-label">To Unit <span
                                        class="text-danger">*</span></label>
                                <select class="form-control" name="to_unit_id" id="to_unit_id" required>
                                    <option value="">-- Select To Unit --</option>
                                    @foreach ($unitsWithCurrentCounts as $unitData)
                                        <option value="{{ $unitData['unit']->id }}"
                                            data-machine-count="{{ $unitData['current_machine_count'] }}"
                                            data-base-machine-count="{{ $unitData['unit']->machineCount }}"
                                            data-mg-target="{{ $unitData['current_mg_target'] }}"
                                            data-capacity-kg="{{ $unitData['unit']->capacity_kg }}"
                                            data-capacity-pieces="{{ $unitData['unit']->capacity_pieces }}">
                                            {{ $unitData['unit']->unitName }} ({{ $unitData['current_machine_count'] }}
                                            machines)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="machine_count" class="form-label">Number of Machines <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="machine_count" id="machine_count"
                                    placeholder="Enter machine count" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="hours" class="form-label">Transfer Duration <span
                                        class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="small">Start Time</label>
                                        <input type="time" class="form-control" name="start_time" id="start_time"
                                            value="08:00" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="small">End Time</label>
                                        <input type="time" class="form-control" name="end_time" id="end_time"
                                            value="16:00" required>
                                    </div>
                                </div>
                                <input type="hidden" name="hours" id="hours" value="8">
                                <small class="text-muted" id="hoursDisplay">Duration: 8 hours</small>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group text-end mt-3">
                                <button type="submit" id="transferBtn" class="btn btn-primary"><i
                                        class="fa-solid fa-exchange-alt mr-2"></i> Transfer Machines</button>
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
                        <h5 class="m-0">Transfer History</h5>
                        {{-- <button id="refreshBtn" class="btn btn-sm btn-warning">
                            <i class="fa fa-refresh"></i> Refresh Today's Data
                        </button> --}}
                        <button id="fixAllBtn" class="btn btn-sm btn-danger">
                            <i class="fa fa-wrench"></i> Refresh Today's Data
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- From Unit Table -->
                    <div class="col-lg-6">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h6 class="m-0"><i class="fa-solid fa-arrow-up-from-bracket me-2"></i>From Unit -
                                    Transfer Out Details</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" id="fromUnitTable">
                                        <thead class="table-danger">
                                            <tr>
                                                <th>Date</th>
                                                <th>Unit</th>
                                                <th>Machines</th>
                                                <th>Hours</th>
                                                <th>Machine Change</th>
                                                <th>MG Target</th>
                                                <th>KG Change</th>
                                                <th>Pieces Change</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- To Unit Table -->
                    <div class="col-lg-6">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="m-0"><i class="fa-solid fa-arrow-down-to-bracket me-2"></i>To Unit -
                                    Transfer In Details</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" id="toUnitTable">
                                        <thead class="table-success">
                                            <tr>
                                                <th>Date</th>
                                                <th>Unit</th>
                                                <th>Machines</th>
                                                <th>Hours</th>
                                                <th>Machine Change</th>
                                                <th>MG Target</th>
                                                <th>KG Change</th>
                                                <th>Pieces Change</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('footer')
        <script type="text/javascript">
            function getTransfers() {
                // Common AJAX configuration
                var commonConfig = {
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{ url('admin/machine-tranfer/get/list') }}",
                        type: 'GET',
                    },
                    aLengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    iDisplayLength: 25,
                    "order": [
                        [0, 'desc']
                    ],
                    "autoWidth": false
                };

                // From Unit Table - Shows transfers where this unit lost machines
                var fromTable = jQuery('#fromUnitTable').DataTable({
                    ...commonConfig,
                    columns: [{
                            data: 'transfer_date',
                            name: 'transfer_date',
                            className: 'text-nowrap small'
                        },
                        {
                            data: 'from_unit_name',
                            name: 'from_unit_name',
                            className: 'fw-bold text-danger'
                        },
                        {
                            data: 'machine_count',
                            name: 'machine_count',
                            className: 'text-center fw-bold',
                            render: function(data, type, row) {
                                return '<span class="badge bg-danger">-' + data + '</span>';
                            }
                        },
                        {
                            data: 'hours',
                            name: 'hours',
                            className: 'text-center small'
                        },
                        {
                            data: 'from_unit_change',
                            name: 'from_unit_change',
                            orderable: false,
                            searchable: false,
                            className: 'text-center small text-danger'
                        },
                        {
                            data: 'mg_target_from_change',
                            name: 'mg_target_from_change',
                            orderable: false,
                            searchable: false,
                            className: 'text-center small'
                        },
                        {
                            data: 'capacity_kg_from_change',
                            name: 'capacity_kg_from_change',
                            orderable: false,
                            searchable: false,
                            className: 'text-center small'
                        },
                        {
                            data: 'capacity_pieces_from_change',
                            name: 'capacity_pieces_from_change',
                            orderable: false,
                            searchable: false,
                            className: 'text-center small'
                        },
                    ],
                    "drawCallback": function(settings) {
                        // Add negative styling for From table
                        $('#fromUnitTable tbody tr').addClass('from-unit-row');
                    }
                });

                // To Unit Table - Shows transfers where this unit gained machines
                var toTable = jQuery('#toUnitTable').DataTable({
                    ...commonConfig,
                    columns: [{
                            data: 'transfer_date',
                            name: 'transfer_date',
                            className: 'text-nowrap small'
                        },
                        {
                            data: 'to_unit_name',
                            name: 'to_unit_name',
                            className: 'fw-bold text-success'
                        },
                        {
                            data: 'machine_count',
                            name: 'machine_count',
                            className: 'text-center fw-bold',
                            render: function(data, type, row) {
                                return '<span class="badge bg-success">+' + data + '</span>';
                            }
                        },
                        {
                            data: 'hours',
                            name: 'hours',
                            className: 'text-center small'
                        },
                        {
                            data: 'to_unit_change',
                            name: 'to_unit_change',
                            orderable: false,
                            searchable: false,
                            className: 'text-center small text-success'
                        },
                        {
                            data: 'mg_target_to_change',
                            name: 'mg_target_to_change',
                            orderable: false,
                            searchable: false,
                            className: 'text-center small'
                        },
                        {
                            data: 'capacity_kg_to_change',
                            name: 'capacity_kg_to_change',
                            orderable: false,
                            searchable: false,
                            className: 'text-center small'
                        },
                        {
                            data: 'capacity_pieces_to_change',
                            name: 'capacity_pieces_to_change',
                            orderable: false,
                            searchable: false,
                            className: 'text-center small'
                        },
                    ],
                    "drawCallback": function(settings) {
                        // Add positive styling for To table
                        $('#toUnitTable tbody tr').addClass('to-unit-row');
                    }
                });
            }
            getTransfers();

            function calculateHoursFromTime() {
                let startTime = $('#start_time').val();
                let endTime = $('#end_time').val();
                let transferDate = $('#transfer_date').val();

                if (startTime && endTime && transferDate) {
                    // Parse the full datetime including date
                    let startDateTime = new Date(transferDate + 'T' + startTime);
                    let endDateTime = new Date(transferDate + 'T' + endTime);

                    // If end time is earlier than start time, assume it's the next day
                    if (endDateTime < startDateTime) {
                        // Add one day to end date
                        let nextDay = new Date(transferDate);
                        nextDay.setDate(nextDay.getDate() + 1);
                        let nextDayStr = nextDay.toISOString().split('T')[0];
                        endDateTime = new Date(nextDayStr + 'T' + endTime);
                    }

                    // Calculate difference in hours
                    let diffMs = endDateTime - startDateTime;
                    let hours = Math.max(1, Math.round((diffMs / (1000 * 60 * 60)) * 100) / 100);

                    // Ensure hours are between 1 and 24
                    hours = Math.min(24, Math.max(1, hours));

                    $('#hours').val(hours);
                    $('#hoursDisplay').text('Duration: ' + hours + ' hours');

                    return hours;
                }
                return 8; // Default 8 hours
            }

            // Function to update unit options based on selected date
            function updateUnitOptionsByDate() {
                let transferDate = $('#transfer_date').val();

                if (!transferDate) {
                    return;
                }

                $.ajax({
                    url: "{{ url('admin/machine-tranfer/get-units-by-date') }}/" + transferDate,
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        // Update From Unit dropdown
                        $('#from_unit_id').empty();
                        $('#from_unit_id').append('<option value="">-- Select From Unit --</option>');

                        $.each(response.units, function(index, unit) {
                            $('#from_unit_id').append(
                                '<option value="' + unit.id + '" ' +
                                'data-machine-count="' + unit.current_machine_count + '" ' +
                                'data-base-machine-count="' + unit.base_machine_count + '" ' +
                                'data-mg-target="' + unit.mg_target + '" ' +
                                'data-capacity-kg="' + unit.capacity_kg + '" ' +
                                'data-capacity-pieces="' + unit.capacity_pieces + '">' +
                                unit.unit_name + ' (' + unit.current_machine_count + ' machines)' +
                                '</option>'
                            );
                        });

                        // Update To Unit dropdown
                        $('#to_unit_id').empty();
                        $('#to_unit_id').append('<option value="">-- Select To Unit --</option>');

                        $.each(response.units, function(index, unit) {
                            $('#to_unit_id').append(
                                '<option value="' + unit.id + '" ' +
                                'data-machine-count="' + unit.current_machine_count + '" ' +
                                'data-base-machine-count="' + unit.base_machine_count + '" ' +
                                'data-mg-target="' + unit.current_mg_target + '" ' +
                                'data-capacity-kg="' + unit.capacity_kg + '" ' +
                                'data-capacity-pieces="' + unit.capacity_pieces + '">' +
                                unit.unit_name + ' (' + unit.current_machine_count + ' machines)' +
                                '</option>'
                            );
                        });

                        // Recalculate transfer after updating options
                        calculateTransfer();
                    },
                    error: function() {
                        console.log('Error loading units for date');
                    }
                });
            }

            // Function to calculate transfer and update display
            function calculateTransfer() {
                let machineCount = parseInt($('#machine_count').val()) || 0;
                let hours = calculateHoursFromTime(); // Use calculated hours from time selection
                let transferDate = $('#transfer_date').val();

                let fromUnitCurrent = parseInt($('#from_unit_id option:selected').data('machine-count')) || 0;
                let toUnitCurrent = parseInt($('#to_unit_id option:selected').data('machine-count')) || 0;

                // Get base machine counts for the day
                let fromUnitBase = parseInt($('#from_unit_id option:selected').data('base-machine-count')) || 0;
                let toUnitBase = parseInt($('#to_unit_id option:selected').data('base-machine-count')) || 0;

                // Get MG targets and capacities from data attributes
                let fromUnitMgTarget = parseFloat($('#from_unit_id option:selected').data('mg-target')) || 0;
                let toUnitMgTarget = parseFloat($('#to_unit_id option:selected').data('mg-target')) || 0;
                let fromUnitCapacityKg = parseFloat($('#from_unit_id option:selected').data('capacity-kg')) || 0;
                let fromUnitCapacityPieces = parseFloat($('#from_unit_id option:selected').data('capacity-pieces')) || 0;
                let toUnitCapacityKg = parseFloat($('#to_unit_id option:selected').data('capacity-kg')) || 0;
                let toUnitCapacityPieces = parseFloat($('#to_unit_id option:selected').data('capacity-pieces')) || 0;

                // Calculate MG Target per machine FROM unit's data
                // Use BASE machine count for per-machine calculations
                let fromUnitMgTargetPerMachine = fromUnitBase > 0 ? fromUnitMgTarget / fromUnitBase : 0;
                let toUnitMgTargetPerMachine = toUnitBase > 0 ? toUnitMgTarget / toUnitBase : 0;

                // Calculate hourly production per machine
                let fromUnitProductionPerMachinePerHour = fromUnitMgTargetPerMachine / 24;
                let toUnitProductionPerMachinePerHour = toUnitMgTargetPerMachine / 24;

                // Calculate capacity per machine and per hour
                let fromUnitKgPerMachine = fromUnitBase > 0 ? fromUnitCapacityKg / fromUnitBase : 0;
                let fromUnitKgPerHour = fromUnitKgPerMachine / 24;
                let fromUnitPiecesPerMachine = fromUnitBase > 0 ? fromUnitCapacityPieces / fromUnitBase : 0;
                let fromUnitPiecesPerHour = fromUnitPiecesPerMachine / 24;

                let toUnitKgPerMachine = toUnitBase > 0 ? toUnitCapacityKg / toUnitBase : 0;
                let toUnitKgPerHour = toUnitKgPerMachine / 24;
                let toUnitPiecesPerMachine = toUnitBase > 0 ? toUnitCapacityPieces / toUnitBase : 0;
                let toUnitPiecesPerHour = toUnitPiecesPerMachine / 24;

                // Calculate machine counts after transfer
                let fromUnitAfter = fromUnitCurrent - machineCount;
                let toUnitAfter = toUnitCurrent + machineCount;

                // Calculate FROM unit MG target changes (based on hourly production loss)
                let fromUnitMgBefore = fromUnitCurrent * fromUnitMgTargetPerMachine;
                let productionLoss = machineCount * fromUnitProductionPerMachinePerHour * hours;
                let fromUnitMgAfter = fromUnitMgBefore - productionLoss;

                // Calculate TO unit MG target changes (based on hourly production gain)
                let toUnitMgBefore = toUnitCurrent * toUnitMgTargetPerMachine;
                let productionGain = machineCount * toUnitProductionPerMachinePerHour * hours;
                let toUnitMgAfter = toUnitMgBefore + productionGain;

                // Calculate capacity changes (KG)
                let fromUnitKgLoss = machineCount * fromUnitKgPerHour * hours;
                let fromUnitKgAfter = fromUnitCapacityKg - fromUnitKgLoss;

                let toUnitKgGain = machineCount * toUnitKgPerHour * hours;
                let toUnitKgAfter = toUnitCapacityKg + toUnitKgGain;

                // Calculate capacity changes (Pieces)
                let fromUnitPiecesLoss = machineCount * fromUnitPiecesPerHour * hours;
                let fromUnitPiecesAfter = fromUnitCapacityPieces - fromUnitPiecesLoss;

                let toUnitPiecesGain = machineCount * toUnitPiecesPerHour * hours;
                let toUnitPiecesAfter = toUnitCapacityPieces + toUnitPiecesGain;

                // Calculate production (using from unit's production rate)
                let calculatedProduction = machineCount * fromUnitProductionPerMachinePerHour * hours;

                // Update display if you have these elements
                if ($('#fromUnitCurrent').length) {
                    $('#fromUnitCurrent').text(fromUnitCurrent);
                    $('#fromUnitAfter').text(fromUnitAfter);
                    $('#toUnitCurrent').text(toUnitCurrent);
                    $('#toUnitAfter').text(toUnitAfter);

                    $('#fromUnitMgBefore').text(Math.round(fromUnitMgBefore));
                    $('#fromUnitMgAfter').text(Math.round(fromUnitMgAfter));
                    $('#toUnitMgBefore').text(Math.round(toUnitMgBefore));
                    $('#toUnitMgAfter').text(Math.round(toUnitMgAfter));

                    // Update capacity display
                    $('#fromUnitKgBefore').text(Math.round(fromUnitCapacityKg));
                    $('#fromUnitKgAfter').text(Math.round(fromUnitKgAfter));
                    $('#toUnitKgBefore').text(Math.round(toUnitCapacityKg));
                    $('#toUnitKgAfter').text(Math.round(toUnitKgAfter));

                    $('#fromUnitPiecesBefore').text(Math.round(fromUnitCapacityPieces));
                    $('#fromUnitPiecesAfter').text(Math.round(fromUnitPiecesAfter));
                    $('#toUnitPiecesBefore').text(Math.round(toUnitCapacityPieces));
                    $('#toUnitPiecesAfter').text(Math.round(toUnitPiecesAfter));

                    $('#calculatedProduction').text(Math.round(calculatedProduction));
                }

                if (machineCount > 0 && hours > 0) {
                    $('#transferNote').text(
                        `Transferring ${machineCount} machines for ${hours} hours (${$('#start_time').val()} to ${$('#end_time').val()})`
                    );
                } else {
                    $('#transferNote').text('Enter machine count and select time to see calculation');
                }
            }

            $(document).ready(function() {
                // Update unit options based on today's date
                updateUnitOptionsByDate();

                // Calculate transfer when date changes
                $(document).on('change', '#transfer_date', function() {
                    updateUnitOptionsByDate();
                });

                // Calculate transfer on page load
                calculateTransfer();

                // Calculate transfer when inputs change
                $(document).on('input', '#machine_count, #start_time, #end_time', function() {
                    calculateTransfer();
                });

                // Calculate transfer when units change
                $(document).on('change', '#from_unit_id, #to_unit_id', function() {
                    calculateTransfer();
                });

                // Transfer form submission
                $(document).on('submit', '#transferForm', function(e) {
                    e.preventDefault();

                    // Validate from unit has enough machines
                    let fromUnitCurrent = parseInt($('#from_unit_id option:selected').data('machine-count')) ||
                        0;
                    let machineCount = parseInt($('#machine_count').val()) || 0;

                    if (fromUnitCurrent < machineCount) {
                        $.toast({
                            heading: 'Error',
                            text: 'From unit does not have enough machines. Available: ' +
                                fromUnitCurrent,
                            position: 'top-center',
                            icon: 'error'
                        });
                        return false;
                    }

                    let form = document.getElementById('transferForm');
                    var formData = new FormData(form);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: $('#transferForm').attr('action'),
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
                            getTransfers();

                            // Refresh the page to get updated machine counts
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(xhr) {
                            let errorMessage = 'An error occurred.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMessage = '';
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    errorMessage += ('' + value + '<br>');
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

                // Update unit options when from unit changes
                $(document).on('change', '#from_unit_id', function() {
                    let fromUnitId = $(this).val();
                    $('#to_unit_id option').show();
                    if (fromUnitId) {
                        $('#to_unit_id option[value="' + fromUnitId + '"]').hide();
                    }
                });

                // Set default end time based on start time
                $(document).on('change', '#start_time', function() {
                    let startTime = $(this).val();
                    let transferDate = $('#transfer_date').val();

                    if (startTime && transferDate) {
                        let startDateTime = new Date(transferDate + 'T' + startTime);
                        let endDateTime = new Date(startDateTime.getTime() + (8 * 60 * 60 *
                            1000)); // Add 8 hours

                        // Handle overnight
                        let endDate = new Date(endDateTime);
                        let endTimeString = endDate.toTimeString().substr(0, 5);
                        $('#end_time').val(endTimeString);

                        calculateTransfer();
                    }
                });
            });

            // Add this to your JavaScript
            $(document).on('click', '#refreshBtn', function() {
                let today = new Date().toISOString().split('T')[0];

                Swal.fire({
                    title: 'Refresh Data?',
                    text: 'This will recalculate all transfers for today (' + today + ')',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, refresh!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('admin/machine-tranfer/refresh/') }}/" + today,
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                $.toast({
                                    heading: 'Success',
                                    text: data.message,
                                    position: 'top-center',
                                    icon: 'success'
                                });

                                // Refresh the tables
                                $('#fromUnitTable').DataTable().ajax.reload();
                                $('#toUnitTable').DataTable().ajax.reload();

                                // Reload page after 2 seconds to update unit counts
                                setTimeout(function() {
                                    location.reload();
                                }, 2000);
                            },
                            error: function() {
                                $.toast({
                                    heading: 'Error',
                                    text: 'Failed to refresh data',
                                    position: 'top-center',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });

            // Edit and Delete functions (keep as is)
            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{ url('admin/machine-tranfer/edit/') }}/" + id,
                    type: "GET",
                    dataType: "html",
                    success: function(data) {
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
                            url: "{{ url('admin/machine-tranfer/delete/') }}/" + id,
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
                                    // Refresh the page to get updated machine counts
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    $.toast({
                                        heading: 'Error',
                                        text: data.error,
                                        position: 'top-center',
                                        icon: 'error'
                                    })
                                }
                                $('#dataTable').DataTable().destroy();
                                getTransfers();
                            }
                        })
                    }
                })
            });

            // Add this to your JavaScript
            $(document).on('click', '#fixAllBtn', function() {
                Swal.fire({
                    title: 'Fix All Transfer Data?',
                    text: 'This will recalculate ALL transfers with progressive counts. This may take a while.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, fix everything!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('admin/machine-tranfer/fix-all') }}",
                            type: "GET",
                            dataType: "json",
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Fixing Data...',
                                    text: 'Please wait while we recalculate all transfers.',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function(data) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    // Refresh the tables
                                    $('#fromUnitTable').DataTable().ajax.reload();
                                    $('#toUnitTable').DataTable().ajax.reload();

                                    // Reload page after 2 seconds
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000);
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Failed to fix data',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
