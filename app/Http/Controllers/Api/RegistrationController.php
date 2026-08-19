<?php

namespace App\Http\Controllers\Api;

use Log;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; // For database transactions
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function register(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate common fields
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:user,email',
                'phone' => 'required|digits_between:10,15',
                'password' => 'required|string|min:8',
                'role' => 'required|in:8,9', // 8 for student, 9 for teacher
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Create the user in the user table
            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'role' => $request->input('role'),
                'password' => Hash::make($request->input('password')),
                'status' => 0,
            ]);

            // Assign role based on input
            if ($request->role == 8) {
                $this->registerStudent($user->id, $request);
            } elseif ($request->role == 9) {
                $this->registerTeacher($user->id, $request);
            }

            DB::commit(); // Commit the transaction
            return response()->json(['success' => true, 'message' => 'User registered successfully.', 'data' => $user], 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }

    private function registerStudent($userId, Request $request)
    {
        // Validate student-specific fields
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string|unique:students,student_id',
            'class' => 'required|string',
            'group' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new \Exception(json_encode($validator->errors()));
        }

        // Create the student record
        Student::create([
            'user_id' => $userId,
            'student_id' => $request->input('student_id'),
            'class' => $request->input('class'),
            'group' => $request->input('group'),
        ]);
    }

    private function registerTeacher($userId, Request $request)
    {
        // Validate teacher-specific fields
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|string|unique:teachers,teacher_id',
            'specialization' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new \Exception(json_encode($validator->errors()));
        }

        // Create the teacher record
        Teacher::create([
            'user_id' => $userId,
            'teacher_id' => $request->input('teacher_id'),
            'specialization' => $request->input('specialization'),
        ]);
    }
}
