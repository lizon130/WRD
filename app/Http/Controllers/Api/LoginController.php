<?php

namespace App\Http\Controllers\Api;

use Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Validate login fields
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Generate a JWT token for valid credentials
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                return response()->json(['success' => false, 'message' => 'Invalid email or password.'], 401);
            }

            // Get the authenticated user
            $user = auth()->user();

            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'token' => $token,
                'data' => [
                    "first_name" =>   $user->first_name,
                    "last_name" =>   $user->last_name,
                    "id" =>   $user->id,
                    "email" =>   $user->email,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Log the error internally
            Log::error('Login error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred. Please try again later.'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Revoke the token that was used to authenticate the current request
            $request->user()->currentAccessToken()->delete();

            return response()->json(['success' => true, 'message' => 'Logout successful.'], 200);
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }
}
