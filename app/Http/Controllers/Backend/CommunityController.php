<?php

namespace App\Http\Controllers\Backend;

use App\Models\Alumni;
use App\Models\Community;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class CommunityController extends Controller
{
    public function index()
    {

        $alumni = Alumni::select('id', 'name')->get();

        return view('backend.pages.community.index', compact('alumni'));
    }

    public function getList(Request $request)
    {

        // $data = Alumni::query();
        $data = Community::query()->orderBy('created_at', 'desc');


        if ($request->alumni_id) {
            $data->where(function ($query) use ($request) {
                $query->where('alumni_id', 'like', "%" . $request->alumni_id . "%");
            });
        }

        if ($request->phone_no) {
            $data->where(function ($query) use ($request) {
                $query->where('phone_no', 'like', "%" . $request->phone . "%");
            });
        }


        return Datatables::of($data)

            ->editColumn('first_name', function ($row) {
                return $row->first_name . ' ' . $row->last_name;
            })

            ->editColumn('role', function ($row) {
                return optional($row->roles)->name;
            })

            // ->editColumn('status', function ($row) {
            //     if ($row->status == 1) {
            //         return '<span class="badge bg-primary w-80">Approve</span>';
            //     }else{
            //         return '<span class="badge bg-danger w-80">Pending</span>';
            //     }
            // })

            ->addColumn('action', function ($row) {
                $btn = '';

                $btn = $btn . '<a href="" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary me-2"><i class="fa-solid fa-pencil"></i></a>';
                // if($row->status == 0){
                //     $btn = $btn . '<a class="approveBtn btn btn-sm btn-success m-1" data-id="'.$row->id.'" href=""><i class="fa-solid fa-thumbs-up"></i></a>';
                // }
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger " data-id="' . $row->id . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';

                return $btn;
            })
            ->rawColumns(['profile_image', 'first_name', 'role', 'status', 'action'])->make(true);
    }


    public function store(Request $request)
    {
        try {
            // Validate request data
            $validatedData = $request->validate([
                'alumni_id'     => 'required',
                'member_id'     => 'required',
                'type'          => 'required',
                'from_year'     => 'required',
                'to_year'       => 'required',
            ]);

            // Create new Community instance
            $community = new Community();
            $community->alumni_id = $request->alumni_id;
            $community->member_id = $request->member_id;
            $community->description = $request->description;
            $community->from_year = $request->from_year;
            $community->to_year = $request->to_year;
            $community->responsibility = $request->responsibility;
            $community->type = $request->type;

            // Save to database
            if ($community->save()) {
                return response()->json([
                    'type' => 'success',
                    'message' => 'Community information saved successfully.',
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Failed to save community information.',
                ], 500);
            }
        } catch (\Exception $e) {
            // Catch and return error message
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $community = Community::find($id);
        $alumni = Alumni::select('id', 'name')->orderBy('name')->get();
        return view('backend.pages.community.edit', compact('community', 'alumni'));
    }

    public function update(Request $request, $id)
    {
        // Validate the form inputs
       $validatedData = $request->validate([
                'alumni_id'     => 'required',
                'member_id'     => 'required',
                'type'          => 'required',
                'from_year'     => 'required',
                'to_year'       => 'required',
            ]);
        // Find the alumni record by ID
        $community = Community::findOrFail($id);

        // Update alumni details
        $community->alumni_id = $request->alumni_id;
        $community->member_id = $request->member_id;
        $community->description = $request->description;
        $community->from_year = $request->from_year;
        $community->to_year = $request->to_year;
        $community->responsibility = $request->responsibility;
        $community->type = $request->type;

        // Save the updates
        if ($community->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Community information updated successfully.',
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Failed to update Community information.',
            ]);
        }
    }

    public function delete($id){
        $community = Community::find($id);
        if($community){
            $community->delete();
            return json_encode(['success' => 'community info deleted successfully.']);
        }else{
            return json_encode(['error' => 'Not found.']);
        }
    }
}
