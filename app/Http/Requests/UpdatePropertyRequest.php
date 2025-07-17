<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'property_type_id' => 'required|exists:property_types,id',
            'agent_id' => 'nullable|exists:agents,id',
            'owner_id' => 'required|exists:owners,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'nullable|string|in:available,vacant,sold,leased,under_maintenance,unavailable',
            'has_units' => 'boolean',
            'total_units' => 'nullable|integer|min:0',
            'area_sqft' => 'nullable|numeric',
            'lot_size_sqft' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'year_built' => 'nullable|integer|min:1800|max:' . date('Y'),
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'rent_price' => 'nullable|numeric|min:0',
            'date_acquired' => 'nullable|date',
            'listing_type' => 'nullable|string|in:sale,rent,both',
            'listed_at' => 'nullable|date',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
        ];
    }
}
