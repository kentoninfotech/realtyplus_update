@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Project Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Project Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-widget widget-user">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        @php
            $featured_img = $project->project_files->where('featured', 'Yes')->first();
        @endphp
        <div class="widget-user-header text-white"
            style="background: @if ($featured_img) url({{ asset('public/files/' . $featured_img->project_id . '/' . $featured_img->file_name) }}); @endif height: 250px !important; text-shadow: 2px 2px #000; background-color: grey;">
            <h1 class="text-right">{{ $project->title }}</h1>
            <h5 class="widget-user-desc text-right"><i class="nav-icon fas fa-map-marker-alt"></i> {{ $project->location }}
            </h5>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-sm-4 border-right">
                    <div class="description-block">
                        <h5 class="description-header">{{ $project->milestones->count() }}</h5>
                        <span class="description-text">MILESTONES</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                    <div class="description-block">
                        <h5 class="description-header">{{ $project->milestones->where('status', 'Completed')->count() }}
                        </h5>
                        <span class="description-text">COMPLETED MILESTONES</span>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
                    <div class="description-block">
                        <h5 class="description-header">35</h5>
                        <span class="description-text">TOTAL EXPENDITURE</span>
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
                                src="{{ asset('public/files/' . $featured_img->project_id . '/' . $featured_img->file_name) }}"
                                alt="{{ $featured_img->file_title }}">
                        @endif
                        <div class="card-body">
                            <h4 class="card-title"><i>Client: </i><b>{{ $project->client->name }}</b></h4>

                            <p class="card-text small"><i class="nav-icon fas fa-map-marker-alt"></i>
                                {{ $project->client->address }}</p>

                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center active small">
                                    Project Status
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Start Date
                                    <span class="badge badge-primary badge-pill">{{ $project->start_date }}</span>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center disabled small">
                                    Estimated Duration
                                    <span
                                        class="badge badge-danger badge-pill small">{{ $project->estimated_duration . ' ' . $project->duration }}</span>
                                </li>

                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center disabled small">
                                    % Completion:
                                    <span class="badge badge-secondary badge-pill"></span>
                                </li>

                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center disabled small">
                                    Project Status
                                    <span class="badge badge-warning badge-pill">{{ $project->status }}</span>
                                </li>
                            </ul>

                            <hr>

                            @if(!auth()->user()->hasrole('Client'))
                               <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action active">Menu </a>
                                    <a href="{{ route('project-transactions', $project->id) }}"
                                        class="list-group-item list-group-item-action">Transactions</a>
                                    <a href="{{ route('project-materials', $project->id) }}"
                                        class="list-group-item list-group-item-action">Materials Used</a>
                                    <a href="{{ route('project-workers', $project->id) }}"
                                        class="list-group-item list-group-item-action">Workers</a>
                                    <a href="/project/{{ $project->id }}/reports"
                                        class="list-group-item list-group-item-action">Reports</a>
                                    <a href="/project/{{ $project->id }}/tasks"
                                        class="list-group-item list-group-item-action">Tasks</a>
                                    <a href="/project/{{ $project->id }}/milestones"
                                        class="list-group-item list-group-item-action">MileStones</a>

                                </div>
                            @endif
                            
                        </div>
                    </div>

                </div>
                <div class="col-md-9">

                    <div class="row">
                        <h5>Project Description: </h5>
                        <p
                            style="text-align: left>{!! $project->details !!}</p>
                    </div>
                    <hr>

                    <div class="row">

                        <div id="carouselId" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">

                                @foreach ($project->project_files as $key => $fit)
                                    @php
                                        $file_ext = pathinfo(asset('public/files/' . $fit->project_id . '/' . $fit->file_name))['extension'];
                                        if ($file_ext == 'png' || $file_ext == 'jpg' || $file_ext == 'jpeg') {
                                            echo '<li data-target="#carouselId" data-slide-to="' . $key++ . '" class="active"></li>';
                                        }
                                    @endphp
                                @endforeach
                            </ol>
                            <div class="carousel-inner" role="listbox">
                                @foreach ($project->project_files as $key => $fit)
                                    @php
                                        $file_ext = pathinfo(asset('public/files/' . $fit->project_id . '/' . $fit->file_name))['extension'];
                                        if ($file_ext == 'png' || $file_ext == 'jpg' || $file_ext == 'jpeg') {
                                            if ($key == 0) {
                                                $active = 'active';
                                            } else {
                                                $active = '';
                                            }
                                            echo '<div class="carousel-item ' .
                                                $active .
                                                '">
                                                    <img src="' .
                                                asset('public/files/' . $fit->project_id . '/' . $fit->file_name) .
                                                '"alt="' .
                                                $fit->file_title .
                                                '" height="300" width="100%">
                                                    <div class="carousel-caption d-none d-md-block">
                                                        <h3>' .
                                                $fit->file_title .
                                                '</h3>
                                                        <p>' .
                                                $fit->details .
                                                '</p>
                                                    </div>
                                                </div>';
                                        }
                                    @endphp
                                @endforeach


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
                                @can('create project')
                                <a href="{{ url('new-milestone/' . $project->id) }}"
                                    class="list-group-item list-group-item-action active">Milestones <span
                                        class="btn btn-default" style="float: right;">Add New</span></a>
                                @else
                                <a href="#" class="list-group-item list-group-item-action active">Milestones</a>
                                @endcan

                                @foreach ($project->milestones as $pm)
                                    <a href="{{ url('milestone/' . $pm->id) }}"
                                        class="list-group-item list-group-item-action">{{ $pm->title }}</a>
                                @endforeach
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="list-group">
                              @can('create project')
                                <a href="{{ url('addp-file/' . $project->id) }}"
                                    class="list-group-item list-group-item-action active">Project Files <span
                                        class="btn btn-default" style="float: right;">New File</span></a>
                              @else
                                <a href="#" class="list-group-item list-group-item-action active">Project Files</a>
                              @endcan

                                @foreach ($project->project_files as $ft)
                                    @php
                                        $file_ext = pathinfo(asset('public/files/' . $ft->project_id . '/' . $ft->file_name))['extension'];
                                    @endphp
                                    <li class="list-group-item list-group-item-action">
                                        <a target="_blank"
                                            href="{{ URL::to('public/files/' . $ft->project_id . '/' . $ft->file_name) }}">{{ $ft->file_title }}
                                            <span class="badge badge-info">{{ $file_ext }}</span></a>
                                         @can('edit project')
                                            <a href="/delete-file/{{ $ft->id }}"
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
