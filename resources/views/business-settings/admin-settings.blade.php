@extends('layouts.template')

@section('content')

@php
    $val = function ($key, $default = '') use ($settings) {
        return old($key, isset($settings[$key]) ? $settings[$key] : $default);
    };
    $imgUrl = function ($key) use ($settings) {
        if (!isset($settings[$key]) || !$settings[$key]) return null;
        $path = $settings[$key];
        return file_exists(public_path($path)) ? asset($path) : null;
    };
@endphp

<style>
    .settings-page {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 30px 0;
    }
    
    .settings-header {
        background: white;
        padding: 30px;
        margin-bottom: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .settings-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .settings-header p {
        color: #666;
        margin: 0;
    }
    
    .tab-nav {
        background: white;
        border-radius: 8px;
        padding: 0;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border: none;
    }
    
    .tab-nav .nav-link {
        color: #666;
        border: none;
        padding: 15px 20px;
        font-weight: 500;
        border-bottom: 3px solid transparent;
        border-radius: 0;
        transition: all 0.3s ease;
    }
    
    .tab-nav .nav-link:hover {
        color: #007bff;
        background: #f8f9fa;
    }
    
    .tab-nav .nav-link.active {
        color: #007bff;
        border-bottom-color: #007bff;
        background: transparent;
    }
    
    .settings-section {
        background: white;
        border-radius: 8px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .settings-section h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .form-group label {
        font-weight: 500;
        color: #555;
        margin-bottom: 8px;
    }
    
    .form-control, .custom-select {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px 12px;
    }
    
    .form-control:focus, .custom-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .image-preview {
        max-height: 120px;
        border: 1px solid #ddd;
        padding: 8px;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    
    .color-input-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .color-input-wrapper input[type="color"] {
        width: 60px;
        height: 40px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .form-row-custom {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .success-alert {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    
    .error-alert {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        padding: 12px 30px;
        font-weight: 500;
        border-radius: 4px;
        color: white;
        transition: all 0.3s ease;
    }
    
    .btn-save:hover {
        background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
    }
    
    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #007bff;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
        font-size: 14px;
        color: #004085;
    }
</style>

<div class="settings-page">
    <div class="container-fluid">
        {{-- Header --}}
        <div class="settings-header">
            <h1><i class="fas fa-sliders-h"></i> Business Settings & Configuration</h1>
            <p>Manage your company branding, contact information, invoicing preferences, and system settings</p>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="success-alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Errors --}}
        @if ($errors->any())
            <div class="error-alert">
                <h5>Please Fix These Errors:</h5>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('business-settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Tabs Navigation --}}
            <ul class="nav nav-tabs tab-nav" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#tab-branding"><i class="fas fa-palette"></i> Branding</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-company"><i class="fas fa-building"></i> Company Info</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-contact"><i class="fas fa-phone"></i> Contact &amp; Address</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-invoice"><i class="fas fa-receipt"></i> Invoices &amp; Receipts</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-tax"><i class="fas fa-percent"></i> Tax &amp; Fees</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-social"><i class="fas fa-share-alt"></i> Social Media</a></li>
            </ul>

            <div class="tab-content">

                {{-- ============ BRANDING TAB ============ --}}
                <div class="tab-pane fade show active" id="tab-branding" role="tabpanel">
                    <div class="settings-section">
                        <h3><i class="fas fa-image"></i> Visual Identity</h3>
                        
                        <div class="info-box">
                            <i class="fas fa-lightbulb"></i> Your logo and colors will appear on invoices, receipts, and throughout the app to maintain consistent branding.
                        </div>

                        <div class="form-row-custom">
                            <div class="form-group">
                                <label>Company Logo <span style="color: red;">*</span></label>
                                <small class="text-muted">Max 4MB. PNG, JPG, SVG, or WEBP</small>
                                @if ($imgUrl('company_logo'))
                                    <div class="image-preview"><img src="{{ $imgUrl('company_logo') }}" alt="logo"></div>
                                @endif
                                <input type="file" name="company_logo" class="form-control-file" accept="image/*">
                            </div>

                            <div class="form-group">
                                <label>Invoice Header Image (optional)</label>
                                <small class="text-muted">Max 4MB. Displayed at top of invoices</small>
                                @if ($imgUrl('invoice_header_image'))
                                    <div class="image-preview"><img src="{{ $imgUrl('invoice_header_image') }}" alt="header"></div>
                                @endif
                                <input type="file" name="invoice_header_image" class="form-control-file" accept="image/*">
                            </div>
                        </div>

                        <h3 style="margin-top: 30px;"><i class="fas fa-paint-brush"></i> Colors</h3>

                        <div class="form-row-custom">
                            <div class="form-group">
                                <label>Primary Color</label>
                                <div class="color-input-wrapper">
                                    <input type="color" name="primary_color" value="{{ old('primary_color', $business->primary_color ?: '#007bff') }}">
                                    <input type="text" class="form-control" value="{{ old('primary_color', $business->primary_color ?: '#007bff') }}" style="width: 120px;">
                                </div>
                                <small class="text-muted">Used for buttons, links, and highlights</small>
                            </div>

                            <div class="form-group">
                                <label>Secondary Color</label>
                                <div class="color-input-wrapper">
                                    <input type="color" name="secondary_color" value="{{ old('secondary_color', $business->secondary_color ?: '#6c757d') }}">
                                    <input type="text" class="form-control" value="{{ old('secondary_color', $business->secondary_color ?: '#6c757d') }}" style="width: 120px;">
                                </div>
                                <small class="text-muted">Used for accents and secondary elements</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ COMPANY INFO TAB ============ --}}
                <div class="tab-pane fade" id="tab-company" role="tabpanel">
                    <div class="settings-section">
                        <h3><i class="fas fa-info-circle"></i> Company Information</h3>

                        <div class="form-row-custom">
                            <div class="form-group">
                                <label>Business Name <span style="color: red;">*</span></label>
                                <input type="text" name="company_name" maxlength="100" class="form-control" value="{{ $val('company_name', $business->business_name) }}" required>
                            </div>

                            <div class="form-group">
                                <label>Company Motto / Tagline</label>
                                <input type="text" name="company_motto" maxlength="150" class="form-control" value="{{ $val('company_motto') }}" placeholder="e.g., Find Your Perfect Home">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Company Address</label>
                            <input type="text" name="company_address" maxlength="200" class="form-control" value="{{ $val('company_address') }}" placeholder="Street address">
                        </div>

                        <div class="form-row-custom">
                            <div class="form-group">
                                <label>Website</label>
                                <input type="url" name="company_website" class="form-control" value="{{ $val('company_website') }}" placeholder="https://example.com">
                            </div>

                            <div class="form-group">
                                <label>Tax ID / Registration Number (optional)</label>
                                <input type="text" name="tax_id" maxlength="50" class="form-control" value="{{ $val('tax_id') }}" placeholder="e.g., RC123456">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ CONTACT TAB ============ --}}
                <div class="tab-pane fade" id="tab-contact" role="tabpanel">
                    <div class="settings-section">
                        <h3><i class="fas fa-envelope"></i> Contact Information</h3>

                        <div class="form-row-custom">
                            <div class="form-group">
                                <label>Primary Email <span style="color: red;">*</span></label>
                                <input type="email" name="company_email" class="form-control" value="{{ $val('company_email') }}" required>
                            </div>

                            <div class="form-group">
                                <label>Primary Phone</label>
                                <input type="tel" name="company_phone" class="form-control" value="{{ $val('company_phone') }}" placeholder="+234 XXX XXX XXXX">
                            </div>
                        </div>

                        <h3><i class="fas fa-map-marker-alt"></i> Address Details</h3>

                        <div class="form-row-custom">
                            <div class="form-group">
                                <label>Address Line 2</label>
                                <input type="text" name="address_line2" class="form-control" value="{{ $val('address_line2') }}">
                            </div>

                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" class="form-control" value="{{ $val('city') }}" placeholder="e.g., Lagos">
                            </div>

                            <div class="form-group">
                                <label>State / Province</label>
                                <input type="text" name="state" class="form-control" value="{{ $val('state') }}" placeholder="e.g., Lagos State">
                            </div>

                            <div class="form-group">
                                <label>Country</label>
                                <input type="text" name="country" class="form-control" value="{{ $val('country') }}" placeholder="e.g., Nigeria">
                            </div>

                            <div class="form-group">
                                <label>Postal Code</label>
                                <input type="text" name="postal_code" class="form-control" value="{{ $val('postal_code') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ INVOICE TAB ============ --}}
                <div class="tab-pane fade" id="tab-invoice" role="tabpanel">
                    <div class="settings-section">
                        <h3><i class="fas fa-file-invoice"></i> Invoice & Receipt Settings</h3>

                        <div class="info-box">
                            <i class="fas fa-lightbulb"></i> Configure how your invoices and receipts appear to clients and for record-keeping.
                        </div>

                        <div class="form-row-custom">
                            <div class="form-group">
                                <label>Invoice Prefix</label>
                                <input type="text" name="invoice_prefix" maxlength="20" class="form-control" value="{{ $val('invoice_prefix', 'INV') }}" placeholder="e.g., INV">
                                <small class="text-muted">Will appear as INV-0001, INV-0002, etc.</small>
                            </div>

                            <div class="form-group">
                                <label>Receipt Prefix</label>
                                <input type="text" name="receipt_prefix" maxlength="20" class="form-control" value="{{ $val('receipt_prefix', 'RCP') }}" placeholder="e.g., RCP">
                                <small class="text-muted">Will appear as RCP-0001, RCP-0002, etc.</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Bank Account Details for Invoices (optional)</label>
                            <textarea name="bank_details" rows="3" class="form-control" placeholder="Account Name&#10;Account Number&#10;Bank Name&#10;Sort Code">{{ $val('bank_details') }}</textarea>
                            <small class="text-muted">Display payment instructions on invoices. Press Enter for new lines.</small>
                        </div>

                        <div class="form-group">
                            <label>Invoice Notes / Terms (optional)</label>
                            <textarea name="invoice_notes" rows="3" class="form-control" placeholder="Thank you for your business!&#10;Please pay within 30 days...">{{ $val('invoice_notes') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Receipt Notes (optional)</label>
                            <textarea name="receipt_notes" rows="3" class="form-control" placeholder="Thank you for your patronage...">{{ $val('receipt_notes') }}</textarea>
                        </div>

                        <h3 style="margin-top: 30px;"><i class="fas fa-cog"></i> Currency</h3>

                        <div class="form-row-custom">
                            <div class="form-group">
                                <label>Currency Symbol</label>
                                <input type="text" name="currency_symbol" maxlength="5" class="form-control" value="{{ $val('currency_symbol', '₦') }}" placeholder="₦">
                            </div>

                            <div class="form-group">
                                <label>Currency Code</label>
                                <input type="text" name="currency_code" maxlength="3" class="form-control" value="{{ $val('currency_code', 'NGN') }}" placeholder="NGN">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ TAX TAB ============ --}}
                <div class="tab-pane fade" id="tab-tax" role="tabpanel">
                    <div class="settings-section">
                        <h3><i class="fas fa-percent"></i> Tax Configuration</h3>

                        <div class="form-row-custom">
                            <div class="form-group">
                                <label>Tax Name</label>
                                <input type="text" name="tax_name" maxlength="50" class="form-control" value="{{ $val('tax_name', 'VAT') }}" placeholder="e.g., VAT, GST, Sales Tax">
                            </div>

                            <div class="form-group">
                                <label>Tax Rate (%)</label>
                                <input type="number" name="tax_rate" step="0.01" min="0" max="100" class="form-control" value="{{ $val('tax_rate', '0') }}">
                            </div>

                            <div class="form-group">
                                <label>Show Tax ID on Documents</label>
                                <select name="show_tax_id" class="form-control">
                                    <option value="0" {{ $val('show_tax_id') == '0' ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $val('show_tax_id') == '1' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tax ID Number</label>
                            <input type="text" name="tax_id_number" maxlength="50" class="form-control" value="{{ $val('tax_id_number') }}" placeholder="Your tax identification number">
                        </div>
                    </div>
                </div>

                {{-- ============ SOCIAL TAB ============ --}}
                <div class="tab-pane fade" id="tab-social" role="tabpanel">
                    <div class="settings-section">
                        <h3><i class="fas fa-share-alt"></i> Social Media Links</h3>

                        <div class="info-box">
                            <i class="fas fa-lightbulb"></i> Add your social media profiles. These will be displayed in your company footer and documents.
                        </div>

                        <div class="form-row-custom">
                            <div class="form-group">
                                <label><i class="fab fa-facebook"></i> Facebook</label>
                                <input type="url" name="social_facebook" class="form-control" value="{{ $val('social_facebook') }}" placeholder="https://facebook.com/yourpage">
                            </div>

                            <div class="form-group">
                                <label><i class="fab fa-twitter"></i> Twitter</label>
                                <input type="url" name="social_twitter" class="form-control" value="{{ $val('social_twitter') }}" placeholder="https://twitter.com/yourhandle">
                            </div>

                            <div class="form-group">
                                <label><i class="fab fa-instagram"></i> Instagram</label>
                                <input type="url" name="social_instagram" class="form-control" value="{{ $val('social_instagram') }}" placeholder="https://instagram.com/yourprofile">
                            </div>

                            <div class="form-group">
                                <label><i class="fab fa-linkedin"></i> LinkedIn</label>
                                <input type="url" name="social_linkedin" class="form-control" value="{{ $val('social_linkedin') }}" placeholder="https://linkedin.com/company/yourcompany">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Submit Buttons --}}
            <div class="settings-section" style="text-align: right; border-left: 4px solid #28a745;">
                <button type="submit" class="btn btn-save">
                    <i class="fas fa-save"></i> Save All Settings
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
