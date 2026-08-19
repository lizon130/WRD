<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Public Machine Dashboard | Machine Tool Solution</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastr for notifications -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
        }

        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: 1px solid #e3e6f0;
            transition: all 0.3s;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
            transform: translateY(-2px);
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            background-color: #f8f9fc;
            vertical-align: middle;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.85rem;
        }

        .bg-primary {
            background-color: #4e73df !important;
        }

        .bg-success {
            background-color: #1cc88a !important;
        }

        .bg-warning {
            background-color: #f6c23e !important;
        }

        .bg-info {
            background-color: #36b9cc !important;
        }

        .badge {
            font-size: 0.7rem;
            font-weight: 600;
        }

        .text-success {
            color: #1cc88a !important;
        }

        .text-danger {
            color: #e74a3b !important;
        }

        .text-info {
            color: #36b9cc !important;
        }

        .text-warning {
            color: #f6c23e !important;
        }

        .text-primary {
            color: #4e73df !important;
        }

        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            margin-top: 40px;
        }

        .info-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .auto-refresh-active {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.8rem;
            }

            .table th,
            .table td {
                padding: 0.5rem;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-industry me-2"></i>Machine Dashboard
            </a>
            <div class="navbar-text text-white">
                <span class="badge bg-light text-dark me-2">
                    <i class="fas fa-eye me-1"></i>Public View
                </span>
                <span class="badge bg-success" id="lastUpdated">
                    <i class="fas fa-clock me-1"></i>Last Updated: {{ now()->format('H:i') }}
                </span>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 mt-4">
        <!-- Date Filter -->
        <div class="card">
            <div class="card-body">
                <form id="dashboardFilter">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" class="form-control" name="date_from" id="date_from"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" name="date_to" id="date_to"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="unit_id" class="form-label">Select Unit</label>
                                <select class="form-control" name="unit_id" id="unit_id">
                                    <option value="">All Units</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->unitName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-end">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa-solid fa-filter"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Units</div>
                                <div class="h5 mb-0" id="totalUnits">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-building fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Machines</div>
                                <div class="h5 mb-0" id="totalMachines">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-cogs fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Total Transfers</div>
                                <div class="h5 mb-0" id="totalTransfers">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fa-solid fa-exchange-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unit-wise Details -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="m-0">
                                <i class="fa-solid fa-building me-2"></i>Unit-wise Machine & Capacity Details
                            </h5>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2">
                                    <i class="fa-solid fa-sync-alt fa-spin me-1"></i>Auto-refresh (5 min)
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="unitDetailsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th rowspan="2">Date</th>
                                        <th rowspan="2">Unit Name</th>
                                        <th colspan="2" class="text-center">Machines</th>
                                        <th colspan="2" class="text-center">MG Target</th>
                                        <th colspan="2" class="text-center">Capacity (KG)</th>
                                        <th colspan="2" class="text-center">Capacity (Pieces)</th>
                                        <th colspan="2" class="text-center">Wash Production</th>
                                        <th rowspan="2">Target/Machine</th>
                                        <th rowspan="2">KG/Machine</th>
                                        <th rowspan="2">Pieces/Machine</th>
                                        <th rowspan="2">Transfers In</th>
                                        <th rowspan="2">Transfers Out</th>
                                        <th rowspan="2">Net Change</th>
                                        <th rowspan="2">Status</th>
                                        <th rowspan="2">Verified Status</th>
                                    </tr>
                                    <tr>
                                        <th>Base</th>
                                        <th>Current</th>
                                        <th>Base</th>
                                        <th>Current</th>
                                        <th>Base</th>
                                        <th>Current</th>
                                        <th>Base</th>
                                        <th>Current</th>
                                        <th class="text-success">Received</th>
                                        <th class="text-primary">Delivery</th>
                                    </tr>
                                </thead>
                                <tbody id="unitDetailsBody">
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Transfers -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="m-0">
                            <i class="fa-solid fa-chart-pie me-2"></i>Current Machine Distribution
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="machineDistributionChart" width="100%" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="m-0">
                            <i class="fa-solid fa-clock-rotate-left me-2"></i>Recent Transfers
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="recentTransfers">
                            <!-- Recent transfers will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transfer History -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="m-0">
                    <i class="fa-solid fa-list me-2"></i>Detailed Transfer History
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="transferHistoryTable">
                        <thead class="text-dark">
                            <tr>
                                <th>Date</th>
                                <th>From Unit</th>
                                <th>To Unit</th>
                                <th>Machines</th>
                                <th>Hours</th>
                                <th>From MG Target</th>
                                <th>To MG Target</th>
                                <th>From Capacity (KG)</th>
                                <th>To Capacity (KG)</th>
                                <th>From Capacity (Pieces)</th>
                                <th>To Capacity (Pieces)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="transferHistoryBody">
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid text-center">
            <p class="mb-1">
                <i class="fas fa-industry me-2"></i>Wash Machine Dashboard - Tusuka
            </p>
            <p class="mb-0">
                <small>
                    <i class="fas fa-clock me-1"></i>Tusuka Wash |
                    <i class="fas fa-globe me-1 ms-2"></i>Real-time Machine Data Monitoring
                </small>
            </p>
        </div>
    </footer>

    <script type="text/javascript">
        let machineDistributionChart;

        // Format number with commas
        function formatNumber(num) {
            return new Intl.NumberFormat('en-US').format(Math.round(num));
        }

        // Format decimal numbers
        function formatDecimal(num) {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(num);
        }

        // Load dashboard data
        // Load dashboard data
        function loadDashboardData() {
            // Get filter values
            const date_from = $('#date_from').val();
            const date_to = $('#date_to').val();
            const unit_id = $('#unit_id').val();

            // Show loading state
            $('#unitDetailsBody').html(`
        <tr>
            <td colspan="20" class="text-center text-muted py-4">
                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                Loading dashboard data...
            </td>
        </tr>
    `);

            // Build query parameters
            const params = new URLSearchParams();
            if (date_from) params.append('date_from', date_from);
            if (date_to) params.append('date_to', date_to);
            if (unit_id) params.append('unit_id', unit_id);

            // Load main dashboard data
            $.ajax({
                url: "{{ route('dashboard.data') }}" + (params.toString() ? '?' + params.toString() : ''),
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.error) {
                        toastr.error(response.error);
                        return;
                    }

                    updateSummaryCards(response.summary);
                    updateUnitDetails(response.unitDetails, response.dateWiseTotals); // Updated this line
                    updateRecentTransfers(response.recentTransfers);
                    updateTransferHistory(response.transferHistory);
                    updateCharts(response.chartData);
                    updateLastUpdated();

                    // Show success notification
                    toastr.success('Dashboard data updated successfully', 'Success', {
                        positionClass: 'toast-top-right',
                        timeOut: 3000
                    });
                },
                error: function(xhr) {
                    console.error('Error loading dashboard data:', xhr);
                    toastr.error('Failed to load dashboard data', 'Error', {
                        positionClass: 'toast-top-right',
                        timeOut: 5000
                    });
                }
            });
        }

        // Update summary cards
        function updateSummaryCards(summary) {
            $('#totalUnits').text(formatNumber(summary.totalUnits));
            $('#totalMachines').text(formatNumber(summary.totalMachines));
            $('#totalTransfers').text(formatNumber(summary.totalTransfers));
            $('#totalProduction').text(formatNumber(summary.totalProduction));
        }

        // Group unit details by date
        function groupUnitDetailsByDate(unitDetails) {
            const grouped = {};

            unitDetails.forEach(detail => {
                if (!grouped[detail.date]) {
                    grouped[detail.date] = [];
                }
                grouped[detail.date].push(detail);
            });

            return grouped;
        }

        // Update unit details table
        // Update unit details table with date-wise totals
        function updateUnitDetails(units, dateWiseTotals) {
            let html = '';

            if (units.length > 0) {
                const groupedData = groupUnitDetailsByDate(units);
                const dates = Object.keys(groupedData).sort().reverse();

                dates.forEach(date => {
                    const dateUnits = groupedData[date];
                    const firstUnit = dateUnits[0];
                    const dateTotals = dateWiseTotals && dateWiseTotals[date] ? dateWiseTotals[date] : null;

                    // Add date header row
                    html += `
                <tr class="table-primary">
                    <td colspan="20" class="text-center fw-bold">
                        <i class="fa-solid fa-calendar-day me-2"></i>${firstUnit.display_date}
                    </td>
                </tr>
            `;

                    // Add data rows for this date
                    dateUnits.forEach(unit => {
                        const netChange = unit.transfersIn - unit.transfersOut;
                        const statusClass = netChange > 0 ? 'success' : (netChange < 0 ? 'danger' :
                            'secondary');
                        const statusText = netChange > 0 ? 'Gain' : (netChange < 0 ? 'Loss' : 'Stable');

                        const machineChangeClass = unit.currentMachineCount > unit.baseMachineCount ?
                            'text-success fw-bold' :
                            (unit.currentMachineCount < unit.baseMachineCount ? 'text-danger fw-bold' :
                                'text-muted');

                        const mgTargetChangeClass = unit.currentMgTarget > unit.baseMgTarget ?
                            'text-success fw-bold' :
                            (unit.currentMgTarget < unit.baseMgTarget ? 'text-danger fw-bold' :
                                'text-muted');

                        const kgCapacityChangeClass = unit.currentCapacityKg > unit.baseCapacityKg ?
                            'text-success fw-bold' :
                            (unit.currentCapacityKg < unit.baseCapacityKg ? 'text-danger fw-bold' :
                                'text-muted');

                        const piecesCapacityChangeClass = unit.currentCapacityPieces > unit
                            .baseCapacityPieces ?
                            'text-success fw-bold' :
                            (unit.currentCapacityPieces < unit.baseCapacityPieces ? 'text-danger fw-bold' :
                                'text-muted');

                        const received = unit.received || 0;
                        const delivery = unit.delivery || 0;

                        html += `
                    <tr>
                        <td class="text-nowrap fw-bold text-primary">${unit.display_date}</td>
                        <td><strong>${unit.unitName}</strong></td>
                        <td class="text-muted">${formatNumber(unit.baseMachineCount)}</td>
                        <td class="${machineChangeClass}">${formatNumber(unit.currentMachineCount)}</td>
                        <td class="text-muted">${formatNumber(unit.baseMgTarget)}</td>
                        <td class="${mgTargetChangeClass}">${formatNumber(unit.currentMgTarget)}</td>
                        <td class="text-muted">${formatNumber(unit.baseCapacityKg)}</td>
                        <td class="${kgCapacityChangeClass}">${formatNumber(unit.currentCapacityKg)}</td>
                        <td class="text-muted">${formatNumber(unit.baseCapacityPieces)}</td>
                        <td class="${piecesCapacityChangeClass}">${formatNumber(unit.currentCapacityPieces)}</td>
                        <td class="text-success fw-bold text-center">${formatNumber(delivery)}</td>
                        <td class="text-primary fw-bold text-center">${formatNumber(received)}</td>
                        <td class="text-info fw-bold">${formatNumber(unit.mgTargetPerMachine)}</td>
                        <td class="text-warning fw-bold">${formatNumber(unit.capacityKgPerMachine)}</td>
                        <td class="text-primary fw-bold">${formatNumber(unit.capacityPiecesPerMachine)}</td>
                        <td class="text-success">+${formatNumber(unit.transfersIn)}</td>
                        <td class="text-danger">-${formatNumber(unit.transfersOut)}</td>
                        <td><span class="badge bg-${statusClass}">${netChange > 0 ? '+' : ''}${formatNumber(netChange)}</span></td>
                        <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                        <td><span class="badge bg-${unit.verified_status_class}">${unit.verified_status}</span></td>
                    </tr>
                `;
                    });

                    // Add DATE TOTAL row (after each date group)
                    if (dateTotals) {
                        const dateNetChangeClass = dateTotals.total_net_change > 0 ? 'success' :
                            (dateTotals.total_net_change < 0 ? 'danger' : 'secondary');

                        html += `
                    <tr class="table-warning fw-bold" style="background-color: #fff3cd !important;">
                        <td colspan="2" class="text-end text-dark">
                            <i class="fa-solid fa-calculator me-2"></i>Date Total (${dateTotals.unit_count || dateUnits.length} units):
                        </td>
                        <!-- Machines -->
                        <td class="text-dark">${formatNumber(dateTotals.total_base_machine_count || 0)}</td>
                        <td class="text-dark">${formatNumber(dateTotals.total_current_machine_count || 0)}</td>
                        <!-- MG Target -->
                        <td class="text-dark">${formatNumber(dateTotals.total_base_mg_target || 0)}</td>
                        <td class="text-dark">${formatNumber(dateTotals.total_current_mg_target || 0)}</td>
                        <!-- Capacity KG -->
                        <td class="text-dark">${formatNumber(dateTotals.total_base_capacity_kg || 0)}</td>
                        <td class="text-dark">${formatNumber(dateTotals.total_current_capacity_kg || 0)}</td>
                        <!-- Capacity Pieces -->
                        <td class="text-dark">${formatNumber(dateTotals.total_base_capacity_pieces || 0)}</td>
                        <td class="text-dark">${formatNumber(dateTotals.total_current_capacity_pieces || 0)}</td>
                        <!-- Wash Production -->
                        <td class="text-dark text-center">${formatNumber(dateTotals.total_delivery || 0)}</td>
                        <td class="text-dark text-center">${formatNumber(dateTotals.total_received || 0)}</td>
                        <td colspan="8" class="text-center text-dark">
                            <i class="fa-solid fa-layer-group me-1"></i>Datewise Summary
                        </td>
                    </tr>
                `;
                    }

                    // Add separator row between dates
                    html += `
                <tr>
                    <td colspan="20" style="height: 10px; background-color: #f8f9fa;"></td>
                </tr>
            `;
                });
            } else {
                html = `
            <tr>
                <td colspan="20" class="text-center text-muted py-4">
                    <i class="fa-solid fa-info-circle me-2"></i>No data found for selected period
                </td>
            </tr>
        `;
            }

            $('#unitDetailsBody').html(html);
        }

        // Update recent transfers
        function updateRecentTransfers(transfers) {
            let html = '';
            if (transfers.length > 0) {
                transfers.forEach(transfer => {
                    html += `
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between">
                                <strong>${transfer.from_unit} → ${transfer.to_unit}</strong>
                                <small class="text-muted">${transfer.machine_count} machines</small>
                            </div>
                            <div class="small text-muted">
                                <i class="fa-solid fa-clock me-1"></i>${transfer.hours} hours
                                <span class="ms-2">
                                    <i class="fa-solid fa-chart-line me-1"></i>${formatNumber(transfer.production)}
                                </span>
                            </div>
                            <div class="small">${transfer.date}</div>
                        </div>
                    `;
                });
            } else {
                html = `
                    <p class="text-muted text-center py-3">
                        <i class="fa-solid fa-info-circle me-2"></i>No transfers found for selected period
                    </p>
                `;
            }
            $('#recentTransfers').html(html);
        }

        // Update transfer history table
        function updateTransferHistory(history) {
            let html = '';
            if (history.length > 0) {
                history.forEach(transfer => {
                    html += `
                        <tr>
                            <td>${transfer.transfer_date}</td>
                            <td><strong>${transfer.from_unit_name}</strong></td>
                            <td><strong>${transfer.to_unit_name}</strong></td>
                            <td class="text-center">
                                <span class="badge bg-primary">${transfer.machine_count}</span>
                            </td>
                            <td class="text-center">${transfer.hours}</td>
                            <td>
                                <div class="small">
                                    <span class="text-danger">Before: ${formatNumber(transfer.from_mg_target_before)}</span><br>
                                    <span class="text-success">After: ${formatNumber(transfer.from_mg_target_after)}</span><br>
                                    <span class="text-muted">
                                        Change: ${formatNumber(transfer.from_mg_target_after - transfer.from_mg_target_before)}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="text-danger">Before: ${formatNumber(transfer.to_mg_target_before)}</span><br>
                                    <span class="text-success">After: ${formatNumber(transfer.to_mg_target_after)}</span><br>
                                    <span class="text-muted">
                                        Change: ${formatNumber(transfer.to_mg_target_after - transfer.to_mg_target_before)}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="text-danger">Before: ${formatNumber(transfer.from_capacity_kg_before)}</span><br>
                                    <span class="text-success">After: ${formatNumber(transfer.from_capacity_kg_after)}</span><br>
                                    <span class="text-muted">
                                        Change: ${formatNumber(transfer.from_capacity_kg_after - transfer.from_capacity_kg_before)}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="text-danger">Before: ${formatNumber(transfer.to_capacity_kg_before)}</span><br>
                                    <span class="text-success">After: ${formatNumber(transfer.to_capacity_kg_after)}</span><br>
                                    <span class="text-muted">
                                        Change: ${formatNumber(transfer.to_capacity_kg_after - transfer.to_capacity_kg_before)}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="text-danger">Before: ${formatNumber(transfer.from_capacity_pieces_before)}</span><br>
                                    <span class="text-success">After: ${formatNumber(transfer.from_capacity_pieces_after)}</span><br>
                                    <span class="text-muted">
                                        Change: ${formatNumber(transfer.from_capacity_pieces_after - transfer.from_capacity_pieces_before)}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="text-danger">Before: ${formatNumber(transfer.to_capacity_pieces_before)}</span><br>
                                    <span class="text-success">After: ${formatNumber(transfer.to_capacity_pieces_after)}</span><br>
                                    <span class="text-muted">
                                        Change: ${formatNumber(transfer.to_capacity_pieces_after - transfer.to_capacity_pieces_before)}
                                    </span>
                                </div>
                            </td>
                            <td><span class="badge bg-success">Completed</span></td>
                        </tr>
                    `;
                });
            } else {
                html = `
                    <tr>
                        <td colspan="13" class="text-center text-muted py-4">
                            <i class="fa-solid fa-info-circle me-2"></i>No transfer history found for selected period
                        </td>
                    </tr>
                `;
            }
            $('#transferHistoryBody').html(html);
        }

        // Update charts
        function updateCharts(chartData) {
            // Destroy existing chart if it exists
            if (machineDistributionChart) {
                machineDistributionChart.destroy();
            }

            // Machine Distribution Chart
            const ctx = document.getElementById('machineDistributionChart').getContext('2d');
            machineDistributionChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        data: chartData.machineCounts,
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
                            '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${formatNumber(value)} machines (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Update last updated timestamp
        function updateLastUpdated() {
            const now = new Date();
            const timeString = now.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            $('#lastUpdated').html(`
                <i class="fas fa-clock me-1"></i>Last Updated: ${timeString}
            `);

            // Add pulse animation
            $('#lastUpdated').addClass('auto-refresh-active');
            setTimeout(() => {
                $('#lastUpdated').removeClass('auto-refresh-active');
            }, 2000);
        }

        $(document).ready(function() {
            // Load initial data
            loadDashboardData();

            // Start auto-refresh every 5 minutes (300000 ms)
            setInterval(function() {
                console.log('Auto-refreshing public dashboard data...');
                loadDashboardData();
            }, 300000); // 5 minutes = 300000 milliseconds

            // Filter form submission
            $(document).on('submit', '#dashboardFilter', function(e) {
                e.preventDefault();
                loadDashboardData();
            });

            // Real-time updates when inputs change
            $(document).on('change', '#date_from, #date_to, #unit_id', function() {
                loadDashboardData();
            });

            // Configure Toastr
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        });
    </script>
</body>

</html>