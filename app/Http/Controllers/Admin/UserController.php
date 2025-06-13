<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Role;

class UserController extends Controller
{
    public function index(){
        $users = User::with('role')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $role = Role::firstOrCreate([
            'name' => $validated['role']
        ]);
        $user->role_id = $role->id;
        $user->save();

        return $this->apiResponse(
            success: true,
            message: 'User role has been updated successfully',
            data: $user
        );
    }
}
