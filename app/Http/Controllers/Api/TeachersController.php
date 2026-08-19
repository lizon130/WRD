<?php

namespace App\Http\Controllers\Api;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TeachersController extends Controller
{
    public function list()
    {
        $teachers = Teacher::with('user')
        ->get()
        ->map(function ($teacher) {
            return [
                'first_name' => $teacher->user->first_name,
                'last_name' => $teacher->user->last_name,
                'profile_image' => $teacher->user->profile_image,
                'specialization' => $teacher->specialization,
            ];
        });
        return response()->json($teachers);
    }

    public function updateTeacherProfile(Request $request, $id)
{
    // Check Auth Token
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized user',
        ], 401);
    }

    // Validate Request
    $validator = Validator::make($request->all(), [
        'first_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

    // Find Teacher
    $teacher = Teacher::find($id);

    if (!$teacher) {
        return response()->json([
            'success' => false,
            'message' => 'Teacher not found',
        ], 404);
    }

    // Find linked User
    $linkedUser = $teacher->user;

    if (!$linkedUser) {
        return response()->json([
            'success' => false,
            'message' => 'User not found for this teacher',
        ], 404);
    }

    // Update User info
    $linkedUser->first_name = $request->first_name;
    $linkedUser->last_name = $request->last_name;
    $linkedUser->email = $request->email;
    $linkedUser->phone = $request->phone;
    $linkedUser->save();

    // Update Teacher info
    $teacher->gender = $request->gender ?? $teacher->gender;
    $teacher->birthdate = $request->birthdate ?? $teacher->birthdate;
    $teacher->department = $request->department ?? $teacher->department;
    $teacher->designation = $request->designation ?? $teacher->designation;
    $teacher->specialization = $request->specialization ?? $teacher->specialization;
    $teacher->facebook_link = $request->facebook_link ?? $teacher->facebook_link;
    $teacher->linkedin_link = $request->linkedin_link ?? $teacher->linkedin_link;
    $teacher->organization = $request->organization ?? $teacher->organization;
    $teacher->bio = $request->bio ?? $teacher->bio;
    $teacher->status = $request->status ? 1 : 0;

    // Handle array fields and encode them
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

    if ($request->has('fromdate')) {
        $teacher->fromdate = is_array($request->fromdate) ? json_encode($request->fromdate) : json_encode([$request->fromdate]);
    }

    if ($request->has('todate')) {
        $teacher->todate = is_array($request->todate) ? json_encode($request->todate) : json_encode([$request->todate]);
    }

    // Currently Working (single checkbox)
    $teacher->currently_working = $request->currentworking_status ?? 0;

    // Save Teacher
    $teacher->save();

    return response()->json([
        'success' => true,
        'message' => 'Teacher profile updated successfully.',
        'data' => [
            'teacher' => $teacher,
            'user' => $linkedUser,
        ],
    ], 200);
}

}
