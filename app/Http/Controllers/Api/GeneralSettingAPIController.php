<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;

class GeneralSettingAPIController extends Controller
{
    public function list()
    {
        $general_configuration_info = Setting::all();

        if ($general_configuration_info->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No Data found',
            ], 404);
        }

        $data = $general_configuration_info->map(function ($section) {
            return [
                'key' => $section->key,
                'value' => $section->value,
            ];
        });

        // Return the response as JSON
        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }



    public function searchFilter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $settings = Setting::query();

        if ($request->filled('key')) {
            $settings->where('key', '=',  $request->input('key'));
        }

        // Filter only active events (assuming active status is 1)
        $active_status = 1;
        $settings->where('is_active', $active_status);


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
