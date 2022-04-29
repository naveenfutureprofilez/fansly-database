<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
                'required',
            ],
            'username' => [
                'required',
                'alpha_dash',
                'unique:users',
                'min:6',
                'max:16'
            ],
            'email' => [
                'required',
                'email',
                'unique:users'
            ],
            'phone' => [
                'required',
                'min:9'
            ],
            'password'=>[
                'required',
                'string',
                'min:6',
                'confirmed'
            ],
            'pre_type' => [
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
            'name.required'     => 'Please enter your full name',
            'phone.required'    => 'Please enter your phone number',
            'phone.min'         => 'Enter valid Phone Number',
            'email.required'    => 'Please fill the email address',
            'email.email'       => 'Enter a valid email address',
            'email.unique'      => 'Account exist with this email id',
            'password.required' => 'Password required.',
            'password.min'      => 'Password should have minimum 6 characters.',
            'password.string'   => 'Password should have atleast one character.',
            'password.confirmed'=> 'Please confirm password correctly!',
            'username.required' => 'Username required.',
            'username.alpha_dash'=> 'Invalid username. User can have alphanumeric string with "-" & "_".',
            'username.min'      => 'Username should have atleast 6 characters.',
            'username.max'      => 'Username can have maximum 16 characters.',
            'username.unique'   => 'Username is not available.',
            'pre_type.required' => 'Invalid Pre-registration value.'
        ];
    }

    /**
     * Prepare the data before validation
     * @return void
     */
    public function prepareForValidation(){
        // $this->merge([
        //     'name' => $this->first_name .' '.$this->last_name
        // ]);
    }
}
