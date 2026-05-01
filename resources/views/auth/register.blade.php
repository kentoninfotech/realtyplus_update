@php $appName = config('app.name'); @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create your business — {{ $appName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <style>
        body { font-family:'Inter',sans-serif; background: linear-gradient(135deg,#1e3a8a,#2563eb,#10b981); min-height:100vh; padding:40px 0; margin:0; }
        .reg-card { max-width: 760px; margin: 0 auto; background:#fff; border-radius: 18px; box-shadow: 0 25px 60px rgba(0,0,0,.25); overflow:hidden; }
        .reg-head { background: #0f172a; color:#fff; padding: 28px 36px; }
        .reg-body { padding: 36px; }
        .brand { color:#fff; text-decoration:none; font-weight:800; font-size:1.4rem; }
        .form-label { font-weight: 600; font-size: .9rem; }
        .plan-pick { border:2px solid #e2e8f0; border-radius:12px; padding:14px; cursor:pointer; transition:all .2s; height:100%; }
        .plan-pick.active, .plan-pick:hover { border-color:#2563eb; background:#eff6ff; }
        .plan-pick h6 { margin-bottom: 4px; font-weight: 700; }
        .plan-pick small { color:#64748b; }
    </style>
</head>
<body>
<div class="reg-card">
    <div class="reg-head d-flex justify-content-between align-items-center">
        <a href="/" class="brand"><i class="fas fa-building me-2"></i>{{ $appName }}</a>
        <a href="{{ route('login') }}" style="color:#cbd5e1;text-decoration:none;font-size:.9rem;">Already have an account? <strong style="color:#fff;">Log in</strong></a>
    </div>
    <div class="reg-body">
        <h2 class="fw-bold mb-1">Create your business account</h2>
        <p class="text-muted mb-4">Free for 14 days. No credit card required.</p>

        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf
            <h5 class="fw-bold mt-2 mb-3">About you</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full name</label>
                    <input class="form-control" name="name" required value="{{ old('name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input class="form-control" name="phone_number" value="{{ old('phone_number') }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required value="{{ old('email') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirm password</label>
                    <input type="password" class="form-control" name="password_confirmation" required>
                </div>
            </div>

            <h5 class="fw-bold mt-4 mb-3">About your business</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Business name</label>
                    <input class="form-control" name="business_name" required value="{{ old('business_name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Business address</label>
                    <input class="form-control" name="business_address" value="{{ old('business_address') }}">
                </div>
            </div>

            @if($plans->count())
                <h5 class="fw-bold mt-4 mb-3">Choose a plan</h5>
                <div class="row g-3">
                    @foreach($plans as $plan)
                        @php
                            $checked = (old('plan_id', request('plan')) == $plan->id) || ($loop->first && !old('plan_id') && !request('plan'));
                        @endphp
                        <div class="col-md-4">
                            <label class="plan-pick d-block {{ $checked ? 'active' : '' }}">
                                <input type="radio" name="plan_id" value="{{ $plan->id }}" class="d-none" {{ $checked ? 'checked' : '' }}>
                                <h6>{{ $plan->name }}</h6>
                                <div class="fw-bold" style="font-size:1.2rem;color:#2563eb;">
                                    {{ $plan->currency }} {{ number_format($plan->price, 0) }}<small class="text-muted">/{{ $plan->billing_cycle }}</small>
                                </div>
                                <small>{{ $plan->trial_days }}-day free trial</small>
                            </label>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="form-check mt-4">
                <input type="checkbox" name="terms" id="terms" class="form-check-input" required {{ old('terms') ? 'checked' : '' }}>
                <label for="terms" class="form-check-label">I agree to the Terms of Service and Privacy Policy.</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-4 py-2 fw-bold" style="background:#2563eb;border:none;border-radius:8px;">Create my account</button>
        </form>
    </div>
</div>
<script>
    document.querySelectorAll('.plan-pick input').forEach(r => {
        r.addEventListener('change', () => {
            document.querySelectorAll('.plan-pick').forEach(p => p.classList.remove('active'));
            r.closest('.plan-pick').classList.add('active');
        });
    });
</script>
</body>
</html>
