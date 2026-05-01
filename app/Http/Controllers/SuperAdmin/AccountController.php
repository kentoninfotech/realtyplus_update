<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $status = $request->get('status');

        $businesses = Business::with(['user', 'activeSubscription.plan'])
            ->when($q, fn($qb) => $qb->where('business_name', 'like', "%$q%"))
            ->when($status, function ($qb, $status) {
                $qb->whereHas('user', fn($u) => $u->where('status', $status));
            })
            ->latest()
            ->paginate(15);

        return view('superadmin.accounts.index', compact('businesses', 'q', 'status'));
    }

    public function show($id)
    {
        $business = Business::with(['user', 'subscriptions.plan'])->findOrFail($id);
        $personnel = User::where('business_id', $business->id)->get();
        return view('superadmin.accounts.show', compact('business', 'personnel'));
    }

    public function activate($id)
    {
        $business = Business::findOrFail($id);
        if ($business->user) {
            $business->user->update([
                'status' => 'active',
                'email_verified_at' => $business->user->email_verified_at ?: now(),
                'activation_token' => null,
            ]);
        }
        return back()->with('status', 'Account activated.');
    }

    public function suspend($id)
    {
        $business = Business::findOrFail($id);
        if ($business->user) {
            $business->user->update(['status' => 'suspended']);
        }
        return back()->with('status', 'Account suspended.');
    }

    public function destroy($id)
    {
        $business = Business::findOrFail($id);
        $business->delete();
        return redirect()->route('superadmin.accounts.index')->with('status', 'Business deleted.');
    }
}
