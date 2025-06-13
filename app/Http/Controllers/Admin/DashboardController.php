<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rental;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function stats()
    {
        $today = Carbon::today();

        $stats = [
            'totalUsers' => User::count(),
            'newUsers' => User::whereDate('created_at', $today)->count(),
            'activeSessions' => DB::table('sessions')
                ->where('last_activity', '>=', now()->subMinutes(5))
                ->count(),
            'recentLogins' => Activity::where('type', 'login')
                ->whereDate('created_at', $today)
                ->count()
        ];

        return response()->json($stats);
    }

    /**
     * Get paginated activities
     */
    public function activities(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $filter = $request->input('filter', 'all');

        $query = Activity::with('user')
            ->latest();

        // Apply filter if not 'all'
        if ($filter !== 'all') {
            $query->where('type', $filter);
        }

        $activities = $query->paginate($perPage);

        return response()->json([
            'data' => $activities->items(),
            'total' => $activities->total(),
            'current_page' => $activities->currentPage(),
            'last_page' => $activities->lastPage()
        ]);
    }
}
