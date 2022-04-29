<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
            'password'=>[
                'required',
                'string',
                'min:6',
                'confirmed'
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
            'password.min'      => 'Password should have minimum 6 characters.',
            'password.string'   => 'Password should have atleast one character.',
            'password.confirmed'=> 'Please confirm password correctly!',
        ];
    }
}
