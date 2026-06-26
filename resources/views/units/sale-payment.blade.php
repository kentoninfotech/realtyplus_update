@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-cash-register"></i> Complete Unit Sale
                    </h1>
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
        {{-- Transaction Type Alert --}}
        <div class="col-md-12 mb-3">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle"></i>
                <strong>Transaction Type:</strong> UNIT SALE (Full Sale) - This is a ONE-TIME property unit sale transaction, not a rental/lease agreement.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>

        {{-- Sale Summary --}}
        <div class="col-md-5">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt"></i> Sale Summary
                    </h3>
                </div>
                <div class="card-body">
                    <div class="sale-summary">
                        {{-- Property & Unit Info --}}
                        <div class="info-section mb-3">
                            <h5 class="section-title" style="color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 5px;">Property & Unit Details</h5>
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
                            <div class="summary-row">
                                <span class="label">Unit Size:</span>
                                <span class="value">{{ $sale->propertyUnit->square_footage ?? 'N/A' }} sqft</span>
                            </div>
                        </div>

                        {{-- Buyer Info --}}
                        <div class="info-section mb-3">
                            <h5 class="section-title" style="color: #28a745; border-bottom: 2px solid #28a745; padding-bottom: 5px;">Buyer Information</h5>
                            <div class="summary-row">
                                <span class="label">Buyer Name:</span>
                                <span class="value"><strong>{{ $buyerName }}</strong></span>
                            </div>
                            <div class="summary-row">
                                <span class="label">Buyer Email:</span>
                                <span class="value" style="font-size: 12px;">{{ $buyerEmail }}</span>
                            </div>
                            <div class="summary-row">
                                <span class="label">Buyer Type:</span>
                                <span class="value">
                                    <span class="badge badge-info">{{ class_basename($sale->buyer_type) }}</span>
                                </span>
                            </div>
                        </div>

                        {{-- Price Summary --}}
                        <div class="info-section mb-3" style="background: #f8f9fa; padding: 12px; border-radius: 4px;">
                            <h5 class="section-title mb-3" style="color: #6c757d; border-bottom: 2px solid #6c757d; padding-bottom: 5px;">FINANCIAL SUMMARY</h5>
                            <div class="summary-row summary-large">
                                <span class="label">Sale Price:</span>
                                <span class="value text-success"><strong>₦{{ number_format($sale->sale_price, 2) }}</strong></span>
                            </div>
                            <div class="summary-row summary-large" id="amountPayingRow" style="display: none;">
                                <span class="label">Amount Paying Now:</span>
                                <span class="value text-info"><strong id="amountPayingValue">₦0.00</strong></span>
                            </div>
                            <div class="summary-row summary-large" id="balanceRow" style="display: none;">
                                <span class="label">Balance Due:</span>
                                <span class="value text-warning"><strong id="balanceValue">₦0.00</strong></span>
                            </div>
                        </div>

                        @if($sale->notes)
                            <div class="alert alert-secondary">
                                <small><strong>Notes:</strong> {{ $sale->notes }}</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Payment Details Form --}}
        <div class="col-md-7">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-credit-card"></i> Payment Details & Options
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('unit.sale.complete', $sale->id) }}" method="post" enctype="multipart/form-data" id="paymentForm">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Errors:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Payment Type Selection --}}
                        <div class="form-group">
                            <label for="payment_type">Payment Type <span class="text-danger">*</span></label>
                            <div class="payment-type-selector">
                                <div class="custom-control custom-radio mb-2">
                                    <input class="custom-control-input" type="radio" id="paymentTypeFull" 
                                        name="payment_type" value="full" checked onchange="updatePaymentDisplay()">
                                    <label class="custom-control-label" for="paymentTypeFull">
                                        <strong>Full Payment</strong> - Complete payment at purchase
                                    </label>
                                </div>
                                <div class="custom-control custom-radio mb-2">
                                    <input class="custom-control-input" type="radio" id="paymentTypeInstallment" 
                                        name="payment_type" value="installment" onchange="updatePaymentDisplay()">
                                    <label class="custom-control-label" for="paymentTypeInstallment">
                                        <strong>Installment Plan</strong> - Spread payment over multiple due dates
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Installment Options (Hidden by default) --}}
                        <div id="installmentOptions" style="display: none; background: #f0f8ff; padding: 15px; border-left: 4px solid #007bff; margin-bottom: 20px;">
                            <div class="form-group">
                                <label for="total_installments">Number of Installments <span class="text-danger">*</span></label>
                                <input type="number" name="total_installments" id="total_installments" 
                                    class="form-control @error('total_installments') is-invalid @enderror" 
                                    min="2" max="12" placeholder="e.g., 3, 6, 12" value="{{ old('total_installments', 3) }}">
                                <small class="form-text text-muted">Choose between 2 to 12 installments</small>
                                @error('total_installments')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="first_payment_amount">First Payment Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₦</span>
                                    </div>
                                    <input type="number" name="first_payment_amount" id="first_payment_amount" 
                                        class="form-control @error('first_payment_amount') is-invalid @enderror" 
                                        step="0.01" min="0" placeholder="0.00" value="{{ old('first_payment_amount', 0) }}">
                                </div>
                                <small class="form-text text-muted">Amount to be paid today. Remaining will be divided into equal installments.</small>
                                @error('first_payment_amount')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="payment_start_date">Payment Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="payment_start_date" id="payment_start_date" 
                                    class="form-control @error('payment_start_date') is-invalid @enderror" 
                                    value="{{ old('payment_start_date', date('Y-m-d')) }}">
                                <small class="form-text text-muted">Date when the first installment payment is due</small>
                                @error('payment_start_date')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="payment_frequency">Payment Frequency <span class="text-danger">*</span></label>
                                <select name="payment_frequency" id="payment_frequency" 
                                    class="form-control @error('payment_frequency') is-invalid @enderror">
                                    <option value="">-- Select Frequency --</option>
                                    <option value="monthly" {{ old('payment_frequency') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="bi-weekly" {{ old('payment_frequency') === 'bi-weekly' ? 'selected' : '' }}>Bi-weekly</option>
                                    <option value="quarterly" {{ old('payment_frequency') === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="bi-annual" {{ old('payment_frequency') === 'bi-annual' ? 'selected' : '' }}>Bi-annual</option>
                                    <option value="annual" {{ old('payment_frequency') === 'annual' ? 'selected' : '' }}>Annual</option>
                                </select>
                                @error('payment_frequency')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Payment Method --}}
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

                        {{-- Transaction Details --}}
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

                        {{-- Payment Proof Document --}}
                        <div class="form-group mt-4 pt-3 border-top">
                            <label for="payment_advice">Payment Proof Document (Optional)</label>
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

                        {{-- Important Notes --}}
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            <strong>Important:</strong>
                            <ul class="mb-0 mt-2" style="font-size: 13px;">
                                <li>This sale will be documented and recorded in the system</li>
                                <li>Unit status will be updated to "sold"</li>
                                <li>Receipt/Invoice will be generated for the buyer</li>
                                @if ($sale->paymentPlan)
                                    <li>A payment plan is already active for this sale</li>
                                @endif
                            </ul>
                        </div>

                        {{-- Action Buttons --}}
                        <button type="submit" class="btn btn-success btn-block btn-lg mt-3">
                            <i class="fas fa-check-circle"></i> 
                            <span id="submitBtnText">Complete Sale & Generate Receipt</span>
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

        .info-section {
            margin-bottom: 15px;
        }

        .section-title {
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

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
            word-break: break-word;
        }

        .summary-row.summary-large {
            font-size: 15px;
            padding: 12px 0;
        }

        .payment-type-selector {
            background: #f9f9f9;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .custom-control-label {
            padding-top: 2px;
            cursor: pointer;
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

        // Update payment display based on payment type
        function updatePaymentDisplay() {
            const paymentType = document.querySelector('input[name="payment_type"]:checked').value;
            const installmentOptions = document.getElementById('installmentOptions');
            const amountPayingRow = document.getElementById('amountPayingRow');
            const balanceRow = document.getElementById('balanceRow');
            const submitBtnText = document.getElementById('submitBtnText');

            if (paymentType === 'installment') {
                installmentOptions.style.display = 'block';
                amountPayingRow.style.display = 'flex';
                balanceRow.style.display = 'flex';
                submitBtnText.textContent = 'Create Payment Plan & Generate Receipt';
                updateFinancialSummary();
            } else {
                installmentOptions.style.display = 'none';
                amountPayingRow.style.display = 'none';
                balanceRow.style.display = 'none';
                submitBtnText.textContent = 'Complete Sale & Generate Receipt';
            }
        }

        // Update financial summary when first payment changes
        function updateFinancialSummary() {
            const salePrice = parseFloat('{{ $sale->sale_price }}') || 0;
            const firstPayment = parseFloat(document.getElementById('first_payment_amount').value) || 0;
            const balance = Math.max(0, salePrice - firstPayment);

            document.getElementById('amountPayingValue').textContent = '₦' + firstPayment.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('balanceValue').textContent = '₦' + balance.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // Listen for changes in first payment amount
        document.getElementById('first_payment_amount')?.addEventListener('change', updateFinancialSummary);
        document.getElementById('first_payment_amount')?.addEventListener('keyup', updateFinancialSummary);

        // Initialize display on page load
        document.addEventListener('DOMContentLoaded', function() {
            updatePaymentDisplay();
        });
    </script>
@endsection
