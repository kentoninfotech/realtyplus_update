@extends('layouts.template')

{{-- Add necessary CSS for map if you're using it, or remove if not --}}
<style>
    body {
        margin: 0;
        padding: 0;
    }

    #map {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 100%;
    }
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
                    <h1 class="m-0">Edit Property: {{ $property->name }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('propertys') }}">Properties</a></li>
                        <li class="breadcrumb-item active">Edit Property</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header">
            <h4 class="card-title">Property Form</h4>
        </div>
        <div class="card-body">
            {{-- Form action to update the property, using PUT method --}}
            <form action="{{ route('update.property', $property->id) }}" method="post">
                @csrf
                @method('PUT') {{-- Use PUT method for updates --}}

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Section 1: Basic Property Information --}}
                <div class="card card-secondary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Basic Property Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="property_type_id">Property Type</label>
                            <select name="property_type_id" id="property_type_id" class="form-control" required>
                                <option value="">Select Type</option>
                                @foreach($propertyTypes as $type)
                                    <option
                                        value="{{ $type->id }}"
                                        data-slug="{{ $type->slug }}"
                                        data-can-have-multiple-units="{{ $type->can_have_multiple_units ? 'true' : 'false' }}"
                                        {{-- Select the existing property type or old input --}}
                                        {{ (old('property_type_id', $property->property_type_id) == $type->id) ? 'selected' : '' }}
                                    >
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Property Name</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name', $property->name) }}">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="owner_id">Owner</label>
                                <select name="owner_id" id="owner_id" class="form-control select2" required>
                                    <option value="">Select Owner</option>
                                    @foreach($owners as $owner)
                                        <option value="{{ $owner->id }}" {{ (old('owner_id', $property->owner_id) == $owner->id) ? 'selected' : '' }}>
                                            {{ $owner->first_name }} {{ $owner->last_name }}{{ $owner->company_name ? ' ('.$owner->company_name.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="agent_id">Agent</label>
                                <select name="agent_id" id="agent_id" class="form-control select2">
                                    <option value="">Select Agent</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ (old('agent_id', $property->agent_id) == $agent->id) ? 'selected' : '' }}>
                                            {{ $agent->first_name }} {{ $agent->last_name }}{{ $agent->license_number ? ' ('.$agent->license_number.')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Location Details --}}
                <div class="card card-secondary card-outline mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Location Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="address">Address</label>
                                <input type="text" name="address" class="form-control" required value="{{ old('address', $property->address) }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="state">State</label>
                                <input type="text" name="state" class="form-control" required value="{{ old('state', $property->state) }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="country">Country</label>
                                <input type="text" name="country" class="form-control" required value="{{ old('country', $property->country) }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="latitude">Latitude</label>
                                <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $property->latitude) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="longitude">Longitude</label>
                                <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $property->longitude) }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Property Size & Unit Configuration --}}
                <div class="card card-secondary card-outline mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Size & Unit Configuration</h3>
                    </div>
                    <div class="card-body">
                        {{-- Property-level size fields (area_sqft, lot_size_sqft) --}}
                        <div class="form-row">
                            <div class="form-group col-md-6" id="area_sqft_group">
                                <label for="area_sqft">Total Built Area (sqft)</label>
                                <input type="number" step="0.01" name="area_sqft" id="area_sqft" class="form-control" value="{{ old('area_sqft', $property->area_sqft) }}">
                                <small class="form-text text-muted">Total living/commercial space of the entire property.</small>
                            </div>
                            <div class="form-group col-md-6" id="lot_size_sqft_group">
                                <label for="lot_size_sqft">Lot Size (sqft)</label>
                                <input type="number" step="0.01" name="lot_size_sqft" id="lot_size_sqft" class="form-control" value="{{ old('lot_size_sqft', $property->lot_size_sqft) }}">
                                <small class="form-text text-muted">Total land area of the property.</small>
                            </div>
                        </div>

                        {{-- New: Checkbox to explicitly state if it has units --}}
                        <div class="form-group p-3 border rounded bg-light" id="has_units_toggle_section">
                            <div class="form-check">
                                {{-- Set checked state based on existing property->has_units or old input --}}
                                <input class="form-check-input" type="checkbox" name="has_units" value="1" id="has_units_checkbox" {{ old('has_units', $property->has_units) ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_units_checkbox">
                                    Does this property contain multiple independent units/dwellings?
                                </label>
                                <small class="form-text text-muted">Check this if it's a duplex, apartment complex, or a single-family home with a separate rental unit (e.g., granny flat).</small>
                            </div>
                        </div>

                        {{-- Dynamic Fields for Single Unit --}}
                        <div id="single_unit_fields" class="hidden-section p-3 border rounded bg-info-light">
                            <h5 class="mt-0">Single Unit Details</h5>
                            <p class="text-muted">These details apply to the *only* unit within this property.</p>

                            {{-- Residential Unit Specific Fields --}}
                            <div id="residential_unit_specific_fields">
                                <div class="form-row">
                                    <div class="form-group col-md-6" id="bedrooms_group">
                                        {{-- Populate from the first unit's data if it exists --}}
                                        <label for="bedrooms">Bedrooms</label>
                                        <input type="number" name="bedrooms" id="bedrooms" class="form-control" value="{{ old('bedrooms', $property->units->first()->bedrooms ?? '') }}">
                                    </div>
                                    <div class="form-group col-md-6" id="bathrooms_group">
                                        {{-- Populate from the first unit's data if it exists --}}
                                        <label for="bathrooms">Bathrooms</label>
                                        <input type="number" step="0.1" name="bathrooms" id="bathrooms" class="form-control" value="{{ old('bathrooms', $property->units->first()->bathrooms ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Land Unit Specific Fields --}}
                            <div id="single_land_fields" class="hidden-section">
                                <div class="form-row">
                                    <div class="form-group col-md-6" id="area_sqm_single_group">
                                        {{-- Populate from the first unit's data if it exists --}}
                                        <label for="area_sqm_single">Unit Area (sqm)</label>
                                        <input type="number" step="0.01" name="area_sqm_single" id="area_sqm_single" class="form-control" value="{{ old('area_sqm_single', $property->units->first()->area_sqm ?? '') }}">
                                        <small class="form-text text-muted">Size of this specific land unit in square meters.</small>
                                    </div>
                                    <div class="form-group col-md-6" id="zoning_type_single_group">
                                        {{-- Populate from the first unit's data if it exists --}}
                                        <label for="zoning_type_single">Zoning Type</label>
                                        <input type="text" name="zoning_type_single" id="zoning_type_single" class="form-control" value="{{ old('zoning_type_single', $property->units->first()->zoning_type ?? '') }}">
                                    </div>
                                </div>
                                <div class="form-group" id="cadastral_id_single_group">
                                    {{-- Populate from the first unit's data if it exists --}}
                                    <label for="cadastral_id_single">Cadastral ID / Parcel Number (for unit)</label>
                                    <input type="text" name="cadastral_id_single" id="cadastral_id_single" class="form-control" value="{{ old('cadastral_id_single', $property->units->first()->unit_number ?? '') }}">
                                    <small class="form-text text-muted">Unique identifier for this specific land unit/plot.</small>
                                </div>
                            </div>
                        </div>

                        {{-- Dynamic Fields for Multi-Unit --}}
                        <div id="multi_unit_fields_section" class="hidden-section p-3 border rounded bg-warning-light">
                            <h5 class="mt-0">Multi-Unit Property Details</h5>
                            <p class="text-muted">This property contains multiple units. You will define individual unit details in a separate step.</p>
                            <div class="form-group">
                                <label for="total_units">Total Units (estimated)</label>
                                <input type="number" name="total_units" id="total_units" class="form-control" value="{{ old('total_units', $property->total_units ?? $property->units->count()) }}">
                                <small class="form-text text-muted">Enter the estimated total number of units in this property.</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Pricing & Listing Details --}}
                <div class="card card-secondary card-outline mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Pricing & Listing Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="year_built">Year Built</label>
                                <input type="number" name="year_built" class="form-control" value="{{ old('year_built', $property->year_built) }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="purchase_price">Purchase Price</label>
                                <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price', $property->purchase_price) }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="sale_price">Sale Price</label>
                                <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price', $property->sale_price) }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="rent_price">Rent Price</label>
                                <input type="number" step="0.01" name="rent_price" class="form-control" value="{{ old('rent_price', $property->rent_price) }}">
                                <small class="form-text text-muted">This will be the default rent for the primary unit if single unit property.</small>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="listing_type">Listing Type</label>
                                <select name="listing_type" class="form-control" required>
                                    <option value="">Select Listing Type</option>
                                    <option value="sale" {{ (old('listing_type', $property->listing_type) == 'sale') ? 'selected' : '' }}>Sale</option>
                                    <option value="rent" {{ (old('listing_type', $property->listing_type) == 'rent') ? 'selected' : '' }}>Rent</option>
                                    <option value="both" {{ (old('listing_type', $property->listing_type) == 'both') ? 'selected' : '' }}>Both Sale & Rent</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="date_acquired">Date Acquired</label>
                                <input type="date" name="date_acquired" class="form-control" value="{{ old('date_acquired', $property->date_acquired) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="listed_at">Listed At</label>
                            <input type="datetime-local" name="listed_at" class="form-control" value="{{ old('listed_at', $property->listed_at ? \Carbon\Carbon::parse($property->listed_at)->format('Y-m-d\TH:i') : '') }}">
                        </div>
                    </div>
                </div>

                {{-- Section 5: Description & Amenities --}}
                <div class="card card-secondary card-outline mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Description & Amenities</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control wyswygeditor">{{ old('description', $property->description) }}</textarea>
                            <small id="task_details" class="form-text text-muted">A Detailed information about the property entered</small>
                        </div>

                        <div class="form-group">
                            <label>Amenities</label>
                            <div class="row">
                                @foreach($amenities as $amenity)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="amenities[]" value="{{ $amenity->id }}" id="amenity_{{ $amenity->id }}"
                                                {{ in_array($amenity->id, old('amenities', $property->amenities->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="amenity_{{ $amenity->id }}">
                                                @if($amenity->icon)
                                                    @if(Str::startsWith($amenity->icon, 'f'))
                                                        <i class="{{ $amenity->icon }}" style="margin-right:5px;"></i>
                                                    @else
                                                        <img src="{{ asset($amenity->icon) }}" alt="{{ $amenity->name }}" style="width:20px;height:20px;vertical-align:middle;margin-right:5px;">
                                                    @endif
                                                @endif
                                                {{ $amenity->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update Property</button>
            </form>
        </div>
    </div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const propertyTypeSelect = document.getElementById('property_type_id');
        const hasUnitsCheckbox = document.getElementById('has_units_checkbox');
        const hasUnitsToggleSection = document.getElementById('has_units_toggle_section');

        const areaSqftInput = document.getElementById('area_sqft');
        const lotSizeSqftInput = document.getElementById('lot_size_sqft');
        const areaSqftGroup = document.getElementById('area_sqft_group');
        const lotSizeSqftGroup = document.getElementById('lot_size_sqft_group');

        const singleUnitFieldsSection = document.getElementById('single_unit_fields');
        const residentialUnitSpecificFields = document.getElementById('residential_unit_specific_fields');
        const bedroomsInput = document.getElementById('bedrooms');
        const bathroomsInput = document.getElementById('bathrooms');
        const bedroomsGroup = document.getElementById('bedrooms_group');
        const bathroomsGroup = document.getElementById('bathrooms_group');

        const singleLandFields = document.getElementById('single_land_fields');
        const areaSqmSingleInput = document.getElementById('area_sqm_single');
        const zoningTypeSingleInput = document.getElementById('zoning_type_single');
        const cadastralIdSingleInput = document.getElementById('cadastral_id_single');
        const areaSqmSingleGroup = document.getElementById('area_sqm_single_group');
        const zoningTypeSingleGroup = document.getElementById('zoning_type_single_group');
        const cadastralIdSingleGroup = document.getElementById('cadastral_id_single_group');

        const multiUnitFieldsSection = document.getElementById('multi_unit_fields_section');
        const totalUnitsInput = document.getElementById('total_units');

        // Store initial values from existing property or old input
        // This is crucial for populating the form correctly on load and after validation errors
        // These values are used to restore fields when they are shown again,
        // preventing them from being blanked out if a user toggles visibility.
        const initialValues = {
            property_type_id: "{{ old('property_type_id', $property->property_type_id) }}",
            area_sqft: "{{ old('area_sqft', $property->area_sqft) }}",
            lot_size_sqft: "{{ old('lot_size_sqft', $property->lot_size_sqft) }}",
            bedrooms: "{{ old('bedrooms', $property->units->first()->bedrooms ?? '') }}",
            bathrooms: "{{ old('bathrooms', $property->units->first()->bathrooms ?? '') }}",
            area_sqm_single: "{{ old('area_sqm_single', $property->units->first()->area_sqm ?? '') }}",
            zoning_type_single: "{{ old('zoning_type_single', $property->units->first()->zoning_type ?? '') }}",
            cadastral_id_single: "{{ old('cadastral_id_single', $property->units->first()->unit_number ?? '') }}", // Using unit_number for cadastral ID
            total_units: "{{ old('total_units', $property->total_units ?? $property->units->count()) }}",
            has_units: "{{ old('has_units', $property->has_units) ? '1' : '0' }}", // Ensure it's '1' or '0' string
        };

        function resetInputValues() {
            // This function only resets the *values* of the dynamic inputs,
            // not their visibility, and specifically avoids resetting the checkbox.
            areaSqftInput.value = initialValues.area_sqft;
            lotSizeSqftInput.value = initialValues.lot_size_sqft;
            bedroomsInput.value = initialValues.bedrooms;
            bathroomsInput.value = initialValues.bathrooms;
            areaSqmSingleInput.value = initialValues.area_sqm_single;
            zoningTypeSingleInput.value = initialValues.zoning_type_single;
            cadastralIdSingleInput.value = initialValues.cadastral_id_single;
            totalUnitsInput.value = initialValues.total_units;
        }

        function hideAllDynamicSections() {
            // This function only hides the dynamic sections and their groups.
            singleUnitFieldsSection.classList.add('hidden-section');
            multiUnitFieldsSection.classList.add('hidden-section');
            residentialUnitSpecificFields.classList.add('hidden-section');
            singleLandFields.classList.add('hidden-section');

            areaSqftGroup.classList.add('hidden-section');
            lotSizeSqftGroup.classList.add('hidden-section');
            bedroomsGroup.classList.add('hidden-section');
            bathroomsGroup.classList.add('hidden-section');
            areaSqmSingleGroup.classList.add('hidden-section');
            zoningTypeSingleGroup.classList.add('hidden-section');
            cadastralIdSingleGroup.classList.add('hidden-section');
        }


        function toggleFields(event) {
            const selectedOption = propertyTypeSelect.options[propertyTypeSelect.selectedIndex];
            const slug = selectedOption.dataset.slug;
            const canHaveMultipleUnitsDefault = selectedOption.dataset.canHaveMultipleUnits === 'true';

            const isPropertyTypeChange = event && event.target === propertyTypeSelect;
            // const isHasUnitsCheckboxChange = event && event.target === hasUnitsCheckbox; // Not strictly needed, as checkbox state is directly read

            if (isPropertyTypeChange) {
                // If the property type dropdown changed, reset all dynamic values and hide all sections
                resetInputValues();
                hideAllDynamicSections();

                // On initial page load or after a validation error, respect old() value first for checkbox
                if (propertyTypeSelect.dataset.initialLoad === 'true' && initialValues.has_units !== '') {
                    hasUnitsCheckbox.checked = initialValues.has_units === '1';
                } else {
                    // Otherwise, set based on the property type's default
                    hasUnitsCheckbox.checked = canHaveMultipleUnitsDefault;
                }
                propertyTypeSelect.dataset.initialLoad = 'false'; // Mark initial load as done
            } else {
                // If the change is from the checkbox itself, just hide sections to re-evaluate visibility
                hideAllDynamicSections();
            }

            // --- Always show the has_units_toggle_section if a property type is selected ---
            if (selectedOption.value) {
                hasUnitsToggleSection.classList.remove('hidden-section');
            } else {
                hasUnitsToggleSection.classList.add('hidden-section');
            }

            let isMultiUnit = hasUnitsCheckbox.checked; // Get the current state of the checkbox (after initial setting/user interaction)

            // --- Show/hide main unit sections based on `isMultiUnit` ---
            if (isMultiUnit) {
                multiUnitFieldsSection.classList.remove('hidden-section');
                totalUnitsInput.value = initialValues.total_units; // Restore value
            } else { // This block handles single-unit properties
                singleUnitFieldsSection.classList.remove('hidden-section');

                // --- Further refine single_unit_fields based on Property Type slug ---
                if (slug === 'land-parcel') {
                    singleLandFields.classList.remove('hidden-section');
                    areaSqmSingleGroup.classList.remove('hidden-section');
                    zoningTypeSingleGroup.classList.remove('hidden-section');
                    cadastralIdSingleGroup.classList.remove('hidden-section');
                    
                    areaSqmSingleInput.value = initialValues.area_sqm_single;
                    zoningTypeSingleInput.value = initialValues.zoning_type_single;
                    cadastralIdSingleInput.value = initialValues.cadastral_id_single;

                    // Hide property-level built area for land
                    areaSqftGroup.classList.add('hidden-section');
                    areaSqftInput.value = ''; // Clear value if hidden
                } else if (selectedOption.value) { // Residential single unit (SFH, Condo, Townhouse, etc.) or other built single units
                    residentialUnitSpecificFields.classList.remove('hidden-section');
                    bedroomsInput.value = initialValues.bedrooms;
                    bathroomsInput.value = initialValues.bathrooms;

                    // Explicitly show bedrooms and bathrooms groups
                    bedroomsGroup.classList.remove('hidden-section');
                    bathroomsGroup.classList.remove('hidden-section');

                    // Show property-level built area for residential/commercial
                    areaSqftGroup.classList.remove('hidden-section');
                    areaSqftInput.value = initialValues.area_sqft;
                }
            }

            // Handle property-level lot_size_sqft visibility
            if (selectedOption.value) {
                lotSizeSqftGroup.classList.remove('hidden-section');
                lotSizeSqftInput.value = initialValues.lot_size_sqft;
            } else {
                lotSizeSqftGroup.classList.add('hidden-section');
                lotSizeSqftInput.value = '';
            }


            // If no property type is selected, hide all dynamic sections
            if (!selectedOption.value) {
                hideAllDynamicSections(); // This will clear all values and hide all sections
                hasUnitsToggleSection.classList.add('hidden-section');
                areaSqftGroup.classList.add('hidden-section');
                lotSizeSqftGroup.classList.add('hidden-section');
            }
        }

        // Set a flag on the propertyTypeSelect element to indicate initial load
        // This helps differentiate between initial page load and subsequent user changes.
        propertyTypeSelect.dataset.initialLoad = 'true';

        // Event Listeners
        propertyTypeSelect.addEventListener('change', toggleFields);
        hasUnitsCheckbox.addEventListener('change', toggleFields);

        // Initial call to set fields based on default selection (if any) or old input
        toggleFields();
    });
</script>
