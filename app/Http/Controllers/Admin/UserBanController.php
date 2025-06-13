<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBan;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserBanController extends Controller
{
    use ApiResponse;

    /**
     * Display a list of banned users
     */
    public function index()
    {
        return view('admin.users.bans.index');
    }

    public function banned()
    {
        $bannedUsers = UserBan::with(['user', 'bannedBy'])
            ->latest()
            ->paginate(10);

        return $this->apiResponse(
            success: true,
            message: 'Banned users fetched successfully',
            data: $bannedUsers
        );
    }

    /**
     * Show the form for banning a user
     */


    /**
     * Ban a user
     */
    public function ban(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'is_permanent' => 'boolean',
            'expires_at' => [
                'nullable',
                'date',
                'after:now',
                Rule::requiredIf(function () use ($request) {
                    return !$request->boolean('is_permanent');
                })
            ]
        ]);

        // Check if user is already banned
        if ($user->activeBan) {
            return $this->apiResponse(
                success: false,
                message: 'User is already banned'
            );
        }

        try {
            DB::beginTransaction();

            $ban = UserBan::create([
                'user_id' => $user->id,
                'banned_by' => auth()->id(),
                'reason' => $request->reason,
                'is_permanent' => $request->boolean('is_permanent'),
                'expires_at' => $request->expires_at
            ]);

            DB::commit();

            return $this->apiResponse(
                success: true,
                message: 'User has been banned successfully',
                data: $ban->load(['user', 'bannedBy'])
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(
                success: false,
                message: 'Failed to ban user: ' . $e->getMessage()
            );
        }
    }

    /**
     * Show the form for editing a ban
     */


    /**
     * Update a ban
     */
    public function update(Request $request, UserBan $ban)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'is_permanent' => 'boolean',
            'expires_at' => [
                'nullable',
                'date',
                'after:now',
                Rule::requiredIf(function () use ($request) {
                    return !$request->boolean('is_permanent');
                })
            ]
        ]);

        try {
            DB::beginTransaction();

            $ban->update([
                'reason' => $request->reason,
                'is_permanent' => $request->boolean('is_permanent'),
                'expires_at' => $request->expires_at
            ]);

            DB::commit();

            return $this->apiResponse(
                success: true,
                message: 'Ban has been updated successfully',
                data: $ban->load(['user', 'bannedBy'])
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(
                success: false,
                message: 'Failed to update ban: ' . $e->getMessage()
            );
        }
    }

    /**
     * Remove a ban
     */
    public function unban(User $user)
    {
        try {
            DB::beginTransaction();

            $user->activeBan()->delete();

            DB::commit();

            return $this->apiResponse(
                success: true,
                message: 'Ban has been removed successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse(
                success: false,
                message: 'Failed to remove ban: ' . $e->getMessage()
            );
        }
    }
}
