<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $unit->unit_number ?? 'Unit' }} - {{ $settings['site_title'] ?? config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">

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

        .gallery-main { height: 500px; border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
        .gallery-main img { width: 100%; height: 100%; object-fit: cover; }
        .gallery-thumbs { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; }
        .gallery-thumb { height: 100px; border-radius: 8px; overflow: hidden; cursor: pointer; border: 2px solid #e2e8f0; transition: all .2s; }
        .gallery-thumb:hover { border-color: var(--rp-primary); }
        .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }

        .property-header { background: var(--rp-bg); padding: 40px 0; border-bottom: 1px solid #e2e8f0; }
        .property-title { font-size: 2rem; font-weight: 800; margin-bottom: 12px; }
        .property-location { color: var(--rp-muted); font-size: 1.1rem; margin-bottom: 16px; }
        .property-price { font-size: 2.4rem; font-weight: 800; color: var(--rp-primary); }

        .property-details { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; padding: 20px 0; border-bottom: 1px solid #e2e8f0; }
        .detail-item { text-align: center; }
        .detail-value { font-size: 1.6rem; font-weight: 700; color: var(--rp-primary); }
        .detail-label { font-size: .9rem; color: var(--rp-muted); margin-top: 4px; }

        .amenities-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 12px; }
        .amenity-item { background: var(--rp-bg); padding: 12px; border-radius: 8px; text-align: center; }
        .amenity-icon { font-size: 1.6rem; color: var(--rp-accent); margin-bottom: 6px; }

        .contact-card { background: var(--rp-bg); border-radius: 12px; padding: 24px; margin-top: 30px; }
        .contact-card h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 16px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-family: inherit; }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: var(--rp-primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }

        .alert { padding: 16px; border-radius: 8px; margin-bottom: 16px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error { background: #fee2e2; color: #7f1d1d; border: 1px solid #fca5a5; }

        .agent-info { background: var(--rp-bg); border-radius: 12px; padding: 24px; margin-top: 30px; text-align: center; }
        .agent-name { font-size: 1.2rem; font-weight: 700; margin-bottom: 4px; }
        .agent-email { color: var(--rp-muted); margin-bottom: 4px; }
        .agent-phone { font-weight: 600; color: var(--rp-primary); }

        .section { padding: 40px 0; }
        .section-title { font-size: 1.6rem; font-weight: 700; margin-bottom: 24px; }

        @media (max-width: 768px) {
            .gallery-main { height: 300px; }
            .property-title { font-size: 1.5rem; }
            .property-price { font-size: 1.8rem; }
            .property-details { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        }
    </style>
</head>
<body>

{{-- NAV --}}
<nav class="navbar-rp">
    <div class="container">
        <a href="/" class="brand">
            <i class="fas fa-building"></i>
            {{ $settings['site_title'] ?? config('app.name') }}
        </a>
        <div class="nav-links">
            <a href="/">Home</a>
            <a href="{{ route('guest.properties') }}">Properties</a>
        </div>
        <div>
            <a href="{{ route('login') }}" class="btn-rp btn-outline-rp" style="margin-right:8px;">Log in</a>
            <a href="{{ route('register') }}" class="btn-rp btn-primary-rp">Get Started</a>
        </div>
    </div>
</nav>

{{-- MAIN CONTENT --}}
<main>
    <div class="container" style="padding-top: 40px;">
        {{-- Gallery --}}
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="gallery-main">
                    @if($displayImage && file_exists(public_path($displayImage)))
                        <img id="mainImage" src="{{ asset($displayImage) }}" alt="{{ $unit->unit_number }}">
                    @else
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0;">
                            <i class="fas fa-image" style="font-size: 4rem; color: #cbd5e1;"></i>
                        </div>
                    @endif
                </div>

                {{-- Thumbnails --}}
                @if($galleryImages->count())
                    <div class="gallery-thumbs">
                        @foreach($galleryImages as $image)
                            @if(file_exists(public_path($image->image_path)))
                                <div class="gallery-thumb" onclick="document.getElementById('mainImage').src='{{ asset($image->image_path) }}'">
                                    <img src="{{ asset($image->image_path) }}" alt="Gallery">
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Quick Info Sidebar --}}
            <div class="col-lg-4">
                <div class="contact-card" style="background: white; border: 1px solid #e2e8f0;">
                    <h3>Unit {{ $unit->unit_number }}</h3>
                    <div style="font-size: 0.95rem; color: var(--rp-muted); margin-bottom: 12px;">
                        <i class="fas fa-building"></i> {{ $unit->property->name }}
                    </div>
                    <div class="property-location">
                        <i class="fas fa-map-marker-alt"></i> {{ $unit->property->state }}, {{ $unit->property->country }}
                    </div>
                    <div class="property-price" style="margin-bottom: 20px;">
                        @if($unit->rent_price)
                            ₦{{ number_format($unit->rent_price, 0) }}<span style="font-size: 0.75rem; font-weight: 600;">/month</span>
                        @elseif($unit->sale_price)
                            ₦{{ number_format($unit->sale_price, 0) }}
                        @else
                            Contact for price
                        @endif
                    </div>

                    {{-- Status Badge --}}
                    @if($unit->status === 'available')
                        <div style="display: inline-block; background: #10b981; color: white; padding: 8px 16px; border-radius: 6px; font-weight: 600; margin-bottom: 16px;">AVAILABLE</div>
                    @elseif($unit->status === 'occupied')
                        <div style="display: inline-block; background: #8b5cf6; color: white; padding: 8px 16px; border-radius: 6px; font-weight: 600; margin-bottom: 16px;">OCCUPIED</div>
                    @else
                        <div style="display: inline-block; background: #6b7280; color: white; padding: 8px 16px; border-radius: 6px; font-weight: 600; margin-bottom: 16px; text-transform: uppercase;">{{ $unit->status }}</div>
                    @endif

                    <div class="property-details" style="border: none; padding: 16px 0; margin-bottom: 0;">
                        @if($unit->bedrooms || $unit->bathrooms)
                            <div class="detail-item">
                                <div class="detail-value">{{ $unit->bedrooms ?? 'N/A' }}</div>
                                <div class="detail-label">Bedrooms</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-value">{{ $unit->bathrooms ?? 'N/A' }}</div>
                                <div class="detail-label">Bathrooms</div>
                            </div>
                        @endif
                        @if($unit->size_sqft)
                            <div class="detail-item">
                                <div class="detail-value">{{ number_format($unit->size_sqft) }}</div>
                                <div class="detail-label">Sq Ft</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Agent Info --}}
                @if($unit->property->agent)
                    <div class="agent-info">
                        <p style="font-size: .9rem; color: var(--rp-muted); margin-bottom: 12px;">LISTING AGENT</p>
                        <div class="agent-name">{{ $unit->property->agent->name }}</div>
                        <a href="mailto:{{ $unit->property->agent->email }}" class="agent-email">{{ $unit->property->agent->email }}</a>
                        @if($unit->property->agent->phone)
                            <div class="agent-phone">{{ $unit->property->agent->phone }}</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-8">
                {{-- Description --}}
                @if($unit->description)
                    <section class="section">
                        <h3 class="section-title">About This Unit</h3>
                        <p>{{ $unit->description }}</p>
                    </section>
                @endif

                {{-- Unit Details --}}
                <section class="section">
                    <h3 class="section-title">Unit Details</h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p style="color: var(--rp-muted); margin-bottom: 4px;">Unit Number</p>
                            <p style="font-weight: 600;">{{ $unit->unit_number }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p style="color: var(--rp-muted); margin-bottom: 4px;">Status</p>
                            <p style="font-weight: 600; text-transform: capitalize;">{{ $unit->status }}</p>
                        </div>
                        @if($unit->bedrooms)
                            <div class="col-md-6 mb-3">
                                <p style="color: var(--rp-muted); margin-bottom: 4px;">Bedrooms</p>
                                <p style="font-weight: 600;">{{ $unit->bedrooms }}</p>
                            </div>
                        @endif
                        @if($unit->bathrooms)
                            <div class="col-md-6 mb-3">
                                <p style="color: var(--rp-muted); margin-bottom: 4px;">Bathrooms</p>
                                <p style="font-weight: 600;">{{ $unit->bathrooms }}</p>
                            </div>
                        @endif
                        @if($unit->size_sqft)
                            <div class="col-md-6 mb-3">
                                <p style="color: var(--rp-muted); margin-bottom: 4px;">Size</p>
                                <p style="font-weight: 600;">{{ number_format($unit->size_sqft) }} sq ft</p>
                            </div>
                        @endif
                        @if($unit->rent_price)
                            <div class="col-md-6 mb-3">
                                <p style="color: var(--rp-muted); margin-bottom: 4px;">Monthly Rent</p>
                                <p style="font-weight: 600;">₦{{ number_format($unit->rent_price, 0) }}</p>
                            </div>
                        @endif
                        @if($unit->sale_price)
                            <div class="col-md-6 mb-3">
                                <p style="color: var(--rp-muted); margin-bottom: 4px;">Sale Price</p>
                                <p style="font-weight: 600;">₦{{ number_format($unit->sale_price, 0) }}</p>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Property Amenities --}}
                @if($unit->property->amenities->count())
                    <section class="section">
                        <h3 class="section-title">Property Amenities</h3>
                        <div class="amenities-grid">
                            @foreach($unit->property->amenities as $amenity)
                                <div class="amenity-item">
                                    <div class="amenity-icon"><i class="fas fa-check-circle"></i></div>
                                    <div>{{ $amenity->name }}</div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            {{-- Forms Column --}}
            <div class="col-lg-4">
                {{-- CTA Section --}}
                <div class="contact-card">
                    <h3 style="font-size: 1.3rem; margin-bottom: 20px;">Interested in this unit?</h3>
                    <p style="color: var(--rp-muted); margin-bottom: 20px;">Get more information or schedule a viewing with our agent.</p>
                    <button class="btn-rp btn-primary-rp" style="width: 100%; margin-bottom: 12px;" onclick="document.getElementById('contactForm').scrollIntoView({behavior:'smooth'});">
                        <i class="fas fa-envelope me-2"></i> Contact Agent
                    </button>
                    <a href="{{ route('guest.properties') }}" class="btn-rp btn-outline-rp" style="width: 100%; text-align: center;">
                        <i class="fas fa-arrow-left me-2"></i> Back to Properties
                    </a>
                </div>
            </div>
        </div>

        {{-- Contact Form --}}
        <div class="row mt-5" id="contactForm">
            <div class="col-lg-8">
                <div class="contact-card">
                    <h3>Send us a message</h3>
                    <p style="color: var(--rp-muted); margin-bottom: 20px;">Have questions about this unit? Get in touch with us today.</p>
                    
                    <form method="POST" action="{{ route('guest.property.interest') }}" style="display: flex; flex-direction: column;">
                        @csrf
                        <input type="hidden" name="property_id" value="{{ $unit->property->id }}">
                        <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                        
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required placeholder="Your full name">
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required placeholder="your.email@example.com">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" required placeholder="Your phone number">
                        </div>

                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" required placeholder="Tell us what you'd like to know about this unit..."></textarea>
                        </div>

                        <button type="submit" class="btn-rp btn-primary-rp" style="padding: 12px 24px; font-size: 1rem;">
                            <i class="fas fa-paper-plane me-2"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- FOOTER --}}
<footer style="background: var(--rp-dark); color: #cbd5e1; padding: 40px 0 20px; margin-top: 60px;">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-3">
                <h5 style="color: #fff; margin-bottom: 16px; font-weight: 700;">{{ $settings['site_title'] ?? config('app.name') }}</h5>
                <p style="font-size: .9rem;">Property management made simple and efficient.</p>
            </div>
            <div class="col-md-3">
                <h5 style="color: #fff; margin-bottom: 16px; font-weight: 700;">Quick Links</h5>
                <ul style="list-style: none; padding: 0;">
                    <li><a href="/" style="color: #cbd5e1; text-decoration: none;">Home</a></li>
                    <li><a href="{{ route('guest.properties') }}" style="color: #cbd5e1; text-decoration: none;">Properties</a></li>
                    <li><a href="#" style="color: #cbd5e1; text-decoration: none;">About</a></li>
                </ul>
            </div>
        </div>
        <div style="border-top: 1px solid #1e293b; padding-top: 20px; text-align: center; font-size: .9rem;">
            <p>&copy; {{ date('Y') }} {{ $settings['site_title'] ?? config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
</body>
</html>
