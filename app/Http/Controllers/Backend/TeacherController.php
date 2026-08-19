<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class TeacherController extends Controller
{
    public function index()
    {
        return view('backend.pages.teacher.index');
    }
    public function getList(Request $request)
    {
        $data = Teacher::with('user'); // Load user relationship

        if ($request->name) {
            $data->whereHas('user', function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('first_name', 'like', "%" . $request->name . "%")
                      ->orWhere('last_name', 'like', "%" . $request->name . "%")
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%" . $request->name . "%"]);
                });
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
                return '<img class="profile-img" src="' . asset($profileImage) . '" alt="Profile Image" width="50" height="50">';
            })
            ->addColumn('name', function ($row) {
                return $row->user?->first_name . ' ' . $row->user?->last_name;
            })
            ->addColumn('email', function ($row) {
                return $row->user?->email;
            })
            ->addColumn('phone_no', function ($row) {
                return $row->user?->phone;
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
        $teacher = Teacher::with('user')->find($id);
        return view('backend.pages.teacher.edit', compact('teacher'));
    }

    public function store(Request $request)
    {
        // Validate the form inputs
        $validator = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Ensure email is unique
            'phone' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female,other',
            'teacher_id' => 'required',
            'birthdate' => 'nullable|date',
        ]);

        // Create a new User
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = bcrypt('password'); // Set a default password or generate one
        $user->save();

        // Create a new Student
        $teacher = new Teacher();
        $teacher->user_id = $user->id;
        $teacher->birthdate = $request->birthdate;
        $teacher->gender = $request->gender;
        $teacher->designation = $request->designation;
        $teacher->teacher_id = $request->teacher_id;
        $teacher->save();

        // Return success response
        return response()->json([
            'type' => 'success',
            'message' => 'Student created successfully.',
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validate the form inputs
        $validator = $request->validate([
            'first_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);


        $teacher = Teacher::findOrFail($id);

        $user = $teacher->user;

    if (!$user) {
        return response()->json([
            'type' => 'error',
            'message' => 'User not found for this teacher.',
        ], 404);
    }

    // Update User details
    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->save();

    // Update Student details

    $teacher->gender = $request->gender;
    $teacher->birthdate = $request->birthdate;
    $teacher->department = $request->department;
    $teacher->designation = $request->designation;
    $teacher->specialization = $request->specialization;
    $teacher->facebook_link = $request->facebook_link;
    $teacher->linkedin_link = $request->linkedin_link;
    $teacher->organization = $request->organization;
    $teacher->bio = $request->bio;

    $teacher->status  = ($request->status) ? 1 : 0;
    if ($request->has('degree')) {
        $teacher->degree = is_array($request->degree) ? json_encode($request->degree) : json_encode([$request->degree]);
    }

    if ($request->has('institute')) {
        $teacher->institute = is_array($request->institute) ? json_encode($request->institute) : json_encode([$request->institute]);
    }

    if ($request->has('organization')) {
        $teacher->organization = is_array($request->organization) ? json_encode($request->organization) : json_encode([$request->organization]);
    }

    if ($request->has('cgpa')) {
        $teacher->cgpa = is_array($request->cgpa) ? json_encode($request->cgpa) : json_encode([$request->cgpa]);
    }

    if ($request->has('position')) {
        $teacher->position = is_array($request->position) ? json_encode($request->position) : json_encode([$request->position]);
    }

    if ($request->has('institute')) {
        $teacher->institute = is_array($request->institute) ? json_encode($request->institute) : json_encode([$request->institute]);
    }

    if ($request->has('fromdate')) {
        $teacher->fromdate = is_array($request->fromdate) ? json_encode($request->fromdate) : json_encode([$request->fromdate]);
    }

    if ($request->has('todate')) {
        $teacher->todate = is_array($request->todate) ? json_encode($request->todate) : json_encode([$request->todate]);
    }

    // currently_working is a single checkbox (no array)
    $teacher->currently_working = $request->currentworking_status ?? 0;


    $teacher->save();
        // Save the updates
        if ($teacher->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Teacher information updated successfully.',
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Failed to update Teacher information.',
            ]);
        }
    }

    public function updateStatus(Request $request)
{
    $teacher = Teacher::find($request->id);

    if (!$teacher) {
        return response()->json(['success' => false, 'message' => 'Teacher not found!'], 404);
    }

    // Update status in user table
    $teacher->user->status = $request->status;
    $teacher->user->save();

    return response()->json([
        'success' => true,
        'message' => 'Status updated successfully!',
        'new_status' => $teacher->status
    ]);
}

    public function delete($id){
        $teacher = Teacher::find($id);

        if ($teacher) {
            // Get the associated user ID before deleting the teacher
            $userId = $teacher->user_id;

            // Delete the teacher record first
            $teacher->delete();

            // Delete the user record if it exists
            if ($userId) {
                User::where('id', $userId)->delete();
            }

            return response()->json(['success' => 'Teacher and associated user deleted successfully.']);
        } else {
            return response()->json(['error' => 'Teacher not found.'], 404);
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


            Teacher::create($teacherData);

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
