<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessAdmin
{
    /**
     * Allow only business admins and super admins to access business admin pages.
     * Super admins are redirected from here and don't pass through.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super admin should use superadmin routes instead
        if ($user->is_super_admin) {
            return redirect()->route('superadmin.dashboard');
        }

        // Only business admins can access these pages
        if ($user->user_type !== 'business_admin') {
            abort(403, 'Only business administrators can access this page.');
        }

        if (!$user->business_id) {
            abort(403, 'No business is associated with your account.');
        }

        // Share the current tenant for views
        view()->share('currentBusiness', $user->business);

        return $next($request);
    }
}
