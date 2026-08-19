<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\LeadingAndGovernor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class TeacherController extends Controller
{
    public function index()
    {
        return view('backend.pages.leadingmember.index');
    }
    public function getList(Request $request)
    {
        $data = LeadingAndGovernor::with('user'); // Load user relationship

        if ($request->name) {
            $data->whereHas('user', function ($query) use ($request) {
                $query->where('first_name', 'like', "%" . $request->name . "%")
                      ->orWhere('last_name', 'like', "%" . $request->name . "%");
            });
        }

        if ($request->email) {
            $data->whereHas('user', function ($query) use ($request) {
                $query->where('email', 'like', "%" . $request->email . "%");
            });
        }

        if ($request->phone) {
            $data->whereHas('user', function ($query) use ($request) {
                $query->where('phone', 'like', "%" . $request->phone . "%");
            });
        }

        return Datatables::of($data)
            ->addColumn('profile_image', function ($row) {
                $profileImage = $row->user->profile_image ?? 'default.jpg';
                return '<img class="profile-img" src="' . asset('uploads/user-images/' . $profileImage) . '" alt="Profile Image" width="50" height="50">';
            })
            ->addColumn('name', function ($row) {
                return $row->user->first_name . ' ' . $row->user->last_name;
            })
            ->addColumn('email', function ($row) {
                return $row->user->email;
            })
            ->addColumn('phone_no', function ($row) {
                return $row->user->phone;
            })
            ->addColumn('status', function ($row) {
                return $row->status == 1
                    ? '<button class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="0">Approved</button>'
                    : '<button class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="1">Pending</button>';
            })

            ->addColumn('action', function ($row) {
                return '<a href="" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary"><i class="fa-solid fa-pencil"></i></a>
                        <a class="delete_btn btn btn-sm btn-danger" data-id="' . $row->id . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            })
            ->rawColumns(['profile_image', 'name', 'email', 'phone_no','status' ,'action'])
            ->make(true);
    }

    public function edit($id){
        $leading_member = LeadingAndGovernor::with('user')->find($id);
        return view('backend.pages.leadingmember.edit', compact('leading_member'));
    }

    public function update(Request $request, $id)
    {
        // Validate the form inputs
        $validator = $request->validate([
            'first_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);


        $leading_member = LeadingAndGovernor::findOrFail($id);

        $user = $leading_member->user;

    if (!$user) {
        return response()->json([
            'type' => 'error',
            'message' => 'User not found for this student.',
        ], 404);
    }

    // Update User details
    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->save();

    // Update Student details

    $leading_member->gender = $request->gender;
    $leading_member->birthdate = $request->birthdate;
    $leading_member->specialization = $request->specialization;

    $leading_member->status  = ($request->status) ? 1 : 0;

    $leading_member->save();
        // Save the updates
        if ($leading_member->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Leadning Member information updated successfully.',
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Failed to update Leading Member information.',
            ]);
        }
    }

    public function updateStatus(Request $request)
    {
        $leading_member = LeadingAndGovernor::find($request->id);

        if (!$leading_member) {
            return response()->json(['success' => false, 'message' => 'Leading Member not found!'], 404);
        }

        // Update status in user table
        $leading_member->user->status = $request->status;
        $leading_member->user->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'new_status' => $leading_member->status
        ]);
    }
    public function delete($id){
        $leading_member = LeadingAndGovernor::find($id);
        if($leading_member){
            $leading_member->delete();
            return json_encode(['success' => 'Leading Member info deleted successfully.']);
        }else{
            return json_encode(['error' => 'Not found.']);
        }
    }

    public function importCsv(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx|max:2048'
        ]);


        $file = $request->file('file');


        $data = $this->parseCsv($file->getPathname());

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Invalid CSV file.'], 400);
        }

        $existingUsers = [];
        $usersTableColumns = Schema::getColumnListing('user');
        $teacherTableColumns = Schema::getColumnListing('teachers');

        foreach ($data as $row) {
            DB::beginTransaction();
            try {
                // Validate required fields
                if (empty($row['name']) || empty($row['mobile_no'])) {
                    throw new \Exception('Missing required fields: name or mobile_no.');
                }


                $fullName = trim($row['name'] ?? '');
                $nameParts = explode(' ', $fullName, 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';

                $user = User::where('phone', $row['mobile_no'])->first();
                if ($user) {
                    $existingUsers[] = $user;
                    DB::rollBack();
                    continue;
                }

                $userData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone' => $row['mobile_no'] ?? '',
                    'password' => bcrypt('defaultpassword'),
                    'status' => 0
                ];


                foreach ($row as $key => $value) {
                    if (in_array($key, $usersTableColumns) && !isset($userData[$key])) {
                        $userData[$key] = $value;
                    }
                }


                $user = User::create($userData);


                $teacherData = [
                    'user_id' => $user->id,
                    'specialization' => $row['specialization'] ?? '',
                    'birthdate' => isset($row['date_of_birth']) ? Carbon::parse($row['date_of_birth'])->format('Y-m-d') : null,
                    'gender' => $row['gender'] ?? '',
                ];


                foreach ($row as $key => $value) {
                    if (in_array($key, $teacherTableColumns) && !isset($teacherData[$key])) {
                        $teacherData[$key] = $value;
                    }
                }


                LeadingAndGovernor::create($teacherData);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Error processing CSV file: ' . $e->getMessage()], 500);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'CSV imported successfully!',
            'existing_users' => $existingUsers
        ], 200);
    }



    public function parseCsv($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) return FALSE;
        $header = NULL;
        $data = array();
        $x = [];
        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 10000, $delimiter)) !== FALSE) {
                if (!$header)
                    $header = $row;
                else {
                    if (count($header) == count($row)) {
                        $data[] = array_combine($header, $row);
                    }
                }
            }
            fclose($handle);
        }
        return $data;
    }

}
