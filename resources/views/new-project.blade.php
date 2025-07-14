@extends('layouts.template')
<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.43.0/mapbox-gl.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.43.0/mapbox-gl.css' rel='stylesheet' />
<style>
    body {
        margin: 0;
        padding: 0;
    }

    #map {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100%;
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
                    <small id="project_location" class="form-text text-muted">An address, district or landmark of the
                        project site</small>
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


    <script src='https://unpkg.com/mapbox@1.0.0-beta9/dist/mapbox-sdk.min.js'></script>

    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoibGF3aXgxMCIsImEiOiJjamJlOGE1bmcyZ2V5MzNtcmlyaWRzcDZlIn0.ZRQ73zzVxwcADIPvsqB6mg';
        console.log(mapboxgl.accessToken);
        var client = new MapboxClient(mapboxgl.accessToken);
        console.log(client);

        var address = 't5h 0k7'
        var test = client.geocodeForward(address, function(err, data, res) {
            // data is the geocoding result as parsed JSON
            // res is the http response, including: status, headers and entity properties

            console.log(res);
            console.log(res.url);
            console.log(data);

            var coordinates = data.features[0].center;

            alert(coordinates);

            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v10',
                center: coordinates,
                zoom: 14
            });
            new mapboxgl.Marker()
                .setLngLat(coordinates)
                .addTo(map);


        });
    </script>
@endsection
