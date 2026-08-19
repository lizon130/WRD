<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class DepartmentAPIController extends Controller
{
    public function list(Request $request)
    {
        try {
        
            // query 
            $department_info = Department::orderBy('created_at', 'DESC')->get();

            // Check if data exists
            if ($department_info->isNotEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data found',
                    'data' => $department_info
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'No data found',
                'data' => []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function fetchDeptNames(Request $request)
    {
        try {
        
            // query 
            $department_info = Department::orderBy('created_at', 'DESC')->get();

            // Extract only dept_name values into a collection
            $deptNames = $department_info->pluck('dept_name');

            // Check if data exists
            if ($deptNames->isNotEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data found',
                    'data' => $deptNames
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    

    public function searchFilter(Request $request){
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'dept_name' => 'required|string|max:255'
            ], [
               
                'dept_name.max' => 'Department name should not exceed 255 characters.',
                'dept_name.required' => 'Department is required.',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            } 
    
            // Start query - fetch only active events
            $department_info = Department::query();
    
            // Apply filters dynamically
            if ($request->filled('dept_name')) {
                $department_info->where('type', '=',  $request->input('type') );
            }

            
            // Filter only active department_info (assuming active status is 1)
            $active_department_info = 1; 
            $department_info->where('status', $active_department_info);

    
            // Fetch results and order by created_at (latest first)
            $department_info = $department_info->orderBy('created_at', 'desc')->get();

    
            if ($department_info->isNotEmpty()) {
                return response()->json(['success' => true, 'message' => 'Data found', 'data' => $department_info]);
            }
            
            return response()->json(['success' => true, 'message' => 'No data found', 'data' => []]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
