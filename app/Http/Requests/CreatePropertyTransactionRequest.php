<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePropertyTransactionRequest extends FormRequest
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
            'transactionable_type' => ['required'],
            'transactionable_id'   => ['required'],
            'payer_type'           => ['required'],
            'payer_id'             => ['required'],
            'type'                 => ['required', 'in:credit,debit'],
            'purpose'              => ['required'],
            'amount'               => ['required', 'numeric', 'min:0.01'],
            'transaction_date'     => ['required', 'date'],
            'payment_method'       => ['required', 'string', 'max:50'],
            'reference_number'     => ['nullable', 'string', 'max:191'],
            'status'               => ['required', 'string', 'max:50'],
            'description'          => ['nullable', 'string'],
            'documents.*'          => ['file', 'max:20480'],
        ];
    }
}
