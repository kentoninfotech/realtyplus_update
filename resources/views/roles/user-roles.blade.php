@extends('layouts.template')
@php
    //$pagetype = 'Table';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manage Roles & Permissions</h1>
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
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body" style="overflow: auto;">
                    <table class="table responsive-table" id="products">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $usr)
                                <tr>
                                    <td>{{ $usr->name }} <br>
                                    <small>{{ $usr->email }}</small>
                                    </td>
                                    <td>{{ implode(', ', $usr->getRoleNames()->toArray()) }}</td>

                                    <td width="90">
                                        <div class="btn-group">
                                            <a href="{{ route('user.role.edit', $usr) }}" class="btn btn-sm btn-primary">Manage</a>
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
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <a href="{{ url()->previous() }}" class="btn btn-md btn-primary" style="float: right;">Back</a>
                    <br>
                    <h2>{{ $user->name }}'s Role/Permissions</h2>
                    <form method="POST" action="{{ route('user.role.update', $user) }}">
                        @csrf
                        <div class="mb-3">
                            <label>Roles</label><br>
                            @foreach($roles as $role)
                                <div class="form-check form-switch form-check-inline mb-3">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="form-check-input"
                                        {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ $role->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mb-3">
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
                                                {{ $user->hasPermissionTo($permission) ? 'checked' : '' }}>
                                            </div> 
                                        </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <button class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
