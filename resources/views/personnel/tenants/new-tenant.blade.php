@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">New Owner</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('owners') }}">Owner</a></li>
                        <li class="breadcrumb-item active">New Owner</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">Add New Owner</h4>
            <a class="btn btn-success sm float-right" href="{{ route('owners') }}">Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('create.owner') }}" method="post">
                @csrf
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
                            placeholder="Enter a First Name" value="{{ old('first_name') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name"
                            placeholder="Enter a Last Name" value="{{ old('last_name') }}">
                    </div>

                </div>

                <div class="row form-group">
                    <div class="form-group col-md-6">
                        <label for="company_name">Name / Organization /Company Name</label>
                        <input type="text" class="form-control" name="company_name" id="company_name"
                            placeholder="Company name"
                            value="{{ old('company_name') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="address">Address </label>
                        <input type="text" class="form-control" name="address" id="address"
                            placeholder="Residential or office address" value="{{ old('address') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" name="phone_number" id="phone_number"
                            placeholder="Phone Number"
                            value="{{ old('phone_number') }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email Address"
                            value="{{ old('email') }}">
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option disabled {{ old('status') ? '' : 'selected' }}>Select Status</option>
                            <option value="Active" old('status') == 'Active' ? 'selected' : ''>Active</option>
                            <option value="Suspended" old('status') == 'Suspended' ? 'selected' : ''>Suspended</option>
                            <option value="Terminated" old('status') == 'Terminated' ? 'selected' : ''>Terminated</option>
                            <option value="Pending" old('status') == 'Pending' ? 'selected' : ''>Pending</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="password">Change Password</label>
                        <input type="text" class="form-control" name="password" id="password"
                            placeholder="Password">
                    </div>
                </div>



                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary">Create Owner</button>
                </div>
            </form>
        </div>
    </div>
@endsection
