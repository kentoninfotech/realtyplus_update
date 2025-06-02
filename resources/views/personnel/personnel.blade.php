@extends('layouts.template')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $user->name }} <span class="text-muted fs-6">'s Profile</span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="container py-2">

    <div class="card mb-4">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <img src="{{ (isset($user->personnel->picture) && $user->personnel->picture !== null) ? asset('public/personnel/pictures/' .$user->personnel->picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                class="rounded-circle mr-3" width="150" height="150" alt="User Avatar">
                <div class="ml-4">
                    <h1 class="mb-1">{{ $user->name }}</h1>
                    <h5 class="mb-0 text-muted">{{ $user->personnel->designation ?? '' }}</h5>
                    <small class="text-muted">{{ $user->email ?? '' }}</small>
                </div>
            </div>
            <a href="{{ route('edit.personnel', $user->id) }}" class="btn btn-outline-secondary btn-sm mt-3 mt-md-0 ml-auto">Edit</a>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Personal Information</strong>
            <a href="{{ route('edit.personnel', $user->id) }}" class="btn btn-link btn-sm ml-auto">Edit</a>
        </div>
        <div class="card-body row">
            <div class="col-md-6 mb-2"><strong>First Name:</strong> {{ $user->personnel->firstname }}</div>
            <div class="col-md-6 mb-2"><strong>Last Name:</strong> {{ $user->personnel->lastname }}</div>
            <div class="col-md-12 mb-2"><strong>Other Names:</strong> {{ $user->personnel->othername }}</div>
            <div class="col-md-6 mb-2"><strong>State:</strong> {{ $user->personnel->state_of_origin ?? '' }}</div>
            <div class="col-md-6 mb-2"><strong>Nationality:</strong> {{ $user->personnel->nationality ?? '' }}</div>
            <div class="col-md-6 mb-2"><strong>Date of Birth:</strong> {{ optional($user->personnel->dob)->format('d-M-Y') ?? '' }}</div>
            <div class="col-md-6 mb-2"><strong>Marital Status:</strong> {{ $user->personnel->marital_status ?? '' }}</div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Contact Information</strong>
            <a href="{{ route('edit.personnel', $user->id) }}" class="btn btn-link btn-sm ml-auto">Edit</a>
        </div>
        <div class="card-body row">
            <div class="col-md-6 mb-2"><strong>Email:</strong> {{ $user->email }}</div>
            <div class="col-md-6 mb-2"><strong>Phone:</strong> {{ $user->phone_number }}</div>
            <div class="col-md-12 mb-2"><strong>Address:</strong> {{ $user->personnel->address ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Address Information -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Official Information</strong>
            @if($user->personnel->id && $user->personnel->cv)
             <a href="{{ asset('personnel/cv/'. $user->personnel->cv) }}" class="btn btn-link btn-sm ml-auto" target="_blank" rel="noopener">View CV</a>
            @endif
        </div>
        <div class="card-body row">
            <div class="col-md-6 mb-2"><strong>Department:</strong> {{ $user->personnel->department ?? 'N/A' }}</div>
            <div class="col-md-6 mb-2"><strong>Designation:</strong> {{ $user->personnel->designation ?? 'N/A' }}</div>
            <div class="col-md-6 mb-2"><strong>Basic Salary:</strong> {{ $user->personnel->salary ?? 'N/A' }}</div>
            <div class="col-md-6 mb-2"><strong>Employment Date:</strong> {{ optional($user->personnel->employment_date)->format('d-M-Y') ?? 'N/A' }}</div>
        </div>
    </div>

    <!-- Roles and Permissions (Optional Section) -->
    @if($user->roles->isNotEmpty())
    <div class="card shadow-sm rounded-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Roles & Permissions</h5>
            <a href="{{ route('user.role.edit', $user) }}" class="btn btn-outline-secondary btn-sm mt-3 mt-md-0 ml-auto">Edit</a>
        </div>
        <div class="card-body">
            {{-- @foreach($user->roles as $role) --}}
                <div class="mb-3">
                    @foreach($user->getRoleNames() ?? [] as $role)
                        <span class="badge
                            @if($role === 'System Admin') bg-danger text-white
                            @elseif($role === 'Manager') bg-warning text-white
                            @elseif($role === 'Finance') bg-info text-white
                            @elseif($role === 'Staff') bg-primary text-white
                            @else bg-secondary text-white
                            @endif
                        ">
                            {{ $role }}</span>
                    @endforeach
                    <ul class="list-group mt-1">
                        <div class="row">
                            @foreach($user->permissions as $permission)
                                <li class="col-md-4 list-group-item d-flex justify-content-between align-items-center">
                                    {{ $permission->name }}
                                    <span class="badge bg-success">Granted</span>
                                </li>
                            @endforeach
                        </div>
                    </ul>
                </div>
            {{-- @endforeach --}}
        </div>
    </div>
    @endif
</div>
@endsection
