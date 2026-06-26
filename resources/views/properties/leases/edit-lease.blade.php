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
                    <h1 class="m-0">Edit Lease</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('leases') }}">Leases</a></li>
                        <li class="breadcrumb-item active">Edit Lease</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">Edit Lease Form</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('update.lease', $lease->id) }}" method="post">
                @csrf
                @method('PUT')

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
                            <label for="tenant_id">Tenant</label>
                            <select name="tenant_id" id="tenant_id" class="form-control select2" required>
                                <option value="">Select Tenant</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" {{ old('tenant_id', $lease->tenant_id) == $tenant->id ? 'selected' : '' }}>
                                        {{ $tenant->first_name }} {{ $tenant->last_name }} ({{ $tenant->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="property_selection_type">Lease For:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="property_selection_type" id="select_property_only" value="property_only" {{ old('property_selection_type', $lease->property_unit_id ? 'specific_unit' : 'property_only') == 'property_only' ? 'checked' : '' }}>
                                <label class="form-check-label" for="select_property_only">
                                    Entire Property
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="property_selection_type" id="select_specific_unit" value="specific_unit" {{ old('property_selection_type', $lease->property_unit_id ? 'specific_unit' : 'property_only') == 'specific_unit' ? 'checked' : '' }}>
                                <label class="form-check-label" for="select_specific_unit">
                                    Specific Unit
                                </label>
                            </div>
                        </div>
                        
                        <div id="property_selection_fields">
                            <div class="form-group">
                                {{ $lease->property->name }} ({{ $lease->property->address }})
                                <input type="hidden" name="property_id" value="{{ $lease->property->id }}">
                                <small class="form-text text-muted">Select the property for this lease.</small>
                            </div>

                            <div class="form-group" id="unit_dropdown_group">
                                <label for="property_unit_id">Unit (Optional)</label>
                                <select name="property_unit_id" id="property_unit_id" class="form-control select2">
                                    <option value="">Select Unit (Leave blank for entire property)</option>
                                    @foreach($lease->property->units as $unit)
                                       <option value="{{ $unit->id }}" {{ old('property_unit_id', $lease->property_unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->unit_number }}</option>
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
                                <input type="date" name="start_date" id="start_date" class="form-control" required value="{{ old('start_date', $lease->start_date->format('Y-m-d')) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required value="{{ old('end_date', $lease->end_date->format('Y-m-d')) }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="rent_amount">Rent Amount</label>
                                <input type="number" step="0.01" name="rent_amount" id="rent_amount" class="form-control" required value="{{ old('rent_amount', $lease->rent_amount) }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="deposit_amount">Deposit Amount</label>
                                <input type="number" step="0.01" name="deposit_amount" id="deposit_amount" class="form-control" value="{{ old('deposit_amount', $lease->deposit_amount ?? '') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="payment_frequency">Payment Frequency</label>
                                <select name="payment_frequency" id="payment_frequency" class="form-control" required>
                                    <option value="">Select Frequency</option>
                                    @foreach($paymentFrequencies as $frequency)
                                        <option value="{{ $frequency }}" {{ old('payment_frequency', $lease->payment_frequency) == $frequency ? 'selected' : '' }}>{{ ucfirst($frequency) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="renewal_date">Renewal Date (Optional)</label>
                            <input type="date" name="renewal_date" id="renewal_date" class="form-control" value="{{ old('renewal_date', optional($lease->renewal_date)->format('Y-m-d') ?? '') }}">
                            <small class="form-text text-muted">Date when the lease can be renewed.</small>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                @foreach($leaseStatuses as $status)
                                    <option value="{{ $status }}" {{ old('status', $lease->status) == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="terms">Terms & Conditions</label>
                            <textarea name="terms" id="terms" class="form-control wyswygeditor">{{ old('terms', $lease->terms ?? '') }}</textarea>
                            <small class="form-text text-muted">Any specific terms or conditions for this lease.</small>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update Lease</button>
                    <a href="{{ route('leases') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const propertyIdHidden = document.querySelector('input[name="property_id"][type="hidden"]');
        const unitDropdownGroup = document.getElementById('unit_dropdown_group');
        const propertyUnitIdSelect = document.getElementById('property_unit_id');
        const selectPropertyOnlyRadio = document.getElementById('select_property_only');
        const selectSpecificUnitRadio = document.getElementById('select_specific_unit');

        // Initialize Select2 for all select elements
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });

        // Function to toggle visibility and required status of unit dropdown
        function toggleUnitSelection() {
            if (selectSpecificUnitRadio.checked) {
                unitDropdownGroup.classList.remove('hidden-section');
            } else {
                unitDropdownGroup.classList.add('hidden-section');
                propertyUnitIdSelect.value = ''; // Clear unit selection if property only
                $(propertyUnitIdSelect).trigger('change'); // Update Select2
            }
        }

        selectPropertyOnlyRadio.addEventListener('change', function() {
            toggleUnitSelection();
        });

        selectSpecificUnitRadio.addEventListener('change', function() {
            toggleUnitSelection();
        });

        toggleUnitSelection(); // Call initially to set visibility based on current state
    });
</script>
