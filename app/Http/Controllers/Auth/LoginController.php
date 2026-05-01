<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Standard login: only check credentials. Status is enforced in
     * authenticated() so we can return a clear message AND so super-admin
     * always passes regardless of business state.
     */
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);

        $ok = Auth::attempt($credentials, $request->filled('remember'));

        Log::info('LoginController.attemptLogin', [
            'email'    => $credentials[$this->username()] ?? null,
            'remember' => $request->filled('remember'),
            'result'   => $ok ? 'success' : 'failure',
        ]);

        return $ok;
    }

    /**
     * After credentials match, enforce activation rules. Super admin
     * bypasses all checks.
     */
    protected function authenticated(Request $request, $user)
    {
        Log::info('LoginController.authenticated', [
            'user_id'        => $user->id,
            'email'          => $user->email,
            'is_super_admin' => (bool) $user->is_super_admin,
            'status'         => $user->status,
        ]);

        if ($user->is_super_admin) {
            return redirect()->route('superadmin.dashboard');
        }

        if ($user->status !== 'active') {
            $email = $user->email;
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->flash('show_resend_activation', $email);

            throw ValidationException::withMessages([
                $this->username() => 'Your account has not been activated yet. Please check your email (including your spam or junk folder) for the activation link.',
            ]);
        }

        return redirect()->intended($this->redirectPath());
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => 'These credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
