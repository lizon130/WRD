<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Auth;
use Helper;
use Illuminate\Support\Facades\Session;
use App\Models\CampusGallary;


class CampusGallaryController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {

    //         $this->user = Auth::user();

    //         if (!$this->user || Helper::hasRight('campusgallary.view') == false) {
    //             session()->flash('error', 'You can not access! Login first.');
    //             return redirect()->route('admin');
    //         }
    //         return $next($request);
    //     });
    // }

    public function index(){
        $campus_gallary = CampusGallary::all();
        return view('backend.pages.campus_gallery.index', compact('campus_gallary'));
    }

    public function getList(Request $request)
    {
        $data = CampusGallary::query();

        // Filter by Title
        if (!empty($request->title)) {
            $data->where('title', 'like', "%" . $request->title . "%");
        }

        // Filter by Description
        if (!empty($request->description)) {
            $data->where('description', 'like', "%" . $request->description . "%");
        }

        // Filter by Short Description
        if (!empty($request->short_description)) {
            $data->where('short_description', 'like', "%" . $request->short_description . "%");
        }

        return Datatables::of($data)

            // Handle Image Column
            ->editColumn('image', function ($row) {
                return ($row->image) 
                    ? '<a href="'.asset($row->image).'" target="_blank"><img class="profile-img" src="'.asset($row->image).'" alt="profile image"></a>' 
                    : '<img class="profile-img" src="'.asset('assets/img/no-img.jpg').'" alt="profile image">';
            })

            // Handle Status Column
            ->editColumn('status', function ($row) {
                return $row->status == 1
                    ? '<span class="badge bg-primary w-75">Active</span>'
                    : '<span class="badge bg-danger w-75">Inactive</span>';
            })

            // Add Action Buttons
            ->addColumn('action', function ($row) {
                return '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                        <a class="delete_btn btn btn-sm btn-danger mx-1" data-id="'.$row->id.'" href="">
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </a>';
            })

            ->rawColumns(['image', 'status', 'action'])
            ->make(true);
    }


    public function store(Request $request)
    {
        // Validate the request
        $validator = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'short_description' => 'nullable|string', // Fixed missing validation
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);



        $campus_gallary = new CampusGallary();
        $campus_gallary->title = $request->title;
        $campus_gallary->description = $request->description;
        $campus_gallary->short_description = $request->short_description; // Fixed variable
        $campus_gallary->status = $request->status ? 1 : 0; 
        $campus_gallary->static_status = $request->static_status ? 1 : 0;

        // Define upload directory
        $uploadPath = public_path('uploads/campusgallary-images');

        // Check if directory exists, if not, create it with proper permissions
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Handle Image Upload
        if ($request->hasFile('gallery_image')) {
            $image = $request->file('gallery_image');
            $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadPath, $filename);
            $campus_gallary->image = 'uploads/campusgallary-images/' . $filename;
        }
        if ($request->hasFile('thumbnail')) {
            $image_two = $request->file('thumbnail');
            $filename_two = time() . uniqid() . '.' . $image_two->getClientOriginalExtension();
            $image_two->move($uploadPath, $filename_two);
            $campus_gallary->image_two = 'uploads/campusgallary-images/' . $filename_two;
        }

        if ($campus_gallary->save()) {

            // language 
            Helper::insertLanguage(CampusGallary::class, $campus_gallary->id, 'en', 'title', $campus_gallary->title);
            Helper::insertLanguage(CampusGallary::class, $campus_gallary->id, 'en', 'short_description', $campus_gallary->short_description);
            Helper::insertLanguage(CampusGallary::class, $campus_gallary->id, 'en', 'description', $campus_gallary->description);
            
            return response()->json([
                'type' => 'success',
                'message' => 'Gallery images created successfully.',
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Something went wrong.',
        ]);
    }


    public function edit($id){
        $campus_gallary = CampusGallary::find($id);
        return view('backend.pages.campus_gallery.edit', compact('campus_gallary'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $validator = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'short_description' => 'nullable|string', // Fixed missing validation
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the record
        $campus_gallary = CampusGallary::find($id);

        if (!$campus_gallary) {
            return response()->json([
                'type' => 'error',
                'message' => 'Record not found.',
            ]);
        }

        // Assign values
        $campus_gallary->title = $request->title;
        $campus_gallary->description = $request->description;
        $campus_gallary->short_description = $request->short_description;
        $campus_gallary->status = $request->status ? 1 : 0; 
        $campus_gallary->static_status = $request->static_status ? 1 : 0; 

        // Multilingual handling
        if (Session::get('admin_language') == 'en') {
            $campus_gallary->title = $request->title;
            $campus_gallary->description = $request->description;
            $campus_gallary->short_description = $request->short_description;
        }

        // Define upload directory
        $uploadPath = public_path('uploads/campusgallary-images');

        // Ensure the directory exists
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Handle Image Upload (Delete old file if exists)
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if (!empty($campus_gallary->image) && file_exists(public_path($campus_gallary->image))) {
                unlink(public_path($campus_gallary->image));
            }

            // Upload new image
            $image = $request->file('image');
            $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadPath, $filename);
            $campus_gallary->image = 'uploads/campusgallary-images/' . $filename;
        }

        if ($campus_gallary->save()) {
            
            // language 
            Helper::insertLanguage(CampusGallary::class, $campus_gallary->id, Session::get('admin_language') ?? 'en', 'title', $request->title);
            Helper::insertLanguage(CampusGallary::class, $campus_gallary->id, Session::get('admin_language') ?? 'en', 'short_description', $request->short_description);
            Helper::insertLanguage(CampusGallary::class, $campus_gallary->id, Session::get('admin_language') ?? 'en', 'description', $request->descriptions);


            return response()->json([
                'type' => 'success',
                'message' => 'Gallery updated successfully.',
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Something went wrong.',
        ]);
    }


    public function delete($id){
        $campus_gallary = CampusGallary::find($id);
        if($campus_gallary){
            if ($campus_gallary->image != null && file_exists(public_path('uploads/campusgallary-images/'.$campus_gallary->image))) {
                unlink(public_path('uploads/campusgallary-images/'.$campus_gallary->image));
            }

            $campus_gallary->delete();
            return json_encode(['success' => 'Gallary info deleted successfully.']);
        }else{
            return json_encode(['error' => 'Gallary ingo not found.']);
        }
    }
}
