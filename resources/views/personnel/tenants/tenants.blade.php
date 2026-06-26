@extends('layouts.template')
@php
    $pagetype = 'Table';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tenants</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Tenants</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card">
        <div class="card-header with-border">
            <h3 class="card-title">
                <i class="fas fa-users"></i> Tenant Management
            </h3>
            @can('create tenant')
                <div class="card-tools pull-right">
                    <a href="{{ route('new.tenant') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Tenant
                    </a>
                </div>
            @endcan
        </div>

        <div class="card-body" style="overflow: auto;">
            @if($tenants->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Name</th>
                                <th>Contact Information</th>
                                <th>Address</th>
                                <th>Active Leases</th>
                                <th>Monthly Rent</th>
                                <th>Outstanding Balance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tenants as $tenant)
                                @php
                                    // Calculate lease info
                                    $activeLeases = $tenant->leases()->where('status', 'active')->get();
                                    $totalMonthlyRent = $activeLeases->sum('rent_amount');
                                    $totalOutstanding = 0;
                                    
                                    foreach ($activeLeases as $lease) {
                                        $payments = $lease->payments()->where('status', 'paid')->sum('amount');
                                        $outstanding = $lease->rent_amount - $payments;
                                        $totalOutstanding += max(0, $outstanding);
                                    }
                                @endphp
                                <tr @if ($tenant->user && $tenant->user->status == 'active') style="background-color: #e8f5e9;" @endif>
                                    <td><strong>{{ $tenant->id }}</strong></td>
                                    <td>
                                        <strong>{{ $tenant->full_name }}</strong>
                                        @if($tenant->user && $tenant->user->status === 'active')
                                            <br><span class="badge badge-success">Active</span>
                                        @else
                                            <br><span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fas fa-phone text-primary"></i>
                                        <a href="tel:{{ $tenant->phone_number }}">{{ $tenant->phone_number ?? 'N/A' }}</a>
                                        <br>
                                        <i class="fas fa-envelope text-info"></i>
                                        <a href="mailto:{{ $tenant->email }}">{{ $tenant->email }}</a>
                                    </td>
                                    <td>
                                        <small>{{ $tenant->address ?? 'N/A' }}</small>
                                        <br>
                                        <small class="text-muted">{{ $tenant->emergency_contact_name ? 'Emg: ' . $tenant->emergency_contact_name : '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $activeLeases->count() }} Active</span>
                                        <br>
                                        <small class="text-muted">{{ $tenant->leases()->count() }} Total</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">₦{{ number_format($totalMonthlyRent, 2) }}</span>
                                    </td>
                                    <td>
                                        @if($totalOutstanding > 0)
                                            <span class="badge badge-danger">₦{{ number_format($totalOutstanding, 2) }}</span>
                                        @else
                                            <span class="badge badge-success">₦0.00</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ ($tenant->user && $tenant->user->status === 'active') ? 'bg-success' : 'bg-danger' }}">
                                            {{ ($tenant->user && $tenant->user->status) ? Str::headline($tenant->user->status) : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <!-- View Details -->
                                            @can('view tenant')
                                                <a href="{{ route('show.tenant', $tenant->id) }}" class="btn btn-info" title="View Full Profile">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            @endcan

                                            <!-- Dropdown Menu -->
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-cog"></i> More
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <!-- View Properties -->
                                                    @if($activeLeases->count() > 0)
                                                        <h6 class="dropdown-header">Leases & Properties</h6>
                                                        <a class="dropdown-item" href="{{ route('show.tenant', $tenant->id) }}#leases">
                                                            <i class="fas fa-home"></i> View Leases
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('show.tenant', $tenant->id) }}#payments">
                                                            <i class="fas fa-receipt"></i> Payment History
                                                        </a>
                                                    @endif

                                                    <div class="dropdown-divider"></div>
                                                    <h6 class="dropdown-header">Management</h6>

                                                    <!-- Edit Profile -->
                                                    @can('edit tenant')
                                                        <a class="dropdown-item" href="{{ route('edit.tenant', $tenant->id) }}">
                                                            <i class="fas fa-edit"></i> Edit Profile
                                                        </a>
                                                    @endcan

                                                    <!-- Manage Role -->
                                                    @hasanyrole('System Admin|Super Admin')
                                                        <a class="dropdown-item" href="{{ route('user.role.edit', $tenant->user) }}">
                                                            <i class="fas fa-shield-alt"></i> Manage Role
                                                        </a>
                                                    @endhasanyrole

                                                    <div class="dropdown-divider"></div>

                                                    <!-- Delete Tenant -->
                                                    @can('delete tenant')
                                                        <form action="{{ route('delete.tenant', $tenant->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure? This will delete all associated data.');">
                                                                <i class="fas fa-trash"></i> Delete Tenant
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3 d-flex justify-content-center">
                    {{ $tenants->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No tenants found. 
                    @can('create tenant')
                        <a href="{{ route('new.tenant') }}">Create the first tenant</a>
                    @endcan
                </div>
            @endif
        </div>
    </div>

    <!-- Summary Card -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Tenants</span>
                    <span class="info-box-number">{{ $tenants->total() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Active Tenants</span>
                    <span class="info-box-number">{{ $tenants->where('user.status', 'active')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-home"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Active Leases</span>
                    <span class="info-box-number">
                        @php
                            $totalActiveLeases = 0;
                            foreach($tenants as $t) {
                                $totalActiveLeases += $t->leases()->where('status', 'active')->count();
                            }
                        @endphp
                        {{ $totalActiveLeases }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Outstanding</span>
                    <span class="info-box-number">
                        @php
                            $totalOutstanding = 0;
                            foreach($tenants as $t) {
                                foreach($t->leases as $l) {
                                    $payments = $l->payments()->where('status', 'paid')->sum('amount');
                                    $outstanding = $l->rent_amount - $payments;
                                    $totalOutstanding += max(0, $outstanding);
                                }
                            }
                        @endphp
                        {{ count(array_filter([$totalOutstanding])) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection
