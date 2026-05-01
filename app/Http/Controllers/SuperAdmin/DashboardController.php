<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Feedback;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'businesses' => Business::count(),
            'users'      => User::count(),
            'plans'      => Plan::count(),
            'feedback'   => Feedback::where('status', 'new')->count(),
            'subscriptions_active' => Subscription::whereIn('status', ['active', 'trial'])->count(),
        ];

        $latestBusinesses = Business::with('user')->latest()->take(8)->get();
        $latestFeedback   = Feedback::latest()->take(8)->get();

        return view('superadmin.dashboard', compact('stats', 'latestBusinesses', 'latestFeedback'));
    }
}
