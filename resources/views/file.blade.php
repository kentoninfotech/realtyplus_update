@extends('layouts.template')

@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Project File</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item">File</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>

<div class="card card-widget">
  <img class="card-img-top" src="{{asset('files/'.$file->project_id.'/'.$file->file_name)}}">
  <div class="widget-header" style="padding: 10px;">
    <h1>{{$file->file_title}}</h1>

    @if($file->task_id!="")
    <h5><i>Task: </i>{{$file->task->subject}}, <i>Client: </i>{{$file->project->name}}</h5>
  @endif

    @if($file->milestone_id!="")
      <h5><i>As part of Milestone: </i>{{$file->milestone->title}}, {{$file->project->title}}</h5>
    @endif
    <hr>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-12">
        <h5>Description</h5>
        {!!$file->details!!}
      </div>

    </div>
  </div>
</div>

@endsection
