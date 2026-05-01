@php $appName = config('app.name'); @endphp
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Account activation — {{ $appName }}</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<style>
body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#1e3a8a,#10b981);min-height:100vh;display:flex;align-items:center;justify-content:center;margin:0;padding:24px;}
.card{background:#fff;border-radius:18px;padding:48px 40px;text-align:center;max-width:520px;box-shadow:0 25px 60px rgba(0,0,0,.25);}
.icon{width:80px;height:80px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.4rem;margin:0 auto 18px;}
.icon.ok{background:#dcfce7;color:#10b981;}
.icon.err{background:#fee2e2;color:#ef4444;}
h2{font-weight:800;}
.btn{display:inline-block;background:#2563eb;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;margin-top:20px;}
</style></head><body>
<div class="card">
    @if($success)
        <div class="icon ok"><i class="fas fa-check"></i></div>
        <h2>You're activated!</h2>
        <p class="text-muted">{{ $message }}</p>
        <a href="{{ route('login') }}" class="btn">Log in to your account</a>
    @else
        <div class="icon err"><i class="fas fa-times"></i></div>
        <h2>Activation failed</h2>
        <p class="text-muted">{{ $message }}</p>
        <a href="{{ route('register') }}" class="btn">Register again</a>
    @endif
</div></body></html>
