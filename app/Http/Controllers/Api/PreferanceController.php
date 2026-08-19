<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class PreferanceController extends Controller
{
    public function preferences(Request $request){
        $data = Setting::all();
        return response()->json([
            'status' => 1,
            'message' => 'Data retrive successful!',
            'data' => $data,
        ], 200);
    }
}
