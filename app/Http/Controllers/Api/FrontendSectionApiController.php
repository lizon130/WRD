<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\FrontendSection;
use App\Models\StudentVideoModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FrontendSectionApiController extends Controller
{
    public function FrontsectionAllData()
    {
        $frontendSection = FrontendSection::all();

        if ($frontendSection->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No Data found',
            ], 404);
        }

        $data = $frontendSection->map(function ($section) {
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

    public function GoldenStudentVideoData()
    {
        $videos = StudentVideoModel::where('status', 1)->get(); // Fetch only approved videos

        return response()->json([
            'success' => true,
            'data' => $videos
        ], 200);
    }


    public function FrontsectionAllDataList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $settings = FrontendSection::query();

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
