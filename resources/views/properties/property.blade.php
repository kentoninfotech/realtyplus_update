@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Property Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Property Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-widget widget-user">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header text-white"
            style="background: @if ($featuredImage) url({{ asset('public/' . $featuredImage->image_path) }}); background-size: cover; background-repeat: no-repeat; @endif height: 250px !important; text-shadow: 2px 2px #000; background-color: grey;">
            <h1 class="text-right">{{ $property->name }}</h1>
            <h5 class="widget-user-desc text-right"><i class="nav-icon fas fa-map-marker-alt"></i> {{ $property->address ?? '' }}, {{ $property->state }}, {{ $property->country }}
            </h5>
            <h5 class="badge badge-primary badge-pill float-right">{{ $property->propertyType->name }}</h5>
        </div>

        <div class="card-footer">
            <div class="row">
                @if ($property->has_units)
                    <div class="col-sm-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header">{{ $property->units->count() }}</h5>
                            <span class="description-text">UNITS</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                @endif
                <!-- /.col -->
                <div class="col-sm-{{ $property->has_units ? 3 : 4 }} border-right">
                    <div class="description-block">
                        <h5 class="description-header">₦{{ number_format($property->rent_price, 0, '.', ',') }}
                        </h5>
                        <span class="description-text">RENT PRICE</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-{{ $property->has_units ? 3 : 4 }} border-right">
                    <div class="description-block">
                        <h5 class="description-header"> ₦{{ number_format($property->sale_price, 0, '.', ',') }}</h5>
                        <span class="description-text">SALE PRICE (VALUE)</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-{{ $property->has_units ? 3 : 4 }}">
                    <div class="description-block">
                        <button class="btn btn-info" data-toggle="modal" data-target="#mapModal">
                            <i class="fa fa-map"></i>
                            Show on MAP
                        </button>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="card">
                            <div class="card-body">
                                @if($displayImage)
                                    <img src="{{ asset('public/'.$displayImage) }}" alt="{{ $property->name }}" class="img-responsive img-featured center-block" id="mainPropertyImage" style="max-width: 100%; height: auto; margin-bottom: 15px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); transition: box-shadow 0.3s;">
                                    <div id="featuredGallery" style="margin-bottom: 10px; background: #f8f9fa; border-radius: 8px; padding: 10px 8px 6px 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                        @php $maxVisible = 6; @endphp
                                        <div id="featuredRow" style="display: inline-block;">
                                            @foreach($property->images->take($maxVisible) as $img)
                                                @php $borderColor = $img->is_featured ? '#337ab7' : 'transparent'; @endphp
                                                <img src="{{ asset('public/'.$img->image_path) }}"
                                                    alt="Featured"
                                                    class="img-featured featured-item {{ $img->is_featured ? 'active' : '' }} thumb-anim"
                                                    data-main-image="{{ asset('public/'.$img->image_path) }}"
                                                    style="width: 80px; height: 60px; object-fit: cover; cursor: pointer;
                                                       border: 2px solid {{ $borderColor }}; display: inline-block;
                                                       margin-right: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                                                       transition: border-color 0.3s, transform 0.2s; {{ $img->is_featured ?
                                                       'transform: scale(1.08); z-index:2;' : '' }}">
                                            @endforeach
                                            @if($property->images->count() > $maxVisible)
                                                <button id="expandGalleryBtn" class="btn btn-info btn-xs" style="vertical-align: top; margin: 15px 0 0 2px; font-weight: bold; letter-spacing: 0.5px; transition: background 0.2s;">+{{ $property->images->count() - $maxVisible }} more</button>
                                            @endif
                                        </div>
                                        <div id="allFeaturedsRow" style="display: none; margin-top: 10px;">
                                            @foreach($property->images as $img)
                                                @php $borderColor = $img->is_featured ? '#0bc624ff' : 'transparent'; @endphp
                                                <img src="{{ asset('/public/'.$img->image_path) }}"
                                                    alt="Featured"
                                                    class="img-featured featured-item {{ $img->is_featured ? 'active' : '' }} thumb-anim"
                                                    data-main-image="{{ asset('/public/'.$img->image_path) }}"
                                                    style="width: 80px; height: 60px; object-fit: cover; cursor: pointer; border: 3px solid {{ $borderColor }}; display: inline-block; margin: 0 8px 8px 0; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: border-color 0.3s, transform 0.2s;{{ $img->is_featured ? 'transform: scale(1.08); z-index:2;' : '' }}">
                                            @endforeach
                                            <button id="collapseGalleryBtn" class="btn btn-default btn-xs" style="vertical-align: top; margin: 15px 0 0 2px; font-weight: bold; letter-spacing: 0.5px; transition: background 0.2s;">Show less</button>
                                        </div>
                                    </div>

                                @else
                                    <div style="height: 220px; display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 8px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                        <p class="text-danger" style="margin:0; font-size: 18px;">No image uploaded for this property.</p>
                                    </div>
                                @endif

                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addImageModal">
                                    Add Images
                                </button>

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                <!-- ADD SET FEATURE BUTTON -->
                                @if($property->images->count())
                                    <div class="text-center" style="margin-top: 15px;">
                                        <p>Manage Featureds:</p>
                                        @foreach($property->images as $img)
                                            @if(!$img->is_featured)
                                                <form action="{{ route('property.setFeaturedImage', [$property->id, $img->id]) }}" method="POST" style="display:inline-block; margin-right: 5px; margin-bottom: 5px;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-info btn-xs">Set Image {{ $loop->index + 1 }} as Featured</button>
                                                </form>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="title">Overview</h5>
                                <div class="container mt-4">
                                    <div class="row text-center">
                                        @if ($property->units()->sum('bedrooms') > 0)
                                            <div class="col-6 col-sm-4 col-md-2">
                                                <div class="property-info-box">
                                                    <i class="fa fa-bed"></i>
                                                    <div class="label">Bedrooms</div>
                                                    <div class="value">{{ $property->units()->sum('bedrooms') }}</div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($property->units()->sum('bathrooms') > 0)
                                            <div class="col-6 col-sm-4 col-md-2">
                                                <div class="property-info-box">
                                                    <i class="fa fa-bath"></i>
                                                    <div class="label">Bathrooms</div>
                                                    <div class="value">{{ $property->units()->sum('bathrooms') }}</div>
                                                </div>
                                            </div>
                                        @endif
                                        @isset($property->year_built)
                                            <div class="col-6 col-sm-4 col-md-2">
                                                <div class="property-info-box">
                                                    <i class="fa fa-calendar"></i>
                                                    <div class="label">Year Built</div>
                                                    <div class="value">{{ $property->year_built}}</div>
                                                </div>
                                            </div>
                                        @endisset
                                        @isset($property->area_sqft)
                                            <div class="col-6 col-sm-4 col-md-2">
                                                <div class="property-info-box">
                                                    <i class="fa fa-arrows-alt"></i>
                                                    <div class="label">Area</div>
                                                    <div class="value">{{ $property->area_sqft}} sq ft</div>
                                                </div>
                                            </div>
                                        @endisset
                                        @isset($property->lot_size_sqft)
                                            <div class="col-6 col-sm-4 col-md-2">
                                                <div class="property-info-box">
                                                    <i class="fa fa-arrows-alt"></i>
                                                    <div class="label">Lot Size</div>
                                                    <div class="value">{{ $property->lot_size_sqft}} sq ft</div>
                                                </div>
                                            </div>
                                        @endisset
                                        @isset($property->date_acquired)
                                            <div class="col-6 col-sm-4 col-md-2">
                                                <div class="property-info-box">
                                                    <i class="fa fa-calendar"></i>
                                                    <div class="label">Date Acquired</div>
                                                    <div class="value">{{ $property->date_acquired->format('M, Y')}}</div>
                                                </div>
                                            </div>
                                        @endisset

                                    </div>
                                </div>
                                <h5 class="title my-3">Additional Details</h5>
                                @isset($property->description)
                                    <div class="card-text">
                                        <h6 class="title">Description: </h6>
                                        <p style="text-align: left">{!! $property->description !!} </p>
                                    </div>
                                @endisset
                                <table class="table mb-4">
                                    <tbody>
                                        <tr>
                                            <th>Property Status:</th>
                                            <td>
                                                <span class="badge badge-{{
                                                    $property->status == 'available' ? 'success' :
                                                    ($property->status == 'sold' ? 'danger' :
                                                    ($property->status == 'under_maintenance' ? 'warning' :
                                                    ($property->status == 'leased' ? 'danger' : 'info')))
                                                    }} badge-pill">
                                                    {{ ucwords(str_replace('_', ' ', $property->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @isset($property->purchase_price)
                                            <tr>
                                                <th>Purchase Price:</th>
                                                <td>
                                                    <span class="small">₦{{ number_format($property->purchase_price, 0, '.', ',') }}</span>
                                                </td>
                                            </tr>
                                        @endisset
                                        @isset($property->listing_type)
                                            <tr>
                                                <th>Listing Type:</th>
                                                <td>
                                                    <span class="badge badge-info badge-pill small">{{ $property->listing_type }}</span>
                                                </td>
                                            </tr>
                                        @endisset
                                        @isset($property->listed_at)
                                            <tr>
                                                <th>Listed At:</th>
                                                <td>
                                                    <span class="small">{{ $property->listed_at->format('d F, Y h:i A')  }}</span>
                                                </td>
                                            </tr>
                                        @endisset
                                        @if (isset($property->latitude) && isset($property->longitude))
                                            <tr>
                                                <th>Coordinate:</th>
                                                <td>
                                                    <span class="badge badge-info">{{ $property->latitude }}</span> /
                                                    <span class="badge badge-primary">{{ $property->longitude }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <h6 class="title">Amenities</h6>
                                <div class="d-flex flex-wrap justify-content-start">
                                    @foreach ($property->amenities as $amenity)
                                        <div class="amenity-box">
                                            <i class="{{ $amenity->icon }} amenity-icon"></i>
                                            <span>{{ $amenity->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- / .col-md-9 -->
                <div class="col-md-3">

                    <div class="card">
                        <div class="card-body">
                            <div class="owner-agent">
                                 <!-- PROPERTY OWNER  -->
                                @isset($property->owner)
                                    <div class="agent-card">
                                        <div>
                                            <div class="text-muted">Owner</div>
                                            <div class="agent-name">{{ $property->owner->full_name ?? '' }}</div>
                                            <a href="{{ route('owner.property', $property->owner->id) }}" class="listing-link">View Properties</a>
                                            <div class="contact-item">
                                                <i class="fa fa-email ml-auto mr-2"></i>
                                                <span class="contact-value">{{ $property->owner->email ?? '' }}</span>
                                            </div>
                                            <div class="contact-item">
                                                <i class="fa fa-phone ml-auto mr-2"></i>
                                                <span class="contact-value">{{ $property->owner->phone_number ?? '' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endisset
                                <!-- PROPERTY AGENT  -->
                                @isset($property->agent)
                                    <div class="agent-card">
                                        <div>
                                        <div class="text-muted">Agent</div>
                                        <div class="agent-name">{{ $property->agent->full_name ?? '' }}</div>
                                        <a href="#" class="listing-link">View Agent Listings</a>
                                        <div class="contact-item">
                                            <i class="fa fa-phone ml-auto mr-2"></i>
                                            <span class="contact-value">{{ $property->agent->phone_number ?? '' }}</span>
                                        </div>
                                        <div class="contact-item">
                                            <i class="fa fa-email ml-auto mr-2"></i>
                                            <span class="contact-value">{{ $property->agent->email ?? '' }}</span>
                                        </div>
                                        </div>
                                    </div>
                                @endisset
                            </div>

                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center active small">
                                    Property Details
                                </li>
                                @isset($property->units->bedrooms)
                                   <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Bedrooms
                                        <span class="small">{{ $property->units->bedrooms->count()  }}</span>
                                  </li>
                                @endisset
                                @isset($property->date_acquired)
                                   <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Year Built
                                        <span class="small">{{ $property->year_built }}</span>
                                    </li>
                                @endisset
                                @isset($property->date_acquired)
                                   <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Date Acquired
                                        <span class="small">{{ $property->date_acquired->format('Y-m-d H:i') }}</span>
                                    </li>
                                @endisset
                                @isset($property->purchase_price)
                                   <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Purchase Price
                                        <span class="small">₦{{ number_format($property->purchase_price, 0, '.', ',') }}</span>
                                    </li>
                                @endisset
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center">
                                    Property Status
                                    <span class="badge badge-{{
                                        $property->status == 'available' ? 'success' :
                                        ($property->status == 'sold' ? 'danger' :
                                        ($property->status == 'under_maintenance' ? 'warning' :
                                        ($property->status == 'leased' ? 'danger' : 'info')))
                                        }} badge-pill">
                                        {{ ucwords(str_replace('_', ' ', $property->status)) }}
                                    </span>
                                </li>
                                @isset($property->area_sqft)
                                   <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Area SQFT
                                        <span class="small">{{ $property->area_sqft  }}</span>
                                  </li>
                                @endisset
                                @isset($property->lot_size_sqft)
                                   <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Lot Size SQFT
                                        <span class="small">{{ $property->lot_size_sqft  }}</span>
                                  </li>
                                @endisset
                                @isset($property->listing_type)
                                   <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Listing Type
                                        <span class="badge badge-info badge-pill small">{{ $property->listing_type  }}</span>
                                   </li>
                                @endisset
                                @isset($property->listed_at)
                                   <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Listed At
                                        <span class="small">{{ $property->listed_at->format('d F, Y H:i' ?? '')  }}</span>
                                   </li>
                                @endisset
                                @if (isset($property->latitude) && isset($property->longitude))
                                   <li class="list-group-item d-flex justify-content-between align-items-center disabled small">
                                        <i class="nav-icon fa fa-map"></i>Coordinates:
                                        <span class="small">{{ $property->latitude }}, {{ $property->longitude }}</span>
                                   </li>
                                @endif

                            </ul>

                            <hr>

                            @if(!auth()->user()->hasrole('Client'))
                               <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action active">Menu </a>
                                    <a href="/property/{{ $property->id }}/reports"
                                        class="list-group-item list-group-item-action">Reports</a>
                                    <a href="/property/{{ $property->id }}/tasks"
                                        class="list-group-item list-group-item-action">Tasks</a>
                                </div>
                            @endif

                        </div>
                    </div>
                </div> <!-- /.col-3 -->
            </div> <!-- / .row -->
        </div> <!-- /.card-body -->
    </div> <!-- /.card -->

    <div class="row mt-5">
        <!-- PROPERTY UNITS -->
         <!-- FIX LOGIC TO USE AND(&&) COMPARISON INSTEAD OF OR(||)  -->
        @if ($property->has_units)
            <div class="col-md-4" id="unitSection">
                    <div class="card card-height">
                      @if($property->units->count() > 0)
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between align-items-center bg-white">
                                <div>
                                    <h5 class="mb-0">Units</h5>
                                </div>
                                <div class="mr-0">
                                    <a href="{{ route('property.units', $property->id) }}" class="btn btn-sm btn-light">view({{ $property->units->count() }})</a>
                                    @can('create property')
                                        <a href="{{ route('new.unit', $property->id) }}"
                                            class="btn btn-primary btn-xs mr-2">Add New</span>
                                        </a>
                                    @endcan
                                    <button id="xUnit" type="button" class="btn btn-sm btn-light">&times</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-reposive">
                                <table class="table table-borderless table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col">Unit</th>
                                            <th scope="col">Type</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($property->units->take(5) as $unit)
                                        <tr>
                                                <td>
                                                    <a href="{{ route('show.unit', $unit->id)}}">{{ $unit->unit_number }}</a>
                                                </td>
                                                <td>{{ $unit->unit_type }}</td>
                                                <td>
                                                    @if ($unit->status == 'under_maintenance')
                                                        <span class="badge badge-warning">Under Maintenance</span>
                                                    @elseif ($unit->status == 'available')
                                                        <span class="badge badge-primary">Available</span>
                                                    @elseif ($unit->status == 'sold')
                                                        <span class="badge badge-danger">Sold</span>
                                                    @elseif ($unit->status == 'leased')
                                                        <span class="badge badge-success">Leased</span>
                                                    @elseif ($unit->status == 'vacant')
                                                        <span class="badge badge-info">Vacant</span>
                                                    @else
                                                        <span class="badge badge-secondary">Unavailable</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>  
                        @else
                            <div class="card-body d-flex align-items-center justify-content-center no-records-bg">
                                <div class="text-center text-white p-4">
                                    @can('create property')
                                        <p class="lead mb-4" style="text-shadow: 2px 2px 6px rgba(0,0,0,0.7);">No Units</p>
                                        <a href="{{ route('new.unit', $property->id) }}"
                                            class="btn btn-primary btn-lg mr-2">Add Unit</span>
                                        </a>
                                    @else
                                        <p class="lead mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">No Units</p>
                                    @endcan
                                </div>
                            </div>
                        @endif
                    </div>
            </div>
        @endif
        <!-- PROPERTY VIEWINGS -->
        <div class="col-md-{{ $property->has_units ? '8' : '6' }}">
            <div class="card card-height">
                @if ($property->viewings->count() > 0 )
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between align-items-center bg-white">
                            <div>
                                <h5 class="mb-0">Viewings</h5>
                            </div>
                            <div class="mr-0">
                                <a href="{{ route('property.viewing', $property->id) }}" class="btn btn-sm btn-light">view({{ $property->viewings->count() }})</a>
                                @can('create property')
                                    <a href="{{ route('property.viewing', ['id' => $property->id, 'modal' => 'viewings']) }}"
                                        class="btn btn-primary btn-xs">Scheduled Viewings</span>
                                    </a>
                                @endcan
                                <button type="button" class="btn btn-sm btn-light">&times</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-reposive">
                            <table class="table table-borderless table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Client</th>
                                        <th scope="col">Property/Unit</th>
                                        <th scope="col">Agent</th>
                                        <th scope="col">Schedule</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($property->viewings as $viewing)
                                        <tr>
                                            <td>
                                                <a href="{{ url('show.viewing', $viewing->id) }}">
                                                {{ $viewing->client_name }}
                                                </a>
                                            </td>
                                            <td>{{ $viewing->property->name ?? $property->propertyUnit->unit_number }}</td>
                                            <td>{{ $viewing->agent->full_name }}</td>
                                            <td>{{ $viewing->scheduled_at->format('d F, Y h:i A') ?? '' }}</td>
                                            <td>
                                                @if ($viewing->status == 'scheduled')
                                                    <span class="badge badge-warning float-right">Scheduled</span>
                                                @elseif ($viewing->status == 'completed')
                                                    <span class="badge badge-success float-right">Completed</span>
                                                @elseif ($viewing->status == 'cancelled')
                                                    <span class="badge badge-danger float-right">Cancelled</span>
                                                @elseif ($viewing->status == 'rescheduled')
                                                    <span class="badge badge-info float-right">Rescheduled</span>
                                                @else
                                                    <span class="badge badge-secondary float-right">In Progress</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="card-body d-flex align-items-center justify-content-center no-records-bg">
                        <div class="text-center text-white p-4">
                            @can('create property')
                                <p class="lead mb-4" style="text-shadow: 2px 2px 6px rgba(0,0,0,0.7);">No viewings have been scheduled for this property yet.</p>
                                <a href="{{ route('property.viewing', ['id' => $property->id, 'modal' => 'viewings']) }}"
                                    class="btn btn-primary btn-lg mr-2">Scheduled Viewings</span>
                                </a>
                            @else
                                <p class="lead mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">No viewings have been scheduled for this property yet.</p>
                            @endcan
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- PROPERTY LEASES -->
        <div class="col-md-6">
            <div class="card card-height">
                @if ($property->leases->count() > 0 )
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between align-items-center bg-white">
                            <div>
                                <h5 class="mb-0">Leases</h5>
                            </div>
                            <div class="mr-0">
                                <a href="{{ route('property.leases', $property->id) }}" class="btn btn-sm btn-light">view({{ $property->leases->count() }})</a>
                                @can('create property')
                                    <a href="{{ route('new.lease', $property->id) }}"
                                        class="btn btn-primary btn-xs">Add New</span>
                                    </a>
                                @endcan
                                <button type="button" class="btn btn-sm btn-light">&times</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-reposive">
                            <table class="table table-borderless table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Tenant</th>
                                        <th scope="col">Property/Unit</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($property->leases->take(5) as $lease)
                                    <tr>
                                            <td>
                                                <a href="">
                                                {{ $lease->tenant->full_name }}
                                                </a>
                                            </td>
                                            <td>{{ $lease->property->name ?? $property->propertyUnit->unit_number }}</td>
                                            <td>{{ $lease->end_date->format('F, Y') ?? '' }}</td>
                                            <td>
                                                @if ($lease->status == 'pending')
                                                    <span class="badge badge-warning float-right">Pending</span>
                                                @elseif ($lease->status == 'active')
                                                    <span class="badge badge-primary float-right">Active</span>
                                                @elseif ($lease->status == 'expired')
                                                    <span class="badge badge-danger float-right">Expired</span>
                                                @elseif ($lease->status == 'renewed')
                                                    <span class="badge badge-success float-right">Renewed</span>
                                                @else
                                                    <span class="badge badge-secondary float-right">{{ Str::headline($lease->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="card-body d-flex align-items-center justify-content-center no-records-bg">
                        <div class="text-center text-white p-4">
                            @can('create property')
                                <p class="lead mb-4" style="text-shadow: 2px 2px 6px rgba(0,0,0,0.7);">No Leases</p>
                                <a href="{{-- route('new.Lease', $property->id) --}}"
                                    class="btn btn-primary btn-lg mr-2">New Lease</span>
                                </a>
                            @else
                                <p class="lead mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">No Leases</p>
                            @endcan
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- PROPERTY TASKS -->
        <div class="col-md-6">
            <div class="card card-height">
                @if ($property->tasks->count() > 0 )
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between align-items-center bg-white">
                            <div>
                                <h5 class="mb-0">Tasks</h5>
                            </div>
                            <div class="mr-0">
                                <a href="{{ route('property.tasks', $property->id) }}" class="btn btn-sm btn-light">view({{ $property->tasks->count() }})</a>
                                @can('create property')
                                    <a href="{{ route('property.tasks', ['id' => $property->id, 'modal' => 'tasks']) }}"
                                        class="btn btn-primary btn-xs">Add New</span>
                                    </a>
                                @endcan
                                <button type="button" class="btn btn-sm btn-light">&times</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-reposive">
                            <table class="table table-borderless table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Assignee</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Priority</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($property->tasks as $task)
                                        <tr>
                                            <td>
                                                <a href="{{ url('show.task', $task->id) }}">
                                                {{ $task->title }}
                                                </a>
                                            </td>
                                            <td>{{ $task->assignee->name }}</td>
                                            <td>{{ $task->due_date }}</td>
                                            <td>
                                                @if ($task->priority == 'hign')
                                                    <span class="badge badge-warning float-right">Hign</span>
                                                @elseif ($task->priority == 'medium')
                                                    <span class="badge badge-success float-right">Medium</span>
                                                @elseif ($task->priority == 'low')
                                                    <span class="badge badge-primary float-right">Low</span>
                                                @else
                                                <span class="badge badge-info float-right">Urgent</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($task->status == 'pending')
                                                    <span class="badge badge-warning float-right">Pending</span>
                                                @elseif ($task->status == 'completed')
                                                    <span class="badge badge-success float-right">Completed</span>
                                                @elseif ($task->status == 'cancelled')
                                                    <span class="badge badge-danger float-right">Cancelled</span>
                                                @elseif ($task->status == 'open')
                                                    <span class="badge badge-primary float-right">Open</span>
                                                @else
                                                    <span class="badge badge-info float-right">In Progress</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="card-body d-flex align-items-center justify-content-center no-records-bg">
                        <div class="text-center text-white p-4">
                            @can('create property')
                                <p class="lead mb-4" style="text-shadow: 2px 2px 6px rgba(0,0,0,0.7);">No Tasks</p>
                                <a href="{{ route('property.tasks', ['id' => $property->id, 'modal' => 'tasks']) }}"
                                    class="btn btn-primary btn-lg mr-2">New Task</span>
                                </a>
                            @else
                                <p class="lead mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">No Tasks</p>
                            @endcan
                        </div>
                    </div>
                @endif
            </div>
        </div> <!-- /.col-6 -->
        <!-- PROPERTY MAINTENANCE REQUESTS -->
        <div class="col-md-7">
            <div class="card card-height">
                @if ($property->maintenanceRequests->count() > 0 )
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between align-items-center bg-white">
                            <div>
                                <h5 class="mb-0">Maintenance Requests</h5>
                            </div>
                            <div class="mr-0">
                                <a href="{{ route('property.maintenanceRequest', $property->id) }}" class="btn btn-sm btn-light">view({{ $property->maintenanceRequests->count() }})</a>
                                @can('create property')
                                    <a href="{{ route('property.maintenanceRequest', ['id' => $property->id, 'modal' => 'requests']) }}"
                                        class="btn btn-primary btn-xs">Add New</span>
                                    </a>
                                @endcan
                                <button type="button" class="btn btn-sm btn-light">&times</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-reposive">
                            <table class="table table-borderless table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Property/Unit</th>
                                        <th scope="col">Reporter</th>
                                        <th scope="col">Priority</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($property->maintenanceRequests as $maintenanceRequest)
                                        <tr>
                                            <td>
                                                <a href="{{ url('show.maintenance-request', $maintenanceRequest->id) }}">
                                                {{ $maintenanceRequest->title }}
                                                </a>
                                            </td>
                                            <td>{{ $maintenanceRequest->property->name ?? $property->propertyUnit->unit_number }}</td>
                                            <td>{{ $maintenanceRequest->reporter->name }}</td>
                                            <td>{{ $maintenanceRequest->priority }}</td>
                                            <td>
                                                @if ($maintenanceRequest->status == 'pending')
                                                    <span class="badge badge-warning float-right">Pending</span>
                                                @elseif ($maintenanceRequest->status == 'completed')
                                                    <span class="badge badge-success float-right">Completed</span>
                                                @elseif ($maintenanceRequest->status == 'cancelled')
                                                    <span class="badge badge-danger float-right">Cancelled</span>
                                                @elseif ($maintenanceRequest->status == 'open')
                                                    <span class="badge badge-primary float-right">Open</span>
                                                @elseif ($maintenanceRequest->status == 'in_progress')
                                                    <span class="badge badge-primary float-right">In Progress</span>
                                                @else
                                                    <span class="badge badge-info float-right">{{ $maintenanceRequest->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="card-body d-flex align-items-center justify-content-center no-records-bg">
                        <div class="text-center text-white p-4">
                            @can('create property')
                                <p class="lead mb-4" style="text-shadow: 2px 2px 6px rgba(0,0,0,0.7);">No Maintenance Requests</p>
                                <a href="{{ route('property.maintenanceRequest', ['id' => $property->id, 'modal' => 'requests']) }}"
                                    class="btn btn-primary btn-lg mr-2">New Maintenance Requests</span>
                                </a>
                            @else
                                <p class="lead mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">No Maintenance Requests</p>
                            @endcan
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- PROPERTY DOCUMENTS -->
        <div class="col-md-5">
            <div class="card card-height">
                @if ($property->documents->count() > 0)
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between align-items-center bg-white">
                            <div>
                                <h5 class="mb-0">Documents</h5>
                            </div>
                            <div class="mr-0">
                                <a href="{{ route('property.document', $property->id) }}" class="btn btn-sm btn-light">view({{ $property->documents->count() }})</a>
                                @can('create property')
                                    <a href="{{ url('addp-file/' . $property->id) }}"
                                        class="btn btn-primary btn-xs">New File</span>
                                    </a>
                                @endcan
                                <button type="button" class="btn btn-sm btn-light">&times</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-reposive">
                            <table class="table table-borderless table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">Document</th>
                                        <th scope="col">Upload By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($property->documents->take(5) as $document)
                                        <tr>
                                            <td>
                                                <a target="_blank"
                                                    href="{{ URL::to('public/documents/' . $document->file_path) }}">{{ $document->title }}
                                                <span class="badge badge-info float-right">{{ $document->file_type }}</span></a>
                                            </td>
                                            <td>{{ $document->uploader->name ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="card-body d-flex align-items-center justify-content-center no-records-bg">
                        <div class="text-center text-white p-4">
                            @can('create property')
                                <p class="lead mb-4" style="text-shadow: 2px 2px 6px rgba(0,0,0,0.7);">No Documents</p>
                                <a href="{{-- route('new.document', $property->id) --}}"
                                    class="btn btn-primary btn-lg mr-2">New Document</span>
                                </a>
                            @else
                                <p class="lead mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">No Documents</p>
                            @endcan
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div> <!-- /.row -->

    <!-- MAP MODAL -->
    <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title">
                        Property location
                     </h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span></button>
                 </div>
                 <div class="modal-body">
                    <!-- Address Info -->
                    <p><strong>Address:</strong>{{ $property->address ?? 'N/A' }}</p>
                    <p><strong>State:</strong>{{ $property->state ?? 'N/A' }}</p>
                    <p><strong>Country:</strong>{{ $property->country ?? 'N/A' }}</p>
                    <!-- Map container -->
                    @if ($property->latitude && $property->longitude)
                       <div id="map" style="height: 400px;"></div>
                    @else
                       <div class="alert alert-warning">
                          No location coordinates available for this property.
                       </div>
                    @endif
                 </div>
             </div>
          </div>
    </div>
    <!-- Modal for adding images -->
    <div class="modal fade" id="addImageModal" tabindex="-1" role="dialog" aria-labelledby="addImageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addImageModalLabel">Upload Property Images</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="imageUploadForm" action="{{ route('property.uploadImage', $property->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="images">Select Images</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                            <small class="form-text text-muted">You can select multiple image files (JPG, PNG, GIF, SVG, WEBP up to 5MB each).</small>
                        </div>
                        <div class="form-group">
                            <label for="caption">Caption (optional, applies to all uploaded images)</label>
                            <input type="text" name="caption" id="caption" class="form-control">
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" class="form-check-input">
                            <label class="form-check-label" for="is_featured">Set as Featured Image (first image uploaded)</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload Images</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

<!-- <script src="{{ asset('plugins/js/property-scripts.js') }}" ></script> -->

<style>
    .hidden {
        display: none;
    }
    .card-height {
        min-height: 380px;
    }
    .no-records-bg {
        background-image: url(' {{ asset('public/property_images/no-records.jpg') }} ');
        background-size: contain;
        background-position: center center;
        background-repeat: no-repeat;
        background-blend-mode: multiply;
        background-color: rgba(110, 109, 109, 0.5);
        /* background-color: rgba(255, 255, 255, 0.5); */
    }
    .featured-item:hover {
        border-color: #5f1d05ff !important;
        transform: scale(1.05);
        box-shadow: 0 4px 16px rgba(51,122,183,0.15);
    }
    #expandGalleryBtn, #collapseGalleryBtn {
        font-weight: bold;
        letter-spacing: 0.5px;
    }
    #expandGalleryBtn:hover, #collapseGalleryBtn:hover {
        background: #337ab7;
        color: #fff;
    }

    .property-info-box {
        background-color: #e6f5fd;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        color: #007fae;
        min-width: 120px;
        margin-bottom: 15px;
    }
    .property-info-box i {
        font-size: 1.5rem;
        margin-bottom: 5px;
    }
    .property-info-box .label {
        font-size: 0.85rem;
        color: #666;
    }
    .property-info-box .value {
        font-size: 1.1rem;
        font-weight: bold;
    }
    .agent-card {
        max-width: 400px;
        border: 1px solid #eee;
        padding: 20px;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 2px 10px rgb(0,0,0,0,05);
    }
    .agent-name {
        font-size: 1.2rem;
        font-weight: 600;
    }
    .contact-label {
        color: #666;
        font-weight: 500;
    }
    .contact-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    .contact-item i {
        color: #00aaf1;
        text-align: center;
        font-size: 0.7rem;
    }
    .contact-value {
        color: #333;
    }
    .listing-link {
        color: #00aaf1;
        font-size: 0.95rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 4px;
    }
    .amenity-box {
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 10px 15px;
      margin: 5px;
      display: flex;
      align-items: center;
      min-width: 100px;
    }
    .amenity-icon {
      font-size: 20px;
      margin-right: 10px;
      color: #333;
    }
    .thumb-anim {
        opacity: 0;
        animation: fadeInThumb 0.5s forwards;
    }
    @keyframes fadeInThumb {
        to { opacity: 1; }
    }
    #allFeaturedsRow {
        animation: fadeInRow 0.4s;
    }
    @keyframes fadeInRow {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: none; }
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@if ($property->latitude && $property->longitude)
<script>
    let mapInitialized = false;
    $('#mapModal').on('show.bs.modal', function () {
        // Prevent multiple map instances
        if (!mapInitialized){
            const map = L.map('map').setView([{{ $property->latitude }},
            {{ $property->longitude }}], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap constributors'
            }).addTo(map);
            L.marker([{{ $property->latitude }},
            {{ $property->longitude }}])
             .addTo(map)
             .bindPopup("{{ $property->name ?? 'Property' }}")
             .openPopup();
        }
        // Resize fix
        setTimeout(() => {
        map.invalidateSize();
       }, 300);
       mapInitialized = true;
    });
</script>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let mainPropertyImage = document.getElementById('mainPropertyImage');
        function updateFeaturedListeners() {
            let featuredItems = document.querySelectorAll('.featured-item');
            featuredItems.forEach(function(featured) {
                featured.onclick = function() {
                    featuredItems.forEach(function(item) { item.classList.remove('active'); });
                    this.classList.add('active');
                    // Animate main image
                    mainPropertyImage.classList.add('main-img-anim');
                    mainPropertyImage.src = this.dataset.mainImage;
                    setTimeout(function(){
                        mainPropertyImage.classList.remove('main-img-anim');
                    }, 350);
                };
            });
        }
        updateFeaturedListeners();
        let activeFeatured = document.querySelector('.featured-item.active');
        if (activeFeatured) {
            mainPropertyImage.src = activeFeatured.dataset.mainImage;
        } else {
            let featuredItems = document.querySelectorAll('.featured-item');
            if (featuredItems.length > 0) {
                featuredItems[0].classList.add('active');
                mainPropertyImage.src = featuredItems[0].dataset.mainImage;
            }
        }
        // Expand/collapse gallery logic
        let expandBtn = document.getElementById('expandGalleryBtn');
        let collapseBtn = document.getElementById('collapseGalleryBtn');
        let featuredRow = document.getElementById('featuredRow');
        let allFeaturedsRow = document.getElementById('allFeaturedsRow');
        if (expandBtn) {
            expandBtn.onclick = function() {
                featuredRow.style.display = 'none';
                allFeaturedsRow.style.display = 'inline-block';
                allFeaturedsRow.classList.add('fadeInRow');
                updateFeaturedListeners();
            };
        }
        if (collapseBtn) {
            collapseBtn.onclick = function() {
                allFeaturedsRow.style.display = 'none';
                featuredRow.style.display = 'inline-block';
                updateFeaturedListeners();
            };
        }
        // Main image animation
        let style = document.createElement('style');
        style.innerHTML = `.main-img-anim { animation: mainImgFade 0.35s; } @keyframes mainImgFade { from { opacity: 0.5; transform: scale(0.97); } to { opacity: 1; transform: scale(1); } }`;
        document.head.appendChild(style);
    });
</script>

