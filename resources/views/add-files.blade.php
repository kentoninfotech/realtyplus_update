@extends('layouts.template')

@php
    
    if (isset($project_id)) {
        $type = 'Edit';
        $button = 'Save Changes';
        $project_id = $project_id;
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
                    <h1 class="m-0">Project Files</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Project</a></li>
                        <li class="breadcrumb-item active">Add File</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">Upload File(s)</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('save-file') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="business_id" value="{{ Auth()->user()->business_id }}">
                <div class="form-group row">
                    <div class="col-md-12">
                        <label for="file_title">Enter Title / Subject of the File(s)</label>
                        <input type="text" class="form-control" name="file_title" id="file_title"
                            aria-describedby="file_title" placeholder="Enter a Title/Subject">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-4">
                        <label for="project_id">Select Project</label>
                        <select class="form-control" name="project_id" id="project_id">
                            <option value="">Not Applicable</option>
                            @if (isset($project_id))
                                @php
                                    $proj = $business->projects->where('id', $project_id)->first();
                                    echo '<option value="' . $proj->id . '" selected>' . $proj->title . '</option>';
                                @endphp
                            @else
                                @foreach ($business->projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="milestone_id">Select Milestone</label>
                        <select class="form-control" name="milestone_id" id="milestone_id">
                            <option value="">Not Applicable</option>

                            @if (isset($project_id))
                                @php
                                    $proj = $business->projects->where('id', $project_id)->first();
                                    foreach ($proj->milestones as $key => $prm) {
                                        echo '<option value="' . $prm->id . '" selected>' . $prm->title . '</option>';
                                    }
                                @endphp
                            @else
                                @foreach ($business->projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="task_id">Select Task</label>
                        <select class="form-control" name="task_id" id="task_id">
                            <option value="">Not Applicable</option>
                            @if (isset($project_id))
                                @php
                                    $proj = $business->projects->where('id', $project_id)->first();
                                    if ($proj) {
                                        foreach ($proj->tasks as $key => $pt) {
                                            echo '<option value="' . $pt->id . '" selected>' . $pt->subject . '</option>';
                                        }
                                    }
                                @endphp
                            @else
                                @foreach ($business->projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>


                <div class="form-group col-md-12">
                    <label for="details">File Description</label>
                    <textarea name="details" id="details" class="wyswygeditor">
                        Enter <em>file</em> <u>description</u> <strong>here</strong>
                        </textarea>

                </div>


                <div class="form-group row">


                    <div class="col-md-3">
                        <label for="files">Upload File(s)</label>
                        <input type="file" class="form-control" name="file_name" id="file_name" multiple>
                    </div>

                    <div class="col-md-3">
                        <label for="cloud_location">URL (Optional)</label>
                        <input type="text" class="form-control" name="cloud_location" id="cloud_location"
                            placeholder="url For remote file">
                    </div>

                    <div class="col-md-3">
                        <label for="featured">Make Featured Image?</label>
                        <input type="checkbox" class="form-control" name="featured" id="featured" value="Yes">
                    </div>

                    <div class="col-md-3">
                        <label for="">Confirm and Submit</label>
                        <button type="submit" class="form-control btn btn-primary">Upload File(s)</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
