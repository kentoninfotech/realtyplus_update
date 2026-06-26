@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-exclamation-triangle"></i> Overdue Installment
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('payment.tracking') }}">Payment Tracking</a></li>
                        <li class="breadcrumb-item active">Overdue Installment</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Overdue Alert --}}
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <strong>OVERDUE PAYMENT!</strong> This payment is {{ now()->diffInDays($installment->due_date) }} days overdue.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="row">
        {{-- Installment Details --}}
        <div class="col-md-5">
            <div class="card card-danger card-outline">
                <div class="card-header">
                    <h3 class="card-title">Overdue Installment Details</h3>
                </div>
                <div class="card-body">
                    <div class="summary-row">
                        <span class="label">Installment #:</span>
                        <span class="value"><strong>{{ $installment->installment_number }}</strong></span>
                    </div>

                    <div class="summary-row">
                        <span class="label">Payment Plan:</span>
                        <span class="value">{{ $paymentPlan->payment_type === 'installment' ? 'Installment' : 'Full Payment' }}</span>
                    </div>

                    <div class="summary-row">
                        <span class="label">Due Date:</span>
                        <span class="value text-danger"><strong>{{ $installment->due_date->format('M d, Y') }}</strong></span>
                    </div>

                    <div class="summary-row">
                        <span class="label">Days Overdue:</span>
                        <span class="value"><span class="badge badge-danger">{{ now()->diffInDays($installment->due_date) }} days</span></span>
                    </div>

                    <hr>

                    <div class="summary-row summary-large">
                        <span class="label">Amount Due:</span>
                        <span class="value text-danger"><strong>₦{{ number_format($installment->amount_due, 2) }}</strong></span>
                    </div>

                    <div class="summary-row">
                        <span class="label">Amount Paid:</span>
                        <span class="value">₦{{ number_format($installment->amount_paid, 2) }}</span>
                    </div>

                    <div class="summary-row">
                        <span class="label">Balance:</span>
                        <span class="value text-danger"><strong>₦{{ number_format($installment->getRemainingBalance(), 2) }}</strong></span>
                    </div>

                    <hr>

                    <div class="summary-row">
                        <span class="label">Status:</span>
                        <span class="value"><span class="badge badge-danger">Overdue</span></span>
                    </div>

                    <div class="summary-row">
                        <span class="label">Payment Method:</span>
                        <span class="value">{{ $installment->payment_method ?? 'Not yet recorded' }}</span>
                    </div>

                    @if($installment->reference_number)
                        <div class="summary-row">
                            <span class="label">Reference #:</span>
                            <span class="value">{{ $installment->reference_number }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Payment Information --}}
        <div class="col-md-7">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">Associated Payment Information</h3>
                </div>
                <div class="card-body">
                    {{-- Transaction Type --}}
                    <div class="alert alert-info">
                        <strong>Transaction Type:</strong> {{ $payableDetails['type'] ?? 'N/A' }}
                        <br>
                        <strong>Property:</strong> {{ $payableDetails['property_name'] ?? 'N/A' }}
                        <br>
                        <strong>Unit:</strong> {{ $payableDetails['unit_number'] ?? 'N/A' }}
                    </div>

                    {{-- Buyer/Tenant Info --}}
                    @if(isset($payableDetails['buyer_name']))
                        <div class="card card-sm card-primary">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Buyer Information</h5>
                            </div>
                            <div class="card-body p-3">
                                <div class="summary-row">
                                    <span class="label">Buyer:</span>
                                    <span class="value"><strong>{{ $payableDetails['buyer_name'] }}</strong></span>
                                </div>
                                <div class="summary-row">
                                    <span class="label">Email:</span>
                                    <span class="value">{{ $payableDetails['buyer_email'] ?? 'N/A' }}</span>
                                </div>
                                <div class="summary-row">
                                    <span class="label">Buyer Type:</span>
                                    <span class="value"><span class="badge badge-info">{{ $payableDetails['buyer_type'] ?? 'N/A' }}</span></span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($payableDetails['tenant_name']))
                        <div class="card card-sm card-info">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Tenant Information</h5>
                            </div>
                            <div class="card-body p-3">
                                <div class="summary-row">
                                    <span class="label">Tenant:</span>
                                    <span class="value"><strong>{{ $payableDetails['tenant_name'] }}</strong></span>
                                </div>
                                <div class="summary-row">
                                    <span class="label">Email:</span>
                                    <span class="value">{{ $payableDetails['tenant_email'] ?? 'N/A' }}</span>
                                </div>
                                <div class="summary-row">
                                    <span class="label">Phone:</span>
                                    <span class="value">{{ $payableDetails['tenant_phone'] ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr>

                    {{-- Payment Plan Summary --}}
                    <h5 class="mt-3 mb-3">Payment Plan Summary</h5>
                    <div class="summary-row">
                        <span class="label">Total Amount:</span>
                        <span class="value">₦{{ number_format($paymentPlan->total_amount, 2) }}</span>
                    </div>

                    <div class="summary-row">
                        <span class="label">Total Paid:</span>
                        <span class="value text-success">₦{{ number_format($paymentPlan->amount_paid, 2) }}</span>
                    </div>

                    <div class="summary-row">
                        <span class="label">Total Balance:</span>
                        <span class="value text-danger">₦{{ number_format($paymentPlan->balance, 2) }}</span>
                    </div>

                    @if($paymentPlan->payment_type === 'installment')
                        <div class="summary-row">
                            <span class="label">Total Installments:</span>
                            <span class="value">{{ $paymentPlan->total_installments }}</span>
                        </div>

                        <div class="summary-row">
                            <span class="label">Paid So Far:</span>
                            <span class="value">{{ $paymentPlan->installments_paid }}/{{ $paymentPlan->total_installments }}</span>
                        </div>
                    @endif

                    <hr>

                    {{-- Action Buttons --}}
                    <div class="mt-3">
                        <a href="{{ route('payment.plan.detail', $paymentPlan->id) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-arrow-left"></i> Back to Payment Plan
                        </a>
                        <a href="{{ route('payment.tracking') }}" class="btn btn-secondary btn-block mt-2">
                            <i class="fas fa-chart-line"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row .label {
            font-weight: 600;
            color: #666;
            flex: 0 0 40%;
        }

        .summary-row .value {
            text-align: right;
            flex: 0 0 60%;
        }

        .summary-row.summary-large {
            font-size: 15px;
            padding: 12px 0;
        }
    </style>
@endsection
