<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\AlumniResource;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AlumniController extends Controller
{
    public function index()
    {
        $alumniTitle = AlumniResource::all();
        return view('backend.pages.alumni.index', compact('alumniTitle'));
    }

    public function getList(Request $request)
    {
        // $data = Alumni::query();
        $data = Alumni::query()->orderBy('created_at', 'desc');

        if ($request->filled('alumni_type')) {
            $data->where('alumni_type', $request->alumni_type);
        }


        if ($request->name) {
            $data->where(function ($query) use ($request) {
                $query->where('name', 'like', "%" . $request->name . "%");
            });
        }

        if ($request->email) {
            $data->where(function ($query) use ($request) {
                $query->where('email', 'like', "%" . $request->email . "%");
            });
        }

        if ($request->phone_no) {
            $data->where(function ($query) use ($request) {
                $query->where('phone_no', 'like', "%" . $request->phone_no . "%");
            });
        }


        return Datatables::of($data)

            // ->editColumn('profile_image', function ($row) {
            //     return ($row->profile_image) ? '<img class="profile-img" src="'.asset('uploads/user-images/'.$row->profile_image).'" alt="profile image">' : '<img class="profile-img" src="'.asset('assets/img/no-img.jpg').'" alt="profile image">';
            // })

            ->editColumn('first_name', function ($row) {
                return $row->first_name . ' ' . $row->last_name;
            })

            ->editColumn('role', function ($row) {
                return optional($row->roles)->name;
            })

            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge bg-primary w-80">Approve</span>';
                } else {
                    return '<span class="badge bg-danger w-80">Pending</span>';
                }
            })

            ->addColumn('action', function ($row) {
                $btn = '';

                $btn = $btn . '<a href="" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
                if ($row->status == 0) {
                    $btn = $btn . '<a class="approveBtn btn btn-sm btn-success m-1" data-id="' . $row->id . '" href=""><i class="fa-solid fa-thumbs-up"></i></a>';
                }
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger " data-id="' . $row->id . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';

                return $btn;
            })
            ->rawColumns(['profile_image', 'first_name', 'role', 'status', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone_no' => 'required',
            'batch' => 'required',
        ]);

        $alumni = new Alumni();
        $alumni->name = $request->name;
        $alumni->email = $request->email;
        $alumni->phone_no = $request->phone_no;
        $alumni->batch = $request->batch;
        $alumni->passing_year = $request->passing_year;
        $alumni->current_profession = $request->current_profession;
        $alumni->company_name = $request->company_name;

        if ($alumni->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Alumni information saved successfully.',
            ]);
        }
    }

    public function edit($id)
    {
        $alumnis = Alumni::find($id);
        return view('backend.pages.alumni.edit', compact('alumnis'));
    }

    public function update(Request $request, $id)
    {
        // Validate the form inputs
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone_no' => 'required',
            'batch' => 'required',
        ]);

        // Find the alumni record by ID
        $alumni = Alumni::findOrFail($id);

        // Update alumni details
        $alumni->name = $request->name;
        $alumni->email = $request->email;
        $alumni->phone_no = $request->phone_no;
        $alumni->batch = $request->batch;
        $alumni->passing_year = $request->passing_year;
        $alumni->current_profession = $request->current_profession;
        $alumni->company_name = $request->company_name;

        // Save the updates
        if ($alumni->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Alumni information updated successfully.',
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Failed to update alumni information.',
            ]);
        }
    }

    public function delete($id)
    {
        $alumnis = Alumni::find($id);
        if ($alumnis) {
            $alumnis->delete();
            return json_encode(['success' => 'Alumni info deleted successfully.']);
        } else {
            return json_encode(['error' => 'Not found.']);
        }
    }

    public function approve($id)
    {
        $alumnis = Alumni::find($id);
        if ($alumnis) {
            $alumnis->status = 1;
            $alumnis->save();
            return json_encode(['success' => 'Alumni request approved successfully.']);
        } else {
            return json_encode(['error' => 'Not found.']);
        }
    }
}
