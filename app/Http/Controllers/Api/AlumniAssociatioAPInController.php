<?php

namespace App\Http\Controllers\Api;

use App\Models\Alumni;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlumniResource;
use Hamcrest\Core\AllOf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;



class AlumniAssociatioAPInController extends Controller
{
    public function list()
    {
        try {
            $alumni_info = Alumni::all();
            if($alumni_info->isNotEmpty()){
                return response()->json(['success' => true, 'data' => $alumni_info], 200);
            } else {
                return response()->json(['success' => true, 'data' => []], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function regAlumni()
    {
        try {
            $alumni_info = Alumni::all();
            
            if($alumni_info->isNotEmpty()){
                return response()->json(['success' => true, 'data' => $alumni_info], 200);
            } else {
                return response()->json(['success' => true, 'data' => []], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:alumnis,email,',
                'phone_no' => 'required|string|regex:/^[0-9+().\s-]+$/|max:20',
                'batch' => 'required|string|max:50',
                'passing_year' => 'required|string|max:10',
                'degree' => 'nullable|string|max:255',
                'current_profession' => 'nullable|string|max:255',
                'job_title' => 'nullable|string|max:255',
                'company_name' => 'nullable|string|max:255',
                'alumni_type' => 'required|string|max:255',
                'image' => 'nullable',
                'transaction_id' => 'nullable',
                'payment_method' => 'nullable',
                'linkedin_url' => 'nullable|string|url|max:255',
            ], [
                'email.unique' => 'Email already exists, please provide a new one.',
                'phone_no.regex' => 'Phone number can only contain numbers, spaces, +, (), and dashes.',
                'linkedin_url.url' => 'Please provide a valid LinkedIn profile URL.',
            ]);

            // fullName, graduationYear, degree, jobTitle, company, email, phone, linkedin, mentoringInterest, expertise, receiveUpdates,


            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }


            $alumni_info = new Alumni();
            $alumni_info->fill($request->except('image'));
            $alumni_info->name = $request->name ? $request->name : null;
            $alumni_info->email = $request->email ? $request->email : null;
            $alumni_info->phone_no = $request->phone_no ? $request->phone_no : null;
            $alumni_info->passing_year = $request->passing_year ? $request->passing_year : null;
            $alumni_info->degree = $request->degree ? $request->degree : null;
            $alumni_info->current_profession = $request->current_profession ? $request->current_profession : null;
            $alumni_info->job_title = $request->job_title ? $request->job_title : null;
            $alumni_info->company_name = $request->company_name ? $request->company_name : null;
            $alumni_info->alumni_type = $request->alumni_type ? $request->alumni_type : null;
            $alumni_info->linkedin_url = $request->linkedin_url ? $request->linkedin_url : null;
            $alumni_info->transaction_id = $request->transaction_id ? $request->transaction_id : null;
            $alumni_info->payment_method = $request->payment_method ? $request->payment_method : null;


            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $uploadPath = public_path('uploads/alumni-images');

                // Create directory if it doesn't exist
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                // Move uploaded file
                $image->move($uploadPath, $filename);
                $alumni_info->image = 'uploads/alumni-images/' . $filename;
            }

            $status = $alumni_info->save();

            if($status) {
                return response()->json(['success' => true, 'message' => 'Alumni info created successfully.', 'data' => $alumni_info], 201);
            } else  {
                return response()->json(['success' => false, 'message' => 'Failed to create alumni info.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function details($id)
    {
        try {
            $alumni_info = Alumni::find($id);

            if (!$alumni_info) {
                return response()->json(['success' => false, 'message' => 'Alumni not found.'], 404);
            }

            return response()->json(['success' => true, 'data' => $alumni_info], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $alumni_info = Alumni::find($id);

            if (!$alumni_info) {
                return response()->json(['success' => false, 'message' => 'Alumni not found.'], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:alumnis,email,',
                'phone_no' => 'required|string|regex:/^[0-9+().\s-]+$/|max:20',
                'batch' => 'required|string|max:50',
                'passing_year' => 'required|string|max:10',
                'degree' => 'nullable|string|max:255',
                'current_profession' => 'nullable|string|max:255',
                'job_title' => 'nullable|string|max:255',
                'company_name' => 'nullable|string|max:255',
                'image' => 'nullable',
                'linkedin_url' => 'nullable|string|url|max:255',
            ], [
                'email.unique' => 'Email already exists, please provide a new one.',
                'phone_no.regex' => 'Phone number can only contain numbers, spaces, +, (), and dashes.',
                'linkedin_url.url' => 'Please provide a valid LinkedIn profile URL.',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Update alumni details except the image
            $alumni_info->fill($request->except('image'));

            if ($request->hasFile('image')) {
                $uploadPath = public_path('uploads/alumni-images');

                // Create directory if it doesn't exist
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                // Delete old image if it exists
                if ($alumni_info->image && File::exists(public_path($alumni_info->image))) {
                    File::delete(public_path($alumni_info->image));
                }

                // Store new image
                $image = $request->file('image');
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($uploadPath, $filename);
                $alumni_info->image = 'uploads/alumni-images/' . $filename;
            }

            $alumni_info->save();

            return response()->json([
                'success' => true,
                'message' => 'Alumni updated successfully.',
                'data' => $alumni_info
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }


    public function delete($id)
    {
        try {
            $alumni_info = Alumni::find($id);

            if (!$alumni_info) {
                return response()->json(['success' => false, 'message' => 'Alumni info not found.'], 404);
            }

            if ($alumni_info->image && file_exists(public_path($alumni_info->image))) {
                unlink(public_path($alumni_info->image));
            }

            $alumni_info->delete();

            return response()->json(['success' => true, 'message' => 'Alumni deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function searchFilter(Request $request){
        try {

            // Validate input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }


            // Start query
            $alumni_info = Alumni::query();

            // Apply filters dynamically
            if ($request->filled('name')) {
                $alumni_info->where('name', 'LIKE', '%' . $request->input('name') . '%');
                // $alumni_info->where('name', '=',  $request->input('name'));
            }


            // Fetch results and order by created_at (latest first)
            $alumni_info = $alumni_info->orderBy('created_at', 'desc')->get();


            if ($alumni_info->isNotEmpty()) {
                return response()->json(['success' => true, 'message' => 'Data found', 'data' => $alumni_info]);
            }

            return response()->json(['success' => true, 'message' => 'No data found', 'data' => []]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function alumniResourse(Request $request){
        $resourse = AlumniResource::where('status', 1)->get();
        return response()->json([
            'success' => true,
            'message' => 'Data found',
            'data' => $resourse
        ]);
    }
    public function alumniResourseDetails($id){
        $resourse = AlumniResource::find($id);
        return response()->json([
            'success' => true,
            'message' => 'Data found',
            'data' => $resourse
        ]);
    }
}
