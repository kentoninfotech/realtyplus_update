@extends('layouts.template')
@php
    $pagetype = 'Table';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Projects</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Projects</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="card">
        <div class="card-body" style="overflow: auto;">
          @can('create project')
            <a href="/addproject" class="btn btn-primary" style="float: right;">Add New</a>
          @endcan
            <table class="table responsive-table" id="products">
                <thead>
                    <tr>
                        <th width="20">#</th>
                        <th>Project Title</th>
                        <th>Company Name</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $pr)
                        <tr @if ($pr->status == 'Completed') style="background-color: azure !important;" @endif
                            @if ($pr->status == 'In Progress') style="background-color: #FFF8B0 !important;" @endif>
                            <td>{{ $pr->id }}</td>
                            <td>{{ $pr->title }}</td>
                            <td>{{ $pr->client->company_name ?? '' }}</td>
                            <td>{{ $pr->location ?? '' }}</td>
                            <td>{{ $pr->status }}</td>
                            <td width="90">



                                <div class="btn-group">
                                  @can('edit project')
                                    <a href="/edit-project/{{ $pr->id }}" class="btn btn-default btn-xs">Edit
                                    </a>
                                  @endcan
                                  @can('view project')
                                    <a href="/project-dashboard/{{ $pr->id }}"
                                        class="btn btn-primary btn-sm">Dashboard</a>
                                  @endcan

                                </div>
                            </td>

                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
    </div>
@endsection
