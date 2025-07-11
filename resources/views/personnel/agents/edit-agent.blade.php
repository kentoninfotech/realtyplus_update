@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Agent</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('agents') }}">Agent</a></li>
                        <li class="breadcrumb-item active">Edit Agent</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">Edit {{$agent->user->name }}' Records</h4>
            <a class="btn btn-success sm float-right" href="{{ route('agents') }}">Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('update.agent', $agent->id) }}" method="post">
                @csrf
                @method('PUT')
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name"
                            placeholder="Enter a First Name" value="{{ old('first_name', $agent->first_name) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name"
                            placeholder="Enter a Last Name" value="{{ old('last_name', $agent->last_name) }}">
                    </div>

                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" name="phone_number" id="phone_number"
                            placeholder="Phone Number"
                            value="{{ old('phone_number', $agent->phone_number) }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email Commission_rate"
                            value="{{ old('email', $agent->email) }}">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="form-group col-md-6">
                        <label for="license_number">License Number</label>
                        <input type="text" class="form-control" name="license_number" id="license_number"
                            placeholder="License Number"
                            value="{{ old('license_number', $agent->license_number) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="commission_rate">Commission Rate </label>
                        <input type="text" class="form-control" name="commission_rate" id="commission_rate"
                            placeholder="Commission Rate" value="{{ old('commission_rate', $agent->commission_rate) }}">
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option disabled {{ old('status', $agent->user->status ?? null) ? '' : 'selected' }}>Select Status</option>
                            <option value="active" {{ old('status',$agent->user->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status',$agent->user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $agent->user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="terminated" {{ old('status', $agent->user->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                            <option value="pending" {{ old('status', $agent->user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="password">Change Password</label>
                        <input type="text" class="form-control" name="password" id="password"
                            placeholder="Password">
                    </div>
                </div>



                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary">Update Agent</button>
                </div>
            </form>
        </div>
    </div>
@endsection
