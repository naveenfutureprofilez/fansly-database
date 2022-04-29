<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaypalMethodRequest extends FormRequest
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
                'email'
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
            'email.required' => 'Please enter paypal email.',
            'email.email'    => 'Invalid email format.',
        ];
    }
}
