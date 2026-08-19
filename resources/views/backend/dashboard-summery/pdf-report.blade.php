<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Summary Report</title>
    <style>
        /* body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8px;
            margin: 0;
            padding: 15px 15px 5px 15px;
            color: #222;
        } */

        .tables-wrapper {
            display: flex;
            flex-direction: row;
            gap: 15px;
            margin-bottom: 5px;
        }

        .table-column {
            flex: 1;
            min-width: 0;
            padding: 0 5px;
        }

        /* Prevent page breaks inside elements */
        .page-break-avoid {
            page-break-inside: avoid;
            page-break-before: auto;
            page-break-after: auto;
        }

        /* Ensure tables don't break */
        table {
            page-break-inside: avoid;
        }

        /* Make the last section stay on first page */
        .last-section {
            page-break-inside: avoid;
            margin-top: 5px;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .header h2 {
            margin: 0 0 5px 0;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header p {
            margin: 3px 0 0 0;
            font-size: 9px;
            color: #555;
        }

        table {
            border-collapse: collapse;
            font-size: 7.5px;
        }

        .layout-table {
            width: 100%;
            border: none;
            margin-bottom: 5px;
        }

        .layout-table td {
            vertical-align: top;
            padding: 0 3px;
        }

        .data-table {
            width: 100%;
            border: 0.5px solid #999;
        }

        .data-table th,
        .data-table td {
            border: 0.5px solid #999;
            padding: 4px 5px;
            text-align: center;
            vertical-align: middle;
        }

        .data-table th {
            background-color: #e9ecef;
            font-weight: bold;
            font-size: 7.5px;
        }

        .data-table tfoot th {
            background-color: #d1d5db;
            font-weight: bold;
            font-size: 7.5px;
            border-top: 1px solid #666;
        }

        .data-table tfoot td {
            background-color: #f3f4f6;
            font-weight: bold;
            font-size: 7.5px;
            border-top: 1px solid #666;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .section-title {
            font-size: 8.5px;
            font-weight: bold;
            margin-bottom: 2px;
            margin-top: 3px;
            background-color: #f0f0f0;
            padding: 2px 4px;
            border-left: 3px solid #007bff;
            display: block;
        }

        /* Right alignment for numbers */
        .num-right {
            text-align: center !important;
        }

        /* Center alignment for percentages */
        .percent-center {
            text-align: center !important;
        }

        /* Left alignment for text */
        .text-left {
            text-align: center !important;
        }

        .text-success {
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-warning {
            color: #ffc107;
        }

        .fw-bold {
            font-weight: bold;
        }

        .main-table td:nth-child(1) {
            text-align: center !important;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            font-size: 6.5px;
            color: #888;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <!-- Header -->
     <div class="header" style="position: relative; margin-bottom: 5px;">
        <div style="position: absolute; top: 0; left: 0;">
            <img src="{{ public_path('assets/img/logo.jpeg') }}" alt="Tusuka" style="height: 30px; width: auto;">
        </div>
        <div style="text-align: center; margin-bottom: 5px;">
            <h2>DASHBOARD SUMMARY - UNIT PRODUCTION DETAILS</h2>
            <p>{{ config('TUSUKA', 'Tusuka') }} | Generated On: {{ $generatedAt }}</p>
        </div>
    </div>

    <!-- Tables Container (Side by Side) -->
    <div class="tables-wrapper">
        <!-- 1. Main Unit Table -->
        <div class="table-column">
            <div style="margin-bottom: 5px;">
                <span class="section-title" style="background-color: #93c8fdce; text-align: center;">Unit Production Details ({{ $fromDate }})</span>
                <table class="data-table main-table">
                    <thead>
                        <tr>
                            <th class="text-left">Unit</th>
                            <th>Used MC</th>
                            <th>Sewing Lines</th>
                            <th>Machine Work Hr</th>
                            <th>Received</th>
                            <th>Delivery</th>
                            <th>Delivery (kg)</th>
                            <th>Garment (g)</th>
                            <th>Balance</th>
                            <th>Approval Pending Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($unitData as $row)
                        <tr>
                            <td class="text-left">{{ $row->unit }}</td>
                            <td class="num-right">{{ number_format($row->used_mc) }}</td>
                            <td class="text-left" style="font-size:6.5px;">{{ $row->sewing_lines ?: '-' }}</td>
                            <td class="num-right">{{ number_format($row->work_hours, 2) }}</td>
                            <td class="num-right">{{ number_format($row->delivery) }}</td>
                            <td class="num-right">{{ number_format($row->received) }}</td>
                            <td class="num-right">{{ number_format($row->delivery_kg, 2) }}</td>
                            <td class="num-right">{{ $row->garment > 0 ? round($row->garment) : 0 }}</td>
                            <td class="num-right">{{ number_format($row->balance) }}</td>
                            <td class="num-right">{{ number_format($row->approval_pending) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-left">Total</th>
                            <td class="num-right">{{ number_format($totals['used_mc']) }}</td>
                            <td class="text-left">-</td>
                            <td class="num-right">{{ number_format($totals['work_hours'], 2) }}</td>
                            <td class="num-right">{{ number_format($totals['delivery']) }}</td>
                            <td class="num-right">{{ number_format($totals['received']) }}</td>
                            <td class="num-right">{{ number_format($totals['delivery_kg'], 2) }}</td>
                            <td class="num-right">{{ number_format(round($avgGarment)) }}</td>
                            <td class="num-right">{{ number_format($totals['balance']) }}</td>
                            <td class="num-right">{{ number_format($totals['approval_pending']) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- 2. Current Month Data Table -->
        <div class="table-column">
            <div style="margin-bottom: 5px;">
                <span class="section-title" style="background-color: #93c8fdce; text-align: center;">Current Month Production Data ({{ $currentMonthStart }} to {{ $today }})</span>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="text-left">Unit</th>
                            <th>Used MC</th>
                            <th>Sewing Lines</th>
                            <th>Machine Work Hr</th>
                            <th>Avg Work Hr</th>
                            <th>Received</th>
                            <th>Avg Recv</th>
                            <th>Delivery</th>
                            <th>Avg Delv</th>
                            <th>Delivery (kg)</th>
                            <th>Garment (g)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthUnitData as $row)
                        <tr>
                            <td class="text-left">{{ $row->unit }}</td>
                            <td class="num-right">{{ number_format($row->used_mc) }}</td>
                            <td class="text-left" style="font-size:6.5px;">{{ $row->sewing_lines ?: '-' }}</td>
                            <td class="num-right">{{ number_format($row->work_hours, 2) }}</td>
                            <td class="num-right">{{ number_format($row->avg_work_hours, 2) }}</td>
                            <td class="num-right">{{ number_format($row->received) }}</td>
                            <td class="num-right">{{ number_format(round($row->avg_recv)) }}</td>
                            <td class="num-right">{{ number_format($row->delivery) }}</td>
                            <td class="num-right">{{ number_format(round($row->avg_delv)) }}</td>
                            <td class="num-right">{{ number_format($row->delivery_kg, 2) }}</td>
                            <td class="num-right">{{ $row->garment > 0 ? round($row->garment) : 0 }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-left">Totals/Avg</th>
                            <td class="num-right">{{ number_format($monthTotals['used_mc']) }}</td>
                            <td class="text-left">-</td>
                            <td class="num-right">{{ number_format($monthTotals['work_hours'], 2) }}</td>
                            <td class="num-right">{{ number_format($monthTotals['avg_work_hours'], 2) }}</td>
                            <td class="num-right">{{ number_format($monthTotals['received']) }}</td>
                            <td class="num-right">{{ number_format(round($monthTotals['avg_recv'])) }}</td>
                            <td class="num-right">{{ number_format($monthTotals['delivery']) }}</td>
                            <td class="num-right">{{ number_format(round($monthTotals['avg_delv'])) }}</td>
                            <td class="num-right">{{ number_format($monthTotals['delivery_kg'], 2) }}</td>
                            <td class="num-right">{{ number_format(round($monthAvgGarment)) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="footer">
        This report is system generated on {{ $generatedAt }} | For internal use only | TUSUKA
    </div>
</body>

</html>