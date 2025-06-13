<?php

namespace App\Services;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log an activity for a user
     *
     * @param int|null $userId The user ID (defaults to authenticated user)
     * @param string $type The type of activity (e.g., 'login', 'rental', 'payment')
     * @param string|null $details Additional details about the activity
     * @return Activity
     */
    public function log(?int $userId = null, string $type, ?string $details = null): Activity
    {
        // If no user ID provided, try to get it from the authenticated user
        $userId = $userId ?? Auth::id();

        // Create and return the activity log
        return Activity::create([
            'user_id' => $userId,
            'type' => $type,
            'details' => $details,
        ]);
    }

    /**
     * Log an activity for the currently authenticated user
     *
     * @param string $type The type of activity
     * @param string|null $details Additional details
     * @return Activity|null Returns null if no user is authenticated
     */
    public function logForCurrentUser(string $type, ?string $details = null): ?Activity
    {
        if (!Auth::check()) {
            return null;
        }

        return $this->log(Auth::id(), $type, $details);
    }
}
