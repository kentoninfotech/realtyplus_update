@extends('layouts.print-template')

@section('content')

@php
    // Get business settings
    $settings = App\Models\BusinessSetting::forBusiness(auth()->user()->business_id ?? $business->id);
    $business = $business ?? \App\Models\Business::find(auth()->user()->business_id);
    
    // Helper functions
    $bsGet = function ($k, $d = null) use ($settings) { 
        return array_key_exists($k, $settings) && $settings[$k] !== null && $settings[$k] !== '' ? $settings[$k] : $d; 
    };
    
    $bsImg = function ($k) use ($settings) {
        if (!array_key_exists($k, $settings) || !$settings[$k]) return null;
        return file_exists(public_path($settings[$k])) ? asset($settings[$k]) : null;
    };
    
    // Invoice settings with fallbacks
    $invoicePrefix = $bsGet('invoice_prefix', 'INV');
    $currencySymbol = $bsGet('currency_symbol', '₦');
    $companyLogo = $bsImg('invoice_logo') ?: $bsImg('header_image');
    $headerImage = $bsImg('invoice_header_image');
    $signatureImage = $bsImg('signature_image');
    $primaryColor = $bsGet('primary_color', '#007bff');
    $headerPosition = $bsGet('header_position', 'center');
    $logoPosition = $bsGet('logo_position', $headerPosition);
    $footerPosition = $bsGet('footer_position', 'center');
    
    // Height settings (in px)
    $logoHeight = $bsGet('logo_height', '80');
    $headerHeight = $bsGet('header_height', 'auto');
    $bannerHeight = $bsGet('banner_height', '120');
    $footerHeight = $bsGet('footer_height', 'auto');
    
    $showCompanyInfo = $bsGet('show_company_info', '1') === '1';
    $showTaxId = $bsGet('show_tax_id', '1') === '1';
    $showTerms = $bsGet('show_terms', '1') === '1';
    
    $invoiceTerms = $bsGet('invoice_terms', '');
    $invoiceFooter = $bsGet('invoice_footer', '');
    $paymentTerms = $bsGet('payment_terms', 'Due upon receipt');
    $bankDetails = $bsGet('bank_account_details', '');
    
    // Check if header/banner images exist
    $headerImage = $bsImg('header_image');
    $receiptBanner = $bsImg('receipt_banner');
    $hasHeaderBanner = $headerImage || $receiptBanner;
@endphp

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        background: #f8f9fa;
    }
    
    .invoice-wrapper {
        max-width: 8.5in;
        margin: 0 auto;
        background: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    
    /* Header Section */
    .invoice-header {
        padding: 5px 5px;
        border-bottom: 2px solid {{ $primaryColor }};
        text-align: {{ $headerPosition }};
        background: #f8f9fa;
        min-height: {{ $headerHeight === 'auto' ? 'auto' : $headerHeight . 'px' }};
    }
    
    .invoice-header img[alt="Receipt Banner"],
    .invoice-header img[alt="Header Image"] {
        max-width: 100%;
        height: {{ $bannerHeight }}px;
        object-fit: contain;
    }
    
    .invoice-header .logo {
        max-width: 80px;
        height: {{ $logoHeight }}px;
        object-fit: contain;
        margin-bottom: 5px;
        display: {{ $logoPosition === 'center' ? 'block' : 'inline-block' }};
        margin-left: {{ $logoPosition === 'left' ? '0' : 'auto' }};
        margin-right: {{ $logoPosition === 'right' ? '0' : 'auto' }};
    }
    
    .invoice-header .company-info {
        font-size: 12px;
        color: #666;
        line-height: 1.4;
        margin-top: 5px;
    }
    
    .company-name {
        font-size: 16px;
        font-weight: bold;
        color: {{ $primaryColor }};
        margin: 3px 0;
    }
    
    .company-motto {
        font-size: 12px;
        font-style: italic;
        color: #999;
        margin-bottom: 3px;
    }
    
    /* Invoice Title */
    .invoice-title-section {
        padding: 8px 15px;
        text-align: center;
        border-bottom: 1px solid #e9ecef;
    }
    
    .invoice-title {
        font-size: 18px;
        font-weight: bold;
        color: {{ $primaryColor }};
        margin-bottom: 2px;
    }
    
    .invoice-number {
        font-size: 14px;
        color: #666;
        margin-top: 2px;
    }
    
    /* Main Content */
    .invoice-content {
        padding: 15px;
    }
    
    /* Bill To / From Section */
    .bill-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 12px;
        font-size: 13px;
    }
    
    .bill-block h4 {
        font-size: 13px;
        font-weight: bold;
        color: {{ $primaryColor }};
        text-transform: uppercase;
        margin-bottom: 3px;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 2px;
    }
    
    .bill-block p {
        margin: 2px 0;
        line-height: 1.3;
        color: #555;
    }
    
    /* Transaction Details */
    .transaction-details {
        margin-bottom: 10px;
        font-size: 12px;
    }
    
    .details-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-bottom: 10px;
    }
    
    .detail-item {
        padding: 6px;
        background: #f8f9fa;
        border-left: 2px solid {{ $primaryColor }};
    }
    
    .detail-label {
        font-size: 14px;
        color: #999;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    
    .detail-value {
        font-size: 16px;
        font-weight: bold;
        color: #333;
    }
    
    /* Items Table */
    .items-section {
        margin-bottom: 10px;
    }
    
    .items-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        margin-bottom: 10px;
    }
    
    .items-table thead {
        background-color: {{ $primaryColor }};
        color: white;
    }
    
    .items-table th {
        padding: 8px 5px;
        text-align: left;
        font-weight: bold;
        border: 1px solid {{ $primaryColor }};
        font-size: 12px;
    }
    
    .items-table td {
        padding: 8px 5px;
        border-bottom: 1px solid #e9ecef;
        font-size: 12px;
    }
    
    .items-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .text-right {
        text-align: right;
    }
    
    .text-center {
        text-align: center;
    }
    
    /* Amount Summary */
    .amount-summary {
        width: 100%;
        margin-bottom: 10px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
        font-size: 12px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .summary-row.total {
        border-top: 1px solid {{ $primaryColor }};
        border-bottom: 2px solid {{ $primaryColor }};
        padding: 6px 0;
        font-weight: bold;
        font-size: 13px;
        color: {{ $primaryColor }};
        margin: 6px 0;
    }
    
    .summary-label {
        text-align: left;
    }
    
    .summary-value {
        text-align: right;
        min-width: 100px;
    }
    
    /* Notes and Terms */
    .notes-section {
        margin-bottom: 10px;
        padding: 8px;
        background: #f8f9fa;
        border-left: 2px solid {{ $primaryColor }};
        font-size: 11px;
    }
    
    .notes-section h5 {
        font-size: 10px;
        font-weight: bold;
        color: {{ $primaryColor }};
        margin-bottom: 3px;
        text-transform: uppercase;
    }
    
    .notes-section p {
        margin: 2px 0;
        line-height: 1.3;
        color: #666;
    }
    
    /* Payment Information */
    .payment-info {
        margin-bottom: 10px;
        padding: 8px;
        border: 1px dashed {{ $primaryColor }};
        border-radius: 3px;
        font-size: 12px;
    }
    
    .payment-info h5 {
        color: {{ $primaryColor }};
        margin-bottom: 3px;
        font-weight: bold;
        font-size: 10px;
    }
    
    .payment-info p {
        margin: 1px 0;
        color: #555;
        font-size: 10px;
    }
    
    /* Signature Section */
    .signature-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 8px;
        font-size: 11px;
        text-align: center;
    }
    
    .signature-block {
        border-top: 1px solid #333;
        padding-top: 3px;
    }
    
    .signature-image {
        max-height: 40px;
        margin-bottom: 2px;
    }
    
    /* Footer */
    .invoice-footer {
        padding: 10px 15px;
        text-align: {{ $footerPosition }};
        border-top: 1px solid {{ $primaryColor }};
        background: #f8f9fa;
        font-size: 9px;
        color: #999;
        min-height: {{ $footerHeight === 'auto' ? 'auto' : $footerHeight . 'px' }};
    }
    
    .footer-divider {
        border-bottom: 1px solid #e9ecef;
        margin: 10px 0;
    }
    
    /* Print Styles */
    @media print {
        body {
            background: none;
            margin: 0;
            padding: 0;
        }
        
        .invoice-wrapper {
            max-width: 100%;
            box-shadow: none;
            margin: 0;
            page-break-inside: avoid;
        }
        
        .invoice-actions {
            display: none;
        }
    }
    
    /* Action Buttons (screen only) */
    .invoice-actions {
        padding: 15px;
        text-align: center;
        background: #e9ecef;
        border-bottom: 1px solid #dee2e6;
    }
    
    .btn {
        padding: 8px 15px;
        margin: 0 5px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-print {
        background: {{ $primaryColor }};
        color: white;
    }
    
    .btn-back {
        background: #6c757d;
        color: white;
    }
</style>

{{-- Action Buttons (visible only on screen) --}}
<div class="invoice-actions">
    <button onclick="window.print()" class="btn btn-print">🖨️ Print / Save as PDF</button>
    <a href="{{ route('show.transaction', $transaction->id) }}" class="btn btn-back">← Back to Transaction</a>
</div>

<div class="invoice-wrapper">
    {{-- Header --}}
    <div class="invoice-header">
        @if ($receiptBanner)
            {{-- Show receipt banner if available, hide company info --}}
            <img src="{{ $receiptBanner }}" alt="Receipt Banner" style="width: 100%; height: auto; display: block;">
        @elseif ($headerImage)
            {{-- Show header image if available, hide company info --}}
            <img src="{{ $headerImage }}" alt="Header Image" style="width: 100%; height: auto; display: block;">
        @else
            {{-- Show company information only if no banner/header image --}}
            @if ($companyLogo)
                <img src="{{ $companyLogo }}" alt="Company Logo" class="logo">
            @endif
            
            <div class="company-name">{{ $bsGet('company_name') ?: $business->business_name }}</div>
            
            @if ($motto = $bsGet('company_motto') ?: $business->motto)
                <div class="company-motto">{{ $motto }}</div>
            @endif
            
            @if ($showCompanyInfo)
                <div class="company-info">
                    @if ($addr = $bsGet('company_address') ?: $business->address)
                        <div>{{ $addr }}</div>
                    @endif
                    @if ($phone = $bsGet('company_phone'))
                        <div>📞 {{ $phone }}</div>
                    @endif
                    @if ($email = $bsGet('company_email'))
                        <div>✉️ {{ $email }}</div>
                    @endif
                    @if ($website = $bsGet('company_website'))
                        <div>🌐 {{ $website }}</div>
                    @endif
                    @if ($showTaxId && ($taxId = $bsGet('tax_id')))
                        <div>{{ $bsGet('tax_name', 'Tax') }} ID: {{ $taxId }}</div>
                    @endif
                </div>
            @endif
        @endif
    </div>

    {{-- Invoice Title --}}
    <div class="invoice-title-section">
        <div class="invoice-title">INVOICE</div>
        <!-- <div class="invoice-number">
            <strong>#{{ $invoicePrefix }}-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</strong>
            | {{ $transaction->transaction_date->format('M d, Y') }}
        </div> -->
    </div>

    {{-- Main Content --}}
    <div class="invoice-content">
        {{-- Bill From / To --}}
        <div class="bill-section">
            <div class="bill-block">
                <h4>From</h4>
                @if (!$hasHeaderBanner)
                    {{-- Show company details only if no header/banner --}}
                    <p><strong>{{ $bsGet('company_name') ?: $business->business_name }}</strong></p>
                    @if ($addr = $bsGet('company_address') ?: $business->address)
                        <p>{{ $addr }}</p>
                    @endif
                    @if ($phone = $bsGet('company_phone'))
                        <p>📞 {{ $phone }}</p>
                    @endif
                    @if ($email = $bsGet('company_email'))
                        <p>{{ $email }}</p>
                    @endif
                @else
                    {{-- Header/banner already contains company info --}}
                    <p><em>See header above for company details</em></p>
                @endif
            </div>

            <div class="bill-block">
                <h4>Bill To</h4>
                @if ($transaction->payer)
                    <p><strong>{{ $transaction->payer->full_name ?? ($transaction->payer->name ?? 'Client') }}</strong></p>
                    @if (($transaction->payer->email ?? false) || ($transaction->payer->phone_number ?? false))
                        <p style="font-size: 16px; margin-top: 1px;">
                            @if ($transaction->payer->email ?? false)
                                {{ $transaction->payer->email }}
                            @endif
                            @if (($transaction->payer->email ?? false) && ($transaction->payer->phone_number ?? false))
                                | 
                            @endif
                            @if ($transaction->payer->phone_number ?? false)
                                {{ $transaction->payer->phone_number }}
                            @endif
                        </p>
                    @endif
                @else
                    <p>Client Information</p>
                @endif
            </div>
        </div>

        {{-- Transaction Details --}}
        <div class="transaction-details">
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Invoice Number</div>
                    <div class="detail-value">{{ $invoicePrefix }}-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Invoice Date</div>
                    <div class="detail-value">{{ $transaction->created_at->format('M d, Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Due Date</div>
                    <div class="detail-value">{{ $transaction->transaction_date->format('M d, Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Reference</div>
                    <div class="detail-value">{{ $transaction->reference_number }}</div>
                </div>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="items-section">
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 45%;">Description</th>
                        <th style="width: 15%;" class="text-right">Quantity</th>
                        <th style="width: 20%;" class="text-right">Unit Price</th>
                        <th style="width: 20%;" class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>{{ ucwords(str_replace('_', ' ', $transaction->purpose)) }}</strong><br>
                            <small style="color: #999;">{{ $transaction->description }}</small>
                            @if ($transaction->transactionable)
                                <div style="margin-top: 6px; padding-top: 6px; border-top: 1px solid #e9ecef; color: #666;">
                                    <strong style="color: #333;">Property/Unit:</strong>
                                    @if ($transaction->transactionable_type === 'App\Models\Lease')
                                        {{ $transaction->transactionable->property->name ?? 'N/A' }}
                                        @if ($transaction->transactionable->propertyUnit)
                                            (Unit: {{ $transaction->transactionable->propertyUnit->unit_number }})
                                        @endif
                                    @elseif ($transaction->transactionable_type === 'App\Models\Property')
                                        {{ $transaction->transactionable->name ?? 'N/A' }}
                                    @elseif ($transaction->transactionable_type === 'App\Models\PropertyUnit')
                                        {{ $transaction->transactionable->property->name ?? 'N/A' }}
                                        (Unit: {{ $transaction->transactionable->unit_number }})
                                    @elseif ($transaction->transactionable_type === 'App\Models\UnitSale')
                                        {{ $transaction->transactionable->propertyUnit->property->name ?? 'N/A' }}
                                        (Unit: {{ $transaction->transactionable->propertyUnit->unit_number }})
                                    @else
                                        {{ class_basename($transaction->transactionable_type) }}
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="text-right">1</td>
                        <td class="text-right">{{ $currencySymbol }}{{ number_format($transaction->amount, 2) }}</td>
                        <td class="text-right"><strong>{{ $currencySymbol }}{{ number_format($transaction->amount, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Amount Summary --}}
        <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
            <div style="width: 300px;">
                <div class="summary-row">
                    <span class="summary-label">Subtotal:</span>
                    <span class="summary-value">{{ $currencySymbol }}{{ number_format($transaction->amount, 2) }}</span>
                </div>
                <div class="summary-row total">
                    <span class="summary-label">Total Due:</span>
                    <span class="summary-value">{{ $currencySymbol }}{{ number_format($transaction->amount, 2) }}</span>
                </div>
                @if ($transaction->is_partial_payment && $transaction->balance_due)
                    <div class="summary-row" style="color: #d9534f; font-weight: bold;">
                        <span class="summary-label">Balance Due:</span>
                        <span class="summary-value">{{ $currencySymbol }}{{ number_format($transaction->balance_due, 2) }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Payment Information --}}
        @if ($transaction->payment_method)
            <div class="payment-info">
                <h5>Payment Information</h5>
                <p><strong>Payment Method:</strong> {{ $transaction->payment_method }}</p>
                <p><strong>Payment Status:</strong> 
                    @if ($transaction->is_partial_payment && $transaction->balance_due > 0)
                        <span style="color: #d9534f; font-weight: bold;">Partial Payment</span>
                    @else
                        <span style="color: #5cb85c; font-weight: bold;">Full Payment</span>
                    @endif
                </p>
                @if ($paymentTerms)
                    <p><strong>Terms:</strong> {{ $paymentTerms }}</p>
                @endif
            </div>
        @endif

        {{-- Notes and Terms --}}
        @if ($invoiceTerms || $invoiceFooter)
            <div class="notes-section">
                @if ($invoiceTerms)
                    <h5>Terms & Conditions</h5>
                    <p>{!! nl2br($invoiceTerms) !!}</p>
                @endif
                @if ($invoiceFooter)
                    <div class="footer-divider"></div>
                    <p>{!! nl2br($invoiceFooter) !!}</p>
                @endif
            </div>
        @endif

        {{-- Signature Section --}}
        <div class="signature-section">
            <div class="signature-block">
                @if ($signatureImage)
                    <img src="{{ $signatureImage }}" alt="Authorized Signature" class="signature-image">
                @else
                    <div style="height: 60px;"></div>
                @endif
                <div><strong>Authorized Signature</strong></div>
            </div>
            <div class="signature-block">
                <div style="height: 60px;"></div>
                <div><strong>Received By</strong></div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="invoice-footer">
        <div style="border-top: 1px solid #e9ecef; padding-top: 8px; margin-top: 8px;">
            <div style="text-align: center; font-size: 10px; color: #666; margin-bottom: 5px;">
                Thank you for your business!
            </div>
            
            @if ($bankDetails)
                <div style="text-align: center; font-size: 9px; color: #999; margin-bottom: 8px; white-space: pre-line;">
                    {!! nl2br(e($bankDetails)) !!}
                </div>
            @endif

            <div style="text-align: center; font-size: 8px; color: #bbb; margin-top: 5px;">
                @if ($bsGet('company_email') || $bsGet('company_phone'))
                    <div>
                        @if ($bsGet('company_email'))
                            {{ $bsGet('company_email') }}
                        @endif
                        @if ($bsGet('company_email') && $bsGet('company_phone'))
                            | 
                        @endif
                        @if ($bsGet('company_phone'))
                            {{ $bsGet('company_phone') }}
                        @endif
                    </div>
                @endif
                <div style="margin-top: 3px;">Generated on {{ now()->format('M d, Y') }}</div>
            </div>
        </div>
    </div>
</div>

@endsection
