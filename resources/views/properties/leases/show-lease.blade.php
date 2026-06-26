@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Lease Details</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('leases') }}">Leases</a></li>
                        <li class="breadcrumb-item active">Lease Details</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lease Information</h3>
            <div class="card-tools">
                <a href="{{ route('edit.lease', $lease->id) }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <a href="{{ route('leases') }}" class="btn btn-sm btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th style="width: 30%">Lease ID:</th>
                                <td>{{ $lease->id }}</td>
                            </tr>
                            <tr>
                                <th>Tenant:</th>
                                <td>
                                    <a href="#">{{ $lease->tenant->full_name ?? 'N/A' }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Property:</th>
                                <td>
                                    <a href="{{ route('show.property', $lease->property->id) }}">{{ $lease->property->name ?? 'N/A' }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th>Unit:</th>
                                <td>
                                    @if($lease->propertyUnit)
                                        <a href="{{ route('show.unit', $lease->propertyUnit->id) }}">{{ $lease->propertyUnit->unit_number }}</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Start Date:</th>
                                <td>{{ $lease->start_date->format('M d, Y') ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>End Date:</th>
                                <td>{{ $lease->end_date->format('M d, Y') ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th style="width: 30%">Status:</th>
                                <td>
                                    <span class="badge badge-{{ 
                                        $lease->status == 'active' ? 'primary' : 
                                        ($lease->status == 'pending' ? 'warning' : 
                                        ($lease->status == 'expired' ? 'danger' : 
                                        ($lease->status == 'renewed' ? 'success' : 'secondary')))
                                    }}">
                                        {{ Str::headline($lease->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Payment Frequency:</th>
                                <td>{{ Str::headline($lease->payment_frequency) ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Rent Amount:</th>
                                <td>₦{{ number_format($lease->rent_amount, 0, '.', ',') }}</td>
                            </tr>
                            <tr>
                                <th>Monthly Rent:</th>
                                <td>₦{{ number_format($lease->monthly_rent ?? 0, 0, '.', ',') }}</td>
                            </tr>
                            <tr>
                                <th>Renewal Date:</th>
                                <td>{{ optional($lease->renewal_date)->format('M d, Y') ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Notes:</th>
                                <td>{{ $lease->notes ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- LEASE TRANSACTIONS -->
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Lease Payments</h3>
            <div class="card-tools">
                @can('create property')
                    <a href="{{ route('add.lease.transaction', $lease->id) }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-credit-card"></i> Record Payment
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @if($lease->transactions && $lease->transactions->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lease->transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                <td>₦{{ number_format($transaction->amount, 0, '.', ',') }}</td>
                                <td>{{ $transaction->transaction_type ?? 'N/A' }}</td>
                                <td>{{ $transaction->description ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted">No payment transactions recorded yet.</p>
            @endif
        </div>
    </div>
@endsection
