<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Segmentation;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SegmentController extends Controller
{
    public function list(Request $request){
        $segments = Segmentation::with('children', 'children.children', 'children.children.children')->whereNull('ancestor_id')->orderBy('ordering', 'asc')->get();
        return response()->json([
            'status' => 1,
            'message' => 'Data retrieved successfully!',
            'data' => $segments, // Current page items
            'all_segments' => Segmentation::orderBy('ordering', 'asc')->get(), // Current page items
        ], 200);
    }

    public function store(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parentId' => 'nullable|integer|exists:segmentations,id', // Ensure parentId references a valid segmentation
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Create a new segmentation record
            $segmentation = new Segmentation();
            $segmentation->name = ucfirst($request->name);
            $segmentation->ancestor_id = $request->parentId; // Nullable field
            $segmentation->status = $request->status ? 1 : 0; // Default to 0 if not provided
            $segmentation->save();

            // Insert translation for the segmentation
            Helper::insertLanguage(Segmentation::class, $segmentation->id, 'en', 'name', $segmentation->name);

            DB::commit(); // Commit the transaction

            return response()->json([
                'status' => 1,
                'message' => 'Segment created successfully',
                'data' => $segmentation,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on error

            return response()->json([
                'status' => 0,
                'message' => 'Failed to create segment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'parentId' => 'nullable|integer|exists:segmentations,id', // Ensure parentId references a valid segmentation
            'status' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Find the segmentation record to update
            $segmentation = Segmentation::findOrFail($id);

            // Update the segmentation fields
            $segmentation->name = ucfirst($request->name);
            $segmentation->ancestor_id = $request->parentId; // Nullable field
            $segmentation->status = $request->status ? 1 : 0; // Default to 0 if not provided
            $segmentation->save();

            // Insert or update the translation for the segmentation
            Helper::insertLanguage(Segmentation::class, $segmentation->id, 'en', 'name', $segmentation->name);

            DB::commit(); // Commit the transaction

            return response()->json([
                'status' => 1,
                'message' => 'Segment updated successfully',
                'data' => $segmentation,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on error

            return response()->json([
                'status' => 0,
                'message' => 'Failed to update segment',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id){
        $segmentation = Segmentation::findOrFail($id);
        $segmentation->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Segment deleted successfully'
        ], 200);
    }
}
