<?php

namespace App\Http\Controllers;

use App\Models\UserHistory;
use Illuminate\Http\Request;

class UserHistoryController extends Controller
{
    public function index()
    {
        $histories = UserHistory::with(['car', 'rental',])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user-history.index', compact('histories'));
    }
}
