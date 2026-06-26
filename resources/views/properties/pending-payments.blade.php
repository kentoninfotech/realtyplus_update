@extends('layouts.template')
@php
    $pagetype = 'Table';
@endphp
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pending Payments</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Pending Payments</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    {{-- Stats Cards --}}
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $pendingRentCount ?? 0 }}</h3>
                        <p>Pending Rent Payments</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-home"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $pendingLandCount ?? 0 }}</h3>
                        <p>Pending Land Payments</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-map"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $pendingPropertyCount ?? 0 }}</h3>
                        <p>Pending Property Payments</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-document"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>₦{{ number_format($totalPendingAmount ?? 0, 0, '.', ',') }}</h3>
                        <p>Total Pending Amount</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-cash"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Payments Table --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Pending Payments</h3>
        </div>
        <div class="card-body" style="overflow: auto;">
            <table class="table responsive-table" id="payments-table">
                <thead>
                    <tr>
                        <th>Payment Type</th>
                        <th>Property/Unit</th>
                        <th>Tenant/Payer</th>
                        <th>Amount (₦)</th>
                        <th>Due Date</th>
                        <th>Days Overdue</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingPayments as $payment)
                        <tr>
                            <td>
                                @if($payment->payment_type === 'rent')
                                    <span class="badge badge-info">Rent</span>
                                @elseif($payment->payment_type === 'land')
                                    <span class="badge badge-warning">Land</span>
                                @elseif($payment->payment_type === 'property')
                                    <span class="badge badge-success">Property</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($payment->payment_type) }}</span>
                                @endif
                            </td>
                            <td>{{ $payment->property_name ?? $payment->unit_number ?? 'N/A' }}</td>
                            <td>{{ $payment->payer_name ?? 'N/A' }}</td>
                            <td>₦{{ number_format($payment->amount, 2, '.', ',') }}</td>
                            <td>{{ $payment->due_date ? $payment->due_date->format('d M, Y') : 'N/A' }}</td>
                            <td>
                                @if($payment->due_date)
                                    @php
                                        $daysOverdue = now()->diffInDays($payment->due_date, false);
                                    @endphp
                                    @if($daysOverdue > 0)
                                        <span class="badge badge-danger">{{ $daysOverdue }} days</span>
                                    @else
                                        <span class="badge badge-warning">Due in {{ abs($daysOverdue) }} days</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-danger">Pending</span>
                            </td>
                            <td width="100">
                                <a href="{{ $payment['detail_url'] ?? route('leases') }}" class="btn btn-sm btn-primary" title="View Details">
                                    <i class="fa fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No pending payments</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(function () {
            $("#payments-table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            }).buttons().container().appendTo('#payments-table_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
