@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-chart-line"></i> Payment Tracking Dashboard
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('properties') }}">Home</a></li>
                        <li class="breadcrumb-item active">Payment Tracking</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Completed Payments</span>
                    <span class="info-box-number">{{ $stats['completed_payments'] }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="fas fa-hourglass-half"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Partial Payments (Installments)</span>
                    <span class="info-box-number">{{ $stats['partial_payments'] }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Debtors (No Payment)</span>
                    <span class="info-box-number">{{ $stats['debtors'] }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Stats --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-calendar-times"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Overdue Payments</span>
                    <span class="info-box-number">{{ $stats['overdue_payments'] }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="info-box bg-secondary">
                <span class="info-box-icon"><i class="fas fa-naira-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Amount Due</span>
                    <span class="info-box-number">₦{{ number_format($stats['total_amount_due'], 0) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs for different payment statuses --}}
    <div class="card">
        <div class="card-header p-0 pt-3">
            <ul class="nav nav-tabs" role="tablist">
                <li class="pt-2 px-3">
                    <a class="nav-link active" id="debtors-tab" data-toggle="pill" href="#debtors" role="tab">
                        <i class="fas fa-user-slash"></i> Debtors ({{ $debtors->count() }})
                    </a>
                </li>
                <li class="pt-2 px-3">
                    <a class="nav-link" id="installment-tab" data-toggle="pill" href="#installment" role="tab">
                        <i class="fas fa-credit-card"></i> Installment Payers ({{ $installmentPayers->count() }})
                    </a>
                </li>
                <li class="pt-2 px-3">
                    <a class="nav-link" id="completed-tab" data-toggle="pill" href="#completed" role="tab">
                        <i class="fas fa-check"></i> Completed Payments ({{ $completedPayments->count() }})
                    </a>
                </li>
                <li class="pt-2 px-3">
                    <a class="nav-link" id="overdue-tab" data-toggle="pill" href="#overdue" role="tab">
                        <i class="fas fa-clock"></i> Overdue ({{ $overduePayments->count() }})
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content">
                {{-- Debtors Tab --}}
                <div class="tab-pane fade show active" id="debtors" role="tabpanel">
                    <h5 class="mb-3">Debtors - Those with No Payment Yet</h5>
                    @if($debtors->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Property</th>
                                        <th>Unit</th>
                                        <th>Buyer/Tenant</th>
                                        <th>Type</th>
                                        <th>Amount Due</th>
                                        <th>Due Date</th>
                                        <th>Days Overdue</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($debtors as $plan)
                                        @php
                                            $payable = $plan->payable;
                                            $nextDue = $plan->getNextDueInstallment();
                                            if ($payable instanceof \App\Models\UnitSale) {
                                                $property = $payable->property->name;
                                                $unit = $payable->propertyUnit->unit_number;
                                                $name = $payable->buyer_name;
                                                $type = 'Unit Sale';
                                            } else {
                                                $property = $payable->property->name;
                                                $unit = $payable->propertyUnit->unit_number;
                                                $name = "{$payable->tenant->first_name} {$payable->tenant->last_name}";
                                                $type = 'Lease';
                                            }
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $property }}</strong></td>
                                            <td>{{ $unit }}</td>
                                            <td>{{ $name }}</td>
                                            <td>
                                                <span class="badge badge-{{ $type === 'Unit Sale' ? 'primary' : 'info' }}">
                                                    {{ $type }}
                                                </span>
                                            </td>
                                            <td><span class="badge badge-danger">₦{{ number_format($plan->total_amount, 0) }}</span></td>
                                            <td>{{ $nextDue ? $nextDue->due_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                @if($nextDue && $nextDue->due_date->isPast())
                                                    <span class="badge badge-danger">{{ now()->diffInDays($nextDue->due_date) }} days</span>
                                                @else
                                                    <span class="badge badge-secondary">Upcoming</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('payment.plan.detail', $plan->id) }}" class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> No debtors at the moment!
                        </div>
                    @endif
                </div>

                {{-- Installment Payers Tab --}}
                <div class="tab-pane fade" id="installment" role="tabpanel">
                    <h5 class="mb-3">Installment Payers - Those Making Partial Payments</h5>
                    @if($installmentPayers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Property</th>
                                        <th>Unit</th>
                                        <th>Buyer/Tenant</th>
                                        <th>Type</th>
                                        <th>Total Amount</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                        <th>Progress</th>
                                        <th>Next Due</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($installmentPayers as $plan)
                                        @php
                                            $payable = $plan->payable;
                                            $nextDue = $plan->getNextDueInstallment();
                                            if ($payable instanceof \App\Models\UnitSale) {
                                                $property = $payable->property->name;
                                                $unit = $payable->propertyUnit->unit_number;
                                                $name = $payable->buyer_name;
                                                $type = 'Unit Sale';
                                            } else {
                                                $property = $payable->property->name;
                                                $unit = $payable->propertyUnit->unit_number;
                                                $name = "{$payable->tenant->first_name} {$payable->tenant->last_name}";
                                                $type = 'Lease';
                                            }
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $property }}</strong></td>
                                            <td>{{ $unit }}</td>
                                            <td>{{ $name }}</td>
                                            <td>
                                                <span class="badge badge-{{ $type === 'Unit Sale' ? 'primary' : 'info' }}">
                                                    {{ $type }}
                                                </span>
                                            </td>
                                            <td>₦{{ number_format($plan->total_amount, 0) }}</td>
                                            <td><span class="text-success">₦{{ number_format($plan->amount_paid, 0) }}</span></td>
                                            <td><span class="text-warning">₦{{ number_format($plan->balance, 0) }}</span></td>
                                            <td>
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar bg-info" style="width: {{ $plan->getProgressPercentage() }}%"></div>
                                                </div>
                                                <small>{{ $plan->getProgressPercentage() }}%</small>
                                            </td>
                                            <td>
                                                @if($nextDue)
                                                    <strong>{{ $nextDue->due_date->format('M d, Y') }}</strong>
                                                    (₦{{ number_format($nextDue->amount_due, 0) }})
                                                @else
                                                    Completed
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('payment.plan.detail', $plan->id) }}" class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No installment payers yet.
                        </div>
                    @endif
                </div>

                {{-- Completed Payments Tab --}}
                <div class="tab-pane fade" id="completed" role="tabpanel">
                    <h5 class="mb-3">Completed Payments - Fully Paid</h5>
                    @if($completedPayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Property</th>
                                        <th>Unit</th>
                                        <th>Buyer/Tenant</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Payment Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedPayments as $plan)
                                        @php
                                            $payable = $plan->payable;
                                            if ($payable instanceof \App\Models\UnitSale) {
                                                $property = $payable->property->name;
                                                $unit = $payable->propertyUnit->unit_number;
                                                $name = $payable->buyer_name;
                                                $type = 'Unit Sale';
                                            } else {
                                                $property = $payable->property->name;
                                                $unit = $payable->propertyUnit->unit_number;
                                                $name = "{$payable->tenant->first_name} {$payable->tenant->last_name}";
                                                $type = 'Lease';
                                            }
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $property }}</strong></td>
                                            <td>{{ $unit }}</td>
                                            <td>{{ $name }}</td>
                                            <td>
                                                <span class="badge badge-{{ $type === 'Unit Sale' ? 'primary' : 'info' }}">
                                                    {{ $type }}
                                                </span>
                                            </td>
                                            <td><span class="badge badge-success">₦{{ number_format($plan->total_amount, 0) }}</span></td>
                                            <td>{{ $plan->last_payment_date ? $plan->last_payment_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('payment.plan.detail', $plan->id) }}" class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No completed payments yet.
                        </div>
                    @endif
                </div>

                {{-- Overdue Tab --}}
                <div class="tab-pane fade" id="overdue" role="tabpanel">
                    <h5 class="mb-3">Overdue Installments - Payment Past Due</h5>
                    @if($overduePayments->count() > 0)
                        <div class="alert alert-danger mb-3">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>{{ $overduePayments->count() }}</strong> installments are overdue and require immediate attention!
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Installment #</th>
                                        <th>Amount Due</th>
                                        <th>Due Date</th>
                                        <th>Days Overdue</th>
                                        <th>Buyer/Tenant</th>
                                        <th>Property</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($overduePayments as $installment)
                                        @php
                                            $plan = $installment->paymentPlan;
                                            $payable = $plan->payable;
                                            if ($payable instanceof \App\Models\UnitSale) {
                                                $name = $payable->buyer_name;
                                                $property = $payable->property->name;
                                            } else {
                                                $name = "{$payable->tenant->first_name} {$payable->tenant->last_name}";
                                                $property = $payable->property->name;
                                            }
                                        @endphp
                                        <tr class="table-danger">
                                            <td><strong>#{{ $installment->installment_number }}</strong></td>
                                            <td><strong>₦{{ number_format($installment->amount_due, 0) }}</strong></td>
                                            <td>{{ $installment->due_date->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge badge-danger">
                                                    {{ now()->diffInDays($installment->due_date) }} days
                                                </span>
                                            </td>
                                            <td>{{ $name }}</td>
                                            <td>{{ $property }}</td>
                                            <td>
                                                <a href="{{ route('payment.overdue.detail', $installment->id) }}" class="btn btn-sm btn-warning" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> No overdue payments!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Export Section --}}
    <div class="row mt-3">
        <div class="col-md-12">
            <a href="{{ route('payment.debtors.export') }}" class="btn btn-primary" title="Export Debtors Report">
                <i class="fas fa-download"></i> Export Debtors Report
            </a>
        </div>
    </div>

    <style>
        .nav-tabs .nav-link {
            color: #495057;
            border: none;
            border-bottom: 3px solid transparent;
        }

        .nav-tabs .nav-link:hover {
            border-bottom-color: #007bff;
        }

        .nav-tabs .nav-link.active {
            color: #007bff;
            border-bottom-color: #007bff;
            background-color: transparent;
        }

        .info-box {
            box-shadow: 0 0 1px rgba(0, 0, 0, 0.1);
        }

        .info-box-number {
            font-size: 28px;
            font-weight: bold;
        }

        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }

        .table-danger {
            background-color: #ffe6e6 !important;
        }
    </style>
@endsection
