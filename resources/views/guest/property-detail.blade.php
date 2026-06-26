<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $property->name }} - {{ $settings['site_title'] ?? config('app.name') }}</title>

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
                        <img id="mainImage" src="{{ asset($displayImage) }}" alt="{{ $property->name }}">
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
                    <h3>{{ $property->name }}</h3>
                    <div class="property-location">
                        <i class="fas fa-map-marker-alt"></i> {{ $property->state }}, {{ $property->country }}
                    </div>
                    <div class="property-price" style="margin-bottom: 20px;">
                        @if($property->listing_type === 'for_sale' && $property->sale_price)
                            ₦{{ number_format($property->sale_price, 0) }}
                        @elseif($property->listing_type === 'for_rent' && $property->rent_price)
                            ₦{{ number_format($property->rent_price, 0) }}/month
                        @elseif($property->purchase_price)
                            ₦{{ number_format($property->purchase_price, 0) }}
                        @else
                            Contact for price
                        @endif
                    </div>

                    {{-- Listing Type Badge --}}
                    @if($property->listing_type === 'for_sale')
                        <div style="display: inline-block; background: #ef4444; color: white; padding: 8px 16px; border-radius: 6px; font-weight: 600; margin-bottom: 16px;">FOR SALE</div>
                    @elseif($property->listing_type === 'for_rent')
                        <div style="display: inline-block; background: #3b82f6; color: white; padding: 8px 16px; border-radius: 6px; font-weight: 600; margin-bottom: 16px;">FOR RENT</div>
                    @endif

                    <div class="property-details" style="border: none; padding: 16px 0; margin-bottom: 0;">
                        @if($property->propertyType)
                            <div class="detail-item">
                                <div class="detail-value"><i class="fas fa-home"></i></div>
                                <div class="detail-label">{{ $property->propertyType->name }}</div>
                            </div>
                        @endif
                        @if($property->area_sqft)
                            <div class="detail-item">
                                <div class="detail-value">{{ number_format($property->area_sqft) }}</div>
                                <div class="detail-label">Sq Ft</div>
                            </div>
                        @endif
                        @if($property->year_built)
                            <div class="detail-item">
                                <div class="detail-value">{{ $property->year_built }}</div>
                                <div class="detail-label">Year Built</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Agent Info --}}
                @if($property->agent)
                    <div class="agent-info">
                        <p style="font-size: .9rem; color: var(--rp-muted); margin-bottom: 12px;">LISTING AGENT</p>
                        <div class="agent-name">{{ $property->agent->name }}</div>
                        <a href="mailto:{{ $property->agent->email }}" class="agent-email">{{ $property->agent->email }}</a>
                        @if($property->agent->phone)
                            <div class="agent-phone">{{ $property->agent->phone }}</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-8">
                {{-- Description --}}
                @if($property->description)
                    <section class="section">
                        <h3 class="section-title">About This Property</h3>
                        <p>{{ $property->description }}</p>
                    </section>
                @endif

                {{-- Details --}}
                <section class="section">
                    <h3 class="section-title">Property Details</h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p style="color: var(--rp-muted); margin-bottom: 4px;">Property Type</p>
                            <p style="font-weight: 600;">{{ $property->propertyType->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p style="color: var(--rp-muted); margin-bottom: 4px;">Status</p>
                            <p style="font-weight: 600; text-transform: capitalize;">{{ $property->status }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p style="color: var(--rp-muted); margin-bottom: 4px;">Address</p>
                            <p style="font-weight: 600;">{{ $property->address }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p style="color: var(--rp-muted); margin-bottom: 4px;">Location</p>
                            <p style="font-weight: 600;">{{ $property->state }}, {{ $property->country }}</p>
                        </div>
                        @if($property->area_sqft)
                            <div class="col-md-6 mb-3">
                                <p style="color: var(--rp-muted); margin-bottom: 4px;">Area</p>
                                <p style="font-weight: 600;">{{ number_format($property->area_sqft) }} sq ft</p>
                            </div>
                        @endif
                        @if($property->lot_size_sqft)
                            <div class="col-md-6 mb-3">
                                <p style="color: var(--rp-muted); margin-bottom: 4px;">Lot Size</p>
                                <p style="font-weight: 600;">{{ number_format($property->lot_size_sqft) }} sq ft</p>
                            </div>
                        @endif
                        @if($property->year_built)
                            <div class="col-md-6 mb-3">
                                <p style="color: var(--rp-muted); margin-bottom: 4px;">Year Built</p>
                                <p style="font-weight: 600;">{{ $property->year_built }}</p>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Amenities --}}
                @if($property->amenities->count())
                    <section class="section">
                        <h3 class="section-title">Amenities</h3>
                        <div class="amenities-grid">
                            @foreach($property->amenities as $amenity)
                                <div class="amenity-item">
                                    <div class="amenity-icon"><i class="fas fa-check-circle"></i></div>
                                    <div>{{ $amenity->name }}</div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Units --}}
                @if($property->units->count())
                    <section class="section">
                        <h3 class="section-title">Available Units</h3>
                        <div class="row">
                            @foreach($property->units as $unit)
                                <div class="col-md-6 mb-3">
                                    <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px;">
                                        <h5 style="font-weight: 700; margin-bottom: 8px;">{{ $unit->name ?? 'Unit ' . $unit->unit_number }}</h5>
                                        @if($unit->rent_price)
                                            <p style="margin-bottom: 4px;"><strong>Rent:</strong> ₦{{ number_format($unit->rent_price, 0) }}/month</p>
                                        @endif
                                        @if($unit->sale_price)
                                            <p style="margin-bottom: 4px;"><strong>Sale Price:</strong> ₦{{ number_format($unit->sale_price, 0) }}</p>
                                        @endif
                                        <p style="margin-bottom: 4px; color: var(--rp-muted); font-size: .9rem;">Status: <span style="text-transform: capitalize;">{{ $unit->status }}</span></p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            {{-- Forms Column --}}
            <div class="col-lg-4">
                {{-- Messages --}}
                @if($message = session('status'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> {{ $message }}
                    </div>
                @endif
                @if($message = session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @endif

                {{-- Interest Form --}}
                <div class="contact-card">
                    <h3><i class="fas fa-heart" style="color: var(--rp-accent); margin-right: 8px;"></i>Express Interest</h3>
                    <form action="{{ route('guest.property.interest', $property->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="interest_name">Your Name</label>
                            <input type="text" id="interest_name" name="name" required placeholder="Enter your name">
                        </div>
                        <div class="form-group">
                            <label for="interest_email">Email Address</label>
                            <input type="email" id="interest_email" name="email" required placeholder="Enter your email">
                        </div>
                        <div class="form-group">
                            <label for="interest_phone">Phone Number</label>
                            <input type="tel" id="interest_phone" name="phone" required placeholder="Enter your phone number">
                        </div>
                        <div class="form-group">
                            <label for="interest_type">Interest Type</label>
                            <select id="interest_type" name="interest_type" required>
                                <option value="">Select one...</option>
                                <option value="buy">Interested to Buy</option>
                                <option value="rent">Interested to Rent</option>
                                <option value="lease">Interested to Lease</option>
                                <option value="sell">Want to Sell Similar Property</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="interest_message">Message (Optional)</label>
                            <textarea id="interest_message" name="message" placeholder="Tell us more about your interest..."></textarea>
                        </div>
                        <button type="submit" class="btn-rp btn-primary-rp" style="width: 100%;">Submit Interest</button>
                    </form>
                </div>

                {{-- Contact Agent Form --}}
                <div class="contact-card" style="margin-top: 24px;">
                    <h3><i class="fas fa-envelope" style="color: var(--rp-primary); margin-right: 8px;"></i>Contact Agent</h3>
                    <form action="{{ route('guest.property.contact-agent', $property->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="contact_name">Your Name</label>
                            <input type="text" id="contact_name" name="name" required placeholder="Enter your name">
                        </div>
                        <div class="form-group">
                            <label for="contact_email">Email Address</label>
                            <input type="email" id="contact_email" name="email" required placeholder="Enter your email">
                        </div>
                        <div class="form-group">
                            <label for="contact_phone">Phone Number</label>
                            <input type="tel" id="contact_phone" name="phone" required placeholder="Enter your phone number">
                        </div>
                        <div class="form-group">
                            <label for="contact_message">Message</label>
                            <textarea id="contact_message" name="message" required placeholder="Your message to the agent..."></textarea>
                        </div>
                        <button type="submit" class="btn-rp btn-outline-rp" style="width: 100%;">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
</body>
</html>
