<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AboutController extends Controller
{
    public function AboutallData()
    {
        $settings = Setting::all();

        if ($settings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No settings found',
            ], 404);
        }

        $data = $settings->map(function ($setting) {
            return [
                'key' => $setting->key,
                'value' => $setting->value,
            ];
        });

        // Return the response as JSON
        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    public function ListAboutData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $settings = Setting::query();

        if ($request->filled('identifier')) {
            $settings->where('identifier', '=',  $request->input('identifier'));
        }

        // Filter only active events (assuming active status is 1)
        $active_status = 1;
        $settings->where('status', $active_status);


        // Fetch results and order by created_at (latest first)
        $settings = $settings->orderBy('created_at', 'desc')->get();
        if ($settings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No settings found',
            ], 404);
        }

        $data = $settings->map(function ($setting) {
            return [
                'key' => $setting->key,
                'value' => $setting->value,
                'status' => $setting->status,
                'identifier' => $setting->identifier,
            ];
        });

        // Return the response as JSON
        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }
}
