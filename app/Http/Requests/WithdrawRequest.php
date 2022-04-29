<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
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
            'amount' => [
                'required',
                'numeric',
                'min:100'
            ],
            'type' => [
                'required',
                'string'
            ]
        ];
    }

    /**
     * Define validation messages for rules
     * @return array
     */
    public function messages()
    {
        return [
            'amount.required' => 'Please enter amount',
            'amount.numeric'  => 'Please enter a valid amount',
            'amount.min'      => 'Amount should be minimum £100',
            'type.required'   => 'Please select a payout method',
            'type.string'     => 'Select a valid payout method',
        ];
    }
}
