<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Right;
use App\Models\Role;
use App\Models\RoleRight;
use Illuminate\Http\Request;

class RoleRightController extends Controller
{
    public function list(){
        $roles = Role::all();
        $rights = Right::all();
        $permissions = RoleRight::all();
        return response()->json([
            'status' => 1,
            'message' => 'Data retrive successful!',
            'roles' => $roles,
            'rights' => $rights,
            'permissions' => $permissions
        ], 200);
    }
}
