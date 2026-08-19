<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\DataTables\Utilities\Helper;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    
    public function index()
    {
        $department_info = Department::all();
        return view('backend.pages.department.index', compact('department_info'));
    }



    public function getList(Request $request){
        
        $data = Department::query();

        if (!empty($request->dept_name)) {
            $data->where(function($query) use ($request){
                $query->where('dept_name','=', $request->dept_name);
            });
        }

        if (!empty($request->description)) {
            $data->where(function($query) use ($request){
                $query->where('description','like', "%" .$request->description ."%" );
            });
        }

        if ($request->status) {
            $data->where(function($query) use ($request){
                if ($request->status == 1) {
                    $status = 1;
                }else{
                    $status = 0;
                }
                $query->where('status', $status);
            });
        }


        return Datatables::of($data)

        ->editColumn('image_url', function ($row) {
            return ($row->image_url) 
                ? '<a href="'.asset($row->image_url).'" target="_blank"><img class="profile-img" src="'.asset($row->image_url).'" alt="profile image"></a>' 
                : '<img class="profile-img" src="'.asset('assets/img/no-img.jpg').'" alt="profile image">';
        })


        // ->editColumn('id', function ($row) {
        //     return $row->company->name ?? 'N/A';
        // })

        

        ->editColumn('status', function ($row) {
            if ($row->status == 1) {
                return '<span class="badge bg-primary w-75">Active</span>';
            }else{
                return '<span class="badge bg-danger w-75">Inactive</span>';
            }
        })

        ->addColumn('action', function ($row) {
            $btn = '';
          
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
        
            return $btn;
        })
        ->rawColumns([ 'dept_name', 'description', 'image_url', 'status',  'action'])->make(true);
    }

    

    
    public function store(Request $request){
        $validator = $request->validate([
            'dept_name' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Ensure it's an image
            'dept_gallary_images' => 'nullable|array', // Allow multiple files
            'dept_gallary_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each image
        ], [
            'dept_name.required' => 'Department name is required.',
            'description.required' => 'Description is required.',
            'image_url.required' => 'Image is required.',
            'dept_gallary_images.*.image' => 'Department gallary images must be an image.',
            'dept_gallary_images.*.mimes' => 'Department gallary images must be a valid image format (jpeg, png, jpg, gif, svg).',
            'dept_gallary_images.*.max' => 'Department gallary images may not be greater than 2MB.',
        ]);
        

        $department_info = new Department();
        $department_info->dept_name = ucfirst($request->dept_name);
        $department_info->description = ucfirst($request->description);
        $department_info->status  = ($request->status) ? 1 : 0;



        $directory = "uploads/department-images/";

        // Check if the directory exists, if not, create it
        if (!is_dir(public_path($directory))) {
            mkdir(public_path($directory), 0755, true);
        }

        if ($request->hasFile('image_url')) {
            $image = $request->file('image_url');
            $filename = time() . uniqid() . $image->getClientOriginalName();
            $image->move(public_path($directory), $filename);
            $department_info->image_url = $directory . $filename; // Store the full relative path
        }

        // Handling multiple dept_gallary_images
        if ($request->hasFile('dept_gallary_images')) {
            $deptGallaryImages = $request->file('dept_gallary_images');
            $imageNames = [];

            // Ensure deptGallaryImages is always an array
            $deptGallaryImages = is_array($deptGallaryImages) ? $deptGallaryImages : [$deptGallaryImages];

            foreach ($deptGallaryImages as $image) {
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($directory), $filename);
                $imageNames[] = $directory . $filename; // Store path in an array
            }

            // Merge with existing images if needed
            if (!empty($department_info->dept_gallary_images)) {
                $existingImages = json_decode($department_info->dept_gallary_images, true) ?? [];
                $imageNames = array_merge($existingImages, $imageNames);
            }

            // Always store as JSON array in longtext column
            $department_info->dept_gallary_images = json_encode($imageNames);
        }


        if ($department_info->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Department info created successfully.',
            ]);
        }
        return response()->json([
            'type' => 'error',
            'message' => 'Department info creation failed.',
        ]);
    }


   
    public function edit($id)
    {
        // Fetch the specific event by its ID
        $department_info = Department::findOrFail($id);

        // Pass the event data to the view
        return view('backend.pages.department.edit', compact('department_info'));
    }


    public function update(Request $request, $id)
    {
        // Validate the input data
        $validator = $request->validate([
            'dept_name' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Ensure it's an image
            'dept_gallary_images' => 'nullable|array', // Allow multiple files
            'dept_gallary_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each image
        ], [
            'dept_name.required' => 'Department name is required.',
            'description.required' => 'Description is required.',
            'image_url.required' => 'Image is required.',
            'dept_gallary_images.*.image' => 'Department gallary images must be an image.',
            'dept_gallary_images.*.mimes' => 'Department gallary images must be a valid image format (jpeg, png, jpg, gif, svg).',
            'dept_gallary_images.*.max' => 'Department gallary images may not be greater than 2MB.',
        ]);
        

        // Find the existing event by ID
        $department_info = Department::findOrFail($id);


        // Update the depa$department_info details
        $department_info->dept_name = ucfirst($request->dept_name);
        $department_info->description = ucfirst($request->description);    
        $department_info->status = ($request->status) ? 1 : 0;


        $directory = "uploads/department-images/";

        // Handling the main event image
        if ($request->hasFile('image_url')) {
            // Delete the old image if it exists
            if ($department_info->image_url && file_exists(public_path($department_info->image_url))) {
                unlink(public_path($department_info->image_url));
            }

            // Store the new image
            $image = $request->file('image_url');
            $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($directory), $filename);
            $department_info->image_url = $directory . $filename; // Store the full relative path
        }

        // Handling multiple dept_gallary_images
        if ($request->hasFile('dept_gallary_images')) {
            $deptGallaryImages = $request->file('dept_gallary_images');
            $imageNames = [];

            // Ensure deptGallaryImages is always an array
            $deptGallaryImages = is_array($deptGallaryImages) ? $deptGallaryImages : [$deptGallaryImages];

            foreach ($deptGallaryImages as $image) {
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($directory), $filename);
                $imageNames[] = $directory . $filename; // Store path in an array
            }

            // Merge with existing images if needed
            if (!empty($department_info->dept_gallary_images)) {
                $existingImages = json_decode($department_info->dept_gallary_images, true) ?? [];
                $imageNames = array_merge($existingImages, $imageNames);
            }

            // Always store as JSON array in longtext column
            $department_info->dept_gallary_images = json_encode($imageNames);
        }



        // Save the updated event data
        if ($department_info->save()) {
            
            return response()->json([
                'type' => 'success',
                'message' => 'Department updated successfully.',
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Failed to update the department info.',
            ]);
        }
    }


    
    public function delete($id)
    {
        $department = Department::find($id);
        $directory = "uploads/department-images/";

        if ($department) {
            // Delete single image
            if (!empty($department->image) && file_exists(public_path($department->image))) {
                unlink(public_path($directory . $department->image));
            }

            // Delete multiple images from dept_gallary_images
            if (!empty($department->dept_gallary_images)) {
                $galleryImages = json_decode($department->dept_gallary_images, true);
                if (is_array($galleryImages)) {
                    foreach ($galleryImages as $image) {
                        if (file_exists(public_path($directory. $image))) {
                            unlink(public_path($directory . $image));
                        }
                    }
                }
            }

            // Delete the department record
            $status = $department->delete();
            if($status){
                return response()->json(['success' => 'Department info deleted successfully.']);
            } else {
                return response()->json(['error' => 'Failed to delete the department info.']);
            }
            
        } else {
            return response()->json(['error' => 'Department info not found.']);
        }
    }

}
