<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProSubscribeRequest extends FormRequest
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
            'auto_renew' => [
                'required',
                'boolean'
            ],
            'via' => [
                'sometimes',
                'required'
            ],
            'card' => [
                'sometimes',
                'required'
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
            'auto_renew.required'   => 'Please select option for auto renew',
            'via.required'          => 'Please select valid option for payment',
            'card.required'         => 'Please select a card for payment',
            'auto_renew.boolean'    => 'Invalid Auto Renew value',
        ];
    }
}
