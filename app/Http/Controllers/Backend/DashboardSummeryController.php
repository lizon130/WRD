<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\WashReportEntry;
use App\Models\WashReportManPower;
use App\Models\MachineTransfer;
use App\Models\MachineStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardSummeryController extends Controller
{
    private const SKIP_UNITS = ['Acid Wash', 'Unit Off', 'Unit 4 (Denim)', 'Unit 4 (Dyeing)', 'Unit 4'];
    private const UNIT_ORDER = ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL'];

    /**
     * Dashboard Index
     */
    public function index()
    {
        return view('backend.dashboard-summery.index');
    }

    /**
     * Get wash production data for a specific unit and date range
     * Optimized: single query per unit for entire date range instead of per-day queries
     */
    private function getWashProductionDataForDateRange($fromDate, $toDate, $unit)
    {
        try {
            $unitName = $unit->unitName;
            $isUnit4Dyeing = str_contains($unitName, 'Unit 4 (Dyeing)') || str_contains($unitName, 'Unit 4 Dyeing');
            $isUnit4Denim = str_contains($unitName, 'Unit 4 (Denim)') || str_contains($unitName, 'Unit 4 Denim');
            $dbUnitName = ($isUnit4Dyeing || $isUnit4Denim) ? 'Unit 4' : $unitName;

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
                AND wop.ProductionDate BETWEEN ? AND ?
                AND wop.UD_WashUnit = ?
                GROUP BY p.ProcessName, WT.UD_WashType
            ";

            $sqlServerData = DB::connection('sqlsrv')->select($query, [$fromDate, $toDate, $dbUnitName]);

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
                AND wop.ProductionDate BETWEEN ? AND ?
                AND wop.UD_WashUnit = ?
            ";

            $garmentData = DB::connection('sqlsrv')->select($garmentQuery, [$fromDate, $toDate, $dbUnitName]);

            $garmentTotalQuantity = 0;
            $garmentTotalWeight = 0;

            if (!empty($garmentData)) {
                $garmentTotalQuantity = (int)($garmentData[0]->TotalQuantity ?? 0);
                $garmentTotalWeight = (float)($garmentData[0]->TotalWeight ?? 0);
            }

            $garment = $garmentTotalQuantity > 0 ? $garmentTotalWeight / $garmentTotalQuantity : 0;

            return [
                'received' => $received,
                'delivery' => $delivery,
                'garment' => $garment,
                'garment_quantity' => $garmentTotalQuantity,
                'garment_weight' => $garmentTotalWeight,
            ];
        } catch (\Exception $e) {
            \Log::error('SQL Server wash data error for ' . $unit->unitName . ' (' . $fromDate . ' to ' . $toDate . '): ' . $e->getMessage());
            return [
                'received' => 0,
                'delivery' => 0,
                'garment' => 0,
                'garment_quantity' => 0,
                'garment_weight' => 0,
            ];
        }
    }

    /**
     * Get Wash Balance From Received data for all units
     */
    private function getWashBalanceData()
    {
        try {
            $query = "
        SELECT
            X.Unit,
            SUM(X.[Wash Balance From Received]) AS [Total Wash Balance From Received]
        FROM
        (
            SELECT
                ISNULL(A.Unit,'') AS Unit,
                ISNULL(SUM(ISNULL(A.[Total Received],0)),0)
                - ISNULL(SUM(ISNULL(A.[Total Send],0)),0)
                AS [Wash Balance From Received]
            FROM
            (
                SELECT
                    WOI.DepartureDate [TOD],
                    ISNULL(WOI.Quantity,0) [Order Quantity],
                    ISNULL(
                    (
                        SELECT SUM(ISNULL(WOIV.Quantity,0))
                        FROM MA_WorkOrderItemVariant WOIV WITH (NOLOCK)
                        WHERE WOIV.WorkOrderItemId = WOI.RecId
                        AND WOIV.SubNo > 1000
                    ),0) [Cutting Quantity],
                    CASE
                        WHEN MWI.UD_WashTd = '1900-01-01 00:00:00.000' THEN ''
                        WHEN MWI.UD_WashTd = '2000-01-01 00:00:00.000' THEN ''
                        WHEN MWI.UD_WashTd = '2001-01-01 00:00:00.000' THEN ''
                        WHEN MWI.UD_WashTd IS NULL THEN ''
                        ELSE RIGHT('0' + CAST(DATEPART(DAY, MWI.UD_WashTd) AS VARCHAR), 2) + '-' +
                             LEFT(DATENAME(MONTH, MWI.UD_WashTd), 3) + '-' +
                             RIGHT(CAST(YEAR(MWI.UD_WashTd) AS VARCHAR), 2)
                    END AS [Wash Approval Date],
                    X.UD_InitialEndDate [Wash Target Date],
                    X.WorkOrderNo [Work Order No],
                    R.ResourceCode [Unit],
                    CASE
                        WHEN ROW_NUMBER() OVER(PARTITION BY MWI.RecId ORDER BY MWI.RecId ASC) = 1
                        THEN
                        (
                            SELECT SUM(ISNULL(WOP.Quantity,0))
                            FROM MA_WorkOrderProduction WOP WITH (NOLOCK)
                            WHERE WOP.WorkOrderItemId = MWI.RecId
                            AND WOP.ProcessId = 315
                        )
                        ELSE 0
                    END [Total Received],
                    CASE
                        WHEN ROW_NUMBER() OVER(PARTITION BY MWI.RecId ORDER BY MWI.RecId ASC) = 1
                        THEN
                        (
                            SELECT SUM(ISNULL(WOP.Quantity,0))
                            FROM MA_WorkOrderProduction WOP WITH (NOLOCK)
                            WHERE WOP.WorkOrderItemId = MWI.RecId
                            AND WOP.ProcessId = 316
                        )
                        ELSE 0
                    END [Total Send],
                    CASE
                        WHEN X.UD_WashCompleteDate = '1900-01-01 00:00:00.000' THEN ''
                        WHEN X.UD_WashCompleteDate = '2000-01-01 00:00:00.000' THEN ''
                        WHEN X.UD_WashCompleteDate = '2001-01-01 00:00:00.000' THEN ''
                        WHEN X.UD_WashCompleteDate IS NULL THEN ''
                        ELSE RIGHT('0' + CAST(DATEPART(DAY, X.UD_WashCompleteDate) AS VARCHAR), 2) + '-' +
                             LEFT(DATENAME(MONTH, X.UD_WashCompleteDate), 3) + '-' +
                             RIGHT(CAST(YEAR(X.UD_WashCompleteDate) AS VARCHAR), 2)
                    END AS [Wash Completed Date],
                    CASE
                        WHEN ROW_NUMBER() OVER(PARTITION BY MWI.RecId ORDER BY MWI.RecId ASC) = 1
                        THEN
                        (
                            SELECT SUM(ISNULL(WOP.UD_RejectQty,0))
                            FROM MA_WorkOrderProduction WOP WITH (NOLOCK)
                            WHERE WOP.WorkOrderItemId = MWI.RecId
                            AND WOP.ProcessId = 315
                        )
                        ELSE 0
                    END [Total Rewash Received],
                    CASE
                        WHEN ROW_NUMBER() OVER(PARTITION BY MWI.RecId ORDER BY MWI.RecId ASC) = 1
                        THEN
                        (
                            SELECT SUM(ISNULL(WOP.UD_RejectQty,0))
                            FROM MA_WorkOrderProduction WOP WITH (NOLOCK)
                            WHERE WOP.WorkOrderItemId = MWI.RecId
                            AND WOP.ProcessId = 316
                        )
                        ELSE 0
                    END [Total Rewash Send],
                    X.Status [Wash Status]
                FROM MA_WorkOrder W WITH (NOLOCK)
                LEFT JOIN MA_WorkOrderItem WI WITH (NOLOCK)
                    ON W.RecId = WI.WorkOrderId
                    AND WI.WorkOrderSubType = 1
                LEFT JOIN MA_WorkOrderItem WOI WITH (NOLOCK)
                    ON WOI.WorkOrderId = W.RecId
                    AND WOI.WorkOrderSubType = 2
                    AND WOI.ParentItemId IS NULL
                LEFT JOIN IM_Item I WITH (NOLOCK)
                    ON WI.InventoryId = I.RecId
                LEFT JOIN IM_Category CAT WITH (NOLOCK)
                    ON I.CategoryId = CAT.RecId
                LEFT JOIN FI_Account A WITH (NOLOCK)
                    ON A.RecId = W.CurrentAccountId
                LEFT JOIN FI_Account AA WITH (NOLOCK)
                    ON AA.RecId = W.FactoryId
                LEFT JOIN IM_ItemDepartment ID WITH (NOLOCK)
                    ON I.ItemDepartmentId = ID.RecId
                LEFT JOIN MD_Country C WITH (NOLOCK)
                    ON C.RecId = WOI.CountryId
                LEFT JOIN TSK_WashWorkOrderItem TWOI WITH (NOLOCK)
                    ON TWOI.DocketWorkOrderItemId = WOI.RecId
                    AND TWOI.WashWorkOrderItemId IN
                    (
                        SELECT MWI.RecId
                        FROM MA_WorkOrderItem MWI WITH (NOLOCK)
                        WHERE MWI.RecId = TWOI.WashWorkOrderItemId
                    )
                LEFT JOIN MA_WorkOrderItem MWI WITH (NOLOCK)
                    ON MWI.RecId = TWOI.WashWorkOrderItemId
                LEFT JOIN MA_WorkOrder X WITH (NOLOCK)
                    ON X.RecId = MWI.WorkOrderId
                LEFT JOIN MA_Resource R WITH (NOLOCK)
                    ON R.RecId = X.ResourceId
                LEFT JOIN MA_Recipe BLK WITH (NOLOCK)
                    ON BLK.RecipeCode = X.UD_RecipeField2
                    AND BLK.CompanyId = X.CompanyId
                LEFT JOIN MA_Recipe REC WITH (NOLOCK)
                    ON REC.RecId = X.RecipeId
                LEFT JOIN TSK_FastReactWashFile FWF WITH (NOLOCK)
                    ON FWF.RecId =
                    (
                        SELECT MAX(TSK.RecId)
                        FROM TSK_FastReactWashFile TSK WITH (NOLOCK,INDEX=TSK_FastReactWashFile_IX1)
                        WHERE TSK.OrderCode = WOI.UD_FastReactNo
                    )
                WHERE W.WorkOrderType = 15
                AND ISNULL(W.IsPLM,0) = 0
                AND ISNULL(W.IsClosed,0) = 0
                AND ISNULL(W.IsVirtual,0) = 0
                AND WOI.DepartureDate >= DATEADD(MONTH, -3, GETDATE())
                AND WOI.DepartureDate <= DATEADD(MONTH, 3, GETDATE())
            ) A
            GROUP BY
                A.Unit,
                A.[Work Order No],
                A.TOD,
                A.[Wash Approval Date],
                A.[Wash Target Date],
                A.[Wash Completed Date],
                A.[Wash Status]
        ) X
        GROUP BY X.Unit
        ORDER BY X.Unit;
        ";

            $balanceData = DB::connection('sqlsrv')->select($query);

            $balanceMap = [];
            foreach ($balanceData as $row) {
                $unitName = trim($row->Unit);
                $balanceValue = $row->{'Total Wash Balance From Received'} ?? 0;

                if ($unitName == 'Unit 4') {
                    $balanceMap['Unit 4'] = ($balanceMap['Unit 4'] ?? 0) + $balanceValue;
                } elseif (strpos($unitName, 'Unit 4') !== false) {
                    $balanceMap['Unit 4'] = ($balanceMap['Unit 4'] ?? 0) + $balanceValue;
                } else {
                    $balanceMap[$unitName] = $balanceValue;
                }
            }

            return $balanceMap;
        } catch (\Exception $e) {
            \Log::error('Error fetching wash balance data: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get Approval Pending Qty data for all units
     */
    private function getApprovalPendingData()
    {
        try {
            $query = "
        SELECT
            ISNULL(A.Unit,'') AS Unit,
            SUM(ISNULL(A.[Total Received],0))
            - SUM(ISNULL(A.[Total Send],0)) AS [Wash Balance From Received]
        FROM
        (
            SELECT
                WOI.DepartureDate AS [TOD],
                ISNULL(WOI.Quantity,0) AS [Order Quantity],
                ISNULL(
                (
                    SELECT SUM(ISNULL(WOIV.Quantity,0))
                    FROM MA_WorkOrderItemVariant WOIV WITH (NOLOCK)
                    WHERE WOIV.WorkOrderItemId = WOI.RecId
                    AND WOIV.SubNo > 1000
                ),0) AS [Cutting Quantity],
                CASE
                    WHEN MWI.UD_WashTd = '1900-01-01 00:00:00.000' THEN ''
                    WHEN MWI.UD_WashTd = '2000-01-01 00:00:00.000' THEN ''
                    WHEN MWI.UD_WashTd = '2001-01-01 00:00:00.000' THEN ''
                    WHEN MWI.UD_WashTd IS NULL THEN ''
                    ELSE RIGHT('0' + CAST(DATEPART(DAY, MWI.UD_WashTd) AS VARCHAR), 2) + '-' +
                         LEFT(DATENAME(MONTH, MWI.UD_WashTd), 3) + '-' +
                         RIGHT(CAST(YEAR(MWI.UD_WashTd) AS VARCHAR), 2)
                END AS [Wash Approval Date],
                X.UD_InitialEndDate AS [Wash Target Date],
                X.WorkOrderNo AS [Work Order No],
                R.ResourceCode AS [Unit],
                CASE
                    WHEN ROW_NUMBER() OVER(PARTITION BY MWI.RecId ORDER BY MWI.RecId ASC) = 1
                    THEN
                    (
                        SELECT SUM(ISNULL(WOP.Quantity,0))
                        FROM MA_WorkOrderProduction WOP WITH (NOLOCK)
                        WHERE WOP.WorkOrderItemId = MWI.RecId
                        AND WOP.ProcessId = 315
                    )
                    ELSE 0
                END AS [Total Received],
                CASE
                    WHEN ROW_NUMBER() OVER(PARTITION BY MWI.RecId ORDER BY MWI.RecId ASC) = 1
                    THEN
                    (
                        SELECT SUM(ISNULL(WOP.Quantity,0))
                        FROM MA_WorkOrderProduction WOP WITH (NOLOCK)
                        WHERE WOP.WorkOrderItemId = MWI.RecId
                        AND WOP.ProcessId = 316
                    )
                    ELSE 0
                END AS [Total Send]
            FROM MA_WorkOrder W WITH (NOLOCK)
            LEFT JOIN MA_WorkOrderItem WI WITH (NOLOCK)
                ON W.RecId = WI.WorkOrderId
                AND WI.WorkOrderSubType = 1
            LEFT JOIN MA_WorkOrderItem WOI WITH (NOLOCK)
                ON WOI.WorkOrderId = W.RecId
                AND WOI.WorkOrderSubType = 2
                AND WOI.ParentItemId IS NULL
            LEFT JOIN IM_Item I WITH (NOLOCK)
                ON WI.InventoryId = I.RecId
            LEFT JOIN IM_Category CAT WITH (NOLOCK)
                ON I.CategoryId = CAT.RecId
            LEFT JOIN FI_Account FA WITH (NOLOCK)
                ON FA.RecId = W.CurrentAccountId
            LEFT JOIN FI_Account AA WITH (NOLOCK)
                ON AA.RecId = W.FactoryId
            LEFT JOIN IM_ItemDepartment ID WITH (NOLOCK)
                ON I.ItemDepartmentId = ID.RecId
            LEFT JOIN MD_Country C WITH (NOLOCK)
                ON C.RecId = WOI.CountryId
            LEFT JOIN TSK_WashWorkOrderItem TWOI WITH (NOLOCK)
                ON TWOI.DocketWorkOrderItemId = WOI.RecId
                AND TWOI.WashWorkOrderItemId IN
                (
                    SELECT MWI.RecId
                    FROM MA_WorkOrderItem MWI WITH (NOLOCK)
                    WHERE MWI.RecId = TWOI.WashWorkOrderItemId
                )
            LEFT JOIN MA_WorkOrderItem MWI WITH (NOLOCK)
                ON MWI.RecId = TWOI.WashWorkOrderItemId
            LEFT JOIN MA_WorkOrder X WITH (NOLOCK)
                ON X.RecId = MWI.WorkOrderId
            LEFT JOIN MA_Resource R WITH (NOLOCK)
                ON R.RecId = X.ResourceId
            LEFT JOIN MA_Recipe BLK WITH (NOLOCK)
                ON BLK.RecipeCode = X.UD_RecipeField2
                AND BLK.CompanyId = X.CompanyId
            LEFT JOIN MA_Recipe REC WITH (NOLOCK)
                ON REC.RecId = X.RecipeId
            LEFT JOIN TSK_FastReactWashFile FWF WITH (NOLOCK)
                ON FWF.RecId =
                (
                    SELECT MAX(TSK.RecId)
                    FROM TSK_FastReactWashFile TSK WITH (NOLOCK, INDEX=TSK_FastReactWashFile_IX1)
                    WHERE TSK.OrderCode = WOI.UD_FastReactNo
                )
            WHERE
                W.WorkOrderType = 15
                AND ISNULL(W.IsPLM,0) = 0
                AND ISNULL(W.IsClosed,0) = 0
                AND ISNULL(W.IsVirtual,0) = 0
                AND WOI.DepartureDate >= DATEADD(MONTH, -3, GETDATE())
                AND WOI.DepartureDate <= DATEADD(MONTH, 3, GETDATE())
        ) A
        WHERE ISNULL(A.[Wash Approval Date],'') = ''
        GROUP BY A.Unit
        ORDER BY A.Unit
        ";

            $pendingData = DB::connection('sqlsrv')->select($query);

            $pendingMap = [];
            foreach ($pendingData as $row) {
                $unitName = trim($row->Unit);
                $pendingValue = $row->{'Wash Balance From Received'} ?? 0;

                if ($unitName == 'Unit 4') {
                    $pendingMap['Unit 4'] = ($pendingMap['Unit 4'] ?? 0) + $pendingValue;
                } elseif (strpos($unitName, 'Unit 4') !== false) {
                    $pendingMap['Unit 4'] = ($pendingMap['Unit 4'] ?? 0) + $pendingValue;
                } else {
                    $pendingMap[$unitName] = $pendingValue;
                }
            }

            return $pendingMap;
        } catch (\Exception $e) {
            \Log::error('Error fetching approval pending data: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Calculate total work hours for a unit
     */
    private function calculateTotalWorkHours(
        string $unitName,
        int $existMachineCount,
        int $usedMachineCount,
        $machineStatusRecords,
        array $washReportEntryMap,
        array $washReportEntriesByUnit
    ): float {
        $totalWorkHours = 0;

        foreach ($machineStatusRecords as $machineStatus) {
            $upHours = $this->convertTimeToDecimalHours($machineStatus->up_duration ?? '0:0:0');
            $idleHours = $this->convertTimeToDecimalHours($machineStatus->idle_duration ?? '0:0:0');
            $totalHours = $upHours + $idleHours;
            $transferDifference = $usedMachineCount - $existMachineCount;
            $dailyWorkHours = 0;

            $dateStr = Carbon::parse($machineStatus->report_date)->toDateString();
            $washReportEntry = $washReportEntryMap[$unitName . '|' . $dateStr] ?? null;

            if ($washReportEntry && $washReportEntry->machine_work_hr > 0) {
                $dailyWorkHours = (float)($washReportEntry->machine_work_hr ?? 0);
            } else {
                if ($existMachineCount > 0 && $usedMachineCount > 0) {
                    $perMachineHours = $totalHours / $existMachineCount;
                    $adjustmentHours = $perMachineHours * abs($transferDifference);
                    $adjustedTotalHours = $transferDifference < 0
                        ? $totalHours - $adjustmentHours
                        : $totalHours + $adjustmentHours;
                    $dailyWorkHours = max(0, ($adjustedTotalHours - $usedMachineCount) / $usedMachineCount);
                } else {
                    $dailyWorkHours = $usedMachineCount > 0
                        ? max(0, ($totalHours - $usedMachineCount) / $usedMachineCount)
                        : 0;
                }
            }

            $totalWorkHours += $dailyWorkHours;
        }

        if ($machineStatusRecords->count() == 0) {
            $unitEntries = $washReportEntriesByUnit[$unitName] ?? [];
            foreach ($unitEntries as $entry) {
                $totalWorkHours += (float)($entry->machine_work_hr ?? 0);
            }
        }

        return $totalWorkHours;
    }

    /**
     * Batch load machine transfer maps for all units
     */
    private function getMachineTransferMaps($fromDate, $toDate): array
    {
        $transfersInMap = MachineTransfer::whereBetween('transfer_date', [$fromDate, $toDate])
            ->selectRaw('to_unit_id, SUM(machine_count) as total')
            ->groupBy('to_unit_id')
            ->pluck('total', 'to_unit_id')
            ->toArray();

        $transfersOutMap = MachineTransfer::whereBetween('transfer_date', [$fromDate, $toDate])
            ->selectRaw('from_unit_id, SUM(machine_count) as total')
            ->groupBy('from_unit_id')
            ->pluck('total', 'from_unit_id')
            ->toArray();

        return [$transfersInMap, $transfersOutMap];
    }

    /**
     * Batch preload wash report entries for work hours calculation
     */
    private function preloadWashReportEntries($fromDate, $toDate): array
    {
        $allEntries = WashReportEntry::whereBetween('date', [$fromDate, $toDate])->get();

        $washReportEntryMap = [];
        $washReportEntriesByUnit = [];

        foreach ($allEntries as $entry) {
            $key = $entry->unit . '|' . Carbon::parse($entry->date)->toDateString();
            if (!isset($washReportEntryMap[$key])) {
                $washReportEntryMap[$key] = $entry;
            }
            if ($entry->machine_work_hr !== null) {
                $washReportEntriesByUnit[$entry->unit][] = $entry;
            }
        }

        return [$washReportEntryMap, $washReportEntriesByUnit];
    }

    /**
     * Get unit summary data
     */
    public function getUnitData(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        if (!$fromDate || !$toDate) {
            $toDate = now()->toDateString();
            $fromDate = now()->subDays(7)->toDateString();
        }

        $units = Unit::all();

        // === BATCH LOAD ALL DATA ===
        $balanceData = $this->getWashBalanceData();
        $approvalPendingData = $this->getApprovalPendingData();
        [$transfersInMap, $transfersOutMap] = $this->getMachineTransferMaps($fromDate, $toDate);
        $machineStatusByUnit = MachineStatus::whereBetween('report_date', [$fromDate, $toDate])
            ->get()
            ->groupBy('unit');
        [$washReportEntryMap, $washReportEntriesByUnit] = $this->preloadWashReportEntries($fromDate, $toDate);

        // === CALCULATE USED MACHINE COUNTS (SUMMATION METHOD) ===
        $unitUsedMc = [];
        $unit4Denim = null;
        $unit4Dyeing = null;

        // Get all machine transfers grouped by date and unit to sum daily machine usage
        $allTransfers = MachineTransfer::whereBetween('transfer_date', [$fromDate, $toDate])
            ->orderBy('transfer_date')
            ->get();

        // Get base machine counts for each unit
        $baseMachineCounts = [];
        foreach ($units as $unit) {
            if (!in_array($unit->unitName, self::SKIP_UNITS)) {
                $baseMachineCounts[$unit->unitName] = (int)($unit->machineCount ?? 0);
            }
            if ($unit->unitName === 'Unit 4 (Denim)') $unit4Denim = $unit;
            if ($unit->unitName === 'Unit 4 (Dyeing)') $unit4Dyeing = $unit;
        }

        // Also handle Unit 4 base machine count
        $unit4BaseCount = 0;
        if ($unit4Denim) $unit4BaseCount += (int)($unit4Denim->machineCount ?? 0);
        if ($unit4Dyeing) $unit4BaseCount += (int)($unit4Dyeing->machineCount ?? 0);
        $baseMachineCounts['Unit 4'] = $unit4BaseCount;

        // Calculate daily machine count and sum for the entire date range
        $dailyMachineCounts = [];
        $currentDate = Carbon::parse($fromDate);
        $endDate = Carbon::parse($toDate);

        // Initialize daily machine counts with base counts
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->toDateString();
            $dailyMachineCounts[$dateStr] = $baseMachineCounts;
            $currentDate->addDay();
        }

        // Apply transfers day by day to get daily machine counts
        foreach ($allTransfers as $transfer) {
            $transferDate = Carbon::parse($transfer->transfer_date)->toDateString();

            // Find from_unit and to_unit names
            $fromUnit = $units->find($transfer->from_unit_id);
            $toUnit = $units->find($transfer->to_unit_id);

            if ($fromUnit && isset($dailyMachineCounts[$transferDate][$fromUnit->unitName])) {
                // Machine LEFT the from_unit on this day
                $dailyMachineCounts[$transferDate][$fromUnit->unitName] -= $transfer->machine_count;
            }

            if ($toUnit && isset($dailyMachineCounts[$transferDate][$toUnit->unitName])) {
                // Machine ARRIVED at to_unit on this day
                $dailyMachineCounts[$transferDate][$toUnit->unitName] += $transfer->machine_count;
            }

            // Also handle Unit 4 transfers (if transfer involves Unit 4 Denim or Dyeing)
            if ($fromUnit && ($fromUnit->unitName === 'Unit 4 (Denim)' || $fromUnit->unitName === 'Unit 4 (Dyeing)')) {
                if (isset($dailyMachineCounts[$transferDate]['Unit 4'])) {
                    $dailyMachineCounts[$transferDate]['Unit 4'] -= $transfer->machine_count;
                }
            }

            if ($toUnit && ($toUnit->unitName === 'Unit 4 (Denim)' || $toUnit->unitName === 'Unit 4 (Dyeing)')) {
                if (isset($dailyMachineCounts[$transferDate]['Unit 4'])) {
                    $dailyMachineCounts[$transferDate]['Unit 4'] += $transfer->machine_count;
                }
            }
        }

        // SUM all daily machine counts for the period
        $summedMachineUsage = [];
        foreach ($dailyMachineCounts as $date => $unitsCount) {
            foreach ($unitsCount as $unitName => $machineCount) {
                if (!isset($summedMachineUsage[$unitName])) {
                    $summedMachineUsage[$unitName] = 0;
                }
                // Only add positive machine counts (no negatives)
                $summedMachineUsage[$unitName] += max(0, $machineCount);
            }
        }

        // Apply to regular units (skip Unit 4 variations, we'll handle Unit 4 separately)
        foreach ($units as $unit) {
            if (in_array($unit->unitName, self::SKIP_UNITS)) {
                continue;
            }

            // Skip the individual Unit 4 sub-units for the main loop
            if ($unit->unitName === 'Unit 4 (Denim)' || $unit->unitName === 'Unit 4 (Dyeing)') {
                continue;
            }

            $unitUsedMc[$unit->unitName] = $summedMachineUsage[$unit->unitName] ?? 0;
        }

        // Handle Unit 4 (combined - using the summed 'Unit 4' key)
        $unit4TotalUsage = $summedMachineUsage['Unit 4'] ?? 0;
        $unit4MachineCount = $unit4BaseCount;
        $unit4SewingLines = [];

        if ($unit4Denim && !empty($unit4Denim->sewing_lines)) $unit4SewingLines[] = $unit4Denim->sewing_lines;
        if ($unit4Dyeing && !empty($unit4Dyeing->sewing_lines)) $unit4SewingLines[] = $unit4Dyeing->sewing_lines;

        $unitUsedMc['Unit 4'] = $unit4TotalUsage;

        // === BUILD UNIT DATA ===
        $unitData = [];

        // First, add all regular units (excluding Unit 4 sub-units)
        foreach ($units as $unit) {
            if (in_array($unit->unitName, self::SKIP_UNITS)) continue;
            if ($unit->unitName === 'Unit 4 (Denim)' || $unit->unitName === 'Unit 4 (Dyeing)') continue;

            $machineCount = (int)($unit->machineCount ?? 0);
            $sewingLines = $unit->sewing_lines ?? '';
            $usedMc = $unitUsedMc[$unit->unitName] ?? 0;

            // Work hours (using preloaded data)
            $msRecords = $machineStatusByUnit[$unit->unitName] ?? collect();
            $totalWorkHours = $this->calculateTotalWorkHours(
                $unit->unitName,
                $machineCount,
                $usedMc,
                $msRecords,
                $washReportEntryMap,
                $washReportEntriesByUnit
            );

            // Wash production data
            $washData = $this->getWashProductionDataForDateRange($fromDate, $toDate, $unit);

            $totalReceived = (int)($washData['received'] ?? 0);
            $totalDelivery = (int)($washData['delivery'] ?? 0);
            $garment = $washData['garment'] ?? 0;
            $calculatedDeliveryKg = ($totalReceived * (float)$garment) / 1000;

            $unitData[] = [
                'unit' => $unit->unitName,
                'used_mc' => $usedMc,
                'sewing_lines' => $sewingLines,
                'work_hours' => round($totalWorkHours, 2),
                'received' => $totalReceived,
                'delivery' => $totalDelivery,
                'delivery_kg' => $calculatedDeliveryKg,
                'garment' => $garment,
                'balance' => $balanceData[$unit->unitName] ?? 0,
                'approval_pending' => $approvalPendingData[$unit->unitName] ?? 0,
            ];
        }

        // === UNIT 4 COMBINED ===
        $usedMc = $unitUsedMc['Unit 4'] ?? $unit4MachineCount;
        $msRecords = $machineStatusByUnit['Unit 4'] ?? collect();

        // Also get machine status from Unit 4 sub-units
        $msRecordsDenim = $machineStatusByUnit['Unit 4 (Denim)'] ?? collect();
        $msRecordsDyeing = $machineStatusByUnit['Unit 4 (Dyeing)'] ?? collect();
        $msRecords = $msRecords->merge($msRecordsDenim)->merge($msRecordsDyeing);

        $totalWorkHours = $this->calculateTotalWorkHours(
            'Unit 4',
            $unit4MachineCount,
            $usedMc,
            $msRecords,
            $washReportEntryMap,
            $washReportEntriesByUnit
        );

        // Wash production for Unit 4 (Denim + Dyeing combined)
        $denimUnit = (object)['unitName' => 'Unit 4 (Denim)'];
        $dyeingUnit = (object)['unitName' => 'Unit 4 (Dyeing)'];

        $washDataDenim = $this->getWashProductionDataForDateRange($fromDate, $toDate, $denimUnit);
        $washDataDyeing = $this->getWashProductionDataForDateRange($fromDate, $toDate, $dyeingUnit);

        $totalReceived = (int)($washDataDenim['received'] ?? 0) + (int)($washDataDyeing['received'] ?? 0);
        $totalDelivery = (int)($washDataDenim['delivery'] ?? 0) + (int)($washDataDyeing['delivery'] ?? 0);
        $totalGarmentQuantity = (int)($washDataDenim['garment_quantity'] ?? 0) + (int)($washDataDyeing['garment_quantity'] ?? 0);
        $totalGarmentWeight = (float)($washDataDenim['garment_weight'] ?? 0) + (float)($washDataDyeing['garment_weight'] ?? 0);

        $garment = $totalGarmentQuantity > 0 ? $totalGarmentWeight / $totalGarmentQuantity : 0;

        $unitData[] = [
            'unit' => 'Unit 4',
            'used_mc' => $usedMc,
            'sewing_lines' => !empty($unit4SewingLines) ? implode(' + ', $unit4SewingLines) : '-',
            'work_hours' => round($totalWorkHours, 2),
            'received' => $totalReceived,
            'delivery' => $totalDelivery,
            'delivery_kg' => ($totalDelivery * $garment) / 1000,
            'garment' => $garment,
            'balance' => $balanceData['Unit 4'] ?? 0,
            'approval_pending' => $approvalPendingData['Unit 4'] ?? 0,
        ];

        // Sort units by defined order
        usort($unitData, function ($a, $b) {
            $posA = array_search($a['unit'], self::UNIT_ORDER);
            $posB = array_search($b['unit'], self::UNIT_ORDER);
            return ($posA === false ? 999 : $posA) - ($posB === false ? 999 : $posB);
        });

        $dateRange = Carbon::parse($fromDate)->format('d-m-Y') . ' to ' . Carbon::parse($toDate)->format('d-m-Y');

        return DataTables::of($unitData)
            ->with('date_range', $dateRange)
            ->editColumn('used_mc', function ($row) {
                return (int)($row['used_mc'] ?? 0);
            })
            ->editColumn('sewing_lines', function ($row) {
                return $row['sewing_lines'] ?? '-';
            })
            ->editColumn('work_hours', function ($row) {
                return (float)($row['work_hours'] ?? 0);
            })
            ->editColumn('received', function ($row) {
                $value = (int)($row['received'] ?? 0);
                return number_format($value);
            })
            ->editColumn('delivery', function ($row) {
                $value = (int)($row['delivery'] ?? 0);
                return number_format($value);
            })
            ->editColumn('delivery_kg', function ($row) {
                $value = (float)($row['delivery_kg'] ?? 0);
                return number_format($value, 2);
            })
            ->editColumn('garment', function ($row) {
                if (isset($row['garment']) && $row['garment'] > 0) {
                    return round((float)$row['garment']);
                }
                return 0;
            })
            ->editColumn('balance', function ($row) {
                $value = (int)($row['balance'] ?? 0);
                return number_format($value);
            })
            ->editColumn('approval_pending', function ($row) {
                $value = $row['approval_pending'] ?? 0;
                return number_format($value);
            })
            ->make(true);
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
     * Get balance data and approval pending data via AJAX
     */
    public function getBalanceData()
    {
        return response()->json([
            'balance_data' => $this->getWashBalanceData(),
            'approval_pending_data' => $this->getApprovalPendingData(),
        ]);
    }

    /**
     * Convert time format (HH:MM:SS or HH:MM) to decimal hours
     */
    private function convertTimeToDecimalHours($timeString)
    {
        if (empty($timeString) || $timeString == '-') {
            return 0;
        }

        $parts = explode(':', trim($timeString));

        if (count($parts) == 3) {
            return (int)$parts[0] + ((int)$parts[1] / 60) + ((int)$parts[2] / 3600);
        } elseif (count($parts) == 2) {
            return (int)$parts[0] + ((int)$parts[1] / 60);
        }

        return 0;
    }



    /**
     * Generate PDF report for dashboard summary
     */
    public function generatePdf(Request $request)
    {
        try {
            // Get date parameters
            $fromDate = $request->from_date;
            $toDate = $request->to_date;

            if (!$fromDate || !$toDate) {
                $toDate = now()->toDateString();
                $fromDate = now()->subDays(7)->toDateString();
            }

            // Get the same data as getUnitData method
            $units = Unit::all();

            // === BATCH LOAD ALL DATA ===
            $balanceData = $this->getWashBalanceData();
            $approvalPendingData = $this->getApprovalPendingData();
            [$transfersInMap, $transfersOutMap] = $this->getMachineTransferMaps($fromDate, $toDate);
            $machineStatusByUnit = MachineStatus::whereBetween('report_date', [$fromDate, $toDate])
                ->get()
                ->groupBy('unit');
            [$washReportEntryMap, $washReportEntriesByUnit] = $this->preloadWashReportEntries($fromDate, $toDate);

            // === CALCULATE USED MACHINE COUNTS (SUMMATION METHOD) ===
            $unitUsedMc = [];
            $unit4Denim = null;
            $unit4Dyeing = null;

            // Get all machine transfers grouped by date and unit to sum daily machine usage
            $allTransfers = MachineTransfer::whereBetween('transfer_date', [$fromDate, $toDate])
                ->orderBy('transfer_date')
                ->get();

            // Get base machine counts for each unit
            $baseMachineCounts = [];
            foreach ($units as $unit) {
                if (!in_array($unit->unitName, self::SKIP_UNITS)) {
                    $baseMachineCounts[$unit->unitName] = (int)($unit->machineCount ?? 0);
                }
                if ($unit->unitName === 'Unit 4 (Denim)') $unit4Denim = $unit;
                if ($unit->unitName === 'Unit 4 (Dyeing)') $unit4Dyeing = $unit;
            }

            // Also handle Unit 4 base machine count
            $unit4BaseCount = 0;
            if ($unit4Denim) $unit4BaseCount += (int)($unit4Denim->machineCount ?? 0);
            if ($unit4Dyeing) $unit4BaseCount += (int)($unit4Dyeing->machineCount ?? 0);
            $baseMachineCounts['Unit 4'] = $unit4BaseCount;

            // Calculate daily machine count and sum for the entire date range
            $dailyMachineCounts = [];
            $currentDate = Carbon::parse($fromDate);
            $endDate = Carbon::parse($toDate);

            // Initialize daily machine counts with base counts
            while ($currentDate <= $endDate) {
                $dateStr = $currentDate->toDateString();
                $dailyMachineCounts[$dateStr] = $baseMachineCounts;
                $currentDate->addDay();
            }

            // Apply transfers day by day to get daily machine counts
            foreach ($allTransfers as $transfer) {
                $transferDate = Carbon::parse($transfer->transfer_date)->toDateString();

                // Find from_unit and to_unit names
                $fromUnit = $units->find($transfer->from_unit_id);
                $toUnit = $units->find($transfer->to_unit_id);

                if ($fromUnit && isset($dailyMachineCounts[$transferDate][$fromUnit->unitName])) {
                    $dailyMachineCounts[$transferDate][$fromUnit->unitName] -= $transfer->machine_count;
                }

                if ($toUnit && isset($dailyMachineCounts[$transferDate][$toUnit->unitName])) {
                    $dailyMachineCounts[$transferDate][$toUnit->unitName] += $transfer->machine_count;
                }

                // Also handle Unit 4 transfers
                if ($fromUnit && ($fromUnit->unitName === 'Unit 4 (Denim)' || $fromUnit->unitName === 'Unit 4 (Dyeing)')) {
                    if (isset($dailyMachineCounts[$transferDate]['Unit 4'])) {
                        $dailyMachineCounts[$transferDate]['Unit 4'] -= $transfer->machine_count;
                    }
                }

                if ($toUnit && ($toUnit->unitName === 'Unit 4 (Denim)' || $toUnit->unitName === 'Unit 4 (Dyeing)')) {
                    if (isset($dailyMachineCounts[$transferDate]['Unit 4'])) {
                        $dailyMachineCounts[$transferDate]['Unit 4'] += $transfer->machine_count;
                    }
                }
            }

            // SUM all daily machine counts for the period
            $summedMachineUsage = [];
            foreach ($dailyMachineCounts as $date => $unitsCount) {
                foreach ($unitsCount as $unitName => $machineCount) {
                    if (!isset($summedMachineUsage[$unitName])) {
                        $summedMachineUsage[$unitName] = 0;
                    }
                    $summedMachineUsage[$unitName] += max(0, $machineCount);
                }
            }

            // Apply to regular units
            foreach ($units as $unit) {
                if (in_array($unit->unitName, self::SKIP_UNITS)) {
                    continue;
                }

                if ($unit->unitName === 'Unit 4 (Denim)' || $unit->unitName === 'Unit 4 (Dyeing)') {
                    continue;
                }

                $unitUsedMc[$unit->unitName] = $summedMachineUsage[$unit->unitName] ?? 0;
            }

            // Handle Unit 4
            $unit4TotalUsage = $summedMachineUsage['Unit 4'] ?? 0;
            $unit4MachineCount = $unit4BaseCount;
            $unit4SewingLines = [];

            if ($unit4Denim && !empty($unit4Denim->sewing_lines)) $unit4SewingLines[] = $unit4Denim->sewing_lines;
            if ($unit4Dyeing && !empty($unit4Dyeing->sewing_lines)) $unit4SewingLines[] = $unit4Dyeing->sewing_lines;

            $unitUsedMc['Unit 4'] = $unit4TotalUsage;

            // === BUILD UNIT DATA ===
            $unitData = [];

            // First, add all regular units
            foreach ($units as $unit) {
                if (in_array($unit->unitName, self::SKIP_UNITS)) continue;
                if ($unit->unitName === 'Unit 4 (Denim)' || $unit->unitName === 'Unit 4 (Dyeing)') continue;

                $machineCount = (int)($unit->machineCount ?? 0);
                $sewingLines = $unit->sewing_lines ?? '';
                $usedMc = $unitUsedMc[$unit->unitName] ?? 0;

                $msRecords = $machineStatusByUnit[$unit->unitName] ?? collect();
                $totalWorkHours = $this->calculateTotalWorkHours(
                    $unit->unitName,
                    $machineCount,
                    $usedMc,
                    $msRecords,
                    $washReportEntryMap,
                    $washReportEntriesByUnit
                );

                $washData = $this->getWashProductionDataForDateRange($fromDate, $toDate, $unit);

                $totalReceived = (int)($washData['received'] ?? 0);
                $totalDelivery = (int)($washData['delivery'] ?? 0);
                $garment = $washData['garment'] ?? 0;
                $calculatedDeliveryKg = ($totalReceived * (float)$garment) / 1000;

                $unitData[] = (object)[
                    'unit' => $unit->unitName,
                    'used_mc' => $usedMc,
                    'sewing_lines' => $sewingLines,
                    'work_hours' => round($totalWorkHours, 2),
                    'received' => $totalReceived,
                    'delivery' => $totalDelivery,
                    'delivery_kg' => $calculatedDeliveryKg,
                    'garment' => $garment,
                    'balance' => $balanceData[$unit->unitName] ?? 0,
                    'approval_pending' => $approvalPendingData[$unit->unitName] ?? 0,
                ];
            }

            // === UNIT 4 COMBINED ===
            $usedMc = $unitUsedMc['Unit 4'] ?? $unit4MachineCount;
            $msRecords = $machineStatusByUnit['Unit 4'] ?? collect();
            $msRecordsDenim = $machineStatusByUnit['Unit 4 (Denim)'] ?? collect();
            $msRecordsDyeing = $machineStatusByUnit['Unit 4 (Dyeing)'] ?? collect();
            $msRecords = $msRecords->merge($msRecordsDenim)->merge($msRecordsDyeing);

            $totalWorkHours = $this->calculateTotalWorkHours(
                'Unit 4',
                $unit4MachineCount,
                $usedMc,
                $msRecords,
                $washReportEntryMap,
                $washReportEntriesByUnit
            );

            $denimUnit = (object)['unitName' => 'Unit 4 (Denim)'];
            $dyeingUnit = (object)['unitName' => 'Unit 4 (Dyeing)'];

            $washDataDenim = $this->getWashProductionDataForDateRange($fromDate, $toDate, $denimUnit);
            $washDataDyeing = $this->getWashProductionDataForDateRange($fromDate, $toDate, $dyeingUnit);

            $totalReceived = (int)($washDataDenim['received'] ?? 0) + (int)($washDataDyeing['received'] ?? 0);
            $totalDelivery = (int)($washDataDenim['delivery'] ?? 0) + (int)($washDataDyeing['delivery'] ?? 0);
            $totalGarmentQuantity = (int)($washDataDenim['garment_quantity'] ?? 0) + (int)($washDataDyeing['garment_quantity'] ?? 0);
            $totalGarmentWeight = (float)($washDataDenim['garment_weight'] ?? 0) + (float)($washDataDyeing['garment_weight'] ?? 0);

            $garment = $totalGarmentQuantity > 0 ? $totalGarmentWeight / $totalGarmentQuantity : 0;

            $unitData[] = (object)[
                'unit' => 'Unit 4',
                'used_mc' => $usedMc,
                'sewing_lines' => !empty($unit4SewingLines) ? implode(' + ', $unit4SewingLines) : '-',
                'work_hours' => round($totalWorkHours, 2),
                'received' => $totalReceived,
                'delivery' => $totalDelivery,
                'delivery_kg' => ($totalDelivery * $garment) / 1000,
                'garment' => $garment,
                'balance' => $balanceData['Unit 4'] ?? 0,
                'approval_pending' => $approvalPendingData['Unit 4'] ?? 0,
            ];

            // Sort units
            usort($unitData, function ($a, $b) {
                $posA = array_search($a->unit, self::UNIT_ORDER);
                $posB = array_search($b->unit, self::UNIT_ORDER);
                return ($posA === false ? 999 : $posA) - ($posB === false ? 999 : $posB);
            });

            // Calculate totals for footer
            $totals = [
                'used_mc' => 0,
                'work_hours' => 0,
                'received' => 0,
                'delivery' => 0,
                'delivery_kg' => 0,
                'balance' => 0,
                'approval_pending' => 0,
            ];

            $garmentSum = 0;
            $garmentCount = 0;

            foreach ($unitData as $row) {
                $totals['used_mc'] += $row->used_mc;
                $totals['work_hours'] += $row->work_hours;
                $totals['received'] += $row->received;
                $totals['delivery'] += $row->delivery;
                $totals['delivery_kg'] += $row->delivery_kg;
                $totals['balance'] += $row->balance;
                $totals['approval_pending'] += $row->approval_pending;

                if ($row->garment > 0) {
                    $garmentSum += $row->garment;
                    $garmentCount++;
                }
            }

            $avgGarment = $garmentCount > 0 ? $garmentSum / $garmentCount : 0;

            // Get current month data for second table
            $currentMonthStart = Carbon::now()->startOfMonth()->toDateString();
            $today = Carbon::now()->toDateString();

            // Calculate days in current month period
            $startDate = Carbon::parse($currentMonthStart);
            $endDatePeriod = Carbon::parse($today);
            $daysCount = $startDate->diffInDays($endDatePeriod) + 1;

            // === RECALCULATE DATA FOR CURRENT MONTH ===
            // Reload data for current month period
            $monthTransfersInMap = [];
            $monthTransfersOutMap = [];
            $monthTransfers = MachineTransfer::whereBetween('transfer_date', [$currentMonthStart, $today])
                ->orderBy('transfer_date')
                ->get();

            $monthMachineStatusByUnit = MachineStatus::whereBetween('report_date', [$currentMonthStart, $today])
                ->get()
                ->groupBy('unit');
            [$monthWashReportEntryMap, $monthWashReportEntriesByUnit] = $this->preloadWashReportEntries($currentMonthStart, $today);

            // Calculate daily machine counts for current month
            $monthDailyMachineCounts = [];
            $monthCurrentDate = Carbon::parse($currentMonthStart);
            $monthEndDate = Carbon::parse($today);

            while ($monthCurrentDate <= $monthEndDate) {
                $monthDateStr = $monthCurrentDate->toDateString();
                $monthDailyMachineCounts[$monthDateStr] = $baseMachineCounts;
                $monthCurrentDate->addDay();
            }

            // Apply transfers day by day for current month
            foreach ($monthTransfers as $transfer) {
                $monthTransferDate = Carbon::parse($transfer->transfer_date)->toDateString();

                $monthFromUnit = $units->find($transfer->from_unit_id);
                $monthToUnit = $units->find($transfer->to_unit_id);

                if ($monthFromUnit && isset($monthDailyMachineCounts[$monthTransferDate][$monthFromUnit->unitName])) {
                    $monthDailyMachineCounts[$monthTransferDate][$monthFromUnit->unitName] -= $transfer->machine_count;
                }

                if ($monthToUnit && isset($monthDailyMachineCounts[$monthTransferDate][$monthToUnit->unitName])) {
                    $monthDailyMachineCounts[$monthTransferDate][$monthToUnit->unitName] += $transfer->machine_count;
                }

                // Handle Unit 4 transfers
                if ($monthFromUnit && ($monthFromUnit->unitName === 'Unit 4 (Denim)' || $monthFromUnit->unitName === 'Unit 4 (Dyeing)')) {
                    if (isset($monthDailyMachineCounts[$monthTransferDate]['Unit 4'])) {
                        $monthDailyMachineCounts[$monthTransferDate]['Unit 4'] -= $transfer->machine_count;
                    }
                }

                if ($monthToUnit && ($monthToUnit->unitName === 'Unit 4 (Denim)' || $monthToUnit->unitName === 'Unit 4 (Dyeing)')) {
                    if (isset($monthDailyMachineCounts[$monthTransferDate]['Unit 4'])) {
                        $monthDailyMachineCounts[$monthTransferDate]['Unit 4'] += $transfer->machine_count;
                    }
                }
            }

            // SUM all daily machine counts for current month
            $monthSummedMachineUsage = [];
            foreach ($monthDailyMachineCounts as $date => $unitsCount) {
                foreach ($unitsCount as $unitName => $machineCount) {
                    if (!isset($monthSummedMachineUsage[$unitName])) {
                        $monthSummedMachineUsage[$unitName] = 0;
                    }
                    $monthSummedMachineUsage[$unitName] += max(0, $machineCount);
                }
            }

            // Build month-specific unit data
            $monthUnitData = [];

            foreach ($units as $unit) {
                if (in_array($unit->unitName, self::SKIP_UNITS)) continue;
                if ($unit->unitName === 'Unit 4 (Denim)' || $unit->unitName === 'Unit 4 (Dyeing)') continue;

                $monthUsedMc = $monthSummedMachineUsage[$unit->unitName] ?? 0;
                $machineCount = (int)($unit->machineCount ?? 0);
                $sewingLines = $unit->sewing_lines ?? '';

                $monthMsRecords = $monthMachineStatusByUnit[$unit->unitName] ?? collect();
                $monthTotalWorkHours = $this->calculateTotalWorkHours(
                    $unit->unitName,
                    $machineCount,
                    $monthUsedMc,
                    $monthMsRecords,
                    $monthWashReportEntryMap,
                    $monthWashReportEntriesByUnit
                );

                $monthWashData = $this->getWashProductionDataForDateRange($currentMonthStart, $today, $unit);

                $monthTotalReceived = (int)($monthWashData['received'] ?? 0);
                $monthTotalDelivery = (int)($monthWashData['delivery'] ?? 0);
                $monthGarment = $monthWashData['garment'] ?? 0;

                $monthUnitData[] = (object)[
                    'unit' => $unit->unitName,
                    'used_mc' => $monthUsedMc,
                    'sewing_lines' => $sewingLines,
                    'work_hours' => round($monthTotalWorkHours, 2),
                    'avg_work_hours' => $daysCount > 0 ? round($monthTotalWorkHours / $daysCount, 2) : 0,
                    'received' => $monthTotalReceived,
                    'avg_recv' => $daysCount > 0 ? round($monthTotalReceived / $daysCount) : 0,
                    'delivery' => $monthTotalDelivery,
                    'avg_delv' => $daysCount > 0 ? round($monthTotalDelivery / $daysCount) : 0,
                    'delivery_kg' => ($monthTotalReceived * (float)$monthGarment) / 1000,
                    'garment' => $monthGarment,
                ];
            }

            // Handle Unit 4 for current month
            $monthUnit4TotalUsage = $monthSummedMachineUsage['Unit 4'] ?? 0;
            $monthUnit4MsRecords = $monthMachineStatusByUnit['Unit 4'] ?? collect();
            $monthUnit4MsRecordsDenim = $monthMachineStatusByUnit['Unit 4 (Denim)'] ?? collect();
            $monthUnit4MsRecordsDyeing = $monthMachineStatusByUnit['Unit 4 (Dyeing)'] ?? collect();
            $monthUnit4MsRecords = $monthUnit4MsRecords->merge($monthUnit4MsRecordsDenim)->merge($monthUnit4MsRecordsDyeing);

            $monthUnit4WorkHours = $this->calculateTotalWorkHours(
                'Unit 4',
                $unit4MachineCount,
                $monthUnit4TotalUsage,
                $monthUnit4MsRecords,
                $monthWashReportEntryMap,
                $monthWashReportEntriesByUnit
            );

            $monthWashDataDenim = $this->getWashProductionDataForDateRange($currentMonthStart, $today, $denimUnit);
            $monthWashDataDyeing = $this->getWashProductionDataForDateRange($currentMonthStart, $today, $dyeingUnit);

            $monthUnit4Received = (int)($monthWashDataDenim['received'] ?? 0) + (int)($monthWashDataDyeing['received'] ?? 0);
            $monthUnit4Delivery = (int)($monthWashDataDenim['delivery'] ?? 0) + (int)($monthWashDataDyeing['delivery'] ?? 0);
            $monthUnit4GarmentQuantity = (int)($monthWashDataDenim['garment_quantity'] ?? 0) + (int)($monthWashDataDyeing['garment_quantity'] ?? 0);
            $monthUnit4GarmentWeight = (float)($monthWashDataDenim['garment_weight'] ?? 0) + (float)($monthWashDataDyeing['garment_weight'] ?? 0);

            $monthUnit4Garment = $monthUnit4GarmentQuantity > 0 ? $monthUnit4GarmentWeight / $monthUnit4GarmentQuantity : 0;

            $monthUnitData[] = (object)[
                'unit' => 'Unit 4',
                'used_mc' => $monthUnit4TotalUsage,
                'sewing_lines' => !empty($unit4SewingLines) ? implode(' + ', $unit4SewingLines) : '-',
                'work_hours' => round($monthUnit4WorkHours, 2),
                'avg_work_hours' => $daysCount > 0 ? round($monthUnit4WorkHours / $daysCount, 2) : 0,
                'received' => $monthUnit4Received,
                'avg_recv' => $daysCount > 0 ? round($monthUnit4Received / $daysCount) : 0,
                'delivery' => $monthUnit4Delivery,
                'avg_delv' => $daysCount > 0 ? round($monthUnit4Delivery / $daysCount) : 0,
                'delivery_kg' => ($monthUnit4Received * (float)$monthUnit4Garment) / 1000,
                'garment' => $monthUnit4Garment,
            ];

            // Sort month unit data
            usort($monthUnitData, function ($a, $b) {
                $posA = array_search($a->unit, self::UNIT_ORDER);
                $posB = array_search($b->unit, self::UNIT_ORDER);
                return ($posA === false ? 999 : $posA) - ($posB === false ? 999 : $posB);
            });

            // Calculate month totals
            $monthTotals = [
                'used_mc' => 0,
                'work_hours' => 0,
                'received' => 0,
                'delivery' => 0,
                'delivery_kg' => 0,
            ];

            $monthGarmentSum = 0;
            $monthGarmentCount = 0;

            foreach ($monthUnitData as $row) {
                $monthTotals['used_mc'] += $row->used_mc;
                $monthTotals['work_hours'] += $row->work_hours;
                $monthTotals['received'] += $row->received;
                $monthTotals['delivery'] += $row->delivery;
                $monthTotals['delivery_kg'] += $row->delivery_kg;

                if ($row->garment > 0) {
                    $monthGarmentSum += $row->garment;
                    $monthGarmentCount++;
                }
            }

            $monthTotals['avg_work_hours'] = $daysCount > 0 ? round($monthTotals['work_hours'] / $daysCount, 2) : 0;
            $monthTotals['avg_recv'] = $daysCount > 0 ? round($monthTotals['received'] / $daysCount) : 0;
            $monthTotals['avg_delv'] = $daysCount > 0 ? round($monthTotals['delivery'] / $daysCount) : 0;

            $monthAvgGarment = $monthGarmentCount > 0 ? $monthGarmentSum / $monthGarmentCount : 0;

            // Generate PDF with options for better performance
            $pdf = Pdf::loadView('backend.dashboard-summery.pdf-report', [
                'unitData' => $unitData,
                'monthUnitData' => $monthUnitData,
                'totals' => $totals,
                'monthTotals' => $monthTotals,
                'avgGarment' => $avgGarment,
                'monthAvgGarment' => $monthAvgGarment,
                'fromDate' => Carbon::parse($fromDate)->format('d-m-Y'),
                'toDate' => Carbon::parse($toDate)->format('d-m-Y'),
                'currentMonthStart' => Carbon::parse($currentMonthStart)->format('d-m-Y'),
                'today' => Carbon::parse($today)->format('d-m-Y'),
                'daysCount' => $daysCount,
                'generatedAt' => Carbon::now()->format('d-m-Y H:i:s'),
            ]);

            // Set PDF options for better performance
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'chroot' => public_path(),
            ]);

            // Stream the PDF instead of downloading (faster response)
            return $pdf->stream('dashboard-summary-' . date('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' line ' . $e->getLine());
            return response()->json(['error' => 'PDF Generation Failed: ' . $e->getMessage()], 500);
        }
    }
}
