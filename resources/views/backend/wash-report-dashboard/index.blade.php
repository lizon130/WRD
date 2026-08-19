@extends('backend.layout.app')
@section('title', 'Wash Report Dashboard | ' . Helper::getSettings('application_name') ?? 'Tusuka')
@section('content')
    <div class="container-fluid px-4">
        <!-- Modern Header with Light Theme -->
        <div class="modern-header mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header-title">
                    <h4 class="mb-1 fw-bold gradient-text">Wash Report Dashboard</h4>
                    <p class="text-muted mb-0">Real-time production monitoring & analytics</p>
                </div>
                <div class="header-stats">
                    <div class="d-flex gap-3">
                        <div class="stat-badge">
                            <i class="fa-solid fa-chart-line text-primary"></i>
                            <span>Live Data</span>
                        </div>
                        <div class="stat-badge">
                            <i class="fa-solid fa-clock text-info"></i>
                            <span>Auto Refresh</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Filter Card with Light Theme -->
        <div class="glass-card my-3">
            <div class="card-body p-4">
                <form method="GET" id="filter_form">
                    <div class="row align-items-end g-3">
                        <div class="col-md-4">
                            <label for="from_date" class="form-label fw-semibold mb-2">
                                <i class="fa-regular fa-calendar me-1 text-primary"></i> From Date
                            </label>
                            <input type="date" class="form-control modern-input" name="from_date" id="from_date">
                        </div>
                        <div class="col-md-4">
                            <label for="to_date" class="form-label fw-semibold mb-2">
                                <i class="fa-regular fa-calendar-check me-1 text-primary"></i> To Date
                            </label>
                            <input type="date" class="form-control modern-input" name="to_date" id="to_date">
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" id="filterBtn" class="btn btn-gradient-primary flex-grow-1">
                                    <i class="fa-solid fa-filter me-2"></i> Apply Filter
                                </button>
                                <button type="button" id="resetBtn" class="btn btn-outline-secondary">
                                    <i class="fa-solid fa-rotate-right"></i>
                                </button>
                                <button type="button" id="todayBtn" class="btn btn-outline-info">
                                    <i class="fa-regular fa-calendar"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modern Date Info Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="gradient-card date-info-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 text-white-50">Current Report Period</p>
                                <h4 class="mb-0 fw-bold text-white" id="dateRange">Loading...</h4>
                            </div>
                            <div class="date-icon-wrapper">
                                <i class="fa-regular fa-calendar-alt fa-3x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Data Table Card -->
        <div class="modern-card mb-4">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="header-icon">
                            <i class="fa-solid fa-chart-simple"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Production Dashboard</h5>
                            <p class="mb-0 small text-muted">Unit-wise detailed performance metrics</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-modern-success" id="refreshBtn">
                            <i class="fa-solid fa-rotate me-2"></i> Refresh
                        </button>
                        <button type="button" class="btn btn-modern-danger" id="downloadPdfBtn">
                            <i class="fa-solid fa-file-pdf me-2"></i> Export PDF
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive modern-table" style="padding: 10px !important">
                    <table class="table table-hover mb-0" id="unitDataTable">
                        <thead>
                            <tr>
                                <th class="text-center">Unit</th>
                                <th class="text-center">Used MC</th>
                                <th class="text-center">Used Capacity (kg)</th>
                                <th class="text-center">Sewing Lines</th>
                                <th class="text-center">Direct</th>
                                <th class="text-center">Indirect</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Machine Work Hr</th>
                                <th class="text-center">Received</th>
                                <th class="text-center">Delivery</th>
                                <th class="text-center">Garment (g)</th>
                                <th class="text-center">Forecast Target</th>
                                <th class="text-center">Deviation</th>
                                <th class="text-center">Deviation %</th>
                                <th class="text-center">Rewash %</th>
                                <th class="text-center">In Hand Balance</th>
                                <th class="text-center">First Wash</th>
                                <th class="text-center">Final Wash</th>
                                <th class="text-center">Wash WIP %</th>
                                <th class="text-center">Acid Wash</th>
                                <th class="text-center">Re-Wash</th>
                                <th class="text-center">Rework Dry Proc</th>
                                <th class="text-center">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="text-center"></tbody>
                        <tfoot class="table-footer-modern">
                            <tr>
                                <th class="text-center">Totals:</th>
                                <th class="text-center" id="footerMachines">0</th>
                                <th class="text-center" id="footerCapacity">0 kg</th>
                                <th class="text-center" id="footerSewingLines">-</th>
                                <th class="text-center" id="footerDirect">0</th>
                                <th class="text-center" id="footerIndirect">0</th>
                                <th class="text-center" id="footerTotal">0</th>
                                <th class="text-center" id="footerWorkHours">0.00</th>
                                <th class="text-center" id="footerDelivery">0</th>
                                <th class="text-center" id="footerReceived">0</th>
                                <th class="text-center" id="footerGarment">-</th>
                                <th class="text-center" id="footerForecastTarget">0</th>
                                <th class="text-center" id="footerDeviation">0</th>
                                <th class="text-center" id="footerDeviationPercent">0%</th>
                                <th class="text-center" id="footerRewashPercent">0%</th>
                                <th class="text-center" id="footerInHandBalance">0</th>
                                <th class="text-center" id="footerFirstWash">0</th>
                                <th class="text-center" id="footerFinalWash">0</th>
                                <th class="text-center" id="footerWashRatio">0</th>
                                <th class="text-center" id="footerAcidWash">0</th>
                                <th class="text-center" id="footerReWash">0</th>
                                <th class="text-center" id="footerReworkDryProc">0</th>
                                <th class="text-center" id="footerRemarks">-</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Two Column Section -->
        <div class="row g-4">
            <!-- First Dry Process Table -->
            <div class="col-lg-6">
                <div class="modern-card h-100">
                    <div class="card-header-modern gradient-primary">
                        <div class="d-flex align-items-center gap-3">
                            <div class="header-icon">
                                <i class="fa-solid fa-fire"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-white">1st Dry Process</h5>
                                <p class="mb-0 small text-white-50">Latest performance data</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive modern-table" style="padding: 10px !important">
                            <table class="table table-hover mb-0" id="firstDryProcessTable">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Plant</th>
                                        <th colspan="2">Whisker</th>
                                        <th colspan="2">Hand Brush</th>
                                        <th colspan="4">1st Dry Final</th>
                                        <th rowspan="2">Deviation</th>
                                        <th rowspan="2">Defect Qty</th>
                                        <th rowspan="2">Used Manpower</th>
                                        <th rowspan="2">Working Hours</th>
                                        <th rowspan="2">SMV</th>
                                        <th rowspan="2">Remarks</th>
                                    </tr>
                                    <tr>
                                        <th>Capacity</th>
                                        <th>Prod.</th>
                                        <th>Capacity</th>
                                        <th>Prod.</th>
                                        <th>Capacity</th>
                                        <th>Prod.</th>
                                        <th>Receive</th>
                                        <th>Delivery</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center"></tbody>
                                <tfoot class="table-footer-modern">
                                    <tr>
                                        <th class="text-center">Totals/Avg:</th>
                                        <th class="text-center" id="footerWhiskerTarget">0</th>
                                        <th class="text-center" id="footerWhiskerProd">0</th>
                                        <th class="text-center" id="footerHandbrushTarget">0</th>
                                        <th class="text-center" id="footerHandbrushProd">0</th>
                                        <th class="text-center" id="footerFirstDryTarget">0</th>
                                        <th class="text-center" id="footerFirstDryProd">0</th>
                                        <th class="text-center" id="footerFirstDryReceive">0</th>
                                        <th class="text-center" id="footerFirstDryDelivery">0</th>
                                        <th class="text-center" id="footerFirstDryDeviation">0</th>
                                        <th class="text-center" id="footerFirstDryDefect">0</th>
                                        <th class="text-center" id="footerFirstManpower">0</th>
                                        <th class="text-center" id="footerFirstWorkingHr">0.00</th>
                                        <th class="text-center" id="footerFirstSmv">0.00</th>
                                        <th class="text-center" id="footerFirstRemarks">-</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Dry Process Table -->
            <div class="col-lg-6">
                <div class="modern-card h-100">
                    <div class="card-header-modern gradient-success">
                        <div class="d-flex align-items-center gap-3">
                            <div class="header-icon">
                                <i class="fa-solid fa-droplet"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-white">2nd Dry Process</h5>
                                <p class="mb-0 small text-white-50">Latest performance data</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive modern-table" style="padding: 10px !important">
                            <table class="table table-hover mb-0" id="secondDryProcessTable">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Plant</th>
                                        <th colspan="2">Laser</th>
                                        <th colspan="2">PP Spray</th>
                                        <th colspan="4">2nd Dry Final</th>
                                        <th rowspan="2">Deviation</th>
                                        <th rowspan="2">Defect Qty</th>
                                        <th rowspan="2">Used Manpower</th>
                                        <th rowspan="2">Working Hour</th>
                                        <th rowspan="2">SMV</th>
                                        <th rowspan="2">Remarks</th>
                                    </tr>
                                    <tr>
                                        <th>Capacity</th>
                                        <th>Prod.</th>
                                        <th>Capacity</th>
                                        <th>Prod.</th>
                                        <th>Capacity</th>
                                        <th>Prod.</th>
                                        <th>Receive</th>
                                        <th>Delivery</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center"></tbody>
                                <tfoot class="table-footer-modern">
                                    <tr>
                                        <th class="text-center">Total/Avg:</th>
                                        <th class="text-center" id="footerLaserTarget">0</th>
                                        <th class="text-center" id="footerLaserProd">0</th>
                                        <th class="text-center" id="footerPpTarget">0</th>
                                        <th class="text-center" id="footerPpProd">0</th>
                                        <th class="text-center" id="footerSecondDryTarget">0</th>
                                        <th class="text-center" id="footerSecondDryProd">0</th>
                                        <th class="text-center" id="footerSecondDryReceive">0</th>
                                        <th class="text-center" id="footerSecondDryDelivery">0</th>
                                        <th class="text-center" id="footerSecondDryDeviation">0</th>
                                        <th class="text-center" id="footerSecondDryDefect">0</th>
                                        <th class="text-center" id="footerSecondManpower">0</th>
                                        <th class="text-center" id="footerSecondWorkingHr">0.00</th>
                                        <th class="text-center" id="footerSecondSmv">0.00</th>
                                        <th class="text-center" id="footerSecondRemarks">-</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transfer and Dryer Section -->
        <div class="row g-4 mt-2">
            <!-- Transfer Data Table -->
            <div class="col-lg-6">
                <div class="modern-card h-100">
                    <div class="card-header-modern gradient-info">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="header-icon">
                                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold text-white">Machine Transfer Analytics</h5>
                                    <p class="mb-0 small text-white-50">Real-time transfer status</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="badge-modern success" id="verifiedTransfersBadge">0</span>
                                <span class="badge-modern warning" id="pendingTransfersBadge">0</span>
                                <span class="badge-modern danger" id="rejectedTransfersBadge">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive modern-table" style="padding: 10px !important">
                            <table class="table table-hover mb-0" id="transferDataTable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Unit</th>
                                        <th colspan="2">Machine</th>
                                        <th rowspan="2">Transfer In</th>
                                        <th rowspan="2">Transfer Out</th>
                                        <th colspan="2">Target</th>
                                        <th colspan="2">M Capacity(pcs)</th>
                                        <th colspan="2">M Capacity(kg)</th>
                                    </tr>
                                    <tr>
                                        <th>Exist M</th>
                                        <th>Used M</th>
                                        <th>Exist M</th>
                                        <th>Used M</th>
                                        <th>Exist M</th>
                                        <th>Used M</th>
                                        <th>Exist M</th>
                                        <th>Used M</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center"></tbody>
                                <tfoot class="table-footer-modern">
                                    <tr>
                                        <th>Totals/Avg:</th>
                                        <th id="footerExistingMc">0</th>
                                        <th id="footerUsedMc">0</th>
                                        <th id="footerTransferIn">-</th>
                                        <th id="footerTransferOut">-</th>
                                        <th id="footerBaseMg">0</th>
                                        <th id="footerCurrentMg">0</th>
                                        <th id="footerBasePcs">0</th>
                                        <th id="footerCurrentPcs">0</th>
                                        <th id="footerBaseKg">0</th>
                                        <th id="footerCurrentKg">0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dryer Data Table -->
            <div class="col-lg-6">
                <div class="modern-card h-100">
                    <div class="card-header-modern gradient-warning">
                        <div class="d-flex align-items-center gap-3">
                            <div class="header-icon">
                                <i class="fa-solid fa-temperature-high"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-dark">Dryer Performance</h5>
                                <p class="mb-0 small text-dark-50">Drying process metrics</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive modern-table" style="padding: 10px !important">
                            <table class="table table-hover mb-0" id="dryerDataTable">
                                <thead>
                                    <tr>
                                        <th>Unit</th>
                                        <th># Dryer</th>
                                        <th>1st Wash</th>
                                        <th>Cold</th>
                                        <th>Meas Corr</th>
                                        <th>Final Wash</th>
                                        <th>Deviation</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center"></tbody>
                                <tfoot class="table-footer-modern">
                                    <tr>
                                        <th>Totals:</th>
                                        <th class="text-center" id="footerNumDryer">0</th>
                                        <th id="footerFirstWashDryer">0.0</th>
                                        <th id="footerColdDryer">0.0</th>
                                        <th id="footerMeasCorrection">0.0</th>
                                        <th id="footerFinalWashDryer">0.0</th>
                                        <th id="footerDeviation">0.0</th>
                                        <th id="footerTotalDryer">0.0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Machine Performance Chart -->
        <div class="row mt-4 mb-4">
            <div class="col-12">
                <div class="modern-card">
                    <div class="card-header-modern gradient-dark">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="header-icon">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold text-white">Machine Performance Analytics</h5>
                                    <p class="mb-0 small text-white-50">Uptime, Idletime & Downtime analysis</p>
                                </div>
                            </div>
                            <div class="performance-stats">
                                <div class="d-flex gap-3">
                                    <div class="stat-chip">
                                        <i class="fa-solid fa-circle text-success"></i>
                                        <span class="text-white">Uptime: <span id="uptimeValueDetail">0</span>%</span>
                                    </div>
                                    <div class="stat-chip">
                                        <i class="fa-solid fa-circle text-warning"></i>
                                        <span class="text-white">Idletime: <span id="idletimeValueDetail">0</span>%</span>
                                    </div>
                                    <div class="stat-chip">
                                        <i class="fa-solid fa-circle text-danger"></i>
                                        <span class="text-white">Downtime: <span id="downtimeValueDetail">0</span>%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-10">
                                <div style="height: 450px;">
                                    <canvas id="cappChart"></canvas>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="info-panel">
                                    <div class="info-panel-header">
                                        <i class="fa-regular fa-calendar text-primary"></i>
                                        <h6 class="mb-0">Period Info</h6>
                                    </div>
                                    <div class="info-panel-body">
                                        <p class="mb-2"><strong>From:</strong> <span id="cappFrom" class="small"></span></p>
                                        <p class="mb-0"><strong>To:</strong> <span id="cappTo" class="small"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Modals (unchanged) -->
    <div class="modal fade" id="remarkModal" tabindex="-1" aria-labelledby="remarkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modern-modal">
                <div class="modal-header gradient-primary text-white border-0">
                    <h5 class="modal-title" id="remarkModalLabel">
                        <i class="fa-solid fa-pen-to-square me-2"></i>
                        Add/Edit Remark
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="remarkForm">
                        <input type="hidden" id="remark_unit" name="unit">
                        <input type="hidden" id="remark_date" name="date">
                        <input type="hidden" id="remark_id" name="id">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Unit</label>
                            <input type="text" class="form-control modern-input bg-light" id="unit_display" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Date</label>
                            <input type="text" class="form-control modern-input bg-light" id="date_display" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Remark</label>
                            <textarea class="form-control modern-input" id="remark_text" name="remark" rows="4" placeholder="Enter your remark here..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-gradient-primary" id="saveRemarkBtn">Save Remark</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Remarks History Modal -->
    <div class="modal fade" id="remarksHistoryModal" tabindex="-1" aria-labelledby="remarksHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modern-modal">
                <div class="modal-header gradient-info text-white border-0">
                    <h5 class="modal-title" id="remarksHistoryModalLabel">
                        <i class="fa-solid fa-clock-rotate-left me-2"></i>
                        Remarks History
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <h6 id="history_unit_name" class="fw-bold"></h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="remarksHistoryTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="remarksHistoryBody">
                                <tr>
                                    <td colspan="3" class="text-center">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Dry Process Remark Modal -->
    <div class="modal fade" id="dryRemarkModal" tabindex="-1" aria-labelledby="dryRemarkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modern-modal">
                <div class="modal-header gradient-primary text-white border-0">
                    <h5 class="modal-title" id="dryRemarkModalLabel">
                        <i class="fa-solid fa-pen-to-square me-2"></i>
                        Add/Edit Dry Process Remark
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="dryRemarkForm">
                        <input type="hidden" id="dry_remark_plant" name="plant">
                        <input type="hidden" id="dry_remark_date" name="date">
                        <input type="hidden" id="dry_remark_id" name="id">
                        <input type="hidden" id="dry_remark_process_type" name="process_type">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Plant / Process</label>
                            <input type="text" class="form-control modern-input bg-light" id="dry_unit_display" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Date</label>
                            <input type="text" class="form-control modern-input bg-light" id="dry_date_display" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Remark</label>
                            <textarea class="form-control modern-input" id="dry_remark_text" name="remark" rows="4" placeholder="Enter your remark here..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-gradient-primary" id="saveDryRemarkBtn">Save Remark</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Dry Process Remarks History Modal -->
    <div class="modal fade" id="dryRemarksHistoryModal" tabindex="-1" aria-labelledby="dryRemarksHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content modern-modal">
                <div class="modal-header gradient-info text-white border-0">
                    <h5 class="modal-title" id="dryRemarksHistoryModalLabel">
                        <i class="fa-solid fa-clock-rotate-left me-2"></i>
                        Dry Process Remarks History
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <h6 id="dry_history_unit_name" class="fw-bold"></h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Remark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="dry_remarks_history_body">
                                <tr>
                                    <td colspan="3" class="text-center">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Light Grey Theme CSS */
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        /* Modern CSS Variables for Light Theme */
        :root {
            --light-bg-primary: #ffffff;
            --light-bg-secondary: #f8f9fa;
            --light-bg-tertiary: #f1f3f5;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #c92a2a 100%);
            --warning-gradient: linear-gradient(135deg, #ffd93d 0%, #f59f00 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --dark-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.05);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.08);
            --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1);
        }

        /* Modern Header */
        .modern-header {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .gradient-text {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Glass Card Effect */
        .glass-card {
            background: white;
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: var(--shadow-lg);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }

        /* Modern Inputs */
        .modern-input {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.625rem 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .modern-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        /* Gradient Buttons */
        .btn-gradient-primary {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.625rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-gradient-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
        }

        .btn-modern-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-modern-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #c92a2a 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-modern-success:hover, .btn-modern-danger:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }

        /* Gradient Cards */
        .gradient-card {
            background: var(--primary-gradient);
            border-radius: 20px;
            color: white;
            box-shadow: var(--shadow-lg);
        }

        .date-info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Modern Cards */
        .modern-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .card-header-modern {
            background: white;
            padding: 1rem 1.5rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .card-header-modern.gradient-primary {
            background: var(--primary-gradient);
        }

        .card-header-modern.gradient-success {
            background: var(--success-gradient);
        }

        .card-header-modern.gradient-info {
            background: var(--info-gradient);
        }

        .card-header-modern.gradient-warning {
            background: var(--warning-gradient);
        }

        .card-header-modern.gradient-dark {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
        }

        .header-icon {
            width: 45px;
            height: 45px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #667eea;
        }

        /* Modern Table */
        .modern-table {
            border-radius: 0;
        }

        .modern-table thead th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem;
            border-bottom: 2px solid #e2e8f0;
            color: #2d3748;
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }

        .modern-table tbody td {
            padding: 0.875rem;
            vertical-align: middle;
            color: #4a5568;
        }

        .table-footer-modern {
            background: #f8f9fa;
            font-weight: 600;
            border-top: 2px solid #e2e8f0;
        }

        .table-footer-modern th {
            padding: 1rem;
            color: #2d3748;
        }

        /* Modern Badges */
        .badge-modern {
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .badge-modern.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .badge-modern.warning {
            background: linear-gradient(135deg, #ffd93d 0%, #f59f00 100%);
            color: white;
        }

        .badge-modern.danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #c92a2a 100%);
            color: white;
        }

        /* Performance Stats */
        .performance-stats {
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 0.5rem 1rem;
        }

        .stat-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .info-panel {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .info-panel-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        /* Modern Modal */
        .modern-modal {
            border-radius: 20px;
            overflow: hidden;
            border: none;
            box-shadow: var(--shadow-xl);
        }

        /* Stat Badge */
        .stat-badge {
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid #e2e8f0;
        }

        /* Date Icon Wrapper */
        .date-icon-wrapper {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .modern-header {
                padding: 1rem;
            }
            
            .glass-card .row {
                flex-direction: column;
            }
            
            .performance-stats {
                margin-top: 1rem;
                flex-wrap: wrap;
            }
            
            .btn-gradient-primary,
            .btn-modern-success,
            .btn-modern-danger {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
        }

        /* Animation Keyframes */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modern-card {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        /* Smooth Hover Effects */
        .btn, .modern-card, .stat-badge {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* DataTables Custom Styling */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 12px;
            border-color: #e2e8f0;
        }
    </style>
@endsection


@push('footer')
    <script type="text/javascript">
        // ============================================
        // 1. GLOBAL VARIABLES & FUNCTIONS
        // ============================================

        var canEditRemarks = {{ Helper::hasRight('remarks.edit') ? 'true' : 'false' }};

        function formatDate(dateString) {
            if (!dateString) return '';
            var date = new Date(dateString);
            var day = ('0' + date.getDate()).slice(-2);
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var year = date.getFullYear();
            return day + '-' + month + '-' + year;
        }

        var today = new Date().toISOString().split('T')[0];

        $(document).ready(function() {
            // Set default dates
            $('#from_date').val(today);
            $('#to_date').val(today);
            updateDateRange();

            // Initialize Main DataTable
            var mainTable = $('#unitDataTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.wash-report-dashboard.get-data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    },
                    dataSrc: function(json) {
                        if (json.date_range) {
                            $('#dateRange').text(json.date_range);
                        }
                        return json.data;
                    }
                },
                aLengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                iDisplayLength: 10,
                order: [
                    [0, 'asc']
                ],
                columns: [{
                        data: 'unit',
                        name: 'unit'
                    },
                    {
                        data: 'used_mc',
                        name: 'used_mc'
                    },
                    {
                        data: 'used_capacity_kg',
                        name: 'used_capacity_kg',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return Math.round(data).toString().replace(/\B(?=(\d{3})+(?!\d))/g,
                                    ",");
                            }
                            return data;
                        }
                    },
                    {
                        data: 'sewing_lines',
                        name: 'sewing_lines'
                    },
                    {
                        data: 'direct',
                        name: 'direct'
                    },
                    {
                        data: 'indirect',
                        name: 'indirect'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'work_hours',
                        name: 'work_hours'
                    },
                    {
                        data: 'delivery',
                        name: 'delivery'
                    },
                    {
                        data: 'received',
                        name: 'received'
                    },
                    {
                        data: 'garment',
                        name: 'garment'
                    },
                    {
                        data: 'forecast_target',
                        name: 'forecast_target'
                    },
                    {
                        data: 'deviation',
                        name: 'deviation'
                    },
                    {
                        data: 'deviation_percent',
                        name: 'deviation_percent'
                    },
                    {
                        data: 'rewash_percent',
                        name: 'rewash_percent'
                    },
                    {
                        data: 'in_hand_balance',
                        name: 'in_hand_balance'
                    },
                    {
                        data: 'first_wash_qty',
                        name: 'first_wash_qty'
                    },
                    {
                        data: 'final_wash_qty',
                        name: 'final_wash_qty'
                    },
                    {
                        data: 'wash_ratio',
                        name: 'wash_ratio'
                    },
                    {
                        data: 'acid_wash_qty',
                        name: 'acid_wash_qty'
                    },
                    {
                        data: 'rewash_qty',
                        name: 'rewash_qty'
                    },
                    {
                        data: 'rework_dry_proc',
                        name: 'rework_dry_proc'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                var remarks = data || '-';
                                var unit = row.unit;
                                var remarkId = 'remark_' + unit.replace(/[^a-zA-Z0-9]/g, '_');
                                var buttonHtml = '';
                                if (canEditRemarks) {
                                    buttonHtml =
                                        '<button type="button" class="btn btn-sm btn-outline-primary remark-toggle-btn" ' +
                                        'data-unit="' + unit + '" data-date="' + today + '" ' +
                                        'data-remark="' + remarks.replace(/"/g, '&quot;') +
                                        '" data-target="' + remarkId + '">' +
                                        '<i class="fa-solid fa-pen"></i></button>';
                                } else {
                                    buttonHtml =
                                        '<button type="button" class="btn btn-sm btn-outline-secondary" disabled title="No permission">' +
                                        '<i class="fa-solid fa-lock"></i></button>';
                                }
                                return '<div class="d-flex align-items-center justify-content-center">' +
                                    '<span id="' + remarkId +
                                    '_text" class="remark-text me-2" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' +
                                    remarks + '">' + remarks + '</span>' +
                                    buttonHtml + '</div>';
                            }
                            return data;
                        }
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var totalUsedMc = 0,
                        totalUsedCapacity = 0,
                        totalDirect = 0,
                        totalIndirect = 0;
                    var totalTotal = 0,
                        totalWorkHours = 0,
                        totalReceived = 0,
                        totalDelivery = 0;
                    var totalFirstWash = 0,
                        totalAcidWash = 0,
                        totalFinalWash = 0,
                        totalReWash = 0;
                    var totalReworkDryProc = 0,
                        totalForecastTarget = 0,
                        totalDeviation = 0;
                    var totalInHandBalance = 0,
                        totalRewashQty = 0,
                        totalReceivedForRewash = 0;
                    var totalFirstWashForRatio = 0,
                        totalFinalWashForRatio = 0;

                    function parseNumber(value) {
                        if (value === null || value === undefined || value === '') return 0;
                        if (typeof value === 'string') {
                            var textValue = value.replace(/<[^>]*>/g, '').replace(/,/g, '').replace(
                                /%/g, '');
                            var num = parseFloat(textValue);
                            return isNaN(num) ? 0 : num;
                        }
                        var num = parseFloat(value);
                        return isNaN(num) ? 0 : num;
                    }

                    function formatNumber(num) {
                        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }

                    var pageData = api.rows({
                        page: 'current'
                    }).data();
                    for (var i = 0; i < pageData.length; i++) {
                        var row = pageData[i];
                        totalUsedMc += parseNumber(row.used_mc);
                        totalUsedCapacity += parseNumber(row.used_capacity_kg);
                        totalDirect += parseNumber(row.direct);
                        totalIndirect += parseNumber(row.indirect);
                        totalTotal += parseNumber(row.total);
                        totalWorkHours += parseNumber(row.work_hours);
                        totalReceived += parseNumber(row.received);
                        totalDelivery += parseNumber(row.delivery);
                        totalFirstWash += parseNumber(row.first_wash_qty);
                        totalAcidWash += parseNumber(row.acid_wash_qty);
                        totalFinalWash += parseNumber(row.final_wash_qty);
                        totalReWash += parseNumber(row.rewash_qty);
                        totalReworkDryProc += parseNumber(row.rework_dry_proc);
                        totalForecastTarget += parseNumber(row.forecast_target);
                        totalDeviation += parseNumber(row.deviation);
                        totalInHandBalance += parseNumber(row.in_hand_balance);
                        totalRewashQty += parseNumber(row.rewash_qty);
                        totalReceivedForRewash += parseNumber(row.received);
                        totalFirstWashForRatio += parseNumber(row.first_wash_qty);
                        totalFinalWashForRatio += parseNumber(row.final_wash_qty);
                    }

                    var weightedWashRatio = totalFirstWashForRatio > 0 ? (totalFinalWashForRatio /
                        totalFirstWashForRatio) * 100 : 0;
                    var weightedRewashPercent = totalReceivedForRewash > 0 ? (totalRewashQty /
                        totalReceivedForRewash) * 100 : 0;
                    var weightedDeviationPercent = totalForecastTarget > 0 ? (totalDeviation /
                        totalForecastTarget) * 100 : 0;

                    $('#footerMachines').text(formatNumber(Math.round(totalUsedMc)));
                    $('#footerCapacity').text(formatNumber(Math.round(totalUsedCapacity)) + ' kg');
                    $('#footerDirect').text(formatNumber(Math.round(totalDirect)));
                    $('#footerIndirect').text(formatNumber(Math.round(totalIndirect)));
                    $('#footerTotal').text(formatNumber(Math.round(totalTotal)));
                    $('#footerWorkHours').text(totalWorkHours.toFixed(2));
                    $('#footerReceived').text(formatNumber(Math.round(totalReceived)));
                    $('#footerDelivery').text(formatNumber(Math.round(totalDelivery)));
                    $('#footerForecastTarget').text(formatNumber(Math.round(totalForecastTarget)));
                    $('#footerDeviation').text(formatNumber(Math.round(
                    totalDeviation))); // Remove Math.abs() here too
                    $('#footerDeviationPercent').text(Math.abs(weightedDeviationPercent).toFixed(2) +
                        '%');
                    $('#footerWashRatio').text(weightedWashRatio.toFixed(2) + '%');
                    $('#footerRewashPercent').text(weightedRewashPercent.toFixed(2) + '%');
                    $('#footerInHandBalance').text(formatNumber(Math.round(totalInHandBalance)));
                    $('#footerFirstWash').text(formatNumber(Math.round(totalFirstWash)));
                    $('#footerAcidWash').text(formatNumber(Math.round(totalAcidWash)));
                    $('#footerFinalWash').text(formatNumber(Math.round(totalFinalWash)));
                    $('#footerReWash').text(formatNumber(Math.round(totalReWash)));
                    $('#footerReworkDryProc').text(formatNumber(Math.round(totalReworkDryProc)));
                }
            });

            // Initialize First Dry Process DataTable
            var firstDryTable = $('#firstDryProcessTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.wash-report-dashboard.first-dry-process') }}",
                    type: 'GET',
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
                paging: true,
                pageLength: 10,
                searching: false,
                info: true,
                ordering: false,
                columns: [{
                        data: 'plant',
                        name: 'plant'
                    },
                    {
                        data: 'whisker_target',
                        name: 'whisker_target'
                    },
                    {
                        data: 'whisker_production',
                        name: 'whisker_production'
                    },
                    {
                        data: 'handbrush_target',
                        name: 'handbrush_target'
                    },
                    {
                        data: 'handbrush_production',
                        name: 'handbrush_production'
                    },
                    {
                        data: 'firstdryfinal_target',
                        name: 'firstdryfinal_target'
                    },
                    {
                        data: 'firstdryfinal_production',
                        name: 'firstdryfinal_production'
                    },
                    {
                        data: 'firstdryfinal_receive',
                        name: 'firstdryfinal_receive'
                    },
                    {
                        data: 'firstdryfinal_delivery',
                        name: 'firstdryfinal_delivery'
                    },
                    {
                        data: 'deviation',
                        name: 'deviation'
                    },
                    {
                        data: 'defect_qty',
                        name: 'defect_qty'
                    },
                    {
                        data: 'manPower',
                        name: 'manPower'
                    },
                    {
                        data: 'workingHr',
                        name: 'workingHr'
                    },
                    {
                        data: 'smv',
                        name: 'smv'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    }
                ],
                columnDefs: [{
                    targets: 14,
                    render: function(data, type, row) {
                        if (type === 'display') {
                            var remarks = data || '-';
                            var plant = row.plant;
                            var remarkId = 'dry_remark_' + plant.replace(/[^a-zA-Z0-9]/g, '_');
                            var words = remarks.split(' ');
                            var truncatedRemarks = words.length > 20 ? words.slice(0, 20).join(
                                ' ') + '...' : remarks;
                            var buttonHtml = '';
                            if (canEditRemarks) {
                                buttonHtml =
                                    '<button type="button" class="btn btn-sm btn-outline-primary dry-process-remark-btn" ' +
                                    'data-plant="' + plant + '" data-date="' + today + '" ' +
                                    'data-remark="' + remarks.replace(/"/g, '&quot;') +
                                    '" data-process-type="first_dry">' +
                                    '<i class="fa-solid fa-pen"></i></button>';
                            } else {
                                buttonHtml =
                                    '<button type="button" class="btn btn-sm btn-outline-secondary" disabled><i class="fa-solid fa-lock"></i></button>';
                            }
                            if (remarks.includes(' | ')) {
                                var mainRemark = remarks.split(' | ')[0];
                                return '<div class="d-flex align-items-center justify-content-center">' +
                                    '<span class="remark-text dry-process-remark-text me-2" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' +
                                    remarks.replace(/"/g, '&quot;') + '" data-plant="' + plant +
                                    '" data-process-type="first_dry">' + mainRemark +
                                    ' <i class="fa-solid fa-chevron-down" style="font-size: 10px;"></i></span>' +
                                    buttonHtml + '</div>';
                            } else {
                                return '<div class="d-flex align-items-center justify-content-center">' +
                                    '<span class="remark-text dry-process-remark-text me-2" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' +
                                    remarks.replace(/"/g, '&quot;') + '" data-plant="' + plant +
                                    '" data-process-type="first_dry">' + truncatedRemarks +
                                    '</span>' +
                                    buttonHtml + '</div>';
                            }
                        }
                        return data;
                    }
                }],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var totalWhiskerTarget = 0,
                        totalWhiskerProd = 0,
                        totalHandbrushTarget = 0;
                    var totalHandbrushProd = 0,
                        totalFirstDryTarget = 0,
                        totalFirstDryProd = 0;
                    var totalFirstDryReceive = 0,
                        totalFirstDryDelivery = 0,
                        totalDeviation = 0;
                    var totalDefectQty = 0,
                        totalManpower = 0;

                    function parseNumber(value) {
                        if (value === null || value === undefined || value === '') return 0;
                        if (typeof value === 'number') return value;
                        var strValue = String(value).replace(/<[^>]*>/g, '').replace(/,/g, '');
                        var num = parseFloat(strValue);
                        return isNaN(num) ? 0 : num;
                    }

                    function formatNumber(num) {
                        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }

                    var pageData = api.rows({
                        page: 'current'
                    }).data();
                    for (var i = 0; i < pageData.length; i++) {
                        var row = pageData[i];
                        totalWhiskerTarget += parseNumber(row.whisker_target);
                        totalWhiskerProd += parseNumber(row.whisker_production);
                        totalHandbrushTarget += parseNumber(row.handbrush_target);
                        totalHandbrushProd += parseNumber(row.handbrush_production);
                        totalFirstDryReceive += parseNumber(row.firstdryfinal_receive);
                        totalFirstDryTarget += parseNumber(row.firstdryfinal_target);
                        totalFirstDryProd += parseNumber(row.firstdryfinal_production);
                        totalFirstDryDelivery += parseNumber(row.firstdryfinal_delivery);
                        totalDeviation += parseNumber(row.firstdryfinal_deviation);
                        totalDefectQty += parseNumber(row.total_defect_qty);
                        totalManpower += parseNumber(row.manPower);
                    }

                    $('#footerWhiskerTarget').text(formatNumber(Math.round(totalWhiskerTarget)));
                    $('#footerWhiskerProd').text(formatNumber(Math.round(totalWhiskerProd)));
                    $('#footerHandbrushTarget').text(formatNumber(Math.round(totalHandbrushTarget)));
                    $('#footerHandbrushProd').text(formatNumber(Math.round(totalHandbrushProd)));
                    $('#footerFirstDryTarget').text(formatNumber(Math.round(totalFirstDryTarget)));
                    $('#footerFirstDryProd').text(formatNumber(Math.round(totalFirstDryProd)));
                    $('#footerFirstDryReceive').text(formatNumber(Math.round(totalFirstDryReceive)));
                    $('#footerFirstDryDelivery').text(formatNumber(Math.round(totalFirstDryDelivery)));
                    $('#footerFirstDryDeviation').text(formatNumber(Math.round(totalDeviation)));
                    $('#footerFirstDryDefect').text(formatNumber(Math.round(totalDefectQty)));
                    $('#footerFirstManpower').text(formatNumber(Math.round(totalManpower)));
                }
            });

            // Initialize Second Dry Process DataTable
            var secondDryTable = $('#secondDryProcessTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.wash-report-dashboard.second-dry-process') }}",
                    type: 'GET',
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
                paging: true,
                pageLength: 10,
                searching: false,
                info: true,
                ordering: false,
                columns: [{
                        data: 'plant',
                        name: 'plant'
                    },
                    {
                        data: 'laser_target',
                        name: 'laser_target'
                    },
                    {
                        data: 'laser_production',
                        name: 'laser_production'
                    },
                    {
                        data: 'ppspray_target',
                        name: 'ppspray_target'
                    },
                    {
                        data: 'ppspray_production',
                        name: 'ppspray_production'
                    },
                    {
                        data: 'seconddryfinal_target',
                        name: 'seconddryfinal_target'
                    },
                    {
                        data: 'seconddryfinal_production',
                        name: 'seconddryfinal_production'
                    },
                    {
                        data: 'seconddryfinal_receive',
                        name: 'seconddryfinal_receive'
                    },
                    {
                        data: 'seconddryfinal_delivery',
                        name: 'seconddryfinal_delivery'
                    },
                    {
                        data: 'deviation',
                        name: 'deviation'
                    },
                    {
                        data: 'defect_qty',
                        name: 'defect_qty'
                    },
                    {
                        data: 'manPower',
                        name: 'manPower'
                    },
                    {
                        data: 'workingHr',
                        name: 'workingHr'
                    },
                    {
                        data: 'smv',
                        name: 'smv'
                    },
                    {
                        data: 'remarks',
                        name: 'remarks'
                    }
                ],
                columnDefs: [{
                    targets: 14,
                    render: function(data, type, row) {
                        if (type === 'display') {
                            var remarks = data || '-';
                            var plant = row.plant;
                            var remarkId = 'dry_remark_' + plant.replace(/[^a-zA-Z0-9]/g, '_');
                            var words = remarks.split(' ');
                            var truncatedRemarks = words.length > 20 ? words.slice(0, 20).join(
                                ' ') + '...' : remarks;
                            var buttonHtml = '';
                            if (canEditRemarks) {
                                buttonHtml =
                                    '<button type="button" class="btn btn-sm btn-outline-primary dry-process-remark-btn" ' +
                                    'data-plant="' + plant + '" data-date="' + today + '" ' +
                                    'data-remark="' + remarks.replace(/"/g, '&quot;') +
                                    '" data-process-type="second_dry">' +
                                    '<i class="fa-solid fa-pen"></i></button>';
                            } else {
                                buttonHtml =
                                    '<button type="button" class="btn btn-sm btn-outline-secondary" disabled><i class="fa-solid fa-lock"></i></button>';
                            }
                            if (remarks.includes(' | ')) {
                                var mainRemark = remarks.split(' | ')[0];
                                return '<div class="d-flex align-items-center justify-content-center">' +
                                    '<span class="remark-text dry-process-remark-text me-2" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' +
                                    remarks.replace(/"/g, '&quot;') + '" data-plant="' + plant +
                                    '" data-process-type="second_dry">' + mainRemark +
                                    ' <i class="fa-solid fa-chevron-down" style="font-size: 10px;"></i></span>' +
                                    buttonHtml + '</div>';
                            } else {
                                return '<div class="d-flex align-items-center justify-content-center">' +
                                    '<span class="remark-text dry-process-remark-text me-2" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="' +
                                    remarks.replace(/"/g, '&quot;') + '" data-plant="' + plant +
                                    '" data-process-type="second_dry">' + truncatedRemarks +
                                    '</span>' +
                                    buttonHtml + '</div>';
                            }
                        }
                        return data;
                    }
                }],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var totalLaserTarget = 0,
                        totalLaserProd = 0,
                        totalPpTarget = 0,
                        totalPpProd = 0;
                    var totalSecondDryTarget = 0,
                        totalSecondDryProd = 0,
                        totalSecondDryReceive = 0;
                    var totalSecondDryDelivery = 0,
                        totalDeviation = 0,
                        totalDefectQty = 0,
                        totalManpower = 0;

                    function parseNumber(value) {
                        if (value === null || value === undefined || value === '') return 0;
                        if (typeof value === 'number') return value;
                        var strValue = String(value).replace(/<[^>]*>/g, '').replace(/,/g, '');
                        var num = parseFloat(strValue);
                        return isNaN(num) ? 0 : num;
                    }

                    function formatNumber(num) {
                        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }

                    var pageData = api.rows({
                        page: 'current'
                    }).data();
                    for (var i = 0; i < pageData.length; i++) {
                        var row = pageData[i];
                        totalLaserTarget += parseNumber(row.laser_target);
                        totalLaserProd += parseNumber(row.laser_production);
                        totalPpTarget += parseNumber(row.ppspray_target);
                        totalPpProd += parseNumber(row.ppspray_production);
                        totalSecondDryTarget += parseNumber(row.seconddryfinal_target);
                        totalSecondDryProd += parseNumber(row.seconddryfinal_production);
                        totalSecondDryReceive += parseNumber(row.seconddryfinal_receive);
                        totalSecondDryDelivery += parseNumber(row.seconddryfinal_delivery);
                        totalDeviation += parseNumber(row.seconddryfinal_deviation);
                        totalDefectQty += parseNumber(row.total_defect_qty);
                        totalManpower += parseNumber(row.manPower);
                    }

                    $('#footerLaserTarget').text(formatNumber(Math.round(totalLaserTarget)));
                    $('#footerLaserProd').text(formatNumber(Math.round(totalLaserProd)));
                    $('#footerPpTarget').text(formatNumber(Math.round(totalPpTarget)));
                    $('#footerPpProd').text(formatNumber(Math.round(totalPpProd)));
                    $('#footerSecondDryTarget').text(formatNumber(Math.round(totalSecondDryTarget)));
                    $('#footerSecondDryProd').text(formatNumber(Math.round(totalSecondDryProd)));
                    $('#footerSecondDryReceive').text(formatNumber(Math.round(totalSecondDryReceive)));
                    $('#footerSecondDryDelivery').text(formatNumber(Math.round(
                    totalSecondDryDelivery)));
                    $('#footerSecondDryDeviation').text(formatNumber(Math.round(totalDeviation)));
                    $('#footerSecondDryDefect').text(formatNumber(Math.round(totalDefectQty)));
                    $('#footerSecondManpower').text(formatNumber(Math.round(totalManpower)));
                }
            });

            // Initialize Transfer DataTable
            var transferTable = $('#transferDataTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.wash-report-dashboard.transfer-data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    },
                    dataSrc: function(json) {
                        if (json.summary) {
                            $('#totalTransfersBadge').text('Total: ' + json.summary.total_transfers);
                            $('#verifiedTransfersBadge').text('✓ ' + json.summary.verified_transfers);
                            $('#pendingTransfersBadge').text('⏳ ' + json.summary.pending_transfers);
                            $('#rejectedTransfersBadge').text('✗ ' + json.summary.rejected_transfers);
                        }
                        return json.data;
                    }
                },
                paging: true,
                pageLength: 10,
                searching: true,
                info: true,
                ordering: true,
                order: [
                    [0, 'asc']
                ],
                columns: [{
                        data: 'unit',
                        name: 'unit'
                    },
                    {
                        data: 'existing_mc',
                        name: 'existing_mc'
                    },
                    {
                        data: 'used_mc',
                        name: 'used_mc'
                    },
                    {
                        data: 'transfer_in_details',
                        name: 'transfer_in_details'
                    },
                    {
                        data: 'transfer_out_details',
                        name: 'transfer_out_details'
                    },
                    {
                        data: 'base_mg_target',
                        name: 'base_mg_target'
                    },
                    {
                        data: 'current_mg_target',
                        name: 'current_mg_target'
                    },
                    {
                        data: 'base_capacity_pieces',
                        name: 'base_capacity_pieces'
                    },
                    {
                        data: 'current_capacity_pieces',
                        name: 'current_capacity_pieces'
                    },
                    {
                        data: 'base_capacity_kg',
                        name: 'base_capacity_kg'
                    },
                    {
                        data: 'current_capacity_kg',
                        name: 'current_capacity_kg'
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var totalExistingMc = 0,
                        totalUsedMc = 0,
                        totalBaseMg = 0,
                        totalCurrentMg = 0;
                    var totalBasePcs = 0,
                        totalCurrentPcs = 0,
                        totalBaseKg = 0,
                        totalCurrentKg = 0;

                    function parseNumber(value) {
                        if (value === null || value === undefined || value === '') return 0;
                        var strValue = String(value).replace(/,/g, '');
                        var num = parseFloat(strValue);
                        return isNaN(num) ? 0 : num;
                    }

                    function formatValue(num) {
                        return num === 0 ? '-' : Math.round(num).toLocaleString();
                    }

                    var pageData = api.rows({
                        page: 'current'
                    }).data();
                    for (var i = 0; i < pageData.length; i++) {
                        var row = pageData[i];
                        totalExistingMc += parseNumber(row.existing_mc);
                        totalUsedMc += parseNumber(row.used_mc);
                        totalBaseMg += parseNumber(row.base_mg_target);
                        totalCurrentMg += parseNumber(row.current_mg_target);
                        totalBasePcs += parseNumber(row.base_capacity_pieces);
                        totalCurrentPcs += parseNumber(row.current_capacity_pieces);
                        totalBaseKg += parseNumber(row.base_capacity_kg);
                        totalCurrentKg += parseNumber(row.current_capacity_kg);
                    }

                    $('#footerExistingMc').text(formatValue(totalExistingMc));
                    $('#footerUsedMc').text(formatValue(totalUsedMc));
                    $('#footerBaseMg').text(formatValue(totalBaseMg));
                    $('#footerCurrentMg').text(formatValue(totalCurrentMg));
                    $('#footerBasePcs').text(formatValue(totalBasePcs));
                    $('#footerCurrentPcs').text(formatValue(totalCurrentPcs));
                    $('#footerBaseKg').text(formatValue(totalBaseKg));
                    $('#footerCurrentKg').text(formatValue(totalCurrentKg));
                }
            });

            // Initialize Dryer DataTable
            var dryerTable = $('#dryerDataTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.wash-report-dashboard.dryer-data') }}",
                    type: 'GET',
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },
                paging: true,
                pageLength: 10,
                searching: false,
                info: true,
                ordering: false,
                columns: [{
                        data: 'unit',
                        name: 'unit'
                    },
                    {
                        data: 'num_dryer',
                        name: 'num_dryer',
                        render: function(data) {
                            return Number(data).toLocaleString();
                        }
                    },
                    {
                        data: 'first_wash_dryer',
                        name: 'first_wash_dryer',
                        render: function(data) {
                            return Number(data).toLocaleString();
                        }
                    },
                    {
                        data: 'cold_dryer',
                        name: 'cold_dryer',
                        render: function(data) {
                            return Number(data).toLocaleString();
                        }
                    },
                    {
                        data: 'measurement_correction',
                        name: 'measurement_correction',
                        render: function(data) {
                            return Number(data).toLocaleString();
                        }
                    },
                    {
                        data: 'final_wash_dryer',
                        name: 'final_wash_dryer',
                        render: function(data) {
                            return Number(data).toLocaleString();
                        }
                    },
                    {
                        data: 'deviation',
                        name: 'deviation'
                    },
                    {
                        data: 'total_dryer',
                        name: 'total_dryer',
                        render: function(data) {
                            return Number(data).toLocaleString();
                        }
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var totalNumDryer = 0,
                        totalFirstWash = 0,
                        totalColdDryer = 0;
                    var totalMeasCorrection = 0,
                        totalFinalWash = 0,
                        totalDeviation = 0,
                        totalDryer = 0;

                    var pageData = api.rows({
                        page: 'current'
                    }).data();
                    for (var i = 0; i < pageData.length; i++) {
                        var rowData = pageData[i];
                        var deviationValue = rowData.deviation_raw !== undefined ? rowData
                            .deviation_raw : rowData.deviation;
                        totalNumDryer += Number(rowData.num_dryer) || 0;
                        totalFirstWash += Number(rowData.first_wash_dryer) || 0;
                        totalColdDryer += Number(rowData.cold_dryer) || 0;
                        totalMeasCorrection += Number(rowData.measurement_correction) || 0;
                        totalFinalWash += Number(rowData.final_wash_dryer) || 0;
                        totalDeviation += Math.abs(Number(deviationValue) || 0);
                        totalDryer += Number(rowData.total_dryer) || 0;
                    }

                    function formatNum(n) {
                        return Math.round(n).toLocaleString();
                    }
                    $('#footerNumDryer').text(formatNum(totalNumDryer));
                    $('#footerFirstWashDryer').text(formatNum(totalFirstWash));
                    $('#footerColdDryer').text(formatNum(totalColdDryer));
                    $('#footerMeasCorrection').text(formatNum(totalMeasCorrection));
                    $('#footerFinalWashDryer').text(formatNum(totalFinalWash));
                    $('#footerDeviation').text(formatNum(Math.abs(totalDeviation)));
                    $('#footerTotalDryer').text(formatNum(totalDryer));
                }
            });

            function updateDateRange() {
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                if (fromDate && toDate) {
                    var formattedFrom = formatDate(fromDate);
                    var formattedTo = formatDate(toDate);
                    $('#dateRange').text(fromDate === toDate ? formattedFrom : formattedFrom + ' to ' +
                    formattedTo);
                }
            }

            // Event Handlers
            $('#filter_form').on('submit', function(e) {
                e.preventDefault();
                updateDateRange();
                mainTable.ajax.reload();
                firstDryTable.ajax.reload();
                secondDryTable.ajax.reload();
                dryerTable.ajax.reload();
                transferTable.ajax.reload();
            });

            $('#resetBtn, #todayBtn').on('click', function() {
                $('#from_date').val(today);
                $('#to_date').val(today);
                updateDateRange();
                mainTable.ajax.reload();
                firstDryTable.ajax.reload();
                secondDryTable.ajax.reload();
                dryerTable.ajax.reload();
                transferTable.ajax.reload();
            });

            $('#refreshBtn').on('click', function() {
                mainTable.ajax.reload(null, false);
                firstDryTable.ajax.reload(null, false);
                secondDryTable.ajax.reload(null, false);
                dryerTable.ajax.reload(null, false);
                transferTable.ajax.reload(null, false);
            });

            $('#from_date, #to_date').on('change', function() {
                updateDateRange();
            });

            // Unit Remarks - Single click on pen
            $(document).on('click', '.remark-toggle-btn', function() {
                var unit = $(this).data('unit');
                // var date = $(this).data('date');
                var date = $('#from_date').val();
                var remark = $(this).data('remark');
                $('#remark_unit').val(unit);
                $('#remark_date').val(date);
                $('#unit_display').val(unit);
                $('#date_display').val(formatDate(date));
                $('#remark_text').val(remark !== '-' ? remark : '');
                $('#remark_id').val('');
                $('#remarkModal').modal('show');
            });

            // Save Unit Remark
            $('#saveRemarkBtn').on('click', function() {
                var unit = $('#remark_unit').val();
                var date = $('#remark_date').val();
                var remark = $('#remark_text').val().trim();
                var id = $('#remark_id').val();
                if (!remark) {
                    alert('Please enter a remark');
                    return;
                }
                var url = id ? "{{ route('admin.wash-report-dashboard.update-remark') }}" :
                    "{{ route('admin.wash-report-dashboard.save-remark') }}";
                var data = id ? {
                    id: id,
                    remark: remark
                } : {
                    unit: unit,
                    date: date,
                    remark: remark
                };
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#remarkModal').modal('hide');
                            mainTable.ajax.reload(null, false);
                        }
                    },
                    error: function(xhr) {
                        alert('Error saving remark');
                    }
                });
            });

            // Unit Remarks History - Double click
            $(document).on('dblclick', '.remark-text', function() {
                if (!canEditRemarks) return;
                var unit = $(this).closest('tr').find('td:first-child').text().trim();
                $('#history_unit_name').text('Remarks History for ' + unit);
                $('#remarksHistoryBody').html(
                    '<tr><td colspan="3" class="text-center">Loading...</td></tr>');
                $.ajax({
                    url: "{{ url('admin/dashboard-two/get-remarks') }}/" + encodeURIComponent(unit),
                    type: 'GET',
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            var html = '';
                            $.each(response.data, function(index, item) {
                                html += '<tr><td class="text-center">' + formatDate(item
                                    .date) + '</td>';
                                html += '<td>' + item.Remarks + '</td>';
                                html += '<td class="text-center">';
                                if (canEditRemarks) {
                                    html +=
                                        '<button type="button" class="btn btn-sm btn-outline-primary edit-history-remark" data-id="' +
                                        item.id + '" data-remark="' + item.Remarks
                                        .replace(/"/g, '&quot;') +
                                        '"><i class="fa-solid fa-pen"></i></button>';
                                }
                                html += '</td></tr>';
                            });
                            $('#remarksHistoryBody').html(html);
                        } else {
                            $('#remarksHistoryBody').html(
                                '<tr><td colspan="3" class="text-center text-muted">No remarks found</td></tr>'
                                );
                        }
                        $('#remarksHistoryModal').modal('show');
                    },
                    error: function() {
                        $('#remarksHistoryBody').html(
                            '<tr><td colspan="3" class="text-center text-danger">Error loading remarks</td></tr>'
                            );
                    }
                });
            });

            // Edit remark from history
            $(document).on('click', '.edit-history-remark', function() {
                var id = $(this).data('id');
                var remark = $(this).data('remark');
                var unit = $('#history_unit_name').text().replace('Remarks History for ', '');
                $('#remark_id').val(id);
                $('#remark_text').val(remark);
                $('#unit_display').val(unit);
                $('#remark_unit').val(unit);
                $('#date_display').val('');
                $('#remarksHistoryModal').modal('hide');
                $('#remarkModal').modal('show');
            });

            // PDF Download - FIXED VERSION
            $('#downloadPdfBtn').on('click', function() {
                var fromDate = $('#from_date').val();
                var toDate = $('#to_date').val();
                var form = $(
                    '<form method="POST" action="{{ route('admin.wash-report-dashboard.download-pdf') }}">'
                    );
                form.append('<input type="hidden" name="_token" value="{{ csrf_token() }}">');
                form.append('<input type="hidden" name="from_date" value="' + fromDate + '">');
                form.append('<input type="hidden" name="to_date" value="' + toDate + '">');
                $('body').append(form);
                form.submit();
                form.remove();
            });
        });

        // ============================================
        // 3. DRY PROCESS REMARK HANDLERS
        // ============================================

        $(document).on('click', '.dry-process-remark-btn', function() {
            var plant = $(this).data('plant');
            var date = $('#from_date').val();
            var remark = $(this).data('remark');
            var processType = $(this).data('process-type');

            var formattedDate = formatDate(date);

            $('#dry_remark_plant').val(plant);
            $('#dry_remark_date').val(date);
            $('#dry_remark_process_type').val(processType);
            $('#dry_unit_display').val(plant + ' (' + (processType === 'first_dry' ? '1st Dry' : '2nd Dry') + ')');
            $('#dry_date_display').val(formattedDate);
            $('#dry_remark_text').val(remark !== '-' ? remark : '');
            $('#dry_remark_id').val('');

            $('#dryRemarkModal').modal('show');
        });

        $(document).on('click', '#saveDryRemarkBtn', function() {
            var plant = $('#dry_remark_plant').val();
            var date = $('#dry_remark_date').val();
            var remark = $('#dry_remark_text').val().trim();
            var id = $('#dry_remark_id').val();
            var processType = $('#dry_remark_process_type').val();

            if (!remark) {
                alert('Please enter a remark');
                return;
            }

            var url = id ? "{{ route('admin.wash-report-dashboard.update-dry-process-remark') }}" :
                "{{ route('admin.wash-report-dashboard.save-dry-process-remark') }}";
            var data = id ? {
                id: id,
                remark: remark
            } : {
                plant: plant,
                date: date,
                remark: remark,
                process_type: processType
            };

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#dryRemarkModal').modal('hide');
                        $('#firstDryProcessTable').DataTable().ajax.reload(null, false);
                        $('#secondDryProcessTable').DataTable().ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
                    alert('Error saving remark');
                }
            });
        });

        $(document).on('dblclick', '.dry-process-remark-text', function() {
            if (!canEditRemarks) return;

            var plant = $(this).data('plant');
            var processType = $(this).data('process-type');
            var processTypeName = processType === 'first_dry' ? '1st Dry Process' : '2nd Dry Process';

            $('#dry_history_unit_name').text('Remarks History for ' + plant + ' (' + processTypeName + ')');
            $('#dry_remarks_history_body').html('<tr><td colspan="3" class="text-center">Loading...</td></tr>');

            $.ajax({
                url: "{{ route('admin.wash-report-dashboard.get-dry-process-remarks') }}",
                type: 'GET',
                data: {
                    plant: plant,
                    process_type: processType
                },
                success: function(response) {
                    if (response.success && response.data.length > 0) {
                        var html = '';
                        $.each(response.data, function(index, item) {
                            html += '<tr>';
                            html += '<td class="text-center">' + formatDate(item.date) +
                            '</td>';
                            html += '<td>' + item.remarks + '</td>';
                            html += '<td class="text-center">';
                            if (canEditRemarks) {
                                html +=
                                    '<button type="button" class="btn btn-sm btn-outline-primary edit-dry-history-remark" ' +
                                    'data-id="' + item.id + '" ' +
                                    'data-remark="' + item.remarks.replace(/"/g, '&quot;') +
                                    '" ' +
                                    'data-plant="' + plant + '" ' +
                                    'data-process-type="' + processType + '">' +
                                    '<i class="fa-solid fa-pen"></i></button>';
                            }
                            html += '</td></tr>';
                        });
                        $('#dry_remarks_history_body').html(html);
                    } else {
                        $('#dry_remarks_history_body').html(
                            '<tr><td colspan="3" class="text-center text-muted">No remarks found</td></tr>'
                            );
                    }
                    $('#dryRemarksHistoryModal').modal('show');
                },
                error: function() {
                    $('#dry_remarks_history_body').html(
                        '<td><td colspan="3" class="text-center text-danger">Error loading remarks</td></tr>'
                        );
                }
            });
        });

        $(document).on('click', '.edit-dry-history-remark', function() {
            var id = $(this).data('id');
            var remark = $(this).data('remark');
            var plant = $(this).data('plant');
            var processType = $(this).data('process-type');

            $('#dry_remark_id').val(id);
            $('#dry_remark_text').val(remark);
            $('#dry_unit_display').val(plant + ' (' + (processType === 'first_dry' ? '1st Dry' : '2nd Dry') + ')');
            $('#dry_remark_plant').val(plant);
            $('#dry_remark_process_type').val(processType);
            $('#dry_date_display').val('');

            $('#dryRemarksHistoryModal').modal('hide');
            $('#dryRemarkModal').modal('show');
        });

        // CAPP Chart
        function loadCappChart() {
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();
            $.ajax({
                url: "{{ route('admin.wash-report-dashboard.capp-machine-status') }}",
                type: 'GET',
                data: {
                    from_date: fromDate,
                    to_date: toDate
                },
                success: function(response) {
                    if (response.success) {
                        $('#cappFrom').text(response.period.from || '-');
                        $('#cappTo').text(response.period.to || '-');
                        if (window.cappChart instanceof Chart) window.cappChart.destroy();
                        const ctx = document.getElementById('cappChart').getContext('2d');
                        window.cappChart = new Chart(ctx, {
                            type: 'bar',
                            data: response.chart_data,
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100,
                                        title: {
                                            display: true,
                                            text: 'Percentage (%)'
                                        },
                                        ticks: {
                                            callback: function(value) {
                                                return value + '%';
                                            }
                                        }
                                    }
                                },
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'top'
                                    }
                                },
                                barPercentage: 0.7,
                                categoryPercentage: 0.8
                            }
                        });
                        if (response.unit_data) {
                            let totalUptime = 0,
                                totalIdletime = 0,
                                totalDowntime = 0,
                                unitCount = 0;
                            Object.values(response.unit_data).forEach(unit => {
                                totalUptime += unit.uptime || 0;
                                totalIdletime += unit.idletime || 0;
                                totalDowntime += unit.downtime || 0;
                                unitCount++;
                            });
                            if (unitCount > 0) {
                                $('#uptimeValueDetail').text((totalUptime / unitCount).toFixed(2));
                                $('#idletimeValueDetail').text((totalIdletime / unitCount).toFixed(2));
                                $('#downtimeValueDetail').text((totalDowntime / unitCount).toFixed(2));
                            }
                        }
                    }
                },
                error: function(xhr) {
                    console.error('API Error:', xhr);
                }
            });
        }

        $(document).ready(function() {
            loadCappChart();
            $('#filter_form').on('submit', function(e) {
                e.preventDefault();
                loadCappChart();
            });
            $('#resetBtn, #todayBtn').on('click', function() {
                loadCappChart();
            });
        });

        $(document).ready(function() {
            // Add fade-in animation to cards
            $('.modern-card, .glass-card').each(function(index) {
                $(this).css('animation-delay', (index * 0.1) + 's');
            });
        });
    </script>
@endpush
