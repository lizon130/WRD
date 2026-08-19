<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Dryer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DryerController extends Controller
{
    public function index()
    {
        return view('backend.pages.dryer.index');
    }

    // Add this method
  public function createForm()
{
    $units = Dryer::getAvailableUnits();
    return view('backend.pages.dryer.modal', compact('units'));
}

    public function getList(Request $request)
    {
        $data = Dryer::query();

        // Apply filters if any
        if ($request->filled('unit')) {
            $data->where('unit', $request->unit);
        }
        
        // Fix date filter logic - prioritize specific date over date range
        if ($request->filled('date')) {
            $data->whereDate('date', $request->date);
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $data->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $editBtn = '<button class="btn btn-sm btn-primary edit_btn mr-1" data-id="' . $row->id . '">
                    <i class="fa fa-edit"></i>
                </button>';
                $deleteBtn = '<button class="btn btn-sm btn-danger delete_btn" data-id="' . $row->id . '">
                    <i class="fa fa-trash"></i>
                </button>';
                return $editBtn . $deleteBtn;
            })
            ->editColumn('capacity', function ($row) {
                return number_format($row->capacity, 2);
            })
            ->editColumn('avg_dryer_time', function ($row) {
                return $row->avg_dryer_time ? number_format($row->avg_dryer_time, 2) : '-';
            })
            ->editColumn('avg_batch', function ($row) {
                return $row->avg_batch ? number_format($row->avg_batch, 2) : '-';
            })
            ->editColumn('working_hr', function ($row) {
                return $row->working_hr ? number_format($row->working_hr, 2) : '-';
            })
            ->editColumn('targetQty', function ($row) {
                return $row->targetQty ? number_format($row->targetQty, 2) : '-';
            })
            ->editColumn('first_wash_dryer', function ($row) {
                return number_format($row->first_wash_dryer, 2);
            })
            ->editColumn('cold_dryer', function ($row) {
                return number_format($row->cold_dryer, 2);
            })
            ->editColumn('measurement_correction', function ($row) {
                return number_format($row->measurement_correction, 2);
            })
            ->editColumn('final_wash_dryer', function ($row) {
                return number_format($row->final_wash_dryer, 2);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit' => 'required|string|in:' . implode(',', Dryer::getAvailableUnits()),
            'date' => 'required|date',
            'capacity' => 'required|numeric|min:0',
            'avg_dryer_time' => 'nullable|numeric|min:0',
            'avg_batch' => 'nullable|numeric|min:0',
            'working_hr' => 'nullable|numeric|min:0',
            'targetQty' => 'nullable|numeric|min:0',
            'first_wash_dryer' => 'required|numeric|min:0',
            'cold_dryer' => 'required|numeric|min:0',
            'measurement_correction' => 'required|numeric|min:0',
            'final_wash_dryer' => 'required|numeric|min:0',
        ]);

        // Check if entry for this unit and date already exists
        $existingDryer = Dryer::where('unit', $request->unit)
            ->where('date', $request->date)
            ->first();

        if ($existingDryer) {
            return response()->json([
                'success' => false,
                'message' => 'Dryer for this unit already exists on this date.'
            ], 422);
        }

        // Create new dryer
        $dryer = new Dryer();
        $dryer->unit = $request->unit;
        $dryer->num_dryer = Dryer::getUnitDryerCount($request->unit);
        $dryer->date = $request->date;
        $dryer->capacity = $request->capacity;
        $dryer->avg_dryer_time = $request->avg_dryer_time;
        $dryer->avg_batch = $request->avg_batch;
        $dryer->working_hr = $request->working_hr;
        $dryer->targetQty = $request->targetQty;
        $dryer->first_wash_dryer = $request->first_wash_dryer;
        $dryer->cold_dryer = $request->cold_dryer;
        $dryer->measurement_correction = $request->measurement_correction;
        $dryer->final_wash_dryer = $request->final_wash_dryer;
        $dryer->save();

        return response()->json([
            'success' => true,
            'message' => 'Dryer data added successfully!'
        ]);
    }

    public function edit($id)
    {
        try {
            $dryer = Dryer::findOrFail($id);
            $units = Dryer::getAvailableUnits();

            return view('backend.pages.dryer.edit-modal', compact('dryer', 'units'));
        } catch (\Exception $e) {
            \Log::error('Error loading dryer edit form: ' . $e->getMessage());
            return response()->view('backend.pages.dryer.edit-modal', [
                'error' => 'Error loading dryer data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'unit' => 'required|string|in:' . implode(',', Dryer::getAvailableUnits()),
            'date' => 'required|date',
            'capacity' => 'required|numeric|min:0',
            'avg_dryer_time' => 'nullable|numeric|min:0',
            'avg_batch' => 'nullable|numeric|min:0',
            'working_hr' => 'nullable|numeric|min:0',
            'targetQty' => 'nullable|numeric|min:0',
            'first_wash_dryer' => 'required|numeric|min:0',
            'cold_dryer' => 'required|numeric|min:0',
            'measurement_correction' => 'required|numeric|min:0',
            'final_wash_dryer' => 'required|numeric|min:0',
        ]);

        $dryer = Dryer::findOrFail($id);

        // Check if unit OR date is being changed and if another dryer exists with the new unit AND date
        if ($dryer->unit !== $request->unit || $dryer->date !== $request->date) {
            $existingDryer = Dryer::where('unit', $request->unit)
                ->where('date', $request->date)
                ->where('id', '!=', $id)
                ->first();

            if ($existingDryer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dryer for this unit already exists on this date.'
                ], 422);
            }

            // Update num_dryer only if unit changed
            if ($dryer->unit !== $request->unit) {
                $dryer->num_dryer = Dryer::getUnitDryerCount($request->unit);
            }
        }

        $dryer->unit = $request->unit;
        $dryer->date = $request->date;
        $dryer->capacity = $request->capacity;
        $dryer->avg_dryer_time = $request->avg_dryer_time;
        $dryer->avg_batch = $request->avg_batch;
        $dryer->working_hr = $request->working_hr;
        $dryer->targetQty = $request->targetQty;
        $dryer->first_wash_dryer = $request->first_wash_dryer;
        $dryer->cold_dryer = $request->cold_dryer;
        $dryer->measurement_correction = $request->measurement_correction;
        $dryer->final_wash_dryer = $request->final_wash_dryer;
        $dryer->save();

        return response()->json([
            'success' => true,
            'message' => 'Dryer data updated successfully!'
        ]);
    }

    public function delete($id)
    {
        $dryer = Dryer::findOrFail($id);
        $dryer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dryer data deleted successfully!'
        ]);
    }

  /**
 * Get latest values for a unit
 */
public function getLatestValues(Request $request)
{
    $unit = $request->get('unit');
    
    if (!$unit) {
        return response()->json([
            'success' => false,
            'message' => 'Unit is required'
        ]);
    }
    
    $latest = Dryer::where('unit', $unit)
        ->orderBy('date', 'desc')
        ->first();
    
    if ($latest) {
        return response()->json([
            'success' => true,
            'data' => [
                'capacity' => $latest->capacity,
                'avg_dryer_time' => $latest->avg_dryer_time,
                'avg_batch' => $latest->avg_batch,
                'targetQty' => $latest->targetQty,
                'date' => $latest->date instanceof \Carbon\Carbon ? $latest->date->format('Y-m-d') : $latest->date
            ]
        ]);
    }
    
    return response()->json([
        'success' => false,
        'message' => 'No previous data found'
    ]);
}
}