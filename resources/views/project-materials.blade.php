@extends('layouts.template')

@section('content')
@php $modal="material"; $pagetype = "Table"; @endphp

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Materials Used</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Material Checkouts</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>

    <div class="row">
            <div class="card">
                        

                <div class="card-body">
                  <h3 class="page-title mt-2 ml-3">Project: <small style="color: green">{{ $project->title }}</small></h3>
                      <a href="{{ url()->previous() }}" class="btn btn-primary float-right mb-3 mx-5"> <i class="fa fa-angle-left"></i> Back</a>
                    <table class="table responsive-table" id="products" style="width:100% !important">
                        <thead>
                            <tr>
                                <th>Material Name</th>
                                <th>Production Batch No.</th>
                                <th>Quantity</th>
                                <th>Details</th>
                                <th>Checked Out By</th>
                                <th>Approved By</th>
                                <th>Date</th>
                                <th>Location/Facility</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($materials as $mtc)

                                <tr>
                                    <td>{{$mtc->material->name}}</td>
                                    <td><b>{{$mtc->task_id}}</b></td>
                                    <td><b>{{$mtc->quantity}}</b></td>
                                    <!-- <td><b>{{$mtc->quantity}}{{$mtc->material->measurement_unit}}</b></td> -->
                                    <td>{{$mtc->details}}</td>
                                    <td>{{$mtc->checkoutby->name}}</td>
                                    <td>{{$mtc->approvedby->name}}</td>
                                    <td>{{$mtc->dated}}</td>
                                    <td>{{$mtc->business->business_name}}</td>

                                    <td>
                                       @can('delete material_checkout')
                                        <a href="/delete-mtc/{{$mtc->id}}/{{$mtc->material_id}}/{{$mtc->quantity}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete the material checkout record, this will return the {{$mtc->material->name}} with quantity {{$mtc->quantity}} back to stock?')">Delete</a>
                                       @endcan
                                      </td>

                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                    <div style="text-align: right">
                        {{$materials->links("pagination::bootstrap-4")}}
                    </div>
                </div>
            </div>

    </div>


   

@endsection
