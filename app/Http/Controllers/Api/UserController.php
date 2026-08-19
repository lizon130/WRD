<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function list(Request $request)
    {
        // Extract filters and pagination details from the request
        $perPage = $request->input('perPage', 20); // Default to 20 items per page
        $page = $request->input('page', 1);
        $filterText = $request->input('filterText', '');
        $filterRole = $request->input('role', '');
        $filterStatus = $request->input('status', '');
        $sortField = $request->input('sortField', 'created_at'); // Default to 'id'
        $sortOrder = $request->input('sortOrder', 'desc'); // Default to ascending

        // Query the users with optional filters
        $query = User::with('roles');

        // Filter by text (search across name, email, or phone fields)
        if (!empty($filterText)) {
            $query->where(function ($q) use ($filterText) {
                $q->where('first_name', 'LIKE', "%{$filterText}%")
                    ->orWhere('last_name', 'LIKE', "%{$filterText}%")
                    ->orWhere('email', 'LIKE', "%{$filterText}%")
                    ->orWhere('phone', 'LIKE', "%{$filterText}%");
            });
        }

        // Filter by role
        if (!empty($filterRole)) {
            $query->whereHas('roles', function ($q) use ($filterRole) {
                $q->where('id', $filterRole);
            });
        }

        // Filter by status
        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        // Apply sorting
        $query->orderBy($sortField, $sortOrder);

        // Get paginated data
        $data = $query->paginate($perPage, ['*'], 'page', $page);
        // Fetch all roles for the filters dropdown
        $roles = Role::all();

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
            'others_data' => [
                'roles' => $roles,
            ],
        ], 200);
    }

    public function store(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:user',
            'phone' => 'required|max:15|unique:user',
            'role' => 'required',
            'status' => 'required',
            'password' => 'nullable|min:6|max:20|confirmed',
            'company_name' => 'required_if:role,4|string|max:255',
            'contact_name' => 'required_if:role,4|string|max:255',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Begin database transaction
        DB::beginTransaction();

        try {
            // Create the user
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->role = $request->role;
            $user->status = $request->status;
            $user->password = Hash::make($request->password ?? $request->phone); // Fallback to phone if no password


            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $uploadPath = public_path('uploads/user-images');
            
                // Check if directory exists, if not, create it with proper permissions
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
            
                $filename = time() . uniqid() . $image->getClientOriginalName();
                $image->move($uploadPath, $filename);
                $user->media = 'uploads/user-images/' . $filename;
            }
            
            $user->save();

            // If role is 4, create the company
            if ($request->role == 4) {
                $company = new Company();
                $company->type = $user->role;
                $company->user_id = $user->id;
                $company->contact_name = $request->contact_name;
                $company->name = $request->company_name;
                $company->phone_number = $request->company_phone_number;
                $company->department = $request->department;
                $company->vat_no = $request->vat_no;
                $company->email = $request->company_email;
                $company->address = $request->address;
                $company->post_code = $request->post_code;
                $company->state = $request->state;
                $company->city = $request->city;
                $company->country = $request->country;
                $company->website_url = $request->website_url;
                $company->status = $request->status;

                if ($request->hasFile('profile_image')) {
                    $image = $request->file('profile_image');
                    $uploadPath = public_path('uploads/user-images');
                
                    // Check if directory exists, if not, create it with proper permissions
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                
                    $filename = time() . uniqid() . $image->getClientOriginalName();
                    $image->move($uploadPath, $filename);
                    $user->media = 'uploads/user-images/' . $filename;
                }


                $company->save();
            }

            // Commit the transaction
            DB::commit();

            // Return success response
            return response()->json([
                'status' => 1,
                'message' => 'User created successfully',
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Return error response
            return response()->json([
                'status' => 0,
                'message' => 'Failed to create user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function details($id)
    {
        try {
            
            $user_info = User::find($id);

            if (!$user_info) {
                return response()->json(['success' => false, 'message' => 'User info not found.'], 404);
            }

            return response()->json(['success' => true, 'data' => $user_info], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function searchFilter(Request $request){
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'email' => 'nullable|string|max:255',
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:100',
                'role' => 'nullable|integer', // take integer as role
            ]);
    
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            } 
    
            // Start query - fetch only active events
            $user_info = User::query();
    
            // Apply filters dynamically
            if ($request->filled('email')) {
                $user_info->where('email', '=',  $request->input('email'));
            }
            
            if ($request->filled('first_name')) {
                $user_info->where('first_name', 'LIKE', '%' . $request->input('first_name') . '%');
            }

            if ($request->filled('last_name')) {
                $user_info->where('last_name', 'LIKE', '%' . $request->input('last_name') . '%');
            }
            
            if ($request->filled('phone')) {
                $user_info->where('phone', '=', $request->input('phone'));
            }

            if ($request->filled('role')) {
                $user_info->where('role', '=', $request->input('role'));
            }


            // Filter only active events (assuming active status is 1)
            $active_user = 1; 
            $user_info->where('status', $active_user);

    
            // Fetch results and order by created_at (latest first)
            $user_info = $user_info->orderBy('created_at', 'desc')->get();

    
            if ($user_info->isNotEmpty()) {
                return response()->json(['success' => true, 'message' => 'Data found', 'data' => $user_info]);
            }
            
            return response()->json(['success' => true, 'message' => 'No data found', 'data' => []]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

}
