@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">New Agent</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('agents') }}">Agent</a></li>
                        <li class="breadcrumb-item active">New Agent</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">Add New Agent</h4>
            <a class="btn btn-success sm float-right" href="{{ route('agents') }}">Back</a>
        </div>
        <div class="card-body">
            <form action="{{ route('create.agent') }}" method="post">
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

                <div class="row form-group">
                    <div class="form-group col-md-6">
                        <label for="license_number">Agent License Number</label>
                        <input type="text" class="form-control" name="license_number" id="license_number"
                            placeholder="Agent License Number"
                            value="{{ old('license_number') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="commission_rate">Commission Rate </label>
                        <input type="number" class="form-control" name="commission_rate" id="commission_rate"
                            placeholder="Commission Rate" value="{{ old('commission_rate') }}">
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-md-6">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option disabled {{ old('status') ? '' : 'selected' }}>Select Status</option>
                            <option value="inactive" old('status') == 'active' ? 'selected' : ''>Active</option>
                            <option value="suspended" old('status') == 'Suspended' ? 'selected' : ''>Suspended</option>
                            <option value="terminated" old('status') == 'Terminated' ? 'selected' : ''>Terminated</option>
                            <option value="Pending" old('status') == 'Pending' ? 'selected' : ''>Pending</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="password">Password</label>
                        <input type="text" class="form-control" name="password" id="password"
                            placeholder="Password">
                    </div>
                </div>



                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary">Create Agent</button>
                </div>
            </form>
        </div>
    </div>
@endsection
