@php $appName = config('app.name'); @endphp
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Check your inbox — {{ $appName }}</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<style>
body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#1e3a8a,#10b981);min-height:100vh;display:flex;align-items:center;justify-content:center;margin:0;padding:24px;}
.card{background:#fff;border-radius:18px;padding:48px 40px;text-align:center;max-width:520px;box-shadow:0 25px 60px rgba(0,0,0,.25);}
.icon{width:80px;height:80px;background:#dcfce7;color:#10b981;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.4rem;margin:0 auto 18px;}
h2{font-weight:800;}
.btn{display:inline-block;background:#2563eb;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;margin-top:20px;}
</style></head><body>
<div class="card">
    <div class="icon"><i class="fas fa-envelope-open-text"></i></div>
    <h2>Almost there!</h2>
    <p class="text-muted">We've sent an activation link to<br><strong>{{ $email ?? 'your email' }}</strong></p>
    <p>Click the link in your inbox to activate your account, then come back and log in.</p>
    <a href="{{ route('login') }}" class="btn">Back to login</a>
</div></body></html>
