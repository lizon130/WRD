<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMode;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeliveryModeController extends Controller
{
    public function list(Request $request)
    {
        // Extract filters and pagination details from the request
        $perPage = $request->input('perPage', 20); // Default to 20 items per page
        $page = $request->input('page', 1);
        $filterText = $request->input('filterText', '');
        $filterStatus = $request->input('status', '');
        $sortField = $request->input('sortField', 'created_at'); // Default to 'id'
        $sortOrder = $request->input('sortOrder', 'desc'); // Default to ascending

        // Query the users with optional filters
        $query = DeliveryMode::query();

        // Filter by text (search across name, email, or phone fields)
        if (!empty($filterText)) {
            $query->where(function ($q) use ($filterText) {
                $q->where('name', 'LIKE', "%{$filterText}%");
            });
        }

        // Filter by status
        if ($filterStatus) {
            $query->where('status', $filterStatus ? 1 : 0);
        }

        // Apply sorting
        $query->orderBy($sortField, $sortOrder);

        // Get paginated data
        $data = $query->paginate($perPage, ['*'], 'page', $page);
        // Return the response
        return response()->json([
            'status' => 1,
            'message' => 'Data retrieved successfully!',
            'data' => $data->items(), // Current page items
            'meta' => [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
            ],
        ], 200);
    }

    public function store(Request $request){
        // Validate the input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
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
            $deliverymode = new DeliveryMode();
            $deliverymode->name = ucfirst($request->name);
            $deliverymode->status = $request->status ? 1 : 0; // Default to 0 if not provided
            $deliverymode->save();

            // Insert translation for the segmentation
            Helper::insertLanguage(DeliveryMode::class, $deliverymode->id, 'en', 'name', $deliverymode->name);

            DB::commit(); // Commit the transaction

            return response()->json([
                'status' => 1,
                'message' => 'Delivery Mode created successfully',
                'data' => $deliverymode,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on error

            return response()->json([
                'status' => 0,
                'message' => 'Failed to create deliverymode type',
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
            'status' => 'nullable',
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
            $deliverymode = DeliveryMode::findOrFail($id);
            $deliverymode->name = ucfirst($request->name);
            $deliverymode->status = $request->status ? 1 : 0; // Default to 0 if not provided
            $deliverymode->save();

            // Insert translation for the segmentation
            Helper::insertLanguage(DeliveryMode::class, $deliverymode->id, 'en', 'name', $deliverymode->name);

            DB::commit(); // Commit the transaction

            return response()->json([
                'status' => 1,
                'message' => 'Delivery Mode updated successfully',
                'data' => $deliverymode,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on error

            return response()->json([
                'status' => 0,
                'message' => 'Failed to update delivery mode',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function delete($id){
        $deliverymode = DeliveryMode::findOrFail($id);
        $deliverymode->delete();
        return response()->json([
            'status' => 1,
            'message' => 'Delivery Mode deleted successfully'
        ], 200);
    }

}
