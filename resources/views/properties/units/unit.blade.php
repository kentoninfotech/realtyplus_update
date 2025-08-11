@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $unit->property->name }}:  {{ $unit->unit_number}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Unit Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-widget widget-user">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <div class="widget-user-header text-white"
            style="background: @if ($featuredImage) url({{ asset('public/' . $featuredImage->image_path) }}); background-size: cover; background-repeat: no-repeat;  @endif height: 250px !important; text-shadow: 2px 2px #000; background-color: grey;">
            <h1 class="text-right">{{ $unit->unit_number }}</h1>
            <h5 class="widget-user-desc text-right"><i class="nav-icon fas fa-map-marker-alt"></i> {{ $unit->property->address ?? '' }}, {{ $unit->property->state }}, {{ $unit->property->country }}
            </h5>
            <h5 class="badge badge-primary badge-pill float-right">{{ $unit->unit_type }}</h5>
        </div>

        <div class="card-footer">
            <div class="row">
                {{--
                @if ($unit->has_units)
                    <div class="col-sm-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header">{{ $unit->units->count() }}</h5>
                            <span class="description-text">UNITS</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                @endif
                --}}
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                    <div class="description-block">
                        <h5 class="description-header">₦{{ number_format($unit->rent_price, 0, '.', ',') }}
                        </h5>
                        <span class="description-text">RENT PRICE</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                    <div class="description-block">
                        <h5 class="description-header"> ₦{{ number_format($unit->sale_price, 0, '.', ',') }}</h5>
                        <span class="description-text">SALE PRICE (VALUE)</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
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
                                    <img src="{{ asset('public/'.$displayImage) }}" alt="{{ $unit->unit_number }}" class="img-responsive img-featured center-block" id="mainUnitImage" style="max-width: 100%; height: auto; margin-bottom: 15px; box-shadow: 0 4px 16px rgba(0,0,0,0.12); transition: box-shadow 0.3s;">
                                    <div id="featuredGallery" style="margin-bottom: 10px; background: #f8f9fa; border-radius: 8px; padding: 10px 8px 6px 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                                        @php $maxVisible = 6; @endphp
                                        <div id="featuredRow" style="display: inline-block;">
                                            @foreach($unit->images->take($maxVisible) as $img)
                                                @php $borderColor = $img->is_featured ? '#337ab7' : 'transparent'; @endphp
                                                <img src="{{ asset('public/'.$img->image_path) }}"
                                                    alt="Featured"
                                                    class="img-featured featured-item {{ $img->is_featured ? 'active' : '' }} thumb-anim"
                                                    data-main-image="{{ asset('public/'.$img->image_path) }}"
                                                    style="width: 80px; height: 60px; object-fit: cover; cursor: pointer; border: 2px solid {{ $borderColor }}; display: inline-block; margin-right: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: border-color 0.3s, transform 0.2s; {{ $img->is_featured ? 'transform: scale(1.08); z-index:2;' : '' }}">
                                            @endforeach
                                            @if($unit->images->count() > $maxVisible)
                                                <button id="expandGalleryBtn" class="btn btn-info btn-xs" style="vertical-align: top; margin: 15px 0 0 2px; font-weight: bold; letter-spacing: 0.5px; transition: background 0.2s;">+{{ $unit->images->count() - $maxVisible }} more</button>
                                            @endif
                                        </div>
                                        <div id="allFeaturedsRow" style="display: none; margin-top: 10px;">
                                            @foreach($unit->images as $img)
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
                                        <p class="text-danger" style="margin:0; font-size: 18px;">No image uploaded for this unit.</p>
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
                                @if($unit->images->count())
                                    <div class="text-center" style="margin-top: 15px;">
                                        <p>Manage Featureds:</p>
                                        @foreach($unit->images as $img)
                                            @if(!$img->is_featured)
                                                <form action="{{ route('unit.setFeaturedImage', [$unit->id, $img->id]) }}" method="POST" style="display:inline-block; margin-right: 5px; margin-bottom: 5px;">
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
                                        @if ($unit->bedrooms)
                                            <div class="col-6 col-sm-4 col-md-2">
                                                <div class="unit-info-box">
                                                    <i class="fa fa-bed"></i>
                                                    <div class="label">Bedrooms</div>
                                                    <div class="value">{{ $unit->bedrooms }}</div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($unit->bathrooms)
                                            <div class="col-6 col-sm-4 col-md-2">
                                                <div class="unit-info-box">
                                                    <i class="fa fa-bath"></i>
                                                    <div class="label">Bathrooms</div>
                                                    <div class="value">{{ $unit->bathrooms }}</div>
                                                </div>
                                            </div>
                                        @endif
                                        @isset($unit->property->year_built)
                                            <div class="col-6 col-sm-4 col-md-2">
                                                <div class="unit-info-box">
                                                    <i class="fa fa-calendar"></i>
                                                    <div class="label">Year Built</div>
                                                    <div class="value">{{ $unit->property->year_built}}</div>
                                                </div>
                                            </div>
                                        @endisset
                                        @isset($unit->area_sqm)
                                            <div class="col-6 col-sm-4 col-md-2">
                                                <div class="unit-info-box">
                                                    <i class="fa fa-arrows-alt"></i>
                                                    <div class="label">Area</div>
                                                    <div class="value">{{ $unit->area_sqm }} sqm</div>
                                                </div>
                                            </div>
                                        @endisset
                                        @isset($unit->square_footage)
                                            <div class="col-6 col-sm-4 col-md-2">
                                                <div class="unit-info-box">
                                                    <i class="fa fa-square-o"></i>
                                                    <div class="label">Lot Size</div>
                                                    <div class="value">{{ $unit->square_footage}} sq ft</div>
                                                </div>
                                            </div>
                                        @endisset
                                        @isset($unit->available_from)
                                            <div class="col-6 col-sm-4 col-md-2">
                                                <div class="unit-info-box">
                                                    <i class="fa fa-calendar"></i>
                                                    <div class="label">Date Acquired</div>
                                                    <div class="value">{{ $unit->available_from->format('M, Y')}}</div>
                                                </div>
                                            </div>
                                        @endisset

                                    </div>
                                </div>
                                <h5 class="title my-3">Additional Details</h5>
                                @isset($unit->description)
                                    <div class="card-text">
                                        <h6 class="title">Description: </h6>
                                        <p style="text-align: left">{!! $unit->description !!} </p>
                                    </div>
                                @endisset
                                <table class="table mb-4">
                                    <tbody>
                                        <tr>
                                            <th>Unit Status:</th>
                                            <td>
                                                <span class="badge badge-{{
                                                    $unit->status == 'available' ? 'success' :
                                                    ($unit->status == 'sold' ? 'danger' :
                                                    ($unit->status == 'under_maintenance' ? 'warning' :
                                                    ($unit->status == 'leased' ? 'danger' : 'info')))
                                                    }} badge-pill">
                                                    {{ ucwords(str_replace('_', ' ', $unit->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @isset($unit->deposit_amount)
                                            <tr>
                                                <th>Deposit Amount:</th>
                                                <td>
                                                    <span class="small">₦{{ number_format($unit->deposit_amount, 0, '.', ',') }}</span>
                                                </td>
                                            </tr>
                                        @endisset
                                        @isset($unit->available_from)
                                            <tr>
                                                <th>Available From:</th>
                                                <td>
                                                    <span class="small">{{ $unit->available_from->format('d F, Y h:i A')  }}</span>
                                                </td>
                                            </tr>
                                        @endisset
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>    
                </div> <!-- /.col-9 -->
                <div class="col-md-3">

                    <div class="card">
                        <div class="card-body">
                            <div class="owner-agent">
                                 <!-- PROPERTY OWNER  -->
                                @if($unit->owner || $unit->property->owner)
                                    <div class="agent-card">
                                        <div>
                                            <div class="text-muted">Owner</div>
                                            <div class="agent-name">{{ $unit->owner->full_name ?? $unit->property->owner->full_name ?? '' }}</div>
                                            <a href="{{ route('owner.property', $unit->owner->id ?? $unit->property->owner->id) }}" class="listing-link">View Properties</a>
                                            <div class="contact-item">
                                                <i class="fa fa-email ml-auto mr-2"></i>
                                                <span class="contact-value">{{ $unit->owner->email ?? $unit->property->owner->email ?? '' }}</span>
                                            </div>
                                            <div class="contact-item">
                                                <i class="fa fa-phone ml-auto mr-2"></i>
                                                <span class="contact-value">{{ $unit->owner->phone_number ?? $unit->property->owner->phone_number ?? '' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <!-- PROPERTY AGENT  -->
                                @isset($unit->property->agent)
                                    <div class="agent-card">
                                        <div>
                                        <div class="text-muted">Agent</div>
                                        <div class="agent-name">{{ $unit->property->agent->full_name ?? '' }}</div>
                                        <a href="#" class="listing-link">View Agent Listings</a>
                                        <div class="contact-item">
                                            <i class="fa fa-phone ml-auto mr-2"></i>
                                            <span class="contact-value">{{ $unit->property->agent->phone_number ?? '' }}</span>
                                        </div>
                                        <div class="contact-item">
                                            <i class="fa fa-email ml-auto mr-2"></i>
                                            <span class="contact-value">{{ $unit->property->agent->email ?? '' }}</span>
                                        </div>
                                        </div>
                                    </div>
                                @endisset
                            </div>

                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center active small">
                                    Property Details
                                </li>
                                @isset($unit->bedrooms)
                                   <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Bedrooms
                                        <span class="small">{{ $unit->bedrooms  }}</span>
                                  </li>
                                @endisset
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center">
                                    Unit Status
                                    <span class="badge badge-{{
                                        $unit->status == 'available' ? 'success' :
                                        ($unit->status == 'sold' ? 'danger' :
                                        ($unit->status == 'under_maintenance' ? 'warning' :
                                        ($unit->status == 'leased' ? 'danger' : 'info')))
                                        }} badge-pill">
                                        {{ ucwords(str_replace('_', ' ', $unit->status)) }}
                                    </span>
                                </li>
                                @isset($unit->area_sqm)
                                   <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Area SQM
                                        <span class="small">{{ $unit->area_sqm  }}</span>
                                  </li>
                                @endisset
                                @isset($unit->square_footage)
                                   <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Square Footage
                                        <span class="small">{{ $unit->square_footage  }}</span>
                                  </li>
                                @endisset
                                @isset($unit->available_from)
                                   <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Available From
                                        <span class="small">{{ $unit->available_from->format('d F, Y H:i' ?? '')  }}</span>
                                   </li>
                                @endisset

                            </ul>

                            <hr>

                            @if(!auth()->user()->hasrole('Client'))
                               <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action active">Menu </a>
                                    <a href="/property/{{ $unit->id }}/reports"
                                        class="list-group-item list-group-item-action">Reports</a>
                                    <a href="/property/{{ $unit->id }}/tasks"
                                        class="list-group-item list-group-item-action">Tasks</a>
                                </div>
                            @endif

                        </div>
                    </div>
                </div> <!-- /.col-3 -->
            </div> <!-- /.row -->
        </div> <!-- /.card-body -->
    </div> <!-- /.card -->

    <div class="row mt-5">
        <!-- UNIT LEASES -->
        <div class="col-md-6">
            <div class="card card-height">
                @if ($unit->leases->count() > 0 )
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between align-items-center bg-white">
                            <div>
                                <h5 class="mb-0">Leases</h5>
                            </div>
                            <div class="mr-0">
                                <a href="{{ url('unit.leases', $unit->id) }}" class="btn btn-sm btn-light">view({{ $unit->leases->count() }})</a>
                                @can('create property')
                                    <a href="{{ url('new.lease', $unit->id) }}"
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
                                        <th scope="col">Tenant</th>
                                        <th scope="col">Property/Unit</th>
                                        <th scope="col">Due Date</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($unit->leases->take(5) as $lease)
                                    <tr>
                                            <td>
                                                <a href="">
                                                {{ $lease->tenant->full_name }}
                                                </a>
                                            </td>
                                            <td>{{ $lease->property->name ?? $unit->unit_number }}</td>
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
                                <a href="{{-- route('new.lease', $unit->id) --}}"
                                    class="btn btn-primary btn-lg mr-2">Add Lease</span>
                                </a>
                            @else
                                <p class="lead mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">No Leases</p>
                            @endcan
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- UNIT MAINTENANCE REQUESTS -->
        <div class="col-md-6">
            <div class="card card-height">
                @if ($unit->maintenanceRequests->count() > 0 )
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between align-items-center bg-white">
                            <div>
                                <h5 class="mb-0">Maintenance Requests</h5>
                            </div>
                            <div class="mr-0">
                                <a href="{{ route('unit.maintenanceRequest', $unit->id) }}" class="btn btn-sm btn-light">view({{ $unit->maintenanceRequests->count() }})</a>
                                @can('create property')
                                    <a href="{{ route('unit.maintenanceRequest', ['id' => $unit->id, 'modal' => 'requests']) }}"
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
                                    @foreach ($unit->maintenanceRequests as $maintenanceRequest)
                                        <tr>
                                            <td>
                                                <a href="{{ url('show.maintenance-request', $maintenanceRequest->id) }}">
                                                {{ $maintenanceRequest->title }}
                                                </a>
                                            </td>
                                            <td>{{ $maintenanceRequest->property->name ?? $unit->unit_number }}</td>
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
                                <p class="lead mb-4" style="text-shadow: 2px 2px 6px rgba(0,0,0,0.7);">No Maintenance Requests</p>
                                <a href="{{ route('unit.maintenanceRequest', ['id' => $unit->id, 'modal' => 'requests']) }}"
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
        
        <!-- UNIT VIEWINGS -->
        <div class="col-md-8">
            <div class="card card-height">
                @if ($unit->viewings->count() > 0 )
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between align-items-center bg-white">
                            <div>
                                <h5 class="mb-0">Viewings</h5>
                            </div>
                            <div class="mr-0">
                                <a href="{{ route('unit.viewing', $unit->id) }}" class="btn btn-sm btn-light">view({{ $unit->viewings->count() }})</a>
                                @can('create property')
                                    <a href="{{ route('unit.viewing', ['id' => $unit->id, 'modal' => 'viewings']) }}"
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
                                        <th scope="col">Property</th>
                                        <th scope="col">Agent</th>
                                        <th scope="col">Schedule</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($unit->viewings as $viewing)
                                        <tr>
                                            <td>
                                                <a href="{{ url('show.viewing', $viewing->id) }}">
                                                {{ $viewing->client_name }}
                                                </a>
                                            </td>
                                            <td>{{ $viewing->property->name ?? $unit->unit_number }}</td>
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
                                <p class="lead mb-4" style="text-shadow: 2px 2px 6px rgba(0,0,0,0.7);">No Viewings</p>
                                <a href="{{ route('unit.viewing', ['id' => $unit->id, 'modal' => 'viewings']) }}"
                                    class="btn btn-primary btn-lg mr-2">Schdule Viewing</span>
                                </a>
                            @else
                                <p class="lead mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">No Viewings</p>
                            @endcan
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div> <!-- /.row -->

    <!-- Modal for adding images -->
    <div class="modal fade" id="addImageModal" tabindex="-1" role="dialog" aria-labelledby="addImageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addImageModalLabel">Upload Unit Images</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="imageUploadForm" action="{{ route('unit.uploadImage', $unit->id) }}" method="POST" enctype="multipart/form-data">
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
    .unit-info-box {
        background-color: #e6f5fd;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        color: #007fae;
        min-width: 120px;
        margin-bottom: 15px;
    }
    .unit-info-box i {
        font-size: 1.5rem;
        margin-bottom: 5px;
    }
    .unit-info-box .label {
        font-size: 0.85rem;
        color: #666;
    }
    .unit-info-box .value {
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let mainUnitImage = document.getElementById('mainUnitImage');
        function updateFeaturedListeners() {
            let featuredItems = document.querySelectorAll('.featured-item');
            featuredItems.forEach(function(featured) {
                featured.onclick = function() {
                    featuredItems.forEach(function(item) { item.classList.remove('active'); });
                    this.classList.add('active');
                    // Animate main image
                    mainUnitImage.classList.add('main-img-anim');
                    mainUnitImage.src = this.dataset.mainImage;
                    setTimeout(function(){
                        mainUnitImage.classList.remove('main-img-anim');
                    }, 350);
                };
            });
        }
        updateFeaturedListeners();
        let activeFeatured = document.querySelector('.featured-item.active');
        if (activeFeatured) {
            mainUnitImage.src = activeFeatured.dataset.mainImage;
        } else {
            let featuredItems = document.querySelectorAll('.featured-item');
            if (featuredItems.length > 0) {
                featuredItems[0].classList.add('active');
                mainUnitImage.src = featuredItems[0].dataset.mainImage;
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
