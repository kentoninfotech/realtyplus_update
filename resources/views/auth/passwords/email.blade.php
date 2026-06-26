@extends('layouts.login_template')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 70vh;">
    <div class="w-100" style="max-width: 450px;">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ __('Reset Password') }}</h5>
            </div>

            <div class="card-body p-4">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
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
