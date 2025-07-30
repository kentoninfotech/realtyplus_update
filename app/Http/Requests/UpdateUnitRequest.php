<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
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
        // The 'unit' parameter from the route will be available as $this->unit
        $unitId = $this->route('id');

        return [
            'property_id' => ['required', 'exists:properties,id'],
            'unit_number' => ['required', 'string', 'max:255', Rule::unique('property_units')->where(function ($query) {
                return $query->where('property_id', $this->property_id);
            })->ignore($unitId)], // Ignore the current unit's ID for unique rule
            'unit_type' => ['required', 'string', Rule::in(['residential', 'commercial', 'land', 'other'])],
            'description' => ['nullable', 'string'],
            'square_footage' => ['nullable', 'numeric', 'min:0'],
            'area_sqm' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string', Rule::in(['available', 'vacant', 'sold', 'under_maintenance', 'leased', 'rented'])],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'rent_price' => ['nullable', 'numeric', 'min:0'],
            'deposit_amount' => ['nullable', 'numeric', 'min:0'],
            'available_from' => ['nullable', 'date'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'unit_number.unique' => 'A unit with this number already exists for this property.',
            'property_id.required' => 'A property must be selected for the unit.',
        ];
    }

}
