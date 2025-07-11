<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTenantRequest extends FormRequest
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
            'first_name'               => 'required|string|max:100',
            'last_name'                => 'required|string|max:100',
            'email'                    => 'required|email',
            'phone_number'             => 'nullable|string|max:150',
            'address'                  => 'nullable|string|max:200',
            'emergency_contact_name'   => 'nullable|string|max:150',
            'emergency_contact_phone'  => 'nullable|string|max:150',
            'password'                 => 'nullable|string|min:8',
        ];
    }
}
