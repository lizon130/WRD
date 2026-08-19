<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wash Report Dashboard</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8px;
            margin: 0;
            padding: 0;
            color: #222;
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
            margin-bottom: 4px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }

        .header h2 {
            margin: 0;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px 0 0 0;
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
            padding: 3px 4px;
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
            text-align: right !important;
        }

        /* Center alignment for percentages */
        .percent-center {
            text-align: center !important;
        }

        /* Left alignment for text */
        .text-left {
            text-align: left !important;
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
            text-align: left;
            font-weight: bold;
        }

        .main-table td:nth-child(4) {
            max-width: 30px;
            overflow: hidden;
            white-space: nowrap;
        }

        .main-table td:last-child {
            max-width: 40px;
            text-align: left;
            font-size: 6.5px;
        }

        .footer {
            position: fixed;
            bottom: 3px;
            width: 100%;
            text-align: center;
            font-size: 6.5px;
            color: #888;
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
            <h2>WASH REPORT-DAILY</h2>
            <p>Period: {{ \Carbon\Carbon::parse($fromDate)->format('d-M-Y') }} to
                {{ \Carbon\Carbon::parse($toDate)->format('d-M-Y') }}
            </p>
        </div>
    </div>


    <!-- 1. Main Unit Table -->
    <div style="margin-bottom: 5px;">
        <span class="section-title" style="background-color: #93c8fdce; text-align: center;">Unit Production
            Details</span>
        <table class="data-table main-table">
            <thead>
                <tr>
                    <th class="text-left">Unit</th>
                    <th>Used<br>MC</th>
                    <th>Cap<br>(kg)</th>
                    <th>Sew<br>Lines</th>
                    <th>Dir</th>
                    <th>Ind</th>
                    <th>Total</th>
                    <th>W.Hr</th>
                    <th>Recv</th>
                    <th>Delv</th>
                    <th>Gmt<br>(g)</th>
                    <th>Forcast<br>Tgt</th>
                    <th>Dev</th>
                    <th class="percent-center">Dev%</th>
                    <th class="percent-center">RW%</th>
                    <th>In<br>Hand</th>
                    <th>1st<br>Wash</th>
                    <th>Final<br>Wash</th>
                    <th class="percent-center">Wash<br>WIP %</th>
                    <th>Acid<br>Wash</th>
                    <th>Rewash</th>
                    <th>Rework<br>Dry</th>
                    <th class="text-left">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($unitData as $u)
                <tr>
                    <td class="text-left">{{ $u->unit }}</td>
                    <td class="num-right">{{ $u->used_mc ?? $u->machines }}</td>
                    <td class="num-right">{{ number_format(round($u->used_capacity_kg ?? $u->capacity_kg)) }}</td>
                    <td class="text-left" style="font-size:5.5px;">{{ $u->sewing_lines }}</td>
                    <td class="num-right">{{ $u->direct }}</td>
                    <td class="num-right">{{ $u->indirect }}</td>
                    <td class="num-right">{{ $u->total }}</td>
                    <td class="num-right">{{ $u->work_hours }}</td>
                    <td class="num-right">{{ $u->delivery }}</td>
                    <td class="num-right">{{ $u->received }}</td>
                    <td class="num-right">{{ $u->garment }}</td>
                    <td class="num-right">{{ $u->forecast_target }}</td>
                    <td class="num-right">{!! $u->deviation !!}</td>
                    <td class="percent-center">{!! $u->deviation_percent !!}</td>
                    <td class="percent-center">{{ $u->rewash_percent }}</td>
                    <td class="num-right">{{ $u->in_hand_balance }}</td>
                    <td class="num-right">{{ $u->first_wash_qty }}</td>
                    <td class="num-right">{{ $u->final_wash_qty }}</td>
                    <td class="percent-center">{{ $u->wash_ratio }}</td>
                    <td class="num-right">{{ $u->acid_wash_qty }}</td>
                    <td class="num-right">{{ $u->rewash_qty }}</td>
                    <td class="num-right">{{ $u->rework_dry_proc }}</td>
                    <td class="text-left" style="font-size:5px;">
                        {{ \Illuminate\Support\Str::limit($u->remarks, 45) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                // Calculate totals DIRECTLY from raw unitData values (remove commas first)
                $totalFirstWashSum = 0;
                $totalFinalWashSum = 0;
                $totalRewashQtySum = 0;
                $totalReceivedSum = 0;

                foreach ($unitData as $unit) {
                if (is_array($unit)) {
                $totalFirstWashSum += (float) str_replace(',', '', $unit['first_wash_qty'] ?? 0);
                $totalFinalWashSum += (float) str_replace(',', '', $unit['final_wash_qty'] ?? 0);
                $totalRewashQtySum += (float) str_replace(',', '', $unit['rewash_qty'] ?? 0);
                $totalReceivedSum += (float) str_replace(',', '', $unit['received'] ?? 0);
                } else {
                $totalFirstWashSum += (float) str_replace(',', '', $unit->first_wash_qty ?? 0);
                $totalFinalWashSum += (float) str_replace(',', '', $unit->final_wash_qty ?? 0);
                $totalRewashQtySum += (float) str_replace(',', '', $unit->rewash_qty ?? 0);
                $totalReceivedSum += (float) str_replace(',', '', $unit->received ?? 0);
                }
                }

                $totalWashWipPercent = 0;
                if ($totalFirstWashSum > 0) {
                $totalWashWipPercent = ($totalFinalWashSum / $totalFirstWashSum) * 100;
                }

                $totalRewashPercent = 0;
                if ($totalReceivedSum > 0) {
                $totalRewashPercent = ($totalRewashQtySum / $totalReceivedSum) * 100;
                }
                @endphp
                <tr>
                    <th class="text-left">Total</th>
                    <td class="num-right">{{ number_format($unitTotals['machines']) }}</td>
                    <td class="num-right">{{ number_format($unitTotals['capacity_kg']) }}</td>
                    <td class="text-left">-</td>
                    <td class="num-right">{{ number_format($unitTotals['direct']) }}</td>
                    <td class="num-right">{{ number_format($unitTotals['indirect']) }}</td>
                    <td class="num-right">{{ number_format($unitTotals['total']) }}</td>
                    <td class="num-right">{{ number_format($unitTotals['work_hours'] / 6, 1) }}</td>
                    <td class="num-right">{{ number_format($unitTotals['delivery']) }}</td>
                    <td class="num-right">{{ number_format($unitTotals['received']) }}</td>
                    <td class="num-right">-</td>
                    <td class="num-right">{{ number_format($unitTotals['forecast_target']) }}</td>
                    <td class="num-right">{{ number_format($unitTotals['deviation']) }}</td>
                    <td class="percent-center">{{ number_format($unitTotals['deviation_percent'], 2) }}%</td>
                    <td class="percent-center">{{ number_format($totalRewashPercent, 2) }}%</td>
                    <td class="num-right">{{ number_format($unitTotals['in_hand_balance'] ?? 0) }}</td>
                    <td class="num-right">{{ number_format($totalFirstWashSum) }}</td>
                    <td class="num-right">{{ number_format($totalFinalWashSum) }}</td>
                    <td class="percent-center">{{ number_format($totalWashWipPercent, 2) }}%</td>
                    <td class="num-right">{{ number_format($unitTotals['acid_wash_qty']) }}</td>
                    <td class="num-right">{{ number_format($unitTotals['rewash_qty']) }}</td>
                    <td class="num-right">{{ number_format($unitTotals['rework_dry_proc']) }}</td>
                    <td class="text-left">-</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- 2. Dry Process Tables -->
    <!-- 2. Dry Process Tables -->
    <table class="layout-table" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="50%" style="padding-right: 3px;">
                <span class="section-title" style="background-color: #93c8fdce; text-align: center;">1st Dry
                    Process</span>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="text-left">Plant</th>
                            <th colspan="2">Whisker</th>
                            <th colspan="2">Hand Brush</th>
                            <th colspan="4">1st Dry Final</th>
                            <th rowspan="2">Dev</th>
                            <th rowspan="2">Def</th>
                            <th rowspan="2">Man</th>
                            <th rowspan="2">W.Hr</th>
                            <th rowspan="2">SMV</th>
                            <th rowspan="2">Remarks</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Tgt</th>
                            <th>Prd</th>
                            <th>Tgt</th>
                            <th>Prd</th>
                            <th>Tgt</th>
                            <th>Prd</th>
                            <th>Recv</th>
                            <th>Delv</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($firstDryData as $d)
                        <tr>
                            <td class="text-left">{{ $d->plant }}</td>
                            <td class="num-right">{{ $d->whisker_target }}</td>
                            <td class="num-right">{{ $d->whisker_production }}</td>
                            <td class="num-right">{{ $d->handbrush_target }}</td>
                            <td class="num-right">{{ $d->handbrush_production }}</td>
                            <td class="num-right">{{ $d->firstdryfinal_target }}</td>
                            <td class="num-right">{{ $d->firstdryfinal_production }}</td>
                            <td class="num-right">{{ $d->firstdryfinal_receive ?? 0 }}</td>
                            <td class="num-right">{{ $d->firstdryfinal_delivery ?? 0 }}</td>
                            <td class="num-right">{!! $d->deviation !!}</td>
                            <td class="num-right">{{ $d->defect_qty }}</td>
                            <td class="num-right">{{ $d->manPower }}</td>
                            <td class="num-right">{{ $d->workingHr }}</td>
                            <td class="num-right">{{ $d->smv }}</td>
                            <td class="text-left" style="font-size:6.5px; max-width: 80px;">
                                {{ \Illuminate\Support\Str::limit($d->remarks ?? '-', 20) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-left">Total</th>
                            <td class="num-right">{{ number_format($firstDryTotals['whisker_target'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($firstDryTotals['whisker_prod'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($firstDryTotals['handbrush_target'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($firstDryTotals['handbrush_prod'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($firstDryTotals['target'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($firstDryTotals['prod'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($firstDryTotals['receive'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($firstDryTotals['delivery'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format(round($firstDryTotals['deviation'] ?? 0)) }}</td>
                            <td class="num-right">{{ number_format($firstDryTotals['defect'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($firstDryTotals['manpower'] ?? 0) }}</td>
                            <td class="num-right">-</td>
                            <td class="num-right">-</td>
                            <td class="text-left">-</td>
                        </tr>
                    </tfoot>
                </table>
            </td>

            <td width="50%" style="padding-left: 3px;">
                <span class="section-title" style="background-color: #93c8fdce; text-align: center;">2nd Dry
                    Process</span>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="text-left">Plant</th>
                            <th colspan="2">Laser</th>
                            <th colspan="2">PP Spray</th>
                            <th colspan="4">2nd Dry Final</th>
                            <th rowspan="2">Dev</th>
                            <th rowspan="2">Def</th>
                            <th rowspan="2">Man</th>
                            <th rowspan="2">W.Hr</th>
                            <th rowspan="2">SMV</th>
                            <th rowspan="2">Remarks</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Tgt</th>
                            <th>Prd</th>
                            <th>Tgt</th>
                            <th>Prd</th>
                            <th>Tgt</th>
                            <th>Prd</th>
                            <th>Recv</th>
                            <th>Delv</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($secondDryData as $d)
                        <tr>
                            <td class="text-left">{{ $d->plant }}</td>
                            <td class="num-right">{{ $d->laser_target }}</td>
                            <td class="num-right">{{ $d->laser_production }}</td>
                            <td class="num-right">{{ $d->ppspray_target }}</td>
                            <td class="num-right">{{ $d->ppspray_production }}</td>
                            <td class="num-right">{{ $d->seconddryfinal_target }}</td>
                            <td class="num-right">{{ $d->seconddryfinal_production }}</td>
                            <td class="num-right">{{ $d->seconddryfinal_receive ?? 0 }}</td>
                            <td class="num-right">{{ $d->seconddryfinal_delivery ?? 0 }}</td>
                            <td class="num-right">{!! $d->deviation !!}</td>
                            <td class="num-right">{{ $d->defect_qty }}</td>
                            <td class="num-right">{{ $d->manPower }}</td>
                            <td class="num-right">{{ $d->workingHr }}</td>
                            <td class="num-right">{{ $d->smv }}</td>
                            <td class="text-left" style="font-size:6.5px; max-width: 80px;">
                                {{ \Illuminate\Support\Str::limit($d->remarks ?? '-', 20) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-left">Total</th>
                            <td class="num-right">{{ number_format($secondDryTotals['laser_target'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($secondDryTotals['laser_prod'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($secondDryTotals['ppspray_target'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($secondDryTotals['ppspray_prod'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($secondDryTotals['target'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($secondDryTotals['prod'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($secondDryTotals['receive'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($secondDryTotals['delivery'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format(round($secondDryTotals['deviation'] ?? 0)) }}</td>
                            <td class="num-right">{{ number_format($secondDryTotals['defect'] ?? 0) }}</td>
                            <td class="num-right">{{ number_format($secondDryTotals['manpower'] ?? 0) }}</td>
                            <td class="num-right">-</td>
                            <td class="num-right">-</td>
                            <td class="text-left">-</td>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
    </table>

    <!-- 3. Transfer & Dryer Tables -->
    <table class="layout-table" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="50%" style="padding-right: 3px;">
                <span class="section-title" style="background-color: #93c8fdce; text-align: center;">Machine Transfer
                    Data</span>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-left">Unit</th>
                            <th colspan="2">Machine</th>
                            <th rowspan="2">Trans In</th>
                            <th rowspan="2">Trans Out</th>
                            <th colspan="2">Target</th>
                            <th colspan="2">M Cap. pcs</th>
                            <th colspan="2">M Cap.kg</th>
                        </tr>
                        <tr>
                            <th>Exist</th>
                            <th>Used</th>
                            <th>Exist</th>
                            <th>Used</th>
                            <th>Exist</th>
                            <th>Used</th>
                            <th>Exist</th>
                            <th>Used</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transferData as $t)
                        <tr>
                            <td class="text-left fw-bold">{{ strip_tags($t->unit) }}</td>
                            <td class="num-right">{{ $t->existing_mc }}</td>
                            <td class="num-right">{{ $t->used_mc }}</td>
                            <td class="text-left" style="font-size:5px;">
                                {{ $t->transfer_in_details_pdf ?? '-' }}
                            </td>
                            <td class="text-left" style="font-size:5px;">
                                {{ $t->transfer_out_details_pdf ?? '-' }}
                            </td>
                            <td class="num-right">{{ $t->base_mg_target }}</td>
                            <td class="num-right">{!! $t->current_mg_target !!}</td>
                            <td class="num-right">{{ $t->base_capacity_pieces }}</td>
                            <td class="num-right">{!! $t->current_capacity_pieces !!}</td>
                            <td class="num-right">{{ $t->base_capacity_kg }}</td>
                            <td class="num-right">{!! $t->current_capacity_kg !!}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-left">Total</th>
                            <td class="num-right">{{ number_format($transferTotals['existing_mc']) }}</td>
                            <td class="num-right">{{ number_format($transferTotals['used_mc']) }}</td>
                            <td class="text-left">-</td>
                            <td class="text-left">-</td>
                            <td class="num-right">-</td>
                            <td class="num-right">{{ number_format($transferTotals['current_mg']) }}</td>
                            <td class="num-right">-</td>
                            <td class="num-right">{{ number_format($transferTotals['current_pcs']) }}</td>
                            <td class="num-right">-</td>
                            <td class="num-right">{{ number_format($transferTotals['current_kg']) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </td>

            <td width="50%" style="padding-left: 3px;">
                <span class="section-title" style="background-color: #93c8fdce; text-align: center;">Dryer Data</span>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="text-left">Unit</th>
                            <th># Dryer</th>
                            {{-- <th>Avg<br>Batch</th> --}}
                            {{-- <th>Avg<br>Time</th> --}}
                            {{-- <th>Capacity</th> --}}
                            <th>1st Wash</th>
                            <th>Cold</th>
                            <th>Mesurement<br>Corr</th>
                            <th>Final Wash</th>
                            <th>Deviation</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dryerData as $d)
                        <tr>
                            <td class="text-left">{{ $d->unit }}</td>
                            <td class="num-right">{{ number_format($d->num_dryer) }}</td>
                            {{-- <td class="num-right">{{ number_format($d->avg_batch) }}
            </td> --}}
            {{-- <td class="num-right">{{ number_format($d->avg_dryer_time) }}</td> --}}
            {{-- <td class="num-right">{{ number_format($d->capacity) }}</td> --}}
            <td class="num-right">{{ number_format($d->first_wash_dryer) }}</td>
            <td class="num-right">{{ number_format($d->cold_dryer) }}</td>
            <td class="num-right">{{ number_format($d->measurement_correction) }}</td>
            <td class="num-right">{{ number_format($d->final_wash_dryer) }}</td>
            <td class="num-right">{!! $d->deviation !!}</td>
            <td class="num-right">{{ number_format($d->total_dryer) }}</td>
        </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th class="text-left">Total</th>
                <td class="num-right">{{ number_format($dryerTotals['num_dryer']) }}</td>
                {{-- <td class="num-right">-</td> --}}
                {{-- <td class="num-right">-</td> --}}
                {{-- <td class="num-right">{{ number_format($dryerTotals['capacity'] ?? 0) }}</td> --}}
                <td class="num-right">{{ number_format($dryerTotals['first_wash']) }}</td>
                <td class="num-right">{{ number_format($dryerTotals['cold']) }}</td>
                <td class="num-right">{{ number_format($dryerTotals['meas']) }}</td>
                <td class="num-right">{{ number_format($dryerTotals['final_wash']) }}</td>
                <td class="num-right">{{ number_format($dryerTotals['deviation']) }}</td>
                <td class="num-right">{{ number_format($dryerTotals['total']) }}</td>
            </tr>
        </tfoot>
    </table>
    </td>
    </tr>
    </table>

    <!-- 4. CAPP Chart -->
    <div style="width: 100%; margin-top: 5px; text-align: center; page-break-inside: avoid; page-break-before: auto;">
        @if (isset($chartPath) && file_exists($chartPath))
        <div style="text-align: center; margin: 0 auto; width: 100%; page-break-inside: avoid;">
            <img src="{{ $chartPath }}" alt="CAPP Chart"
                style="max-width: 100%; height: auto; max-height: 180px; width: auto; display: block; margin: 0 auto; border: 1px solid #ccc; image-rendering: auto;">
        </div>
        @else
        <table class="data-table"
            style="width: 70%; margin: 0 auto; border: 0.5px solid #999; page-break-inside: avoid;">
            <tr>
                <td style="padding: 15px; text-align: center; color: #999;">
                    CAPP Data Unavailable
                </td>
            </tr>
        </table>
        @endif
    </div>

    <div class="footer">
        Generated on {{ now()->format('d-M-Y H:i:s') }} | TUSUKA
    </div>
</body>

</html