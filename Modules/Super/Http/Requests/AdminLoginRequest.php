<?php

namespace Modules\Super\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
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
            'apassword' => [
                'required',
                'min:6'
            ],
            'ausername' => [
                'required',
                'min:6'
            ]
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'ausername.required'    => 'Username/Email required.',
            'ausername.min'         => 'Invalid Username/Email.',
            'apassword.required' => 'Password required.',
            'apassword.min'      => 'Invalid password.'  
        ];
    }
}
