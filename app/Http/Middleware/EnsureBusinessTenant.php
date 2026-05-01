<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureBusinessTenant
{
    /**
     * Block users that have not been activated, or that lack a business
     * (unless they are super admin), from accessing tenant pages.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->is_super_admin) {
            return redirect()->route('superadmin.dashboard');
        }

        if ($user->status !== 'active') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Your account is not active. Please check your email for the activation link.',
            ]);
        }

        if (! $user->business_id) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'No business is associated with your account.',
            ]);
        }

        // Share the current tenant for views.
        view()->share('currentBusiness', $user->business);

        return $next($request);
    }
}
