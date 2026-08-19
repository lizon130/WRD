<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'identifier' => 'required', // Can be email or phone
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        // Determine if the identifier is an email or phone number
        $identifier = $request->input('identifier');
        $credentials = [
            'password' => $request->input('password'),
        ];

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $identifier;
        } else {
            $credentials['phone'] = $identifier;
        }

        // Check user existence and status
        $user = User::where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->first();

        if (!$user || $user->status != 1) {
            return response()->json([
                'status' => 0,
                'errors' => [
                    'account' => ['Your account is not active yet!']
                ],
            ], 403);
        }

        // Attempt login
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => 0,
                'errors' => [
                    'credentials' => ['Invalid credentials.']
                ],
            ], 401);
        }

        // Response with token and user role
        $authUser = Auth::user()->role;
        $redirectTo = $authUser == 1 ? 'admin.index' : 'home';

        return response()->json([
            'status' => 1,
            'message' => 'Login successful!',
            'token' => $token,
            'user' => Auth::user(),
            'redirect_to' => $redirectTo,
        ], 200);
    }

    // Get authenticated user
    public function me()
    {
        try {
            // Verify and get the authenticated user
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'status' => 0,
                    'message' => 'User not found.'
                ], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Token has expired.',
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Token is invalid.',
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Token is missing.',
            ], 401);
        }

        // Return the authenticated user
        return response()->json([
            'status' => 1,
            'user' => $user,
        ], 200);
    }

    // Logout and invalidate token
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function verifyToken()
    {
        try {
            if ($user = JWTAuth::parseToken()->authenticate()) {
                $response = [
                    'status' => 1,
                    'msg' => 'Token is valid'
                ];
                return response()->json($response, 200);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Token has expired.',
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Token is invalid.',
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Token is missing.',
            ], 401);
        }
    }
}
