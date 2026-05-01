<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ActivateAccountNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivationController extends Controller
{
    public function resend(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        // Always show a generic success-style message (avoid email enumeration).
        $generic = 'If an unactivated account exists for that email, a new activation link has been sent. Please check your inbox and your spam/junk folder.';

        if (! $user || $user->status === 'active' || $user->is_super_admin) {
            return redirect()->route('login')->with('status', $generic);
        }

        if (empty($user->activation_token)) {
            $user->activation_token = Str::random(64);
            $user->save();
        }

        try {
            $user->notify(new ActivateAccountNotification(
                $user->activation_token,
                optional($user->business)->business_name ?? ''
            ));
        } catch (\Throwable $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Could not send the activation email: ' . $e->getMessage(),
            ])->withInput(['email' => $request->email]);
        }

        return redirect()->route('login')->with('status', $generic);
    }

    public function activate(Request $request, string $token)
    {
        $user = User::where('activation_token', $token)->first();

        if (! $user) {
            return view('auth.activate', [
                'success' => false,
                'message' => 'Invalid or expired activation link.',
            ]);
        }

        $user->status = 'active';
        $user->email_verified_at = now();
        $user->activation_token = null;
        $user->save();

        return view('auth.activate', [
            'success' => true,
            'message' => 'Your account is now active. You can log in.',
        ]);
    }
}
