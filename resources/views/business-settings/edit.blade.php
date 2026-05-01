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

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Business Settings</h1>
            </div>
            <div class="col-sm-6 text-right">
                <small class="text-muted">Configure how your business identity appears across the app, invoices and receipts.</small>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('business-settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card card-primary card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="settings-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#tab-branding" role="tab">Branding</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-contact" role="tab">Contact &amp; Address</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-invoice" role="tab">Invoices &amp; Receipts</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-tax" role="tab">Tax</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-social" role="tab">Social</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">

                    {{-- BRANDING --}}
                    <div class="tab-pane fade show active" id="tab-branding" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Business Name</label>
                                    <input type="text" name="business_name" maxlength="70" class="form-control"
                                           value="{{ old('business_name', $business->business_name) }}">
                                </div>
                                <div class="form-group">
                                    <label>Tag Line</label>
                                    <input type="text" name="tag_line" maxlength="120" class="form-control"
                                           value="{{ $val('tag_line') }}"
                                           placeholder="e.g. Smarter property management">
                                </div>
                                <div class="form-group">
                                    <label>Motto</label>
                                    <input type="text" name="motto" maxlength="70" class="form-control"
                                           value="{{ old('motto', $business->motto) }}">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Primary Color</label>
                                        <input type="color" name="primary_color" class="form-control"
                                               value="{{ old('primary_color', $business->primary_color ?: '#0000FF') }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Secondary Color</label>
                                        <input type="color" name="secondary_color" class="form-control"
                                               value="{{ old('secondary_color', $business->secondary_color ?: '#5e9a52') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Logo (used on dashboard, invoices &amp; receipts)</label>
                                    @if ($business->logo && file_exists(public_path('images/'.$business->logo)))
                                        <div class="mb-2"><img src="{{ asset('images/'.$business->logo) }}" alt="logo" style="max-height:80px;border:1px solid #eee;padding:4px;border-radius:6px;"></div>
                                    @endif
                                    <input type="file" name="logo" class="form-control-file" accept="image/*">
                                </div>

                                <div class="form-group">
                                    <label>Header Image (top of invoices / receipts)</label>
                                    @if ($u = $imgUrl('header_image'))
                                        <div class="mb-2"><img src="{{ $u }}" alt="header" style="max-height:80px;border:1px solid #eee;padding:4px;border-radius:6px;"></div>
                                    @endif
                                    <input type="file" name="header_image" class="form-control-file" accept="image/*">
                                </div>

                                <div class="form-group">
                                    <label>Footer Image (bottom of invoices / receipts)</label>
                                    @if ($u = $imgUrl('footer_image'))
                                        <div class="mb-2"><img src="{{ $u }}" alt="footer" style="max-height:80px;border:1px solid #eee;padding:4px;border-radius:6px;"></div>
                                    @endif
                                    <input type="file" name="footer_image" class="form-control-file" accept="image/*">
                                </div>

                                <div class="form-group">
                                    <label>Receipt Banner (wide banner above receipts)</label>
                                    @if ($u = $imgUrl('receipt_banner'))
                                        <div class="mb-2"><img src="{{ $u }}" alt="banner" style="max-height:80px;border:1px solid #eee;padding:4px;border-radius:6px;"></div>
                                    @endif
                                    <input type="file" name="receipt_banner" class="form-control-file" accept="image/*">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CONTACT --}}
                    <div class="tab-pane fade" id="tab-contact" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Email</label>
                                    <input type="email" name="contact_email" class="form-control" value="{{ $val('contact_email') }}">
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="contact_phone" class="form-control" value="{{ $val('contact_phone') }}">
                                </div>
                                <div class="form-group">
                                    <label>Alternate Phone</label>
                                    <input type="text" name="contact_phone_alt" class="form-control" value="{{ $val('contact_phone_alt') }}">
                                </div>
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="url" name="website" class="form-control" value="{{ $val('website') }}" placeholder="https://">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Address Line 1</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $business->address) }}">
                                </div>
                                <div class="form-group">
                                    <label>Address Line 2</label>
                                    <input type="text" name="address_line2" class="form-control" value="{{ $val('address_line2') }}">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>City</label>
                                        <input type="text" name="city" class="form-control" value="{{ $val('city') }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>State</label>
                                        <input type="text" name="state" class="form-control" value="{{ $val('state') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Country</label>
                                        <input type="text" name="country" class="form-control" value="{{ $val('country', 'Nigeria') }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Postal Code</label>
                                        <input type="text" name="postal_code" class="form-control" value="{{ $val('postal_code') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- INVOICE / RECEIPT --}}
                    <div class="tab-pane fade" id="tab-invoice" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Numbering &amp; Currency</h6>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Invoice Prefix</label>
                                        <input type="text" name="invoice_prefix" class="form-control" value="{{ $val('invoice_prefix', 'INV-') }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Next Invoice #</label>
                                        <input type="number" name="invoice_next_number" class="form-control" value="{{ $val('invoice_next_number', 1) }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Receipt Prefix</label>
                                        <input type="text" name="receipt_prefix" class="form-control" value="{{ $val('receipt_prefix', 'RCP-') }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Next Receipt #</label>
                                        <input type="number" name="receipt_next_number" class="form-control" value="{{ $val('receipt_next_number', 1) }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Currency Code</label>
                                        <input type="text" name="currency_code" maxlength="6" class="form-control" value="{{ $val('currency_code', 'NGN') }}">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Currency Symbol</label>
                                        <input type="text" name="currency_symbol" maxlength="6" class="form-control" value="{{ $val('currency_symbol', '₦') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Invoice Logo (overrides Branding logo on invoices)</label>
                                    @if ($u = $imgUrl('invoice_logo'))
                                        <div class="mb-2"><img src="{{ $u }}" alt="invoice logo" style="max-height:60px;"></div>
                                    @endif
                                    <input type="file" name="invoice_logo" class="form-control-file" accept="image/*">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6>Notes &amp; Terms</h6>
                                <div class="form-group">
                                    <label>Default Invoice Notes</label>
                                    <textarea name="invoice_notes" rows="3" class="form-control" placeholder="Shown on every invoice">{{ $val('invoice_notes') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Default Receipt Notes</label>
                                    <textarea name="receipt_notes" rows="3" class="form-control" placeholder="Shown on every receipt">{{ $val('receipt_notes', 'Thank you for your payment.') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Invoice Footer (e.g. legal note / signature line)</label>
                                    <textarea name="invoice_footer" rows="2" class="form-control">{{ $val('invoice_footer') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Payment Terms</label>
                                    <textarea name="payment_terms" rows="2" class="form-control" placeholder="e.g. Payment due within 14 days">{{ $val('payment_terms') }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Bank / Payment Details</label>
                                    <textarea name="bank_details" rows="3" class="form-control" placeholder="Bank name, account number, etc.">{{ $val('bank_details') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAX --}}
                    <div class="tab-pane fade" id="tab-tax" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Tax Name</label>
                                <input type="text" name="tax_name" class="form-control" value="{{ $val('tax_name', 'VAT') }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Tax Rate (%)</label>
                                <input type="number" step="0.01" min="0" max="100" name="tax_rate" class="form-control" value="{{ $val('tax_rate', '7.5') }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Tax ID / Registration #</label>
                                <input type="text" name="tax_id_number" class="form-control" value="{{ $val('tax_id_number') }}">
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="custom-control custom-switch">
                                    <input type="hidden" name="tax_inclusive" value="0">
                                    <input type="checkbox" class="custom-control-input" id="tax_inclusive" name="tax_inclusive" value="1" {{ $val('tax_inclusive') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="tax_inclusive">
                                        Prices entered are <strong>tax-inclusive</strong> (uncheck if tax should be added on top)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SOCIAL --}}
                    <div class="tab-pane fade" id="tab-social" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><i class="fab fa-facebook"></i> Facebook URL</label>
                                <input type="url" name="social_facebook" class="form-control" value="{{ $val('social_facebook') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label><i class="fab fa-twitter"></i> Twitter / X URL</label>
                                <input type="url" name="social_twitter" class="form-control" value="{{ $val('social_twitter') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label><i class="fab fa-instagram"></i> Instagram URL</label>
                                <input type="url" name="social_instagram" class="form-control" value="{{ $val('social_instagram') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label><i class="fab fa-linkedin"></i> LinkedIn URL</label>
                                <input type="url" name="social_linkedin" class="form-control" value="{{ $val('social_linkedin') }}">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Settings</button>
            </div>
        </div>
    </form>
</div>

@endsection
