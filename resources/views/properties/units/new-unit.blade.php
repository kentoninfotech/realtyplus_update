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
                    <h1 class="m-0">Add New Unit</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('show.property', $property->id) }}">Property</a></li>
                        <li class="breadcrumb-item active">Add New Unit</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">Unit Form</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('create.unit') }}" method="post">
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

                {{-- Section 1: Basic Unit Information --}}
                <div class="card card-secondary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Basic Unit Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="property_id">Associated Property</label>
                            @if(isset($property) && $property->id)
                                {{-- If property ID is passed from route, use hidden input and display name --}}
                                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id }}">
                                <p class="form-control-static">
                                    <strong>{{ $property->name }} ({{ $property->address }})</strong>
                                    <small class="text-muted">(Property pre-selected)</small>
                                </p>
                            @else
                                {{-- Otherwise, allow user to select from dropdown --}}
                                <select name="property_id" id="property_id" class="form-control select2" required>
                                    <option value="">Select Property</option>
                                    @foreach($properties as $prop)
                                        <option value="{{ $prop->id }}" {{ (old('property_id') == $prop->id) ? 'selected' : '' }}>
                                            {{ $prop->name }} ({{ $prop->address }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Select the property this unit belongs to.</small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="unit_number">Unit Number/Identifier</label>
                            <input type="text" name="unit_number" id="unit_number" class="form-control" required value="{{ old('unit_number') }}">
                            <small class="form-text text-muted">e.g., Apt 101, Unit B, Lot 5, Office 300.</small>
                        </div>

                        <div class="form-group">
                            <label for="unit_type">Unit Type</label>
                            <select name="unit_type" id="unit_type" class="form-control" required>
                                <option value="">Select Unit Type</option>
                                @foreach($unitTypes as $type)
                                    <option value="{{ $type }}" {{ old('unit_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control wyswygeditor">{{ old('description') }}</textarea>
                            <small class="form-text text-muted">Detailed information about this specific unit.</small>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Rented</option>
                                <option value="leased" {{ old('status') == 'leased' ? 'selected' : '' }}>Leased</option>
                                <option value="under_maintenance" {{ old('status') == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                                <option value="vacant" {{ old('status') == 'vacant' ? 'selected' : '' }}>Vacant</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Type-Specific Details (Dynamic) --}}
                <div class="card card-secondary card-outline mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Type-Specific Details</h3>
                    </div>
                    <div class="card-body">
                        {{-- Residential/Commercial Fields --}}
                        <div id="residential_commercial_fields" class="hidden-section">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="square_footage">Square Footage (sqft)</label>
                                    <input type="number" step="0.01" name="square_footage" id="square_footage" class="form-control" value="{{ old('square_footage') }}">
                                    <small class="form-text text-muted">Built area of the unit in square feet.</small>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="bedrooms">Bedrooms</label>
                                    <input type="number" name="bedrooms" id="bedrooms" class="form-control" value="{{ old('bedrooms') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bathrooms">Bathrooms</label>
                                    <input type="number" step="0.1" name="bathrooms" id="bathrooms" class="form-control" value="{{ old('bathrooms') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Land Specific Fields --}}
                        <div id="land_fields" class="hidden-section">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="area_sqm">Area (sqm)</label>
                                    <input type="number" step="0.01" name="area_sqm" id="area_sqm" class="form-control" value="{{ old('area_sqm') }}">
                                    <small class="form-text text-muted">Area of the land unit in square meters.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Pricing & Availability --}}
                <div class="card card-secondary card-outline mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Pricing & Availability</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="sale_price">Sale Price</label>
                                <input type="number" step="0.01" name="sale_price" id="sale_price" class="form-control" value="{{ old('sale_price') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="rent_price">Rent Price</label>
                                <input type="number" step="0.01" name="rent_price" id="rent_price" class="form-control" value="{{ old('rent_price') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="deposit_amount">Deposit Amount</label>
                                <input type="number" step="0.01" name="deposit_amount" id="deposit_amount" class="form-control" value="{{ old('deposit_amount') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="available_from">Available From</label>
                            <input type="date" name="available_from" id="available_from" class="form-control" value="{{ old('available_from') }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Create Unit</button>
            </form>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const unitTypeSelect = document.getElementById('unit_type');
        const residentialCommercialFields = document.getElementById('residential_commercial_fields');
        const landFields = document.getElementById('land_fields');

        // Store initial values to restore them when fields are hidden/shown
        // Using old() directly to ensure values persist across validation errors
        const initialValues = {
            square_footage: "{{ old('square_footage') }}",
            bedrooms: "{{ old('bedrooms') }}",
            bathrooms: "{{ old('bathrooms') }}",
            area_sqm: "{{ old('area_sqm') }}",
        };

        function toggleUnitTypeFields() {
            let selectedType = unitTypeSelect.value;

            // Hide all dynamic sections first
            residentialCommercialFields.classList.add('hidden-section');
            landFields.classList.add('hidden-section');

            // Reset values to initial state (important for old() values after validation)
            document.getElementById('square_footage').value = initialValues.square_footage;
            document.getElementById('bedrooms').value = initialValues.bedrooms;
            document.getElementById('bathrooms').value = initialValues.bathrooms;
            document.getElementById('area_sqm').value = initialValues.area_sqm;

            // Show relevant sections based on selected unit type
            if (selectedType === 'residential' || selectedType === 'commercial' || selectedType === 'other') {
                residentialCommercialFields.classList.remove('hidden-section');
            } else if (selectedType === 'land') {
                landFields.classList.remove('hidden-section');
            }
        }

        // Event listener for unit type change
        unitTypeSelect.addEventListener('change', toggleUnitTypeFields);

        // Initial call to set fields based on default selection or old input
        toggleUnitTypeFields();

        // Initialize Select2 for property_id dropdown if it's visible
        const propertyIdSelect = document.getElementById('property_id');
        if (propertyIdSelect && propertyIdSelect.tagName === 'SELECT') {
            $('.select2').select2({
                placeholder: "Select a Property",
                allowClear: true
            });
        }
    });
</script>
