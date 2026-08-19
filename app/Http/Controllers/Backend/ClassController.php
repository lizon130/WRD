<?php

namespace App\Http\Controllers\Backend;

use App\Models\Teacher;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class ClassController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->get();
        return view('backend.pages.class.index', compact('teachers'));
    }
    public function getList(Request $request)
    {
        $data = ClassModel::query(); // Use ClassModel directly

        if ($request->name) {
            $data->where('name', 'like', "%" . $request->name . "%");
        }

        if ($request->duration) {
            $data->where('duration', 'like', "%" . $request->duration . "%");
        }

        if ($request->fees) {
            $data->where('fees', 'like', "%" . $request->fees . "%");
        }

        return DataTables::of($data)
    ->addColumn('image', function ($row) {
        $imagePath = !empty($row->image) ? asset($row->image) : asset('uploads/class-images/default.jpg');
        return '<img class="profile-img" src="' . $imagePath . '" alt="Class Image" width="50" height="50">';
    })
    ->addColumn('name', fn($row) => $row->name)
    ->addColumn('duration', fn($row) => $row->duration)
    ->addColumn('fees', fn($row) => $row->fees)
    ->addColumn('status', function ($row) {
        return $row->status == 1
            ? '<button class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="0">Approved</button>'
            : '<button class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="1">Pending</button>';
    })
    ->addColumn('action', function ($row) {
        return '<a href="#" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a>
                <a href="#" class="delete_btn btn btn-sm btn-danger" data-id="' . $row->id . '"><i class="fa fa-trash"></i></a>';
    })
    ->rawColumns(['image', 'status', 'action'])
    ->make(true);

    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'short_description' => 'required',
        'long_description' => 'required',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'duration' => 'required',
        'fees' => 'required|numeric',
        'requiredments' => 'nullable|array',
        'subject' => 'nullable|array',
        'teacher' => 'nullable|array',
    ]);

    $class = new ClassModel();
    $class->name = $request->name;
    $class->short_description = $request->short_description;
    $class->long_description = $request->long_description;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $uploadPath = public_path('uploads/class-images');

        // Ensure the directory exists
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move($uploadPath, $filename);
        $class->image = 'uploads/class-images/' . $filename;
    }

    $class->duration = $request->duration;
    $class->fees = $request->fees;
    $class->requiredments = json_encode($request->requiredments);
    $class->subject = json_encode($request->subject);
    $class->teacher = json_encode($request->teacher);
    $class->status = 1;

    if ($class->save()) {
        return response()->json([
            'status' => 'success',
            'message' => 'Class created successfully!',
        ]);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create class.',
        ]);
    }
}
public function edit($id){
    $teachers = Teacher::with('user')->get();

    $class = ClassModel::find($id);
    return view('backend.pages.class.edit', compact('class','teachers'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'short_description' => 'nullable',
        'long_description' => 'nullable',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'duration' => 'nullable',
        'fees' => 'nullable|numeric',
        'requiredments' => 'nullable',
        'subject' => 'nullable',
        'teacher' => 'nullable',
    ]);

    $class = ClassModel::find($id);

    if (!$class) {
        return response()->json([
            'status' => 'error',
            'message' => 'Class not found!',
        ]);
    }

    $class->name = $request->name;
    $class->short_description = $request->short_description;
    $class->long_description = $request->long_description;


    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $uploadPath = public_path('uploads/class-images');

        // Ensure the directory exists
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move($uploadPath, $filename);
        $class->image = 'uploads/class-images/' . $filename;
    }

    $class->duration = $request->duration;
    $class->fees = $request->fees;
    $class->requiredments = json_encode($request->requiredments);
    $class->subject = json_encode($request->subject);
    $class->teacher = json_encode($request->teacher);
    $class->status = $request->status ?? 1; // Default to active if not provided

    if ($class->save()) {
        return response()->json([
            'status' => 'success',
            'message' => 'Class updated successfully!',
        ]);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update class.',
        ]);
    }
}

public function delete($id){
    $class = ClassModel::find($id);
    if($class){
        $class->delete();
        return json_encode(['success' => 'Class info deleted successfully.']);
    }else{
        return json_encode(['error' => 'Not found.']);
    }
}
public function updateStatus(Request $request)
{
    $class = ClassModel::find($request->id);

    if (!$class) {
        return response()->json(['success' => false, 'message' => 'class not found!'], 404);
    }

    // Update status in user table
    $class->status = $request->status;
    $class->save();

    return response()->json([
        'success' => true,
        'message' => 'Status updated successfully!',
        'new_status' => $class->status
    ]);
}
}
