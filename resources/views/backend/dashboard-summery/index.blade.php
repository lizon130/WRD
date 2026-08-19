@extends('backend.layout.app')
@section('title', 'Dashboard Summary | ' . Helper::getSettings('application_name') ?? 'Tusuka')
@section('content')
<div class="container-fluid px-4">
    <h4 class="mt-2">Dashboard Summary - Unit Production Details</h4>

    <!-- Filter Card -->
    <div class="card my-3">
        <div class="card-body">
            <form method="GET" id="filter_form">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label for="from_date" class="form-label">From Date</label>
                        <input type="date" class="form-control" name="from_date" id="from_date">
                    </div>
                    <div class="col-md-4 mb-2">
                        <label for="to_date" class="form-label">To Date</label>
                        <input type="date" class="form-control" name="to_date" id="to_date">
                    </div>
                    <div class="col-md-4 mb-2 d-flex align-items-end">
                        <div class="form-group w-100">
                            <button type="submit" id="filterBtn" class="btn btn-primary">
                                <i class="feather icon-filter"></i> Apply Filter
                            </button>
                            <button type="button" id="resetBtn" class="btn btn-secondary">
                                <i class="feather icon-refresh-cw"></i> Reset
                            </button>
                            <button type="button" id="todayBtn" class="btn btn-info">
                                <i class="feather icon-calendar"></i> Today
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Date Info Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Showing Data For Date Range</h6>
                            <h4 class="mb-0" id="dateRange">Loading...</h4>
                        </div>
                        <i class="fa-solid fa-calendar fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unit Data Table Card -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <h5 class="m-0">Unit Details with Production Data</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success" id="refreshBtn">
                            <i class="fa-solid fa-rotate"></i> Refresh
                        </button>

                        <button type="button" class="btn btn-danger" id="downloadPdfBtn">
    <i class="fa-solid fa-file-pdf"></i> Download PDF
</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered text-center" id="unitDataTable">
                <thead>
                    <tr class="text-center">
                        <th class="text-center">Unit</th>
                        <th class="text-center">Used MC</th>
                        <th class="text-center">Sewing Lines</th>
                        <th class="text-center">Machine Work Hr</th>
                        <th class="text-center">Received</th>
                        <th class="text-center">Delivery</th>
                        <th class="text-center">Delivery (kg)</th>
                        <th class="text-center">Garment (g)</th>
                        <th class="text-center">Balance</th>
                        <th class="text-center">Approval Pending Qty</th>
                    </tr>
                </thead>
                <tbody class="text-center"></tbody>
                <tfoot class="bg-light font-weight-bold">
                    <tr>
                        <th class="text-center">Totals:</th>
                        <th class="text-center" id="footerMachines">0</th>
                        <th class="text-center" id="footerSewingLines">-</th>
                        <th class="text-center" id="footerWorkHours">0.00</th>
                        <th class="text-center" id="footerDelivery">0</th>
                        <th class="text-center" id="footerReceived">0</th>
                        <th class="text-center" id="footerDeliveryKg">0.00</th>
                        <th class="text-center" id="footerGarment">-</th>
                        <th class="text-center" id="footerBalance">0</th>
                        <th class="text-center" id="footerApprovalPending">0</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Current Month Data Table Card (Auto-filtered: 1st of month to today) -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <div class="row">
                <div class="col-12 d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <h5 class="m-0" id="monthTableTitle">Current Month Production Data</h5>
                    </div>
                    <div>
                        <button type="button" class="btn btn-light" id="refreshMonthBtn">
                            <i class="fa-solid fa-rotate"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered text-center" id="currentMonthDataTable">
                <thead>
                    <tr class="text-center">
                        <th class="text-center">Unit</th>
                        <th class="text-center">Used MC</th>
                        <th class="text-center">Sewing Lines</th>
                        <th class="text-center">Machine Work Hr</th>
                        <th class="text-center">Avg Work Hr</th>
                        <th class="text-center">Received</th>
                        <th class="text-center">Avg Recv</th>
                        <th class="text-center">Delivery</th>
                        <th class="text-center">Avg Delv</th>
                        <th class="text-center">Delivery (kg)</th>
                        <th class="text-center">Garment (g)</th>
                    </tr>
                </thead>
                <tbody class="text-center"></tbody>
                <tfoot class="bg-light font-weight-bold">
                    <tr>
                        <th class="text-center">Totals/Avg:</th>
                        <th class="text-center" id="monthFooterMachines">0</th>
                        <th class="text-center" id="monthFooterSewingLines">-</th>
                        <th class="text-center" id="monthFooterWorkHours">0.00</th>
                        <th class="text-center" id="monthFooterAvgWorkHours">0.00</th>
                        <th class="text-center" id="monthFooterReceived">0</th>
                        <th class="text-center" id="monthFooterAvgRecv">0</th>
                        <th class="text-center" id="monthFooterDelivery">0</th>
                        <th class="text-center" id="monthFooterAvgDelv">0</th>
                        <th class="text-center" id="monthFooterDeliveryKg">0.00</th>
                        <th class="text-center" id="monthFooterGarment">-</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<style>
    .remark-text {
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px dashed #6c757d;
    }

    .remark-text:hover {
        background-color: #e9ecef;
        border-bottom: 1px solid #0d6efd;
    }
</style>
@endsection

@push('footer')
<script type="text/javascript">
    function formatDate(dateString) {
        if (!dateString) return '';
        var date = new Date(dateString);
        var day = ('0' + date.getDate()).slice(-2);
        var month = ('0' + (date.getMonth() + 1)).slice(-2);
        var year = date.getFullYear();
        return day + '-' + month + '-' + year;
    }

    // Get current month start date (1st of current month)
    function getCurrentMonthStart() {
        var today = new Date();
        var year = today.getFullYear();
        var month = today.getMonth();
        var firstDay = new Date(year, month, 1);
        var formattedYear = firstDay.getFullYear();
        var formattedMonth = String(firstDay.getMonth() + 1).padStart(2, '0');
        var formattedDay = String(firstDay.getDate()).padStart(2, '0');
        return formattedYear + '-' + formattedMonth + '-' + formattedDay;
    }

    function getToday() {
        var today = new Date();
        var year = today.getFullYear();
        var month = String(today.getMonth() + 1).padStart(2, '0');
        var day = String(today.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }

    var today = getToday();
    var currentMonthStart = getCurrentMonthStart();

    $(document).ready(function() {
        // Set default dates for main table
        $('#from_date').val(today);
        $('#to_date').val(today);
        updateDateRange();

        // Initialize Main DataTable (user filtered)
        var mainTable = $('#unitDataTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('dashboard.summary.get-data') }}",
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
                    name: 'used_mc',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var num = parseFloat(data);
                            return isNaN(num) ? '0' : num.toLocaleString();
                        }
                        return data;
                    }
                },
                {
                    data: 'sewing_lines',
                    name: 'sewing_lines',
                    render: function(data) {
                        return data || '-';
                    }
                },
                {
                    data: 'work_hours',
                    name: 'work_hours',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var num = parseFloat(data);
                            return isNaN(num) ? '0.00' : num.toFixed(2);
                        }
                        return data;
                    }
                },

                {
                    data: 'delivery',
                    name: 'delivery',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var num = typeof data === 'string' ? parseFloat(data.replace(/,/g, '')) : parseFloat(data);
                            return isNaN(num) ? '0' : num.toLocaleString();
                        }
                        return data;
                    }
                },
                {
                    data: 'received',
                    name: 'received',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var num = typeof data === 'string' ? parseFloat(data.replace(/,/g, '')) : parseFloat(data);
                            return isNaN(num) ? '0' : num.toLocaleString();
                        }
                        return data;
                    }
                },
                {
                    data: 'delivery_kg',
                    name: 'delivery_kg',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var cleanData = data.toString().replace(/,/g, '');
                            var num = parseFloat(cleanData);
                            if (isNaN(num)) return '0.00';
                            return num.toLocaleString(undefined, {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                        return data;
                    }
                },
                {
                    data: 'garment',
                    name: 'garment',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var num = parseFloat(data);
                            return isNaN(num) ? '0' : Math.round(num).toLocaleString();
                        }
                        return data;
                    }
                },
                {
                    data: 'balance',
                    name: 'balance',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            return data;
                        }
                        return data;
                    }
                },
                {
                    data: 'approval_pending',
                    name: 'approval_pending',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var num = typeof data === 'string' ? parseFloat(data.replace(/,/g, '')) : parseFloat(data);
                            return isNaN(num) ? '0' : num.toLocaleString();
                        }
                        return data;
                    }
                }
            ],
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();
                var totalUsedMc = 0;
                var totalWorkHours = 0;
                var totalReceived = 0;
                var totalDelivery = 0;
                var totalDeliveryKg = 0;
                var totalGarment = 0;
                var totalBalance = 0;
                var totalApprovalPending = 0;
                var garmentCount = 0;

                function parseNumber(value) {
                    if (value === null || value === undefined || value === '') return 0;
                    var strValue = String(value);
                    var cleanValue = strValue.replace(/[^\d.-]/g, '');
                    var num = parseFloat(cleanValue);
                    if (isNaN(num)) return 0;
                    return num;
                }

                function formatNumber(num) {
                    return Math.round(num).toLocaleString();
                }

                var allData = api.data();

                for (var i = 0; i < allData.length; i++) {
                    var rowData = allData[i];

                    totalUsedMc += parseNumber(rowData.used_mc);
                    totalWorkHours += parseNumber(rowData.work_hours);
                    totalReceived += parseNumber(rowData.received);
                    totalDelivery += parseNumber(rowData.delivery);
                    totalDeliveryKg += parseNumber(rowData.delivery_kg);
                    totalBalance += parseNumber(rowData.balance);
                    totalApprovalPending += parseNumber(rowData.approval_pending);

                    var garmentVal = parseNumber(rowData.garment);
                    if (garmentVal > 0) {
                        totalGarment += garmentVal;
                        garmentCount++;
                    }
                }

                var avgGarment = garmentCount > 0 ? totalGarment / garmentCount : 0;

                $('#footerMachines').text(formatNumber(totalUsedMc));
                $('#footerWorkHours').text(totalWorkHours.toFixed(2));
                $('#footerDelivery').text(formatNumber(totalDelivery));
                $('#footerReceived').text(formatNumber(totalReceived));
                $('#footerDeliveryKg').text(totalDeliveryKg.toFixed(2));
                $('#footerGarment').text(Math.round(avgGarment).toLocaleString());
                $('#footerBalance').text(formatNumber(totalBalance));
                $('#footerApprovalPending').text(formatNumber(totalApprovalPending));
            }
        });

        // Initialize Current Month DataTable (auto-filtered: 1st of month to today)
        var monthTable = $('#currentMonthDataTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('dashboard.summary.get-data') }}",
                type: 'GET',
                data: function(d) {
                    d.from_date = currentMonthStart;
                    d.to_date = today;
                },
                dataSrc: function(json) {
                    var fromDate = new Date(currentMonthStart);
                    var toDate = new Date(today);
                    var diffTime = Math.abs(toDate - fromDate);
                    var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    window.monthTableDaysCount = diffDays;

                    var formattedFrom = formatDate(currentMonthStart);
                    var formattedTo = formatDate(today);
                    $('#monthDateRange').text(formattedFrom + ' to ' + formattedTo);
                    $('#monthTableTitle').text('Current Month Production Data (' + formattedFrom + ' to ' + formattedTo + ')');
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
                    name: 'used_mc',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var num = parseFloat(data);
                            return isNaN(num) ? '0' : num.toLocaleString();
                        }
                        return data;
                    }
                },
                {
                    data: 'sewing_lines',
                    name: 'sewing_lines',
                    render: function(data) {
                        return data || '-';
                    }
                },
                {
                    data: 'work_hours',
                    name: 'work_hours',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var num = parseFloat(data);
                            return isNaN(num) ? '0.00' : num.toFixed(2);
                        }
                        return data;
                    }
                },
                {
                    data: 'work_hours',
                    name: 'avg_work_hours',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var num = parseFloat(data);
                            var days = window.monthTableDaysCount || 1;
                            var avg = isNaN(num) ? 0 : num / days;
                            return avg.toFixed(2);
                        }
                        return data;
                    }
                },
                {
                    data: 'delivery',
                    name: 'delivery',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var num = typeof data === 'string' ? parseFloat(data.replace(/,/g, '')) : parseFloat(data);
                            return isNaN(num) ? '0' : num.toLocaleString();
                        }
                        return data;
                    }
                },
                {
                    data: 'delivery',
                    name: 'avg_recv',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var num = typeof data === 'string' ? parseFloat(data.replace(/,/g, '')) : parseFloat(data);
                            var days = window.monthTableDaysCount || 1;
                            var avg = isNaN(num) ? 0 : num / days;
                            return Math.round(avg).toLocaleString();
                        }
                        return data;
                    }
                },
                {
                    data: 'received',
                    name: 'received',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var num = typeof data === 'string' ? parseFloat(data.replace(/,/g, '')) : parseFloat(data);
                            return isNaN(num) ? '0' : num.toLocaleString();
                        }
                        return data;
                    }
                },
                {
                    data: 'received',
                    name: 'avg_delv',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var num = typeof data === 'string' ? parseFloat(data.replace(/,/g, '')) : parseFloat(data);
                            var days = window.monthTableDaysCount || 1;
                            var avg = isNaN(num) ? 0 : num / days;
                            return Math.round(avg).toLocaleString();
                        }
                        return data;
                    }
                },
                {
                    data: 'delivery_kg',
                    name: 'delivery_kg',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var cleanData = data ? data.toString().replace(/,/g, '') : '0';
                            var num = parseFloat(cleanData);
                            if (isNaN(num)) return '0.00';
                            return num.toLocaleString(undefined, {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                        return data;
                    }
                },
                {
                    data: 'garment',
                    name: 'garment',
                    render: function(data, type, row) {
                        if (type === 'display' || type === 'filter') {
                            var num = parseFloat(data);
                            return isNaN(num) ? '0' : Math.round(num).toLocaleString();
                        }
                        return data;
                    }
                }
            ],
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();
                var totalUsedMc = 0;
                var totalWorkHours = 0;
                var totalReceived = 0;
                var totalDelivery = 0;
                var totalDeliveryKg = 0;
                var totalGarment = 0;
                var garmentCount = 0;

                function parseNumber(value) {
                    if (value === null || value === undefined || value === '') return 0;
                    var strValue = String(value);
                    var cleanValue = strValue.replace(/[^\d.-]/g, '');
                    var num = parseFloat(cleanValue);
                    if (isNaN(num)) return 0;
                    return num;
                }

                function formatNumber(num) {
                    return Math.round(num).toLocaleString();
                }

                var allData = api.data();
                var daysCount = window.monthTableDaysCount || 1;

                for (var i = 0; i < allData.length; i++) {
                    var rowData = allData[i];
                    totalUsedMc += parseNumber(rowData.used_mc);
                    totalWorkHours += parseNumber(rowData.work_hours);
                    totalReceived += parseNumber(rowData.delivery);
                    totalDelivery += parseNumber(rowData.received);
                    totalDeliveryKg += parseNumber(rowData.delivery_kg);

                    var garmentVal = parseNumber(rowData.garment);
                    if (garmentVal > 0) {
                        totalGarment += garmentVal;
                        garmentCount++;
                    }
                }

                var avgGarment = garmentCount > 0 ? totalGarment / garmentCount : 0;
                var avgWorkHours = totalWorkHours / daysCount;
                var avgReceived = totalReceived / daysCount;
                var avgDelivery = totalDelivery / daysCount;

                $('#monthFooterMachines').text(formatNumber(totalUsedMc));
                $('#monthFooterWorkHours').text(totalWorkHours.toFixed(2));
                $('#monthFooterAvgWorkHours').text(avgWorkHours.toFixed(2));
                $('#monthFooterReceived').text(formatNumber(totalReceived));
                $('#monthFooterAvgRecv').text(Math.round(avgReceived).toLocaleString());
                $('#monthFooterDelivery').text(formatNumber(totalDelivery));
                $('#monthFooterAvgDelv').text(Math.round(avgDelivery).toLocaleString());
                $('#monthFooterDeliveryKg').text(totalDeliveryKg.toFixed(2));
                $('#monthFooterGarment').text(Math.round(avgGarment).toLocaleString());
            }
        });

        function updateDateRange() {
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();
            if (fromDate && toDate) {
                var formattedFrom = formatDate(fromDate);
                var formattedTo = formatDate(toDate);
                $('#dateRange').text(fromDate === toDate ? formattedFrom : formattedFrom + ' to ' + formattedTo);
            }
        }

        // Add date range indicator for month table
        $('.card-header.bg-success .d-flex .d-flex').append('<div class="ms-3 text-white"><i class="fa-solid fa-calendar-alt"></i> <span id="monthDateRange">Loading...</span></div>');

        // Function to refresh only the balance and approval pending columns
        function refreshBalanceData() {
            $.ajax({
                url: "{{ route('dashboard.summary.get-balance') }}",
                type: 'GET',
                success: function(response) {
                    if (response.balance_data) {
                        var table = $('#unitDataTable').DataTable();
                        var rows = table.rows().data();

                        for (var i = 0; i < rows.length; i++) {
                            var unit = rows[i].unit;
                            if (response.balance_data[unit] !== undefined) {
                                rows[i].balance = response.balance_data[unit];
                            }
                            if (response.approval_pending_data && response.approval_pending_data[unit] !== undefined) {
                                rows[i].approval_pending = response.approval_pending_data[unit];
                            }
                        }

                        table.rows().invalidate().draw(false);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error refreshing balance data:', error);
                }
            });
        }

        // Auto-refresh balance every 5 minutes (300000 ms)
        // setInterval(refreshBalanceData, 300000);

        // Event Handlers
        $('#filter_form').on('submit', function(e) {
            e.preventDefault();
            updateDateRange();
            mainTable.ajax.reload();
        });

        $('#resetBtn, #todayBtn').on('click', function() {
            $('#from_date').val(today);
            $('#to_date').val(today);
            updateDateRange();
            mainTable.ajax.reload();
        });

        $('#refreshBtn').on('click', function() {
            mainTable.ajax.reload(null, false);
        });

        $('#refreshMonthBtn').on('click', function() {
            monthTable.ajax.reload(null, false);
        });

        $('#from_date, #to_date').on('change', function() {
            updateDateRange();
        });
        

        $('#downloadPdfBtn').on('click', function() {
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();
            var url = "{{ route('dashboard.summary.generate-pdf') }}?from_date=" + fromDate + "&to_date=" + toDate;
            
            // Show loading indicator
            var $btn = $(this);
            var originalHtml = $btn.html();
            $btn.html('<i class="fa-solid fa-spinner fa-spin"></i> Generating...').prop('disabled', true);
            
            // Open in new tab or download
            window.location.href = url;
            
            // Reset button after 3 seconds
            setTimeout(function() {
                $btn.html(originalHtml).prop('disabled', false);
            }, 3000);
        });

    });
</script>
@endpush