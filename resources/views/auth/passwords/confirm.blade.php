@extends('layouts.login_template')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 70vh;">
    <div class="w-100" style="max-width: 450px;">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ __('Confirm Password') }}</h5>
            </div>

            <div class="card-body p-4">
                <p class="text-muted mb-4">{{ __('Please confirm your password before continuing.') }}</p>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Confirm Password') }}
                        </button>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-center">
                            <a href="{{ route('password.request') }}" class="btn btn-link btn-sm">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </div>
                    @endif
                </form>

                <hr>
                <div class="text-center">
                    <a href="{{ route('login') }}" class="btn btn-link">← {{ __('Back to Login') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
