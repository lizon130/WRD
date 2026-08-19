<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\MachineTransfer;
use App\Models\DailyUnitMachineCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MachineTransferApiController extends Controller
{
    /**
     * Get all units for dashboard filter
     */
    public function getDashboardUnits()
    {
        try {
            $units = Unit::all(['id', 'unitName', 'machineCount', 'mgTarget', 'capacity_kg', 'capacity_pieces']);

            return response()->json([
                'success' => true,
                'units' => $units
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get comprehensive dashboard data
     */
    public function getDashboardData(Request $request)
    {
        try {
            // Get filter parameters
            $dateFrom = $request->date_from ?: date('Y-m-d');
            $dateTo = $request->date_to ?: date('Y-m-d');
            $unitId = $request->unit_id;

            // Get all units - handle both single and multiple IDs
            $unitsQuery = Unit::query();

            if ($unitId) {
                // Check if unitId contains comma (multiple IDs)
                if (strpos($unitId, ',') !== false) {
                    // Multiple unit IDs - split and process
                    $unitIds = explode(',', $unitId);
                    $unitIds = array_filter(array_map('intval', $unitIds));

                    if (!empty($unitIds)) {
                        $unitsQuery->whereIn('id', $unitIds);
                    }
                } else {
                    // Single unit ID
                    $unitsQuery->where('id', $unitId);
                }
            }

            $units = $unitsQuery->get();

            // If no units found for the provided IDs
            if ($unitId && $units->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'error' => 'No units found with the provided IDs'
                ], 404);
            }

            // Get all dates in the range
            $dates = $this->getDatesInRange($dateFrom, $dateTo);

            // Create unit details - ONE ROW PER UNIT PER DATE
            $unitDetails = [];

            foreach ($dates as $date) {
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

                    // Add entry for this unit on this date
                    $unitDetails[] = [
                        'date' => $date,
                        'display_date' => date('d M, Y', strtotime($date)),
                        'unit_id' => $unit->id,
                        'unit_name' => $unit->unitName,
                        'base_machine_count' => $baseMachineCount,
                        'current_machine_count' => $currentMachineCount,
                        'base_mg_target' => round($unit->mgTarget),
                        'current_mg_target' => round($currentMgTarget),
                        'base_capacity_kg' => round($unit->capacity_kg),
                        'current_capacity_kg' => round($finalKgCapacity),
                        'base_capacity_pieces' => $unit->capacity_pieces,
                        'current_capacity_pieces' => round($finalPiecesCapacity),
                        'mg_target_per_machine' => round($mgTargetPerMachine),
                        'capacity_kg_per_machine' => round($capacityKgPerMachine),
                        'capacity_pieces_per_machine' => round($capacityPiecesPerMachine),
                        'kg_per_machine_per_hour' => round($kgPerMachinePerHour),
                        'pieces_per_machine_per_hour' => round($piecesPerMachinePerHour),

                        // Wash data
                        'wash_received' => $unitWashData['delivery'],
                        'wash_delivery' => $unitWashData['received'],
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'unit_details' => $unitDetails,
                    'filters' => [
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                        'unit_id' => $unitId,
                        'parsed_unit_ids' => $unitId ? (strpos($unitId, ',') !== false ? explode(',', $unitId) : [$unitId]) : null,
                        'found_unit_ids' => $units->pluck('id')->toArray(),
                    ],
                    'summary' => [
                        'total_units' => $units->count(),
                        'total_dates' => count($dates),
                        'total_entries' => count($unitDetails),
                    ]
                ],
                'message' => 'Dashboard data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard data error: ' . $e->getMessage());
            return $this->errorResponse('Failed to load dashboard data: ' . $e->getMessage());
        }
    }

    /**
     * Get specific unit details
     */
    public function getUnitDetails($id)
    {
        try {
            $unit = Unit::with([
                'machineTransfersFrom',
                'machineTransfersTo'
            ])->findOrFail($id);

            // Get today's data
            $today = date('Y-m-d');

            $transfersIn = $unit->machineTransfersTo()
                ->whereDate('transfer_date', $today)
                ->sum('machine_count');

            $transfersOut = $unit->machineTransfersFrom()
                ->whereDate('transfer_date', $today)
                ->sum('machine_count');

            $baseMachineCount = $this->getBaseMachineCountForDate($unit->id, $today);
            $currentMachineCount = $baseMachineCount - $transfersOut + $transfersIn;

            return response()->json([
                'success' => true,
                'data' => [
                    'unit' => $unit,
                    'today_stats' => [
                        'date' => $today,
                        'base_machine_count' => $baseMachineCount,
                        'current_machine_count' => $currentMachineCount,
                        'transfers_in' => $transfersIn,
                        'transfers_out' => $transfersOut,
                        'net_change' => $transfersIn - $transfersOut,
                    ],
                    'total_transfers_in' => $unit->machineTransfersTo()->count(),
                    'total_transfers_out' => $unit->machineTransfersFrom()->count(),
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get unit details for specific date
     */
    public function getUnitDateDetails($unitId, $date)
    {
        try {
            $unit = Unit::findOrFail($unitId);

            $transfersIn = MachineTransfer::where('to_unit_id', $unitId)
                ->whereDate('transfer_date', $date)
                ->sum('machine_count');

            $transfersOut = MachineTransfer::where('from_unit_id', $unitId)
                ->whereDate('transfer_date', $date)
                ->sum('machine_count');

            $baseMachineCount = $this->getBaseMachineCountForDate($unitId, $date);
            $currentMachineCount = $baseMachineCount - $transfersOut + $transfersIn;

            // Calculate per-machine values
            $mgTargetPerMachine = $baseMachineCount > 0 ? $unit->mgTarget / $baseMachineCount : 0;
            $capacityKgPerMachine = $baseMachineCount > 0 ? $unit->capacity_kg / $baseMachineCount : 0;
            $capacityPiecesPerMachine = $baseMachineCount > 0 ? $unit->capacity_pieces / $baseMachineCount : 0;
            $currentMgTarget = $currentMachineCount * $mgTargetPerMachine;

            // Get wash data
            $washData = $this->getWashProductionDataForUnitAndDate($date, $unit);

            // Get transfers
            $transfers = MachineTransfer::where(function ($q) use ($unitId) {
                $q->where('from_unit_id', $unitId)
                    ->orWhere('to_unit_id', $unitId);
            })
                ->whereDate('transfer_date', $date)
                ->with(['fromUnit', 'toUnit'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'unit' => $unit,
                    'date' => $date,
                    'display_date' => date('d M, Y', strtotime($date)),
                    'machine_stats' => [
                        'base_machine_count' => $baseMachineCount,
                        'current_machine_count' => $currentMachineCount,
                        'transfers_in' => $transfersIn,
                        'transfers_out' => $transfersOut,
                        'net_change' => $transfersIn - $transfersOut,
                    ],
                    'target_stats' => [
                        'base_mg_target' => $unit->mgTarget,
                        'current_mg_target' => $currentMgTarget,
                        'mg_target_per_machine' => round($mgTargetPerMachine, 2),
                    ],
                    'capacity_stats' => [
                        'base_capacity_kg' => $unit->capacity_kg,
                        'capacity_kg_per_machine' => round($capacityKgPerMachine, 2),
                        'base_capacity_pieces' => $unit->capacity_pieces,
                        'capacity_pieces_per_machine' => round($capacityPiecesPerMachine, 2),
                    ],
                    'wash_production' => $washData,
                    'transfers' => $transfers,
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get transfer history
     */
    public function getTransferHistory(Request $request)
    {
        try {
            $dateFrom = $request->date_from ?: date('Y-m-d');
            $dateTo = $request->date_to ?: date('Y-m-d');
            $unitId = $request->unit_id;
            $status = $request->status;
            $perPage = $request->per_page ?? 20;

            $query = MachineTransfer::with(['fromUnit', 'toUnit'])
                ->whereBetween('transfer_date', [$dateFrom, $dateTo]);

            if ($unitId) {
                $query->where(function ($q) use ($unitId) {
                    $q->where('from_unit_id', $unitId)
                        ->orWhere('to_unit_id', $unitId);
                });
            }

            if ($status !== null) {
                $query->where('status', $status);
            }

            $transfers = $query->latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => [
                    'transfers' => $transfers->items(),
                    'pagination' => [
                        'total' => $transfers->total(),
                        'per_page' => $transfers->perPage(),
                        'current_page' => $transfers->currentPage(),
                        'last_page' => $transfers->lastPage(),
                        'from' => $transfers->firstItem(),
                        'to' => $transfers->lastItem(),
                    ],
                    'filters' => [
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                        'unit_id' => $unitId,
                        'status' => $status,
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get summary statistics
     */
    public function getSummary(Request $request)
    {
        try {
            $dateFrom = $request->date_from ?: date('Y-m-d');
            $dateTo = $request->date_to ?: date('Y-m-d');

            $totalTransfers = MachineTransfer::whereBetween('transfer_date', [$dateFrom, $dateTo])->count();
            $totalProduction = MachineTransfer::whereBetween('transfer_date', [$dateFrom, $dateTo])->sum('calculated_production');

            $statusCounts = MachineTransfer::whereBetween('transfer_date', [$dateFrom, $dateTo])
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->status => $item->count];
                });

            $unitTransfers = MachineTransfer::whereBetween('transfer_date', [$dateFrom, $dateTo])
                ->selectRaw('from_unit_id, to_unit_id, count(*) as transfer_count')
                ->groupBy('from_unit_id', 'to_unit_id')
                ->with(['fromUnit', 'toUnit'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'date_range' => [
                        'from' => $dateFrom,
                        'to' => $dateTo,
                        'days' => (strtotime($dateTo) - strtotime($dateFrom)) / (60 * 60 * 24) + 1,
                    ],
                    'total_transfers' => $totalTransfers,
                    'total_production' => $totalProduction,
                    'status_summary' => [
                        'pending' => $statusCounts[0] ?? 0,
                        'approved' => $statusCounts[1] ?? 0,
                        'rejected' => $statusCounts[2] ?? 0,
                    ],
                    'unit_transfers' => $unitTransfers,
                    'total_units' => Unit::count(),
                    'total_machines' => Unit::sum('machineCount'),
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get today's summation of all units
     */
    /**
     * Get summation data with dynamic filters
     */
    public function getTodaySummation(Request $request)
    {
        try {
            // Get date parameters (default to today if not provided)
            $dateFrom = $request->date_from ?: date('Y-m-d');
            $dateTo = $request->date_to ?: $dateFrom;

            // Get unit IDs parameter
            $unitId = $request->unit_id;

            // Get all units or filtered units
            $unitsQuery = Unit::query();

            if ($unitId) {
                // Check if unitId contains comma (multiple IDs)
                if (strpos($unitId, ',') !== false) {
                    // Multiple unit IDs - split and process
                    $unitIds = explode(',', $unitId);
                    $unitIds = array_filter(array_map('intval', $unitIds));

                    if (!empty($unitIds)) {
                        $unitsQuery->whereIn('id', $unitIds);
                    }
                } else {
                    // Single unit ID
                    $unitsQuery->where('id', $unitId);
                }
            }

            $units = $unitsQuery->get();

            // If specific units requested but none found
            if ($unitId && $units->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'error' => 'No units found with the provided IDs'
                ], 404);
            }

            // Get all dates in the range
            $dates = $this->getDatesInRange($dateFrom, $dateTo);

            $totalSummation = [
                'date_range' => [
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'display_date_range' => date('d M, Y', strtotime($dateFrom)) . ($dateFrom != $dateTo ? ' to ' . date('d M, Y', strtotime($dateTo)) : ''),
                    'total_days' => count($dates),
                ],
                'unit_selection' => [
                    'unit_id' => $unitId ?: 'ALL',
                    'unit_name' => $unitId ? ($units->count() > 1 ? 'Selected Units (' . $units->count() . ')' : $units->first()->unitName) : 'All Units',
                    'total_units' => $units->count(),
                    'unit_ids' => $units->pluck('id')->toArray(),
                    'unit_names' => $units->pluck('unitName')->toArray(),
                ],
                'base_machine_count' => 0,
                'current_machine_count' => 0,
                'base_mg_target' => 0,
                'current_mg_target' => 0,
                'base_capacity_kg' => 0,
                'current_capacity_kg' => 0,
                'base_capacity_pieces' => 0,
                'current_capacity_pieces' => 0,
                'mg_target_per_machine' => 0,
                'capacity_kg_per_machine' => 0,
                'capacity_pieces_per_machine' => 0,
                'kg_per_machine_per_hour' => 0,
                'pieces_per_machine_per_hour' => 0,
                'wash_received' => 0,
                'wash_delivery' => 0,
                'transfers_in' => 0,
                'transfers_out' => 0,
                'net_machine_change' => 0,
                'wash_total' => 0,
            ];

            // Collect all unit data for detailed breakdown
            $unitDetails = [];

            // For each unit, calculate totals across all dates
            foreach ($units as $unit) {
                $unitTotal = [
                    'unit_id' => $unit->id,
                    'unit_name' => $unit->unitName,
                    'base_machine_count' => 0,
                    'current_machine_count' => 0,
                    'base_mg_target' => round($unit->mgTarget),
                    'current_mg_target' => 0,
                    'base_capacity_kg' => round($unit->capacity_kg),
                    'current_capacity_kg' => 0,
                    'base_capacity_pieces' => $unit->capacity_pieces,
                    'current_capacity_pieces' => 0,
                    'wash_received' => 0,
                    'wash_delivery' => 0,
                    'transfers_in' => 0,
                    'transfers_out' => 0,
                    'total_dates' => count($dates),
                ];

                // Calculate totals across all dates for this unit
                foreach ($dates as $date) {
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

                    // Accumulate unit totals
                    $unitTotal['base_machine_count'] += $baseMachineCount;
                    $unitTotal['current_machine_count'] += $currentMachineCount;
                    $unitTotal['current_mg_target'] += round($currentMgTarget);
                    $unitTotal['current_capacity_kg'] += round($finalKgCapacity);
                    $unitTotal['current_capacity_pieces'] += round($finalPiecesCapacity);
                    $unitTotal['wash_received'] += $unitWashData['delivery'];
                    $unitTotal['wash_delivery'] += $unitWashData['received'];
                    $unitTotal['transfers_in'] += $transfersIn;
                    $unitTotal['transfers_out'] += $transfersOut;

                    // Accumulate to overall totals
                    $totalSummation['base_machine_count'] += $baseMachineCount;
                    $totalSummation['current_machine_count'] += $currentMachineCount;
                    $totalSummation['base_mg_target'] += round($unit->mgTarget);
                    $totalSummation['current_mg_target'] += round($currentMgTarget);
                    $totalSummation['base_capacity_kg'] += round($unit->capacity_kg);
                    $totalSummation['current_capacity_kg'] += round($finalKgCapacity);
                    $totalSummation['base_capacity_pieces'] += $unit->capacity_pieces;
                    $totalSummation['current_capacity_pieces'] += round($finalPiecesCapacity);
                    $totalSummation['wash_received'] += $unitWashData['delivery'];
                    $totalSummation['wash_delivery'] += $unitWashData['received'];
                    $totalSummation['transfers_in'] += $transfersIn;
                    $totalSummation['transfers_out'] += $transfersOut;
                }

                // Calculate averages for this unit
                if ($unitTotal['base_machine_count'] > 0) {
                    $unitTotal['mg_target_per_machine'] = round($unit->mgTarget / $unitTotal['base_machine_count'] * count($dates), 2);
                    $unitTotal['capacity_kg_per_machine'] = round($unit->capacity_kg / $unitTotal['base_machine_count'] * count($dates), 2);
                    $unitTotal['capacity_pieces_per_machine'] = round($unit->capacity_pieces / $unitTotal['base_machine_count'] * count($dates), 2);
                    $unitTotal['kg_per_machine_per_hour'] = round($unitTotal['capacity_kg_per_machine'] / 24, 2);
                    $unitTotal['pieces_per_machine_per_hour'] = round($unitTotal['capacity_pieces_per_machine'] / 24, 2);
                }

                // Calculate average current values (divide by number of dates)
                $unitTotal['avg_current_machine_count'] = round($unitTotal['current_machine_count'] / count($dates), 2);
                $unitTotal['avg_current_mg_target'] = round($unitTotal['current_mg_target'] / count($dates), 2);
                $unitTotal['avg_current_capacity_kg'] = round($unitTotal['current_capacity_kg'] / count($dates), 2);
                $unitTotal['avg_current_capacity_pieces'] = round($unitTotal['current_capacity_pieces'] / count($dates), 2);
                $unitTotal['avg_wash_received'] = round($unitTotal['wash_received'] / count($dates), 2);
                $unitTotal['avg_wash_delivery'] = round($unitTotal['wash_delivery'] / count($dates), 2);
                $unitTotal['net_machine_change'] = $unitTotal['transfers_in'] - $unitTotal['transfers_out'];

                $unitDetails[] = $unitTotal;
            }

            // Calculate averages for the total
            if ($totalSummation['base_machine_count'] > 0) {
                $totalSummation['mg_target_per_machine'] = round($totalSummation['base_mg_target'] / $totalSummation['base_machine_count'], 2);
                $totalSummation['capacity_kg_per_machine'] = round($totalSummation['base_capacity_kg'] / $totalSummation['base_machine_count'], 2);
                $totalSummation['capacity_pieces_per_machine'] = round($totalSummation['base_capacity_pieces'] / $totalSummation['base_machine_count'], 2);
                $totalSummation['kg_per_machine_per_hour'] = round($totalSummation['capacity_kg_per_machine'] / 24, 2);
                $totalSummation['pieces_per_machine_per_hour'] = round($totalSummation['capacity_pieces_per_machine'] / 24, 2);
            }

            // Calculate net machine change and wash total
            $totalSummation['net_machine_change'] = $totalSummation['transfers_in'] - $totalSummation['transfers_out'];
            $totalSummation['wash_total'] = $totalSummation['wash_received'] + $totalSummation['wash_delivery'];

            // Calculate averages (divide by number of days)
            $totalSummation['avg_current_machine_count'] = round($totalSummation['current_machine_count'] / count($dates), 2);
            $totalSummation['avg_current_mg_target'] = round($totalSummation['current_mg_target'] / count($dates), 2);
            $totalSummation['avg_current_capacity_kg'] = round($totalSummation['current_capacity_kg'] / count($dates), 2);
            $totalSummation['avg_current_capacity_pieces'] = round($totalSummation['current_capacity_pieces'] / count($dates), 2);
            $totalSummation['avg_wash_received'] = round($totalSummation['wash_received'] / count($dates), 2);
            $totalSummation['avg_wash_delivery'] = round($totalSummation['wash_delivery'] / count($dates), 2);

            return response()->json([
                'success' => true,
                'data' => [
                    'total_summation' => $totalSummation,
                    'unit_details' => $request->detailed == 'true' ? $unitDetails : null,
                    'filters' => [
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                        'unit_id' => $unitId,
                        'parsed_unit_ids' => $unitId ? (strpos($unitId, ',') !== false ? explode(',', $unitId) : [$unitId]) : 'ALL',
                        'found_unit_ids' => $units->pluck('id')->toArray(),
                        'total_dates' => count($dates),
                        'detailed' => $request->detailed == 'true',
                    ]
                ],
                'message' => 'Summation data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Summation data error: ' . $e->getMessage());
            return $this->errorResponse('Failed to load summation data: ' . $e->getMessage());
        }
    }

    /**
     * Get chart data
     */
    public function getChartData(Request $request)
    {
        try {
            $dateFrom = $request->date_from ?: date('Y-m-d');
            $dateTo = $request->date_to ?: date('Y-m-d');
            $unitId = $request->unit_id;

            $units = Unit::when($unitId, function ($query, $unitId) {
                return $query->where('id', $unitId);
            })->get();

            $dates = $this->getDatesInRange($dateFrom, $dateTo);

            $machineData = [];
            $targetData = [];
            $capacityData = [];

            foreach ($dates as $date) {
                $totalMachines = 0;
                $totalTarget = 0;
                $totalCapacity = 0;

                foreach ($units as $unit) {
                    $transfersIn = MachineTransfer::where('to_unit_id', $unit->id)
                        ->whereDate('transfer_date', $date)
                        ->sum('machine_count');

                    $transfersOut = MachineTransfer::where('from_unit_id', $unit->id)
                        ->whereDate('transfer_date', $date)
                        ->sum('machine_count');

                    $baseMachineCount = $this->getBaseMachineCountForDate($unit->id, $date);
                    $currentMachineCount = $baseMachineCount - $transfersOut + $transfersIn;

                    $mgTargetPerMachine = $baseMachineCount > 0 ? $unit->mgTarget / $baseMachineCount : 0;
                    $currentMgTarget = $currentMachineCount * $mgTargetPerMachine;

                    $totalMachines += $currentMachineCount;
                    $totalTarget += $currentMgTarget;
                    $totalCapacity += $unit->capacity_kg;
                }

                $machineData[] = [
                    'date' => $date,
                    'display_date' => date('d M', strtotime($date)),
                    'machines' => $totalMachines,
                ];

                $targetData[] = [
                    'date' => $date,
                    'display_date' => date('d M', strtotime($date)),
                    'target' => round($totalTarget, 2),
                ];

                $capacityData[] = [
                    'date' => $date,
                    'display_date' => date('d M', strtotime($date)),
                    'capacity' => $totalCapacity,
                ];
            }

            // Unit-wise data for pie charts
            $unitWiseData = $units->map(function ($unit) use ($dateTo) {
                $transfersIn = MachineTransfer::where('to_unit_id', $unit->id)
                    ->whereDate('transfer_date', $dateTo)
                    ->sum('machine_count');

                $transfersOut = MachineTransfer::where('from_unit_id', $unit->id)
                    ->whereDate('transfer_date', $dateTo)
                    ->sum('machine_count');

                $baseMachineCount = $this->getBaseMachineCountForDate($unit->id, $dateTo);
                $currentMachineCount = $baseMachineCount - $transfersOut + $transfersIn;

                return [
                    'unit_id' => $unit->id,
                    'unit_name' => $unit->unitName,
                    'machines' => $currentMachineCount,
                    'mg_target' => $unit->mgTarget,
                    'capacity_kg' => $unit->capacity_kg,
                    'color' => $this->generateColor($unit->id),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'date_wise' => [
                        'machines' => $machineData,
                        'targets' => $targetData,
                        'capacity' => $capacityData,
                    ],
                    'unit_wise' => [
                        'machines' => $unitWiseData,
                        'targets' => $unitWiseData->map(function ($item) {
                            return [
                                'unit_name' => $item['unit_name'],
                                'value' => $item['mg_target'],
                                'color' => $item['color'],
                            ];
                        }),
                        'capacity' => $unitWiseData->map(function ($item) {
                            return [
                                'unit_name' => $item['unit_name'],
                                'value' => $item['capacity_kg'],
                                'color' => $item['color'],
                            ];
                        }),
                    ],
                    'filters' => [
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                        'unit_id' => $unitId,
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get wash production data
     */
    public function getWashProductionData($date, Request $request)
    {
        try {
            $unitId = $request->unit_id;

            if ($unitId) {
                $unit = Unit::findOrFail($unitId);
                $washData = $this->getWashProductionDataForUnitAndDate($date, $unit);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'date' => $date,
                        'unit' => $unit,
                        'wash_data' => $washData,
                    ]
                ]);
            }

            // Get data for all units
            $units = Unit::all();
            $allWashData = [];

            foreach ($units as $unit) {
                $washData = $this->getWashProductionDataForUnitAndDate($date, $unit);
                $allWashData[] = [
                    'unit_id' => $unit->id,
                    'unit_name' => $unit->unitName,
                    'wash_data' => $washData,
                    'total' => $washData['received'] + $washData['delivery'],
                ];
            }

            // Calculate totals
            $totals = [
                'received' => array_sum(array_column($allWashData, 'wash_data.received')),
                'delivery' => array_sum(array_column($allWashData, 'wash_data.delivery')),
                'total' => array_sum(array_column($allWashData, 'total')),
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'date' => $date,
                    'units_data' => $allWashData,
                    'totals' => $totals,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Wash production data error: ' . $e->getMessage());
            return $this->errorResponse('Failed to load wash production data');
        }
    }

    /**
     * Helper methods (copied from original controller)
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

    private function getBaseMachineCountForDate($unitId, $date)
    {
        $dailyCount = DailyUnitMachineCount::where('unit_id', $unitId)
            ->whereDate('date', $date)
            ->first();

        if ($dailyCount) {
            return $dailyCount->machine_count;
        }

        $unit = Unit::find($unitId);
        return $unit ? $unit->machineCount : 0;
    }

    private function getWashProductionDataForUnitAndDate($date, $unit)
    {
        try {
            $unitName = $unit->unitName;

            $isUnit4Dyeing = str_contains($unitName, 'Unit 4 (Dyeing)') || str_contains($unitName, 'Unit 4 Dyeing');
            $isUnit4Denim = str_contains($unitName, 'Unit 4 (Denim)') || str_contains($unitName, 'Unit 4 Denim');

            if ($isUnit4Dyeing || $isUnit4Denim) {
                $dbUnitName = 'Unit 4';
            } else {
                $dbUnitName = $unitName;
            }

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

            $params = [$date, $dbUnitName];
            $sqlServerData = DB::connection('sqlsrv')->select($query, $params);

            $received = 0;
            $delivery = 0;

            foreach ($sqlServerData as $row) {
                $washType = $row->UD_WashType ?? null;

                if ($isUnit4Dyeing) {
                    if ($washType === 'Over Dye') {
                        if ($row->ProcessName === 'Send from Wash') {
                            $received += $row->Quantity;
                        } elseif ($row->ProcessName === 'Received from Sewing') {
                            $delivery += $row->Quantity;
                        }
                    }
                } elseif ($isUnit4Denim) {
                    if ($washType !== 'Over Dye') {
                        if ($row->ProcessName === 'Send from Wash') {
                            $received += $row->Quantity;
                        } elseif ($row->ProcessName === 'Received from Sewing') {
                            $delivery += $row->Quantity;
                        }
                    }
                } else {
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
            \Log::error('SQL Server wash data error for ' . $unit->unitName . ' on ' . $date . ': ' . $e->getMessage());
            return [
                'received' => 0,
                'delivery' => 0
            ];
        }
    }

    /**
     * Generate color for charts
     */
    private function generateColor($id)
    {
        $colors = [
            '#FF6384',
            '#36A2EB',
            '#FFCE56',
            '#4BC0C0',
            '#9966FF',
            '#FF9F40',
            '#8AC926',
            '#1982C4',
            '#6A4C93',
            '#FF595E'
        ];

        return $colors[$id % count($colors)];
    }

    /**
     * Error response helper
     */
    private function errorResponse($message, $code = 500)
    {
        return response()->json([
            'success' => false,
            'error' => $message,
            'code' => $code
        ], $code);
    }
}