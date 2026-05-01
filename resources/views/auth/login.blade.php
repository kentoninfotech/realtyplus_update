@extends('layouts.login_template')

@section('title', 'Sign in')

@section('content')
    @php($resendEmail = session('show_resend_activation'))
    <div class="top-row">
        <span>New here?&nbsp;<a href="{{ route('register') }}">Create an account</a></span>
    </div>

    <h2>Sign in</h2>
    <p class="lead">Welcome back — please enter your details.</p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>
    @endif

    @if ($resendEmail)
        <div class="alert alert-warning" role="alert">
            <strong>Account not activated.</strong>
            <div class="mt-1">Your account has not been activated yet. Please check your email for the activation link.</div>
            <div class="mt-1"><i class="fas fa-info-circle me-1"></i> Be sure to check your <strong>Spam</strong> or <strong>Junk</strong> folder — activation emails sometimes end up there.</div>
        </div>

        <form method="POST" action="{{ route('activate.resend') }}" novalidate>
            @csrf
            <input type="hidden" name="email" value="{{ $resendEmail }}">
            <button type="submit" class="btn-rp-primary">
                <i class="fas fa-paper-plane me-2"></i> Resend activation email
            </button>
        </form>

        <div class="divider">OR</div>

        <p class="text-center mb-0" style="font-size:.9rem;color:var(--rp-muted);">
            <a href="{{ route('login') }}" style="color:var(--rp-primary);font-weight:600;text-decoration:none;">Use a different account</a>
        </p>
    @else
    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input id="email"
                       type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="you@business.com"
                       required autocomplete="email" autofocus>
            </div>
            @error('email')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label for="password" class="form-label mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size:.85rem;color:var(--rp-primary);text-decoration:none;font-weight:600;">Forgot password?</a>
                @endif
            </div>
            <div class="pwd-wrap mt-1">
                <input id="password"
                       type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password"
                       placeholder="Enter your password"
                       required autocomplete="current-password">
                <button type="button" class="pwd-toggle" data-pwd-toggle="#password" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember" style="font-size:.9rem;color:#334155;">Keep me signed in for 30 days</label>
        </div>

        <button type="submit" class="btn-rp-primary">
            <i class="fas fa-sign-in-alt me-2"></i> Sign in
        </button>
    </form>

    <div class="divider">OR</div>

    <p class="text-center mb-0" style="font-size:.9rem;color:var(--rp-muted);">
        Don't have an account?
        <a href="{{ route('register') }}" style="color:var(--rp-primary);font-weight:600;text-decoration:none;">Start your free trial</a>
    </p>
    @endif
@endsection
