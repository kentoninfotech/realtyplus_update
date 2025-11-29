@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">View Tenant</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('tenants') }}">Tenant</a></li>
                        <li class="breadcrumb-item active">View Tenant</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">Edit {{$tenant->user->name }}' Records</h4>
            <a class="btn btn-success sm float-right" href="{{ route('tenants') }}">Back</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Name</th>
                    <td>{{ $tenant->first_name }} {{ $tenant->last_name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $tenant->email }}</td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td>{{ $tenant->phone_number }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>{{ $tenant->address }}</td>
                </tr>
                <tr>
                    <th>Emergency Contact Name</th>
                    <td>{{ $tenant->emergency_contact_name }}</td>
                </tr>
                <tr>
                    <th>Emergency Contact Phone</th>
                    <td>{{ $tenant->emergency_contact_phone }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
