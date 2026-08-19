<?php

namespace App\Http\Controllers\Backend;

use App\Models\Unit;
use App\Models\DayCapacity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class DayCapacityController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('backend.pages.day-capacity.index', compact('units'));
    }

    public function dashboard()
    {
        $units = Unit::all();
        $today = now()->format('Y-m-d');
        
        // Get today's capacities for all units
        $todayCapacities = DayCapacity::with('unit')
            ->where('date', $today)
            ->get()
            ->keyBy('unit_id');

        return view('backend.pages.day-capacity.dashboard', compact('units', 'todayCapacities', 'today'));
    }

    public function getList(Request $request)
    {
        $data = DayCapacity::with('unit');

        if ($request->date) {
            $data->where('date', $request->date);
        }

        if ($request->unit_id) {
            $data->where('unit_id', $request->unit_id);
        }

        return Datatables::of($data)
            ->editColumn('date', function ($row) {
                return $row->date->format('d M, Y');
            })
            ->addColumn('unit_name', function ($row) {
                return $row->unit->unitName;
            })
            ->editColumn('total_initial_target', function ($row) {
                return number_format($row->total_initial_target, 2);
            })
            ->editColumn('total_mg_target', function ($row) {
                return number_format($row->total_mg_target, 2);
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

    public function store(Request $request)
    {
        $validator = $request->validate([
            'date' => 'required|date',
            'unit_id' => 'required|exists:unit,id',
        ]);

        // Check if capacity already exists for this date and unit
        $existingCapacity = DayCapacity::where('date', $request->date)
            ->where('unit_id', $request->unit_id)
            ->first();

        if ($existingCapacity) {
            return response()->json([
                'type' => 'error',
                'message' => 'Capacity already exists for this date and unit.',
            ]);
        }

        $unit = Unit::find($request->unit_id);
        
        // Calculate per hour targets
        $initialTargetPerHour = 1000 / 24; // 41.6666 per hour
        $mgTargetPerHour = 956.52 / 24; // 39.855 per hour

        $dayCapacity = new DayCapacity();
        $dayCapacity->date = $request->date;
        $dayCapacity->unit_id = $request->unit_id;

        $totalInitial = 0;
        $totalMg = 0;

        // Set values for each hour and calculate totals
        for ($hour = 0; $hour < 24; $hour++) {
            $hourField = 'hour_' . sprintf('%02d', $hour);
            $hourValue = $request->$hourField ?? 0;
            $dayCapacity->$hourField = $hourValue;

            // Calculate targets for this hour based on machine count
            $hourInitialTarget = $hourValue * $initialTargetPerHour * $unit->machineCount;
            $hourMgTarget = $hourValue * $mgTargetPerHour * $unit->machineCount;

            $totalInitial += $hourInitialTarget;
            $totalMg += $hourMgTarget;
        }

        $dayCapacity->total_initial_target = $totalInitial;
        $dayCapacity->total_mg_target = $totalMg;

        if ($dayCapacity->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Day capacity created successfully.',
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Failed to create day capacity.',
        ]);
    }

    public function edit($id)
    {
        $capacity = DayCapacity::with('unit')->find($id);
        $units = Unit::all();
        
        if (!$capacity) {
            return response()->json(['error' => 'Capacity record not found.'], 404);
        }
        
        return view('backend.pages.day-capacity.edit', compact('capacity', 'units'));
    }

    public function update(Request $request, $id)
    {
        $dayCapacity = DayCapacity::findOrFail($id);
        $unit = Unit::find($dayCapacity->unit_id);

        $validator = $request->validate([
            'date' => 'required|date',
            'unit_id' => 'required|exists:unit,id',
        ]);

        // Calculate per hour targets
        $initialTargetPerHour = 1000 / 24; // 41.6666 per hour
        $mgTargetPerHour = 956.52 / 24; // 39.855 per hour

        $dayCapacity->date = $request->date;
        $dayCapacity->unit_id = $request->unit_id;

        $totalInitial = 0;
        $totalMg = 0;

        // Set values for each hour and calculate totals
        for ($hour = 0; $hour < 24; $hour++) {
            $hourField = 'hour_' . sprintf('%02d', $hour);
            $hourValue = $request->$hourField ?? 0;
            $dayCapacity->$hourField = $hourValue;

            // Calculate targets for this hour based on machine count
            $hourInitialTarget = $hourValue * $initialTargetPerHour * $unit->machineCount;
            $hourMgTarget = $hourValue * $mgTargetPerHour * $unit->machineCount;

            $totalInitial += $hourInitialTarget;
            $totalMg += $hourMgTarget;
        }

        $dayCapacity->total_initial_target = $totalInitial;
        $dayCapacity->total_mg_target = $totalMg;

        if ($dayCapacity->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Day capacity updated successfully.',
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Failed to update day capacity.',
        ]);
    }

    public function delete($id)
    {
        $capacity = DayCapacity::find($id);
        if ($capacity) {
            $capacity->delete();
            return response()->json(['success' => 'Capacity record deleted successfully.']);
        } else {
            return response()->json(['error' => 'Capacity record not found.']);
        }
    }

    // Helper method to get hourly breakdown
    public function getHourlyBreakdown($unitId)
    {
        $unit = Unit::find($unitId);
        if (!$unit) {
            return response()->json(['error' => 'Unit not found.'], 404);
        }

        $initialTargetPerHour = 1000 / 24;
        $mgTargetPerHour = 956.52 / 24;

        $breakdown = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $breakdown[] = [
                'hour' => sprintf('%02d:00', $hour),
                'hour_field' => 'hour_' . sprintf('%02d', $hour),
                'initial_per_machine' => $initialTargetPerHour,
                'mg_per_machine' => $mgTargetPerHour,
                'total_initial' => $initialTargetPerHour * $unit->machineCount,
                'total_mg' => $mgTargetPerHour * $unit->machineCount
            ];
        }

        return response()->json($breakdown);
    }
}