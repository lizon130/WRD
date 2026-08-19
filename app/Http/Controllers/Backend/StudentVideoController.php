<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Models\StudentVideoModel;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;


class StudentVideoController extends Controller
{
    public function index(){

        return view('backend.pages.student_video.index');
    }


    public function getList(Request $request)
    {
        $data = StudentVideoModel::query(); // Fetch Student Video Data

        if ($request->title) {
            $data->where('title', 'like', "%" . $request->title . "%");
        }

        return DataTables::of($data)
            ->addColumn('image', function ($row) {
                $directory = 'uploads/student-images/';
                $imagePath = $row->image ? asset($directory . $row->image) : asset('uploads/student-images/default.jpg');
                return '<img class="profile-img" src="' . $imagePath . '" alt="Thumbnail" width="50" height="50">';
            })
            ->addColumn('title', fn($row) => $row->title)
            ->addColumn('action', function ($row) {
                return '<a href="#" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a>
                        <a href="#" class="delete_btn btn btn-sm btn-danger" data-id="' . $row->id . '"><i class="fa fa-trash"></i></a>';
            })
            ->rawColumns(['image', 'action'])
            ->make(true);
    }


    public function store(Request $request){
        $validator = $request->validate([
            'title' => 'required|string|max:500',
            'video_link' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'Title is required.',
            'video_link.required' => 'Video link is required.',
            'image.image' => 'Please select a valid image file.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2MB.',
        ]);


        $studentVideo = new StudentVideoModel();
        $studentVideo->title = $request->title;
        $studentVideo->video_link = $request->video_link;

        $directory = "uploads/student-images/";
        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().uniqid().$image->getClientOriginalName();
            $image->move(public_path($directory), $filename);
            $studentVideo->image = $directory . $filename;
        }
        $studentVideo->status = $request->status ? 1: 0;

        if ($studentVideo->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Student Video saved successfully.',
            ]);
        } else{
            return response()->json([
                'type' => 'error',
                'message' => 'Student video store failed.',
            ]);
        }
    }
    public function edit($id){
        $student = StudentVideoModel::find($id);
        return view('backend.pages.student_video.edit', compact('student'));
    }

    public function update(Request $request, $id) {
        $validator = $request->validate([
            'title' => 'required|string|max:500',
            'video_link' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'Title is required.',
            'video_link.required' => 'Video link is required.',
            'image.image' => 'Please select a valid image file.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image may not be greater than 2MB.',
        ]);
        

        $studentVideo = StudentVideoModel::findOrFail($id);
        $studentVideo->title = $request->title ?? null;
        $studentVideo->video_link = $request->video_link ?? null;

        // Handle image update if a new image is uploaded
        $directory = 'uploads/student-images/';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time().uniqid().$image->getClientOriginalName();
            $image->move(public_path($directory), $filename);

            // Delete the old image if exists
            if ($studentVideo->image && file_exists(public_path($directory . $studentVideo->image))) {
                unlink(public_path($directory .$studentVideo->image));
            }

            $studentVideo->image = $directory . $filename;
        }

        // Update status (checkbox logic: checked = 1, unchecked = 0)
        $studentVideo->status = $request->has('status') ? 1 : 0;

        if ($studentVideo->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Student Video updated successfully.',
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Failed to update Student Video.',
        ], 500);
    }


    public function delete($id){
        $class = StudentVideoModel::find($id);
        if($class){
            $class->delete();
            return json_encode(['success' => 'Video info deleted successfully.']);
        }else{
            return json_encode(['error' => 'Not found.']);
        }
    }
}
