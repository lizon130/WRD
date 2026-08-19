<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlumniSection;
use Illuminate\Http\Request;

class AlumniBannerSectionApiController extends Controller
{
    public function AlumniBannersectionAllData()
    {
        $frontendSection = AlumniSection::all();

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
}
