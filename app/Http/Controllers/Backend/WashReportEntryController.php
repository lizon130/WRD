<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WashReportEntry;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class WashReportEntryController extends Controller
{
    /**
     * Display the wash report entry index page
     */
    public function index()
    {
        // Static unit options for filter dropdown
        $units = ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL'];

        return view('backend.wash-report-entry.index', compact('units'));
    }

    public function getList(Request $request)
    {
        $data = WashReportEntry::select('washReportEntry.*');

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

        if ($request->filled('sewing_line')) {
            $data->where('SewingLine', 'like', '%' . $request->sewing_line . '%');
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
            ->addColumn('total_qty', function ($row) {
                return $row->FirstWashQty + $row->AcidWashQty + $row->FinalWashQty + $row->ReWashQty;
            })
            ->editColumn('date', function ($row) {
                return Carbon::parse($row->date)->format('d-m-Y');
            })
            ->editColumn('FirstWashQty', function ($row) {
                return number_format($row->FirstWashQty);
            })
            ->editColumn('AcidWashQty', function ($row) {
                return number_format($row->AcidWashQty);
            })
            ->editColumn('FinalWashQty', function ($row) {
                return number_format($row->FinalWashQty);
            })
            ->editColumn('ReWashQty', function ($row) {
                return number_format($row->ReWashQty);
            })
            ->editColumn('in_hand_balance', function ($row) {
                return $row->in_hand_balance !== null ? number_format($row->in_hand_balance) : '-';
            })
            ->editColumn('rework_dry_proc', function ($row) {
                return $row->rework_dry_proc !== null ? number_format($row->rework_dry_proc, 2) : '-';
            })
            ->editColumn('machine_work_hr', function ($row) {
                return $row->machine_work_hr !== null ? number_format($row->machine_work_hr, 2) : '-';
            })
            ->editColumn('SewingLine', function ($row) {
                return $row->SewingLine ?? '-';
            })
            ->editColumn('Remarks', function ($row) {
                return $row->Remarks ?? '-';
            })
            ->rawColumns(['action']) // This is important - tells DataTable to render HTML in action column
            ->make(true);
    }

    /**
     * Show create form modal
     */
    public function createForm()
    {
        // Static unit options for create form
        $units = ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL'];

        return view('backend.wash-report-entry.create', compact('units'));
    }

    /**
     * Store new wash report entry record
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'unit' => 'required|in:Unit 1,Unit 2,Unit 3,Unit 4,Unit 5,Unit TWL',
            'FirstWashQty' => 'nullable|integer|min:0',
            'AcidWashQty' => 'nullable|integer|min:0',
            'FinalWashQty' => 'nullable|integer|min:0',
            'ReWashQty' => 'nullable|integer|min:0',
            'in_hand_balance' => 'nullable|integer|min:0',
            'rework_dry_proc' => 'nullable|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'machine_work_hr' => 'nullable|numeric|min:0|max:72|regex:/^\d+(\.\d{1,2})?$/', // Add this line
            'SewingLine' => 'nullable|string|max:100',
            'Remarks' => 'nullable|string',
        ]);

        try {
            // Check for duplicate entry
            $exists = WashReportEntry::where('date', $request->date)
                ->where('unit', $request->unit)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wash report entry for this date and unit already exists!'
                ], 422);
            }

            // Set default values for empty fields
            $data = $request->all();
            $data['FirstWashQty'] = $request->FirstWashQty ?? 0;
            $data['AcidWashQty'] = $request->AcidWashQty ?? 0;
            $data['FinalWashQty'] = $request->FinalWashQty ?? 0;
            $data['ReWashQty'] = $request->ReWashQty ?? 0;
            $data['in_hand_balance'] = $request->in_hand_balance !== null ? $request->in_hand_balance : null;
            $data['rework_dry_proc'] = $request->rework_dry_proc !== null ? $request->rework_dry_proc : null;
            $data['machine_work_hr'] = $request->machine_work_hr !== null ? $request->machine_work_hr : null; // Add this line

            $entry = WashReportEntry::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Wash report entry created successfully!',
                'data' => $entry
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating wash report entry: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show edit form modal
     */
    public function edit($id)
    {
        $entry = WashReportEntry::findOrFail($id);

        // Static unit options for edit form
        $units = ['Unit 1', 'Unit 2', 'Unit 3', 'Unit 4', 'Unit 5', 'Unit TWL'];

        return view('backend.wash-report-entry.edit', compact('entry', 'units'));
    }

    /**
     * Update wash report entry record
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'unit' => 'required|in:Unit 1,Unit 2,Unit 3,Unit 4,Unit 5,Unit TWL',
            'FirstWashQty' => 'nullable|integer|min:0',
            'AcidWashQty' => 'nullable|integer|min:0',
            'FinalWashQty' => 'nullable|integer|min:0',
            'ReWashQty' => 'nullable|integer|min:0',
            'in_hand_balance' => 'nullable|integer|min:0',
            'rework_dry_proc' => 'nullable|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'machine_work_hr' => 'nullable|numeric|min:0|max:72|regex:/^\d+(\.\d{1,2})?$/', // Add this line
            'SewingLine' => 'nullable|string|max:100',
            'Remarks' => 'nullable|string',
        ]);

        try {
            $entry = WashReportEntry::findOrFail($id);

            // Check for duplicate entry (excluding current record)
            $exists = WashReportEntry::where('date', $request->date)
                ->where('unit', $request->unit)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Another wash report entry for this date and unit already exists!'
                ], 422);
            }

            // Set default values for empty fields
            $data = $request->all();
            $data['FirstWashQty'] = $request->FirstWashQty ?? 0;
            $data['AcidWashQty'] = $request->AcidWashQty ?? 0;
            $data['FinalWashQty'] = $request->FinalWashQty ?? 0;
            $data['ReWashQty'] = $request->ReWashQty ?? 0;
            $data['in_hand_balance'] = $request->in_hand_balance !== null ? $request->in_hand_balance : null;
            $data['rework_dry_proc'] = $request->rework_dry_proc !== null ? $request->rework_dry_proc : null;
            $data['machine_work_hr'] = $request->machine_work_hr !== null ? $request->machine_work_hr : null; // Add this line

            $entry->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Wash report entry updated successfully!',
                'data' => $entry
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating wash report entry: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete wash report entry record
     */
    public function delete($id)
    {
        try {
            $entry = WashReportEntry::findOrFail($id);
            $entry->delete();

            return response()->json([
                'success' => true,
                'message' => 'Wash report entry deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting wash report entry: ' . $e->getMessage()
            ]);
        }
    }
}