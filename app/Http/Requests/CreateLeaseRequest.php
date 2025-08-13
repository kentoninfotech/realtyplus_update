<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\PropertyUnit; // Import Unit model

class CreateLeaseRequest extends FormRequest
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $mergedData = [];

        // Check if property_unit_id is provided and not empty
        if ($this->has('property_unit_id') && !empty($this->property_unit_id)) {
            $unit = PropertyUnit::find($this->property_unit_id);
            if ($unit) {
                // If a valid unit is selected, set property_id from the unit
                $mergedData['property_id'] = $unit->property_id;
                $mergedData['property_unit_id'] = $this->property_unit_id;
            } else {
                // If property_unit_id is provided but invalid, set property_id to null
                // The 'exists' rule will catch the invalid property_unit_id later.
                $mergedData['property_id'] = null;
                $mergedData['property_unit_id'] = $this->property_unit_id;
            }
        } elseif ($this->has('property_id') && !empty($this->property_id)) {
            // If property_unit_id is NOT provided, but property_id IS provided,
            // then ensure property_unit_id is explicitly null.
            $mergedData['property_id'] = $this->property_id;
            $mergedData['property_unit_id'] = null;
        } else {
            // If neither is provided or both are empty, set both to null.
            // The 'property_id.requiredIf' rule will then apply if needed.
            $mergedData['property_id'] = null;
            $mergedData['property_unit_id'] = null;
        }

        $this->merge($mergedData);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // Either property_id OR property_unit_id is required.
            // The prepareForValidation method ensures mutual exclusivity.
            'property_id' => [
                Rule::requiredIf(empty($this->property_unit_id)), // Required if no unit is selected
                'exists:properties,id',
            ],
            'property_unit_id' => ['nullable', 'exists:property_units,id'], // Nullable if lease is for entire property
            'tenant_id' => ['required', 'exists:tenants,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'rent_amount' => ['required', 'numeric', 'min:0'],
            'deposit_amount' => ['nullable', 'numeric', 'min:0'],
            'payment_frequency' => ['required', 'string', Rule::in(['monthly', 'quarterly', 'annually', 'bi-annually'])],
            'renewal_date' => ['nullable', 'date', 'after_or_equal:end_date'],
            'status' => ['required', 'string', Rule::in(['active', 'pending', 'terminated', 'renewed', 'expired'])],
            'terms' => ['nullable', 'string'],
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
            'property_id.required' => 'Either a property or a specific unit must be selected.',
            'property_unit_id.exists' => 'The selected unit is invalid.',
            'tenant_id.required' => 'A tenant must be selected for the lease.',
            'tenant_id.exists' => 'The selected tenant is invalid.',
            'end_date.after' => 'The end date must be after the start date.',
            'renewal_date.after_or_equal' => 'The renewal date must be on or after the end date.',
        ];
    }
}
