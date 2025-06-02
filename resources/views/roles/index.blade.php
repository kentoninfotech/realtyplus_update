@extends('layouts.template')
@php
    //$pagetype = 'Table';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manage Roles & Permission</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Role & Permission</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body" style="overflow: auto;">
                    <table class="table responsive-table" id="products">
                        <thead>
                            <tr>
                                <th width="20">#</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Designation</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }} <br>
                                    <small>{{ $user->email }}</small>
                                    </td>
                                    <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                                    <td>{{ $user->status }}</td>
                                    <td>{{ $user->designation ?? '' }}</td>

                                    <td width="90">
                                        <div class="btn-group">
                                            <a href="{{ route('user.role.edit', $user) }}" class="btn btn-sm btn-primary">Manage</a>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="text-align: right">
                        {{ $users->links("pagination::bootstrap-4") }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
          <div class="card">
                <div class="card-body" style="overflow: auto;">
                    <a href="{{ route('role.create') }}" class="btn btn-primary" style="float: right;">New Role</a>
                    <br>
                    <table class="table responsive-table" id="products">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr @if ($role->name === 'System Admin') style="background-color: azure !important;" @endif>
                                    <td>{{ $role->name }}</td>
                                    <td width="90">
                                        <div class="btn-group">
                                            <a href="{{ route('role.edit', $role) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('role.destroy', $role) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
          </div>
        </div>
    </div>
@endsection
