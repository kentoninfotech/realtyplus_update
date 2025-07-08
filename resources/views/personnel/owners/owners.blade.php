@extends('layouts.template')
@php
    $pagetype = 'Table';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Owners</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Owners</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="card">

        <div class="card-body" style="overflow: auto;">
          @can('create owner')
            <a href="{{ route('new-owner') }}" class="btn btn-primary" style="float: right;">Add New</a>
          @endcan
            <br>
            <table class="table responsive-table" id="products">
                <thead>
                    <tr>
                        <th width="20">#</th>
                        <th>Name</th>
                        <th>Company Name</th>
                        <th>email</th>
                        <th>Phone Number</th>
                        <th>Address</th>
                        <th>roles</th>
                        <th>Property</th>
                        <th>Available Property</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($owners as $owner)
                        <tr @if ($owner->status == 'Active') style="background-color: azure !important;" @endif>
                            <td>{{ $owner->id }}</td>
                            <td>{{ $owner->name }}</td>
                            <td>{{ $owner->company_name }}</td>
                            <td>{{ $owner->email }}</td>
                            <td>{{ $owner->phone_number }}</td>
                            <td>{{ $owner->address }}</td>
                            <td>{{ Str::headline($owner->user_type) }}</td>

                            <td>{{ $owner->property->count() }}</td>
                            <td>{{ isset($owner->property->where('status', 'available')->first()->title) ? $owner->property->where('status', 'available')->first()->title : 'None' }}
                            </td>
                            <td width="90">
                                <div class="btn-group">
                                @can('edit owner')
                                    <a href="{{ route('edit-owner', $owner->id) }}" class="btn btn-default btn-xs">Edit</a>
                                @endcan
                                @can('view property')
                                    <a href="/owner-property/{{ $owner->id }}"
                                        class="btn btn-success btn-xs">Property</a>
                                @endcan

                                @can('create property')
                                    <a href="/new-property/{{ $owner->id }}/" class="btn btn-primary btn-xs">New</a>
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
