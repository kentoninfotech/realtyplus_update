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
        
        /* Carousel fixes */
        .carousel { position: relative; }
        .carousel-inner { position: relative; width: 100%; overflow: hidden; }
        .carousel-item { position: relative; display: none; float: left; width: 100%; margin-right: -100%; }
        .carousel-item.active { display: block; animation: fadeIn 0.5s ease-in; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .carousel-control-prev, .carousel-control-next { 
            position: absolute; top: 50%; z-index: 1; 
            display: flex; align-items: center; justify-content: center;
            width: 15%; height: auto; padding: 0; cursor: pointer;
            text-indent: -9999px; background: rgba(0,0,0,.3); border: none;
            opacity: .7; transition: opacity .2s;
        }
        .carousel-control-prev:hover, .carousel-control-next:hover { opacity: 1; }
        .carousel-control-prev { left: 0; }
        .carousel-control-next { right: 0; }
        .carousel-control-prev-icon, .carousel-control-next-icon {
            display: inline-block; width: 2rem; height: 2rem;
            background: no-repeat 50% / 100% 100%;
        }
        .carousel-indicators [data-bs-target] { 
            background-color: rgba(255,255,255,.7); 
            border: none; border-radius: 50%; 
            width: 12px; height: 12px; 
            transition: all .2s;
        }
        .carousel-indicators .active { 
            background-color: #fff; 
            width: 32px; border-radius: 6px;
        }

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
        .plan-price { font-size: 2rem; font-weight: 800; margin: 8px 0; }
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
        .accordion-rp { background: transparent; border: none; }
        .accordion-rp .accordion-item { 
            background: #fff; 
            border: 1px solid #e2e8f0; 
            border-radius: 8px; 
            margin-bottom: 12px;
        }
        .accordion-rp .accordion-button { 
            font-weight: 600; 
            background: #fff;
            color: var(--rp-dark);
            padding: 16px 20px;
            border: none;
        }
        .accordion-rp .accordion-button:not(.collapsed) {
            background: #f1f5f9;
            color: var(--rp-primary);
            box-shadow: none;
        }
        .accordion-rp .accordion-button:focus {
            border-color: var(--rp-primary);
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25);
        }
        .accordion-rp .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%232563eb'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        }
        .accordion-rp .accordion-body {
            padding: 16px 20px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }

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

{{-- FEATURED PROPERTIES --}}
@if($featuredProperties->count())
<section id="featured-properties" class="section-bg">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-eyebrow">Discover Opportunities</span>
            <h2 class="section-title">Featured Properties</h2>
            <p class="section-sub">Browse our handpicked selection of premium properties available for sale, rent, or lease.</p>
        </div>
        <div class="row g-4">
            @foreach($featuredProperties as $property)
                @php
                    $featuredImage = $property->images->firstWhere('is_featured', 1);
                    $displayImage = $featuredImage ? $featuredImage->image_path : ($property->images->count() > 0 ? $property->images->first()->image_path : asset('plugins/fontawesome-free/svgs/solid/image.svg'));
                    $amenityList = $property->amenities->take(3)->pluck('name')->toArray();
                @endphp
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="property-card" style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; transition: all .3s; cursor: pointer; height: 100%; display: flex; flex-direction: column;"
                         onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(15,23,42,.12)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                         onclick="window.location.href='{{ route('guest.property.detail', $property->id) }}'">
                        
                        {{-- Property Image --}}
                        <div style="position: relative; overflow: hidden; height: 240px; background: #f1f5f9;">
                            @if(file_exists(public_path($displayImage)))
                                <img src="{{ asset($displayImage) }}" alt="{{ $property->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0;">
                                    <i class="fas fa-image" style="font-size: 3rem; color: #cbd5e1;"></i>
                                </div>
                            @endif
                            
                            {{-- Badge for Listing Type --}}
                            <div style="position: absolute; top: 12px; right: 12px;">
                                @if($property->listing_type === 'for_sale')
                                    <span style="background: #ef4444; color: white; padding: 6px 12px; border-radius: 6px; font-size: .8rem; font-weight: 600;">FOR SALE</span>
                                @elseif($property->listing_type === 'for_rent')
                                    <span style="background: #3b82f6; color: white; padding: 6px 12px; border-radius: 6px; font-size: .8rem; font-weight: 600;">FOR RENT</span>
                                @else
                                    <span style="background: #6b7280; color: white; padding: 6px 12px; border-radius: 6px; font-size: .8rem; font-weight: 600;">LEASED</span>
                                @endif
                            </div>
                        </div>

                        {{-- Property Info --}}
                        <div style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column;">
                            {{-- Name --}}
                            <h3 style="font-size: 1.1rem; font-weight: 700; margin: 0 0 8px; color: var(--rp-dark); text-decoration: none;">{{ $property->name }}</h3>
                            
                            {{-- Location --}}
                            <div style="color: var(--rp-muted); font-size: .9rem; margin-bottom: 12px;">
                                <i class="fas fa-map-marker-alt" style="margin-right: 6px;"></i>
                                {{ $property->state }}, {{ $property->country }}
                            </div>

                            {{-- Price --}}
                            <div style="font-size: 1.4rem; font-weight: 800; color: var(--rp-primary); margin-bottom: 12px;">
                                @if($property->listing_type === 'for_sale' && $property->sale_price)
                                    ₦{{ number_format($property->sale_price, 0) }}
                                @elseif($property->listing_type === 'for_rent' && $property->rent_price)
                                    ₦{{ number_format($property->rent_price, 0) }}<span style="font-size: 0.75rem; font-weight: 600;">/month</span>
                                @elseif($property->purchase_price)
                                    ₦{{ number_format($property->purchase_price, 0) }}
                                @else
                                    <span style="color: var(--rp-muted); font-size: 0.9rem;">Contact for price</span>
                                @endif
                            </div>

                            {{-- Property Type & Details --}}
                            <div style="display: flex; gap: 12px; font-size: .85rem; color: #64748b; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                                @if($property->propertyType)
                                    <span><i class="fas fa-home" style="margin-right: 4px;"></i>{{ $property->propertyType->name }}</span>
                                @endif
                                @if($property->area_sqft)
                                    <span><i class="fas fa-ruler" style="margin-right: 4px;"></i>{{ number_format($property->area_sqft) }} sqft</span>
                                @endif
                            </div>

                            {{-- Amenities --}}
                            @if($amenityList)
                                <div style="margin-bottom: 12px;">
                                    <p style="font-size: .8rem; color: var(--rp-muted); margin-bottom: 6px; font-weight: 600;">Amenities:</p>
                                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                        @foreach($amenityList as $amenity)
                                            <span style="background: #f1f5f9; color: #334155; padding: 4px 10px; border-radius: 20px; font-size: .75rem;">{{ $amenity }}</span>
                                        @endforeach
                                        @if($property->amenities->count() > 3)
                                            <span style="background: #f1f5f9; color: #334155; padding: 4px 10px; border-radius: 20px; font-size: .75rem;">+{{ $property->amenities->count() - 3 }} more</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Agent Info --}}
                            @if($property->agent)
                                <div style="margin-top: auto; padding-top: 12px; border-top: 1px solid #e2e8f0;">
                                    <p style="font-size: .8rem; color: var(--rp-muted); margin-bottom: 4px;">Listed by</p>
                                    <p style="font-weight: 600; color: var(--rp-dark); margin: 0;">{{ $property->agent->name }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- View All Button --}}
        <div class="text-center mt-5">
            <a href="{{ route('guest.properties') }}" class="btn-rp btn-primary-rp" style="padding: 12px 36px; font-size: 1rem;">
                View All Properties <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- FEATURED UNITS FOR SALE --}}
@if($featuredUnitsForSale->count())
<section id="featured-units-sale" class="section-bg">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-eyebrow">Prime Opportunities</span>
            <h2 class="section-title">Featured Units for Purchase</h2>
            <p class="section-sub">Explore our selection of premium individual units available for purchase.</p>
        </div>
        <div class="row g-4">
            @foreach($featuredUnitsForSale as $unit)
                @php
                    $propertyImage = $unit->property->images->firstWhere('is_featured', 1);
                    $displayImage = $propertyImage ? $propertyImage->image_path : ($unit->property->images->count() > 0 ? $unit->property->images->first()->image_path : asset('plugins/fontawesome-free/svgs/solid/image.svg'));
                @endphp
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="property-card" style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; transition: all .3s; cursor: pointer; height: 100%; display: flex; flex-direction: column;"
                         onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(15,23,42,.12)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                         onclick="window.location.href='{{ route('guest.unit.detail', $unit->id) }}'">
                        
                        {{-- Unit Image --}}
                        <div style="position: relative; overflow: hidden; height: 240px; background: #f1f5f9;">
                            @if(file_exists(public_path($displayImage)))
                                <img src="{{ asset($displayImage) }}" alt="{{ $unit->unit_number }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0;">
                                    <i class="fas fa-image" style="font-size: 3rem; color: #cbd5e1;"></i>
                                </div>
                            @endif
                            
                            {{-- Badge for Unit Type --}}
                            <div style="position: absolute; top: 12px; right: 12px;">
                                <span style="background: #ef4444; color: white; padding: 6px 12px; border-radius: 6px; font-size: .8rem; font-weight: 600;">FOR SALE</span>
                            </div>
                        </div>

                        {{-- Unit Info --}}
                        <div style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column;">
                            {{-- Unit Name --}}
                            <h3 style="font-size: 1.1rem; font-weight: 700; margin: 0 0 4px; color: var(--rp-dark); text-decoration: none;">{{ $unit->unit_number }}</h3>
                            
                            {{-- Property Name --}}
                            <p style="color: var(--rp-muted); font-size: .9rem; margin-bottom: 12px;">
                                {{ $unit->property->name }}
                            </p>

                            {{-- Location --}}
                            <div style="color: var(--rp-muted); font-size: .9rem; margin-bottom: 12px;">
                                <i class="fas fa-map-marker-alt" style="margin-right: 6px;"></i>
                                {{ $unit->property->state }}, {{ $unit->property->country }}
                            </div>

                            {{-- Price --}}
                            @if($unit->sale_price)
                                <div style="font-size: 1.4rem; font-weight: 800; color: var(--rp-primary); margin-bottom: 12px;">
                                    ₦{{ number_format($unit->sale_price, 0) }}
                                </div>
                            @endif

                            {{-- Unit Type & Details --}}
                            <div style="display: flex; gap: 12px; font-size: .85rem; color: #64748b; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                                <span><i class="fas fa-door-open" style="margin-right: 4px;"></i>{{ ucfirst($unit->unit_type) }}</span>
                                @if($unit->property->area_sqft)
                                    <span><i class="fas fa-ruler" style="margin-right: 4px;"></i>{{ number_format($unit->property->area_sqft) }} sqft</span>
                                @endif
                            </div>

                            {{-- Agent Info --}}
                            @if($unit->property->agent)
                                <div style="margin-top: auto; padding-top: 12px; border-top: 1px solid #e2e8f0;">
                                    <p style="font-size: .8rem; color: var(--rp-muted); margin-bottom: 4px;">Listed by</p>
                                    <p style="font-weight: 600; color: var(--rp-dark); margin: 0;">{{ $unit->property->agent->name }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FEATURED UNITS FOR RENT --}}
@if($featuredUnitsForRent->count())
<section id="featured-units-rent">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-eyebrow">Rental Opportunities</span>
            <h2 class="section-title">Featured Units for Rent</h2>
            <p class="section-sub">Browse our premium units available for lease or short-term rental.</p>
        </div>
        <div class="row g-4">
            @foreach($featuredUnitsForRent as $unit)
                @php
                    $propertyImage = $unit->property->images->firstWhere('is_featured', 1);
                    $displayImage = $propertyImage ? $propertyImage->image_path : ($unit->property->images->count() > 0 ? $unit->property->images->first()->image_path : asset('plugins/fontawesome-free/svgs/solid/image.svg'));
                @endphp
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="property-card" style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; transition: all .3s; cursor: pointer; height: 100%; display: flex; flex-direction: column;"
                         onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(15,23,42,.12)'"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                         onclick="window.location.href='{{ route('guest.unit.detail', $unit->id) }}'">
                        
                        {{-- Unit Image --}}
                        <div style="position: relative; overflow: hidden; height: 240px; background: #f1f5f9;">
                            @if(file_exists(public_path($displayImage)))
                                <img src="{{ asset($displayImage) }}" alt="{{ $unit->unit_number }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0;">
                                    <i class="fas fa-image" style="font-size: 3rem; color: #cbd5e1;"></i>
                                </div>
                            @endif
                            
                            {{-- Badge for Unit Type --}}
                            <div style="position: absolute; top: 12px; right: 12px;">
                                <span style="background: #3b82f6; color: white; padding: 6px 12px; border-radius: 6px; font-size: .8rem; font-weight: 600;">FOR RENT</span>
                            </div>
                        </div>

                        {{-- Unit Info --}}
                        <div style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column;">
                            {{-- Unit Name --}}
                            <h3 style="font-size: 1.1rem; font-weight: 700; margin: 0 0 4px; color: var(--rp-dark); text-decoration: none;">{{ $unit->unit_number }}</h3>
                            
                            {{-- Property Name --}}
                            <p style="color: var(--rp-muted); font-size: .9rem; margin-bottom: 12px;">
                                {{ $unit->property->name }}
                            </p>

                            {{-- Location --}}
                            <div style="color: var(--rp-muted); font-size: .9rem; margin-bottom: 12px;">
                                <i class="fas fa-map-marker-alt" style="margin-right: 6px;"></i>
                                {{ $unit->property->state }}, {{ $unit->property->country }}
                            </div>

                            {{-- Price --}}
                            @if($unit->rent_price)
                                <div style="font-size: 1.4rem; font-weight: 800; color: var(--rp-primary); margin-bottom: 12px;">
                                    ₦{{ number_format($unit->rent_price, 0) }}<span style="font-size: 0.75rem; font-weight: 600;">/month</span>
                                </div>
                            @endif

                            {{-- Unit Type & Details --}}
                            <div style="display: flex; gap: 12px; font-size: .85rem; color: #64748b; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0;">
                                <span><i class="fas fa-door-open" style="margin-right: 4px;"></i>{{ ucfirst($unit->unit_type) }}</span>
                                @if($unit->property->area_sqft)
                                    <span><i class="fas fa-ruler" style="margin-right: 4px;"></i>{{ number_format($unit->property->area_sqft) }} sqft</span>
                                @endif
                            </div>

                            {{-- Agent Info --}}
                            @if($unit->property->agent)
                                <div style="margin-top: auto; padding-top: 12px; border-top: 1px solid #e2e8f0;">
                                    <p style="font-size: .8rem; color: var(--rp-muted); margin-bottom: 4px;">Listed by</p>
                                    <p style="font-weight: 600; color: var(--rp-dark); margin: 0;">{{ $unit->property->agent->name }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
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
                <div class="col-md-6 col-lg-3">
                    <div class="plan-card {{ $plan->is_featured ? 'featured' : '' }}">
                        @if($plan->is_featured)<span class="plan-badge">MOST POPULAR</span>@endif
                        <div class="plan-name">{{ $plan->name }}</div>
                        <div class="plan-price">
                            {{ $plan->price > 0 ? $plan->currency . number_format($plan->price, 0) : '--' }}
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
        <div class="text-center mb-4">
            <span class="section-eyebrow">FAQ</span>
            <h2 class="section-title">Questions? We have answers.</h2>
        </div>
        <div class="accordion accordion-rp" id="faqAccordion">
            @foreach($faqs as $i => $faq)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button {{ $i!=0?'collapsed':'' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faq-{{ $faq->id }}" aria-expanded="{{ $i==0?'true':'false' }}" aria-controls="faq-{{ $faq->id }}">
                            {{ $faq->title }}
                        </button>
                    </h2>
                    <div id="faq-{{ $faq->id }}" class="accordion-collapse collapse {{ $i==0?'show':'' }}" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">{{ $faq->body }}</div>
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

<script>
// Initialize carousel and accordion after DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize hero carousel
    const heroCarousel = document.getElementById('heroCarousel');
    if (heroCarousel) {
        new bootstrap.Carousel(heroCarousel, {
            interval: 6000,
            wrap: true,
            keyboard: true,
            touch: true
        });
    }

    // Initialize FAQ accordion - ensure collapse works
    const faqButtons = document.querySelectorAll('.accordion-button');
    faqButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Bootstrap 5 handles this automatically with data-bs-toggle="collapse"
            // This ensures it works even if there are JS conflicts
            const target = this.getAttribute('data-bs-target');
            if (target) {
                const element = document.querySelector(target);
                if (element) {
                    const bsCollapse = new bootstrap.Collapse(element, {
                        toggle: true
                    });
                }
            }
        });
    });

    // Auto-initialize any tooltips or popovers (optional)
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
</body>
</html>
