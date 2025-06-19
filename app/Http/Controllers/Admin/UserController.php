<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
class UserController extends Controller
{
    public function index(){
        $users = User::with(['role', 'rentals'])
            ->withExists(['bans as is_banned' => function ($q) {
                $q->where(function ($q) {
                    $q->where('is_permanent', true)
                    ->orWhere('expires_at', '>', now())
                    ->orWhereNull('expires_at');
                });
            }])
            ->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'role' => 'required|in:admin,customer'
        ]);

        $role = Role::firstOrCreate([
            'name' => $validated['role']
        ]);
        $user->role_id = $role->id;
        $user->save();
        \Log::info('User role updated', [
            'user_id' => $user->id,
            'new_role' => $role->name
        ]);
        return $this->apiResponse(
            success: true,
            message: 'User role has been updated successfully',
            data: $user
        );
    }
}
