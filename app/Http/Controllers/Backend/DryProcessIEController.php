<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DryProcessIE;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DryProcessIEController extends Controller
{
    /**
     * Display the index page
     */
    public function index()
    {
        $plants = ['TPL', 'TWL'];
        $processTypes = ['1st Dry Process', '2nd Dry Process'];
        
        return view('backend.ie-dry-process.index', compact('plants', 'processTypes'));
    }

    /**
     * Get data for DataTable
     */
    public function getList(Request $request)
    {
        $query = DryProcessIE::query();

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
            ->editColumn('manPower', function ($row) {
                return number_format($row->manPower);
            })
            ->editColumn('workingHr', function ($row) {
                return number_format($row->workingHr, 2);
            })
            ->editColumn('smv', function ($row) {
                return number_format($row->smv, 2);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get create form for modal
     */
    public function createForm()
    {
        $plants = ['TPL', 'TWL'];
        $processTypes = ['1st Dry Process', '2nd Dry Process'];
        
        return view('backend.ie-dry-process.create', compact('plants', 'processTypes'));
    }

    /**
     * Store new record
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plant' => 'required|in:TPL,TWL',
            'date' => 'required|date',
            'processType' => 'required|in:1st Dry Process,2nd Dry Process',
            'manPower' => 'required|integer|min:1',
            'workingHr' => 'required|numeric|min:0.5|max:24',
            'smv' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DryProcessIE::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Dry Process IE data created successfully!'
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
        $dryProcess = DryProcessIE::findOrFail($id);
        $plants = ['TPL', 'TWL'];
        $processTypes = ['1st Dry Process', '2nd Dry Process'];
        
        return view('backend.ie-dry-process.edit', compact('dryProcess', 'plants', 'processTypes'));
    }

    /**
     * Update record
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'plant' => 'required|in:TPL,TWL',
            'date' => 'required|date',
            'processType' => 'required|in:1st Dry Process,2nd Dry Process',
            'manPower' => 'required|integer|min:1',
            'workingHr' => 'required|numeric|min:0.5|max:24',
            'smv' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $dryProcess = DryProcessIE::findOrFail($id);
            $dryProcess->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Dry Process IE data updated successfully!'
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
            $dryProcess = DryProcessIE::findOrFail($id);
            $dryProcess->delete();

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