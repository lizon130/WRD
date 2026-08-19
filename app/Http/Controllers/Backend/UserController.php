<?php

namespace App\Http\Controllers\Backend;

use Log;
use Auth;
use Hash;
use Helper;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\LeadingAndGovernor;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('user.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('backend.pages.user.index');
    }

    public function getList(Request $request)
    {

        $data = User::query();
        if ($request->name) {
            $data->where(function ($query) use ($request) {
                $query->where('first_name', 'like', "%" . $request->name . "%")
                    ->orWhere('last_name', 'like', "%" . $request->name . "%");
            });
        }

        if ($request->email) {
            $data->where(function ($query) use ($request) {
                $query->where('email', 'like', "%" . $request->email . "%");
            });
        }

        if ($request->phone) {
            $data->where(function ($query) use ($request) {
                $query->where('phone', 'like', "%" . $request->phone . "%");
            });
        }

        // Order by created_at descending
        $data->orderBy('created_at', 'desc');


        return Datatables::of($data)

            ->editColumn('profile_image', function ($row) {
                return ($row->profile_image) ? '<img class="profile-img" src="' . asset($row->profile_image) . '" alt="profile image">' : '<img class="profile-img" src="' . asset('assets/img/no-img.jpg') . '" alt="profile image">';
            })

            ->editColumn('first_name', function ($row) {
                return $row->first_name . ' ' . $row->last_name;
            })

            ->editColumn('role', function ($row) {
                return optional($row->roles)->name;
            })

            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<button class="btn btn-primary btn-sm change-user-status" data-id="' . $row->id . '" data-status="0">Active</button>';
                } else {
                    return '<button class="btn btn-danger btn-sm change-user-status" data-id="' . $row->id . '" data-status="1">Inactive</button>';
                }
            })

            ->addColumn('action', function ($row) {
                $btn = '';
                if (Helper::hasRight('user.edit')) {
                    $btn = $btn . '<a href="" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
                }
                if (Helper::hasRight('user.edit')) {
                    $btn = $btn . '<a class="change_password btn btn-sm btn-warning text-light mx-1 " data-id="' . $row->id . '" href="" title="Change Password"><i class="fa-solid fa-key"></i></a>';
                }
                if (Helper::hasRight('user.delete')) {
                    $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger " data-id="' . $row->id . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['profile_image', 'first_name', 'role', 'status', 'action'])->make(true);
    }

    // public function store(Request $request){
    //     $validator = $request->validate([
    // 		'first_name' => 'required',
    // 		'last_name' => 'required',
    // 		'email' => 'required|email|unique:user',
    // 		'phone' => 'required|unique:user',
    // 		'role' => 'required',
    // 	]);

    //     $user = new User();
    //     $user->first_name = $request->first_name;
    //     $user->last_name = $request->last_name;
    //     $user->email = $request->email;
    //     $user->phone = $request->phone;
    //     $user->role  = $request->role;
    //     $user->password  = Hash::make($request->password ?? $request->phone);

    //     if ($user->save()) {
    //         // Insert into Teacher or Student table based on role
    //         if ($user->role == 9) {
    //             Teacher::create(['user_id' => $user->id]); // Assuming Teacher table has a 'user_id' column
    //         } elseif ($user->role == 8) {
    //             Student::create(['user_id' => $user->id]); // Assuming Student table has a 'user_id' column
    //         }

    //         return response()->json([
    //             'type' => 'success',
    //             'message' => 'User created successfully.',
    //         ]);
    //     }

    //     return response()->json([
    //         'type' => 'error',
    //         'message' => 'Failed to create user.',
    //     ], 500);
    // }
    public function store(Request $request)
    {
        // Log the incoming request
        \Log::info('User Store Request:', $request->all());

        // Validate request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'phone' => 'nullable',
            'role' => 'required|integer',
            'password' => 'nullable|min:6',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'type' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            \DB::beginTransaction();
            \Log::info('Transaction started');

            $six_digit_random_number = random_int(100000, 999999);

            // Create new user
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->role = $request->role;
            $user->status = 1;
            $user->password = Hash::make($request->password ?? $six_digit_random_number);

            // Handle Profile Image Upload
            if ($request->hasFile('profile_image')) {
                \Log::info('Profile image uploaded');
                $image = $request->file('profile_image');
                $uploadPath = public_path('uploads/user-images');

                // Ensure the directory exists
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($uploadPath, $filename);
                $user->profile_image = 'uploads/user-images/' . $filename;
            }

            \Log::info('Attempting to save user');
            if (!$user->save()) {
                throw new \Exception('Failed to save user');
            }
            \Log::info('User saved with ID: ' . $user->id);

            // Insert into specific tables based on role
            switch ($user->role) {
                case 9: // Teacher
                    \Log::info('Creating teacher for user ID: ' . $user->id);
                    Teacher::create([
                        'user_id' => $user->id,
                        'teacher_id' => 'T' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                        'specialization' => $request->specialization ?? null,
                        'designation' => $request->designation ?? null,
                        'birthdate' => $request->birthdate ?? null,
                        'gender' => $request->gender ?? null,
                        'status' => 1,
                    ]);
                    break;

                case 8: // Student
                    \Log::info('Creating student for user ID: ' . $user->id);
                    Student::create([
                        'user_id' => $user->id,
                        'status' => 1,
                    ]);
                    break;

                case 10: // Leading & Governor
                    \Log::info('Creating Leading & Governor for user ID: ' . $user->id);
                    LeadingAndGovernor::create([
                        'user_id' => $user->id,
                        'leading_gov_id' => 'LG' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                        'specialization' => $request->specialization ?? null,
                        'designation' => $request->designation ?? null,
                        'birthdate' => $request->birthdate ?? null,
                        'gender' => $request->gender ?? null,
                        'status' => 1
                    ]);
                    break;
            }

            // Send password email
            $email = $user->email;
            if ($email) {
                \Log::info('Sending welcome email to: ' . $email);
                $subject = 'Thank you for registering on our platform';
                $data['user'] = $user;
                $data['otp'] = $request->password ?? $six_digit_random_number;
                $data['message'] = 'Thank you for registering on our platform. Your default password is below. Please do not share the code with others and please change your password after logging in.';

                try {
                    Helper::sendEmail($email, $subject, $data, 'welcome');
                    \Log::info('Email sent successfully');
                } catch (\Exception $e) {
                    \Log::error('Email sending failed: ' . $e->getMessage());
                }
            }

            \DB::commit();
            Log::info('Transaction committed successfully');

            return response()->json([
                'type' => 'success',
                'message' => 'User created successfully.',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('User creation failed: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());

            return response()->json([
                'type' => 'error',
                'message' => 'Failed to create user. ' . $e->getMessage(),
            ], 500);
        }
    }
    public function edit($id)
    {
        $user = User::find($id);
        return view('backend.pages.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'role' => 'required|integer',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'type' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $existed_user = User::find($id);
        if (!$existed_user) {
            return response()->json([
                'type' => 'error',
                'message' => 'User not found.',
            ], 404);
        }

        $oldRole = $existed_user->role; // Store old role before updating

        // Update user fields
        $existed_user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->has('status') ? 1 : 0,
            'password' => !empty($request->password) ? Hash::make($request->password) : $existed_user->password,
        ]);

        // If role has changed, remove old role-specific entry and insert new one
        if ($oldRole != $request->role) {
            $this->removeOldRoleEntry($oldRole, $existed_user->id);
            $this->handleUserRoleUpdate($request, $existed_user);
        }

        // Handle Profile Image Upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $uploadPath = public_path('uploads/user-images');

            // Ensure the directory exists
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Delete the previous image if it exists
            if ($existed_user->profile_image && File::exists(public_path($existed_user->profile_image))) {
                File::delete(public_path($existed_user->profile_image));
            }

            // Generate new filename
            $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadPath, $filename);
            $existed_user->profile_image = 'uploads/user-images/' . $filename;
            $existed_user->save();
        }

        // Assign new role-specific entry if role was changed
        if ($existed_user->role != $request->role) {
            $this->handleUserRoleUpdate($request, $existed_user);
        }

        return response()->json([
            'type' => 'success',
            'message' => 'User updated successfully.',
            'user' => $existed_user,
        ]);
    }

    private function removeOldRoleEntry($role, $userId)
    {
        switch ($role) {
            case 9:
                Teacher::where('user_id', $userId)->delete();
                break;
            case 8:
                Student::where('user_id', $userId)->delete();
                break;
            case 10:
                LeadingAndGovernor::where('user_id', $userId)->delete();
                break;
        }
    }


    private function handleUserRoleUpdate($request, $user)
    {
        // \Log::info('Assigning new role to user', ['role' => $request->role, 'user_id' => $user->id]);

        switch ($request->role) {
            case 9: // Assign as Teacher
                Teacher::create([
                    'user_id' => $user->id,
                    'teacher_id' => 'T' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                    'specialization' => $request->specialization ?? null,
                    'designation' => $request->designation ?? null,
                    'birthdate' => $request->birthdate ?? null,
                    'gender' => $request->gender ?? null,
                    'status' => $request->status ?? 0,
                ]);
                break;

            case 8: // Assign as Student
                Student::create([
                    'user_id' => $user->id,
                    'student_id' => 'S' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                    'class' => $request->class ?? null,
                    'group' => $request->group ?? null,
                    'birthdate' => $request->birthdate ?? null,
                    'father_name' => $request->father_name ?? null,
                    'village' => $request->village ?? null,
                    'post_office' => $request->post_office ?? null,
                    'upazila' => $request->upazila ?? null,
                    'district' => $request->district ?? null,
                    'status' => 1,
                ]);
                break;

            case 10: // Assign as Leading & Governor
                LeadingAndGovernor::create([
                    'user_id' => $user->id,
                    'leading_gov_id' => 'LG' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                    'specialization' => $request->specialization ?? null,
                    'designation' => $request->designation ?? null,
                    'birthdate' => $request->birthdate ?? null,
                    'gender' => $request->gender ?? null,
                    'status' => $request->status ?? 0,
                ]);
                break;
        }
    }



    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return json_encode(['success' => 'User deleted successfully.']);
        } else {
            return json_encode(['error' => 'User not found.']);
        }
    }

    public function changePassword(Request $request)
    {
        $validator = $request->validate([
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ], [
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = User::find($request->user_id);
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'type' => 'success',
                'message' => 'User password changed successfully.',
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'User not found.',
            ]);
        }
    }

    public function updateStatus(Request $request)
    {
        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found!'], 404);
        }

        // Toggle status (1 = Active, 0 = Inactive)
        $user->status = $request->status;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully!',
            'new_status' => $user->status
        ]);
    }
}