<?php

namespace App\Http\Controllers\Backend;

use Log;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Models\MachineTransfer;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyUnitMachineCount;

class MachineTransferController extends Controller
{

    // ....... Start Machine Transfer Methods .......
    public function index()
    {
        $units = Unit::all();
        $today = now()->format('Y-m-d');

        // Calculate current machine counts for each unit considering transfers for today
        $unitsWithCurrentCounts = [];
        foreach ($units as $unit) {
            $currentCount = $this->getCurrentMachineCountForDate($unit->id, $today);
            $unitsWithCurrentCounts[] = [
                'unit' => $unit,
                'current_machine_count' => $currentCount,
                'current_mg_target' => $this->calculateCurrentMgTarget($unit->id, $currentCount, $today),
                'base_machine_count' => $unit->machineCount,
                'mg_target' => $unit->mgTarget,
                'capacity_kg' => $unit->capacity_kg,
                'capacity_pieces' => $unit->capacity_pieces
            ];
        }

        return view('backend.pages.machine-transfer.index', compact('unitsWithCurrentCounts'));
    }

    /**
     * Calculate current machine count for a unit considering all transfers
     */
    private function getCurrentMachineCount($unitId, $transferDate = null)
    {
        if (!$transferDate) {
            $transferDate = now()->format('Y-m-d');
        }

        $dailyCount = $this->getOrCreateDailyMachineCount($unitId, $transferDate);

        // Calculate transfers for this specific date only
        $transferredOut = MachineTransfer::where('from_unit_id', $unitId)
            ->whereDate('transfer_date', $transferDate)
            ->approved()
            ->sum('machine_count');

        $transferredIn = MachineTransfer::where('to_unit_id', $unitId)
            ->whereDate('transfer_date', $transferDate)
            ->approved()
            ->sum('machine_count');

        return $dailyCount->machine_count - $transferredOut + $transferredIn;
    }

    /**
     * Calculate current MG target based on current machine count
     */
    private function calculateCurrentMgTarget($unitId, $currentMachineCount, $transferDate = null)
    {
        $unit = Unit::find($unitId);

        if (!$transferDate) {
            $transferDate = now()->format('Y-m-d');
        }

        // Get the daily count to find the base for that day
        $dailyCount = $this->getOrCreateDailyMachineCount($unitId, $transferDate);

        // Calculate MG target per machine based on unit's actual MG target and base machine count for that day
        if ($dailyCount->machine_count > 0) {
            $mgTargetPerMachine = $unit->mgTarget / $dailyCount->machine_count;
        } else {
            $mgTargetPerMachine = 0;
        }

        return $currentMachineCount * $mgTargetPerMachine;
    }

    private function getOrCreateDailyMachineCount($unitId, $date)
    {
        return DailyUnitMachineCount::firstOrCreate(
            [
                'unit_id' => $unitId,
                'date' => $date
            ],
            [
                'machine_count' => Unit::find($unitId)->machineCount
            ]
        );
    }

    public function getList(Request $request)
    {
        $data = MachineTransfer::with(['fromUnit', 'toUnit'])->orderBy('transfer_date', 'desc')->orderBy('created_at', 'asc');


        return Datatables::of($data)
            ->editColumn('transfer_date', function ($row) {
                return $row->transfer_date->format('d M, Y');
            })
            ->addColumn('from_unit_name', function ($row) {
                return $row->fromUnit->unitName . ' (' . $row->from_unit_machine_count_before . '→' . $row->from_unit_machine_count_after . ')';
            })
            ->addColumn('to_unit_name', function ($row) {
                return $row->toUnit->unitName . ' (' . $row->to_unit_machine_count_before . '→' . $row->to_unit_machine_count_after . ')';
            })
            ->addColumn('from_unit_name', function ($row) {
                return $row->fromUnit->unitName;
            })
            ->addColumn('to_unit_name', function ($row) {
                return $row->toUnit->unitName;
            })
            ->addColumn('from_unit_change', function ($row) {
                // The stored values should already be correct for that specific transfer
                return $row->from_unit_machine_count_before . ' → ' . $row->from_unit_machine_count_after;
            })
            ->addColumn('to_unit_change', function ($row) {
                return $row->to_unit_machine_count_before . ' → ' . $row->to_unit_machine_count_after;
            })
            ->addColumn('mg_target_from_change', function ($row) {
                return number_format($row->from_unit_mg_target_before, 0) . ' → ' . number_format($row->from_unit_mg_target_after, 0);
            })
            ->addColumn('mg_target_to_change', function ($row) {
                return number_format($row->to_unit_mg_target_before, 0) . ' → ' . number_format($row->to_unit_mg_target_after, 0);
            })
            ->addColumn('capacity_kg_from_change', function ($row) {
                return number_format($row->from_unit_capacity_kg_before, 0) . ' → ' . number_format($row->from_unit_capacity_kg_after, 0);
            })
            ->addColumn('capacity_kg_to_change', function ($row) {
                return number_format($row->to_unit_capacity_kg_before, 0) . ' → ' . number_format($row->to_unit_capacity_kg_after, 0);
            })
            ->addColumn('capacity_pieces_from_change', function ($row) {
                return number_format($row->from_unit_capacity_pieces_before, 0) . ' → ' . number_format($row->from_unit_capacity_pieces_after, 0);
            })
            ->addColumn('capacity_pieces_to_change', function ($row) {
                return number_format($row->to_unit_capacity_pieces_before, 0) . ' → ' . number_format($row->to_unit_capacity_pieces_after, 0);
            })
            ->editColumn('calculated_production', function ($row) {
                return number_format($row->calculated_production, 0);
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M, Y H:i') : 'N/A';
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                $btn .= '<a href="" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary me-1"><i class="fa-solid fa-pencil"></i></a>';
                $btn .= '<a class="delete_btn btn btn-sm btn-danger" data-id="' . $row->id . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getUnitMachines($id)
    {
        $unit = Unit::find($id);
        if (!$unit) {
            return response()->json(['error' => 'Unit not found.'], 404);
        }

        $today = now()->format('Y-m-d');
        $currentCount = $this->getCurrentMachineCount($id, $today);
        $currentMgTarget = $this->calculateCurrentMgTarget($id, $currentCount, $today);

        return response()->json([
            'machine_count' => $currentCount,
            'mg_target' => $currentMgTarget
        ]);
    }


    public function store(Request $request)
    {
        $validator = $request->validate([
            'transfer_date' => 'required|date',
            'from_unit_id' => 'required|exists:unit,id',
            'to_unit_id' => 'required|exists:unit,id|different:from_unit_id',
            'machine_count' => 'required|integer|min:1',
            'hours' => 'required|integer|min:1|max:24',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|different:start_time',
        ]);

        $machineCount = $request->machine_count;
        $hours = $request->hours;
        $transferDate = $request->transfer_date;

        // Get units
        $fromUnit = Unit::find($request->from_unit_id);
        $toUnit = Unit::find($request->to_unit_id);

        // Get the daily base count
        $fromDailyCount = $this->getOrCreateDailyMachineCount($fromUnit->id, $transferDate);
        $fromUnitBaseCount = $fromDailyCount->machine_count;

        $toDailyCount = $this->getOrCreateDailyMachineCount($toUnit->id, $transferDate);
        $toUnitBaseCount = $toDailyCount->machine_count;

        // CRITICAL: Get the ACTUAL count BEFORE this transfer
        // This considers ALL previous transfers on the same day
        $fromUnitCurrentCount = $this->getActualMachineCountBeforeTransfer($fromUnit->id, $transferDate);
        $toUnitCurrentCount = $this->getActualMachineCountBeforeTransfer($toUnit->id, $transferDate);

        // Check if from unit has enough machines
        if ($fromUnitCurrentCount < $machineCount) {
            return response()->json([
                'type' => 'error',
                'message' => 'From unit does not have enough machines for ' . $transferDate . '. Available: ' . $fromUnitCurrentCount,
            ]);
        }

        // Calculate machine counts after THIS transfer
        $fromUnitMachineCountAfter = $fromUnitCurrentCount - $machineCount;
        $toUnitMachineCountAfter = $toUnitCurrentCount + $machineCount;

        // Use BASE count for per-machine calculations (this should be constant for the day)
        $fromUnitMgTargetPerMachine = $fromUnitBaseCount > 0 ? $fromUnit->mgTarget / $fromUnitBaseCount : 0;
        $toUnitMgTargetPerMachine = $toUnitBaseCount > 0 ? $toUnit->mgTarget / $toUnitBaseCount : 0;

        // Calculate hourly production per machine
        $fromUnitProductionPerMachinePerHour = $fromUnitMgTargetPerMachine / 24;
        $toUnitProductionPerMachinePerHour = $toUnitMgTargetPerMachine / 24;

        // Calculate MG targets based on CURRENT counts (before this transfer)
        $fromUnitMgTargetBefore = $fromUnitCurrentCount * $fromUnitMgTargetPerMachine;
        $productionLoss = $machineCount * $fromUnitProductionPerMachinePerHour * $hours;
        $fromUnitMgTargetAfter = $fromUnitMgTargetBefore - $productionLoss;

        $toUnitMgTargetBefore = $toUnitCurrentCount * $toUnitMgTargetPerMachine;
        $productionGain = $machineCount * $toUnitProductionPerMachinePerHour * $hours;
        $toUnitMgTargetAfter = $toUnitMgTargetBefore + $productionGain;

        // Calculate capacity changes (KG) - use BASE count for per-machine calculation
        $fromUnitKgPerMachine = $fromUnitBaseCount > 0 ? $fromUnit->capacity_kg / $fromUnitBaseCount : 0;
        $fromUnitKgPerHour = $fromUnitKgPerMachine / 24;
        $fromUnitKgLoss = $machineCount * $fromUnitKgPerHour * $hours;
        $fromUnitKgAfter = $fromUnit->capacity_kg - $fromUnitKgLoss;

        $toUnitKgPerMachine = $toUnitBaseCount > 0 ? $toUnit->capacity_kg / $toUnitBaseCount : 0;
        $toUnitKgPerHour = $toUnitKgPerMachine / 24;
        $toUnitKgGain = $machineCount * $toUnitKgPerHour * $hours;
        $toUnitKgAfter = $toUnit->capacity_kg + $toUnitKgGain;

        // Calculate capacity changes (Pieces)
        $fromUnitPiecesPerMachine = $fromUnitBaseCount > 0 ? $fromUnit->capacity_pieces / $fromUnitBaseCount : 0;
        $fromUnitPiecesPerHour = $fromUnitPiecesPerMachine / 24;
        $fromUnitPiecesLoss = $machineCount * $fromUnitPiecesPerHour * $hours;
        $fromUnitPiecesAfter = $fromUnit->capacity_pieces - $fromUnitPiecesLoss;

        $toUnitPiecesPerMachine = $toUnitBaseCount > 0 ? $toUnit->capacity_pieces / $toUnitBaseCount : 0;
        $toUnitPiecesPerHour = $toUnitPiecesPerMachine / 24;
        $toUnitPiecesGain = $machineCount * $toUnitPiecesPerHour * $hours;
        $toUnitPiecesAfter = $toUnit->capacity_pieces + $toUnitPiecesGain;

        // Calculate PRODUCTION for the transfer hours
        $calculatedProduction = $machineCount * $fromUnitProductionPerMachinePerHour * $hours;

        // Create transfer record
        $transfer = new MachineTransfer();
        $transfer->transfer_date = $transferDate;
        $transfer->from_unit_id = $request->from_unit_id;
        $transfer->to_unit_id = $request->to_unit_id;
        $transfer->machine_count = $machineCount;
        $transfer->hours = $hours;
        $transfer->status = MachineTransfer::STATUS_PENDING;

        // Store the CORRECT progressive counts
        $transfer->from_unit_machine_count_before = $fromUnitCurrentCount;
        $transfer->from_unit_machine_count_after = $fromUnitMachineCountAfter;
        $transfer->to_unit_machine_count_before = $toUnitCurrentCount;
        $transfer->to_unit_machine_count_after = $toUnitMachineCountAfter;

        // Store MG Targets
        $transfer->from_unit_mg_target_before = $fromUnitMgTargetBefore;
        $transfer->from_unit_mg_target_after = $fromUnitMgTargetAfter;
        $transfer->to_unit_mg_target_before = $toUnitMgTargetBefore;
        $transfer->to_unit_mg_target_after = $toUnitMgTargetAfter;

        // Store capacity changes
        $transfer->from_unit_capacity_kg_before = $fromUnit->capacity_kg;
        $transfer->from_unit_capacity_kg_after = $fromUnitKgAfter;
        $transfer->to_unit_capacity_kg_before = $toUnit->capacity_kg;
        $transfer->to_unit_capacity_kg_after = $toUnitKgAfter;

        $transfer->from_unit_capacity_pieces_before = $fromUnit->capacity_pieces;
        $transfer->from_unit_capacity_pieces_after = $fromUnitPiecesAfter;
        $transfer->to_unit_capacity_pieces_before = $toUnit->capacity_pieces;
        $transfer->to_unit_capacity_pieces_after = $toUnitPiecesAfter;

        $transfer->calculated_production = $calculatedProduction;

        if ($transfer->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Machine transfer recorded successfully.',
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Failed to record machine transfer.',
        ]);
    }

    private function getActualMachineCountBeforeTransfer($unitId, $date)
    {
        $dailyCount = $this->getOrCreateDailyMachineCount($unitId, $date);
        $baseCount = $dailyCount->machine_count;

        // Get ALL approved transfers for this unit on this date
        $transferredOut = MachineTransfer::where('from_unit_id', $unitId)
            ->whereDate('transfer_date', $date)
            ->approved()
            ->sum('machine_count');

        $transferredIn = MachineTransfer::where('to_unit_id', $unitId)
            ->whereDate('transfer_date', $date)
            ->approved()
            ->sum('machine_count');

        // This is the count BEFORE any new transfer is added
        return $baseCount - $transferredOut + $transferredIn;
    }

    public function fixAllTransfers()
    {
        // Get all transfers grouped by date
        $dates = MachineTransfer::selectRaw('DATE(transfer_date) as date')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('date');

        $totalFixed = 0;

        foreach ($dates as $date) {
            // Get transfers for this date in order
            $transfers = MachineTransfer::whereDate('transfer_date', $date)
                ->orderBy('created_at', 'asc')
                ->get();

            // Reset to base counts for this date
            $unitCounts = [];

            foreach ($transfers as $index => $transfer) {
                // Initialize counts for units if not set
                if (!isset($unitCounts[$transfer->from_unit_id])) {
                    $dailyCount = $this->getOrCreateDailyMachineCount($transfer->from_unit_id, $date);
                    $unitCounts[$transfer->from_unit_id] = $dailyCount->machine_count;
                }

                if (!isset($unitCounts[$transfer->to_unit_id])) {
                    $dailyCount = $this->getOrCreateDailyMachineCount($transfer->to_unit_id, $date);
                    $unitCounts[$transfer->to_unit_id] = $dailyCount->machine_count;
                }

                // Store the BEFORE counts
                $fromBefore = $unitCounts[$transfer->from_unit_id];
                $toBefore = $unitCounts[$transfer->to_unit_id];

                // Update the transfer with correct progressive counts
                $transfer->from_unit_machine_count_before = $fromBefore;
                $transfer->from_unit_machine_count_after = $fromBefore - $transfer->machine_count;

                $transfer->to_unit_machine_count_before = $toBefore;
                $transfer->to_unit_machine_count_after = $toBefore + $transfer->machine_count;

                // Recalculate MG targets
                $this->recalculateTransferTargets($transfer, $date);

                $transfer->save();
                $totalFixed++;

                // Update our running counts for the next transfer
                $unitCounts[$transfer->from_unit_id] = $fromBefore - $transfer->machine_count;
                $unitCounts[$transfer->to_unit_id] = $toBefore + $transfer->machine_count;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Fixed ' . $totalFixed . ' transfers with progressive counts.',
        ]);
    }

    private function recalculateTransferTargets($transfer, $date)
    {
        $fromUnit = $transfer->fromUnit;
        $toUnit = $transfer->toUnit;

        // Get base counts
        $fromDailyCount = $this->getOrCreateDailyMachineCount($fromUnit->id, $date);
        $fromUnitBaseCount = $fromDailyCount->machine_count;

        $toDailyCount = $this->getOrCreateDailyMachineCount($toUnit->id, $date);
        $toUnitBaseCount = $toDailyCount->machine_count;

        // Calculate per-machine values
        $fromUnitMgTargetPerMachine = $fromUnitBaseCount > 0 ? $fromUnit->mgTarget / $fromUnitBaseCount : 0;
        $toUnitMgTargetPerMachine = $toUnitBaseCount > 0 ? $toUnit->mgTarget / $toUnitBaseCount : 0;

        $fromUnitProductionPerMachinePerHour = $fromUnitMgTargetPerMachine / 24;
        $toUnitProductionPerMachinePerHour = $toUnitMgTargetPerMachine / 24;

        // Calculate MG targets
        $transfer->from_unit_mg_target_before = $transfer->from_unit_machine_count_before * $fromUnitMgTargetPerMachine;
        $transfer->from_unit_mg_target_after = $transfer->from_unit_mg_target_before - ($transfer->machine_count * $fromUnitProductionPerMachinePerHour * $transfer->hours);

        $transfer->to_unit_mg_target_before = $transfer->to_unit_machine_count_before * $toUnitMgTargetPerMachine;
        $transfer->to_unit_mg_target_after = $transfer->to_unit_mg_target_before + ($transfer->machine_count * $toUnitProductionPerMachinePerHour * $transfer->hours);

        // Calculate capacity changes
        $fromUnitKgPerMachine = $fromUnitBaseCount > 0 ? $fromUnit->capacity_kg / $fromUnitBaseCount : 0;
        $fromUnitKgPerHour = $fromUnitKgPerMachine / 24;
        $fromUnitKgLoss = $transfer->machine_count * $fromUnitKgPerHour * $transfer->hours;
        $transfer->from_unit_capacity_kg_after = $fromUnit->capacity_kg - $fromUnitKgLoss;

        $toUnitKgPerMachine = $toUnitBaseCount > 0 ? $toUnit->capacity_kg / $toUnitBaseCount : 0;
        $toUnitKgPerHour = $toUnitKgPerMachine / 24;
        $toUnitKgGain = $transfer->machine_count * $toUnitKgPerHour * $transfer->hours;
        $transfer->to_unit_capacity_kg_after = $toUnit->capacity_kg + $toUnitKgGain;

        // Calculate pieces capacity
        $fromUnitPiecesPerMachine = $fromUnitBaseCount > 0 ? $fromUnit->capacity_pieces / $fromUnitBaseCount : 0;
        $fromUnitPiecesPerHour = $fromUnitPiecesPerMachine / 24;
        $fromUnitPiecesLoss = $transfer->machine_count * $fromUnitPiecesPerHour * $transfer->hours;
        $transfer->from_unit_capacity_pieces_after = $fromUnit->capacity_pieces - $fromUnitPiecesLoss;

        $toUnitPiecesPerMachine = $toUnitBaseCount > 0 ? $toUnit->capacity_pieces / $toUnitBaseCount : 0;
        $toUnitPiecesPerHour = $toUnitPiecesPerMachine / 24;
        $toUnitPiecesGain = $transfer->machine_count * $toUnitPiecesPerHour * $transfer->hours;
        $transfer->to_unit_capacity_pieces_after = $toUnit->capacity_pieces + $toUnitPiecesGain;

        // Calculate production
        $transfer->calculated_production = $transfer->machine_count * $fromUnitProductionPerMachinePerHour * $transfer->hours;
    }



    /**
     * Calculate progressive count before a new transfer
     * This calculates what the machine count is RIGHT BEFORE this transfer
     */
    private function calculateProgressiveCountBeforeTransfer($unitId, $transferDate)
    {
        $dailyCount = $this->getOrCreateDailyMachineCount($unitId, $transferDate);
        $baseCount = $dailyCount->machine_count;

        // Get all transfers for this unit on this date
        $transferredOut = MachineTransfer::where('from_unit_id', $unitId)
            ->whereDate('transfer_date', $transferDate)
            ->approved()
            ->sum('machine_count');

        $transferredIn = MachineTransfer::where('to_unit_id', $unitId)
            ->whereDate('transfer_date', $transferDate)
            ->approved()
            ->sum('machine_count');

        // This is the count BEFORE any new transfer
        return $baseCount - $transferredOut + $transferredIn;
    }

    public function edit($id)
    {
        $transfer = MachineTransfer::with(['fromUnit', 'toUnit'])->find($id);
        $units = Unit::all();

        if (!$transfer) {
            return response()->json(['error' => 'Transfer record not found.'], 404);
        }

        return view('backend.pages.machine-transfer.edit', compact('transfer', 'units'));
    }

    public function update(Request $request, $id)
    {
        $transfer = MachineTransfer::findOrFail($id);
        $originalDate = $transfer->transfer_date->format('Y-m-d');

        $validator = $request->validate([
            'transfer_date' => 'required|date',
            'from_unit_id' => 'required|exists:unit,id',
            'to_unit_id' => 'required|exists:unit,id|different:from_unit_id',
            'machine_count' => 'required|integer|min:1',
            'hours' => 'required|integer|min:1|max:24',
        ]);

        $machineCount = $request->machine_count;
        $hours = $request->hours;
        $transferDate = $request->transfer_date;

        // Temporarily delete this transfer to calculate correct counts
        $oldMachineCount = $transfer->machine_count;
        $transfer->delete(); // Temporarily remove

        try {
            // Calculate counts WITHOUT this transfer
            $fromUnitCurrentCount = $this->calculateProgressiveCountBeforeTransfer($request->from_unit_id, $transferDate);
            $toUnitCurrentCount = $this->calculateProgressiveCountBeforeTransfer($request->to_unit_id, $transferDate);

            // Restore the transfer
            $transfer->restore();

            // Check if from unit has enough machines
            if ($fromUnitCurrentCount < $machineCount) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'From unit does not have enough machines for ' . $transferDate . '. Available: ' . $fromUnitCurrentCount,
                ]);
            }

            // Get units
            $fromUnit = Unit::find($request->from_unit_id);
            $toUnit = Unit::find($request->to_unit_id);

            // Get base counts for the day
            $fromDailyCount = $this->getOrCreateDailyMachineCount($fromUnit->id, $transferDate);
            $fromUnitBaseCount = $fromDailyCount->machine_count;

            $toDailyCount = $this->getOrCreateDailyMachineCount($toUnit->id, $transferDate);
            $toUnitBaseCount = $toDailyCount->machine_count;

            // Calculate machine counts after transfer
            $fromUnitMachineCountAfter = $fromUnitCurrentCount - $machineCount;
            $toUnitMachineCountAfter = $toUnitCurrentCount + $machineCount;

            // Calculate per-machine values using BASE counts
            $fromUnitMgTargetPerMachine = $fromUnitBaseCount > 0 ? $fromUnit->mgTarget / $fromUnitBaseCount : 0;
            $toUnitMgTargetPerMachine = $toUnitBaseCount > 0 ? $toUnit->mgTarget / $toUnitBaseCount : 0;

            // Calculate hourly production per machine
            $fromUnitProductionPerMachinePerHour = $fromUnitMgTargetPerMachine / 24;
            $toUnitProductionPerMachinePerHour = $toUnitMgTargetPerMachine / 24;

            // Calculate MG targets
            $fromUnitMgTargetBefore = $fromUnitCurrentCount * $fromUnitMgTargetPerMachine;
            $productionLoss = $machineCount * $fromUnitProductionPerMachinePerHour * $hours;
            $fromUnitMgTargetAfter = $fromUnitMgTargetBefore - $productionLoss;

            $toUnitMgTargetBefore = $toUnitCurrentCount * $toUnitMgTargetPerMachine;
            $productionGain = $machineCount * $toUnitProductionPerMachinePerHour * $hours;
            $toUnitMgTargetAfter = $toUnitMgTargetBefore + $productionGain;

            // Calculate capacity changes
            $fromUnitKgPerMachine = $fromUnitBaseCount > 0 ? $fromUnit->capacity_kg / $fromUnitBaseCount : 0;
            $fromUnitKgPerHour = $fromUnitKgPerMachine / 24;
            $fromUnitKgLoss = $machineCount * $fromUnitKgPerHour * $hours;
            $fromUnitKgAfter = $fromUnit->capacity_kg - $fromUnitKgLoss;

            $toUnitKgPerMachine = $toUnitBaseCount > 0 ? $toUnit->capacity_kg / $toUnitBaseCount : 0;
            $toUnitKgPerHour = $toUnitKgPerMachine / 24;
            $toUnitKgGain = $machineCount * $toUnitKgPerHour * $hours;
            $toUnitKgAfter = $toUnit->capacity_kg + $toUnitKgGain;

            // Calculate capacity changes (Pieces)
            $fromUnitPiecesPerMachine = $fromUnitBaseCount > 0 ? $fromUnit->capacity_pieces / $fromUnitBaseCount : 0;
            $fromUnitPiecesPerHour = $fromUnitPiecesPerMachine / 24;
            $fromUnitPiecesLoss = $machineCount * $fromUnitPiecesPerHour * $hours;
            $fromUnitPiecesAfter = $fromUnit->capacity_pieces - $fromUnitPiecesLoss;

            $toUnitPiecesPerMachine = $toUnitBaseCount > 0 ? $toUnit->capacity_pieces / $toUnitBaseCount : 0;
            $toUnitPiecesPerHour = $toUnitPiecesPerMachine / 24;
            $toUnitPiecesGain = $machineCount * $toUnitPiecesPerHour * $hours;
            $toUnitPiecesAfter = $toUnit->capacity_pieces + $toUnitPiecesGain;

            // Calculate total production
            $calculatedProduction = $machineCount * $fromUnitProductionPerMachinePerHour * $hours;

            // Update transfer record
            $transfer->transfer_date = $transferDate;
            $transfer->from_unit_id = $request->from_unit_id;
            $transfer->to_unit_id = $request->to_unit_id;
            $transfer->machine_count = $machineCount;
            $transfer->hours = $hours;

            // Update counts
            $transfer->from_unit_machine_count_before = $fromUnitCurrentCount;
            $transfer->from_unit_machine_count_after = $fromUnitMachineCountAfter;
            $transfer->to_unit_machine_count_before = $toUnitCurrentCount;
            $transfer->to_unit_machine_count_after = $toUnitMachineCountAfter;

            // Update mgTarget calculations
            $transfer->from_unit_mg_target_before = $fromUnitMgTargetBefore;
            $transfer->from_unit_mg_target_after = $fromUnitMgTargetAfter;
            $transfer->to_unit_mg_target_before = $toUnitMgTargetBefore;
            $transfer->to_unit_mg_target_after = $toUnitMgTargetAfter;

            // Update capacity calculations
            $transfer->from_unit_capacity_kg_before = $fromUnit->capacity_kg;
            $transfer->from_unit_capacity_kg_after = $fromUnitKgAfter;
            $transfer->to_unit_capacity_kg_before = $toUnit->capacity_kg;
            $transfer->to_unit_capacity_kg_after = $toUnitKgAfter;

            $transfer->from_unit_capacity_pieces_before = $fromUnit->capacity_pieces;
            $transfer->from_unit_capacity_pieces_after = $fromUnitPiecesAfter;
            $transfer->to_unit_capacity_pieces_before = $toUnit->capacity_pieces;
            $transfer->to_unit_capacity_pieces_after = $toUnitPiecesAfter;

            $transfer->calculated_production = $calculatedProduction;

            if ($transfer->save()) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'Transfer record updated successfully.',
                ]);
            }

            return response()->json([
                'type' => 'error',
                'message' => 'Failed to update transfer record.',
            ]);
        } catch (\Exception $e) {
            // If anything fails, restore the transfer
            if (!$transfer->exists) {
                $transfer->save();
            }

            return response()->json([
                'type' => 'error',
                'message' => 'Error updating transfer: ' . $e->getMessage(),
            ]);
        }
    }

    public function refreshTransfersForDate($date)
    {
        // Get all transfers for this date
        $transfers = MachineTransfer::whereDate('transfer_date', $date)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($transfers as $transfer) {
            // Recalculate this transfer
            $this->recalculateSingleTransfer($transfer);
        }

        return response()->json([
            'success' => true,
            'message' => 'Refreshed ' . count($transfers) . ' transfers for ' . $date,
        ]);
    }


    private function recalculateSingleTransfer($transfer)
    {
        $transferDate = $transfer->transfer_date->format('Y-m-d');

        // Calculate count BEFORE this transfer
        $fromUnitCurrentCount = $this->calculateCountBeforeSpecificTransfer($transfer->from_unit_id, $transferDate, $transfer);
        $toUnitCurrentCount = $this->calculateCountBeforeSpecificTransfer($transfer->to_unit_id, $transferDate, $transfer);

        // Update the transfer with correct counts
        $transfer->from_unit_machine_count_before = $fromUnitCurrentCount;
        $transfer->from_unit_machine_count_after = $fromUnitCurrentCount - $transfer->machine_count;
        $transfer->to_unit_machine_count_before = $toUnitCurrentCount;
        $transfer->to_unit_machine_count_after = $toUnitCurrentCount + $transfer->machine_count;

        // Recalculate MG targets and capacity changes
        $fromUnit = $transfer->fromUnit;
        $toUnit = $transfer->toUnit;

        $fromDailyCount = $this->getOrCreateDailyMachineCount($fromUnit->id, $transferDate);
        $fromUnitBaseCount = $fromDailyCount->machine_count;

        $toDailyCount = $this->getOrCreateDailyMachineCount($toUnit->id, $transferDate);
        $toUnitBaseCount = $toDailyCount->machine_count;

        // Recalculate MG targets
        $fromUnitMgTargetPerMachine = $fromUnitBaseCount > 0 ? $fromUnit->mgTarget / $fromUnitBaseCount : 0;
        $toUnitMgTargetPerMachine = $toUnitBaseCount > 0 ? $toUnit->mgTarget / $toUnitBaseCount : 0;

        $fromUnitProductionPerMachinePerHour = $fromUnitMgTargetPerMachine / 24;
        $toUnitProductionPerMachinePerHour = $toUnitMgTargetPerMachine / 24;

        $transfer->from_unit_mg_target_before = $fromUnitCurrentCount * $fromUnitMgTargetPerMachine;
        $transfer->from_unit_mg_target_after = $transfer->from_unit_mg_target_before - ($transfer->machine_count * $fromUnitProductionPerMachinePerHour * $transfer->hours);

        $transfer->to_unit_mg_target_before = $toUnitCurrentCount * $toUnitMgTargetPerMachine;
        $transfer->to_unit_mg_target_after = $transfer->to_unit_mg_target_before + ($transfer->machine_count * $toUnitProductionPerMachinePerHour * $transfer->hours);

        $transfer->save();
    }

    private function calculateCountBeforeSpecificTransfer($unitId, $date, $specificTransfer)
    {
        $dailyCount = $this->getOrCreateDailyMachineCount($unitId, $date);
        $baseCount = $dailyCount->machine_count;

        // Get all transfers BEFORE this specific transfer (by created_at)
        $transferredOut = MachineTransfer::where('from_unit_id', $unitId)
            ->whereDate('transfer_date', $date)
            ->where('created_at', '<', $specificTransfer->created_at)
            ->sum('machine_count');

        $transferredIn = MachineTransfer::where('to_unit_id', $unitId)
            ->whereDate('transfer_date', $date)
            ->where('created_at', '<', $specificTransfer->created_at)
            ->sum('machine_count');

        return $baseCount - $transferredOut + $transferredIn;
    }

    public function getUnitsByDate($date)
    {
        $units = Unit::all();
        $unitsWithCounts = [];

        foreach ($units as $unit) {
            $currentCount = $this->getCurrentMachineCount($unit->id, $date);
            $unitsWithCounts[] = [
                'id' => $unit->id,
                'unit_name' => $unit->unitName,
                'current_machine_count' => $currentCount,
                'base_machine_count' => $unit->machineCount,
                'mg_target' => $unit->mgTarget,
                'current_mg_target' => $this->calculateCurrentMgTarget($unit->id, $currentCount, $date),
                'capacity_kg' => $unit->capacity_kg,
                'capacity_pieces' => $unit->capacity_pieces
            ];
        }

        return response()->json([
            'units' => $unitsWithCounts,
            'date' => $date
        ]);
    }

    /**
     * Get current machine count for edit (excluding the current transfer being edited)
     */
    private function getCurrentMachineCountForEdit($unitId, $transferDate, $currentTransfer)
    {
        $dailyCount = $this->getOrCreateDailyMachineCount($unitId, $transferDate);

        // Calculate net transfers for this date excluding the current transfer being edited
        $transferredOut = MachineTransfer::where('from_unit_id', $unitId)
            ->whereDate('transfer_date', $transferDate)
            ->where('id', '!=', $currentTransfer->id)
            ->sum('machine_count');

        $transferredIn = MachineTransfer::where('to_unit_id', $unitId)
            ->whereDate('transfer_date', $transferDate)
            ->where('id', '!=', $currentTransfer->id)
            ->sum('machine_count');

        return $dailyCount->machine_count - $transferredOut + $transferredIn;
    }

    public function delete($id)
    {
        $transfer = MachineTransfer::find($id);
        if ($transfer) {
            $transfer->delete();
            return response()->json(['success' => 'Transfer record deleted successfully.']);
        } else {
            return response()->json(['error' => 'Transfer record not found.']);
        }
    }

    // ....... End Machine Transfer Methods .......




    // ......... start Machine approvals .......
    public function approvals()
    {
        return view('backend.pages.machine-transfer.approval');
    }

    public function getPendingList(Request $request)
    {
        $data = MachineTransfer::with(['fromUnit', 'toUnit'])
            ->pending()
            ->latest();

        return Datatables::of($data)
            ->editColumn('transfer_date', function ($row) {
                return $row->transfer_date->format('d M, Y');
            })
            ->addColumn('from_unit_name', function ($row) {
                return $row->fromUnit->unitName;
            })
            ->addColumn('to_unit_name', function ($row) {
                return $row->toUnit->unitName;
            })
            ->addColumn('from_unit_change', function ($row) {
                return $row->from_unit_machine_count_before . ' → ' . $row->from_unit_machine_count_after;
            })
            ->addColumn('to_unit_change', function ($row) {
                return $row->to_unit_machine_count_before . ' → ' . $row->to_unit_machine_count_after;
            })
            ->addColumn('mg_target_from_change', function ($row) {
                return number_format($row->from_unit_mg_target_before, 0) . ' → ' . number_format($row->from_unit_mg_target_after, 0);
            })
            ->addColumn('mg_target_to_change', function ($row) {
                return number_format($row->to_unit_mg_target_before, 0) . ' → ' . number_format($row->to_unit_mg_target_after, 0);
            })
            ->editColumn('calculated_production', function ($row) {
                return number_format($row->calculated_production, 0);
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format('d M, Y H:i') : 'N/A';
            })
            ->addColumn('action', function ($row) {
                $btn = '<a href="#" data-id="' . $row->id . '" class="approve_btn btn btn-sm btn-success me-1" title="Approve"><i class="fa-solid fa-check"></i></a>';
                $btn .= '<a href="#" data-id="' . $row->id . '" class="reject_btn btn btn-sm btn-danger" title="Reject"><i class="fa-solid fa-times"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function approve($id)
    {
        try {
            $transfer = MachineTransfer::findOrFail($id);

            if (!$transfer->isPending()) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'This transfer has already been processed.'
                ], 400);
            }

            $transfer->update([
                'status' => MachineTransfer::STATUS_APPROVED,
                'approved_at' => now(),
                'approved_by' => Auth::id()
            ]);

            return response()->json([
                'type' => 'success',
                'message' => 'Transfer approved successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'Failed to approve transfer: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $request->validate([
                'rejection_reason' => 'required|string|max:500'
            ]);

            $transfer = MachineTransfer::findOrFail($id);

            if (!$transfer->isPending()) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'This transfer has already been processed.'
                ], 400);
            }

            $transfer->update([
                'status' => MachineTransfer::STATUS_REJECTED,
                'rejected_at' => now(),
                'rejected_by' => Auth::id(),
                'rejection_reason' => $request->rejection_reason
            ]);

            return response()->json([
                'type' => 'success',
                'message' => 'Transfer rejected successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'Failed to reject transfer: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $transfer = MachineTransfer::with(['fromUnit', 'toUnit', 'approver', 'rejector'])->findOrFail($id);
        return view('backend.pages.machine-transfer.show', compact('transfer'));
    }
    // ......... End Machine approvals .......



    // ....... Start Machine Dashboard .......

    public function dashboard()
    {
        $units = Unit::all();
        return view('backend.pages.machine-transfer.dashboard', compact('units'));
    }

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
                        // Verified status fields
                        'verified_status' => $verifiedStatus,
                        'verified_status_class' => $verifiedStatusClass,
                        // WASH DATA FOR THIS SPECIFIC DATE - based on unit type
                        'received' => $unitWashData['received'],
                        'delivery' => $unitWashData['delivery'],
                        'total_transfers' => $unitTransfers->count(),
                    ];
                }
            }

            // Calculate totals for each date (ONLY FOR SUMMABLE FIELDS)
            $dateTotals = [];

            foreach ($unitDetails as $detail) {
                $date = $detail['date'];

                if (!isset($dateTotals[$date])) {
                    $dateTotals[$date] = [
                        'display_date' => $detail['display_date'],
                        // Machines
                        'baseMachineCount' => 0,
                        'currentMachineCount' => 0,
                        // MG Target
                        'baseMgTarget' => 0,
                        'currentMgTarget' => 0,
                        // Capacity KG
                        'baseCapacityKg' => 0,
                        'currentCapacityKg' => 0,
                        // Capacity Pieces
                        'baseCapacityPieces' => 0,
                        'currentCapacityPieces' => 0,
                        // Wash Production
                        'received' => 0,
                        'delivery' => 0,
                        // Transfers (for net change calculation)
                        'transfersIn' => 0,
                        'transfersOut' => 0,
                        // Unit count for this date
                        'unit_count' => 0,
                    ];
                }

                // Sum up the totals for this date
                $dateTotals[$date]['baseMachineCount'] += $detail['baseMachineCount'];
                $dateTotals[$date]['currentMachineCount'] += $detail['currentMachineCount'];
                $dateTotals[$date]['baseMgTarget'] += $detail['baseMgTarget'];
                $dateTotals[$date]['currentMgTarget'] += $detail['currentMgTarget'];
                $dateTotals[$date]['baseCapacityKg'] += $detail['baseCapacityKg'];
                $dateTotals[$date]['currentCapacityKg'] += $detail['currentCapacityKg'];
                $dateTotals[$date]['baseCapacityPieces'] += $detail['baseCapacityPieces'];
                $dateTotals[$date]['currentCapacityPieces'] += $detail['currentCapacityPieces'];
                $dateTotals[$date]['received'] += $detail['received'];
                $dateTotals[$date]['delivery'] += $detail['delivery'];
                $dateTotals[$date]['transfersIn'] += $detail['transfersIn'];
                $dateTotals[$date]['transfersOut'] += $detail['transfersOut'];
                $dateTotals[$date]['unit_count']++;
            }

            // Calculate grand totals across all dates (ONLY FOR SUMMABLE FIELDS)
            $grandTotals = [
                // Machines
                'baseMachineCount' => 0,
                'currentMachineCount' => 0,
                // MG Target
                'baseMgTarget' => 0,
                'currentMgTarget' => 0,
                // Capacity KG
                'baseCapacityKg' => 0,
                'currentCapacityKg' => 0,
                // Capacity Pieces
                'baseCapacityPieces' => 0,
                'currentCapacityPieces' => 0,
                // Wash Production
                'received' => 0,
                'delivery' => 0,
                // Transfers (for net change calculation)
                'transfersIn' => 0,
                'transfersOut' => 0,
                // Averages (calculated separately)
                'avgMgTargetPerMachine' => 0,
                'avgCapacityKgPerMachine' => 0,
                'avgCapacityPiecesPerMachine' => 0,
                // Counters
                'total_entries' => count($unitDetails),
            ];

            // Variables for calculating averages
            $sumMgTargetPerMachine = 0;
            $sumCapacityKgPerMachine = 0;
            $sumCapacityPiecesPerMachine = 0;
            $validAvgEntries = 0;

            foreach ($unitDetails as $detail) {
                // Sum up the grand totals
                $grandTotals['baseMachineCount'] += $detail['baseMachineCount'];
                $grandTotals['currentMachineCount'] += $detail['currentMachineCount'];
                $grandTotals['baseMgTarget'] += $detail['baseMgTarget'];
                $grandTotals['currentMgTarget'] += $detail['currentMgTarget'];
                $grandTotals['baseCapacityKg'] += $detail['baseCapacityKg'];
                $grandTotals['currentCapacityKg'] += $detail['currentCapacityKg'];
                $grandTotals['baseCapacityPieces'] += $detail['baseCapacityPieces'];
                $grandTotals['currentCapacityPieces'] += $detail['currentCapacityPieces'];
                $grandTotals['received'] += $detail['received'];
                $grandTotals['delivery'] += $detail['delivery'];
                $grandTotals['transfersIn'] += $detail['transfersIn'];
                $grandTotals['transfersOut'] += $detail['transfersOut'];

                // Accumulate for averages (only if values are valid numbers)
                if (is_numeric($detail['mgTargetPerMachine']) && $detail['mgTargetPerMachine'] > 0) {
                    $sumMgTargetPerMachine += $detail['mgTargetPerMachine'];
                    $validAvgEntries++;
                }
                if (is_numeric($detail['capacityKgPerMachine']) && $detail['capacityKgPerMachine'] > 0) {
                    $sumCapacityKgPerMachine += $detail['capacityKgPerMachine'];
                }
                if (is_numeric($detail['capacityPiecesPerMachine']) && $detail['capacityPiecesPerMachine'] > 0) {
                    $sumCapacityPiecesPerMachine += $detail['capacityPiecesPerMachine'];
                }
            }

            // Calculate averages safely
            if ($validAvgEntries > 0) {
                $grandTotals['avgMgTargetPerMachine'] = round($sumMgTargetPerMachine / $validAvgEntries, 2);
                $grandTotals['avgCapacityKgPerMachine'] = round($sumCapacityKgPerMachine / $validAvgEntries, 2);
                $grandTotals['avgCapacityPiecesPerMachine'] = round($sumCapacityPiecesPerMachine / $validAvgEntries, 2);
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
                        'from_unit' => $transfer->fromUnit->unitName,
                        'to_unit' => $transfer->toUnit->unitName,
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
                    'from_unit_name' => $transfer->fromUnit->unitName,
                    'to_unit_name' => $transfer->toUnit->unitName,
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
                'success' => true,
                'summary' => $summary,
                'unitDetails' => $unitDetails,
                'dateTotals' => $dateTotals,
                'grandTotals' => $grandTotals,
                'recentTransfers' => $recentTransfers,
                'transferHistory' => $transferHistory,
                'chartData' => $chartData,
                'totalDates' => count($dates),
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard data error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load dashboard data: ' . $e->getMessage()
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

            // Base query for all wash production data
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
     * Get wash production data from SQL Server for all units
     */
    private function getWashProductionData($date, $unitId = null)
    {
        try {
            // Get the specific unit if unitId is provided
            $specificUnit = null;
            if ($unitId) {
                $specificUnit = Unit::find($unitId);
            }

            // Connect to SQL Server database with the updated query
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
            GROUP BY
                p.ProcessName,
                wop.ProductionDate,
                wop.UD_WashUnit,
                WT.UD_WashType
            ORDER BY wop.ProductionDate DESC
            ";

            $sqlServerData = DB::connection('sqlsrv')->select($query, [$date]);

            // Get all units for matching
            $units = Unit::all();
            $washData = [];

            // Process the SQL Server data
            foreach ($sqlServerData as $row) {
                $unitNameFromDB = $row->UD_WashUnit;
                $washType = $row->UD_WashType ?? null;

                // Find matching units
                $matchingUnits = $units->filter(function ($unit) use ($unitNameFromDB) {
                    // Check if the unit name contains the database unit name or vice versa
                    $dbName = 'Unit 4'; // Database has 'Unit 4'
                    $unitName = $unit->unitName;

                    // Check for Unit 4 variations
                    if (str_contains($unitName, 'Unit 4')) {
                        return true; // This is a Unit 4 variant
                    }

                    return str_contains($unitName, $unitNameFromDB) ||
                        str_contains($unitNameFromDB, $unitName) ||
                        strtolower($unitName) === strtolower($unitNameFromDB);
                });

                foreach ($matchingUnits as $unit) {
                    // Filter by unit ID if specified
                    if ($unitId && $unit->id != $unitId) {
                        continue;
                    }

                    $isUnit4Dyeing = str_contains($unit->unitName, 'Unit 4 (Dyeing)') || str_contains($unit->unitName, 'Unit 4 Dyeing');
                    $isUnit4Denim = str_contains($unit->unitName, 'Unit 4 (Denim)') || str_contains($unit->unitName, 'Unit 4 Denim');

                    // Check if we should include this wash type for this unit
                    $shouldInclude = false;

                    if ($isUnit4Dyeing && $washType === 'Over Dye') {
                        $shouldInclude = true;
                    } elseif ($isUnit4Denim && $washType !== 'Over Dye') {
                        $shouldInclude = true;
                    } elseif (!$isUnit4Dyeing && !$isUnit4Denim) {
                        $shouldInclude = true; // Other units get all wash types
                    }

                    if (!$shouldInclude) {
                        continue;
                    }

                    // Initialize unit data if not exists
                    if (!isset($washData[$unit->id])) {
                        $washData[$unit->id] = [
                            'unit_id' => $unit->id,
                            'unit_name' => $unit->unitName,
                            'date' => $row->ProductionDate,
                            'received' => 0,
                            'delivery' => 0,
                            'total_quantity' => 0
                        ];
                    }

                    // Add data based on process name
                    if ($row->ProcessName === 'Send from Wash') {
                        $washData[$unit->id]['received'] += $row->Quantity;
                    } elseif ($row->ProcessName === 'Received from Sewing') {
                        $washData[$unit->id]['delivery'] += $row->Quantity;
                    }

                    $washData[$unit->id]['total_quantity'] += $row->Quantity;
                }
            }

            return array_values($washData);
        } catch (\Exception $e) {
            // Log error and return empty array if SQL Server connection fails
            \Log::error('SQL Server wash data error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Calculate current machine count for a specific date (considering transfers up to that date)
     */
    private function getCurrentMachineCountForDate($unitId, $date)
    {
        $dailyCount = $this->getOrCreateDailyMachineCount($unitId, $date);
        $baseCount = $dailyCount->machine_count;

        // Calculate net transfers for this specific date only
        $transferredOut = MachineTransfer::where('from_unit_id', $unitId)
            ->whereDate('transfer_date', $date)
            ->approved()
            ->sum('machine_count');

        $transferredIn = MachineTransfer::where('to_unit_id', $unitId)
            ->whereDate('transfer_date', $date)
            ->approved()
            ->sum('machine_count');

        return $baseCount - $transferredOut + $transferredIn;
    }

    /**
     * Calculate current capacity based on transfers
     */

    // ....... End Machine Dashboard .......
}