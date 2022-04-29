<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'name' => [
                'required'
            ],
            'bio' => [
                'required',
                'max:655'
            ],
            'phone' => [
                'required',
                'digits_between:9,13'
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
            'name.required'     => 'Please enter your full name',
            'phone.required'    => 'Please enter your phone number',
            'phone.digits_between'=> 'Enter valid Phone Number',
            'bio.required'      => 'Please write something about you.',
            'bio.max'           => 'Bio can have maximum 655 characters',
        ];
    }
}
