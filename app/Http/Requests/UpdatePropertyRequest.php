<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\PropertyType;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        // Ensure 'has_units' is always present in the request data.
        // If the checkbox was checked, it will be '1'. If unchecked, it won't be in the request,
        // so we merge it as false.
        $this->merge([
            'has_units' => $this->has('has_units'), // Returns true if 'has_units' key exists, false otherwise
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Fetch the selected property type to apply conditional validation rules
        $propertyType = PropertyType::find($this->property_type_id);

        $rules = [
            'property_type_id' => ['required', 'exists:property_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'zoning_type' => ['nullable', 'string', 'max:100'],
            'cadastral_id' => ['nullable', 'string', 'max:255'],
            'owner_id' => ['required', 'exists:users,id'],
            'agent_id' => ['nullable', 'exists:users,id'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string', 'max:255'],
            // 'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            // 'zip_code' => ['nullable', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'area_sqft' => ['nullable', 'numeric', 'min:0'],
            'lot_size_sqft' => ['nullable', 'numeric', 'min:0'],
            'year_built' => ['nullable', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'rent_price' => ['nullable', 'numeric', 'min:0'],
            'date_acquired' => ['nullable', 'date'],
            'listing_type' => ['required', 'string', Rule::in(['sale', 'rent', 'both'])],
            'listed_at' => ['nullable', 'date'],
            'has_units' => ['boolean'], // From the checkbox
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['exists:amenities,id'],
        ];

        // Conditional validation based on has_units checkbox and property type slug
        if ($this->boolean('has_units')) {
            // If it's a multi-unit property
            $rules['total_units'] = ['nullable', 'integer'];
            // Unit-specific fields from the 'single_unit_fields' section should NOT be present/required
            $rules['bedrooms'] = ['nullable'];
            $rules['bathrooms'] = ['nullable'];
            $rules['area_sqm_single'] = ['nullable'];
            $rules['zoning_type_single'] = ['nullable'];
            $rules['cadastral_id_single'] = ['nullable'];
        } else {
            // If it's a single-unit property
            $rules['total_units'] = ['nullable']; // Not directly submitted for single units

            if ($propertyType && $propertyType->slug === 'land-parcel') {
                // For a single land parcel
                $rules['area_sqm_single'] = ['required', 'numeric', 'min:0'];
                $rules['zoning_type_single'] = ['nullable', 'string', 'max:255'];
                $rules['cadastral_id_single'] = ['nullable', 'string', 'max:255'];
                // Bedrooms, bathrooms, square_footage remain null for land units
                $rules['bedrooms'] = ['nullable'];
                $rules['bathrooms'] = ['nullable'];
            } else {
                // For a single residential/commercial unit (SFH, Condo, Office Space, etc.)
                $rules['bedrooms'] = ['required', 'integer', 'min:0'];
                $rules['bathrooms'] = ['required', 'numeric', 'min:0'];
                // area_sqm_single, zoning_type_single, cadastral_id_single remain null for built units
                $rules['area_sqm_single'] = ['nullable'];
                $rules['zoning_type_single'] = ['nullable'];
                $rules['cadastral_id_single'] = ['nullable'];
            }
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            // Custom messages here if needed
        ];
    }
}
