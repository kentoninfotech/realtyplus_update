@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Roles & Permissions</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Roles & Permissions</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

<div class="card">
  <div class="card-body">
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow">
            <div class="card-body" style="overflow: auto;">
                
                <table class="table responsive-table" id="products">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roleData as $roles)
                            <tr @if ($roles->name === 'Admin') style="background-color: azure !important;" @endif>
                                <td>{{ $roles->name }}</td>
                                <td width="90">
                                    <div class="btn-group">
                                        <a href="{{ route('role.edit', $roles) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('role.destroy', $roles) }}" method="POST" class="d-inline">
                                            @csrf 
                                            @method('DELETE')
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
        <div class="col-lg-8">
            <div class="card shadow mb-4">
            <div class="card-body">
                <a href="{{ url()->previous() }}" class="btn btn-md btn-primary" style="float: right;">Back</a>
                <br>
                <h2>Edit Role</h2>
                <form method="POST" action="{{ route('role.update', $role) }}">
                    @csrf
                    @method('PUT')
                
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
                            </div>
                            <button type="button" class="btn shadow btn-light mb-3" id="selectAllPermissions">
                                Select All
                            </button>
                            <button type="button" class="btn shadow btn-light mb-3" id="deselectAllPermissions">
                                Deselect All
                            </button>
                            <br>
                        
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Permissions</th>
                                        <th>View</th>
                                        <th>Create</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($groupPermissions as $groupTitle => $permissions)
                                    <tr>
                                        <td class="lead">{{ Str::headline(str_replace('_', ' ', $groupTitle)) }}</td>
                                        @foreach($permissions as $permission)
                                        <td class="text-center">
                                            <div class="form-check form-switch ">
                                                <input type="checkbox" name="permissions[]" value="{{ $permission }}" class="form-check-input permission-checkbox"
                                                {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}>
                                            </div> 
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                        </div>
                    </div>
                    <button class="btn btn-primary">Update Role</button>
                </form>
            </div>
        </div>
        </div>
    </div>
  </div>
</div>   
@endsection
