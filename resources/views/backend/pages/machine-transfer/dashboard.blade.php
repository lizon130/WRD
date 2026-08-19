@extends('backend.layout.app')
@section('title', 'Machine Transfer Dashboard | ' . Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Machine Transfer Dashboard</h4>

        <!-- Date Filter -->
        <div class="card my-2">
            <div class="card-body">
                <form id="dashboardFilter">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" class="form-control" name="date_from" id="date_from"
                                    value="{{ date('Y-m-d') }}"> <!-- Changed from date('Y-m-01') -->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" name="date_to" id="date_to"
                                    value="{{ date('Y-m-d') }}"> <!-- Already correct -->
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
                                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter"></i>
                                    Filter</button>
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
                            <h5 class="m-0"><i class="fa-solid fa-building me-2"></i>Unit-wise Machine & Capacity Details
                            </h5>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2">
                                    <i class="fa-solid fa-sync-alt fa-spin me-1"></i>Auto-refresh
                                </span>
                                <span class="badge bg-primary" id="lastUpdated">
                                    <i class="fa-solid fa-clock me-1"></i>Last Updated: {{ now()->format('H:i') }}
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
                                        <!-- WASH PRODUCTION COLUMNS -->
                                        <th colspan="2" class="text-center">Wash Production</th>
                                        <!-- END WASH PRODUCTION COLUMNS -->
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
                                        <!-- WASH PRODUCTION HEADERS -->
                                        <th class="text-success">Received</th>
                                        <th class="text-primary">Delivery</th>

                                        <!-- END WASH PRODUCTION HEADERS -->
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
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="m-0"><i class="fa-solid fa-chart-pie me-2"></i>Current Machine Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="machineDistributionChart" width="100%" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="m-0"><i class="fa-solid fa-clock-rotate-left me-2"></i>Recent Transfers</h5>
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
                <h5 class="m-0"><i class="fa-solid fa-list me-2"></i>Detailed Transfer History</h5>
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

    @push('footer')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            function loadDashboardData() {
                const formData = new FormData(document.getElementById('dashboardFilter'));

                // Load main dashboard data
                $.ajax({
                    url: "{{ route('admin.machineTrans.dashboard.data') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        updateSummaryCards(response.summary);
                        updateUnitDetails(response.unitDetails);
                        updateRecentTransfers(response.recentTransfers);
                        updateTransferHistory(response.transferHistory);
                        updateCharts(response.chartData);
                        updateCapacitySummary(response.unitDetails);
                        $('#lastUpdated').text('Last Updated: ' + new Date().toLocaleString());
                    },
                    error: function(xhr) {
                        console.error('Error loading dashboard data:', xhr);
                        $.toast({
                            heading: 'Error',
                            text: 'Failed to load dashboard data',
                            position: 'top-center',
                            icon: 'error'
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

                // Update timestamp in a subtle way
                const now = new Date();
                $('#lastUpdated').text('Auto-refreshed: ' + now.toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                }));
            }

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

            // Update unit details table with wash data
            function updateUnitDetails(units) {
                let html = '';

                if (units.length > 0) {
                    const groupedData = groupUnitDetailsByDate(units);
                    const dates = Object.keys(groupedData).sort().reverse();

                    dates.forEach(date => {
                        const dateUnits = groupedData[date];
                        const firstUnit = dateUnits[0];

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

                            // Use actual wash data from the unit object
                            const received = unit.received || 0;
                            const delivery = unit.delivery || 0;

                            html += `
                    <tr>
                        <td class="text-nowrap fw-bold text-primary">${unit.display_date}</td>
                        <td>
                            <strong>${unit.unitName}</strong>
                        </td>
                        <td class="text-muted">${formatNumber(unit.baseMachineCount)}</td>
                        <td class="${machineChangeClass}">${formatNumber(unit.currentMachineCount)}</td>
                        <td class="text-muted">${formatNumber(unit.baseMgTarget)}</td>
                        <td class="${mgTargetChangeClass}">${formatNumber(unit.currentMgTarget)}</td>
                        <td class="text-muted">${formatNumber(unit.baseCapacityKg)}</td>
                        <td class="${kgCapacityChangeClass}">${formatNumber(unit.currentCapacityKg)}</td>
                        <td class="text-muted">${formatNumber(unit.baseCapacityPieces)}</td>
                        <td class="${piecesCapacityChangeClass}">${formatNumber(unit.currentCapacityPieces)}</td>
                        <!-- WASH DATA COLUMNS WITH ACTUAL DATA -->
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

            // Update capacity summary
            function updateCapacitySummary(units) {
                let totalKgCapacity = 0;
                let totalPiecesCapacity = 0;
                let totalMachines = 0;
                let totalKgPerMachine = 0;
                let totalPiecesPerMachine = 0;

                units.forEach(unit => {
                    totalKgCapacity += unit.currentCapacityKg;
                    totalPiecesCapacity += unit.currentCapacityPieces;
                    totalMachines += unit.currentMachineCount;
                    totalKgPerMachine += unit.capacityKgPerMachine;
                    totalPiecesPerMachine += unit.capacityPiecesPerMachine;
                });

                const avgKgPerMachine = totalMachines > 0 ? totalKgPerMachine / units.length : 0;
                const avgPiecesPerMachine = totalMachines > 0 ? totalPiecesPerMachine / units.length : 0;

                $('#totalKgCapacity').text(formatNumber(totalKgCapacity));
                $('#totalPiecesCapacity').text(formatNumber(totalPiecesCapacity));
                $('#avgKgPerMachine').text(formatNumber(avgKgPerMachine));
                $('#avgPiecesPerMachine').text(formatNumber(avgPiecesPerMachine));
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
                                    <span class="ms-2"><i class="fa-solid fa-chart-line me-1"></i>${formatNumber(transfer.production)}</span>
                                </div>
                                <div class="small">${transfer.date}</div>
                            </div>
                        `;
                    });
                } else {
                    html =
                        '<p class="text-muted text-center py-3"><i class="fa-solid fa-info-circle me-2"></i>No transfers found for selected period</p>';
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
                                <td class="text-center"><span class="badge bg-primary">${transfer.machine_count}</span></td>
                                <td class="text-center">${transfer.hours}</td>
                                <td>
                                    <div class="small">
                                        <span class="text-danger">Before: ${formatNumber(transfer.from_mg_target_before)}</span><br>
                                        <span class="text-success">After: ${formatNumber(transfer.from_mg_target_after)}</span><br>
                                        <span class="text-muted">Change: ${formatNumber(transfer.from_mg_target_after - transfer.from_mg_target_before)}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <span class="text-danger">Before: ${formatNumber(transfer.to_mg_target_before)}</span><br>
                                        <span class="text-success">After: ${formatNumber(transfer.to_mg_target_after)}</span><br>
                                        <span class="text-muted">Change: ${formatNumber(transfer.to_mg_target_after - transfer.to_mg_target_before)}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <span class="text-danger">Before: ${formatNumber(transfer.from_capacity_kg_before)}</span><br>
                                        <span class="text-success">After: ${formatNumber(transfer.from_capacity_kg_after)}</span><br>
                                        <span class="text-muted">Change: ${formatNumber(transfer.from_capacity_kg_after - transfer.from_capacity_kg_before)}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <span class="text-danger">Before: ${formatNumber(transfer.to_capacity_kg_before)}</span><br>
                                        <span class="text-success">After: ${formatNumber(transfer.to_capacity_kg_after)}</span><br>
                                        <span class="text-muted">Change: ${formatNumber(transfer.to_capacity_kg_after - transfer.to_capacity_kg_before)}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <span class="text-danger">Before: ${formatNumber(transfer.from_capacity_pieces_before)}</span><br>
                                        <span class="text-success">After: ${formatNumber(transfer.from_capacity_pieces_after)}</span><br>
                                        <span class="text-muted">Change: ${formatNumber(transfer.from_capacity_pieces_after - transfer.from_capacity_pieces_before)}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <span class="text-danger">Before: ${formatNumber(transfer.to_capacity_pieces_before)}</span><br>
                                        <span class="text-success">After: ${formatNumber(transfer.to_capacity_pieces_after)}</span><br>
                                        <span class="text-muted">Change: ${formatNumber(transfer.to_capacity_pieces_after - transfer.to_capacity_pieces_before)}</span>
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
                                '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
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

            $(document).ready(function() {
                let machineDistributionChart;

                // Format number with commas
                function formatNumber(num) {
                    if (num === null || num === undefined || isNaN(num)) return '0';
                    return new Intl.NumberFormat('en-US').format(Math.round(num));
                }

                // Format decimal numbers
                function formatDecimal(num) {
                    if (num === null || num === undefined || isNaN(num)) return '0.00';
                    return new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(num);
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

                // Load dashboard data
                function loadDashboardData() {
                    const formData = new FormData(document.getElementById('dashboardFilter'));

                    // Show loading state
                    $('#unitDetailsBody').html(`
            <tr>
                <td colspan="21" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Loading dashboard data...</p>
                </td>
            </tr>
        `);

                    $.ajax({
                        url: "{{ route('admin.machineTrans.dashboard.data') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log('Dashboard response:', response);

                            if (!response.success) {
                                $.toast({
                                    heading: 'Error',
                                    text: response.error || 'Failed to load data',
                                    position: 'top-center',
                                    icon: 'error'
                                });
                                return;
                            }

                            // Update all components
                            updateSummaryCards(response.summary);
                            updateUnitDetails(response.unitDetails, response.dateTotals, response
                                .grandTotals);
                            updateRecentTransfers(response.recentTransfers);
                            updateTransferHistory(response.transferHistory);
                            updateCharts(response.chartData);

                            // Update timestamp
                            const now = new Date();
                            $('#lastUpdated').html(`
                    <i class="fa-solid fa-clock me-1"></i>
                    Last Updated: ${now.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}
                `);

                            $.toast({
                                heading: 'Success',
                                text: 'Dashboard data loaded successfully',
                                position: 'top-center',
                                icon: 'success',
                                hideAfter: 2000
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                            $.toast({
                                heading: 'Error',
                                text: 'Failed to load dashboard data: ' + error,
                                position: 'top-center',
                                icon: 'error'
                            });

                            $('#unitDetailsBody').html(`
                    <tr>
                        <td colspan="21" class="text-center text-danger py-5">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                            Failed to load data. Please try again.
                        </td>
                    </tr>
                `);
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

                // Update unit details table
                function updateUnitDetails(units, dateTotals, grandTotals) {
                    console.log('Updating unit details:', {
                        units,
                        dateTotals,
                        grandTotals
                    });

                    let html = '';

                    if (units && units.length > 0) {
                        const groupedData = groupUnitDetailsByDate(units);
                        const dates = Object.keys(groupedData).sort().reverse();

                        dates.forEach(date => {
                            const dateUnits = groupedData[date];
                            const firstUnit = dateUnits[0];
                            const dateTotal = dateTotals && dateTotals[date] ? dateTotals[date] : {};

                            // Add date header row
                            html += `
                <tr class="table-primary">
                    <td colspan="21" class="text-center fw-bold">
                        <i class="fa-solid fa-calendar-day me-2"></i>${firstUnit.display_date}
                    </td>
                </tr>
                `;

                            // Add data rows for this date
                            dateUnits.forEach(unit => {
                                const netChange = unit.transfersIn - unit.transfersOut;
                                const statusClass = netChange > 0 ? 'success' : (netChange < 0 ?
                                    'danger' : 'secondary');
                                const statusText = netChange > 0 ? 'Gain' : (netChange < 0 ? 'Loss' :
                                    'Stable');

                                const machineChangeClass = unit.currentMachineCount > unit
                                    .baseMachineCount ?
                                    'text-success fw-bold' :
                                    (unit.currentMachineCount < unit.baseMachineCount ?
                                        'text-danger fw-bold' : 'text-muted');

                                html += `
                    <tr>
                        <td class="text-nowrap fw-bold text-primary">${unit.display_date}</td>
                        <td><strong>${unit.unitName}</strong></td>
                        <td class="text-muted">${formatNumber(unit.baseMachineCount)}</td>
                        <td class="${machineChangeClass}">${formatNumber(unit.currentMachineCount)}</td>
                        <td class="text-muted">${formatNumber(unit.baseMgTarget)}</td>
                        <td class="${machineChangeClass}">${formatNumber(unit.currentMgTarget)}</td>
                        <td class="text-muted">${formatNumber(unit.baseCapacityKg)}</td>
                        <td class="${machineChangeClass}">${formatNumber(unit.currentCapacityKg)}</td>
                        <td class="text-muted">${formatNumber(unit.baseCapacityPieces)}</td>
                        <td class="${machineChangeClass}">${formatNumber(unit.currentCapacityPieces)}</td>
                        <!-- WASH DATA COLUMNS -->
                        <td class="text-success fw-bold text-center">${formatNumber(unit.delivery || 0)}</td>
                        <td class="text-primary fw-bold text-center">${formatNumber(unit.received || 0)}</td>
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

                            // Add DATE TOTAL row
                            const dateNetChange = (dateTotal.transfersIn || 0) - (dateTotal.transfersOut || 0);
                            const dateNetChangeClass = dateNetChange > 0 ? 'success' : (dateNetChange < 0 ?
                                'danger' : 'secondary');

                            html += `
                <tr class="table-warning fw-bold" style="background-color: #fff3cd !important;">
                    <td colspan="2" class="text-dark">
                        <i class="fa-solid fa-calculator me-2"></i>Total (${dateTotal.unit_count || dateUnits.length} units):
                    </td>
                    <!-- Machines -->
                    <td class="text-dark">${formatNumber(dateTotal.baseMachineCount || 0)}</td>
                    <td class="text-dark">${formatNumber(dateTotal.currentMachineCount || 0)}</td>
                    <!-- MG Target -->
                    <td class="text-dark">${formatNumber(dateTotal.baseMgTarget || 0)}</td>
                    <td class="text-dark">${formatNumber(dateTotal.currentMgTarget || 0)}</td>
                    <!-- Capacity KG -->
                    <td class="text-dark">${formatNumber(dateTotal.baseCapacityKg || 0)}</td>
                    <td class="text-dark">${formatNumber(dateTotal.currentCapacityKg || 0)}</td>
                    <!-- Capacity Pieces -->
                    <td class="text-dark">${formatNumber(dateTotal.baseCapacityPieces || 0)}</td>
                    <td class="text-dark">${formatNumber(dateTotal.currentCapacityPieces || 0)}</td>
                    <!-- Wash Production -->
                    <td class="text-dark text-center">${formatNumber(dateTotal.delivery || 0)}</td>
                    <td class="text-dark text-center">${formatNumber(dateTotal.received || 0)}</td>
                    <!-- Per Machine Values (show N/A for totals) -->                
                    <td colspan="8" class="text-center text-dark">
                        <i class="fa-solid fa-layer-group me-1"></i>Datewise Summary
                    </td>
                </tr>
                `;
                        });

                        // Add GRAND TOTAL row (across all dates)
                        if (grandTotals) {
                            const grandNetChange = (grandTotals.transfersIn || 0) - (grandTotals.transfersOut || 0);
                            const grandNetChangeClass = grandNetChange > 0 ? 'success' : (grandNetChange < 0 ?
                                'danger' : 'secondary');

                            html += `
                <tr class="table-success fw-bold" style="background-color: #d1e7dd !important; display: none;">
                    <td colspan="2" class="text-dark">
                        <i class="fa-solid fa-chart-bar me-2"></i>GRAND TOTAL
                    </td>
                    <!-- Machines -->
                    <td class="text-dark">${formatNumber(grandTotals.baseMachineCount || 0)}</td>
                    <td class="text-dark">${formatNumber(grandTotals.currentMachineCount || 0)}</td>
                    <!-- MG Target -->
                    <td class="text-dark">${formatNumber(grandTotals.baseMgTarget || 0)}</td>
                    <td class="text-dark">${formatNumber(grandTotals.currentMgTarget || 0)}</td>
                    <!-- Capacity KG -->
                    <td class="text-dark">${formatNumber(grandTotals.baseCapacityKg || 0)}</td>
                    <td class="text-dark">${formatNumber(grandTotals.currentCapacityKg || 0)}</td>
                    <!-- Capacity Pieces -->
                    <td class="text-dark">${formatNumber(grandTotals.baseCapacityPieces || 0)}</td>
                    <td class="text-dark">${formatNumber(grandTotals.currentCapacityPieces || 0)}</td>
                    <!-- Wash Production -->
                    <td class="text-dark text-center">${formatNumber(grandTotals.delivery || 0)}</td>
                    <td class="text-dark text-center">${formatNumber(grandTotals.received || 0)}</td>
                    <td colspan="8" class="text-center text-dark">
                        <i class="fa-solid fa-trophy me-1"></i>Overall Summary
                    </td>
                </tr>
                `;
                        }
                    } else {
                        html = `
            <tr>
                <td colspan="21" class="text-center text-muted py-4">
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
                    if (transfers && transfers.length > 0) {
                        transfers.forEach(transfer => {
                            html += `
                <div class="border-bottom pb-2 mb-2">
                    <div class="d-flex justify-content-between">
                        <strong>${transfer.from_unit} → ${transfer.to_unit}</strong>
                        <small class="text-muted">${transfer.machine_count} machines</small>
                    </div>
                    <div class="small text-muted">
                        <i class="fa-solid fa-clock me-1"></i>${transfer.hours} hours
                        <span class="ms-2"><i class="fa-solid fa-chart-line me-1"></i>${formatNumber(transfer.production)}</span>
                    </div>
                    <div class="small">${transfer.date}</div>
                </div>
                `;
                        });
                    } else {
                        html =
                            '<p class="text-muted text-center py-3"><i class="fa-solid fa-info-circle me-2"></i>No transfers found</p>';
                    }
                    $('#recentTransfers').html(html);
                }

                // Update transfer history table
                function updateTransferHistory(history) {
                    let html = '';
                    if (history && history.length > 0) {
                        history.forEach(transfer => {
                            html += `
                <tr>
                    <td>${transfer.transfer_date}</td>
                    <td><strong>${transfer.from_unit_name}</strong></td>
                    <td><strong>${transfer.to_unit_name}</strong></td>
                    <td class="text-center"><span class="badge bg-primary">${transfer.machine_count}</span></td>
                    <td class="text-center">${transfer.hours}</td>
                    <td>
                        <div class="small">
                            <span class="text-danger">Before: ${formatNumber(transfer.from_mg_target_before)}</span><br>
                            <span class="text-success">After: ${formatNumber(transfer.from_mg_target_after)}</span>
                        </div>
                    </td>
                    <td>
                        <div class="small">
                            <span class="text-danger">Before: ${formatNumber(transfer.to_mg_target_before)}</span><br>
                            <span class="text-success">After: ${formatNumber(transfer.to_mg_target_after)}</span>
                        </div>
                    </td>
                    <td>
                        <div class="small">
                            <span class="text-danger">Before: ${formatNumber(transfer.from_capacity_kg_before)}</span><br>
                            <span class="text-success">After: ${formatNumber(transfer.from_capacity_kg_after)}</span>
                        </div>
                    </td>
                    <td>
                        <div class="small">
                            <span class="text-danger">Before: ${formatNumber(transfer.to_capacity_kg_before)}</span><br>
                            <span class="text-success">After: ${formatNumber(transfer.to_capacity_kg_after)}</span>
                        </div>
                    </td>
                    <td>
                        <div class="small">
                            <span class="text-danger">Before: ${formatNumber(transfer.from_capacity_pieces_before)}</span><br>
                            <span class="text-success">After: ${formatNumber(transfer.from_capacity_pieces_after)}</span>
                        </div>
                    </td>
                    <td>
                        <div class="small">
                            <span class="text-danger">Before: ${formatNumber(transfer.to_capacity_pieces_before)}</span><br>
                            <span class="text-success">After: ${formatNumber(transfer.to_capacity_pieces_after)}</span>
                        </div>
                    </td>
                    <td><span class="badge bg-success">${transfer.status}</span></td>
                </tr>
                `;
                        });
                    } else {
                        html = `
            <tr>
                <td colspan="13" class="text-center text-muted py-4">
                    <i class="fa-solid fa-info-circle me-2"></i>No transfer history found
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

                    if (!chartData || !chartData.labels || chartData.labels.length === 0) {
                        $('#machineDistributionChart').closest('.card-body').html(`
                <div class="text-center text-muted py-4">
                    <i class="fa-solid fa-chart-pie fa-2x mb-2"></i>
                    <p>No chart data available</p>
                </div>
            `);
                        return;
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
                                    '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
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
                                            const percentage = total > 0 ? Math.round((value / total) *
                                                100) : 0;
                                            return `${label}: ${formatNumber(value)} machines (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // Initialize
                loadDashboardData();

                // Filter form submission
                $(document).on('submit', '#dashboardFilter', function(e) {
                    e.preventDefault();
                    loadDashboardData();
                });

                // Auto-refresh every 5 minutes
                setInterval(function() {
                    loadDashboardData();
                }, 300000);
            });
        </script>

        <style>
            .card {
                box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
                border: 1px solid #e3e6f0;
                transition: all 0.3s;
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
                /* letter-spacing: 0.5px; */
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

            /* Responsive table */
            @media (max-width: 768px) {
                .table-responsive {
                    font-size: 0.8rem;
                }

                .table th,
                .table td {
                    padding: 0.5rem;
                }
            }

            /* Auto-refresh indicator */
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

            .auto-refresh-active {
                animation: pulse 2s infinite;
            }

            /* Update the timestamp style */
            #lastUpdated {
                font-size: 0.8rem;
                transition: color 0.3s ease;
            }

            /* Summation row styling */
            .table-warning {
                background-color: #fff3cd !important;
                border-bottom: 1px solid #ffc107 !important;
            }

            .table-success {
                background-color: #d1e7dd !important;
                border-top: 1px solid #198754 !important;
                border-bottom: 1px solid #198754 !important;
            }

            /* Make total rows stand out */
            .table-warning td,
            .table-success td {
                font-weight: 700 !important;
                font-size: 0.8rem !important;
            }

            /* Add subtle animation to total rows */
            @keyframes highlight {
                0% {
                    background-color: inherit;
                }

                50% {
                    background-color: rgba(255, 193, 7, 0.2);
                }

                100% {
                    background-color: inherit;
                }
            }

            .table-warning,
            .table-success {
                animation: highlight 1s ease-in-out;
            }

            /* Loading spinner */
            .spinner-border {
                width: 3rem;
                height: 3rem;
            }

            /* Toast notifications */
            .jq-toast-wrap {
                z-index: 999999 !important;
            }

            /* Ensure table responsiveness */
            .table-responsive {
                overflow-x: auto;
            }

            /* Fix for long unit names */
            #unitDetailsTable td:nth-child(2) {
                min-width: 50px;
                max-width: 200px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /* Better column alignment */
            #unitDetailsTable td {
                text-align: center;
            }

            #unitDetailsTable td:first-child,
            #unitDetailsTable td:nth-child(2) {
                text-align: left;
            }
        </style>
    @endpush
@endsection
