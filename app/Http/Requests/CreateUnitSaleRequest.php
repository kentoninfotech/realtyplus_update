<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUnitSaleRequest extends FormRequest
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
            'property_unit_id' => ['required', 'exists:property_units,id'],
            'buyer_type' => ['required', 'in:owner,tenant,client'],
            'buyer_id' => ['nullable', 'integer'],
            'buyer_first_name' => ['nullable', 'string', 'max:255'],
            'buyer_last_name' => ['nullable', 'string', 'max:255'],
            'buyer_name' => ['nullable', 'string', 'max:255'],
            'buyer_email' => ['nullable', 'email', 'max:255'],
            'buyer_phone' => ['nullable', 'string', 'max:20'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Custom messages
     */
    public function messages()
    {
        return [
            'property_unit_id.required' => 'Property unit is required.',
            'property_unit_id.exists' => 'The selected property unit does not exist.',
            'buyer_type.required' => 'Buyer type is required.',
            'buyer_type.in' => 'Invalid buyer type selected.',
            'sale_price.required' => 'Sale price is required.',
            'sale_price.numeric' => 'Sale price must be a valid number.',
        ];
    }
}
