@php
    $appName = config('app.name');
    $u = auth()->user();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Super Admin') — {{ $appName }}</title>
    @if(!empty($appFavicon) && file_exists(public_path($appFavicon)))
        <link rel="icon" type="image/x-icon" href="{{ asset($appFavicon) }}">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <style>
        body { font-family:'Inter',sans-serif; background:#f1f5f9; margin:0; }
        .sa-shell { display: flex; min-height: 100vh; }
        .sa-side { width: 240px; background:#0f172a; color:#cbd5e1; padding: 24px 0; flex-shrink: 0; }
        .sa-side .brand { color:#fff; font-weight:800; font-size: 1.2rem; padding: 0 24px 24px; display:block; text-decoration:none; border-bottom:1px solid #1e293b; }
        .sa-side a.nav-link { color:#cbd5e1; padding: 12px 24px; display:block; border-left: 3px solid transparent; }
        .sa-side a.nav-link:hover, .sa-side a.nav-link.active { background:#1e293b; color:#fff; border-left-color:#2563eb; }
        .sa-side a.nav-link i { width: 22px; }
        .sa-main { flex: 1; padding: 28px 32px; }
        .sa-top { display:flex;justify-content:space-between;align-items:center;margin-bottom:24px; }
        .sa-top h1 { font-weight:800; font-size:1.6rem; margin:0; }
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(15,23,42,.08); }
        .stat-card { padding: 20px; border-radius:12px; background:#fff; }
        .stat-num { font-size: 2rem; font-weight:800; color:#0f172a; }
        .stat-label { color:#64748b; font-size:.85rem; text-transform: uppercase; letter-spacing:.5px; }
        .table th { font-weight: 600; font-size:.85rem; color:#64748b; text-transform:uppercase; letter-spacing:.4px; }
        .badge-status-active { background:#dcfce7; color:#16a34a; }
        .badge-status-pending { background:#fef3c7; color:#d97706; }
        .badge-status-suspended { background:#fee2e2; color:#dc2626; }
        .badge-status-new { background:#dbeafe; color:#2563eb; }
    </style>
    @stack('styles')
</head>
<body>
<div class="sa-shell">
    <aside class="sa-side">
        <a class="brand" href="{{ route('superadmin.dashboard') }}">
            @if(!empty($appLogo) && file_exists(public_path($appLogo)))
                <img src="{{ asset($appLogo) }}" alt="{{ $appName }}" style="max-height:32px; vertical-align:middle; margin-right:8px;">
            @else
                <i class="fas fa-shield-alt me-2"></i>
            @endif
            {{ $appName }}
        </a>
        <a class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a>
        <a class="nav-link {{ request()->routeIs('superadmin.accounts.*') ? 'active' : '' }}" href="{{ route('superadmin.accounts.index') }}"><i class="fas fa-building"></i> Businesses</a>
        <a class="nav-link {{ request()->routeIs('superadmin.plans.*') ? 'active' : '' }}" href="{{ route('superadmin.plans.index') }}"><i class="fas fa-tags"></i> Plans</a>
        <a class="nav-link {{ request()->routeIs('superadmin.landing.*') ? 'active' : '' }}" href="{{ route('superadmin.landing.index') }}"><i class="fas fa-paint-brush"></i> Landing CMS</a>
        <a class="nav-link {{ request()->routeIs('superadmin.feedback.*') ? 'active' : '' }}" href="{{ route('superadmin.feedback.index') }}"><i class="fas fa-comments"></i> Feedback</a>
        <a class="nav-link {{ request()->routeIs('superadmin.app-settings.*') ? 'active' : '' }}" href="{{ route('superadmin.app-settings.edit') }}"><i class="fas fa-image"></i> App Branding</a>
        <a class="nav-link" href="/" target="_blank"><i class="fas fa-external-link-alt"></i> View site</a>
        <form action="{{ url('logout') }}" method="GET" class="px-4 mt-4">
            <button type="submit" class="btn btn-sm btn-outline-light w-100"><i class="fas fa-sign-out-alt"></i> Sign out</button>
        </form>
    </aside>
    <main class="sa-main">
        <div class="sa-top">
            <h1>@yield('title','Dashboard')</h1>
            <span class="text-muted">Signed in as <strong>{{ $u->name }}</strong></span>
        </div>
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
    </main>
</div>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')
</body>
</html>
