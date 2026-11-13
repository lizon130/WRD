<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wash Report Dashboard</title>
    <style>
        @page {
            margin: 6mm 7mm 5mm 7mm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 0;
            color: #102037;
            background: #e9f4fc;
        }

        table {
            page-break-inside: avoid;
            border-collapse: collapse;
            font-size: 8.5px;
        }

        .page-break-avoid {
            page-break-inside: avoid;
            page-break-before: auto;
            page-break-after: auto;
        }

        /* ============ HERO HEADER (ftwash.png full cover + navy veil watermark) ============ */
        .hero-wrap {
            width: 100%;
            position: relative;
            border-bottom: 2px solid #62eaff;
            margin-bottom: 4px;
            page-break-inside: avoid;
        }

        /* Full-cover background image */
        .hero-cover {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 58px;
        }

        /* Navy veil on top of the image => light effect, keeps text readable */
        .hero-veil {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 58px;
            background-color: #052c54;
            opacity: 0.45;
        }

        .hero-t {
            width: 100%;
            border-collapse: collapse;
            position: relative;
        }

        .hc {
            height: 58px;
            padding: 0;
            border: 0;
            vertical-align: middle;
        }

        .h-mid {
            text-align: center;
        }

        /* Title block - perfectly centered over the whole header */
        .hero-center {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 58px;
            text-align: center;
            padding-top: 9px;
        }

        .hero-title {
            text-align: center;
            color: #ffffff;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1.5px;
            margin: 0;
        }

        .hero-sub {
            text-align: center;
            padding-top: 2px;
        }

        .period-pill {
            display: inline-block;
            border: 1px solid #62eaff;
            background-color: #124970;
            color: #ffffff;
            border-radius: 9px;
            font-size: 9px;
            padding: 2px 16px;
            font-weight: bold;
        }

        .washer {
            position: absolute;
            width: 24px;
            height: 24px;
            border: 2px solid #0b476f;
            border-radius: 12px;
        }

        .washer-inner {
            position: absolute;
            top: 5px;
            left: 5px;
            width: 10px;
            height: 10px;
            border: 0;
            border-radius: 5px;
            background-color: #0b476f;
        }

        /* ============ PANELS ============ */
        .panel {
            border: 1px solid #9cc8e6;
            border-radius: 6px;
            background-color: #e2f1fc;
            padding: 3px 4px 4px;
            margin-bottom: 4px;
        }

        .ptitle {
            background-color: #0963a3;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            padding: 3px 10px;
            border-radius: 5px 5px 2px 2px;
            border-left: 4px solid #62eaff;
            margin-bottom: 3px;
            text-align: left;
        }

        /* ============ DATA TABLES ============ */
        .data-table {
            width: 100%;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #a7b9c8;
            padding: 4px 4px;
            text-align: center;
            vertical-align: middle;
            line-height: 1.3;
        }

        .data-table th {
            background-color: #d5e8f7;
            color: #07182b;
            font-weight: bold;
            font-size: 8px;
            line-height: 1.25;
        }

        .data-table tbody tr:nth-child(odd) td {
            background-color: #f3f9ff;
        }

        .data-table tbody tr:nth-child(even) td {
            background-color: #e9f4fd;
        }

        .data-table tfoot th,
        .data-table tfoot td {
            background-color: #cbe1f4;
            font-weight: bold;
            border-top: 1px solid #728ea4;
        }

        .layout-table {
            width: 100%;
            border: none;
            margin-bottom: 3px;
        }

        .layout-table td {
            vertical-align: top;
            padding: 0 3px;
            border: 0;
        }

        .chart-box {
            border: 2px solid #ffffff;
            border-radius: 6px;
            background-color: #eef7ff;
            padding: 3px 6px 4px;
        }

        /* ============ CSS 3D BAR CHARTS ============ */
        .bc-legend {
            font-size: 8px;
            font-weight: bold;
            color: #32465c;
            margin: 0 0 2px 4px;
        }

        .bc-key {
            display: inline-block;
            width: 12px;
            height: 8px;
            border-radius: 2px;
            margin: 0 3px 0 10px;
            vertical-align: middle;
        }

        .k-red {
            background-color: #d90f29;
        }

        .k-yel {
            background-color: #e3a500;
        }

        .k-grn {
            background-color: #0f9d4a;
        }

        .bc-plot {
            border-bottom: 1.5px solid #8baabd;
            padding: 0 2px 0 6px;
        }

        .bc-plot table,
        .bc-plot tr,
        .bc-plot td {
            border: 0;
            border-collapse: collapse;
        }

        .bc-group {
            vertical-align: bottom;
            text-align: center;
            padding: 0 6px;
        }

        .bc-cell {
            vertical-align: bottom;
            height: 92px;
            text-align: center;
            padding: 0 2px;
        }

        .bc-val {
            font-size: 7px;
            font-weight: bold;
            color: #141414;
            padding-bottom: 1px;
            text-align: center;
            border: 0;
        }

        /* Bars are rendered as table cells - most reliable in DomPDF */
        .barg {
            border: 0;
            padding: 0;
            border-collapse: collapse;
        }

        /* Wrapper cell for each bar column - bottom aligned + gap between bars */
        .bcw {
            vertical-align: bottom;
            padding: 0 4px;
            border: 0;
        }

        /* 3D look: lighter left edge + glossy top edge */
        .bar-red {
            background-color: #e0243a;
            border-left: 3px solid #ff969b;
            border-top: 2px solid #ff969b;
        }

        .bar-yel {
            background-color: #e8a800;
            border-left: 3px solid #ffdd66;
            border-top: 2px solid #ffdd66;
        }

        .bar-grn {
            background-color: #0fa34e;
            border-left: 3px solid #6fdda4;
            border-top: 2px solid #6fdda4;
        }

        .bc-name {
            font-size: 7.5px;
            font-weight: bold;
            color: #32465c;
            padding-top: 2px;
        }

        /* Alignment helpers (existing behaviour preserved) */
        .num-right {
            text-align: right !important;
        }

        .percent-center {
            text-align: center !important;
        }

        .text-left {
            text-align: left !important;
        }

        .text-success {
            color: #008b33;
            font-weight: bold;
        }

        .text-danger {
            color: #e92132;
            font-weight: bold;
        }

        .text-warning {
            color: #b28704;
        }

        .fw-bold {
            font-weight: bold;
        }

        .main-table td:nth-child(1) {
            text-align: left;
            font-weight: bold;
        }

        .main-table td:nth-child(4) {
            max-width: 32px;
            overflow: hidden;
            white-space: nowrap;
        }

        .main-table td:last-child {
            max-width: 45px;
            text-align: left;
            font-size: 7px;
        }

        .footer {
            position: fixed;
            bottom: 2px;
            width: 100%;
            text-align: center;
        }

        .footer-pill {
            display: inline-block;
            background-color: #477fae;
            border: 1px solid #ffffff;
            color: #ffffff;
            font-size: 8px;
            padding: 2px 18px;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <!-- ================= HERO HEADER (image full + white left fade + centered title) ================= -->
    <div class="hero-wrap page-break-avoid">
        <img class="hero-cover" src="{{ public_path('assets/img/ftwash.png') }}" alt="">
        <div class="hero-veil"></div>
        <table class="hero-t" cellpadding="0" cellspacing="0">
            <tr>
                <td class="hc" width="14%" style="background-color: #ffffff; text-align: center;">
                    <img src="{{ public_path('assets/img/logo.jpeg') }}" alt="Tusuka"
                        style="height: 38px; width: auto;">
                </td>
                <td class="hc" width="86%"></td>
            </tr>
        </table>
        {{-- Smooth white fade over the image: thin layers with decreasing opacity --}}
        @php
        $fadeOpacities = [0.90, 0.80, 0.71, 0.62, 0.54, 0.46, 0.39, 0.32, 0.26, 0.20, 0.15, 0.10];
        $fadeLeft = 14.0;
        @endphp
        @foreach ($fadeOpacities as $fadeOpacity)
        <div style="position: absolute; top: 0; left: {{ $fadeLeft }}%; width: 2.4%; height: 58px; background-color: #ffffff; opacity: {{ $fadeOpacity }};"></div>
        @php $fadeLeft += 2.4; @endphp
        @endforeach
        <div class="hero-center">
            <div class="hero-title">WASH REPORT - DAILY</div>
            <div class="hero-sub">
                <span class="period-pill">Period:
                    {{ \Carbon\Carbon::parse($fromDate)->format('d-M-Y') }} to
                    {{ \Carbon\Carbon::parse($toDate)->format('d-M-Y') }}</span>
            </div>
        </div>
    </div>

    <!-- ================= 1. MAIN UNIT TABLE ================= -->
    <div class="panel page-break-avoid">
        <div class="ptitle">Unit Production Details</div>
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
                    <td class="text-left" style="font-size:6.5px;">{{ $u->sewing_lines }}</td>
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
                    <td class="text-left" style="font-size:6.5px;">
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

    <!-- ================= 2. DRY PROCESS TABLES ================= -->
    <table class="layout-table" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="50%" style="padding-right: 3px;">
                <div class="panel">
                    <div class="ptitle">1st Dry Process</div>
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
                                <td class="text-left" style="font-size:7px; max-width: 80px;">
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
                </div>
            </td>

            <td width="50%" style="padding-left: 3px;">
                <div class="panel">
                    <div class="ptitle">2nd Dry Process</div>
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
                                <td class="text-left" style="font-size:7px; max-width: 80px;">
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
                </div>
            </td>
        </tr>
    </table>

    <!-- ================= 3. TRANSFER & DRYER TABLES ================= -->
    <table class="layout-table" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="50%" style="padding-right: 3px;">
                <div class="panel">
                    <div class="ptitle">Machine Transfer Data</div>
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
                                <td class="text-left" style="font-size:6.5px;">
                                    {{ $t->transfer_in_details_pdf ?? '-' }}
                                </td>
                                <td class="text-left" style="font-size:6.5px;">
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
                </div>
            </td>

            <td width="50%" style="padding-left: 3px;">
                <div class="panel">
                    <div class="ptitle">Dryer Data</div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="text-left">Unit</th>
                                <th># Dryer</th>
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
                                <td class="num-right">{{ number_format($dryerTotals['first_wash']) }}</td>
                                <td class="num-right">{{ number_format($dryerTotals['cold']) }}</td>
                                <td class="num-right">{{ number_format($dryerTotals['meas']) }}</td>
                                <td class="num-right">{{ number_format($dryerTotals['final_wash']) }}</td>
                                <td class="num-right">{{ number_format($dryerTotals['deviation']) }}</td>
                                <td class="num-right">{{ number_format($dryerTotals['total']) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- ================= 4. MACHINE PERFORMANCE + ALL UNITS SUMMARY (CSS 3D bars) ================= -->
    <div style="width: 100%; margin-top: 2px; page-break-inside: avoid; page-break-before: auto;">
        @if (count($perfLabels ?? []) > 0)
        <table style="width: 100%; border: 0; border-collapse: collapse; page-break-inside: avoid;">
            <tr>
                <td style="width: 76%; vertical-align: top; padding: 0 5px 0 0; border: 0;">
                    <div class="panel" style="margin-bottom: 0;">
                        <div class="ptitle">Machine Performance</div>
                        <div class="chart-box">
                            <div class="bc-legend"><span class="bc-key k-red"></span>Downtime<span
                                    class="bc-key k-yel"></span>Idletime<span class="bc-key k-grn"></span>Uptime</div>
                            <div class="bc-plot">
                                <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                    <tr>
                                        @foreach ($perfLabels as $i => $perfLabel)
                                        <td class="bc-group">
                                            <table cellpadding="0" cellspacing="0" class="barg" style="margin: 0 auto;">
                                                <tr>
                                                    <td class="bcw">
                                                        <table cellpadding="0" cellspacing="0" class="barg">
                                                            <tr>
                                                                <td class="bc-val">{{ round($perfDowntime[$i] ?? 0, 1) }}%
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="barg bar-red" width="20"
                                                                    style="height: {{ max(1, round(($perfDowntime[$i] ?? 0) / 70 * 92)) }}px;">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="bcw">
                                                        <table cellpadding="0" cellspacing="0" class="barg">
                                                            <tr>
                                                                <td class="bc-val">{{ round($perfIdletime[$i] ?? 0, 1) }}%
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="barg bar-yel" width="20"
                                                                    style="height: {{ max(1, round(($perfIdletime[$i] ?? 0) / 70 * 92)) }}px;">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="bcw">
                                                        <table cellpadding="0" cellspacing="0" class="barg">
                                                            <tr>
                                                                <td class="bc-val">{{ round($perfUptime[$i] ?? 0, 1) }}%
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="barg bar-grn" width="20"
                                                                    style="height: {{ max(1, round(($perfUptime[$i] ?? 0) / 70 * 92)) }}px;">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <div class="bc-name">{{ $perfLabel }}</div>
                                        </td>
                                        @endforeach
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </td>
                <td style="width: 24%; vertical-align: top; padding: 0 0 0 5px; border: 0;">
                    @if (!empty($machinePerfTotals))
                    <div class="panel" style="margin-bottom: 0;">
                        <div class="ptitle">All Units Summary ({{ $machinePerfTotals['units'] }} Units)</div>
                        <div class="chart-box">
                            <div class="bc-legend"><span class="bc-key k-red"></span>Down<span
                                    class="bc-key k-yel"></span>Idle<span class="bc-key k-grn"></span>Up</div>
                            <div class="bc-plot">
                                <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                    <tr>
                                        <td class="bc-group">
                                            <table cellpadding="0" cellspacing="0" class="barg" style="margin: 0 auto;">
                                                <tr>
                                                    <td class="bcw">
                                                        <table cellpadding="0" cellspacing="0" class="barg">
                                                            <tr>
                                                                <td class="bc-val">{{ $machinePerfTotals['downtime'] }}%
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="barg bar-red" width="36"
                                                                    style="height: {{ max(1, round($machinePerfTotals['downtime'] / 70 * 92)) }}px;">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="bcw">
                                                        <table cellpadding="0" cellspacing="0" class="barg">
                                                            <tr>
                                                                <td class="bc-val">{{ $machinePerfTotals['idletime'] }}%
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="barg bar-yel" width="36"
                                                                    style="height: {{ max(1, round($machinePerfTotals['idletime'] / 70 * 92)) }}px;">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="bcw">
                                                        <table cellpadding="0" cellspacing="0" class="barg">
                                                            <tr>
                                                                <td class="bc-val">{{ $machinePerfTotals['uptime'] }}%
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="barg bar-grn" width="36"
                                                                    style="height: {{ max(1, round($machinePerfTotals['uptime'] / 70 * 92)) }}px;">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <div class="bc-name">All Units</div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </td>
            </tr>
        </table>
        @elseif (isset($chartPath) && file_exists($chartPath))
        <table style="width: 100%; border: 0; border-collapse: collapse; page-break-inside: avoid;">
            <tr>
                <td style="width: 76%; vertical-align: top; padding: 0 5px 0 0; border: 0;">
                    <div class="panel" style="margin-bottom: 0;">
                        <div class="ptitle">Machine Performance</div>
                        <div class="chart-box">
                            <img src="{{ $chartPath }}" alt="CAPP Chart"
                                style="width: 100%; height: 105px; display: block;">
                        </div>
                    </div>
                </td>
                <td style="width: 24%; vertical-align: top; padding: 0 0 0 5px; border: 0;">
                    <div class="panel" style="margin-bottom: 0;">
                        <div class="ptitle">All Units Summary ({{ $machinePerfTotals['units'] ?? '' }} Units)</div>
                        @if (isset($summaryChartPath) && file_exists($summaryChartPath))
                        <div class="chart-box">
                            <img src="{{ $summaryChartPath }}" alt="All Units Summary"
                                style="width: 100%; height: 105px; display: block;">
                        </div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
        @elseif (isset($summaryChartPath) && file_exists($summaryChartPath))
        <table style="width: 100%; border: 0; border-collapse: collapse;">
            <tr>
                <td style="width: 100%; vertical-align: top; border: 0; text-align: center;">
                    <div class="panel" style="margin: 0 auto; width: 45%;">
                        <div class="ptitle">All Units Summary ({{ $machinePerfTotals['units'] ?? '' }} Units)</div>
                        <div class="chart-box">
                            <img src="{{ $summaryChartPath }}" alt="All Units Summary"
                                style="width: 100%; height: 105px; display: block;">
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        @else
        <table class="data-table" style="width: 70%; margin: 0 auto; page-break-inside: avoid;">
            <tr>
                <td style="padding: 15px; text-align: center; color: #6c8ba4;">
                    CAPP Data Unavailable
                </td>
            </tr>
        </table>
        @endif
    </div>

    <div class="footer">
        <span class="footer-pill">Generated on {{ now()->format('d-M-Y H:i:s') }} | TUSUKA</span>
    </div>
</body>

</html>
