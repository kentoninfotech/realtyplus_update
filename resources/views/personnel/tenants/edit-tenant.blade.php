@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Tenant</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('tenants') }}">Tenant</a></li>
                        <li class="breadcrumb-item active">Edit Tenant</li>
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
            <form action="{{ route('update.tenant', $tenant->id) }}" method="post">
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
                            placeholder="Enter a First Name" value="{{ old('first_name', $tenant->first_name) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name"
                            placeholder="Enter a Last Name" value="{{ old('last_name', $tenant->last_name) }}">
                    </div>

                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" name="phone_number" id="phone_number"
                            placeholder="Phone Number"
                            value="{{ old('phone_number', $tenant->phone_number) }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email Address"
                            value="{{ old('email', $tenant->email) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="address">Address </label>
                        <input type="text" class="form-control" name="address" id="address"
                            placeholder="Residential or office address" value="{{ old('address', $tenant->address) }}">
                    </div>
                </div>

                <div class="row center"><h4>Emergency Contact Person</h4></div>
                <div class="row form-group">
                    <div class="form-group col-md-6">
                        <label for="emergency_contact_name">Emergency Contact Name</label>
                        <input type="text" class="form-control" name="emergency_contact_name" id="emergency_contact_name"
                            placeholder="Emergency Contact Name"
                            value="{{ old('emergency_contact_name', $tenant->emergency_contact_name) }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="emergency_contact_phone">Emergency Contact Phone</label>
                        <input type="emergency_contact_phone" class="form-control" name="emergency_contact_phone" id="emergency_contact_phone" placeholder="Emergency Contact Phone"
                            value="{{ old('emergency_contact_phone', $tenant->emergency_contact_phone) }}">
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option disabled {{ old('status', $tenant->user->status ?? null) ? '' : 'selected' }}>Select Status</option>
                            <option value="active" {{ old('status', $tenant->user->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $tenant->user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $tenant->user->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="terminated" {{ old('status', $tenant->user->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                            <option value="pending" {{ old('status', $tenant->user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="password">Change Password</label>
                        <input type="text" class="form-control" name="password" id="password"
                            placeholder="Password">
                    </div>
                </div>



                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary">Update Tenant</button>
                </div>
            </form>
        </div>
    </div>
@endsection
