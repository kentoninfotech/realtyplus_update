@extends('layouts.template')

<style>
    /* Basic styling for hidden sections to prevent layout shifts */
    .hidden-section {
        display: none;
    }
</style>

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create New Lease</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('leases') }}">Leases</a></li>
                        <li class="breadcrumb-item active">Create New Lease</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">Lease Form</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('create.lease') }}" method="post">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Section 1: Lease Association & Tenant --}}
                <div class="card card-secondary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Lease Association & Tenant</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="tenant_id">
                                Tenant
                                <button type="button" class="btn btn-sm btn-info ml-2" data-toggle="modal" data-target="#createTenantModal">
                                    <i class="fas fa-plus"></i> Create New
                                </button>
                            </label>
                            <select name="tenant_id" id="tenant_id" class="form-control select2" required>
                                <option value="">Select Tenant</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                        {{ $tenant->first_name }} {{ $tenant->last_name }} ({{ $tenant->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="property_selection_type">Lease For:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="property_selection_type" id="select_property_only" value="property_only" {{ old('property_selection_type', !isset($unit) ? 'property_only' : '') == 'property_only' ? 'checked' : '' }}>
                                <label class="form-check-label" for="select_property_only">
                                    Entire Property
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="property_selection_type" id="select_specific_unit" value="specific_unit" {{ old('property_selection_type', isset($unit) ? 'specific_unit' : '') == 'specific_unit' ? 'checked' : '' }}>
                                <label class="form-check-label" for="select_specific_unit">
                                    Specific Unit
                                </label>
                            </div>
                        </div>
                        
                        <div id="property_selection_fields">
                            <div class="form-group">
                                {{ $property->name }} ({{ $property->address }})
                                    <input type="hidden" name="property_id" value="{{ $property->id }}">

                                @if(isset($unit))
                                    {{-- Hidden input to ensure property_id is submitted if disabled --}}
                                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                                @endif
                                <small class="form-text text-muted">Select the property for this lease.</small>
                            </div>

                            <div class="form-group" id="unit_dropdown_group">
                                <label for="property_unit_id">Unit (Optional)</label>
                                <select name="property_unit_id" id="property_unit_id" class="form-control select2">
                                    <option value="">Select Unit (Leave blank for entire property)</option>
                                    @foreach($units as $u)
                                       <option value="{{ $u->id }}" {{ (old('property_unit_id', isset($unit) ? $unit->id : null) == $u->id) ? 'selected' : '' }}>{{ $u->unit_number }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Select a specific unit within the property, or leave blank for a whole property lease.</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Lease Terms --}}
                <div class="card card-secondary card-outline mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Lease Terms</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required value="{{ old('start_date') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required value="{{ old('end_date') }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="rent_amount">Rent Amount</label>
                                <input type="number" step="0.01" name="rent_amount" id="rent_amount" class="form-control" required value="{{ old('rent_amount') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="deposit_amount">Deposit Amount</label>
                                <input type="number" step="0.01" name="deposit_amount" id="deposit_amount" class="form-control" value="{{ old('deposit_amount') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="payment_frequency">Payment Frequency</label>
                                <select name="payment_frequency" id="payment_frequency" class="form-control" required>
                                    <option value="">Select Frequency</option>
                                    @foreach($paymentFrequencies as $frequency)
                                        <option value="{{ $frequency }}" {{ old('payment_frequency') == $frequency ? 'selected' : '' }}>{{ ucfirst($frequency) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="renewal_date">Renewal Date (Optional)</label>
                            <input type="date" name="renewal_date" id="renewal_date" class="form-control" value="{{ old('renewal_date') }}">
                            <small class="form-text text-muted">Date when the lease can be renewed.</small>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                @foreach($leaseStatuses as $status)
                                    <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="terms">Terms & Conditions</label>
                            <textarea name="terms" id="terms" class="form-control wyswygeditor">{{ old('terms') }}</textarea>
                            <small class="form-text text-muted">Any specific terms or conditions for this lease.</small>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Create Lease</button>
            </form>
        </div>
    </div>

    {{-- Create New Tenant Modal --}}
    <div class="modal fade" id="createTenantModal" tabindex="-1" role="dialog" aria-labelledby="createTenantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createTenantModalLabel">Create New Tenant</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="tenantFormErrors" class="alert alert-danger d-none" role="alert"></div>
                    
                    <form id="createTenantForm">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="modalFirstName">First Name *</label>
                                <input type="text" class="form-control" id="modalFirstName" name="first_name" required>
                                <small class="form-text text-danger d-none" id="error-first_name"></small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="modalLastName">Last Name *</label>
                                <input type="text" class="form-control" id="modalLastName" name="last_name" required>
                                <small class="form-text text-danger d-none" id="error-last_name"></small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="modalEmail">Email Address *</label>
                            <input type="email" class="form-control" id="modalEmail" name="email" required>
                            <small class="form-text text-muted">Must be unique in your account</small>
                            <small class="form-text text-danger d-none" id="error-email"></small>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="modalPhoneNumber">Phone Number</label>
                                <input type="tel" class="form-control" id="modalPhoneNumber" name="phone_number">
                                <small class="form-text text-danger d-none" id="error-phone_number"></small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="modalAddress">Address</label>
                                <input type="text" class="form-control" id="modalAddress" name="address">
                                <small class="form-text text-danger d-none" id="error-address"></small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="modalEmergencyContactName">Emergency Contact Name</label>
                                <input type="text" class="form-control" id="modalEmergencyContactName" name="emergency_contact_name">
                                <small class="form-text text-danger d-none" id="error-emergency_contact_name"></small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="modalEmergencyContactPhone">Emergency Contact Phone</label>
                                <input type="tel" class="form-control" id="modalEmergencyContactPhone" name="emergency_contact_phone">
                                <small class="form-text text-danger d-none" id="error-emergency_contact_phone"></small>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Note:</strong> A temporary password will be generated. The tenant can reset it after login.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitCreateTenantBtn">
                        <span id="btnText">Create Tenant</span>
                        <span id="btnSpinner" class="spinner-border spinner-border-sm ml-2 d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const propertyIdSelect = document.getElementById('property_id');
        const unitDropdownGroup = document.getElementById('unit_dropdown_group');
        const propertyUnitIdSelect = document.getElementById('property_unit_id');
        const selectPropertyOnlyRadio = document.getElementById('select_property_only');
        const selectSpecificUnitRadio = document.getElementById('select_specific_unit');

        // Initialize Select2 for all select elements
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });

        // Function to load units based on selected property
        function loadUnitsForProperty(propertyId, selectedUnitId = null) {
            if (propertyId) {
                fetch(`{{ route('get.units.by.property') }}?property_id=${propertyId}`)
                    .then(response => response.json())
                    .then(units => {
                        // Clear existing options, but keep the "Select Unit" placeholder
                        propertyUnitIdSelect.innerHTML = '<option value="">Select Unit (Leave blank for entire property)</option>';
                        units.forEach(unit => {
                            const option = document.createElement('option');
                            option.value = unit.id;
                            option.textContent = unit.unit_number;
                            if (selectedUnitId && unit.id == selectedUnitId) {
                                option.selected = true;
                            }
                            propertyUnitIdSelect.appendChild(option);
                        });
                        // Trigger Select2 update
                        $(propertyUnitIdSelect).trigger('change');
                    })
                    .catch(error => {
                        console.error('Error loading units:', error);
                        propertyUnitIdSelect.innerHTML = '<option value="">Error loading units</option>';
                        $(propertyUnitIdSelect).trigger('change');
                    });
            } else {
                // If no property selected, clear units dropdown
                propertyUnitIdSelect.innerHTML = '<option value="">Select Unit (Leave blank for entire property)</option>';
                $(propertyUnitIdSelect).trigger('change');
            }
        }

        // Function to toggle visibility and required status of unit dropdown
        function toggleUnitSelection() {
            if (selectSpecificUnitRadio.checked) {
                unitDropdownGroup.classList.remove('hidden-section');
                // propertyUnitIdSelect.setAttribute('required', 'required'); // Make unit required if specific unit selected
            } else {
                unitDropdownGroup.classList.add('hidden-section');
                propertyUnitIdSelect.value = ''; // Clear unit selection if property only
                $(propertyUnitIdSelect).trigger('change'); // Update Select2
                // propertyUnitIdSelect.removeAttribute('required');
            }
        }

        // Event listeners
        propertyIdSelect.addEventListener('change', function() {
            loadUnitsForProperty(this.value);
        });

        selectPropertyOnlyRadio.addEventListener('change', function() {
            toggleUnitSelection();
            // When "Entire Property" is selected, ensure property_id is enabled and unit_id is cleared
            propertyIdSelect.removeAttribute('disabled');
            // If a unit was pre-selected from route, remove its hidden input
            const hiddenPropertyIdInput = document.querySelector('input[name="property_id"][type="hidden"]');
            if (hiddenPropertyIdInput) {
                hiddenPropertyIdInput.remove();
            }
        });

        selectSpecificUnitRadio.addEventListener('change', function() {
            toggleUnitSelection();
            // When "Specific Unit" is selected, ensure property_id is enabled (unless pre-selected from route)
            // and unit_id is enabled
            if (!("{{ isset($unit) }}" === "true")) { // Only re-enable if not pre-selected from route
                propertyIdSelect.removeAttribute('disabled');
            }
        });

        // Initial setup on page load
        const initialPropertyId = propertyIdSelect.value;
        const initialUnitId = propertyUnitIdSelect.value;

        // If a unit was pre-selected from the route, disable property_id and select specific unit radio
        @if(isset($unit) && $unit->id)
            propertyIdSelect.setAttribute('disabled', 'disabled');
            selectSpecificUnitRadio.checked = true;
            selectPropertyOnlyRadio.checked = false; // Ensure other is unchecked
            loadUnitsForProperty(initialPropertyId, initialUnitId); // Load units and select the pre-selected one
        @elseif(old('property_id'))
            // If there's old input for property_id (e.g., after validation error)
            // Load units for the old property_id and try to select old property_unit_id
            loadUnitsForProperty(initialPropertyId, initialUnitId);
        @endif

        toggleUnitSelection(); // Call initially to set visibility based on old input or default
    });

    // Handle Create Tenant Form Submission
    document.addEventListener('DOMContentLoaded', function() {
        const createTenantForm = document.getElementById('createTenantForm');
        const submitBtn = document.getElementById('submitCreateTenantBtn');
        const tenantIdSelect = document.getElementById('tenant_id');
        const tenantModal = document.getElementById('createTenantModal');
        const errorContainer = document.getElementById('tenantFormErrors');

        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                e.preventDefault();

                // Hide previous errors
                errorContainer.classList.add('d-none');
                errorContainer.innerHTML = '';
                
                // Clear field-level errors
                document.querySelectorAll('.form-text.text-danger').forEach(el => {
                    el.classList.add('d-none');
                });

                // Show loading state
                submitBtn.disabled = true;
                document.getElementById('btnText').classList.add('d-none');
                document.getElementById('btnSpinner').classList.remove('d-none');

                // Prepare form data
                const formData = new FormData(createTenantForm);

                // Send AJAX request
                fetch('{{ route('create.tenant.ajax') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add new tenant to dropdown
                        const option = document.createElement('option');
                        option.value = data.tenant.id;
                        option.textContent = data.tenant.full_name + ' (' + data.tenant.email + ')';
                        option.selected = true;
                        tenantIdSelect.appendChild(option);
                        
                        // Update Select2
                        $(tenantIdSelect).val(data.tenant.id).trigger('change');

                        // Show success message
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });

                        // Close modal
                        $(tenantModal).modal('hide');

                        // Reset form
                        createTenantForm.reset();
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            let errorHtml = '<strong>Please fix the following errors:</strong><ul>';
                            for (const field in data.errors) {
                                const errors = data.errors[field];
                                errors.forEach(error => {
                                    errorHtml += '<li>' + error + '</li>';
                                    // Show field-level error
                                    const errorEl = document.getElementById('error-' + field);
                                    if (errorEl) {
                                        errorEl.textContent = error;
                                        errorEl.classList.remove('d-none');
                                    }
                                });
                            }
                            errorHtml += '</ul>';
                            errorContainer.innerHTML = errorHtml;
                        } else {
                            errorContainer.innerHTML = '<strong>Error:</strong> ' + data.message;
                        }
                        errorContainer.classList.remove('d-none');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorContainer.innerHTML = '<strong>Error:</strong> An unexpected error occurred. Please try again.';
                    errorContainer.classList.remove('d-none');
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while creating the tenant.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                })
                .finally(() => {
                    // Hide loading state
                    submitBtn.disabled = false;
                    document.getElementById('btnText').classList.remove('d-none');
                    document.getElementById('btnSpinner').classList.add('d-none');
                });
            });

            // Clear errors when user modifies fields
            createTenantForm.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    const errorEl = document.getElementById('error-' + this.name);
                    if (errorEl) {
                        errorEl.classList.add('d-none');
                    }
                });
            });
        }
    });
</script>
