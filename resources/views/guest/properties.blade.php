<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Available Properties - {{ $settings['site_title'] ?? config('app.name') }}</title>

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

        .page-header { background: linear-gradient(135deg, var(--rp-primary), var(--rp-accent)); color: #fff; padding: 60px 0; }
        .page-title { font-size: 2.2rem; font-weight: 800; }
        .page-subtitle { font-size: 1.1rem; opacity: .95; }

        .property-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            transition: all .3s;
            cursor: pointer;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .property-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(15,23,42,.12);
        }

        .property-image { position: relative; overflow: hidden; height: 240px; background: #f1f5f9; }
        .property-image img { width: 100%; height: 100%; object-fit: cover; }
        .property-badge {
            position: absolute; top: 12px; right: 12px;
            padding: 6px 12px; border-radius: 6px; font-size: .8rem; font-weight: 600; color: white;
        }
        .badge-sale { background: #ef4444; }
        .badge-rent { background: #3b82f6; }
        .badge-lease { background: #6b7280; }

        .property-body { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
        .property-name { font-size: 1.1rem; font-weight: 700; margin: 0 0 8px; color: var(--rp-dark); }
        .property-location { color: var(--rp-muted); font-size: .9rem; margin-bottom: 12px; }
        .property-price { font-size: 1.4rem; font-weight: 800; color: var(--rp-primary); margin-bottom: 12px; }

        .property-info { display: flex; gap: 12px; font-size: .85rem; color: #64748b; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #e2e8f0; }
        .property-amenities { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 12px; }
        .amenity-tag { background: #f1f5f9; color: #334155; padding: 4px 10px; border-radius: 20px; font-size: .75rem; }

        .agent-info { margin-top: auto; padding-top: 12px; border-top: 1px solid #e2e8f0; }
        .agent-name { font-weight: 600; color: var(--rp-dark); margin: 0; }
        .agent-role { font-size: .8rem; color: var(--rp-muted); margin-bottom: 4px; }

        .filters { background: var(--rp-bg); border-radius: 12px; padding: 24px; margin-bottom: 30px; }
        .filter-group { margin-bottom: 16px; }
        .filter-group label { display: block; font-weight: 600; margin-bottom: 8px; }
        .filter-group select { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; }

        .pagination { justify-content: center; margin-top: 40px; }
        .pagination .page-link { color: var(--rp-primary); border-color: #e2e8f0; }
        .pagination .page-link:hover { background: var(--rp-bg); color: var(--rp-primary-dark); }
        .pagination .page-item.active .page-link { background: var(--rp-primary); border-color: var(--rp-primary); }

        .no-properties { text-align: center; padding: 60px 20px; }
        .no-properties i { font-size: 4rem; color: #cbd5e1; margin-bottom: 16px; }
        .no-properties h3 { color: var(--rp-muted); margin-bottom: 12px; }

        @media (max-width: 768px) {
            .page-title { font-size: 1.5rem; }
            .property-image { height: 180px; }
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
            @auth
                <a href="{{ url('/home') }}" class="btn-rp btn-primary-rp">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-rp btn-outline-rp" style="margin-right:8px;">Log in</a>
                <a href="{{ route('register') }}" class="btn-rp btn-primary-rp">Get Started</a>
            @endauth
        </div>
    </div>
</nav>

{{-- PAGE HEADER --}}
<header class="page-header">
    <div class="container">
        <h1 class="page-title"><i class="fas fa-home" style="margin-right: 12px;"></i>Available Properties</h1>
        <p class="page-subtitle">Browse our complete selection of premium properties</p>
    </div>
</header>

{{-- MAIN CONTENT --}}
<main style="padding: 40px 0;">
    <div class="container">
        {{-- Filters --}}
        <div class="filters">
            <form method="GET" action="{{ route('guest.properties') }}">
                <div class="row">
                    <div class="col-md-4 filter-group">
                        <label for="listing_type">Property Type</label>
                        <select name="listing_type" id="listing_type">
                            <option value="">All Types</option>
                            <option value="for_sale" {{ request('listing_type') == 'for_sale' ? 'selected' : '' }}>For Sale</option>
                            <option value="for_rent" {{ request('listing_type') == 'for_rent' ? 'selected' : '' }}>For Rent</option>
                        </select>
                    </div>
                    <div class="col-md-4 filter-group">
                        <label for="state">Location</label>
                        <input type="text" name="state" id="state" placeholder="Search by state..." value="{{ request('state') }}">
                    </div>
                    <div class="col-md-4" style="display: flex; align-items: flex-end;">
                        <button type="submit" class="btn-rp btn-primary-rp" style="width: 100%;">
                            <i class="fas fa-search" style="margin-right: 8px;"></i>Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Properties Grid --}}
        @if($properties->count())
            <div class="row g-4">
                @foreach($properties as $property)
                    @php
                        $featuredImage = $property->images->firstWhere('is_featured', 1);
                        $displayImage = $featuredImage ? $featuredImage->image_path : ($property->images->count() > 0 ? $property->images->first()->image_path : null);
                        $amenityList = $property->amenities->take(2)->pluck('name')->toArray();
                    @endphp
                    <div class="col-md-6 col-lg-4 mb-4">
                        <a href="{{ route('guest.property.detail', $property->id) }}" style="text-decoration: none; color: inherit;">
                            <div class="property-card">
                                <div class="property-image">
                                    @if($displayImage && file_exists(public_path($displayImage)))
                                        <img src="{{ asset($displayImage) }}" alt="{{ $property->name }}">
                                    @else
                                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0;">
                                            <i class="fas fa-image" style="font-size: 3rem; color: #cbd5e1;"></i>
                                        </div>
                                    @endif
                                    <div class="property-badge {{ $property->listing_type === 'for_sale' ? 'badge-sale' : ($property->listing_type === 'for_rent' ? 'badge-rent' : 'badge-lease') }}">
                                        @if($property->listing_type === 'for_sale')
                                            FOR SALE
                                        @elseif($property->listing_type === 'for_rent')
                                            FOR RENT
                                        @else
                                            LEASED
                                        @endif
                                    </div>
                                </div>

                                <div class="property-body">
                                    <h3 class="property-name">{{ $property->name }}</h3>
                                    <div class="property-location">
                                        <i class="fas fa-map-marker-alt" style="margin-right: 6px;"></i>
                                        {{ $property->state }}, {{ $property->country }}
                                    </div>

                                    <div class="property-price">
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

                                    <div class="property-info">
                                        @if($property->propertyType)
                                            <span><i class="fas fa-home" style="margin-right: 4px;"></i>{{ $property->propertyType->name }}</span>
                                        @endif
                                        @if($property->area_sqft)
                                            <span><i class="fas fa-ruler" style="margin-right: 4px;"></i>{{ number_format($property->area_sqft) }} sqft</span>
                                        @endif
                                    </div>

                                    @if($amenityList)
                                        <div class="property-amenities">
                                            @foreach($amenityList as $amenity)
                                                <span class="amenity-tag">{{ $amenity }}</span>
                                            @endforeach
                                            @if($property->amenities->count() > 2)
                                                <span class="amenity-tag">+{{ $property->amenities->count() - 2 }} more</span>
                                            @endif
                                        </div>
                                    @endif

                                    @if($property->agent)
                                        <div class="agent-info">
                                            <div class="agent-role">Listed by</div>
                                            <div class="agent-name">{{ $property->agent->name }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="row mt-5">
                <div class="col-12">
                    {{ $properties->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @else
            <div class="no-properties">
                <i class="fas fa-search"></i>
                <h3>No Properties Found</h3>
                <p style="color: var(--rp-muted);">Try adjusting your search filters or browse all available properties.</p>
                <a href="{{ route('guest.properties') }}" class="btn-rp btn-primary-rp" style="margin-top: 16px;">View All Properties</a>
            </div>
        @endif
    </div>
</main>

<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
