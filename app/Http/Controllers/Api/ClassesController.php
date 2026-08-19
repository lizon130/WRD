<?php

namespace App\Http\Controllers\Api;

use App\Models\ClassModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassesController extends Controller
{
    public function classAllData()
    {
        $classes = ClassModel::where('status', 1)->get();

        if ($classes->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No approved classes found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $classes
        ], 200);
    }
    public function classById($id)
{
    $class = ClassModel::where('id', $id)->where('status', 1)->first();

    if (!$class) {
        return response()->json([
            'success' => false,
            'message' => 'Class not found or not approved',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $class
    ], 200);
}

    public function ClassnameSearch(Request $request)
{
    $search = $request->query('name');

    if (!$search) {
        return response()->json([
            'status' => 'error',
            'message' => 'Search query is required!',
        ], 400);
    }

    $classes = ClassModel::where('name', 'LIKE', "%{$search}%")->get();

    if ($classes->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'No classes found!',
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'data' => $classes,
    ]);
}
}
