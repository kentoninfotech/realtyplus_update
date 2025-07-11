@extends('layouts.template')

@php
    $pagetype = 'Table';
@endphp

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Contractors <span class="text-muted fs-6">({{ $contractors->total() }})</span></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Contractors</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<div class="card">
  <div class="card-body" style="overflow: auto;">

    <div class="mb-1">
        <div>
            <!-- <button class="btn btn-outline-secondary me-2">Export</button> -->
          @can('create user')
            <a href="{{ route('new.personnel') }}" class="btn btn-primary" style="float: right;"><i class="fa fa-plus"></i> New Personnel</a>
          @endcan
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle" id="products">
            <thead class="table-light">
                <tr>
                    <!-- <th><input type="checkbox"></th> -->
                    <th class="col-md-3">Name</th>
                    <th>Phone</th>
                    <th>Designation</th>
                    <th>Staff ID</th>
                    <th>Role</th>
                    <th width="20">Status</th>
                    <th width="20">Options</th>
                </tr>
            </thead>
            <tbody>
                @if(!$contractors->isEmpty())
                @foreach($contractors as $contractor)
                <tr>
                    <!-- <td><input type="checkbox" name="select_contractor[]" value="{{-- $contractor->id --}}"></td> -->
                    <td class="d-flex align-items-center">
                        <img src="{{ (isset($contractor->personnel->picture) && $contractor->personnel->picture !== null) ? asset('public/personnel/pictures/' .$contractor->personnel->picture) : 'https://ui-avatars.com/api/?name=' . urlencode($contractor->name) }}" class="rounded-circle me-2" width="40" height="40" alt="{{ $contractor->name }}">
                        <div style="margin-left:15px;">
                            <div>{{ $contractor->name }}</div>
                            <div class="text-muted small">{{ $contractor->email }}</div>
                        </div>
                    </td>
                    <td>{{ $contractor->phone_number ?? 'N/A' }}</td>
                    <td>{{ $contractor->personnel->designation ?? 'N/A' }}
                        <div class="text-muted small">{{ $contractor->personnel->department ?? 'N/A' }}</div>
                    </td>
                    <td>{{ $contractor->personnel->staff_id ?? '' }}</td>
                    <td>
                        @foreach($contractor->getRoleNames() ?? [] as $role)
                            <span class="badge
                                @if($role === 'Admin') bg-danger text-white
                                @elseif($role === 'Manager') bg-warning text-white
                                @elseif($role === 'Finance') bg-info text-white
                                @elseif($role === 'Staff') bg-primary text-white
                                @else bg-secondary text-white
                                @endif
                            ">
                                {{ $role }}</span>
                        @endforeach
                    </td>
                    <td>
                        <span class="badge {{ $contractor->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ $contractor->status }}
                        </span>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn btn-secondary" data-toggle="dropdown">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16"><path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/></svg>
                            </button>
                            <div class="dropdown-menu text-center">
                                @can('view user')
                                    <a class="dropdown-item" href="{{ route('show.personnel', $contractor->id) }}"><i class="fa fa-eye"></i> View</a>
                                @endcan
                                @can('edit user')
                                    <a class="dropdown-item" href="{{ route('edit.personnel', $contractor->id) }}"><i class="fa fa-edit"></i> Edit</a>
                                @endcan
                                @hasanyrole('System Admin|Super Admin') 
                                    <a class="dropdown-item" href="{{ route('user.role.edit', $contractor) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16"><path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/></svg>  
                                    Manage role</a>
                                @endhasanyrole
                                @can('delete user')
                                    <div class="dropdown-divider"></div>
                                    <form class="d-inline" action="{{ route('delete.personnel', $contractor->id) }}" method="post">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this personnel?');">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/><path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/></svg> 
                                        Delete
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
                 @endforeach
                 @else
                <tr>
                    <td colspan="7" class="text-center text-muted">No contractor found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-between mt-3">
        <div>
            {{ $contractors->links('pagination::bootstrap-4') }}
        </div>
    </div>
 </div>
</div>
@endsection
