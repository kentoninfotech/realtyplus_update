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
          <li class="breadcrumb-item active">Workers</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>

    <!-- <h3 class="page-title">Project: <small style="color: green">{{ $project->title }}</small></h3> -->
    <div class="row">
            <div class="card">
                <div class="card-heading">

                        <!-- <a href="#" class="btn btn-primary pull-right" data-toggle="modal" data-target="#materialcheckout">Collect Materials</a> -->


                </div>
                <div class="card-body"> 
                  <h3 class="page-title mt-2 ml-3">Project: <small style="color: green">{{ $project->title }}</small></h3>
                      <a href="{{ url()->previous() }}" class="btn btn-primary float-right mb-3 mx-5"> <i class="fa fa-angle-left"></i> Back</a>                   
                    <table class="table responsive-table" id="products" style="width:100% !important">
                    <thead>
                        <tr style="color: ">
                            <th>Name</th>
                            <th>Task</th>
                            <th>Location/Facility</th>
                            <th>Job Date</th>

                            <th>Amount Paid(<s>N</s>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workers as $twkrs)
                            <tr>
                                <td>{{ $twkrs->worker->name }}</td>
                                <td>{{$twkrs->task->subject}}</td>
                                <th>{{$twkrs->business->business_name ?? ''}}</th>
                                <td>{{ $twkrs->work_date }}</td>
                                <td>{{ $twkrs->amount_paid }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                    <div style="text-align: right">
                        {{$workers->links("pagination::bootstrap-4")}}
                    </div>
                </div>
            </div>

    </div>


   

@endsection
