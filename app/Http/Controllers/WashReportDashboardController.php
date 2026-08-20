<?php

namespace App\Http\Controllers;

use App\Models\Dryer;
use App\Models\DryProcessManual;
use App\Models\DryProcessIE;
use App\Models\MachineTransfer;
use App\Models\SecondDryProcessEntry;
use App\Models\Unit;
use App\Models\WashReportEntry;
use App\Models\WashReportManPower;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class WashReportDashboardController extends Controller
{

    /**
     * Dashboard Index
     */
    public function index()
    {
        return view('backend.wash-report-dashboard.index');
    }

    /**
     * Get wash production data for a specific unit and date
     */
    private function getWashProductionDataForUnitAndDate($date, $unit)
    {
        try {
            $unitName = $unit->unitName;

            // Check if this is Unit 4 (Dyeing) or Unit 4 (Denim)
            $isUnit4Dyeing = str_contains($unitName, 'Unit 4 (Dyeing)') || str_contains($unitName, 'Unit 4 Dyeing');
            $isUnit4Denim = str_contains($unitName, 'Unit 4 (Denim)') || str_contains($unitName, 'Unit 4 Denim');

            // Determine the database unit name to search for
            if ($isUnit4Dyeing || $isUnit4Denim) {
                $dbUnitName = 'Unit 4';
            } else {
                $dbUnitName = $unitName;
            }

            // Query for both processes (315 and 316) to get received and delivery
            $query = "
            SELECT   
                p.ProcessName,
                WT.UD_WashType,         
                SUM(wop.Quantity) AS Quantity
            FROM [TusukaExtreme].[dbo].[MA_WorkOrderProduction] wop
            JOIN MA_WorkOrderItem woi ON wop.WorkOrderItemId = woi.RecId
            JOIN MA_Process p ON wop.ProcessId = p.RecId
            OUTER APPLY (
                SELECT DISTINCT KI.UD_WashType
                FROM TSK_WashWorkOrderItem TSK
                JOIN MA_WorkOrderItem KI ON KI.RecId = TSK.DocketWorkOrderItemId
                WHERE TSK.WashWorkOrderItemId = woi.RecId
            ) WT
            WHERE p.RecId IN (315, 316) 
            AND wop.ProductionDate = ?
            AND wop.UD_WashUnit = ?
            GROUP BY p.ProcessName, WT.UD_WashType
            ";

            $params = [$date, $dbUnitName];
            $sqlServerData = DB::connection('sqlsrv')->select($query, $params);

            $received = 0;
            $delivery = 0;

            foreach ($sqlServerData as $row) {
                $washType = $row->UD_WashType ?? null;
                $quantity = (int)($row->Quantity ?? 0);

                if ($isUnit4Dyeing) {
                    if ($washType === 'Over Dye') {
                        if ($row->ProcessName === 'Send from Wash') {
                            $received += $quantity;
                        } elseif ($row->ProcessName === 'Received from Sewing') {
                            $delivery += $quantity;
                        }
                    }
                } elseif ($isUnit4Denim) {
                    if ($washType !== 'Over Dye') {
                        if ($row->ProcessName === 'Send from Wash') {
                            $received += $quantity;
                        } elseif ($row->ProcessName === 'Received from Sewing') {
                            $delivery += $quantity;
                        }
                    }
                } else {
                    if ($row->ProcessName === 'Send from Wash') {
                        $received += $quantity;
                    } elseif ($row->ProcessName === 'Received from Sewing') {
                        $delivery += $quantity;
                    }
                }
            }

            // Separate query for Garment calculation - ONLY Process 316 (Received from Sewing)
            $garmentQuery = "
                SELECT   
                    SUM(wop.Quantity) AS TotalQuantity,
                    SUM(wop.Quantity * wo.UD_WeightOfPcsGmts) AS TotalWeight
                FROM [TusukaExtreme].[dbo].[MA_WorkOrderProduction] wop
                JOIN MA_WorkOrderItem woi ON wop.WorkOrderItemId = woi.RecId
                JOIN MA_WorkOrder wo ON woi.WorkOrderId = wo.RecId
                JOIN MA_Process p ON wop.ProcessId = p.RecId
                OUTER APPLY (
                    SELECT DISTINCT KI.UD_WashType
                    FROM TSK_WashWorkOrderItem TSK
                    JOIN MA_WorkOrderItem KI ON KI.RecId = TSK.DocketWorkOrderItemId
                    WHERE TSK.WashWorkOrderItemId = woi.RecId
                ) WT
                WHERE p.RecId = 316 
                AND wop.ProductionDate = ?
                AND wop.UD_WashUnit = ?
                ";

            $garmentParams = [$date, $dbUnitName];
            $garmentData = DB::connection('sqlsrv')->select($garmentQuery, $garmentParams);

            $garmentTotalQuantity = 0;
            $garmentTotalWeight = 0;

            if (!empty($garmentData)) {
                $garmentTotalQuantity = (int)($garmentData[0]->TotalQuantity ?? 0);
                $garmentTotalWeight = (float)($garmentData[0]->TotalWeight ?? 0);
            }

            // Calculate Garment ONLY from Process 316 (Received from Sewing)
            $garment = $garmentTotalQuantity > 0 ? $garmentTotalWeight / $garmentTotalQuantity : 0;

            return [
                'received' => $received,
                'delivery' => $delivery,
                'garment' => $garment,
                'garment_quantity' => $garmentTotalQuantity,
                'garment_weight' => $garmentTotalWeight
            ];
        } catch (\Exception $e) {
            \Log::error('SQL Server wash data error for ' . $unit->unitName . ' on ' . $date . ': ' . $e->getMessage());
            return [
                'received' => 0,
                'delivery' => 0,
                'garment' => 0,
                'garment_quantity' => 0,
                'garment_weight' => 0
            ];
        }
    }

    /**
     * Get used machine count for a single unit on a single date.
     *
     * IMPORTANT:
     * This is date-wise, not whole-range-wise.
     * For a range like 01 June to 30 June, getUnitData() will loop every date,
     * calculate that date's Used MC, and then SUM those values for the dashboard row.
     */
    private function getUsedMachineCountForUnitDate($unit, $date)
    {
        if (!$unit) {
            return 0;
        }

        $baseMachineCount = (int)$this->getBaseMachineCountForDate($unit->id, $date);

        $transfersInCount = (int)MachineTransfer::where('to_unit_id', $unit->id)
            ->whereDate('transfer_date', $date)
            ->sum('machine_count');

        $transfersOutCount = (int)MachineTransfer::where('from_unit_id', $unit->id)
            ->whereDate('transfer_date', $date)
            ->sum('machine_count');

        $usedMachineCount = $baseMachineCount - $transfersOutCount + $transfersInCount;

        return max(0, $usedMachineCount);
    }

    /**
     * Unit 4 is shown as one combined row, but internally it comes from
     * Unit 4 (Denim) + Unit 4 (Dyeing). This returns date-wise combined Used MC.
     */
    private function getUsedMachineCountForUnit4Date($unit4Denim, $unit4Dyeing, $date)
    {
        $usedMc = 0;

        if ($unit4Denim) {
            $usedMc += $this->getUsedMachineCountForUnitDate($unit4Denim, $date);
        }

        if ($unit4Dyeing) {
            $usedMc += $this->getUsedMachineCountForUnitDate($unit4Dyeing, $date);
        }

        return max(0, $usedMc);
    }

    /**
     * Calculate one day's machine work hour for one dashboard unit.
     * Priority:
     * 1) Manual WashReportEntry.machine_work_hr
     * 2) MachineStatus API downtime calculation
     * 3) 0 if no data
     */
    private function getMachineWorkHoursForUnitDate($unitName, $date, $usedMachineCount)
    {
        $washReportEntry = WashReportEntry::where('unit', $unitName)
            ->whereDate('date', $date)
            ->first();

        if ($washReportEntry && (float)($washReportEntry->machine_work_hr ?? 0) > 0) {
            return max(0, (float)$washReportEntry->machine_work_hr);
        }

        $machineStatus = \App\Models\MachineStatus::where('unit', $unitName)
            ->whereDate('report_date', $date)
            ->first();

        if (!$machineStatus || $usedMachineCount <= 0) {
            return 0;
        }

        $totalMachineHr = $usedMachineCount * 23;
        $breakdownDuration = $machineStatus->down_duration ?? '00:00:00';
        $breakdownHr = $this->convertTimeToDecimalHours($breakdownDuration);
        $mcHr = $totalMachineHr - $breakdownHr;

        // Work hour per machine for that date
        return max(0, $usedMachineCount > 0 ? ($mcHr / $usedMachineCount) : 0);
    }

    /**
     * Get unit-wise Production Dashboard data.
     *
     * Date range logic fixed:
     * - Used MC is now SUM of each date's Used MC.
     * - Used Capacity KG is now SUM of each date's capacity based on that day's Used MC.
     * - Machine Work Hr is now SUM of each date's work hour.
     * - Forecast Target is calculated date-wise and then summed, avoiding monthly over-multiplication.
     */
    public function getUnitData(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        if (!$fromDate || !$toDate) {
            $toDate = now()->toDateString();
            $fromDate = now()->subDays(7)->toDateString();
        }

        $dates = $this->getDatesInRange($fromDate, $toDate);
        $units = Unit::all();
        $unitData = [];

        $unit4Denim = Unit::where('unitName', 'Unit 4 (Denim)')->first();
        $unit4Dyeing = Unit::where('unitName', 'Unit 4 (Dyeing)')->first();

        /*
        |--------------------------------------------------------------------------
        | REGULAR UNITS
        |--------------------------------------------------------------------------
        */
        foreach ($units as $unit) {
            if ($unit->unitName == 'Acid Wash' || $unit->unitName == 'Unit Off') {
                continue;
            }

            if (in_array($unit->unitName, ['Unit 4', 'Unit 4 (Denim)', 'Unit 4 (Dyeing)'])) {
                continue;
            }

            $machineCount = (int)($unit->machineCount ?? 0);
            $capacityKg = (float)($unit->capacity_kg ?? 0);
            $sewingLines = $unit->sewing_lines ?? '';

            $manpowerRecords = WashReportManPower::where('unit', $unit->unitName)
                ->whereBetween('date', [$fromDate, $toDate])
                ->get();

            $manpowerDirect = (int)$manpowerRecords->sum('direct');
            $manpowerIndirect = (int)$manpowerRecords->sum('indirect');
            $manpowerSmv = (float)$manpowerRecords->sum('smv');

            $entryData = WashReportEntry::where('unit', $unit->unitName)
                ->whereBetween('date', [$fromDate, $toDate])
                ->get();

            $firstWashQty = $entryData->sum('FirstWashQty');
            $acidWashQty = $entryData->sum('AcidWashQty');
            $finalWashQty = $entryData->sum('FinalWashQty');
            $rewashQty = $entryData->sum('ReWashQty');
            $reworkDryProc = $entryData->sum('rework_dry_proc');
            $remarks = $entryData->pluck('Remarks')->filter()->implode(' | ');

            $latestEntry = WashReportEntry::where('unit', $unit->unitName)
                ->whereBetween('date', [$fromDate, $toDate])
                ->whereNotNull('in_hand_balance')
                ->orderBy('date', 'desc')
                ->first();

            $inHandBalance = $latestEntry ? (int)($latestEntry->in_hand_balance ?? 0) : 0;

            $totalUsedMc = 0;
            $totalUsedCapacityKg = 0;
            $totalWorkHours = 0;
            $totalReceived = 0;
            $totalDelivery = 0;
            $totalGarmentQuantity = 0;
            $totalGarmentWeight = 0;

            $dailyRows = [];

            foreach ($dates as $dateStr) {
                $dailyUsedMc = $this->getUsedMachineCountForUnitDate($unit, $dateStr);
                $dailyUsedCapacityKg = $machineCount > 0 ? $capacityKg * ($dailyUsedMc / $machineCount) : 0;
                $dailyWorkHours = $this->getMachineWorkHoursForUnitDate($unit->unitName, $dateStr, $dailyUsedMc);

                $washData = $this->getWashProductionDataForUnitAndDate($dateStr, $unit);

                $totalUsedMc += $dailyUsedMc;
                $totalUsedCapacityKg += $dailyUsedCapacityKg;
                $totalWorkHours += $dailyWorkHours;
                $totalReceived += (int)($washData['received'] ?? 0);
                $totalDelivery += (int)($washData['delivery'] ?? 0);
                $totalGarmentQuantity += (int)($washData['garment_quantity'] ?? 0);
                $totalGarmentWeight += (float)($washData['garment_weight'] ?? 0);

                $dailyRows[] = [
                    'date' => $dateStr,
                    'used_mc' => $dailyUsedMc,
                    'used_capacity_kg' => $dailyUsedCapacityKg,
                    'work_hours' => $dailyWorkHours,
                    'garment_quantity' => (int)($washData['garment_quantity'] ?? 0),
                    'garment_weight' => (float)($washData['garment_weight'] ?? 0),
                ];
            }

            $garment = $totalGarmentQuantity > 0 ? $totalGarmentWeight / $totalGarmentQuantity : 0;
            $usedCapacityKg = $totalUsedCapacityKg;
            $manpowerWorkHours = $totalWorkHours;
            $usedMc = $totalUsedMc;

            // Forecast Target is calculated date-wise then summed.
            // This prevents monthly over-multiplication when Used MC and Work Hr are both date-range totals.
            $forecastTarget = 0;
            foreach ($dailyRows as $dailyRow) {
                $dailyGarment = $garment;

                // If a specific date has production/weight, use that day's garment weight.
                if ($dailyRow['garment_quantity'] > 0) {
                    $dailyGarment = $dailyRow['garment_weight'] / $dailyRow['garment_quantity'];
                }

                if ($dailyRow['used_mc'] > 0 && $dailyRow['work_hours'] > 0 && $dailyGarment > 0) {
                    // Unit 5 uses 850 instead of 1000. All other units use 1000.
                    $forecastMcMultiplier = $unit->unitName === 'Unit 5' ? 850 : 1000;

                    $forecastTarget += round((((($dailyRow['used_mc'] * $forecastMcMultiplier * 0.6) / 23) * $dailyRow['work_hours']) / ($dailyGarment / 1000)));
                }
            }

            $deviation = $totalReceived - $forecastTarget;
            $deviationPercent = $forecastTarget > 0 ? ($deviation / $forecastTarget) * 100 : 0;
            $washRatio = $firstWashQty > 0 ? $finalWashQty / $firstWashQty : 0;
            $rewashPercent = $totalReceived > 0 ? ($rewashQty / $totalReceived) * 100 : 0;

            $unitData[] = [
                'unit' => $unit->unitName,
                'machines' => $machineCount,
                'used_mc' => $usedMc,
                'capacity_kg' => $capacityKg,
                'used_capacity_kg' => $usedCapacityKg,
                'sewing_lines' => $sewingLines,
                'direct' => $manpowerDirect,
                'indirect' => $manpowerIndirect,
                'total' => $manpowerDirect + $manpowerIndirect,
                'work_hours' => round($manpowerWorkHours, 2),
                'smv' => $manpowerSmv,
                'received' => $totalReceived,
                'delivery' => $totalDelivery,
                'garment' => $garment,
                'first_wash_qty' => $firstWashQty,
                'acid_wash_qty' => $acidWashQty,
                'final_wash_qty' => $finalWashQty,
                'rewash_qty' => $rewashQty,
                'rework_dry_proc' => $reworkDryProc,
                'remarks' => $remarks ?: '-',
                'forecast_target' => $forecastTarget,
                'deviation' => $deviation,
                'deviation_percent' => $deviationPercent,
                'wash_ratio' => $washRatio,
                'rewash_percent' => $rewashPercent,
                'in_hand_balance' => $inHandBalance,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | UNIT 4 COMBINED ROW
        |--------------------------------------------------------------------------
        */
        $unit4MachineCount = 0;
        $unit4CapacityKg = 0;
        $unit4SewingLines = [];

        if ($unit4Denim) {
            $unit4MachineCount += (int)($unit4Denim->machineCount ?? 0);
            $unit4CapacityKg += (float)($unit4Denim->capacity_kg ?? 0);
            if (!empty($unit4Denim->sewing_lines)) {
                $unit4SewingLines[] = $unit4Denim->sewing_lines;
            }
        }

        if ($unit4Dyeing) {
            $unit4MachineCount += (int)($unit4Dyeing->machineCount ?? 0);
            $unit4CapacityKg += (float)($unit4Dyeing->capacity_kg ?? 0);
            if (!empty($unit4Dyeing->sewing_lines)) {
                $unit4SewingLines[] = $unit4Dyeing->sewing_lines;
            }
        }

        if ($unit4MachineCount > 0 || $unit4Denim || $unit4Dyeing) {
            $manpowerRecords = WashReportManPower::where('unit', 'Unit 4')
                ->whereBetween('date', [$fromDate, $toDate])
                ->get();

            $manpowerDirect = (int)$manpowerRecords->sum('direct');
            $manpowerIndirect = (int)$manpowerRecords->sum('indirect');
            $manpowerSmv = (float)$manpowerRecords->sum('smv');

            $entryData = WashReportEntry::where('unit', 'Unit 4')
                ->whereBetween('date', [$fromDate, $toDate])
                ->get();

            $firstWashQty = $entryData->sum('FirstWashQty');
            $acidWashQty = $entryData->sum('AcidWashQty');
            $finalWashQty = $entryData->sum('FinalWashQty');
            $rewashQty = $entryData->sum('ReWashQty');
            $reworkDryProc = $entryData->sum('rework_dry_proc');
            $remarks = $entryData->pluck('Remarks')->filter()->implode(' | ');

            $latestUnit4Entry = WashReportEntry::where('unit', 'Unit 4')
                ->whereBetween('date', [$fromDate, $toDate])
                ->whereNotNull('in_hand_balance')
                ->orderBy('date', 'desc')
                ->first();

            $unit4InHandBalance = $latestUnit4Entry ? (int)($latestUnit4Entry->in_hand_balance ?? 0) : 0;

            $denimUnit = new \stdClass();
            $denimUnit->unitName = 'Unit 4 (Denim)';

            $dyeingUnit = new \stdClass();
            $dyeingUnit->unitName = 'Unit 4 (Dyeing)';

            $totalUsedMc = 0;
            $totalUsedCapacityKg = 0;
            $totalWorkHours = 0;
            $totalReceived = 0;
            $totalDelivery = 0;
            $totalGarmentQuantity = 0;
            $totalGarmentWeight = 0;

            $dailyRows = [];

            foreach ($dates as $dateStr) {
                $dailyUsedMc = $this->getUsedMachineCountForUnit4Date($unit4Denim, $unit4Dyeing, $dateStr);
                $dailyUsedCapacityKg = $unit4MachineCount > 0 ? $unit4CapacityKg * ($dailyUsedMc / $unit4MachineCount) : 0;
                $dailyWorkHours = $this->getMachineWorkHoursForUnitDate('Unit 4', $dateStr, $dailyUsedMc);

                $washDataDenim = $this->getWashProductionDataForUnitAndDate($dateStr, $denimUnit);
                $washDataDyeing = $this->getWashProductionDataForUnitAndDate($dateStr, $dyeingUnit);

                $dailyGarmentQuantity = (int)($washDataDenim['garment_quantity'] ?? 0) + (int)($washDataDyeing['garment_quantity'] ?? 0);
                $dailyGarmentWeight = (float)($washDataDenim['garment_weight'] ?? 0) + (float)($washDataDyeing['garment_weight'] ?? 0);

                $totalUsedMc += $dailyUsedMc;
                $totalUsedCapacityKg += $dailyUsedCapacityKg;
                $totalWorkHours += $dailyWorkHours;
                $totalReceived += (int)($washDataDenim['received'] ?? 0) + (int)($washDataDyeing['received'] ?? 0);
                $totalDelivery += (int)($washDataDenim['delivery'] ?? 0) + (int)($washDataDyeing['delivery'] ?? 0);
                $totalGarmentQuantity += $dailyGarmentQuantity;
                $totalGarmentWeight += $dailyGarmentWeight;

                $dailyRows[] = [
                    'date' => $dateStr,
                    'used_mc' => $dailyUsedMc,
                    'used_capacity_kg' => $dailyUsedCapacityKg,
                    'work_hours' => $dailyWorkHours,
                    'garment_quantity' => $dailyGarmentQuantity,
                    'garment_weight' => $dailyGarmentWeight,
                ];
            }

            $garment = $totalGarmentQuantity > 0 ? $totalGarmentWeight / $totalGarmentQuantity : 0;
            $usedCapacityKg = $totalUsedCapacityKg;
            $manpowerWorkHours = $totalWorkHours;
            $usedMc = $totalUsedMc;

            $forecastTarget = 0;
            foreach ($dailyRows as $dailyRow) {
                $dailyGarment = $garment;

                if ($dailyRow['garment_quantity'] > 0) {
                    $dailyGarment = $dailyRow['garment_weight'] / $dailyRow['garment_quantity'];
                }

                if ($dailyRow['used_mc'] > 0 && $dailyRow['work_hours'] > 0 && $dailyGarment > 0) {
                    $forecastTarget += round((((($dailyRow['used_mc'] * 1000 * 0.6) / 23) * $dailyRow['work_hours']) / ($dailyGarment / 1000)));
                }
            }

            $deviation = $totalReceived - $forecastTarget;
            $deviationPercent = $forecastTarget > 0 ? ($deviation / $forecastTarget) * 100 : 0;
            $washRatio = $firstWashQty > 0 ? $finalWashQty / $firstWashQty : 0;
            $rewashPercent = $totalReceived > 0 ? ($rewashQty / $totalReceived) * 100 : 0;

            $unitData[] = [
                'unit' => 'Unit 4',
                'machines' => $unit4MachineCount,
                'used_mc' => $usedMc,
                'capacity_kg' => $unit4CapacityKg,
                'used_capacity_kg' => $usedCapacityKg,
                'sewing_lines' => !empty($unit4SewingLines) ? implode(' + ', $unit4SewingLines) : '-',
                'direct' => $manpowerDirect,
                'indirect' => $manpowerIndirect,
                'total' => $manpowerDirect + $manpowerIndirect,
                'work_hours' => round($manpowerWorkHours, 2),
                'smv' => $manpowerSmv,
                'received' => $totalReceived,
                'delivery' => $totalDelivery,
                'garment' => $garment,
                'first_wash_qty' => $firstWashQty,
                'acid_wash_qty' => $acidWashQty,
                'final_wash_qty' => $finalWashQty,
                'rewash_qty' => $rewashQty,
                'rework_dry_proc' => $reworkDryProc,
                'remarks' => $remarks ?: '-',
                'forecast_target' => $forecastTarget,
                'deviation' => $deviation,
                'deviation_percent' => $deviationPercent,
                'wash_ratio' => $washRatio,
                'rewash_percent' => $rewashPercent,
                'in_hand_balance' => $unit4InHandBalance,
            ];
        }

        $order = ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL'];
        usort($unitData, function ($a, $b) use ($order) {
            $posA = array_search($a['unit'], $order);
            $posB = array_search($b['unit'], $order);
            if ($posA === false) $posA = 999;
            if ($posB === false) $posB = 999;
            return $posA - $posB;
        });

        $formattedFromDate = Carbon::parse($fromDate)->format('d-m-Y');
        $formattedToDate = Carbon::parse($toDate)->format('d-m-Y');
        $dateRange = $formattedFromDate . ' to ' . $formattedToDate;

        return DataTables::of($unitData)
            ->with('date_range', $dateRange)
            ->editColumn('capacity_kg', function ($row) {
                return (float)($row['capacity_kg'] ?? 0);
            })
            ->editColumn('sewing_lines', function ($row) {
                return $row['sewing_lines'] ?? '-';
            })
            ->editColumn('direct', function ($row) {
                return (int)($row['direct'] ?? 0);
            })
            ->editColumn('indirect', function ($row) {
                return (int)($row['indirect'] ?? 0);
            })
            ->editColumn('total', function ($row) {
                return (int)($row['total'] ?? 0);
            })
            ->editColumn('work_hours', function ($row) {
                return (float)($row['work_hours'] ?? 0);
            })
            ->editColumn('smv', function ($row) {
                return (float)($row['smv'] ?? 0);
            })
            ->editColumn('received', function ($row) {
                return number_format((int)($row['received'] ?? 0));
            })
            ->editColumn('delivery', function ($row) {
                return number_format((int)($row['delivery'] ?? 0));
            })
            ->editColumn('garment', function ($row) {
                if (isset($row['garment']) && $row['garment'] > 0) {
                    return round((float)$row['garment']);
                }
                return 0;
            })
            ->editColumn('first_wash_qty', function ($row) {
                return number_format((int)($row['first_wash_qty'] ?? 0));
            })
            ->editColumn('acid_wash_qty', function ($row) {
                return number_format((int)($row['acid_wash_qty'] ?? 0));
            })
            ->editColumn('final_wash_qty', function ($row) {
                return number_format((int)($row['final_wash_qty'] ?? 0));
            })
            ->editColumn('rewash_qty', function ($row) {
                return number_format((int)($row['rewash_qty'] ?? 0));
            })
            ->editColumn('rework_dry_proc', function ($row) {
                return number_format((int)($row['rework_dry_proc'] ?? 0));
            })
            ->editColumn('forecast_target', function ($row) {
                return number_format(round($row['forecast_target'] ?? 0));
            })
            ->editColumn('deviation', function ($row) {
                $deviation = round($row['deviation'] ?? 0);
                $class = $deviation < 0 ? 'text-danger' : ($deviation > 0 ? 'text-success' : '');
                return '<span class="' . $class . ' fw-bold">' . number_format($deviation, 0) . '</span>';
            })
            ->editColumn('deviation_percent', function ($row) {
                $deviationPercent = (float)($row['deviation_percent'] ?? 0);
                $class = $deviationPercent > 0 ? 'text-success' : ($deviationPercent < 0 ? 'text-danger' : '');
                return '<span class="' . $class . ' fw-bold">' . number_format(abs($deviationPercent), 2) . '%</span>';
            })
            ->editColumn('wash_ratio', function ($row) {
                $washRatio = (float)($row['wash_ratio'] ?? 0);
                return number_format($washRatio * 100, 2) . '%';
            })
            ->editColumn('rewash_percent', function ($row) {
                return number_format((float)($row['rewash_percent'] ?? 0)) . '%';
            })
            ->editColumn('in_hand_balance', function ($row) {
                return number_format((int)($row['in_hand_balance'] ?? 0));
            })
            ->rawColumns(['deviation', 'deviation_percent'])
            ->make(true);
    }


    /**
     * Get Dry Process Delivery/Receive Data from API
     * Returns array keyed by processDescription and transactionType
     */
    private function getDryProcessDataFromApi($fromDate, $toDate)
    {
        try {
            $apiUrl = "http://192.168.136.53:5000/api/OutApi/dry-process-summary?fromDate={$fromDate}&toDate={$toDate}";

            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json',
                'Content-Type: application/json'
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);

                if (is_array($data)) {
                    // Transform API data into the same structure as before
                    $result = [];

                    foreach ($data as $item) {
                        $processDescription = $item['processDescription'];
                        $transactionType = $item['transactionType'];
                        $tpl = (int)($item['tpl'] ?? 0);
                        $twl = (int)($item['twl'] ?? 0);

                        if (!isset($result[$processDescription])) {
                            $result[$processDescription] = [
                                'TPL' => 0,
                                'TWL' => 0,
                                'TPL_Receive' => 0,
                                'TWL_Receive' => 0,
                                'TPL_Delivery' => 0,
                                'TWL_Delivery' => 0
                            ];
                        }

                        if ($transactionType === 'Receive') {
                            $result[$processDescription]['TPL_Receive'] = $tpl;
                            $result[$processDescription]['TWL_Receive'] = $twl;
                        } elseif ($transactionType === 'Delivery') {
                            $result[$processDescription]['TPL_Delivery'] = $tpl;
                            $result[$processDescription]['TWL_Delivery'] = $twl;
                        }
                    }

                    \Log::info('API Dry Process Data:', $result);
                    return $result;
                }
            } else {
                \Log::warning("API request failed for dry process data. HTTP Code: {$httpCode}");
            }

            return [];
        } catch (\Exception $e) {
            \Log::error('Error fetching Dry Process Data from API: ' . $e->getMessage());
            return [];
        }
    }


    /**
     * Get Delivery Data from Third Database (washRD2)
     * Returns array keyed by Description => ['TPL' => val, 'TWL' => val]
     */
    private function getDryProcessDeliveryData($fromDate, $toDate)
    {
        try {
            // Query to fetch delivery data based on shift logic (before 8 AM = previous day)
            $deliveryQuery = "
            SELECT
                ps.[Description],
                SUM(CASE 
                        WHEN wo.Unit IN ('Unit 1','Unit 2','Unit 3','Unit 4','Unit 5') 
                        THEN wt.Quantity 
                        ELSE 0 
                    END) AS TPL,
                SUM(CASE 
                        WHEN wo.Unit NOT IN ('Unit 1','Unit 2','Unit 3','Unit 4','Unit 5') 
                        THEN wt.Quantity 
                        ELSE 0 
                    END) AS TWL
            FROM [TestWash].[dbo].[WashTransactions] wt
        
            JOIN [ProcessStages] ps 
                ON ps.Id = wt.ProcessStageId
            JOIN [WorkOrders] wo 
                ON wo.Id = wt.WorkOrderId
            WHERE 
                wt.TransactionType = 'Delivery' 
                AND wt.ShiftDate BETWEEN ? AND ?
                AND ps.[Description] IN ('First Dry Process', 'Second Dry Process')
            GROUP BY 
                ps.[Description]
        ";

            // Query to fetch receive data
            $receiveQuery = "
            SELECT
                ps.[Description],
                SUM(CASE 
                        WHEN wo.Unit IN ('Unit 1','Unit 2','Unit 3','Unit 4','Unit 5') 
                        THEN wt.Quantity 
                        ELSE 0 
                    END) AS TPL,
                SUM(CASE 
                        WHEN wo.Unit NOT IN ('Unit 1','Unit 2','Unit 3','Unit 4','Unit 5') 
                        THEN wt.Quantity 
                        ELSE 0 
                    END) AS TWL
            FROM [TestWash].[dbo].[WashTransactions] wt
            
            JOIN [ProcessStages] ps 
                ON ps.Id = wt.ProcessStageId
            JOIN [WorkOrders] wo 
                ON wo.Id = wt.WorkOrderId
            WHERE 
                wt.TransactionType = 'Receive' 
                AND wt.ShiftDate BETWEEN ? AND ?
                AND ps.[Description] IN ('First Dry Process', 'Second Dry Process')
            GROUP BY 
                ps.[Description]
        ";



            $params = [$fromDate, $toDate];

            // Get delivery results
            $deliveryResults = DB::connection('sqlsrv_third')->select($deliveryQuery, $params);
            // Get receive results
            $receiveResults = DB::connection('sqlsrv_third')->select($receiveQuery, $params);

            $data = [];
            foreach ($deliveryResults as $row) {
                if (!isset($data[$row->Description])) {
                    $data[$row->Description] = [
                        'TPL' => 0,
                        'TWL' => 0,
                        'TPL_Receive' => 0,
                        'TWL_Receive' => 0
                    ];
                }
                $data[$row->Description]['TPL'] = (int)$row->TPL;
                $data[$row->Description]['TWL'] = (int)$row->TWL;
            }

            foreach ($receiveResults as $row) {
                if (!isset($data[$row->Description])) {
                    $data[$row->Description] = [
                        'TPL' => 0,
                        'TWL' => 0,
                        'TPL_Receive' => 0,
                        'TWL_Receive' => 0
                    ];
                }
                $data[$row->Description]['TPL_Receive'] = (int)$row->TPL;
                $data[$row->Description]['TWL_Receive'] = (int)$row->TWL;
            }

            return $data;
        } catch (\Exception $e) {
            \Log::error('Error fetching Dry Process Delivery Data: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get 1st Dry Process data
     */
    public function getFirstDryProcessData(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        if (!$fromDate || !$toDate) {
            $toDate = now()->toDateString();
            $fromDate = now()->subDays(7)->toDateString();
        }

        try {
            $finalData = [];
            $plants = ['TPL', 'TWL'];

            // 1. Fetch Delivery/Receive Data from API (REPLACED THE OLD METHOD)
            $deliveryData = $this->getDryProcessDataFromApi($fromDate, $toDate);

            foreach ($plants as $plant) {
                $plantData = [
                    'plant' => $plant,
                    'whisker_target' => 0,
                    'whisker_production' => 0,
                    'whisker_defect' => 0,
                    'handbrush_target' => 0,
                    'handbrush_production' => 0,
                    'handbrush_defect' => 0,
                    'firstdryfinal_target' => 0,
                    'firstdryfinal_production' => 0,
                    'firstdryfinal_receive' => 0,
                    'firstdryfinal_delivery' => 0,
                    'firstdryfinal_defect' => 0,
                    'firstdryfinal_deviation' => 0,
                    'total_defect_qty' => 0,
                    'manPower' => 0,
                    'workingHr' => 0,
                    'smv' => 0,
                    'remarks' => ''
                ];

                // Collect remarks for this plant across date range
                $remarksList = DryProcessManual::where('plantName', $plant)
                    ->whereBetween('date', [$fromDate, $toDate])
                    ->whereNotNull('remarks')
                    ->where('remarks', '!=', '')
                    ->orderBy('date', 'desc')
                    ->pluck('remarks', 'date')
                    ->toArray();

                $plantData['remarks'] = !empty($remarksList) ? implode(' | ', array_slice($remarksList, 0, 3)) : '';

                // Loop through each date in the range
                $currentDate = Carbon::parse($fromDate);
                $endDate = Carbon::parse($toDate);

                while ($currentDate <= $endDate) {
                    $dateStr = $currentDate->toDateString();

                    // Check SecondDryProcessEntry FIRST
                    $manualEntries = SecondDryProcessEntry::where('plant', $plant)
                        ->where('date', $dateStr)
                        ->whereIn('processType', ['Whisker', 'Hand Brush', '1st Dry Final'])
                        ->get()
                        ->keyBy('processType');

                    $hasManualData = $manualEntries->isNotEmpty();

                    if ($hasManualData) {
                        // Use Manual Entry Data
                        if (isset($manualEntries['Whisker'])) {
                            $row = $manualEntries['Whisker'];
                            $plantData['whisker_target'] += (int)($row->TargetQty ?? 0);
                            $plantData['whisker_production'] += (int)($row->ProductionQty ?? 0);
                            $plantData['whisker_defect'] += (int)($row->defectQty ?? 0);
                            $plantData['total_defect_qty'] += (int)($row->defectQty ?? 0);
                        }
                        if (isset($manualEntries['Hand Brush'])) {
                            $row = $manualEntries['Hand Brush'];
                            $plantData['handbrush_target'] += (int)($row->TargetQty ?? 0);
                            $plantData['handbrush_production'] += (int)($row->ProductionQty ?? 0);
                            $plantData['handbrush_defect'] += (int)($row->defectQty ?? 0);
                            $plantData['total_defect_qty'] += (int)($row->defectQty ?? 0);
                        }
                        if (isset($manualEntries['1st Dry Final'])) {
                            $row = $manualEntries['1st Dry Final'];
                            $plantData['firstdryfinal_target'] += (int)($row->TargetQty ?? 0);
                            $plantData['firstdryfinal_production'] += (int)($row->ProductionQty ?? 0);
                            $plantData['firstdryfinal_defect'] += (int)($row->defectQty ?? 0);
                            $plantData['total_defect_qty'] += (int)($row->defectQty ?? 0);
                        }
                    } else {
                        // Fallback Logic
                        if ($dateStr < Carbon::now()->toDateString()) {
                            $manualData = DryProcessManual::where('plantName', $plant)
                                ->where('date', $dateStr)
                                ->first();

                            if ($manualData) {
                                $plantData['whisker_target'] += (int)($manualData->whisker_target ?? 0);
                                $plantData['whisker_production'] += (int)($manualData->whisker_production ?? 0);
                                $plantData['handbrush_target'] += (int)($manualData->handBrush_target ?? 0);
                                $plantData['handbrush_production'] += (int)($manualData->handBrush_production ?? 0);
                                $plantData['firstdryfinal_target'] += (int)($manualData->FirstDryFinal_target ?? 0);
                                $plantData['firstdryfinal_production'] += (int)($manualData->FirstDryFinal_production ?? 0);
                                $plantData['firstdryfinal_defect'] += (int)($manualData->FirstDryFinal_defectQty ?? 0);
                                $plantData['total_defect_qty'] += (int)($manualData->FirstDryFinal_defectQty ?? 0);
                            }
                        } else {
                            $queryData = $this->getFirstDryDataFromQuery($dateStr, $dateStr);

                            if (isset($queryData[$plant])) {
                                $plantData['whisker_target'] += $queryData[$plant]['whisker_target'];
                                $plantData['whisker_production'] += $queryData[$plant]['whisker_production'];
                                $plantData['whisker_defect'] += $queryData[$plant]['whisker_defect'];
                                $plantData['handbrush_target'] += $queryData[$plant]['handbrush_target'];
                                $plantData['handbrush_production'] += $queryData[$plant]['handbrush_production'];
                                $plantData['handbrush_defect'] += $queryData[$plant]['handbrush_defect'];
                                $plantData['firstdryfinal_target'] += $queryData[$plant]['firstdryfinal_target'];
                                $plantData['firstdryfinal_production'] += $queryData[$plant]['firstdryfinal_production'];
                                $plantData['firstdryfinal_defect'] += $queryData[$plant]['firstdryfinal_defect'];
                                $plantData['total_defect_qty'] += $queryData[$plant]['firstdryfinal_defect'];
                            }
                        }
                    }

                    $currentDate->addDay();
                }

                // 2. Populate Delivery & Receive from API data (UPDATED)
                if (isset($deliveryData['First Dry Process'])) {
                    if ($plant == 'TPL') {
                        $plantData['firstdryfinal_delivery'] = $deliveryData['First Dry Process']['TPL_Delivery'] ?? 0;
                        $plantData['firstdryfinal_receive'] = $deliveryData['First Dry Process']['TPL_Receive'] ?? 0;
                    } else {
                        $plantData['firstdryfinal_delivery'] = $deliveryData['First Dry Process']['TWL_Delivery'] ?? 0;
                        $plantData['firstdryfinal_receive'] = $deliveryData['First Dry Process']['TWL_Receive'] ?? 0;
                    }
                } else {
                    $plantData['firstdryfinal_receive'] = 0;
                    $plantData['firstdryfinal_delivery'] = 0;
                }

                // Calculate deviation
                $plantData['firstdryfinal_deviation'] = $plantData['firstdryfinal_target'] - $plantData['firstdryfinal_production'];

                // SMV & IE LOGIC
                $ieData = DryProcessIE::select(
                    DB::raw('AVG(manPower) as avg_manPower'),
                    DB::raw('AVG(workingHr) as avg_workingHr')
                )
                    ->where('plant', $plant)
                    ->whereBetween('date', [$fromDate, $toDate])
                    ->where('processType', '1st Dry Process')
                    ->first();

                $plantData['manPower'] = round($ieData->avg_manPower ?? 0);
                $plantData['workingHr'] = round($ieData->avg_workingHr ?? 0, 2);

                if ($plant == 'TPL') {
                    try {
                        $avgSmv = DB::connection('sqlsrv_second')
                            ->table('WorkingHourDetailManPower as whdmp')
                            ->join('WorkingHour as wh', 'whdmp.WorkingHourId', '=', 'wh.Id')
                            ->join('WashProcess as wp', 'whdmp.WashProcessId', '=', 'wp.Id')
                            ->whereBetween('wh.WorkingHourDay', [$fromDate, $toDate])
                            ->where('wp.ProcessName', 'First Dry Final')
                            ->where('whdmp.smv', '>', 0)
                            ->avg('whdmp.smv');

                        $plantData['smv'] = round($avgSmv ?? 0, 2);
                    } catch (\Exception $e) {
                        $plantData['smv'] = 0;
                        \Log::error("TPL SMV Query Error (1st Dry): " . $e->getMessage());
                    }
                } else {
                    $smvData = DryProcessIE::select(DB::raw('AVG(smv) as avg_smv'))
                        ->where('plant', $plant)
                        ->whereBetween('date', [$fromDate, $toDate])
                        ->where('processType', '1st Dry Process')
                        ->first();

                    $plantData['smv'] = round($smvData->avg_smv ?? 0, 2);
                }

                $finalData[] = $plantData;
            }

            usort($finalData, function ($a, $b) {
                return strcmp($a['plant'], $b['plant']);
            });

            return DataTables::of($finalData)
                ->editColumn('plant', function ($row) {
                    return $row['plant'] ?? '-';
                })
                ->editColumn('whisker_target', function ($row) {
                    return number_format((int)($row['whisker_target'] ?? 0));
                })
                ->editColumn('whisker_production', function ($row) {
                    return number_format((int)($row['whisker_production'] ?? 0));
                })
                ->editColumn('handbrush_target', function ($row) {
                    return number_format((int)($row['handbrush_target'] ?? 0));
                })
                ->editColumn('handbrush_production', function ($row) {
                    return number_format((int)($row['handbrush_production'] ?? 0));
                })
                ->editColumn('firstdryfinal_target', function ($row) {
                    return number_format((int)($row['firstdryfinal_target'] ?? 0));
                })
                ->editColumn('firstdryfinal_production', function ($row) {
                    return number_format((int)($row['firstdryfinal_production'] ?? 0));
                })
                ->editColumn('firstdryfinal_receive', function ($row) {
                    return number_format((int)($row['firstdryfinal_receive'] ?? 0));
                })
                ->editColumn('firstdryfinal_delivery', function ($row) {
                    return number_format((int)($row['firstdryfinal_delivery'] ?? 0));
                })
                ->editColumn('deviation', function ($row) {
                    $deviation = (int)($row['firstdryfinal_deviation'] ?? 0);
                    $class = $deviation > 0 ? 'text-danger' : ($deviation < 0 ? 'text-success' : '');
                    return '<span class="' . $class . ' fw-bold">' . number_format(abs($deviation)) . '</span>';
                })
                ->editColumn('defect_qty', function ($row) {
                    return number_format((int)($row['total_defect_qty'] ?? 0));
                })
                ->editColumn('manPower', function ($row) {
                    return number_format((int)($row['manPower'] ?? 0));
                })
                ->editColumn('workingHr', function ($row) {
                    return number_format((float)($row['workingHr'] ?? 0), 2);
                })
                ->editColumn('smv', function ($row) {
                    return number_format((float)($row['smv'] ?? 0), 2);
                })
                ->editColumn('remarks', function ($row) {
                    return $row['remarks'] ?? '-';
                })
                ->rawColumns(['deviation'])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error('Error in getFirstDryProcessData: ' . $e->getMessage());
            return DataTables::of([])->make(true);
        }
    }



    /**
     * Helper function: Get First Dry data from original query (for recent dates)
     */
    private function getFirstDryDataFromQuery($fromDate, $toDate)
    {
        try {
            // ========== PART 1: GET TPL DATA FROM QC DATABASE ==========
            $query = "
            SELECT
                fc.PlantName,
                fc.UnitName,
                wp.ProcessName,
                SUM(fc.ProductionQty) AS ProductionQty,
                SUM(fc.DefectQty) AS DefectQty,
                SUM(ISNULL(t.DayTarget, 0)) AS TargetQty
            FROM
            (
                SELECT 
                    p.PlantName,
                    pu.UnitName,
                    fq.QcDate,
                    fq.WashProcessId,
                    SUM(CASE WHEN fq.QcStatusId IN (1,3) THEN 1 ELSE 0 END) AS ProductionQty,
                    SUM(CASE WHEN fq.QcStatusId = 2 THEN 1 ELSE 0 END) AS DefectQty
                FROM [DHU_WashDB].[dbo].[FirstDryProcessQc] fq
                JOIN FirstDryProcess fp ON fq.FirstDryProcessId = fp.Id
                JOIN PlantUnit pu ON fp.UnitId = pu.id
                JOIN Plant p ON pu.PlantId = p.Id
                WHERE fq.IsDeleted = 0
                AND fq.QcDate BETWEEN ? AND ?
                GROUP BY 
                    p.PlantName,
                    pu.UnitName,
                    fq.QcDate,
                    fq.WashProcessId
            ) fc
            LEFT JOIN
            (
                SELECT 
                    wh.WorkingHourDay AS [Date],
                    whdpm.WashProcessId,
                    SUM(whdpm.DailyTarget) AS DayTarget
                FROM WorkingHourDetailManPower whdpm
                JOIN WorkingHourDetail whd ON whdpm.WorkingHourDetailId = whd.Id
                JOIN WorkingHour wh ON whd.WorkingHourId = wh.Id
                WHERE wh.WorkingHourDay BETWEEN ? AND ?
                GROUP BY 
                    wh.WorkingHourDay,
                    whdpm.WashProcessId
            ) t
                ON t.WashProcessId = fc.WashProcessId
                AND t.[Date] = fc.QcDate
            JOIN WashProcess wp 
                ON wp.Id = fc.WashProcessId
            WHERE wp.ProcessName IN ('Whisker', 'Handbrush', 'First Dry Final')
            GROUP BY fc.PlantName, fc.UnitName, wp.ProcessName
            ORDER BY fc.PlantName, wp.ProcessName;
        ";

            $params = [$fromDate, $toDate, $fromDate, $toDate];
            $results = DB::connection('sqlsrv_second')->select($query, $params);

            // ========== PART 2: GET TWL DATA FROM SecondDryProcessEntry ==========
            $whiskerData = SecondDryProcessEntry::select(
                DB::raw("'TWL' as PlantName"),
                DB::raw("'Whisker' as ProcessName"),
                DB::raw('SUM(TargetQty) as TargetQty'),
                DB::raw('SUM(ProductionQty) as ProductionQty'),
                DB::raw('0 as DefectQty')
            )
                ->where('plant', 'TWL')
                ->where('processType', 'Whisker')
                ->whereBetween('date', [$fromDate, $toDate])
                ->groupBy('plant')
                ->get();

            $handBrushData = SecondDryProcessEntry::select(
                DB::raw("'TWL' as PlantName"),
                DB::raw("'Handbrush' as ProcessName"),
                DB::raw('SUM(TargetQty) as TargetQty'),
                DB::raw('SUM(ProductionQty) as ProductionQty'),
                DB::raw('0 as DefectQty')
            )
                ->where('plant', 'TWL')
                ->where('processType', 'Hand Brush')
                ->whereBetween('date', [$fromDate, $toDate])
                ->groupBy('plant')
                ->get();

            $firstDryFinalData = SecondDryProcessEntry::select(
                DB::raw("'TWL' as PlantName"),
                DB::raw("'First Dry Final' as ProcessName"),
                DB::raw('SUM(TargetQty) as TargetQty'),
                DB::raw('SUM(ProductionQty) as ProductionQty'),
                DB::raw('SUM(defectQty) as DefectQty')
            )
                ->where('plant', 'TWL')
                ->where('processType', '1st Dry Final')
                ->whereBetween('date', [$fromDate, $toDate])
                ->groupBy('plant')
                ->get();

            // ========== PART 3: MERGE THE DATA ==========
            $mergedResults = collect($results);

            foreach ($whiskerData as $item) {
                $mergedResults->push((object)[
                    'PlantName' => 'TWL',
                    'UnitName' => null,
                    'ProcessName' => 'Whisker',
                    'ProductionQty' => $item->ProductionQty,
                    'DefectQty' => $item->DefectQty,
                    'TargetQty' => $item->TargetQty
                ]);
            }

            foreach ($handBrushData as $item) {
                $mergedResults->push((object)[
                    'PlantName' => 'TWL',
                    'UnitName' => null,
                    'ProcessName' => 'Handbrush',
                    'ProductionQty' => $item->ProductionQty,
                    'DefectQty' => $item->DefectQty,
                    'TargetQty' => $item->TargetQty
                ]);
            }

            foreach ($firstDryFinalData as $item) {
                $mergedResults->push((object)[
                    'PlantName' => 'TWL',
                    'UnitName' => null,
                    'ProcessName' => 'First Dry Final',
                    'ProductionQty' => $item->ProductionQty,
                    'DefectQty' => $item->DefectQty,
                    'TargetQty' => $item->TargetQty
                ]);
            }

            // Transform data to the required format (group by plant only)
            $groupedData = [];
            foreach ($mergedResults as $row) {
                $plant = $row->PlantName;
                $key = $plant;

                if (!isset($groupedData[$key])) {
                    $groupedData[$key] = [
                        'plant' => $plant,
                        'whisker_target' => 0,
                        'whisker_production' => 0,
                        'whisker_defect' => 0,
                        'handbrush_target' => 0,
                        'handbrush_production' => 0,
                        'handbrush_defect' => 0,
                        'firstdryfinal_target' => 0,
                        'firstdryfinal_production' => 0,
                        'firstdryfinal_defect' => 0,
                        'firstdryfinal_deviation' => 0,
                        'total_defect_qty' => 0,
                    ];
                }

                switch ($row->ProcessName) {
                    case 'Whisker':
                        $groupedData[$key]['whisker_target'] += (int)$row->TargetQty;
                        $groupedData[$key]['whisker_production'] += (int)$row->ProductionQty;
                        $groupedData[$key]['whisker_defect'] += (int)($row->DefectQty ?? 0);
                        $groupedData[$key]['total_defect_qty'] += (int)($row->DefectQty ?? 0);
                        break;
                    case 'Handbrush':
                        $groupedData[$key]['handbrush_target'] += (int)$row->TargetQty;
                        $groupedData[$key]['handbrush_production'] += (int)$row->ProductionQty;
                        $groupedData[$key]['handbrush_defect'] += (int)($row->DefectQty ?? 0);
                        $groupedData[$key]['total_defect_qty'] += (int)($row->DefectQty ?? 0);
                        break;
                    case 'First Dry Final':
                        $groupedData[$key]['firstdryfinal_target'] += (int)$row->TargetQty;
                        $groupedData[$key]['firstdryfinal_production'] += (int)$row->ProductionQty;
                        $groupedData[$key]['firstdryfinal_defect'] += (int)($row->DefectQty ?? 0);
                        $groupedData[$key]['firstdryfinal_deviation'] += (int)$row->TargetQty - (int)$row->ProductionQty;
                        $groupedData[$key]['total_defect_qty'] += (int)($row->DefectQty ?? 0);
                        break;
                }
            }

            return $groupedData;
        } catch (\Exception $e) {
            \Log::error('Error in getFirstDryDataFromQuery: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get 2nd Dry Process data
     */
    public function getSecondDryProcessData(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        if (!$fromDate || !$toDate) {
            $toDate = now()->toDateString();
            $fromDate = now()->subDays(7)->toDateString();
        }

        try {
            $plants = ['TPL', 'TWL'];
            $finalData = [];

            // 1. Fetch Delivery/Receive Data from API (REPLACED THE OLD METHOD)
            $deliveryData = $this->getDryProcessDataFromApi($fromDate, $toDate);

            foreach ($plants as $plant) {
                $plantData = [
                    'plant' => $plant,
                    'laser_target' => 0,
                    'laser_production' => 0,
                    'laser_defect' => 0,
                    'ppspray_target' => 0,
                    'ppspray_production' => 0,
                    'ppspray_defect' => 0,
                    'seconddryfinal_target' => 0,
                    'seconddryfinal_production' => 0,
                    'seconddryfinal_receive' => 0,
                    'seconddryfinal_delivery' => 0,
                    'seconddryfinal_defect' => 0,
                    'seconddryfinal_deviation' => 0,
                    'total_defect_qty' => 0,
                    'manPower' => 0,
                    'workingHr' => 0,
                    'smv' => 0,
                    'remarks' => ''
                ];

                // Collect remarks for this plant across date range
                $remarksList = DryProcessManual::where('plantName', $plant)
                    ->whereBetween('date', [$fromDate, $toDate])
                    ->whereNotNull('remarks2')
                    ->where('remarks2', '!=', '')
                    ->orderBy('date', 'desc')
                    ->pluck('remarks2', 'date')
                    ->toArray();

                $plantData['remarks'] = !empty($remarksList) ? implode(' | ', array_slice($remarksList, 0, 3)) : '';

                $currentDate = Carbon::parse($fromDate);
                $endDate = Carbon::parse($toDate);

                // Prepare filter dates with buffer for timezone safety
                $filterStart = Carbon::parse($fromDate)->subDay();
                $filterEnd = Carbon::parse($toDate)->addDay();

                // 1. Fetch all potentially relevant entries from DB (Raw Query)
                $manualEntriesRaw = SecondDryProcessEntry::where('plant', $plant)
                    ->whereBetween('date', [$filterStart, $filterEnd])
                    ->whereIn('processType', ['Laser', 'PP Spray', '2nd Dry Final', '2nd Dry Process'])
                    ->get();

                // 2. Filter in PHP using Carbon (Timezone Safe)
                $manualEntries = $manualEntriesRaw->filter(function ($item) use ($fromDate, $toDate) {
                    $itemDate = Carbon::parse($item->date)->format('Y-m-d');
                    return $itemDate >= $fromDate && $itemDate <= $toDate;
                })->keyBy('processType');

                // 3. Aggregate Data
                $plantData['laser_target'] = $manualEntries->where('processType', 'Laser')->sum('TargetQty');
                $plantData['laser_production'] = $manualEntries->where('processType', 'Laser')->sum('ProductionQty');
                $plantData['laser_defect'] = $manualEntries->where('processType', 'Laser')->sum('defectQty');

                $plantData['ppspray_target'] = $manualEntries->where('processType', 'PP Spray')->sum('TargetQty');
                $plantData['ppspray_production'] = $manualEntries->where('processType', 'PP Spray')->sum('ProductionQty');
                $plantData['ppspray_defect'] = $manualEntries->where('processType', 'PP Spray')->sum('defectQty');

                // Check for both possible names: '2nd Dry Final' and '2nd Dry Process'
                $secondDryEntries = $manualEntries->filter(function ($item) {
                    return in_array($item->processType, ['2nd Dry Final', '2nd Dry Process']);
                });

                $plantData['seconddryfinal_target'] = $secondDryEntries->sum('TargetQty');
                $plantData['seconddryfinal_production'] = $secondDryEntries->sum('ProductionQty');
                $plantData['seconddryfinal_defect'] = $secondDryEntries->sum('defectQty');

                // Total Defect Qty
                $plantData['total_defect_qty'] = $plantData['laser_defect'] + $plantData['ppspray_defect'] + $plantData['seconddryfinal_defect'];

                // --- FALLBACK TO DryProcessManual ---
                if ($plantData['seconddryfinal_target'] == 0 && $plantData['seconddryfinal_production'] == 0) {
                    $manualData = DryProcessManual::where('plantName', $plant)
                        ->whereBetween('date', [$filterStart, $filterEnd])
                        ->get()
                        ->filter(function ($item) use ($fromDate, $toDate) {
                            $itemDate = Carbon::parse($item->date)->format('Y-m-d');
                            return $itemDate >= $fromDate && $itemDate <= $toDate;
                        })
                        ->first();

                    if ($manualData) {
                        $plantData['seconddryfinal_target'] = (int)($manualData->SecondDryFinal_target ?? 0);
                        $plantData['seconddryfinal_production'] = (int)($manualData->SecondDryFinal_production ?? 0);
                        $plantData['seconddryfinal_defect'] = (int)($manualData->SecondDryFinal_defectQty ?? 0);
                        $plantData['total_defect_qty'] = (int)($manualData->SecondDryFinal_defectQty ?? 0);
                    }
                }

                // --- FALLBACK TO SQL SERVER QUERY (Only if still 0) ---
                if ($plantData['seconddryfinal_target'] == 0 && $plantData['seconddryfinal_production'] == 0) {
                    if ($fromDate == now()->toDateString() && $toDate == now()->toDateString()) {
                        $queryData = $this->getSecondDryDataFromQuery($fromDate, $toDate);
                        if (isset($queryData[$plant])) {
                            $plantData['seconddryfinal_target'] = $queryData[$plant]['seconddryfinal_target'];
                            $plantData['seconddryfinal_production'] = $queryData[$plant]['seconddryfinal_production'];
                            $plantData['seconddryfinal_defect'] = $queryData[$plant]['seconddryfinal_defect'];
                            $plantData['total_defect_qty'] = $queryData[$plant]['total_defect_qty'];
                            $plantData['laser_target'] = $queryData[$plant]['laser_target'];
                            $plantData['laser_production'] = $queryData[$plant]['laser_production'];
                            $plantData['ppspray_target'] = $queryData[$plant]['ppspray_target'];
                            $plantData['ppspray_production'] = $queryData[$plant]['ppspray_production'];
                        }
                    }
                }

                // 2. Populate Delivery & Receive from API data (UPDATED)
                if (isset($deliveryData['Second Dry Process'])) {
                    if ($plant == 'TPL') {
                        $plantData['seconddryfinal_delivery'] = $deliveryData['Second Dry Process']['TPL_Delivery'] ?? 0;
                        $plantData['seconddryfinal_receive'] = $deliveryData['Second Dry Process']['TPL_Receive'] ?? 0;
                    } else {
                        $plantData['seconddryfinal_delivery'] = $deliveryData['Second Dry Process']['TWL_Delivery'] ?? 0;
                        $plantData['seconddryfinal_receive'] = $deliveryData['Second Dry Process']['TWL_Receive'] ?? 0;
                    }
                } else {
                    $plantData['seconddryfinal_receive'] = 0;
                    $plantData['seconddryfinal_delivery'] = 0;
                }

                // Calculate deviation
                $plantData['seconddryfinal_deviation'] = $plantData['seconddryfinal_target'] - $plantData['seconddryfinal_production'];

                // SMV & IE LOGIC
                $ieData = DryProcessIE::select(
                    DB::raw('AVG(manPower) as avg_manPower'),
                    DB::raw('AVG(workingHr) as avg_workingHr')
                )
                    ->where('plant', $plant)
                    ->whereBetween('date', [$fromDate, $toDate])
                    ->where('processType', '2nd Dry Process')
                    ->first();

                $plantData['manPower'] = round($ieData->avg_manPower ?? 0);
                $plantData['workingHr'] = round($ieData->avg_workingHr ?? 0, 2);

                if ($plant == 'TPL') {
                    try {
                        $avgSmv = DB::connection('sqlsrv_second')
                            ->table('WorkingHourDetailManPower as whdmp')
                            ->join('WorkingHour as wh', 'whdmp.WorkingHourId', '=', 'wh.Id')
                            ->join('WashProcess as wp', 'whdmp.WashProcessId', '=', 'wp.Id')
                            ->whereBetween('wh.WorkingHourDay', [$fromDate, $toDate])
                            ->where('wp.ProcessName', '2nd Dry Final')
                            ->where('whdmp.smv', '>', 0)
                            ->avg('whdmp.smv');

                        $plantData['smv'] = round($avgSmv ?? 0, 2);
                    } catch (\Exception $e) {
                        $plantData['smv'] = 0;
                    }
                } else {
                    $smvData = DryProcessIE::select(DB::raw('AVG(smv) as avg_smv'))
                        ->where('plant', $plant)
                        ->whereBetween('date', [$fromDate, $toDate])
                        ->where('processType', '2nd Dry Process')
                        ->first();

                    $plantData['smv'] = round($smvData->avg_smv ?? 0, 2);
                }

                $finalData[] = $plantData;
            }

            usort($finalData, function ($a, $b) {
                return strcmp($a['plant'], $b['plant']);
            });

            return DataTables::of($finalData)
                ->editColumn('plant', function ($row) {
                    return $row['plant'] ?? '-';
                })
                ->editColumn('laser_target', function ($row) {
                    return number_format((int)($row['laser_target'] ?? 0));
                })
                ->editColumn('laser_production', function ($row) {
                    return number_format((int)($row['laser_production'] ?? 0));
                })
                ->editColumn('ppspray_target', function ($row) {
                    return number_format((int)($row['ppspray_target'] ?? 0));
                })
                ->editColumn('ppspray_production', function ($row) {
                    return number_format((int)($row['ppspray_production'] ?? 0));
                })
                ->editColumn('seconddryfinal_target', function ($row) {
                    return number_format((int)($row['seconddryfinal_target'] ?? 0));
                })
                ->editColumn('seconddryfinal_production', function ($row) {
                    return number_format((int)($row['seconddryfinal_production'] ?? 0));
                })
                ->editColumn('seconddryfinal_receive', function ($row) {
                    return number_format((int)($row['seconddryfinal_receive'] ?? 0));
                })
                ->editColumn('seconddryfinal_delivery', function ($row) {
                    return number_format((int)($row['seconddryfinal_delivery'] ?? 0));
                })
                ->editColumn('deviation', function ($row) {
                    $deviation = (int)($row['seconddryfinal_deviation'] ?? 0);
                    $class = $deviation > 0 ? 'text-danger' : ($deviation < 0 ? 'text-success' : '');
                    return '<span class="' . $class . ' fw-bold">' . number_format(abs($deviation)) . '</span>';
                })
                ->editColumn('defect_qty', function ($row) {
                    return number_format((int)($row['total_defect_qty'] ?? 0));
                })
                ->editColumn('manPower', function ($row) {
                    return number_format((int)($row['manPower'] ?? 0));
                })
                ->editColumn('workingHr', function ($row) {
                    return number_format((float)($row['workingHr'] ?? 0), 2);
                })
                ->editColumn('smv', function ($row) {
                    return number_format((float)($row['smv'] ?? 0), 2);
                })
                ->editColumn('remarks', function ($row) {
                    return $row['remarks'] ?? '-';
                })
                ->rawColumns(['deviation'])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error('Error in getSecondDryProcessData: ' . $e->getMessage());
            return DataTables::of([])->make(true);
        }
    }

    // Add to WashReportDashboardController.php

    /**
     * Save a new remark for Dry Process
     */
    /**
     * Save a new remark for Dry Process
     */
    public function saveDryProcessRemark(Request $request)
    {
        try {
            $request->validate([
                'plant' => 'required|string',
                'date' => 'required|date',
                'remark' => 'required|string',
                'process_type' => 'required|string' // 'first_dry' or 'second_dry'
            ]);

            $plant = $request->plant;
            $date = $request->date;
            $remark = $request->remark;
            $processType = $request->process_type;

            // Find or create DryProcessManual record for this date and plant
            $entry = DryProcessManual::where('plantName', $plant)
                ->where('date', $date)
                ->first();

            if (!$entry) {
                // Create new entry
                $entry = DryProcessManual::create([
                    'date' => $date,
                    'plantName' => $plant,
                    'whisker_target' => 0,
                    'whisker_production' => 0,
                    'handBrush_target' => 0,
                    'handBrush_production' => 0,
                    'FirstDryFinal_target' => 0,
                    'FirstDryFinal_production' => 0,
                    'FirstDryFinal_defectQty' => 0,
                    'SecondDryFinal_target' => 0,
                    'SecondDryFinal_production' => 0,
                    'SecondDryFinal_defectQty' => 0
                ]);
            }

            // Save to appropriate remark column based on process type
            if ($processType === 'first_dry') {
                $entry->remarks = $remark;
                $message = '1st Dry Process remark saved successfully!';
            } else {
                $entry->remarks2 = $remark;
                $message = '2nd Dry Process remark saved successfully!';
            }

            $entry->save();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $entry
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving dry process remark: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving remark: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing dry process remark
     */
    public function updateDryProcessRemark(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer',
                'remark' => 'required|string',
                'process_type' => 'required|string'
            ]);

            $entry = DryProcessManual::findOrFail($request->id);

            if ($request->process_type === 'first_dry') {
                $entry->remarks = $request->remark;
            } else {
                $entry->remarks2 = $request->remark;
            }

            $entry->save();

            return response()->json([
                'success' => true,
                'message' => 'Remark updated successfully!',
                'data' => $entry
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating dry process remark: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating remark: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get remarks for dry process
     */
    public function getDryProcessRemarks(Request $request)
    {
        try {
            $plant = $request->plant;
            $processType = $request->process_type;

            $remarks = DryProcessManual::where('plantName', $plant)
                ->whereNotNull('remarks')
                ->where('remarks', '!=', '')
                ->orderBy('date', 'desc')
                ->get(['id', 'date', 'remarks']);

            return response()->json([
                'success' => true,
                'data' => $remarks
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting dry process remarks: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting remarks: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper function: Get Second Dry data from original query (for recent dates)
     */
    private function getSecondDryDataFromQuery($fromDate, $toDate)
    {
        try {
            // ========== PART 1: GET LASER & PP SPRAY DATA ==========
            $laserData = SecondDryProcessEntry::select(
                'plant',
                DB::raw('SUM(TargetQty) as total_target'),
                DB::raw('SUM(ProductionQty) as total_production'),
                DB::raw('SUM(defectQty) as total_defect')
            )
                ->where('processType', 'Laser')
                ->whereBetween('date', [$fromDate, $toDate])
                ->groupBy('plant')
                ->get()
                ->keyBy('plant');

            $ppSprayData = SecondDryProcessEntry::select(
                'plant',
                DB::raw('SUM(TargetQty) as total_target'),
                DB::raw('SUM(ProductionQty) as total_production'),
                DB::raw('SUM(defectQty) as total_defect')
            )
                ->where('processType', 'PP Spray')
                ->whereBetween('date', [$fromDate, $toDate])
                ->groupBy('plant')
                ->get()
                ->keyBy('plant');

            // ========== PART 2: GET 2ND DRY FINAL DATA ==========
            // TPL from QC database
            $query = "
            SELECT
                fc.PlantName,
                SUM(fc.ProductionQty) AS ProductionQty,
                SUM(fc.DefectQty) AS DefectQty,
                SUM(ISNULL(t.DailyTarget, 0)) AS TargetQty
            FROM
            (
                SELECT 
                    p.PlantName,
                    pu.UnitName,
                    fq.QcDate,
                    fq.WashProcessId,
                    SUM(CASE WHEN fq.QcStatusId IN (1,3) THEN 1 ELSE 0 END) AS ProductionQty,
                    SUM(CASE WHEN fq.QcStatusId = 2 THEN 1 ELSE 0 END) AS DefectQty
                FROM [DHU_WashDB].[dbo].[FirstDryProcessQc] fq
                JOIN FirstDryProcess fp ON fq.FirstDryProcessId = fp.Id
                JOIN PlantUnit pu ON fp.UnitId = pu.id
                JOIN Plant p ON pu.PlantId = p.Id
                WHERE fq.IsDeleted = 0
                  AND fq.QcDate BETWEEN ? AND ?
                GROUP BY 
                    p.PlantName,
                    pu.UnitName,
                    fq.QcDate,
                    fq.WashProcessId
            ) fc
            LEFT JOIN
            (
                SELECT 
                    wh.WorkingHourDay AS [Date],
                    whdpm.WashProcessId,
                    SUM(whdpm.DailyTarget) AS DailyTarget
                FROM WorkingHourDetailManPower whdpm
                JOIN WorkingHourDetail whd ON whdpm.WorkingHourDetailId = whd.Id
                JOIN WorkingHour wh ON whd.WorkingHourId = wh.Id
                WHERE wh.WorkingHourDay BETWEEN ? AND ?
                GROUP BY 
                    wh.WorkingHourDay,
                    whdpm.WashProcessId
            ) t
                ON t.WashProcessId = fc.WashProcessId
                AND t.[Date] = fc.QcDate
            JOIN WashProcess wp 
                ON wp.Id = fc.WashProcessId
            WHERE wp.ProcessName = '2nd Dry Final'
            GROUP BY fc.PlantName
            ORDER BY fc.PlantName;
        ";

            $params = [$fromDate, $toDate, $fromDate, $toDate];
            $secondDryFinalResults = DB::connection('sqlsrv_second')->select($query, $params);

            // TWL 2nd Dry Final from SecondDryProcessEntry
            $twlSecondDryFinalData = SecondDryProcessEntry::select(
                DB::raw("'TWL' as PlantName"),
                DB::raw('SUM(TargetQty) as TargetQty'),
                DB::raw('SUM(ProductionQty) as ProductionQty'),
                DB::raw('SUM(defectQty) as DefectQty')
            )
                ->where('plant', 'TWL')
                ->where('processType', '2nd Dry Final')
                ->whereBetween('date', [$fromDate, $toDate])
                ->groupBy('plant')
                ->first();

            // ========== PART 3: BUILD RESULT ARRAY ==========
            $allPlants = ['TPL', 'TWL'];
            $combinedData = [];

            foreach ($allPlants as $plant) {
                $combinedData[$plant] = [
                    'plant' => $plant,
                    'laser_target' => 0,
                    'laser_production' => 0,
                    'laser_defect' => 0,
                    'ppspray_target' => 0,
                    'ppspray_production' => 0,
                    'ppspray_defect' => 0,
                    'seconddryfinal_target' => 0,
                    'seconddryfinal_production' => 0,
                    'seconddryfinal_defect' => 0,
                    'seconddryfinal_deviation' => 0,
                    'total_defect_qty' => 0,
                ];
            }

            // Add Laser data
            foreach ($laserData as $plant => $item) {
                if (isset($combinedData[$plant])) {
                    $combinedData[$plant]['laser_target'] = (int)$item->total_target;
                    $combinedData[$plant]['laser_production'] = (int)$item->total_production;
                    $combinedData[$plant]['laser_defect'] = (int)($item->total_defect ?? 0);
                    $combinedData[$plant]['total_defect_qty'] += (int)($item->total_defect ?? 0);
                }
            }

            // Add PP Spray data
            foreach ($ppSprayData as $plant => $item) {
                if (isset($combinedData[$plant])) {
                    $combinedData[$plant]['ppspray_target'] = (int)$item->total_target;
                    $combinedData[$plant]['ppspray_production'] = (int)$item->total_production;
                    $combinedData[$plant]['ppspray_defect'] = (int)($item->total_defect ?? 0);
                    $combinedData[$plant]['total_defect_qty'] += (int)($item->total_defect ?? 0);
                }
            }

            // Add 2nd Dry Final data from QC database (TPL)
            foreach ($secondDryFinalResults as $row) {
                $plant = $row->PlantName;
                if ($plant == 'TWL') continue;

                if (isset($combinedData[$plant])) {
                    $combinedData[$plant]['seconddryfinal_target'] = (int)$row->TargetQty;
                    $combinedData[$plant]['seconddryfinal_production'] = (int)$row->ProductionQty;
                    $combinedData[$plant]['seconddryfinal_defect'] = (int)($row->DefectQty ?? 0);
                    $combinedData[$plant]['total_defect_qty'] += (int)($row->DefectQty ?? 0);
                }
            }

            // Add TWL 2nd Dry Final
            if ($twlSecondDryFinalData && isset($combinedData['TWL'])) {
                $combinedData['TWL']['seconddryfinal_target'] = (int)$twlSecondDryFinalData->TargetQty;
                $combinedData['TWL']['seconddryfinal_production'] = (int)$twlSecondDryFinalData->ProductionQty;
                $combinedData['TWL']['seconddryfinal_defect'] = (int)($twlSecondDryFinalData->DefectQty ?? 0);
                $combinedData['TWL']['total_defect_qty'] += (int)($twlSecondDryFinalData->DefectQty ?? 0);
            }

            return $combinedData;
        } catch (\Exception $e) {
            \Log::error('Error in getSecondDryDataFromQuery: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get Dryer Data (Aggregated by unit across date range)
     */
    public function getDryerData(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        if (!$fromDate || !$toDate) {
            $toDate = now()->toDateString();
            $fromDate = now()->subDays(7)->toDateString();
        }

        try {
            // First, let's check if there's any data at all
            $anyData = Dryer::whereBetween('date', [$fromDate, $toDate])->get();
            \Log::info('Any Dryer Data Found:', ['count' => $anyData->count(), 'data' => $anyData->toArray()]);

            // Get all units first to ensure we have them
            $allUnits = ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL'];

            // Get aggregated dryer data grouped by unit
            $dryerData = Dryer::select(
                'unit',
                DB::raw('COALESCE(AVG(num_dryer), 0) as avg_num_dryer'),
                DB::raw('COALESCE(AVG(avg_dryer_time), 0) as avg_avg_dryer_time'),
                DB::raw('COALESCE(AVG(avg_batch), 0) as avg_avg_batch'),
                DB::raw('COALESCE(AVG(capacity), 0) as avg_capacity'),
                // DB::raw('COALESCE(SUM(targetQty), 0) as total_targetQty'), // COMMENTED OUT
                DB::raw('COALESCE(SUM(first_wash_dryer), 0) as total_first_wash_dryer'),
                DB::raw('COALESCE(SUM(cold_dryer), 0) as total_cold_dryer'),
                DB::raw('COALESCE(SUM(measurement_correction), 0) as total_measurement_correction'),
                DB::raw('COALESCE(SUM(final_wash_dryer), 0) as total_final_wash_dryer')
            )
                ->whereBetween('date', [$fromDate, $toDate])
                ->groupBy('unit')
                ->get()
                ->keyBy('unit');

            \Log::info('Aggregated Dryer Data:', $dryerData->toArray());

            // Get delivery data (Process 316 - Send from Wash)
            $deliveryQuery = "
            SELECT 
                wop.UD_WashUnit,
                COALESCE(SUM(wop.Quantity), 0) as total_delivery
            FROM [TusukaExtreme].[dbo].[MA_WorkOrderProduction] wop
            JOIN MA_WorkOrderItem woi ON wop.WorkOrderItemId = woi.RecId
            JOIN MA_WorkOrder wo ON woi.WorkOrderId = wo.RecId
            JOIN MA_Process p ON wop.ProcessId = p.RecId
            WHERE p.RecId IN (316)
                AND wop.ProductionDate BETWEEN ? AND ?
            GROUP BY wop.UD_WashUnit
        ";

            $deliveryParams = [$fromDate, $toDate];
            $deliveryResults = DB::connection('sqlsrv')->select($deliveryQuery, $deliveryParams);

            // Create delivery map
            $deliveryMap = [];
            foreach ($deliveryResults as $row) {
                $unit = $row->UD_WashUnit;
                $deliveryMap[$unit] = (float)$row->total_delivery;
            }

            \Log::info('Delivery Map:', $deliveryMap);

            // Build final data array
            $finalData = [];

            foreach ($allUnits as $unit) {
                $data = $dryerData->get($unit);

                // Get values from database or use defaults
                $avgNumDryer = $data ? (float)$data->avg_num_dryer : Dryer::getUnitDryerCount($unit);
                $avgDryerTime = $data ? (float)$data->avg_avg_dryer_time : 0;
                $avgBatch = $data ? (float)$data->avg_avg_batch : 0;
                $avgCapacity = $data ? (float)$data->avg_capacity : 0;
                // $totalTargetQty = $data ? (float)$data->total_targetQty : 0; // COMMENTED OUT
                $totalFirstWash = $data ? (float)$data->total_first_wash_dryer : 0;
                $totalColdDryer = $data ? (float)$data->total_cold_dryer : 0;
                $totalMeasCorrection = $data ? (float)$data->total_measurement_correction : 0;
                $totalFinalWash = $data ? (float)$data->total_final_wash_dryer : 0;

                // Get delivery quantity
                $deliveryQty = 0;
                if (isset($deliveryMap[$unit])) {
                    $deliveryQty = $deliveryMap[$unit];
                } elseif ($unit == 'Unit 4') {
                    // Try to get from variants
                    $deliveryQty = ($deliveryMap['Unit 4 (Denim)'] ?? 0) + ($deliveryMap['Unit 4 (Dyeing)'] ?? 0);
                }

                $totalDryer = $totalFirstWash + $totalColdDryer + $totalMeasCorrection + $totalFinalWash;
                $deviation = $totalFinalWash - $deliveryQty;

                \Log::info("Processing $unit - Avg Dryer Time: $avgDryerTime, Capacity: $avgCapacity");

                $finalData[] = [
                    'unit' => $unit,
                    'num_dryer' => $avgNumDryer,
                    'avg_batch' => $avgBatch,
                    'avg_dryer_time' => $avgDryerTime,
                    'capacity' => $avgCapacity,
                    // 'target_qty' => $totalTargetQty, // COMMENTED OUT
                    'first_wash_dryer' => $totalFirstWash,
                    'cold_dryer' => $totalColdDryer,
                    'measurement_correction' => $totalMeasCorrection,
                    'final_wash_dryer' => $totalFinalWash,
                    'total_dryer' => $totalDryer,
                    'deviation_raw' => $deviation,
                    'deviation' => $deviation,
                ];
            }

            \Log::info('Final Data (raw):', $finalData);

            // IMPORTANT: Return RAW numbers here so JavaScript can calculate totals
            return DataTables::of($finalData)
                ->editColumn('unit', function ($row) {
                    return $row['unit'];
                })
                ->editColumn('num_dryer', function ($row) {
                    return round($row['num_dryer']);
                })
                ->editColumn('avg_batch', function ($row) {
                    return round($row['avg_batch']);
                })
                ->editColumn('avg_dryer_time', function ($row) {
                    return round($row['avg_dryer_time']);
                })
                ->editColumn('capacity', function ($row) {
                    return round($row['capacity']);
                })
                ->editColumn('first_wash_dryer', function ($row) {
                    return round($row['first_wash_dryer']);
                })
                ->editColumn('cold_dryer', function ($row) {
                    return round($row['cold_dryer']);
                })
                ->editColumn('measurement_correction', function ($row) {
                    return round($row['measurement_correction']);
                })
                ->editColumn('final_wash_dryer', function ($row) {
                    return round($row['final_wash_dryer']);
                })
                ->editColumn('total_dryer', function ($row) {
                    return round($row['total_dryer']);
                })
                ->editColumn('deviation', function ($row) {
                    $deviation = (float)$row['deviation'];
                    // Keep color logic: Positive = Success (Green), Negative = Danger (Red)
                    $class = $deviation > 0 ? 'text-success' : ($deviation < 0 ? 'text-danger' : '');
                    // Use abs() to remove the minus sign for display
                    return '<span class="' . $class . ' fw-bold">' . number_format(abs($deviation), 0) . '</span>';
                })
                ->rawColumns(['deviation'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error in getDryerData: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return DataTables::of([])->make(true);
        }
    }

    /**
     * Get Transfer Data for Dashboard
     */
    public function getTransferData(Request $request)
    {
        try {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;

            if (!$fromDate || !$toDate) {
                $toDate = now()->toDateString();
                $fromDate = now()->subDays(7)->toDateString();
            }

            // Get all units
            $units = Unit::all();

            // Prepare aggregated transfer data array
            $transferData = [];

            foreach ($units as $unit) {

                // SKIP Acid Wash unit - do not show in transfer table
                if ($unit->unitName == 'Acid Wash' || $unit->unitName == 'Unit Off') {
                    continue;
                }

                // Get ALL transfers for this unit across the date range (not per date)
                $transfersIn = MachineTransfer::with(['fromUnit', 'toUnit'])
                    ->where('to_unit_id', $unit->id)
                    ->whereBetween('transfer_date', [$fromDate, $toDate])
                    ->get();

                $transfersOut = MachineTransfer::with(['fromUnit', 'toUnit'])
                    ->where('from_unit_id', $unit->id)
                    ->whereBetween('transfer_date', [$fromDate, $toDate])
                    ->get();

                // Sum total machine counts across all dates
                $transfersInCount = $transfersIn->sum('machine_count');
                $transfersOutCount = $transfersOut->sum('machine_count');

                // Format transfer in details - but now aggregated by source unit
                $transferInDetails = [];
                $transferInSummary = [];

                foreach ($transfersIn as $transfer) {
                    $fromUnitName = $transfer->fromUnit->unitName ?? 'Unknown';
                    if (!isset($transferInSummary[$fromUnitName])) {
                        $transferInSummary[$fromUnitName] = 0;
                    }
                    $transferInSummary[$fromUnitName] += $transfer->machine_count;
                }

                // Create summary strings (aggregated)
                foreach ($transferInSummary as $fromUnit => $count) {
                    $transferInDetails[] = $fromUnit . ' → ' . $count . 'MC';
                }
                $transferInText = !empty($transferInDetails) ? implode('<br>', $transferInDetails) : '-';

                // Format transfer out details - aggregated by destination unit
                $transferOutDetails = [];
                $transferOutSummary = [];

                foreach ($transfersOut as $transfer) {
                    $toUnitName = $transfer->toUnit->unitName ?? 'Unknown';
                    if (!isset($transferOutSummary[$toUnitName])) {
                        $transferOutSummary[$toUnitName] = 0;
                    }
                    $transferOutSummary[$toUnitName] += $transfer->machine_count;
                }

                // Create summary strings (aggregated)
                foreach ($transferOutSummary as $toUnit => $count) {
                    $transferOutDetails[] = $toUnit . ' → ' . $count . 'MC';
                }
                $transferOutText = !empty($transferOutDetails) ? implode('<br>', $transferOutDetails) : '-';

                // Get base machine count (using the latest date or just unit default)
                $baseMachineCount = $this->getBaseMachineCountForDate($unit->id, $toDate);

                // Calculate used machine count (base - total out + total in) across all dates
                $usedMachineCount = $baseMachineCount - $transfersOutCount + $transfersInCount;

                // Calculate MG Target per machine
                $mgTargetPerMachine = $baseMachineCount > 0 ? ($unit->mgTarget ?? 0) / $baseMachineCount : 0;
                $baseMgTarget = $unit->mgTarget ?? 0;
                $currentMgTarget = $usedMachineCount * $mgTargetPerMachine;

                // Capacity calculations
                $capacityPiecesPerMachine = $baseMachineCount > 0 ? ($unit->capacity_pieces ?? 0) / $baseMachineCount : 0;
                $capacityKgPerMachine = $baseMachineCount > 0 ? ($unit->capacity_kg ?? 0) / $baseMachineCount : 0;

                $baseCapacityPieces = $unit->capacity_pieces ?? 0;
                $baseCapacityKg = $unit->capacity_kg ?? 0;

                $currentCapacityPieces = $usedMachineCount * $capacityPiecesPerMachine;
                $currentCapacityKg = $usedMachineCount * $capacityKgPerMachine;

                // Add ONE entry per unit (not per date)
                $transferData[] = [
                    'unit' => $unit->unitName,
                    'unit_id' => $unit->id,
                    // Machine section
                    'existing_mc' => (int)$baseMachineCount,
                    'used_mc' => (int)$usedMachineCount,
                    // Transfer details (aggregated)
                    'transfer_in_details' => $transferInText,
                    'transfer_out_details' => $transferOutText,
                    'transfer_in_total' => (int)$transfersInCount,
                    'transfer_out_total' => (int)$transfersOutCount,
                    // MG Target section - ROUNDED
                    'base_mg_target' => (int)round($baseMgTarget),
                    'current_mg_target' => (int)round($currentMgTarget),
                    // Capacity Pieces section - ROUNDED
                    'base_capacity_pieces' => (int)round($baseCapacityPieces),
                    'current_capacity_pieces' => (int)round($currentCapacityPieces),
                    // Capacity KG section - ROUNDED
                    'base_capacity_kg' => (int)round($baseCapacityKg),
                    'current_capacity_kg' => (int)round($currentCapacityKg),
                ];
            }

            // Sort by unit name
            usort($transferData, function ($a, $b) {
                return strcmp($a['unit'], $b['unit']);
            });

            // Get summary statistics
            $summary = [
                'total_transfers' => MachineTransfer::whereBetween('transfer_date', [$fromDate, $toDate])->count(),
                'verified_transfers' => MachineTransfer::whereBetween('transfer_date', [$fromDate, $toDate])
                    ->where('status', 1)->count(),
                'pending_transfers' => MachineTransfer::whereBetween('transfer_date', [$fromDate, $toDate])
                    ->where('status', 0)->count(),
                'rejected_transfers' => MachineTransfer::whereBetween('transfer_date', [$fromDate, $toDate])
                    ->where('status', 2)->count(),
                'total_machines_moved' => (int)MachineTransfer::whereBetween('transfer_date', [$fromDate, $toDate])
                    ->sum('machine_count'),
            ];

            return DataTables::of($transferData)
                ->with('summary', $summary)
                ->editColumn('unit', function ($row) {
                    return '<span class="fw-bold">' . $row['unit'] . '</span>';
                })
                ->editColumn('existing_mc', function ($row) {
                    return number_format($row['existing_mc']);
                })
                ->editColumn('used_mc', function ($row) {
                    return number_format($row['used_mc']);
                })
                ->editColumn('transfer_in_details', function ($row) {
                    if ($row['transfer_in_details'] == '-') {
                        return '<span class="text-muted">-</span>';
                    }
                    return '<span class="text-success small">' . $row['transfer_in_details'] . '</span>';
                })
                ->editColumn('transfer_out_details', function ($row) {
                    if ($row['transfer_out_details'] == '-') {
                        return '<span class="text-muted">-</span>';
                    }
                    return '<span class="text-danger small">' . $row['transfer_out_details'] . '</span>';
                })
                ->editColumn('base_mg_target', function ($row) {
                    return number_format($row['base_mg_target']);
                })
                ->editColumn('current_mg_target', function ($row) {
                    $class = $row['current_mg_target'] > $row['base_mg_target'] ? 'text-success' : ($row['current_mg_target'] < $row['base_mg_target'] ? 'text-danger' : '');
                    return '<span class="' . $class . '">' . number_format($row['current_mg_target']) . '</span>';
                })
                ->editColumn('base_capacity_pieces', function ($row) {
                    return number_format($row['base_capacity_pieces']);
                })
                ->editColumn('current_capacity_pieces', function ($row) {
                    $class = $row['current_capacity_pieces'] > $row['base_capacity_pieces'] ? 'text-success' : ($row['current_capacity_pieces'] < $row['base_capacity_pieces'] ? 'text-danger' : '');
                    return '<span class="' . $class . '">' . number_format($row['current_capacity_pieces']) . '</span>';
                })
                ->editColumn('base_capacity_kg', function ($row) {
                    return number_format($row['base_capacity_kg']);
                })
                ->editColumn('current_capacity_kg', function ($row) {
                    $class = $row['current_capacity_kg'] > $row['base_capacity_kg'] ? 'text-success' : ($row['current_capacity_kg'] < $row['base_capacity_kg'] ? 'text-danger' : '');
                    return '<span class="' . $class . '">' . number_format($row['current_capacity_kg']) . '</span>';
                })
                ->rawColumns([
                    'unit',
                    'existing_mc',
                    'used_mc',
                    'transfer_in_details',
                    'transfer_out_details',
                    'base_mg_target',
                    'current_mg_target',
                    'base_capacity_pieces',
                    'current_capacity_pieces',
                    'base_capacity_kg',
                    'current_capacity_kg'
                ])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error('Error in getTransferData: ' . $e->getMessage());
            return DataTables::of([])->make(true);
        }
    }

    /**
     * Helper function to get dates in range
     */
    private function getDatesInRange($startDate, $endDate)
    {
        $dates = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current <= $end) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }

        return $dates;
    }

    /**
     * Helper function to get base machine count for a unit on a specific date
     */
    private function getBaseMachineCountForDate($unitId, $date)
    {
        // You can implement this based on your business logic
        // For now, returning the default machine count from the unit
        $unit = Unit::find($unitId);
        return $unit ? ($unit->machineCount ?? 0) : 0;
    }


    /**
     * Get the latest date from manpower records
     */
    public function getLatestDate()
    {
        $latestDate = WashReportManPower::max('date');

        return response()->json([
            'latest_date' => $latestDate ? Carbon::parse($latestDate)->format('d-m-Y') : 'No data found'
        ]);
    }

    /**
     * RDB Report Index page
     */
    public function rdbIndex()
    {
        return view('backend.rdb-report.index');
    }

    /**
     * RDB Report data: date-wise Receive / Delivery / In Hand Balance
     * for Unit-01, Unit-02, Unit-03, Unit-04, TWL and Total.
     * Dates shown month-wise from day 1 up to today (till now).
     * Same data source as the Wash Report Dashboard (SQL Server + WashReportEntry).
     */
    public function getRdbData(Request $request)
    {
        try {
            $type = $request->input('type', 'month');

            if ($type === 'year') {
                $year = $request->input('year', now()->format('Y'));

                return response()->json($this->buildRdbYearlyReportData($year));
            }

            $month = $request->input('month', now()->format('Y-m'));

            return response()->json($this->buildRdbReportData($month));
        } catch (\Exception $e) {
            \Log::error('Error in getRdbData: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load RDB report data.'], 500);
        }
    }

    /**
     * Build the RDB report data array (shared by JSON endpoint & PDF export)
     */
    private function buildRdbReportData($month)
    {
        try {
            $monthStart = Carbon::parse($month . '-01')->startOfMonth();
        } catch (\Exception $e) {
            $monthStart = now()->startOfMonth();
            $month = $monthStart->format('Y-m');
        }

            $today = now()->toDateString();
            $start = $monthStart->toDateString();

            // Day 1 to till now for the current month; full month for previous months
            $end = $monthStart->copy()->endOfMonth()->toDateString();
            if ($end > $today) {
                $end = $today;
            }
            if ($end < $start) {
                $end = $start;
            }

            $dates = $this->getDatesInRange($start, $end);

            $rdbUnits = ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL'];

            $denimUnit = new \stdClass();
            $denimUnit->unitName = 'Unit 4 (Denim)';
            $dyeingUnit = new \stdClass();
            $dyeingUnit->unitName = 'Unit 4 (Dyeing)';

            /*
            | In Hand Balance logic:
            | Opening = last stored in_hand_balance before the month starts.
            | If a date has a stored value (WashReportEntry), use it.
            | Otherwise carry forward: previous balance + Receive - Delivery.
            */
            $runningBalances = [];
            foreach ($rdbUnits as $unitName) {
                $lastEntry = WashReportEntry::where('unit', $unitName)
                    ->where('date', '<', $start)
                    ->whereNotNull('in_hand_balance')
                    ->orderBy('date', 'desc')
                    ->first();
                $runningBalances[$unitName] = $lastEntry ? (int)$lastEntry->in_hand_balance : 0;
            }

            $balanceEntries = WashReportEntry::whereBetween('date', [$start, $end])
                ->whereNotNull('in_hand_balance')
                ->get()
                ->groupBy(function ($entry) {
                    return $entry->unit . '|' . Carbon::parse($entry->date)->format('Y-m-d');
                });

            $rows = [];

            foreach ($dates as $dateStr) {
                $receive = [];
                $delivery = [];
                $inHand = [];

                foreach ($rdbUnits as $unitName) {
                    if ($unitName === 'Unit 4') {
                        // Unit 4 is combined internally: Denim + Dyeing
                        $denimData = $this->getWashProductionDataForUnitAndDate($dateStr, $denimUnit);
                        $dyeingData = $this->getWashProductionDataForUnitAndDate($dateStr, $dyeingUnit);

                        $received = (int)($denimData['received'] ?? 0) + (int)($dyeingData['received'] ?? 0);
                        $delivered = (int)($denimData['delivery'] ?? 0) + (int)($dyeingData['delivery'] ?? 0);
                    } else {
                        $unitObj = new \stdClass();
                        $unitObj->unitName = $unitName;

                        $washData = $this->getWashProductionDataForUnitAndDate($dateStr, $unitObj);

                        $received = (int)($washData['received'] ?? 0);
                        $delivered = (int)($washData['delivery'] ?? 0);
                    }

                    /*
                    | NOTE: Key mapping is inverted in getWashProductionDataForUnitAndDate():
                    | 'received' = "Send from Wash" process  -> actual Delivery
                    | 'delivery' = "Received from Sewing"    -> actual Receive
                    | The Wash Report Dashboard view swaps these when rendering,
                    | so RDB Report does the same to stay consistent.
                    */
                    $receive[$unitName] = $delivered;
                    $delivery[$unitName] = $received;

                    $storedEntry = $balanceEntries->get($unitName . '|' . $dateStr)?->first();
                    if ($storedEntry) {
                        $runningBalances[$unitName] = (int)($storedEntry->in_hand_balance ?? 0);
                    } else {
                        $runningBalances[$unitName] = $runningBalances[$unitName] + $delivered - $received;
                    }

                    $inHand[$unitName] = $runningBalances[$unitName];
                }

                $receive['total'] = array_sum($receive);
                $delivery['total'] = array_sum($delivery);
                $inHand['total'] = array_sum($inHand);

                $rows[] = [
                    'date' => Carbon::parse($dateStr)->format('d-m-Y'),
                    'receive' => $receive,
                    'delivery' => $delivery,
                    'in_hand' => $inHand,
                ];
            }

            $unitKeys = array_merge($rdbUnits, ['total']);
            $dayCount = max(1, count($rows));

            $totals = ['receive' => array_fill_keys($unitKeys, 0), 'delivery' => array_fill_keys($unitKeys, 0), 'in_hand' => array_fill_keys($unitKeys, 0)];
            $averages = ['receive' => array_fill_keys($unitKeys, 0), 'delivery' => array_fill_keys($unitKeys, 0), 'in_hand' => array_fill_keys($unitKeys, 0)];

            foreach ($rows as $row) {
                foreach ($unitKeys as $key) {
                    $totals['receive'][$key] += (int)$row['receive'][$key];
                    $totals['delivery'][$key] += (int)$row['delivery'][$key];
                    // For In Hand Balance, keep the closing (latest date) balance in Total
                    $totals['in_hand'][$key] = (int)$row['in_hand'][$key];
                }
            }

            foreach ($unitKeys as $key) {
                $averages['receive'][$key] = round($totals['receive'][$key] / $dayCount, 1);
                $averages['delivery'][$key] = round($totals['delivery'][$key] / $dayCount, 1);
                $averages['in_hand'][$key] = round($totals['in_hand'][$key] / $dayCount, 1);
            }

            return [
                'type' => 'monthly',
                'month' => $month,
                'month_label' => $monthStart->format('F Y'),
                'date_range' => Carbon::parse($start)->format('d-m-Y') . ' to ' . Carbon::parse($end)->format('d-m-Y'),
                'days' => $dayCount,
                'rows' => $rows,
                'totals' => $totals,
                'averages' => $averages,
            ];
    }

    /**
     * Build the yearly RDB report data array (shared by JSON endpoint & PDF export):
     * month-wise Receive / Delivery totals with Avg per day, GRAND TOTAL and Avg per Month.
     */
    private function buildRdbYearlyReportData($year)
    {
        try {
            $yearStart = Carbon::createFromDate((int)$year, 1, 1)->startOfYear();
        } catch (\Exception $e) {
            $yearStart = now()->startOfYear();
        }
        $year = $yearStart->format('Y');

        $today = now()->toDateString();
        $start = $yearStart->toDateString();
        $end = $yearStart->copy()->endOfYear()->toDateString();
        if ($end > $today) {
            $end = $today;
        }

        $rdbUnits = ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL'];
        $unitKeys = array_merge($rdbUnits, ['total']);

        // Fetch the whole year in one query per unit (Unit 4 = Denim + Dyeing)
        $unitData = [];
        foreach ($rdbUnits as $unitName) {
            if ($unitName === 'Unit 4') {
                $denimUnit = new \stdClass();
                $denimUnit->unitName = 'Unit 4 (Denim)';
                $dyeingUnit = new \stdClass();
                $dyeingUnit->unitName = 'Unit 4 (Dyeing)';

                $unitData[$unitName] = $this->mergeRdbRangeMaps(
                    $this->getWashProductionDataForUnitAndDateRange($start, $end, $denimUnit),
                    $this->getWashProductionDataForUnitAndDateRange($start, $end, $dyeingUnit)
                );
            } else {
                $unitObj = new \stdClass();
                $unitObj->unitName = $unitName;

                $unitData[$unitName] = $this->getWashProductionDataForUnitAndDateRange($start, $end, $unitObj);
            }
        }

        $rows = [];
        $monthsCount = 0;
        $totalDays = 0;

        for ($m = 1; $m <= 12; $m++) {
            $monthStart = $yearStart->copy()->month($m)->startOfMonth();
            $monthStartStr = $monthStart->toDateString();

            if ($monthStartStr > $end) {
                continue; // month not reached yet
            }

            $monthEndStr = $monthStart->copy()->endOfMonth()->toDateString();
            if ($monthEndStr > $end) {
                $monthEndStr = $end;
            }

            $receive = array_fill_keys($rdbUnits, 0);
            $delivery = array_fill_keys($rdbUnits, 0);

            foreach ($unitData as $unitName => $dateMap) {
                foreach ($dateMap as $date => $vals) {
                    if ($date >= $monthStartStr && $date <= $monthEndStr) {
                        /*
                        | NOTE: Key mapping is inverted (same as buildRdbReportData):
                        | 'delivery' = "Received from Sewing" -> actual Receive
                        | 'received' = "Send from Wash"       -> actual Delivery
                        */
                        $receive[$unitName] += (int)$vals['delivery'];
                        $delivery[$unitName] += (int)$vals['received'];
                    }
                }
            }

            $receive['total'] = array_sum($receive);
            $delivery['total'] = array_sum($delivery);

            $days = $monthStart->diffInDays(Carbon::parse($monthEndStr)) + 1;

            $rows[] = [
                'month_label' => $monthStart->format('F'),
                'days' => $days,
                'receive' => $receive,
                'delivery' => $delivery,
                'avg_receive_per_day' => $days > 0 ? round($receive['total'] / $days, 1) : 0,
                'avg_delivery_per_day' => $days > 0 ? round($delivery['total'] / $days, 1) : 0,
                'remarks' => '',
            ];

            $monthsCount++;
            $totalDays += $days;
        }

        $grandTotal = [
            'receive' => array_fill_keys($unitKeys, 0),
            'delivery' => array_fill_keys($unitKeys, 0),
            'avg_receive_per_day' => 0,
            'avg_delivery_per_day' => 0,
        ];
        $avgPerMonth = [
            'receive' => array_fill_keys($unitKeys, 0),
            'delivery' => array_fill_keys($unitKeys, 0),
            'avg_receive_per_day' => 0,
            'avg_delivery_per_day' => 0,
        ];

        $sumAvgRcv = 0;
        $sumAvgDel = 0;

        foreach ($rows as $row) {
            foreach ($unitKeys as $key) {
                $grandTotal['receive'][$key] += (int)$row['receive'][$key];
                $grandTotal['delivery'][$key] += (int)$row['delivery'][$key];
            }
            $sumAvgRcv += $row['avg_receive_per_day'];
            $sumAvgDel += $row['avg_delivery_per_day'];
        }

        $divisor = max(1, $monthsCount);

        foreach ($unitKeys as $key) {
            $avgPerMonth['receive'][$key] = round($grandTotal['receive'][$key] / $divisor, 1);
            $avgPerMonth['delivery'][$key] = round($grandTotal['delivery'][$key] / $divisor, 1);
        }

        $grandTotal['avg_receive_per_day'] = $totalDays > 0 ? round($grandTotal['receive']['total'] / $totalDays, 1) : 0;
        $grandTotal['avg_delivery_per_day'] = $totalDays > 0 ? round($grandTotal['delivery']['total'] / $totalDays, 1) : 0;
        $avgPerMonth['avg_receive_per_day'] = round($sumAvgRcv / $divisor, 1);
        $avgPerMonth['avg_delivery_per_day'] = round($sumAvgDel / $divisor, 1);

        return [
            'type' => 'yearly',
            'year' => $year,
            'year_label' => 'Year ' . $year,
            'date_range' => Carbon::parse($start)->format('d-m-Y') . ' to ' . Carbon::parse($end)->format('d-m-Y'),
            'months' => $monthsCount,
            'days' => $totalDays,
            'rows' => $rows,
            'grand_total' => $grandTotal,
            'avg_per_month' => $avgPerMonth,
        ];
    }

    /**
     * Get wash production receive/delivery sums per date for a unit over a date range (single query).
     * Key semantics match getWashProductionDataForUnitAndDate():
     * 'received' = "Send from Wash" -> actual Delivery, 'delivery' = "Received from Sewing" -> actual Receive.
     */
    private function getWashProductionDataForUnitAndDateRange($startDate, $endDate, $unit)
    {
        try {
            $unitName = $unit->unitName;

            $isUnit4Dyeing = str_contains($unitName, 'Unit 4 (Dyeing)') || str_contains($unitName, 'Unit 4 Dyeing');
            $isUnit4Denim = str_contains($unitName, 'Unit 4 (Denim)') || str_contains($unitName, 'Unit 4 Denim');

            $dbUnitName = ($isUnit4Dyeing || $isUnit4Denim) ? 'Unit 4' : $unitName;

            $query = "
            SELECT
                wop.ProductionDate,
                p.ProcessName,
                WT.UD_WashType,
                SUM(wop.Quantity) AS Quantity
            FROM [TusukaExtreme].[dbo].[MA_WorkOrderProduction] wop
            JOIN MA_WorkOrderItem woi ON wop.WorkOrderItemId = woi.RecId
            JOIN MA_Process p ON wop.ProcessId = p.RecId
            OUTER APPLY (
                SELECT DISTINCT KI.UD_WashType
                FROM TSK_WashWorkOrderItem TSK
                JOIN MA_WorkOrderItem KI ON KI.RecId = TSK.DocketWorkOrderItemId
                WHERE TSK.WashWorkOrderItemId = woi.RecId
            ) WT
            WHERE p.RecId IN (315, 316)
            AND wop.ProductionDate BETWEEN ? AND ?
            AND wop.UD_WashUnit = ?
            GROUP BY wop.ProductionDate, p.ProcessName, WT.UD_WashType
            ";

            $rows = DB::connection('sqlsrv')->select($query, [$startDate, $endDate, $dbUnitName]);

            $result = [];

            foreach ($rows as $row) {
                $date = Carbon::parse($row->ProductionDate)->format('Y-m-d');
                $washType = $row->UD_WashType ?? null;
                $quantity = (int)($row->Quantity ?? 0);

                if (!isset($result[$date])) {
                    $result[$date] = ['received' => 0, 'delivery' => 0];
                }

                $applies = true;
                if ($isUnit4Dyeing) {
                    $applies = ($washType === 'Over Dye');
                } elseif ($isUnit4Denim) {
                    $applies = ($washType !== 'Over Dye');
                }

                if ($applies) {
                    if ($row->ProcessName === 'Send from Wash') {
                        $result[$date]['received'] += $quantity;
                    } elseif ($row->ProcessName === 'Received from Sewing') {
                        $result[$date]['delivery'] += $quantity;
                    }
                }
            }

            return $result;
        } catch (\Exception $e) {
            \Log::error('SQL Server wash range data error for ' . $unit->unitName . ' (' . $startDate . ' to ' . $endDate . '): ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Merge two date => ['received','delivery'] maps (used to combine Unit 4 Denim + Dyeing)
     */
    private function mergeRdbRangeMaps(array $mapA, array $mapB)
    {
        $merged = $mapA;

        foreach ($mapB as $date => $vals) {
            if (!isset($merged[$date])) {
                $merged[$date] = ['received' => 0, 'delivery' => 0];
            }
            $merged[$date]['received'] += (int)$vals['received'];
            $merged[$date]['delivery'] += (int)$vals['delivery'];
        }

        return $merged;
    }

    /**
     * Download RDB Report as PDF (month-wise or year-wise)
     */
    public function rdbDownloadPdf(Request $request)
    {
        try {
            $type = $request->input('type', 'month');

            if ($type === 'year') {
                $year = $request->input('year', now()->format('Y'));
                $data = $this->buildRdbYearlyReportData($year);
                $filename = 'RDB_Report_Yearly_' . $data['year'] . '.pdf';
            } else {
                $month = $request->input('month', now()->format('Y-m'));
                $data = $this->buildRdbReportData($month);
                $filename = 'RDB_Report_' . Carbon::parse($data['month'] . '-01')->format('M-Y') . '.pdf';
            }

            $pdf = Pdf::loadView('backend.rdb-report.pdf', ['data' => $data]);
            $pdf->setPaper('A4', 'landscape');

            return $pdf->download($filename);
        } catch (\Exception $e) {
            \Log::error('RDB PDF Generation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save a new remark for a unit on a specific date
     */
    public function saveRemark(Request $request)
    {
        try {
            $request->validate([
                'unit' => 'required|string',
                'date' => 'required|date',
                'remark' => 'required|string'
            ]);

            $unit = $request->unit;
            $date = $request->date;
            $remark = $request->remark;

            // For Unit 4, we save directly to "Unit 4" in washreportentry
            if ($unit == 'Unit 4') {
                // Check if entry exists for Unit 4 on this date
                $entry = WashReportEntry::where('unit', 'Unit 4')
                    ->where('date', $date)
                    ->first();

                if ($entry) {
                    // Update existing entry
                    $entry->Remarks = $remark;
                    $entry->save();
                    $message = 'Remark updated for Unit 4 successfully!';
                } else {
                    // Create new entry with just the remark
                    $entry = WashReportEntry::create([
                        'date' => $date,
                        'unit' => 'Unit 4',
                        'FirstWashQty' => 0,
                        'AcidWashQty' => 0,
                        'FinalWashQty' => 0,
                        'ReWashQty' => 0,
                        'SewingLine' => '',
                        'Remarks' => $remark
                    ]);
                    $message = 'Remark added for Unit 4 successfully!';
                }

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $entry
                ]);
            } else {
                // For regular units, save normally
                $entry = WashReportEntry::where('unit', $unit)
                    ->where('date', $date)
                    ->first();

                if ($entry) {
                    $entry->Remarks = $remark;
                    $entry->save();
                    $message = 'Remark updated successfully!';
                } else {
                    $entry = WashReportEntry::create([
                        'date' => $date,
                        'unit' => $unit,
                        'FirstWashQty' => 0,
                        'AcidWashQty' => 0,
                        'FinalWashQty' => 0,
                        'ReWashQty' => 0,
                        'SewingLine' => '',
                        'Remarks' => $remark
                    ]);
                    $message = 'Remark added successfully!';
                }

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $entry
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error saving remark: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving remark: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get remarks for a unit within date range
     */
    public function getRemarks($unit)
    {
        try {
            if ($unit == 'Unit 4') {
                // Get remarks directly from Unit 4 entries
                $remarks = WashReportEntry::where('unit', 'Unit 4')
                    ->whereNotNull('Remarks')
                    ->where('Remarks', '!=', '')
                    ->orderBy('date', 'desc')
                    ->get(['id', 'date', 'Remarks']);

                return response()->json([
                    'success' => true,
                    'data' => $remarks
                ]);
            } else {
                // Get remarks for regular unit
                $remarks = WashReportEntry::where('unit', $unit)
                    ->whereNotNull('Remarks')
                    ->where('Remarks', '!=', '')
                    ->orderBy('date', 'desc')
                    ->get(['id', 'date', 'Remarks']);

                return response()->json([
                    'success' => true,
                    'data' => $remarks
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error getting remarks: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error getting remarks: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing remark
     */
    public function updateRemark(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer',
                'remark' => 'required|string'
            ]);

            $entry = WashReportEntry::findOrFail($request->id);
            $entry->Remarks = $request->remark;
            $entry->save();

            return response()->json([
                'success' => true,
                'message' => 'Remark updated successfully!',
                'data' => $entry
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating remark: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating remark: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Convert time format (HH:MM:SS or HH:MM) to decimal hours
     * Handles formats like "286:38:28" or "265:56:48"
     */
    private function convertTimeToDecimalHours($timeString)
    {
        if (empty($timeString) || $timeString == '-') {
            return 0;
        }

        // Remove any whitespace
        $timeString = trim($timeString);

        // Split by colon
        $parts = explode(':', $timeString);

        if (count($parts) == 3) {
            // Format: HH:MM:SS
            $hours = (int)$parts[0];
            $minutes = (int)$parts[1];
            $seconds = (int)$parts[2];

            return $hours + ($minutes / 60) + ($seconds / 3600);
        } elseif (count($parts) == 2) {
            // Format: HH:MM
            $hours = (int)$parts[0];
            $minutes = (int)$parts[1];

            return $hours + ($minutes / 60);
        }

        return 0;
    }


    /**
     * Machine Status Bar Chart
     */
    public function getCappMachineStatus(Request $request)
    {
        try {
            // Get date from request
            $fromDate = $request->from_date;
            $toDate = $request->to_date;

            // If no dates provided, use today
            if (!$fromDate || !$toDate) {
                $fromDate = now()->toDateString();
                $toDate = now()->toDateString();
            }

            // Define the unit mappings with their correct API endpoints
            $unitApis = [
                'Unit 1' => 'http://192.168.30.89/tusuka-wms/api/machines/statistic-data-vt/rabiul_mis@tusuka.com/7/13',
                'Unit 2' => 'http://192.168.30.89/tusuka-wms/api/machines/statistic-data-vt/rabiul_mis@tusuka.com/7/14',
                'Unit 3' => 'http://192.168.30.89/tusuka-wms/api/machines/statistic-data-vt/rabiul_mis@tusuka.com/7/18',
                'Unit 4' => 'http://192.168.30.89/tusuka-wms/api/machines/statistic-data-vt/rabiul_mis@tusuka.com/7/19',
                'Unit 5' => 'http://192.168.30.89/tusuka-wms/api/machines/statistic-data-vt/rabiul_mis@tusuka.com/7/20',
                'Unit TWL' => 'http://192.168.30.89/tusuka-wms/api/machines/statistic-data-vt/rabiul_mis@tusuka.com/8/23'
            ];

            // Define the unit order (EXCLUDING Unit 5)
            $unitOrder = ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit TWL'];

            $unitData = [];
            $periodFrom = '';
            $periodTo = '';

            // Initialize cURL multi handle for parallel requests
            $mh = curl_multi_init();
            $curlHandles = [];

            // Create cURL handles for each unit
            foreach ($unitApis as $unit => $url) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

                curl_multi_add_handle($mh, $ch);
                $curlHandles[$unit] = $ch;
            }

            // Execute all requests simultaneously
            $running = null;
            do {
                curl_multi_exec($mh, $running);
                curl_multi_select($mh);
            } while ($running > 0);

            // Get all responses and save to database
            foreach ($curlHandles as $unit => $ch) {
                $response = curl_multi_getcontent($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($httpCode === 200 && $response) {
                    $data = json_decode($response, true);

                    // Log the response for debugging
                    \Log::info("API Response for {$unit}:", ['data' => $data]);

                    if ($data) {
                        // Case 1: Handle TWL structure
                        if (isset($data['TWL'])) {
                            foreach ($data['TWL'] as $unitKey => $unitStats) {
                                // Check if this is the double-nested TWL structure
                                if ($unitKey == 'TWL' && isset($unitStats['TWL'])) {
                                    // This is the double-nested case: {"TWL": {"TWL": {...}}}
                                    $actualStats = $unitStats['TWL'];
                                    $actualUnitKey = 'TWL';
                                } else {
                                    $actualStats = $unitStats;
                                    $actualUnitKey = $unitKey;
                                }

                                // Extract the correct date from "Data Between" field
                                $dataBetween = $actualStats['Data Between'] ?? '';
                                $reportDate = now()->toDateString();

                                // Parse "2026-05-12 20:00:00 2026-05-13 20:00:00 Total Range Past: 24" to get end date
                                if (preg_match('/(\d{4}-\d{2}-\d{2}) \d{2}:\d{2}:\d{2} (\d{4}-\d{2}-\d{2})/', $dataBetween, $matches)) {
                                    $reportDate = $matches[2]; // Second date is 2026-05-13
                                }

                                // Map the unit name
                                $mappedUnit = $this->mapApiUnitToDashboardUnit($actualUnitKey);

                                if ($mappedUnit && in_array($mappedUnit, $unitOrder)) {
                                    // STORE breakdown->Total to down_duration
                                    $breakdownTotal = $actualStats['breakdown']['Total'] ?? '00:00:00';

                                    // Store in unitData array for response
                                    $unitData[$mappedUnit] = [
                                        'uptime' => $actualStats['uptime'] ?? 0,
                                        'idletime' => $actualStats['idletime'] ?? 0,
                                        'downtime' => $actualStats['downtime'] ?? 0,
                                        'up_duration' => $actualStats['up_duration'] ?? '',
                                        'idle_duration' => $actualStats['idle_duration'] ?? '',
                                        'down_duration' => $breakdownTotal, // USING breakdown->Total HERE
                                    ];

                                    // SAVE TO DATABASE with CORRECT DATE
                                    \App\Models\MachineStatus::updateOrCreate(
                                        [
                                            'unit' => $mappedUnit,
                                            'report_date' => $reportDate
                                        ],
                                        [
                                            'plant' => 'TWL',
                                            'machine_group' => $actualUnitKey,
                                            'uptime' => $actualStats['uptime'] ?? 0,
                                            'idletime' => $actualStats['idletime'] ?? 0,
                                            'downtime' => $actualStats['downtime'] ?? 0,
                                            'up_duration' => $actualStats['up_duration'] ?? '',
                                            'idle_duration' => $actualStats['idle_duration'] ?? '',
                                            'down_duration' => $breakdownTotal, // USING breakdown->Total HERE
                                            'fetched_at' => now()
                                        ]
                                    );

                                    \Log::info("Saved TWL data for {$mappedUnit} with date {$reportDate}, down_duration: {$breakdownTotal}");
                                }
                            }
                        }

                        // Case 2: Handle TPL structure
                        elseif (isset($data['TPL'])) {
                            foreach ($data['TPL'] as $unitKey => $unitStats) {
                                // Extract the correct date from "Data Between" field
                                $dataBetween = $unitStats['Data Between'] ?? '';
                                $reportDate = now()->toDateString();

                                if (preg_match('/(\d{4}-\d{2}-\d{2}) \d{2}:\d{2}:\d{2} (\d{4}-\d{2}-\d{2})/', $dataBetween, $matches)) {
                                    $reportDate = $matches[2]; // Second date is the end date
                                }

                                $mappedUnit = $this->mapApiUnitToDashboardUnit($unitKey);

                                if ($mappedUnit && in_array($mappedUnit, $unitOrder)) {
                                    // STORE breakdown->Total to down_duration
                                    $breakdownTotal = $unitStats['breakdown']['Total'] ?? '00:00:00';

                                    // Save with CORRECT DATE
                                    \App\Models\MachineStatus::updateOrCreate(
                                        [
                                            'unit' => $mappedUnit,
                                            'report_date' => $reportDate
                                        ],
                                        [
                                            'plant' => 'TPL',
                                            'machine_group' => $unitKey,
                                            'uptime' => $unitStats['uptime'] ?? 0,
                                            'idletime' => $unitStats['idletime'] ?? 0,
                                            'downtime' => $unitStats['downtime'] ?? 0,
                                            'up_duration' => $unitStats['up_duration'] ?? '',
                                            'idle_duration' => $unitStats['idle_duration'] ?? '',
                                            'down_duration' => $breakdownTotal, // USING breakdown->Total HERE
                                            'fetched_at' => now()
                                        ]
                                    );

                                    \Log::info("Saved TPL data for {$mappedUnit} with date {$reportDate}, down_duration: {$breakdownTotal}");
                                }
                            }
                        }

                        // Use the first unit's period info (if available)
                        if (empty($periodFrom)) {
                            if (isset($data['TPL'])) {
                                $firstUnit = reset($data['TPL']);
                                if (isset($firstUnit['from'])) {
                                    $periodFrom = $firstUnit['from'];
                                    $periodTo = $firstUnit['to'];
                                }
                            } elseif (isset($data['TWL'])) {
                                $firstUnit = reset($data['TWL']);
                                if (isset($firstUnit['from'])) {
                                    $periodFrom = $firstUnit['from'];
                                    $periodTo = $firstUnit['to'];
                                }
                            }
                        }
                    }
                } else {
                    \Log::warning("Failed to fetch data for {$unit}. HTTP Code: {$httpCode}");
                }
                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
            }
            curl_multi_close($mh);

            // Now, get the AVERAGE data from database for the requested date range
            $sortedUptime = [];
            $sortedIdletime = [];
            $sortedDowntime = [];
            $sortedUnitData = [];

            foreach ($unitOrder as $unit) {
                // Get average data from database for the selected date range
                $statusData = \App\Models\MachineStatus::where('unit', $unit)
                    ->whereBetween('report_date', [$fromDate, $toDate])
                    ->selectRaw('AVG(uptime) as avg_uptime, AVG(idletime) as avg_idletime, AVG(downtime) as avg_downtime')
                    ->first();

                if ($statusData && $statusData->avg_uptime !== null) {
                    $sortedUptime[] = round($statusData->avg_uptime, 2);
                    $sortedIdletime[] = round($statusData->avg_idletime, 2);
                    $sortedDowntime[] = round($statusData->avg_downtime, 2);

                    $sortedUnitData[$unit] = [
                        'uptime' => round($statusData->avg_uptime, 2),
                        'idletime' => round($statusData->avg_idletime, 2),
                        'downtime' => round($statusData->avg_downtime, 2)
                    ];
                } else {
                    // If no data in DB for this range, use today's fetched data as fallback
                    if (isset($unitData[$unit])) {
                        $sortedUptime[] = $unitData[$unit]['uptime'] ?? 0;
                        $sortedIdletime[] = $unitData[$unit]['idletime'] ?? 0;
                        $sortedDowntime[] = $unitData[$unit]['downtime'] ?? 0;

                        $sortedUnitData[$unit] = [
                            'uptime' => $unitData[$unit]['uptime'] ?? 0,
                            'idletime' => $unitData[$unit]['idletime'] ?? 0,
                            'downtime' => $unitData[$unit]['downtime'] ?? 0
                        ];
                    } else {
                        $sortedUptime[] = 0;
                        $sortedIdletime[] = 0;
                        $sortedDowntime[] = 0;

                        $sortedUnitData[$unit] = [
                            'uptime' => 0,
                            'idletime' => 0,
                            'downtime' => 0
                        ];
                    }
                }
            }

            // Format the data for the chart
            $chartData = [
                'labels' => $unitOrder,
                'datasets' => [
                    [
                        'label' => 'Downtime (%)',
                        'data' => $sortedDowntime,
                        'backgroundColor' => 'rgba(220, 53, 69, 0.7)',
                        'borderColor' => 'rgba(220, 53, 69, 1)',
                        'borderWidth' => 1
                    ],
                    [
                        'label' => 'Idletime (%)',
                        'data' => $sortedIdletime,
                        'backgroundColor' => 'rgba(255, 193, 7, 0.7)',
                        'borderColor' => 'rgba(255, 193, 7, 1)',
                        'borderWidth' => 1
                    ],
                    [
                        'label' => 'Uptime (%)',
                        'data' => $sortedUptime,
                        'backgroundColor' => 'rgba(40, 167, 69, 0.7)',
                        'borderColor' => 'rgba(40, 167, 69, 1)',
                        'borderWidth' => 1
                    ]
                ]
            ];

            return response()->json([
                'success' => true,
                'chart_data' => $chartData,
                'unit_data' => $sortedUnitData,
                'period' => [
                    'from' => $fromDate,
                    'to' => $toDate,
                    'api_from' => $periodFrom,
                    'api_to' => $periodTo
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('CAPP API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching machine status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Map API unit names to dashboard unit names
     */
    private function mapApiUnitToDashboardUnit($apiUnit)
    {
        $mapping = [
            'Unit-1' => 'Unit 1',
            'Unit-2' => 'Unit 2',
            'Unit-3' => 'Unit 3',
            'Unit-4' => 'Unit 4',
            'Unit-5' => 'Unit 5',
            'TWL' => 'Unit TWL',      // Add this for TWL structure
            'Unit-TWL' => 'Unit TWL', // Keep existing for consistency
        ];

        return $mapping[$apiUnit] ?? null;
    }

    /**
     * Generate PDF report and return the file path
     */
    public function generatePdfReport($fromDate, $toDate)
    {
        try {
            // 1. Get Unit Data & Totals
            $unitDataRequest = new Request(['from_date' => $fromDate, 'to_date' => $toDate]);
            $unitDataResponse = $this->getUnitData($unitDataRequest);
            $unitData = $unitDataResponse->getData()->data ?? [];

            $unitTotals = [
                'machines' => 0,
                'capacity_kg' => 0,
                'direct' => 0,
                'indirect' => 0,
                'total' => 0,
                'work_hours' => 0,
                'smv' => 0,
                'received' => 0,
                'delivery' => 0,
                'forecast_target' => 0,
                'deviation' => 0,
                'deviation_percent' => 0,
                'wash_ratio' => 0,
                'rewash_percent' => 0,
                'first_wash_qty' => 0,
                'acid_wash_qty' => 0,
                'final_wash_qty' => 0,
                'rewash_qty' => 0,
                'rework_dry_proc' => 0,
                'in_hand_balance' => 0,
            ];
            $unitCount = count($unitData);

            // Helper for parsing numbers (defined once)
            $parseNum = function ($val) {
                if (!$val) return 0;
                if (is_string($val)) {
                    $val = str_replace(',', '', $val);
                }
                return (float)$val;
            };

            foreach ($unitData as $u) {
                if (is_array($u)) {
                    $u = (object) $u;
                }

                // PDF total must match the Production Dashboard footer.
                // Dashboard footer sums used_mc and used_capacity_kg, not base machines/capacity.
                $unitTotals['machines'] += $parseNum($u->used_mc ?? ($u->machines ?? 0));
                $unitTotals['capacity_kg'] += $parseNum($u->used_capacity_kg ?? ($u->capacity_kg ?? 0));
                $unitTotals['direct'] += (int)($u->direct ?? 0);
                $unitTotals['indirect'] += (int)($u->indirect ?? 0);
                $unitTotals['total'] += (int)($u->total ?? 0);
                $unitTotals['work_hours'] += (float)($u->work_hours ?? 0);
                $unitTotals['smv'] += (float)($u->smv ?? 0);

                $unitTotals['received'] += $parseNum($u->received ?? 0);
                $unitTotals['delivery'] += $parseNum($u->delivery ?? 0);
                $unitTotals['forecast_target'] += $parseNum($u->forecast_target ?? 0);
                $unitTotals['first_wash_qty'] += $parseNum($u->first_wash_qty ?? 0);
                $unitTotals['acid_wash_qty'] += $parseNum($u->acid_wash_qty ?? 0);
                $unitTotals['final_wash_qty'] += $parseNum($u->final_wash_qty ?? 0);
                $unitTotals['rewash_qty'] += $parseNum($u->rewash_qty ?? 0);
                $unitTotals['rework_dry_proc'] += $parseNum($u->rework_dry_proc ?? 0);
                $unitTotals['in_hand_balance'] += $parseNum($u->in_hand_balance ?? 0);

                // Deviation (Parse absolute value from HTML string)
                $deviationRaw = $u->deviation ?? 0;
                if (is_string($deviationRaw)) {
                    $clean = strip_tags($deviationRaw);
                    $clean = str_replace(',', '', $clean);
                    $unitTotals['deviation'] += (float)$clean;
                } else {
                    $unitTotals['deviation'] += (float)$deviationRaw;
                }

                $unitTotals['wash_ratio'] += $parseNum($u->wash_ratio ?? 0);
                $unitTotals['rewash_percent'] += $parseNum($u->rewash_percent ?? 0);
            }

            if ($unitTotals['forecast_target'] > 0) {
                $unitTotals['deviation_percent'] = ($unitTotals['deviation'] / $unitTotals['forecast_target']) * 100;
            } else {
                $unitTotals['deviation_percent'] = 0;
            }

            // 2. Get First Dry Data & Totals
            $firstDryRequest = new Request(['from_date' => $fromDate, 'to_date' => $toDate]);
            $firstDryResponse = $this->getFirstDryProcessData($firstDryRequest);
            $firstDryData = $firstDryResponse->getData()->data ?? [];

            // FIX: Added 'delivery' => 0 here
            $firstDryTotals = [
                'whisker_target' => 0,
                'whisker_prod' => 0,
                'handbrush_target' => 0,
                'handbrush_prod' => 0,
                'target' => 0,
                'prod' => 0,
                'receive' => 0,
                'delivery' => 0, // <--- FIX APPLIED
                'deviation' => 0,
                'defect' => 0,
                'manpower' => 0
            ];

            foreach ($firstDryData as $d) {
                $firstDryTotals['whisker_target'] += $parseNum($d->whisker_target ?? 0);
                $firstDryTotals['whisker_prod'] += $parseNum($d->whisker_production ?? 0);
                $firstDryTotals['handbrush_target'] += $parseNum($d->handbrush_target ?? 0);
                $firstDryTotals['handbrush_prod'] += $parseNum($d->handbrush_production ?? 0);
                $firstDryTotals['target'] += $parseNum($d->firstdryfinal_target ?? 0);
                $firstDryTotals['prod'] += $parseNum($d->firstdryfinal_production ?? 0);
                $firstDryTotals['receive'] += $parseNum($d->firstdryfinal_receive ?? 0);

                // Now this works without error
                $firstDryTotals['delivery'] += $parseNum($d->firstdryfinal_delivery ?? 0);

                $firstDryTotals['defect'] += $parseNum($d->defect_qty ?? 0);
                $firstDryTotals['manpower'] += $parseNum($d->manPower ?? 0);

                $devRaw = $d->deviation ?? 0;
                if (is_string($devRaw)) {
                    $clean = strip_tags($devRaw);
                    $clean = str_replace(',', '', $clean);
                    $firstDryTotals['deviation'] += (float)$clean;
                } else {
                    $firstDryTotals['deviation'] += (float)$devRaw;
                }
            }

            // 3. Get Second Dry Data & Totals
            $secondDryRequest = new Request(['from_date' => $fromDate, 'to_date' => $toDate]);
            $secondDryResponse = $this->getSecondDryProcessData($secondDryRequest);
            $secondDryData = $secondDryResponse->getData()->data ?? [];

            // FIX: Added 'delivery' => 0 here
            $secondDryTotals = [
                'laser_target' => 0,
                'laser_prod' => 0,
                'ppspray_target' => 0,
                'ppspray_prod' => 0,
                'target' => 0,
                'prod' => 0,
                'receive' => 0,
                'delivery' => 0, // <--- FIX APPLIED
                'deviation' => 0,
                'defect' => 0,
                'manpower' => 0
            ];

            foreach ($secondDryData as $d) {
                $secondDryTotals['laser_target'] += $parseNum($d->laser_target ?? 0);
                $secondDryTotals['laser_prod'] += $parseNum($d->laser_production ?? 0);
                $secondDryTotals['ppspray_target'] += $parseNum($d->ppspray_target ?? 0);
                $secondDryTotals['ppspray_prod'] += $parseNum($d->ppspray_production ?? 0);
                $secondDryTotals['target'] += $parseNum($d->seconddryfinal_target ?? 0);
                $secondDryTotals['prod'] += $parseNum($d->seconddryfinal_production ?? 0);
                $secondDryTotals['receive'] += $parseNum($d->seconddryfinal_receive ?? 0);

                // Now this works without error
                $secondDryTotals['delivery'] += $parseNum($d->seconddryfinal_delivery ?? 0);

                $secondDryTotals['defect'] += $parseNum($d->defect_qty ?? 0);
                $secondDryTotals['manpower'] += $parseNum($d->manPower ?? 0);

                $devRaw = $d->deviation ?? 0;
                if (is_string($devRaw)) {
                    $clean = strip_tags($devRaw);
                    $clean = str_replace(',', '', $clean);
                    $secondDryTotals['deviation'] += (float)$clean;
                } else {
                    $secondDryTotals['deviation'] += (float)$devRaw;
                }
            }

            // 4. Get Transfer Data & Totals
            $transferRequest = new Request(['from_date' => $fromDate, 'to_date' => $toDate]);
            $transferResponse = $this->getTransferData($transferRequest);
            $transferData = $transferResponse->getData()->data ?? [];
            $transferTotals = ['existing_mc' => 0, 'used_mc' => 0, 'current_mg' => 0, 'current_pcs' => 0, 'current_kg' => 0];

            foreach ($transferData as $item) {
                if (isset($item->transfer_in_details)) {
                    $item->transfer_in_details_pdf = str_replace('→', '-', strip_tags($item->transfer_in_details));
                } else {
                    $item->transfer_in_details_pdf = '-';
                }
                if (isset($item->transfer_out_details)) {
                    $item->transfer_out_details_pdf = str_replace('→', '-', strip_tags($item->transfer_out_details));
                } else {
                    $item->transfer_out_details_pdf = '-';
                }
            }

            $parseTransferNum = function ($value) {
                if (!$value) return 0;
                $clean = strip_tags($value);
                $clean = str_replace(',', '', $clean);
                return (int)$clean;
            };

            foreach ($transferData as $t) {
                $transferTotals['existing_mc'] += $parseTransferNum($t->existing_mc ?? 0);
                $transferTotals['used_mc'] += $parseTransferNum($t->used_mc ?? 0);
                $transferTotals['current_mg'] += $parseTransferNum($t->current_mg_target ?? 0);
                $transferTotals['current_pcs'] += $parseTransferNum($t->current_capacity_pieces ?? 0);
                $transferTotals['current_kg'] += $parseTransferNum($t->current_capacity_kg ?? 0);
            }

            // 5. Get Dryer Data & Totals
            $dryerRequest = new Request(['from_date' => $fromDate, 'to_date' => $toDate]);
            $dryerResponse = $this->getDryerData($dryerRequest);
            $dryerData = $dryerResponse->getData()->data ?? [];
            $dryerTotals = ['num_dryer' => 0, 'capacity' => 0, 'target' => 0, 'first_wash' => 0, 'cold' => 0, 'meas' => 0, 'final_wash' => 0, 'total' => 0, 'deviation' => 0];

            foreach ($dryerData as $d) {
                $dryerTotals['num_dryer'] += (int)($d->num_dryer ?? 0);
                $dryerTotals['capacity'] += (float)($d->capacity ?? 0);
                $dryerTotals['target'] += (float)($d->target_qty ?? 0);
                $dryerTotals['first_wash'] += (float)($d->first_wash_dryer ?? 0);
                $dryerTotals['cold'] += (float)($d->cold_dryer ?? 0);
                $dryerTotals['meas'] += (float)($d->measurement_correction ?? 0);
                $dryerTotals['final_wash'] += (float)($d->final_wash_dryer ?? 0);
                $dryerTotals['total'] += (float)($d->total_dryer ?? 0);

                $devRaw = $d->deviation ?? 0;
                if (is_string($devRaw)) {
                    $clean = strip_tags($devRaw);
                    $clean = str_replace(',', '', $clean);
                    $dryerTotals['deviation'] += (float)$clean;
                } else {
                    $dryerTotals['deviation'] += (float)$devRaw;
                }
            }

            // 6. Get CAPP Data & Generate Chart
            $cappRequest = new Request(['from_date' => $fromDate, 'to_date' => $toDate]);
            $cappResponse = $this->getCappMachineStatus($cappRequest);
            $cappData = $cappResponse->getData(true);
            $chartPath = null;

            if (!empty($cappData['success'])) {
                $labels = $cappData['chart_data']['labels'];
                $downtimeData = $cappData['chart_data']['datasets'][0]['data'];
                $idletimeData = $cappData['chart_data']['datasets'][1]['data'];
                $uptimeData = $cappData['chart_data']['datasets'][2]['data'];
                try {
                    $chartPath = $this->generateLocalChart($labels, $downtimeData, $idletimeData, $uptimeData);
                } catch (\Exception $e) {
                    \Log::error('Local chart generation failed: ' . $e->getMessage());
                }
            }

            // Generate PDF
            $pdf = Pdf::loadView('backend.wash-report-dashboard.pdf', [
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'unitData' => $unitData,
                'unitTotals' => $unitTotals,
                'unitCount' => $unitCount,
                'firstDryData' => $firstDryData,
                'firstDryTotals' => $firstDryTotals,
                'secondDryData' => $secondDryData,
                'secondDryTotals' => $secondDryTotals,
                'transferData' => $transferData,
                'transferTotals' => $transferTotals,
                'dryerData' => $dryerData,
                'dryerTotals' => $dryerTotals,
                'chartPath' => $chartPath
            ]);

            $pdf->setPaper('A4', 'landscape');
            $tempDir = storage_path('app/reports');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            $filename = 'Wash_Report_' . Carbon::parse($fromDate)->format('d-m-Y') . '_to_' . Carbon::parse($toDate)->format('d-m-Y') . '_' . uniqid() . '.pdf';
            $pdfPath = $tempDir . '/' . $filename;
            $pdf->save($pdfPath);

            if ($chartPath && file_exists($chartPath)) {
                session(['last_chart_path' => $chartPath]);
            }

            return $pdfPath;
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate Download PDF report
     */
    public function downloadPdf(Request $request)
    {
        try {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;

            if (!$fromDate || !$toDate) {
                $toDate = now()->toDateString();
                $fromDate = now()->subDays(7)->toDateString();
            }

            // Generate PDF and get file path
            $pdfPath = $this->generatePdfReport($fromDate, $toDate);

            // Read PDF content
            $pdfContent = file_get_contents($pdfPath);

            // Clean up temp files
            if ($pdfPath && file_exists($pdfPath)) {
                unlink($pdfPath);
            }

            $chartPath = session('last_chart_path');
            if ($chartPath && file_exists($chartPath)) {
                unlink($chartPath);
                session()->forget('last_chart_path');
            }

            $filename = 'Wash_Report_' . Carbon::parse($fromDate)->format('d-m-Y') . '_to_' .
                Carbon::parse($toDate)->format('d-m-Y') . '.pdf';

            return response()->streamDownload(
                fn() => print($pdfContent),
                $filename
            );
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());

            $chartPath = session('last_chart_path');
            if ($chartPath && file_exists($chartPath)) {
                unlink($chartPath);
                session()->forget('last_chart_path');
            }

            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate a bar chart image locally using GD and save to file
     */
    public function generateLocalChart($labels, $downtimeData, $idletimeData, $uptimeData)
    {
        $width = 1000;
        $height = 250;
        $padding = 50;
        $bottomPadding = 50;

        $chartWidth = $width - (2 * $padding);
        $chartHeight = $height - $padding - $bottomPadding;

        $maxScale = 70;

        $image = imagecreatetruecolor($width, $height);
        imageantialias($image, true);

        // Colors
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $gray = imagecolorallocate($image, 240, 240, 240);
        $darkGray = imagecolorallocate($image, 150, 150, 150);

        $red = imagecolorallocate($image, 220, 53, 69);
        $yellow = imagecolorallocate($image, 255, 193, 7);
        $green = imagecolorallocate($image, 40, 167, 69);
        $blue = imagecolorallocate($image, 0, 123, 255);

        // Background
        imagefill($image, 0, 0, $white);

        // Title
        $title = 'Machine Performance';
        $titleX = ($width - (strlen($title) * imagefontwidth(4))) / 2;
        imagestring($image, 4, $titleX, 5, $title, $blue);

        // Border
        imagerectangle($image, 1, 1, $width - 2, $height - 2, $darkGray);

        /*
        |--------------------------------------------------------------------------
        | Grid Lines
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i <= 5; $i++) {

            $percentage = ($i * $maxScale) / 5;

            $y = $padding + ($chartHeight * (5 - $i) / 5);

            imageline($image, $padding - 5, $y, $width - $padding + 5, $y, $gray);

            imagestring($image, 2, 10, $y - 6, round($percentage) . '%', $black);
        }

        imageline(
            $image,
            $padding - 5,
            $padding - 5,
            $padding - 5,
            $height - $bottomPadding + 5,
            $black
        );

        /*
        |--------------------------------------------------------------------------
        | Layout Settings
        |--------------------------------------------------------------------------
        */

        $barGroups = max(1, count($labels));

        $groupWidth = $chartWidth / $barGroups;

        /*
        ⭐ Each group has 3 bars → divide group space into 3 slots
        */

        $slotWidth = $groupWidth * 0.7;
        $barWidth = $slotWidth / 3;

        /*
        Inside group gap between bars
        */
        $barGap = 6;

        /*
        |--------------------------------------------------------------------------
        | Draw Bars
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < $barGroups; $i++) {

            $groupX = $padding + ($i * $groupWidth) + ($groupWidth * 0.15);

            $label = $labels[$i] ?? '';

            /*
            ----------------------
            Center Unit Label
            ----------------------
            */

            $labelTextWidth = strlen($label) * 3;

            $labelX = $groupX + ($slotWidth / 2) - $labelTextWidth;

            imagestring(
                $image,
                3,
                $labelX,
                $height - 30,
                $label,
                $black
            );

            /*
            ===============================
            Downtime Bar
            ===============================
            */

            $baseY = $height - $bottomPadding;

            $value = $downtimeData[$i] ?? 0;
            $barHeight = ($value / $maxScale) * $chartHeight;

            $x1 = $groupX;
            $y1 = $baseY - $barHeight;

            $x2 = $x1 + $barWidth;

            imagefilledrectangle($image, $x1, $y1, $x2, $baseY, $red);

            imagestring(
                $image,
                1,
                $x1,
                $y1 - 12,
                round($value, 1) . '%',
                $black
            );

            /*
            ===============================
            Idletime Bar
            ===============================
            */

            $value = $idletimeData[$i] ?? 0;
            $barHeight = ($value / $maxScale) * $chartHeight;

            $x1 = $groupX + $barWidth + $barGap;
            $y1 = $baseY - $barHeight;
            $x2 = $x1 + $barWidth;

            imagefilledrectangle($image, $x1, $y1, $x2, $baseY, $yellow);

            imagestring(
                $image,
                1,
                $x1,
                $y1 - 12,
                round($value, 1) . '%',
                $black
            );

            /*
            ===============================
            Uptime Bar
            ===============================
            */

            $value = $uptimeData[$i] ?? 0;
            $barHeight = ($value / $maxScale) * $chartHeight;

            $x1 = $groupX + 2 * ($barWidth + $barGap);
            $y1 = $baseY - $barHeight;
            $x2 = $x1 + $barWidth;

            imagefilledrectangle($image, $x1, $y1, $x2, $baseY, $green);

            imagestring(
                $image,
                1,
                $x1,
                $y1 - 12,
                round($value, 1) . '%',
                $black
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Legend
        |--------------------------------------------------------------------------
        */

        $legendY = 30;

        imagefilledrectangle(
            $image,
            $padding,
            $legendY - 5,
            $padding + 12,
            $legendY + 5,
            $red
        );

        imagestring(
            $image,
            2,
            $padding + 15,
            $legendY - 5,
            'Downtime',
            $black
        );

        imagefilledrectangle(
            $image,
            $padding + 100,
            $legendY - 5,
            $padding + 112,
            $legendY + 5,
            $yellow
        );

        imagestring(
            $image,
            2,
            $padding + 115,
            $legendY - 5,
            'Idletime',
            $black
        );

        imagefilledrectangle(
            $image,
            $padding + 200,
            $legendY - 5,
            $padding + 212,
            $legendY + 5,
            $green
        );

        imagestring(
            $image,
            2,
            $padding + 215,
            $legendY - 5,
            'Uptime',
            $black
        );

        /*
        |--------------------------------------------------------------------------
        | Save Image
        |--------------------------------------------------------------------------
        */

        $tempDir = storage_path('app/temp');

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempFile = $tempDir . '/chart_' . uniqid() . '.png';

        imagepng($image, $tempFile, 9);

        imagedestroy($image);

        return $tempFile;
    }

}
