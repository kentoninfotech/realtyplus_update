@extends('layouts.print-template')
@section('content')

@php
    use App\Models\BusinessSetting;
    use App\Models\Business;
    
    // Get business settings
    $business = $business ?? Business::find(auth()->user()->business_id);
    $settings = BusinessSetting::forBusiness($business->id ?? auth()->user()->business_id);
    
    // Helper functions
    $bsGet = function ($k, $d = null) use ($settings) { 
        return array_key_exists($k, $settings) && $settings[$k] !== null && $settings[$k] !== '' ? $settings[$k] : $d; 
    };
    
    $bsImg = function ($k) use ($settings) {
        if (!array_key_exists($k, $settings) || !$settings[$k]) return null;
        return file_exists(public_path($settings[$k])) ? asset($settings[$k]) : null;
    };
    
    // Transaction type for styling
    $transactionType = 'default';
    if ($transaction->transactionable_type === 'App\\Models\\Lease') {
        $transactionType = 'lease';
    } elseif ($transaction->transactionable_type === 'App\\Models\\UnitSale') {
        $transactionType = 'sale';
    } elseif ($transaction->transactionable_type === 'App\\Models\\Property') {
        $transactionType = 'property';
    } elseif ($transaction->transactionable_type === 'App\\Models\\MaintenanceRequest') {
        $transactionType = 'maintenance';
    }
    
    // Logo and styling settings
    $companyLogo = $bsImg('company_logo');
    $primaryColor = $bsGet('primary_color', '#007bff');
    $headerPosition = $bsGet('header_position', 'center');
    $logoPosition = $bsGet('logo_position', $headerPosition); // Falls back to headerPosition
    $footerPosition = $bsGet('footer_position', 'center');
    
    // Height settings (in px)
    $logoHeight = $bsGet('logo_height', '80');
    $headerHeight = $bsGet('header_height', 'auto');
    $bannerHeight = $bsGet('banner_height', '120');
    $footerHeight = $bsGet('footer_height', 'auto');
    
    $currencySymbol = '₦'; // Nigeria Naira
    $receiptPrefix = $bsGet('receipt_prefix', 'RCP');
    
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
    
    .receipt-wrapper {
        max-width: 8.5in;
        margin: 0 auto;
        background: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    
    /* Header Section */
    .receipt-header {
        padding: 15px 15px;
        border-bottom: 2px solid {{ $primaryColor }};
        text-align: {{ $headerPosition }};
        background: #f8f9fa;
        min-height: {{ $headerHeight === 'auto' ? 'auto' : $headerHeight . 'px' }};
    }
    
    .receipt-header img[alt="Receipt Banner"],
    .receipt-header img[alt="Header Image"] {
        max-width: 100%;
        height: {{ $bannerHeight }}px;
        object-fit: contain;
    }
    
    .receipt-header .logo {
        max-width: 80px;
        height: {{ $logoHeight }}px;
        object-fit: contain;
        margin-bottom: 5px;
        display: {{ $logoPosition === 'center' ? 'block' : 'inline-block' }};
        margin-left: {{ $logoPosition === 'left' ? '0' : 'auto' }};
        margin-right: {{ $logoPosition === 'right' ? '0' : 'auto' }};
    }
    
    .receipt-header .company-info {
        font-size: 10px;
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
        font-size: 10px;
        font-style: italic;
        color: #999;
        margin-bottom: 3px;
    }
    
    /* Receipt Title */
    .receipt-title-section {
        padding: 8px 15px;
        text-align: center;
        border-bottom: 1px solid #e9ecef;
    }
    
    .receipt-title {
        font-size: 18px;
        font-weight: bold;
        color: {{ $primaryColor }};
        margin-bottom: 2px;
    }
    
    .receipt-number {
        font-size: 11px;
        color: #666;
        margin-top: 2px;
    }
    
    /* Type Badge */
    .type-badge {
        display: inline-block;
        padding: 3px 8px;
        background: {{ $primaryColor }};
        color: white;
        border-radius: 3px;
        font-size: 9px;
        font-weight: bold;
        margin-top: 4px;
    }
    
    .type-badge.lease {
        background: #28a745;
    }
    
    .type-badge.sale {
        background: #17a2b8;
    }
    
    .type-badge.property {
        background: #0d6efd;
    }
    
    .type-badge.maintenance {
        background: #ff9800;
    }
    
    /* Amount Box */
    .amount-box {
        padding: 10px 15px;
        background: #f0f7ff;
        border-left: 2px solid {{ $primaryColor }};
        margin-bottom: 10px;
        text-align: center;
    }
    
    .amount-box.lease {
        background: #f0fff4;
        border-left-color: #28a745;
    }
    
    .amount-box.sale {
        background: #f0f9ff;
        border-left-color: #17a2b8;
    }
    
    .amount-box .label {
        font-size: 10px;
        color: #666;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    
    .amount-box .amount {
        font-size: 20px;
        font-weight: bold;
        color: {{ $primaryColor }};
        margin-bottom: 4px;
    }
    
    .amount-box.lease .amount {
        color: #28a745;
    }
    
    .amount-box.sale .amount {
        color: #17a2b8;
    }
    
    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
    }
    
    .status-badge.completed,
    .status-badge.success {
        background: #d4edda;
        color: #155724;
    }
    
    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-badge.failed {
        background: #f8d7da;
        color: #721c24;
    }
    
    /* Main Content */
    .receipt-content {
        padding: 15px;
    }
    
    /* Details Section */
    .details-section {
        margin-bottom: 12px;
    }
    
    .section-title {
        font-size: 11px;
        font-weight: bold;
        color: {{ $primaryColor }};
        text-transform: uppercase;
        margin-bottom: 6px;
        padding-bottom: 3px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        font-size: 11px;
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
        font-weight: 500;
        color: #333;
    }
    
    .separator {
        border-bottom: 1px solid #e9ecef;
        margin: 6px 0;
    }
    
    /* Footer */
    .receipt-footer {
        padding: 8px 15px;
        text-align: {{ $footerPosition }};
        border-top: 1px solid {{ $primaryColor }};
        background: #f8f9fa;
        font-size: 9px;
        color: #999;
        min-height: {{ $footerHeight === 'auto' ? 'auto' : $footerHeight . 'px' }};
    }
    
    .footer-note {
        margin: 3px 0;
    }
    
    .footer-timestamp {
        margin-top: 3px;
        font-size: 8px;
        color: #ccc;
    }
    
    /* Action Buttons */
    .receipt-actions {
        padding: 15px;
        text-align: center;
        background: #e9ecef;
        border-bottom: 1px solid #dee2e6;
    }
    
    .action-btn {
        padding: 8px 15px;
        margin: 0 5px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        text-decoration: none;
        display: inline-block;
        background: {{ $primaryColor }};
        color: white;
    }
    
    .action-btn:hover {
        opacity: 0.9;
    }
    
    .action-btn.btn-back {
        background: #6c757d;
    }
    
    /* Print Styles */
    @media print {
        body {
            background: none;
            margin: 0;
            padding: 0;
        }
        
        .receipt-wrapper {
            max-width: 100%;
            box-shadow: none;
            margin: 0;
            page-break-inside: avoid;
        }
        
        .receipt-actions {
            display: none;
        }
    }
</style>

{{-- Action Buttons (visible only on screen) --}}
<div class="receipt-actions">
    <button onclick="window.print()" class="action-btn">🖨️ Print / Save as PDF</button>
    <a href="{{ route('show.transaction', $transaction->id) }}" class="action-btn btn-back">← Back to Transaction</a>
</div>

<div class="receipt-wrapper">
    {{-- Header --}}
    <div class="receipt-header">
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
                @if ($bsGet('show_tax_id') && ($taxId = $bsGet('tax_id')))
                    <div>{{ $bsGet('tax_name', 'Tax') }} ID: {{ $taxId }}</div>
                @endif
            </div>
        @endif
    </div>

    {{-- Receipt Title --}}
    <div class="receipt-title-section">
        <div class="receipt-title">RECEIPT</div>
        <div class="receipt-number">
            <strong>#{{ $receiptPrefix }}-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</strong>
            | {{ $transaction->transaction_date->format('M d, Y') }}
        </div>
        
        {{-- Transaction Type Badge --}}
        @if($transaction->transactionable_type === 'App\\Models\\Lease')
            <div class="type-badge lease">🏠 LEASE</div>
        @elseif($transaction->transactionable_type === 'App\\Models\\UnitSale')
            <div class="type-badge sale">💰 SALE</div>
        @elseif($transaction->transactionable_type === 'App\\Models\\Property')
            <div class="type-badge property">🏢 PROPERTY</div>
        @elseif($transaction->transactionable_type === 'App\\Models\\MaintenanceRequest')
            <div class="type-badge maintenance">🔧 MAINTENANCE</div>
        @endif
    </div>

    {{-- Main Content --}}
    <div class="receipt-content">
        {{-- Amount Box --}}
        <div class="amount-box {{ $transactionType }}">
            <div class="label">Transaction Amount</div>
            <div class="amount">{{ $currencySymbol }}{{ number_format($transaction->amount, 2) }}</div>
            <div class="status-badge status-{{ $transaction->status }}">{{ ucfirst($transaction->status) }}</div>
        </div>

        {{-- Payer Information --}}
        <div class="details-section">
            <div class="section-title">Payer Information</div>
            @if($transaction->payer)
                <div style="font-size: 16px; padding: 6px; background: #f8f9fa; border-left: 2px solid {{ $primaryColor }};">
                    <strong>{{ $transaction->payer->full_name ?? ($transaction->payer->name ?? 'N/A') }}</strong>
                    @if($transaction->payer->email || ($transaction->payer->phone_number ?? false))
                        <br>
                        <span style="font-size: 12px; color: #666;">
                            @if($transaction->payer->email)
                                {{ $transaction->payer->email }}
                            @endif
                            @if($transaction->payer->email && ($transaction->payer->phone_number ?? false))
                                | 
                            @endif
                            @if($transaction->payer->phone_number ?? false)
                                {{ $transaction->payer->phone_number }}
                            @endif
                        </span>
                    @endif
                </div>
            @else
                <div style="font-size: 11px; padding: 6px; background: #f8f9fa; border-left: 2px solid {{ $primaryColor }};">Unknown</div>
            @endif
        </div>

        {{-- Transaction Details --}}
        <div class="details-section">
            <div class="section-title">Transaction Details</div>

            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Transaction Type</div>
                    <div class="detail-value">{{ ucfirst($transaction->type === 'credit' ? 'Income' : 'Expense') }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Purpose</div>
                    <div class="detail-value">{{ ucwords(str_replace('_', ' ', $transaction->purpose)) }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Transaction Date</div>
                    <div class="detail-value">{{ $transaction->transaction_date->format('M d, Y') }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Payment Method</div>
                    <div class="detail-value">{{ $transaction->payment_method }}</div>
                </div>

                @if($transaction->reference_number)
                    <div class="detail-item">
                        <div class="detail-label">Reference</div>
                        <div class="detail-value">{{ $transaction->reference_number }}</div>
                    </div>
                @endif
            </div>

            @if($transaction->description)
                <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-left: 3px solid {{ $primaryColor }};">
                    <div style="font-size: 10px; color: #999; text-transform: uppercase; margin-bottom: 5px;">Notes</div>
                    <div style="font-size: 13px; line-height: 1.6;">{{ $transaction->description }}</div>
                </div>
            @endif
        </div>

        {{-- Lease Details (if applicable) --}}
        @if($transaction->transactionable_type === 'App\\Models\\Lease' && $transaction->transactionable)
            <div class="details-section">
                <div class="section-title">Lease Information</div>

                <div class="details-grid">
                    <div class="detail-item">
                        <div class="detail-label">Lease ID</div>
                        <div class="detail-value">#{{ $transaction->transactionable->id }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Tenant</div>
                        <div class="detail-value">
                            @if($transaction->transactionable->tenant)
                                {{ $transaction->transactionable->tenant->first_name . ' ' . $transaction->transactionable->tenant->last_name }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value" style="text-transform: capitalize; font-weight: bold;">
                            {{ $transaction->transactionable->status }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Payment Frequency</div>
                        <div class="detail-value" style="text-transform: capitalize;">
                            {{ $transaction->transactionable->payment_frequency }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Start Date</div>
                        <div class="detail-value">{{ optional($transaction->transactionable->start_date)->format('M d, Y') }}</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">End Date</div>
                        <div class="detail-value">{{ optional($transaction->transactionable->end_date)->format('M d, Y') }}</div>
                    </div>

                    @if($transaction->transactionable->property)
                        <div class="detail-item">
                            <div class="detail-label">Property</div>
                            <div class="detail-value">{{ $transaction->transactionable->property->name }}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif


        {{-- Footer --}}
        <div class="receipt-footer">
            <div style="border-top: 1px solid #e9ecef; padding-top: 15px; margin-top: 20px;">
                <div style="text-align: center; font-size: 12px; color: #666; margin-bottom: 10px;">
                    Thank you for your business!
                </div>
                
                @if ($bankDetails = $bsGet('bank_account_details'))
                    <div style="text-align: center; font-size: 11px; color: #999; margin-bottom: 10px; white-space: pre-line;">
                        {!! nl2br(e($bankDetails)) !!}
                    </div>
                @endif

                <div style="text-align: center; font-size: 10px; color: #bbb; margin-top: 15px;">
                    <div>{{ $business->business_name }}</div>
                    @if ($email = $bsGet('company_email'))
                        <div>{{ $email }}</div>
                    @endif
                    <div style="margin-top: 5px;">Generated on {{ now()->format('M d, Y') }} at {{ now()->format('h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    // Auto-print on page load if requested via URL parameter
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === '1') {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    });
</script>

