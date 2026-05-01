@php $appName = config('app.name'); @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sign in') — {{ $appName }}</title>
    @if(!empty($appFavicon) && file_exists(public_path($appFavicon)))
        <link rel="icon" type="image/x-icon" href="{{ asset($appFavicon) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

    <style>
        :root {
            --rp-primary: #2563eb;
            --rp-primary-dark: #1d4ed8;
            --rp-accent: #10b981;
            --rp-dark: #0f172a;
            --rp-muted: #64748b;
        }
        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--rp-dark);
            margin: 0;
            background: #f8fafc;
        }

        .auth-shell {
            display: flex;
            min-height: 100vh;
        }

        /* LEFT — branding / tips panel */
        .auth-side {
            flex: 1.1;
            position: relative;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 55%, #10b981 100%);
            color: #fff;
            padding: 56px 56px 40px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .auth-side::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(circle at 15% 20%, rgba(255,255,255,.18), transparent 45%),
                radial-gradient(circle at 85% 80%, rgba(16,185,129,.35), transparent 50%);
            pointer-events: none;
        }
        .auth-side > * { position: relative; z-index: 1; }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-size: 1.35rem;
            color: #fff;
            text-decoration: none;
        }
        .brand .brand-mark {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: rgba(255,255,255,.18);
            display: inline-flex; align-items: center; justify-content: center;
            backdrop-filter: blur(6px);
        }

        .auth-headline {
            margin-top: 60px;
            max-width: 460px;
        }
        .auth-headline h1 {
            font-size: clamp(1.8rem, 2.6vw, 2.4rem);
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 14px;
        }
        .auth-headline p {
            color: rgba(255,255,255,.85);
            font-size: 1.05rem;
            line-height: 1.6;
        }

        .tips {
            list-style: none;
            padding: 0;
            margin: 36px 0 0;
            display: grid;
            gap: 18px;
            max-width: 460px;
        }
        .tips li {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }
        .tip-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: rgba(255,255,255,.15);
            display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            color: #fff;
        }
        .tip-text strong { display: block; font-weight: 600; margin-bottom: 2px; }
        .tip-text span { color: rgba(255,255,255,.78); font-size: .9rem; }

        .auth-quote {
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.14);
            backdrop-filter: blur(8px);
            border-radius: 14px;
            padding: 20px 22px;
            max-width: 460px;
        }
        .auth-quote p { font-style: italic; margin: 0 0 10px; color: #fff; }
        .auth-quote .who { font-size: .85rem; color: rgba(255,255,255,.78); }

        .auth-foot { font-size: .82rem; color: rgba(255,255,255,.65); }

        /* RIGHT — form panel */
        .auth-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
            background: #fff;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
        }
        .auth-card .top-row {
            display: flex; justify-content: flex-end;
            font-size: .9rem; color: var(--rp-muted);
            margin-bottom: 24px;
        }
        .auth-card .top-row a { color: var(--rp-primary); font-weight: 600; text-decoration: none; }
        .auth-card h2 { font-weight: 800; font-size: 1.7rem; margin-bottom: 6px; }
        .auth-card .lead { color: var(--rp-muted); margin-bottom: 28px; }

        .form-label { font-weight: 600; font-size: .85rem; color: #334155; }
        .form-control { padding: .7rem .9rem; border-radius: 10px; border: 1px solid #e2e8f0; }
        .form-control:focus { border-color: var(--rp-primary); box-shadow: 0 0 0 3px rgba(37,99,235,.12); }
        .input-group-text { background: #f1f5f9; border: 1px solid #e2e8f0; border-right: 0; border-radius: 10px 0 0 10px; color: var(--rp-muted); }
        .input-group .form-control { border-left: 0; border-radius: 0 10px 10px 0; }

        .btn-rp-primary {
            display: block;
            width: 100%;
            padding: .8rem 1rem;
            background: var(--rp-primary);
            color: #fff;
            font-weight: 600;
            border: 0;
            border-radius: 10px;
            transition: all .2s;
        }
        .btn-rp-primary:hover { background: var(--rp-primary-dark); transform: translateY(-1px); }

        .divider { display: flex; align-items: center; gap: 12px; margin: 20px 0; color: var(--rp-muted); font-size: .8rem; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

        .pwd-wrap { position: relative; }
        .pwd-toggle {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: 0; color: var(--rp-muted); cursor: pointer;
        }

        .invalid-feedback { display: block; }

        @media (max-width: 991px) {
            .auth-shell { flex-direction: column; }
            .auth-side { padding: 36px 28px; min-height: auto; }
            .auth-headline { margin-top: 30px; }
            .tips { grid-template-columns: 1fr; }
        }
        @media (max-width: 575px) {
            .auth-headline h1 { font-size: 1.5rem; }
            .auth-main { padding: 28px 18px; }
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="auth-shell">

    {{-- LEFT: branding + tips --}}
    <aside class="auth-side">
        <a href="/" class="brand">
            <span class="brand-mark"><i class="fas fa-building"></i></span>
            <span>{{ $appName }}</span>
        </a>

        <div>
            <div class="auth-headline">
                <h1>Welcome back. Let's get you back to growing your portfolio.</h1>
                <p>Manage properties, tenants, leases, leads and finances — all from one professional, multi-business workspace.</p>
            </div>

            <ul class="tips">
                <li>
                    <span class="tip-icon"><i class="fas fa-shield-alt"></i></span>
                    <span class="tip-text">
                        <strong>Bank-grade security</strong>
                        <span>Encrypted sessions and per-business data isolation.</span>
                    </span>
                </li>
                <li>
                    <span class="tip-icon"><i class="fas fa-bolt"></i></span>
                    <span class="tip-text">
                        <strong>Lightning-fast workflow</strong>
                        <span>From lead to lease in a few clicks — no spreadsheets.</span>
                    </span>
                </li>
                <li>
                    <span class="tip-icon"><i class="fas fa-headset"></i></span>
                    <span class="tip-text">
                        <strong>Real human support</strong>
                        <span>Reach our team any business day — we're here to help.</span>
                    </span>
                </li>
            </ul>

            <div class="auth-quote mt-4">
                <p>"{{ $appName }} replaced three different tools for our agency. Onboarding took less than a day."</p>
                <div class="who">— Adaeze N., Lagos</div>
            </div>
        </div>

        <div class="auth-foot">&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</div>
    </aside>

    {{-- RIGHT: form slot --}}
    <main class="auth-main">
        <div class="auth-card">
            @yield('content')
        </div>
    </main>
</div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script>
    document.querySelectorAll('[data-pwd-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.querySelector(btn.getAttribute('data-pwd-toggle'));
            if (!input) return;
            const isPwd = input.type === 'password';
            input.type = isPwd ? 'text' : 'password';
            btn.querySelector('i').className = isPwd ? 'fas fa-eye-slash' : 'fas fa-eye';
        });
    });
</script>
@stack('scripts')
</body>
</html>
