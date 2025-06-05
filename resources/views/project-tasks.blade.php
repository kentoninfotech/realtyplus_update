@extends('layouts.template')

@section('content')
@php $modal="material"; $pagetype = "Table"; @endphp

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h3 class="m-0">Project Title : {{$project->title}}, {{$project->location}}</h3>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Project</a></li>
          <li class="breadcrumb-item active">Tasks</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>

    <!-- <h3 class="page-title">Project: <small style="color: green">{{ $project->title }}</small></h3> -->
    <div class="row">
            <div class="card">
                <div class="card-heading">
                    <div class="right">
                        <a href="{{ url()->previous() }}" class="btn btn-primary btn-sm float-right mt-3 mx-3"> <i class="fa fa-angle-left"></i> Back</a>
                    </div>
                        


                </div>
                <div class="card-body">                    
                    <table class="table responsive-table" id="products" style="font-size: 0.8em !important;">
                    <thead>
                        <tr style="color: ">
                            <th>Title</th>
                            <th>Details</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr @if ($task->status == 'Completed') style="background-color: azure !important;" @endif
                                @if ($task->status == 'In Progress') style="background-color: #FFF8B0 !important;" @endif>
                                <td><b>{{ $task->subject }}</b> <br> <small>Category: <i>{{ $task->category }}</i></small>
                                </td>
                                <td>{!! isset($task->project) ? '<i>Project: </i>' . $task->project->title . '<br><hr>' : '' !!}
                                    {!! $task->details !!}</td>
                                <td>{{ $task->start_date . ' ' . $task->end_date }}</td>
                                <td>{{ $task->status }}</td>
                                <td>{{ is_numeric($task->assigned_to) ? $task->assignedTo->name : '' }}
                                </td>

                                <td>
                                  @can('edit task')
                                    <a href="{{ url('/inprogresstask/' . $task->id) }}/{{ $task->member }}"
                                        class="badge badge-warning">In Progress</a>
                                    <a href="{{ url('/completetask/' . $task->id) }}/{{ $task->member }}"
                                        class="badge badge-success">Completed</a>
                                  @endcan    
                                    <br>
                                  @can('create milestone_report')
                                    <a href="{{ url('/new-task-report/' . $task->id) }}/{{ $task->member }}"
                                        class="badge badge-info">Add Report</a>
                                  @endcan
                                  @can('delete task')
                                    <a href="{{ url('/del-task/' . $task->id) }}" class="badge badge-danger"
                                        onclick="return confirm('Are you sure you want to delete this task? {{ $task->title }}?')">Delete</a>
                                  @endcan
                                </td>

                            </tr>
                        @endforeach


                      </tbody>
                  </table>
                    <div style="text-align: right">
                        {{$tasks->links("pagination::bootstrap-4")}}
                    </div>
                </div>
            </div>

    </div>


   

@endsection
