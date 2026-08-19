<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WashReportManPower;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class WashReportManPowerController extends Controller
{
    /**
     * Display the manpower index page
     */
    public function index()
    {
        // Static unit options for filter dropdown
        $units = ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL'];

        return view('backend.manpower.index', compact('units'));
    }

    /**
     * Get manpower data for DataTable
     */
    public function getList(Request $request)
    {
        $data = WashReportManPower::select('washReportManPower.*');

        // Apply filters
        if ($request->filled('date')) {
            $data->whereDate('date', $request->date);
        }

        if ($request->filled('unit')) {
            $data->where('unit', $request->unit);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $data->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-info edit_btn mr-1" data-id="' . $row->id . '" title="Edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>';
                $btn .= '<button type="button" class="btn btn-sm btn-danger delete_btn" data-id="' . $row->id . '" title="Delete">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>';
                return $btn;
            })
            ->editColumn('date', function ($row) {
                return Carbon::parse($row->date)->format('d-m-Y');
            })
            ->editColumn('direct', function ($row) {
                return number_format($row->direct);
            })
            ->editColumn('indirect', function ($row) {
                return number_format($row->indirect);
            })
            ->editColumn('work_hours', function ($row) {
                return number_format($row->work_hours, 2);
            })
            ->editColumn('smv', function ($row) {
                return number_format($row->smv, 2);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show create form modal
     */
    public function createForm()
    {
        // Static unit options for create form
        $units = ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL'];

        return view('backend.manpower.create', compact('units'));
    }

    /**
     * Store new manpower record
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'unit' => 'required|in:Unit 1,Unit 2,Unit 3,Unit 4,Unit 5,Unit TWL',
            'direct' => 'required|integer|min:0',
            'indirect' => 'required|integer|min:0',
            'work_hours' => 'required|numeric|min:0',
            'smv' => 'required|numeric|min:0',
        ]);

        try {
            // Check for duplicate entry
            $exists = WashReportManPower::where('date', $request->date)
                ->where('unit', $request->unit)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Manpower record for this date and unit already exists!'
                ], 422);
            }

            $manpower = WashReportManPower::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Manpower record created successfully!',
                'data' => $manpower
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating manpower record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show edit form modal
     */
    public function edit($id)
    {
        $manpower = WashReportManPower::findOrFail($id);

        // Static unit options for edit form
        $units = ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL'];

        return view('backend.manpower.edit', compact('manpower', 'units'));
    }

    /**
     * Update manpower record
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'unit' => 'required|in:Unit 1,Unit 2,Unit 3,Unit 4,Unit 5,Unit TWL',
            'direct' => 'required|integer|min:0',
            'indirect' => 'required|integer|min:0',
            'work_hours' => 'required|numeric|min:0',
            'smv' => 'required|numeric|min:0',
        ]);

        try {
            $manpower = WashReportManPower::findOrFail($id);

            // Check for duplicate entry (excluding current record)
            $exists = WashReportManPower::where('date', $request->date)
                ->where('unit', $request->unit)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Another manpower record for this date and unit already exists!'
                ], 422);
            }

            $manpower->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Manpower record updated successfully!',
                'data' => $manpower
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating manpower record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete manpower record
     */
    public function delete($id)
    {
        try {
            $manpower = WashReportManPower::findOrFail($id);
            $manpower->delete();

            return response()->json([
                'success' => true,
                'message' => 'Manpower record deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting manpower record: ' . $e->getMessage()
            ]);
        }
    }
}