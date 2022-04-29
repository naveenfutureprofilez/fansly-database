<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => [
                'required',
                'min:6'
            ],
            'password' => [
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
            'email.required'    => 'Username/Email required.',
            'email.min'         => 'Invalid Username/Email.',
            'password.required' => 'Password required.',
            'password.min'      => 'Invalid password.'  
        ];
    }
}
