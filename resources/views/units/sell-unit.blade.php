@extends('layouts.template')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sell Unit</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('properties') }}">Properties</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('show.property', $unit->property->id) }}">{{ $unit->property->name }}</a></li>
                        <li class="breadcrumb-item active">Sell Unit {{ $unit->unit_number }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Unit Sale Form</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('unit.sale.process') }}" method="post" id="saleForm">
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

                {{-- Unit Information --}}
                <div class="card card-secondary card-outline mb-3">
                    <div class="card-header">
                        <h4 class="card-title">Unit Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Property</label>
                                <input type="text" class="form-control" value="{{ $unit->property->name }}" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Unit Number</label>
                                <input type="text" class="form-control" value="{{ $unit->unit_number }}" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Unit Type</label>
                                <input type="text" class="form-control" value="{{ $unit->unit_type ?? 'N/A' }}" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Status</label>
                                <span class="badge badge-success">{{ ucfirst($unit->status) }}</span>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Square Footage</label>
                                <input type="text" class="form-control" value="{{ $unit->square_footage ?? 'N/A' }} sqft" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sale Price --}}
                <div class="card card-secondary card-outline mb-3">
                    <div class="card-header">
                        <h4 class="card-title">Sale Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="sale_price">Sale Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₦</span>
                                </div>
                                <input type="number" step="0.01" name="sale_price" id="sale_price" class="form-control @error('sale_price') is-invalid @enderror" 
                                    placeholder="Enter sale price" value="{{ old('sale_price', $unit->sale_price) }}" required>
                                @error('sale_price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="notes">Sale Notes</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                                rows="3" placeholder="Add any notes about this sale...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Buyer Information --}}
                <div class="card card-secondary card-outline mb-3">
                    <div class="card-header">
                        <h4 class="card-title">Buyer Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="buyer_type">Buyer Type <span class="text-danger">*</span></label>
                            <select name="buyer_type" id="buyer_type" class="form-control @error('buyer_type') is-invalid @enderror" required onchange="toggleBuyerFields()">
                                <option value="">-- Select Buyer Type --</option>
                                <option value="owner" {{ old('buyer_type') === 'owner' ? 'selected' : '' }}>Existing Owner</option>
                                <option value="tenant" {{ old('buyer_type') === 'tenant' ? 'selected' : '' }}>Existing Tenant</option>
                                <option value="client" {{ old('buyer_type') === 'client' ? 'selected' : '' }}>Existing Client</option>
                                <option value="owner_new" {{ old('buyer_type') === 'owner_new' ? 'selected' : '' }}>New Owner</option>
                                <option value="tenant_new" {{ old('buyer_type') === 'tenant_new' ? 'selected' : '' }}>New Tenant</option>
                                <option value="client_new" {{ old('buyer_type') === 'client_new' ? 'selected' : '' }}>New Client</option>
                            </select>
                            @error('buyer_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Select Existing Buyer --}}
                        <div id="existing_buyer_section">
                            <div id="owner_list" class="form-group hidden-section">
                                <label for="buyer_id">Select Owner</label>
                                <select name="buyer_id" id="buyer_id" class="form-control select2">
                                    <option value="">-- Choose an Owner --</option>
                                    @foreach($owners as $owner)
                                        <option value="{{ $owner->id }}" {{ old('buyer_id') == $owner->id ? 'selected' : '' }}>
                                            {{ $owner->first_name }} {{ $owner->last_name }}
                                            @if($owner->company_name)
                                                ({{ $owner->company_name }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="tenant_list" class="form-group hidden-section">
                                <label for="buyer_id">Select Tenant</label>
                                <select name="buyer_id" id="buyer_id" class="form-control select2">
                                    <option value="">-- Choose a Tenant --</option>
                                    @foreach($tenants as $tenant)
                                        <option value="{{ $tenant->id }}" {{ old('buyer_id') == $tenant->id ? 'selected' : '' }}>
                                            {{ $tenant->first_name }} {{ $tenant->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="client_list" class="form-group hidden-section">
                                <label for="buyer_id">Select Client</label>
                                <select name="buyer_id" id="buyer_id" class="form-control select2">
                                    <option value="">-- Choose a Client --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('buyer_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Create New Buyer --}}
                        <div id="new_buyer_section" class="hidden-section">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Fill in the details below to create a new buyer
                            </div>

                            <div id="name_fields" class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="buyer_first_name">First Name</label>
                                    <input type="text" name="buyer_first_name" id="buyer_first_name" class="form-control" 
                                        placeholder="First name" value="{{ old('buyer_first_name') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="buyer_last_name">Last Name</label>
                                    <input type="text" name="buyer_last_name" id="buyer_last_name" class="form-control" 
                                        placeholder="Last name" value="{{ old('buyer_last_name') }}">
                                </div>
                            </div>

                            <div id="client_name_field" class="form-group hidden-section">
                                <label for="buyer_name">Full Name/Company Name</label>
                                <input type="text" name="buyer_name" id="buyer_name" class="form-control" 
                                    placeholder="Name" value="{{ old('buyer_name') }}">
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="buyer_email">Email</label>
                                    <input type="email" name="buyer_email" id="buyer_email" class="form-control" 
                                        placeholder="email@example.com" value="{{ old('buyer_email') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="buyer_phone">Phone Number</label>
                                    <input type="text" name="buyer_phone" id="buyer_phone" class="form-control" 
                                        placeholder="+234 801 234 5678" value="{{ old('buyer_phone') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Hidden field for unit ID --}}
                <input type="hidden" name="property_unit_id" value="{{ $unit->id }}">

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Continue to Payment
                    </button>
                    <a href="{{ route('show.property', $unit->property->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .hidden-section {
            display: none;
        }
    </style>

    <script>
        function toggleBuyerFields() {
            const buyerType = document.getElementById('buyer_type').value;
            const existingSection = document.getElementById('existing_buyer_section');
            const newSection = document.getElementById('new_buyer_section');
            const ownerList = document.getElementById('owner_list');
            const tenantList = document.getElementById('tenant_list');
            const clientList = document.getElementById('client_list');
            const nameFields = document.getElementById('name_fields');
            const clientNameField = document.getElementById('client_name_field');
            
            // Hide all
            existingSection.querySelectorAll('.form-group').forEach(el => el.classList.add('hidden-section'));
            nameFields.classList.add('hidden-section');
            clientNameField.classList.add('hidden-section');
            
            if (buyerType === 'owner') {
                existingSection.classList.remove('hidden-section');
                newSection.classList.add('hidden-section');
                ownerList.classList.remove('hidden-section');
            } else if (buyerType === 'tenant') {
                existingSection.classList.remove('hidden-section');
                newSection.classList.add('hidden-section');
                tenantList.classList.remove('hidden-section');
            } else if (buyerType === 'client') {
                existingSection.classList.remove('hidden-section');
                newSection.classList.add('hidden-section');
                clientList.classList.remove('hidden-section');
            } else if (buyerType === 'owner_new') {
                existingSection.classList.add('hidden-section');
                newSection.classList.remove('hidden-section');
                nameFields.classList.remove('hidden-section');
                
                // Update form to use 'owner' internally
                document.getElementById('buyer_type').value = buyerType;
            } else if (buyerType === 'tenant_new') {
                existingSection.classList.add('hidden-section');
                newSection.classList.remove('hidden-section');
                nameFields.classList.remove('hidden-section');
                
                document.getElementById('buyer_type').value = buyerType;
            } else if (buyerType === 'client_new') {
                existingSection.classList.add('hidden-section');
                newSection.classList.remove('hidden-section');
                clientNameField.classList.remove('hidden-section');
                nameFields.classList.add('hidden-section');
                
                document.getElementById('buyer_type').value = buyerType;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Adjust form submission to normalize buyer_type
            document.getElementById('saleForm').addEventListener('submit', function(e) {
                const buyerType = document.getElementById('buyer_type').value;
                
                // Normalize buyer_type for controller
                if (buyerType === 'owner_new') {
                    document.getElementById('buyer_type').value = 'owner';
                } else if (buyerType === 'tenant_new') {
                    document.getElementById('buyer_type').value = 'tenant';
                } else if (buyerType === 'client_new') {
                    document.getElementById('buyer_type').value = 'client';
                }
            });
            
            toggleBuyerFields();
        });
    </script>
@endsection
