<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends FormRequest
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
            'meta' => [
                'required',
                'array'
            ],
            'card' => [
                'required',
                'digits_between:13,20'
            ],
            'month' => [
                'required',
                'integer',
                'min:1',
                'max:12'
            ],
            'year' => [
                'required',
                'digits:4',
            ],
            'cvc' => [
                'required',
                'digits_between:3,4'
            ]
        ];
    }

    /**
     * Get the validation Messages that apply to the rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'meta.required' => 'Please fill all the personal details',
            'meta.array'    => 'Please use valid data in personal info fields',
            'card.required' => 'Please enter Card number',
            'card.digits_between' => 'Please check your card number as it is invalid.',
            'cvc.required'  => 'Please enter CVC of the card.',
            'cvc.digits_between' => 'Please enter valid CVC of your card.',
            'month.required'=> 'Select a valid expiry month',
            'month.min'     => 'Select a valid expiry month',
            'month.max'     => 'Select a valid expiry month',
            'year.required' => 'Select a valid expiry year',
            'year.digits'   => 'Selected expiry year is not a valid year',
        ];
    }
}
