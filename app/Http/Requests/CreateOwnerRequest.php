<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOwnerRequest extends FormRequest
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
            'first_name'    => 'required|string|max:70',
            'last_name'     => 'required|string|max:70',
            'company_name'  => 'nullable|string|max:50',
            'email'         => 'required|email|unique:users,email',
            'phone_number'  => 'nullable|string|max:100',
            'address'       => 'nullable|string|max:100',
            'password'      => 'nullable|string|min:8',
        ];
    }
}
