@extends('layouts.template')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<!-- Leaflet JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<!-- Leaflet Geocoder -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<style>
    body {
        margin: 0;
        padding: 0;
    }

    #map {
        position: relative;
        width: 100%;
        height: 100%;
    }
    
    .map-info {
        background: #f0f7ff;
        border-left: 4px solid #007bff;
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 4px;
        font-size: 12px;
        color: #666;
    }
</style>

@php
    
    if (isset($project->id)) {
        $type = 'Edit';
        $button = 'Save Changes';
        $project_id = $project->id;
    } else {
        $cid = 0;
        // $client = (object) [];
        $type = 'New';
        $button = 'Save New ';
        $project_id = '';
    }
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $type }} Project</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('projects') }}">Projects</a></li>
                        <li class="breadcrumb-item active">{{ $type }} Project</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">{{ $type }} Project Form</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('save-project') }}" method="post">
                @csrf

                <input type="hidden" name="project_id" value="{{ $project_id }}">

                @if (isset($client_id))
                    <input type="hidden" name="client_id" value="{{ $client_id }}">
                @else
                    <div class="row">
                        <div class="form-group col-md-12">
                            <select name="client_id" class="form-control select2">
                                @foreach ($clients as $cl)
                                    <option data-select2-id="{{ $cl->id }}" value="{{ $cl->id }}">
                                        {{ $cl->name }} ({{ $cl->client->company_name ?? $cl->name }})</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                @endif

                <div class="form-group col-md-12">
                    <label for="title">Project Title</label>
                    <input type="text" class="form-control" name="title" id="title"
                        aria-describedby="project_title" placeholder="Enter a Title"
                        value="{{ isset($project->title) ? $project->title : '' }}">
                    <small id="project_title" class="form-text text-muted">A descriptive name of the project</small>
                </div>

                <div class="form-group col-md-12">
                    <label for="location">Project Location</label>
                    <input type="text" class="form-control" name="location" id="location"
                        aria-describedby="project_location" placeholder="Enter a Location"
                        value="{{ isset($project->location) ? $project->location : '' }}">
                    <small id="project_location" class="form-text text-muted">An address, district or landmark of the project site</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="latitude">Latitude</label>
                            <input type="text" class="form-control" name="latitude" id="latitude"
                                placeholder="Latitude (click on map)" readonly
                                value="{{ isset($project->latitude) ? $project->latitude : '' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="longitude">Longitude</label>
                            <input type="text" class="form-control" name="longitude" id="longitude"
                                placeholder="Longitude (click on map)" readonly
                                value="{{ isset($project->longitude) ? $project->longitude : '' }}">
                        </div>
                    </div>
                </div>

                <div class="map-info">
                    <strong>📍 Click on the map</strong> to select a location. The coordinates will be automatically captured and entered in the Latitude and Longitude fields.
                </div>

                <div class="row" style="width: 100%; height: 300px; overflow: hidden">
                    <div id='map' style="position: relative !important;"></div>
                </div>

                <div class="form-group col-md-12">
                    <label for="details">Details</label>
                    <textarea name="details" id="details" class="wyswygeditor">
              {{ isset($project->details) ? $project->details : 'Place <em>some</em> <u>text</u> <strong>here</strong>' }}
            </textarea>
                    <small id="task_details" class="form-text text-muted">A Detailed infomation about the project
                        entered</small>
                </div>

                <div class="form-group row">


                    <div class="col-md-4">
                        <label>Start Date:</label>
                        <div class="input-group date" id="start_date_activator" data-target-input="nearest">
                            <input type="text" name="start_date" class="form-control datetimepicker-input"
                                data-target="#start_date_activator"
                                value="{{ isset($project->start_date) ? $project->start_date : '' }}">
                            <div class="input-group-append" data-target="#start_date_activator"
                                data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="estimated_duration">Estimated Duration</label>
                        <input type="number" class="form-control" name="estimated_duration" id="estimated_duration"
                            placeholder="Estimated Duration"
                            value="{{ isset($project->estimated_duration) ? $project->estimated_duration : '' }}">
                    </div>

                    <div class="col-md-4">
                        <label>Days/Weeks/Months/Years:</label>
                        <select name="duration" class="form-control">
                            <option value="{{ isset($project->duration) ? $project->duration : '' }}" selected>
                                {{ isset($project->duration) ? $project->duration : 'Select Duration' }}</option>
                            <option value="Days">Days</option>
                            <option value="Weeks">Weeks</option>
                            <option value="Months">Months</option>
                            <option value="Years">Years</option>
                        </select>
                    </div>

                </div>

                <div class="form-group row">
                    <div class="col-md-8">
                        <label>Project Manager:</label>
                        <select name="project_manager" class="form-control select2">
                            @foreach ($staff as $st)
                                <option data-select="{{ $st->id }}" value="{{ $st->id }}" {{ (isset($project->project_manager) && $project->project_manager == $st->id) ? 'selected' : '' }}>
                                    {{ $st->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Status:</label>
                        <select name="status" class="form-control">
                            <option value="Upcoming">Upcoming</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="Paused">Paused</option>
                            <option value="Terminated">Terminated</option>
                        </select>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12" style="text-align: right">
                        <button type="submit" class="btn btn-primary">{{ $button }} Project</button>
                    </div>
                </div>

            </form>
        </div>
    </div>


    <script>
        // Initialize Leaflet map
        var map = L.map('map').setView([6.5244, 3.3792], 13); // Default center (Lagos, Nigeria)
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Add geocoder control
        L.Control.geocoder().addTo(map);
        
        // Variable to store the current marker
        var currentMarker = null;
        
        // Initialize with existing coordinates if available
        @if(isset($project->latitude) && isset($project->longitude))
            var initialLat = {{ $project->latitude }};
            var initialLng = {{ $project->longitude }};
            map.setView([initialLat, initialLng], 14);
            currentMarker = L.marker([initialLat, initialLng]).addTo(map)
                .bindPopup('📍 Project Location');
        @endif
        
        // Handle map clicks to select location
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            
            // Update input fields
            document.getElementById('latitude').value = lat.toFixed(6);
            document.getElementById('longitude').value = lng.toFixed(6);
            
            // Remove previous marker if exists
            if (currentMarker) {
                map.removeLayer(currentMarker);
            }
            
            // Add new marker at clicked location
            currentMarker = L.marker([lat, lng]).addTo(map)
                .bindPopup('<strong>📍 Selected Location</strong><br>Lat: ' + lat.toFixed(6) + '<br>Lng: ' + lng.toFixed(6))
                .openPopup();
            
            // Optionally reverse geocode to get address (requires a geocoding service)
            reverseGeocode(lat, lng);
        });
        
        // Simple reverse geocoding using Nominatim (OpenStreetMap)
        function reverseGeocode(lat, lng) {
            var url = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.address) {
                        // Try to get a readable address
                        var address = data.address.road || data.address.suburb || data.address.town || data.address.city || 'Selected Location';
                        // Optionally update the location field
                        // document.getElementById('location').value = address;
                    }
                })
                .catch(error => console.log('Reverse geocoding failed:', error));
        }
    </script>
@endsection
