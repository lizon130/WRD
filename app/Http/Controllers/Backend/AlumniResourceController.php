<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AlumniResource;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class AlumniResourceController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('resource.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $resources = AlumniResource::all();
        return view('backend.pages.alumni.alumni.index', compact('resources'));
    }

    public function getList(Request $request)
    {

        $data = AlumniResource::query();
        return DataTables::of($data)

            ->editColumn('image', function ($row) {
                return ($row->image) ? '<a href="' . asset('uploads/resource-images/' . $row->image) . '" target="_blank"><img class="profile-img b-1" src="' . asset('uploads/resource-images/' . $row->image) . '" alt="profile image"></a>' : '<img class="profile-img b-1" src="' . asset('assets/img/no-img.jpg') . '" alt="profile image">';
            })

            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge bg-primary w-80">Visible</span>';
                } else {
                    return '<span class="badge bg-danger w-80">Hidden</span>';
                }
            })

            ->addColumn('action', function ($row) {
                $btn = '';
                if (Helper::hasRight('resource.edit')) {
                    $btn = $btn . '<a href="" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary me-2"><i class="fa-solid fa-pencil"></i></a>';
                }
                $btn = $btn . '<a href="' . route('admin.alumni.user', ['alumni_type' => $row->title]) . '" class="btn btn-sm btn-success"><i class="fa-solid fa-magnifying-glass"></i></a>';


                if (Helper::hasRight('resource.delete')) {
                    $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="' . $row->id . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['image', 'status', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'title' => 'required',
            'image' => 'required|image:png,jpg,jpeg,gif,webp,',
        ]);

        $resource = new AlumniResource();
        $resource->type = $request->type;
        $resource->title = $request->title;
        $resource->details = $request->details ?? '';
        $resource->short_description = $request->short_description ?? '';
        $resource->status = ($request->status) ? 1 : 0;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . uniqid() . $image->getClientOriginalName();
            $image->move(public_path('uploads/resource-images'), $filename);
            $resource->image = $filename;
        }
        if ($resource->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Resource created successfully.',
            ]);
        }
    }

    public function edit($id)
    {
        $resource = AlumniResource::find($id);
        return view('backend.pages.alumni.alumni.edit', compact('resource'));
    }

    public function update(Request $request, $id)
    {
        $validator = $request->validate([
            'title' => 'required',
            'image' => 'nullable|image:png,jpg,jpeg,gif,webp,',
        ]);

        $resource = AlumniResource::find($id);
        $resource->type = $request->type;
        $resource->title = $request->title;
        $resource->details = $request->details ?? '';
        $resource->short_description = $request->short_description ?? '';
        $resource->status = ($request->status) ? 1 : 0;
        if ($request->hasFile('image')) {
            if (file_exists(public_path('uploads/resource-images/' . $resource->image))) {
                unlink(public_path('uploads/resource-images/' . $resource->image));
            }
            $image = $request->file('image');
            $filename = time() . uniqid() . $image->getClientOriginalName();
            $image->move(public_path('uploads/resource-images'), $filename);
            $resource->image = $filename;
        }
        if ($resource->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Resource updated successfully.',
            ]);
        } else {
            return response()->json([
                'type' => 'success',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function delete($id)
    {
        $resource = AlumniResource::find($id);
        if ($resource) {
            if ($resource->media != null && file_exists(public_path('uploads/resource-images/' . $resource->media))) {
                unlink(public_path('uploads/resource-images/' . $resource->media));
            }
            $resource->delete();
            return json_encode(['success' => 'Resource deleted successfully.']);
        } else {
            return json_encode(['error' => 'Resource not found.']);
        }
    }
}
