<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsernameRequest extends FormRequest
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
            'username' => [
                'required',
                'alpha_dash',
                'unique:users',
                'min:6',
                'max:16'
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
            'username.required' => 'Username required.',
            'username.alpha_dash'=> 'Invalid username. User can have alphanumeric string with "-" & "_".',
            'username.min'      => 'Username should have atleast 6 characters.',
            'username.max'      => 'Username can have maximum 16 characters.',
            'username.unique'   => 'Username is not available.',
        ];
    }
}
