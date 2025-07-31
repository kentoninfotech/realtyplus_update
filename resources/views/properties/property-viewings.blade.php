@extends('layouts.template')
@php
    $pagetype = 'Table';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $property->name }}' Viewing</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">{{ $property->name }}' Viewing</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="card">

        <div class="card-body" style="overflow: auto;">
          @can('create property')
            <a href="{{-- route('new.viewing') --}}" class="btn btn-primary" style="float: right;">Add New</a>
          @endcan
            <br>
            <table class="table responsive-table" id="products">
                <thead>
                    <tr>
                        <th width="20">#</th>
                        <th>Client</th>
                        <th>Agent</th>
                        <th>Scheduled</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($viewings as $viewing)
                        <tr @if ($viewing->status == 'scheduled') style="background-color: azure !important;" @endif>
                            <td>{{ $viewing->id }}</td>
                            <td>{{ $viewing->client_name }} <br>
                                <span class="text-muted">{{ $viewing->client_email }} - {{ $viewing->client_phone }}</span>
                            </td>
                            <td>{{ $viewing->agent->full_name }} <br>
                                <span class="text-muted">({{ $viewing->agent->phone_number }})</span>
                            </td>
                            <td>{{ $viewing->scheduled_at->format('d M, Y h:m A') }}</td>
                            <td>
                                @if ($viewing->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif ($viewing->status == 'scheduled')
                                    <span class="badge badge-primary">Scheduled</span>
                                @elseif ($viewing->status == 'cancelled')
                                    <span class="badge badge-danger">Cancelled</span>
                                @elseif ($viewing->status == 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @else
                                    <span class="badge badge-secondary">{{ Str::headline($viewing->status) }}</span>
                                @endif
                            </td>
                            <td>
                              <div class="btn-group">

                                <div class="dropdown">
                                    <button type="button" class="btn btn-secondary btn-xs" data-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/></svg>
                                    </button>
                                    <div class="dropdown-menu text-center">
                                        @can('view property')
                                            <a class="dropdown-item" href="{{-- route('show.viewing', $viewing->id) --}}"><i class="fa fa-eye"></i> View</a>
                                        @endcan
                                        @can('edit property')
                                            <a class="dropdown-item" href="{{-- route('edit.viewing', $viewing->id) --}}"><i class="fa fa-edit"></i> Edit</a>
                                        @endcan
                                        @can('delete property')
                                            <div class="dropdown-divider"></div>
                                            <form class="d-inline" action="{{-- route('delete.viewing', $viewing->id) --}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this viewing?');">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg>
                                                Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                              </div>
                            </td>

                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
    </div>
@endsection
