<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LeadingAndGovernor;

class LeadingAndGoverningAPIController extends Controller
{
    public function list()
    {
        $teachers = LeadingAndGovernor::with('user')
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
}
