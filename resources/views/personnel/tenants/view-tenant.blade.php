@extends('layouts.template')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Tenant Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('tenants') }}">Tenants</a></li>
                    <li class="breadcrumb-item active">{{ $tenant->full_name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-3">
    <div class="col-12">
        <div class="btn-group float-right">
            @can('edit tenant')
                <a href="{{ route('edit.tenant', $tenant->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
            @endcan
            @can('create property')
                <a href="{{ route('new.property') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add Property
                </a>
            @endcan
            <a href="{{ route('tenants') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div>

<!-- Tenant Summary Cards -->
<div class="row">
    <!-- Profile Card -->
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img src="{{ (isset($tenant->user->personnel->picture) && $tenant->user->personnel->picture !== null) ? asset('personnel-files/pictures/' . $tenant->user->personnel->picture) : 'https://ui-avatars.com/api/?name=' . urlencode($tenant->full_name) }}" 
                         alt="Tenant Avatar" class="profile-user-img img-fluid img-circle" style="width: 100px; height: 100px;">
                </div>

                <h3 class="profile-username text-center">{{ $tenant->full_name }}</h3>
                
                <p class="text-muted text-center">
                    <span class="badge {{ $tenant->user && $tenant->user->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                        {{ $tenant->user && $tenant->user->status ? Str::headline($tenant->user->status) : 'Inactive' }}
                    </span>
                </p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Email:</b>
                        <a href="mailto:{{ $tenant->email }}" class="float-right">{{ $tenant->email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Phone:</b>
                        <a href="tel:{{ $tenant->phone_number }}" class="float-right">{{ $tenant->phone_number }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>Address:</b>
                        <span class="float-right">{{ $tenant->address ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Emergency Contact:</b>
                        <span class="float-right">{{ $tenant->emergency_contact_name ?? 'N/A' }}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Emergency Phone:</b>
                        <a href="tel:{{ $tenant->emergency_contact_phone }}" class="float-right">{{ $tenant->emergency_contact_phone ?? 'N/A' }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="col-md-8">
        <div class="row">
            <!-- Total Rent -->
            <div class="col-md-6 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info">
                        <i class="fas fa-money-bill-wave"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Monthly Rent</span>
                        <span class="info-box-number">₦{{ number_format($totalRent, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Total Paid -->
            <div class="col-md-6 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Paid</span>
                        <span class="info-box-number">₦{{ number_format($totalPaidRent, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Outstanding Balance -->
            <div class="col-md-6 col-sm-6 col-12">
                <div class="info-box {{ $totalOutstanding > 0 ? 'bg-danger' : 'bg-success' }}">
                    <span class="info-box-icon">
                        <i class="fas {{ $totalOutstanding > 0 ? 'fa-exclamation-triangle' : 'fa-check' }}"></i>
                    </span>
                    <div class="info-box-content" style="color: white;">
                        <span class="info-box-text">Outstanding Balance</span>
                        <span class="info-box-number">₦{{ number_format($totalOutstanding, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Active Leases -->
            <div class="col-md-6 col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-warning">
                        <i class="fas fa-building"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Active Leases</span>
                        <span class="info-box-number">{{ $activeLeases }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active Leases Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header with-border">
                <h3 class="card-title">
                    <i class="fas fa-home"></i> Active Leases & Properties
                </h3>
            </div>
            <div class="card-body">
                @if($leases->where('status', 'active')->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Property</th>
                                    <th>Unit</th>
                                    <th>Monthly Rent</th>
                                    <th>Lease Start</th>
                                    <th>Lease End</th>
                                    <th>Payment Frequency</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leases->where('status', 'active') as $lease)
                                    <tr>
                                        <td>
                                            @if($lease->property)
                                                <strong>{{ $lease->property->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $lease->property->state }}, {{ $lease->property->country }}</small>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($lease->propertyUnit)
                                                {{ $lease->propertyUnit->unit_name ?? 'Unit #' . $lease->propertyUnit->id }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-success">₦{{ number_format($lease->rent_amount, 2) }}</span>
                                        </td>
                                        <td>
                                            {{ $lease->start_date ? \Carbon\Carbon::parse($lease->start_date)->format('M d, Y') : 'N/A' }}
                                        </td>
                                        <td>
                                            {{ $lease->end_date ? \Carbon\Carbon::parse($lease->end_date)->format('M d, Y') : 'N/A' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ Str::headline($lease->payment_frequency ?? 'Monthly') }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">{{ Str::headline($lease->status) }}</span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-xs btn-primary" title="View Lease Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('edit property')
                                                <a href="#" class="btn btn-xs btn-warning" title="Edit Lease">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No active leases for this tenant.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Payment History Section -->
<div class="row mt-4">
    <div class="col-md-8">
        <div class="card card-success">
            <div class="card-header with-border">
                <h3 class="card-title">
                    <i class="fas fa-history"></i> Payment History
                </h3>
            </div>
            <div class="card-body">
                @if($recentPayments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Property/Unit</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments->take(10) as $transaction)
                                    <tr>
                                        <td>
                                            <strong>{{ $transaction->created_at ? $transaction->created_at->format('M d, Y') : 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $transaction->created_at ? $transaction->created_at->format('H:i A') : '' }}</small>
                                        </td>
                                        <td>
                                            @if($transaction->transactionable && $transaction->transactionable->property)
                                                {{ $transaction->transactionable->property->name }}
                                                @if($transaction->transactionable->propertyUnit)
                                                    - {{ $transaction->transactionable->propertyUnit->unit_name ?? 'Unit #' . $transaction->transactionable->propertyUnit->unit_number }}
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-success">₦{{ number_format($transaction->amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ Str::headline($transaction->payment_method ?? 'Unknown') }}</span>
                                        </td>
                                        <td>
                                            @if ($transaction->is_partial_payment && $transaction->balance_due > 0)
                                                <span class="badge badge-warning">Partial Payment</span>
                                            @else
                                                <span class="badge badge-success">Full Payment</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No payment records found in the last 12 months.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Payments Section -->
    <div class="col-md-4">
        <div class="card card-warning">
            <div class="card-header with-border">
                <h3 class="card-title">
                    <i class="fas fa-calendar-alt"></i> Upcoming Payments
                </h3>
            </div>
            <div class="card-body">
                @if($leases->where('status', 'active')->count() > 0)
                    @php
                        $upcomingLeases = $leases->where('status', 'active')
                            ->where('renewal_date', '>', now())
                            ->sortBy('renewal_date')
                            ->take(5);
                    @endphp
                    
                    @if($upcomingLeases->count() > 0)
                        <div class="timeline">
                            @foreach($upcomingLeases as $lease)
                                @php
                                    $daysUntilRenewal = now()->diffInDays($lease->renewal_date);
                                    $isUrgent = $daysUntilRenewal <= 7;
                                @endphp
                                <div class="timeline-item">
                                    <span class="timeline-icon bg-{{ $isUrgent ? 'danger' : 'info' }}">
                                        <i class="fas fa-calendar-check"></i>
                                    </span>
                                    <div class="timeline-content">
                                        <h3 class="timeline-header">
                                            <a href="#">{{ $lease->property->name ?? 'Property' }}</a>
                                            <span class="float-right badge badge-{{ $isUrgent ? 'danger' : 'info' }}">
                                                {{ $daysUntilRenewal }} days
                                            </span>
                                        </h3>
                                        <div class="timeline-body">
                                            <strong>Amount:</strong> ₦{{ number_format($lease->rent_amount, 2) }}<br>
                                            <strong>Due Date:</strong> {{ \Carbon\Carbon::parse($lease->renewal_date)->format('M d, Y') }}<br>
                                            <strong>Unit:</strong> {{ $lease->propertyUnit->unit_name ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No upcoming payments scheduled.
                        </div>
                    @endif
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No active leases.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- All Payments with Pagination -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card card-info">
            <div class="card-header with-border">
                <h3 class="card-title">
                    <i class="fas fa-list"></i> All Payments
                </h3>
            </div>
            <div class="card-body">
                @if(isset($allTransactions) && $allTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Property/Unit</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Payment Status</th>
                                    <th>Balance Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at ? $transaction->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            @if($transaction->transactionable && $transaction->transactionable->property)
                                                {{ $transaction->transactionable->property->name }}
                                                @if($transaction->transactionable->propertyUnit)
                                                    - {{ $transaction->transactionable->propertyUnit->unit_name ?? 'Unit #' . $transaction->transactionable->propertyUnit->unit_number }}
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td><span class="badge badge-success">₦{{ number_format($transaction->amount, 2) }}</span></td>
                                        <td>{{ Str::headline($transaction->payment_method ?? 'Unknown') }}</td>
                                        <td>
                                            @if ($transaction->is_partial_payment && $transaction->balance_due > 0)
                                                <span class="badge badge-warning">Partial Payment</span>
                                            @else
                                                <span class="badge badge-success">Full Payment</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($transaction->balance_due > 0)
                                                <span class="badge badge-danger">₦{{ number_format($transaction->balance_due, 2) }}</span>
                                            @else
                                                <span class="badge badge-success">Paid</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $allTransactions->links('pagination::bootstrap-4') }}
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No payment records found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Lease History Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card card-secondary">
            <div class="card-header with-border">
                <h3 class="card-title">
                    <i class="fas fa-archive"></i> Lease History (All Leases)
                </h3>
            </div>
            <div class="card-body">
                @if($leases->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Property</th>
                                    <th>Unit</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Monthly Rent</th>
                                    <th>Deposit</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leases as $lease)
                                    <tr class="{{ $lease->status === 'active' ? 'table-success' : '' }}">
                                        <td>
                                            <strong>{{ $lease->property->name ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $lease->property->state ?? '' }}, {{ $lease->property->country ?? '' }}</small>
                                        </td>
                                        <td>{{ $lease->propertyUnit->unit_name ?? 'N/A' }}</td>
                                        <td>{{ $lease->start_date ? \Carbon\Carbon::parse($lease->start_date)->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $lease->end_date ? \Carbon\Carbon::parse($lease->end_date)->format('M d, Y') : 'N/A' }}</td>
                                        <td><span class="badge badge-info">₦{{ number_format($lease->rent_amount, 2) }}</span></td>
                                        <td><span class="badge badge-secondary">₦{{ number_format($lease->deposit_amount, 2) }}</span></td>
                                        <td>
                                            <span class="badge {{ $lease->status === 'active' ? 'badge-success' : ($lease->status === 'terminated' ? 'badge-danger' : 'badge-warning') }}">
                                                {{ Str::headline($lease->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No leases found for this tenant.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
