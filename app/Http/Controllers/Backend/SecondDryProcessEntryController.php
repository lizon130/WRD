<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SecondDryProcessEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SecondDryProcessEntryController extends Controller
{
    /**
     * Display the index page
     */
    public function index()
    {
        $plants = ['TPL', 'TWL'];
        $processTypes = ['Laser', 'PP Spray', '2nd Dry Final', 'Whisker', 'Hand Brush', '1st Dry Final'];

        return view('backend.second-dry-process-entry.index', compact('plants', 'processTypes'));
    }

    /**
     * Get data for DataTable
     */
    public function getList(Request $request)
    {
        $query = SecondDryProcessEntry::query();

        // Apply filters
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('plant')) {
            $query->where('plant', $request->plant);
        }

        if ($request->filled('processType')) {
            $query->where('processType', $request->processType);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" class="btn btn-sm btn-primary edit_btn" data-id="' . $row->id . '">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger delete_btn" data-id="' . $row->id . '">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                ';
            })
            ->editColumn('date', function ($row) {
                return date('d-m-Y', strtotime($row->date));
            })
            ->editColumn('TargetQty', function ($row) {
                return number_format($row->TargetQty);
            })
            ->editColumn('ProductionQty', function ($row) {
                return number_format($row->ProductionQty);
            })
            ->editColumn('defectQty', function ($row) {
                return $row->defectQty ? number_format($row->defectQty) : '-';
            })
            ->addColumn('achievement', function ($row) {
                if ($row->TargetQty > 0) {
                    $percentage = ($row->ProductionQty / $row->TargetQty) * 100;
                    $badgeClass = $percentage >= 100 ? 'success' : ($percentage >= 80 ? 'warning' : 'danger');
                    return '<span class="badge bg-' . $badgeClass . '">' . number_format($percentage, 2) . '%</span>';
                }
                return '<span class="badge bg-secondary">0%</span>';
            })
            ->rawColumns(['action', 'achievement'])
            ->make(true);
    }

    /**
     * Get create form for modal
     */
    public function createForm()
    {
        $plants = ['TPL', 'TWL'];
        $processTypes = [
            'TPL' => ['Laser', 'PP Spray'],
            'TWL' => ['Laser', 'PP Spray', '2nd Dry Final', 'Whisker', 'Hand Brush', '1st Dry Final']
        ];

        return view('backend.second-dry-process-entry.create', compact('plants', 'processTypes'));
    }

    /**
     * Store new record
     */
    public function store(Request $request)
    {
        $rules = [
            'plant' => 'required|in:TPL,TWL',
            'date' => 'required|date',
            'processType' => 'required|string',
            'TargetQty' => 'required|integer|min:0',
            'ProductionQty' => 'required|integer|min:0',
            'defectQty' => 'nullable|integer|min:0',
        ];

        // Make defectQty required for 1st Dry Final and 2nd Dry Final
        if (in_array($request->processType, ['1st Dry Final', '2nd Dry Final'])) {
            $rules['defectQty'] = 'required|integer|min:0';
        }

        $validator = Validator::make($request->all(), $rules);

        // Additional validation based on plant
        $validator->after(function ($validator) use ($request) {
            $plant = $request->plant;
            $processType = $request->processType;

            if ($plant === 'TPL' && !in_array($processType, ['Laser', 'PP Spray'])) {
                $validator->errors()->add('processType', 'TPL plant only allows Laser and PP Spray process types.');
            }

            if ($plant === 'TWL' && !in_array($processType, ['Laser', 'PP Spray', '2nd Dry Final', 'Whisker', 'Hand Brush', '1st Dry Final'])) {
                $validator->errors()->add('processType', 'Invalid process type for TWL plant.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            SecondDryProcessEntry::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Second Dry Process Entry created successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get edit form for modal
     */
    public function edit($id)
    {
        $secondDryProcess = SecondDryProcessEntry::findOrFail($id);
        $plants = ['TPL', 'TWL'];
        $processTypes = [
            'TPL' => ['Laser', 'PP Spray'],
            'TWL' => ['Laser', 'PP Spray', '2nd Dry Final', 'Whisker', 'Hand Brush', '1st Dry Final']
        ];

        return view('backend.second-dry-process-entry.edit', compact('secondDryProcess', 'plants', 'processTypes'));
    }

    /**
     * Update record
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'plant' => 'required|in:TPL,TWL',
            'date' => 'required|date',
            'processType' => 'required|string',
            'TargetQty' => 'required|integer|min:0',
            'ProductionQty' => 'required|integer|min:0',
            'defectQty' => 'nullable|integer|min:0',
        ];

        // Make defectQty required for 1st Dry Final and 2nd Dry Final
        if (in_array($request->processType, ['1st Dry Final', '2nd Dry Final'])) {
            $rules['defectQty'] = 'required|integer|min:0';
        }

        $validator = Validator::make($request->all(), $rules);

        // Additional validation based on plant
        $validator->after(function ($validator) use ($request) {
            $plant = $request->plant;
            $processType = $request->processType;

            if ($plant === 'TPL' && !in_array($processType, ['Laser', 'PP Spray'])) {
                $validator->errors()->add('processType', 'TPL plant only allows Laser and PP Spray process types.');
            }

            if ($plant === 'TWL' && !in_array($processType, ['Laser', 'PP Spray', '2nd Dry Final', 'Whisker', 'Hand Brush', '1st Dry Final'])) {
                $validator->errors()->add('processType', 'Invalid process type for TWL plant.');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $secondDryProcess = SecondDryProcessEntry::findOrFail($id);
            $secondDryProcess->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Second Dry Process Entry updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete record
     */
    public function delete($id)
    {
        try {
            $secondDryProcess = SecondDryProcessEntry::findOrFail($id);
            $secondDryProcess->delete();

            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting record: ' . $e->getMessage()
            ], 500);
        }
    }
}
