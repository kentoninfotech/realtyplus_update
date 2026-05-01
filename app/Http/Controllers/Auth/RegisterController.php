<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\ActivateAccountNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Public business registration flow:
 *   1. Visitor fills the registration form on the landing page.
 *   2. We create a Business + owner User (status = pending).
 *   3. We send an activation email containing a token.
 *   4. On click, the user is activated and may log in.
 */
class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $plans = Plan::active()->orderBy('sort_order')->get();
        return view('auth.register', compact('plans'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'             => ['required', 'string', 'max:120'],
            'email'            => ['required', 'string', 'email', 'max:160', 'unique:users,email'],
            'phone_number'     => ['nullable', 'string', 'max:30'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
            'business_name'    => ['required', 'string', 'max:120'],
            'business_address' => ['nullable', 'string', 'max:255'],
            'plan_id'          => ['nullable', 'exists:plans,id'],
            'terms'            => ['accepted'],
        ]);

        $token = Str::random(64);

        $user = DB::transaction(function () use ($data, $token) {
            $user = User::create([
                'name'             => $data['name'],
                'email'            => $data['email'],
                'phone_number'     => $data['phone_number'] ?? null,
                'password'         => Hash::make($data['password']),
                'user_type'        => 'business_admin',
                'status'           => 'pending',
                'is_super_admin'   => false,
                'activation_token' => $token,
            ]);

            $business = Business::create([
                'user_id'       => $user->id,
                'business_name' => $data['business_name'],
                'address'       => $data['business_address'] ?? null,
                'mode'          => 'production',
            ]);

            $user->business_id = $business->id;
            $user->save();

            try {
                $user->assignRole('Business Admin');
            } catch (\Throwable $e) {
                // Role may not exist on a fresh install; ignore quietly.
            }

            // Auto-create an Owner record representing the registering company,
            // so the admin can immediately select their own company as Owner
            // when adding a new property.
            $nameParts = preg_split('/\s+/', trim($data['name']), 2);
            Owner::create([
                'business_id'  => $business->id,
                'user_id'      => $user->id,
                'first_name'   => $nameParts[0] ?? $data['name'],
                'last_name'    => $nameParts[1] ?? '',
                'company_name' => $data['business_name'],
                'email'        => $data['email'],
                'phone_number' => $data['phone_number'] ?? null,
                'address'      => $data['business_address'] ?? null,
            ]);

            if (! empty($data['plan_id'])) {
                $plan = Plan::find($data['plan_id']);
                if ($plan) {
                    Subscription::create([
                        'business_id'   => $business->id,
                        'plan_id'       => $plan->id,
                        'status'        => $plan->trial_days > 0 ? 'trial' : 'active',
                        'trial_ends_at' => $plan->trial_days > 0 ? now()->addDays($plan->trial_days) : null,
                        'starts_at'     => now(),
                    ]);
                }
            }

            return $user;
        });

        try {
            $user->notify(new ActivateAccountNotification($token, $user->business->business_name ?? ''));
        } catch (\Throwable $e) {
            return redirect()->route('register')->withErrors([
                'email' => 'Account created, but the activation email could not be sent: ' . $e->getMessage(),
            ]);
        }

        return redirect()->route('register.success')->with('email', $user->email);
    }

    public function showSuccess()
    {
        return view('auth.register-success', ['email' => session('email')]);
    }
}
