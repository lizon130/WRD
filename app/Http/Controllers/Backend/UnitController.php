<?php

namespace App\Http\Controllers\Backend;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class UnitController extends Controller
{
    public function index()
    {
        return view('backend.pages.unit.index');
    }

    public function getList(Request $request)
    {
        try {
            $data = Unit::query();

            // Apply filters
            if ($request->has('unitName') && !empty($request->unitName)) {
                $data->where('unitName', 'like', "%" . $request->unitName . "%");
            }

            if ($request->has('machineCount') && !empty($request->machineCount)) {
                $data->where('machineCount', $request->machineCount);
            }

            if ($request->has('sewing_lines') && !empty($request->sewing_lines)) {
                $data->where('sewing_lines', 'like', "%" . $request->sewing_lines . "%");
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('initialTarget', function ($row) {
                    return number_format((float)$row->initialTarget, 0);
                })
                ->editColumn('mgTarget', function ($row) {
                    return number_format((float)$row->mgTarget, 0);
                })
                ->editColumn('capacity_kg', function ($row) {
                    return number_format(round((float)$row->capacity_kg), 0);
                })
                ->editColumn('capacity_pieces', function ($row) {
                    return number_format(round((float)$row->capacity_pieces), 0);
                })
                ->editColumn('piece_weight_gram', function ($row) {
                    return number_format((float)$row->piece_weight_gram, 0) . 'g';
                })
                ->editColumn('sewing_lines', function ($row) {
                    return $row->sewing_lines ?? '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M, Y H:i') : 'N/A';
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at ? $row->updated_at->format('d M, Y H:i') : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '<a href="" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary me-1"><i class="fa-solid fa-pencil"></i></a>';
                    $btn .= '<a class="delete_btn btn btn-sm btn-danger" data-id="' . $row->id . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error('DataTables error: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'unitName' => 'required|string|max:255|unique:unit,unitName',
            'machineCount' => 'required|integer|min:0',
            'capacity_kg' => 'required|numeric|min:0',
            'piece_weight_gram' => 'required|numeric|min:0',
            'sewing_lines' => 'nullable|string|max:255', // New validation as string
        ]);

        $unit = new Unit();
        $unit->unitName = $request->unitName;
        $unit->machineCount = $request->machineCount;
        $unit->initialTarget = $request->machineCount * 1000;
        $unit->mgTarget = $request->mgTarget;
        $unit->capacity_kg = $request->capacity_kg;
        $unit->piece_weight_gram = $request->piece_weight_gram;
        $unit->sewing_lines = $request->sewing_lines; // New field

        // Calculate capacity_pieces dynamically
        $piece_weight_kg = $request->piece_weight_gram / 1000; // Convert gram to kg
        $unit->capacity_pieces = $piece_weight_kg > 0 ? $request->capacity_kg / $piece_weight_kg : 0;

        if ($unit->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Unit created successfully.',
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Failed to create unit.',
        ]);
    }

    public function edit($id)
    {
        $unit = Unit::find($id);
        if (!$unit) {
            return response()->json(['error' => 'Unit not found.'], 404);
        }

        return view('backend.pages.unit.edit', compact('unit'));
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $validator = $request->validate([
            'unitName' => 'required|string|max:255|unique:unit,unitName,' . $id,
            'machineCount' => 'required|integer|min:0',
            'capacity_kg' => 'required|numeric|min:0',
            'piece_weight_gram' => 'required|numeric|min:0',
            'sewing_lines' => 'nullable|string|max:255', // New validation as string
        ]);

        $unit->unitName = $request->unitName;
        $unit->machineCount = $request->machineCount;
        $unit->initialTarget = $request->machineCount * 1000;
        $unit->mgTarget = $request->machineCount * 956.52;
        $unit->capacity_kg = $request->capacity_kg;
        $unit->piece_weight_gram = $request->piece_weight_gram;
        $unit->sewing_lines = $request->sewing_lines; // New field

        // Calculate capacity_pieces dynamically
        $piece_weight_kg = $request->piece_weight_gram / 1000; // Convert gram to kg
        $unit->capacity_pieces = $piece_weight_kg > 0 ? $request->capacity_kg / $piece_weight_kg : 0;

        if ($unit->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Unit updated successfully.',
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Failed to update unit.',
        ]);
    }

    public function delete($id)
    {
        $unit = Unit::find($id);
        if ($unit) {
            $unit->delete();
            return response()->json(['success' => 'Unit deleted successfully.']);
        } else {
            return response()->json(['error' => 'Unit not found.']);
        }
    }
}