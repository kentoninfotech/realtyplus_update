@php
    $appName = $settings['site_title'] ?? config('app.name');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $appName }} — {{ $settings['site_tagline'] ?? 'Property management, simplified.' }}</title>

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
            --rp-bg: #f8fafc;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--rp-dark);
            background: #fff;
            line-height: 1.6;
            margin: 0;
        }
        .navbar-rp {
            position: sticky; top: 0; z-index: 100;
            background: rgba(255,255,255,.92); backdrop-filter: blur(10px);
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 0;
        }
        .navbar-rp .container { display:flex; align-items:center; justify-content:space-between; }
        .brand { font-weight: 800; font-size: 1.4rem; color: var(--rp-primary); text-decoration:none; }
        .brand i { margin-right: 8px; }
        .nav-links a { color: var(--rp-dark); text-decoration:none; margin: 0 14px; font-weight: 500; }
        .nav-links a:hover { color: var(--rp-primary); }
        .btn-rp { display:inline-block; padding: 10px 22px; border-radius: 8px; font-weight: 600; text-decoration:none; transition: all .2s; border: none; cursor:pointer; }
        .btn-primary-rp { background: var(--rp-primary); color: #fff !important; }
        .btn-primary-rp:hover { background: var(--rp-primary-dark); transform: translateY(-1px); }
        .btn-outline-rp { background: transparent; color: var(--rp-primary) !important; border: 2px solid var(--rp-primary); }
        .btn-outline-rp:hover { background: var(--rp-primary); color: #fff !important; }
        .btn-light-rp { background: #fff; color: var(--rp-primary) !important; }

        /* Hero */
        .hero {
            position: relative;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #10b981 100%);
            color: #fff;
            overflow: hidden;
        }
        .hero::before { content:''; position:absolute; inset:0; background: radial-gradient(circle at 20% 30%, rgba(255,255,255,.12), transparent 50%); pointer-events:none; }
        .carousel-item .hero-content {
            min-height: 560px;
            display: flex; align-items: center;
            padding: 80px 0;
        }
        .hero h1 { font-size: clamp(2rem, 4.5vw, 3.6rem); font-weight: 800; line-height: 1.1; margin-bottom: 1rem; }
        .hero p.lead { font-size: 1.25rem; opacity: .95; margin-bottom: 2rem; max-width: 640px; }
        .hero .cta-row { display:flex; gap: 12px; flex-wrap: wrap; }
        .carousel-indicators [data-bs-target] { background-color: rgba(255,255,255,.7); }

        /* Sections */
        section { padding: 80px 0; }
        .section-bg { background: var(--rp-bg); }
        .section-eyebrow { color: var(--rp-primary); font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: .85rem; }
        .section-title { font-size: clamp(1.8rem, 3vw, 2.6rem); font-weight: 800; margin: .5rem 0 1rem; }
        .section-sub { color: var(--rp-muted); font-size: 1.1rem; max-width: 720px; margin: 0 auto 3rem; }

        /* Stats strip */
        .stats-strip { background: var(--rp-dark); color:#fff; padding: 40px 0; }
        .stat-item { text-align: center; }
        .stat-num { font-size: 2.4rem; font-weight: 800; color: var(--rp-accent); }
        .stat-label { color: #cbd5e1; font-size: .95rem; }

        /* Features */
        .feature-card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
            padding: 32px 28px; height: 100%; transition: all .25s;
        }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(15,23,42,.08); border-color: transparent; }
        .feature-icon {
            width: 56px; height: 56px; border-radius: 14px;
            display:flex; align-items:center; justify-content:center;
            background: linear-gradient(135deg, var(--rp-primary), var(--rp-accent));
            color: #fff; font-size: 1.4rem; margin-bottom: 18px;
        }

        /* Plans */
        .plan-card {
            background:#fff; border:1px solid #e2e8f0; border-radius: 18px;
            padding: 36px 28px; height: 100%; position: relative; transition: all .25s;
        }
        .plan-card.featured {
            border: 2px solid var(--rp-primary);
            box-shadow: 0 25px 50px rgba(37,99,235,.18);
            transform: scale(1.03);
        }
        .plan-badge {
            position:absolute; top: -14px; left: 50%; transform: translateX(-50%);
            background: var(--rp-primary); color:#fff; padding: 4px 14px; border-radius: 999px;
            font-size: .75rem; font-weight: 700; letter-spacing: .5px;
        }
        .plan-name { font-size: 1.1rem; font-weight: 700; color: var(--rp-muted); }
        .plan-price { font-size: 2.6rem; font-weight: 800; margin: 8px 0; }
        .plan-price small { font-size: 1rem; color: var(--rp-muted); font-weight: 500; }
        .plan-features { list-style: none; padding: 0; margin: 24px 0; }
        .plan-features li { padding: 8px 0; color: #334155; }
        .plan-features li i { color: var(--rp-accent); margin-right: 10px; }

        /* Testimonials */
        .testimonial-card {
            background:#fff; border-radius: 16px; padding: 28px;
            border: 1px solid #e2e8f0; height: 100%;
        }
        .testimonial-card p { font-style: italic; color: #334155; }
        .testimonial-author { font-weight: 700; }
        .testimonial-role { color: var(--rp-muted); font-size: .9rem; }

        /* CTA */
        .cta-banner {
            background: linear-gradient(135deg, var(--rp-primary), var(--rp-accent));
            color: #fff; border-radius: 24px; padding: 60px 40px; text-align: center;
        }
        .cta-banner h2 { font-size: 2rem; font-weight: 800; }

        /* FAQ */
        .accordion-rp .accordion-button { font-weight: 600; }

        /* Contact / Footer */
        footer { background: var(--rp-dark); color: #cbd5e1; padding: 60px 0 24px; }
        footer h5 { color: #fff; margin-bottom: 16px; font-weight: 700; }
        footer a { color: #cbd5e1; text-decoration: none; }
        footer a:hover { color: #fff; }
        .footer-bottom { border-top: 1px solid #1e293b; padding-top: 20px; margin-top: 40px; text-align: center; font-size: .9rem; }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .plan-card.featured { transform: none; }
        }
    </style>
</head>
<body>

{{-- NAV --}}
<nav class="navbar-rp">
    <div class="container">
        <a href="/" class="brand">
            @if(!empty($appLogo) && file_exists(public_path($appLogo)))
                <img src="{{ asset($appLogo) }}" alt="{{ $appName }}" style="height:36px; vertical-align:middle; margin-right:8px;">
            @else
                <i class="fas fa-building"></i>
            @endif
            {{ $appName }}
        </a>
        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="#plans">Plans</a>
            <a href="#testimonials">Testimonials</a>
            <a href="#faq">FAQ</a>
            <a href="#contact">Contact</a>
        </div>
        <div>
            @auth
                <a href="{{ url('/home') }}" class="btn-rp btn-primary-rp">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-rp btn-outline-rp" style="margin-right:8px;">Log in</a>
                <a href="{{ route('register') }}" class="btn-rp btn-primary-rp">Get Started</a>
            @endauth
        </div>
    </div>
</nav>

{{-- HERO CAROUSEL --}}
<header class="hero">
    @php
        $slides = $heroSlides->count() ? $heroSlides : collect([
            (object)['title' => 'Property management, simplified.', 'subtitle' => 'Run your real-estate business from a single, modern dashboard.', 'cta_label' => 'Start Free Trial', 'cta_url' => route('register'), 'image' => null],
        ]);
    @endphp
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
        <div class="carousel-indicators">
            @foreach($slides as $i => $s)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i==0?'active':'' }}" aria-label="Slide {{ $i+1 }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach($slides as $i => $s)
                <div class="carousel-item {{ $i==0?'active':'' }}">
                    <div class="hero-content">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-7">
                                    <h1>{{ $s->title }}</h1>
                                    <p class="lead">{{ $s->subtitle }}</p>
                                    <div class="cta-row">
                                        @if(!empty($s->cta_label))
                                            <a href="{{ $s->cta_url ?: route('register') }}" class="btn-rp btn-light-rp">{{ $s->cta_label }} <i class="fas fa-arrow-right ms-1"></i></a>
                                        @else
                                            <a href="{{ route('register') }}" class="btn-rp btn-light-rp">Start Free Trial <i class="fas fa-arrow-right ms-1"></i></a>
                                        @endif
                                        <a href="#features" class="btn-rp btn-outline-rp" style="border-color:#fff;color:#fff !important;">Learn more</a>
                                    </div>
                                </div>
                                <div class="col-lg-5 d-none d-lg-block text-center">
                                    <i class="fas fa-city" style="font-size: 14rem; opacity: .25;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
    </div>
</header>

{{-- STATS --}}
@if($stats->count())
<section class="stats-strip" style="padding:48px 0;">
    <div class="container">
        <div class="row">
            @foreach($stats as $stat)
                <div class="col-6 col-md-3 stat-item my-2">
                    <div class="stat-num">{{ $stat->title }}</div>
                    <div class="stat-label">{{ $stat->subtitle }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FEATURES --}}
<section id="features">
    <div class="container">
        <div class="text-center">
            <span class="section-eyebrow">Everything you need</span>
            <h2 class="section-title">Built for modern property businesses</h2>
            <p class="section-sub">From your first listing to a full multi-branch portfolio — we grow with you.</p>
        </div>
        <div class="row g-4">
            @forelse($features as $feature)
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fas {{ $feature->icon ?: 'fa-check' }}"></i></div>
                        <h4 class="fw-bold">{{ $feature->title }}</h4>
                        <p class="text-muted mb-0">{{ $feature->body }}</p>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">No features configured yet.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- PLANS --}}
<section id="plans" class="section-bg">
    <div class="container">
        <div class="text-center">
            <span class="section-eyebrow">Pricing</span>
            <h2 class="section-title">Simple, transparent plans</h2>
            <p class="section-sub">Start free. Upgrade when you need more. Cancel anytime.</p>
        </div>
        <div class="row g-4 justify-content-center">
            @forelse($plans as $plan)
                <div class="col-md-6 col-lg-4">
                    <div class="plan-card {{ $plan->is_featured ? 'featured' : '' }}">
                        @if($plan->is_featured)<span class="plan-badge">MOST POPULAR</span>@endif
                        <div class="plan-name">{{ $plan->name }}</div>
                        <div class="plan-price">
                            {{ $plan->currency }} {{ number_format($plan->price, 0) }}
                            <small>/ {{ $plan->billing_cycle }}</small>
                        </div>
                        <p class="text-muted">{{ $plan->description }}</p>
                        <ul class="plan-features">
                            @foreach(($plan->features ?? []) as $feat)
                                <li><i class="fas fa-check-circle"></i>{{ $feat }}</li>
                            @endforeach
                            @if($plan->trial_days)
                                <li><i class="fas fa-gift"></i>{{ $plan->trial_days }} day free trial</li>
                            @endif
                        </ul>
                        <a href="{{ route('register', ['plan' => $plan->id]) }}" class="btn-rp {{ $plan->is_featured ? 'btn-primary-rp' : 'btn-outline-rp' }} w-100 text-center" style="display:block;">Choose {{ $plan->name }}</a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">No plans configured yet.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- TESTIMONIALS --}}
@if($testimonials->count())
<section id="testimonials">
    <div class="container">
        <div class="text-center">
            <span class="section-eyebrow">Loved by teams</span>
            <h2 class="section-title">What our customers say</h2>
        </div>
        <div class="row g-4">
            @foreach($testimonials as $t)
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="mb-3" style="color:#f59e0b;">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p>"{{ $t->body }}"</p>
                        <div class="testimonial-author mt-3">{{ $t->title }}</div>
                        <div class="testimonial-role">{{ $t->subtitle }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FAQ --}}
@if($faqs->count())
<section id="faq" class="section-bg">
    <div class="container" style="max-width: 820px;">
        <div class="text-center">
            <span class="section-eyebrow">FAQ</span>
            <h2 class="section-title">Questions? We have answers.</h2>
        </div>
        <div class="accordion accordion-rp" id="faqAccordion">
            @foreach($faqs as $i => $faq)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button {{ $i!=0?'collapsed':'' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq-{{ $faq->id }}">
                            {{ $faq->title }}
                        </button>
                    </h2>
                    <div id="faq-{{ $faq->id }}" class="accordion-collapse collapse {{ $i==0?'show':'' }}" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">{{ $faq->body }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section>
    <div class="container">
        <div class="cta-banner">
            <h2>Ready to take control of your portfolio?</h2>
            <p class="mb-4">Sign up in under a minute. Free 14-day trial — no credit card required.</p>
            <a href="{{ route('register') }}" class="btn-rp btn-light-rp" style="font-size:1.1rem;">Start your free trial <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</section>

{{-- CONTACT / FEEDBACK --}}
<section id="contact" class="section-bg">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <span class="section-eyebrow">Get in touch</span>
                <h2 class="section-title">We'd love to hear from you</h2>
                <p class="text-muted">Questions, feedback or feature requests? Drop us a note and our team will get back within one business day.</p>
                <div class="mt-4">
                    <p><i class="fas fa-envelope text-primary me-2"></i>{{ $settings['contact_email'] }}</p>
                    <p><i class="fas fa-phone text-primary me-2"></i>{{ $settings['contact_phone'] }}</p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="feature-card">
                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div>
                    @endif
                    <form method="POST" action="{{ route('landing.feedback') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input class="form-control" name="name" required value="{{ old('name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required value="{{ old('email') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone (optional)</label>
                                <input class="form-control" name="phone" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category">
                                    <option value="general">General</option>
                                    <option value="feature">Feature request</option>
                                    <option value="bug">Bug report</option>
                                    <option value="billing">Billing</option>
                                    <option value="complaint">Complaint</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Subject</label>
                                <input class="form-control" name="subject" value="{{ old('subject') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message</label>
                                <textarea class="form-control" rows="5" name="message" required>{{ old('message') }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn-rp btn-primary-rp">Send message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5><i class="fas fa-building me-2"></i>{{ $appName }}</h5>
                <p>{{ $settings['site_tagline'] }}</p>
            </div>
            <div class="col-md-2">
                <h5>Product</h5>
                <a href="#features" class="d-block py-1">Features</a>
                <a href="#plans" class="d-block py-1">Plans</a>
                <a href="#faq" class="d-block py-1">FAQ</a>
            </div>
            <div class="col-md-2">
                <h5>Company</h5>
                <a href="#contact" class="d-block py-1">Contact</a>
                <a href="{{ route('register') }}" class="d-block py-1">Sign up</a>
                <a href="{{ route('login') }}" class="d-block py-1">Log in</a>
            </div>
            <div class="col-md-4">
                <h5>Newsletter</h5>
                <p class="small">Tips and updates straight to your inbox.</p>
                <form method="POST" action="{{ route('landing.feedback') }}" class="d-flex gap-2">
                    @csrf
                    <input type="hidden" name="category" value="general">
                    <input type="hidden" name="message" value="Newsletter signup">
                    <input type="hidden" name="name" value="Newsletter">
                    <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                    <button class="btn-rp btn-primary-rp">Join</button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
        </div>
    </div>
</footer>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
