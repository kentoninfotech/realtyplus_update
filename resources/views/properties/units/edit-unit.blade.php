@extends('layouts.template')

<style>
    /* Basic styling for hidden sections to prevent layout shifts */
    .hidden-section {
        display: none;
    }
    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 20px;
        justify-content: flex-start; /* Align to start */
    }
    .image-preview {
        position: relative;
        width: 150px;
        height: 150px;
        border: 1px solid #ddd;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f9f9f9;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-radius: 8px;
    }
    .image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .image-preview .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.8);
        color: white;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9em;
        cursor: pointer;
        line-height: 1;
        border: 1px solid rgba(255,255,255,0.5);
    }
    .image-preview .image-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        font-size: 0.75em;
        padding: 3px 5px;
        text-align: center;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .image-preview .featured-badge {
        position: absolute;
        top: 5px;
        left: 5px;
        background: rgba(40, 167, 69, 0.8);
        color: white;
        font-size: 0.7em;
        padding: 2px 5px;
        border-radius: 5px;
        z-index: 10;
    }
</style>

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Unit: {{ $unit->unit_number }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('units') }}">Units</a></li>
                        <li class="breadcrumb-item active">Edit Unit</li>
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
            <form action="{{ route('update.unit', $unit->id) }}" method="post">
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

                {{-- Section 1: Basic Unit Information --}}
                <div class="card card-secondary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Basic Unit Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="property_id">Associated Property</label>
                                {{-- If property ID is passed from route, use hidden input and display name --}}
                                <input type="hidden" name="property_id" id="property_id" value="{{ $unit->property->id }}">
                                <p class="form-control-static">
                                    <strong>{{ $unit->property->name }} ({{ $unit->property->address }})</strong>
                                    <small class="text-muted">(Property pre-selected)</small>
                                </p>
                        </div>

                        <div class="form-group">
                            <label for="unit_number">Unit Number/Identifier</label>
                            <input type="text" name="unit_number" id="unit_number" class="form-control" required value="{{ old('unit_number', $unit->unit_number) }}">
                            <small class="form-text text-muted">e.g., Apt 101, Unit B, Lot 5, Office 300.</small>
                        </div>

                        <div class="form-group">
                            <label for="unit_type">Unit Type</label>
                            <select name="unit_type" id="unit_type" class="form-control" required>
                                <option value="">Select Unit Type</option>
                                @foreach($unitTypes as $type)
                                    <option value="{{ $type }}" {{ (old('unit_type', $unit->unit_type) == $type) ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control wyswygeditor">{{ old('description', $unit->description) }}</textarea>
                            <small class="form-text text-muted">Detailed information about this specific unit.</small>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="available" {{ (old('status', $unit->status) == 'available') ? 'selected' : '' }}>Available</option>
                                <option value="rented" {{ (old('status', $unit->status) == 'rented') ? 'selected' : '' }}>Rented</option>
                                <option value="leased" {{ (old('status', $unit->status) == 'leased') ? 'selected' : '' }}>Leased</option>
                                <option value="under_maintenance" {{ (old('status', $unit->status) == 'under_maintenance') ? 'selected' : '' }}>Under Maintenance</option>
                                <option value="sold" {{ (old('status', $unit->status) == 'sold') ? 'selected' : '' }}>Sold</option>
                                <option value="vacant" {{ (old('status', $unit->status) == 'vacant') ? 'selected' : '' }}>Vacant</option>
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
                                    <input type="number" step="0.01" name="square_footage" id="square_footage" class="form-control" value="{{ old('square_footage', $unit->square_footage) }}">
                                    <small class="form-text text-muted">Built area of the unit in square feet.</small>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="bedrooms">Bedrooms</label>
                                    <input type="number" name="bedrooms" id="bedrooms" class="form-control" value="{{ old('bedrooms', $unit->bedrooms) }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bathrooms">Bathrooms</label>
                                    <input type="number" step="0.1" name="bathrooms" id="bathrooms" class="form-control" value="{{ old('bathrooms', $unit->bathrooms) }}">
                                </div>
                            </div>
                        </div>

                        {{-- Land Specific Fields --}}
                        <div id="land_fields" class="hidden-section">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="area_sqm">Area (sqm)</label>
                                    <input type="number" step="0.01" name="area_sqm" id="area_sqm" class="form-control" value="{{ old('area_sqm', $unit->area_sqm) }}">
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
                                <input type="number" step="0.01" name="sale_price" id="sale_price" class="form-control" value="{{ old('sale_price', $unit->sale_price) }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="rent_price">Rent Price</label>
                                <input type="number" step="0.01" name="rent_price" id="rent_price" class="form-control" value="{{ old('rent_price', $unit->rent_price) }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="deposit_amount">Deposit Amount</label>
                                <input type="number" step="0.01" name="deposit_amount" id="deposit_amount" class="form-control" value="{{ old('deposit_amount', $unit->deposit_amount) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="available_from">Available From</label>
                            <input type="date" name="available_from" id="available_from" class="form-control" value="{{ old('available_from', $unit->available_from ? $unit->available_from->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>

                {{-- Section 4: Featured Unit Settings --}}
                <div class="card card-secondary card-outline mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Featured Unit Settings</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group form-check">
                            <input type="checkbox" name="featured" id="featured" value="1" class="form-check-input" {{ old('featured', $unit->featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="featured">
                                Feature this unit on guest home page
                            </label>
                            <small class="form-text text-muted d-block mt-2">
                                Check this box to display this unit in the "Featured Units" section on the landing page. Featured units must have either a sale price or rent price to be displayed.
                            </small>
                        </div>

                        <div class="form-group" id="featured_order_group" style="display: {{ old('featured', $unit->featured) ? 'block' : 'none' }};">
                            <label for="featured_order">Featured Order (Display Priority)</label>
                            <input type="number" name="featured_order" id="featured_order" class="form-control" min="1" max="100" value="{{ old('featured_order', $unit->featured_order ?? 1) }}">
                            <small class="form-text text-muted">Lower numbers appear first. Leave blank if not featured.</small>
                        </div>
                    </div>
                </div>

                {{-- Section 5: Unit Images --}}
                <div class="card card-secondary card-outline mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Unit Images</h3>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addImageModal">
                            Add Images
                        </button>

                        <div class="image-preview-container" id="existing_image_previews">
                            @forelse($unit->images as $image)
                                <div class="image-preview" data-image-id="{{ $image->id }}">
                                    <img src="{{ asset(''. $image->image_path) }}" alt="{{ $image->caption ?? 'Unit Image' }}">
                                    @if($image->is_featured)
                                        <span class="featured-badge">Featured</span>
                                    @endif
                                    @if($image->caption)
                                        <div class="image-caption">{{ $image->caption }}</div>
                                    @endif
                                    
                                    <!-- DELETE IMAGE BUTTON (AJAX) -->
                                    <button type="button" class="remove-image delete-image-btn" data-image-id="{{ $image->id }}" data-delete-url="{{ route('unit.deleteImage', $image->id) }}" title="Delete Image">&times;</button>
                                    
                                </div>
                            @empty
                                <p class="text-muted">No images uploaded for this unit yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update Unit</button>
            </form>
        </div>
    </div>

    {{-- Add Image Modal (for unit image upload) --}}
    <div class="modal fade" id="addImageModal" tabindex="-1" role="dialog" aria-labelledby="addImageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addImageModalLabel">Upload Unit Images</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="unitImageUploadForm" action="{{ route('unit.uploadImage', $unit->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="images">Select Images</label>
                            <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                            <small class="form-text text-muted">You can select multiple image files (JPG, PNG, GIF, SVG, WEBP up to 5MB each).</small>
                        </div>
                        <div class="form-group">
                            <label for="caption">Caption (optional, applies to all uploaded images)</label>
                            <input type="text" name="caption" id="caption" class="form-control">
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" class="form-check-input">
                            <label class="form-check-label" for="is_featured">Set as Featured Image (first image uploaded)</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload Images</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const unitTypeSelect = document.getElementById('unit_type');
        const residentialCommercialFields = document.getElementById('residential_commercial_fields');
        const landFields = document.getElementById('land_fields');

        // Store initial values from existing unit or old input for edit form
        const initialValues = {
            square_footage: "{{ old('square_footage', $unit->square_footage) }}",
            bedrooms: "{{ old('bedrooms', $unit->bedrooms) }}",
            bathrooms: "{{ old('bathrooms', $unit->bathrooms) }}",
            area_sqm: "{{ old('area_sqm', $unit->area_sqm) }}",
        };

        function toggleUnitTypeFields() {
            const selectedType = unitTypeSelect.value;

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

        // Initialize Select2 for property_id dropdown
        $('.select2').select2({
            placeholder: "Select a Property",
            allowClear: true
        });

        // Handle image deletion via AJAX
        document.querySelectorAll('.delete-image-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const imageId = this.getAttribute('data-image-id');
                const deleteUrl = this.getAttribute('data-delete-url');
                
                if (confirm('Are you sure you want to delete this image?')) {
                    fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            // Remove the image preview from the DOM
                            const imagePreview = document.querySelector(`[data-image-id="${imageId}"]`);
                            if (imagePreview) {
                                imagePreview.remove();
                            }
                            // Show success message (optional)
                            Swal.fire('Success', 'Image deleted successfully!', 'success');
                        } else {
                            Swal.fire('Error', 'Error deleting image. Please try again.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Error deleting image. Please check the console.', 'error');
                    });
                }
            });
        });

        // Handle featured checkbox toggle
        const featuredCheckbox = document.getElementById('featured');
        const featuredOrderGroup = document.getElementById('featured_order_group');

        if (featuredCheckbox) {
            featuredCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    featuredOrderGroup.style.display = 'block';
                } else {
                    featuredOrderGroup.style.display = 'none';
                }
            });
        }
    });
</script>
