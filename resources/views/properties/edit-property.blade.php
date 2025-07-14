@extends('layouts.template')
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
                        <li class="breadcrumb-item"><a href="{{ route('properties') }}">Property</a></li>
                        <li class="breadcrumb-item active">Add Property</li>
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

            <form action="{{ route('update.property', $property->id) }}" method="post">
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

                <div class="form-group">
                    <label for="property_type_id">Property Type</label>
                    <select name="property_type_id" class="form-control" required>
                        <option value="">Select Type</option>
                        @foreach($propertyTypes as $type)
                            <option value="{{ $type->id }}" {{ $property->property_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">Property Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $property->name) }}" required>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="owner_id">Owner</label>
                        <select name="owner_id" id="owner_id" class="form-control select2" required>
                            <option value="">Select Owner</option>
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}" {{ $property->owner_id == $owner->id ? 'selected' : '' }}>{{ $owner->first_name }} {{ $owner->last_name }}{{ $owner->company_name ? ' ('.$owner->company_name.')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="agent_id">Agent</label>
                        <select name="agent_id" id="agent_id" class="form-control select2">
                            <option value="">Select Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ $property->agent_id == $agent->id ? 'selected' : '' }}>{{ $agent->first_name }} {{ $agent->last_name }}{{ $agent->license_number ? ' ('.$agent->license_number.')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control wyswygeditor">{{ old('description', $property->description) }}</textarea>
                    <small id="task_details" class="form-text text-muted">A Detailed infomation about the property entered</small>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="address">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $property->address) }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="state">State</label>
                        <input type="text" name="state" class="form-control" value="{{ old('state', $property->state) }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="country">Country</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country', $property->country) }}" required>
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

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="area_sqft">Area (sqft)</label>
                        <input type="number" name="area_sqft" class="form-control" value="{{ old('area_sqft', $property->area_sqft) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="lot_size_sqft">Lot Size (sqft)</label>
                        <input type="number" name="lot_size_sqft" class="form-control" value="{{ old('lot_size_sqft', $property->lot_size_sqft) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="bedrooms">Bedrooms</label>
                        <input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', $property->bedrooms) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="bathrooms">Bathrooms</label>
                        <input type="number" name="bathrooms" class="form-control" value="{{ old('bathrooms', $property->bathrooms) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="year_built">Year Built</label>
                        <input type="number" name="year_built" class="form-control" value="{{ old('year_built', $property->year_built) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="purchase_price">Purchase Price</label>
                        <input type="number" name="purchase_price" class="form-control" value="{{ old('purchase_price', $property->purchase_price) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="sale_price">Sale Price</label>
                        <input type="number" name="sale_price" class="form-control" value="{{ old('sale_price', $property->sale_price) }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="rent_price">Rent Price</label>
                        <input type="number" name="rent_price" class="form-control" value="{{ old('rent_price', $property->rent_price) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="listing_type">Listing Type</label>
                        <select name="listing_type" class="form-control" required>
                            <option value="">Select Listing Type</option>
                            <option value="sale" {{ $property->listing_type == 'sale' ? 'selected' : '' }}>Sale</option>
                            <option value="rent" {{ $property->listing_type == 'rent' ? 'selected' : '' }}>Rent</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="date_acquired">Date Acquired</label>
                        <input type="date" name="date_acquired" class="form-control" value="{{ old('date_acquired', $property->date_acquired ? $property->date_acquired->format('Y-m-d') : '') }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="listed_at">Listed At</label>
                        <input type="datetime-local" name="listed_at" class="form-control" value="{{ old('listed_at', $property->listed_at ? $property->listed_at->format('Y-m-d\TH:i') : '') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Amenities</label>
                    <div class="row">
                        @php
                            $propertyAmenityIds = $property->amenities->pluck('id')->toArray();
                        @endphp
                        @foreach($amenities as $amenity)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="amenities[]" value="{{ $amenity->id }}" id="amenity_{{ $amenity->id }}" {{ in_array($amenity->id, $propertyAmenityIds) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="amenity_{{ $amenity->id }}">
                                        @if($amenity->icon)
                                            @if(Str::startsWith($amenity->icon, 'fa'))
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

                <button type="submit" class="btn btn-primary">Update Property</button>
            </form>
        </div>
    </div>

@endsection
