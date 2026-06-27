@extends('layouts.template')

{{-- Add necessary CSS for map if you're using it, or remove if not --}}
<style>
    body {
        margin: 0;
        padding: 0;
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
                    <h1 class="m-0">Add Property</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('properties') }}">Properties</a></li>
                        <li class="breadcrumb-item active">Add Property</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="card card-primary">
        <div class="card-header d-flex align-items-center">
            <h4 class="card-title mb-0">Property Form</h4>
            <button type="button" class="btn btn-sm btn-outline-info ml-auto" data-toggle="modal" data-target="#propertyFormGuide">
                <i class="fas fa-question-circle"></i> User Guide
            </button>
        </div>
        <div class="card-body">
            <form action="{{ route('create.property') }}" method="post">
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
                                        {{ old('property_type_id') == $type->id ? 'selected' : '' }}
                                    >
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Property Name</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="owner_id">Owner (Optional)</label>
                                <select name="owner_id" id="owner_id" class="form-control select2">
                                    <option value="">-- No Owner --</option>
                                    @foreach($owners as $owner)
                                        @if($owner->user && $owner->user->exists)
                                            <option value="{{ $owner->user_id }}" {{ old('owner_id') == $owner->user_id ? 'selected' : '' }}>
                                                {{ $owner->first_name }} {{ $owner->last_name }}{{ $owner->company_name ? ' ('.$owner->company_name.')' : '' }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Select a valid owner or leave blank</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="agent_id">Agent</label>
                                <select name="agent_id" id="agent_id" class="form-control select2">
                                    <option value="">Select Agent</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
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
                                <input type="text" name="address" class="form-control" required value="{{ old('address') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="state">State</label>
                                <input type="text" name="state" class="form-control" required value="{{ old('state') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="country">Country</label>
                                <input type="text" name="country" class="form-control" required value="{{ old('country') }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="latitude">Latitude</label>
                                <input type="text" name="latitude" class="form-control" value="{{ old('latitude') }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="longitude">Longitude</label>
                                <input type="text" name="longitude" class="form-control" value="{{ old('longitude') }}">
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
                                <input type="number" step="0.01" name="area_sqft" id="area_sqft" class="form-control" value="{{ old('area_sqft') }}">
                                <small class="form-text text-muted">Total living/commercial space of the entire property.</small>
                            </div>
                            <div class="form-group col-md-6" id="lot_size_sqft_group">
                                <label for="lot_size_sqft">Lot Size (sqft)</label>
                                <input type="number" step="0.01" name="lot_size_sqft" id="lot_size_sqft" class="form-control" value="{{ old('lot_size_sqft') }}">
                                <small class="form-text text-muted">Total land area of the property.</small>
                            </div>
                        </div>

                        {{-- New: Checkbox to explicitly state if it has units --}}
                        <div class="form-group p-3 border rounded bg-light" id="has_units_toggle_section">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="has_units" value="1" id="has_units_checkbox" {{ old('has_units') ? 'checked' : '' }}>
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
                                        <label for="bedrooms">Bedrooms</label>
                                        <input type="number" name="bedrooms" id="bedrooms" class="form-control" value="{{ old('bedrooms') }}">
                                    </div>
                                    <div class="form-group col-md-6" id="bathrooms_group">
                                        <label for="bathrooms">Bathrooms</label>
                                        <input type="number" step="0.1" name="bathrooms" id="bathrooms" class="form-control" value="{{ old('bathrooms') }}">
                                    </div>
                                </div>
                                {{-- Note: square_footage for the unit is derived from property.area_sqft if single unit --}}
                            </div>

                            {{-- Land Unit Specific Fields --}}
                            <div id="single_land_fields" class="hidden-section">
                                <div class="form-row">
                                    <div class="form-group col-md-6" id="area_sqm_single_group">
                                        <label for="area_sqm_single">Unit Area (sqm)</label>
                                        <input type="number" step="0.01" name="area_sqm_single" id="area_sqm_single" class="form-control" value="{{ old('area_sqm_single') }}">
                                        <small class="form-text text-muted">Size of this specific land unit in square meters.</small>
                                    </div>
                                    <div class="form-group col-md-6" id="zoning_type_single_group">
                                        <label for="zoning_type_single">Zoning Type</label>
                                        <input type="text" name="zoning_type_single" id="zoning_type_single" class="form-control" value="{{ old('zoning_type_single') }}">
                                    </div>
                                </div>
                                <div class="form-group" id="cadastral_id_single_group">
                                    <label for="cadastral_id_single">Cadastral ID / Parcel Number (for unit)</label>
                                    <input type="text" name="cadastral_id_single" id="cadastral_id_single" class="form-control" value="{{ old('cadastral_id_single') }}">
                                    <small class="form-text text-muted">Unique identifier for this specific land unit/plot.</small>
                                </div>
                            </div>
                        </div>

                        {{-- Dynamic Fields for Multi-Unit --}}
                        <div id="multi_unit_fields_section" class="hidden-section p-3 border rounded bg-warning-light">
                            <h5 class="mt-0">Multi-Unit Property Details</h5>
                            <p class="text-muted">This property contains multiple units. You will define individual unit details in property dashboard.</p>
                            <div class="form-group">
                                <label for="total_units">Total Units (estimated)</label>
                                <input type="number" name="total_units" id="total_units" class="form-control" value="{{ old('total_units') }}">
                                <small class="form-text text-muted">Enter the estimated total number of units in this property.</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 4: Pricing & Listing Details --}}
                <div class="card card-secondary card-outline mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Pricing & Listing Details</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="year_built">Year Built</label>
                                <input type="number" name="year_built" class="form-control" value="{{ old('year_built') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="purchase_price">Purchase Price</label>
                                <input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ old('purchase_price') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="sale_price">Sale Price</label>
                                <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price') }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="rent_price">Rent Price</label>
                                <input type="number" step="0.01" name="rent_price" class="form-control" value="{{ old('rent_price') }}">
                                <small class="form-text text-muted">This will be the default rent for the primary unit if single unit property.</small>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="listing_type">Listing Type</label>
                                <select name="listing_type" class="form-control" required>
                                    <option value="" disabled hidden>Select Listing Type</option>
                                    <option value="sale" {{ old('listing_type') == 'sale' ? 'selected' : '' }}>Sale</option>
                                    <option value="rent" {{ old('listing_type') == 'rent' ? 'selected' : '' }}>Rent</option>
                                    <option value="both" {{ old('listing_type') == 'both' ? 'selected' : '' }}>Both Sale & Rent</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="date_acquired">Date Acquired</label>
                                <input type="date" name="date_acquired" class="form-control" value="{{ old('date_acquired') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="listed_at">Listed At</label>
                            <input type="datetime-local" name="listed_at" class="form-control" value="{{ old('listed_at') }}">
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
                            <textarea name="description" class="form-control wyswygeditor">{{ old('description', 'Place <em>some</em> <u>text</u> <strong>here</strong>') }}</textarea>
                            <small id="task_details" class="form-text text-muted">A Detailed information about the property entered</small>
                        </div>

                        <div class="form-group">
                            <label>Amenities</label>
                            <div class="row">
                                @foreach($amenities as $amenity)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="amenities[]" value="{{ $amenity->id }}" id="amenity_{{ $amenity->id }}" {{ in_array($amenity->id, old('amenities', [])) ? 'checked' : '' }}>
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

                <button type="submit" class="btn btn-primary mt-3">Add Property</button>
            </form>
        </div>
    </div>

    {{-- User Guide Modal --}}
    <div class="modal fade" id="propertyFormGuide" tabindex="-1" role="dialog" aria-labelledby="propertyFormGuideLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="propertyFormGuideLabel"><i class="fas fa-book-open"></i> How to Fill the Property Form</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">This guide explains every field on the form. Required fields are marked <span class="badge badge-danger">required</span>.</p>

                    <h6 class="text-primary mt-3"><i class="fas fa-info-circle"></i> 1. Basic Property Information</h6>
                    <dl class="row">
                        <dt class="col-sm-4">Property Type <span class="badge badge-danger">required</span></dt>
                        <dd class="col-sm-8">Choose the category (e.g. Apartment, House, Land, Commercial). The form fields adapt to your selection — land properties show plot/zoning fields, residential ones show bedrooms/bathrooms.</dd>

                        <dt class="col-sm-4">Property Name <span class="badge badge-danger">required</span></dt>
                        <dd class="col-sm-8">A short, friendly label you'll recognise (e.g. “Villa 7, Lekki Phase 1”, “Block A — Garki”).</dd>

                        <dt class="col-sm-4">Owner <span class="badge badge-danger">required</span></dt>
                        <dd class="col-sm-8">The legal owner of the property. Your own company is auto-listed here from registration. Add new owners under <em>Clients → Owners</em>.</dd>

                        <dt class="col-sm-4">Agent</dt>
                        <dd class="col-sm-8">Optional. The agent managing this listing. Leave blank if not applicable.</dd>
                    </dl>

                    <h6 class="text-primary mt-3"><i class="fas fa-map-marker-alt"></i> 2. Location Details</h6>
                    <dl class="row">
                        <dt class="col-sm-4">Address <span class="badge badge-danger">required</span></dt>
                        <dd class="col-sm-8">Full street address (e.g. “12 Adeola Odeku Street”).</dd>
                        <dt class="col-sm-4">State <span class="badge badge-danger">required</span></dt>
                        <dd class="col-sm-8">Province or state (e.g. “Lagos”).</dd>
                        <dt class="col-sm-4">Country <span class="badge badge-danger">required</span></dt>
                        <dd class="col-sm-8">Country (e.g. “Nigeria”).</dd>
                        <dt class="col-sm-4">Latitude / Longitude</dt>
                        <dd class="col-sm-8">Optional GPS coordinates (decimal format, e.g. 6.4281, 3.4216). Useful for map views and search radius features.</dd>
                    </dl>

                    <h6 class="text-primary mt-3"><i class="fas fa-ruler-combined"></i> 3. Size & Unit Configuration</h6>
                    <dl class="row">
                        <dt class="col-sm-4">Total Built Area (sqft)</dt>
                        <dd class="col-sm-8">Sum of indoor floor area for the entire property.</dd>
                        <dt class="col-sm-4">Lot Size (sqft)</dt>
                        <dd class="col-sm-8">Total land area, including outdoor space.</dd>
                        <dt class="col-sm-4">Multiple units toggle</dt>
                        <dd class="col-sm-8">Tick this for duplexes, blocks of flats, estates with several apartments, etc. Leave unchecked for a single house, single shop, or one plot of land.</dd>
                        <dt class="col-sm-4">Bedrooms / Bathrooms</dt>
                        <dd class="col-sm-8">Shown for residential single-unit properties. Bathrooms accept halves (e.g. 2.5).</dd>
                        <dt class="col-sm-4">Unit Area (sqm) / Zoning / Cadastral ID</dt>
                        <dd class="col-sm-8">Shown for land-type single units. Cadastral ID is the unique parcel/plot number from the land registry.</dd>
                        <dt class="col-sm-4">Total Units (estimated)</dt>
                        <dd class="col-sm-8">For multi-unit properties: rough number of units. You'll add each unit individually from the property dashboard after saving.</dd>
                    </dl>

                    <h6 class="text-primary mt-3"><i class="fas fa-tags"></i> 4. Pricing & Listing Details <small class="text-muted">(collapsed by default — click “+” to expand)</small></h6>
                    <dl class="row">
                        <dt class="col-sm-4">Year Built</dt>
                        <dd class="col-sm-8">Construction year (4-digit). Skip for land.</dd>
                        <dt class="col-sm-4">Purchase Price</dt>
                        <dd class="col-sm-8">What was paid to acquire the property. For internal records only.</dd>
                        <dt class="col-sm-4">Sale Price</dt>
                        <dd class="col-sm-8">Asking price if listing for sale.</dd>
                        <dt class="col-sm-4">Rent Price</dt>
                        <dd class="col-sm-8">Default rent (per period) if listing for rent. Becomes the rent for the only unit on single-unit properties.</dd>
                        <dt class="col-sm-4">Listing Type <span class="badge badge-danger">required</span></dt>
                        <dd class="col-sm-8">Sale, Rent, or Both. Controls how the property appears on listings.</dd>
                        <dt class="col-sm-4">Date Acquired</dt>
                        <dd class="col-sm-8">Date the owner took possession.</dd>
                        <dt class="col-sm-4">Listed At</dt>
                        <dd class="col-sm-8">When this listing should go live. Leave blank to publish immediately.</dd>
                    </dl>

                    <h6 class="text-primary mt-3"><i class="fas fa-list-ul"></i> 5. Description & Amenities</h6>
                    <dl class="row">
                        <dt class="col-sm-4">Description</dt>
                        <dd class="col-sm-8">Rich text describing condition, finishing, neighbourhood, etc. Use the toolbar for formatting.</dd>
                        <dt class="col-sm-4">Amenities</dt>
                        <dd class="col-sm-8">Tick every feature the property offers (parking, pool, security, gym, etc.). These appear as icons on the public listing.</dd>
                    </dl>

                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fas fa-lightbulb"></i>
                        <strong>Tip:</strong> You can edit anything later. Save the property first, then upload images and configure individual units from the property dashboard.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Got it</button>
                </div>
            </div>
        </div>
    </div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const propertyTypeSelect = document.getElementById('property_type_id');
        const hasUnitsCheckbox = document.getElementById('has_units_checkbox');
        const hasUnitsToggleSection = document.getElementById('has_units_toggle_section');

        // Get parent form-group elements for easier hiding/showing
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

        // Store initial values to restore them when fields are hidden
        // This is important for old() values after validation fails
        const initialValues = {
            area_sqft: areaSqftInput.value,
            lot_size_sqft: lotSizeSqftInput.value,
            bedrooms: bedroomsInput.value,
            bathrooms: bathroomsInput.value,
            area_sqm_single: areaSqmSingleInput.value,
            zoning_type_single: zoningTypeSingleInput.value,
            cadastral_id_single: cadastralIdSingleInput.value,
            total_units: totalUnitsInput.value,
            has_units: hasUnitsCheckbox.checked ? '1' : '', // Store current checked state
        };

        function resetAndHideAllDynamicFields() {
            // Reset values
            areaSqftInput.value = '';
            lotSizeSqftInput.value = '';
            bedroomsInput.value = '';
            bathroomsInput.value = '';
            areaSqmSingleInput.value = '';
            zoningTypeSingleInput.value = '';
            cadastralIdSingleInput.value = '';
            totalUnitsInput.value = '';

            // Hide sections
            singleUnitFieldsSection.classList.add('hidden-section');
            multiUnitFieldsSection.classList.add('hidden-section');
            residentialUnitSpecificFields.classList.add('hidden-section'); // Hide the inner div
            singleLandFields.classList.add('hidden-section'); // Hide the inner div

            // Hide individual form groups that are dynamically shown/hidden
            areaSqftGroup.classList.add('hidden-section');
            lotSizeSqftGroup.classList.add('hidden-section');
            bedroomsGroup.classList.add('hidden-section');
            bathroomsGroup.classList.add('hidden-section');
            areaSqmSingleGroup.classList.add('hidden-section');
            zoningTypeSingleGroup.classList.add('hidden-section');
            cadastralIdSingleGroup.classList.add('hidden-section');
        }

        function toggleFields(event) { // Pass event object
            resetAndHideAllDynamicFields(); // Start by hiding everything

            const selectedOption = propertyTypeSelect.options[propertyTypeSelect.selectedIndex];
            const slug = selectedOption.dataset.slug;
            const canHaveMultipleUnitsDefault = selectedOption.dataset.canHaveMultipleUnits === 'true';

            // --- Always show the has_units_toggle_section if a property type is selected ---
            if (selectedOption.value) {
                hasUnitsToggleSection.classList.remove('hidden-section');
            } else {
                hasUnitsToggleSection.classList.add('hidden-section');
            }

            // --- Set initial state of has_units_checkbox based on property type's default or old input ---
            // This logic ensures the checkbox is set based on the property type's default,
            // but can be overridden by old() value after a validation error, and is always toggleable.

            const isPropertyTypeChange = event && event.target === propertyTypeSelect;

            if (isPropertyTypeChange) {
                // If the change originated from the property type dropdown
                if (propertyTypeSelect.dataset.initialLoad === 'true' && initialValues.has_units !== '') {
                    // On initial page load or after a validation error, respect old() value first
                    hasUnitsCheckbox.checked = initialValues.has_units === '1';
                } else {
                    // Otherwise, set based on the property type's default
                    hasUnitsCheckbox.checked = canHaveMultipleUnitsDefault;
                }
                propertyTypeSelect.dataset.initialLoad = 'false'; // Mark initial load as done
            }
            // If the change originated from the checkbox itself, its `checked` state is already correct.
            // We don't need to re-set it here.

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
                resetAndHideAllDynamicFields();
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

    // Initialize SummerNote WYSIWYG Editor
    $(document).ready(function() {
        $('.wyswygeditor').summernote({
            height: 300,
            minHeight: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            codemirror: {
                mode: 'htmlmixed',
                lineNumbers: true,
                lineWrapping: true,
            }
        });
    });
</script>

