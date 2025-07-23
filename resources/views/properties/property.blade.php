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
                        <i class="fas fa-map-marker-alt"></i>
                        <a href="#">View on</a> <br>
                        <span class="description-text">MAP</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><i>Owner: </i>
                            <a href=""><b>{{ $property->owner->full_name ?? '' }}</b></a>
                           </h4>
                            <p class="card-text small"><i class="nav-icon fas fa-map-marker-alt"></i>
                                {{ $property->owner->address ?? '' }}
                            </p>
                            @if ($property->agent)
                                <hr>
                                <h4 class="lead"><i>Agent: </i>
                                    <a href=""><b>{{ $property->agent->full_name ?? '' }}</b></a>
                                </h4>
                                <p class="card-text small"><i class="nav-icon fas fa-map-marker-alt"></i>
                                {{ $property->agent->address ?? '' }}
                            </p>
                            @endif

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
                                        <span class="small">{{ $property->listed_at->format('Y-m-d H:i')  }}</span>
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

                </div>
                <div class="col-md-9">

                    <!-- <div class="row">
                        <h5>Property Description: </h5>
                        <p style="text-align: left">{!! $property->details !!} </p>
                    </div>
                    <hr> -->

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
                                <h5 class="title">Amenities</h5>
                                <div class="d-flex flex-wrap justify-content-start">
                                    @foreach ($property->amenities as $amenity)
                                        <div class="amenity-box">
                                            <i class="{{ $amenity->icon }} amenity-icon"></i>
                                            <span>{{ $amenity->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                @isset($property->description)
                                    <div class="card-text">
                                        <hr>
                                        <h5>Property Description: </h5>
                                        <p style="text-align: left">{!! $property->description !!} </p>
                                    </div>
                                @endisset
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <!-- PROPERTY UNITS -->
                        @if ($property->units->count() > 1 || $property->has_units)
                            <div class="col-md-6">
                                    <ul class="list-group mb-3">
                                        @can('create property')
                                        <a href="{{ route('new.unit', $property->id) }}"
                                            class="list-group-item list-group-item-action active">Property Units <span
                                                class="btn btn-default" style="float: right;">Add New</span></a>
                                        @else
                                        <a href="#" class="list-group-item list-group-item-action active">Units</a>
                                        @endcan

                                        @foreach ($property->units->take(5) as $unit)
                                        <li class="list-group-item list-group-item-action">
                                            <a href="{{ route('show.unit', $unit->id) }}">
                                               {{ $unit->unit_number ?? '' }}
                                            </a>
                                                @if ($unit->status == 'under_maintenance')
                                                    <span class="badge badge-warning float-right">Under Maintenance</span>
                                                @elseif ($unit->status == 'available')
                                                    <span class="badge badge-primary float-right">Available</span>
                                                @elseif ($unit->status == 'sold')
                                                    <span class="badge badge-danger float-right">Sold</span>
                                                @elseif ($unit->status == 'leased')
                                                    <span class="badge badge-success float-right">Leased</span>
                                                @elseif ($unit->status == 'vacant')
                                                    <span class="badge badge-info float-right">Vacant</span>
                                                @else
                                                    <span class="badge badge-secondary float-right">Unavailable</span>
                                                @endif
                                        </li>
                                        @endforeach
                                    </ul>
                            </div>
                        @endif
                        <!-- PROPERTY DOCUMENTS -->
                        @if ($property->documents->count() > 0)
                            <div class="col-md-6">
                                <ul class="list-group mb-3">
                                @can('create property')
                                    <a href="{{ url('addp-file/' . $property->id) }}"
                                        class="list-group-item list-group-item-action active">Property Documents <span
                                            class="btn btn-default" style="float: right;">New File</span></a>
                                @else
                                    <a href="#" class="list-group-item list-group-item-action active">Property Files</a>
                                @endcan
                                    @foreach ($property->documents as $document)
                                        @php
                                            $file_ext = pathinfo($document->file_path, PATHINFO_EXTENSION);
                                            $file_ext = strtoupper($file_ext);
                                        @endphp
                                        <li class="list-group-item list-group-item-action">
                                            <a target="_blank"
                                                href="{{ URL::to('public/documents/' . $document->file_path) }}">{{ $document->title }}
                                                <span class="badge badge-info">{{ $file_ext }}</span></a>
                                            @can('edit property')
                                                <a href="/delete-file/{{ $document->id }}"
                                                class="btn btn-inline btn-xs btn-danger float-right">Del</a>
                                            @endcan
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                         <!-- PROPERTY LEASES -->
                         @if ($property->leases->count() > 0 )
                            <div class="col-md-6">
                                <ul class="list-group mb-3">
                                    @can('create property')
                                    <a href="{{ url('new.lease', $property->id) }}"
                                        class="list-group-item list-group-item-action active">Leases <span
                                            class="btn btn-default" style="float: right;">Add New</span></a>
                                    @else
                                    <a href="#" class="list-group-item list-group-item-action active">Leases</a>
                                    @endcan
                                    @foreach ($property->leases as $lease)
                                        <li class="list-group-item list-group-item-action">
                                            <b>Tenant:</b> {{ $lease->tenant->first_name  }} <i>End on:</i>
                                            {{ $lease->end_date->format('F, Y') }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <!-- PROPERTY MAINTENANCE REQUESTS -->
                        @if ($property->maintenanceRequests->count() > 0 )
                            <div class="col-md-6">
                                <ul class="list-group mb-3">
                                    @can('create property')
                                    <a href="{{ url('new.maintenance-request', $property->id) }}"
                                        class="list-group-item list-group-item-action active">Maintenance Requests <span
                                            class="btn btn-default" style="float: right;">Add New</span></a>
                                    @else
                                    <a href="#" class="list-group-item list-group-item-action active">Maintenance Requests</a>
                                    @endcan

                                    @foreach ($property->maintenanceRequests as $maintenanceRequest)
                                        <li class="list-group-item list-group-item-
                                            {{ $maintenanceRequest->priority == 'urgent' ? 'list-group-item-danger' :
                                            ($maintenanceRequest->priority == 'hign' ? 'list-group-item-warning' : '') }}">
                                            <a href="{{ url('show.maintenance-request', $maintenanceRequest->id) }}">
                                                {{ $maintenanceRequest->title ?? '' }}
                                            </a>
                                            @if ($maintenanceRequest->status == 'pending')
                                                <span class="badge badge-warning float-right">Pending</span>
                                            @elseif ($maintenanceRequest->status == 'completed')
                                                <span class="badge badge-success float-right">Completed</span>
                                            @elseif ($maintenanceRequest->status == 'cancelled')
                                                <span class="badge badge-danger float-right">Cancelled</span>
                                            @elseif ($maintenanceRequest->status == 'open')
                                                <span class="badge badge-primary float-right">Open</span>
                                            @else
                                                <span class="badge badge-secondary float-right">In Progress</span>
                                            @endif
                                        </li>
                                    @endforeach
                                    </ul>
                            </div>
                        @endif
                        <!-- PROPERTY TASKS -->
                        @if ($property->tasks->count() > 0 )
                            <div class="col-md-6">
                                <ul class="list-group mb-3">
                                    @can('create property')
                                    <a href="{{ url('new.viewing', $property->id) }}"
                                        class="list-group-item list-group-item-action active">Tasks <span
                                            class="btn btn-default" style="float: right;">Add New</span></a>
                                    @else
                                    <a href="#" class="list-group-item list-group-item-action active">Tasks</a>
                                    @endcan
                                    @foreach ($property->tasks as $task)
                                        <li class="list-group-item list-group-item-action">
                                            <a href="{{ url('show.task', $task->id) }}">
                                                {{ $task->title ?? '' }}
                                            </a>
                                            <span class="small">To: {{ $task->assignee->name }} </span>
                                            @if ($task->status == 'pending')
                                                <span class="badge badge-warning float-right">Pending</span>
                                            @elseif ($task->status == 'completed')
                                                <span class="badge badge-success float-right">Completed</span>
                                            @elseif ($task->status == 'cancelled')
                                                <span class="badge badge-danger float-right">Cancelled</span>
                                            @else
                                                <span class="badge badge-secondary float-right">In Progress</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                         <!-- PROPERTY VIEWINGS -->
                        @if ($property->viewings->count() > 0 )
                            <div class="col-md-6">
                                <ul class="list-group mb-3">
                                    @can('create property')
                                    <a href="{{ url('new.viewing', $property->id) }}"
                                        class="list-group-item list-group-item-action active">Viewings <span
                                            class="btn btn-default" style="float: right;">Add New</span></a>
                                    @else
                                    <a href="#" class="list-group-item list-group-item-action active">Viewings</a>
                                    @endcan
                                    @foreach ($property->viewings as $viewing)
                                        <li class="list-group-item list-group-item-action">
                                            <a href="{{ url('show.viewing', $viewing->id) }}">
                                                {{ $viewing->client_name ?? '' }}
                                            </a>
                                            <span class="small">{{ $viewing->scheduled_at->format('d F, Y h:i A') }} </span>
                                            @if ($viewing->status == 'scheduled')
                                                <span class="badge badge-warning float-right">Scheduled</span>
                                            @elseif ($viewing->status == 'completed')
                                                <span class="badge badge-success float-right">Completed</span>
                                            @elseif ($viewing->status == 'cancelled')
                                                <span class="badge badge-danger float-right">Cancelled</span>
                                            @else
                                                <span class="badge badge-secondary float-right">In Progress</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div> <!-- /.row -->
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

<style>
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
