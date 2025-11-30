<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Receipt #{{ $transaction->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            height: 100%;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            background-color: #f5f5f5;
            line-height: 1.4;
        }
        
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 25px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        /* Header Section */
        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        
        .company-logo {
            max-width: 80px;
            height: auto;
            margin-bottom: 8px;
        }
        
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        
        .company-info {
            font-size: 11px;
            color: #666;
            line-height: 1.5;
            margin-bottom: 3px;
        }
        
        .company-info div {
            margin: 2px 0;
        }
        
        .receipt-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-top: 10px;
            margin-bottom: 3px;
        }
        
        .receipt-id {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }
        
        /* Transaction Details */
        .details-section {
            margin-bottom: 15px;
        }
        
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: white;
            background-color: #007bff;
            padding: 6px 10px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 8px;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        
        .detail-label {
            font-size: 9px;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.2px;
            margin-bottom: 2px;
        }
        
        .detail-value {
            font-size: 12px;
            color: #333;
            font-weight: 500;
        }
        
        .detail-value.amount {
            font-size: 16px;
            font-weight: bold;
            color: #28a745;
        }
        
        .detail-value.debit {
            font-size: 16px;
            font-weight: bold;
            color: #dc3545;
        }
        
        /* Payer Information */
        .payer-info {
            background-color: #f9f9f9;
            padding: 10px;
            border-left: 3px solid #17a2b8;
            margin-bottom: 10px;
        }
        
        .payer-info .detail-label {
            color: #666;
        }
        
        .payer-info .detail-value {
            font-size: 12px;
            font-weight: 600;
        }
        
        /* Lease Details */
        .lease-section {
            background-color: #f0f8ff;
            padding: 10px;
            border-left: 3px solid #28a745;
            margin-bottom: 10px;
        }
        
        .lease-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
        }
        
        /* Amount Summary Box */
        .amount-box {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            margin: 12px 0;
        }
        
        .amount-box .label {
            font-size: 10px;
            text-transform: uppercase;
            opacity: 0.9;
            margin-bottom: 5px;
            letter-spacing: 0.3px;
        }
        
        .amount-box .amount {
            font-size: 28px;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 6px;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .status-failed {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Footer */
        .receipt-footer {
            border-top: 1px solid #e0e0e0;
            padding-top: 10px;
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
        
        .footer-note {
            margin-bottom: 5px;
            font-style: italic;
        }
        
        .footer-timestamp {
            font-size: 8px;
            color: #bbb;
        }
        
        /* Transaction Type Specific Styles */
        /* Lease Transaction */
        .receipt-container.lease-type .receipt-header {
            border-bottom: 3px solid #28a745;
        }
        
        .receipt-container.lease-type .company-name {
            color: #28a745;
        }
        
        .receipt-container.lease-type .receipt-title {
            color: #28a745;
        }
        
        .receipt-container.lease-type .amount-box {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }
        
        .receipt-container.lease-type .section-title {
            background-color: #28a745;
        }
        
        /* Property Transaction */
        .receipt-container.property-type .receipt-header {
            border-bottom: 3px solid #0d6efd;
        }
        
        .receipt-container.property-type .company-name {
            color: #0d6efd;
        }
        
        .receipt-container.property-type .receipt-title {
            color: #0d6efd;
        }
        
        .receipt-container.property-type .amount-box {
            background: linear-gradient(135deg, #0d6efd 0%, #0a47a8 100%);
        }
        
        .receipt-container.property-type .section-title {
            background-color: #0d6efd;
        }
        
        .receipt-container.property-type .property-badge {
            display: inline-block;
            background: #cfe2ff;
            color: #084298;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        /* Maintenance Request Transaction */
        .receipt-container.maintenance-type .receipt-header {
            border-bottom: 3px solid #ffc107;
        }
        
        .receipt-container.maintenance-type .company-name {
            color: #ffc107;
        }
        
        .receipt-container.maintenance-type .receipt-title {
            color: #ff9800;
        }
        
        .receipt-container.maintenance-type .amount-box {
            background: linear-gradient(135deg, #ff9800 0%, #e68900 100%);
        }
        
        .receipt-container.maintenance-type .section-title {
            background-color: #ff9800;
        }
        
        .receipt-container.maintenance-type .maintenance-badge {
            display: inline-block;
            background: #fff3cd;
            color: #856404;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        /* Separator */
        .separator {
            height: 1px;
            background-color: #e0e0e0;
            margin: 8px 0;
        }
        
        .separator-small {
            height: 1px;
            background-color: #e0e0e0;
            margin: 6px 0;
        }
        
        /* Action Buttons */
        .receipt-actions {
            text-align: right;
            margin-bottom: 15px;
        }
        
        .print-btn {
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            margin-left: 8px;
            text-decoration: none;
            display: inline-block;
        }
        
        .print-btn:hover {
            background-color: #0056b3;
        }
        
        /* Print Styles */
        @media print {
            body {
                background-color: white;
                margin: 0;
                padding: 0;
            }
            .receipt-container {
                box-shadow: none;
                margin: 0;
                padding: 15px;
                max-width: 100%;
            }
            .receipt-actions {
                display: none;
            }
        }
        
        /* Responsive */
        @media (max-width: 600px) {
            .receipt-container {
                padding: 15px;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
                gap: 8px;
            }
            
            .lease-grid {
                grid-template-columns: 1fr;
            }
            
            .company-name {
                font-size: 18px;
            }
            
            .amount-box .amount {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    @php
        $transactionType = 'default';
        if ($transaction->transactionable_type === 'App\\Models\\Lease') {
            $transactionType = 'lease-type';
        } elseif ($transaction->transactionable_type === 'App\\Models\\Property') {
            $transactionType = 'property-type';
        } elseif ($transaction->transactionable_type === 'App\\Models\\MaintenanceRequest') {
            $transactionType = 'maintenance-type';
        }
    @endphp
    <div class="receipt-container {{ $transactionType }}">
        {{-- Action Buttons (visible only on screen, not in print) --}}
        <div class="receipt-actions">
            <button onclick="window.print()" class="print-btn">
                🖨️ Print / Save as PDF
            </button>
            <a href="{{ route('show.transaction', $transaction->id) }}" class="print-btn" style="background-color: #6c757d;">
                ← Back
            </a>
        </div>

        {{-- Header --}}
        <div class="receipt-header">
            @if($business && $business->logo && file_exists(public_path('images/' . $business->logo)))
                <img src="{{ public_path('images/' . $business->logo) }}" alt="Company Logo" class="company-logo">
            @else
                <div style="width: 100px; height: 100px; background-color: #f0f0f0; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: 2px solid #ddd;">
                    <span style="color: #999; font-size: 12px;">Logo</span>
                </div>
            @endif
            
            <div class="company-name">{{ $business->business_name ?? 'RealtyPlus' }}</div>
            
            <div class="company-info">
                @if($business && $business->address)
                    <div>{{ $business->address }}</div>
                @endif
                @if($business && $business->user)
                    @if($business->user->phone_number)
                        <div>📞 {{ $business->user->phone_number }}</div>
                    @endif
                    @if($business->user->email)
                        <div>✉️ {{ $business->user->email }}</div>
                    @endif
                @endif
                @if($business && $business->motto)
                    <div style="margin-top: 8px; font-style: italic; font-size: 11px;">{{ $business->motto }}</div>
                @endif
            </div>
            
            <div class="receipt-title">TRANSACTION RECEIPT</div>
            
            {{-- Transaction Type Badge --}}
            @if($transaction->transactionable_type === 'App\\Models\\Lease')
                <div style="display: inline-block; background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 3px; font-size: 9px; font-weight: bold; margin-right: 5px;">
                    🏠 LEASE
                </div>
            @elseif($transaction->transactionable_type === 'App\\Models\\Property')
                <div class="property-badge">
                    🏢 PROPERTY
                </div>
            @elseif($transaction->transactionable_type === 'App\\Models\\MaintenanceRequest')
                <div class="maintenance-badge">
                    🔧 MAINTENANCE
                </div>
            @endif
            
            <div class="receipt-id">Receipt #{{ $transaction->id }}</div>
        </div>
        
        {{-- Amount Box --}}
        <div class="amount-box">
            <div class="label">Transaction Amount</div>
            <div class="amount">
                {{ $transaction->type === 'credit' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
            </div>
            <div class="status-badge status-{{ $transaction->status }}">
                {{ ucfirst($transaction->status) }}
            </div>
        </div>
        
        {{-- Payer Information --}}
        <div class="details-section">
            <div class="section-title">Payer Information</div>
            @if($transaction->payer)
                <div class="payer-info">
                    <div class="detail-item">
                        <div class="detail-label">Payer Type</div>
                        <div class="detail-value">{{ class_basename($transaction->payer_type) }}</div>
                    </div>
                    <div class="separator-small"></div>
                    <div class="detail-item">
                        <div class="detail-label">Payer Name</div>
                        <div class="detail-value">
                            {{ $transaction->payer->name ?? ($transaction->payer->first_name . ' ' . $transaction->payer->last_name ?? 'N/A') }}
                        </div>
                    </div>
                    @if($transaction->payer->email)
                        <div class="separator-small"></div>
                        <div class="detail-item">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $transaction->payer->email }}</div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
        
        {{-- Transaction Details --}}
        <div class="details-section">
            <div class="section-title">Transaction Details</div>
            
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Transaction Type</div>
                    <div class="detail-value" style="text-transform: uppercase; color: {{ $transaction->type === 'credit' ? '#28a745' : '#dc3545' }}; font-weight: bold;">
                        {{ $transaction->type }}
                    </div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Purpose</div>
                    <div class="detail-value">{{ ucwords(str_replace('_', ' ', $transaction->purpose)) }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Transaction Date</div>
                    <div class="detail-value">{{ optional($transaction->transaction_date)->format('M d, Y') }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Payment Method</div>
                    <div class="detail-value">{{ $transaction->payment_method }}</div>
                </div>
                
                @if($transaction->reference_number)
                    <div class="detail-item">
                        <div class="detail-label">Reference Number</div>
                        <div class="detail-value">{{ $transaction->reference_number }}</div>
                    </div>
                @endif
            </div>
            
            @if($transaction->description)
                <div class="separator"></div>
                <div class="detail-item">
                    <div class="detail-label">Description</div>
                    <div class="detail-value" style="line-height: 1.6;">{{ $transaction->description }}</div>
                </div>
            @endif
        </div>
        
        {{-- Lease Details (if applicable) --}}
        @if($transaction->transactionable_type === 'App\\Models\\Lease' && $transaction->transactionable)
            <div class="details-section">
                <div class="section-title">Lease Details</div>
                
                <div class="lease-section">
                    <div class="lease-grid">
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
                            <div class="detail-value" style="text-transform: capitalize; color: {{ $transaction->transactionable->status === 'active' ? '#28a745' : '#ffc107' }}; font-weight: bold;">
                                {{ $transaction->transactionable->status }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="separator-small" style="margin: 12px 0;"></div>
                    
                    <div class="lease-grid">
                        <div class="detail-item">
                            <div class="detail-label">Start Date</div>
                            <div class="detail-value">{{ optional($transaction->transactionable->start_date)->format('M d, Y') }}</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">End Date</div>
                            <div class="detail-value">{{ optional($transaction->transactionable->end_date)->format('M d, Y') }}</div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-label">Payment Frequency</div>
                            <div class="detail-value" style="text-transform: capitalize;">
                                {{ $transaction->transactionable->payment_frequency }}
                            </div>
                        </div>
                    </div>
                    
                    @if($transaction->transactionable->property)
                        <div class="separator-small" style="margin: 12px 0;"></div>
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
            <div class="footer-note">
                This is a computer-generated receipt. No signature is required.
            </div>
            <div class="footer-timestamp">
                Generated on {{ now()->format('M d, Y \a\t h:i A') }}
            </div>
        </div>
    </div>

    <script>
        // Auto-print on page load if requested via URL parameter
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === '1') {
                // Delay print to ensure page is fully rendered
                setTimeout(function() {
                    window.print();
                }, 500);
            }
        });
    </script>
</body>
</html>
