<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateViewingRequest extends FormRequest
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
            'property_id' => 'required|exists:properties,id',
            'property_unit_id' => 'nullable|exists:property_units,id',
            'lead_id' => 'nullable|exists:leads,id',
            'agent_id' => 'required|exists:agents,id',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'nullable|string|max:255',
            'scheduled_at' => 'required|date',
            'status' => 'required|string|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string',
        ];
    }
}
