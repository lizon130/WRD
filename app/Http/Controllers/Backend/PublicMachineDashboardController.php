<?php

namespace App\Http\Controllers\Backend;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Models\MachineTransfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\DailyUnitMachineCount;

class PublicMachineDashboardController extends Controller
{
    /**
     * Show public dashboard
     */
    public function dashboard()
    {
        $units = Unit::all();
        return view('backend.pages.public_dashboard', compact('units'));
    }

    /**
     * Get dashboard data
     */
    public function dashboardData(Request $request)
    {
        try {
            // Get filter parameters
            $dateFrom = $request->date_from ?: date('Y-m-d');
            $dateTo = $request->date_to ?: date('Y-m-d');
            $unitId = $request->unit_id;

            // Get all units
            $unitsQuery = Unit::query();
            if ($unitId) {
                $unitsQuery->where('id', $unitId);
            }
            $units = $unitsQuery->get();

            // Get all dates in the range
            $dates = $this->getDatesInRange($dateFrom, $dateTo);

            // Create unit details - ONE ROW PER UNIT PER DATE
            $unitDetails = [];
            $dateWiseTotals = [];

            foreach ($dates as $date) {
                // Initialize date totals for this date
                $dateWiseTotals[$date] = [
                    'display_date' => date('d M, Y', strtotime($date)),
                    // Machines
                    'total_base_machine_count' => 0,
                    'total_current_machine_count' => 0,
                    // MG Target
                    'total_base_mg_target' => 0,
                    'total_current_mg_target' => 0,
                    // Capacity KG
                    'total_base_capacity_kg' => 0,
                    'total_current_capacity_kg' => 0,
                    // Capacity Pieces
                    'total_base_capacity_pieces' => 0,
                    'total_current_capacity_pieces' => 0,
                    // Wash Production
                    'total_received' => 0,
                    'total_delivery' => 0,
                    // Transfers
                    'total_transfers_in' => 0,
                    'total_transfers_out' => 0,
                    // Per Machine Values (for averaging)
                    'sum_mg_target_per_machine' => 0,
                    'sum_capacity_kg_per_machine' => 0,
                    'sum_capacity_pieces_per_machine' => 0,
                    // Count
                    'unit_count' => 0,
                ];

                foreach ($units as $unit) {
                    // Get transfers for this unit on this specific date
                    $transfersIn = MachineTransfer::where('to_unit_id', $unit->id)
                        ->whereDate('transfer_date', $date)
                        ->sum('machine_count');

                    $transfersOut = MachineTransfer::where('from_unit_id', $unit->id)
                        ->whereDate('transfer_date', $date)
                        ->sum('machine_count');

                    // Get base machine count (from daily record or unit default)
                    $baseMachineCount = $this->getBaseMachineCountForDate($unit->id, $date);

                    // Calculate current machine count for this date
                    $currentMachineCount = $baseMachineCount - $transfersOut + $transfersIn;

                    // Calculate per-machine values using BASE machine count
                    $mgTargetPerMachine = $baseMachineCount > 0 ? $unit->mgTarget / $baseMachineCount : 0;
                    $capacityKgPerMachine = $baseMachineCount > 0 ? $unit->capacity_kg / $baseMachineCount : 0;
                    $capacityPiecesPerMachine = $baseMachineCount > 0 ? $unit->capacity_pieces / $baseMachineCount : 0;

                    // Calculate current MG target
                    $currentMgTarget = $currentMachineCount * $mgTargetPerMachine;

                    // Get transfers for capacity calculation
                    $unitTransfers = MachineTransfer::where(function ($q) use ($unit) {
                        $q->where('from_unit_id', $unit->id)
                            ->orWhere('to_unit_id', $unit->id);
                    })
                        ->whereDate('transfer_date', $date)
                        ->get();

                    // Calculate verified status
                    $hasTransfers = $unitTransfers->count() > 0;
                    $allVerified = $hasTransfers ? $unitTransfers->every(function ($transfer) {
                        return $transfer->status == 1; // All transfers are verified
                    }) : false;

                    $hasRejected = $hasTransfers ? $unitTransfers->contains(function ($transfer) {
                        return $transfer->status == 2; // Has rejected transfers
                    }) : false;

                    $hasPending = $hasTransfers ? $unitTransfers->contains(function ($transfer) {
                        return $transfer->status == 0; // Has pending transfers
                    }) : false;

                    // Determine verified status text and color
                    if (!$hasTransfers) {
                        $verifiedStatus = 'No Transfers';
                        $verifiedStatusClass = 'secondary';
                    } elseif ($hasRejected) {
                        $verifiedStatus = 'Rejected';
                        $verifiedStatusClass = 'danger';
                    } elseif ($hasPending) {
                        $verifiedStatus = 'Pending';
                        $verifiedStatusClass = 'warning';
                    } elseif ($allVerified) {
                        $verifiedStatus = 'Verified';
                        $verifiedStatusClass = 'success';
                    } else {
                        $verifiedStatus = 'Mixed';
                        $verifiedStatusClass = 'info';
                    }

                    // Calculate hourly per-machine rates
                    $kgPerMachinePerHour = $capacityKgPerMachine / 24;
                    $piecesPerMachinePerHour = $capacityPiecesPerMachine / 24;

                    // Calculate capacity changes based on transfers
                    $kgChange = 0;
                    $piecesChange = 0;

                    foreach ($unitTransfers as $transfer) {
                        if ($transfer->from_unit_id == $unit->id) {
                            // Unit lost machines
                            $kgChange -= ($transfer->machine_count * $kgPerMachinePerHour * $transfer->hours);
                            $piecesChange -= ($transfer->machine_count * $piecesPerMachinePerHour * $transfer->hours);
                        } elseif ($transfer->to_unit_id == $unit->id) {
                            // Unit gained machines
                            $kgChange += ($transfer->machine_count * $kgPerMachinePerHour * $transfer->hours);
                            $piecesChange += ($transfer->machine_count * $piecesPerMachinePerHour * $transfer->hours);
                        }
                    }

                    // Calculate final capacities
                    $finalKgCapacity = $unit->capacity_kg + $kgChange;
                    $finalPiecesCapacity = $unit->capacity_pieces + $piecesChange;

                    // Get wash data for THIS SPECIFIC DATE based on unit type
                    $unitWashData = $this->getWashProductionDataForUnitAndDate($date, $unit);

                    // Determine net change
                    $netChange = $transfersIn - $transfersOut;
                    $hasTransfers = ($transfersIn + $transfersOut) > 0;

                    // Add entry for this unit on this date
                    $unitDetails[] = [
                        'date' => $date,
                        'display_date' => date('d M, Y', strtotime($date)),
                        'unitName' => $unit->unitName,
                        'transfer_type' => $hasTransfers ? ($netChange > 0 ? 'TO' : ($netChange < 0 ? 'FROM' : 'MIXED')) : 'BASE',
                        'baseMachineCount' => $baseMachineCount,
                        'currentMachineCount' => $currentMachineCount,
                        'baseMgTarget' => $unit->mgTarget,
                        'currentMgTarget' => $currentMgTarget,
                        'baseCapacityKg' => $unit->capacity_kg,
                        'currentCapacityKg' => $finalKgCapacity,
                        'baseCapacityPieces' => $unit->capacity_pieces,
                        'currentCapacityPieces' => $finalPiecesCapacity,
                        'transfersIn' => $transfersIn,
                        'transfersOut' => $transfersOut,
                        'mgTargetPerMachine' => round($mgTargetPerMachine, 2),
                        'capacityKgPerMachine' => round($capacityKgPerMachine, 2),
                        'capacityPiecesPerMachine' => round($capacityPiecesPerMachine, 2),
                        'hours' => $unitTransfers->sum('hours'),
                        'calculated_production' => $unitTransfers->where('from_unit_id', $unit->id)->sum('calculated_production'),
                        'status' => $hasTransfers ? 'Has Transfers' : 'No Transfers',
                        'verified_status' => $verifiedStatus,
                        'verified_status_class' => $verifiedStatusClass,
                        // WASH DATA FOR THIS SPECIFIC DATE
                        'received' => $unitWashData['received'],
                        'delivery' => $unitWashData['delivery'],
                        'total_transfers' => $unitTransfers->count(),
                    ];

                    // Accumulate date-wise totals
                    $dateWiseTotals[$date]['total_base_machine_count'] += $baseMachineCount;
                    $dateWiseTotals[$date]['total_current_machine_count'] += $currentMachineCount;
                    $dateWiseTotals[$date]['total_base_mg_target'] += $unit->mgTarget;
                    $dateWiseTotals[$date]['total_current_mg_target'] += $currentMgTarget;
                    $dateWiseTotals[$date]['total_base_capacity_kg'] += $unit->capacity_kg;
                    $dateWiseTotals[$date]['total_current_capacity_kg'] += $finalKgCapacity;
                    $dateWiseTotals[$date]['total_base_capacity_pieces'] += $unit->capacity_pieces;
                    $dateWiseTotals[$date]['total_current_capacity_pieces'] += $finalPiecesCapacity;
                    $dateWiseTotals[$date]['total_received'] += $unitWashData['received'];
                    $dateWiseTotals[$date]['total_delivery'] += $unitWashData['delivery'];
                    $dateWiseTotals[$date]['total_transfers_in'] += $transfersIn;
                    $dateWiseTotals[$date]['total_transfers_out'] += $transfersOut;
                    $dateWiseTotals[$date]['sum_mg_target_per_machine'] += $mgTargetPerMachine;
                    $dateWiseTotals[$date]['sum_capacity_kg_per_machine'] += $capacityKgPerMachine;
                    $dateWiseTotals[$date]['sum_capacity_pieces_per_machine'] += $capacityPiecesPerMachine;
                    $dateWiseTotals[$date]['unit_count']++;
                }

                // Calculate averages for per-machine values for this date
                if ($dateWiseTotals[$date]['unit_count'] > 0) {
                    $dateWiseTotals[$date]['avg_mg_target_per_machine'] = round(
                        $dateWiseTotals[$date]['sum_mg_target_per_machine'] / $dateWiseTotals[$date]['unit_count'],
                        2
                    );
                    $dateWiseTotals[$date]['avg_capacity_kg_per_machine'] = round(
                        $dateWiseTotals[$date]['sum_capacity_kg_per_machine'] / $dateWiseTotals[$date]['unit_count'],
                        2
                    );
                    $dateWiseTotals[$date]['avg_capacity_pieces_per_machine'] = round(
                        $dateWiseTotals[$date]['sum_capacity_pieces_per_machine'] / $dateWiseTotals[$date]['unit_count'],
                        2
                    );
                } else {
                    $dateWiseTotals[$date]['avg_mg_target_per_machine'] = 0;
                    $dateWiseTotals[$date]['avg_capacity_kg_per_machine'] = 0;
                    $dateWiseTotals[$date]['avg_capacity_pieces_per_machine'] = 0;
                }

                // Calculate net change for this date
                $dateWiseTotals[$date]['total_net_change'] =
                    $dateWiseTotals[$date]['total_transfers_in'] - $dateWiseTotals[$date]['total_transfers_out'];
            }

            // Summary statistics for the date range
            $summary = [
                'totalUnits' => $units->count(),
                'totalMachines' => $units->sum('machineCount'),
                'totalTransfers' => MachineTransfer::whereBetween('transfer_date', [$dateFrom, $dateTo])->count(),
                'totalProduction' => MachineTransfer::whereBetween('transfer_date', [$dateFrom, $dateTo])->sum('calculated_production'),
            ];

            // Recent transfers (last 5)
            $recentTransfers = MachineTransfer::with(['fromUnit', 'toUnit'])
                ->whereBetween('transfer_date', [$dateFrom, $dateTo])
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($transfer) {
                    return [
                        'from_unit' => $transfer->fromUnit ? $transfer->fromUnit->unitName : '[Deleted Unit]',
                        'to_unit' => $transfer->toUnit ? $transfer->toUnit->unitName : '[Deleted Unit]',
                        'machine_count' => $transfer->machine_count,
                        'hours' => $transfer->hours,
                        'production' => $transfer->calculated_production,
                        'date' => $transfer->transfer_date->format('d M, Y'),
                        'status' => $transfer->status_text,
                    ];
                });

            // Transfer history
            $transferHistoryQuery = MachineTransfer::with(['fromUnit', 'toUnit'])
                ->whereBetween('transfer_date', [$dateFrom, $dateTo]);

            if ($unitId) {
                $transferHistoryQuery->where(function ($query) use ($unitId) {
                    $query->where('from_unit_id', $unitId)
                        ->orWhere('to_unit_id', $unitId);
                });
            }

            $transferHistory = $transferHistoryQuery->latest()->get()->map(function ($transfer) {
                return [
                    'transfer_date' => $transfer->transfer_date->format('d M, Y'),
                    'from_unit_name' => $transfer->fromUnit ? $transfer->fromUnit->unitName : '[Deleted Unit]',
                    'to_unit_name' => $transfer->toUnit ? $transfer->toUnit->unitName : '[Deleted Unit]',
                    'machine_count' => $transfer->machine_count,
                    'hours' => $transfer->hours,
                    'from_mg_target_before' => $transfer->from_unit_mg_target_before,
                    'from_mg_target_after' => $transfer->from_unit_mg_target_after,
                    'to_mg_target_before' => $transfer->to_unit_mg_target_before,
                    'to_mg_target_after' => $transfer->to_unit_mg_target_after,
                    'from_capacity_kg_before' => $transfer->from_unit_capacity_kg_before,
                    'from_capacity_kg_after' => $transfer->from_unit_capacity_kg_after,
                    'to_capacity_kg_before' => $transfer->to_unit_capacity_kg_before,
                    'to_capacity_kg_after' => $transfer->to_unit_capacity_kg_after,
                    'from_capacity_pieces_before' => $transfer->from_unit_capacity_pieces_before,
                    'from_capacity_pieces_after' => $transfer->from_unit_capacity_pieces_after,
                    'to_capacity_pieces_before' => $transfer->to_unit_capacity_pieces_before,
                    'to_capacity_pieces_after' => $transfer->to_unit_capacity_pieces_after,
                    'calculated_production' => $transfer->calculated_production,
                    'status' => $transfer->status_text,
                ];
            });

            // Chart data - Group by unit name (latest data)
            $latestDataByUnit = [];
            foreach ($unitDetails as $detail) {
                $latestDataByUnit[$detail['unitName']] = $detail;
            }

            $chartData = [
                'labels' => array_column($latestDataByUnit, 'unitName'),
                'machineCounts' => array_column($latestDataByUnit, 'currentMachineCount'),
            ];

            return response()->json([
                'summary' => $summary,
                'unitDetails' => $unitDetails,
                'dateWiseTotals' => $dateWiseTotals, // Add date-wise totals
                'recentTransfers' => $recentTransfers,
                'transferHistory' => $transferHistory,
                'chartData' => $chartData,
                'success' => true,
            ]);
        } catch (\Exception $e) {
            Log::error('Public dashboard data error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load dashboard data: ' . $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }


    /**
     * Get all dates in a range
     */
    private function getDatesInRange($startDate, $endDate)
    {
        $dates = [];
        $currentDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        while ($currentDate <= $endDate) {
            $dates[] = date('Y-m-d', $currentDate);
            $currentDate = strtotime('+1 day', $currentDate);
        }

        return $dates;
    }

    /**
     * Get base machine count for a specific date
     */
    private function getBaseMachineCountForDate($unitId, $date)
    {
        // Try to get daily machine count record
        $dailyCount = DailyUnitMachineCount::where('unit_id', $unitId)
            ->whereDate('date', $date)
            ->first();

        if ($dailyCount) {
            return $dailyCount->machine_count;
        }

        // If no daily record, get the unit's default machine count
        $unit = Unit::find($unitId);
        return $unit ? $unit->machineCount : 0;
    }

    /**
     * Get wash production data for specific date and unit based on unit type
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
                // For Unit 4 variants, the database has 'Unit 4'
                $dbUnitName = 'Unit 4';
            } else {
                // For other units, use the actual unit name
                $dbUnitName = $unitName;
            }

            // Base query for all wash production data with wash type
            $query = "
            SELECT   
                p.ProcessName,
                wop.ProductionDate,
                WT.UD_WashType,         
                SUM(wop.Quantity) AS Quantity,
                wop.UD_WashUnit
            FROM [TusukaExtreme].[dbo].[MA_WorkOrderProduction] wop
            JOIN MA_WorkOrderItem woi
                ON wop.WorkOrderItemId = woi.RecId
            JOIN MA_Process p
                ON wop.ProcessId = p.RecId
            OUTER APPLY (
                SELECT DISTINCT KI.UD_WashType
                FROM TSK_WashWorkOrderItem TSK
                JOIN MA_WorkOrderItem KI
                    ON KI.RecId = TSK.DocketWorkOrderItemId
                WHERE TSK.WashWorkOrderItemId = woi.RecId
            ) WT
            WHERE p.RecId IN (315, 316) 
            AND wop.ProductionDate = ?
            AND wop.UD_WashUnit = ?
            GROUP BY
                p.ProcessName,
                wop.ProductionDate,
                wop.UD_WashUnit,
                WT.UD_WashType
            ORDER BY wop.ProductionDate DESC
            ";

            // Parameters for the query
            $params = [$date, $dbUnitName];

            // Execute query
            $sqlServerData = DB::connection('sqlsrv')->select($query, $params);

            $received = 0;
            $delivery = 0;

            // Process the SQL Server data
            foreach ($sqlServerData as $row) {
                $washType = $row->UD_WashType ?? null;

                // For Unit 4 (Dyeing) - only count 'Over Dye'
                if ($isUnit4Dyeing) {
                    if ($washType === 'Over Dye') {
                        if ($row->ProcessName === 'Send from Wash') {
                            $received += $row->Quantity;
                        } elseif ($row->ProcessName === 'Received from Sewing') {
                            $delivery += $row->Quantity;
                        }
                    }
                }
                // For Unit 4 (Denim) - count everything EXCEPT 'Over Dye'
                elseif ($isUnit4Denim) {
                    if ($washType !== 'Over Dye') {
                        if ($row->ProcessName === 'Send from Wash') {
                            $received += $row->Quantity;
                        } elseif ($row->ProcessName === 'Received from Sewing') {
                            $delivery += $row->Quantity;
                        }
                    }
                }
                // For all other units - count all wash types (no filtering)
                else {
                    if ($row->ProcessName === 'Send from Wash') {
                        $received += $row->Quantity;
                    } elseif ($row->ProcessName === 'Received from Sewing') {
                        $delivery += $row->Quantity;
                    }
                }
            }

            return [
                'received' => $received,
                'delivery' => $delivery
            ];
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('SQL Server wash data error for ' . $unit->unitName . ' on ' . $date . ': ' . $e->getMessage());

            // Return zeros if SQL Server connection fails
            return [
                'received' => 0,
                'delivery' => 0
            ];
        }
    }

    /**
     * Calculate current machine count for a specific date (considering transfers up to that date)
     */
    private function getCurrentMachineCountForDate($unitId, $date)
    {
        $dailyCount = $this->getBaseMachineCountForDate($unitId, $date);
        $baseCount = $dailyCount;

        // Calculate net transfers for this specific date only
        $transferredOut = MachineTransfer::where('from_unit_id', $unitId)
            ->whereDate('transfer_date', $date)
            ->sum('machine_count');

        $transferredIn = MachineTransfer::where('to_unit_id', $unitId)
            ->whereDate('transfer_date', $date)
            ->sum('machine_count');

        return $baseCount - $transferredOut + $transferredIn;
    }
}