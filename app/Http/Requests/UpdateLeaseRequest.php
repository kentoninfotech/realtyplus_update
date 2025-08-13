<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\PropertyUnit; 

class UpdateLeaseRequest extends FormRequest
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
        // If property_unit_id is provided, automatically set property_id to its parent property's ID.
        // This ensures consistency and simplifies the controller logic.
        if ($this->property_unit_id) {
            $unit = PropertyUnit::find($this->property_unit_id);
            if ($unit) {
                $this->merge([
                    'property_id' => $unit->property_id,
                ]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // The 'lease' parameter from the route will be available as $this->lease
        return [
            // Either property_id OR property_unit_id is required.
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
