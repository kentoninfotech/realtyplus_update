@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Complete Unit Sale</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('properties') }}">Properties</a></li>
                        <li class="breadcrumb-item active">Complete Sale</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Sale Summary --}}
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Sale Summary</h3>
                </div>
                <div class="card-body">
                    <div class="sale-summary">
                        <div class="summary-row">
                            <span class="label">Property:</span>
                            <span class="value"><strong>{{ $sale->property->name }}</strong></span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Unit Number:</span>
                            <span class="value">{{ $sale->propertyUnit->unit_number }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Unit Type:</span>
                            <span class="value">{{ $sale->propertyUnit->unit_type ?? 'N/A' }}</span>
                        </div>
                        <hr>
                        <div class="summary-row">
                            <span class="label">Buyer Name:</span>
                            <span class="value"><strong>{{ $buyerName }}</strong></span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Buyer Email:</span>
                            <span class="value">{{ $buyerEmail }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Buyer Type:</span>
                            <span class="value">
                                <span class="badge badge-info">
                                    {{ class_basename($sale->buyer_type) }}
                                </span>
                            </span>
                        </div>
                        <hr>
                        <div class="summary-row text-lg font-weight-bold">
                            <span class="label">Sale Price:</span>
                            <span class="value text-success">₦{{ number_format($sale->sale_price, 2) }}</span>
                        </div>
                        @if($sale->notes)
                            <div class="summary-row">
                                <span class="label">Notes:</span>
                                <span class="value">{{ $sale->notes }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Details Form --}}
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Payment Details</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('unit.sale.complete', $sale->id) }}" method="post" id="paymentForm">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                                <option value="">-- Select Payment Method --</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method }}" {{ old('payment_method') === $method ? 'selected' : '' }}>
                                        {{ $method }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_method')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="transaction_date">Transaction Date <span class="text-danger">*</span></label>
                            <input type="date" name="transaction_date" id="transaction_date" 
                                class="form-control @error('transaction_date') is-invalid @enderror" 
                                value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                            @error('transaction_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="reference_number">Reference Number (e.g., Bank Transfer ID, Cheque No)</label>
                            <input type="text" name="reference_number" id="reference_number" 
                                class="form-control @error('reference_number') is-invalid @enderror" 
                                placeholder="e.g., TRF-20240623-001234" value="{{ old('reference_number') }}">
                            <small class="form-text text-muted">Optional: Will auto-generate if left blank</small>
                            @error('reference_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mt-4 pt-3 border-top">
                            <label for="payment_advice">Payment Advice Document (Optional)</label>
                            <p class="small text-muted mb-3">
                                <i class="fas fa-info-circle"></i> Upload a payment slip, receipt, or proof of payment (PDF, JPG, PNG)
                            </p>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('payment_advice') is-invalid @enderror" 
                                    id="payment_advice" name="payment_advice" accept=".pdf,.jpg,.jpeg,.png">
                                <label class="custom-file-label" for="payment_advice">Choose file...</label>
                                @error('payment_advice')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <small class="form-text text-muted d-block mt-2">Max 5MB (PDF, JPG, PNG)</small>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> Completing this sale will generate a receipt/invoice for the buyer and update the unit status to "sold".
                        </div>

                        <button type="submit" class="btn btn-success btn-block btn-lg">
                            <i class="fas fa-check-circle"></i> Complete Sale & Generate Receipt
                        </button>
                        <a href="{{ route('show.property', $sale->property->id) }}" class="btn btn-secondary btn-block mt-2">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .sale-summary {
            font-size: 14px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
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

        .summary-row.text-lg {
            font-size: 18px;
            padding: 12px 0;
        }
    </style>

    <script>
        // Handle file input label update
        document.getElementById('payment_advice')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Choose file...';
            const label = document.querySelector('label[for="payment_advice"]');
            if (label) {
                label.textContent = fileName;
            }
        });
    </script>
@endsection
