@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-file-invoice"></i> Payment Plan Details
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('payment.tracking') }}">Payment Tracking</a></li>
                        <li class="breadcrumb-item active">Payment Plan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Alert --}}
    <div class="row mb-3">
        <div class="col-md-12">
            @php
                $statusColors = [
                    'completed' => 'success',
                    'partial' => 'info',
                    'pending' => 'warning',
                    'overdue' => 'danger',
                    'defaulted' => 'dark',
                ];
                $color = $statusColors[$paymentPlan->status] ?? 'secondary';
            @endphp
            <div class="alert alert-{{ $color }} alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle"></i>
                <strong>Payment Status:</strong> {{ ucfirst($paymentPlan->status) }} 
                | Progress: <strong>{{ $paymentPlan->getProgressPercentage() }}%</strong>
                | Balance: <strong>₦{{ number_format($paymentPlan->balance, 2) }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Payment Plan Summary --}}
        <div class="col-md-5">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Payment Plan Summary</h3>
                </div>
                <div class="card-body">
                    <div class="summary-row">
                        <span class="label">Transaction Type:</span>
                        <span class="value"><span class="badge badge-primary">{{ $payableDetails['type'] ?? 'N/A' }}</span></span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="label">Property:</span>
                        <span class="value"><strong>{{ $payableDetails['property_name'] ?? 'N/A' }}</strong></span>
                    </div>

                    <div class="summary-row">
                        <span class="label">Unit:</span>
                        <span class="value">{{ $payableDetails['unit_number'] ?? 'N/A' }}</span>
                    </div>

                    <hr>

                    @if(isset($payableDetails['buyer_name']))
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
                    @endif

                    @if(isset($payableDetails['tenant_name']))
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
                    @endif

                    <hr>

                    <div class="summary-row summary-large">
                        <span class="label">Total Amount:</span>
                        <span class="value text-success"><strong>₦{{ number_format($paymentPlan->total_amount, 2) }}</strong></span>
                    </div>

                    <div class="summary-row">
                        <span class="label">Amount Paid:</span>
                        <span class="value text-info">₦{{ number_format($paymentPlan->amount_paid, 2) }}</span>
                    </div>

                    <div class="summary-row">
                        <span class="label">Balance Due:</span>
                        <span class="value text-{{ $paymentPlan->balance > 0 ? 'danger' : 'success' }}">₦{{ number_format($paymentPlan->balance, 2) }}</span>
                    </div>

                    <hr>

                    <div class="summary-row">
                        <span class="label">Payment Type:</span>
                        <span class="value"><span class="badge badge-{{ $paymentPlan->payment_type === 'full' ? 'success' : 'warning' }}">{{ ucfirst($paymentPlan->payment_type) }}</span></span>
                    </div>

                    @if($paymentPlan->payment_type === 'installment')
                        <div class="summary-row">
                            <span class="label">Total Installments:</span>
                            <span class="value">{{ $paymentPlan->total_installments }}</span>
                        </div>

                        <div class="summary-row">
                            <span class="label">Installments Paid:</span>
                            <span class="value text-success">{{ $paymentPlan->installments_paid }}</span>
                        </div>
                    @endif

                    <hr>

                    <div class="summary-row">
                        <span class="label">Start Date:</span>
                        <span class="value">{{ $paymentPlan->start_date ? $paymentPlan->start_date->format('M d, Y') : 'N/A' }}</span>
                    </div>

                    <div class="summary-row">
                        <span class="label">End Date:</span>
                        <span class="value">{{ $paymentPlan->end_date ? $paymentPlan->end_date->format('M d, Y') : 'N/A' }}</span>
                    </div>

                    <div class="summary-row">
                        <span class="label">Last Payment:</span>
                        <span class="value">{{ $paymentPlan->last_payment_date ? $paymentPlan->last_payment_date->format('M d, Y') : 'No payment yet' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Installments List --}}
        <div class="col-md-7">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">Payment Schedule</h3>
                </div>
                <div class="card-body">
                    @if($paymentPlan->payment_type === 'full')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>Full Payment Completed</strong> - This was a single full payment transaction.
                        </div>
                    @else
                        {{-- Progress Bar --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Overall Payment Progress</span>
                                <span><strong>{{ $paymentPlan->getProgressPercentage() }}%</strong></span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: {{ $paymentPlan->getProgressPercentage() }}%"></div>
                            </div>
                        </div>

                        {{-- Installments Table --}}
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Installment</th>
                                        <th>Due Date</th>
                                        <th>Amount Due</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paymentPlan->installments()->orderBy('installment_number')->get() as $installment)
                                        @php
                                            $daysOverdue = $installment->status === 'overdue' ? now()->diffInDays($installment->due_date) : 0;
                                        @endphp
                                        <tr class="{{ $installment->status === 'overdue' ? 'table-danger' : '' }}">
                                            <td><strong>#{{ $installment->installment_number }}</strong></td>
                                            <td>{{ $installment->due_date->format('M d, Y') }}</td>
                                            <td>₦{{ number_format($installment->amount_due, 2) }}</td>
                                            <td class="text-success">₦{{ number_format($installment->amount_paid, 2) }}</td>
                                            <td class="{{ $installment->getRemainingBalance() > 0 ? 'text-danger' : 'text-success' }}">
                                                ₦{{ number_format($installment->getRemainingBalance(), 2) }}
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $installment->getStatusBadgeColor() }}">
                                                    {{ ucfirst($installment->status) }}
                                                    @if($installment->status === 'overdue')
                                                        ({{ $daysOverdue }}d)
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                @if($installment->status !== 'paid')
                                                    <button class="btn btn-sm btn-primary" data-toggle="modal" 
                                                        data-target="#paymentModal{{ $installment->id }}" title="Record Payment">
                                                        <i class="fas fa-credit-card"></i>
                                                    </button>
                                                @else
                                                    <span class="badge badge-success">Paid</span>
                                                @endif
                                            </td>
                                        </tr>

                                        {{-- Payment Modal --}}
                                        @if($installment->status !== 'paid')
                                            <div class="modal fade" id="paymentModal{{ $installment->id }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Record Payment - Installment #{{ $installment->installment_number }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('payment.installment.record', $installment->id) }}" method="post">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label>Amount Due</label>
                                                                    <input type="text" class="form-control" value="₦{{ number_format($installment->amount_due, 2) }}" readonly>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Amount Paid</label>
                                                                    <input type="text" class="form-control" value="₦{{ number_format($installment->amount_paid, 2) }}" readonly>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Remaining Balance</label>
                                                                    <input type="text" class="form-control text-danger" 
                                                                        value="₦{{ number_format($installment->getRemainingBalance(), 2) }}" readonly>
                                                                </div>

                                                                <hr>

                                                                <div class="form-group">
                                                                    <label for="amount_paid{{ $installment->id }}">Amount to Pay <span class="text-danger">*</span></label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">₦</span>
                                                                        </div>
                                                                        <input type="number" name="amount_paid" id="amount_paid{{ $installment->id }}" 
                                                                            class="form-control" step="0.01" min="0.01" 
                                                                            value="{{ $installment->getRemainingBalance() }}" required>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="payment_date{{ $installment->id }}">Payment Date <span class="text-danger">*</span></label>
                                                                    <input type="date" name="payment_date" id="payment_date{{ $installment->id }}" 
                                                                        class="form-control" value="{{ date('Y-m-d') }}" required>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="payment_method{{ $installment->id }}">Payment Method <span class="text-danger">*</span></label>
                                                                    <select name="payment_method" id="payment_method{{ $installment->id }}" 
                                                                        class="form-control" required>
                                                                        <option value="">-- Select Method --</option>
                                                                        <option>Bank Transfer</option>
                                                                        <option>Cash</option>
                                                                        <option>Credit Card</option>
                                                                        <option>Cheque</option>
                                                                        <option>Mobile Money</option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="reference{{ $installment->id }}">Reference Number (Optional)</label>
                                                                    <input type="text" name="reference_number" id="reference{{ $installment->id }}" 
                                                                        class="form-control" placeholder="e.g., Bank confirmation #">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Record Payment</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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

        .table-danger {
            background-color: #ffe6e6 !important;
        }
    </style>
@endsection
