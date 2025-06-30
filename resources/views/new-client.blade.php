@extends('layouts.template')
@php
    $object = 'Client';
    if (isset($client->id)) {
        $type = 'Edit';
        $password_action = 'Change';
        $button = 'Save Changes';
    } else {
        $cid = 0;
        // $client = (object) [];
        $type = 'New';
        $password_action = '';
        $button = 'Save New ' . $object;
    }
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $type }} {{ $object }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('clients') }}">{{ $object }}</a></li>
                        <li class="breadcrumb-item active">{{ $type }} {{ $object }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">{{ $type }} {{ $object }} Form</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('saveClient') }}" method="post">
                @csrf
                <input type="hidden" name="cid" value="{{ isset($client->id) ? $client->id : 0 }}">
                <input type="hidden" name="object" value="{{ $object }}">
                <input type="hidden" name="oldpassword" value="{{ isset($client->password) ? $client->password : '' }}">

                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" name="name" id="name"
                            placeholder="Enter a Full Name" value="{{ isset($client->name) ? $client->name : '' }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="company_name">Name / Organization /Company Name</label>
                        <input type="text" class="form-control" name="company_name" id="company_name"
                            placeholder="Company name"
                            value="{{ isset($client->client->company_name) ? $client->client->company_name : '' }}">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="form-group col-md-6">
                        <label for="about">About {{ $object }} </label>
                        <textarea rows="3" type="text" class="form-control" name="about" id="about"
                            aria-describedby="about_client" placeholder="About">{{ isset($client->client->about) ? $client->client->about : '' }}
                        </textarea>
                        <small id="about_client" class="form-text text-muted">Please, write a brief information about the
                            {{ $object }}</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="address">Address </label>
                        <textarea rows="3" type="text" class="form-control" name="address" id="address"
                            placeholder="Residential or office address">{{ isset($client->client->address) ? $client->client->address : '' }}
                        </textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" name="phone_number" id="phone_number"
                            placeholder="Phone Number"
                            value="{{ isset($client->phone_number) ? $client->phone_number : '' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email Address"
                            value="{{ isset($client->email) ? $client->email : '' }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="password">{{ $password_action }} Password</label>
                        <input type="text" class="form-control" name="password" id="password"
                            placeholder="{{ $type }} Password for the  {{ $object }}">
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="user_type">User Type</label>
                        <select name="user_type" id="user_type" class="form-control">
                            <option value="{{ isset($client->user_type) ? $client->user_type : '' }}" selected>
                                {{ isset($client->user_type) ? $client->user_type : 'Select user_type' }}</option>
                            <option value="client">Client</option>
                            <option value="supplier">Supplier</option>
                            <option value="labourer">Labourer</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="{{ isset($client->status) ? $client->status : '' }}" selected>
                                {{ isset($client->status) ? $client->status : 'Select Status' }}</option>
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
                    <button type="submit" class="btn btn-primary">{{ $button }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
