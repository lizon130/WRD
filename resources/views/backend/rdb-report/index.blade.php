@extends('backend.layout.app')
@section('title', 'RDB Report | ' . Helper::getSettings('application_name') ?? 'Tusuka')

@section('content')
    <style>
        .rdb-card {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #eef0f4;
        }

        .rdb-header {
            background: linear-gradient(135deg, #1f3b73 0%, #2d5da8 100%);
            border-radius: 14px 14px 0 0;
            padding: 18px 22px;
            color: #fff;
        }

        .rdb-table {
            width: 100%;
            font-size: 0.82rem;
            margin: 0;
            border-collapse: collapse;
        }

        .rdb-table thead th {
            background: #f4f6fa;
            color: #1f3b73;
            font-weight: 700;
            text-align: center;
            vertical-align: middle;
            border-bottom: 2px solid #d8dee9 !important;
            padding: 8px 10px;
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .rdb-table thead tr.group-row th {
            background: #1f3b73;
            color: #fff;
            font-size: 0.9rem;
            letter-spacing: 0.4px;
            top: 2px;
            z-index: 2;
        }

        .rdb-table tbody td {
            text-align: right;
            padding: 7px 10px;
            border-bottom: 1px solid #eef0f4;
            white-space: nowrap;
        }

        .rdb-table tbody td.date-cell,
        .rdb-table tfoot td.date-cell {
            text-align: center;
            font-weight: 600;
            color: #1f3b73;
            background: #f8fafd;
            position: sticky;
            left: 0;
            z-index: 1;
        }

        .rdb-table thead th.date-head {
            position: sticky;
            left: 0;
            z-index: 3;
            background: #f4f6fa;
        }

        .rdb-table thead tr.group-row th.date-head {
            background: #1f3b73;
            z-index: 3;
        }

        .rdb-table tbody tr:hover td {
            background: #f2f7ff;
        }

        .rdb-table tbody tr:hover td.date-cell,
        .rdb-table tbody tr:hover td.total-col {
            background: #e8f1ff;
        }

        .rdb-table .total-col {
            font-weight: 700;
            background: #f8fafd;
        }

        .rdb-table .group-sep {
            border-left: 2px solid #d8dee9;
        }

        .rdb-table tfoot td {
            text-align: right;
            font-weight: 700;
            padding: 9px 10px;
            background: #eaf0f9;
            color: #1f3b73;
            border-top: 2px solid #1f3b73;
            white-space: nowrap;
        }

        .rdb-table tfoot td.date-cell {
            text-align: center;
        }

        .rdb-table tfoot tr.avg-row td {
            background: #f4f8fd;
            color: #2d5da8;
        }

        .rdb-table tfoot tr.grand-row td {
            background: #dce7f6;
            color: #16305f;
        }

        .rdb-table .avg-col {
            background: #f6f9fd;
            color: #2d5da8;
            font-weight: 600;
        }

        .rdb-table tbody td.remarks-cell {
            min-width: 120px;
            text-align: left;
            color: #9aa5b1;
        }

        .rdb-type-toggle .btn {
            border-radius: 10px;
            font-weight: 600;
        }

        .rdb-type-toggle .btn.active {
            background: linear-gradient(135deg, #1f3b73 0%, #2d5da8 100%);
            color: #fff;
            border-color: transparent;
        }

        .rdb-loading {
            padding: 60px 0;
            text-align: center;
            color: #6c757d;
        }

        /* Skeleton shimmer loading */
        @keyframes rdbShimmer {
            0% { background-position: -400px 0; }
            100% { background-position: 400px 0; }
        }

        .rdb-skeleton-cell {
            display: inline-block;
            height: 12px;
            border-radius: 6px;
            background: linear-gradient(90deg, #eef0f4 25%, #f7f9fc 50%, #eef0f4 75%);
            background-size: 800px 100%;
            animation: rdbShimmer 1.3s infinite linear;
        }

        .rdb-table tbody tr.rdb-skeleton-row td {
            height: 38px;
            padding: 12px 10px;
        }

        /* Indeterminate progress bar */
        @keyframes rdbSlide {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(350%); }
        }

        .rdb-progress {
            height: 3px;
            background: #e3e8f0;
            overflow: hidden;
        }

        .rdb-progress span {
            display: block;
            height: 100%;
            width: 35%;
            border-radius: 3px;
            background: linear-gradient(90deg, #2d5da8, #6b9ae0);
            animation: rdbSlide 1.1s ease-in-out infinite;
        }

        /* Staggered fade-in for data rows */
        @keyframes rdbFadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .rdb-table tbody tr.rdb-fade-in {
            animation: rdbFadeIn .3s ease both;
        }

        .rdb-table-wrap {
            max-height: 68vh;
            overflow: auto;
            border-radius: 0 0 14px 14px;
        }

        .rdb-page-header h4 {
            background: linear-gradient(90deg, #1f3b73, #2d5da8);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.5rem;
        }

        .rdb-stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #ffffff;
            border: 1px solid #e3e8f0;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #1f3b73;
            box-shadow: 0 2px 8px rgba(31, 59, 115, 0.08);
        }

        .rdb-filter-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #eef0f4;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .rdb-btn-primary {
            background: linear-gradient(135deg, #1f3b73 0%, #2d5da8 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
        }

        .rdb-btn-primary:hover {
            background: linear-gradient(135deg, #1a3363 0%, #265090 100%);
            color: #fff;
        }

        .rdb-month-input {
            border-radius: 10px;
            border: 1px solid #d8dee9;
            padding: 10px 14px;
        }

        .rdb-month-input:focus {
            border-color: #2d5da8;
            box-shadow: 0 0 0 0.2rem rgba(45, 93, 168, 0.15);
        }
    </style>

    <div class="container-fluid px-4">
        <!-- Header -->
        <div class="rdb-page-header mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-1 fw-bold">RDB Report</h4>
                    <p class="text-muted mb-0">Date-wise Receive, Delivery &amp; In Hand Balance</p>
                </div>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="rdb-stat-badge">
                        <i class="fa-solid fa-calendar-days text-primary"></i>
                        <span id="rdbMonthLabel">--</span>
                    </div>
                    <div class="rdb-stat-badge">
                        <i class="fa-solid fa-clock text-info"></i>
                        <span id="rdbDateRange">--</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Month Filter -->
        <div class="rdb-filter-card my-3">
            <div class="card-body p-4">
                <form id="rdbFilterForm" onsubmit="return false;">
                    <div class="row align-items-end g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold mb-2">
                                <i class="fa-solid fa-table-list me-1 text-primary"></i> Report Type
                            </label>
                            <div class="btn-group w-100 rdb-type-toggle" id="rdbTypeToggle">
                                <button type="button" class="btn btn-outline-primary active" data-type="month">Month-wise</button>
                                <button type="button" class="btn btn-outline-primary" data-type="year">Year-wise</button>
                            </div>
                        </div>
                        <div class="col-md-3" id="rdbMonthCol">
                            <label for="rdbMonth" class="form-label fw-semibold mb-2">
                                <i class="fa-regular fa-calendar me-1 text-primary"></i> Month
                            </label>
                            <input type="month" class="form-control rdb-month-input" id="rdbMonth"
                                value="{{ now()->format('Y-m') }}">
                        </div>
                        <div class="col-md-3 d-none" id="rdbYearCol">
                            <label for="rdbYear" class="form-label fw-semibold mb-2">
                                <i class="fa-regular fa-calendar me-1 text-primary"></i> Year
                            </label>
                            <input type="number" class="form-control rdb-month-input" id="rdbYear" min="2000"
                                max="2100" step="1" value="{{ now()->format('Y') }}">
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex gap-2">
                                <button type="button" id="rdbApplyBtn" class="btn rdb-btn-primary flex-grow-1">
                                    <i class="fa-solid fa-filter me-2"></i> Apply Filter
                                </button>
                                <button type="button" id="rdbResetBtn" class="btn btn-outline-secondary"
                                    title="Current Month / Year">
                                    <i class="fa-solid fa-rotate-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- RDB Table -->
        <div class="rdb-card mb-4">
            <div class="rdb-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-table-columns fa-2x"></i>
                        <div>
                            <h5 class="mb-0 fw-bold">RDB Report</h5>
                            <p class="mb-0 small" style="opacity:.8">Unit-wise Daily Statement</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light btn-sm" id="rdbRefreshBtn">
                            <i class="fa-solid fa-rotate me-1"></i> Refresh
                        </button>
                        <button type="button" class="btn btn-light btn-sm" id="rdbExportPdfBtn">
                            <i class="fa-solid fa-file-pdf me-1 text-danger"></i> Export PDF
                        </button>
                    </div>
                </div>
            </div>
            <div class="rdb-progress d-none" id="rdbProgress"><span></span></div>
            <div class="rdb-table-wrap">
                <table class="table rdb-table mb-0" id="rdbTable">
                    <thead id="rdbTableHead">
                        <tr class="group-row">
                            <th class="date-head" rowspan="2">Date</th>
                            <th colspan="7">Receive</th>
                            <th class="group-sep" colspan="7">Delivery</th>
                            <th class="group-sep" colspan="7">In Hand Balance</th>
                        </tr>
                        <tr>
                            <th>Unit-01</th>
                            <th>Unit-02</th>
                            <th>Unit-03</th>
                            <th>Unit-04</th>
                            <th>Unit-05</th>
                            <th>TWL</th>
                            <th>Total</th>
                            <th class="group-sep">Unit-01</th>
                            <th>Unit-02</th>
                            <th>Unit-03</th>
                            <th>Unit-04</th>
                            <th>Unit-05</th>
                            <th>TWL</th>
                            <th>Total</th>
                            <th class="group-sep">Unit-01</th>
                            <th>Unit-02</th>
                            <th>Unit-03</th>
                            <th>Unit-04</th>
                            <th>Unit-05</th>
                            <th>TWL</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="rdbTableBody">
                        <tr>
                            <td colspan="22" class="rdb-loading">
                                <i class="fa-solid fa-spinner fa-spin me-2"></i> Loading data...
                            </td>
                        </tr>
                    </tbody>
                    <tfoot id="rdbTableFoot"></tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script>
        $(function() {
            const unitKeys = ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL', 'total'];
            const unitHeadLabels = ['Unit-01', 'Unit-02', 'Unit-03', 'Unit-04', 'Unit-05', 'TWL', 'Total'];
            let rdbType = 'month'; // 'month' | 'year'

            function formatNumber(value) {
                const n = Number(value) || 0;
                return Math.round(n).toLocaleString();
            }

            function currentParams() {
                if (rdbType === 'year') {
                    return {
                        type: 'year',
                        year: $('#rdbYear').val() || String(new Date().getFullYear())
                    };
                }
                return {
                    type: 'month',
                    month: $('#rdbMonth').val() || new Date().toISOString().slice(0, 7)
                };
            }

            /* ---------- Table heads ---------- */
            function monthlyHead() {
                let units = '';
                for (let g = 0; g < 3; g++) {
                    unitHeadLabels.forEach((label, i) => {
                        units += '<th' + (g > 0 && i === 0 ? ' class="group-sep"' : '') + '>' + label + '</th>';
                    });
                }
                return '<tr class="group-row">' +
                    '<th class="date-head" rowspan="2">Date</th>' +
                    '<th colspan="7">Receive</th>' +
                    '<th class="group-sep" colspan="7">Delivery</th>' +
                    '<th class="group-sep" colspan="7">In Hand Balance</th>' +
                    '</tr><tr>' + units + '</tr>';
            }

            function yearlyHead() {
                let units = '';
                const avgLabels = ['Avg. Rcv/Day', 'Avg. Del/Day'];
                for (let g = 0; g < 2; g++) {
                    unitHeadLabels.forEach((label, i) => {
                        units += '<th' + (g > 0 && i === 0 ? ' class="group-sep"' : '') + '>' + label + '</th>';
                    });
                    units += '<th>' + avgLabels[g] + '</th>';
                }
                return '<tr class="group-row">' +
                    '<th class="date-head" rowspan="2">Month</th>' +
                    '<th colspan="8">Receive</th>' +
                    '<th class="group-sep" colspan="8">Delivery</th>' +
                    '<th class="group-sep" rowspan="2">Remarks</th>' +
                    '</tr><tr>' + units + '</tr>';
            }

            function messageRow(msg, isError) {
                const colspan = rdbType === 'year' ? 19 : 22;
                const icon = isError ?
                    '<i class="fa-solid fa-triangle-exclamation me-2"></i>' :
                    '<i class="fa-regular fa-folder-open me-2"></i>';
                const cls = isError ? 'rdb-loading text-danger' : 'rdb-loading';
                return '<tr><td colspan="' + colspan + '" class="' + cls + '">' + icon + msg + '</td></tr>';
            }

            function skeletonRows() {
                const cols = rdbType === 'year' ? 19 : 22;
                const rows = rdbType === 'year' ? 12 : 16;
                let html = '';
                for (let r = 0; r < rows; r++) {
                    html += '<tr class="rdb-skeleton-row">';
                    for (let c = 0; c < cols; c++) {
                        const w = c === 0 ? 60 : 45 + ((r * 7 + c * 13) % 45);
                        html += '<td' + (c === 0 ? ' class="date-cell"' : '') +
                            '><span class="rdb-skeleton-cell" style="width:' + w + '%"></span></td>';
                    }
                    html += '</tr>';
                }
                return html;
            }

            function setLoading(isLoading) {
                $('#rdbProgress').toggleClass('d-none', !isLoading);
                $('#rdbApplyBtn, #rdbRefreshBtn, #rdbExportPdfBtn').prop('disabled', isLoading);
            }

            function animateRows() {
                $('#rdbTableBody tr').each(function(i) {
                    $(this).css('animation-delay', Math.min(i * 18, 400) + 'ms')
                        .addClass('rdb-fade-in');
                });
            }

            /* ---------- Row builders ---------- */
            function buildMonthlyRow(label, receive, delivery, inHand, cssClass, hideInHand) {
                let html = '<tr' + (cssClass ? ' class="' + cssClass + '"' : '') + '>' +
                    '<td class="date-cell">' + label + '</td>';

                [receive, delivery, inHand].forEach((group, groupIndex) => {
                    unitKeys.forEach(key => {
                        const cls = (groupIndex > 0 ? 'group-sep' : '') +
                            (key === 'total' ? ' total-col' : '');
                        const value = (hideInHand && groupIndex === 2) ? '' : formatNumber(group[key]);
                        html += '<td class="' + cls.trim() + '">' + value + '</td>';
                    });
                });

                return html + '</tr>';
            }

            function buildYearlyRow(label, receive, delivery, avgRcv, avgDel, cssClass) {
                let html = '<tr' + (cssClass ? ' class="' + cssClass + '"' : '') + '>' +
                    '<td class="date-cell">' + label + '</td>';

                [receive, delivery].forEach((group, groupIndex) => {
                    unitKeys.forEach(key => {
                        const cls = (groupIndex > 0 ? 'group-sep' : '') +
                            (key === 'total' ? ' total-col' : '');
                        html += '<td class="' + cls.trim() + '">' + formatNumber(group[key]) + '</td>';
                    });
                    html += '<td class="avg-col">' + formatNumber(groupIndex === 0 ? avgRcv : avgDel) + '</td>';
                });

                return html + '<td class="remarks-cell"></td></tr>';
            }

            /* ---------- Renderers ---------- */
            function renderMonthly(data) {
                let body = '';
                data.rows.forEach(row => {
                    body += buildMonthlyRow(row.date, row.receive, row.delivery, row.in_hand, '');
                });

                if (!data.rows.length) {
                    body = loadingRow('No data found for this month.');
                }

                $('#rdbTableBody').html(body);

                let foot = '';
                foot += buildMonthlyRow('Total', data.totals.receive, data.totals.delivery, data.totals.in_hand, '', true);
                foot += buildMonthlyRow('Avg', data.averages.receive, data.averages.delivery, data.averages.in_hand, 'avg-row', true);
                $('#rdbTableFoot').html(foot);

                $('#rdbMonthLabel').text(data.month_label);
                $('#rdbDateRange').text(data.date_range + ' (' + data.days + ' days)');
            }

            function renderYearly(data) {
                let body = '';
                data.rows.forEach(row => {
                    body += buildYearlyRow(row.month_label, row.receive, row.delivery,
                        row.avg_receive_per_day, row.avg_delivery_per_day, '');
                });

                if (!data.rows.length) {
                    body = messageRow('No data found for this year.');
                }

                $('#rdbTableBody').html(body);

                let foot = '';
                foot += buildYearlyRow('GRAND TOTAL', data.grand_total.receive, data.grand_total.delivery,
                    data.grand_total.avg_receive_per_day, data.grand_total.avg_delivery_per_day, 'grand-row');
                foot += buildYearlyRow('Avg / Month', data.avg_per_month.receive, data.avg_per_month.delivery,
                    data.avg_per_month.avg_receive_per_day, data.avg_per_month.avg_delivery_per_day, 'avg-row');
                $('#rdbTableFoot').html(foot);

                $('#rdbMonthLabel').text(data.year_label);
                $('#rdbDateRange').text(data.date_range + ' (' + data.months + ' months)');
            }

            function loadRdbData() {
                $('#rdbTableHead').html(rdbType === 'year' ? yearlyHead() : monthlyHead());
                $('#rdbTableBody').html(skeletonRows());
                $('#rdbTableFoot').html('');
                setLoading(true);

                $.ajax({
                    url: "{{ route('admin.rdb-report.get-data') }}",
                    type: 'GET',
                    data: currentParams(),
                    success: function(response) {
                        if (response.type === 'yearly') {
                            renderYearly(response);
                        } else {
                            renderMonthly(response);
                        }
                        animateRows();
                    },
                    error: function(xhr) {
                        const msg = (xhr.responseJSON && xhr.responseJSON.error) ? xhr.responseJSON.error :
                            'Failed to load RDB report data.';
                        $('#rdbTableBody').html(messageRow(msg, true));
                    },
                    complete: function() {
                        setLoading(false);
                    }
                });
            }

            /* ---------- Events ---------- */
            $('#rdbApplyBtn').on('click', loadRdbData);
            $('#rdbRefreshBtn').on('click', loadRdbData);

            $('#rdbTypeToggle button').on('click', function() {
                const type = $(this).data('type');
                if (type === rdbType) return;

                rdbType = type;
                $('#rdbTypeToggle button').removeClass('active');
                $(this).addClass('active');
                $('#rdbMonthCol').toggleClass('d-none', type === 'year');
                $('#rdbYearCol').toggleClass('d-none', type !== 'year');
                loadRdbData();
            });

            $('#rdbResetBtn').on('click', function() {
                $('#rdbMonth').val(new Date().toISOString().slice(0, 7));
                $('#rdbYear').val(String(new Date().getFullYear()));
                loadRdbData();
            });

            $('#rdbExportPdfBtn').on('click', function() {
                const btn = $(this);
                const params = currentParams();
                const isYear = rdbType === 'year';

                btn.prop('disabled', true)
                    .html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Exporting...');

                $.ajax({
                    url: "{{ route('admin.rdb-report.download-pdf') }}",
                    type: 'GET',
                    data: params,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(blob, status, xhr) {
                        // Check if server returned JSON error instead of a PDF
                        if (blob.type === 'application/json') {
                            const reader = new FileReader();
                            reader.onload = function() {
                                const resp = JSON.parse(reader.result);
                                alert(resp.message || 'Failed to generate PDF.');
                            };
                            reader.readAsText(blob);
                            return;
                        }

                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = isYear ?
                            'RDB_Report_Yearly_' + params.year + '.pdf' :
                            'RDB_Report_' + params.month + '.pdf';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(url);
                    },
                    error: function() {
                        alert('Failed to export RDB report PDF.');
                    },
                    complete: function() {
                        btn.prop('disabled', false)
                            .html('<i class="fa-solid fa-file-pdf me-1 text-danger"></i> Export PDF');
                    }
                });
            });

            // Initial load (current month, day 1 to till now)
            loadRdbData();
        });
    </script>
@endpush
