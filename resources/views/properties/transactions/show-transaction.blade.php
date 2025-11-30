@extends('layouts.template')

@section('content')
<div class="container-fluid mt-4">
    {{-- Back Button & Actions --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('property.transaction') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Transactions
        </a>
        <div>
            <a href="{{ route('receipt.pdf', $transaction->id) }}?print=1" target="_blank" class="btn btn-outline-info btn-sm mr-2">
                <i class="fas fa-print"></i> Print
            </a>
            <a href="{{ route('receipt.pdf', $transaction->id) }}" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
        </div>
    </div>

    {{-- Main Header Card --}}
    <div class="card border-left-primary mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="mb-0 text-primary">Transaction #{{ $transaction->id }}</h2>
                    <p class="text-muted mb-0 mt-1">
                        {{ optional($transaction->transaction_date)->format('M d, Y') }}
                    </p>
                </div>
                <div class="col-auto">
                    <div class="text-right">
                        <h4 class="text-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                            {{ $transaction->type === 'credit' ? '+' : '-' }}
                            ${{ number_format($transaction->amount, 2) }}
                        </h4>
                        <span class="badge badge-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Column: Transaction Details --}}
        <div class="col-lg-8">
            {{-- Payer Information --}}
            <div class="card mb-4 border-left-info">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user"></i> Payer Information
                    </h5>
                </div>
                <div class="card-body">
                    @if ($transaction->payer)
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted small">Payer Type</p>
                                <h6 class="font-weight-bold">{{ class_basename($transaction->payer_type) }}</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small">Payer Name</p>
                                <h6 class="font-weight-bold">
                                    {{ $transaction->payer->name ?? $transaction->payer->first_name . ' ' . $transaction->payer->last_name ?? 'N/A' }}
                                </h6>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">Payer information not available</p>
                    @endif
                </div>
            </div>

            {{-- Transaction Details --}}
            <div class="card mb-4 border-left-warning">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-exchange-alt"></i> Transaction Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small">Transaction Type</p>
                            <h6 class="font-weight-bold text-uppercase">
                                <span class="badge badge-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                                    {{ $transaction->type }}
                                </span>
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small">Purpose</p>
                            <h6 class="font-weight-bold">{{ ucwords(str_replace('_', ' ', $transaction->purpose)) }}</h6>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted small">Payment Method</p>
                            <h6 class="font-weight-bold">{{ $transaction->payment_method }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small">Reference Number</p>
                            <h6 class="font-weight-bold">{{ $transaction->reference_number ?? 'N/A' }}</h6>
                        </div>
                    </div>

                    @if ($transaction->description)
                        <div class="row">
                            <div class="col-12">
                                <p class="text-muted small">Description</p>
                                <p class="mb-0">{{ $transaction->description }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Lease Details (if applicable) --}}
            @if ($transaction->transactionable_type === 'App\\Models\\Lease' && $transaction->transactionable)
                <div class="card mb-4 border-left-success">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-file-contract"></i> Lease Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted small">Lease ID</p>
                                <h6 class="font-weight-bold">#{{ $transaction->transactionable->id }}</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small">Tenant</p>
                                <h6 class="font-weight-bold">
                                    {{ $transaction->transactionable->tenant->name ?? $transaction->transactionable->tenant->first_name . ' ' . $transaction->transactionable->tenant->last_name ?? 'N/A' }}
                                </h6>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p class="text-muted small">Start Date</p>
                                <h6 class="font-weight-bold">{{ optional($transaction->transactionable->start_date)->format('M d, Y') }}</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small">End Date</p>
                                <h6 class="font-weight-bold">{{ optional($transaction->transactionable->end_date)->format('M d, Y') }}</h6>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted small">Payment Frequency</p>
                                <h6 class="font-weight-bold text-capitalize">
                                    {{ ucfirst($transaction->transactionable->payment_frequency) }}
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small">Lease Status</p>
                                <h6 class="font-weight-bold">
                                    <span class="badge badge-{{ $transaction->transactionable->status === 'active' ? 'success' : ($transaction->transactionable->status === 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($transaction->transactionable->status) }}
                                    </span>
                                </h6>
                            </div>
                        </div>

                        @if ($transaction->transactionable->property)
                            <div class="row mt-3 pt-3 border-top">
                                <div class="col-12">
                                    <p class="text-muted small">Property</p>
                                    <h6 class="font-weight-bold">{{ $transaction->transactionable->property->name ?? 'N/A' }}</h6>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Documents Section --}}
            <div class="card border-left-secondary">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-paperclip"></i> Attached Documents
                    </h5>
                </div>
                <div class="card-body">
                    @forelse ($transaction->documents as $doc)
                        <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                            <i class="fas fa-file text-primary mr-3"></i>
                            <div class="flex-grow-1">
                                <a href="{{ asset('public/'. $doc->file_path) }}" target="_blank" class="text-decoration-none">
                                    <strong>{{ $doc->title ?? basename($doc->file_path) }}</strong>
                                </a>
                                <p class="text-muted small mb-0">
                                    {{ optional($doc->created_at)->format('M d, Y') }}
                                </p>
                            </div>
                            <a href="{{ asset('public/'. $doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4 mb-0">
                            <i class="fas fa-inbox fa-2x text-secondary mb-3 d-block"></i>
                            No documents attached to this transaction.
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column: Summary Sidebar --}}
        <div class="col-lg-4">
            {{-- Quick Summary --}}
            <div class="card mb-4 border-left-danger">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Transaction Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-item mb-3">
                        <p class="text-muted small mb-1">Amount</p>
                        <h4 class="text-{{ $transaction->type === 'credit' ? 'success' : 'danger' }} mb-0">
                            {{ $transaction->type === 'credit' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                        </h4>
                    </div>

                    <hr>

                    <div class="info-item mb-3">
                        <p class="text-muted small mb-1">Status</p>
                        <span class="badge badge-lg badge-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }} px-3 py-2">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>

                    <hr>

                    <div class="info-item">
                        <p class="text-muted small mb-1">Transaction Date</p>
                        <p class="mb-0">{{ optional($transaction->transaction_date)->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Related Information --}}
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-link"></i> Related Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="related-item mb-3">
                        <p class="text-muted small mb-1">Related To</p>
                        <h6 class="font-weight-bold">
                            {{ class_basename($transaction->transactionable_type) }}
                            <span class="text-muted">#{{ $transaction->transactionable_id }}</span>
                        </h6>
                    </div>

                    <hr>

                    <div class="related-item">
                        <p class="text-muted small mb-1">Payer Type</p>
                        <h6 class="font-weight-bold">{{ class_basename($transaction->payer_type) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-left-primary {
        border-left: 4px solid #007bff !important;
    }
    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }
    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }
    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }
    .border-left-danger {
        border-left: 4px solid #dc3545 !important;
    }
    .border-left-secondary {
        border-left: 4px solid #6c757d !important;
    }
    .badge-lg {
        font-size: 0.95rem !important;
    }
    .info-item {
        padding: 8px 0;
    }
</style>
@endsection
