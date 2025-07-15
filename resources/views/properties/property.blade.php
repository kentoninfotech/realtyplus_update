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
        @php
            $featured_img = $property->images->where('featured', true)->first();
        @endphp
        <div class="widget-user-header text-white"
            style="background: @if ($featured_img) url({{ asset('public/property_images/' . $featured_img->property_id . '/' . $featured_img->file_name) }}); @endif height: 250px !important; text-shadow: 2px 2px #000; background-color: grey;">
            <h1 class="text-right">{{ $property->name }}</h1>
            <h5 class="widget-user-desc text-right"><i class="nav-icon fas fa-map-marker-alt"></i> {{ $property->address ?? '' }}, {{ $property->state }}, {{ $property->country }}
            </h5>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <h5 class="description-header">{{ $property->units->count() }}</h5>
                        <span class="description-text">UNITS</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <h5 class="description-header">{{ $property->acquired_price }}
                        </h5>
                        <span class="description-text">ACQUIRED PRICE</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <h5 class="description-header"> ₦{{ number_format($property->sale_price, 0, '.', ',') }}</h5>
                        <span class="description-text">SALE PRICE (VALUE)</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3">
                    <div class="description-block">
                        <a href="#">View in</a>
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
                        @if ($featured_img)
                            <img class="card-img-top"
                                src="{{ asset('public/files/' . $featured_img->property_id . '/' . $featured_img->file_name) }}"
                                alt="{{ $featured_img->file_title }}">
                        @endif
                        <div class="card-body">
                            <h4 class="card-title"><i>Owner: </i><b>{{ $property->owner->full_name ?? '' }}</b></h4>

                            <p class="card-text small"><i class="nav-icon fas fa-map-marker-alt"></i>
                                {{ $property->owner->address ?? '' }}</p>

                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center active small">
                                    Property Status
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Year Built
                                    <span class="badge badge-primary badge-pill">{{ $property->year_built }}</span>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center disabled small">
                                    Estimated Duration
                                    <span
                                        class="badge badge-danger badge-pill small">{{ $property->estimated_duration . ' ' . $property->duration }}</span>
                                </li>

                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center disabled small">
                                    % Completion:
                                    <span class="badge badge-secondary badge-pill"></span>
                                </li>

                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center disabled small">
                                    Property Status
                                    <span class="badge badge-warning badge-pill">{{ $property->status }}</span>
                                </li>
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

                    <div class="row">
                        <h5>Property Description: </h5>
                        <p
                            style="text-align: left>{!! $property->details !!}</p>
                    </div>
                    <hr>

                    <div class="row">

                        <div id="carouselId" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <!-- ADD IMAGES HERE -->
                            </ol>
                            <div class="carousel-inner" role="listbox">
                                <!-- ADD IMAGES HERE -->

                            </div>
                            <a class="carousel-control-prev" href="#carouselId" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselId" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">

                            <div class="list-group">
                                @can('create property')
                                <a href="{{ url('new-milestone/' . $property->id) }}"
                                    class="list-group-item list-group-item-action active">Property Units <span
                                        class="btn btn-default" style="float: right;">Add New</span></a>
                                @else
                                <a href="#" class="list-group-item list-group-item-action active">Units</a>
                                @endcan

                                @foreach ($property->units as $unit)
                                    <a href="{{ url('property-unit/' . $unit->id) }}"
                                        class="list-group-item list-group-item-action">{{ $unit->unit_number ?? '' }}</a>
                                @endforeach
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="list-group">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
