@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Client</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('clients') }}">Client</a></li>
                        <li class="breadcrumb-item active">Edit Client</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">Edit {{$client->name }}' Records</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('update.client', $client->id) }}" method="post">
                @csrf
                
                <input type="hidden" name="oldpassword" value="{{ $client->password }}">

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" name="name" id="name"
                            placeholder="Enter a Full Name" value="{{$client->name }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="company_name">Name / Organization /Company Name</label>
                        <input type="text" class="form-control" name="company_name" id="company_name"
                            placeholder="Company name"
                            value="{{ $client->client->company_name }}">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="form-group col-md-6">
                        <label for="about">About Client </label>
                        <textarea rows="3" type="text" class="form-control" name="about" id="about"
                            aria-describedby="about_client" placeholder="About">{{ $client->client->about ?? '' }}
                        </textarea>
                        <small id="about_client" class="form-text text-muted">Please, write a brief information about the
                            Client</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="address">Address </label>
                        <textarea rows="3" type="text" class="form-control" name="address" id="address"
                            placeholder="Residential or office address">{{ $client->client->address ?? '' }}
                        </textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" name="phone_number" id="phone_number"
                            placeholder="Phone Number"
                            value="{{ $client->phone_number }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email Address"
                            value="{{ $client->email }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="password">Change Password</label>
                        <input type="text" class="form-control" name="password" id="password"
                            placeholder="Password">
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="category">Category</label>
                        <select name="category" id="category" class="form-control">
                            <option value="{{ $client->category  }}" selected> {{ $client->category }}</option>
                            <option value="client">Client</option>
                            <option value="supplier">Supplier</option>
                            <option value="labourer">Labourer</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="{{ $client->status ?? '' }}" selected>
                                {{ $client->status ?? 'Select Status' }}</option>
                            <option value="Active">Active</option>
                            <option value="Suspended">Suspended</option>
                            <option value="Terminated">Terminated</option>
                            <option value="Awaiting Approval">Awaiting Approval</option>
                        </select>
                    </div>

                    <!-- <div class="form-group col-md-4">
                        <label for="role">System Role</label>
                        <select name="role" id="role" class="form-control">
                            <option value="{{ isset($client->role) ? $client->role : '' }}" selected>
                                {{-- isset($client->role) ? $client->role : 'Select Role' --}}</option>

                            <option value="Admin">Admin</option>
                            <option value="Staff">Staff</option>
                            <option value="Manager">Manager</option>
                            <option value="Super">Super</option>
                            <option value="Client">Client</option>
                        </select>
                    </div> -->
                </div>



                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary">Update Client</button>
                </div>
            </form>
        </div>
    </div>
@endsection
