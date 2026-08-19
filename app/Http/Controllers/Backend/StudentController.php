<?php
namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use Helper;
use App\Models\User;
use App\Models\Student;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class StudentController extends Controller
{
    public function index()
    {
        $classes = ClassModel::all();
        return view('backend.pages.student.index', compact('classes'));
    }

    public function getList(Request $request)
    {
        $data = Student::with('user');

        if ($request->education_year) {
            $data->whereHas('students', function ($query) use ($request) {
                $query->where('education_year', 'like', "%" . $request->education_year . "%");
            });
        }

        if ($request->name) {
            $data->whereHas('user', function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('first_name', 'like', "%" . $request->name . "%")
                      ->orWhere('last_name', 'like', "%" . $request->name . "%")
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%" . $request->name . "%"]);
                });
            });
        }
        


        if ($request->class_id) {
            $data->whereHas('user', function ($query) use ($request) {
                $query->where('class', 'like', "%" . $request->class_id . "%");
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
                return $row->user->status == 1
                ? '<button class="btn btn-success btn-sm change-status" data-id="' . $row->id . '" data-status="0">Approved</button>'
                : '<button class="btn btn-warning btn-sm change-status" data-id="' . $row->id . '" data-status="1">Pending</button>';
            })

            ->addColumn('action', function ($row) {
                return '<a href="" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary"><i class="fa-solid fa-pencil"></i></a>
                        <a class="delete_btn btn btn-sm btn-danger" data-id="' . $row->id . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            })
            ->rawColumns(['profile_image', 'name', 'email', 'phone_no', 'status', 'action'])
            ->make(true);
    }
    public function edit($id)
    {
        $student = Student::with('user')->find($id);
        return view('backend.pages.student.edit', compact('student'));
    }


    public function store(Request $request)
    {
        // Validate the form inputs
        $validator = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Ensure email is unique
            'phone' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'education_year' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female,other',
            'location' => 'nullable|string|max:255',
            'student_id' => 'required|string|unique:students,student_id', // Ensure student_id is unique
            'birthdate' => 'required|date',
        ]);
        $six_digit_random_number = random_int(100000, 999999);
        // Create a new User
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($six_digit_random_number); // Set a default password or generate one
        $user->save();
    
        // Create a new Student
        $student = new Student();
        $student->user_id = $user->id; // Link the student to the user
        $student->birthdate = $request->birthdate;
        $student->gender = $request->gender;
        $student->location = $request->location;
        $student->student_id = $request->student_id;
        $student->class = $request->class;
        $student->education_year = $request->education_year;
        $student->save();
    
        // Send email to the user
        $email = $user->email;
        if ($email) {
            $subject = 'Thank you for registering on our platform';
            $data['user'] = $user;
            $data['otp'] = $six_digit_random_number; // Default password or generate one
            $data['message'] = 'Thank you for registering on our platform. Your default password is below —. Please do not share the code with others and please change your password after logging in.';
            
            // Assuming Helper::sendEmail is a custom helper function to send emails
            Helper::sendEmail($email, $subject, $data, 'welcome');
        }
    
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
            'first_name' => 'required|string|max: 255',
            'email'      => 'required|email|max: 255',
            'phone'      => 'required|string|max: 255',
            'class'      => 'required|string|max: 255',
            'group'      => 'required|string|max: 255',
        ]);

        $student = Student::findOrFail($id);

        $user = $student->user;

        if (! $user) {
            return response()->json([
                'type'    => 'error',
                'message' => 'User not found for this student.',
            ], 404);
        }

        // Update User details
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        $user->phone      = $request->phone;
        $user->save();

        // Update Student details
        $student->birthdate            = $request->birthdate;
        $student->section              = $request->section;
        $student->roll                 = $request->roll;
        $student->gender               = $request->gender;
        $student->blood_grp            = $request->blood_grp;
        $student->nationality          = $request->nationality;
        $student->language             = $request->language;
        $student->current_school       = $request->current_school;
        $student->previous_school      = $request->previous_school;
        $student->father_name          = $request->father_name;
        $student->father_phone         = $request->father_phone;
        $student->mother_name          = $request->mother_name;
        $student->mother_phone         = $request->mother_phone;
        $student->local_guardian_name  = $request->local_guardian_name;
        $student->local_guardian_phone = $request->local_guardian_phone;
        $student->emergency_phone      = $request->emergency_phone;
        $student->current_village      = $request->current_village;
        $student->current_post_office  = $request->current_post_office;
        $student->current_district     = $request->current_district;
        $student->current_upazila      = $request->current_upazila;
        $student->hobbie               = json_encode($request->hobbie);
        $student->village              = $request->village;
        $student->post_office          = $request->post_office;
        $student->upazila              = $request->upazila;
        $student->district             = $request->district;
        $student->status               = ($request->status) ? 1 : 0;
        $student->alternative_phone    = $request->alternative_phone;
        $student->location             = $request->location;

        // newly added
        $student->class = $request->class ?? null;
        $student->group = $request->group ?? null;

        $student->save();
        // Save the updates
        if ($student->save()) {
            return response()->json([
                'type'    => 'success',
                'message' => 'Student information updated successfully.',
            ]);
        } else {
            return response()->json([
                'type'    => 'error',
                'message' => 'Failed to update alumni information.',
            ]);
        }
    }

    public function delete($id)
    {
        $student = Student::find($id);

        if ($student) {
            // Get the associated user ID before deleting the student
            $userId = $student->user_id;

            // Delete the student record first
            $student->delete();

            // Delete the user record if it exists
            if ($userId) {
                User::where('id', $userId)->delete();
            }

            return response()->json(['success' => 'Student and associated user deleted successfully.']);
        } else {
            return response()->json(['error' => 'Student not found.'], 404);
        }
    }


    public function updateStatus(Request $request)
    {
        $student = Student::find($request->id);

        if (! $student) {
            return response()->json(['success' => false, 'message' => 'Student not found!'], 404);
        }

        // Update status in user table
        $student->user->status = $request->status;
        $student->user->save();

        return response()->json([
            'success'    => true,
            'message'    => 'Status updated successfully!',
            'new_status' => $student->user->status,
        ]);
    }

// public function importCsv(Request $request)
// {
//     // Validate the uploaded file
//     $request->validate([
//         'file' => 'required|mimes:csv,txt,xlsx|max:2048'
//     ]);

//     // Get the uploaded file
//     $file = $request->file('file');

//     // Read CSV file
//     $data = $this->parseCsv($file->getPathname());

//     if (!$data) {
//         return response()->json(['success' => false, 'message' => 'Invalid CSV file.'], 400);
//     }

//     foreach ($data as $row) {
//         DB::beginTransaction();
//         try {
//             // Validate required fields
//             if (empty($row['name']) || empty($row['mobile_no'])) {
//                 throw new \Exception('Missing required fields: name or mobile_no.');
//             }

//             // Extract first and last name from the full name field
//             $fullName = trim($row['name'] ?? '');
//             $nameParts = explode(' ', $fullName, 2);
//             $firstName = $nameParts[0] ?? '';
//             $lastName = $nameParts[1] ?? '';

//             // Create User
//             $user = User::create([
//                 'first_name' => $firstName,
//                 'last_name' => $lastName,
//                 'phone' => $row['mobile_no'] ?? '',
//                 'password' => bcrypt('defaultpassword'), // Default password
//                 'status' => 1 // Active user
//             ]);

//             // Create Student
//             Student::create([
//                 'user_id' => $user->id,
//                 'father_name' => $row['father_name'] ?? '',
//                 'birthdate' => isset($row['date_of_birth']) ? Carbon::parse($row['date_of_birth'])->format('Y-m-d') : null,
//                 'village' => $row['village'] ?? '',
//                 'post_office' => $row['post_office'] ?? '',
//                 'upazila' => $row['upazila'] ?? '',
//                 'district' => $row['dristricts'] ?? ''
//             ]);

//             DB::commit();
//         } catch (\Exception $e) {
//             DB::rollBack();
//             return response()->json(['success' => false, 'message' => 'Error processing CSV file: ' . $e->getMessage()], 500);
//         }
//     }

//     return response()->json(['success' => true, 'message' => 'CSV imported successfully!'], 200);
// }

// this is  good
// public function importCsv(Request $request)
// {
//     // Validate the uploaded file
//     $request->validate([
//         'file' => 'required|mimes:csv,txt,xlsx|max:2048'
//     ]);

//     // Get the uploaded file
//     $file = $request->file('file');

//     // Read CSV file
//     $data = $this->parseCsv($file->getPathname());

//     if (!$data) {
//         return response()->json(['success' => false, 'message' => 'Invalid CSV file.'], 400);
//     }

//     $existingUsers = [];

//     foreach ($data as $row) {
//         DB::beginTransaction();
//         try {
//             // Validate required fields
//             if (empty($row['name']) || empty($row['mobile_no'])) {
//                 throw new \Exception('Missing required fields: name or mobile_no.');
//             }

//             $fullName = trim($row['name'] ?? '');
//             $nameParts = explode(' ', $fullName, 2);
//             $firstName = $nameParts[0] ?? '';
//             $lastName = $nameParts[1] ?? '';

//             $user = User::where('phone', $row['mobile_no'])->first();
//             if ($user) {
//                 $existingUsers[] = $user;
//                 DB::rollBack();
//                 continue;
//             }

//             // Create User
//             $user = User::create([
//                 'first_name' => $firstName,
//                 'last_name' => $lastName,
//                 'phone' => $row['mobile_no'] ?? '',
//                 'password' => bcrypt('defaultpassword'), // Default password
//                 'status' => 1 // Active user
//             ]);

//             // Create Student
//             Student::create([
//                 'user_id' => $user->id,
//                 'father_name' => $row['father_name'] ?? '',
//                 'birthdate' => isset($row['date_of_birth']) ? Carbon::parse($row['date_of_birth'])->format('Y-m-d') : null,
//                 'village' => $row['village'] ?? '',
//                 'post_office' => $row['post_office'] ?? '',
//                 'upazila' => $row['upazila'] ?? '',
//                 'district' => $row['dristricts'] ?? ''
//             ]);

//             DB::commit();
//         } catch (\Exception $e) {
//             DB::rollBack();
//             return response()->json(['success' => false, 'message' => 'Error processing CSV file: ' . $e->getMessage()], 500);
//         }
//     }

//     return response()->json([
//         'success' => true,
//         'message' => 'CSV imported successfully!',
//         'existing_users' => $existingUsers
//     ], 200);
// }

// if extra column form csv then dynamicly import this code
    public function importCsv(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx|max:2048',
        ]);

        $file = $request->file('file');

        $data = $this->parseCsv($file->getPathname());

        if (! $data) {
            return response()->json(['success' => false, 'message' => 'Invalid CSV file.'], 400);
        }

        $existingUsers        = [];
        $usersTableColumns    = Schema::getColumnListing('user');
        $studentsTableColumns = Schema::getColumnListing('students');

        foreach ($data as $row) {
            DB::beginTransaction();
            try {
                // Validate required fields
                if (empty($row['name']) || empty($row['mobile_no'])) {
                    throw new \Exception('Missing required fields: name or mobile_no.');
                }

                $fullName  = trim($row['name'] ?? '');
                $nameParts = explode(' ', $fullName, 2);
                $firstName = $nameParts[0] ?? '';
                $lastName  = $nameParts[1] ?? '';

                $user = User::where('phone', $row['mobile_no'])->first();
                if ($user) {
                    $existingUsers[] = $user;
                    DB::rollBack();
                    continue;
                }

                $userData = [
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'phone'      => $row['mobile_no'] ?? '',
                    'password'   => bcrypt('defaultpassword'),
                    'status'     => 0,
                ];

                foreach ($row as $key => $value) {
                    if (in_array($key, $usersTableColumns) && ! isset($userData[$key])) {
                        $userData[$key] = $value;
                    }
                }

                $user = User::create($userData);

                $studentData = [
                    'user_id'     => $user->id,
                    'father_name' => $row['father_name'] ?? '',
                    'birthdate'   => isset($row['date_of_birth']) ? Carbon::parse($row['date_of_birth'])->format('Y-m-d') : null,
                    'village'     => $row['village'] ?? '',
                    'post_office' => $row['post_office'] ?? '',
                    'upazila'     => $row['upazila'] ?? '',
                    'district'    => $row['dristricts'] ?? '',
                ];

                foreach ($row as $key => $value) {
                    if (in_array($key, $studentsTableColumns) && ! isset($studentData[$key])) {
                        $studentData[$key] = $value;
                    }
                }

                Student::create($studentData);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Error processing CSV file: ' . $e->getMessage()], 500);
            }
        }

        return response()->json([
            'success'        => true,
            'message'        => 'CSV imported successfully!',
            'existing_users' => $existingUsers,
        ], 200);
    }

    public function parseCsv($filename = '', $delimiter = ',')
    {
        if (! file_exists($filename) || ! is_readable($filename)) {
            return false;
        }

        $header = null;
        $data   = [];
        $x      = [];
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 10000, $delimiter)) !== false) {
                if (! $header) {
                    $header = $row;
                } else {
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
