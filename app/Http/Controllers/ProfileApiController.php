<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Profile;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileApiController extends Controller
{
    // List all profiles
    public function list($user_id)
    {
        try {
            // Fetch the profile using the user_id instead of profile id
            $profile = Profile::with([
                'user:id,profile_image,first_name,last_name,phone',
                'student:user_id,student_id,class,group',
                'teacher:user_id,teacher_id,specialization'
            ])->where('user_id', $user_id)->first();

            // Check if profile exists
            if (!$profile) {
                return response()->json(['error' => 'Profile not found'], 404);
            }

            return response()->json(['profile' => $profile], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch profile',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            // Validate the input data
            $validator = Validator::make($request->all(), [
                'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'designation' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'about' => 'nullable|string',
                'student' => 'nullable|array',
                'student.student_id' => 'nullable|string|max:255',
                'student.class' => 'nullable|string|max:255',
                'student.group' => 'nullable|string|max:255',
                'teacher' => 'nullable|array',
                'teacher.teacher_id' => 'nullable|string|max:255',
                'teacher.specialization' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Get the authenticated user
            $authUser = auth()->user();

            // Check if the user exists
            if (!$authUser) {
                return response()->json(['error' => 'Unauthorized access'], 401);
            }

            // Use authenticated user data to create the profile
            $user = $authUser;

            // Handle banner image upload
            $bannerImage = null;
            if ($request->hasFile('banner_image')) {
                $image = $request->file('banner_image');
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/banner-images'), $filename);
                $bannerImage = $filename;
            }

            // Create the profile
            $profile = Profile::create([
                'user_id' => $user->id,
                'designation' => $request->input('designation'),
                'location' => $request->input('location'),
                'about' => $request->input('about'),
                'banner_image' => $bannerImage,
            ]);

            // Create or update student information
            if ($request->has('student')) {
                Student::updateOrCreate(
                    ['user_id' => $user->id],
                    $request->input('student')
                );
            }

            // Create or update teacher information if role is 9
            if ($request->has('teacher') && $user->role == 9) {
                Teacher::updateOrCreate(
                    ['user_id' => $user->id],
                    $request->input('teacher')
                );
            }

            // Reload the profile with its relationships
            $profile = $profile->load([
                'user:id,profile_image,first_name,last_name,phone,email',
                'student:id,user_id,student_id,class,group',
                'teacher:id,user_id,teacher_id,specialization'
            ]);

            return response()->json(['message' => 'Profile created successfully', 'profile' => $profile], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create profile', 'message' => $e->getMessage()], 500);
        }
    }

    // Store a new profile
    // public function store(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'user_id' => 'required|exists:user,id', // Corrected: `user,id` to `users,id`
    //             'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //             'designation' => 'nullable|string|max:255',
    //             'location' => 'nullable|string|max:255',
    //             'about' => 'nullable|string',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json(['errors' => $validator->errors()], 422);
    //         }

    //         $data = $request->all();

    //         // Handle banner image upload
    //         if ($request->hasFile('banner_image')) {
    //             $image = $request->file('banner_image');
    //             $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
    //             $image->move(public_path('uploads/banner-images'), $filename); // Save image to public/uploads/banner-images
    //             $data['banner_image'] = 'uploads/banner-images/' . $filename; // Store file path in the $data array
    //         }

    //         // Create profile using the modified $data array
    //         $profile = Profile::create($data);

    //         return response()->json(['message' => 'Profile created successfully', 'profile' => $profile], 201);
    //     } catch (Exception $e) {
    //         return response()->json(['error' => 'Failed to create profile', 'message' => $e->getMessage()], 500);
    //     }
    // }

    public function update(Request $request, $user_id)
    {
        try {
            // Validate the input data
            $validator = Validator::make($request->all(), [
                'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'designation' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'about' => 'nullable|string',
                'user' => 'nullable|array',
                'user.first_name' => 'nullable|string|max:255',
                'user.last_name' => 'nullable|string|max:255',
                'user.phone' => 'nullable|string|max:15',
                'student' => 'nullable|array',
                'student.student_id' => 'nullable|string|max:255',
                'student.class' => 'nullable|string|max:255',
                'student.group' => 'nullable|string|max:255',
                'teacher' => 'nullable|array',
                'teacher.teacher_id' => 'nullable|string|max:255',
                'teacher.specialization' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Fetch the profile for the given user_id
            $profile = Profile::where('user_id', $user_id)->first();

            if (!$profile) {
                return response()->json(['error' => 'Profile not found'], 404);
            }

            $data = $request->only(['designation', 'location', 'about']);

            // Handle banner image upload
            if ($request->hasFile('banner_image')) {
                $image = $request->file('banner_image');
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/banner-images'), $filename);
                $data['banner_image'] = $filename;

                // Delete the old banner image if it exists
                if ($profile->banner_image && file_exists(public_path('uploads/banner-images/' . $profile->banner_image))) {
                    unlink(public_path('uploads/banner-images/' . $profile->banner_image));
                }
            }

            // Update the profile
            $profile->update($data);

            // Update the user information
            if ($request->has('user')) {
                $profile->user()->update($request->input('user'));
            }

            // Update the student information
            if ($request->has('student')) {
                Student::updateOrCreate(
                    ['user_id' => $user_id],
                    $request->input('student')
                );
            }

            // Update the teacher information
            if ($request->has('teacher')) {
                // Fetch the user's role from the user table
                $user = $profile->user;

                if ($user && $user->role == 9) {
                    Teacher::updateOrCreate(
                        ['user_id' => $user_id],
                        $request->input('teacher')
                    );
                }
            }

            // Reload the profile with its relationships
            $profile = $profile->load([
                'user:id,profile_image,first_name,last_name,phone',
                'student:id,user_id,student_id,class,group',
                'teacher:id,user_id,teacher_id,specialization'
            ]);

            return response()->json(['message' => 'Profile updated successfully', 'profile' => $profile], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update profile', 'message' => $e->getMessage()], 500);
        }
    }



    public function profile($user_id)
    {

        $user = User::where('id', $user_id)->first();


        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User info not found',
                'data' => [],
            ], 404);
        }


        if($user != null) {
            $student = Student::where('user_id', $user->id)->first();


            // combine all information for that user
            $data = [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'profile_image' => $user->profile_image,
                'banner' => $user->banner,

                // Student details (handle null values safely)
                'student_id' => optional($student)->student_id,
                'class' => optional($student)->class,
                'group' => optional($student)->group,
                'father_name' => optional($student)->father_name,
                'birthdate' => optional($student)->birthdate,
                'village' => optional($student)->village,
                'post_office' => optional($student)->post_office,
                'upazila' => optional($student)->upazila,
                'district' => optional($student)->district,
                'roll' => optional($student)->roll,
                'section' => optional($student)->section,
                'gender' => optional($student)->gender,
                'blood_grp' => optional($student)->blood_grp,
                'nationality' => optional($student)->nationality,
                'language' => optional($student)->language,
                'current_school' => optional($student)->current_school,
                'previous_school' => optional($student)->previous_school,
                'father_phone' => optional($student)->father_phone,
                'mother_name' => optional($student)->mother_name,
                'mother_phone' => optional($student)->mother_phone,
                'local_guardian_name' => optional($student)->local_guardian_name,
                'local_guardian_phone' => optional($student)->local_guardian_phone,
                'emergency_phone' => optional($student)->emergency_phone,
                'current_village' => optional($student)->current_village,
                'current_post_office' => optional($student)->current_post_office,
                'current_district' => optional($student)->current_district,
                'current_upazila' => optional($student)->current_upazila,
                'alternative_phone' => optional($student)->alternative_phone,
                'location' => optional($student)->location,
                'hobbies' => json_decode(optional($student)->hobbie, true) ?? []
            ];

            return response()->json([
                'success' => true,
                'message' => "User info found.",
                'data' => $data
            ]);
        }

    }



    public function updateProfile(Request $request,$user_id)
    {
        try {

            $user = User::find($user_id);


            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized user'
                ], 401);
            }

            // Validate request data
            $request->validate([
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:15',
                'profile_image' => 'nullable|image|max:2048',
                'class' => 'nullable|string|max:255',
                'group' => 'nullable|string|max:255',
                'father_name' => 'nullable|string|max:255',
                'birthdate' => 'nullable|date',
                'village' => 'nullable|string|max:255',
                'post_office' => 'nullable|string|max:255',
                'upazila' => 'nullable|string|max:255',
                'district' => 'nullable|string|max:255',
                'roll' => 'nullable|string|max:255',
                'section' => 'nullable|string|max:255',
                'gender' => 'nullable|string|max:255',
                'blood_grp' => 'nullable|string|max:255',
                'nationality' => 'nullable|string|max:255',
                'language' => 'nullable|string|max:255',
                'current_school' => 'nullable|string|max:255',
                'previous_school' => 'nullable|string|max:255',
                'father_phone' => 'nullable|string|max:15',
                'mother_name' => 'nullable|string|max:255',
                'mother_phone' => 'nullable|string|max:15',
                'local_guardian_name' => 'nullable|string|max:255',
                'local_guardian_phone' => 'nullable|string|max:15',
                'emergency_phone' => 'nullable|string|max:15',
                'current_village' => 'nullable|string|max:255',
                'current_post_office' => 'nullable|string|max:255',
                'current_district' => 'nullable|string|max:255',
                'current_upazila' => 'nullable|string|max:255',
                'alternative_phone' => 'nullable|string|max:15',
                'location' => 'nullable|string|max:255',
                'hobbies' => 'nullable|array',
            ]);

            // Update user fields
            $user_update_status = $user->update([
                'first_name' => $request->first_name ?? $user->first_name,
                'last_name' => $request->last_name ?? $user->last_name,
                'email' => $request->email ?? $user->email,
                'phone' => $request->phone ?? $user->phone,
            ]);
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $uploadPath = public_path('uploads/user-images');

                // Create the directory if it doesn't exist
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($uploadPath, $filename);

                // Save image path to user
                $user->profile_image = 'uploads/user-images/' . $filename;
                $user->save(); // ✅ Save the updated media field
            }

            if ($request->hasFile('banner')) {
                $bannerimage = $request->file('banner');
                $uploadPathbanner = public_path('uploads/user-images');

                // Create the directory if it doesn't exist
                if (!file_exists($uploadPathbanner)) {
                    mkdir($uploadPathbanner, 0777, true);
                }

                $filenamebanner = time() . '_' . uniqid() . '.' . $bannerimage->getClientOriginalExtension();
                $bannerimage->move($uploadPathbanner, $filenamebanner);

                // Save image path to user
                $user->banner = 'uploads/user-images/' . $filenamebanner;
                $user->save(); // ✅ Save the updated media field
            }

            if($user_update_status) {
                // Check if the user has a student profile
                $student = Student::where('user_id', $user->id)->first();

                if ($student) {
                    $student_update_status = $student->update(
                        [
                            'class' => $request->class ?? $student->class,
                            'group' => $request->group ?? $student->group,
                            'father_name' => $request->father_name ?? $student->father_name,
                            'birthdate' => $request->birthdate ?? $student->birthdate,
                            'village' => $request->village ?? $student->village,
                            'post_office' => $request->post_office ?? $student->post_office,
                            'upazila' => $request->upazila ?? $student->upazila,
                            'district' => $request->district ?? $student->district,
                            'roll' => $request->roll ?? $student->roll,
                            'section' => $request->section ?? $student->section,
                            'gender' => $request->gender ?? $student->gender,
                            'blood_grp' => $request->blood_grp ?? $student->blood_grp,
                            'nationality' => $request->nationality ?? $student->nationality,
                            'language' => $request->language ?? $student->language,
                            'current_school' => $request->current_school ?? $student->current_school,
                            'previous_school' => $request->previous_school ?? $student->previous_school,
                            'father_phone' => $request->father_phone ?? $student->father_phone,
                            'mother_name' => $request->mother_name ?? $student->mother_name,
                            'mother_phone' => $request->mother_phone ?? $student->mother_phone,
                            'local_guardian_name' => $request->local_guardian_name ?? $student->local_guardian_name,
                            'local_guardian_phone' => $request->local_guardian_phone ?? $student->local_guardian_phone,
                            'emergency_phone' => $request->emergency_phone ?? $student->emergency_phone,
                            'current_village' => $request->current_village ?? $student->current_village,
                            'current_post_office' => $request->current_post_office ?? $student->current_post_office,
                            'current_district' => $request->current_district ?? $student->current_district,
                            'current_upazila' => $request->current_upazila ?? $student->current_upazila,
                            'alternative_phone' => $request->alternative_phone ?? $student->alternative_phone,
                            'location' => $request->location ?? $student->location,
                            // 'hobbie' => json_encode($request->hobbies ?? $student->hobbie),
                            'hobbie' => $request->has('hobbies') ? json_encode($request->hobbies) : null,
                        ]
                    );

                    if($student_update_status) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Profile updated successfully!',
                            'user' => $user,
                            'student' => $student
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to update profile.'
                        ], 500);
                    }
                }
            }


        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }



    public function storeNonExisitingProfileInfo(Request $request)
    {
        try {

            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized user, please login with necessary token.'
                ], 401);
            }

            // Validate request data
            $request->validate([
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:15',
                'profile_image' => 'nullable|image|max:2048',
                'class' => 'nullable|string|max:255',
                'group' => 'nullable|string|max:255',
                'father_name' => 'nullable|string|max:255',
                'birthdate' => 'nullable|date',
                'village' => 'nullable|string|max:255',
                'post_office' => 'nullable|string|max:255',
                'upazila' => 'nullable|string|max:255',
                'district' => 'nullable|string|max:255',
                'roll' => 'nullable|string|max:255',
                'section' => 'nullable|string|max:255',
                'gender' => 'nullable|string|max:255',
                'blood_grp' => 'nullable|string|max:255',
                'nationality' => 'nullable|string|max:255',
                'language' => 'nullable|string|max:255',
                'current_school' => 'nullable|string|max:255',
                'previous_school' => 'nullable|string|max:255',
                'father_phone' => 'nullable|string|max:15',
                'mother_name' => 'nullable|string|max:255',
                'mother_phone' => 'nullable|string|max:15',
                'local_guardian_name' => 'nullable|string|max:255',
                'local_guardian_phone' => 'nullable|string|max:15',
                'emergency_phone' => 'nullable|string|max:15',
                'current_village' => 'nullable|string|max:255',
                'current_post_office' => 'nullable|string|max:255',
                'current_district' => 'nullable|string|max:255',
                'current_upazila' => 'nullable|string|max:255',
                'alternative_phone' => 'nullable|string|max:15',
                'location' => 'nullable|string|max:255',
                'hobbies' => 'nullable|array',
            ]);

            // Update user fields
            $user_update_status = $user->update([
                'first_name' => $request->first_name ?? $user->first_name,
                'last_name' => $request->last_name ?? $user->last_name,
                'email' => $request->email ?? $user->email,
                'phone' => $request->phone ?? $user->phone,
            ]);

            if($user_update_status) {
                // Check if the user has a student profile
                $student = Student::where('user_id', $user->id)->first();

                $data = [
                    'user_id' => $user->id, // Ensure user_id is set when creating
                    'class' => $request->class ?? optional($student)->class,
                    'group' => $request->group ?? optional($student)->group,
                    'father_name' => $request->father_name ?? optional($student)->father_name,
                    'birthdate' => $request->birthdate ?? optional($student)->birthdate,
                    'village' => $request->village ?? optional($student)->village,
                    'post_office' => $request->post_office ?? optional($student)->post_office,
                    'upazila' => $request->upazila ?? optional($student)->upazila,
                    'district' => $request->district ?? optional($student)->district,
                    'roll' => $request->roll ?? optional($student)->roll,
                    'section' => $request->section ?? optional($student)->section,
                    'gender' => $request->gender ?? optional($student)->gender,
                    'blood_grp' => $request->blood_grp ?? optional($student)->blood_grp,
                    'nationality' => $request->nationality ?? optional($student)->nationality,
                    'language' => $request->language ?? optional($student)->language,
                    'current_school' => $request->current_school ?? optional($student)->current_school,
                    'previous_school' => $request->previous_school ?? optional($student)->previous_school,
                    'father_phone' => $request->father_phone ?? optional($student)->father_phone,
                    'mother_name' => $request->mother_name ?? optional($student)->mother_name,
                    'mother_phone' => $request->mother_phone ?? optional($student)->mother_phone,
                    'local_guardian_name' => $request->local_guardian_name ?? optional($student)->local_guardian_name,
                    'local_guardian_phone' => $request->local_guardian_phone ?? optional($student)->local_guardian_phone,
                    'emergency_phone' => $request->emergency_phone ?? optional($student)->emergency_phone,
                    'current_village' => $request->current_village ?? optional($student)->current_village,
                    'current_post_office' => $request->current_post_office ?? optional($student)->current_post_office,
                    'current_district' => $request->current_district ?? optional($student)->current_district,
                    'current_upazila' => $request->current_upazila ?? optional($student)->current_upazila,
                    'alternative_phone' => $request->alternative_phone ?? optional($student)->alternative_phone,
                    'location' => $request->location ?? optional($student)->location,
                    'hobbie' => json_encode($request->hobbies ?? json_decode(optional($student)->hobbie, true)),
                ];

                if ($student) {
                    // Update existing student
                    $student_update_status = $student->update($data);
                    if($student_update_status) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Profile updated successfully!',
                            'user' => $user,
                            'student' => $student
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to update profile.'
                        ], 500);
                    }
                } else {
                    // Create new student
                    $student = Student::create($data);
                    if($student) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Profile info created successfully!',
                            'student' => $student
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to create profile info.'
                        ], 500);
                    }

                }

            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
}
