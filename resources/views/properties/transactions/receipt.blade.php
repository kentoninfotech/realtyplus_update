@extends('layouts.print-template')
@section('content')

@php
    $transactionType = 'default';
    if ($transaction->transactionable_type === 'App\\Models\\Lease') {
        $transactionType = 'lease-type';
    } elseif ($transaction->transactionable_type === 'App\\Models\\Property') {
        $transactionType = 'property-type';
    } elseif ($transaction->transactionable_type === 'App\\Models\\MaintenanceRequest') {
        $transactionType = 'maintenance-type';
    }

    $bs = isset($businessSettings) && is_array($businessSettings) ? $businessSettings : [];
    $bsGet = function ($k, $d = null) use ($bs) { return array_key_exists($k, $bs) && $bs[$k] !== null && $bs[$k] !== '' ? $bs[$k] : $d; };
    $bsImg = function ($k) use ($bs) {
        if (!array_key_exists($k, $bs) || !$bs[$k]) return null;
        return file_exists(public_path($bs[$k])) ? asset($bs[$k]) : null;
    };
    $currencySymbol = $bsGet('currency_symbol', '$');
    $receiptPrefix  = $bsGet('receipt_prefix', '');
@endphp

<div class="receipt-wrapper">
    {{-- Banner: prefer business receipt_banner, fallback to header_image, then legacy default --}}
    <div class="receipt-banner">
        @php $bannerUrl = $bsImg('receipt_banner') ?: $bsImg('header_image'); @endphp
        @if ($bannerUrl)
            <img src="{{ $bannerUrl }}" alt="Company Banner">
        @elseif(file_exists(public_path('dist/img/banner.png')))
            <img src="{{ asset('dist/img/banner.png') }}" alt="Company Banner">
        @endif
    </div>

    {{-- Action Buttons (visible only on screen, not in print) --}}
    <div class="receipt-actions">
        <button onclick="window.print()" class="action-btn">
            🖨️ Print / Save as PDF
        </button>
        <a href="{{ route('show.transaction', $transaction->id) }}" class="action-btn btn-back">
            ← Back
        </a>
    </div>

    <div class="receipt-container">
        {{-- Header --}}
        <div class="receipt-header {{ $transactionType }}">
            @php $logoUrl = $bsImg('invoice_logo'); @endphp
            @if ($logoUrl)
                <img src="{{ $logoUrl }}" alt="Company Logo" class="company-logo">
            @elseif($business && $business->logo && file_exists(public_path('images/' . $business->logo)))
                <img src="{{ asset('images/' . $business->logo) }}" alt="Company Logo" class="company-logo">
            @else
                <div style="width: 100px; height: 100px; background-color: #f0f0f0; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: 2px solid #ddd;">
                    <span style="color: #999; font-size: 12px;">Logo</span>
                </div>
            @endif

            <div class="company-name">{{ $business->business_name ?? 'RealtyPlus' }}</div>

            @if ($tagLine = $bsGet('tag_line'))
                <div style="font-size:11px; color:#666; margin-top:2px;">{{ $tagLine }}</div>
            @endif

            <div class="company-info">
                @php
                    $addr = $bsGet('address_line2') ? trim(($business->address ?? '') . ', ' . $bsGet('address_line2'), ', ') : ($business->address ?? null);
                    $cityState = trim(($bsGet('city', '') ? $bsGet('city') . ', ' : '') . ($bsGet('state', '') ?: ''), ', ');
                @endphp
                @if ($addr)<div>{{ $addr }}</div>@endif
                @if ($cityState)<div>{{ $cityState }}{{ $bsGet('postal_code') ? ' ' . $bsGet('postal_code') : '' }}</div>@endif
                @if ($bsGet('country'))<div>{{ $bsGet('country') }}</div>@endif
                @if ($phone = $bsGet('contact_phone'))
                    <div>📞 {{ $phone }}@if($alt = $bsGet('contact_phone_alt')), {{ $alt }}@endif</div>
                @elseif($business && $business->user && $business->user->phone_number)
                    <div>📞 {{ $business->user->phone_number }}</div>
                @endif
                @if ($email = $bsGet('contact_email'))
                    <div>✉️ {{ $email }}</div>
                @elseif($business && $business->user && $business->user->email)
                    <div>✉️ {{ $business->user->email }}</div>
                @endif
                @if ($web = $bsGet('website'))<div>🌐 {{ $web }}</div>@endif
                @if ($taxId = $bsGet('tax_id_number'))<div style="font-size:11px;">{{ $bsGet('tax_name', 'Tax') }} ID: {{ $taxId }}</div>@endif
                @if($business && $business->motto)
                    <div style="margin-top: 8px; font-style: italic; font-size: 11px;">{{ $business->motto }}</div>
                @endif
            </div>

            <div class="receipt-title">TRANSACTION RECEIPT</div>

            {{-- Transaction Type Badge --}}
            @if($transaction->transactionable_type === 'App\\Models\\Lease')
                <div class="type-badge">
                    🏠 LEASE
                </div>
            @elseif($transaction->transactionable_type === 'App\\Models\\Property')
                <div class="type-badge property-badge">
                    🏢 PROPERTY
                </div>
            @elseif($transaction->transactionable_type === 'App\\Models\\MaintenanceRequest')
                <div class="type-badge maintenance-badge">
                    🔧 MAINTENANCE
                </div>
            @endif

            <div class="receipt-id">Receipt #{{ $receiptPrefix }}{{ $transaction->id }}</div>
        </div>

        {{-- Amount Box --}}
        <div class="amount-box {{ $transactionType }}">
            <div class="label">Transaction Amount</div>
            <div class="amount">
                {{ $transaction->type === 'credit' ? '+' : '-' }}{{ $currencySymbol }}{{ number_format($transaction->amount, 2) }}
            </div>
            <div class="status-badge status-{{ $transaction->status }}">
                {{ ucfirst($transaction->status) }}
            </div>
        </div>

        {{-- Payer Information --}}
        <div class="details-section {{ $transactionType }}">
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
        <div class="details-section {{ $transactionType }}">
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
            <div class="details-section {{ $transactionType }}">
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
            @if ($notes = $bsGet('receipt_notes'))
                <div style="margin-bottom:8px;">{{ $notes }}</div>
            @endif
            @if ($bank = $bsGet('bank_details'))
                <div style="margin-bottom:8px; font-size:11px; white-space:pre-line;">{!! e($bank) !!}</div>
            @endif
            @if ($footerImg = $bsImg('footer_image'))
                <div style="margin:10px 0;"><img src="{{ $footerImg }}" alt="footer" style="max-height:60px;"></div>
            @endif
            <div class="footer-note">
                This is a computer-generated receipt. No signature is required.
            </div>
            <div class="footer-timestamp">
                Generated on {{ now()->format('M d, Y \a\t h:i A') }}
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
            // Delay print to ensure page is fully rendered
            setTimeout(function() {
                window.print();
            }, 500);
        }
    });
</script>

