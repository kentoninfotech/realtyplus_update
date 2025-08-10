<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceRequest extends FormRequest
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
            'property_unit_id' => 'nullable|exists:property_units,id',
            'reported_by_user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'status' => 'required|in:open,in_progress,on_hold,completed,cancelled',
            'assigned_to_personnel_id' => 'nullable|exists:personnels,id',
            'reported_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
        ];
    }
}
