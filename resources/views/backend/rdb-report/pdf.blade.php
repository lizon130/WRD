<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RDB Report</title>
    <style>
        @page {
            margin: 8mm 9mm 13mm 9mm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            color: #1f2937;
        }

        /* ---------- Header ---------- */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
        }

        .header-table td {
            vertical-align: middle;
            padding: 0;
        }

        .header-logo {
            width: 95px;
        }

        .header-logo img {
            height: 44px;
            width: auto;
        }

        .header-center {
            text-align: center;
        }

        .header-center h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #1f3b73;
        }

        .header-center p {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #4b5563;
        }

        .header-right {
            width: 95px;
            text-align: right;
            font-size: 8.5px;
            color: #6b7280;
            line-height: 1.6;
        }

        .title-bar {
            background-color: #1f3b73;
            color: #ffffff;
            text-align: center;
            font-size: 11.5px;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 5px 4px;
            margin-bottom: 6px;
        }

        /* ---------- Table ---------- */
        table.data-table {
            border-collapse: collapse;
            font-size: 9px;
            width: 100%;
        }

        .data-table {
            border: 1px solid #6b7280;
        }

        .data-table th,
        .data-table td {
            border: 0.6px solid #94a3b8;
            padding: 3px 4px;
            text-align: right;
            vertical-align: middle;
            white-space: nowrap;
        }

        .data-table thead th {
            background-color: #e2e8f0;
            font-weight: bold;
            text-align: center;
            font-size: 9.5px;
        }

        .data-table thead tr.group-row th {
            background-color: #1f3b73;
            color: #fff;
            font-size: 10.5px;
            letter-spacing: 0.6px;
            padding: 5px 4px;
        }

        .data-table th.date-head,
        .data-table td.date-cell {
            text-align: center;
            font-weight: bold;
            background-color: #eef3fb;
        }

        .data-table .group-sep {
            border-left: 1.2px solid #475569;
        }

        .data-table .total-col {
            font-weight: bold;
            background-color: #f1f5f9;
        }

        .data-table tbody tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .data-table tbody tr:nth-child(even) td.date-cell {
            background-color: #e3ecf9;
        }

        .data-table tfoot td {
            background-color: #cbd5e1;
            font-weight: bold;
            font-size: 9.5px;
            border-top: 1.2px solid #475569;
            padding: 5px 5px;
        }

        .data-table tfoot td.date-cell {
            background-color: #b8c4d6;
        }

        .data-table tfoot tr.avg-row td {
            background-color: #e2e8f0;
        }

        .data-table .avg-col {
            background-color: #eef3fb;
            color: #1f3b73;
        }

        /* ---------- Yearly Table (roomier layout) ---------- */
        .yearly-table {
            font-size: 10px;
            margin-top: 4px;
        }

        .yearly-table th,
        .yearly-table td {
            padding: 6px 4px;
        }

        .yearly-table thead tr.group-row th {
            font-size: 11px;
            padding: 7px 4px;
        }

        .yearly-table tfoot td {
            font-size: 10px;
            padding: 7px 5px;
        }

        /* ---------- Footer ---------- */
        .footer {
            position: fixed;
            bottom: 4mm;
            left: 0;
            right: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #9ca3af;
            border-top: 0.5px solid #d1d5db;
            padding-top: 2px;
        }
    </style>
</head>

<body>

    @php
        $isYearly = ($data['type'] ?? 'monthly') === 'yearly';
    @endphp

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="{{ public_path('assets/img/logo.jpeg') }}" alt="Tusuka">
            </td>
            <td class="header-center">
                <h2>TUSUKA PROCESSING &amp; WASHING LTD.</h2>
                @if ($isYearly)
                    <p>Yearly Receive &amp; Delivery Report: {{ $data['year_label'] }} ({{ $data['date_range'] }})</p>
                @else
                    <p>Receive, Delivery &amp; Balance Status Report: {{ $data['month_label'] }} ({{ $data['date_range'] }})</p>
                @endif
            </td>
            <td class="header-right">
                @if ($isYearly)
                    Period:<br>{{ $data['months'] }} Months
                @else
                    Period:<br>{{ $data['days'] }} Days
                @endif
            </td>
        </tr>
    </table>

    @php
        $unitKeys = ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL', 'total'];
        $unitLabels = ['Unit-01', 'Unit-02', 'Unit-03', 'Unit-04', 'Unit-05', 'TWL', 'Total'];
    @endphp

    @if ($isYearly)
        <!-- RDB Yearly Table -->
        <table class="data-table yearly-table">
            <thead>
                <tr class="group-row">
                    <th class="date-head" rowspan="2" style="width: 10%;">Month</th>
                    <th colspan="8" style="width: 44%;">Receive</th>
                    <th class="group-sep" colspan="8" style="width: 46%;">Delivery</th>
                </tr>
                <tr>
                    @foreach ($unitLabels as $i => $unitLabel)
                        <th style="width: 5.3%;">{{ $unitLabel }}</th>
                    @endforeach
                    <th style="width: 6.5%;">Avg. Rcv/Day</th>
                    @foreach ($unitLabels as $i => $unitLabel)
                        <th @if ($i === 0) class="group-sep" @endif style="width: 5.3%;">{{ $unitLabel }}</th>
                    @endforeach
                    <th style="width: 6.5%;">Avg. Del/Day</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['rows'] as $row)
                    <tr>
                        <td class="date-cell">{{ $row['month_label'] }}</td>
                        @foreach (['receive', 'delivery'] as $gi => $groupKey)
                            @foreach ($unitKeys as $unitKey)
                                <td class="{{ $gi > 0 ? 'group-sep ' : '' }}{{ $unitKey === 'total' ? 'total-col' : '' }}">
                                    {{ number_format((float) ($row[$groupKey][$unitKey] ?? 0)) }}
                                </td>
                            @endforeach
                            <td class="avg-col">
                                {{ number_format((float) ($row[$groupKey === 'receive' ? 'avg_receive_per_day' : 'avg_delivery_per_day'])) }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="date-cell">GRAND TOTAL</td>
                    @foreach (['receive', 'delivery'] as $gi => $groupKey)
                        @foreach ($unitKeys as $unitKey)
                            <td @if ($gi > 0) class="group-sep" @endif>
                                {{ number_format((float) ($data['grand_total'][$groupKey][$unitKey] ?? 0)) }}
                            </td>
                        @endforeach
                        <td>
                            {{ number_format((float) ($data['grand_total'][$groupKey === 'receive' ? 'avg_receive_per_day' : 'avg_delivery_per_day'])) }}
                        </td>
                    @endforeach
                </tr>
                <tr class="avg-row">
                    <td class="date-cell">Avg / Month</td>
                    @foreach (['receive', 'delivery'] as $gi => $groupKey)
                        @foreach ($unitKeys as $unitKey)
                            <td @if ($gi > 0) class="group-sep" @endif>
                                {{ number_format((float) ($data['avg_per_month'][$groupKey][$unitKey] ?? 0)) }}
                            </td>
                        @endforeach
                        <td>
                            {{ number_format((float) ($data['avg_per_month'][$groupKey === 'receive' ? 'avg_receive_per_day' : 'avg_delivery_per_day'])) }}
                        </td>
                    @endforeach
                </tr>
            </tfoot>
        </table>

        @if (count($data['rows']) === 0)
            <p style="text-align: center; color: #999; margin-top: 15px;">No data found for this year.</p>
        @endif
    @else
        @php
            $groups = ['receive' => 'Receive', 'delivery' => 'Delivery', 'in_hand' => 'In Hand Balance'];
        @endphp

        <!-- RDB Table -->
        <table class="data-table">
            <thead>
                <tr class="group-row">
                    <th class="date-head" rowspan="2">Date</th>
                    @foreach ($groups as $label)
                        <th @if (!$loop->first) class="group-sep" @endif colspan="7">{{ $label }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($groups as $groupKey => $label)
                        @foreach ($unitLabels as $i => $unitLabel)
                            <th @if (!$loop->parent->first) class="group-sep" @endif>{{ $unitLabel }}</th>
                        @endforeach
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data['rows'] as $row)
                    <tr>
                        <td class="date-cell">{{ $row['date'] }}</td>
                        @foreach (['receive', 'delivery', 'in_hand'] as $gi => $groupKey)
                            @foreach ($unitKeys as $unitKey)
                                <td class="{{ $gi > 0 ? 'group-sep ' : '' }}{{ $unitKey === 'total' ? 'total-col' : '' }}">
                                    {{ number_format((float) ($row[$groupKey][$unitKey] ?? 0)) }}
                                </td>
                            @endforeach
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="date-cell">Total</td>
                    @foreach (['receive', 'delivery', 'in_hand'] as $gi => $groupKey)
                        @foreach ($unitKeys as $unitKey)
                            <td @if ($gi > 0) class="group-sep" @endif>
                                {{ $groupKey === 'in_hand' ? '' : number_format((float) ($data['totals'][$groupKey][$unitKey] ?? 0)) }}
                            </td>
                        @endforeach
                    @endforeach
                </tr>
                <tr class="avg-row">
                    <td class="date-cell">Avg</td>
                    @foreach (['receive', 'delivery', 'in_hand'] as $gi => $groupKey)
                        @foreach ($unitKeys as $unitKey)
                            <td @if ($gi > 0) class="group-sep" @endif>
                                {{ $groupKey === 'in_hand' ? '' : number_format((float) ($data['averages'][$groupKey][$unitKey] ?? 0)) }}
                            </td>
                        @endforeach
                    @endforeach
                </tr>
            </tfoot>
        </table>

        @if (count($data['rows']) === 0)
            <p style="text-align: center; color: #999; margin-top: 15px;">No data found for this month.</p>
        @endif
    @endif

    <div class="footer">
        Generated on {{ now()->format('d-M-Y H:i:s') }} | TUSUKA PROCESSING &amp; WASHING LTD.
    </div>
</body>

</html>
