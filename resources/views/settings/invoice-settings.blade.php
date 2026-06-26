@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Invoice & Receipt Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Settings</a></li>
                        <li class="breadcrumb-item active">Invoice Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <h4 class="alert-heading">Error!</h4>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            <form action="{{ route('business-settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Company Branding Section --}}
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-palette"></i> Company Branding
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name">Company Name</label>
                                    <input type="text" id="company_name" name="company_name" class="form-control" 
                                        value="{{ old('company_name', $invoiceSettings['company_name'] ?? '') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="company_motto">Company Motto</label>
                                    <input type="text" id="company_motto" name="company_motto" class="form-control" 
                                        placeholder="e.g., Your trusted real estate partner" 
                                        value="{{ old('company_motto', $invoiceSettings['company_motto'] ?? '') }}">
                                </div>

                                <div class="form-group">
                                    <label for="primary_color">Primary Color (for invoices)</label>
                                    <input type="color" id="primary_color" name="primary_color" class="form-control form-control-color" 
                                        value="{{ old('primary_color', $invoiceSettings['primary_color'] ?? '#007bff') }}">
                                    <small class="form-text text-muted">Used for headers, borders, and highlights in invoices</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_logo">Company Logo</label>
                                    @if ($invoiceSettings['company_logo'] ?? null)
                                        <div class="mb-2">
                                            <img src="{{ asset($invoiceSettings['company_logo']) }}" alt="Company Logo" style="max-height: 100px;">
                                            <form action="{{ route('settings.delete-image') }}" method="POST" class="d-inline mt-2">
                                                @csrf
                                                <input type="hidden" name="key" value="company_logo">
                                                <button type="submit" class="btn btn-sm btn-danger">Remove Logo</button>
                                            </form>
                                        </div>
                                    @endif
                                    <input type="file" id="company_logo" name="company_logo" class="form-control-file" 
                                        accept="image/*">
                                    <small class="form-text text-muted">Max 2MB (PNG, JPG, GIF)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="card card-info mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-phone"></i> Contact Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_address">Company Address</label>
                                    <textarea id="company_address" name="company_address" class="form-control" rows="3">{{ old('company_address', $invoiceSettings['company_address'] ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_phone">Phone Number</label>
                                    <input type="tel" id="company_phone" name="company_phone" class="form-control" 
                                        value="{{ old('company_phone', $invoiceSettings['company_phone'] ?? '') }}">
                                </div>

                                <div class="form-group">
                                    <label for="company_email">Email Address</label>
                                    <input type="email" id="company_email" name="company_email" class="form-control" 
                                        value="{{ old('company_email', $invoiceSettings['company_email'] ?? '') }}">
                                </div>

                                <div class="form-group">
                                    <label for="company_website">Website</label>
                                    <input type="url" id="company_website" name="company_website" class="form-control" 
                                        placeholder="https://example.com" 
                                        value="{{ old('company_website', $invoiceSettings['company_website'] ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tax Information --}}
                <div class="card card-warning mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-receipt"></i> Tax Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tax_name">Tax Type Name</label>
                                    <input type="text" id="tax_name" name="tax_name" class="form-control" 
                                        placeholder="e.g., VAT, GST, TAXES" 
                                        value="{{ old('tax_name', $invoiceSettings['tax_name'] ?? 'Tax') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tax_id">Tax ID Number</label>
                                    <input type="text" id="tax_id" name="tax_id" class="form-control" 
                                        placeholder="e.g., 12345678" 
                                        value="{{ old('tax_id', $invoiceSettings['tax_id'] ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Invoice Customization --}}
                <div class="card card-success mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-invoice"></i> Invoice Customization
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_prefix">Invoice Number Prefix</label>
                                    <input type="text" id="invoice_prefix" name="invoice_prefix" class="form-control" 
                                        placeholder="e.g., INV" 
                                        value="{{ old('invoice_prefix', $invoiceSettings['invoice_prefix'] ?? 'INV') }}">
                                    <small class="form-text text-muted">Invoice will be numbered as: INV-000001</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="header_position">Invoice Header Position</label>
                                    <select id="header_position" name="header_position" class="form-control">
                                        <option value="left" {{ old('header_position', $invoiceSettings['header_position'] ?? 'center') === 'left' ? 'selected' : '' }}>Left</option>
                                        <option value="center" {{ old('header_position', $invoiceSettings['header_position'] ?? 'center') === 'center' ? 'selected' : '' }}>Center</option>
                                        <option value="right" {{ old('header_position', $invoiceSettings['header_position'] ?? 'center') === 'right' ? 'selected' : '' }}>Right</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_header_image">Invoice Header Image</label>
                                    @if ($invoiceSettings['invoice_header_image'] ?? null)
                                        <div class="mb-2">
                                            <img src="{{ asset($invoiceSettings['invoice_header_image']) }}" alt="Header" style="max-width: 100%; max-height: 100px;">
                                            <form action="{{ route('settings.delete-image') }}" method="POST" class="d-inline mt-2">
                                                @csrf
                                                <input type="hidden" name="key" value="invoice_header_image">
                                                <button type="submit" class="btn btn-sm btn-danger">Remove Header</button>
                                            </form>
                                        </div>
                                    @endif
                                    <input type="file" id="invoice_header_image" name="invoice_header_image" class="form-control-file" 
                                        accept="image/*">
                                    <small class="form-text text-muted">Optional decorative header for invoices. Max 2MB</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="signature_image">Authorized Signature</label>
                                    @if ($invoiceSettings['signature_image'] ?? null)
                                        <div class="mb-2">
                                            <img src="{{ asset($invoiceSettings['signature_image']) }}" alt="Signature" style="max-height: 80px;">
                                            <form action="{{ route('settings.delete-image') }}" method="POST" class="d-inline mt-2">
                                                @csrf
                                                <input type="hidden" name="key" value="signature_image">
                                                <button type="submit" class="btn btn-sm btn-danger">Remove Signature</button>
                                            </form>
                                        </div>
                                    @endif
                                    <input type="file" id="signature_image" name="signature_image" class="form-control-file" 
                                        accept="image/*">
                                    <small class="form-text text-muted">Authorized signature image. Max 1MB</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label for="invoice_footer_text">Invoice Footer Text</label>
                            <textarea id="invoice_footer_text" name="invoice_footer_text" class="form-control" rows="3" 
                                placeholder="e.g., Thank you for your business!">{{ old('invoice_footer_text', $invoiceSettings['invoice_footer_text'] ?? '') }}</textarea>
                            <small class="form-text text-muted">This text will appear at the bottom of all invoices</small>
                        </div>
                    </div>
                </div>

                {{-- Terms & Conditions --}}
                <div class="card card-secondary mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-alt"></i> Terms & Conditions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="invoice_terms">Invoice Terms</label>
                            <textarea id="invoice_terms" name="invoice_terms" class="form-control" rows="5" 
                                placeholder="e.g., Payment terms, cancellation policy, etc.">{{ old('invoice_terms', $invoiceSettings['invoice_terms'] ?? '') }}</textarea>
                            <small class="form-text text-muted">These terms will appear on invoices. Use line breaks for multiple items</small>
                        </div>

                        <div class="form-group mt-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="show_company_info" name="show_company_info" 
                                    {{ old('show_company_info', $invoiceSettings['show_company_info'] ?? '1') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="show_company_info">
                                    Show company information on invoices
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="show_tax_id" name="show_tax_id" 
                                    {{ old('show_tax_id', $invoiceSettings['show_tax_id'] ?? '1') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="show_tax_id">
                                    Show tax ID on invoices
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="show_terms" name="show_terms" 
                                    {{ old('show_terms', $invoiceSettings['show_terms'] ?? '1') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="show_terms">
                                    Show terms & conditions on invoices
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Display Settings --}}
                <div class="card card-outline-secondary mt-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-eye"></i> Display Settings
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="footer_position">Invoice Footer Position</label>
                                    <select id="footer_position" name="footer_position" class="form-control">
                                        <option value="left" {{ old('footer_position', $invoiceSettings['footer_position'] ?? 'center') === 'left' ? 'selected' : '' }}>Left</option>
                                        <option value="center" {{ old('footer_position', $invoiceSettings['footer_position'] ?? 'center') === 'center' ? 'selected' : '' }}>Center</option>
                                        <option value="right" {{ old('footer_position', $invoiceSettings['footer_position'] ?? 'center') === 'right' ? 'selected' : '' }}>Right</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            <strong>Preview:</strong> After saving, click on "View Invoice" when viewing a transaction to see how your invoice will look with these settings.
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Save Invoice Settings
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .form-control-color {
            height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
        }
    </style>
@endsection
